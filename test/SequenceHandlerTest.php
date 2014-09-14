<?php

namespace Kata\Test\SequenceHandler;

use Kata\SequenceHandler;

class SequenceHandlerTest extends \PHPUnit_Framework_TestCase
{
	const MIN_VALUE = 0;
	const MAX_VALUE = 1;
	const NUM_VALUE = 2;
	const AVG_VALUE = 3;

	/**
	 * @dataProvider providerSequence
	 */
	public function testMinimumValue($result, $sequence)
	{
		$sequenceHandler = new SequenceHandler();

		$this->assertEquals($result[self::MIN_VALUE], $sequenceHandler->getMinimumValue($sequence));
	}

	/**
	 * @dataProvider providerSequence
	 */
	public function testMaximumValue($result, $sequence)
	{
		$sequenceHandler = new SequenceHandler();

		$this->assertEquals($result[self::MAX_VALUE], $sequenceHandler->getMaximumValue($sequence));
	}

	/**
	 * @dataProvider providerSequence
	 */
	public function testNumberOfElements($result, $sequence)
	{
		$sequenceHandler = new SequenceHandler();

		$this->assertEquals($result[self::NUM_VALUE], $sequenceHandler->getNumberOfElements($sequence));
	}

	/**
	 * @dataProvider providerSequence
	 */
	public function testAverageValue($result, $sequence)
	{
		$sequenceHandler = new SequenceHandler();

		$this->assertEquals($result[self::AVG_VALUE], $sequenceHandler->getAverage($sequence));
	}

	public function providerSequence()
	{
		return array(
			array(array(-2, 92, 6, 21.833333333333), array(6, 9, 15, -2, 92, 11)),
		);
	}

}
