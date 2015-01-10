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
	 * @param string $expectedNegativeNumbers
	 * @param string $numbersDefinition
	 *
	 * @return void
	 *
	 * @dataProvider providerNegativeNumbers
	 */
	public function testNegativeNumberException($expectedNegativeNumbers, $numbersDefinition)
	{
		$delimiters = new Delimiters();

		try
		{
			new Numbers($numbersDefinition, $delimiters);
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
	 * Tests summary.
	 *
	 * @param int        $expectedSummary
	 * @param string     $numbersDefinition
	 * @param Delimiters $delimiters
	 * @param int        $limit
	 *
	 * @return void
	 *
	 * @dataProvider providerSummary
	 */
	public function testGetSummary($expectedSummary, $numbersDefinition, $delimiters, $limit = null)
	{
		$numbers = new Numbers($numbersDefinition, $delimiters, $limit);

		$this->assertEquals($expectedSummary, $numbers->getSummary());
	}

	/**
	 * Data provider with negative numbers.
	 *
	 * @return array
	 */
	public function providerNegativeNumbers()
	{
		return array(
			array(
				'-1',
				'-1',
			),
			array(
				'-1,-3',
				'-1,2,-3',
			),
			array(
				'-3',
				'1,2,-3',
			),
			array(
				'-1,-2,-3',
				'-1,-2,-3',
			),
		);
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

	/**
	 * Data provider to getSummary.
	 *
	 * @return array
	 */
	public function providerSummary()
	{
		return array(
			array(
				0,
				'',
				new Delimiters(),
			),
			array(
				1,
				'1',
				new Delimiters(),
			),
			array(
				3,
				'1,2',
				new Delimiters(),
			),
			array(
				6,
				'1;;2;3',
				new Delimiters('//[;]'),
			),
			array(
				6,
				'1AsD2***3AsD1001',
				new Delimiters('//[AsD][***]'),
			),
			array(
				6,
				'1+2+501+3',
				new Delimiters('//[%2B]'),
				500,
			),
		);
	}

}
