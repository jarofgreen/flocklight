<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */
require dirname(__FILE__).'/../src/global.php';


$user = TwitterUser::findByUserName($_GET['id']);
if (!$user) die('not found');

$tpl = getSmarty();
$tpl->assign('user',$user);

$search = new TwitterUserSearch();
$search->follows($user);
$search->followedBy($user);
$tpl->assign('searchUsers',$search);

$tpl->display('userFollows.htm');


