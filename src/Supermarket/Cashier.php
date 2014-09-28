<?php

namespace Kata\Supermarket;

use Kata\Supermarket\ShoppingCart;

/**
 * Cashier.
 */
class Cashier
{
	private $shoppingCart = array();

	public function __construct(ShoppingCart $shoppingCart)
	{
		$this->shoppingCart = $shoppingCart;
	}

	public function getTotalPrice()
	{

	}

}
