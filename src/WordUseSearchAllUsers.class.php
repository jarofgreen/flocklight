<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */


class WordUseSearchAllUsers extends BaseSearch {
	
	protected $searchForWord;
	
	public function byWord($word) {
		$this->searchForWord = $word;
	}

	protected function execute() {
		if ($this->searchDone) throw new Exception("Search already done!");
		$db = getDB();
		$where = array();
		$joins = array();
		$vars = array();
				
		if ($this->searchForWord) {
			$where[] = " word_use.word LIKE :term";
			$vars['term'] = '%'.$this->searchForWord.'%';
		}
		
		$sql = "SELECT word_use.word, SUM(word_use.num_used) AS num_used ".
			"FROM word_use ".implode(" ", $joins).(count($where) > 0 ? " WHERE ".implode(" AND ", $where) : "").
			" GROUP BY word_use.word ";
		//die($sql);
		$stat = $db->prepare($sql);
		$stat->execute($vars);
		while($d = $stat->fetch(PDO::FETCH_ASSOC)) {
			$this->results[] = $d;
		}
		$this->searchDone = true;
		
		
	}
	
	
	public function nextResult() {
		if (!$this->searchDone) $this->execute();
		$d = array_shift($this->results);
		return $d;
	}
	
}

