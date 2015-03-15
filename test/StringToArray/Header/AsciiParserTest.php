<?php

namespace Kata\Test\StringToArray;

use Kata\StringToArray\Header\AsciiParser;

/**
 * Tests for ASCII parser class.
 */
class AsciiParserTest extends \PHPUnit_Framework_TestCase
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
		new AsciiParser(null);
	}

	/**
	 * Tests constructor without header delimiter.
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringToArray\InvalidHeaderException
	 * @expectedExceptionMessageRegExp /No header delimiter in the given string/
	 */
	public function testConstructorWithoutHeaderDelimiter()
	{
		new AsciiParser("%0123123");
	}

	/**
	 * Tests constructor with invalid header starter.
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringToArray\InvalidHeaderException
	 * @expectedExceptionMessageRegExp /No header starter in the given string/
	 */
	public function testConstructorWithInvalidHeaderStarter()
	{
		new AsciiParser("|0123123\n");
	}

	/**
	 * Tests constructor with invalid length.
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringToArray\InvalidHeaderException
	 * @expectedExceptionMessageRegExp /The length of the header is invalid/
	 */
	public function testConstructorWithInvalidLength()
	{
		new AsciiParser("%012312\n");
	}

	/**
	 * Tests constructor with non-number characters.
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringToArray\InvalidHeaderException
	 * @expectedExceptionMessageRegExp /The header contains non-number characters/
	 */
	public function testConstructorWithNonNumberCharacters()
	{
		new AsciiParser("%012A12B\n");
	}

	/**
	 * Tests constructor with invalid useFirstLineAsLabels flag.
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringToArray\InvalidHeaderException
	 * @expectedExceptionMessageRegExp /The value of the useFirstLineAsLabels flag is invalid/
	 */
	public function testConstructorWithInvalidUseLabelFlag()
	{
		new AsciiParser("%2123123\n");
	}

	/**
	 * Tests constructor with invalid columnDelimiterAsciiCode.
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringToArray\InvalidHeaderException
	 * @expectedExceptionMessageRegExp /The column delimiter is invalid/
	 */
	public function testConstructorWithInvalidColumnDelimiterAsciiCode()
	{
		new AsciiParser("%0444123\n");
	}

	/**
	 * Tests constructor.
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringToArray\InvalidHeaderException
	 * @expectedExceptionMessageRegExp /The line delimiter is invalid/
	 */
	public function testConstructorWithInvalidLineDelimiterAsciiCode()
	{
		new AsciiParser("%0123444\n");
	}

	/**
	 * Tests constructor with invalid lineDelimiterAsciiCode.
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
		$asciiParser = new AsciiParser($string);

		$this->assertEquals($expectedUseFirstLineAsLabels, $asciiParser->getUseFirstLineAsLabels());
		$this->assertEquals($expectedColumnDelimiter, $asciiParser->getColumnDelimiter());
		$this->assertEquals($expectedLineDelimiter, $asciiParser->getLineDelimiter());
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
				"%1044010\n",
				true,
				chr(44),
				chr(10),
			),
			array(
				"%0044010\n",
				false,
				chr(44),
				chr(10),
			),
		);
	}

}
