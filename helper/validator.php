<?php
/**
 *
 * This file is part of the phpBB Forum Software package.
 *
 * @copyright (c) phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * For full copyright and license information, please see
 * the docs/CREDITS.txt file.
 *
 */

namespace phpbb\skeleton\helper;

use phpbb\user;

class validator
{
	/** @var user */
	protected $user;

	public function __construct(user $user)
	{
		$this->user = $user;
	}

	public function validate_num_authors($value)
	{
		if (preg_match('#^\d+$#', $value) && $value > 0 && $value <= 20)
		{
			return $value;
		}

		throw new \RuntimeException($this->user->lang('SKELETON_INVALID_NUM_AUTHORS'));
	}

	public function validate_extension_name($value)
	{
		if (preg_match('#^[a-zA-Z][a-zA-Z0-9]*$#', $value))
		{
			return $value;
		}

		throw new \RuntimeException($this->user->lang('SKELETON_INVALID_EXTENSION_NAME'));
	}

	public function validate_extension_time($value)
	{
		if (preg_match('#^\d{4}\-\d{2}\-\d{2}$#', $value))
		{
			return $value;
		}

		throw new \RuntimeException($this->user->lang('SKELETON_INVALID_EXTENSION_TIME'));
	}

	public function validate_extension_version($value)
	{
		if (preg_match('#^\d+(\.\d){1,3}(?:-((?:a|b|RC|pl)\d+)|dev)?$#', $value))
		{
			return $value;
		}

		throw new \RuntimeException($this->user->lang('SKELETON_INVALID_EXTENSION_VERSION'));
	}

	public function validate_vendor_name($value)
	{
		if (preg_match('#^[a-zA-Z][a-zA-Z0-9]*$#', $value))
		{
			return $value;
		}

		throw new \RuntimeException($this->user->lang('SKELETON_INVALID_VENDOR_NAME'));
	}
}
