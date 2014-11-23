<?php

namespace Kata\Test\Registration;

use Kata\Registration\UserBuilder;
use Kata\Registration\User;
use Kata\Registration\Request;

class UserBuilderTest extends \PHPUnit_Framework_TestCase
{
	/** Test data */
	const USER1_USERNAME        = 'elrond';
	const USER1_PLAIN_PASSWORD  = 'TheLordOf1mladr1s';
	const USER1_HASHED_PASSWORD = '852f1157e7f230a66f1d03691f7708567b5ef063';

	const USER2_USERNAME        = 'galadriel';
	const USER2_PLAIN_PASSWORD  = 'TheQueen0fLothl0rien';
	const USER2_HASHED_PASSWORD = 'ff0144cc5a82d26acef4cdeb97450658f63e8e5f';

	const USER3_USERNAME        = 'haldir';
	const USER3_PLAIN_PASSWORD  = 'WarriorOfLothl0rien';
	const USER3_HASHED_PASSWORD = 'baa7d605eb0ebc9253b9484a493285b3f0da5dff';

	/**
	 * Tests user creation.
	 *
	 * @param Request $request
	 * @param User    $user
	 *
	 * @return void
	 *
	 * @dataProvider providerUsers
	 */
	public function testCreateUser($request, $user)
	{
		$generator = $this->getMock('\Kata\Registration\Generator');
		$generator->method('getPassword')
				->willReturn('');

		$userBuilder = new UserBuilder($generator);

		$this->assertEquals($user, $userBuilder->createUser($request), "The user object doesn't match! [" . $user->username . "]");
	}

	/**
	 * Tests user creation without password.
	 *
	 * @param Request $request
	 * @param User    $user
	 *
	 * @return voids
	 *
	 * @dataProvider providerUsersWithoutPassword
	 */
	public function testCreateUserWithoutPassword($request, $user)
	{
		$generator = $this->getMock('\Kata\Registration\Generator');
		$generator->expects($this->once())
				->method('getPassword')
				->willReturn($user->passwordPlain);

		$userBuilder = new UserBuilder($generator);

		$this->assertEquals($user, $userBuilder->createUser($request), "The user object doesn't match! [" . $user->username . "]");
	}

	/**
	 * Returns request and user objects.
	 *
	 * @return array
	 */
	public function providerUsers()
	{
		return array(
			array(
				new Request(self::USER1_USERNAME, self::USER1_PLAIN_PASSWORD, self::USER1_PLAIN_PASSWORD),
				new User(self::USER1_USERNAME, self::USER1_HASHED_PASSWORD, self::USER1_PLAIN_PASSWORD),
			),
			array(
				new Request(self::USER2_USERNAME, self::USER2_PLAIN_PASSWORD, self::USER2_PLAIN_PASSWORD),
				new User(self::USER2_USERNAME, self::USER2_HASHED_PASSWORD, self::USER2_PLAIN_PASSWORD),
			),
			array(
				new Request(self::USER3_USERNAME, self::USER3_PLAIN_PASSWORD, self::USER3_PLAIN_PASSWORD),
				new User(self::USER3_USERNAME, self::USER3_HASHED_PASSWORD, self::USER3_PLAIN_PASSWORD),
			),
		);
	}

	/**
	 * Returns request and user objects.
	 *
	 * @return array
	 */
	public function providerUsersWithoutPassword()
	{
		return array(
			array(
				new Request(self::USER1_USERNAME),
				new User(self::USER1_USERNAME, self::USER1_HASHED_PASSWORD, self::USER1_PLAIN_PASSWORD),
			),
			array(
				new Request(self::USER2_USERNAME),
				new User(self::USER2_USERNAME, self::USER2_HASHED_PASSWORD, self::USER2_PLAIN_PASSWORD),
			),
			array(
				new Request(self::USER3_USERNAME),
				new User(self::USER3_USERNAME, self::USER3_HASHED_PASSWORD, self::USER3_PLAIN_PASSWORD),
			),
		);
	}

}
