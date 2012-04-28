<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */


class UserRelationship {
	
	/** @var TwitterUser **/
	protected $user1;
	/** @var TwitterUser **/
	protected $user2;
	
	public function __construct(TwitterUser $user1, TwitterUser $user2) {
		$this->user1 = $user1;
		$this->user2 = $user2;
		$this->getData();
	}
	
	protected $user1followsuser2 = false;
	protected $user2followsuser1 = false;

	public function isDirectLink() { return $this->user1followsuser2 || $this->user2followsuser1; }

	public function getData() {
		$db = getDB();
		
		// Direct Links
		$stat = $db->prepare("SELECT * FROM follows WHERE ".
				"(account_id = :u1 AND follows_account_id = :u2) ".
				"OR (account_id = :u2 AND follows_account_id = :u1)");
		$stat->execute(array('u1'=>$this->user1->getId(), 'u2'=>$this->user2->getId()));
		while($d = $stat->fetch()) {
			if ($d['account_id'] == $this->user1->getId() && $d['follows_account_id'] == $this->user2->getId()) {
				$this->user1followsuser2 = true;
			} else if ($d['account_id'] == $this->user2->getId() && $d['follows_account_id'] == $this->user1->getId()) {
				$this->user2followsuser1 = true;
			} 
		}
		
		
		
	}
}

