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
use phpbb\exception\runtime_exception;
use phpbb\skeleton\ext;
use Symfony\Component\DependencyInjection\ContainerInterface;

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

	/** @var \phpbb\skeleton\helper\packager */
	protected $packager;

	/** @var \phpbb\skeleton\helper\packager|\PHPUnit\Framework\MockObject\MockObject */
	protected $packager_mock;

	/** @var \phpbb\skeleton\helper\validator|\PHPUnit\Framework\MockObject\MockObject */
	protected $validator;

	/** @var ContainerInterface */
	protected $container;

	protected function setUp(): void
	{
		global $phpbb_root_path;

		// Mocks are dummy implementations that provide the API of components we depend on //
		$this->template = $this->getMockBuilder('\phpbb\template\template')
			->disableOriginalConstructor()
			->getMock();

		$this->language = $this->getMockBuilder('\phpbb\language\language')
			->disableOriginalConstructor()
			->getMock();

		$this->request = $this->getMockBuilder('\phpbb\request\request')
			->disableOriginalConstructor()
			->getMock();

		$this->user = $this->getMockBuilder('\phpbb\user')
			->disableOriginalConstructor()
			->getMock();

		$this->controller_helper = $this->getMockBuilder('\phpbb\controller\helper')
			->disableOriginalConstructor()
			->getMock();

		$this->packager_mock = $this->getMockBuilder('\phpbb\skeleton\helper\packager')
			->disableOriginalConstructor()
			->getMock();

		$phpbb_container = new \phpbb_mock_container_builder();
		$skeletons = new \phpbb\di\service_collection($phpbb_container);
		$skeletons->add('phpbb.skeleton.ext.skeleton.phplistener');
		$phpbb_container->set(
			'phpbb.skeleton.ext.skeleton.phplistener',
			new \phpbb\skeleton\skeleton('phplistener',
				false,
				[],
				['config/services.yml', 'event/main_listener.php', 'language/en/common.php'],
				'BACK_END'
			)
		);

		$this->packager = new \phpbb\skeleton\helper\packager($phpbb_container, $skeletons, $phpbb_root_path);

		$this->validator = $this->getMockBuilder('\phpbb\skeleton\helper\validator')
			->disableOriginalConstructor()
			->getMock();
	}

	/**
	 * @param \phpbb\skeleton\helper\packager|\PHPUnit\Framework\MockObject\MockObject $packager
	 * @return \phpbb\skeleton\controller\main
	 */
	public function get_controller($packager): \phpbb\skeleton\controller\main
	{
		return new \phpbb\skeleton\controller\main(
			new \phpbb\config\config([]),
			$this->controller_helper,
			$this->language,
			$this->request,
			$packager,
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
				['vendor_name', '', true, \phpbb\request\request_interface::REQUEST, 'foo'],
				['author_name', [''], true, \phpbb\request\request_interface::REQUEST, ['bar']],
				['extension_version', '1.0.0-dev', true, \phpbb\request\request_interface::REQUEST, '1.0.0-dev'],
				['php_version', '>=' . ext::DEFAULT_PHP, false, \phpbb\request\request_interface::REQUEST, '>=' . ext::DEFAULT_PHP],
				['component_phplistener', false, false, \phpbb\request\request_interface::REQUEST, true],
			]);

		$this->controller_helper->expects(self::once())
			->method('render')
			->willReturnCallback(function ($template_file, $page_title = '', $status_code = 200) {
				return new \Symfony\Component\HttpFoundation\Response($template_file, $status_code);
			});

		$this->template->expects(self::atLeastOnce())
			->method('assign_block_vars')
			->withConsecutive(
				['extension', [
					'NAME'         => 'vendor_name',
					'DESC'         => 'SKELETON_QUESTION_VENDOR_NAME_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_VENDOR_NAME_EXPLAIN',
					'VALUE'        => 'foo',
				]],
				['extension', [
					'NAME'         => 'extension_name',
					'DESC'         => 'SKELETON_QUESTION_EXTENSION_NAME_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_EXTENSION_NAME_EXPLAIN',
					'VALUE'        => '',
				]],
				['extension', [
					'NAME'         => 'extension_display_name',
					'DESC'         => 'SKELETON_QUESTION_EXTENSION_DISPLAY_NAME_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_EXTENSION_DISPLAY_NAME_EXPLAIN',
					'VALUE'        => '',
				]],
				['extension', [
					'NAME'         => 'extension_description',
					'DESC'         => 'SKELETON_QUESTION_EXTENSION_DESCRIPTION_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_EXTENSION_DESCRIPTION_EXPLAIN',
					'VALUE'        => '',
				]],
				['extension', [
					'NAME'         => 'extension_version',
					'DESC'         => 'SKELETON_QUESTION_EXTENSION_VERSION_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_EXTENSION_VERSION_EXPLAIN',
					'VALUE'        => '1.0.0-dev',
				]],
				['extension', [
					'NAME'         => 'extension_time',
					'DESC'         => 'SKELETON_QUESTION_EXTENSION_TIME_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_EXTENSION_TIME_EXPLAIN',
					'VALUE'        => '',
				]],
				['extension', [
					'NAME'         => 'extension_homepage',
					'DESC'         => 'SKELETON_QUESTION_EXTENSION_HOMEPAGE_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_EXTENSION_HOMEPAGE_EXPLAIN',
					'VALUE'        => '',
				]],
				['author', [
					'NAME'         => 'author_name',
					'DESC'         => 'SKELETON_QUESTION_AUTHOR_NAME_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_AUTHOR_NAME_EXPLAIN',
					'VALUE'        => 'bar',
				]],
				['author', [
					'NAME'         => 'author_email',
					'DESC'         => 'SKELETON_QUESTION_AUTHOR_EMAIL_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_AUTHOR_EMAIL_EXPLAIN',
					'VALUE'        => '',
				]],
				['author', [
					'NAME'         => 'author_homepage',
					'DESC'         => 'SKELETON_QUESTION_AUTHOR_HOMEPAGE_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_AUTHOR_HOMEPAGE_EXPLAIN',
					'VALUE'        => '',
				]],
				['author', [
					'NAME'         => 'author_role',
					'DESC'         => 'SKELETON_QUESTION_AUTHOR_ROLE_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_AUTHOR_ROLE_EXPLAIN',
					'VALUE'        => '',
				]],
				['requirement', [
					'NAME'         => 'php_version',
					'DESC'         => 'SKELETON_QUESTION_PHP_VERSION_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_PHP_VERSION_EXPLAIN',
					'VALUE'        => '>=' . ext::DEFAULT_PHP,
				]],
				['requirement', [
					'NAME'         => 'phpbb_version_min',
					'DESC'         => 'SKELETON_QUESTION_PHPBB_VERSION_MIN_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_PHPBB_VERSION_MIN_EXPLAIN',
					'VALUE'        => '',
				]],
				['requirement', [
					'NAME'         => 'phpbb_version_max',
					'DESC'         => 'SKELETON_QUESTION_PHPBB_VERSION_MAX_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_PHPBB_VERSION_MAX_EXPLAIN',
					'VALUE'        => '',
				]],
				['component_BACK_END', [
					'NAME'         => 'component_phplistener',
					'DESC'         => 'SKELETON_QUESTION_COMPONENT_PHPLISTENER_UI',
					'DESC_EXPLAIN' => 'SKELETON_QUESTION_COMPONENT_PHPLISTENER_EXPLAIN',
					'VALUE'        => true,
				]]
			)
		;

		$response = $this->get_controller($this->packager)->handle();

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

		$this->get_controller($this->packager_mock)->handle();
	}

	public function test_submit_success()
	{
		$this->user->data['is_bot'] = false;

		$this->request->expects(self::once())
			->method('is_set_post')
			->willReturn(true);

		$this->request->method('variable')
			->willReturnMap([
				['author_name', [''], true, \phpbb\request\request_interface::REQUEST, ['foo_auth']],
				['vendor_name', '', true, \phpbb\request\request_interface::REQUEST, 'foo_vendor'],
				['php_version', '>=5.4', true, \phpbb\request\request_interface::REQUEST, '>=5.4'],
				['component_phplistener', true, false, \phpbb\request\request_interface::REQUEST, true],
				['component_githubactions', true, false, \phpbb\request\request_interface::REQUEST, true],
			]);

		$this->packager_mock->expects(self::once())
			->method('get_composer_dialog_values')
			->willReturn([
				'author' => ['author_name' => null],
				'extension' => ['vendor_name' => null],
				'requirements' => ['php_version' => '>=5.4'],
			]);

		$this->packager_mock->expects(self::once())
			->method('get_component_dialog_values')
			->willReturn([
				'phplistener' => [
					'default'      => false,
					'dependencies' => [],
					'files'        => ['config/services.yml', 'event/main_listener.php', 'language/en/common.php'],
					'group'        => 'BACK_END',
				],
				'githubactions' => [
					'default'      => false,
					'dependencies' => ['tests'],
					'files'        => ['.github/workflows/tests.yml'],
					'group'        => 'TEST_DEPLOY',
				],
			]);

		$this->packager_mock->expects($this->once())
			->method('create_extension');

		$this->packager_mock->expects($this->once())
			->method('create_zip')
			->willReturn(__DIR__ . '/../fixtures/dummy.txt');

		$response = $this->get_controller($this->packager_mock)->handle();

		self::assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $response);
	}

	public function test_submit_exception()
	{
		$this->user->data['is_bot'] = false;

		$this->request->expects(self::once())
			->method('is_set_post')
			->with('submit')
			->willReturn(true);

		$this->request->method('variable')
			->with(self::anything())
			->willReturnMap([
				['author_name', [''], true, \phpbb\request\request_interface::REQUEST, ['']],
				['vendor_name', '', true, \phpbb\request\request_interface::REQUEST, 'foo_vendor'],
				['php_version', '>=5.4', true, \phpbb\request\request_interface::REQUEST, '>=5.4'],
			]);

		$this->packager_mock->expects(self::atLeastOnce())
			->method('get_composer_dialog_values')
			->willReturn([
				'author' => ['author_name' => null],
				'extension' => ['vendor_name' => null],
				'requirements' => ['php_version' => '>=5.4'],
			]);

		$this->packager_mock->expects(self::atLeastOnce())
			->method('get_component_dialog_values')
			->willReturn([
				'phplistener' => [
					'default'      => false,
					'dependencies' => [],
					'files'        => ['config/services.yml', 'event/main_listener.php', 'language/en/common.php'],
					'group'        => 'BACK_END',
				],
			]);

		$this->validator->expects(self::once())
			->method('validate_vendor_name')
			->with('foo_vendor')
			->willThrowException(new runtime_exception('SKELETON_INVALID_VENDOR_NAME'));

		$this->packager_mock->expects($this->never())
			->method('create_extension');

		$this->packager_mock->expects($this->never())
			->method('create_zip');

		$this->template->expects($this->exactly(2))
			->method('assign_var')
			->withConsecutive(
				['ERROR'],
				['S_POST_ACTION']
			);

		$this->get_controller($this->packager_mock)->handle();
	}
}
