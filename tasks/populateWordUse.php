<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */
require dirname(__FILE__).'/../src/global.php';

$db = getDB();
$statSelect = $db->prepare("SELECT * FROM word_use WHERE account_id=:id AND word=:w");
$statUpdate = $db->prepare("UPDATE word_use SET num_used=:n WHERE account_id=:id AND word=:w");
$statInsert = $db->prepare("INSERT INTO word_use (num_used,account_id,word) VALUES (:n,:id,:w)");

$total = 0;

$userSearch = new TwitterUserSearch();
$continue = true;
while(($user = $userSearch->nextResult()) && $continue) {

	print $user->getUserName()."\n";
	
	$data = array();
	
	$tweetSearch = new TweetSearch();
	$tweetSearch->by($user);
	while($tweet = $tweetSearch->nextResult()) {
		$bits = array_count_values(str_word_count(strtolower($tweet->getTweet()), 1));
		foreach($bits as $word=>$count) {
			if (isset($data[$word])) {
				$data[$word] += $count;
			} else {
				$data[$word] = $count;
			}
		}
	}
	
	var_dump (count($data));
	$total += count($data);
	
	
	
	foreach($data as $word=>$number) {
		$statSelect->execute(array('id'=>$user->getId(),'w'=>$word));
		if ($statSelect->rowCount() == 0) {
			$statInsert->execute(array('id'=>$user->getId(),'w'=>$word,'n'=>$number));
		} else {
			$statUpdate->execute(array('id'=>$user->getId(),'w'=>$word,'n'=>$number));
		}
	}
	
	
	print "\n\n";
	sleep(1);

}
print $total;
print "\n\n";
	