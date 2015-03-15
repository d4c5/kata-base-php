<?php

/**
 * Header parser class.
 *
 * @package StringToArray
 * @subpackage Header
 */

namespace Kata\StringToArray\Header;

use \Kata\StringToArray\InvalidStringException;

/**
 * Parses the given string to header parameters.
 */
abstract class AbstractHeaderParser
{
	/** The end character of the header */
	const HEADER_DELIMITER = "\n";

	/**
	 * Use first line as labels.
	 *
	 * @var boolean
	 */
	protected $useFirstLineAsLabels = false;

	/**
	 * Column delimiter.
	 *
	 * @var string
	 */
	protected $columnDelimiter = ',';

	/**
	 * Line delimiter.
	 *
	 * @var string
	 */
	protected $lineDelimiter = "\n";

	/**
	 * Sets parsing parameters.
	 *
	 * @param string $string
	 *
	 * @return void
	 *
	 * @throws \Kata\StringToArray\InvalidStringException
	 */
	public function __construct($string)
	{
		if (is_string($string) !== true)
		{
			throw new InvalidStringException(__METHOD__ . ' - The given input is not a string [string: ' . $string . ']');
		}

		$this->isValidHeader($string);
		$this->setParsingParameters($string);
	}

	/**
	 * Checks the header format.
	 *
	 * @return boolean
	 */
	abstract protected function isValidHeader($string);

	/**
	 * Sets parsing parameters by the header of the given string.
	 *
	 * @param string $string
	 *
	 * @return void
	 */
	abstract protected function setParsingParameters($string);

	/**
	 * Returns labels flags.
	 *
	 * @return boolean
	 */
	public function getUseFirstLineAsLabels()
	{
		return $this->useFirstLineAsLabels;
	}

	/**
	 * Returns column delimiter.
	 *
	 * @return string
	 */
	public function getColumnDelimiter()
	{
		return $this->columnDelimiter;
	}

	/**
	 * Returns line delimiter.
	 *
	 * @return string
	 */
	public function getLineDelimiter()
	{
		return $this->lineDelimiter;
	}

}
