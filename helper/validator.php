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
	protected $language;

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
	public function validate_num_authors($value)
	{
		if ($value > 0 && $value <= 20 && ctype_digit($value))
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
	public function validate_extension_name($value)
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
	public function validate_extension_display_name($value)
	{
		if ((string) $value !== '')
		{
			return $this->validate_generated_string($value, 'SKELETON_INVALID_DISPLAY_NAME');
		}

		throw new runtime_exception($this->language->lang('SKELETON_INVALID_DISPLAY_NAME'));
	}

	/**
	 * Validate the extension description for insertion into composer.json
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_extension_description($value)
	{
		return $this->validate_generated_string($value, 'SKELETON_INVALID_EXTENSION_DESCRIPTION');
	}

	/**
	 * Validate and require the extension date/time
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_extension_time($value)
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
	public function validate_extension_version($value)
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
	public function validate_vendor_name($value)
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
	public function validate_extension_homepage($value)
	{
		$value = $this->validate_generated_string($value, 'SKELETON_INVALID_EXTENSION_URL');

		if ($value !== '' && filter_var($value, FILTER_VALIDATE_URL) === false)
		{
			throw new runtime_exception($this->language->lang('SKELETON_INVALID_EXTENSION_URL'));
		}

		return $value;
	}

	/**
	 * Validate the author name
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_author_name($value)
	{
		return $this->validate_generated_string($value, 'SKELETON_INVALID_AUTHOR_NAME');
	}

	/**
	 * Validate the author homepage URL
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_author_homepage($value)
	{
		$value = $this->validate_generated_string($value, 'SKELETON_INVALID_AUTHOR_URL');

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
	public function validate_author_email($value)
	{
		$value = $this->validate_generated_string($value, 'SKELETON_INVALID_AUTHOR_EMAIL');

		if ($value !== '' && filter_var($value, FILTER_VALIDATE_EMAIL) === false)
		{
			throw new runtime_exception($this->language->lang('SKELETON_INVALID_AUTHOR_EMAIL'));
		}

		return $value;
	}

	/**
	 * Validate the author role
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_author_role($value)
	{
		return $this->validate_generated_string($value, 'SKELETON_INVALID_AUTHOR_ROLE');
	}

	/**
	 * Validate and require the phpBB minimum version
	 *
	 * @param string $value The value to validate
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	public function validate_phpbb_version_min($value)
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
	public function validate_phpbb_version_max($value)
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
	public function validate_php_version($value)
	{
		if ($this->check_version($value))
		{
			return $value;
		}

		throw new runtime_exception($this->language->lang('SKELETON_INVALID_PHP_VERSION'));

	}

	/**
	 * Validate a string inserted without escaping into generated files.
	 * Values must be safe inside a JSON string and must not contain a PHP comment
	 * terminator. Request values are HTML escaped, so inspect their decoded form
	 * and return safe characters without HTML entities.
	 *
	 * @param string $value The value to validate
	 * @param string $error Language key used for invalid input
	 * @return string The valid value
	 * @throws runtime_exception
	 */
	protected function validate_generated_string($value, $error)
	{
		$decoded_value = htmlspecialchars_decode((string) $value, ENT_QUOTES);

		if (preg_match('/["\\\\\x00-\x1F]/', $decoded_value) || strpos($decoded_value, '*/') !== false)
		{
			throw new runtime_exception($this->language->lang($error));
		}

		return htmlspecialchars_decode((string) $value, ENT_NOQUOTES);
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
	protected function check_version($value)
	{
		return (bool) preg_match('/^[<>=]*[\d+][\w.@-]+$/', htmlspecialchars_decode($value, ENT_NOQUOTES));
	}
}
