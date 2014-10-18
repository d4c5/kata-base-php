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
	 * Sets the product and quantity.
	 *
	 * @param Product $product
	 * @param int     $quantity
	 *
	 * @throws ShoppingCartException
	 */
	public function __construct(Product $product, $quantity)
	{
		if (!is_numeric($quantity))
		{
			throw new ProductToPurchaseException(ProductToPurchaseException::NOT_NUMERIC_QUANTITY);
		}

		$this->product  = $product;
		$this->quantity = $quantity;
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
