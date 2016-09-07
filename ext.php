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
	 * The current phpBB version should meet or exceed
	 * the minimum version required by this extension:
	 *
	 * Requires phpBB 3.1.4
	 *
	 * @return bool
	 */
	public function is_enableable()
	{
		return class_exists('ZipArchive') && ($this->phpbb_legacy_compatibility() || $this->phpbb_current_compatibility());
	}

	/**
	 * Check phpBB 3.1 compatibility
	 *
	 * Requires phpBB 3.1.4 or greater
	 *
	 * @return bool
	 */
	protected function phpbb_legacy_compatibility()
	{
		return phpbb_version_compare(PHPBB_VERSION, '3.1.4', '>=') && phpbb_version_compare(PHPBB_VERSION, '3.2.0-dev', '<');
	}

	/**
	 * Check phpBB 3.2 (and later) compatibility
	 *
	 * Requires phpBB 3.2.0-b3 or greater
	 *
	 * @return bool
	 */
	protected function phpbb_current_compatibility()
	{
		return phpbb_version_compare(PHPBB_VERSION, '3.2.0-b3', '>=');
	}
}

