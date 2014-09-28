<?php

namespace Kata\Test\Supermarket;

use Kata\Supermarket\Cashier;
use Kata\Supermarket\Discount;
use Kata\Supermarket\Product;
use Kata\Supermarket\ProductToPurchase;

class CashierTest extends \PHPUnit_Framework_TestCase
{
	/** Data of products and discounts. */
	const PRODUCT_APPLE_ID    = 0;
	const PRODUCT_APPLE_NAME  = 'Name';
	const PRODUCT_APPLE_PRICE = 32;
	const PRODUCT_APPLE_UNIT  = 'kg';

	const DISCOUNT_APPLE_TYPE         = Discount::DISCOUNT_LESSER_PRICE;
	const DISCOUNT_APPLE_MIN_QUANTITY = 5.0;
	const DISCOUNT_APPLE_PRICE        = 25;

	const PRODUCT_LIGHT_ID    = 1;
	const PRODUCT_LIGHT_NAME  = 'Light';
	const PRODUCT_LIGHT_PRICE = 15;
	const PRODUCT_LIGHT_UNIT  = 'year';

	const DISCOUNT_LIGHT_TYPE = Discount::DISCOUNT_NONE;

	const PRODUCT_STARSHIP_ID    = 2;
	const PRODUCT_STARSHIP_NAME  = 'Starship';
	const PRODUCT_STARSHIP_PRICE = 999.99;
	const PRODUCT_STARSHIP_UNIT  = 'piece';

	const DISCOUNT_STARSHIP_TYPE          = Discount::DISCOUNT_TWO_PAID_ONE_FREE;
	const DISCOUNT_STARSHIP_MIN_QUANTITY  = 2;
	const DISCOUNT_STARSHIP_FREE_QUANTITY = 1;

	/**
	 * Products.
	 *
	 * @var array
	 */
	private $products = array();

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
		$stub = $this->getMock('\Kata\Supermarket\ShoppingCart', array('getShoppingCart'));
		$stub->expects($this->any())
				->method('getShoppingCart')
				->will($this->returnValue($purchases));

		$cashier = new Cashier($stub);

		$this->assertEquals($totalPrice, $cashier->getTotalPrice());
	}

	/**
	 * Sets properties of the apple product.
	 *
	 * @return void
	 */
	private function setAppleProduct()
	{
		$appleDiscount = new Discount();
		$appleDiscount->setType(self::DISCOUNT_APPLE_TYPE);
		$appleDiscount->setMinimumQuantity(self::DISCOUNT_APPLE_MIN_QUANTITY);
		$appleDiscount->setDiscountPricePerUnit(self::DISCOUNT_APPLE_PRICE);

		$apple = new Product();
		$apple->setName(self::PRODUCT_APPLE_NAME);
		$apple->setPricePerUnit(self::PRODUCT_APPLE_PRICE);
		$apple->setUnit(self::PRODUCT_APPLE_UNIT);
		$apple->setDiscount($appleDiscount);

		$this->products[self::PRODUCT_APPLE_ID] = $apple;
	}

	/**
	 * Sets properties of the light product.
	 *
	 * @return void
	 */
	private function setLightProduct()
	{
		$lightDiscount = new Discount();
		$lightDiscount->setType(self::DISCOUNT_LIGHT_TYPE);

		$light = new Product();
		$light->setName(self::PRODUCT_LIGHT_NAME);
		$light->setPricePerUnit(self::PRODUCT_LIGHT_PRICE);
		$light->setUnit(self::PRODUCT_LIGHT_UNIT);
		$light->setDiscount($lightDiscount);

		$this->products[self::PRODUCT_LIGHT_ID] = $light;
	}

	/**
	 * Sets properties of the starship product.
	 *
	 * @return void
	 */
	private function setStarshipProduct()
	{
		$starshipDiscount = new Discount();
		$starshipDiscount->setType(self::DISCOUNT_STARSHIP_TYPE);
		$starshipDiscount->setMinimumQuantity(self::DISCOUNT_STARSHIP_MIN_QUANTITY);
		$starshipDiscount->setFreeQuantity(self::DISCOUNT_STARSHIP_FREE_QUANTITY);

		$starship = new Product();
		$starship->setName(self::PRODUCT_STARSHIP_NAME);
		$starship->setPricePerUnit(self::PRODUCT_STARSHIP_PRICE);
		$starship->setUnit(self::PRODUCT_STARSHIP_UNIT);
		$starship->setDiscount($starshipDiscount);

		$this->products[self::PRODUCT_STARSHIP_ID] = $starship;
	}

	/**
	 * Returns normal test purchases.
	 *
	 * @return array
	 */
	private function getNormalTestData()
	{
		$purchases = array();

		$test1 = new ProductToPurchase();
		$test1->setProduct($this->products[self::PRODUCT_APPLE_ID]);
		$test1->setQuantity(2.0);

		$purchases[] = array(64, array($test1));


		$test21 = new ProductToPurchase();
		$test21->setProduct($this->products[self::PRODUCT_LIGHT_ID]);
		$test21->setQuantity(5);

		$test22 = new ProductToPurchase();
		$test22->setProduct($this->products[self::PRODUCT_APPLE_ID]);
		$test22->setQuantity(1.0);

		$purchases[] = array(107, array($test21, $test22));


		$test3 = new ProductToPurchase();
		$test3->setProduct($this->products[self::PRODUCT_STARSHIP_ID]);
		$test3->setQuantity(1);

		$purchases[] = array(999.99, array($test3));

		return $purchases;
	}

	/**
	 * Returns discount test purchases.
	 *
	 * @return array
	 */
	private function getDiscountTestData()
	{
		$purchases = array();

		$test4 = new ProductToPurchase();
		$test4->setProduct($this->products[self::PRODUCT_APPLE_ID]);
		$test4->setQuantity(7.0);

		$purchases[] = array(175, array($test4));


		$test51 = new ProductToPurchase();
		$test51->setProduct($this->products[self::PRODUCT_LIGHT_ID]);
		$test51->setQuantity(5);

		$test52 = new ProductToPurchase();
		$test52->setProduct($this->products[self::PRODUCT_APPLE_ID]);
		$test52->setQuantity(8.0);

		$purchases[] = array(275, array($test51, $test52));


		$test6 = new ProductToPurchase();
		$test6->setProduct($this->products[self::PRODUCT_STARSHIP_ID]);
		$test6->setQuantity(6);

		$purchases[] = array(3999.96, array($test6));

		return $purchases;
	}

	/**
	 * Test purchases.
	 *
	 * Total price, the content of shopping cart.
	 *
	 * @return array
	 */
	public function providerPurchase()
	{
		$this->setAppleProduct();
		$this->setLightProduct();
		$this->setStarshipProduct();

		$normalPurchases   = $this->getNormalTestData();
		$discountPurchases = $this->getDiscountTestData();

		return array_merge(
			$normalPurchases,
			$discountPurchases
		);
	}

}
