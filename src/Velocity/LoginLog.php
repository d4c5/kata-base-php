<?php

namespace Kata\Velocity;

/**
 * Login log object.
 */
class LoginLog
{
	/**
	 * IP object.
	 *
	 * @var Ip
	 */
	private $ip     = null;

	/**
	 * User object.
	 *
	 * @var User
	 */
	private $user   = null;

	/**
	 * The result of the login.
	 *
	 * @var boolean
	 */
	private $result = false;

	/**
	 * Validates and sets the login parameters.
	 *
	 * @param Ip      $ip
	 * @param User    $user
	 * @param boolean $result
	 *
	 * @throws LoginLogException
	 *
	 * @return void
	 */
	public function __construct(Ip $ip, User $user, $result)
	{
		if (is_bool($result) === false)
		{
			throw new LoginLogException(LoginLogException::INVALID_LOGIN_RESULT);
		}

		$this->ip     = $ip;
		$this->user   = $user;
		$this->result = $result;
	}

	/**
	 * Returns IP object.
	 *
	 * @return Ip
	 */
	public function getIp()
	{
		return $this->ip;
	}

	/**
	 * Returns User object.
	 *
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Returns the result of the login.
	 *
	 * @return boolean
	 */
	public function getResult()
	{
		return $this->result;
	}

}
