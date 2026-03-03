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

use phpbb\exception\runtime_exception;
use phpbb\language\language;

class validator
{
	/** @var language */
	protected language $language;

	/**
	 * Constructor
	 *
	 * @param language $language
	 */
	public function __construct(language $language)
	{
		$this->language = $language;
	}

	/**
	 * Validate the number of authors
	 * Should be between 1 and 20
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_num_authors(string $value): string
	{
		if ((int) $value > 0 && (int) $value <= 20 && ctype_digit($value))
		{
			return $value;
		}

		throw new runtime_exception($this->language->lang('SKELETON_INVALID_NUM_AUTHORS'));
	}

	/**
	 * Validate and require the extension name
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_extension_name(string $value): string
	{
		if (preg_match('#^[a-z][a-z0-9]*$#', $value))
		{
			return $value;
		}

		throw new runtime_exception($this->language->lang('SKELETON_INVALID_PACKAGE_NAME'));
	}

	/**
	 * Validate and require the extension display name
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_extension_display_name(string $value): string
	{
		if ($value !== '' && !str_contains($value, '&quot;'))
		{
			return htmlspecialchars_decode($value, ENT_NOQUOTES);
		}

		throw new runtime_exception($this->language->lang('SKELETON_INVALID_DISPLAY_NAME'));
	}

	/**
	 * Validate and require the extension date/time
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_extension_time(string $value): string
	{
		if (preg_match('#^\d{4}-\d{2}-\d{2}$#', $value))
		{
			return $value;
		}

		throw new runtime_exception($this->language->lang('SKELETON_INVALID_EXTENSION_TIME'));
	}

	/**
	 * Validate and require the extension version number
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_extension_version(string $value): string
	{
		if (preg_match('#^\d+(\.\d){1,3}(-(((?:a|b|RC|pl)\d+)|dev))?$#', $value))
		{
			return $value;
		}

		throw new runtime_exception($this->language->lang('SKELETON_INVALID_EXTENSION_VERSION'));
	}

	/**
	 * Validate and require the extension vendor name
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_vendor_name(string $value): string
	{
		if ($value !== 'core' && preg_match('#^[a-z][a-z0-9]*$#', $value))
		{
			return $value;
		}

		throw new runtime_exception($this->language->lang('SKELETON_INVALID_VENDOR_NAME'));
	}

	/**
	 * Validate the extension homepage URL
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_extension_homepage(string $value): string
	{
		if ( $value !== '' && filter_var($value, FILTER_VALIDATE_URL) === false)
		{
			throw new runtime_exception($this->language->lang('SKELETON_INVALID_EXTENSION_URL'));
		}

		return $value;
	}

	/**
	 * Validate the author homepage URL
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_author_homepage(string $value): string
	{
		if ($value !== '' && filter_var($value, FILTER_VALIDATE_URL) === false)
		{
			throw new runtime_exception($this->language->lang('SKELETON_INVALID_AUTHOR_URL'));
		}

		return $value;
	}

	/**
	 * Validate the author email
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_author_email(string $value): string
	{
		if ($value !== '' && filter_var($value, FILTER_VALIDATE_EMAIL) === false)
		{
			throw new runtime_exception($this->language->lang('SKELETON_INVALID_AUTHOR_EMAIL'));
		}

		return $value;
	}

	/**
	 * Validate and require the phpBB minimum version
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_phpbb_version_min(string $value): string
	{
		if ($this->check_version($value))
		{
			return $value;
		}

		throw new runtime_exception($this->language->lang('SKELETON_INVALID_PHPBB_MIN_VERSION'));

	}

	/**
	 * Validate and require the phpBB maximum version
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_phpbb_version_max(string $value): string
	{
		if ($this->check_version($value))
		{
			return $value;
		}

		throw new runtime_exception($this->language->lang('SKELETON_INVALID_PHPBB_MAX_VERSION'));

	}

	/**
	 * Validate and require the PHP version
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_php_version(string $value): string
	{
		if ($this->check_version($value))
		{
			return $value;
		}

		throw new runtime_exception($this->language->lang('SKELETON_INVALID_PHP_VERSION'));

	}

	/**
	 * Version value check. Checks for values like:
	 *     1.0.0-RC1
	 *     >=1.0.0
	 *     <1.0@dev
	 *
	 * @param string $value The value to check
	 * @return bool True if valid, false if not
	 */
	protected function check_version(string $value): bool
	{
		return (bool) preg_match('/^[<>=]*[\d+][\w.@-]+$/', htmlspecialchars_decode($value, ENT_NOQUOTES));
	}
}
