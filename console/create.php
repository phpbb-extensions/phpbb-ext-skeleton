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

use phpbb\console\command\command;
use phpbb\skeleton\helper\packager;
use phpbb\skeleton\helper\validator;
use phpbb\user;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class create extends command
{
	protected $data = array();

	/** @var validator */
	protected $validator;

	/** @var packager */
	protected $packager;

	/**
	 * Constructor
	 *
	 * @param user $user User instance (mostly for translation)
	 * @param packager $packager
	 * @param validator $validator
	 */
	public function __construct(user $user, packager $packager, validator $validator)
	{
		parent::__construct($user);

		$this->packager = $packager;
		$this->validator = $validator;
	}

	/**
	* {@inheritdoc}
	*/
	protected function configure()
	{
		$this->user->add_lang_ext('phpbb/skeleton', 'common');
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

		$this->packager->create_extension($this->data);
	}

	/**
	 * @param DialogHelper $dialog
	 * @param OutputInterface $output
	 */
	protected function get_composer_data(DialogHelper $dialog, OutputInterface $output)
	{
		$dialog_questions = $this->packager->get_composer_dialog_values();
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
		$components = $this->packager->get_component_dialog_values();
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
}
