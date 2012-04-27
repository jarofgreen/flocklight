<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */

class Tweet {

	private $id;
	private $text;
	private $created_at;
	private $account_id;
	
	public static function findOrCreate($tweetData) {
		$db = getDB();
		$s = $db->prepare("SELECT tweet.* FROM tweet WHERE id=:id");
		$s->execute(array('id'=>$tweetData->id_str));
		if ($s->rowCount() == 1) {
			return new Tweet($s->fetch());
		} else {
			$s = $db->prepare("INSERT INTO tweet (id,text,created_at,account_id) VALUES (:id,:text,:created_at,:account_id)");
			$data = array(
					'id'=>$tweetData->id_str,
					'text'=>$tweetData->text,
					'created_at'=>date("Y-m-d H:i:s", strtotime($tweetData->created_at)),
					'account_id'=>$tweetData->from_user_id,
				);
			$s->execute($data);
			return new Tweet($data);
		}
	}	

	
	public static function findOrCreateFromData($tweet_id,$tweet,$created_at,$account_id) {
		$db = getDB();
		$s = $db->prepare("SELECT tweet.* FROM tweet WHERE id=:id");
		$s->execute(array('id'=>$tweet_id));
		if ($s->rowCount() == 1) {
			return new Tweet($s->fetch());
		} else {
			$s = $db->prepare("INSERT INTO tweet (id,text,created_at,account_id) VALUES (:id,:text,:created_at,:account_id)");
			$data = array(
					'id'=>$tweet_id,
					'text'=>$tweet,
					'created_at'=>date("Y-m-d H:i:s", strtotime($created_at)),
					'account_id'=>$account_id,
				);
			$s->execute($data);
			return new Tweet($data);
		}
	}	
	
	public function  __construct($data) {
		if (isset($data['id'])) $this->id = $data['id'];
		if (isset($data['text'])) $this->text = $data['text'];
		if (isset($data['created_at'])) $this->created_at = strtotime($data['created_at']);
		if (isset($data['account_id'])) $this->account_id = $data['account_id'];
	}
	
}


