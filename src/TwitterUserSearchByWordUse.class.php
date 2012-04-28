<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */

class TwitterUserSearchByWordUse extends  BaseSearch {

	public function  __construct($word) {
		$this->className = "TwitterUser";
		$this->word = $word;
	}	
	
	protected $word;





	protected function execute() {
		if ($this->searchDone) throw new Exception("Search already done!");
		$db = getDB();
		$where = array();
		$joins = array();
		$vars = array();
		
		$joins[] = " JOIN word_use ON word_use.account_id = twitter_account.id ";
		$where[] = " word_use.word = :word ";
		$vars['word'] = $this->word;
		
		
		$sql = "SELECT twitter_account.* ".
			"FROM twitter_account ".implode(" ", $joins).(count($where) > 0 ? " WHERE ".implode(" AND ", $where) : "")."  GROUP BY twitter_account.id ";
		//die($sql);
		$stat = $db->prepare($sql);
		$stat->execute($vars);
		while($d = $stat->fetch(PDO::FETCH_ASSOC)) {
			$this->results[] = $d;
		}
		$this->searchDone = true;
	}
	
	
}