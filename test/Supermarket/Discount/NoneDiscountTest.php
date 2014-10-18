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
	 * Creates test purchases.
	 *
	 * @return array
	 */
	public function providerNoneDiscountPurchases()
	{
		$lightProduct = $this->getLightProduct();

		return array(
			array(
				2 * self::PRODUCT_LIGHT_PRICE,
				new ProductToPurchase($lightProduct, 2),
			),
			array(
				0,
				new ProductToPurchase($lightProduct, 0),
			),
			array(
				10.0 * self::PRODUCT_LIGHT_PRICE,
				new ProductToPurchase($lightProduct, 10.0),
			),
		);
	}

	/**
	 * Returns light product.
	 *
	 * @return Product
	 */
	private function getLightProduct()
	{
		$lightDiscount = new NoneDiscount();

		$lightProduct = new Product();
		$lightProduct->setName(self::PRODUCT_LIGHT_NAME);
		$lightProduct->setPricePerUnit(self::PRODUCT_LIGHT_PRICE);
		$lightProduct->setUnit(self::PRODUCT_LIGHT_UNIT);
		$lightProduct->setDiscount($lightDiscount);

		return $lightProduct;
	}

}
