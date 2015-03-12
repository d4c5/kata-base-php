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

		if (!empty($string) && strpos($string, "\n") !== false)
		{
			$result = $this->getArrayByMultiLineString($string);
		}
		else
		{
			$result = $this->getArrayByOneLineString($string);
		}

		return $result;
	}

	/**
	 * Parses one-line string to array.
	 *
	 * @param string $string
	 *
	 * @return array
	 */
	private function getArrayByOneLineString($string)
	{
		return explode(',', $string);
	}

	/**
	 * Parses multi-line string to array.
	 *
	 * @param string $string
	 *
	 * @return array
	 */
	private function getArrayByMultiLineString($string)
	{
		$result = array();

		$stringParts = explode("\n", $string);

		foreach ($stringParts as $stringPart)
		{
			$result[] = $this->getArrayByOneLineString($stringPart);
		}

		return $result;
	}

}
