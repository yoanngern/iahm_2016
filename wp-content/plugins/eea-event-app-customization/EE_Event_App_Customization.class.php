<?php if ( ! defined( 'EVENT_ESPRESSO_VERSION' )) { exit(); }

// define the plugin directory path and URL
define( 'EE_EVENT_APP_CUSTOMIZATION_BASENAME', plugin_basename( EE_EVENT_APP_CUSTOMIZATION_PLUGIN_FILE ) );
define( 'EE_EVENT_APP_CUSTOMIZATION_PATH', plugin_dir_path( __FILE__ ) );
define( 'EE_EVENT_APP_CUSTOMIZATION_URL', plugin_dir_url( __FILE__ ) );
define( 'EE_EVENT_APP_CUSTOMIZATION_ADMIN', EE_EVENT_APP_CUSTOMIZATION_PATH . 'admin' . DS . 'event_app_customization' . DS );



/**
 *
 * Class  EE_Event_App_Customization
 *
 * @package			Event Espresso
 * @subpackage		eea-event-app-customization
 * @author			    Brent Christensen
 *
 */
Class  EE_Event_App_Customization extends EE_Addon {

	public static function register_addon() {
		// register addon via Plugin API
		EE_Register_Addon::register(
			'Event_App_Customization',
			array(
				'version' 					=> EE_EVENT_APP_CUSTOMIZATION_VERSION,
				'plugin_slug' 			=> 'espresso_event_app_customization',
				'min_core_version' => EE_EVENT_APP_CUSTOMIZATION_CORE_VERSION_REQUIRED,
				'main_file_path' 		=> EE_EVENT_APP_CUSTOMIZATION_PLUGIN_FILE,
				'admin_path' 			=> EE_EVENT_APP_CUSTOMIZATION_ADMIN,
				'admin_callback'		=> '',
				'config_class' 			=> 'EE_Event_App_Customization_Config',
				'config_name' 		=> 'EE_Event_App_Customization',
				'autoloader_paths' => array(
					'EE_Event_App_Customization' 						=> EE_EVENT_APP_CUSTOMIZATION_PATH . 'EE_Event_App_Customization.class.php',
					'EE_Event_App_Customization_Config' 			=> EE_EVENT_APP_CUSTOMIZATION_PATH . 'EE_Event_App_Customization_Config.php',
					'Event_App_Customization_Admin_Page' 		=> EE_EVENT_APP_CUSTOMIZATION_ADMIN . 'Event_App_Customization_Admin_Page.core.php',
					'Event_App_Customization_Admin_Page_Init' => EE_EVENT_APP_CUSTOMIZATION_ADMIN . 'Event_App_Customization_Admin_Page_Init.core.php',
				),
				'module_paths' 		=> array( 
					EE_EVENT_APP_CUSTOMIZATION_PATH . 'EED_Event_App_Customization_REST_API.module.php',
					EE_EVENT_APP_CUSTOMIZATION_PATH . 'EED_Event_App_Customization_Image.module.php'
				),
				'pue_options'			=> array(
					'pue_plugin_slug' 		=> 'eea-event-app-customization',
					'plugin_basename' 	=> EE_EVENT_APP_CUSTOMIZATION_BASENAME,
					'checkPeriod' 				=> '24',
					'use_wp_update' 		=> FALSE,
				),
			)
		);
	}

	





}
// End of file EE_Event_App_Customization.class.php
// Location: wp-content/plugins/eea-event-app-customization/EE_Event_App_Customization.class.php
