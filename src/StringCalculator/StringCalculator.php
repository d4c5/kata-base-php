<?php

namespace Kata\StringCalculator;

/**
 * String calculator class.
 */
class StringCalculator
{
	/**
	 * Formatted and checked numbers.
	 *
	 * @var array
	 */
	private $integers = array();

	/**
	 * Constructor.
	 *
	 * @param string $numbers
	 *
	 * @return void
	 */
	public function __construct($numbers)
	{
		$this->init($numbers);

		return;
	}

	/**
	 * Initializes integers array.
	 *
	 * @param string $numbers
	 *
	 * @return void
	 *
	 * @throws InvalidIntegerException
	 */
	private function init($numbers)
	{
		$integers = preg_split('/[\r\n,]/', $numbers);

		foreach ($integers as $integer)
		{
			if (empty($integer))
			{
				$integer = 0;
			}

			$trimmedInteger = trim($integer);
			if (!is_numeric($trimmedInteger))
			{
				throw new InvalidIntegerException('The given number is not integer [' . $trimmedInteger . ']');
			}

			$this->integers[] = $trimmedInteger;
		}

		return;
	}

	/**
	 * Adds the numbers in string.
	 *
	 * @param string $numbers
	 *
	 * @return int
	 */
	public function add()
	{
		return array_sum($this->integers);
	}

}
