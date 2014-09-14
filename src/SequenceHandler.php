<?php

namespace Kata;

class SequenceHandler
{
	public function getMinimumValue(array $sequence)
	{
		return min($sequence);
	}

	public function getMaximumValue(array $sequence)
	{
		return max($sequence);
	}

	public function getNumberOfElements(array $sequence)
	{
		return count($sequence);
	}

	public function getAverage(array $sequence)
	{
		return array_sum($sequence) / count($sequence);
	}

}
