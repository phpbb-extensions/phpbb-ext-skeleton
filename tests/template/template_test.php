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

namespace phpbb\skeleton\tests\template;

use phpbb\config\config;
use phpbb\event\dispatcher;
use phpbb\filesystem\filesystem;
use phpbb\language\language;
use phpbb\language\language_file_loader;
use phpbb\path_helper;
use phpbb\skeleton\template\twig\extension\skeleton_version_compare;
use phpbb\symfony_request;
use phpbb\template\assets_bag;
use phpbb\template\context;
use phpbb\template\twig\environment;
use phpbb\template\twig\extension;
use phpbb\template\twig\lexer;
use phpbb\template\twig\loader;
use phpbb\template\twig\twig;
use phpbb\user;
use phpbb_mock_request;
use phpbb_template_template_test_case;
use phpbb\datetime;
use phpbb\request\request;

require_once __DIR__ . '/../../../../../../tests/template/template_test_case.php';

class template_test extends phpbb_template_template_test_case
{
	protected $test_path = __DIR__;

	protected function setup_engine(array $new_config = array()): void
	{
		global $phpbb_root_path, $phpEx;

		$defaults = $this->config_defaults();
		$config = new config(array_merge($defaults, $new_config));
		$lang_loader = new language_file_loader($phpbb_root_path, $phpEx);
		$this->lang = $lang = new language($lang_loader);
		$user = new user($lang, datetime::class);
		$this->user = $user;

		$filesystem = new filesystem();

		$path_helper = new path_helper(
			new symfony_request(
				new phpbb_mock_request()
			),
			$this->createMock(request::class),
			$phpbb_root_path,
			$phpEx
		);

		$this->template_path = $this->test_path . '/templates';

		$cache_path = $phpbb_root_path . 'cache/twig';
		$context = new context();
		$loader = new loader();
		$assets_bag = new assets_bag();
		$twig = new environment(
			$assets_bag,
			$config,
			$filesystem,
			$path_helper,
			$cache_path,
			null,
			$loader,
			new dispatcher(),
			[
				'cache'			=> false,
				'debug'			=> false,
				'auto_reload'	=> true,
				'autoescape'	=> false,
			]
		);
		$this->template = new twig(
			$path_helper,
			$config,
			$context,
			$twig,
			$cache_path,
			$this->user,
			[
				new extension($context, $twig, $this->lang),
				new skeleton_version_compare(),
			]
		);
		$twig->setLexer(new lexer($twig));
		$this->template->set_custom_style('tests', $this->template_path);
	}

	public function data_template_version_check(): array
	{
		return [
			[
				'skeleton_version_compare.html',
				[
					'ver1' => '3.1.0',
					'ver2' => '3.3.0',
					'operator' => '<',
				],
				'true',
			],
			[
				'skeleton_version_compare.html',
				[
					'ver1' => '3.1.0-rc5',
					'ver2' => '4.0.0@dev',
					'operator' => '<',
				],
				'true',
			],
			[
				'skeleton_version_compare.html',
				[
					'ver1' => '3.1.0',
					'ver2' => '3.3.0',
					'operator' => '>',
				],
				'false',
			],
			[
				'skeleton_version_compare.html',
				[
					'ver1' => '3.1.0',
					'ver2' => '3.3.0',
					'operator' => null,
				],
				'false',
			],
			[
				'skeleton_version_compare.html',
				[
					'ver1' => null,
					'ver2' => '3.3.0',
					'operator' => '<',
				],
				'false',
			],
		];
	}

	/**
	 * @dataProvider data_template_version_check
	 */
	public function test_skeleton_version_compare($file, $vars, $expected)
	{
		$this->run_template($file, $vars, [], [], $expected);
	}
}
