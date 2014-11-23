<?php

namespace Kata\Test\Registration;

use Kata\Registration\Generator;

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
	/** Number of the generated passwords */
	const NUMBER_OF_GENERATED_PASSWORDS = 100;

	/**
	 * Generator object.
	 *
	 * @param Generator
	 */
	private $generator = null;

	/**
	 * Sets generator object.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		$this->generator = new Generator();
	}

	/**
	 * Tests syntax of generated passwords.
	 *
	 * @return void
	 */
	public function testGetPasswordSyntax()
	{
		$passwordRegex = '/^[' . Generator::PASSWORD_CHARACTERS . ']{' . Generator::PASSWORD_MIN_LENGTH . ',' . Generator::PASSWORD_MAX_LENGTH . '}$/';

		for ($i = 0; $i < self::NUMBER_OF_GENERATED_PASSWORDS; $i++)
		{
			$generatedPassword = $this->generator->getPassword();

			$this->assertEquals(1, preg_match($passwordRegex, $generatedPassword), "The password does not match the rules! [" . $i . ": " . $generatedPassword . "]");
		}
	}

	/**
	 * Tests unique of generated passwords.
	 *
	 * @return void
	 */
	public function testGetPasswordUnique()
	{
		$previousPassword = $this->generator->getPassword();

		for ($i = 0; $i < self::NUMBER_OF_GENERATED_PASSWORDS; $i++)
		{
			$generatedPassword = $this->generator->getPassword();

			$this->assertNotEquals($previousPassword, $generatedPassword, "The passwords are the same! [" . $previousPassword . ", " . $generatedPassword . "]");

			$previousPassword = $generatedPassword;
		}
	}

}
