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

use phpbb\controller\helper;
use phpbb\event\dispatcher;
use phpbb\skeleton\event\main_listener;
use phpbb\template\template;
use phpbb_test_case;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class event_listener_test extends phpbb_test_case
{
	protected helper|MockObject $controller_helper;
	protected template|MockObject $template;
	protected main_listener $listener;

	public function setUp(): void
	{
		$this->template = $this->getMockBuilder(template::class)
			->disableOriginalConstructor()
			->getMock();
		$this->controller_helper = $this->getMockBuilder(helper::class)
			->disableOriginalConstructor()
			->getMock();

		$this->listener = new main_listener(
			$this->controller_helper,
			$this->template
		);
	}

	public function test_construct()
	{
		$this->assertInstanceOf(EventSubscriberInterface::class, $this->listener);
	}

	/**
	 * Test the event listener is subscribing events
	 */
	public function test_getSubscribedEvents()
	{
		$this->assertEquals(array(
			'core.user_setup',
			'core.page_header',
		), array_keys(main_listener::getSubscribedEvents()));
	}

	public function test_load_language_on_setup()
	{
		$dispatcher = new dispatcher();
		$dispatcher->addListener('core.user_setup', [$this->listener, 'load_language_on_setup']);

		$lang_set_ext = [];
		$event_data = ['lang_set_ext'];
		$event_data_after = $dispatcher->trigger_event('core.user_setup', compact($event_data));
		extract($event_data_after);

		$this->assertEquals([['ext_name' => 'phpbb/skeleton', 'lang_set' => 'common']], $lang_set_ext);
	}

	public function test_add_page_header_link()
	{
		$this->controller_helper->expects($this->once())
			->method('route')
			->willReturnArgument(0);

		$this->template->expects($this->once())
			->method('assign_var')
			->with('U_PHPBB_SKELETON_EXT', 'phpbb_skeleton_controller');

		$dispatcher = new dispatcher();
		$dispatcher->addListener('core.page_header', [$this->listener, 'add_page_header_link']);

		$dispatcher->trigger_event('core.page_header');
	}
}
