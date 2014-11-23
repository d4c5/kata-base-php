<?php

namespace Kata\Registration;

class RegistrationException extends \Exception
{
	const G_SQL_ERROR              = 101;
	const G_REGEX_ERROR            = 102;

	const E_USERNAME_IS_NOT_UNIQUE = 201;

	const E_INVALID_USERNAME       = 301;
	const E_INVALID_PASSWORD       = 302;
	const E_PASSWORDS_DO_NOT_MATCH = 303;

	private static $errorMessages = array(
		self::G_SQL_ERROR              => 'The SQL request is failed!',
		self::G_REGEX_ERROR            => 'Regex error occured!',

		self::E_USERNAME_IS_NOT_UNIQUE => 'The username is not unique!',

		self::E_INVALID_USERNAME       => 'The username is invalid!',
		self::E_INVALID_PASSWORD       => 'The password is invalid!',
		self::E_PASSWORDS_DO_NOT_MATCH => 'The password and confirm password does not match!',
	);

	public function __construct($errorDetails = '', $code = 0, Exception $previous = null)
	{
		parent::__construct(self::$errorMessages[$code] . (!empty($errorDetails) ? '[' . $errorDetails . ']' : ''), $code, $previous);
	}
}
