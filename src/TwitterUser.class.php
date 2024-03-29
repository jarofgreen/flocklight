<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */


class TwitterUser {
	
	private $id;
	private $user_name;
	private $profile_image_url;
	private $attending;
	private $oauth_token;
	private $oauth_secret;
	

	public static function find($userID) {
		$db = getDB();
		$s = $db->prepare("SELECT twitter_account.* FROM twitter_account WHERE id=:id"); 
		$s->execute(array('id'=>$userID));
		if ($s->rowCount() == 1) {
			return new TwitterUser($s->fetch());
		}
	}
	
	public static function findByUserName($userName) {
		$db = getDB();
		$s = $db->prepare("SELECT twitter_account.* FROM twitter_account WHERE user_name=:un"); 
		$s->execute(array('un'=>$userName));
		if ($s->rowCount() == 1) {
			return new TwitterUser($s->fetch());
		}
	}
	
	public static function findOrCreate($userID, $userName, $profileImageURL) {
		$db = getDB();
		$s = $db->prepare("SELECT twitter_account.* FROM twitter_account WHERE id=:id");
		$s->execute(array('id'=>$userID));
		if ($s->rowCount() == 1) {
			$u = new TwitterUser($s->fetch());
			$u->update($userName, $profileImageURL);
			return $u;
		} else {
			$s = $db->prepare("INSERT INTO twitter_account (id,user_name,profile_image_url) VALUES (:id,:user_name,:profile_image_url)");
			if (strlen($profileImageURL) > 250) $profileImageURL = "https://twimg0-a.akamaihd.net/sticky/default_profile_images/default_profile_4_normal.png";
			$data = array(
					'id'=>$userID,
					'user_name'=>$userName,
					'profile_image_url'=>$profileImageURL,
				);
			$s->execute($data);
			return new TwitterUser($data);
		}
	}
	
	public function  __construct($data) {
		if (isset($data['id'])) $this->id = $data['id'];
		if (isset($data['user_name'])) $this->user_name = $data['user_name'];
		if (isset($data['profile_image_url'])) $this->profile_image_url = $data['profile_image_url'];
		if (isset($data['attending'])) $this->attending = $data['attending'];
		if (isset($data['oauth_secret'])) $this->oauth_secret = $data['oauth_secret'];
		if (isset($data['oauth_token'])) $this->oauth_token = $data['oauth_token'];
	}
	
	
	public function getId() { return $this->id; }
	public function getUserName() { return $this->user_name; }
	public function getProfileImageURL() { return $this->profile_image_url; }
	public function getAttending() { return $this->attending; }
	
	public function update($userName, $profileImageURL) {
		if (strlen($profileImageURL) > 250) $profileImageURL = "https://twimg0-a.akamaihd.net/sticky/default_profile_images/default_profile_4_normal.png";
		$db = getDB();
		$s = $db->prepare("UPDATE twitter_account  SET user_name=:user_name,profile_image_url=:profile_image_url WHERE id=:id");
		$data = array(
				'id'=>$this->id,
				'user_name'=>$userName,
				'profile_image_url'=>$profileImageURL,
			);
		$s->execute($data);
	}	


	public function follows(TwitterUser $user) {
		$db = getDB();
		$stat = $db->prepare("INSERT IGNORE INTO follows (account_id,follows_account_id,created_at) ".
				"VALUES (:account_id,:follows_account_id,:created_at)");
		$stat->execute(array(
				'account_id'=>$this->id, 
				'follows_account_id'=>$user->getId(),
				'created_at'=>date("Y-m-d H:i:s")
			));	
	}
	
	public function isFollowing(TwitterUser $user) {
		$db = getDB();
		$stat = $db->prepare("SELECT follows.* FROM follows WHERE account_id=:i AND follows_account_id=:f");
		$stat->execute(array('i'=>$this->id, 'f'=>$user->getId()));
		return ($stat->rowCount() > 0);
	}

	public function addTokens($token,$secret) {
		$db = getDB();
		$s = $db->prepare("UPDATE twitter_account  SET oauth_token=:t,oauth_secret=:s WHERE id=:id");
		$data = array(
				'id'=>$this->id,
				't'=>$token,
				's'=>$secret,
			);
		$s->execute($data);
	}

	public function setAttending($value) {
		$db = getDB();
		$s = $db->prepare("UPDATE twitter_account  SET attending=:a WHERE id=:id");
		$data = array(
				'id'=>$this->id,
				'a'=>($value?1:0)
			);
		$s->execute($data);
	}
	
	/** @return TwitterOAuth **/
	public function getTwitterConnection() {
		if ($this->oauth_token) {
			return new TwitterOAuth(TWITTER_APP_KEY, TWITTER_APP_SECRET, $this->oauth_token, $this->oauth_secret);
		} else {
			return new TwitterOAuth(TWITTER_APP_KEY, TWITTER_APP_SECRET, TWITTER_ACCESS_TOKEN, TWITTER_ACCESS_TOKEN_SECRET);
		}
	}	
	
}


