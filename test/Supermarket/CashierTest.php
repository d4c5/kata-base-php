<?php

namespace Kata\Test\Supermarket;

use Kata\Supermarket\Cashier;
use Kata\Supermarket\ShoppingCart;
use Kata\Supermarket\Discount;
use Kata\Supermarket\Product;
use Kata\Supermarket\ProductToPurchase;

class CashierTest extends \PHPUnit_Framework_TestCase
{
	/** Products */
	const PRODUCT_APPLE    = 0;
	const PRODUCT_LIGHT    = 1;
	const PRODUCT_STARSHIP = 2;

	/**
	 * Products.
	 *
	 * @var array
	 */
	private $products = array();

	/**
	 * Sets the products and discounts.
	 */
	protected function setUp()
	{
		$this->setAppleProduct();
		$this->setLightProduct();
		$this->setStarshipProduct();
	}

	/**
	 * Sets properties of the apple product.
	 *
	 * @return void
	 */
	private function setAppleProduct()
	{
		$appleDiscount = new Discount();
		$appleDiscount->setType(Discount::DISCOUNT_LESSER_PRICE);
		$appleDiscount->setMinimumQuantity(5.0);
		$appleDiscount->setDiscountPricePerUnit(25);

		$apple = new Product();
		$apple->setName('Apple');
		$apple->setPricePerUnit(32);
		$apple->setUnit('kg');
		$apple->setDiscount($appleDiscount);

		$this->products[self::PRODUCT_APPLE] = $apple;
	}

	/**
	 * Sets properties of the light product.
	 */
	private function setLightProduct()
	{
		$light = new Product();
		$light->setName('Light');
		$light->setPricePerUnit(15);
		$light->setUnit('year');

		$this->products[self::PRODUCT_LIGHT] = $light;
	}

	/**
	 * Sets properties of the starship product.
	 */
	private function setStarshipProduct()
	{
		$starshipDiscount = new Discount();
		$starshipDiscount->setType(Discount::DISCOUNT_TWO_PAID_ONE_FREE);
		$starshipDiscount->setMinimumQuantity(2);
		$starshipDiscount->setFreeQuantity(1);

		$starship = new Product();
		$starship->setName('Starship');
		$starship->setPricePerUnit(999.99);
		$starship->setUnit('piece');
		$starship->setDiscount($starshipDiscount);

		$this->products[self::PRODUCT_STARSHIP] = $starship;
	}

	/**
	 * Tests the calculation of the cashier.
	 *
	 * @param float $totalPrice
	 * @param array $purchases
	 *
	 * @dataProvider providerPurchase
	 */
	public function testTotalPrice($totalPrice, $purchases)
	{
		$shoppingCart = new ShoppingCart();

		foreach ($purchases as $product => $quantity)
		{
			$productToPurchase = new ProductToPurchase();
			$productToPurchase->setProduct($this->products[$product]);
			$productToPurchase->setQuantity($quantity);

			$shoppingCart->add($productToPurchase);
		}

		$cashier = new Cashier($shoppingCart);

		$this->assertEquals($totalPrice, $cashier->getTotalPrice());
	}

	/**
	 * Test purchases.
	 *
	 * Total price, (product => quantity).
	 *
	 * @return array
	 */
	public function providerPurchase()
	{
		return array(
			array( 64,    array(self::PRODUCT_APPLE    => 2.0)),
			array(107,    array(self::PRODUCT_LIGHT    => 5, self::PRODUCT_APPLE => 1.0)),
			array(999.99, array(self::PRODUCT_STARSHIP => 1)),
		);
	}

}
