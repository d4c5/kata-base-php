<?php

/**
 * String parser class.
 *
 * @package Kata
 * @subpackage StringToArray
 */

namespace Kata\StringToArray;

use Kata\StringToArray\Header\AbstractHeaderParser;
use Kata\StringToArray\Header\HeaderParserInterface;
use Kata\StringToArray\Header\EmptyParser;

/**
 * Converts the given string to an array.
 */
class StringParser
{
	/**
	 * Header parser.
	 *
	 * @var \Kata\StringToArray\Header\UrlParser|\Kata\StringToArray\Header\AsciiParser|\Kata\StringToArray\Header\LabelParser|\Kata\StringToArray\Header\EmptyParser
	 */
	private $headerParser = null;

	/**
	 * String.
	 *
	 * @var string
	 */
	private $string = '';

	/**
	 * Result object.
	 *
	 * @var \Kata\StringToArray\Result
	 */
	private $result = null;

	/**
	 * Sets header and string parsers.
	 *
	 * @param \Kata\StringToArray\Header\HeaderParserInterface $headerParser
	 * @param string                                     $string
	 *
	 * @return void
	 *
	 * @throws \Kata\StringToArray\InvalidStringException
	 */
	public function __construct(HeaderParserInterface $headerParser, $string)
	{
		if (is_string($string) !== true)
		{
			throw new InvalidStringException(__METHOD__ . ' - The given input is not a string [string: ' . $string . ']');
		}

		$this->headerParser = $headerParser;
		$this->string       = $string;
		$this->result       = new Result();

		$this->init();
	}

	/**
	 * Returns parsed string in array.
	 *
	 * @return array
	 */
	public function getArray()
	{
		$array = array();

		if (!empty($this->result->getLabels()))
		{
			$array = array(
				'labels' => $this->result->getLabels(),
				'data'   => $this->result->getData(),
			);
		}
		else
		{
			$array = $this->result->getData();
		}

		return $array;
	}

	/**
	 * Returns parsed strng as an object.
	 *
	 * @return \Kata\StringToArray\Result
	 */
	public function getObject()
	{
		return $this->result;
	}

	/**
	 * Initializes string parsing.
	 *
	 * @return void
	 */
	private function init()
	{
		$this->removeHeader();
		$this->setLabels();
		$this->setData();

		return;
	}

	/**
	 * Removes header information from string.
	 *
	 * @return void
	 */
	private function removeHeader()
	{
		if (!($this->headerParser instanceof EmptyParser))
		{
			$headlessString = substr($this->string, strpos($this->string, AbstractHeaderParser::HEADER_DELIMITER) + 1);
			$this->string   = $headlessString;
		}

		return;
	}

	/**
	 * Sets labels.
	 *
	 * @return void
	 */
	private function setLabels()
	{
		if ($this->headerParser->getUseFirstLineAsLabels() === true)
		{
			$lines  = explode($this->headerParser->getLineDelimiter(), $this->string);
			$labels = explode($this->headerParser->getColumnDelimiter(), $lines[0]);

			$this->result->setLabels($labels);

			$stringWithoutLabels = substr($this->string, strpos($this->string, $this->headerParser->getLineDelimiter()) + 1);
			$this->string        = $stringWithoutLabels;
		}

		return;
	}

	/**
	 * Sets data.
	 *
	 * @return void
	 */
	private function setData()
	{
		$data = array();

		$lines = explode($this->headerParser->getLineDelimiter(), $this->string);

		// multi-line
		if (count($lines) > 1)
		{
			foreach ($lines as $line)
			{
				$data[] = explode($this->headerParser->getColumnDelimiter(), $line);
			}
		}
		// one-line
		else
		{
			$data = explode($this->headerParser->getColumnDelimiter(), $lines[0]);
		}

		$this->result->setData($data);

		return;
	}

}
