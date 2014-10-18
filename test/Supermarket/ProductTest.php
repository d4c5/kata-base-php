<?php

namespace Kata\Test\Supermarket;

use Kata\Supermarket\Product;
use Kata\Supermarket\Discount\NoneDiscount;

class ProductTest extends \PHPUnit_Framework_TestCase
{
	/** Data of products. */
	const PRODUCT_APPLE_NAME  = 'Apple';
	const PRODUCT_APPLE_PRICE = 32;
	const PRODUCT_APPLE_UNIT  = 'kg';

	/**
	 * Tests the given and returned name are equal.
	 */
	public function testGetName()
	{
		$product = new Product();
		$product->setName(self::PRODUCT_APPLE_NAME);

		$this->assertEquals(self::PRODUCT_APPLE_NAME, $product->getName());
	}

	/**
	 * Tests the given and returned unit are equal.
	 */
	public function testGetUnit()
	{
		$product = new Product();
		$product->setUnit(self::PRODUCT_APPLE_UNIT);

		$this->assertEquals(self::PRODUCT_APPLE_UNIT, $product->getUnit());
	}

	/**
	 * Tests the given and returned price are equal.
	 */
	public function testGetPricePerUnit()
	{
		$product = new Product();
		$product->setPricePerUnit(self::PRODUCT_APPLE_PRICE);

		$this->assertEquals(self::PRODUCT_APPLE_PRICE, $product->getPricePerUnit());
	}

	/**
	 * Tests the given and returned discount are equal.
	 */
	public function testGetDiscount()
	{
		$discount = new NoneDiscount();

		$product = new Product();
		$product->setDiscount($discount);

		$this->assertEquals($discount, $product->getDiscount());
	}

}
