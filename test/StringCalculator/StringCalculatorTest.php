<?php

namespace Kata\Test\StringCalculator;

use Kata\StringCalculator\StringCalculator;

/**
 * Tests for StringCalculator class.
 */
class StringCalculatorTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests add method.
	 *
	 * @param int    $expectedSummary
	 * @param string $numbers
	 *
	 * @return void
	 *
	 * @dataProvider providerNumbers
	 */
	public function testAdd($expectedSummary, $numbers)
	{
		$stringCalculator = new StringCalculator($numbers);
		$summary          = $stringCalculator->add();

		$this->assertEquals($expectedSummary, $summary, 'Difference between summaries');
	}

	/**
	 * Tests invalid number exception.
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringCalculator\InvalidNumberException
	 */
	public function testInvalidNumberException()
	{
		$stringCalculator = new StringCalculator("A,B");
		$stringCalculator->add();
	}

	/**
	 * Tests negative number exception.
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringCalculator\NegativeNumberException
	 * @expectedExceptionMessage The numbers contains negative numbers [-2, -3]
	 */
	public function testNegativeNumberException()
	{
		$stringCalculator = new StringCalculator("-2,-3");
		$stringCalculator->add();
	}

	/**
	 * Data provider to add.
	 *
	 * @return array
	 */
	public function providerNumbers()
	{
		return array(
			array(0, ""),
			array(1, "1"),
			array(3, "1,2"),
			array(6, "1,2,3"),
			array(5, "1,,4"),
			array(6, "1\n2,3"),
			array(8, "2\n2\n2,2"),
			array(3, "//[;]\n1;2"),
			array(6, "//[:]\n1\n2:3"),
			array(2, "//[%0A]\n1\n1"),
			array(2, "2,1001"),
			array(1001, "1,1000"),
			array(6, "//[***]\n1***2***3"),
		);
	}

}
