<?php

namespace Kata\Velocity;

use Kata\Velocity\Counter;

/*
 * Velocity checker
 *
 * Functional specification:
 * We have got a login mechanism, but we need an anti-bruteforce detection system. Everybody said that captcha is a good
 * thing, and yes it is, but i don’t want to make the user’s life harder more then it is needed. I want to give them
 * captcha only when it’s necessary.
 *
 * We want to use the captcha in these cases:
 *  - from one ip we have 3 failed login
 *  - from an ip range we have 500 failed login
 *  - from a country we have 1000 failed login
 *  - with a username we have 3 failed login
 *
 * If we have a failed login from an ip we must increase the ip, the range and the country counter till the captcha is
 * not active. After that we should increase only the ip counter.
 *
 * If the username registration country is different from the client country and the login is failed then you must
 * increase the ip counter to the captcha limit (so you need to activate the captcha to the next try)
 *
 * The counters have a 3600 sec ttl, with the following description:
 * The counter logs the failed logins continuously and we count for the last one hour. It is allowed to summarise the
 * count in 300 second blocks.
 *
 * Additional informations:l
 *  - you don’t need to implement the ip range calculator
 *  - you don’t need to implement the geoip
 *  - you don’t need to implement a login function
 */
class VelocityChecker
{
	const MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP       = 3;
	const MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_RANGE    = 500;
	const MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_COUNTRY  = 1000;
	const MAX_FAILED_LOGIN_ATTEMPTS_WITH_ONE_USERNAME = 3;

	/**
	 * Login log object.
	 *
	 * @var LoginLog
	 */
	private $loginLog = null;

	/**
	 * User object.
	 *
	 * @var User
	 */
	private $user     = null;

	/**
	 * IP object.
	 *
	 * @var Ip
	 */
	private $ip       = null;

	/**
	 * IP counter.
	 *
	 * @var Counter
	 */
	private $ipCounter        = null;

	/**
	 * IP range counter.
	 *
	 * @var Counter
	 */
	private $ipRangeCounter   = null;

	/**
	 * IP country counter.
	 *
	 * @var Counter
	 */
	private $ipCountryCounter = null;

	/**
	 * Username counter.
	 *
	 * @var Counter
	 */
	private $usernameCounter  = null;

	/**
	 * Sets login log, user, ip objects and counters.
	 *
	 * @param LoginLog   $loginLog
	 * @param SQLite3    $dbConnection
	 *
	 * @return void
	 */
	public function __construct(LoginLog $loginLog, \SQLite3 $dbConnection)
	{
		$this->loginLog = $loginLog;

		$this->user = $this->loginLog->getUser();
		$this->ip   = $this->loginLog->getIp();

		// TODO: DI?
		$this->ipCounter        = new Counter($dbConnection, Counter::TYPE_IP, $this->ip->getIp(), self::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_IP);
		$this->ipRangeCounter   = new Counter($dbConnection, Counter::TYPE_IP_RANGE, $this->ip->getRange(), self::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_RANGE);
		$this->ipCountryCounter = new Counter($dbConnection, Counter::TYPE_IP_COUNTRY, $this->ip->getCountry(), self::MAX_FAILED_LOGIN_ATTEMPTS_FROM_ONE_COUNTRY);
		$this->usernameCounter  = new Counter($dbConnection, Counter::TYPE_USERNAME, $this->user->getUsername(), self::MAX_FAILED_LOGIN_ATTEMPTS_WITH_ONE_USERNAME);
	}

	/**
	 * Checks the status of the captcha.
	 *
	 * @param LoginLog $loginLog
	 *
	 * return boolean
	 */
	public function isCaptchaActive()
	{
		$captchaActive = false;

		if ($this->loginLog->getResult() === true)
		{
			$this->resetCounters();
		}
		elseif (
			$this->ipCounter->getCounter() > $this->ipCounter->getLimit()
			|| $this->usernameCounter->getCounter() > $this->usernameCounter->getLimit()
			|| $this->ipRangeCounter->getCounter() > $this->ipRangeCounter->getLimit()
			|| $this->ipCountryCounter->getCounter() > $this->ipCountryCounter->getLimit()
		) {
			$captchaActive = true;
		}
		elseif ($this->user->getCountry() !== $this->ip->getCountry())
		{
			$this->ipCounter->setCounterToUpperLimit();
		}

		return $captchaActive;
	}

	/**
	 * Resets the IP and username counters.
	 *
	 * @return void
	 */
	private function resetCounters()
	{
		$this->ipCounter->reset();
		$this->usernameCounter->reset();
	}

}
