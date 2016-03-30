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

namespace {EXTENSION.vendor_name}\{EXTENSION.extension_name}\tests\dbal;

// Need to include functions.php to use phpbb_version_compare in this test
require_once dirname(__FILE__) . '/../../../../../includes/functions.php';

class simple_test extends \phpbb_database_test_case
{
	static protected function setup_extensions()
	{
		return array('{EXTENSION.vendor_name}/{EXTENSION.extension_name}');
	}

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	public function getDataSet()
	{
		return $this->createXMLDataSet(dirname(__FILE__) . '/fixtures/config.xml');
	}

	public function test_column()
	{
		$this->db = $this->new_dbal();
		$tools = (phpbb_version_compare(PHPBB_VERSION, '3.2.0-dev', '<') ? '\phpbb\db\tools' : '\phpbb\db\tools\tools');
		$db_tools = new $tools($this->db);
		$this->assertTrue($db_tools->sql_column_exists(USERS_TABLE, 'user_acme'), 'Asserting that column "user_acme" exists');
		$this->assertFalse($db_tools->sql_column_exists(USERS_TABLE, 'user_acme_demo'), 'Asserting that column "user_acme_demo" does not exist');
	}
}
