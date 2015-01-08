<?php

namespace Kata\Test\StringCalculator;

use Kata\StringCalculator\StringCalculator;

/**
 * Tests for StringCalculator class.
 */
class StringCalculatorTest extends \PHPUnit_Framework_TestCase
{
	public function testAdd()
	{
		$stringCalculator = new StringCalculator();
		$sum = $stringCalculator->add("");

		$this->assertEquals(0, $sum);
	}

}
