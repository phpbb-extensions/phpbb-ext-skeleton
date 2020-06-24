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
use phpbb\language\language;
use phpbb\skeleton\helper\packager;
use phpbb\skeleton\helper\validator;
use phpbb\user;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class create extends command
{
	/** @var array user input data array */
	protected $data = [];

	/** @var QuestionHelper $helper */
	protected $helper;

	/** @var InputInterface */
	protected $input;

	/** @var OutputInterface */
	protected $output;

	/** @var language */
	protected $language;

	/** @var packager */
	protected $packager;

	/** @var validator */
	protected $validator;

	/**
	 * Constructor
	 *
	 * @param user      $user
	 * @param language  $language
	 * @param packager  $packager
	 * @param validator $validator
	 */
	public function __construct(user $user, language $language, packager $packager, validator $validator)
	{
		$this->language = $language;
		$this->packager = $packager;
		$this->validator = $validator;

		$this->language->add_lang('common', 'phpbb/skeleton');
		parent::__construct($user);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configure()
	{
		$this
			->setName('extension:create')
			->setDescription($this->language->lang('CLI_DESCRIPTION_SKELETON_CREATE'))
		;
	}

	/**
	 * Executes the command extension:create.
	 *
	 * Creates an extension skeleton
	 *
	 * @param InputInterface  $input  An InputInterface instance
	 * @param OutputInterface $output An OutputInterface instance
	 *
	 * @see \phpbb\config\config::delete()
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->packager->create_extension($this->data);

		$output->writeln($this->language->lang('EXTENSION_CLI_SKELETON_SUCCESS'));
	}

	/**
	 * Interacts with the user.
	 *
	 * @param InputInterface  $input  An InputInterface instance
	 * @param OutputInterface $output An OutputInterface instance
	 */
	protected function interact(InputInterface $input, OutputInterface $output)
	{
		$this->input  = $input;
		$this->output = $output;

		$this->helper = $this->getHelper('question');

		$output->writeln($this->language->lang('SKELETON_CLI_COMPOSER_QUESTIONS'));
		$this->get_composer_data();

		$output->writeln($this->language->lang('SKELETON_CLI_COMPONENT_QUESTIONS'));
		$this->get_component_data();
	}

	/**
	 * Get composer data from the user
	 */
	protected function get_composer_data()
	{
		$dialog_questions = $this->packager->get_composer_dialog_values();
		foreach ($dialog_questions['extension'] as $value => $default)
		{
			$this->data['extension'][$value] = $this->get_user_input($value, $default);
		}

		$question = new Question($this->language->lang('SKELETON_QUESTION_NUM_AUTHORS') . $this->language->lang('COLON'), 1);
		$question->setValidator([$this->validator, 'validate_num_authors']);
		$num_authors = $this->helper->ask($this->input, $this->output, $question);

		$this->data['authors'] = [];
		for ($i = 0; $i < $num_authors; $i++)
		{
			foreach ($dialog_questions['author'] as $value => $default)
			{
				$this->data['authors'][$i][$value] = $this->get_user_input($value, $default);
			}
		}

		foreach ($dialog_questions['requirements'] as $value => $default)
		{
			$this->data['requirements'][$value] = $this->get_user_input($value, $default);
		}
	}

	/**
	 * Get component data from the user
	 */
	protected function get_component_data()
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

			$this->data['components'][$component] = $this->get_user_input('component_' . $component, $details['default']);
		}
	}

	/**
	 * Helper for getting user input
	 *
	 * @param string $value
	 * @param mixed  $default
	 * @return mixed|string
	 */
	protected function get_user_input($value, $default)
	{
		$dialog = $this->language->lang('SKELETON_QUESTION_' . strtoupper($value)) . $this->language->lang('COLON');

		if (method_exists($this->validator, 'validate_' . $value))
		{
			$question = new Question($dialog, $default);
			$question->setValidator([$this->validator, 'validate_' . $value]);
			$return_value = $this->helper->ask($this->input, $this->output, $question);
		}
		else if (is_bool($default))
		{
			$question = new ConfirmationQuestion($dialog, $default);
			$return_value = $this->helper->ask($this->input, $this->output, $question);
		}
		else
		{
			$question = new Question($dialog, $default);
			$return_value = $this->helper->ask($this->input, $this->output, $question);
		}

		return $return_value;
	}
}
