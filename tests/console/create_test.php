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

namespace phpbb\skeleton\tests\console;

use phpbb\exception\runtime_exception;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Tester\CommandTester;
use phpbb\language\language;
use phpbb\skeleton\console\create;
use phpbb\skeleton\helper\packager;
use phpbb\skeleton\helper\validator;
use phpbb\user;
use PHPUnit\Framework\MockObject\MockObject;

class create_test extends \phpbb_test_case
{
	/** @var language|MockObject */
	protected $language;

	/** @var user|MockObject */
	protected $user;

	/** @var packager|MockObject */
	protected $packager;

	/** @var validator|MockObject */
	protected $validator;

	/** @var string|null */
	protected $command_name;

	public function setUp(): void
	{
		$this->language = $this->getMockBuilder('\phpbb\language\language')
			->disableOriginalConstructor()
			->getMock();

		$this->language->method('lang')
			->will($this->returnArgument(0));

		$this->user = $this->user = $this->getMockBuilder('\phpbb\user')
			->disableOriginalConstructor()
			->getMock();

		$this->validator = new validator($this->language);

		$this->packager = $this->getMockBuilder('\phpbb\skeleton\helper\packager')
			->disableOriginalConstructor()
			->getMock();

		$this->packager->expects(self::atMost(1))
			->method('get_composer_dialog_values')
			->willReturn([
				'author' => ['author_name' => null],
				'extension' => ['vendor_name' => null],
				'requirements' => ['php_version' => '>=5.4'],
			]);

		$this->packager->expects(self::atMost(1))
			->method('get_component_dialog_values')
			->willReturn([
				'phplistener' => [
					'default'      => false,
					'dependencies' => [],
					'files'        => ['config/services.yml', 'event/main_listener.php', 'language/en/common.php'],
					'group'        => 'BACK_END',
				],
				'htmllistener' => [
					'default'      => false,
					'dependencies' => [],
					'files'        => ['styles/prosilver/template/event/overall_header_navigation_prepend.html'],
					'group'        => 'FRONT_END',
				],
				'tests' => [
					'default'      => false,
					'dependencies' => [],
					'files'        => ['tests/controller/main_test.php', 'tests/dbal/fixtures/config.xml', 'tests/dbal/simple_test.php', 'tests/functional/view_test.php', 'phpunit.xml.dist'],
					'group'        => 'TEST_DEPLOY',
				],
				'githubactions' => [
					'default'      => false,
					'dependencies' => ['tests'],
					'files'        => ['.github/workflows/tests.yml'],
					'group'        => 'TEST_DEPLOY',
				],
			]);
	}

	public function get_command_tester($question_answers = []): CommandTester
	{
		$application = new Application();
		$application->add(new create(
			$this->user,
			$this->language,
			$this->packager,
			$this->validator
		));

		$command = $application->find('extension:create');
		$this->command_name = $command->getName();

		if (!empty($question_answers))
		{
			$ask = function(InputInterface $input, OutputInterface $output, Question $question) use ($question_answers)
			{
				$text = $question->getQuestion();

				foreach ($question_answers as $expected_question => $answer)
				{
					if (strpos($text, $expected_question) !== false)
					{
						$response = $answer;

						if ($validator = $question->getValidator())
						{
							$response = $validator($response);
						}
					}
				}

				if (!isset($response))
				{
					throw new \RuntimeException('Was asked for input on an unhandled question: ' . $text);
				}

				$output->writeln(print_r($response, true));
				return $response;
			};
			$helper = $this->getMockBuilder('\Symfony\Component\Console\Helper\QuestionHelper')
				->setMethods(['ask'])
				->disableOriginalConstructor()
				->getMock();
			$helper->method('ask')
				->willReturnCallback($ask);
			$command->getHelperSet()->set($helper, 'question');
		}

		return new CommandTester($command);
	}

	public function get_questions()
	{
		return [
			'SKELETON_QUESTION_VENDOR_NAME'				=> 'foo',
//			'SKELETON_QUESTION_EXTENSION_NAME'			=> 'bar',
//			'SKELETON_QUESTION_EXTENSION_DISPLAY_NAME'	=> 'Foo Bar',
//			'SKELETON_QUESTION_EXTENSION_DESCRIPTION'	=> 'Extensions description text.',
//			'SKELETON_QUESTION_EXTENSION_VERSION'		=> '1.0.0',
//			'SKELETON_QUESTION_EXTENSION_TIME'			=> '',
//			'SKELETON_QUESTION_EXTENSION_HOMEPAGE'		=> '',
			'SKELETON_QUESTION_NUM_AUTHORS'				=> '1',
			'SKELETON_QUESTION_AUTHOR_NAME'				=> 'Test Dev',
//			'SKELETON_QUESTION_AUTHOR_EMAIL'			=> '',
//			'SKELETON_QUESTION_AUTHOR_HOMEPAGE'			=> '',
//			'SKELETON_QUESTION_AUTHOR_ROLE'				=> 'Developer',
			'SKELETON_QUESTION_PHP_VERSION'				=> '7.4.0',
//			'SKELETON_QUESTION_PHPBB_VERSION_MIN'		=> '>=3.3.0',
//			'SKELETON_QUESTION_PHPBB_VERSION_MAX'		=> '<=4.0.0',
			'SKELETON_QUESTION_COMPONENT_PHPLISTENER'	=> 'y',
			'SKELETON_QUESTION_COMPONENT_HTMLLISTENER'	=> 'n',
//			'SKELETON_QUESTION_COMPONENT_ACP'			=> 'y',
//			'SKELETON_QUESTION_COMPONENT_MCP'			=> 'y',
//			'SKELETON_QUESTION_COMPONENT_UCP'			=> 'y',
//			'SKELETON_QUESTION_COMPONENT_MIGRATION'		=> 'y',
//			'SKELETON_QUESTION_COMPONENT_SERVICE'		=> 'y',
//			'SKELETON_QUESTION_COMPONENT_CONTROLLER'	=> 'y',
//			'SKELETON_QUESTION_COMPONENT_EXT'			=> 'y',
//			'SKELETON_QUESTION_COMPONENT_CONSOLE'		=> 'y',
//			'SKELETON_QUESTION_COMPONENT_CRON'			=> 'y',
//			'SKELETON_QUESTION_COMPONENT_NOTIFICATION'	=> 'y',
//			'SKELETON_QUESTION_COMPONENT_PERMISSIONS'	=> 'y',
			'SKELETON_QUESTION_COMPONENT_TESTS'			=> 'y',
			'SKELETON_QUESTION_COMPONENT_GITHUBACTIONS'	=> 2,
//			'SKELETON_QUESTION_COMPONENT_BUILD'			=> 'y',
		];
	}

	public function test_create()
	{
		$questions = $this->get_questions();

		$command_tester = $this->get_command_tester($questions);

		$command_tester->setInputs($questions);

		$command_tester->execute([
			'command' => $this->command_name,
		]);

		$this->assertStringContainsString('EXTENSION_CLI_SKELETON_SUCCESS', $command_tester->getDisplay());
	}

	public function invalid_data()
	{
		return [
			[['SKELETON_QUESTION_VENDOR_NAME' => 'foo bar']],
			[['SKELETON_QUESTION_NUM_AUTHORS' => '']],
			[['SKELETON_QUESTION_PHP_VERSION' => 'NAN']],
		];
	}

	/**
	 * @dataProvider invalid_data
	 * @param $response
	 */
	public function test_invalid_create($response)
	{
		$questions = array_merge($this->get_questions(), $response);

		$this->expectException(runtime_exception::class);

		$command_tester = $this->get_command_tester($questions);

		$command_tester->setInputs($questions);

		$command_tester->execute([
			'command' => $this->command_name,
		]);
	}
}
