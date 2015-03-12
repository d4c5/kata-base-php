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
	const LABEL_PREFIX          = 'useFirstLineAsLabels';
	const COLUMN_DELIMITER_NAME = 'columnDelimiter';
	const LINE_DELIMITER_NAME   = 'lineDelimiter';

	/**
	 * Use labels.
	 *
	 * @var boolean
	 */
	private $useLabels = false;

	/**
	 * Column delimiter.
	 *
	 * @var string
	 */
	private $columnDelimiter = ',';

	/**
	 * Line delimiter.
	 *
	 * @var string
	 */
	private $lineDelimiter = "\n";

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
			&& strpos($string, '#') === 0
			&& strpos($string, "\n") !== false
		) {
			$this->setParseParametersByHeader($string);
			$string = substr($string, strpos($string, "\n") + 1);
		}

		if (
			!empty($string)
			&& $this->useLabels === true
			&& strpos($string, $this->lineDelimiter) !== false
		) {
			$result = $this->getArrayByMultiLineStringWithLabels($string);
		}
		elseif (
			!empty($string)
			&& $this->useLabels === false
			&& strpos($string, $this->lineDelimiter) !== false
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
	 * Parses header.
	 *
	 * @param string $string
	 *
	 * @return void
	 */
	private function setParseParametersByHeader($string)
	{
		$header = substr($string, 1, strpos($string, "\n") - 1);

		if ($header == self::LABEL_PREFIX)
		{
			$this->useLabels = true;
		}
		else
		{
			$matches = array();
			if (preg_match('/' . self::LABEL_PREFIX . '=(?P<useLabels>[01])/', $header, $matches))
			{
				$this->useLabels = $matches['useLabels'] == 1 ? true : false;
			}
			if (preg_match('/' . self::COLUMN_DELIMITER_NAME . '=(?P<columnDelimiter>.)/', $header, $matches))
			{
				$this->columnDelimiter = $matches['columnDelimiter'];
			}
			if (preg_match('/' . self::LINE_DELIMITER_NAME . '=(?P<lineDelimiter>.*)/', $header, $matches))
			{
				$this->lineDelimiter   = $matches['lineDelimiter'];
			}
		}

		return;
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
		$result = explode($this->columnDelimiter, $string);

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

		$stringParts = explode($this->lineDelimiter, $string);

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
			$stringParts = explode($this->lineDelimiter, $string);
			$labelsObj   = $this->getArrayByOneLineString($stringParts[0]);
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

		$stringParts = explode($this->lineDelimiter, $string);

		unset($stringParts[0]);

		$dataString = implode($this->lineDelimiter, $stringParts);

		$dataObj = $this->getArrayByMultiLineString($dataString);
		$data    = $dataObj->toArray();

		$resultObj = new Result($data, $labels);

		return $resultObj;
	}

}
