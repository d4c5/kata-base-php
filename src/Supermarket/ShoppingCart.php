<?php

namespace Kata\Supermarket;

use Kata\Supermarket\ProductToPurchase;

/**
 * Shopping cart.
 */
class ShoppingCart
{
	/**
	 * Shopping cart which contains products to purchase.
	 *
	 * @var array
	 */
	private $shoppingCart = array();

	/**
	 * Returns the content of the shopping cart.
	 *
	 * @return array
	 */
	public function getShoppingCart()
	{
		return $this->shoppingCart;
	}

	/**
	 * Adds a product to purchase.
	 *
	 * @param ProductToPurchase $productToPurchase
	 *
	 * @return void
	 */
	public function add(ProductToPurchase $productToPurchase)
	{
		$this->shoppingCart[] = $productToPurchase;
	}

	/**
	 * Remove a product from purchase.
	 *
	 * @param int $numberOfPurchase   The number of the product to purchase.
	 *
	 * @return void
	 */
	public function remove($numberOfPurchase)
	{
		unset($this->shoppingCart[$numberOfPurchase]);
	}

}
