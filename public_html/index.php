<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */
require dirname(__FILE__).'/../src/global.php';



$tpl = getSmarty();
$tpl->assign('searchUsers',new TwitterUserSearch());
$tpl->display('index.htm');



