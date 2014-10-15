<?php

namespace Kata\Velocity;

/*
 *  Velocity checker
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
 *
 * Data of one login: IP, Network, IP country, registration country, username, success
 *
 * failed login -> ipCounter++, networkCounter++, ipCountryCounter++
 * failed login && ipCountry != regCountry -> setIpCounterToMax()
 *
 * store: created_at > NOW() - 300 -> UPDATE
 *        ELSE INSERT
 *
 * getCounter(): where created_at > NOW() - 3600
 *
 */

/**
 * Description of VelocityChecker
 *
 * @author dac
 */
class VelocityChecker
{
	/**
	 * Is captcha active?
	 * 
	 * @return boolean
	 */
	public function isCaptchaActive()
	{
		return true;
	}

}
