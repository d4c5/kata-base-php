<?php

namespace Kata\Supermarket\Discount;

use Kata\Supermarket\ProductToPurchase;

/**
 * Discount abstract.
 */
abstract class DiscountAbstract
{
	/**
	 * Returns normal price.
	 *
	 * @param ProductToPurchase $productToPurchase
	 *
	 * @return float
	 */
	public function getPrice(ProductToPurchase $productToPurchase)
	{
		$product = $productToPurchase->getProduct();

		return $productToPurchase->getQuantity() * $product->getPricePerUnit();
	}

}
