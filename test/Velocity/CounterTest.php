<?php

namespace Kata\Test\Velocity;

use Kata\Velocity\Counter;
use Kata\Velocity\CounterDao;
use Kata\Velocity\VelocityChecker;

/**
 * TODO LIST:
 *  - Exceptions			[ok]
 *  - Getters				[ok]
 *  - Methods				[ok]
 *  - counterDao mocking    [ok]
 */
class CounterTest extends \PHPUnit_Framework_TestCase
{
	const TEST_DATABASE_PATH = 'test/Velocity/velocityCheckerTest.db';

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
	 * Tests that the type is empty.
	 *
     * @expectedException Kata\Velocity\CounterException
	 * @expectedExceptionCode 601
     */
    public function testEmptyTypeException()
    {
		$dbConnection = new \SQLite3(self::TEST_DATABASE_PATH);
		$counterDao   = $this->getMock('\Kata\Velocity\CounterDao', array(), array($dbConnection));

		new Counter($counterDao, self::TEST_EMPTY_TYPE, self::TEST_IP_ADDRESS, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP);
    }

	/**
	 * Tests that the type is invalid.
	 *
     * @expectedException Kata\Velocity\CounterException
	 * @expectedExceptionCode 602
     */
    public function testInvalidTypeException()
    {
		$dbConnection = new \SQLite3(self::TEST_DATABASE_PATH);
		$counterDao   = $this->getMock('\Kata\Velocity\CounterDao', array(), array($dbConnection));

		new Counter($counterDao, self::TEST_INVALID_TYPE, self::TEST_IP_ADDRESS, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP);
    }

	/**
	 * Tests that the measure is empty.
	 *
     * @expectedException Kata\Velocity\CounterException
	 * @expectedExceptionCode 603
     */
    public function testEmptyMeasureException()
    {
		$dbConnection = new \SQLite3(self::TEST_DATABASE_PATH);
		$counterDao   = $this->getMock('\Kata\Velocity\CounterDao', array(), array($dbConnection));

		new Counter($counterDao, Counter::TYPE_IP, self::TEST_EMPTY_MEASURE, VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP);
    }

	/**
	 * Tests the counter.
	 */
	public function testGetCounter()
	{
		$dbConnection = new \SQLite3(self::TEST_DATABASE_PATH);

		$counterDao = $this->getMock('\Kata\Velocity\CounterDao', array('getCounter'), array($dbConnection));
		$counterDao->expects($this->any())
				->method('getCounter')
				->will($this->onConsecutiveCalls(0, 3, 5));

		$counterWithoutEntry   = new Counter($counterDao, Counter::TYPE_IP, self::TEST_IP_ADDRESS);
		$this->assertEquals(0, $counterWithoutEntry->getCounter());

		$counterWithTwoEntries = new Counter($counterDao, Counter::TYPE_IP, self::TEST_IP_ADDRESS);
		$this->assertEquals(3, $counterWithTwoEntries->getCounter());

		$counterWithOldEntry   = new Counter($counterDao, Counter::TYPE_IP, self::TEST_IP_ADDRESS);
		$this->assertEquals(5, $counterWithOldEntry->getCounter());
	}

	/**
	 * Tests that the counter sets to the upper limit.
	 */
	public function testSetCounter()
	{
		$dbConnection = new \SQLite3(self::TEST_DATABASE_PATH);

		$counterDao = $this->getMock(
				'\Kata\Velocity\CounterDao',
				array('deleteLogEntry', 'insertLogEntry', 'getCounter'),
				array($dbConnection)
		);
		$counterDao->expects($this->any())
				->method('deleteLogEntry')
				->will($this->returnValue(true));
		$counterDao->expects($this->any())
				->method('insertLogEntry')
				->will($this->returnValue(true));
		$counterDao->expects($this->any())
				->method('getCounter')
				->will($this->returnValue(VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP));

		$counter = new Counter($counterDao, Counter::TYPE_IP, self::TEST_IP_ADDRESS);

		$counter->setCounter(VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP);

		$this->assertEquals(VelocityChecker::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP, $counter->getCounter());
	}

	/**
	 * Tests the reset method.
	 */
	public function testReset()
	{
		$dbConnection = new \SQLite3(self::TEST_DATABASE_PATH);

		$counterDao = $this->getMock(
				'\Kata\Velocity\CounterDao',
				array('deleteLogEntry', 'getCounter'),
				array($dbConnection)
		);
		$counterDao->expects($this->any())
				->method('deleteLogEntry')
				->will($this->returnValue(true));
		$counterDao->expects($this->any())
				->method('getCounter')
				->will($this->returnValue(0));

		$counter = new Counter($counterDao, Counter::TYPE_IP, self::TEST_IP_ADDRESS);

		$counter->reset();

		$this->assertEquals(0, $counter->getCounter());
	}

}
