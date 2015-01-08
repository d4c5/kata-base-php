<?php

namespace Kata\StringCalculator;

/**
 * String calculator class.
 */
class StringCalculator
{
	const DEFAULT_DELIMITER = ',';
	const DELIMITER_PREFIX  = '//';

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
	 * @throws InvalidIntegerException|NegativeNumberException
	 */
	private function init($numbers)
	{
		$delimiter      = $this->getDelimiter($numbers);
		$cleanedNumbers = $this->getCleanedNumbers($numbers);

		$integers = preg_split('/[\n'. preg_quote($delimiter) . ']/', $cleanedNumbers);

		$negativeNumbers = array();

		foreach ($integers as $integer)
		{
			if (empty($integer))
			{
				$integer = 0;
			}

			$trimmedInteger = trim($integer);
			if (!is_numeric($trimmedInteger))
			{
				throw new InvalidNumberException('The given number is not integer [' . $trimmedInteger . ']');
			}
			if ($trimmedInteger < 0)
			{
				$negativeNumbers[] = $trimmedInteger;
			}

			$this->integers[] = $trimmedInteger;
		}

		if (count($negativeNumbers))
		{
			throw new NegativeNumberException('The numbers contains negative numbers [' . implode(', ', $negativeNumbers) . ']');
		}

		return;
	}

	/**
	 * Returns delimtier.
	 *
	 * @param string $numbers
	 *
	 * @return string
	 */
	private function getDelimiter($numbers)
	{
		$delimiter = self::DEFAULT_DELIMITER;

		if (substr($numbers, 0, 2) === self::DELIMITER_PREFIX)
		{
			$numbersWithoutDelimiterPrefix = substr($numbers, 2);
			list($delimiter,)              = explode("\n", $numbersWithoutDelimiterPrefix, 2);
		}

		return urldecode($delimiter);
	}

	/**
	 * Returns cleaned numbers.
	 *
	 * @param string $numbers
	 *
	 * @return string
	 */
	private function getCleanedNumbers($numbers)
	{
		$cleanedNumbers = $numbers;

		if (substr($numbers, 0, 2) === self::DELIMITER_PREFIX)
		{
			// TODO: false-ra Exception!
			$firstNewLinePosition = strpos($numbers, "\n");
			$cleanedNumbers       = substr($numbers, $firstNewLinePosition);
		}

		return $cleanedNumbers;
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
