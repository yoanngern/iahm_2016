<?php
/*
  Plugin Name: Event Espresso - Event App Customization (EE4.9.9+)
  Plugin URI: http://www.eventespresso.com
  Description: The EE4 Event App Customization add-ons allows you to customize the EE4 Android and Apple event apps.
  Version: 1.0.0.p
  Author: Event Espresso
  Author URI: http://www.eventespresso.com
  Copyright 2016 Event Espresso (email : support@eventespresso.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA02110-1301USA
 *
 * ------------------------------------------------------------------------
 *
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @ package		Event Espresso
 * @ author			Event Espresso
 * @ copyright	(c) 2008-2016 Event Espresso  All Rights Reserved.
 * @ license		http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link				http://www.eventespresso.com
 * @ version	 	EE4
 *
 * ------------------------------------------------------------------------
 */
// define versions and this file
define( 'EE_EVENT_APP_CUSTOMIZATION_WP_VERSION_REQUIRED', '4.4.2' );
define( 'EE_EVENT_APP_CUSTOMIZATION_CORE_VERSION_REQUIRED', '4.9.9.rc.010' );
define( 'EE_EVENT_APP_CUSTOMIZATION_VERSION', '1.0.0.p' );
define( 'EE_EVENT_APP_CUSTOMIZATION_PLUGIN_FILE',  __FILE__ );




/**
 *    captures plugin activation errors for debugging
 */
function espresso_event_app_customization_plugin_activation_errors() {

	if ( WP_DEBUG ) {
		$activation_errors = ob_get_contents();
		file_put_contents( EVENT_ESPRESSO_UPLOAD_DIR . 'logs' . DS . 'espresso_event_app_customization_plugin_activation_errors.html', $activation_errors );
	}
}
add_action( 'activated_plugin', 'espresso_event_app_customization_plugin_activation_errors' );



/**
 *    registers addon with EE core
 */
function load_espresso_event_app_customization() {
  if ( class_exists( 'EE_Addon' )) {
      // event_app_customization version
      require_once ( plugin_dir_path( __FILE__ ) . 'EE_Event_App_Customization.class.php' );
      EE_Event_App_Customization::register_addon();
  } else {
    add_action( 'admin_notices', 'espresso_event_app_customization_activation_error' );
  }
}
add_action( 'AHEE__EE_System__load_espresso_addons', 'load_espresso_event_app_customization' );



/**
 *    verifies that addon was activated
 */
function espresso_event_app_customization_activation_check() {
  if ( ! did_action( 'AHEE__EE_System__load_espresso_addons' ) ) {
    add_action( 'admin_notices', 'espresso_event_app_customization_activation_error' );
  }
}
add_action( 'init', 'espresso_event_app_customization_activation_check', 1 );



/**
 *    displays activation error admin notice
 */
function espresso_event_app_customization_activation_error() {
  unset( $_GET[ 'activate' ] );
  unset( $_REQUEST[ 'activate' ] );
  if ( ! function_exists( 'deactivate_plugins' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
  }
  deactivate_plugins( plugin_basename( EE_EVENT_APP_CUSTOMIZATION_PLUGIN_FILE ) );
  ?>
  <div class="error">
    <p><?php printf( esc_html__( 'Event Espresso Event App Customization could not be activated. Please ensure that Event Espresso version %1$s or higher is running', 'event_espresso' ), EE_EVENT_APP_CUSTOMIZATION_CORE_VERSION_REQUIRED ); ?></p>
  </div>
<?php
}



// End of file espresso_event_app_customization.php
// Location: wp-content/plugins/eea-event-app-customization/espresso_event_app_customization.php