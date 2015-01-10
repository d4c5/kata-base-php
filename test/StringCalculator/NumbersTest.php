<?php

namespace Kata\Test\StringCalculator;

use Kata\StringCalculator\Numbers;
use Kata\StringCalculator\Delimiters;
use Kata\StringCalculator\NegativeNumberException;

/**
 * Tests for StringCalculator class.
 */
class NumbersTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests invalid numbers part (invalid input type).
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringCalculator\InvalidArgumentException
	 */
	public function testInvalidInputTypeException()
	{
		$delimiters = new Delimiters("1");

		new Numbers(array(1), $delimiters);
	}

	/**
	 * Tests invalid numbers part (no new line character in definition).
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringCalculator\InvalidArgumentException
	 */
	public function testMissingNewLineCharException()
	{
		$delimiters = new Delimiters("1");

		new Numbers("//abcde1abcde2", $delimiters);
	}

	/**
	 * Tests invalid limit.
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringCalculator\InvalidArgumentException
	 */
	public function testInvalidLimitException()
	{
		$delimiters = new Delimiters("1");

		new Numbers("1", $delimiters, "asd");
	}

	/**
	 * Tests invalid numbers (wrong delimiter).
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringCalculator\InvalidArgumentException
	 */
	public function testInvalidDelimiterException()
	{
		$delimiters = new Delimiters("1");

		new Numbers("1;2", $delimiters);
	}

	/**
	 * Tests invalid numbers (wrong number).
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringCalculator\InvalidNumberException
	 */
	public function testInvalidNumberException()
	{
		$delimiters = new Delimiters("1");

		new Numbers("1,A", $delimiters);
	}

	/**
	 * Tests negative number exception.
	 *
	 * @param string $expectedNegativeNumbers
	 * @param string $numbers
	 *
	 * @return void
	 *
	 * @dataProvider providerNegativeNumbers
	 */
	public function testNegativeNumberException($expectedNegativeNumbers, $numbers)
	{
		$delimiters = new Delimiters($numbers);

		try
		{
			new Numbers($numbers, $delimiters);
		}
		catch (NegativeNumberException $e)
		{
			$expectedExceptionMessage = 'The "numbers" contains negative numbers [' . $expectedNegativeNumbers . ']';
			$this->assertEquals($expectedExceptionMessage, $e->getMessage());
		}
	}

	/**
	 * Tests get method.
	 *
	 * @param array  $expectedNumbers
	 * @param string $numbers
	 * @param int    $limit
	 *
	 * @return void
	 *
	 * @dataProvider providerNumbers
	 */
	public function testGetNumbers(array $expectedNumbers, $numbers, $limit = null)
	{
		$delimiters = new Delimiters($numbers);
		$numbersObj = new Numbers($numbers, $delimiters, $limit);

		$this->assertEquals(sort($expectedNumbers), sort($numbersObj->getNumbers()));
	}

	/**
	 * Data provider with negative numbers.
	 *
	 * array(
	 *		Negative numbers in exception message,
	 *		numbers string
	 * )
	 *
	 * @return array
	 */
	public function providerNegativeNumbers()
	{
		return array(
			array(
				"-1",
				"-1",
			),
			array(
				"-1,-3",
				"-1,2,-3",
			),
			array(
				"-3",
				"1,2,-3",
			),
			array(
				"-1,-2,-3",
				"-1,-2,-3",
			),
		);
	}

	/**
	 * Data provider to getNumbers.
	 *
	 * array(
	 *		expected numbers,
	 *		numbers string,
	 *		limit
	 * )
	 *
	 * @return array
	 */
	public function providerNumbers()
	{
		return array(
			array(
				array(1),
				"1",
			),
			array(
				array(1, 2),
				"1,2",
			),
			array(
				array(1, 2, 3),
				"//[;]\n1;2;3",
			),
			array(
				array(1, 2, 3),
				"//[;][%]\n1;2%3",
			),
			array(
				array(1, 2, 3),
				"//[***]\n1***2***3",
			),
			array(
				array(1, 2, 3),
				"//[AsD][***]\n1AsD2***3",
			),
			array(
				array(1, 2, 3),
				"1,2,1001,3",
			),
			array(
				array(1, 2, 3),
				"1,501,2,3",
				500,
			),
		);
	}

}
