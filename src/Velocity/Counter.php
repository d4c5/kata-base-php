<?php

namespace Kata\Velocity;

/**
 * Counter object.
 */
class Counter
{
	/** Counter types. */
	const TYPE_IP         = 'ip';
	const TYPE_IP_RANGE   = 'ip_range';
	const TYPE_IP_COUNTRY = 'ip_country';
	const TYPE_USERNAME   = 'username';

	/**
	 * The type of the counter.
	 *
	 * @var string
	 */
	protected $type    = '';

	/**
	 * The measure.
	 *
	 * @var string
	 */
	protected $measure = null;

	/**
	 * The upper limit.
	 *
	 * @var int
	 */
	protected $limit   = null;

	/**
	 * Counter.
	 *
	 * @var int
	 */
	protected $counter = 0;

	/**
	 * Counter DAO.
	 *
	 * @var SQLite3
	 */
	protected $counterDao = null;

	/**
	 * Sets the value.
	 *
	 * @param SQLite3 $dbConnection   SQLite database connection.
	 * @param string  $type           The type of the counter.
	 * @param string  $measure        IP address, IP range, country or username.
	 * @param int     $limit          The upper limit of counter.
	 *
	 * @return void
	 *
	 * @throws CounterException
	 */
	final public function __construct(\SQLite3 $dbConnection, $type, $measure, $limit)
	{
		if (empty($type))
		{
			throw new CounterException(CounterException::EMPTY_TYPE);
		}
		if ($this->isTypeValid($type) !== true)
		{
			throw new CounterException(CounterException::INVALID_TYPE);
		}
		if (empty($measure))
		{
			throw new CounterException(CounterException::EMPTY_MEASURE);
		}
		if (empty($limit))
		{
			throw new CounterException(CounterException::EMPTY_LIMIT);
		}
		if (is_numeric($limit) !== true)
		{
			throw new CounterException(CounterException::INVALID_LIMIT);
		}

		$this->type    = $type;
		$this->measure = $measure;
		$this->limit   = $limit;

		$this->counterDao = new CounterDao($dbConnection);

		$this->init();
	}

	/**
	 * Returns the counter.
	 *
	 * @return int
	 */
	public function getCounter()
	{
		return $this->counter;
	}

	/**
	 * Returns the upper limit.
	 *
	 * @return int
	 */
	public function getLimit()
	{
		return $this->limit;
	}

	/**
	 * Sets the counter to limit.
	 *
	 * @return void
	 */
	public function setCounterToUpperLimit()
	{
		$this->counterDao->deleteLogEntry($this->type, $this->measure);
		$this->counterDao->insertLogEntry($this->type, $this->measure, $this->limit);

		$this->counter = $this->limit;
	}

	/**
	 * Is given type valid?
	 *
	 * @param string $type
	 *
	 * @return boolean
	 */
	private function isTypeValid($type)
	{
		$isValid = false;

		$validTypes = array(
			self::TYPE_IP,
			self::TYPE_IP_RANGE,
			self::TYPE_IP_COUNTRY,
			self::TYPE_USERNAME,
		);

		if (in_array($type, $validTypes, true))
		{
			$isValid = true;
		}

		return $isValid;
	}

	/**
	 * Sets the counter.
	 *
	 * @return void
	 */
	private function init()
	{
		$this->counter = $this->counterDao->getCounter($this->type, $this->measure);
	}

	/**
	 * Deletes the counter of the IP address.
	 *
	 * @return void
	 */
	public function reset()
	{
		$this->counter = 0;

		$this->counterDao->deleteLogEntry($this->type, $this->measure);
	}

}
