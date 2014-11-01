<?php

namespace Kata\Test\Velocity;

use Kata\Velocity\VelocityChecker;
use Kata\Velocity\Ip;
use Kata\Velocity\User;
use Kata\Velocity\LoginLog;
use Kata\Velocity\Counter;
use Kata\Velocity\CounterDao;

/**
 * TODO LIST:
 *  - The status of captcha after successful login													[ok]
 *  - The successful login result resets counters													[ok]
 *  - failed login attempts reaches the IP limit													[ok]
 *  - failed login attempts reaches the username limit												[ok]
 *  - failed login attempts reaches the IP range limit												[ok]
 *  - failed login attempts reaches the IP country limit											[ok]
 *  - failed login attempts = IP limit when the IP country and registration country are different	[ok]
 *  - failed login attempts = no IP limit when the IP country and registration country are equal	[no need]
 *  - Counter mocking																				[ok]
 */
class VelocityCheckerTest extends \PHPUnit_Framework_TestCase
{
	const TEST_DATABASE_PATH = 'test/Velocity/velocityCheckerTest.db';

	const TEST_USERNAME    = 'test_user';
	const TEST_REG_COUNTRY = 'LU';

	const TEST_FOREIGN_REG_COUNTRY = 'HU';

	const TEST_IP_ADDRESS = '192.168.4.1';
	const TEST_IP_RANGE   = 'OfficeLAN';
	const TEST_IP_COUNTRY = 'LU';

	const TEST_LOGIN_RESULT_SUCCESS = true;
	const TEST_LOGIN_RESULT_FAILED  = false;

	/**
	 * Tests that failed login attempts reaches the limit.
	 *
	 * @param boolean $statusOfCaptcha
	 * @param int     $numberOfIpCounter
	 * @param int     $numberOfIpRangeCounter
	 * @param int     $numberOfIpCountryCounter
	 * @param int     $numberOfUsernameCounter
	 * @param boolean $resultOfLogin
	 * @param string  $registrationCountry
	 *
	 * @return void
	 *
	 * @dataProvider providerCountersToLimitChecking
	 */
	public function testCapthca(
		$statusOfCaptcha, $numberOfIpCounter, $numberOfIpRangeCounter, $numberOfIpCountryCounter, $numberOfUsernameCounter,
		$resultOfLogin, $registrationCountry
	) {
		$dbConnection = new \SQLite3(self::TEST_DATABASE_PATH);
		$counterDao   = new CounterDao($dbConnection);

		$ipCounter = $this->getMock(
			'\Kata\Velocity\Counter',
			array('getCounter', 'reset', 'setCounter'),
			array($counterDao, Counter::TYPE_IP, self::TEST_IP_ADDRESS)
		);
		$ipCounter->expects($this->any())
				->method('getCounter')
				->will($this->returnValue($numberOfIpCounter));
		$ipCounter->expects($this->any())
				->method('reset')
				->will($this->returnValue(true));
		$ipCounter->expects($this->any())
				->method('setCounter')
				->will($this->returnValue(true));

		$ipRangeCounter = $this->getMock(
			'\Kata\Velocity\Counter',
			array('getCounter'),
			array($counterDao, Counter::TYPE_IP_RANGE, self::TEST_IP_RANGE)
		);
		$ipRangeCounter->expects($this->any())
				->method('getCounter')
				->will($this->returnValue($numberOfIpRangeCounter));

		$ipCountryCounter = $this->getMock(
			'\Kata\Velocity\Counter',
			array('getCounter'),
			array($counterDao, Counter::TYPE_IP_COUNTRY, self::TEST_IP_COUNTRY)
		);
		$ipCountryCounter->expects($this->any())
				->method('getCounter')
				->will($this->returnValue($numberOfIpCountryCounter));
		$usernameCounter = $this->getMock(
			'\Kata\Velocity\Counter',
			array('getCounter', 'reset'),
			array($counterDao, Counter::TYPE_USERNAME, self::TEST_USERNAME)
		);
		$usernameCounter->expects($this->any())
				->method('getCounter')
				->will($this->returnValue($numberOfUsernameCounter));
		$usernameCounter->expects($this->any())
				->method('reset')
				->will($this->returnValue(true));

		$user     = new User(self::TEST_USERNAME, $registrationCountry);
		$ip       = new Ip(self::TEST_IP_ADDRESS, self::TEST_IP_RANGE, self::TEST_IP_COUNTRY);
		$loginLog = new LoginLog($ip, $user, $resultOfLogin);
		$velocity = new VelocityChecker($loginLog, $ipCounter, $ipRangeCounter, $ipCountryCounter, $usernameCounter);

		$this->assertEquals($statusOfCaptcha, $velocity->isCaptchaActive());
	}

	/**
	 * Returns the status of the captcha, counters, result of login and registration country.
	 *
	 * @return array
	 */
	public function providerCountersToLimitChecking()
	{
		return array(
			// Successful login checks: captcha.
			array(false, 0, 0, 0, 0, self::TEST_LOGIN_RESULT_SUCCESS, self::TEST_REG_COUNTRY),
			// Tests that failed login attempts reaches the limit.
			array(false, 0, 0, 0, 0, self::TEST_LOGIN_RESULT_FAILED, self::TEST_REG_COUNTRY),
			array(false, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP, 0, 0, 0, self::TEST_LOGIN_RESULT_FAILED, self::TEST_REG_COUNTRY),
			array(false, 0, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_RANGE, 0, 0, self::TEST_LOGIN_RESULT_FAILED, self::TEST_REG_COUNTRY),
			array(false, 0, 0, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_COUNTRY, 0, self::TEST_LOGIN_RESULT_FAILED, self::TEST_REG_COUNTRY),
			array(false, 0, 0, 0, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_WITH_ONE_USERNAME, self::TEST_LOGIN_RESULT_FAILED, self::TEST_REG_COUNTRY),
			array(true, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP + 1, 0, 0, 0, self::TEST_LOGIN_RESULT_FAILED, self::TEST_REG_COUNTRY),
			array(true, 0, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_RANGE + 1, 0, 0, self::TEST_LOGIN_RESULT_FAILED, self::TEST_REG_COUNTRY),
			array(true, 0, 0, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_COUNTRY + 1, 0, self::TEST_LOGIN_RESULT_FAILED, self::TEST_REG_COUNTRY),
			array(true, 0, 0, 0, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_WITH_ONE_USERNAME + 1, self::TEST_LOGIN_RESULT_FAILED, self::TEST_REG_COUNTRY),
			// Tests that failed login attempts = IP limit when the IP country and registration country are different.
			array(false, 0, 0, 0, 0, self::TEST_LOGIN_RESULT_FAILED, self::TEST_FOREIGN_REG_COUNTRY),
		);
	}

}
