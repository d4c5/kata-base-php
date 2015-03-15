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
	 * Sets data.
	 *
	 * @param array $data
	 *
	 * @return void
	 */
	public function setData(array $data)
	{
		$this->data = $data;
	}

	/**
	 * Returns data.
	 *
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Sets labels.
	 *
	 * @param array $labels
	 *
	 * @return void
	 */
	public function setLabels(array $labels)
	{
		$this->labels = $labels;
	}

	/**
	 * Returns labels.
	 *
	 * @return array
	 */
	public function getLabels()
	{
		return $this->labels;
	}

}
