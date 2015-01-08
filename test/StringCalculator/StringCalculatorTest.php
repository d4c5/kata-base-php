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

		$this->assertEquals($expectedSummary, $summary, 'Difference between summaries: ' . $numbers);
	}

	/**
	 * Tests invalid integer exception.
	 *
	 * @return void
	 *
	 * @expectedException \Kata\StringCalculator\InvalidIntegerException
	 */
	public function testInvalidInteger()
	{
		$stringCalculator = new StringCalculator("A,B");
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
		);
	}

}
