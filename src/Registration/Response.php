<?php

namespace Kata\Registration;

/**
 * Response.
 */
class Response
{
	/**
	 * Statuses of a response.
	 */
	const STATUS_SUCCESS = 'yes';
	const STATUS_FAILURE = 'no';

	/**
	 * Codes of a status.
	 */
	const CODE_OK                    = 201;
	const CODE_OTHER_ERROR           = 500;
	const CODE_USERNAME_FORMAT_ERROR = 601;
	const CODE_PASSWORD_FORMAT_ERROR = 602;
	const CODE_USER_ALREADY_EXISTS   = 701;

	/**
	 * Status of the response (yes, no).
	 *
	 * @var string
	 */
	public $status     = '';

	/**
	 * The code of the status.
	 *
	 * @var int
	 */
	public $statusCode = 0;

	/**
	 * Sets attributes of the response.
	 *
	 * @param string $status
	 * @param int    $statusCode
	 *
	 * @return void
	 */
	public function __construct($status = '', $statusCode = 0)
	{
		$this->status     = $status;
		$this->statusCode = $statusCode;
	}

}
