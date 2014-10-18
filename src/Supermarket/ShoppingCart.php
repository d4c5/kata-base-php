<?php

namespace Kata\Supermarket;

use Kata\Supermarket\Product;
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
	 * Returns the number of searched product in cart.
	 *
	 * @param Product $product
	 * @param float   $quantity
	 *
	 * @return int|null
	 */
	private function getNumberOfProductToPurchaseInCart(Product $product)
	{
		$numberOfProductToPurchase = null;

		foreach ($this->shoppingCart as $number => $productToPurchaseInCart)
		{
			$productInCart = $productToPurchaseInCart->getProduct();

			if ($product == $productInCart)
			{
				$numberOfProductToPurchase = $number;
				break;
			}
		}

		return $numberOfProductToPurchase;
	}

	/**
	 * Modify the products in cart.
	 *
	 * @param Product $product
	 * @param float   $quantity
	 *
	 * @return boolean
	 *
	 * @throws Exception
	 */
	public function modify(Product $product, $quantity)
	{
		$number = $this->getNumberOfProductToPurchaseInCart($product);

		if (!is_null($number))
		{
			$quantityInCart = $this->shoppingCart[$number]->getQuantity();

			// Deletes product from cart.
			if ($quantityInCart + $quantity == 0)
			{
				unset($this->shoppingCart[$number]);
			}
			// Modifies quantity in cart.
			elseif ($quantityInCart + $quantity > 0)
			{
				$this->shoppingCart[$number]->setQuantity($quantityInCart + $quantity);
			}
			// Invalid quantity.
			else
			{
				throw new ShoppingCartException(ShoppingCartException::NEGATIV_QUANTITY_IN_CART);
			}
		}
		else
		{
			// Adds product to cart.
			if ($quantity > 0)
			{
				$this->shoppingCart[] = new ProductToPurchase($product, $quantity);
			}
			// Product to purchase with invalid quantity.
			else
			{
				throw new ShoppingCartException(ShoppingCartException::NEGATIV_OR_ZERO_QUANTITY_TO_ADD);
			}
		}

		return true;
	}

}
