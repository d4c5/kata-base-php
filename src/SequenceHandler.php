<?php

namespace Kata;

class SequenceHandler
{
	/**
	 * Returns minimum value of a sequence.
	 *
	 * @param array $sequence   A sequence of integer numbers.
	 *
	 * @return int|null
	 */
	public function getMinimumValue(array $sequence)
	{
		if (empty($sequence))
		{
			return null;
		}

		return min($sequence);
	}

	/**
	 * Returns maximum value of a sequence.
	 *
	 * @param array $sequence   A sequence of integer numbers.
	 *
	 * @return int|null
	 */
	public function getMaximumValue(array $sequence)
	{
		if (empty($sequence))
		{
			return null;
		}

		return max($sequence);
	}

	/**
	 * Returns number of elements.
	 *
	 * @param array $sequence   A sequence of integer numbers.
	 *
	 * @return int
	 */
	public function getNumberOfElements(array $sequence)
	{
		return count($sequence);
	}

	/**
	 * Returns average of a sequence.
	 *
	 * @param array $sequence   A sequence of integer numbers.
	 *
	 * @return float|null
	 */
	public function getAverage(array $sequence)
	{
		if (empty($sequence))
		{
			return null;
		}

		return array_sum($sequence) / count($sequence);
	}

}
