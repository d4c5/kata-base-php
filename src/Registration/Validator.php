<?php

namespace Kata\Registration;

/**
 * Validator.
 */
class Validator
{
	/** Regex for username */
	const USERNAME_REGEX = '/^[0-9a-z]{4,128}$/';

	/** Regex for password */
	const PASSWORD_REGEX = '/^.{6,}$/';

	/**
	 * Validates username.
	 *
	 * @param string $username
	 *
	 * @return boolean  Returns true if the username is valid.
	 *
	 * @throws RegistrationException
	 */
	public function isUsername($username)
	{
		$matching = preg_match(self::USERNAME_REGEX, $username);
		if ($matching === 0)
		{
			throw new RegistrationException($username, RegistrationException::E_INVALID_USERNAME);
		}
		if ($matching === false)
		{
			// @codeCoverageIgnoreStart
			throw new RegistrationException($username, RegistrationException::G_REGEX_ERROR);
			// @codeCoverageIgnoreEnd
		}

		return true;
	}

	/**
	 * Validates password.
	 *
	 * @param string $password
	 * @param string $passwordConfirm
	 *
	 * @return boolean  Returns true if the password is valid and equals the confirmed password.
	 *
	 * @throws RegistrationException
	 */
	public function isPassword($password, $passwordConfirm)
	{
		$matching = preg_match(self::PASSWORD_REGEX, $password);
		if ($matching === 0)
		{
			throw new RegistrationException($password, RegistrationException::E_INVALID_PASSWORD);
		}
		if ($matching === false)
		{
			// @codeCoverageIgnoreStart
			throw new RegistrationException($password, RegistrationException::G_REGEX_ERROR);
			// @codeCoverageIgnoreEnd
		}
		if ($password !== $passwordConfirm)
		{
			throw new RegistrationException($password. ', ' . $passwordConfirm, RegistrationException::E_PASSWORDS_DO_NOT_MATCH);
		}

		return true;
	}

}
