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
 * #useFirstLineAsLabels
 */
class LabelParser extends AbstractHeaderParser implements HeaderParserInterface
{
	const HEADER_STARTER = "#";

	const LABEL_PREFIX = 'useFirstLineAsLabels';

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
		if (substr($string, 0, strpos($string, AbstractHeaderParser::HEADER_DELIMITER)) != self::HEADER_STARTER . self::LABEL_PREFIX)
		{
			throw new InvalidHeaderException(__METHOD__ . ' - The header does not contain useFirstLineAsLabels flag [string: ' . $string . ']');
		}

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
		$this->useFirstLineAsLabels = true;

		return;
	}

}
