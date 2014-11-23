<?php

namespace Kata\Test\Registration;

use Kata\Registration\Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
	/** Test data */
	const USER1_USERNAME = 'thorin';
	const USER1_PASSWORD = 'Oak3nsh1eld';

	const USER2_USERNAME = 'balin';
	const USER2_PASSWORD = 'TheK1ngOfKh4zadDum';

	const USER3_USERNAME = 'gimli';
	const USER3_PASSWORD = 'TheLord0fAglar0nd';

	/** Test data to exceptions */
	const INVALID_USERNAME = 'dwa-lin';
	const INVALID_PASSWORD = 'M1m';

	/**
	 * Validator object.
	 *
	 * @param Validator
	 */
	private $validator = null;

	/**
	 * Sets validator object.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->validator = new Validator();
	}

	/**
	 * Tests usernames.
	 *
	 * @param string $username
	 *
	 * @return void
	 *
	 * @dataProvider providerUsernames
	 */
	public function testIsUsername($username)
	{
		$this->assertTrue($this->validator->isUsername($username), "The username is invalid! [" . $username . "]");
	}

	/**
	 * Tests passwords.
	 *
	 * @param string $password
	 * @param string $passwordConfirm
	 *
	 * @return void
	 *
	 * @dataProvider providerPasswords
	 */
	public function testIsPassword($password, $passwordConfirm)
	{
		$this->assertTrue($this->validator->isPassword($password, $passwordConfirm), "The password is invalid! [" . $password . ", " . $passwordConfirm . "]");
	}

	/**
	 * Tests invalid username.
	 *
	 * @expectedException      Kata\Registration\RegistrationException
	 * @expectedExceptionCode  301
	 */
	public function testInvalidUsername()
	{
		$this->validator->isUsername(self::INVALID_USERNAME);
	}

	/**
	 * Tests invalid password.
	 *
	 * @return void
	 *
	 * @expectedException      Kata\Registration\RegistrationException
	 * @expectedExceptionCode  302
	 */
	public function testInvalidPassword()
	{
		$this->validator->isPassword(self::INVALID_PASSWORD, self::INVALID_PASSWORD);
	}

	/**
	 * Tests invalid password confirmation.
	 *
	 * @return void
	 *
	 * @expectedException      Kata\Registration\RegistrationException
	 * @expectedExceptionCode  303
	 */
	public function testInvalidConfirmPassword()
	{
		$this->validator->isPassword(self::USER1_PASSWORD, self::INVALID_PASSWORD);
	}

	/**
	 * Returns usernames.
	 *
	 * @return array
	 */
	public function providerUsernames()
	{
		return array(
			array(self::USER1_USERNAME),
			array(self::USER2_USERNAME),
			array(self::USER3_USERNAME),
		);
	}

	/**
	 * Returns passwords.
	 *
	 * @return array
	 */
	public function providerPasswords()
	{
		return array(
			array(self::USER1_PASSWORD, self::USER1_PASSWORD),
			array(self::USER2_PASSWORD, self::USER2_PASSWORD),
			array(self::USER3_PASSWORD, self::USER3_PASSWORD),
		);
	}

}
