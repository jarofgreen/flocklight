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

	$url = "http://api.twitter.com/1/friends/ids.json?stringify_ids=true&user_id=".$user->getId();

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
		foreach($data->ids as $id) {
			$user2 = TwitterUser::find($id);
			if ($user2) {
				print $id . " YES ";
				$user->follows($user2);
			} else {
				print $id . " ";
			}
		}
	}
	
	print "\n\n";
	sleep(1);

}

