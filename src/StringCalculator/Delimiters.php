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
	 * @param string $numbers
	 *
	 * @return void
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct($numbers)
	{
		if (is_string($numbers) !== true)
		{
			throw new InvalidArgumentException('The "numbers" is not a string.');
		}

		$delimitersDefinition = $this->getDelimitersDefinition($numbers);
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
	 * Returns delimiters definitions by numbers.
	 *
	 * @param string $numbers
	 *
	 * @return string
	 *
	 * @throws InvalidArgumentException
	 */
	private function getDelimitersDefinition($numbers)
	{
		$firstNewLineCharPosition = 0;

		if (strpos($numbers, self::DELIMITER_PREFIX) === 0)
		{
			$firstNewLineCharPosition = strpos($numbers, self::DELIMITER_NEW_LINE);
			if ($firstNewLineCharPosition === false)
			{
				throw new InvalidArgumentException('The new line character is required if delimiter part is set.');
			}
		}

		return ($firstNewLineCharPosition > 0 ? substr($numbers, strlen(self::DELIMITER_PREFIX), $firstNewLineCharPosition) : '');
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
