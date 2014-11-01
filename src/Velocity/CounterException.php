<?php

namespace Kata\Velocity;

class CounterException extends \Exception
{
	const EMPTY_TYPE    = 601;
	const INVALID_TYPE  = 602;
	const EMPTY_MEASURE = 603;
	const EMPTY_LIMIT   = 604;

	private static $errorMessages = array(
		self::EMPTY_TYPE    => 'The counter type is required!',
		self::INVALID_TYPE  => 'The counter type is invalid!',
		self::EMPTY_MEASURE => 'The measure is required!',
	);

	public function __construct($messageCode, $code = 0, Exception $previous = null)
	{
		parent::__construct(self::$errorMessages[$messageCode], (!empty($code) ? $code : $messageCode), $previous);
	}

}
