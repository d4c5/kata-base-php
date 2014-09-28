<?php

namespace Kata\Supermarket;

use Kata\Supermarket\ShoppingCart;
use Kata\Supermarket\ProductToPurchase;
use Kata\Supermarket\Discount;

/**
 * Cashier.
 */
class Cashier
{
	/**
	 * Shopping cart.
	 *
	 * @var array
	 */
	private $shoppingCart = array();

	/**
	 * Sets shopping cart.
	 *
	 * @param ShoppingCart $shoppingCart
	 *
	 * @return void
	 */
	public function __construct(ShoppingCart $shoppingCart)
	{
		$this->shoppingCart = $shoppingCart;
	}

	/**
	 * Returns total price.
	 *
	 * @return float
	 */
	public function getTotalPrice()
	{
		$totalPrice = 0.0;

		foreach ($this->shoppingCart->getShoppingCart() as $productToPurchase)
		{
			$product  = $productToPurchase->getProduct();
			$discount = $product->getDiscount();

			if ($discount->getType() == Discount::DISCOUNT_LESSER_PRICE)
			{
				$totalPrice += $this->getDiscountLesserPrice($productToPurchase);
			}
			elseif ($discount->getType() == Discount::DISCOUNT_TWO_PAID_ONE_FREE)
			{
				$totalPrice += $this->getDiscountTwoPaidOneFreePrice($productToPurchase);
			}
			else
			{
				$totalPrice += $this->getNormalPrice($productToPurchase);
			}
		}

		return $totalPrice;
	}

	/**
	 * Returns discount price.
	 *
	 * @param ProductToPurchase $productToPurchase
	 *
	 * @return float
	 */
	private function getDiscountLesserPrice(ProductToPurchase $productToPurchase)
	{
		$price    = 0.0;
		$product  = $productToPurchase->getProduct();
		$discount = $product->getDiscount();

		if ($productToPurchase->getQuantity() > $discount->getMinimumQuantity())
		{
			$price = $productToPurchase->getQuantity() * $discount->getDiscountPricePerUnit();
		}
		else
		{
			$price = $this->getNormalPrice($productToPurchase);
		}

		return $price;
	}

	/**
	 * Returns discount price.
	 *
	 * @param ProductToPurchase $productToPurchase
	 *
	 * @return float
	 */
	private function getDiscountTwoPaidOneFreePrice(ProductToPurchase $productToPurchase)
	{
		$price    = 0.0;
		$product  = $productToPurchase->getProduct();
		$discount = $product->getDiscount();

		if ($productToPurchase->getQuantity() > $discount->getMinimumQuantity())
		{
			// D + F
			$discountGroupCount = $discount->getMinimumQuantity() + $discount->getFreeQuantity();
			// intval(Q / (D + F)) * (D + F) = I
			$discountQuantity   = intval($productToPurchase->getQuantity() / $discountGroupCount) * $discountGroupCount;
			// D / (D + F) * P = R
			$discountPrice      = $discount->getMinimumQuantity() / $discountGroupCount * $product->getPricePerUnit();
			// Q - I
			$quantity           = $productToPurchase->getQuantity() - $discountQuantity;

			$price = $quantity * $product->getPricePerUnit() + $discountQuantity * $discountPrice;
		}
		else
		{
			$price = $this->getNormalPrice($productToPurchase);
		}

		return $price;
	}

	/**
	 * Returns normal price.
	 *
	 * @param ProductToPurchase $productToPurchase
	 *
	 * @return float
	 */
	private function getNormalPrice(ProductToPurchase $productToPurchase)
	{
		$product = $productToPurchase->getProduct();

		return $productToPurchase->getQuantity() * $product->getPricePerUnit();
	}

}
