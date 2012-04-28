<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */
require dirname(__FILE__).'/../src/global.php';



$tpl = getSmarty();

$search = new WordUseSearchAllUsers();
$tpl->assign('wordSearch',$search);

$tpl->display('wordUse.htm');


