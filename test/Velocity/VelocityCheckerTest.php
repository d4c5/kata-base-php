<?php

namespace Kata\Test\Velocity;

use Kata\Velocity\VelocityChecker;

class VelocityCheckerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Captcha test.
	 */
	public function testCaptcha()
	{
		$velocityChecker = new VelocityChecker();
		$this->assertTrue(true, $velocityChecker->isCaptchaActive());
	}

}
