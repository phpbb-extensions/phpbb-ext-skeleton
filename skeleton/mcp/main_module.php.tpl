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

class main_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $template, $user;

		$this->tpl_name = 'mcp_demo_body';
		$this->page_title = $user->lang('MCP_DEMO_TITLE');
		add_form_key('acme/demo');

		$template->assign_var('U_POST_ACTION', $this->u_action);
	}
}
