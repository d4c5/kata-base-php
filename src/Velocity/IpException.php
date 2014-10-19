<?php

namespace Kata\Velocity;

class IpException extends \Exception
{
	const INVALID_IP_ADDRESS      = 401;
	const INVALID_IP_COUNTRY      = 402;
	const THE_IP_RANGE_IS_REQUIRE = 403;

	private static $errorMessages = array(
		self::INVALID_IP_ADDRESS      => 'The IP address is invalid!',
		self::INVALID_IP_COUNTRY      => 'The country is invalid!',
		self::THE_IP_RANGE_IS_REQUIRE => 'The IP range is required!',
	);

	public function __construct($messageCode, $code = 0, Exception $previous = null)
	{
		parent::__construct(self::$errorMessages[$messageCode], (!empty($code) ? $code : $messageCode), $previous);
	}

}
