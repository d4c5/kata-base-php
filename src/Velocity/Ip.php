<?php

namespace Kata\Velocity;

/**
 * IP.
 */
class Ip
{
	/** RegExps for validations. */
	const REGEXP_COUNTRY = '/^[A-Z]{2}$/';

	/**
	 * The IPv4 address.
	 *
	 * @var string
	 */
	private $ip      = '';

	/**
	 * The range (network) of the IP address.
	 *
	 * @var string
	 */
	private $range   = '';

	/**
	 * The country code of the IP address.
	 *
	 * @var string
	 */
	private $country = '';

	/**
	 * Validates and sets the attributes.
	 *
	 * @param string $ip
	 * @param string $range
	 * @param string $country
	 *
	 * @throws IpException
	 *
	 * @return void
	 */
	public function __construct($ip, $range, $country)
	{
		if (filter_var($ip, FILTER_VALIDATE_IP) === false)
		{
			throw new IpException(IpException::INVALID_IP_ADDRESS);
		}
		if (preg_match(self::REGEXP_COUNTRY, $country) !== 1)
		{
			throw new IpException(IpException::INVALID_IP_COUNTRY);
		}
		if (empty($range))
		{
			throw new IpException(IpException::THE_IP_RANGE_IS_REQUIRE);
		}

		$this->ip      = $ip;
		$this->range   = $range;
		$this->country = $country;
	}

	/**
	 * Returns IP address.
	 *
	 * @return string
	 */
	public function getIp()
	{
		return $this->ip;
	}

	/**
	 * Returns the range of the IP address.
	 *
	 * @return string
	 */
	public function getRange()
	{
		return $this->range;
	}

	/**
	 * Returns the country code of the IP address.
	 *
	 * @return string
	 */
	public function getCountry()
	{
		return $this->country;
	}

}
