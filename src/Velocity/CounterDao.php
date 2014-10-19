<?php

namespace Kata\Velocity;

/**
 * Counter DAO.
 */
class CounterDao
{
	/** Time limits */
	const CUMULATIVE_TIME = 300;
	const COUNTER_TTL     = 3600;

	/** SQLite3 database connection. */
	private $dbConnection = null;

	/**
	 * Sets DB connection.
	 *
	 * @param SQLite3 $dbConnection
	 *
	 * @return void
	 */
	public function __construct(\SQLite3 $dbConnection)
	{
		$this->dbConnection = $dbConnection;

		// $this->createTable();
	}

	/**
	 * Creates counter table.
	 *
	 * @return void
	 */
	public function createTable()
	{
		$this->dropTable();

		$this->dbConnection->exec("
			CREATE TABLE `counter` (
				`type` varchar(15) NOT NULL,
				`measure` varchar(16) NOT NULL,
				`counter` int(11),
				`time` timestamp,
				PRIMARY KEY(`type`, `measure`, `time`)
			)"
		);
	}

	/**
	 * Returns counter.
	 *
	 * @param string $type
	 * @param string $measure
	 *
	 * @return int
	 */
	public function getCounter($type, $measure)
	{
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
		$statement->bindValue(':lowerTimestampLimit', time() - self::COUNTER_TTL, SQLITE3_INTEGER);

		$result = $statement->execute();

		$row = $result->fetchArray(SQLITE3_ASSOC);

		return !empty($row['counter']) ? $row['counter'] : 0;
	}

	/**
	 * Creates log entry.
	 *
	 * @param string $type
	 * @param string $measure
	 * @param int    $counter
	 * @param int    $unixTimestamp
	 *
	 * @return void
	 */
	public function createLogEntry($type, $measure, $counter = 0, $unixTimestamp = null)
	{
		$cumulatedCounter = $this->getCumulatedCounter($type, $measure);

		if ($cumulatedCounter > 0)
		{
			$this->updateLogEntry($type, $measure, $counter + $cumulatedCounter);
		}
		else
		{
			$this->insertLogEntry($type, $measure, $counter, $unixTimestamp);
		}
	}

	/**
	 * Returns cumulated counter.
	 *
	 * @param string $type
	 * @param string $measure
	 *
	 * @return int
	 */
	private function getCumulatedCounter($type, $measure)
	{
		$statement = $this->dbConnection->prepare("
			SELECT
				`counter`
			FROM
				`counter`
			WHERE
				`type` = :type
				AND
				`measure` = :measure
				AND
				`time` >= :lowerTimestampLimit
			ORDER BY
				`time` DESC
			LIMIT
				1"
		);

		$statement->bindValue(':type', $type);
		$statement->bindValue(':measure', $measure);
		$statement->bindValue(':lowerTimestampLimit', time() - self::CUMULATIVE_TIME, SQLITE3_INTEGER);

		$result = $statement->execute();

		$counter = 0;

		if ($result)
		{
			$row     = $result->fetchArray(SQLITE3_ASSOC);
			$counter = !empty($row['counter']) ? $row['counter'] : 0;
		}

		return $counter;
	}

	/**
	 * Insertss log entry.
	 *
	 * @param string $type
	 * @param string $measure
	 * @param int    $counter
	 * @param int    $unixTimestamp
	 *
	 * @return void
	 */
	public function insertLogEntry($type, $measure, $counter = 0, $unixTimestamp = null)
	{
		if (empty($unixTimestamp))
		{
			$unixTimestamp = time();
		}

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

		$statement->bindValue(':type', $type);
		$statement->bindValue(':measure', $measure);
		$statement->bindValue(':counter', $counter, SQLITE3_INTEGER);
		$statement->bindValue(':time', $unixTimestamp, SQLITE3_INTEGER);

		$statement->execute();
	}

	/**
	 * Updates log entry.
	 *
	 * @param string $type
	 * @param string $measure
	 * @param int    $counter
	 *
	 * @return void
	 */
	public function updateLogEntry($type, $measure, $counter)
	{
		$statement = $this->dbConnection->prepare("
			UPDATE
				`counter`
			SET
				`counter` = :counter
			WHERE
				`type` = :type
				AND
				`measure` = :measure
				AND
				`time` >= :lowerTimestampLimit"
		);

		$statement->bindValue(':counter', $counter, SQLITE3_INTEGER);
		$statement->bindValue(':type', $type);
		$statement->bindValue(':measure', $measure);
		$statement->bindValue(':lowerTimestampLimit', time() - self::CUMULATIVE_TIME, SQLITE3_INTEGER);

		$statement->execute();
	}

	/**
	 * Resets a counter.
	 *
	 * @param string $type
	 * @param string $measure
	 *
	 * return SQLite3Result
	 */
	public function deleteLogEntry($type, $measure)
	{
		$statement = $this->dbConnection->prepare("
			DELETE FROM
				`counter`
			WHERE
				`type` = :type
				AND
				`measure` = :measure"
		);

		$statement->bindValue(':type', $type);
		$statement->bindValue(':measure', $measure);

		$statement->execute();
	}

	/**
	 * Drops counter table.
	 *
	 * @return void
	 */
	public function dropTable()
	{
		$this->dbConnection->exec("DROP TABLE IF EXISTS `counter`");
	}

	/**
	 * Destructor.
	 *
	 * @return void
	 */
	public function __destruct()
	{
		// $this->dropTable();
	}

}
