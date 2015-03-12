<?php

namespace Kata\Test\StringToArray;

use Kata\StringToArray\Result;

/**
 * Tests for Result class.
 */
class ResultTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests toArray method.
	 *
	 * @param Result $resultObj
	 * @param array $epexctedArray
	 *
	 * @return void
	 *
	 * @dataProvider providerResults
	 */
	public function testToArray(Result $resultObj, array $expectedArray)
	{
		$array = $resultObj->toArray();

		$this->assertEquals($expectedArray, $array);
	}

	/**
	 * Returns result data objects.
	 *
	 * @return array
	 */
	public function providerResults()
	{
		// Result, expected array
		return array(
			array(
				new Result(array(1, 2)),
				array(1, 2),
			),
			array(
				new Result(array(1, 2), array('a', 'b')),
				array('labels' => array('a', 'b'), 'data' => array(1, 2)),
			),
		);
	}

}
