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

namespace phpbb\skeleton\console;

use phpbb\config\config;
use phpbb\console\command\command;
use phpbb\path_helper;
use phpbb\skeleton\helper\validator;
use phpbb\template\context;
use phpbb\template\twig\twig;
use phpbb\user;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class create extends command
{
	protected $data = array();

	/** @var \phpbb\skeleton\helper\validator */
	protected $validator;

	protected function get_composer_dialog_values()
	{
		return array(
			'author' => array(
				'author_name' => null,
				'author_email' => null,
				'author_homepage' => null,
				'author_role' => null,
			),
			'extension' => array(
				'vendor_name' => null,
				'extension_display_name' => null,
				'extension_name' => null,
				'extension_description' => null,
				'extension_version' => '1.0.0-dev',
				'extension_homepage' => null,
				'extension_time' => date('Y-m-d'),
			),
			'requirements' => array(
				'php_version' => '5.3.3',
				'phpbb_version_min' => '3.1.4',
				'phpbb_version_max' => '3.2.0',
			)
		);
	}

	protected function get_component_dialog_values()
	{
		return array(
			'phplistener' => array(
				'default' => true,
				'dependencies' => array(),
				'files' => array(
					'config/services.yml',
					'event/main_listener.php',
				),
			),
			'htmllistener' => array(
				'default' => true,
				'dependencies' => array(),
				'files' => array(
					'styles/prosilver/template/event/overall_header_navigation_prepend.html',
				),
			),
			'acp' => array(
				'default' => true,
				'dependencies' => array(),
				'files' => array(
					'acp/main_info.php',
					'acp/main_module.php',
					'adm/style/demo_body.html',
					'language/en/info_acp_demo.php',
				),
			),
			'migration' => array(
				'default' => true,
				'dependencies' => array(),
				'files' => array(
					'migrations/release_1_0_0.php',
					'migrations/release_1_0_1.php',
				),
			),
			'service' => array(
				'default' => true,
				'dependencies' => array(),
				'files' => array(
					'service.php',
					'config/services.yml',
					'config/parameters.yml',
				),
			),
			'controller' => array(
				'default' => true,
				'dependencies' => array('service', 'route'),
				'files' => array(
					'config/services.yml',
					'event/main_listener.php',
					'language/en/common.php',
					'controller/main.php',
					'styles/prosilver/template/demo_body.html',
				),
			),
			'tests' => array(
				'default' => true,
				'dependencies' => array(),
				'files' => array(
					'tests/controller/main_test.php',
					'tests/dbal/fixtures/config.xml',
					'tests/dbal/simple_test.php',
					'tests/functional/demo_test.php',
					'tests/mock/controller_helper.php',
					'tests/mock/template.php',
					'tests/mock/user.php',
					'phpunit.xml.dist',
				),
			),
			'travis' => array(
				'default' => true,
				'dependencies' => array('tests'),
				'files' => array(
					'travis/prepare-phpbb.sh',
					'.travis.yml',
				),
			),
//			'build' => array(
//				'default' => true,
//				'dependencies' => array(),
//				'files' => array(),
//			),
		);
	}

	/**
	 * Constructor
	 *
	 * @param user $user User instance (mostly for translation)
	 * @param path_helper $path_helper The filesystem object
	 * @param string $root_path
	 */
	public function __construct(user $user, path_helper $path_helper, $root_path)
	{
		parent::__construct($user);
		$this->validator = new validator($this->user);
		$this->path_helper = $path_helper;
		$this->root_path = $root_path;
	}

	/**
	* {@inheritdoc}
	*/
	protected function configure()
	{
		$this
			->setName('skeleton:create')
			->setDescription($this->user->lang('CLI_DESCRIPTION_SKELETON_CREATE'))
		;
	}

	/**
	* Executes the command config:delete.
	*
	* Removes a configuration option
	*
	* @param InputInterface  $input  An InputInterface instance
	* @param OutputInterface $output An OutputInterface instance
	*
	* @return null
	* @see \phpbb\config\config::delete()
	*/
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		/** @var DialogHelper $dialog */
		$dialog = $this->getHelper('dialog');

		$this->get_composer_data($dialog, $output);
		$this->get_component_data($dialog, $output);

		$this->create_extension();
	}

	/**
	 * @param DialogHelper $dialog
	 * @param OutputInterface $output
	 */
	protected function get_composer_data(DialogHelper $dialog, OutputInterface $output)
	{
		$dialog_questions = $this->get_composer_dialog_values();
		foreach ($dialog_questions['extension'] as $value => $default)
		{
			$this->data['extension'][$value] = $this->get_user_input($dialog, $output, $value, $default);
		}

		$num_authors = $dialog->askAndValidate(
			$output,
			$this->user->lang('SKELETON_QUESTION_NUM_AUTHORS'),
			array($this->validator, 'validate_num_authors'),
			false,
			1
		);

		$this->data['authors'] = array();
		for ($i = 0; $i < $num_authors; $i++)
		{
			foreach ($dialog_questions['author'] as $value => $default)
			{
				$this->data['authors'][$i][$value] = $this->get_user_input($dialog, $output, $value, $default);
			}
		}

		foreach ($dialog_questions['requirements'] as $value => $default)
		{
			$this->data['requirements'][$value] = $this->get_user_input($dialog, $output, $value, $default);
		}
	}

	/**
	 * @param DialogHelper $dialog
	 * @param OutputInterface $output
	 */
	protected function get_component_data(DialogHelper $dialog, OutputInterface $output)
	{
		$components = $this->get_component_dialog_values();
		foreach ($components as $component => $details)
		{
			foreach ($details['dependencies'] as $depends)
			{
				if (empty($this->data['components'][$depends]))
				{
					$this->data['components'][$component] = false;
					continue 2;
				}
			}

			$this->data['components'][$component] = $this->get_user_input($dialog, $output, 'component_' . $component, $details['default']);
		}
	}

	protected function create_extension()
	{
		$ext_path = $this->root_path . 'store/tmp-ext/' . "{$this->data['extension']['vendor_name']}/{$this->data['extension']['extension_name']}/";
		$filesystem = new Filesystem();
		$filesystem->remove($this->root_path . 'store/tmp-ext/');
		$filesystem->mkdir($ext_path);

		$template_engine = new twig($this->path_helper, new config(array(
			'load_tplcompile' => true,
			'tpl_allow_php' => false,
			'assets_version' => null,
		)), $this->user, new context());
		$template_engine->set_custom_style('skeletonextension', $this->root_path . 'ext/phpbb/skeleton/skeleton');

		$template_engine->assign_vars(array(
			'COMPONENT' => $this->data['components'],
			'EXTENSION' => $this->data['extension'],
			'REQUIREMENTS' => $this->data['requirements'],
			'AUTHORS' => $this->data['authors'],
		));

		$component_data = $this->get_component_dialog_values();
		$skeleton_files = array(
			'license.txt',
			'README.md',
		);

		foreach ($this->data['components'] as $component => $selected)
		{
			if ($selected && !empty($component_data[$component]['files']))
			{
				$skeleton_files = array_merge($skeleton_files, $component_data[$component]['files']);
			}
		}

		foreach ($skeleton_files as $file)
		{
			$template_engine->set_filenames(array('body' => $file . '.tpl'));
			$body = $template_engine->assign_display('body');
			if (substr($file, -5) === '.html')
			{
				$body = str_replace('&lt;', '<', $body);
				$body = str_replace('&#123;', '{', $body);
			}
			$filesystem->dumpFile($ext_path . $file, trim($body) . "\n");
		}

		$filesystem->dumpFile($ext_path . 'composer.json', $this->get_composer_json_from_data());
	}

	/**
	 * @param DialogHelper $dialog
	 * @param OutputInterface $output
	 * @param string $value
	 * @param mixed $default
	 * @return mixed|string
	 */
	protected function get_user_input(DialogHelper $dialog, OutputInterface $output, $value, $default)
	{
		if (method_exists($this->validator, 'validate_' . $value))
		{
			$return_value = $dialog->askAndValidate(
				$output,
				$this->user->lang('SKELETON_QUESTION_' . strtoupper($value)),
				array($this->validator, 'validate_' . $value),
				false,
				$default
			);
		}
		else if (is_bool($default))
		{
			$return_value = $dialog->askConfirmation(
				$output,
				$this->user->lang('SKELETON_QUESTION_' . strtoupper($value)),
				$default
			);
		}
		else
		{
			$return_value = $dialog->ask(
				$output,
				$this->user->lang('SKELETON_QUESTION_' . strtoupper($value)),
				$default
			);
		}

		return $return_value;
	}

	/**
	 * @return string
	 */
	protected function get_composer_json_from_data()
	{
		$composer = array(
			'name' => "{$this->data['extension']['vendor_name']}/{$this->data['extension']['extension_name']}",
			'type' => 'phpbb-extension',
			'description' => "{$this->data['extension']['extension_description']}",
			'homepage' => "{$this->data['extension']['extension_homepage']}",
			'version' => "{$this->data['extension']['extension_version']}",
			'time' => "{$this->data['extension']['extension_time']}",
			'license' => 'GPL-2.0',
			'authors' => array(),
			'require' => array(
				'php' => ">={$this->data['requirements']['php_version']}",
			),
			'require-dev' => array(
				'phpbb/epv' => 'dev-master',
			),
			'extra' => array(
				'display-name' => "{$this->data['extension']['extension_display_name']}",
				'soft-require' => array(
					'phpbb/phpbb' => ">={$this->data['requirements']['phpbb_version_min']},<{$this->data['requirements']['phpbb_version_max']}@dev",
				),
			),
		);

		if ($this->data['components']['build'])
		{
			$composer['require-dev']['phing/phing'] = '2.4.*';
		}

		foreach ($this->data['authors'] as $i => $author_data)
		{
			if ($this->data['authors'][$i]['author_name'] !== '')
			{
				$composer['authors'][] = array(
					'name' => "{$this->data['authors'][$i]['author_name']}",
					'email' => "{$this->data['authors'][$i]['author_email']}",
					'homepage' => "{$this->data['authors'][$i]['author_homepage']}",
					'role' => "{$this->data['authors'][$i]['author_role']}",
				);
			}
		}

		return json_encode($composer, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES);
	}
}
