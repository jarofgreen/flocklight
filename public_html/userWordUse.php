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

$search = new WordUseSearch();
$search->byUser($user);
$tpl->assign('searchWordUse',$search);

$tpl->display('userWordUse.htm');


