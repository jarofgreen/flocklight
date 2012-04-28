<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */
require dirname(__FILE__).'/../src/global.php';



$tpl = getSmarty();

if ($_POST && isset($_POST['search'])) {
	$search = new WordUseSearchAllUsers();
	$search->byWord($_POST['search']);
	$tpl->assign('wordSearch',$search);
} else {
	$tpl->assign('wordSearch',null);
}

$tpl->display('wordUse.htm');


