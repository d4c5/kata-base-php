<?php

namespace Kata\Supermarket\Discount;

use Kata\Supermarket\ProductToPurchase;

/**
 * Lesser price discount.
 */
class LesserPriceDiscount extends DiscountAbstract
{
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
	 * Returns reduced price by discount.
	 *
	 * @param ProductToPurchase $productToPurchase
	 *
	 * @return float
	 */
	public function getPrice(ProductToPurchase $productToPurchase)
	{
		if ($productToPurchase->getQuantity() > $this->minimumQuantity)
		{
			return $productToPurchase->getQuantity() * $this->discountPricePerUnit;
		}
		else
		{
			return parent::getPrice($productToPurchase);
		}
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

}
