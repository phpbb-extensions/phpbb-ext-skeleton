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

namespace phpbb\skeleton\tests\functional;

use phpbb_functional_test_case;

/**
 * @group functional
 */
class view_test extends phpbb_functional_test_case
{
	/**
	 * @inheritdoc
	 */
	protected static function setup_extensions(): array
	{
		return ['phpbb/skeleton'];
	}

	public function test_view_skeleton()
	{
		$this->add_lang_ext('phpbb/skeleton', 'common');
		$crawler = self::request('GET', 'app.php/skeleton');
		self::assertStringContainsString($this->lang('PHPBB_CREATE_SKELETON_EXT'), $crawler->filter('h2')->text());
	}
}
