<?php

namespace Kata\Supermarket;

class ShoppingCartException extends \Exception
{
	const NEGATIV_QUANTITY_IN_CART        = 301;
	const NEGATIV_OR_ZERO_QUANTITY_TO_ADD = 302;

	private static $errorMessages = array(
		self::NEGATIV_QUANTITY_IN_CART         => 'The calculated quantity is negative!',
		self::NEGATIV_OR_ZERO_QUANTITY_TO_ADD  => 'The given quantity is negative or zero!',
	);

	public function __construct($messageCode, $code = 0, Exception $previous = null)
	{
		parent::__construct(self::$errorMessages[$messageCode], !empty($code) ? $code : $messageCode, $previous);
	}
}
