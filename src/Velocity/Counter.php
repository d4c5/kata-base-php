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
	 * @param CounterDao $counterDao   SQLite database connection.
	 * @param string     $type         The type of the counter.
	 * @param string     $measure      IP address, IP range, country or username.
	 * @param int        $limit        The upper limit of counter.
	 *
	 * @return void
	 *
	 * @throws CounterException
	 */
	final public function __construct(CounterDao $counterDao, $type, $measure)
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

		$this->type    = $type;
		$this->measure = $measure;

		$this->counterDao = $counterDao;
	}

	/**
	 * Returns the counter.
	 *
	 * @return int
	 */
	public function getCounter()
	{
		return $this->counterDao->getCounter($this->type, $this->measure);
	}

	/**
	 * Sets the counter.
	 *
	 * @param int $counter
	 *
	 * @return void
	 */
	public function setCounter($counter)
	{
		$this->counterDao->deleteLogEntry($this->type, $this->measure);
		$this->counterDao->insertLogEntry($this->type, $this->measure, $counter);

		$this->counter = $counter;
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
