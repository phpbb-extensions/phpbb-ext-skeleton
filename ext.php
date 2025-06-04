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
	public const DEFAULT_SKELETON_PHP = '7.1.3';
	public const DEFAULT_SKELETON_PHPBB_MIN = '3.3.0';
	public const DEFAULT_SKELETON_PHPBB_MAX = '4.0.0@dev';

	public const MIN_PHPBB_ALLOWED = '3.3.0';
	public const MAX_PHPBB_ALLOWED = '4.0.0-dev';
	public const MIN_PHP_ALLOWED = 70100;

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
	 * Check phpBB requirements.
	 *
	 * @param string $phpBB_version
	 * @return void
	 */
	protected function phpbb_requirement($phpBB_version = PHPBB_VERSION)
	{
		if (phpbb_version_compare($phpBB_version, self::MIN_PHPBB_ALLOWED, '<'))
		{
			$this->errors[] = 'PHPBB_VERSION_MIN_ERROR';
		}
		else if (phpbb_version_compare($phpBB_version, self::MAX_PHPBB_ALLOWED, '>='))
		{
			$this->errors[] = 'PHPBB_VERSION_MAX_ERROR';
		}
	}

	/**
	 * Check PHP minimum requirement.
	 *
	 * @param int $php_version
	 * @return void
	 */
	protected function php_requirement($php_version = PHP_VERSION_ID)
	{
		if ($php_version < self::MIN_PHP_ALLOWED)
		{
			$this->errors[] = 'PHP_VERSION_ERROR';
		}
	}

	/**
	 * Check PHP ZipArchive binary requirement.
	 *
	 * @param string $zip_class
	 * @return void
	 */
	protected function ziparchive_exists($zip_class = 'ZipArchive')
	{
		if (!class_exists($zip_class, false))
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
