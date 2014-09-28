<?php

namespace Kata\Supermarket;

use Kata\Supermarket\Product;

/**
 * Product to purchase.
 */
class ProductToPurchase
{
	/**
	 * Product.
	 *
	 * @var Product
	 */
	private $product = null;

	/**
	 * The quantity of purchased amount.
	 *
	 * @var float
	 */
	private $quantity = 0.0;

	/**
	 * Sets the product.
	 *
	 * @param Product $product
	 *
	 * @return void
	 */
	public function setProduct(Product $product)
	{
		$this->product = $product;
	}

	/**
	 * Returns the product.
	 *
	 * @return Product
	 */
	public function getProduct()
	{
		return $this->product;
	}

	/**
	 * Sets the purchased quantity.
	 *
	 * @param float $quantity
	 *
	 * @return void
	 */
	public function setQuantity($quantity)
	{
		$this->quantity = $quantity;
	}

	/**
	 * Returns the purchased quantity.
	 *
	 * @return float
	 */
	public function getQuantity()
	{
		return $this->quantity;
	}

}
