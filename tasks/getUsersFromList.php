<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */
require dirname(__FILE__).'/../src/global.php';

$user_name = $argv[1];
$list = $argv[2];

$opts = getopt('a');
$markAsAttending = isset($opts['a']) && $opts['a'];

print "Username ".$user_name." list ".$list."\n";
print "Mark as attending: ".($markAsAttending?'y':'n')."\n";

$next_cursor = -1; 
while ($next_cursor) {

	$url = "http://api.twitter.com/1/lists/members.json?".
			"owner_screen_name=".$user_name."&slug=".$list."&cursor=".$next_cursor;

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
	} else {
		foreach($data->users as $userData) {
			//var_dump($tweetData);
			if (!$userData->protected) {
				print ".";
				$user = TwitterUser::findOrCreate($userData->id_str, $userData->screen_name, $userData->profile_image_url_https);
				$user->setAttending(true);
			}
		}
	}

	$next_cursor = $data->next_cursor;
	print "Next Cursor: ".$data->next_cursor."\n";
	sleep(1);
}
print "\n\n";

