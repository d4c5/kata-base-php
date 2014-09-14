<?php

namespace Kata;

class Prime
{
	/**
	 * Returns number's prime decomposition.
	 *
	 * @param int $number
	 *
	 * @return array
	 */
	public function getPrimeDecomposition($number)
	{
		$primes = array();

		if ($number < 0)
		{
			$primes[] = -1;
			$number   = $number * (-1);
		}

		for ($i = 2; $i <= $number; $i++)
		{
			if ($number % $i === 0)
			{
				$primes[] = $i;
				$number  /= $i;
				$i       -= 1;
			}
		}

		return $primes;
	}

}
