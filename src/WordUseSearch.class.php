<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */

class WordUseSearch extends  BaseSearch {

	public function  __construct() {
		$this->className = "WordUse";
	}	
	
	/** @var TwitterUser **/
	protected $user;

	public function byUser(TwitterUser $user) {
		$this->user = $user;
	}
	
	
	protected function execute() {
		if ($this->searchDone) throw new Exception("Search already done!");
		$db = getDB();
		$where = array();
		$joins = array();
		
		if ($this->user) {
			$where[] = " word_use.account_id = ".  intval($this->user->getId());
		}		
		
		$sql = "SELECT word_use.* ".
			"FROM word_use ".implode(" ", $joins).(count($where) > 0 ? " WHERE ".implode(" AND ", $where) : "").
			" ORDER BY word_use.num_used DESC  ";
		//die($sql);
		$stat = $db->prepare($sql);
		$stat->execute();
		while($d = $stat->fetch(PDO::FETCH_ASSOC)) {
			$this->results[] = $d;
		}
		$this->searchDone = true;
	}
	
	
}