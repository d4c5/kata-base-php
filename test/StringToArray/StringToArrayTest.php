<?php

namespace Kata\Test\StringToArray;

use Kata\StringToArray\StringToArray;

/**
 * Tests for StringToArray class.
 */
class StringToArrayTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests the getArrayByString method with non-string input.
	 *
	 * @return void
	 *
	 * @expectedException \Exception
	 */
	public function testGetArrayByStringWithNonStringInput()
	{
		$stringToArray = new StringToArray();
		$stringToArray->getArrayByString(array());
	}

	/**
	 * Tests the getArrayByString method.
	 *
	 * @param string $string
	 * @param array  $expectedArray
	 *
	 * @return void
	 *
	 * @dataProvider providerStrings
	 */
	public function testGetArrayByString($string, array $expectedArray)
	{
		$stringToArray = new StringToArray();
		$array         = $stringToArray->getArrayByString($string);

		$this->assertEquals($expectedArray, $array);
	}

	/**
	 * Returns string to getArrayByString test.
	 *
	 * @return array
	 */
	public function providerStrings()
	{
		// string input, expected array
		return array(
			array(
				'',
				array(
					'',
				),
			),
			array(
				'singleElement',
				array(
					'singleElement',
				),
			),
			array(
				'a,b,c',
				array(
					'a',
					'b',
					'c',
				),
			),
			array(
				'100,982,444,990,1',
				array(
					'100',
					'982',
					'444',
					'990',
					'1',
				),
			),
			array(
				'Mark,Anthony,marka@lib.de',
				array(
					'Mark',
					'Anthony',
					'marka@lib.de',
				),
			),
			array(
				"211,22,35\n10,20,33",
				array(
					0 => array(
						'211',
						'22',
						'35',
					),
					1 => array(
						'10',
						'20',
						'33',
					),
				),
			),
			array(
				"luxembourg,kennedy,44\nbudapest,expo ter,5-7\ngyors,fo utca,9",
				array(
					0 => array(
						'luxembourg',
						'kennedy',
						'44',
					),
					1 => array(
						'budapest',
						'expo ter',
						'5-7',
					),
					2 => array(
						'gyors',
						'fo utca',
						'9',
					),
				),
			),
		);
	}

}
