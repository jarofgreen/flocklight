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

	$url = "http://api.twitter.com/1/statuses/user_timeline.json?count=100&user_id=".$user->getId();

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'chs');
	$rawData = curl_exec($ch);
	curl_close($ch);

	print date('r')." Searching ".$url."\n";

	$data = json_decode($rawData);
	//print $rawData;

	if (property_exists($data, 'error') && $data->error) {
		print date('r')." Error: ".$data->error."\n";
		$continue = false;
	} else {
		//var_dump($data);
		foreach($data as $tweetData) {
			var_dump($tweetData);
			
			$tweet = Tweet::findOrCreateFromData($tweetData->id_str, $tweetData->text,$tweetData->created_at,$tweetData->user->id_str);
		}
	}

	sleep(1);
		
}
