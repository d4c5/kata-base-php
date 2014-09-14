<?php

namespace Kata\Test\Prime;

use Kata\Prime;

class PrimeTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider providerPrimeDecomposition
	 */
	public function testPrimeDecomposition($number, $decomposition)
	{
		$prime = new Prime();

		$this->assertEquals($decomposition, $prime->getPrimeDecomposition($number));
	}

	public function providerPrimeDecomposition()
	{
		return array(
			// Negative cases.
			array(-4, array(-1, 2, 2)),
			array(-2, array(-1, 2)),

			// Extreme cases.
			array(0,  array()),
			array(1,  array()),

			// Textbook examples.
			array(2,  array(2)),
			array(3,  array(3)),
			array(4,  array(2, 2)),
			array(6,  array(2, 3)),
			array(9,  array(3, 3)),
			array(12, array(2, 2, 3)),
			array(15, array(3, 5)),

			// Fermat's factorization: 2^2*n + 1
			array(5,     array(5)),
			array(17,    array(17)),
			array(257,   array(257)),
			array(65537, array(65537)),

			// Wiki's example.
			array(9438, array(2, 3, 11, 11, 13)),
		);
	}

}
