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
	protected string $name;

	/** @var bool */
	protected bool $default;

	/** @var array */
	protected array $dependencies;

	/** @var array */
	protected array $files;

	/** @var string */
	protected string $group;

	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param bool $default
	 * @param array  $dependencies
	 * @param array  $files
	 * @param string $group
	 */
	public function __construct(string $name, bool $default, array $dependencies, array $files, string $group)
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
	public function get_name(): string
	{
		return $this->name;
	}

	/**
	 * Get default
	 *
	 * @return bool
	 */
	public function get_default(): bool
	{
		return $this->default;
	}

	/**
	 * Get dependencies
	 *
	 * @return array
	 */
	public function get_dependencies(): array
	{
		return $this->dependencies;
	}

	/**
	 * Get files
	 *
	 * @return array
	 */
	public function get_files(): array
	{
		return $this->files;
	}

	/**
	 * Get group
	 *
	 * @return string
	 */
	public function get_group(): string
	{
		return $this->group;
	}
}
