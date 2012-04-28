<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */
require dirname(__FILE__).'/../src/global.php';


$word = $_GET['id'];

$tpl = getSmarty();

$search = new TwitterUserSearchByWordUse($word);
$tpl->assign('searchUsers',$search);

$tpl->display('word.htm');


