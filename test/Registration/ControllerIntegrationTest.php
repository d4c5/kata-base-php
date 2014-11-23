<?php

namespace Kata\Test\Registration;

use Kata\Registration\Response;
use Kata\Registration\Request;
use Kata\Registration\Controller;
use Kata\Registration\Validator;
use Kata\Registration\Generator;
use Kata\Registration\UserBuilder;
use Kata\Registration\UserDao;

/**
 * Registration controller test.
 */
class ControllerIntegrationTest extends \PHPUnit_Framework_TestCase
{
	/** The name of test DB. */
	const TEST_DATABASE_FILE = 'RegistrationTest.db';

	/** Test data */
	const USER1_USERNAME        = 'frodo';
	const USER1_PLAIN_PASSWORD  = 'B4gg1ns';
	const USER1_HASHED_PASSWORD = 'cbacb647f5ab835807a8c86f2edd894822317590';

	const USER2_USERNAME        = 'gamgee';
	const USER2_PLAIN_PASSWORD  = 'Samwis3';
	const USER2_HASHED_PASSWORD = 'df859e0972f3698d940ac9c7e5f97ff5a9f5a652';

	const USER3_USERNAME        = 'meriadoc';
	const USER3_PLAIN_PASSWORD  = 'Br4ndybuck';
	const USER3_HASHED_PASSWORD = 'b61bbf853e3b95f18d0df2a852823edadca58027';

	const INVALID_USERNAME = 'dwa-lin';
	const INVALID_PASSWORD = 'M1m';

	/**
	 * Database connection.
	 *
	 * @var SQLite3
	 */
	private $dbConnection = null;

	/**
	 * Controller object.
	 *
	 * @var Controller
	 */
	private $controller = null;

	/**
	 * Sets DB connection and controller objects and creates users SQL table.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->dbConnection = new \SQLite3('test/Registration/' . self::TEST_DATABASE_FILE);
		$this->dbConnection->exec("
			CREATE TABLE `users` (
				`username` VARCHAR(128) NOT NULL PRIMARY KEY,
				`password_hash` VARCHAR(64) NOT NULL
			)"
		);

		$validator   = new Validator();
		$generator   = new Generator();
		$userBuilder = new UserBuilder($generator);
		$userDao     = new UserDao($this->dbConnection);

		$this->controller = new Controller($validator, $userBuilder, $userDao);
	}

	/**
	 * Drops users table.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
		$this->dbConnection->exec("DROP TABLE IF EXISTS `users`");
	}

	/**
	 * Tests registration.
	 *
	 * @param Response $expectedResponse
	 * @param Request  $request
	 *
	 * @return void
	 *
	 * @dataProvider providerRequests
	 */
	public function testDoRegistration(Response $expectedResponse, Request $request)
	{
		if ($expectedResponse->statusCode === Response::CODE_USER_ALREADY_EXISTS)
		{
			$response = $this->controller->doRegistration($request);
		}

		$response = $this->controller->doRegistration($request);

		$this->assertEquals($expectedResponse->status, $response->status, "The status doesn't match!");
		$this->assertEquals($expectedResponse->statusCode, $response->statusCode, "The status code doesn't match!");

		if ($response->statusCode === Response::CODE_OK)
		{
			$sth = $this->dbConnection->prepare("
				SELECT
					COUNT(*) AS cnt
				FROM
					`users`
				WHERE
					`username` = :username"
			);

			$sth->bindValue(':username', $request->username);
			$resultCheck = $sth->execute();

			$row = $resultCheck->fetchArray(SQLITE3_ASSOC);

			$this->assertEquals(1, $row['cnt'], "The storage is failed! [" . $request->username . "]");
		}
	}

	/**
	 * Tests auto registration.
	 *
	 * @param Response $expectedResponse
	 * @param Request  $request
	 *
	 * @return void
	 *
	 * @dataProvider providerRequestsWithoutPassword
	 */
	public function testDoAutoRegistration(Response $expectedResponse, Request $request)
	{
		if ($expectedResponse->statusCode === Response::CODE_USER_ALREADY_EXISTS)
		{
			$response = $this->controller->doAutoRegistration($request);
		}

		$response = $this->controller->doAutoRegistration($request);

		$this->assertEquals($expectedResponse->status, $response->status, "The status doesn't match!");
		$this->assertEquals($expectedResponse->statusCode, $response->statusCode, "The status code doesn't match!");

		if ($response->statusCode === Response::CODE_OK)
		{
			$sth = $this->dbConnection->prepare("
				SELECT
					COUNT(*) AS cnt
				FROM
					`users`
				WHERE
					`username` = :username"
			);

			$sth->bindValue(':username', $request->username);
			$resultCheck = $sth->execute();

			$row = $resultCheck->fetchArray(SQLITE3_ASSOC);

			$this->assertEquals(1, $row['cnt'], "The storage is failed! [" . $request->username . "]");
		}
	}

	/**
	 * Returns request and expected response objects.
	 *
	 * @return array
	 */
	public function providerRequests()
	{
		return array(
			// CODE_OK
			array(
				new Response(Response::STATUS_SUCCESS, Response::CODE_OK),
				new Request(self::USER1_USERNAME, self::USER1_PLAIN_PASSWORD, self::USER1_PLAIN_PASSWORD),
			),
			array(
				new Response(Response::STATUS_SUCCESS, Response::CODE_OK),
				new Request(self::USER2_USERNAME, self::USER2_PLAIN_PASSWORD, self::USER2_PLAIN_PASSWORD),
			),
			array(
				new Response(Response::STATUS_SUCCESS, Response::CODE_OK),
				new Request(self::USER3_USERNAME, self::USER3_PLAIN_PASSWORD, self::USER3_PLAIN_PASSWORD),
			),

			// CODE_USERNAME_FORMAT_ERROR <- E_INVALID_USERNAME
			array(
				new Response(Response::STATUS_FAILURE, Response::CODE_USERNAME_FORMAT_ERROR),
				new Request(self::INVALID_USERNAME, self::USER1_PLAIN_PASSWORD, self::USER1_PLAIN_PASSWORD),
			),

			// CODE_USER_ALREADY_EXISTS   <- E_USERNAME_IS_NOT_UNIQUE
			array(
				new Response(Response::STATUS_FAILURE, Response::CODE_USER_ALREADY_EXISTS),
				new Request(self::USER1_USERNAME, self::USER1_PLAIN_PASSWORD, self::USER1_PLAIN_PASSWORD),
			),

			// CODE_PASSWORD_FORMAT_ERROR <- E_INVALID_PASSWORD
			array(
				new Response(Response::STATUS_FAILURE, Response::CODE_PASSWORD_FORMAT_ERROR),
				new Request(self::USER1_USERNAME, self::INVALID_PASSWORD, self::INVALID_PASSWORD),
			),

			// CODE_PASSWORD_FORMAT_ERROR <- E_PASSWORDS_DO_NOT_MATCH
			array(
				new Response(Response::STATUS_FAILURE, Response::CODE_PASSWORD_FORMAT_ERROR),
				new Request(self::USER1_USERNAME, self::USER1_PLAIN_PASSWORD, self::USER2_PLAIN_PASSWORD),
			),

			// CODE_OTHER_ERROR <- ?
		);
	}

	/**
	 * Returns request and expected response objects.
	 *
	 * @return array
	 */
	public function providerRequestsWithoutPassword()
	{
		return array(
			// CODE_OK
			array(
				new Response(Response::STATUS_SUCCESS, Response::CODE_OK),
				new Request(self::USER1_USERNAME),
			),
			array(
				new Response(Response::STATUS_SUCCESS, Response::CODE_OK),
				new Request(self::USER2_USERNAME),
			),
			array(
				new Response(Response::STATUS_SUCCESS, Response::CODE_OK),
				new Request(self::USER3_USERNAME),
			),

			// CODE_USERNAME_FORMAT_ERROR <- E_INVALID_USERNAME
			array(
				new Response(Response::STATUS_FAILURE, Response::CODE_USERNAME_FORMAT_ERROR),
				new Request(self::INVALID_USERNAME),
			),

			// CODE_USER_ALREADY_EXISTS   <- E_USERNAME_IS_NOT_UNIQUE
			array(
				new Response(Response::STATUS_FAILURE, Response::CODE_USER_ALREADY_EXISTS),
				new Request(self::USER1_USERNAME),
			),

			// CODE_OTHER_ERROR <- ?
		);
	}

}
