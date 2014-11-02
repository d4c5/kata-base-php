<?php

namespace Kata\Test\Velocity;

use Kata\Velocity\Counter;
use Kata\Velocity\CounterDao;

/**
 * TODO LIST:
 *  - methods				[ok]
 *  - SQL mocking
 */
class CounterDaoTest extends \PHPUnit_Framework_TestCase
{
	const TEST_DATABASE_PATH = 'test/Velocity/velocityCheckerTest.db';

	const TEST_IP_ADDRESS = '192.168.4.1';
	const TEST_IP_RANGE   = 'OfficeLAN';
	const TEST_IP_COUNTRY = 'LU';

	const TEST_USERNAME    = 'test_user';
	const TEST_REG_COUNTRY = 'LU';

	/**
	 * Database connection.
	 *
	 * @param SQLite3 $dbConnection
	 */
	private $dbConnection = null;

	/**
	 * Sets the DB connection.
	 *
	 * @return void
	 */
	public function setUp()
	{
		$this->dbConnection = new \SQLite3(self::TEST_DATABASE_PATH);

		$statement = $this->dbConnection->prepare("DELETE FROM `counter`");
		$statement->execute();
	}

	/**
	 * Tests getCounter method.
	 *
	 * @param string $type
	 * @param string $measure
	 * @param int    $counter
	 * @param int    $unixTimestamp
	 *
	 * @return void
	 *
	 * @dataProvider providerDataOfCounters
	 */
	public function testGetCounter($type, $measure, $counter, $unixTimestamp)
	{
		$this->insertCounter($type, $measure, $counter, $unixTimestamp);

		$counterDao = new CounterDao($this->dbConnection);

		$this->assertEquals($counter, $counterDao->getCounter($type, $measure));
	}

	/**
	 * Returns type, measure, counter and unixTimestamp.
	 *
	 * @return array
	 */
	public function providerDataOfCounters()
	{
		return array(
			array(Counter::TYPE_IP, self::TEST_IP_ADDRESS, 2, time()),
			array(Counter::TYPE_IP_RANGE, self::TEST_IP_RANGE, 102, time()),
			array(Counter::TYPE_IP_COUNTRY, self::TEST_IP_COUNTRY, 302, time()),
			array(Counter::TYPE_USERNAME, self::TEST_USERNAME, 2, time()),
		);
	}

	/**
	 * Tests log entry creating.
	 *
	 * @param int    $expectedCounter
	 * @param string $type
	 * @param string $measure
	 * @param int    $counter
	 * @param int    $unixTimestamp
	 * @param int    $cumulatedCounter
	 *
	 * @return void
	 *
	 * @dataProvider providerDataOfCountersToCreating
	 */
	public function testCreateLogEntry($expectedCounter, $type, $measure, $counter, $unixTimestamp, $cumulatedCounter = null)
	{
		if (!empty($cumulatedCounter))
		{
			$this->insertCounter($type, $measure, $cumulatedCounter, time());
		}

		$counterDao = new CounterDao($this->dbConnection);
		$counterDao->createLogEntry($type, $measure, $counter, $unixTimestamp);

		$row = $this->getCounter($type, $measure);

		$this->assertEquals($expectedCounter, $row['counter']);
	}

	/**
	 * Returns expected counter, type, measure, counter and unixTimestamp.
	 *
	 * @return array
	 */
	public function providerDataOfCountersToCreating()
	{
		return array(
			array(1, Counter::TYPE_IP, self::TEST_IP_ADDRESS, 1, time()),
			array(0, Counter::TYPE_IP, self::TEST_IP_ADDRESS, 1, time() - CounterDao::COUNTER_TTL - 1),
			array(1, Counter::TYPE_IP_RANGE, self::TEST_IP_RANGE, 1, time()),
			array(1, Counter::TYPE_IP_COUNTRY, self::TEST_IP_COUNTRY, 1, time()),
			array(1, Counter::TYPE_USERNAME, self::TEST_USERNAME, 1, time()),
			// With cumulated counter
			array(3, Counter::TYPE_USERNAME, self::TEST_USERNAME, 1, time(), 2),
		);
	}

	/**
	 * Tests inserting.
	 *
	 * @param string $type
	 * @param string $measure
	 * @param int    $counter
	 * @param int    $unixTimestamp
	 *
	 * @return void
	 *
	 * @dataProvider providerDataOfCountersToInserting
	 */
	public function testInsertLogEntry($type, $measure, $counter, $unixTimestamp)
	{
		$counterDao = new CounterDao($this->dbConnection);
		$counterDao->insertLogEntry($type, $measure, $counter, $unixTimestamp);

		$row = $this->getCounter($type, $measure);

		$this->assertEquals($counter, $row['counter']);
	}

	/**
	 * Returns type, measure, counter and timestamp.
	 *
	 * @return array
	 */
	public function providerDataOfCountersToInserting()
	{
		return array(
			array(Counter::TYPE_IP, self::TEST_IP_ADDRESS, 1, time()),
			array(Counter::TYPE_IP_RANGE, self::TEST_IP_RANGE, 1, null),
			array(Counter::TYPE_IP_COUNTRY, self::TEST_IP_COUNTRY, 1, time()),
			array(Counter::TYPE_USERNAME, self::TEST_USERNAME, 1, time()),
		);
	}

	/**
	 * Tests updating.
	 *
	 * @param string $type
	 * @param string $measure
	 * @param int    $counter
	 *
	 * @return void
	 *
	 * @dataProvider providerDataOfCounterToUpdating
	 */
	public function testUpdateLogEntry($type, $measure, $counter)
	{
		$this->insertCounter($type, $measure, 1, time());

		$counterDao = new CounterDao($this->dbConnection);
		$counterDao->updateLogEntry($type, $measure, $counter);

		$row = $this->getCounter($type, $measure);

		$this->assertEquals($counter, $row['counter']);
	}

	/**
	 * Returns type, measure and counter.
	 *
	 * @return array
	 */
	public function providerDataOfCounterToUpdating()
	{
		return array(
			array(Counter::TYPE_IP, self::TEST_IP_ADDRESS, 4),
			array(Counter::TYPE_IP_RANGE, self::TEST_IP_RANGE, 4),
			array(Counter::TYPE_IP_COUNTRY, self::TEST_IP_COUNTRY, 4),
			array(Counter::TYPE_USERNAME, self::TEST_USERNAME, 4),
		);
	}

	/**
	 * Tests deleting.
	 *
	 * @param string $type
	 * @param string $measure
	 *
	 * @return void
	 *
	 * @dataProvider providerDataOfCounterToDeleting
	 */
	public function testDeleteLogEntry($type, $measure)
	{
		$this->insertCounter($type, $measure, 1, time());

		$counterDao = new CounterDao($this->dbConnection);
		$counterDao->deleteLogEntry($type, $measure);

		$row = $this->getCounter($type, $measure);

		$this->assertEquals(0, $row['counter']);
	}

	/**
	 * Returns type ans measure.
	 *
	 * @return array
	 */
	public function providerDataOfCounterToDeleting()
	{
		return array(
			array(Counter::TYPE_IP, self::TEST_IP_ADDRESS),
			array(Counter::TYPE_IP_RANGE, self::TEST_IP_RANGE),
			array(Counter::TYPE_IP_COUNTRY, self::TEST_IP_COUNTRY),
			array(Counter::TYPE_USERNAME, self::TEST_USERNAME),
		);
	}

	/**
	 * Initializes the counter.
	 *
	 * @param string $type
	 * @param string $measure
	 * @param int    $counter
	 * @param int    $unixTimestamp
	 *
	 * @return void
	 */
	private function insertCounter($type, $measure, $counter, $unixTimestamp)
	{
		$statement = $this->dbConnection->prepare("
			INSERT INTO
				`counter`
			(
				`type`,
				`measure`,
				`counter`,
				`time`
			)
			VALUES
			(
				:type,
				:measure,
				:counter,
				:time
			)"
		);

		$statement->bindValue(':type',    $type);
		$statement->bindValue(':measure', $measure);
		$statement->bindValue(':counter', $counter, SQLITE3_INTEGER);
		$statement->bindValue(':time',    $unixTimestamp, SQLITE3_INTEGER);

		$statement->execute();
	}

	/**
	 * Returns counter.
	 *
	 * @return array
	 */
	private function getCounter($type, $measure)
	{
		$row = array();

		$statement = $this->dbConnection->prepare("
			SELECT
				SUM(`counter`) AS counter
			FROM
				`counter`
			WHERE
				`type` = :type
				AND
				`measure` = :measure
				AND
				`time` >= :lowerTimestampLimit"
		);

		$statement->bindValue(':type', $type);
		$statement->bindValue(':measure', $measure);
		$statement->bindValue(':lowerTimestampLimit', time() - CounterDao::COUNTER_TTL, SQLITE3_INTEGER);

		$result = $statement->execute();
		if ($result !== false)
		{
			$row = $result->fetchArray(SQLITE3_ASSOC);
		}

		return $row;
	}

}
