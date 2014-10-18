<?php

namespace Kata\Test\Supermarket;

use Kata\Supermarket\ShoppingCart;
use Kata\Supermarket\Product;
use Kata\Supermarket\Discount\NoneDiscount;

class ShoppingCartTest extends \PHPUnit_Framework_TestCase
{
	/** Data of products. */
	const PRODUCT_APPLE_NAME  = 'Apple';
	const PRODUCT_APPLE_PRICE = 32;
	const PRODUCT_APPLE_UNIT  = 'kg';

	const PRODUCT_LIGHT_NAME  = 'Light';
	const PRODUCT_LIGHT_PRICE = 15;
	const PRODUCT_LIGHT_UNIT  = 'year';

	/**
	 * Shopping cart object.
	 *
	 * @var ShoppingCart
	 */
	private $shoppingCart = null;

	/**
	 * Sets the cart object.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->shoppingCart = new ShoppingCart();
	}

	/**
	 * Tests that the quantity in cart is negative.
	 *
     * @expectedException Kata\Supermarket\ShoppingCartException
	 * @expectedExceptionCode 301
     */
    public function testNegativeQuantityInCartException()
    {
		$appleProduct = $this->getAppleProduct();

		// Adds 5.0 kg apple to cart.
		$this->shoppingCart->modify($appleProduct, 5);

		// Removes 10 kg apple from cart.
		$this->shoppingCart->modify($appleProduct, -10);
    }

	/**
	 * Tests that the quantity in adding is negative.
	 *
     * @expectedException Kata\Supermarket\ShoppingCartException
	 * @expectedExceptionCode 302
     */
    public function testNegativeQuantityToAddException()
    {
		$lightProduct = $this->getLightProduct();

		// Adds -5 light to cart.
		$this->shoppingCart->modify($lightProduct, -5);
    }

	/**
	 * Tests adding and removing.
	 *
	 * @param array $productsToAdd
	 * @param array $productsInCart
	 *
	 * @dataProvider providerProductsToPurchase
	 */
	public function testAdding(array $productsToAdd, array $productsInCart)
	{
		foreach ($productsToAdd as $dataOfPurchase)
		{
			$this->shoppingCart->modify($dataOfPurchase['product'], $dataOfPurchase['quantity']);
		}

		// Number of products in the cart.
		$shoppingCartList = $this->shoppingCart->getShoppingCart();
		$this->assertEquals(count($productsInCart), count($shoppingCartList));

		$reindexedShoppingCartList = $this->reindexCart($shoppingCartList);

		// Quantities and types of products in the cart.
		foreach ($reindexedShoppingCartList as $number => $productToPurchase)
		{
			$this->assertEquals($productsInCart[$number]['quantity'], $productToPurchase->getQuantity());
			$this->assertEquals($productsInCart[$number]['product'],  $productToPurchase->getProduct());
		}
	}

	/**
	 * Test purchases.
	 *
	 * @return array
	 */
	public function providerProductsToPurchase()
	{
		$appleProduct = $this->getAppleProduct();
		$lightProduct = $this->getLightProduct();

		return array(
			array(
				// Products to add.
				array(
					array('product' => $appleProduct, 'quantity' => 10),
					array('product' => $lightProduct, 'quantity' => 5),
					array('product' => $appleProduct, 'quantity' => 5),
				),
				// Products in cart.
				array(
					array('product' => $appleProduct, 'quantity' => 15),
					array('product' => $lightProduct, 'quantity' => 5),
				),
			),
			array(
				// Products to add.
				array(
					array('product' => $appleProduct, 'quantity' => 10),
					array('product' => $appleProduct, 'quantity' => -5),
					array('product' => $appleProduct, 'quantity' => -5),
				),
				// Products in cart.
				array(),
			),
			array(
				// Products to add.
				array(
					array('product' => $appleProduct, 'quantity' => 10),
					array('product' => $lightProduct, 'quantity' => 5),
					array('product' => $appleProduct, 'quantity' => -10),
				),
				// Products in cart.
				array(
					array('product' => $lightProduct, 'quantity' => 5),
				),
			),
		);
	}

	/**
	 * Sets properties of the apple product.
	 *
	 * @param int $priceType
	 *
	 * @return Product
	 */
	private function getAppleProduct()
	{
		$appleDiscount = new NoneDiscount();

		$apple = new Product();
		$apple->setName(self::PRODUCT_APPLE_NAME);
		$apple->setPricePerUnit(self::PRODUCT_APPLE_PRICE);
		$apple->setUnit(self::PRODUCT_APPLE_UNIT);
		$apple->setDiscount($appleDiscount);

		return $apple;
	}

	/**
	 * Returns light product.
	 *
	 * @param int $priceType
	 *
	 * @return Product
	 */
	private function getLightProduct()
	{
		$lightDiscount = new NoneDiscount();

		$light = new Product();
		$light->setName(self::PRODUCT_LIGHT_NAME);
		$light->setPricePerUnit(self::PRODUCT_LIGHT_PRICE);
		$light->setUnit(self::PRODUCT_LIGHT_UNIT);
		$light->setDiscount($lightDiscount);

		return $light;
	}

	/**
	 * Reindexes the shopping cart to check.
	 *
	 * @param array $shoppingCart
	 *
	 * @return array
	 */
	private function reindexCart(array $shoppingCart)
	{
		return array_values($shoppingCart);
	}

}
