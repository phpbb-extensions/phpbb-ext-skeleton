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

namespace {{ EXTENSION.vendor_name }}\{{ EXTENSION.extension_name }}\migrations;

class install_user_schema extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return $this->db_tools->sql_column_exists($this->table_prefix . 'users', 'user_acme');
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v31x\v314');
	}

	public function update_schema()
	{
		return array(
			'add_tables'		=> array(
				$this->table_prefix . 'acme_demo'	=> array(
					'COLUMNS'		=> array(
						'acme_id'			=> array('UINT', null, 'auto_increment'),
						'acme_name'			=> array('VCHAR:255', ''),
					),
					'PRIMARY_KEY'	=> 'acme_id',
				),
			),
			'add_columns'	=> array(
				$this->table_prefix . 'users'			=> array(
					'user_acme'				=> array('UINT', 0),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns'	=> array(
				$this->table_prefix . 'users'			=> array(
					'user_acme',
				),
			),
			'drop_tables'		=> array(
				$this->table_prefix . 'acme_demo',
			),
		);
	}
}
