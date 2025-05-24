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

use phpbb\db\migrator;
use phpbb\finder;
use phpbb\language\language;
use phpbb\skeleton\ext;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ext_test extends \phpbb_test_case
{
	protected $ext;

	protected function setUp(): void
	{
		$this->ext = new ext(
			$this->createMock(ContainerInterface::class),
			$this->createMock(finder::class),
			$this->createMock(migrator::class),
			'phpbb/skeleton',
			''
		);
	}

	public function test_ext_is_instance_of_base()
	{
		$this->assertInstanceOf(ext::class, $this->ext);
	}

	public function test_is_enableable_true()
	{
		$ext = $this->getMockBuilder(ext::class)
			->disableOriginalConstructor()
			->setMethods(['ziparchive_exists', 'phpbb_requirement', 'php_requirement'])
			->getMock();

		$ext->method('ziparchive_exists')->willReturn(null);
		$ext->method('phpbb_requirement')->willReturn(null);
		$ext->method('php_requirement')->willReturn(null);

		$this->setExtErrors($ext, []);
		$this->assertTrue($ext->is_enableable());
	}

	public function test_is_enableable_with_errors()
	{
		$ext = $this->getMockBuilder(ext::class)
			->disableOriginalConstructor()
			->setMethods(['ziparchive_exists', 'phpbb_requirement', 'php_requirement', 'enable_failed'])
			->getMock();

		$ext->method('ziparchive_exists')->willReturnCallback(function () use ($ext) {
			$this->appendExtError($ext, 'NO_ZIPARCHIVE_ERROR');
		});
		$ext->method('phpbb_requirement')->willReturn(null);
		$ext->method('php_requirement')->willReturn(null);
		$ext->method('enable_failed')->willReturn(['NO_ZIPARCHIVE_ERROR']);

		$this->setExtErrors($ext, ['NO_ZIPARCHIVE_ERROR']);
		$this->assertEquals(['NO_ZIPARCHIVE_ERROR'], $ext->is_enableable());
	}

	public function test_enable_failed_returns_expected()
	{
		$ext = $this->getMockBuilder(ext::class)
			->disableOriginalConstructor()
			->getMock();

		$this->setExtErrors($ext, ['SOME_ERROR']);

		$languageMock = $this->createMock(language::class);
		$languageMock->method('add_lang')->willReturn(null);
		$languageMock->method('lang')->willReturnCallback(function ($msg) {
			return "LANG: $msg";
		});

		$containerMock = $this->createMock(ContainerInterface::class);
		$containerMock->method('get')->with('language')->willReturn($languageMock);

		$this->setProperty($ext, 'container', $containerMock);

		$method = (new \ReflectionClass($ext))->getMethod('enable_failed');
		$method->setAccessible(true);

		$this->assertEquals(['LANG: SOME_ERROR'], $method->invoke($ext));
	}

	public function test_phpbb_requirement_min_error()
	{
		$this->setExtErrors($this->ext, []);
		$this->invokeProtectedMethod($this->ext, 'phpbb_requirement', ['3.2.2']);
		$this->assertContains('PHPBB_VERSION_MIN_ERROR', $this->getExtErrors($this->ext));
	}

	public function test_phpbb_requirement_max_error()
	{
		$this->setExtErrors($this->ext, []);
		$this->invokeProtectedMethod($this->ext, 'phpbb_requirement', ['4.0.0-dev']);
		$this->assertContains('PHPBB_VERSION_MAX_ERROR', $this->getExtErrors($this->ext));
	}

	public function test_php_requirement_error()
	{
		$this->setExtErrors($this->ext, []);
		$this->invokeProtectedMethod($this->ext, 'php_requirement', [50500]);
		$this->assertContains('PHP_VERSION_ERROR', $this->getExtErrors($this->ext));
	}

	public function test_ziparchive_exists_error()
	{
		$this->setExtErrors($this->ext, []);
		$this->invokeProtectedMethod($this->ext, 'ziparchive_exists', ['NotZipArchive']);
		$this->assertContains('NO_ZIPARCHIVE_ERROR', $this->getExtErrors($this->ext));
	}

	// --- Helpers ---

	protected function invokeProtectedMethod($object, string $methodName, array $args = [])
	{
		$method = (new \ReflectionClass($object))->getMethod($methodName);
		$method->setAccessible(true);
		return $method->invokeArgs($object, $args);
	}

	protected function getExtErrors($ext): array
	{
		$prop = (new \ReflectionClass($ext))->getProperty('errors');
		$prop->setAccessible(true);
		return $prop->getValue($ext);
	}

	protected function setExtErrors($ext, array $errors): void
	{
		$prop = (new \ReflectionClass($ext))->getProperty('errors');
		$prop->setAccessible(true);
		$prop->setValue($ext, $errors);
	}

	protected function appendExtError($ext, string $error): void
	{
		$errors = $this->getExtErrors($ext);
		$errors[] = $error;
		$this->setExtErrors($ext, $errors);
	}

	protected function setProperty($object, string $property, $value): void
	{
		$prop = (new \ReflectionClass($object))->getProperty($property);
		$prop->setAccessible(true);
		$prop->setValue($object, $value);
	}
}
