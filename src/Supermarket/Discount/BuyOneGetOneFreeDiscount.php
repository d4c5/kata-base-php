<?php

namespace Kata\Supermarket\Discount;

use Kata\Supermarket\ProductToPurchase;

/**
 * BOGOF discount.
 */
class BuyOneGetOneFreeDiscount extends DiscountAbstract
{
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
			$product = $productToPurchase->getProduct();

			// D + F
			$discountGroupCount = $this->minimumQuantity + $this->freeQuantity;
			// intval(Q / (D + F)) * (D + F) = I
			$discountQuantity   = intval($productToPurchase->getQuantity() / $discountGroupCount) * $discountGroupCount;
			// D / (D + F) * P = R
			$discountPrice      = $this->minimumQuantity / $discountGroupCount * $product->getPricePerUnit();
			// Q - I
			$quantity           = $productToPurchase->getQuantity() - $discountQuantity;

			return $quantity * $product->getPricePerUnit() + $discountQuantity * $discountPrice;
		}
		else
		{
			return parent::getPrice($productToPurchase);
		}
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
