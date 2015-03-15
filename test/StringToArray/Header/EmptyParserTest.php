<?php

namespace Kata\Test\StringToArray;

use Kata\StringToArray\Header\EmptyParser;

/**
 * Tests for empty parser class.
 */
class EmptyParserTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests constructor with non-string input.
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringToArray\InvalidStringException
	 * @expectedExceptionMessageRegExp /The given input is not a string/
	 */
	public function testConstructorWithNonString()
	{
		new EmptyParser(null);
	}

	/**
	 * Tests constructor.
	 *
	 * @param string  $string
	 * @param boolean $expectedUseFirstLineAsLabels
	 * @param string  $expectedColumnDelimiter
	 * @param string  $expectedLineDelimiter
	 *
	 * @return void
	 *
	 * @dataProvider providerStrings
	 */
	public function testConstructor($string, $expectedUseFirstLineAsLabels, $expectedColumnDelimiter, $expectedLineDelimiter)
	{
		$emptyParser = new EmptyParser($string);

		$this->assertEquals($expectedUseFirstLineAsLabels, $emptyParser->getUseFirstLineAsLabels());
		$this->assertEquals($expectedColumnDelimiter, $emptyParser->getColumnDelimiter());
		$this->assertEquals($expectedLineDelimiter, $emptyParser->getLineDelimiter());
	}

	/**
	 * Returns string and expected header parameters.
	 *
	 * @return array
	 */
	public function providerStrings()
	{
		return array(
			array(
				"",
				false,
				chr(44),
				chr(10),
			),
		);
	}

}
