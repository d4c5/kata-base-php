<?php

/**
 * Result class.
 *
 * @package Kata
 * @subpackage StringToArray
 */

namespace Kata\StringToArray;

/**
 * Converts the given string to an array.
 */
class Result
{
	/**
	 * Labels.
	 *
	 * @var array
	 */
	private $labels = array();

	/**
	 * Data.
	 *
	 * @var array
	 */
	private $data   = array();

	/**
	 * Sets data and labels.
	 *
	 * @param array $data
	 * @param array $labels
	 *
	 * @return void
	 */
	public function __construct(array $data = array(), array $labels = array())
	{
		$this->labels = $labels;
		$this->data   = $data;
	}

	/**
	 * Returns the result in an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		$array = array();

		if (!empty($this->labels))
		{
			$array = array(
				'labels' => $this->labels,
				'data'   => $this->data,
			);
		}
		else
		{
			$array = $this->data;
		}

		return $array;
	}

}
