<?php

namespace Kata\Test\Registration;

use Kata\Registration\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
	/** Test data */
	const USER1_USERNAME       = 'frodo';
	const USER1_PLAIN_PASSWORD = 'B4gg1ns';

	/**
	 * Tests the constructor.
	 *
	 * @return void
	 */
	public function testConstructor()
	{
		$request = new Request(self::USER1_USERNAME, self::USER1_PLAIN_PASSWORD, self::USER1_PLAIN_PASSWORD);

		$this->assertEquals($request->username, self::USER1_USERNAME, "The username does not match!");
		$this->assertEquals($request->password, self::USER1_PLAIN_PASSWORD, "The password does not match!");
		$this->assertEquals($request->passwordConfirm, self::USER1_PLAIN_PASSWORD, "The confirm passowrd does not match!");
	}

}
