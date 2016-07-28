<?php
/**
 *
 * @package phpBB Extension - Acme Demo
 * @copyright (c) 2013 phpBB Group
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */
namespace phpbb\skeleton;

/**
 * Class ext
 *
 * It is recommended to remove this file from
 * an extension if it is not going to be used.
 */
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
	 * @access public
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

