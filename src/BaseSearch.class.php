<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */

abstract class BaseSearch {


	protected $searchDone = false;

	protected $className = null;
	

	protected $results = array();

	public function  __construct() {

	}

	abstract protected function execute();

	//---------------------------------------------------------- get results

	public function nextResult() {
		if (!$this->searchDone) $this->execute();
		$d = array_shift($this->results);
		return $d ? new $this->className($d) : null;
	}

	/** @return Integer the number of results on the current page (if pageing is on) **/
	public function num() {
		if (!$this->searchDone) $this->execute();
		return count($this->results);
	}

}


