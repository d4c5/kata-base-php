<?php

namespace Kata\Test\Velocity;

use Kata\Velocity\LoginLog;
use Kata\Velocity\User;
use Kata\Velocity\Ip;

/**
 * TODO LIST:
 *  - Exceptions			[ok]
 *  - Getters				[ok]
 */
class LoginLogTest extends \PHPUnit_Framework_TestCase
{
	const TEST_USERNAME    = 'test_user';
	const TEST_REG_COUNTRY = 'LU';

	const TEST_IP_ADDRESS = '192.168.4.1';
	const TEST_IP_RANGE   = 'OfficeLAN';
	const TEST_IP_COUNTRY = 'LU';

	const TEST_LOGIN_RESULT = true;

	const FAILED_LOGIN_RESULT = 'asd-asd?';

	/**
	 * Tests that the result of the login is valid.
	 *
     * @expectedException Kata\Velocity\LoginLogException
	 * @expectedExceptionCode 301
     */
    public function testInvalidLoginResultException()
    {
		$user = new User(self::TEST_USERNAME, self::TEST_REG_COUNTRY);
		$ip   = new Ip(self::TEST_IP_ADDRESS, self::TEST_IP_RANGE, self::TEST_IP_COUNTRY);

		new LoginLog($ip, $user, self::FAILED_LOGIN_RESULT);
    }

	/**
	 * Tests the given and returned IP objects are equal.
	 */
	public function testGetIp()
	{
		$user = new User(self::TEST_USERNAME, self::TEST_REG_COUNTRY);
		$ip = new Ip(self::TEST_IP_ADDRESS, self::TEST_IP_RANGE, self::TEST_IP_COUNTRY);

		$loginLog = new LoginLog($ip, $user, self::TEST_LOGIN_RESULT);

		$this->assertEquals($ip, $loginLog->getIp());
	}

	/**
	 * Tests the given and returned User objects are equal.
	 */
	public function testGetUser()
	{
		$user = new User(self::TEST_USERNAME, self::TEST_REG_COUNTRY);
		$ip = new Ip(self::TEST_IP_ADDRESS, self::TEST_IP_RANGE, self::TEST_IP_COUNTRY);

		$loginLog = new LoginLog($ip, $user, self::TEST_LOGIN_RESULT);

		$this->assertEquals($user, $loginLog->getUser());
	}

	/**
	 * Tests the given and returned results of login are equal.
	 */
	public function testGetResult()
	{
		$user = new User(self::TEST_USERNAME, self::TEST_REG_COUNTRY);
		$ip = new Ip(self::TEST_IP_ADDRESS, self::TEST_IP_RANGE, self::TEST_IP_COUNTRY);

		$loginLog = new LoginLog($ip, $user, self::TEST_LOGIN_RESULT);

		$this->assertEquals(self::TEST_LOGIN_RESULT, $loginLog->getResult());
	}

}
