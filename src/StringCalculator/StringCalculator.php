<?php

namespace Kata\StringCalculator;

/**
 * String calculator class.
 */
class StringCalculator
{
	/**
	 * Adds the numbers in string.
	 *
	 * @param string $numbers
	 *
	 * @return int
	 */
	public function add($numbers)
	{
		$integers = explode(',', $numbers);

		return array_sum($integers);
	}

}
