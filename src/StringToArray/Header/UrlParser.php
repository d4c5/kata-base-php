<?php

/**
 * Header parser class.
 *
 * @package StringToArray
 * @subpackage Header
 */

namespace Kata\StringToArray\Header;

use Kata\StringToArray\InvalidHeaderException;

/**
 * Parses the given string to header parameters.
 *
 * Accepted format (http query string url encoded):
 * #useFirstLineAsLabels=[0|1]&columnDelimiter=[.]&lineDelimiter=(.*)
 *
 * Example:
 * #useFirstLineAsLabels=1&columnDelimiter=,&lineDelimiter=%0A
 */
class UrlParser extends AbstractHeaderParser implements HeaderParserInterface
{
	const HEADER_STARTER = "#";

	const LABEL_PREFIX          = 'useFirstLineAsLabels';
	const COLUMN_DELIMITER_NAME = 'columnDelimiter';
	const LINE_DELIMITER_NAME   = 'lineDelimiter';

	/**
	 * Checks the header format.
	 *
	 * @param string $string
	 *
	 * @return boolean
	 *
	 * @throws \Kata\StringToArray\InvalidHeaderException
	 */
	protected function isValidHeader($string)
	{
		if (strpos($string, AbstractHeaderParser::HEADER_DELIMITER) === false)
		{
			throw new InvalidHeaderException(__METHOD__ . ' - No header delimiter in the given string [string: ' . $string . ']');
		}
		if (strpos($string, self::HEADER_STARTER) !== 0)
		{
			throw new InvalidHeaderException(__METHOD__ . ' - No header starter in the given string [starter: ' . self::HEADER_STARTER . ', '
								. 'string: ' . $string . ']');
		}

		// regex ?

		return true;
	}

	/**
	 * Sets parsing parameters.
	 *
	 * @param string $string
	 *
	 * @return void
	 */
	protected function setParsingParameters($string)
	{
		$headerLine = substr($string, 1, strpos($string, AbstractHeaderParser::HEADER_DELIMITER) - 1);

		$parts = array();
		parse_str($headerLine, $parts);

		if (isset($parts[self::LABEL_PREFIX]) && $parts[self::LABEL_PREFIX] == "1")
		{
			$this->useFirstLineAsLabels = true;
		}
		if (isset($parts[self::COLUMN_DELIMITER_NAME]))
		{
			$this->columnDelimiter = urldecode($parts[self::COLUMN_DELIMITER_NAME]);
		}
		if (isset($parts[self::LINE_DELIMITER_NAME]))
		{
			$this->lineDelimiter = urldecode($parts[self::LINE_DELIMITER_NAME]);
		}

		return;
	}

}
