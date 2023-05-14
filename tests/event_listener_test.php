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

namespace phpbb\skeleton\tests;

class event_listener_test extends \phpbb_test_case
{
	/** @var \phpbb\controller\helper|\PHPUnit\Framework\MockObject\MockObject */
	protected $controller_helper;

	/** @var \phpbb\template\template|\PHPUnit\Framework\MockObject\MockObject */
	protected $template;

	/** @var \phpbb\skeleton\event\main_listener */
	protected $listener;

	public function setUp(): void
	{
		$this->template = $this->getMockBuilder('\phpbb\template\template')
			->disableOriginalConstructor()
			->getMock();
		$this->controller_helper = $this->controller_helper = $this->getMockBuilder('\phpbb\controller\helper')
			->disableOriginalConstructor()
			->getMock();

		$this->listener = new \phpbb\skeleton\event\main_listener(
			$this->controller_helper,
			$this->template
		);
	}

	public function test_construct()
	{
		self::assertInstanceOf('\Symfony\Component\EventDispatcher\EventSubscriberInterface', $this->listener);
	}

	/**
	 * Test the event listener is subscribing events
	 */
	public function test_getSubscribedEvents()
	{
		self::assertEquals(array(
			'core.user_setup',
			'core.page_header',
		), array_keys(\phpbb\skeleton\event\main_listener::getSubscribedEvents()));
	}

	public function test_load_language_on_setup()
	{
		$dispatcher = new \phpbb\event\dispatcher();
		$dispatcher->addListener('core.user_setup', [$this->listener, 'load_language_on_setup']);

		$lang_set_ext = [];
		$event_data = ['lang_set_ext'];
		$event_data_after = $dispatcher->trigger_event('core.user_setup', compact($event_data));
		extract($event_data_after, EXTR_OVERWRITE);

		self::assertEquals([['ext_name' => 'phpbb/skeleton', 'lang_set' => 'common']], $lang_set_ext);
	}

	public function test_add_page_header_link()
	{
		$this->controller_helper->expects(self::once())
			->method('route')
			->willReturnArgument(0);

		$this->template->expects(self::once())
			->method('assign_var')
			->with('U_PHPBB_SKELETON_EXT', 'phpbb_skeleton_controller');

		$dispatcher = new \phpbb\event\dispatcher();
		$dispatcher->addListener('core.page_header', [$this->listener, 'add_page_header_link']);

		$dispatcher->trigger_event('core.page_header');
	}
}
