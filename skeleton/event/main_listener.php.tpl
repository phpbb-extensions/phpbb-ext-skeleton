<?php
/**
*
* @package phpBB Extension - Acme Demo
* @copyright (c) 2013 phpBB Group
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace {EXTENSION.vendor_name}\{EXTENSION.extension_name}\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class main_listener implements EventSubscriberInterface
{
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'						=> 'load_language_on_setup',
<!-- IF COMPONENT.route -->
			'core.page_header'						=> 'add_page_header_link',
<!-- ENDIF -->
		);
	}

	/* @var \phpbb\controller\helper */
	protected $helper;

	/* @var \phpbb\template\template */
	protected $template;

	/**
	* Constructor
	*
	* @param \phpbb\controller\helper	$helper		Controller helper object
	* @param \phpbb\template\template	$template	Template object
	*/
	public function __construct(\phpbb\controller\helper $helper, \phpbb\template\template $template)
	{
		$this->helper = $helper;
		$this->template = $template;
	}

	public function load_language_on_setup($event)
	{
<!-- IF not COMPONENT.language-controller -->
		var_dump('hello event');
<!-- ELSE -->
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => '{EXTENSION.vendor_name}/{EXTENSION.extension_name}',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
<!-- ENDIF -->
	}
<!-- IF COMPONENT.route -->

	public function add_page_header_link($event)
	{
		$this->template->assign_vars(array(
			'U_DEMO_PAGE'	=> $this->helper->route('{EXTENSION.vendor_name}_{EXTENSION.extension_name}_controller', array('name' => 'world')),
		));
	}
<!-- ENDIF -->
}
