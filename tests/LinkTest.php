<?php
/**
 * @package FlocklightTests
 * @license GPLv3
 * @see https://github.com/jarofgreen/flocklight
 */


class LinkTest extends AbstractTest {
	
	function test1() {
	
		$db = $this->setupDB();
		$this->insertUser(1, 'test1');
		$this->insertUser(2, 'test2');
		
		$user1 = TwitterUser::find(1);
		$user2 = TwitterUser::find(2);
		
		$r = new UserRelationship($user1,$user2);
		$this->assertEquals(false, $r->isDirectLink());
		
		$user1->follows($user2);
		
		$r = new UserRelationship($user1,$user2);
		$this->assertEquals(true, $r->isDirectLink());

	}
	
}

