<?php

namespace Kata\Test\Supermarket\Discount;

use Kata\Supermarket\Discount\BuyOneGetOneFreeDiscount;
use Kata\Supermarket\Product;
use Kata\Supermarket\ProductToPurchase;

class BuyOneGetOneFreeDiscountTest extends \PHPUnit_Framework_TestCase
{
	/** Data of products and discounts. */
	const PRODUCT_STARSHIP_ID    = 2;
	const PRODUCT_STARSHIP_NAME  = 'Starship';
	const PRODUCT_STARSHIP_PRICE = 999.99;
	const PRODUCT_STARSHIP_UNIT  = 'piece';

	const DISCOUNT_STARSHIP_MIN_QUANTITY  = 2;
	const DISCOUNT_STARSHIP_FREE_QUANTITY = 1;

	/**
	 * The test product.
	 *
	 * @var Product
	 */
	private $starship = null;

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
	 * Sets properties of the starship product.
	 *
	 * @return void
	 */
	private function setStarshipProduct()
	{
		$starshipDiscount = new BuyOneGetOneFreeDiscount();
		$starshipDiscount->setMinimumQuantity(self::DISCOUNT_STARSHIP_MIN_QUANTITY);
		$starshipDiscount->setFreeQuantity(self::DISCOUNT_STARSHIP_FREE_QUANTITY);

		$this->starship = new Product();
		$this->starship->setName(self::PRODUCT_STARSHIP_NAME);
		$this->starship->setPricePerUnit(self::PRODUCT_STARSHIP_PRICE);
		$this->starship->setUnit(self::PRODUCT_STARSHIP_UNIT);
		$this->starship->setDiscount($starshipDiscount);
	}

	/**
	 * Creates test purchases.
	 *
	 * @return array
	 */
	public function providerDiscountPurchases()
	{
		$this->setStarshipProduct();

		$purchases = array();

		$test1 = new ProductToPurchase();
		$test1->setProduct($this->starship);
		$test1->setQuantity(2);

		$purchases[] = array(1999.98, $test1);

		$test2 = new ProductToPurchase();
		$test2->setProduct($this->starship);
		$test2->setQuantity(0);

		$purchases[] = array(0, $test2);

		$test3 = new ProductToPurchase();
		$test3->setProduct($this->starship);
		$test3->setQuantity(9);

		$purchases[] = array(5999.94, $test3);

		return $purchases;
	}

}
