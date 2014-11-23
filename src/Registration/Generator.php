<?php

namespace Kata\Registration;

/**
 * Password generator.
 */
class Generator
{
	/** Rules for creating password. */
	const PASSWORD_MIN_LENGTH = 8;
	const PASSWORD_MAX_LENGTH = 16;
	const PASSWORD_CHARACTERS = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";

	/**
	 * Returns a random password.
	 *
	 * @return string
	 */
	public function getPassword()
	{
		$password = "";

		$passwordLength     = mt_rand(self::PASSWORD_MIN_LENGTH, self::PASSWORD_MAX_LENGTH);
		$passwordCharacters = self::PASSWORD_CHARACTERS;

		for ($i = 0; $i < $passwordLength; $i++)
		{
			$characterPosition = mt_rand(0, strlen($passwordCharacters) - 1);
			$password         .= $passwordCharacters[$characterPosition];
		}

		return $password;
	}

}
