<?php

namespace Kata\Test\Supermarket;

use Kata\Supermarket\Cashier;
use Kata\Supermarket\Product;
use Kata\Supermarket\ProductToPurchase;

class CashierTest extends \PHPUnit_Framework_TestCase
{
	/** Data of products and discounts. */
	const PRODUCT_APPLE_NAME           = 'Apple';
	const PRODUCT_APPLE_PRICE          = 32;
	const PRODUCT_APPLE_UNIT           = 'kg';
	const PRODUCT_APPLE_DISCOUNT_PRICE = 25;

	const PRODUCT_LIGHT_NAME  = 'Light';
	const PRODUCT_LIGHT_PRICE = 15;
	const PRODUCT_LIGHT_UNIT  = 'year';

	const PRODUCT_STARSHIP_NAME  = 'Starship';
	const PRODUCT_STARSHIP_PRICE = 999.99;
	const PRODUCT_STARSHIP_UNIT  = 'piece';

	/** Price flags */
	const PRODUCT_WITH_DISCOUNT_PRICE = 0;
	const PRODUCT_WITH_NORMAL_PRICE   = 1;

	/**
	 * Tests the calculation of the cashier.
	 *
	 * @param float $totalPrice
	 * @param array $purchases
	 *
	 * @dataProvider providerNormalPurchases
	 */
	public function testTotalPriceOfNormalPurchases($totalPrice, $purchases)
	{
		$shoppingCart = $this->getStubbedShoppingCart($purchases);
		$cashier      = new Cashier($shoppingCart);

		$this->assertEquals($totalPrice, $cashier->getTotalPrice());
	}

	/**
	 * Test purchases with normal price.
	 *
	 * Total price, the content of shopping cart.
	 *
	 * @return array
	 */
	public function providerNormalPurchases()
	{
		$appleProduct    = $this->getAppleProduct();
		$lightProduct    = $this->getLightProduct();
		$starshipProduct = $this->getStarshipProduct();

		$purchases = array();

		$test1 = new ProductToPurchase();
		$test1->setProduct($appleProduct);
		$test1->setQuantity(2.0);

		$purchases[] = array(2.0 * self::PRODUCT_APPLE_PRICE, array($test1));


		$test21 = new ProductToPurchase();
		$test21->setProduct($lightProduct);
		$test21->setQuantity(5);

		$test22 = new ProductToPurchase();
		$test22->setProduct($appleProduct);
		$test22->setQuantity(1.0);

		$purchases[] = array(5 * self::PRODUCT_LIGHT_PRICE + 1.0 * self::PRODUCT_APPLE_PRICE, array($test21, $test22));


		$test3 = new ProductToPurchase();
		$test3->setProduct($starshipProduct);
		$test3->setQuantity(1);

		$purchases[] = array(1 * self::PRODUCT_STARSHIP_PRICE, array($test3));

		return $purchases;
	}

	/**
	 * Tests the calculation of the cashier.
	 *
	 * @param float $totalPrice
	 * @param array $purchases
	 *
	 * @dataProvider providerDiscountPurchases
	 */
	public function testTotalPriceOfDiscountPurchases($totalPrice, $purchases)
	{
		$shoppingCart = $this->getStubbedShoppingCart($purchases);
		$cashier      = new Cashier($shoppingCart);

		$this->assertEquals($totalPrice, $cashier->getTotalPrice());
	}

	/**
	 * Returns discount test purchases.
	 *
	 * @return array
	 */
	public function providerDiscountPurchases()
	{
		$appleProduct    = $this->getAppleProduct(self::PRODUCT_WITH_DISCOUNT_PRICE);
		$lightProduct    = $this->getLightProduct(self::PRODUCT_WITH_DISCOUNT_PRICE);
		$starshipProduct = $this->getStarshipProduct(self::PRODUCT_WITH_DISCOUNT_PRICE);

		$purchases = array();

		$test4 = new ProductToPurchase();
		$test4->setProduct($appleProduct);
		$test4->setQuantity(7.0);

		$purchases[] = array(7.0 * self::PRODUCT_APPLE_DISCOUNT_PRICE, array($test4));


		$test51 = new ProductToPurchase();
		$test51->setProduct($lightProduct);
		$test51->setQuantity(5);

		$test52 = new ProductToPurchase();
		$test52->setProduct($appleProduct);
		$test52->setQuantity(8.0);

		$purchases[] = array(5 * self::PRODUCT_LIGHT_PRICE + 8.0 * self::PRODUCT_APPLE_DISCOUNT_PRICE, array($test51, $test52));


		$test6 = new ProductToPurchase();
		$test6->setProduct($starshipProduct);
		$test6->setQuantity(6);

		$purchases[] = array(4 * self::PRODUCT_STARSHIP_PRICE, array($test6));

		return $purchases;
	}

	/**
	 * Returns stubbed discount object.
	 *
	 * @param array $priceType
	 *
	 * @return NonCumulativeQuantityDiscount
	 */
	private function getStubbedAppleDiscount($priceType = self::PRODUCT_WITH_NORMAL_PRICE)
	{
		if ($priceType === self::PRODUCT_WITH_DISCOUNT_PRICE)
		{
			$returnValues = $this->onConsecutiveCalls(7.0 * self::PRODUCT_APPLE_DISCOUNT_PRICE, 8.0 * self::PRODUCT_APPLE_DISCOUNT_PRICE);
		}
		else
		{
			$returnValues = $this->onConsecutiveCalls(2.0 * self::PRODUCT_APPLE_PRICE, 1.0 * self::PRODUCT_APPLE_PRICE);
		}

		$appleDiscount = $this->getMock('\Kata\Supermarket\Discount\NonCumulativeQuantityDiscount');
		$appleDiscount->expects($this->any())
				->method('getPrice')
				->will($returnValues);

		return $appleDiscount;
	}

	/**
	 * Returns apple product.
	 *
	 * @param int $priceType
	 *
	 * @return Product
	 */
	private function getAppleProduct($priceType = self::PRODUCT_WITH_NORMAL_PRICE)
	{
		$appleDiscount = $this->getStubbedAppleDiscount($priceType);

		$apple = new Product();
		$apple->setName(self::PRODUCT_APPLE_NAME);
		$apple->setPricePerUnit(self::PRODUCT_APPLE_PRICE);
		$apple->setUnit(self::PRODUCT_APPLE_UNIT);
		$apple->setDiscount($appleDiscount);

		return $apple;
	}

	/**
	 * Returns stubbed discount object.
	 *
	 * @param array $priceType
	 *
	 * @return NoneDiscount
	 */
	private function getStubbedLightDiscount($priceType = self::PRODUCT_WITH_NORMAL_PRICE)
	{
		if ($priceType === self::PRODUCT_WITH_DISCOUNT_PRICE)
		{
			$returnValues = $this->onConsecutiveCalls(5 * self::PRODUCT_LIGHT_PRICE);
		}
		else
		{
			$returnValues = $this->onConsecutiveCalls(5 * self::PRODUCT_LIGHT_PRICE);
		}

		$lightDiscount = $this->getMock('\Kata\Supermarket\Discount\NoneDiscount');
		$lightDiscount->expects($this->any())
				->method('getPrice')
				->will($returnValues);

		return $lightDiscount;
	}

	/**
	 * Returns light product.
	 *
	 * @param int $priceType
	 *
	 * @return Product
	 */
	private function getLightProduct($priceType = self::PRODUCT_WITH_NORMAL_PRICE)
	{
		$lightDiscount = $this->getStubbedLightDiscount($priceType);

		$light = new Product();
		$light->setName(self::PRODUCT_LIGHT_NAME);
		$light->setPricePerUnit(self::PRODUCT_LIGHT_PRICE);
		$light->setUnit(self::PRODUCT_LIGHT_UNIT);
		$light->setDiscount($lightDiscount);

		return $light;
	}

	/**
	 * Returns stubbed discount object.
	 *
	 * @param array $priceType
	 *
	 * @return BuyOneGetOneFreeDiscount
	 */
	private function getStubbedStarshipDiscount($priceType = self::PRODUCT_WITH_NORMAL_PRICE)
	{
		if ($priceType === self::PRODUCT_WITH_DISCOUNT_PRICE)
		{
			$returnValues = $this->onConsecutiveCalls(4 * self::PRODUCT_STARSHIP_PRICE);
		}
		else
		{
			$returnValues = $this->onConsecutiveCalls(1 * self::PRODUCT_STARSHIP_PRICE);
		}

		$starshipDiscount = $this->getMock('\Kata\Supermarket\Discount\BuyOneGetOneFreeDiscount');
		$starshipDiscount->expects($this->any())
				->method('getPrice')
				->will($returnValues);

		return $starshipDiscount;
	}

	/**
	 * Returns starship product.
	 *
	 * @param int $priceType
	 *
	 * @return Product
	 */
	private function getStarshipProduct($priceType = self::PRODUCT_WITH_NORMAL_PRICE)
	{
		$starshipDiscount = $this->getStubbedStarshipDiscount($priceType);

		$starship = new Product();
		$starship->setName(self::PRODUCT_STARSHIP_NAME);
		$starship->setPricePerUnit(self::PRODUCT_STARSHIP_PRICE);
		$starship->setUnit(self::PRODUCT_STARSHIP_UNIT);
		$starship->setDiscount($starshipDiscount);

		return $starship;
	}

	/**
	 * Returns stubbed cashier object.
	 *
	 * @param array $purchases
	 *
	 * @return Cashier
	 */
	private function getStubbedShoppingCart($purchases)
	{
		$stub = $this->getMock('\Kata\Supermarket\ShoppingCart');
		$stub->expects($this->any())
				->method('getShoppingCart')
				->will($this->returnValue($purchases));

		return $stub;
	}

}
