<?php

namespace Kata\Test\Supermarket\Discount;

use Kata\Supermarket\Discount\NonCumulativeQuantityDiscount;
use Kata\Supermarket\Product;
use Kata\Supermarket\ProductToPurchase;

class NonCumulativeQuantityDiscountTest extends \PHPUnit_Framework_TestCase
{
	/** Data of products and discounts. */
	const PRODUCT_APPLE_NAME  = 'Apple';
	const PRODUCT_APPLE_PRICE = 32;
	const PRODUCT_APPLE_UNIT  = 'kg';

	const DISCOUNT_APPLE_MIN_QUANTITY = 5.0;
	const DISCOUNT_APPLE_PRICE        = 25;

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
	 * Creates test purchases.
	 *
	 * @return array
	 */
	public function providerDiscountPurchases()
	{
		$appleProduct = $this->getAppleProduct();

		return array(
			array(
				2.0 * self::PRODUCT_APPLE_PRICE,
				new ProductToPurchase($appleProduct, 2.0),
			),
			array(
				0,
				new ProductToPurchase($appleProduct, 0),
			),
			array(
				10.0 * self::DISCOUNT_APPLE_PRICE,
				new ProductToPurchase($appleProduct, 10.0),
			),
		);
	}

	/**
	 * Tests returned discount price per unit.
	 */
	public function testGetDiscountPriceUnit()
	{
		$discount = new NonCumulativeQuantityDiscount();
		$discount->setDiscountPricePerUnit(10);

		$this->assertEquals(10, $discount->getDiscountPricePerUnit());
	}

	/**
	 * Tests returned minimum quantity.
	 */
	public function testGetMinimumQuantity()
	{
		$discount = new NonCumulativeQuantityDiscount();
		$discount->setMinimumQuantity(1);

		$this->assertEquals(1, $discount->getMinimumQuantity());
	}

	/**
	 * Returns apple product.
	 *
	 * @return Product
	 */
	private function getAppleProduct()
	{
		$appleDiscount = new NonCumulativeQuantityDiscount();
		$appleDiscount->setMinimumQuantity(self::DISCOUNT_APPLE_MIN_QUANTITY);
		$appleDiscount->setDiscountPricePerUnit(self::DISCOUNT_APPLE_PRICE);

		$appleProduct = new Product();
		$appleProduct->setName(self::PRODUCT_APPLE_NAME);
		$appleProduct->setPricePerUnit(self::PRODUCT_APPLE_PRICE);
		$appleProduct->setUnit(self::PRODUCT_APPLE_UNIT);
		$appleProduct->setDiscount($appleDiscount);

		return $appleProduct;
	}

}
