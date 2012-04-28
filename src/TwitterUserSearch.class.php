<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */

class TwitterUserSearch extends  BaseSearch {

	public function  __construct() {
		$this->className = "TwitterUser";
	}	
	
	/** @var TwitterUser **/
	protected $follows;
	/** @var TwitterUser **/
	protected $followedBy;
	/** @var TwitterUser **/
	protected $tweetsFrom;
	/** @var TwitterUser **/
	protected $tweetsTo;


	public function follows(TwitterUser $user) {
		$this->follows = $user;
	}
	
	
	public function followedBy(TwitterUser $user) {
		$this->followedBy = $user;
	}
	
	public function tweetsFrom(TwitterUser $user) {
		$this->tweetsFrom = $user;
	}
	
	public function tweetsTo(TwitterUser $user) {
		$this->tweetsTo = $user;
	}
	
	

	protected function execute() {
		if ($this->searchDone) throw new Exception("Search already done!");
		$db = getDB();
		$where = array();
		$joins = array();
		
		if ($this->follows) {
			$joins[] = " JOIN follows AS f1 ON f1.follows_account_id = twitter_account.id ";
			$where[] = " f1.account_id = ".  intval($this->follows->getId());
		}		
		if ($this->followedBy) {
			$joins[] = " JOIN follows AS f2 ON f2.account_id = twitter_account.id ";
			$where[] = " f2.follows_account_id = ".  intval($this->followedBy->getId());
		}
		
		if ($this->tweetsFrom) {
			$joins[] = " JOIN tweet_conversation AS t1 ON t1.to_account_id = twitter_account.id ";
			$where[] = " t1.from_account_id = ".  intval($this->tweetsFrom->getId());
		}		
		if ($this->tweetsTo) {
			$joins[] = " JOIN tweet_conversation AS t2 ON t2.from_account_id = twitter_account.id ";
			$where[] = " t2.to_account_id = ".  intval($this->tweetsTo->getId());
		}
		
		
		$sql = "SELECT twitter_account.* ".
			"FROM twitter_account ".implode(" ", $joins).(count($where) > 0 ? " WHERE ".implode(" AND ", $where) : "")."  GROUP BY twitter_account.id ";
		//die($sql);
		$stat = $db->prepare($sql);
		$stat->execute();
		while($d = $stat->fetch(PDO::FETCH_ASSOC)) {
			$this->results[] = $d;
		}
		$this->searchDone = true;
	}
	
	
}