<?php

namespace Kata\StringCalculator;

/**
 * Delimiters handler.
 */
class Delimiters
{
	/** Parts of delimiter definition */
	const DELIMITER_PREFIX     = '//';
	const DELIMITER_PARAM_NAME = 'delimiters';
	const DELIMITER_REGEX      = '/\[(?P<delimiters>[^\]]+)\]/';

	/** Predefined delimiters */
	const DELIMITER_COMMA    = ',';
	const DELIMITER_NEW_LINE = "\n";

	/**
	 * Formatted, checked and escaped delimiters.
	 *
	 * @var array
	 */
	private $delimiters = array(
		self::DELIMITER_COMMA,
	);

	/**
	 * Validates, parses and escapes input.
	 *
	 * @param string $delimitersDefinition
	 *
	 * @return void
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct($delimitersDefinition = '')
	{
		if (!empty($delimitersDefinition))
		{
			$this->validate($delimitersDefinition);

			$this->delimiters = $this->parse($delimitersDefinition);
		}

		$this->escape();

		// Adds the new line character to delimiters because of the compatibility.
		if (!in_array(self::DELIMITER_NEW_LINE, $this->delimiters, true))
		{
			$this->delimiters[] = self::DELIMITER_NEW_LINE;
		}

		return;
	}

	/**
	 * Returns all delimiters.
	 *
	 * @return array
	 */
	public function getDelimiters()
	{
		return $this->delimiters;
	}

	/**
	 * Validates delimiter definition.
	 *
	 * @param string $delimitersDefinition
	 *
	 * @return void
	 *
	 * @throws InvalidArgumentException
	 */
	private function validate($delimitersDefinition)
	{
		$matches = array();
		if (!preg_match_all(self::DELIMITER_REGEX, $delimitersDefinition, $matches))
		{
			throw new InvalidArgumentException('The delimiter definition has syntax error.');
		}
	}

	/**
	 * Parses delimiter definition.
	 *
	 * @param string $delimitersDefinition
	 *
	 * @return array
	 */
	private function parse($delimitersDefinition)
	{
		$matches = array();
		preg_match_all(self::DELIMITER_REGEX, $delimitersDefinition, $matches);

		return $matches[self::DELIMITER_PARAM_NAME];
	}

	/**
	 * Escapes delimiters.
	 *
	 * @return void
	 */
	private function escape()
	{
		foreach ($this->delimiters as $i => $delimiter)
		{
			$urlDecodedDelimiter  = urldecode($delimiter);
			$this->delimiters[$i] = preg_quote($urlDecodedDelimiter, '/');
		}

		return;
	}

}
