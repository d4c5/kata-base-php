<?php

namespace Kata\Test\Registration;

use Kata\Registration\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests the constructor.
	 *
	 * @return void
	 */
	public function testConstructor()
	{
		$response = new Response(Response::STATUS_SUCCESS, Response::CODE_OK);

		$this->assertEquals($response->status, Response::STATUS_SUCCESS, "The status does not match!");
		$this->assertEquals($response->statusCode, Response::CODE_OK, "The status code does not match!");
	}

}
