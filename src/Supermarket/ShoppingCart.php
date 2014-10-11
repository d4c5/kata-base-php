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
		// Reindex keys of array.
		return array_values($this->shoppingCart);
	}

	/**
	 * Returns the number of searched product in cart.
	 *
	 * @param ProductToPurchase $productToPurchase
	 *
	 * @return int|boolean
	 */
	private function getNumberOfProductToPurchaseInCart(ProductToPurchase $productToPurchase)
	{
		$searchedProduct = $productToPurchase->getProduct();

		$numberOfProductToPurchase = false;

		foreach ($this->shoppingCart as $number => $productToPurchaseInCart)
		{
			$product = $productToPurchaseInCart->getProduct();

			if ($searchedProduct == $product)
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
	 * @param ProductToPurchase $productToPurchase
	 *
	 * @return boolean
	 *
	 * @throws Exception
	 */
	public function modify(ProductToPurchase $productToPurchase)
	{
		$number      = $this->getNumberOfProductToPurchaseInCart($productToPurchase);
		$newQuantity = $productToPurchase->getQuantity();

		if ($number !== false)
		{
			$quantityInCart = $this->shoppingCart[$number]->getQuantity();

			// Deletes product from cart.
			if ($quantityInCart + $newQuantity == 0)
			{
				unset($this->shoppingCart[$number]);
			}
			// Modifies quantity in cart.
			elseif ($quantityInCart + $newQuantity > 0)
			{
				$this->shoppingCart[$number]->setQuantity($quantityInCart + $newQuantity);
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
			if ($newQuantity > 0)
			{
				$this->shoppingCart[] = $productToPurchase;
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
