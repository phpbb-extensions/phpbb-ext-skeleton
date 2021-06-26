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

namespace phpbb\skeleton\tests\controller;

use phpbb\exception\http_exception;

class main_test extends \phpbb_test_case
{
	/** @var \phpbb\template\template|\PHPUnit\Framework\MockObject\MockObject */
	protected $template;

	/** @var \phpbb\language\language|\PHPUnit\Framework\MockObject\MockObject */
	protected $language;

	/** @var \phpbb\request\request|\PHPUnit\Framework\MockObject\MockObject */
	protected $request;

	/** @var \phpbb\user|\PHPUnit\Framework\MockObject\MockObject */
	protected $user;

	/** @var \phpbb\controller\helper|\PHPUnit\Framework\MockObject\MockObject */
	protected $controller_helper;

	/** @var \phpbb\skeleton\helper\packager|\PHPUnit\Framework\MockObject\MockObject */
	protected $packager;

	/** @var \phpbb\skeleton\helper\validator|\PHPUnit\Framework\MockObject\MockObject */
	protected $validator;

	protected function setUp(): void
	{
		// Mocks are dummy implementations that provide the API of components we depend on //
		/** @var \phpbb\template\template|\PHPUnit_Framework_MockObject_MockObject $template Mock the template class */
		$this->template = $this->getMockBuilder('\phpbb\template\template')
			->disableOriginalConstructor()
			->getMock();

		/** @var \phpbb\language\language|\PHPUnit_Framework_MockObject_MockObject $language Mock the language class */
		$this->language = $this->getMockBuilder('\phpbb\language\language')
			->disableOriginalConstructor()
			->getMock();

		/** @var \phpbb\request\request|\PHPUnit_Framework_MockObject_MockObject $request */
		$this->request = $this->getMockBuilder('\phpbb\request\request')
			->disableOriginalConstructor()
			->getMock();

		/** @var \phpbb\user|\PHPUnit_Framework_MockObject_MockObject $user */
		$this->user = $this->getMockBuilder('\phpbb\user')
			->disableOriginalConstructor()
			->getMock();

		/** @var \phpbb\controller\helper|\PHPUnit_Framework_MockObject_MockObject $controller_helper Mock the controller helper class */
		$this->controller_helper = $this->getMockBuilder('\phpbb\controller\helper')
			->disableOriginalConstructor()
			->getMock();

		/** @var \phpbb\skeleton\helper\packager|\PHPUnit_Framework_MockObject_MockObject $packager */
		$this->packager = $this->getMockBuilder('\phpbb\skeleton\helper\packager')
			->disableOriginalConstructor()
			->getMock();

		/** @var \phpbb\skeleton\helper\validator $validator */
		$this->validator = $this->getMockBuilder('\phpbb\skeleton\helper\validator')
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * @return \phpbb\skeleton\controller\main
	 */
	public function get_controller(): \phpbb\skeleton\controller\main
	{
		return new \phpbb\skeleton\controller\main(
			new \phpbb\config\config([]),
			$this->controller_helper,
			$this->language,
			$this->request,
			$this->packager,
			$this->validator,
			$this->template,
			$this->user
		);
	}

	public function handle_data(): array
	{
		return [
			[200, '@phpbb_skeleton/skeleton_body.html'],
		];
	}

	/**
	 * @dataProvider handle_data
	 * @param int $status_code
	 * @param string $page_content
	 * @throws \Exception
	 */
	public function test_handle($status_code, $page_content)
	{
		$this->user->data['is_bot'] = false;

		$this->language->method('lang')
			->will(self::returnArgument(0));
		$this->language->method('is_set')
			->willReturn(true);

		$this->request->expects(self::once())
			->method('is_set_post')
			->willReturn(false);

		$this->request->method('variable')
			->with(self::anything())
			->willReturnMap([
				['author_name', [''], true, \phpbb\request\request_interface::REQUEST, [null]],
				['vendor_name', '', true, \phpbb\request\request_interface::REQUEST, ''],
				['php_version', '>=5.4', false, \phpbb\request\request_interface::REQUEST, ''],
				['component_phplistener', false, false, \phpbb\request\request_interface::REQUEST, ''],
			]);

		$this->controller_helper->expects(self::once())
			->method('render')
			->willReturnCallback(function ($template_file, $page_title = '', $status_code = 200) {
				return new \Symfony\Component\HttpFoundation\Response($template_file, $status_code);
			});

		$this->packager->expects(self::once())
			->method('get_composer_dialog_values')
			->willReturn([
				'author' => ['author_name' => null],
				'extension' => ['vendor_name' => null],
				'requirements' => ['php_version' => '>=5.4'],
			]);

		$this->packager->expects(self::once())
			->method('get_component_dialog_values')
			->willReturn([
				'phplistener' => ['default' => false, 'group' => 'BACK_END']
			]);

		$this->template->expects(self::atLeastOnce())
			->method('assign_block_vars')
			->withConsecutive(
				['extension', [
					'NAME'         => 'vendor_name',
					'DESC'         => 'SKELETON_QUESTION_VENDOR_NAME_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_VENDOR_NAME_EXPLAIN',
					'VALUE'        => '',
				]],
				['author', [
					'NAME'         => 'author_name',
					'DESC'         => 'SKELETON_QUESTION_AUTHOR_NAME_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_AUTHOR_NAME_EXPLAIN',
					'VALUE'        => '',
				]],
				['requirement', [
					'NAME'         => 'php_version',
					'DESC'         => 'SKELETON_QUESTION_PHP_VERSION_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_PHP_VERSION_EXPLAIN',
					'VALUE'        => '',
				]],
				['component_BACK_END', [
					'NAME'         => 'component_phplistener',
					'DESC'         => 'SKELETON_QUESTION_COMPONENT_PHPLISTENER_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_COMPONENT_PHPLISTENER_EXPLAIN',
					'VALUE'        => '',
				]]
			)
		;

		$response = $this->get_controller()->handle();

		self::assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
		self::assertEquals($status_code, $response->getStatusCode());
		self::assertEquals($page_content, $response->getContent());
	}

	/**
	 * @throws \Exception
	 */
	public function test_handle_unauthorised()
	{
		$this->user->data['is_bot'] = true;

		$this->expectException(http_exception::class);
		$this->expectExceptionMessage('NOT_AUTHORISED');

		$this->get_controller()->handle();
	}

	public function test_submit_success()
	{
		$this->user->data['is_bot'] = false;

		$this->request->expects(self::once())
			->method('is_set_post')
			->willReturn(true);

		$this->request->method('variable')
			->willReturnMap([
				['author_name', [''], false, \phpbb\request\request_interface::REQUEST, ['foo_auth']],
				['vendor_name', '', true, \phpbb\request\request_interface::REQUEST, 'foo_vendor'],
				['php_version', '>=5.4', false, \phpbb\request\request_interface::REQUEST, '>=5.4'],
				['component_phplistener', false, false, \phpbb\request\request_interface::REQUEST, ''],
			]);

		$this->packager->expects(self::once())
			->method('get_composer_dialog_values')
			->willReturn([
				'author' => ['author_name' => null],
				'extension' => ['vendor_name' => null],
				'requirements' => ['php_version' => '>=5.4'],
			]);

		$this->packager->expects(self::once())
			->method('get_component_dialog_values')
			->willReturn([
				'phplistener' => ['dependencies' => ['config/services.yml', 'event/main_listener.php', 'language/en/common.php']]
			]);

		$this->packager->expects($this->once())
			->method('create_extension');

		$this->packager->expects($this->once())
			->method('create_zip')
			->willReturn(__DIR__ . '/../fixtures/dummy.txt');

		$response = $this->get_controller()->handle();

		self::assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
	}

	public function test_submit_exception()
	{
		$this->user->data['is_bot'] = false;

		$this->request->expects(self::once())
			->method('is_set_post')
			->willReturn(true);

		$this->request->method('variable')
			->with(self::anything())
			->willReturnMap([
				['author_name', [''], true, \phpbb\request\request_interface::REQUEST, ['']],
			]);

		$this->packager->expects(self::atLeastOnce())
			->method('get_composer_dialog_values')
			->willReturn([
				'author' => ['author_name' => null],
				'extension' => ['vendor_name' => null],
				'requirements' => ['php_version' => '>=5.4'],
			]);

		// this returning an empty array will cause the submit to error
		$this->packager->expects(self::atLeastOnce())
			->method('get_component_dialog_values')
			->willReturn([]);

		$this->packager->expects($this->never())
			->method('create_extension');

		$this->packager->expects($this->never())
			->method('create_zip');

		$this->template->expects($this->at(0))
			->method('assign_var')
			->with('ERROR');

		$this->get_controller()->handle();
	}
}
