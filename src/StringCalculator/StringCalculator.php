<?php

namespace Kata\StringCalculator;

/**
 * String calculator class.
 */
class StringCalculator
{
	/** Parts of delimiter */
	const DEFAULT_DELIMITER = ',';
	const DELIMITER_PREFIX  = '//';

	/** Upper limit to ignore numbers */
	const IGNORE_UPPER_LIMIT = 1000;

	/**
	 * Part of numbers.
	 *
	 * @var string
	 */
	private $numbersString = '';

	/**
	 * Formatted, checked and escaped numbers.
	 *
	 * @var array
	 */
	private $numbers = array();

	/**
	 * Part of delimiters.
	 *
	 * @var string
	 */
	private $delimitersString = '';

	/**
	 * Formatted, checked and escaped delimiters.
	 *
	 * @var array
	 */
	private $delimiters = array();

	/**
	 * Adds the numbers from string.
	 *
	 * @param string $numbers
	 *
	 * @return int
	 *
	 * @throws InvalidArgumentException|InvalidIntegerException|NegativeNumberException
	 */
	public function add($numbers)
	{
		$this->validateInput($numbers);

		$this->init($numbers);

		return array_sum($this->numbers);
	}

	/**
	 * Checks that the input is string.
	 *
	 * @param string $numbers
	 *
	 * @return void
	 *
	 * @throws InvalidArgumentException
	 */
	private function validateInput($numbers)
	{
		if (is_string($numbers) !== true)
		{
			throw new InvalidArgumentException('The "numbers" is not a string.');
		}
		if (
			strpos($numbers, self::DELIMITER_PREFIX) === 0
			&& strpos($numbers, "\n") === false
		) {
			throw new InvalidArgumentException('The "numbers" does not contain new line character.');
		}
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
		list($this->delimitersString, $this->numbersString) = $this->splitNumbers($numbers);

		$this->setDelimiters();
		$this->setNumbers();

		$this->validateNumbers();
		$this->checkNegativeNumbers();

		$this->ignoreBigNumbers();

		return;
	}

	/**
	 * Splits numbers string.
	 *
	 * @param string $numbers
	 *
	 * @return array	Delimiters definition and numbers in string.
	 */
	private function splitNumbers($numbers)
	{
		$firstNewLineCharPosition = 0;

		if (strpos($numbers, self::DELIMITER_PREFIX) === 0)
		{
			$firstNewLineCharPosition = strpos($numbers, "\n");
		}

		return array(
			($firstNewLineCharPosition > 0 ? substr($numbers, strlen(self::DELIMITER_PREFIX), $firstNewLineCharPosition) : ''),
			($firstNewLineCharPosition > 0 ? substr($numbers, $firstNewLineCharPosition + 1) : $numbers),
		);
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
			if (empty($number))
			{
				continue;
			}

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
			throw new NegativeNumberException('The "numbers" contains negative numbers [' . implode(', ', $negativeNumbers) . ']');
		}

		return;
	}

	/**
	 * Ignores numbers greater than limit.
	 *
	 * @param int $limit
	 *
	 * @return void
	 */
	private function ignoreBigNumbers($limit = self::IGNORE_UPPER_LIMIT)
	{
		foreach ($this->numbers as $i => $number)
		{
			if ((int)$number > $limit)
			{
				unset($this->numbers[$i]);
			}
		}

		return;
	}

	/**
	 * Returns delimtier.
	 *
	 * @return void
	 *
	 * @throws InvalidArgumentException
	 */
	private function setDelimiters()
	{
		if (!empty($this->delimitersString))
		{
			$delimiters = $this->parseSpecialDelimiterString();
		}
		else
		{
			$delimiters = array(self::DEFAULT_DELIMITER);
		}

		// Adds the new line character to delimiters because of the compatibility.
		$delimiters[] = "\n";

		$this->delimiters = $delimiters;

		$this->escapeDelimiters();

		return;
	}

	/**
	 * Parses special delimiter part of numbers string.
	 *
	 * @return array
	 *
	 * @throws InvalidArgumentException
	 */
	private function parseSpecialDelimiterString()
	{
		$matches = array();
		if (!preg_match_all('/\[(?P<delimiter>[^\]]+)\]/', $this->delimitersString, $matches))
		{
			throw new InvalidArgumentException('The delimiter definition has syntax error.');
		}

		return $matches['delimiter'];
	}

	/**
	 * Escapes (urldecode, preg_quote) delimiters.
	 *
	 * @return void
	 */
	private function escapeDelimiters()
	{
		foreach ($this->delimiters as $i => $delimiter)
		{
			$urlDecodedDelimiter  = urldecode($delimiter);
			$this->delimiters[$i] = preg_quote($urlDecodedDelimiter, '/');
		}

		return;
	}

	/**
	 * Returns cleaned numbers.
	 *
	 * @return void
	 */
	private function setNumbers()
	{
		$splitRegex = '/(' . implode('|', $this->delimiters) . ')/';
		if (preg_match($splitRegex, $this->numbersString))
		{
			$this->numbers = preg_split($splitRegex, $this->numbersString, -1, PREG_SPLIT_NO_EMPTY);
		}
		elseif (!empty($this->numbersString))
		{
			$this->numbers = array($this->numbersString);
		}

		return;
	}

}
