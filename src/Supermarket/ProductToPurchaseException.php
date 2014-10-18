<?php

namespace Kata\Supermarket;

class ProductToPurchaseException extends \Exception
{
	const NOT_NUMERIC_QUANTITY = 401;

	private static $errorMessages = array(
		self::NOT_NUMERIC_QUANTITY => 'The given quantity is not numeric!',
	);

	public function __construct($messageCode, $code = 0, Exception $previous = null)
	{
		parent::__construct(self::$errorMessages[$messageCode], !empty($code) ? $code : $messageCode, $previous);
	}
}
