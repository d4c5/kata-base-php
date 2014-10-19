<?php

namespace Kata\Test\Velocity;

use Kata\Velocity\Counter;
use Kata\Velocity\CounterDao;
use Kata\Velocity\VelocityChecker;

/**
 * TODO LIST:
 *  - Exceptions			[ok]
 *  - Getters				[ok]
 *  - Methods
 */
class CounterTest extends \PHPUnit_Framework_TestCase
{
	const TEST_IP_ADDRESS = '192.168.4.1';
	const TEST_IP_RANGE   = 'OfficeLAN';
	const TEST_IP_COUNTRY = 'LU';

	const TEST_USERNAME    = 'test_user';
	const TEST_REG_COUNTRY = 'LU';

	const TEST_EMPTY_TYPE    = null;
	const TEST_INVALID_TYPE  = 'asd-asd';
	const TEST_EMPTY_MEASURE = null;
	const TEST_EMPTY_LIMIT   = null;
	const TEST_INVALID_LIMIT = 'asd-asd';

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
	 * Sets the database connection and counter DAO.
	 *
	 * @return void
	 */
	public function setUp()
	{
		$this->dbConnection = new \SQLite3('velocityCheckerTest.db');
		$this->counterDao   = new CounterDao($this->dbConnection);

		$this->counterDao->createTable();
	}

	/**
	 * Tests that the type is empty.
	 *
     * @expectedException Kata\Velocity\CounterException
	 * @expectedExceptionCode 601
     */
    public function testEmptyTypeException()
    {
		new Counter($this->dbConnection, self::TEST_EMPTY_TYPE, self::TEST_IP_ADDRESS, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP);
    }

	/**
	 * Tests that the type is invalid.
	 *
     * @expectedException Kata\Velocity\CounterException
	 * @expectedExceptionCode 602
     */
    public function testInvalidTypeException()
    {
		new Counter($this->dbConnection, self::TEST_INVALID_TYPE, self::TEST_IP_ADDRESS, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP);
    }

	/**
	 * Tests that the measure is empty.
	 *
     * @expectedException Kata\Velocity\CounterException
	 * @expectedExceptionCode 603
     */
    public function testEmptyMeasureException()
    {
		new Counter($this->dbConnection, Counter::TYPE_IP, self::TEST_EMPTY_MEASURE, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP);
    }

	/**
	 * Tests that the limit is empty.
	 *
     * @expectedException Kata\Velocity\CounterException
	 * @expectedExceptionCode 604
     */
    public function testEmptyLimitException()
    {
		new Counter($this->dbConnection, Counter::TYPE_IP, self::TEST_IP_ADDRESS, self::TEST_EMPTY_LIMIT);
    }

	/**
	 * Tests that the limit is invalid.
	 *
     * @expectedException Kata\Velocity\CounterException
	 * @expectedExceptionCode 605
     */
    public function testInvalidLimitException()
    {
		new Counter($this->dbConnection, Counter::TYPE_IP, self::TEST_IP_ADDRESS, self::TEST_INVALID_LIMIT);
    }

	/**
	 * Tests the counter.
	 */
	public function testGetCounter()
	{
		$counterWithoutEntry = new Counter($this->dbConnection, Counter::TYPE_IP, self::TEST_IP_ADDRESS, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP);

		$this->assertEquals(0, $counterWithoutEntry->getCounter());

		$this->counterDao->createLogEntry(Counter::TYPE_IP, self::TEST_IP_ADDRESS, 1, time() - (CounterDao::CUMULATIVE_TIME * 2));
		$this->counterDao->createLogEntry(Counter::TYPE_IP, self::TEST_IP_ADDRESS, 2);

		$counterWithTwoEntries = new Counter($this->dbConnection, Counter::TYPE_IP, self::TEST_IP_ADDRESS, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP);
		$this->assertEquals(3, $counterWithTwoEntries->getCounter());

		$this->counterDao->insertLogEntry(Counter::TYPE_IP, self::TEST_IP_ADDRESS, 1, time() - (CounterDao::COUNTER_TTL * 2));
		$this->counterDao->createLogEntry(Counter::TYPE_IP, self::TEST_IP_ADDRESS, 2);

		$counterWithOldEntry = new Counter($this->dbConnection, Counter::TYPE_IP, self::TEST_IP_ADDRESS, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP);
		$this->assertEquals(5, $counterWithOldEntry->getCounter());
	}

	/**
	 * Tests the limit.
	 */
	public function testGetLimit()
	{
		$counter = new Counter($this->dbConnection, Counter::TYPE_IP, self::TEST_IP_ADDRESS, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP);

		$this->assertEquals(VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP, $counter->getLimit());
	}

	/**
	 * Tests that the counter sets to the upper limit.
	 */
	public function testSetCounterToUpperLimit()
	{
		$counter = new Counter($this->dbConnection, Counter::TYPE_IP, self::TEST_IP_ADDRESS, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP);

		$counter->setCounterToUpperLimit();

		$this->assertEquals(VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP, $counter->getCounter());
	}

	/**
	 * Tests the reset method.
	 */
	public function testReset()
	{
		$counter = new Counter($this->dbConnection, Counter::TYPE_IP, self::TEST_IP_ADDRESS, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP);

		$this->counterDao->createLogEntry(Counter::TYPE_IP, self::TEST_IP_ADDRESS, 2);

		$counter->reset();

		$this->assertEquals(0, $counter->getCounter());
	}

}
