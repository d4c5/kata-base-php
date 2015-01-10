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
	 * @param Numbers $numbers
	 *
	 * @return int
	 */
	public function add(Numbers $numbers)
	{
		return array_sum($numbers->getNumbers());
	}

}
