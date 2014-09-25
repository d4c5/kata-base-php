<?php

namespace Kata;

class Doors
{
	private $numberOfSteps = 100;
	private $numberOfDoors = 100;
	private $prime         = null;

	private $doors         = array();

	public function __construct(Prime $prime, $numberOfDoors = 100, $numberOfSteps = 100)
	{
		$this->prime         = $prime;
		$this->numberOfDoors = (int)$numberOfDoors;
		$this->numberOfSteps = (int)$numberOfSteps;
	}

	public function getStatesOfDoors()
	{
		return array(true);
	}

}
