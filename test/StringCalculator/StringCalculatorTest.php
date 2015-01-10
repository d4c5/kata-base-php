<?php

namespace Kata\Test\StringCalculator;

use Kata\StringCalculator\StringCalculator;
use Kata\StringCalculator\Delimiters;
use Kata\StringCalculator\Numbers;

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
	public function testAdd($expectedSummary, $numbers, $limit = null)
	{
		$delimiters = new Delimiters($numbers);
		$numbersObj = new Numbers($numbers, $delimiters, $limit);

		$stringCalculator = new StringCalculator();
		$summary          = $stringCalculator->add($numbersObj);

		$this->assertEquals($expectedSummary, $summary);
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
			array(7, "//[AsD]\n2AsD2AsD3"),
			array(7, "//[;][:]\n1;3:3"),
			array(4, "//[*][%]\n1*2%1"),
			array(4, "//[AsD][***]\n1AsD2***1"),
		);
	}

}
