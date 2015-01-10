<?php

namespace Kata\StringCalculator;

/**
 * String calculator class.
 */
class StringCalculator
{
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

		list($delimitersString, $numbersString) = $this->splitNumbers($numbers);

		$delimitersObj = new Delimiters($delimitersString);
		$numbersObj    = new Numbers($numbersString, $delimitersObj);

		return $numbersObj->getSummary();
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

		if (strpos($numbers, Delimiters::DELIMITER_PREFIX) === 0)
		{
			$firstNewLineCharPosition = strpos($numbers, Delimiters::DELIMITER_NEW_LINE);
		}

		return array(
			($firstNewLineCharPosition > 0 ? substr($numbers, strlen(Delimiters::DELIMITER_PREFIX), $firstNewLineCharPosition) : ''),
			($firstNewLineCharPosition > 0 ? substr($numbers, $firstNewLineCharPosition + 1) : $numbers),
		);
	}

}
