<?php

namespace Kata\Registration;

/**
 * User model.
 */
class User
{
	/**
	 * Username.
	 *
	 * @var string
	 */
	public $username      = '';

	/**
	 * Hashed password.
	 *
	 * @var string
	 */
	public $passwordHash  = '';

	/**
	 * Plain text password.
	 *
	 * @var string
	 */
	public $passwordPlain = '';

	/**
	 * Sets attributes of the user.
	 *
	 * @param string $username
	 * @param string $passwordHash
	 * @param string $passwordPlain
	 *
	 * @return void
	 */
	public function __construct($username, $passwordHash, $passwordPlain)
	{
		$this->username      = $username;
		$this->passwordHash  = $passwordHash;
		$this->passwordPlain = $passwordPlain;
	}

}
