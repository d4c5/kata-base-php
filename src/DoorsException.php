<?php

namespace Kata;

use Exception;

class DoorsException extends Exception
{
	const THE_NUMBER_OF_DOORS_IS_INVALID_INT  = 401;
	const THE_NUMBER_OF_STEPS_IS_INVALID_INT  = 402;
	const THE_NUMBER_OF_DOORS_IS_OUT_OF_RANGE = 403;
	const THE_NUMBER_OF_STEPS_IS_OUT_OF_RANGE = 404;

	private static $errorMessages = array(
		self::THE_NUMBER_OF_DOORS_IS_INVALID_INT  => 'The number of doors is invalid!',
		self::THE_NUMBER_OF_STEPS_IS_INVALID_INT  => 'The number of steps is invalid!',
		self::THE_NUMBER_OF_DOORS_IS_OUT_OF_RANGE => 'The number of doors is out of allowed range!',
		self::THE_NUMBER_OF_STEPS_IS_OUT_OF_RANGE => 'The number of steps is out of allowed range!',
	);

	public function __construct($messageCode, $code = 0, Exception $previous = null)
	{
		if (empty($code) && !empty($messageCode))
		{
			$code = $messageCode;
		}

		parent::__construct(self::$errorMessages[$messageCode], $code, $previous);
	}

}
