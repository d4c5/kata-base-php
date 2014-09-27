<?php

namespace Kata\Test\Doors;

use Kata\Doors;
use Kata\Prime;

class DoorsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Prime object.
	 *
	 * @var Prime
	 */
	protected $prime = null;

	/**
	 * Sets the prime object.
	 */
	protected function setUp()
	{
		$this->prime = new Prime();
	}

	/**
	 * Tests that the number of doors is integer.
	 *
     * @expectedException Kata\DoorsException
     */
    public function testInvalidDoorsException()
    {
		$doors = new Doors($this->prime, 'D', 3);
		$doors->getStatesOfDoors();
    }

	/**
	 * Tests that the number of steps is integer.
	 *
     * @expectedException Kata\DoorsException
     */
    public function testInvalidStepsException()
    {
		$doors = new Doors($this->prime, 3, 'A');
		$doors->getStatesOfDoors();
    }

	/**
	 * Tests that the number of doors is in the allowed range.
	 *
     * @expectedException Kata\DoorsException
     */
    public function testOutOfRangeDoorsException()
    {
		$doors = new Doors($this->prime, -3, 3);
		$doors->getStatesOfDoors();
    }

	/**
	 * Tests that the number of steps is in the allowed range.
	 *
     * @expectedException Kata\DoorsException
     */
    public function testOutOfRangeStepsException()
    {
		$doors = new Doors($this->prime, 3, -3);
		$doors->getStatesOfDoors();
    }

	/**
	 * Tests the states of doors by number of doors and steps.
	 *
	 * @dataProvider providerDoors
	 */
	public function testStateOfDoors(array $doorStates, $numberOfDoors, $numberOfSteps)
	{
		$doors = new Doors($this->prime, $numberOfDoors, $numberOfSteps);

		$this->assertEquals($doorStates, $doors->getStatesOfDoors());
	}

	/**
	 * States of doors, number of doors, number of steps.
	 * 
	 * @return array
	 */
	public function providerDoors()
	{
		return array(
			array(array(1 => true), 1, 1),
			array(array(1 => true, 2 => false), 2, 2),
			array(array(1 => true, 2 => false, 3 => false), 3, 3),

			array(array(1 => true, 2 => false, 3 => false), 3, 10),
		);
	}

	/**
	 * @dataProvider providerOneDoorState
	 */
	public function testOneState($doorNumber, $doorState, $numberOfDoors, $numberOfSteps)
	{
		$doors = new Doors($this->prime, $numberOfDoors, $numberOfSteps);

		$statesOfDoors = $doors->getStatesOfDoors();

		$this->assertEquals($doorState, $statesOfDoors[$doorNumber]);
	}

	public function providerOneDoorState()
	{
		return array(
			array( 1, true,    1,   1),
			array( 9, true,  100, 100),
			array(26, false, 100, 100),
		);
	}

}
