<?php

namespace Kata\Test\Registration;

use Kata\Registration\User;
use Kata\Registration\UserDao;

class UserDaoTest extends \PHPUnit_Framework_TestCase
{
	/** The name of test DB. */
	const TEST_DATABASE_FILE = 'RegistrationTest.db';

	/** Test data */
	const USER1_USERNAME        = 'gandalf';
	const USER1_PLAIN_PASSWORD  = 'TheGr3yWiz4rd';
	const USER1_HASHED_PASSWORD = '761d635b5ebb448768f68f25c4579db801989ba7';

	const USER2_USERNAME        = 'saruman';
	const USER2_PLAIN_PASSWORD  = 'TheWhit3W1zard';
	const USER2_HASHED_PASSWORD = 'a65003b41c807c0b4f946ac29f826ce84c6cd2c1';

	const USER3_USERNAME        = 'radagast';
	const USER3_PLAIN_PASSWORD  = 'TheBr0wnW1z4rd';
	const USER3_HASHED_PASSWORD = '2de05ecb5e495d95590247ef0c7947b5f13adf95';

	/**
	 * Database connection.
	 *
	 * @var SQLite3
	 */
	private $dbConnection = null;

	/**
	 * UserDao object.
	 *
	 * @var UserDao
	 */
	private $userDao = null;

	/**
	 * Sets database connection.
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

		$this->userDao = new UserDao($this->dbConnection);
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
	 * Tests storage.
	 *
	 * @param User $user
	 *
	 * @return void
	 *
	 * @dataProvider providerUsers
	 */
	public function testStore(User $user)
	{
		$result = $this->userDao->store($user);

		$this->assertTrue($result, "The result of the storage is failed!");

		$sth = $this->dbConnection->prepare("
			SELECT
				`username`,
				`password_hash`
			FROM
				`users`
			WHERE
				`username` = :username");

		$sth->bindValue(':username', $user->username);
		$resultCheck = $sth->execute();

		$row = $resultCheck->fetchArray(SQLITE3_ASSOC);

		$this->assertEquals($user->username, $row['username'], "The username doesn't match! [" . $user->username . ", " . $row['username'] . "]");
		$this->assertEquals($user->passwordHash, $row['password_hash'], "The password doesn't match! [" . $user->passwordHash . ", " . $row['password_hash'] . "]");
	}

	/**
	 * Tests unique username exception.
	 *
	 * @expectedException      Kata\Registration\RegistrationException
	 * @expectedExceptionCode  201
	 */
	public function testUnique()
	{
		$sth = $this->dbConnection->prepare("
			INSERT INTO
				`users`
			(
				`username`,
				`password_hash`
			)
			VALUES
			(
				:username,
				:passwordHash
			)"
		);

		$sth->bindValue(':username', self::USER1_USERNAME);
		$sth->bindValue(':passwordHash', self::USER1_HASHED_PASSWORD);

		$sth->execute();

		$user = new User(self::USER1_USERNAME, self::USER1_HASHED_PASSWORD, self::USER1_PLAIN_PASSWORD);

		$this->userDao->store($user);
	}

	/**
	 * Returns user objects.
	 *
	 * @return array
	 */
	public function providerUsers()
	{
		return array(
			array(new User(self::USER1_USERNAME, self::USER1_HASHED_PASSWORD, self::USER1_PLAIN_PASSWORD)),
			array(new User(self::USER2_USERNAME, self::USER2_HASHED_PASSWORD, self::USER2_PLAIN_PASSWORD)),
			array(new User(self::USER3_USERNAME, self::USER3_HASHED_PASSWORD, self::USER3_PLAIN_PASSWORD)),
		);
	}

}
