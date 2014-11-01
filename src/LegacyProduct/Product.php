<?php

namespace Kata\LegacyProduct;

/**
* Product data object / value object.
*/
class Product
{
	/**
	 * Sets data of products.
	 *
	 * @param int    $id
	 * @param string $ean
	 * @param string $name
	 *
	 * @return void
	 */
	public function __construct($id = null, $ean = null, $name = null)
	{
		$this->id   = $id;
		$this->ean  = $ean;
		$this->name = $name;
	}

	/**
	* @var int
	*/
	public $id;

	/**
	* @var string
	*/
	public $ean;

	/**
	* @var string
	*/
	public $name;
}
