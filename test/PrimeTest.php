<?php

namespace Kata\Test\Prime;

use Kata\Prime;

class PrimeTest extends \PHPUnit_Framework_TestCase
{
	public function testPrime()
	{
		$prime = new Prime();

		$this->assertEquals(array(2),       $prime->getPrimes(2));
		$this->assertEquals(array(3),       $prime->getPrimes(3));
		$this->assertEquals(array(2, 2),    $prime->getPrimes(4));
//		$this->assertEquals(array(2, 3),    $prime->getPrimes(6));
//		$this->assertEquals(array(3, 3),    $prime->getPrimes(9));
//		$this->assertEquals(array(2, 2, 3), $prime->getPrimes(12));
//		$this->assertEquals(array(3, 5),    $prime->getPrimes(15));
	}
}

