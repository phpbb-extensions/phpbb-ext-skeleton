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

namespace phpbb\skeleton\controller;

function add_form_key($form_name)
{
}

function check_form_key($form_name)
{
	global $phpbb_skeleton_form_key_valid;

	return $phpbb_skeleton_form_key_valid;
}
