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

namespace {EXTENSION.vendor_name}\{EXTENSION.extension_name}\migrations;

class install_ucp_module extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		$sql = 'SELECT module_id
			FROM ' . $this->table_prefix . "modules
			WHERE module_class = 'ucp'
				AND module_langname = 'UCP_DEMO_TITLE'";
		$result = $this->db->sql_query($sql);
		$module_id = (int) $this->db->sql_fetchfield('module_id');
		$this->db->sql_freeresult($result);

		return !$module_id;
	}

	static public function depends_on()
	{
		return array('\{EXTENSION.vendor_name}\{EXTENSION.extension_name}\migration\install_user_chema');
	}

	public function update_data()
	{
		return array(
			array('module.add', array(
				'ucp',
				0,
				'UCP_DEMO_TITLE'
			)),
			array('module.add', array(
				'ucp',
				'UCP_DEMO_TITLE',
				array(
					'module_basename'	=> '\{EXTENSION.vendor_name}\{EXTENSION.extension_name}\ucp\main_module',
					'modes'				=> array('settings'),
				),
			)),
		);
	}
}
