<?php

namespace Kata\Test\Velocity;

use Kata\Velocity\User;

/**
 * TODO LIST:
 *  - Exceptions			[ok]
 *  - Getters				[ok]
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
	const TEST_USERNAME    = 'test_user';
	const TEST_REG_COUNTRY = 'LU';

	const FAILED_USERNAME    = 'asd-asd?';
	const FAILED_REG_COUNTRY = '01';

	/**
	 * Tests that the username is valid.
	 *
     * @expectedException Kata\Velocity\UserException
	 * @expectedExceptionCode 501
     */
    public function testInvalidUsernameException()
    {
		new User(self::FAILED_USERNAME, self::TEST_REG_COUNTRY);
    }

	/**
	 * Tests that the country is valid.
	 *
     * @expectedException Kata\Velocity\UserException
	 * @expectedExceptionCode 502
     */
    public function testInvalidRegCountryException()
    {
		new User(self::TEST_USERNAME, self::FAILED_REG_COUNTRY);
    }

	/**
	 * Tests the given and returned usernames are equal.
	 */
	public function testGetUsername()
	{
		$user = new User(self::TEST_USERNAME, self::TEST_REG_COUNTRY);

		$this->assertEquals(self::TEST_USERNAME, $user->getUsername());
	}

	/**
	 * Tests the given and returned countries are equal.
	 */
	public function testGetCountry()
	{
		$user = new User(self::TEST_USERNAME, self::TEST_REG_COUNTRY);

		$this->assertEquals(self::TEST_REG_COUNTRY, $user->getCountry());
	}

}
