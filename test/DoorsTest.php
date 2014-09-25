<?php

namespace Kata\Test\Doors;

use Kata\Doors;
use Kata\Prime;

class DoorsTest extends \PHPUnit_Framework_TestCase
{
	protected $prime = null;

	protected function setUp()
	{
		$this->prime = new Prime();
	}

	/**
	 * @dataProvider providerDoors
	 */
	public function testStatesOfDoors(array $doorStates, $numberOfDoors, $numberOfSteps)
	{
		$doors = new Doors($this->prime, $numberOfDoors, $numberOfSteps);

		$this->assertEquals($doorStates, $doors->getStatesOfDoors());
	}

	public function providerDoors()
	{
		return array(
			array(array(1 => true), 1, 1),
			array(array(1 => true, 2 => false), 2, 2),
			array(array(1 => true, 2 => false, 3 => false), 3, 3),
		);
	}

}
