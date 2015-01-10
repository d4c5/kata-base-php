<?php

namespace Kata\Test\StringCalculator;

use Kata\StringCalculator\Delimiters;

/**
 * Tests for StringCalculator class.
 */
class DelimitersTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests invalid delimiter part (no new line character in definition).
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringCalculator\InvalidArgumentException
	 */
	public function testInvalidArgumentException()
	{
		new Delimiters("//abcde1abcde2");
	}

	/**
	 * Tests get method.
	 *
	 * @param array  $expectedDelimiters
	 * @param string $delimitersDefinition
	 *
	 * @return void
	 *
	 * @dataProvider providerDelimiters
	 */
	public function testGetDelimiters(array $expectedDelimiters, $delimitersDefinition)
	{
		$delimiters = new Delimiters($delimitersDefinition);

		$this->assertEquals(sort($expectedDelimiters), sort($delimiters->getDelimiters()));
	}

	/**
	 * Data provider to getDelimiters.
	 *
	 * @return array
	 */
	public function providerDelimiters()
	{
		return array(
			array(
				array(Delimiters::DELIMITER_COMMA, Delimiters::DELIMITER_NEW_LINE),
				"",
			),
			array(
				array(';', '%', Delimiters::DELIMITER_NEW_LINE),
				"//[;][%]",
			),
			array(
				array("\n"),
				"//[%0A]",
			),
			array(
				array('AsD', '\*\*\*', Delimiters::DELIMITER_NEW_LINE),
				"//[AsD][***][%0A]",
			),
		);
	}

}
