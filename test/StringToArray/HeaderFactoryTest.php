<?php

namespace Kata\Test\StringToArray;

use Kata\StringToArray\HeaderFactory;
use Kata\StringToArray\Header\AbstractHeaderParser;

/**
 * Tests for StringToArray class.
 */
class HeaderFactoryTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests invalid header type.
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringToArray\InvalidHeaderException
	 */
	public function testInvalidHeaderType()
	{
		HeaderFactory::getHeaderParser("\$asd\n");
	}

	/**
	 * Tests header parsser.
	 *
	 * @param string $string
	 * @param string $expectedHeaderType
	 *
	 * @return void
	 *
	 * @dataProvider providerStrings
	 */
	public function testHeaderParser($string, $expectedHeaderType)
	{
		$headerParser = HeaderFactory::getHeaderParser($string);

		$this->assertInstanceOf($expectedHeaderType, $headerParser);
	}

	/**
	 * Returns string and expected header parser type.
	 *
	 * @return array
	 */
	public function providerStrings()
	{
		return array(
			array(
				"",
				'\Kata\StringToArray\Header\EmptyParser',
			),
			array(
				"%0044010\n",
				'\Kata\StringToArray\Header\AsciiParser',
			),
			array(
				"#useFirstLineAsLabels\n",
				'\Kata\StringToArray\Header\LabelParser',
			),
			array(
				"#useFirstLineAsLabels=1&columnDelimiter=;\n",
				'\Kata\StringToArray\Header\UrlParser',
			),
		);
	}

}
