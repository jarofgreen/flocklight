<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */
require dirname(__FILE__).'/../src/global.php';



$search = new TwitterUserSearch();
$continue = true;
while(($user = $search->nextResult()) && $continue) {
	
	print $user->getUserName()."\n";

	$conn = $user->getTwitterConnection();
	$url = "statuses/user_timeline.json?count=100&user_id=".$user->getId();
	print date('r')." Searching ".$url."\n";
	$data = $conn->get($url);

	//var_dump($data);

	if (property_exists($data, 'error') && $data->error) {
		print date('r')." Error: ".$data->error."\n";
		$continue = false;
	} else {
		//var_dump($data);
		foreach($data as $tweetData) {
			//var_dump($tweetData);
			print $tweetData->id_str." ";
			$tweet = Tweet::findOrCreateFromData($tweetData->id_str, $tweetData->text,$tweetData->created_at,$tweetData->user->id_str);
		}
	}
	
	print "\n\n";
	sleep(1);
		
}
