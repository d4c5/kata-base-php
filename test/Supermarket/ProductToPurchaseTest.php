<?php

namespace Kata\Test\Supermarket;

use Kata\Supermarket\Product;
use Kata\Supermarket\ProductToPurchase;
use Kata\Supermarket\Discount\NoneDiscount;

class ProductToPurchaseTest extends \PHPUnit_Framework_TestCase
{
	/** Data of products. */
	const PRODUCT_APPLE_NAME  = 'Apple';
	const PRODUCT_APPLE_PRICE = 32;
	const PRODUCT_APPLE_UNIT  = 'kg';

	/**
	 * Tests that the quantity in product to purchase is not numeric.
	 *
     * @expectedException Kata\Supermarket\ProductToPurchaseException
	 * @expectedExceptionCode 401
     */
    public function testNotNumericQuantityInProductToPurchase()
    {
		$appleProduct = $this->getAppleProduct();

		// 'qwesdf' kg apple
		new ProductToPurchase($appleProduct, 'qwesdf');
    }

	/**
	 * Tests the given and returned product are equal.
	 */
	public function testGetProduct()
	{
		$appleProduct = $this->getAppleProduct();

		$productToPurchase = new ProductToPurchase($appleProduct, 10);

		$this->assertEquals($appleProduct, $productToPurchase->getProduct());
	}

	/**
	 * Tests the given and returned quantity are equal.
	 */
	public function testGetQuantity()
	{
		$appleProduct = $this->getAppleProduct();

		$productToPurchase = new ProductToPurchase($appleProduct, 10);

		$this->assertEquals(10, $productToPurchase->getQuantity());
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

}
