<?php

namespace Kata\Registration;

/**
 * User builder.
 */
class UserBuilder
{
	/** Salt to hashing. */
	const PASSWORD_SALT = 'StarG4t3';

	/**
	 * Generator object.
	 *
	 * @var Generator
	 */
	private $generator = null;

	/**
	 * Sets user's attributes.
	 *
	 * @param Generator $generator
	 *
	 * @return void
	 */
	public function __construct(Generator $generator)
	{
		$this->generator = $generator;
	}

	/**
	 * Creates user by request.
	 *
	 * @param Request $request
	 *
	 * @return User
	 */
	public function createUser(Request $request)
	{
		$username = '';
		if (!empty($request->username))
		{
			$username = $request->username;
		}

		if (!empty($request->password))
		{
			$passwordPlain = $request->password;
		}
		else
		{
			$passwordPlain = $this->generator->getPassword();
		}

		$passwordHash = $this->hashPassword($username, $passwordPlain);

		return new User($username, $passwordHash, $passwordPlain);
	}

	/**
	 * Creates a hash from plain password.
	 *
	 * @param string $username
	 * @param string $passwordPlain
	 *
	 * @return string
	 */
	private function hashPassword($username, $passwordPlain)
	{
		return sha1(self::PASSWORD_SALT . md5($username . $passwordPlain));
	}

}
