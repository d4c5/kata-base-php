<?php

namespace Kata\Test\Supermarket;

use Kata\Supermarket\ShoppingCart;
use Kata\Supermarket\ProductToPurchase;
use Kata\Supermarket\Product;
use Kata\Supermarket\Discount\NoneDiscount;

class ShoppingCartTest extends \PHPUnit_Framework_TestCase
{
	/** Data of test products */
	const PRODUCT_TEST1_NAME  = 'Test 1';
	const PRODUCT_TEST1_PRICE = 1;
	const PRODUCT_TEST1_UNIT  = 'unit';

	const PRODUCT_TEST2_NAME  = 'Test 2';
	const PRODUCT_TEST2_PRICE = 2;
	const PRODUCT_TEST2_UNIT  = 'unit';

	/**
	 * Shopping cart object.
	 *
	 * @var ShoppingCart
	 */
	private $shoppingCart = null;

	/**
	 * Test1 product object.
	 *
	 * @var Product
	 */
	private $testProduct1 = null;

	/**
	 * Test2 product object.
	 *
	 * @var Product
	 */
	private $testProduct2 = null;

	/**
	 * Sets the test product objects.
	 *
	 * @return void
	 */
	public function __construct($name = NULL, array $data = array(), $dataName = '')
	{
		$testProduct1Discount = new NoneDiscount();

		$this->testProduct1 = new Product();
		$this->testProduct1->setName(self::PRODUCT_TEST1_NAME);
		$this->testProduct1->setPricePerUnit(self::PRODUCT_TEST1_PRICE);
		$this->testProduct1->setUnit(self::PRODUCT_TEST1_UNIT);
		$this->testProduct1->setDiscount($testProduct1Discount);

		$testProduct2Discount = new NoneDiscount();

		$this->testProduct2 = new Product();
		$this->testProduct2->setName(self::PRODUCT_TEST2_NAME);
		$this->testProduct2->setPricePerUnit(self::PRODUCT_TEST2_PRICE);
		$this->testProduct2->setUnit(self::PRODUCT_TEST2_UNIT);
		$this->testProduct2->setDiscount($testProduct2Discount);

		parent::__construct($name, $data, $dataName);
	}

	/**
	 * Sets the cart object.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->shoppingCart = new ShoppingCart();
	}

	/**
	 * Tests that the quantity in cart is negative.
	 *
     * @expectedException Kata\Supermarket\ShoppingCartException
     */
    public function testNegativeQuantityInCartException()
    {
		// Adds 5 Test1 products to cart.
		$productToPurchase1 = new ProductToPurchase();
		$productToPurchase1->setProduct($this->testProduct1);
		$productToPurchase1->setQuantity(5);

		$this->shoppingCart->modify($productToPurchase1);

		// Removes 10 Test1 products to cart.
		$productToPurchase2 = new ProductToPurchase();
		$productToPurchase2->setProduct($this->testProduct1);
		$productToPurchase2->setQuantity(-10);

		$this->shoppingCart->modify($productToPurchase2);
    }

	/**
	 * Tests that the quantity in adding is negative.
	 *
     * @expectedException Kata\Supermarket\ShoppingCartException
     */
    public function testNegativeQuantityToAddException()
    {
		// Adds -5 Test1 products to cart.
		$productToPurchase1 = new ProductToPurchase();
		$productToPurchase1->setProduct($this->testProduct1);
		$productToPurchase1->setQuantity(-5);

		$this->shoppingCart->modify($productToPurchase1);
    }

	/**
	 * Tests adding and removing.
	 *
	 * @param array $productsToAdd
	 * @param array $productsInCart
	 *
	 * @dataProvider providerProductsToPurchase
	 */
	public function testAdding($productsToAdd, $productsInCart)
	{
		foreach ($productsToAdd as $dataOfPurchase)
		{
			$productToPurchase = new ProductToPurchase();
			$productToPurchase->setProduct($dataOfPurchase['product']);
			$productToPurchase->setQuantity($dataOfPurchase['quantity']);

			$this->shoppingCart->modify($productToPurchase);
		}

		// Number of products in the cart.
		$shoppingCartList = $this->shoppingCart->getShoppingCart();
		$this->assertEquals(count($productsInCart), count($shoppingCartList));

		// Quantities and types of products in the cart.
		foreach ($shoppingCartList as $number => $productToPurchase)
		{
			$this->assertEquals($productsInCart[$number]['quantity'], $productToPurchase->getQuantity());
			$this->assertEquals($productsInCart[$number]['product'],  $productToPurchase->getProduct());
		}
	}

	/**
	 * Test purchases.
	 *
	 * @return array
	 */
	public function providerProductsToPurchase()
	{
		return array(
			array(
				// Products to add.
				array(
					array('product' => $this->testProduct1, 'quantity' => 10),
					array('product' => $this->testProduct2, 'quantity' => 5),
					array('product' => $this->testProduct1, 'quantity' => 5),
				),
				// Products in cart.
				array(
					array('product' => $this->testProduct1, 'quantity' => 15),
					array('product' => $this->testProduct2, 'quantity' => 5),
				),
			),
			array(
				// Products to add.
				array(
					array('product' => $this->testProduct1, 'quantity' => 10),
					array('product' => $this->testProduct1, 'quantity' => -5),
					array('product' => $this->testProduct1, 'quantity' => -5),
				),
				// Products in cart.
				array(),
			),
			array(
				// Products to add.
				array(
					array('product' => $this->testProduct1, 'quantity' => 10),
					array('product' => $this->testProduct2, 'quantity' => 5),
					array('product' => $this->testProduct1, 'quantity' => -10),
				),
				// Products in cart.
				array(
					array('product' => $this->testProduct2, 'quantity' => 5),
				),
			),
		);
	}

}
