<?php

namespace Kata\Test\Registration;

use Kata\Registration\Response;
use Kata\Registration\Request;
use Kata\Registration\User;
use Kata\Registration\Controller;
use Kata\Registration\RegistrationException;

/**
 * Registration controller test.
 */
class ControllerTest extends \PHPUnit_Framework_TestCase
{
	/** Provider flags */
	const REQUEST_WITH_PASSWORD    = true;
	const REQUEST_WITHOUT_PASSWORD = false;

	/** Method responses */
	const IS_USERNAME_SUCCESSFUL_RESPONSE = true;
	const IS_PASSWORD_SUCCESSFUL_RESPONSE = true;
	const STORE_SUCCESSFUL_RESPONSE       = true;

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
	 * Tests registration.
	 *
	 * @param Response $expectedResponse       The expected response from Controller.
	 * @param Request  $request                The request.
	 * @param mixed    $responseOfIsUsername   The response of the Validator::isUsername method.
	 * @param mixed    $responseOfIsPassword   The response of the Validator::isPassword method.
	 * @param mixed    $responseOfCreateUser   The response of the UserBuilder::createUser method.
	 * @param mixed    $responseOfStore        The response of the UserDao::store method.
	 *
	 * @return void
	 *
	 * @dataProvider providerRequests
	 */
	public function testDoRegistration(
		Response $expectedResponse, Request $request,
		$responseOfIsUsername = null, $responseOfIsPassword = null, $responseOfCreateUser = null, $responseOfStore = null
	) {
		$validator   = $this->getValidatorMock($responseOfIsUsername, $responseOfIsPassword);
		$userBuilder = $this->getUserBuilderMock($responseOfCreateUser);
		$userDao     = $this->getUserDaoMock($responseOfStore);

		$controller  = new Controller($validator, $userBuilder, $userDao);
		$response    = $controller->doRegistration($request);

		$this->assertEquals($expectedResponse->status,     $response->status,     "The status doesn't match! [" . $request->username . "]");
		$this->assertEquals($expectedResponse->statusCode, $response->statusCode, "The status code doesn't match! [" . $request->username . "]");
	}

	/**
	 * Tests auto registration.
	 *
	 * @param Response $expectedResponse       The expected response from Controller.
	 * @param Request  $request                The request.
	 * @param mixed    $responseOfIsUsername   The response of the Validator::isUsername method.
	 * @param mixed    $responseOfIsPassword   The response of the Validator::isPassword method.
	 * @param mixed    $responseOfCreateUser   The response of the UserBuilder::createUser method.
	 * @param mixed    $responseOfStore        The response of the UserDao::store method.
	 *
	 * @return void
	 *
	 * @dataProvider providerRequestsWithoutPassword
	 */
	public function testDoAutoRegistration(
		Response $expectedResponse, Request $request,
		$responseOfIsUsername = null, $responseOfIsPassword = null, $responseOfCreateUser = null, $responseOfStore = null
	) {
		$validator   = $this->getValidatorMock($responseOfIsUsername, $responseOfIsPassword);
		$userBuilder = $this->getUserBuilderMock($responseOfCreateUser);
		$userDao     = $this->getUserDaoMock($responseOfStore);

		$controller  = new Controller($validator, $userBuilder, $userDao);
		$response    = $controller->doAutoRegistration($request);

		$this->assertEquals($expectedResponse->status,     $response->status,     "The status doesn't match! [" . $request->username . "]");
		$this->assertEquals($expectedResponse->statusCode, $response->statusCode, "The status code doesn't match! [" . $request->username . "]");
	}

	/**
	 * Returns:
	 * - request object,
	 * - response object,
	 * - response of isUsername (true or RegistrationException),
	 * - response of isPassword (true or RegistrationException),
	 * - response User object of creation,
	 * - response of store (true or RegistrationException).
	 *
	 * @return array
	 */
	public function providerRequests()
	{
		return array_merge(
			$this->getSuccessfulRequests(),
			$this->getUsernameFormatErrorRequests(),
			$this->getPasswordFormatErrorRequests(),
			$this->getUsernameAlreadyExistsRequests(),
			$this->getOtherErrorRequests()
		);
	}

	/**
	 * Returns:
	 * - request object,
	 * - response object,
	 * - response of isUsername (true or RegistrationException),
	 * - response of isPassword (true or RegistrationException),
	 * - response User object of creation,
	 * - response of store (true or RegistrationException).
	 *
	 * @return array
	 */
	public function providerRequestsWithoutPassword()
	{
		return array_merge(
			$this->getSuccessfulRequests(self::REQUEST_WITHOUT_PASSWORD),
			$this->getUsernameFormatErrorRequests(self::REQUEST_WITHOUT_PASSWORD),
			$this->getUsernameAlreadyExistsRequests(self::REQUEST_WITHOUT_PASSWORD),
			$this->getOtherErrorRequests(self::REQUEST_WITHOUT_PASSWORD)
		);
	}

	/**
	 * Returns successul (CODE_OK) registration requests.
	 *
	 * @param boolean $withPassword
	 *
	 * @return array
	 */
	private function getSuccessfulRequests($withPassword = self::REQUEST_WITH_PASSWORD)
	{
		return array(
			array(
				new Response(Response::STATUS_SUCCESS, Response::CODE_OK),
				new Request(self::USER1_USERNAME, ($withPassword ? self::USER1_PLAIN_PASSWORD : null), ($withPassword ? self::USER1_PLAIN_PASSWORD : null)),
				self::IS_USERNAME_SUCCESSFUL_RESPONSE,
				self::IS_PASSWORD_SUCCESSFUL_RESPONSE,
				new User(self::USER1_USERNAME, self::USER1_HASHED_PASSWORD, self::USER1_PLAIN_PASSWORD),
				self::STORE_SUCCESSFUL_RESPONSE,
			),
			array(
				new Response(Response::STATUS_SUCCESS, Response::CODE_OK),
				new Request(self::USER2_USERNAME, ($withPassword ? self::USER2_PLAIN_PASSWORD : null), ($withPassword ? self::USER2_PLAIN_PASSWORD : null)),
				self::IS_USERNAME_SUCCESSFUL_RESPONSE,
				self::IS_PASSWORD_SUCCESSFUL_RESPONSE,
				new User(self::USER2_USERNAME, self::USER2_HASHED_PASSWORD, self::USER2_PLAIN_PASSWORD),
				self::STORE_SUCCESSFUL_RESPONSE,
			),
			array(
				new Response(Response::STATUS_SUCCESS, Response::CODE_OK),
				new Request(self::USER3_USERNAME, ($withPassword ? self::USER3_PLAIN_PASSWORD : null), ($withPassword ? self::USER3_PLAIN_PASSWORD : null)),
				self::IS_USERNAME_SUCCESSFUL_RESPONSE,
				self::IS_PASSWORD_SUCCESSFUL_RESPONSE,
				new User(self::USER3_USERNAME, self::USER3_HASHED_PASSWORD, self::USER3_PLAIN_PASSWORD),
				self::STORE_SUCCESSFUL_RESPONSE,
			),
		);
	}

	/**
	 * Returns failed (CODE_USERNAME_FORMAT_ERROR) registration requests.
	 *
	 * @param boolean $withPassword
	 *
	 * @return array
	 */
	private function getUsernameFormatErrorRequests($withPassword = self::REQUEST_WITH_PASSWORD)
	{
		return array(
			array(
				new Response(Response::STATUS_FAILURE, Response::CODE_USERNAME_FORMAT_ERROR),
				new Request(self::INVALID_USERNAME, ($withPassword ? self::USER1_PLAIN_PASSWORD : null), ($withPassword ? self::USER1_PLAIN_PASSWORD : null)),
				new RegistrationException(self::INVALID_USERNAME, RegistrationException::E_INVALID_USERNAME),
			),
		);
	}

	/**
	 * Returns failed (CODE_USER_ALREADY_EXISTS) registration requests.
	 *
	 * @param boolean $withPassword
	 *
	 * @return array
	 */
	private function getUsernameAlreadyExistsRequests($withPassword = self::REQUEST_WITH_PASSWORD)
	{
		return array(
			array(
				new Response(Response::STATUS_FAILURE, Response::CODE_USER_ALREADY_EXISTS),
				new Request(self::USER1_USERNAME, ($withPassword ? self::USER1_PLAIN_PASSWORD : null), ($withPassword ? self::USER1_PLAIN_PASSWORD : null)),
				new RegistrationException(self::USER1_USERNAME, RegistrationException::E_USERNAME_IS_NOT_UNIQUE),
			),
		);
	}

	/**
	 * Returns failed (CODE_PASSWORD_FORMAT_ERROR) registration requests.
	 *
	 * @return array
	 */
	private function getPasswordFormatErrorRequests()
	{
		return array(
			// E_INVALID_PASSWORD
			array(
				new Response(Response::STATUS_FAILURE, Response::CODE_PASSWORD_FORMAT_ERROR),
				new Request(self::USER1_USERNAME, self::INVALID_PASSWORD, self::INVALID_PASSWORD),
				self::IS_USERNAME_SUCCESSFUL_RESPONSE,
				new RegistrationException(self::USER1_PLAIN_PASSWORD, RegistrationException::E_INVALID_PASSWORD),
			),

			// E_PASSWORDS_DO_NOT_MATCH
			array(
				new Response(Response::STATUS_FAILURE, Response::CODE_PASSWORD_FORMAT_ERROR),
				new Request(self::USER1_USERNAME, self::USER1_PLAIN_PASSWORD, self::USER2_PLAIN_PASSWORD),
				self::IS_USERNAME_SUCCESSFUL_RESPONSE,
				new RegistrationException(self::USER1_PLAIN_PASSWORD . ', ' . self::USER1_PLAIN_PASSWORD, RegistrationException::E_PASSWORDS_DO_NOT_MATCH),
			),
		);
	}

	/**
	 * Returns failed (CODE_OTHER_ERROR) registration requests.
	 *
	 * @param boolean $withPassword
	 *
	 * @return array
	 */
	private function getOtherErrorRequests($withPassword = self::REQUEST_WITH_PASSWORD)
	{
		return array(
			// G_SQL_ERROR
			array(
				new Response(Response::STATUS_FAILURE, Response::CODE_OTHER_ERROR),
				new Request(self::USER1_USERNAME, ($withPassword ? self::USER1_PLAIN_PASSWORD : null), ($withPassword ? self::USER2_PLAIN_PASSWORD : null)),
				self::IS_USERNAME_SUCCESSFUL_RESPONSE,
				self::IS_PASSWORD_SUCCESSFUL_RESPONSE,
				new User(self::USER1_USERNAME, self::USER1_HASHED_PASSWORD, self::USER1_PLAIN_PASSWORD),
				new RegistrationException("Generated SQL error", RegistrationException::G_SQL_ERROR),
			),

			// G_REGEX_ERROR
			array(
				new Response(Response::STATUS_FAILURE, Response::CODE_OTHER_ERROR),
				new Request(self::USER1_USERNAME, ($withPassword ? self::USER1_PLAIN_PASSWORD : null), ($withPassword ? self::USER2_PLAIN_PASSWORD : null)),
				new RegistrationException("Generated REGEX error", RegistrationException::G_REGEX_ERROR),
			),
		);
	}

	/**
	 * Creates and returns a validator mock object.
	 *
	 * @param mixed $responseOfIsUsername   True or RegistrationException.
	 * @param mixed $responseOfIsPassword   True or RegistrationException.
	 *
	 * @return Validator
	 */
	private function getValidatorMock($responseOfIsUsername, $responseOfIsPassword = null)
	{
		$validator = $this->getMock('\Kata\Registration\Validator');

		if ($responseOfIsUsername instanceof RegistrationException)
		{
			$returnIsUsername = $this->throwException($responseOfIsUsername);
		}
		else
		{
			$returnIsUsername = $this->returnValue($responseOfIsUsername);
		}

		$validator->expects($this->once())
				->method('isUsername')
				->will($returnIsUsername);

		if ($responseOfIsPassword !== null)
		{
			if ($responseOfIsPassword instanceof RegistrationException)
			{
				$returnIsPassword = $this->throwException($responseOfIsPassword);
			}
			else
			{
				$returnIsPassword = $this->returnValue($responseOfIsPassword);
			}

			$validator->method('isPassword')
					->will($returnIsPassword);
		}

		return $validator;
	}

	/**
	 * Creates and returns UserBuilder mock object.
	 *
	 * @param mixed $responseOfCreateUser   True or RegistrationException.
	 *
	 * @return UserBuilder
	 */
	private function getUserBuilderMock($responseOfCreateUser)
	{
		$userBuilder = $this->getMock(
			'\Kata\Registration\UserBuilder',
			array('createUser'),
			array(),
			'',
			false
		);

		if ($responseOfCreateUser instanceof RegistrationException)
		{
			$returnCreateUser = $this->throwException($responseOfCreateUser);
		}
		else
		{
			$returnCreateUser = $this->returnValue($responseOfCreateUser);
		}

		$userBuilder->method('createUser')
				->will($returnCreateUser);

		return $userBuilder;
	}

	/**
	 * Creates and returns UserDao mock object.
	 *
	 * @param mixed $responseOfStore   True or RegistrationException.
	 *
	 * @return UserDao
	 */
	public function getUserDaoMock($responseOfStore)
	{
		$userDao = $this->getMock(
			'\Kata\Registration\UserDao',
			array('store'),
			array(),
			'',
			false
		);

		if ($responseOfStore instanceof RegistrationException)
		{
			$returnStore = $this->throwException($responseOfStore);
		}
		else
		{
			$returnStore = $this->returnValue($responseOfStore);
		}

		$userDao->method('store')
				->will($returnStore);

		return $userDao;
	}

}
