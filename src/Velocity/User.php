<?php

namespace Kata\Velocity;

/**
 * User object.
 */
class User
{
	/** RegExps for validations. */
	const REGEXP_USERNAME = '/^[a-z0-9_]{2,16}$/';
	const REGEXP_COUNTRY  = '/^[A-Z]{2}$/';

	/**
	 * Username.
	 *
	 * @var string
	 */
	private $username = '';

	/**
	 * Registration country.
	 *
	 * @var string
	 */
	private $country  = '';

	/**
	 * Validates and sets the attributes.
	 *
	 * @param string $username
	 * @param string $country
	 *
	 * @return void
	 *
	 * @throws UserException
	 */
	public function __construct($username, $country)
	{
		if (preg_match(self::REGEXP_USERNAME, $username) !== 1)
		{
			throw new UserException(UserException::INVALID_USERNAME);
		}
		if (preg_match(self::REGEXP_COUNTRY, $country) !== 1)
		{
			throw new UserException(UserException::INVALID_REG_COUNTRY);
		}

		$this->username = $username;
		$this->country  = $country;
	}

	/**
	 * Returns username.
	 *
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Returns the registration country of user.
	 *
	 * @return string
	 */
	public function getCountry()
	{
		return $this->country;
	}

}
