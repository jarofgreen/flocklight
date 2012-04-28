<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */
require '../src/global.php';
if (!session_id()) session_start();

if ($_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
  header('Location: /');
  die("Wrong Session");
}

$connection = new TwitterOAuth(TWITTER_APP_KEY, TWITTER_APP_SECRET, $_SESSION['oauth_token'],$_SESSION['oauth_token_secret']);
$token_credentials = $connection->getAccessToken($_REQUEST['oauth_verifier']);

unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

$connection = new TwitterOAuth(TWITTER_APP_KEY, TWITTER_APP_SECRET, $token_credentials['oauth_token'],$token_credentials['oauth_token_secret']);
$content = $connection->get('account/verify_credentials');

//TODO check for fail

$user = TwitterUser::findOrCreate($content->id,$content->screen_name,$content->profile_image_url);
$_SESSION['userID'] = $content->id;
$_SESSION['userScreenName'] = $content->screen_name;
$_SESSION['userProfileImageURL'] = $content->profile_image_url;

$user->addTokens($token_credentials['oauth_token'],$token_credentials['oauth_token_secret']);

header("Location: ".(isset($_SESSION['afterLoginGoTo'])?$_SESSION['afterLoginGoTo']:'/'));
unset($_SESSION['afterLoginGoTo']);
