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

namespace phpbb\skeleton\controller;

use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\exception\http_exception;
use phpbb\language\language;
use phpbb\request\request;
use phpbb\skeleton\helper\packager;
use phpbb\skeleton\helper\validator;
use phpbb\template\template;
use phpbb\user;
use Symfony\Component\HttpFoundation\Response;

class main
{
	/** @var array */
	protected $data;

	/* @var config */
	protected $config;

	/* @var helper */
	protected $helper;

	/** @var language */
	protected $language;

	/* @var request */
	protected $request;

	/* @var packager */
	protected $packager;

	/* @var validator */
	protected $validator;

	/* @var template */
	protected $template;

	/* @var user */
	protected $user;

	/**
	 * Constructor
	 *
	 * @param config    $config
	 * @param helper    $helper
	 * @param language  $language
	 * @param request   $request
	 * @param packager  $packager
	 * @param validator $validator
	 * @param template  $template
	 * @param user      $user
	 */
	public function __construct(config $config, helper $helper, language $language, request $request, packager $packager, validator $validator, template $template, user $user)
	{
		$this->config = $config;
		$this->helper = $helper;
		$this->language = $language;
		$this->request = $request;
		$this->packager = $packager;
		$this->validator = $validator;
		$this->template = $template;
		$this->user = $user;

		$this->language->add_lang('common', 'phpbb/skeleton');
	}

	/**
	 * Controller for route /skeleton
	 *
	 * @throws http_exception
	 * @throws \Exception
	 *
	 * @return Response A Symfony Response object
	 */
	public function handle()
	{
		if ($this->user->data['is_bot'])
		{
			throw new http_exception(403, 'NOT_AUTHORISED');
		}

		if ($this->request->is_set_post('submit'))
		{
			try
			{
				$this->get_composer_data();
				$this->get_component_data();

				$this->packager->create_extension($this->data);
				$filename = $this->packager->create_zip($this->data);

				$response = new Response($filename);
				$response->headers->set('Content-type', 'application/octet-stream');
				$response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '";');
				$response->headers->set('Content-length', filesize($filename));
				$response->sendHeaders();
				$response->setContent(readfile($filename));

				return $response;
			}
			catch (\Exception $e)
			{
				$this->template->assign_var('ERROR', $e->getMessage());
			}
		}

		$dialog_questions = $this->packager->get_composer_dialog_values();
		foreach ($dialog_questions['extension'] as $value => $default)
		{
			$this->template->assign_block_vars('extension', [
				'NAME'			=> $value,
				'DESC'			=> $this->language->lang('SKELETON_QUESTION_' . strtoupper($value) . '_UI'),
				'DESC_EXPLAIN'	=> $this->language->is_set('SKELETON_QUESTION_' . strtoupper($value) . '_EXPLAIN') ? $this->language->lang('SKELETON_QUESTION_' . strtoupper($value) . '_EXPLAIN') : '',
				'VALUE'			=> $this->request->variable($value, (string) $default, true),
			]);
		}

		$author_values = [];
		foreach ($dialog_questions['author'] as $value => $default)
		{
			$author_values[$value] = $this->request->variable($value, [(string) $default], true);
		}

		$num_authors = max(1, count($author_values['author_name']));
		for ($i = 0; $i < $num_authors; $i++)
		{
			foreach ($dialog_questions['author'] as $value => $default)
			{
				$this->template->assign_block_vars('author', [
					'NAME'			=> $value,
					'DESC'			=> $this->language->lang('SKELETON_QUESTION_' . strtoupper($value) . '_UI'),
					'DESC_EXPLAIN'	=> $this->language->is_set('SKELETON_QUESTION_' . strtoupper($value) . '_EXPLAIN') ? $this->language->lang('SKELETON_QUESTION_' . strtoupper($value) . '_EXPLAIN') : '',
					'VALUE'			=> isset($author_values[$value][$i]) ? $author_values[$value][$i] : '',
				]);
			}
		}

		foreach ($dialog_questions['requirements'] as $value => $default)
		{
			$this->template->assign_block_vars('requirement', [
				'NAME'			=> $value,
				'DESC'			=> $this->language->lang('SKELETON_QUESTION_' . strtoupper($value) . '_UI'),
				'DESC_EXPLAIN'	=> $this->language->is_set('SKELETON_QUESTION_' . strtoupper($value) . '_EXPLAIN') ? $this->language->lang('SKELETON_QUESTION_' . strtoupper($value) . '_EXPLAIN') : '',
				'VALUE'			=> $this->request->variable($value, (string) $default),
			]);
		}

		$components = $this->packager->get_component_dialog_values();
		foreach ($components as $component => $details)
		{
			$this->template->assign_block_vars('component_' . $details['group'], [
				'NAME'			=> 'component_' . $component,
				'DESC'			=> $this->language->lang('SKELETON_QUESTION_COMPONENT_' . strtoupper($component) . '_UI'),
				'DESC_EXPLAIN'	=> $this->language->is_set('SKELETON_QUESTION_COMPONENT_' . strtoupper($component) . '_EXPLAIN') ? $this->language->lang('SKELETON_QUESTION_COMPONENT_' . strtoupper($component) . '_EXPLAIN') : '',
				'VALUE'			=> $this->request->variable('component_' . $component, $details['default']),
			]);

			$this->data['components'][$component] = $this->get_user_input('component_' . $component, $details['default']);
		}

		$this->template->assign_var('S_POST_ACTION', $this->helper->route('phpbb_skeleton_controller'));

		return $this->helper->render('@phpbb_skeleton/skeleton_body.html', $this->language->lang('PHPBB_CREATE_SKELETON_EXT'));
	}

	/**
	 * Get composer data
	 */
	protected function get_composer_data()
	{
		$dialog_questions = $this->packager->get_composer_dialog_values();
		foreach ($dialog_questions['extension'] as $value => $default)
		{
			$this->data['extension'][$value] = $this->get_user_input($value, $default);
		}

		$num_authors = max(1, count($this->request->variable('author_name', [''])));
		for ($i = 0; $i < $num_authors; $i++)
		{
			foreach ($dialog_questions['author'] as $value => $default)
			{
				$this->data['authors'][$i][$value] = $this->get_user_input($value, $default, $i);
			}
		}

		foreach ($dialog_questions['requirements'] as $value => $default)
		{
			$this->data['requirements'][$value] = $this->get_user_input($value, $default);
		}
	}

	/**
	 * Get components data
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
	 * Get user input values
	 *
	 * @param string   $value
	 * @param mixed    $default
	 * @param null|int $array_key for multi user support
	 *
	 * @return mixed|string
	 */
	protected function get_user_input($value, $default, $array_key = null)
	{
		$return_value = $this->get_request_variable($value, $default, $array_key);

		if (method_exists($this->validator, 'validate_' . $value))
		{
			$return_value = call_user_func([$this->validator, 'validate_' . $value], $return_value);
		}

		return $return_value;
	}

	/**
	 * Get request variables
	 *
	 * @param string   $value
	 * @param mixed    $default
	 * @param null|int $array_key for multi user support
	 *
	 * @return mixed|string
	 */
	protected function get_request_variable($value, $default, $array_key = null)
	{
		if (is_bool($default))
		{
			if ($array_key !== null)
			{
				$return_value = $this->request->variable($value, [$default]);
				return isset($return_value[$array_key]) ? $return_value[$array_key] : $default;
			}

			return $this->request->variable($value, $default);
		}

		if ($array_key !== null)
		{
			$return_value = $this->request->variable($value, [(string) $default], true);
			return isset($return_value[$array_key]) ? (string) $return_value[$array_key] : (string) $default;
		}

		return $this->request->variable($value, (string) $default, true);
	}
}
