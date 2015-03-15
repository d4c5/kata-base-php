<?php

namespace Kata\Test\StringToArray;

use Kata\StringToArray\Header\LabelParser;

/**
 * Tests for URL parser class.
 */
class LabelParserTest extends \PHPUnit_Framework_TestCase
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
		new LabelParser(null);
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
		new LabelParser("#useFirstLineAsLabels=1&columnDelimiter=,&lineDelimiter=%0A");
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
		new LabelParser("|useFirstLineAsLabels=1&columnDelimiter=,&lineDelimiter=%0A\n");
	}

	/**
	 * Tests constructor with invalid header.
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringToArray\InvalidHeaderException
	 * @expectedExceptionMessageRegExp /The header does not contain useFirstLineAsLabels flag/
	 */
	public function testConstructorWithInvalidHeader()
	{
		new LabelParser("#fooBar\n");
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
		$labelParser = new LabelParser($string);

		$this->assertEquals($expectedUseFirstLineAsLabels, $labelParser->getUseFirstLineAsLabels());
		$this->assertEquals($expectedColumnDelimiter, $labelParser->getColumnDelimiter());
		$this->assertEquals($expectedLineDelimiter, $labelParser->getLineDelimiter());
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
				"#useFirstLineAsLabels\n",
				true,
				chr(44),
				chr(10),
			),
		);
	}

}
