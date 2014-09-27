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
	 * Creates a map of arguments to return values.
	 *
	 * @var array
	 */
    protected $primeMap = array(
		array(  1, array(0 => 1)),
		array(  2, array(0 => 2)),
		array(  3, array(0 => 3)),
		array(  4, array(0 => 2, 1 => 2)),
		array(  5, array(0 => 5)),
		array(  6, array(0 => 2, 1 => 3)),
		array(  7, array(0 => 7)),
		array(  8, array(0 => 2, 1 => 2, 2 => 2)),
		array(  9, array(0 => 3, 1 => 3)),
		array( 10, array(0 => 2, 1 => 5)),
	);

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
		// Creates a stub.
		$stub = $this->getMock('\Kata\Prime', array('getPrimeDecomposition'));
		$stub->expects($this->any())
				->method('getPrimeDecomposition')
				->will($this->returnValueMap($this->primeMap));

		$doors = new Doors($stub, $numberOfDoors, $numberOfSteps);

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
	 * Tests status of one door.
	 *
	 * @dataProvider providerOneDoorState
	 */
	public function testOneState($doorNumber, $doorState, $numberOfDoors, $numberOfSteps)
	{
		$doors = new Doors($this->prime, $numberOfDoors, $numberOfSteps);

		$statesOfDoors = $doors->getStatesOfDoors();

		$this->assertEquals($doorState, $statesOfDoors[$doorNumber]);
	}

	/**
	 * The number of door, the status of door, the number of doors and the number of steps.
	 *
	 * @return array
	 */
	public function providerOneDoorState()
	{
		return array(
			array( 1, true,    1,   1),
			array( 9, true,  100, 100),
			array(26, false, 100, 100),
		);
	}

}
