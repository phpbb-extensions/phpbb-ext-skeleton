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

namespace phpbb\skeleton;

class ext extends \phpbb\extension\base
{
	const DEFAULT_PHP = '7.1.3';
	const DEFAULT_PHPBB_MIN = '3.3.0';
	const DEFAULT_PHPBB_MAX = '4.0.0@dev';

	/**
	 * @var array An array of installation error messages
	 */
	protected $errors = [];

	/**
	 * Check whether the extension can be enabled.
	 *
	 * @return bool|array True if it can be enabled. False if not, or an array of error messages in phpBB 3.3.
	 */
	public function is_enableable()
	{
		// Check requirements
		$this->phpbb_requirement();
		$this->php_requirement();
		$this->ziparchive_exists();

		return count($this->errors) ? $this->enable_failed() : true;
	}

	/**
	 * Check phpBB 3.2.3 minimum requirement.
	 *
	 * @return void
	 */
	protected function phpbb_requirement()
	{
		if (phpbb_version_compare(PHPBB_VERSION, '4.0.0-dev', '<'))
		{
			$this->errors[] = 'PHPBB_VERSION_ERROR';
		}
	}

	/**
	 * Check PHP 8.1 minimum requirement.
	 *
	 * @return void
	 */
	protected function php_requirement()
	{
		if (PHP_VERSION_ID < 80100)
		{
			$this->errors[] = 'PHP_VERSION_ERROR';
		}
	}

	/**
	 * Check PHP ZipArchive binary requirement.
	 *
	 * @return void
	 */
	protected function ziparchive_exists()
	{
		if (!class_exists('ZipArchive'))
		{
			$this->errors[] = 'NO_ZIPARCHIVE_ERROR';
		}
	}

	/**
	 * Generate the best enable failed response for the current phpBB environment.
	 * Return error messages in phpBB 3.3 or newer. Return boolean false otherwise.
	 *
	 * @return array|bool
	 */
	protected function enable_failed()
	{
		if (phpbb_version_compare(PHPBB_VERSION, '3.3.0-b1', '>='))
		{
			$language = $this->container->get('language');
			$language->add_lang('common', 'phpbb/skeleton');
			return array_map([$language, 'lang'], $this->errors);
		}

		return false;
	}
}
