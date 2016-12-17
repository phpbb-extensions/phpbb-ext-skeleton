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
	/**
	 * Check whether or not the extension can be enabled.
	 *
	 * @return bool
	 */
	public function is_enableable()
	{
		return $this->php_requirements() && ($this->phpbb_31x_compatible() || $this->phpbb_current_compatible());
	}

	/**
	 * Check PHP requirements
	 *
	 * Requires PHP 5.4.0 or greater
	 * Requires PHP ZipArchive binary
	 *
	 * @return bool
	 */
	protected function php_requirements()
	{
		return phpbb_version_compare(PHP_VERSION, '5.4.0', '>=') && class_exists('ZipArchive');
	}

	/**
	 * Check phpBB 3.2 (and later) compatibility
	 *
	 * Requires phpBB 3.2.0-b3 or greater
	 *
	 * @return bool
	 */
	protected function phpbb_current_compatible()
	{
		return phpbb_version_compare(PHPBB_VERSION, '3.2.0-b3', '>=');
	}

	/**
	 * Check phpBB 3.1 compatibility
	 *
	 * Requires phpBB 3.1.4 or greater
	 *
	 * @return bool
	 */
	protected function phpbb_31x_compatible()
	{
		return phpbb_version_compare(PHPBB_VERSION, '3.1.4', '>=') && phpbb_version_compare(PHPBB_VERSION, '3.2.0-dev', '<');
	}
}

