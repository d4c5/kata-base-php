<?php

namespace Kata\Supermarket;

/**
 * Product.
 */
class Product
{
	/**
	 * The name of the product.
	 *
	 * @var string
	 */
	private $name = '';

	/**
	 * The unit of the product.
	 *
	 * @var string
	 */
	private $unit = '';

	/**
	 * The price of one unit product.
	 *
	 * @var float
	 */
	private $pricePerUnit = 0.0;

	/**
	 * Discount.
	 *
	 * @var Discount
	 */
	private $discount = null;

	/**
	 * Sets the name of the product.
	 *
	 * @param string $name
	 *
	 * @return void
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Returns the name of the product.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Sets the unit of the product.
	 *
	 * @param string $unit
	 *
	 * @return void
	 */
	public function setUnit($unit)
	{
		$this->unit = $unit;
	}

	/**
	 * Returns the unit of the product.
	 *
	 * @return string
	 */
	public function getUnit()
	{
		return $this->unit;
	}

	/**
	 * Sets the price of one unit product.
	 *
	 * @param float $pricePerUnit
	 *
	 * @return void
	 */
	public function setPricePerUnit($pricePerUnit)
	{
		$this->pricePerUnit = $pricePerUnit;
	}

	/**
	 * Returns the price of one unit product.
	 *
	 * @return float
	 */
	public function getPricePerUnit()
	{
		return $this->pricePerUnit;
	}

	/**
	 * Sets discount.
	 *
	 * @param Discount $discount
	 *
	 * @return void
	 */
	public function setDiscount($discount)
	{
		$this->discount = $discount;
	}

	/**
	 * Returns discount.
	 *
	 * @return Discount
	 */
	public function getDiscount()
	{
		return $this->discount;
	}

}
