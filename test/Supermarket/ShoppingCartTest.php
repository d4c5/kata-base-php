<?php

namespace Kata\Test\Supermarket;

use Kata\Supermarket\ShoppingCart;
use Kata\Supermarket\ProductToPurchase;
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
		$productToPurchase1 = new ProductToPurchase();
		$productToPurchase1->setProduct($appleProduct);
		$productToPurchase1->setQuantity(5);

		$this->shoppingCart->modify($productToPurchase1);

		// Removes 10 kg apple from cart.
		$productToPurchase2 = new ProductToPurchase();
		$productToPurchase2->setProduct($appleProduct);
		$productToPurchase2->setQuantity(-10);

		$this->shoppingCart->modify($productToPurchase2);
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
		$productToPurchase1 = new ProductToPurchase();
		$productToPurchase1->setProduct($lightProduct);
		$productToPurchase1->setQuantity(-5);

		$this->shoppingCart->modify($productToPurchase1);
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
			$productToPurchase = new ProductToPurchase();
			$productToPurchase->setProduct($dataOfPurchase['product']);
			$productToPurchase->setQuantity($dataOfPurchase['quantity']);

			$this->shoppingCart->modify($productToPurchase);
		}

		// Number of products in the cart.
		$shoppingCartList = $this->shoppingCart->getShoppingCart();
		$this->assertEquals(count($productsInCart), count($shoppingCartList));

		// Quantities and types of products in the cart.
		foreach ($shoppingCartList as $number => $productToPurchase)
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

}
