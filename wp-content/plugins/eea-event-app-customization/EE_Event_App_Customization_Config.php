<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) { exit('No direct script access allowed'); }
/**
 * Event Espresso
 *
 * Event Registration and Ticketing Management Plugin for WordPress
 *
 * @ package			Event Espresso
 * @ author			    Event Espresso
 * @ copyright		(c) 2008-2014 Event Espresso  All Rights Reserved.
 * @ license			http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link					http://www.eventespresso.com
 * @ version		 	$VID:$
 *
 * ------------------------------------------------------------------------
 */
 /**
 *
 * Class EE_Event_App_Customization_Config
 *
 * Configuration data for the event app customization addon
 *
 * @package         Event Espresso
 * @subpackage    core
 * @author				Brent Christensen
 * @since		 	   $VID:$
 *
 */

class EE_Event_App_Customization_Config extends EE_Config_Base {
	public $powered_by = '';
	public $logo_image_url = '';

	public function __construct() {
		$this->powered_by = esc_html__( 'Powered by Event Espresso', 'event_espresso' );
		$this->logo_image_url = '';
	}
}



// End of file EE_Event_App_Customization_Config.php
// Location: /wp-content/plugins/eea-event-app-customization/EE_Event_App_Customization_Config.php