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

use phpbb\exception\runtime_exception;

class validator_test extends \phpbb_test_case
{
	/** @var \phpbb\skeleton\helper\validator */
	protected $validator;

	public function setUp(): void
	{
		global $phpbb_root_path, $phpEx;

		$this->validator = new \phpbb\skeleton\helper\validator(
			new \phpbb\language\language(
				new \phpbb\language\language_file_loader($phpbb_root_path, $phpEx)
			)
		);
	}

	public function valid_data(): array
	{
		return [
			['num_authors', '1'],
			['num_authors', '20'],

			['extension_name', 'foo'],
			['extension_name', 'foo01'],

			['extension_display_name', 'Foo bar'],
			['extension_display_name', 'Foo >bar\'s< & world'],

			['extension_time', '0000-00-00'],

			['extension_version', '1.0'],
			['extension_version', '1.0.0'],
			['extension_version', '1.0.0-dev'],
			['extension_version', '1.0.0-RC1'],
			['extension_version', '1.0.0-a1'],
			['extension_version', '1.0.0-b1'],
			['extension_version', '1.0.0-pl1'],
			['extension_version', '1.0.0.0'],
			['extension_version', '1.0.0.0-dev'],

			['vendor_name', 'foo'],
			['vendor_name', 'foo01'],

			['extension_homepage', 'http://www.web.com'],
			['extension_homepage', 'https://web.com'],
			['extension_homepage', ''],
			['extension_homepage', null],

			['author_homepage', 'http://www.web.com'],
			['author_homepage', 'https://web.com'],
			['author_homepage', ''],
			['author_homepage', null],

			['author_email', 'user@web.com'],
			['author_email', ''],
			['author_email', null],

			['phpbb_version_min', '1.0.0'],
			['phpbb_version_min', '>1.0.0'],
			['phpbb_version_min', '>=1.0.0'],
			['phpbb_version_min', '>=1.0.0@dev'],
			['phpbb_version_min', '>=1.0.0-pl1'],
			['phpbb_version_min', '<1.0.0'],
			['phpbb_version_min', '<=1.0.0'],
			['phpbb_version_min', '<=1.0.0@dev'],
			['phpbb_version_min', '<=1.0.0-pl1'],
			['phpbb_version_min', '&gt;1.0.0'],
			['phpbb_version_min', '&lt;=1.0.0'],

			['phpbb_version_max', '1.0.0'],
			['phpbb_version_max', '>1.0.0'],
			['phpbb_version_max', '>=1.0.0'],
			['phpbb_version_max', '>=1.0.0@dev'],
			['phpbb_version_max', '>=1.0.0-pl1'],
			['phpbb_version_max', '<1.0.0'],
			['phpbb_version_max', '<=1.0.0'],
			['phpbb_version_max', '<=1.0.0@dev'],
			['phpbb_version_max', '<=1.0.0-pl1'],
			['phpbb_version_max', '&gt;1.0.0'],
			['phpbb_version_max', '&lt;=1.0.0'],

			['php_version', '1.0.0'],
			['php_version', '>1.0.0'],
			['php_version', '>=1.0.0'],
			['php_version', '>=1.0.0@dev'],
			['php_version', '>=1.0.0-pl1'],
			['php_version', '<1.0.0'],
			['php_version', '<=1.0.0'],
			['php_version', '<=1.0.0@dev'],
			['php_version', '<=1.0.0-pl1'],
			['php_version', '&gt;1.0.0'],
			['php_version', '&lt;=1.0.0'],
		];
	}

	/**
	 * Test validator with valid data succeeds
	 *
	 * @dataProvider valid_data
	 * @param string $validator Name of the validator method
	 * @param string $value     Value to validate
	 */
	public function test_validator_valid($validator, $value)
	{
		self::assertEquals($value, call_user_func([$this->validator, "validate_{$validator}"], $value));
	}

	public function invalid_data(): array
	{
		return [
			['num_authors', '21', 'SKELETON_INVALID_NUM_AUTHORS'],
			['num_authors', '-1', 'SKELETON_INVALID_NUM_AUTHORS'],
			['num_authors', '0', 'SKELETON_INVALID_NUM_AUTHORS'],
			['num_authors', '', 'SKELETON_INVALID_NUM_AUTHORS'],
			['num_authors', null, 'SKELETON_INVALID_NUM_AUTHORS'],
			['num_authors', 'foo', 'SKELETON_INVALID_NUM_AUTHORS'],
			['num_authors', '$foo', 'SKELETON_INVALID_NUM_AUTHORS'],

			['extension_name', 'Foo', 'SKELETON_INVALID_PACKAGE_NAME'],
			['extension_name', 'fooBar', 'SKELETON_INVALID_PACKAGE_NAME'],
			['extension_name', 'foo-bar', 'SKELETON_INVALID_PACKAGE_NAME'],
			['extension_name', 'foo_01', 'SKELETON_INVALID_PACKAGE_NAME'],
			['extension_name', '01foo', 'SKELETON_INVALID_PACKAGE_NAME'],
			['extension_name', 'foo$bar', 'SKELETON_INVALID_PACKAGE_NAME'],
			['extension_name', '', 'SKELETON_INVALID_PACKAGE_NAME'],
			['extension_name', null, 'SKELETON_INVALID_PACKAGE_NAME'],

			['extension_display_name', '', 'SKELETON_INVALID_DISPLAY_NAME'],
			['extension_display_name', null, 'SKELETON_INVALID_DISPLAY_NAME'],
			['extension_display_name', 'Foo bar&quot;s world', 'SKELETON_INVALID_DISPLAY_NAME'],

			['extension_time', '00-00-0000', 'SKELETON_INVALID_EXTENSION_TIME'],
			['extension_time', 'FOO', 'SKELETON_INVALID_EXTENSION_TIME'],
			['extension_time', '', 'SKELETON_INVALID_EXTENSION_TIME'],
			['extension_time', null, 'SKELETON_INVALID_EXTENSION_TIME'],

			['extension_version', '1', 'SKELETON_INVALID_EXTENSION_VERSION'],
			['extension_version', '1.0.0-RC', 'SKELETON_INVALID_EXTENSION_VERSION'],
			['extension_version', '1.0.0-a', 'SKELETON_INVALID_EXTENSION_VERSION'],
			['extension_version', '1.0.0-b', 'SKELETON_INVALID_EXTENSION_VERSION'],
			['extension_version', '1.0.0-pl', 'SKELETON_INVALID_EXTENSION_VERSION'],
			['extension_version', '1.0.0-foo', 'SKELETON_INVALID_EXTENSION_VERSION'],
			['extension_version', '1.0.0-foo1', 'SKELETON_INVALID_EXTENSION_VERSION'],
			['extension_version', '', 'SKELETON_INVALID_EXTENSION_VERSION'],
			['extension_version', null, 'SKELETON_INVALID_EXTENSION_VERSION'],
			['extension_version', 'foo', 'SKELETON_INVALID_EXTENSION_VERSION'],
			['extension_version', '1.0.0.0.0', 'SKELETON_INVALID_EXTENSION_VERSION'],

			['vendor_name', 'core', 'SKELETON_INVALID_VENDOR_NAME'],
			['vendor_name', 'Foo', 'SKELETON_INVALID_VENDOR_NAME'],
			['vendor_name', 'fooBar', 'SKELETON_INVALID_VENDOR_NAME'],
			['vendor_name', 'foo-bar', 'SKELETON_INVALID_VENDOR_NAME'],
			['vendor_name', 'foo_01', 'SKELETON_INVALID_VENDOR_NAME'],
			['vendor_name', '01foo', 'SKELETON_INVALID_VENDOR_NAME'],
			['vendor_name', 'foo$bar', 'SKELETON_INVALID_VENDOR_NAME'],
			['vendor_name', '', 'SKELETON_INVALID_VENDOR_NAME'],
			['vendor_name', null, 'SKELETON_INVALID_VENDOR_NAME'],

			['extension_homepage', 'www.web.com', 'SKELETON_INVALID_EXTENSION_URL'],
			['extension_homepage', 'foo', 'SKELETON_INVALID_EXTENSION_URL'],

			['author_homepage', 'www.web.com', 'SKELETON_INVALID_AUTHOR_URL'],
			['author_homepage', 'foo', 'SKELETON_INVALID_AUTHOR_URL'],

			['author_email', 'mail.com', 'SKELETON_INVALID_AUTHOR_EMAIL'],
			['author_email', 'user@mail', 'SKELETON_INVALID_AUTHOR_EMAIL'],
			['author_email', 'foo', 'SKELETON_INVALID_AUTHOR_EMAIL'],

			['phpbb_version_min', '', 'SKELETON_INVALID_PHPBB_MIN_VERSION'],
			['phpbb_version_min', null, 'SKELETON_INVALID_PHPBB_MIN_VERSION'],
			['phpbb_version_min', 'foo', 'SKELETON_INVALID_PHPBB_MIN_VERSION'],
			['phpbb_version_min', '~1.0.0', 'SKELETON_INVALID_PHPBB_MIN_VERSION'],
			['phpbb_version_min', '^1.0.0', 'SKELETON_INVALID_PHPBB_MIN_VERSION'],
			['phpbb_version_min', '1.0.0 | 2.0.0', 'SKELETON_INVALID_PHPBB_MIN_VERSION'],
			['phpbb_version_min', '&amp;gt;=5.4', 'SKELETON_INVALID_PHPBB_MIN_VERSION'],

			['phpbb_version_max', '', 'SKELETON_INVALID_PHPBB_MAX_VERSION'],
			['phpbb_version_max', null, 'SKELETON_INVALID_PHPBB_MAX_VERSION'],
			['phpbb_version_max', 'foo', 'SKELETON_INVALID_PHPBB_MAX_VERSION'],
			['phpbb_version_max', '~1.0.0', 'SKELETON_INVALID_PHPBB_MAX_VERSION'],
			['phpbb_version_max', '^1.0.0', 'SKELETON_INVALID_PHPBB_MAX_VERSION'],
			['phpbb_version_max', '1.0.0 | 2.0.0', 'SKELETON_INVALID_PHPBB_MAX_VERSION'],
			['phpbb_version_max', '&amp;gt;=5.4', 'SKELETON_INVALID_PHPBB_MAX_VERSION'],

			['php_version', '', 'SKELETON_INVALID_PHP_VERSION'],
			['php_version', null, 'SKELETON_INVALID_PHP_VERSION'],
			['php_version', 'foo', 'SKELETON_INVALID_PHP_VERSION'],
			['php_version', '~1.0.0', 'SKELETON_INVALID_PHP_VERSION'],
			['php_version', '^1.0.0', 'SKELETON_INVALID_PHP_VERSION'],
			['php_version', '1.0.0 | 2.0.0', 'SKELETON_INVALID_PHP_VERSION'],
			['php_version', '&amp;gt;=5.4', 'SKELETON_INVALID_PHP_VERSION'],
		];
	}

	/**
	 * Test validator with invalid data fails
	 *
	 * @dataProvider invalid_data
	 * @param string $validator Name of the validator method
	 * @param string $value     Value to validate
	 * @param string $expected  Expected error message
	 */
	public function test_validator_invalid($validator, $value, $expected)
	{
		$this->expectException(runtime_exception::class);
		$this->expectExceptionMessage($expected);

		call_user_func([$this->validator, "validate_{$validator}"], $value);
	}
}
