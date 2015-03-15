<?php

/**
 * Header factory.
 *
 * @package Kata
 * @subpackage StringToArray
 */

namespace Kata\StringToArray;

use Kata\StringToArray\InvalidHeaderException;
use Kata\StringToArray\Header\AbstractHeaderParser;
use Kata\StringToArray\Header\UrlParser;
use Kata\StringToArray\Header\AsciiParser;
use Kata\StringToArray\Header\LabelParser;
use Kata\StringToArray\Header\EmptyParser;

/**
 * Header factory.
 */
class HeaderFactory
{
	/**
	 * Returns a proper header parser to string.
	 *
	 * @param string $string
	 *
	 * @return \Kata\StringToArray\HeaderParserV1|\Kata\StringToArray\HeaderParserV2
	 *
	 * @throws \Kata\StringToArray\InvalidHeaderException
	 */
	public static function getHeaderParser($string)
	{
		$headerParser = null;

		if (strpos($string, AbstractHeaderParser::HEADER_DELIMITER) === false)
		{
			$headerParser = new EmptyParser($string);
		}
		elseif (strpos($string, AsciiParser::HEADER_STARTER) === 0)
		{
			$headerParser = new AsciiParser($string);
		}
		elseif (substr($string, 0, strpos($string, AbstractHeaderParser::HEADER_DELIMITER)) === LabelParser::HEADER_STARTER . LabelParser::LABEL_PREFIX)
		{
			$headerParser = new LabelParser($string);
		}
		elseif (strpos($string, UrlParser::HEADER_STARTER) === 0)
		{
			$headerParser = new UrlParser($string);
		}
		else
		{
			throw new InvalidHeaderException(__METHOD__ . ' - Malformed header in string [string: ' . $string . ']');
		}

		return $headerParser;
	}

}
