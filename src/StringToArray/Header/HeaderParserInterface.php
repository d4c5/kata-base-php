<?php

/**
 * Header parser interface.
 *
 * @package StringToArray
 * @subpackage Header
 */

namespace Kata\StringToArray\Header;

/**
 * Provides the required methods to parse.
 */
interface HeaderParserInterface
{
	public function getUseFirstLineAsLabels();
	public function getColumnDelimiter();
	public function getLineDelimiter();
}
