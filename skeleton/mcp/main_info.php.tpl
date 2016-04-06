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

namespace {EXTENSION.vendor_name}\{EXTENSION.extension_name}\mcp;

class main_info
{
	function module()
	{
		return array(
			'filename'	=> '\{EXTENSION.vendor_name}\{EXTENSION.extension_name}\mcp\main_module',
			'title'		=> 'MCP_DEMO_TITLE',
			'modes'		=> array(
				'front'	=> array(
					'title'	=> 'MCP_DEMO',
					'auth'	=> 'ext_{EXTENSION.vendor_name}/{EXTENSION.extension_name}',
					'cat'	=> array('MCP_DEMO_TITLE')
				),
			),
		);
	}
}
