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
	* @param config		$config
	* @param helper		$helper
	* @param request	$request
	* @param packager	$packager
	* @param validator	$validator
	* @param template	$template
	* @param user		$user
	*/
	public function __construct(config $config, helper $helper, request $request, packager $packager, validator $validator, template $template, user $user)
	{
		$this->config = $config;
		$this->helper = $helper;
		$this->request = $request;
		$this->packager = $packager;
		$this->validator = $validator;
		$this->template = $template;
		$this->user = $user;

		$this->user->add_lang_ext('phpbb/skeleton', 'common');
	}

	/**
	* Demo controller for route /skeleton
	*
	* @return \Symfony\Component\HttpFoundation\Response A Symfony Response object
	*/
	public function handle()
	{
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
			$this->template->assign_block_vars('extension', array(
				'NAME'			=> $value,
				'DESC'			=> $this->user->lang('SKELETON_QUESTION_' . strtoupper($value) . '_UI'),
				'DESC_EXPLAIN'	=> isset($this->user->lang['SKELETON_QUESTION_' . strtoupper($value) . '_EXPLAIN']) ? $this->user->lang('SKELETON_QUESTION_' . strtoupper($value) . '_EXPLAIN') : '',
				'VALUE'			=> $this->request->variable($value, (string) $default),
			));
		}

		// TODO we need JS magic for multi author support
		foreach ($dialog_questions['author'] as $value => $default)
		{
			$this->template->assign_block_vars('author', array(
				'NAME'			=> $value,
				'DESC'			=> $this->user->lang('SKELETON_QUESTION_' . strtoupper($value) . '_UI'),
				'DESC_EXPLAIN'	=> isset($this->user->lang['SKELETON_QUESTION_' . strtoupper($value) . '_EXPLAIN']) ? $this->user->lang('SKELETON_QUESTION_' . strtoupper($value) . '_EXPLAIN') : '',
				'VALUE'			=> $this->request->variable($value, (string) $default),
			));
		}

		foreach ($dialog_questions['requirements'] as $value => $default)
		{
			$this->template->assign_block_vars('requirement', array(
				'NAME'			=> $value,
				'DESC'			=> $this->user->lang('SKELETON_QUESTION_' . strtoupper($value) . '_UI'),
				'DESC_EXPLAIN'	=> isset($this->user->lang['SKELETON_QUESTION_' . strtoupper($value) . '_EXPLAIN']) ? $this->user->lang('SKELETON_QUESTION_' . strtoupper($value) . '_EXPLAIN') : '',
				'VALUE'			=> $this->request->variable($value, (string) $default),
			));
		}

		$components = $this->packager->get_component_dialog_values();
		foreach ($components as $component => $details)
		{
			$this->template->assign_block_vars('component', array(
				'NAME'			=> $component,
				'DESC'			=> $this->user->lang('SKELETON_QUESTION_COMPONENT_' . strtoupper($component) . '_UI'),
				'DESC_EXPLAIN'	=> isset($this->user->lang['SKELETON_QUESTION_COMPONENT_' . strtoupper($component) . '_EXPLAIN']) ? $this->user->lang('SKELETON_QUESTION_COMPONENT_' . strtoupper($component) . '_EXPLAIN') : '',
				'VALUE'			=> $this->request->variable('component_' . $component, $details['default']),
			));

			$this->data['components'][$component] = $this->get_user_input('component_' . $component, $details['default']);
		}

		return $this->helper->render('skeleton_body.html', $this->user->lang('PHPBB_SKELETON_EXT'));
	}

	/**
	 *
	 */
	protected function get_composer_data()
	{
		$dialog_questions = $this->packager->get_composer_dialog_values();
		foreach ($dialog_questions['extension'] as $value => $default)
		{
			$this->data['extension'][$value] = $this->get_user_input($value, $default);
		}

		// TODO we need JS magic for multi author support
		foreach ($dialog_questions['author'] as $value => $default)
		{
			$this->data['authors'][0][$value] = $this->get_user_input($value, $default);
		}

		foreach ($dialog_questions['requirements'] as $value => $default)
		{
			$this->data['requirements'][$value] = $this->get_user_input($value, $default);
		}
	}

	/**
	 *
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
	 * @param string $value
	 * @param mixed $default
	 * @return mixed|string
	 * @throws \Exception
	 */
	protected function get_user_input($value, $default)
	{
		if (method_exists($this->validator, 'validate_' . $value))
		{
			$return_value = $this->request->variable($value, (string) $default);
			$return_value = call_user_func(array($this->validator, 'validate_' . $value), $return_value);
		}
		else if (is_bool($default))
		{
			$return_value = $this->request->variable($value, $default);
		}
		else
		{
			$return_value = $this->request->variable($value, (string) $default);
		}

		return $return_value;
	}
}
