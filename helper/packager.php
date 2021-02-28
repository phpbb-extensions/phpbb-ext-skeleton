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

namespace phpbb\skeleton\helper;

use phpbb\config\config;
use phpbb\di\service_collection;
use phpbb\skeleton\template\twig\extension\skeleton_version_compare;
use phpbb\template\context;
use phpbb\template\twig\environment;
use phpbb\template\twig\loader;
use phpbb\template\twig\twig;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class packager
{
	/** @var ContainerInterface */
	protected $phpbb_container;

	/** @var service_collection */
	protected $collection;

	/** @var string */
	protected $root_path;

	/**
	 * Constructor
	 *
	 * @param ContainerInterface $phpbb_container Container
	 * @param service_collection $collection      Service collection
	 * @param string             $root_path       phpBB root path
	 */
	public function __construct(ContainerInterface $phpbb_container, service_collection $collection, $root_path)
	{
		$this->phpbb_container = $phpbb_container;
		$this->collection = $collection;
		$this->root_path = $root_path;
	}

	/**
	 * Get composer dialog values
	 *
	 * @return array
	 */
	public function get_composer_dialog_values()
	{
		return [
			'author'       => [
				'author_name'     => null,
				'author_email'    => null,
				'author_homepage' => null,
				'author_role'     => null,
			],
			'extension'    => [
				'vendor_name'            => null,
				'extension_name'         => null,
				'extension_display_name' => null,
				'extension_description'  => null,
				'extension_version'      => '1.0.0-dev',
				'extension_time'         => date('Y-m-d'),
				'extension_homepage'     => null,
			],
			'requirements' => [
				'php_version'       => '>=5.4',
				'phpbb_version_min' => '>=3.2.0',
				'phpbb_version_max' => '<4.0.0@dev',
			],
		];
	}

	/**
	 * Get components dialog values
	 *
	 * @return array
	 */
	public function get_component_dialog_values()
	{
		$components = [];
		foreach ($this->collection as $service)
		{
			/** @var \phpbb\skeleton\skeleton $service */
			$components[$service->get_name()] = [
				'default'      => $service->get_default(),
				'dependencies' => $service->get_dependencies(),
				'files'        => $service->get_files(),
				'group'        => $service->get_group(),
			];
		}

		return $components;
	}

	/**
	 * Create the extension
	 *
	 * @param array $data Extension data
	 */
	public function create_extension($data)
	{
		$ext_path = $this->root_path . 'store/tmp-ext/' . "{$data['extension']['vendor_name']}/{$data['extension']['extension_name']}/";
		$filesystem = new Filesystem();
		$filesystem->remove($this->root_path . 'store/tmp-ext');
		$filesystem->mkdir($ext_path);

		$phpbb31 = (bool) preg_match('/^[\D]*3\.1.*$/', $data['requirements']['phpbb_version_min']);

		$template_engine = $this->get_template_engine();
		$template_engine->set_custom_style('skeletonextension', $this->root_path . 'ext/phpbb/skeleton/skeleton');
		$template_engine->assign_vars([
			'COMPONENT'    => $data['components'],
			'EXTENSION'    => $data['extension'],
			'REQUIREMENTS' => $data['requirements'],
			'AUTHORS'      => $data['authors'],
			'LANGUAGE'     => $this->get_language_version_data($phpbb31),
			'S_PHPBB_31'   => $phpbb31,
		]);

		$component_data = $this->get_component_dialog_values();
		$skeleton_files[] = [
			'composer.json',
			'license.txt',
			'README.md',
		];

		foreach ($data['components'] as $component => $selected)
		{
			if ($selected && !empty($component_data[$component]['files']))
			{
				$skeleton_files[] = $component_data[$component]['files'];
			}
		}
		$skeleton_files = call_user_func_array('array_merge', $skeleton_files);

		foreach ($skeleton_files as $file)
		{
			$body = $template_engine
				->set_filenames(['body' => $file . '.twig'])
				->assign_display('body');
			$filesystem->dumpFile($ext_path . str_replace('demo', strtolower($data['extension']['extension_name']), $file), trim($body) . "\n");
		}
	}

	/**
	 * Create the zip archive
	 *
	 * @param array $data Extension data
	 *
	 * @return string
	 */
	public function create_zip($data)
	{
		$tmp_path = $this->root_path . 'store/tmp-ext/';
		$ext_path = "{$data['extension']['vendor_name']}/{$data['extension']['extension_name']}/";
		$zip_path = $tmp_path . "{$data['extension']['vendor_name']}_{$data['extension']['extension_name']}-{$data['extension']['extension_version']}.zip";

		$zip_archive = new \ZipArchive();
		$zip_archive->open($zip_path, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

		$finder = new Finder();
		$finder->ignoreDotFiles(false)
			->ignoreVCS(false)
			->files()
			->in($tmp_path . $ext_path);

		foreach ($finder as $file)
		{
			$zip_archive->addFile(
				$file->getRealPath(),
				$ext_path . $file->getRelativePath() . DIRECTORY_SEPARATOR . $file->getFilename()
			);
		}

		$zip_archive->close();

		return $zip_path;
	}

	/**
	 * Get the template engine to use for parsing skeleton templates.
	 *
	 * @return twig Template object
	 */
	protected function get_template_engine()
	{
		$config = new config([
			'load_tplcompile' => true,
			'tpl_allow_php'   => false,
			'assets_version'  => null,
		]);

		$path_helper = $this->phpbb_container->get('path_helper');
		$environment = new environment(
			$config,
			$this->phpbb_container->get('filesystem'),
			$path_helper,
			$this->phpbb_container->getParameter('core.cache_dir'),
			$this->phpbb_container->get('ext.manager'),
			new loader(
				new \phpbb\filesystem\filesystem()
			)
		);

		// Custom filter for use by packager to decode greater/less than symbols
		$filter = new \Twig\TwigFilter('skeleton_decode', function ($string) {
			return str_replace(['&lt;', '&gt;'], ['<', '>'], $string);
		}, ['is_safe' => ['html']]);
		$environment->addFilter($filter);

		$template_engine = new twig(
			$path_helper,
			$config,
			new context(),
			$environment,
			$this->phpbb_container->getParameter('core.cache_dir'),
			$this->phpbb_container->get('user'),
			[
				new skeleton_version_compare()
			]
		);

		return $template_engine;
	}

	/**
	 * Get an array of language class and methods depending on 3.1 or 3.2
	 * compatibility, for use in the skeleton twig templates.
	 *
	 * @param bool $phpbb31 Is phpBB 3.1 support requested?
	 *
	 * @return array An array of language data
	 */
	protected function get_language_version_data($phpbb31)
	{
		return [
			'class'		=> $phpbb31 ? '\phpbb\user' : '\phpbb\language\language',
			'object'	=> $phpbb31 ? 'user' : 'language',
			'function'	=> $phpbb31 ? 'add_lang_ext' : 'add_lang',
			'indent'	=> [
				'class'		=> $phpbb31 ? "\t\t\t" : '',
				'object'	=> $phpbb31 ? "\t" : '',
			],
		];
	}
}
