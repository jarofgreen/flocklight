<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */
require dirname(__FILE__).'/../src/global.php';


$db = getDB();
$stat = $db->prepare('SELECT * FROM tweet');
$stat->execute();
	
$statInsert = $db->prepare("INSERT IGNORE INTO tweet_conversation (from_account_id,to_account_id,tweet_id) ".
			"VALUES (:f, :t, :tweetid)");

while ($d = $stat->fetch()) {
	print $d['id']. " ";
	
	$matches = array();
	preg_match_all('/@(\w+)/', $d['text'], $matches);
	//print $d['text']."\n";
	foreach($matches[1] as $username) {
		//print $username." IS MATCHED\n";
		$user = TwitterUser::findByUserName($username);
		if ($user) {
			$statInsert->execute(array(
					'f'=>$d['account_id'],
					't'=>$user->getId(),
					'tweetid'=>$d['id']
				));
			print ".";
		}
	}

	print "\n";	
}
print "\n\n";
