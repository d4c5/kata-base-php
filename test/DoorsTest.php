<?php

namespace Kata\Test\Doors;

use Kata\Doors;
use Kata\Prime;

class DoorsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider providerDoors
	 */
	public function testStatesOfDoors(array $doorStates, $numberOfDoors, $numberOfSteps)
	{
		$prime = new Prime();
		$doors = new Doors($prime, $numberOfDoors, $numberOfSteps);

		$this->assertEquals($doorStates, $doors->getStatesOfDoors());
	}

	public function providerDoors()
	{
		return array(
			array(array(1 => true), 1, 1),
		);
	}

}
