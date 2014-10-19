<?php

namespace Kata\Velocity;

class LoginLogException extends \Exception
{
	const INVALID_LOGIN_RESULT = 301;

	private static $errorMessages = array(
		self::INVALID_LOGIN_RESULT => 'The result of the login is invalid!',
	);

	public function __construct($messageCode, $code = 0, Exception $previous = null)
	{
		parent::__construct(self::$errorMessages[$messageCode], (!empty($code) ? $code : $messageCode), $previous);
	}

}
