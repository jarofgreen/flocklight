<?php
/**
 * @package FlocklightTests
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */


require dirname(__FILE__).'/../testConfig.php';
if (!defined('DEFAULT_TIME_ZONE')) define('DEFAULT_TIME_ZONE', 'Europe/London');

require dirname(__FILE__).'/../src/globalFuncs.php';

class AbstractTest extends PHPUnit_Framework_TestCase {

	function setupDB() {		
		$db = getDB();
		$db->exec(file_get_contents(dirname(__FILE__).'/../sql/destroy.sql'));	
		$db->exec(file_get_contents(dirname(__FILE__).'/../sql/create.sql'));
		return $db;
	}

	function insertUser($id, $name, $url='test') {
		$db = getDB();
		$stat = $db->prepare('INSERT INTO twitter_account (id,user_name,profile_image_url) '.
				'VALUES (:id,:user_name,:profile_image_url)');
		$stat->execute(array(
				'id'=>$id,
				'user_name'=>$name,
				'profile_image_url'=>$url
			));
	}
	
	
	
}

