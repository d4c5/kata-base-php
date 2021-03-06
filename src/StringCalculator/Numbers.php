<?php

namespace Kata\StringCalculator;

/**
 * Numbers handler.
 */
class Numbers
{
	/** Upper limit to ignore numbers */
	const IGNORE_UPPER_LIMIT = 1000;

	/**
	 * Formatted, checked and escaped numbers.
	 *
	 * @var array
	 */
	private $numbers = array();

	/**
	 * Delimiters.
	 *
	 * @var Delimiters
	 */
	private $delimiters = null;

	/**
	 * Limit.
	 *
	 * @var int
	 */
	private $limit = self::IGNORE_UPPER_LIMIT;

	/**
	 * Validates, parses and escapes input.
	 *
	 * @param string     $numbers
	 * @param Delimiters $delimiters
	 * @param int        $limit
	 *
	 * @return void
	 *
	 * @throws InvalidArgumentException|InvalidIntegerException|NegativeNumberException
	 */
	public function __construct($numbers, Delimiters $delimiters, $limit = null)
	{
		if (is_string($numbers) !== true)
		{
			throw new InvalidArgumentException('The "numbers" is not a string.');
		}

		$numbersDefinition = $this->getNumbersDefinition($numbers);
		$this->delimiters  = $delimiters;

		if (!empty($limit))
		{
			$this->validateLimit($limit);
			$this->limit = $limit;
		}

		if (!empty($numbersDefinition))
		{
			$this->validate($numbersDefinition);
			$this->numbers = $this->parse($numbersDefinition);
		}

		$this->validateNumbers();
		$this->checkNegativeNumbers();
		$this->ignoreBigNumbers();

		return;
	}

	/**
	 * Returns all numbers.
	 *
	 * @return array
	 */
	public function getNumbers()
	{
		return $this->numbers;
	}

	/**
	 * Returns numbers definition by numbers.
	 *
	 * @param string $numbers
	 *
	 * @return string
	 *
	 * @throws InvalidArgumentException
	 */
	private function getNumbersDefinition($numbers)
	{
		$firstNewLineCharPosition = 0;

		if (strpos($numbers, Delimiters::DELIMITER_PREFIX) === 0)
		{
			$firstNewLineCharPosition = strpos($numbers, Delimiters::DELIMITER_NEW_LINE);
			if ($firstNewLineCharPosition === false)
			{
				throw new InvalidArgumentException('The new line character is required if delimiter part is set.');
			}
		}

		return ($firstNewLineCharPosition > 0 ? substr($numbers, $firstNewLineCharPosition + 1) : $numbers);
	}

	/**
	 * Validates limit.
	 *
	 * @param int $limit
	 *
	 * @return void
	 *
	 * @throws InvalidArgumentException
	 */
	private function validateLimit($limit)
	{
		if (!is_int($limit))
		{
			throw new InvalidArgumentException('The limit is invalid.');
		}

		return;
	}

	/**
	 * Validates numbers definition.
	 *
	 * @param string $numbersDefinition
	 *
	 * @return void
	 *
	 * @throws InvalidArgumentException
	 */
	private function validate($numbersDefinition)
	{
		if (
			!empty($numbersDefinition)
			&& !is_numeric($numbersDefinition)
			&& !preg_match('/(' . implode('|', $this->delimiters->getDelimiters()) . ')/', $numbersDefinition)
		) {
			throw new InvalidArgumentException('The "numbers" has syntax error.');
		}

		return;
	}

	/**
	 * Parses numbers definition.
	 *
	 * @param string $numbersDefinition
	 *
	 * @return array
	 */
	private function parse($numbersDefinition)
	{
		$numbers = array();

		$splitRegex = '/(' . implode('|', $this->delimiters->getDelimiters()) . ')/';
		if (preg_match($splitRegex, $numbersDefinition))
		{
			$numbers = preg_split($splitRegex, $numbersDefinition, -1, PREG_SPLIT_NO_EMPTY);
		}
		elseif (is_numeric($numbersDefinition))
		{
			$numbers = array($numbersDefinition);
		}

		return $numbers;
	}

	/**
	 * Validates numbers.
	 *
	 * @return void
	 *
	 * @throws InvalidNumberException
	 */
	private function validateNumbers()
	{
		foreach ($this->numbers as $number)
		{
			if (is_numeric($number) === false)
			{
				throw new InvalidNumberException('The part of the given numbers is not numeric [' . $number . ']');
			}
		}

		return;
	}

	/**
	 * Checks negative numbers.
	 *
	 * @return void
	 *
	 * @throws NegativeNumberException
	 */
	private function checkNegativeNumbers()
	{
		$negativeNumbers = array();

		foreach ($this->numbers as $number)
		{
			if ((int)$number < 0)
			{
				$negativeNumbers[] = $number;
			}
		}

		if (count($negativeNumbers))
		{
			throw new NegativeNumberException('The "numbers" contains negative numbers [' . implode(',', $negativeNumbers) . ']');
		}

		return;
	}

	/**
	 * Ignores numbers greater than limit.
	 *
	 * @return void
	 */
	private function ignoreBigNumbers()
	{
		foreach ($this->numbers as $i => $number)
		{
			if ((int)$number > $this->limit)
			{
				unset($this->numbers[$i]);
			}
		}

		return;
	}

}
