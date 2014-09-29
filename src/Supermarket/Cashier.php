<?php

namespace Kata\Supermarket;

use Kata\Supermarket\ShoppingCart;
use Kata\Supermarket\ProductToPurchase;

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

			$totalPrice += $discount->getPrice($productToPurchase);
		}

		return $totalPrice;
	}

}
