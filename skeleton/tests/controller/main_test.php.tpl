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

namespace {EXTENSION.vendor_name}\{EXTENSION.extension_name}\tests\controller;

class main_test extends \phpbb_test_case
{
	public function handle_data()
	{
		return array(
			array(200, 'demo_body.html'),
		);
	}

	/**
	 * @dataProvider handle_data
	 */
	public function test_handle($status_code, $page_content)
	{
		$controller = new \{EXTENSION.vendor_name}\{EXTENSION.extension_name}\controller\main(
			new \phpbb\config\config(array()),
			new \{EXTENSION.vendor_name}\{EXTENSION.extension_name}\tests\mock\controller_helper(),
			new \{EXTENSION.vendor_name}\{EXTENSION.extension_name}\tests\mock\template(),
			new \{EXTENSION.vendor_name}\{EXTENSION.extension_name}tests\mock\user()
		);

		$response = $controller->handle('test');
		$this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		$this->assertEquals($status_code, $response->getStatusCode());
		$this->assertEquals($page_content, $response->getContent());
	}
}
