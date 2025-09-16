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

namespace phpbb\skeleton\tests\helper;

use ArrayIterator;
use phpbb\extension\manager;
use phpbb\filesystem\filesystem;
use phpbb\path_helper;
use phpbb\skeleton\helper\packager;
use phpbb\di\service_collection;
use phpbb\skeleton\skeleton;
use phpbb\template\assets_bag;
use phpbb\template\twig\twig;
use phpbb\user;
use phpbb_test_case;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerInterface;

class packager_test extends phpbb_test_case
{
	protected ContainerInterface|MockObject $container;
	protected MockObject|service_collection $collection;
	protected packager $packager;
	protected string $root_path = '/tmp/phpbb/';

	/**
	 * @return void
	 */
	public function setUpTheCollectionToReturnAFakeSkeletonClass(): void
	{
		// Set up the collection to return a fake skeleton class
		$skeleton = $this->getMockBuilder(skeleton::class)
			->disableOriginalConstructor()
			->getMock();
		$skeleton->method('get_name')->willReturn('phplistener');
		$skeleton->method('get_default')->willReturn(false);
		$skeleton->method('get_dependencies')->willReturn([]);
		$skeleton->method('get_files')->willReturn(['test.php']);
		$skeleton->method('get_group')->willReturn('BACK_END');

		$this->collection->method('getIterator')->willReturn(new ArrayIterator([$skeleton]));
	}

	protected function setUp(): void
	{
		$this->container = $this->createMock(ContainerInterface::class);
		$this->collection = $this->createMock(service_collection::class);

		$this->packager = new packager($this->container, $this->collection, $this->root_path);
	}

	public function test_get_composer_dialog_values_returns_expected_structure()
	{
		$result = $this->packager->get_composer_dialog_values();
		$this->assertArrayHasKey('author', $result);
		$this->assertArrayHasKey('extension', $result);
		$this->assertArrayHasKey('requirements', $result);
	}

	public function test_get_component_dialog_values_calls_collection()
	{
		$this->setUpTheCollectionToReturnAFakeSkeletonClass();

		$result = $this->packager->get_component_dialog_values();
		$this->assertArrayHasKey('phplistener', $result);
		$this->assertSame([
			'default' => false,
			'dependencies' => [],
			'files' => ['test.php'],
			'group' => 'BACK_END'
		], $result['phplistener']);
	}

	public function test_create_extension_runs_without_exception()
	{
		// Create a partial mock for packager, stubbing get_template_engine
		$packager = $this->getMockBuilder(packager::class)
			->setConstructorArgs([$this->container, $this->collection, $this->root_path])
			->onlyMethods(['get_template_engine'])
			->getMock();

		// Mock the template engine
		$templateMock = $this->createMock(twig::class);
		$templateMock->method('set_custom_style')->willReturnSelf();
		$templateMock->method('assign_vars')->willReturnSelf();
		$templateMock->method('set_filenames')->willReturnSelf();
		$templateMock->method('assign_display')->willReturn('dummy');
		$packager->method('get_template_engine')->willReturn($templateMock);

		$this->setUpTheCollectionToReturnAFakeSkeletonClass();

		// You can mock dependencies further as needed
		$data = [
			'extension' => [
				'vendor_name' => 'testvendor',
				'extension_name' => 'testext',
				'extension_display_name' => 'Test Ext',
				'extension_version' => '1.0.0',
			],
			'requirements' => [
				'phpbb_version_min' => '3.2.0',
			],
			'components' => [],
			'authors' => [],
		];
		// No assertions, just ensure no exceptions
		$packager->create_extension($data);
		$this->assertTrue(true);
	}

	public function test_create_zip_creates_zip_file()
	{
		$data = [
			'extension' => [
				'vendor_name' => 'testvendor',
				'extension_name' => 'testext',
				'extension_version' => '1.0.0',
			],
		];
		// You might want to mock filesystem interactions or use vfsStream
		$zipPath = $this->packager->create_zip($data);
		$this->assertIsString($zipPath);
		// Clean up or further assertions here
	}

	/**
	 * @dataProvider provide_language_version_data
	 */
	public function test_get_language_version_data_returns_expected($phpbb_version, $expected)
	{
		$data = [
			'requirements' => [
				'phpbb_version_min' => $phpbb_version
			]
		];

		$result = $this->invokeMethod($this->packager, 'get_language_version_data', [$data]);

		$this->assertSame($expected['class'], $result['class']);
		$this->assertSame($expected['object'], $result['object']);
		$this->assertSame($expected['function'], $result['function']);
		$this->assertSame($expected['indent']['class'], $result['indent']['class']);
		$this->assertSame($expected['indent']['object'], $result['indent']['object']);
	}

	public static function provide_language_version_data()
	{
		return [
			'3.1 version' => [
				'3.1.0',
				[
					'class' => '\phpbb\user',
					'object' => 'user',
					'function' => 'add_lang_ext',
					'indent' => [
						'class' => "\t\t\t",
						'object' => "\t"
					]
				]
			],
			'3.2 version' => [
				'3.2.0',
				[
					'class' => '\phpbb\language\language',
					'object' => 'language',
					'function' => 'add_lang',
					'indent' => [
						'class' => '',
						'object' => ''
					]
				]
			],
			'null version' => [
				null,
				[
					'class' => '\phpbb\language\language',
					'object' => 'language',
					'function' => 'add_lang',
					'indent' => [
						'class' => '',
						'object' => ''
					]
				]
			]
		];
	}

	public function test_get_template_engine_returns_twig_instance()
	{
		// Mock all required dependencies for the container
		$filesystem = $this->createMock(filesystem::class);
		$path_helper = $this->createMock(path_helper::class);
		$ext_manager = $this->createMock(manager::class);
		$user = $this->createMock(user::class);
		$assets_bag = $this->createMock(assets_bag::class);

		$callCount = 0;
		$expectedArgs = [
			['path_helper'],
			['filesystem'],
			['assets.bag'],
			['ext.manager'],
			['user']
		];
		$returnValues = [
			$path_helper,
			$filesystem,
			$assets_bag,
			$ext_manager,
			$user
		];

		$this->container->expects($this->exactly(5))
			->method('get')
			->willReturnCallback(function($arg) use (&$callCount, $expectedArgs, $returnValues) {
				$this->assertSame($expectedArgs[$callCount][0], $arg);
				$return = $returnValues[$callCount];
				$callCount++;
				return $return;
			});

		$this->container->expects($this->once())
			->method('getParameter')
			->with('core.cache_dir')
			->willReturn(false);

		// Call the protected method using your invokeMethod helper
		$twig = $this->invokeMethod($this->packager, 'get_template_engine');
		$this->assertInstanceOf(twig::class, $twig);
	}

	// Helper for protected/private method invocation
	protected function invokeMethod($object, $methodName, array $parameters = [])
	{
		$reflection = new ReflectionClass(get_class($object));
		return $reflection->getMethod($methodName)->invokeArgs($object, $parameters);
	}
}
