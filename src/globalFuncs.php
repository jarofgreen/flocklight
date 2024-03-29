<?php
/**
 * @package Flocklight
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */

function my_autoload($class_name){
	$inc = dirname(__FILE__);	
	if ($class_name == 'TwitterOAuth') {
		require $inc.'/../libs/twitteroauth/twitteroauth.php';
	} else if (file_exists($inc."/".$class_name.'.class.php')) {
		require_once($inc."/".$class_name.'.class.php');
	}
}
spl_autoload_register("my_autoload");

date_default_timezone_set('UTC');


$DB_CONNECTION = null;
/** @return PDO **/
function getDB() {
	global $DB_CONNECTION;
	if (!$DB_CONNECTION) {
		$DB_CONNECTION = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
		$DB_CONNECTION->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$DB_CONNECTION->exec('SET CHARACTER SET utf8');
		$DB_CONNECTION->exec("SET NAMES 'utf8'");		
	}
	return $DB_CONNECTION;
}



/** @return Smarty **/
function getSmarty() {
	if (!session_id()) session_start();
	require_once dirname(__FILE__).'/../libs/smarty/Smarty.class.php';
	$s = new Smarty();
	$s->template_dir = dirname(__FILE__) . '/../templates/';
	$s->compile_dir = dirname(__FILE__) . '/../smarty_c/';
	$s->assign('eventName',EVENT_NAME);
	$s->assign('userID',isset($_SESSION['userID']) ? $_SESSION['userID'] : null);
	$s->assign('userScreenName',isset($_SESSION['userScreenName']) ? $_SESSION['userScreenName'] : null);
	$s->assign('userProfileImageURL',isset($_SESSION['userProfileImageURL']) ? $_SESSION['userProfileImageURL'] : null);	
	return $s;
}