<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */
require dirname(__FILE__).'/../src/global.php';
$continue = true;
foreach($searchTerms as $searchTerm) {
	if ($continue) {

		$url = "http://search.twitter.com/search.json?q=".urlencode($searchTerm)."&result_type=recent&rpp=100";

		print date('r')." Searching ".$url."\n";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_USERAGENT, 'chs');
		$rawData = curl_exec($ch);
		curl_close($ch);

		$data = json_decode($rawData);
		//print $rawData;

		if (property_exists($data, 'error') && $data->error) {
			print date('r')." Error: ".$data->error."\n";
			$continue = false;
		} else {
			//var_dump($data);
			foreach($data->results as $tweetData) {
				$at = strtotime($tweetData->created_at);
				$user = TwitterUser::findOrCreate($tweetData->from_user_id_str, $tweetData->from_user, $tweetData->profile_image_url);
				print $tweetData->id_str." ";
				$tweet = Tweet::findOrCreate($tweetData);
			}
		}
		print "\n\n";
		sleep(1);


		print date('r')." Finished Search\n";
	}
}
