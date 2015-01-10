<?php

namespace Kata\Test\StringCalculator;

use Kata\StringCalculator\Delimiters;

/**
 * Tests for StringCalculator class.
 */
class DelimitersTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests invalid delimiter part (invalid input type).
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringCalculator\InvalidArgumentException
	 */
	public function testInvalidInputTypeException()
	{
		new Delimiters(array(1));
	}

	/**
	 * Tests invalid delimiter part (no new line character in definition).
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringCalculator\InvalidArgumentException
	 */
	public function testMissingNewLineCharException()
	{
		new Delimiters("//abcde1abcde2");
	}

	/**
	 * Tests invalid delimiter part (syntax error).
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringCalculator\InvalidArgumentException
	 */
	public function testInvalidInputSyntaxException()
	{
		new Delimiters("//[a\n1a2");
	}

	/**
	 * Tests get method.
	 *
	 * @param array  $expectedDelimiters
	 * @param string $numbers
	 *
	 * @return void
	 *
	 * @dataProvider providerDelimiters
	 */
	public function testGetDelimiters(array $expectedDelimiters, $numbers)
	{
		$delimiters = new Delimiters($numbers);

		$this->assertEquals(sort($expectedDelimiters), sort($delimiters->getDelimiters()));
	}

	/**
	 * Data provider to getDelimiters.
	 *
	 * array(
	 *		expected delimiters,
	 *		numbers string
	 * )
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
				"//[;][%]\n1;2%3",
			),
			array(
				array("\n"),
				"//[%0A]\n1\n2",
			),
			array(
				array('AsD', '\*\*\*', Delimiters::DELIMITER_NEW_LINE),
				"//[AsD][***][%0A]\n1\n***2AsD3",
			),
		);
	}

}
