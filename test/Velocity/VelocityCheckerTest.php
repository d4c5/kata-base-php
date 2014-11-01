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
 */
class VelocityCheckerTest extends \PHPUnit_Framework_TestCase
{
	const TEST_USERNAME    = 'test_user';
	const TEST_REG_COUNTRY = 'LU';

	const TEST_FOREIGN_REG_COUNTRY = 'HU';

	const TEST_IP_ADDRESS = '192.168.4.1';
	const TEST_IP_RANGE   = 'OfficeLAN';
	const TEST_IP_COUNTRY = 'LU';

	const TEST_LOGIN_RESULT_SUCCESS = true;
	const TEST_LOGIN_RESULT_FAILED  = false;

	/**
	 * Database connection
	 *
	 * @var SQLite3
	 */
	private $dbConnection = null;

	/**
	 * Counter DAO.
	 *
	 * @var CounterDao
	 */
	private $counterDao = null;

	/**
	 * User object.
	 *
	 * @var User
	 */
	private $user = null;

	/**
	 * IP object.
	 *
	 * @var Ip
	 */
	private $ip = null;

	private $ipCounter        = null;
	private $ipRangeCounter   = null;
	private $ipCountryCounter = null;
	private $usernameCounter  = null;

	/**
	 * Sets the database connection and counter DAO.
	 *
	 * @return void
	 */
	public function setUp()
	{
		$this->dbConnection = new \SQLite3('test/Velocity/velocityCheckerTest.db');
		$this->counterDao   = new CounterDao($this->dbConnection);
		$this->user         = new User(self::TEST_USERNAME, self::TEST_REG_COUNTRY);
		$this->ip           = new Ip(self::TEST_IP_ADDRESS, self::TEST_IP_RANGE, self::TEST_IP_COUNTRY);

		$this->ipCounter        = new Counter($this->dbConnection, Counter::TYPE_IP, self::TEST_IP_ADDRESS);
		$this->ipRangeCounter   = new Counter($this->dbConnection, Counter::TYPE_IP_RANGE, self::TEST_IP_RANGE);
		$this->ipCountryCounter = new Counter($this->dbConnection, Counter::TYPE_IP_COUNTRY, self::TEST_IP_COUNTRY);
		$this->usernameCounter  = new Counter($this->dbConnection, Counter::TYPE_USERNAME, self::TEST_USERNAME);

		$this->counterDao->createTable();
	}

	/**
	 * Successful login checks: captcha.
	 */
	public function testSuccessfulLoginCaptcha()
	{
		$loginLog        = new LoginLog($this->ip, $this->user, self::TEST_LOGIN_RESULT_SUCCESS);
		$velocityChecker = new VelocityChecker($loginLog, $this->ipCounter, $this->ipRangeCounter, $this->ipCountryCounter, $this->usernameCounter);

		$this->assertFalse($velocityChecker->isCaptchaActive());
	}

	/**
	 * Successful login checks: counters.
	 */
	public function testSuccessfulLoginCounters()
	{
		$loginLog        = new LoginLog($this->ip, $this->user, self::TEST_LOGIN_RESULT_SUCCESS);
		$velocityChecker = new VelocityChecker($loginLog, $this->ipCounter, $this->ipRangeCounter, $this->ipCountryCounter, $this->usernameCounter);

		$this->counterDao->createLogEntry(Counter::TYPE_IP, self::TEST_IP_ADDRESS, 1);
		$this->counterDao->createLogEntry(Counter::TYPE_USERNAME, self::TEST_USERNAME, 1);

		$velocityChecker->isCaptchaActive();

		$result = $this->dbConnection->query("SELECT COUNT(*) AS cnt FROM `counter`");
		$row    = $result->fetchArray(SQLITE3_ASSOC);

		$this->assertEquals(0, !empty($row['cnt']) ? $row['cnt'] : false);
	}

	/**
	 * Tests that failed login attempts reaches the IP limit.
	 */
	public function testIpLimit()
	{
		$loginLog = new LoginLog($this->ip, $this->user, self::TEST_LOGIN_RESULT_FAILED);

		$this->counterDao->createLogEntry(Counter::TYPE_IP, self::TEST_IP_ADDRESS, 2);

		$velocityCheckerCaptchaInactive  = new VelocityChecker($loginLog, $this->ipCounter, $this->ipRangeCounter, $this->ipCountryCounter, $this->usernameCounter);

		$this->assertFalse($velocityCheckerCaptchaInactive->isCaptchaActive());

		$this->counterDao->createLogEntry(Counter::TYPE_IP, self::TEST_IP_ADDRESS, 2);

		$velocityCheckerCaptchaActive  = new VelocityChecker($loginLog, $this->ipCounter, $this->ipRangeCounter, $this->ipCountryCounter, $this->usernameCounter);

		$this->assertTrue($velocityCheckerCaptchaActive->isCaptchaActive());
	}

	/**
	 * Tests that failed login attempts reaches the IP range limit.
	 */
	public function testIpRangeLimit()
	{
		$loginLog = new LoginLog($this->ip, $this->user, self::TEST_LOGIN_RESULT_FAILED);

		$this->counterDao->createLogEntry(Counter::TYPE_IP_RANGE, self::TEST_IP_RANGE, 1);

		$velocityCheckerCaptchaInactive = new VelocityChecker($loginLog, $this->ipCounter, $this->ipRangeCounter, $this->ipCountryCounter, $this->usernameCounter);

		$this->assertFalse($velocityCheckerCaptchaInactive->isCaptchaActive());

		$this->counterDao->createLogEntry(Counter::TYPE_IP_RANGE, self::TEST_IP_RANGE, 500);

		$velocityCheckerCaptchaActive = new VelocityChecker($loginLog, $this->ipCounter, $this->ipRangeCounter, $this->ipCountryCounter, $this->usernameCounter);

		$this->assertTrue($velocityCheckerCaptchaActive->isCaptchaActive());
	}

	/**
	 * Tests that failed login attempts reaches the IP country limit.
	 */
	public function testIpCountryLimit()
	{
		$loginLog = new LoginLog($this->ip, $this->user, self::TEST_LOGIN_RESULT_FAILED);

		$this->counterDao->createLogEntry(Counter::TYPE_IP_COUNTRY, self::TEST_IP_COUNTRY, 1);

		$velocityCheckerCaptchaInactive = new VelocityChecker($loginLog, $this->ipCounter, $this->ipRangeCounter, $this->ipCountryCounter, $this->usernameCounter);

		$this->assertFalse($velocityCheckerCaptchaInactive->isCaptchaActive());

		$this->counterDao->createLogEntry(Counter::TYPE_IP_COUNTRY, self::TEST_IP_COUNTRY, 1000);

		$velocityCheckerCaptchaActive = new VelocityChecker($loginLog, $this->ipCounter, $this->ipRangeCounter, $this->ipCountryCounter, $this->usernameCounter);

		$this->assertTrue($velocityCheckerCaptchaActive->isCaptchaActive());
	}

	/**
	 * Tests that failed login attempts reaches the username limit.
	 */
	public function testUsernameLimit()
	{
		$loginLog = new LoginLog($this->ip, $this->user, self::TEST_LOGIN_RESULT_FAILED);

		$this->counterDao->createLogEntry(Counter::TYPE_USERNAME, self::TEST_USERNAME, 1);

		$velocityCheckerCaptchaInactive = new VelocityChecker($loginLog, $this->ipCounter, $this->ipRangeCounter, $this->ipCountryCounter, $this->usernameCounter);

		$this->assertFalse($velocityCheckerCaptchaInactive->isCaptchaActive());

		$this->counterDao->createLogEntry(Counter::TYPE_USERNAME, self::TEST_USERNAME, 3);

		$velocityCheckerCaptchaActive = new VelocityChecker($loginLog, $this->ipCounter, $this->ipRangeCounter, $this->ipCountryCounter, $this->usernameCounter);

		$this->assertTrue($velocityCheckerCaptchaActive->isCaptchaActive());
	}

	/**
	 * Tests that failed login attempts = IP limit when the IP country and registration country are different.
	 */
	public function testFailedLoginWithDifferentRegistrationCountry()
	{
		$user     = new User(self::TEST_USERNAME, self::TEST_FOREIGN_REG_COUNTRY);
		$loginLog = new LoginLog($this->ip, $user, self::TEST_LOGIN_RESULT_FAILED);

		$velocityCheckerCaptchaInactive = new VelocityChecker($loginLog, $this->ipCounter, $this->ipRangeCounter, $this->ipCountryCounter, $this->usernameCounter);

		$this->assertFalse($velocityCheckerCaptchaInactive->isCaptchaActive());

		$this->counterDao->createLogEntry(Counter::TYPE_IP, self::TEST_IP_ADDRESS, 1);

		$velocityCheckerCaptchaActive = new VelocityChecker($loginLog, $this->ipCounter, $this->ipRangeCounter, $this->ipCountryCounter, $this->usernameCounter);

		$this->assertTrue($velocityCheckerCaptchaActive->isCaptchaActive());
	}

}
