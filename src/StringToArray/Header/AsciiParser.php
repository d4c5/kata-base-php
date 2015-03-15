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
 * Accepted format:
 * %[01][0-255]{2}[0-255]{3}
 *
 * Example:
 * %1044010 (44 is "," and 10 is "\n")
 */
class AsciiParser extends AbstractHeaderParser implements HeaderParserInterface
{
	const HEADER_STARTER = "%";

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
		if (strpos($string, AbstractHeaderParser::HEADER_DELIMITER) != 8)
		{
			throw new InvalidHeaderException(__METHOD__ . " - The length of the header is invalid [string: " . $string . "]");
		}
		if (!preg_match('/^%[0-9]{7}' . AbstractHeaderParser::HEADER_DELIMITER . '/', $string))
		{
			throw new InvalidHeaderException(__METHOD__ . " - The header contains non-number characters [string: " . $string . "]");
		}

		$headerLine = substr($string, 1, strpos($string, AbstractHeaderParser::HEADER_DELIMITER) - 1);
		if ($headerLine[0] != "0" && $headerLine[0] != "1")
		{
			throw new InvalidHeaderException(__METHOD__ . " - The value of the useFirstLineAsLabels flag is invalid [useFirstLineAsLabels: " . $headerLine[0] . "]");
		}

		$columnDelimiterAsciiCode = substr($headerLine, 1, 3);
		if ($columnDelimiterAsciiCode < 0 || $columnDelimiterAsciiCode > 255)
		{
			throw new InvalidHeaderException(__METHOD__ . " - The column delimiter is invalid [columnDelimiterAsciiCode: " . $columnDelimiterAsciiCode . "]");
		}

		$lineDelimiterAsciiCode = substr($headerLine, 4, 3);
		if ($lineDelimiterAsciiCode < 0 || $lineDelimiterAsciiCode > 255)
		{
			throw new InvalidHeaderException(__METHOD__ . " - The line delimiter is invalid [lineDelimiterAsciiCode: " . $lineDelimiterAsciiCode . "]");
		}

		return true;
	}

	/**
	 * Sets parsing parameters.
	 *
	 * @param string $string
	 *
	 * @return void
	 *
	 * @throws \Exception
	 */
	protected function setParsingParameters($string)
	{
		$headerLine = substr($string, 1, strpos($string, AbstractHeaderParser::HEADER_DELIMITER) - 1);

		$this->useFirstLineAsLabels = $headerLine[0] == "1" ? true : false;

		$columnDelimiterAsciiCode = substr($headerLine, 1, 3);
		$this->columnDelimiter    = chr((int)$columnDelimiterAsciiCode);

		$lineDelimiterAsciiCode = substr($headerLine, 4, 3);
		$this->lineDelimiter    = chr((int)$lineDelimiterAsciiCode);

		return;
	}

}
