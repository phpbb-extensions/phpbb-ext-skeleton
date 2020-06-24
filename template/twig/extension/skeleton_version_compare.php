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

namespace phpbb\skeleton\template\twig\extension;

class skeleton_version_compare extends \Twig_Extension
{
	/**
	 * Get the name of this extension
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'skeleton_version_compare';
	}

	/**
	 * Returns a list of global functions to add to the existing list.
	 *
	 * @return array An array of global functions
	 */
	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('skeleton_version_compare', [$this, 'version_compare']),
		];
	}

	/**
	 * Use phpbb_version_compare() in templates.
	 *
	 * How to use in a template:
	 * - {{ if skeleton_version_compare('1.0.0', '2.0.0', '>=') }}
	 * All three arguments are required.
	 *
	 * @uses \phpbb_version_compare()
	 *
	 * @return bool Result of version compare, or false if any version was invalid.
	 */
	public function version_compare()
	{
		$args = func_get_args();

		// Strip out any prefixed junk in front of a version number
		$regex = '/^[\D]*(\d.*)$/';
		preg_match($regex, $args[0], $ver1);
		preg_match($regex, $args[1], $ver2);

		return isset($ver1[1], $ver2[1], $args[2]) ? phpbb_version_compare($ver1[1], $ver2[1], $args[2]) : false;
	}
}
