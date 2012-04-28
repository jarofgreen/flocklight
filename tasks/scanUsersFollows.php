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
	$url = "friends/ids.json?stringify_ids=true&user_id=".$user->getId();
	print date('r')." Searching ".$url."\n";
	$data = $conn->get($url);

	//var_dump($data);
	
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

