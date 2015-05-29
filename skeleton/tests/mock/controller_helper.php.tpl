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

namespace {EXTENSION.vendor_name}\{EXTENSION.extension_name}\tests\mock;

/**
* Controller helper Mock
* @package phpBB3
*/
class controller_helper extends \phpbb\controller\helper
{
	public function __construct()
	{
	}

	public function route($route, array $params = array(), $is_amp = true, $session_id = false, $reference_type = false)
	{
		return $route . '#' . serialize($params);
	}

	public function error($message, $code = 500)
	{
		return new \Symfony\Component\HttpFoundation\Response($message, $code);
	}

	public function render($template_file, $page_title = '', $status_code = 200, $display_online_list = false)
	{
		return new \Symfony\Component\HttpFoundation\Response($template_file, $status_code);
	}
}
