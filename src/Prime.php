<?php

namespace Kata;

class Prime
{
	public function getPrimes($number)
	{
		$primes = array();

		for ($i = 2; $i <= $number; $i++)
		{
			if ($number % $i === 0)
			{
				$primes[] = $i;
				$number /= $i;
				$i--;
			}
		}

		return $primes;
	}
}