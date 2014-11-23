<?php

namespace Kata\Test\Registration;

use Kata\Registration\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
	/** Test data */
	const USER1_USERNAME        = 'frodo';
	const USER1_PLAIN_PASSWORD  = 'B4gg1ns';
	const USER1_HASHED_PASSWORD = 'cbacb647f5ab835807a8c86f2edd894822317590';

	/**
	 * Tests the constructor.
	 *
	 * @return void
	 */
	public function testConstructor()
	{
		$user = new User(self::USER1_USERNAME, self::USER1_HASHED_PASSWORD, self::USER1_PLAIN_PASSWORD);

		$this->assertEquals($user->username, self::USER1_USERNAME, "The username does not match!");
		$this->assertEquals($user->passwordHash, self::USER1_HASHED_PASSWORD, "The hashed password does not match!");
		$this->assertEquals($user->passwordPlain, self::USER1_PLAIN_PASSWORD, "The plain password does not match!");
	}

}
