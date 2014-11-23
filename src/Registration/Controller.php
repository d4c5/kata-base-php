<?php

namespace Kata\Registration;

/**
 * Registration controller.
 */
class Controller
{
	/**
	 * Validator object.
	 *
	 * @param Validator
	 */
	private $validator = null;

	/**
	 * User builder object.
	 *
	 * @var UserBuilder
	 */
	private $userBuilder = null;

	/**
	 * User DAO object.
	 *
	 * @var UserDao
	 */
	private $userDao = null;

	/**
	 * Sets validator, user builder and user DAO objects.
	 *
	 * @param Validator   $validator
	 * @param UserBuilder $userBuilder
	 * @param UserDao     $userDao
	 *
	 * @return void
	 */
	public function __construct(Validator $validator, UserBuilder $userBuilder, UserDao $userDao)
	{
		$this->validator   = $validator;
		$this->userBuilder = $userBuilder;
		$this->userDao     = $userDao;
	}

	/**
	 * Creates user.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function doRegistration(Request $request)
	{
		$response = new Response();

		try
		{
			$this->validator->isUsername($request->username);
			$this->validator->isPassword($request->password, $request->passwordConfirm);

			$user = $this->userBuilder->createUser($request);

			$this->userDao->store($user);

			$response->status     = Response::STATUS_SUCCESS;
			$response->statusCode = Response::CODE_OK;
		}
		catch (\Exception $e)
		{
			$response->status     = Response::STATUS_FAILURE;
			$response->statusCode = $this->getStatusCode($e->getCode());
		}

		return $response;
	}

	/**
	 * Creates user.
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function doAutoRegistration(Request $request)
	{
		$response = new Response();

		try
		{
			$request->password        = '';
			$request->passwordConfirm = '';

			$this->validator->isUsername($request->username);

			$user = $this->userBuilder->createUser($request);

			$this->userDao->store($user);

			$response->status     = Response::STATUS_SUCCESS;
			$response->statusCode = Response::CODE_OK;
		}
		catch (\Exception $e)
		{
			$response->status     = Response::STATUS_FAILURE;
			$response->statusCode = $this->getStatusCode($e->getCode());
		}

		return $response;
	}

	/**
	 * Returns status code by exception code.
	 *
	 * @param int $exceptionCode
	 *
	 * @return int
	 */
	private function getStatusCode($exceptionCode = 0)
	{
		$statusCode = Response::CODE_OTHER_ERROR;

		switch ($exceptionCode)
		{
			case RegistrationException::E_INVALID_USERNAME:
				$statusCode = Response::CODE_USERNAME_FORMAT_ERROR;
				break;

			case RegistrationException::E_INVALID_PASSWORD:
			case RegistrationException::E_PASSWORDS_DO_NOT_MATCH:
				$statusCode = Response::CODE_PASSWORD_FORMAT_ERROR;
				break;

			case RegistrationException::E_USERNAME_IS_NOT_UNIQUE:
				$statusCode = Response::CODE_USER_ALREADY_EXISTS;
				break;
		}

		return $statusCode;
	}

}
