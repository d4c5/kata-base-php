<?php

namespace Kata\Supermarket;

use Kata\Supermarket\ShoppingCart;

/**
 * Cashier.
 */
class Cashier
{
	private $shoppingCart = array();

	public function __construct(ShoppingCart $shoppingCart)
	{
		$this->shoppingCart = $shoppingCart;
	}

	public function getTotalPrice()
	{
		$totalPrice = 0.0;

		// Total price without discounts.
		foreach ($this->shoppingCart->getShoppingCart() as $productToPurchase)
		{
			$product    = $productToPurchase->getProduct();
			// $discount   = $product->getDiscount();

			$totalPrice += $productToPurchase->getQuantity() * $product->getPricePerUnit();
		}

		return $totalPrice;
	}

}
