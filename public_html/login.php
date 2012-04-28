<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */
require '../src/global.php';

$connection = new TwitterOAuth(TWITTER_APP_KEY, TWITTER_APP_SECRET);
$temporary_credentials = $connection->getRequestToken('http://'.HTTP_HOST.'/loginCallBack.php?');
if (!session_id()) session_start();
$_SESSION['oauth_token'] = $temporary_credentials['oauth_token'];
$_SESSION['oauth_token_secret'] = $temporary_credentials['oauth_token_secret'];
if ($connection->http_code == 200) {
	$redirect_url = $connection->getAuthorizeURL($temporary_credentials);
	header("Location: ".$redirect_url);
} else {
	die("Could not make connection to Twitter ".$connection->http_code);
}
