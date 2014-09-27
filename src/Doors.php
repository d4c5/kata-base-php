<?php

namespace Kata;

class Doors
{
	/**
	 * The number of doors.
	 *
	 * @var int
	 */
	private $numberOfDoors = 100;

	/**
	 * The number of steps.
	 *
	 * @var int
	 */
	private $numberOfSteps = 100;

	/**
	 * The prime object.
	 *
	 * @var Prime
	 */
	private $prime         = null;

	/**
	 * The states of doors.
	 *
	 * @var array
	 */
	private $doors         = array();

	/**
	 * Sets the parameters to member parameters.
	 *
	 * @param Prime $prime           The prime object.
	 * @param int   $numberOfDoors   The number of doors.
	 * @param int   $numberOfSteps   The number of steps.
	 *
	 * @return void
	 */
	public function __construct(Prime $prime, $numberOfDoors = 100, $numberOfSteps = 100)
	{
		$this->numberOfDoors = $numberOfDoors;
		$this->numberOfSteps = $numberOfSteps;
		$this->prime         = $prime;
	}

	/**
	 * Returns the states of doors.
	 *
	 * @return array
	 */
	public function getStatesOfDoors()
	{
		$this->checkNumbers();
		$this->initialize();
		$this->setStatesOfDoors();

		return $this->doors;
	}

	/**
	 * Checks the parameters (type and range).
	 *
	 * @return void
	 *
	 * @throws DoorsException
	 */
	private function checkNumbers()
	{
		if (!is_int($this->numberOfDoors))
		{
			throw new DoorsException(DoorsException::THE_NUMBER_OF_DOORS_IS_INVALID_INT);
		}
		if (!is_int($this->numberOfSteps))
		{
			throw new DoorsException(DoorsException::THE_NUMBER_OF_STEPS_IS_INVALID_INT);
		}
		if ($this->numberOfDoors < 1 || $this->numberOfDoors > PHP_INT_MAX)
		{
			throw new DoorsException(DoorsException::THE_NUMBER_OF_DOORS_IS_OUT_OF_RANGE);
		}
		if ($this->numberOfSteps < 1 || $this->numberOfSteps > PHP_INT_MAX)
		{
			throw new DoorsException(DoorsException::THE_NUMBER_OF_STEPS_IS_OUT_OF_RANGE);
		}
	}

	/**
	 * Sets the states of doors and corrects numbers.
	 *
	 * @return void
	 */
	private function initialize()
	{
		// If the number of steps is greater than number of doors, then the excess steps are unnecessary,
		// because the number of steps over the number of doors will not change the states of doors.
		if ($this->numberOfSteps > $this->numberOfDoors)
		{
			$this->numberOfSteps = $this->numberOfDoors;
		}

		// From 2, because the state of the first door is static.
		// True, because the first door opening sets this status.
		if ($this->numberOfDoors > 1)
		{
			$this->doors = array_fill(2, $this->numberOfDoors - 1, true);
		}
	}

	/**
	 * Sets the states of doors after steps.
	 *
	 * @return void
	 */
	private function setStatesOfDoors()
	{
		foreach (array_keys($this->doors) as $doorNumber)
		{
			// Gets the number of divisors.
			$numberOfDivisors = $this->getNumberOfDivisors($doorNumber);
			// If the number of divisors is even then the status will be false (closed),
			// if odd it will be true (opened), because repeatedly to be changed the states
			// as many as the number of divisors.
			$this->doors[$doorNumber] = ($numberOfDivisors % 2 == 1);
		}

		// Sets the skipped first door.
		$this->doors[1] = true;

		// Sorts the doors by number.
		ksort($this->doors);
	}

	/**
	 * Returns the number of divisors.
	 *
	 * @param int $number   Number.
	 *
	 * @return int
	 */
	private function getNumberOfDivisors($number)
	{
        $primes           = $this->prime->getPrimeDecomposition($number);
        $numberOfDivisors = 1;
        $canonicalFormat  = array_count_values($primes);
		// (k + 1) * (l + 1) * (n + 1) ... = numberOfDivisors
        foreach ($canonicalFormat as $exp)
        {
                $numberOfDivisors *= ($exp + 1);
        }

        return $numberOfDivisors;
	}

}
