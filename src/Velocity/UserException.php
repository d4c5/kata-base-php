<?php

namespace Kata\Velocity;

class UserException extends \Exception
{
	const INVALID_USERNAME    = 501;
	const INVALID_REG_COUNTRY = 502;

	private static $errorMessages = array(
		self::INVALID_USERNAME    => 'The username is invalid!',
		self::INVALID_REG_COUNTRY => 'The registration country is invalid!',
	);

	public function __construct($messageCode, $code = 0, Exception $previous = null)
	{
		parent::__construct(self::$errorMessages[$messageCode], (!empty($code) ? $code : $messageCode), $previous);
	}

}
