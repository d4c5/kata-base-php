<?php

namespace Kata\Test\StringToArray;

use Kata\StringToArray\Header\UrlParser;

/**
 * Tests for URL parser class.
 */
class UrlParserTest extends \PHPUnit_Framework_TestCase
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
		new UrlParser(null);
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
		new UrlParser("#useFirstLineAsLabels=1&columnDelimiter=,&lineDelimiter=%0A");
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
		new UrlParser("|useFirstLineAsLabels=1&columnDelimiter=,&lineDelimiter=%0A\n");
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
		$urlParser = new UrlParser($string);

		$this->assertEquals($expectedUseFirstLineAsLabels, $urlParser->getUseFirstLineAsLabels());
		$this->assertEquals($expectedColumnDelimiter, $urlParser->getColumnDelimiter());
		$this->assertEquals($expectedLineDelimiter, $urlParser->getLineDelimiter());
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
				"#useFirstLineAsLabels=1&columnDelimiter=,&lineDelimiter=%0A\n",
				true,
				chr(44),
				chr(10),
			),
			array(
				"#useFirstLineAsLabels=0&columnDelimiter=;&lineDelimiter=|\n",
				false,
				";",
				"|",
			),
		);
	}

}
