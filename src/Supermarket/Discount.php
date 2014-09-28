<?php

namespace Kata\Supermarket;

/**
 * Discount.
 */
class Discount
{
	/** The type of discounts */
	const DISCOUNT_NONE              = 0;
	const DISCOUNT_LESSER_PRICE      = 1;
	const DISCOUNT_TWO_PAID_ONE_FREE = 2;

	/**
	 * The type of the discount.
	 *
	 * @var int
	 */
	private $type = self::DISCOUNT_NONE;

	/**
	 * The discount price per unit.
	 *
	 * @var float
	 */
	private $discountPricePerUnit = 0.0;

	/**
	 * The minimum quantity to discount.
	 *
	 * @var float
	 */
	private $minimumQuantity = 0.0;

	/**
	 * The quantity of the free product(s).
	 *
	 * @var float
	 */
	private $freeQuantity = 0.0;

	/**
	 * Sets the type of the discount.
	 *
	 * @param int $type
	 *
	 * @return void
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * Returns the type of the discount.
	 *
	 * @return int
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Sets the discount price per unit.
	 *
	 * @param float $discountPricePerUnit
	 *
	 * @return void
	 */
	public function setDiscountPricePerUnit($discountPricePerUnit)
	{
		$this->discountPricePerUnit = $discountPricePerUnit;
	}

	/**
	 * Returns the discount price per unit.
	 *
	 * @return float
	 */
	public function getDiscountPricePerUnit()
	{
		return $this->discountPricePerUnit;
	}

	/**
	 * Sets the minimum quantity to discount.
	 *
	 * @param float $minimumQuantity
	 *
	 * @return void
	 */
	public function setMinimumQuantity($minimumQuantity)
	{
		$this->minimumQuantity = $minimumQuantity;
	}

	/**
	 * Returns the minimum quantity to discount.
	 *
	 * @return float
	 */
	public function getMinimumQuantity()
	{
		return $this->minimumQuantity;
	}

	/**
	 * Sets the free quantity of discount.
	 *
	 * @param float $freeQuantity
	 *
	 * @return void
	 */
	public function setFreeQuantity($freeQuantity)
	{
		$this->freeQuantity = $freeQuantity;
	}

	/**
	 * Returns the free quantity of discount.
	 *
	 * @return float
	 */
	public function getFreeQuantity()
	{
		return $this->freeQuantity;
	}

}
