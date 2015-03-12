<?php

/**
 * StringToArray class.
 *
 * @package Kata
 * @subpackage StringToArray
 */

namespace Kata\StringToArray;

/**
 * Converts the given string to an array.
 */
class StringToArray
{
	/**
	 * Returns an array by given string.
	 *
	 * @param string $string
	 *
	 * @return array
	 *
	 * @throws \Exception
	 */
	public function getArrayByString($string)
	{
		if (is_string($string) !== true)
		{
			throw new \Exception(__METHOD__ . ' - The given input is not a string [string: ' . $string . ']');
		}

		return explode(',', $string);
	}

}
