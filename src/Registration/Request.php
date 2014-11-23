<?php

namespace Kata\Registration;

/**
 * Request.
 */
class Request
{
	/**
	 * Username.
	 *
	 * @var string
	 */
	public $username = '';

	/**
	 * Password.
	 *
	 * @var string
	 */
	public $password = '';

	/**
	 * Confirmed password.
	 *
	 * @var string
	 */
	public $passwordConfirm = '';

	/**
	 * Sets attributes of the request.
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $passwordConfirm
	 *
	 * @return void
	 */
	public function __construct($username, $password = '', $passwordConfirm = '')
	{
		$this->username        = $username;
		$this->password        = $password;
		$this->passwordConfirm = $passwordConfirm;
	}

}
