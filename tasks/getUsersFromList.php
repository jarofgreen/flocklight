<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */
require dirname(__FILE__).'/../src/global.php';

$user_name = $argv[1];
$list = $argv[2];

print "Username ".$user_name." list ".$list."\n";

$url = "http://api.twitter.com/1/lists/members.json?owner_screen_name=".$user_name."&slug=".$list;

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
		TwitterUser::findOrCreate($userData->id_str, $userData->screen_name, $userData->profile_image_url_https);
	}
}

