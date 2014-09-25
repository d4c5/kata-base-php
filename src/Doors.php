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
		$this->numberOfSteps = (int)$numberOfSteps;
		$this->numberOfDoors = (int)$numberOfDoors;
		$this->prime         = $prime;
	}

	public function getStatesOfDoors()
	{
		$this->checkNumbers();
		$this->initialize();
		$this->setStatesOfDoors();

		return $this->doors;
	}

	private function checkNumbers()
	{
		if ($this->numberOfSteps < 0 || $this->numberOfSteps > PHP_INT_MAX)
		{
			throw new LimitExceededException(__METHOD__ . " - A lepesek szamanak 1 es " . PHP_INT_MAX . " koze kell esnie!");
		}
		if ($this->numberOfDoors < 0 || $this->numberOfDoors > PHP_INT_MAX)
		{
			throw new LimitExceededException(__METHOD__ . " - Az ajtok szama 1 es " . PHP_INT_MAX . " koze kell esnie!");
		}
	}

	private function initialize()
	{
		// Ha nagyobb a lepesek szama, mint az ajtok szama, akkor a tobblet lepesnek nincs ertelme,
		// hiszen az ajtok szama feletti lepesszamnal nem fog valtozni az ajtok allapota
		if ($this->numberOfSteps > $this->numberOfDoors)
		{
			$this->numberOfSteps = $this->numberOfDoors;
		}

		// 2-tol, mert az elso ajto az alapallapotban marad
		// true - mert az elso autonyitast el is vegeztuk
		if ($this->numberOfDoors > 1)
		{
			$this->doors = array_fill(2, $this->numberOfDoors - 1, true);
		}
	}

	private function setStatesOfDoors()
	{
		foreach (array_keys($this->doors) as $doorNumber)
		{
			// Az osztok szamanak lekerdezese
			$numberOfDivisors = $this->getNumberOfDivisors($doorNumber);
			// Ha az osztok szama paros, akkor false a vegeredmeny, ha paratlan, akkor true
			// hiszen ahany osztoja van, annyiszor kellett valtoztatni az allapotot
			$this->doors[$doorNumber] = ($numberOfDivisors % 2 == 1);
		}

		// A kihagyott 1-es beallitasa
		$this->doors[1] = true;

		ksort($this->doors);
	}

	public function getNumberOfDivisors($number)
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

class LimitExceededException extends \Exception
{

}