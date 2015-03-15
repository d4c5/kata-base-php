<?php

/**
 * Header parser class.
 *
 * @package StringToArray
 * @subpackage Header
 */

namespace Kata\StringToArray\Header;

/**
 * Parses the given string to header parameters.
 */
class EmptyParser extends AbstractHeaderParser implements HeaderParserInterface
{
	/**
	 * Checks the header format.
	 *
	 * @param string $string
	 *
	 * @return boolean
	 */
	protected function isValidHeader($string)
	{
		// No header.
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
		// We use all parameters with default value.
		return;
	}

}
