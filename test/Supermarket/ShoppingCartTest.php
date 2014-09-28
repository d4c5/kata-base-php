<?php

namespace Kata\Test\Supermarket;

use Kata\Supermarket\ShoppingCart;
use Kata\Supermarket\ProductToPurchase;

class ShoppingCartTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests the adding to shopping cart.
	 */
	public function testAdding()
	{
		$shoppingCart      = new ShoppingCart();
		$productToPurchase = new ProductToPurchase();

		$shoppingCart->add($productToPurchase);

		$shoppingCartList = $shoppingCart->getShoppingCart();
		$lastProductToPurchase = array_pop($shoppingCartList);

		$this->assertEquals($productToPurchase, $lastProductToPurchase);
	}

	/**
	 * Tests the removing from shopping cart.
	 */
	public function testRemoving()
	{
		$shoppingCart      = new ShoppingCart();
		$productToPurchase = new ProductToPurchase();

		$shoppingCartListBeforeAdding = $shoppingCart->getShoppingCart();

		$shoppingCart->add($productToPurchase);

		$shoppingCart->remove(0);

		$shoppingCartListAfterRemoving = $shoppingCart->getShoppingCart();

		$this->assertEquals($shoppingCartListBeforeAdding, $shoppingCartListAfterRemoving);
	}

}
