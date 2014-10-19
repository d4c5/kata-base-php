<?php

namespace Kata\Test\Velocity;

use Kata\Velocity\Ip;

/**
 * TODO LIST:
 *  - Exceptions			[ok]
 *  - Getters				[ok]
 */
class IpTest extends \PHPUnit_Framework_TestCase
{
	const TEST_IP_ADDRESS = '192.168.4.1';
	const TEST_IP_RANGE   = 'OfficeLAN';
	const TEST_IP_COUNTRY = 'LU';

	const FAILED_IP_ADDRESS = 'A.B.C.D';
	const FAILED_IP_COUNTRY = '01';

	const EMPTY_IP_RANGE = false;

	/**
	 * Tests that the IP address is valid.
	 *
     * @expectedException Kata\Velocity\IpException
	 * @expectedExceptionCode 401
     */
    public function testInvalidIpAddressException()
    {
		new Ip(self::FAILED_IP_ADDRESS, self::TEST_IP_RANGE, self::TEST_IP_COUNTRY);
    }

	/**
	 * Tests that the country is valid.
	 *
     * @expectedException Kata\Velocity\IpException
	 * @expectedExceptionCode 402
     */
    public function testInvalidIpCountryException()
    {
		new Ip(self::TEST_IP_ADDRESS, self::TEST_IP_RANGE, self::FAILED_IP_COUNTRY);
    }

	/**
	 * Tests that the IP range is not empty.
	 *
     * @expectedException Kata\Velocity\IpException
	 * @expectedExceptionCode 403
     */
    public function testTheIpRangeIsRequiredException()
    {
		new Ip(self::TEST_IP_ADDRESS, self::EMPTY_IP_RANGE, self::TEST_IP_COUNTRY);
    }

	/**
	 * Tests the given and returned IP addresses are equal.
	 */
	public function testGetIp()
	{
		$ip = new Ip(self::TEST_IP_ADDRESS, self::TEST_IP_RANGE, self::TEST_IP_COUNTRY);

		$this->assertEquals(self::TEST_IP_ADDRESS, $ip->getIp());
	}

	/**
	 * Tests the given and returned IP ranges are equal.
	 */
	public function testGetUnit()
	{
		$ip = new Ip(self::TEST_IP_ADDRESS, self::TEST_IP_RANGE, self::TEST_IP_COUNTRY);

		$this->assertEquals(self::TEST_IP_RANGE, $ip->getRange());
	}

	/**
	 * Tests the given and returned countries are equal.
	 */
	public function testGetCountry()
	{
		$ip = new Ip(self::TEST_IP_ADDRESS, self::TEST_IP_RANGE, self::TEST_IP_COUNTRY);

		$this->assertEquals(self::TEST_IP_COUNTRY, $ip->getCountry());
	}

}
