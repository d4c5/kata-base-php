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
	const LABEL_PREFIX = '#useFirstLineAsLabels';

	/**
	 * Returns an array by given string.
	 *
	 * @param string $string
	 *
	 * @return Result
	 *
	 * @throws \Exception
	 */
	public function getArrayByString($string)
	{
		if (is_string($string) !== true)
		{
			throw new \Exception(__METHOD__ . ' - The given input is not a string [string: ' . $string . ']');
		}

		if (
			!empty($string)
			&& strpos($string, "\n") !== false
			&& strpos($string, self::LABEL_PREFIX) === 0
		) {
			$result = $this->getArrayByMultiLineStringWithLabels($string);
		}
		elseif (
			!empty($string)
			&& strpos($string, "\n") !== false
		) {
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
	 * @return Result
	 */
	private function getArrayByOneLineString($string)
	{
		$result = explode(',', $string);

		$resultObj = new Result($result);

		return $resultObj;
	}

	/**
	 * Parses multi-line string to array.
	 *
	 * @param string $string
	 *
	 * @return Result
	 */
	private function getArrayByMultiLineString($string)
	{
		$result = array();

		$stringParts = explode("\n", $string);

		foreach ($stringParts as $stringPart)
		{
			$resultObj = $this->getArrayByOneLineString($stringPart);
			$result[]  = $resultObj->toArray();
		}

		$resultObj = new Result($result);

		return $resultObj;
	}

	/**
	 * Returns labels if exist in string.
	 *
	 * @param string $string
	 *
	 * @return array
	 */
	private function getLabelsByMultiLineString($string)
	{
		$labels = array();

		if (strpos($string, self::LABEL_PREFIX) === 0)
		{
			$stringParts = explode("\n", $string);
			$labelsObj   = $this->getArrayByOneLineString($stringParts[1]);
			$labels      = $labelsObj->toArray();
		}

		return $labels;
	}

	/**
	 * Parses multi-line string to array with labels.
	 *
	 * @param string $string
	 *
	 * @return Result
	 */
	private function getArrayByMultiLineStringWithLabels($string)
	{
		$labels = $this->getLabelsByMultiLineString($string);

		$stringParts = explode("\n", $string);

		unset($stringParts[0], $stringParts[1]);

		$dataString = implode("\n", $stringParts);

		$dataObj = $this->getArrayByMultiLineString($dataString);
		$data    = $dataObj->toArray();

		$resultObj = new Result($data, $labels);

		return $resultObj;
	}

}
