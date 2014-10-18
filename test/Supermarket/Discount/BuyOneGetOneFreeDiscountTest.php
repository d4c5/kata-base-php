<?php

namespace Kata\Test\Supermarket\Discount;

use Kata\Supermarket\Discount\BuyOneGetOneFreeDiscount;
use Kata\Supermarket\Product;
use Kata\Supermarket\ProductToPurchase;

class BuyOneGetOneFreeDiscountTest extends \PHPUnit_Framework_TestCase
{
	/** Data of products and discounts. */
	const PRODUCT_STARSHIP_NAME  = 'Starship';
	const PRODUCT_STARSHIP_PRICE = 999.99;
	const PRODUCT_STARSHIP_UNIT  = 'piece';

	const DISCOUNT_STARSHIP_MIN_QUANTITY  = 2;
	const DISCOUNT_STARSHIP_FREE_QUANTITY = 1;

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
		$starshipProduct = $this->getStarshipProduct();

		return array(
			array(
				2 * self::PRODUCT_STARSHIP_PRICE,
				new ProductToPurchase($starshipProduct, 2),
			),
			array(
				0,
				new ProductToPurchase($starshipProduct, 0),
			),
			array(
				6 * self::PRODUCT_STARSHIP_PRICE,
				new ProductToPurchase($starshipProduct, 9),
			),
		);
	}

	/**
	 * Tests returned minimum quantity.
	 */
	public function testGetMinimumQuantity()
	{
		$discount = new BuyOneGetOneFreeDiscount();
		$discount->setMinimumQuantity(1);

		$this->assertEquals(1, $discount->getMinimumQuantity());
	}

	/**
	 * Tests returned free quantity.
	 */
	public function testGetFreeQuantity()
	{
		$discount = new BuyOneGetOneFreeDiscount();
		$discount->setFreeQuantity(1);

		$this->assertEquals(1, $discount->getFreeQuantity());
	}

	/**
	 * Returns starship product.
	 *
	 * @return Product
	 */
	private function getStarshipProduct()
	{
		$starshipDiscount = new BuyOneGetOneFreeDiscount();
		$starshipDiscount->setMinimumQuantity(self::DISCOUNT_STARSHIP_MIN_QUANTITY);
		$starshipDiscount->setFreeQuantity(self::DISCOUNT_STARSHIP_FREE_QUANTITY);

		$starshipProduct = new Product();
		$starshipProduct->setName(self::PRODUCT_STARSHIP_NAME);
		$starshipProduct->setPricePerUnit(self::PRODUCT_STARSHIP_PRICE);
		$starshipProduct->setUnit(self::PRODUCT_STARSHIP_UNIT);
		$starshipProduct->setDiscount($starshipDiscount);

		return $starshipProduct;
	}

}
