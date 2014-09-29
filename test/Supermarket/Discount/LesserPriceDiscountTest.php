<?php

namespace Kata\Test\Supermarket\Discount;

use Kata\Supermarket\Discount\LesserPriceDiscount;
use Kata\Supermarket\Product;
use Kata\Supermarket\ProductToPurchase;

class LesserPriceDiscountTest extends \PHPUnit_Framework_TestCase
{
	/** Data of products and discounts. */
	const PRODUCT_APPLE_ID    = 0;
	const PRODUCT_APPLE_NAME  = 'Name';
	const PRODUCT_APPLE_PRICE = 32;
	const PRODUCT_APPLE_UNIT  = 'kg';

	const DISCOUNT_APPLE_MIN_QUANTITY = 5.0;
	const DISCOUNT_APPLE_PRICE        = 25;

	/**
	 * The test product.
	 *
	 * @var Product
	 */
	private $apple = null;

	/**
	 * Tests purchases with discount price.
	 *
	 * @param float             $totalPrice
	 * @param ProductToPurchase $productToPurchase
	 *
	 * @dataProvider providerDiscountPurchases
	 */
	public function testDiscountPrice($totalPrice, $productToPurchase)
	{
		$product  = $productToPurchase->getProduct();
		$discount = $product->getDiscount();

		$this->assertEquals($totalPrice, $discount->getPrice($productToPurchase));
	}

	/**
	 * Sets properties of the apple product.
	 *
	 * @return void
	 */
	private function setAppleProduct()
	{
		$appleDiscount = new LesserPriceDiscount();
		$appleDiscount->setMinimumQuantity(self::DISCOUNT_APPLE_MIN_QUANTITY);
		$appleDiscount->setDiscountPricePerUnit(self::DISCOUNT_APPLE_PRICE);

		$this->apple = new Product();
		$this->apple->setName(self::PRODUCT_APPLE_NAME);
		$this->apple->setPricePerUnit(self::PRODUCT_APPLE_PRICE);
		$this->apple->setUnit(self::PRODUCT_APPLE_UNIT);
		$this->apple->setDiscount($appleDiscount);
	}

	/**
	 * Creates test purchases.
	 *
	 * @return array
	 */
	public function providerDiscountPurchases()
	{
		$this->setAppleProduct();

		$purchases = array();

		$test1 = new ProductToPurchase();
		$test1->setProduct($this->apple);
		$test1->setQuantity(2.0);

		$purchases[] = array(64, $test1);

		$test2 = new ProductToPurchase();
		$test2->setProduct($this->apple);
		$test2->setQuantity(0);

		$purchases[] = array(0, $test2);

		$test3 = new ProductToPurchase();
		$test3->setProduct($this->apple);
		$test3->setQuantity(10.0);

		$purchases[] = array(250, $test3);

		return $purchases;
	}

}
