<?php

namespace Kata\Registration;

/**
 * User DB object.
 */
class UserDao
{
	/**
	 * SQLite3 database connection.
	 *
	 * @var SQLite3
	 */
	private $dbConnection = null;

	/**
	 * Sets DB connection.
	 *
	 * @param SQLite3 $dbConnection
	 *
	 * @return void
	 */
	public function __construct(\SQLite3 $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}

	/**
	 * Checks that given username is unique.
	 *
	 * @param string $username
	 *
	 * @return boolean
	 *
	 * @throws RegistrationException
	 */
	private function isUnique($username)
	{
		$statement = $this->dbConnection->prepare("
			SELECT
				COUNT(*) AS cnt
			FROM
				`users`
			WHERE
				`username` = :username"
		);

		$statement->bindValue(':username', $username);

		$result = $statement->execute();
		if ($result === false)
		{
			// @codeCoverageIgnoreStart
			throw new RegistrationException($this->dbConnection->lastErrorMsg(), RegistrationException::G_SQL_ERROR);
			// @codeCoverageIgnoreEnd
		}

		$row = $result->fetchArray(SQLITE3_ASSOC);

		return !empty($row['cnt']) ? false : true;
	}

	/**
	 * Stores given user.
	 *
	 * @param User $user
	 *
	 * @return boolean  Returns true if storage is successful.
	 *
	 * @throws RegistrationException
	 */
	public function store(User $user)
	{
		if (!$this->isUnique($user->username))
		{
			throw new RegistrationException($user->username, RegistrationException::E_USERNAME_IS_NOT_UNIQUE);
		}

		$statement = $this->dbConnection->prepare("
			INSERT INTO
				`users`
			(
				`username`,
				`password_hash`
			)
			VALUES
			(
				:username,
				:password
			)"
		);

		$statement->bindValue(':username', $user->username);
		$statement->bindValue(':password', $user->passwordHash);

		$result = $statement->execute();
		if ($result === false)
		{
			// @codeCoverageIgnoreStart
			throw new RegistrationException($this->dbConnection->lastErrorMsg(), RegistrationException::G_SQL_ERROR);
			// @codeCoverageIgnoreEnd
		}

		return true;
	}

}
