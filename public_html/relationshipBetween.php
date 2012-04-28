<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */
require dirname(__FILE__).'/../src/global.php';

$user1 = TwitterUser::findByUserName($_GET['id1']);
if (!$user1) die('not found');

$user2 = TwitterUser::findByUserName($_GET['id2']);
if (!$user2) die('not found');

$tweetSearch = new TweetSearch();
$tweetSearch->conversation($user1, $user2);

$tpl = getSmarty();
$tpl->assign('user1',$user1);
$tpl->assign('user2',$user2);
$tpl->assign('tweetSearch',$tweetSearch);
$tpl->display('relationshipBetween.htm');


