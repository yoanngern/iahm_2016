<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) exit('No direct script access allowed');
/**
* Event Espresso
*
* Event Registration and Management Plugin for WordPress
*
* @ package 		Event Espresso
* @ author			Seth Shoultes
* @ copyright 	(c) 2008-2011 Event Espresso  All Rights Reserved.
* @ license 		{@link http://eventespresso.com/support/terms-conditions/}   * see Plugin Licensing *
* @ link 				{@link http://www.eventespresso.com}
* @ since		 	$VID:$
*
* ------------------------------------------------------------------------
*
* Event_App_Customization_Admin_Page_Init class
*
* This is the init for the Event_App_Customization Addon Admin Pages.  See EE_Admin_Page_Init for method inline docs.
*
* @package			Event Espresso (event_app_customization addon)
* @subpackage		admin/Event_App_Customization_Admin_Page_Init.core.php
* @author				Darren Ethier
*
* ------------------------------------------------------------------------
*/
class Event_App_Customization_Admin_Page_Init extends EE_Admin_Page_Init  {

	/**
	 * 	constructor
	 *
	 * @access public
	 * @return \Event_App_Customization_Admin_Page_Init
	 */
	public function __construct() {

		do_action( 'AHEE_log', __FILE__, __FUNCTION__, '' );

		define( 'EVENT_APP_CUSTOMIZATION_PG_SLUG', 'espresso_event_app_customization' );
		define( 'EVENT_APP_CUSTOMIZATION_LABEL', __( 'App Customization', 'event_espresso' ));
		define( 'EE_EVENT_APP_CUSTOMIZATION_ADMIN_URL', admin_url( 'admin.php?page=' . EVENT_APP_CUSTOMIZATION_PG_SLUG ));
		define( 'EE_EVENT_APP_CUSTOMIZATION_ADMIN_ASSETS_PATH', EE_EVENT_APP_CUSTOMIZATION_ADMIN . 'assets' . DS );
		define( 'EE_EVENT_APP_CUSTOMIZATION_ADMIN_ASSETS_URL', EE_EVENT_APP_CUSTOMIZATION_URL . 'admin' . DS . 'event_app_customization' . DS . 'assets' . DS );
		define( 'EE_EVENT_APP_CUSTOMIZATION_ADMIN_TEMPLATE_PATH', EE_EVENT_APP_CUSTOMIZATION_ADMIN . 'templates' . DS );
		define( 'EE_EVENT_APP_CUSTOMIZATION_ADMIN_TEMPLATE_URL', EE_EVENT_APP_CUSTOMIZATION_URL . 'admin' . DS . 'event_app_customization' . DS . 'templates' . DS );

		parent::__construct();
		$this->_folder_path = EE_EVENT_APP_CUSTOMIZATION_ADMIN;

	}





	protected function _set_init_properties() {
		$this->label = EVENT_APP_CUSTOMIZATION_LABEL;
	}



	/**
	*		_set_menu_map
	*
	*		@access 		protected
	*		@return 		void
	*/
	protected function _set_menu_map() {
		$this->_menu_map = new EE_Admin_Page_Sub_Menu( array(
			'menu_group' => 'addons',
			'menu_order' => 25,
			'show_on_menu' => EE_Admin_Page_Menu_Map::BLOG_ADMIN_ONLY,
			'parent_slug' => 'espresso_events',
			'menu_slug' => EVENT_APP_CUSTOMIZATION_PG_SLUG,
			'menu_label' => EVENT_APP_CUSTOMIZATION_LABEL,
			'capability' => 'administrator',
			'admin_init_page' => $this
		));
	}



}
// End of file Event_App_Customization_Admin_Page_Init.core.php
// Location: /wp-content/plugins/eea-event-app-customization/admin/event_app_customization/Event_App_Customization_Admin_Page_Init.core.php
