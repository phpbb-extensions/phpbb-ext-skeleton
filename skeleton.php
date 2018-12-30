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

class skeleton
{
	/** @var string */
	protected $name;

	/** @var bool */
	protected $default;

	/** @var array */
	protected $dependencies;

	/** @var array */
	protected $files;

	/** @var string */
	protected $group;

	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param bool   $default
	 * @param array  $dependencies
	 * @param array  $files
	 * @param string $group
	 */
	public function __construct($name, $default, array $dependencies, array $files, $group)
	{
		$this->name = $name;
		$this->default = $default;
		$this->dependencies = $dependencies;
		$this->files = $files;
		$this->group = $group;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function get_name()
	{
		return $this->name;
	}

	/**
	 * Get default
	 *
	 * @return bool
	 */
	public function get_default()
	{
		return $this->default;
	}

	/**
	 * Get dependencies
	 *
	 * @return array
	 */
	public function get_dependencies()
	{
		return $this->dependencies;
	}

	/**
	 * Get files
	 *
	 * @return array
	 */
	public function get_files()
	{
		return $this->files;
	}

	/**
	 * Get group
	 *
	 * @return string
	 */
	public function get_group()
	{
		return $this->group;
	}
}
