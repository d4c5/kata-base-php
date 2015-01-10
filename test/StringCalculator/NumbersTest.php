<?php

namespace Kata\Test\StringCalculator;

use Kata\StringCalculator\Numbers;
use Kata\StringCalculator\Delimiters;

/**
 * Tests for StringCalculator class.
 */
class NumbersTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests invalid limit.
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringCalculator\InvalidArgumentException
	 */
	public function testInvalidLimitException()
	{
		$delimiters = new Delimiters();

		new Numbers('1', $delimiters, 'asd');
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
		$delimiters = new Delimiters();

		new Numbers('1;2', $delimiters);
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
		$delimiters = new Delimiters();

		new Numbers('1,A', $delimiters);
	}

	/**
	 * Tests negative number exception.
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringCalculator\NegativeNumberException
	 * @expectedExceptionMessage The "numbers" contains negative numbers [-2, -3]
	 */
	public function testNegativeNumberException()
	{
		$delimiters = new Delimiters();

		new Numbers('-2,-3', $delimiters);
	}

	/**
	 * Tests get method.
	 *
	 * @param array      $expectedNumbers
	 * @param string     $numbersDefinition
	 * @param Delimiters $delimiters
	 * @param int        $limit
	 *
	 * @return void
	 *
	 * @dataProvider providerNumbers
	 */
	public function testGetNumbers(array $expectedNumbers, $numbersDefinition, $delimiters, $limit = null)
	{
		$numbers = new Numbers($numbersDefinition, $delimiters, $limit);

		$this->assertEquals(sort($expectedNumbers), sort($numbers->getNumbers()));
	}

	/**
	 * Data provider to getNumbers.
	 *
	 * @return array
	 */
	public function providerNumbers()
	{
		return array(
			array(
				array(1),
				'1',
				new Delimiters(),
			),
			array(
				array(1, 2),
				'1,2',
				new Delimiters(),
			),
			array(
				array(1, 2, 3),
				'1;2;3',
				new Delimiters('//[;]'),
			),
			array(
				array(1, 2, 3),
				'1;2%3',
				new Delimiters('//[;][%]'),
			),
			array(
				array(1, 2, 3),
				'1***2***3',
				new Delimiters('//[***]'),
			),
			array(
				array(1, 2, 3),
				'1AsD2***3',
				new Delimiters('//[AsD][***]'),
			),
			array(
				array(1, 2, 3),
				'1,2,1001,3',
				new Delimiters(),
			),
			array(
				array(1, 2, 3),
				'1,501,2,3',
				new Delimiters(),
				500,
			),
		);
	}
}
