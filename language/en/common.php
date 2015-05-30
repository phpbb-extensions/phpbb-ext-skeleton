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
	$lang = array();
}

$lang = array_merge($lang, array(
	'CLI_DESCRIPTION_SKELETON_CREATE'	=> 'A console command to create a basic extension',
	'PHPBB_SKELETON_EXT'				=> 'Create skeleton extension',
	'EXTENSION_SKELETON_SUCCESS'		=> 'Extension created successfully',

	'SKELETON_QUESTION_VENDOR_NAME'				=> 'Please enter the vendor name',
	'SKELETON_QUESTION_VENDOR_NAME_UI'			=> 'Vendor name',
	'SKELETON_QUESTION_VENDOR_NAME_EXPLAIN'		=> 'Starting with a letter, letters and numbers only',
	'SKELETON_QUESTION_EXTENSION_DISPLAY_NAME'	=> 'Please enter the display (readable) name of your extension',
	'SKELETON_QUESTION_EXTENSION_DISPLAY_NAME_UI'	=> 'Display (readable) extension name',
	'SKELETON_QUESTION_EXTENSION_NAME'			=> 'Please enter the folder name of your extension',
	'SKELETON_QUESTION_EXTENSION_NAME_UI'			=> 'Folder name',
	'SKELETON_QUESTION_EXTENSION_NAME_EXPLAIN'	=> 'Starting with a letter, letters and numbers only',
	'SKELETON_QUESTION_EXTENSION_DESCRIPTION'	=> 'Please enter the Description of your extension',
	'SKELETON_QUESTION_EXTENSION_DESCRIPTION_UI'=> 'Description',
	'SKELETON_QUESTION_EXTENSION_VERSION'		=> 'Please enter the version of your extension',
	'SKELETON_QUESTION_EXTENSION_VERSION_UI'	=> 'Version',
	'SKELETON_QUESTION_EXTENSION_VERSION_EXPLAIN'	=> 'e.g. 1.0.0-dev',
	'SKELETON_QUESTION_EXTENSION_HOMEPAGE'		=> 'Please enter the homepage of your extension',
	'SKELETON_QUESTION_EXTENSION_HOMEPAGE_UI'	=> 'Homepage of your extension',
	'SKELETON_QUESTION_EXTENSION_TIME'			=> 'Please enter the date of your extension',
	'SKELETON_QUESTION_EXTENSION_TIME_UI'		=> 'Date of your extension',
	'SKELETON_QUESTION_EXTENSION_TIME_EXPLAIN'	=> 'YYYY-MM-DD, default: today',

	'SKELETON_QUESTION_NUM_AUTHORS'				=> 'How many authors does your extension have',
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

	'SKELETON_QUESTION_PHP_VERSION'					=> 'Please enter the php requirement of your extension',
	'SKELETON_QUESTION_PHP_VERSION_UI'				=> 'php requirement of your extension',
	'SKELETON_QUESTION_PHP_VERSION_EXPLAIN'			=> 'default: >=5.3.3',
	'SKELETON_QUESTION_PHPBB_VERSION_MIN'			=> 'Please enter the minimum phpBB requirement of your extension',
	'SKELETON_QUESTION_PHPBB_VERSION_MIN_UI'		=> 'Minimum phpBB requirement of your extension',
	'SKELETON_QUESTION_PHPBB_VERSION_MIN_EXPLAIN'	=> 'default: >=3.1.4',
	'SKELETON_QUESTION_PHPBB_VERSION_MAX'			=> 'Please enter the maximum phpBB requirement of your extension',
	'SKELETON_QUESTION_PHPBB_VERSION_MAX_UI'		=> 'Maximum phpBB requirement of your extension',
	'SKELETON_QUESTION_PHPBB_VERSION_MAX_EXPLAIN'	=> 'default: <3.2.0@dev',

	'SKELETON_QUESTION_COMPONENT_PHPLISTENER'		=> 'Should we add a sample for php listeners',
	'SKELETON_QUESTION_COMPONENT_PHPLISTENER_UI'	=> 'php listeners',
	'SKELETON_QUESTION_COMPONENT_PHPLISTENER_EXPLAIN'	=> 'default: y; [y/n]',
	'SKELETON_QUESTION_COMPONENT_HTMLLISTENER'		=> 'Should we add a sample for styles listeners',
	'SKELETON_QUESTION_COMPONENT_HTMLLISTENER_UI'	=> 'Styles listeners',
	'SKELETON_QUESTION_COMPONENT_HTMLLISTENER_EXPLAIN'	=> 'default: y; [y/n]',
	'SKELETON_QUESTION_COMPONENT_ACP'				=> 'Should we add a sample for a administration module',
	'SKELETON_QUESTION_COMPONENT_ACP_UI'			=> 'Administration module (ACP)',
	'SKELETON_QUESTION_COMPONENT_ACP_EXPLAIN'		=> 'default: y; [y/n]',
	'SKELETON_QUESTION_COMPONENT_MIGRATION'			=> 'Should we add a sample for a database migration',
	'SKELETON_QUESTION_COMPONENT_MIGRATION_UI'		=> 'Database migration',
	'SKELETON_QUESTION_COMPONENT_MIGRATION_EXPLAIN'	=> 'default: y; [y/n]',
	'SKELETON_QUESTION_COMPONENT_SERVICE'			=> 'Should we add a sample for a service',
	'SKELETON_QUESTION_COMPONENT_SERVICE_UI'		=> 'Service',
	'SKELETON_QUESTION_COMPONENT_SERVICE_EXPLAIN'	=> 'default: y; [y/n]',
	'SKELETON_QUESTION_COMPONENT_CONTROLLER'		=> 'Should we add a sample for a controller',
	'SKELETON_QUESTION_COMPONENT_CONTROLLER_UI'		=> 'Controller (frontpage)',
	'SKELETON_QUESTION_COMPONENT_CONTROLLER_EXPLAIN'=> 'default: y; [y/n]',
	'SKELETON_QUESTION_COMPONENT_TESTS'				=> 'Should we add a sample for a phpunit tests',
	'SKELETON_QUESTION_COMPONENT_TESTS_UI'			=> 'phpunit tests',
	'SKELETON_QUESTION_COMPONENT_TESTS_EXPLAIN'		=> 'default: y; [y/n]',
	'SKELETON_QUESTION_COMPONENT_TRAVIS'			=> 'Should we add a sample for test execution on Travis CI',
	'SKELETON_QUESTION_COMPONENT_TRAVIS_UI'			=> 'Test execution on Travis CI',
	'SKELETON_QUESTION_COMPONENT_TRAVIS_EXPLAIN'	=> 'default: y; [y/n]',
	'SKELETON_QUESTION_COMPONENT_BUILD'				=> 'Should we add a sample script to building packages for the customisation database',
	'SKELETON_QUESTION_COMPONENT_BUILD_UI'			=> 'Script to building packages for the customisation database',
	'SKELETON_QUESTION_COMPONENT_BUILD_EXPLAIN'		=> 'default: n; [y/n]',

	'SKELETON_INVALID_EXTENSION_NAME'	=> 'The extension name you provided is invalid',
	'SKELETON_INVALID_EXTENSION_TIME'	=> 'The extension date you provided is invalid',
	'SKELETON_INVALID_EXTENSION_VERSION'	=> 'The extension version you provided is invalid',
	'SKELETON_INVALID_NUM_AUTHORS'	=> 'The number of authors you provided is invalid',
	'SKELETON_INVALID_VENDOR_NAME'	=> 'The vendor name you provided is invalid',
));
