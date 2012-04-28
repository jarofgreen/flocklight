<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */

class TweetSearch extends  BaseSearch {

	public function  __construct() {
		$this->className = "Tweet";
	}	
	
	/** @var TwitterUser **/
	protected $conversationUser1;
	/** @var TwitterUser **/
	protected $conversationUser2;

	public function conversation(TwitterUser $user1, TwitterUser $user2) {
		$this->conversationUser1 = $user1;
		$this->conversationUser2 = $user2;
	}

	protected function execute() {
		if ($this->searchDone) throw new Exception("Search already done!");
		$db = getDB();
		$where = array();
		$joins = array();
		
		if ($this->conversationUser1) {
			$joins[] = " LEFT JOIN tweet_conversation AS tc1 ON tc1.tweet_id = tweet.id AND ".
					 " tc1.from_account_id = ".  intval($this->conversationUser1->getId()) . " AND ".
					" tc1.to_account_id = ".  intval($this->conversationUser2->getId());
			$joins[] = " LEFT JOIN tweet_conversation AS tc2 ON tc2.tweet_id = tweet.id AND ".
					 " tc2.to_account_id = ".  intval($this->conversationUser1->getId()) . " AND ".
					" tc2.from_account_id = ".  intval($this->conversationUser2->getId());
			$where[] = "  (tc1.to_account_id IS NOT NULL OR  tc2.to_account_id IS NOT NULL)  "  ;
		}
		
		$sql = "SELECT tweet.* ".
			"FROM tweet ".implode(" ", $joins).(count($where) > 0 ? " WHERE ".implode(" AND ", $where) : "")."  GROUP BY tweet.id ";
		//die($sql);
		$stat = $db->prepare($sql);
		$stat->execute();
		while($d = $stat->fetch(PDO::FETCH_ASSOC)) {
			$this->results[] = $d;
		}
		$this->searchDone = true;
	}
	
	
}