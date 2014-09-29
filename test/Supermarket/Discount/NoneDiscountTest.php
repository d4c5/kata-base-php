<?php

namespace Kata\Test\Supermarket\Discount;

use Kata\Supermarket\Discount\NoneDiscount;
use Kata\Supermarket\Product;
use Kata\Supermarket\ProductToPurchase;

class NoneDiscountTest extends \PHPUnit_Framework_TestCase
{
	/** Data of products and discounts. */
	const PRODUCT_LIGHT_NAME  = 'Light';
	const PRODUCT_LIGHT_PRICE = 15;
	const PRODUCT_LIGHT_UNIT  = 'year';

	/**
	 * The test product.
	 *
	 * @var Product
	 */
	private $light = null;

	/**
	 * Tests normal price purchases.
	 *
	 * @param float             $totalPrice
	 * @param ProductToPurchase $productToPurchase
	 *
	 * @dataProvider providerNoneDiscountPurchases
	 */
	public function testNormalPrice($totalPrice, $productToPurchase)
	{
		$product  = $productToPurchase->getProduct();
		$discount = $product->getDiscount();

		$this->assertEquals($totalPrice, $discount->getPrice($productToPurchase));
	}

	/**
	 * Sets properties of the light product.
	 *
	 * @return void
	 */
	private function setLightProduct()
	{
		$lightDiscount = new NoneDiscount();

		$this->light = new Product();
		$this->light->setName(self::PRODUCT_LIGHT_NAME);
		$this->light->setPricePerUnit(self::PRODUCT_LIGHT_PRICE);
		$this->light->setUnit(self::PRODUCT_LIGHT_UNIT);
		$this->light->setDiscount($lightDiscount);
	}

	/**
	 * Creates test purchases.
	 *
	 * @return array
	 */
	public function providerNoneDiscountPurchases()
	{
		$this->setLightProduct();

		$purchases = array();

		$test1 = new ProductToPurchase();
		$test1->setProduct($this->light);
		$test1->setQuantity(2.0);

		$purchases[] = array(30, $test1);

		$test2 = new ProductToPurchase();
		$test2->setProduct($this->light);
		$test2->setQuantity(0);

		$purchases[] = array(0, $test2);

		$test3 = new ProductToPurchase();
		$test3->setProduct($this->light);
		$test3->setQuantity(10.0);

		$purchases[] = array(150, $test3);

		return $purchases;
	}

}
