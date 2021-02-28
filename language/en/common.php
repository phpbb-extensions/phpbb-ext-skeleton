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

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [
	'CLI_DESCRIPTION_SKELETON_CREATE'	=> 'Create a basic skeleton extension',
	'PHPBB_SKELETON_EXT'				=> 'Skeleton Extension',
	'PHPBB_CREATE_SKELETON_EXT'			=> 'Create skeleton extension',
	'PHPBB_SKELETON_EXT_DOCS'			=> 'Read the Documentation',

	'EXTENSION_CLI_SKELETON_SUCCESS'	=> "<info>Extension created successfully.\nCopy the extension from `store/tmp-ext/` into the `ext/` folder.</info>",
	'SKELETON_CLI_COMPOSER_QUESTIONS'	=> '<comment>Enter composer.json details (hit enter to leave an option empty)</comment>',
	'SKELETON_CLI_COMPONENT_QUESTIONS'	=> '<comment>Install optional components. Default: No; [y/n]</comment>',

	'SKELETON_RESERVED_NAME_WARNING_UI'	=> 'Warning: The name entered is not allowed.',

	'SKELETON_ADD_AUTHOR'						=> 'Add author',
	'SKELETON_QUESTION_VENDOR_NAME'				=> 'Please enter the vendor name',
	'SKELETON_QUESTION_VENDOR_NAME_UI'			=> 'Vendor name',
	'SKELETON_QUESTION_VENDOR_NAME_EXPLAIN'		=> 'Starting with a letter, lowercase letters and numbers only',
	'SKELETON_QUESTION_EXTENSION_NAME'			=> 'Please enter the package (folder) name of the extension',
	'SKELETON_QUESTION_EXTENSION_NAME_UI'		=> 'Package name',
	'SKELETON_QUESTION_EXTENSION_NAME_EXPLAIN'	=> 'Starting with a letter, lowercase letters and numbers only',
	'SKELETON_QUESTION_EXTENSION_DISPLAY_NAME'	=> 'Please enter the display name (title) of the extension',
	'SKELETON_QUESTION_EXTENSION_DISPLAY_NAME_UI'		=> 'Display name',
	'SKELETON_QUESTION_EXTENSION_DISPLAY_NAME_EXPLAIN'	=> 'This is the name (title) of the extension',
	'SKELETON_QUESTION_EXTENSION_DESCRIPTION'	=> 'Please enter the Description of the extension',
	'SKELETON_QUESTION_EXTENSION_DESCRIPTION_UI'=> 'Description',
	'SKELETON_QUESTION_EXTENSION_VERSION'		=> 'Please enter the version of the extension',
	'SKELETON_QUESTION_EXTENSION_VERSION_UI'	=> 'Version',
	'SKELETON_QUESTION_EXTENSION_VERSION_EXPLAIN'	=> 'e.g. 1.0.0-dev',
	'SKELETON_QUESTION_EXTENSION_HOMEPAGE'		=> 'Please enter the homepage of the extension',
	'SKELETON_QUESTION_EXTENSION_HOMEPAGE_UI'	=> 'Homepage of the extension',
	'SKELETON_QUESTION_EXTENSION_TIME'			=> 'Please enter the date of the extension',
	'SKELETON_QUESTION_EXTENSION_TIME_UI'		=> 'Date of the extension',
	'SKELETON_QUESTION_EXTENSION_TIME_EXPLAIN'	=> 'YYYY-MM-DD, default: today',

	'SKELETON_QUESTION_NUM_AUTHORS'				=> 'How many authors does the extension have',
	'SKELETON_QUESTION_NUM_AUTHORS_EXPLAIN'		=> 'default: 1',

	'SKELETON_QUESTION_AUTHOR_NAME'			=> 'Please enter the author name',
	'SKELETON_QUESTION_AUTHOR_NAME_UI'		=> 'Author name',
	'SKELETON_QUESTION_AUTHOR_NAME_EXPLAIN'	=> 'Must not be empty',
	'SKELETON_QUESTION_AUTHOR_EMAIL'		=> 'Please enter the author email',
	'SKELETON_QUESTION_AUTHOR_EMAIL_UI'		=> 'Author email',
	'SKELETON_QUESTION_AUTHOR_HOMEPAGE'		=> 'Please enter the author homepage',
	'SKELETON_QUESTION_AUTHOR_HOMEPAGE_UI'	=> 'Author homepage',
	'SKELETON_QUESTION_AUTHOR_ROLE'			=> 'Please enter the author role',
	'SKELETON_QUESTION_AUTHOR_ROLE_UI'		=> 'Author role',

	'SKELETON_QUESTION_PHP_VERSION'					=> 'Please enter the PHP requirement of the extension',
	'SKELETON_QUESTION_PHP_VERSION_UI'				=> 'PHP requirement of the extension',
	'SKELETON_QUESTION_PHP_VERSION_EXPLAIN'			=> 'default: &gt;=5.4',
	'SKELETON_QUESTION_PHPBB_VERSION_MIN'			=> 'Please enter the minimum phpBB requirement of the extension',
	'SKELETON_QUESTION_PHPBB_VERSION_MIN_UI'		=> 'Minimum phpBB requirement of the extension',
	'SKELETON_QUESTION_PHPBB_VERSION_MIN_EXPLAIN'	=> 'default: &gt;=3.2.0',
	'SKELETON_QUESTION_PHPBB_VERSION_MAX'			=> 'Please enter the maximum phpBB requirement of the extension',
	'SKELETON_QUESTION_PHPBB_VERSION_MAX_UI'		=> 'Maximum phpBB requirement of the extension',
	'SKELETON_QUESTION_PHPBB_VERSION_MAX_EXPLAIN'	=> 'default: &lt;4.0.0@dev',

	'SKELETON_QUESTION_COMPONENT_PHPLISTENER'		=> 'Create sample PHP event listeners?',
	'SKELETON_QUESTION_COMPONENT_PHPLISTENER_UI'	=> 'PHP event listeners',
	'SKELETON_QUESTION_COMPONENT_PHPLISTENER_EXPLAIN'	=> 'PHP event listeners work with core events to inject code into phpBB.',
	'SKELETON_QUESTION_COMPONENT_HTMLLISTENER'		=> 'Create sample styles listeners?',
	'SKELETON_QUESTION_COMPONENT_HTMLLISTENER_UI'	=> 'Style listeners',
	'SKELETON_QUESTION_COMPONENT_HTMLLISTENER_EXPLAIN'	=> 'Style listeners use template events to inject HTML, JS and CSS into phpBB’s style files.',
	'SKELETON_QUESTION_COMPONENT_ACP'				=> 'Create a sample ACP administration module?',
	'SKELETON_QUESTION_COMPONENT_ACP_UI'			=> 'Administration control panel (ACP)',
	'SKELETON_QUESTION_COMPONENT_ACP_EXPLAIN'		=> 'Add a functional ACP module for an extension to the ACP’s Extensions tab.',
	'SKELETON_QUESTION_COMPONENT_MCP'				=> 'Create a sample MCP moderation module?',
	'SKELETON_QUESTION_COMPONENT_MCP_UI'			=> 'Moderator control panel (MCP)',
	'SKELETON_QUESTION_COMPONENT_MCP_EXPLAIN'		=> 'Add a functional MCP module tab for an extension to the MCP.',
	'SKELETON_QUESTION_COMPONENT_UCP'				=> 'Create a sample UCP user module?',
	'SKELETON_QUESTION_COMPONENT_UCP_UI'			=> 'User control panel (UCP)',
	'SKELETON_QUESTION_COMPONENT_UCP_EXPLAIN'		=> 'Add a functional UCP module tab for an extension to the UCP.',
	'SKELETON_QUESTION_COMPONENT_MIGRATION'			=> 'Create sample database migrations?',
	'SKELETON_QUESTION_COMPONENT_MIGRATION_UI'		=> 'Database migration',
	'SKELETON_QUESTION_COMPONENT_MIGRATION_EXPLAIN'	=> 'Migration files are used to make database changes.',
	'SKELETON_QUESTION_COMPONENT_SERVICE'			=> 'Create a sample service?',
	'SKELETON_QUESTION_COMPONENT_SERVICE_UI'		=> 'Service',
	'SKELETON_QUESTION_COMPONENT_SERVICE_EXPLAIN'	=> 'This is a PHP class that does something behind the scenes. It is a class that can be accessed by controllers, event listeners, or control panel modules.',
	'SKELETON_QUESTION_COMPONENT_CONTROLLER'		=> 'Create a sample controller?',
	'SKELETON_QUESTION_COMPONENT_CONTROLLER_UI'		=> 'Controller (front page)',
	'SKELETON_QUESTION_COMPONENT_CONTROLLER_EXPLAIN'=> 'Controllers are typically used for the front-facing files/classes. They run the code that produces the page the user views.',
	'SKELETON_QUESTION_COMPONENT_EXT'				=> 'Create a sample ext.php?',
	'SKELETON_QUESTION_COMPONENT_EXT_UI'			=> 'Extension base (ext.php)',
	'SKELETON_QUESTION_COMPONENT_EXT_EXPLAIN'		=> 'Create the optional ext.php file which can be used to run code before or during extension installation and removal.',
	'SKELETON_QUESTION_COMPONENT_CONSOLE'			=> 'Create a sample console command?',
	'SKELETON_QUESTION_COMPONENT_CONSOLE_UI'		=> 'Console command',
	'SKELETON_QUESTION_COMPONENT_CONSOLE_EXPLAIN'	=> 'A functional CLI interface to perform console/terminal commands.',
	'SKELETON_QUESTION_COMPONENT_CRON'				=> 'Create a sample cron task?',
	'SKELETON_QUESTION_COMPONENT_CRON_UI'			=> 'Cron task',
	'SKELETON_QUESTION_COMPONENT_CRON_EXPLAIN'		=> 'Add a cron task to be able to schedule and run cron-based actions from an extension.',
	'SKELETON_QUESTION_COMPONENT_NOTIFICATION'		=> 'Create a sample notification?',
	'SKELETON_QUESTION_COMPONENT_NOTIFICATION_UI'	=> 'Notifications',
	'SKELETON_QUESTION_COMPONENT_NOTIFICATION_EXPLAIN'	=> 'Notifications allow an extension to notify users of specific activities. When choosing notifications, do not use phpBB 3.1 as the minimum requirement. Notifications changed in phpBB 3.2.0 and are not backwards compatible with 3.1.x.',
	'SKELETON_QUESTION_COMPONENT_PERMISSIONS'		=> 'Create sample permissions?',
	'SKELETON_QUESTION_COMPONENT_PERMISSIONS_UI'	=> 'Permissions',
	'SKELETON_QUESTION_COMPONENT_PERMISSIONS_EXPLAIN'	=> 'Permissions can be used to grant users, groups and roles access to specific extension features.',
	'SKELETON_QUESTION_COMPONENT_TESTS'				=> 'Create sample PHPUnit tests?',
	'SKELETON_QUESTION_COMPONENT_TESTS_UI'			=> 'Tests (PHPUnit)',
	'SKELETON_QUESTION_COMPONENT_TESTS_EXPLAIN'		=> 'Unit tests can test an extension to verify that specific portions of its source code work properly. This helps ensure basic code integrity and prevents regressions as an extension is being developed and debugged.',
	'SKELETON_QUESTION_COMPONENT_GITHUBACTIONS'		=> 'Create a workflow for test execution using Github Actions?',
	'SKELETON_QUESTION_COMPONENT_GITHUBACTIONS_UI'	=> 'Github Actions CI configuration',
	'SKELETON_QUESTION_COMPONENT_GITHUBACTIONS_EXPLAIN'	=> 'Github Actions allow you to run PHPUnit tests on your GitHub repository. This will generate the workflow YML needed to begin testing your phpBB extension with each commit and pull request. Note that this workflow will be inside a hidden folder (.github/workflows).',
	'SKELETON_QUESTION_COMPONENT_TRAVIS'			=> 'Create a sample for test execution on Travis CI?',
	'SKELETON_QUESTION_COMPONENT_TRAVIS_UI'			=> 'Travis CI configuration',
	'SKELETON_QUESTION_COMPONENT_TRAVIS_EXPLAIN'	=> 'Travis CI is a platform for running your PHPUnit tests on a GitHub repository. This will generate the basic config and script files needed to begin testing your phpBB extension with each commit and pull request.',
	'SKELETON_QUESTION_COMPONENT_BUILD'				=> 'Create a sample build script for phing?',
	'SKELETON_QUESTION_COMPONENT_BUILD_UI'			=> 'Build script (phing)',
	'SKELETON_QUESTION_COMPONENT_BUILD_EXPLAIN'		=> 'A phing build script is generated for your extension which can be used to generate build packages to help simplify the release or deployment processes.',

	'SKELETON_COMPONENT_GROUP_FRONT_END'			=> 'Front End (HTML)',
	'SKELETON_COMPONENT_GROUP_BACK_END'				=> 'Back End (PHP)',
	'SKELETON_COMPONENT_GROUP_DATABASE'				=> 'Database',
	'SKELETON_COMPONENT_GROUP_CONTROL_PANELS'		=> 'Control Panels',
	'SKELETON_COMPONENT_GROUP_OTHER'				=> 'Extras',
	'SKELETON_COMPONENT_GROUP_TEST_DEPLOY'			=> 'Testing & Deployment',

	'SKELETON_TITLE_EXTENSION_INFO'		=> 'Extension packaging',
	'SKELETON_TITLE_AUTHOR_INFO'		=> 'Authors',
	'SKELETON_TITLE_REQUIREMENT_INFO'	=> 'Requirements',
	'SKELETON_TITLE_COMPONENT_INFO'		=> 'Components',

	'SKELETON_INVALID_AUTHOR_EMAIL'		=> 'An author email is invalid',
	'SKELETON_INVALID_AUTHOR_URL'		=> 'An author homepage URL is invalid',
	'SKELETON_INVALID_DISPLAY_NAME'		=> 'The display name you provided is invalid',
	'SKELETON_INVALID_EXTENSION_TIME'	=> 'The extension date you provided is invalid',
	'SKELETON_INVALID_EXTENSION_URL'	=> 'The extension homepage URL is invalid',
	'SKELETON_INVALID_EXTENSION_VERSION'=> 'The extension version you provided is invalid',
	'SKELETON_INVALID_NUM_AUTHORS'		=> 'The number of authors you provided is invalid',
	'SKELETON_INVALID_PACKAGE_NAME'		=> 'The package name you provided is invalid',
	'SKELETON_INVALID_VENDOR_NAME'		=> 'The vendor name you provided is invalid',
	'SKELETON_INVALID_PHP_VERSION'		=> 'The PHP version requirement is invalid.',
	'SKELETON_INVALID_PHPBB_MIN_VERSION'=> 'The minimum phpBB version requirement is invalid.',
	'SKELETON_INVALID_PHPBB_MAX_VERSION'=> 'The maximum phpBB version requirement is invalid.',

	'NO_ZIPARCHIVE_ERROR'	=> 'The ZipArchive class is required, but was not found in your PHP configuration.',
	'PHP_VERSION_ERROR'		=> 'PHP 5.4 or newer is required to use this extension.',
	'PHPBB_VERSION_ERROR'	=> 'phpBB 3.2.0 or newer is required to use this extension.',
]);
