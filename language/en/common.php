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

	'SKELETON_QUESTION_VENDOR_NAME'		=> 'Please enter the vendor name (starting with a letter, letters and numbers only): ',
	'SKELETON_QUESTION_EXTENSION_DISPLAY_NAME'		=> 'Please enter the display (readable) name of your extension: ',
	'SKELETON_QUESTION_EXTENSION_NAME'		=> 'Please enter the folder name of your extension (starting with a letter, letters and numbers only): ',
	'SKELETON_QUESTION_EXTENSION_DESCRIPTION'		=> 'Please enter the Description of your extension: ',
	'SKELETON_QUESTION_EXTENSION_VERSION'		=> 'Please enter the version of your extension (e.g. 1.0.0-dev): ',
	'SKELETON_QUESTION_EXTENSION_HOMEPAGE'		=> 'Please enter the homepage of your extension: ',
	'SKELETON_QUESTION_EXTENSION_TIME'		=> 'Please enter the date of your extension (YYYY-MM-DD, default: today): ',
	'SKELETON_QUESTION_NUM_AUTHORS'		=> 'How many authors does your extension have (default: 1): ',

	'SKELETON_QUESTION_AUTHOR_NAME'		=> 'Please enter the author name (must not be empty): ',
	'SKELETON_QUESTION_AUTHOR_EMAIL'		=> 'Please enter the author email: ',
	'SKELETON_QUESTION_AUTHOR_HOMEPAGE'		=> 'Please enter the author homepage: ',
	'SKELETON_QUESTION_AUTHOR_ROLE'		=> 'Please enter the author role: ',

	'SKELETON_QUESTION_PHP_VERSION'		=> 'Please enter the php requirement of your extension (default: >=5.3.3): ',
	'SKELETON_QUESTION_PHPBB_VERSION_MIN'		=> 'Please enter the php requirement of your extension (default: >=3.1.4): ',
	'SKELETON_QUESTION_PHPBB_VERSION_MAX'		=> 'Please enter the php requirement of your extension (default: <3.2.0@dev): ',

	'SKELETON_QUESTION_COMPONENT_PHPLISTENER'		=> 'Should we add a sample for php listeners (default: y) [y/n]: ',
	'SKELETON_QUESTION_COMPONENT_HTMLLISTENER'		=> 'Should we add a sample for styles listeners (default: y) [y/n]: ',
	'SKELETON_QUESTION_COMPONENT_ACP'				=> 'Should we add a sample for a administration module (default: y) [y/n]: ',
	'SKELETON_QUESTION_COMPONENT_MIGRATION'			=> 'Should we add a sample for a database migration (default: y) [y/n]: ',
	'SKELETON_QUESTION_COMPONENT_SERVICE'			=> 'Should we add a sample for a service (default: y) [y/n]: ',
	'SKELETON_QUESTION_COMPONENT_CONTROLLER'		=> 'Should we add a sample for a controller (default: y) [y/n]: ',
	'SKELETON_QUESTION_COMPONENT_TESTS'				=> 'Should we add a sample for a phpunit tests (default: y) [y/n]: ',
	'SKELETON_QUESTION_COMPONENT_TRAVIS'			=> 'Should we add a sample for test execution on Travis CI (default: y) [y/n]: ',
	'SKELETON_QUESTION_COMPONENT_BUILD'				=> 'Should we add a sample script for building packages for the customisation database (default: n) [y/n]: ',

	'SKELETON_INVALID_EXTENSION_NAME'	=> 'The extension name you provided is invalid',
	'SKELETON_INVALID_EXTENSION_TIME'	=> 'The extension date you provided is invalid',
	'SKELETON_INVALID_EXTENSION_VERSION'	=> 'The extension version you provided is invalid',
	'SKELETON_INVALID_NUM_AUTHORS'	=> 'The number of authors you provided is invalid',
	'SKELETON_INVALID_VENDOR_NAME'	=> 'The vendor name you provided is invalid',
));
