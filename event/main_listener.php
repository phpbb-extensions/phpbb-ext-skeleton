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

namespace phpbb\skeleton\event;

/**
 * @ignore
 */

use phpbb\controller\helper;
use phpbb\event\data;
use phpbb\template\template;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class main_listener implements EventSubscriberInterface
{
	public static function getSubscribedEvents(): array
	{
		return [
			'core.user_setup'	=> 'load_language_on_setup',
			'core.page_header'	=> 'add_page_header_link',
		];
	}

	/* @var helper */
	protected helper $helper;

	/* @var template */
	protected template $template;

	/**
	 * Constructor
	 *
	 * @param helper $helper   Controller helper object
	 * @param template $template Template object
	 */
	public function __construct(helper $helper, template $template)
	{
		$this->helper = $helper;
		$this->template = $template;
	}

	/**
	 * Load language files
	 *
	 * @param data $event
	 */
	public function load_language_on_setup(data $event): void
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'phpbb/skeleton',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * Add the page header link
	 */
	public function add_page_header_link(): void
	{
		$this->template->assign_var('U_PHPBB_SKELETON_EXT', $this->helper->route('phpbb_skeleton_controller'));
	}
}
