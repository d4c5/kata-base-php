<?php

namespace Kata\Test\Supermarket;

use Kata\Supermarket\Cashier;
use Kata\Supermarket\ShoppingCart;

class CashierTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests the calculation of the cashier.
	 */
	public function testTotalPrice()
	{
		$shoppingCart = new ShoppingCart();
		$cashier      = new Cashier($shoppingCart);

		$this->assertEquals(0, $cashier->getTotalPrice());
	}

}
