<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */

class WordUse {

	private $account_id;
	private $word;
	private $num_used;
	 
	public function  __construct($data) {
		if (isset($data['account_id'])) $this->account_id = $data['account_id'];
		if (isset($data['word'])) $this->word = $data['word'];
		if (isset($data['num_used'])) $this->num_used = $data['num_used'];
	}
	
	public function getNumUsed() { return $this->num_used; }
	public function getWord() { return $this->word; }
	public function getAccountId() { return $this->account_id; }
	
	
}

