<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) { exit('No direct script access allowed'); }
/*
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @ package		Event Espresso
 * @ author			Event Espresso
 * @ copyright	(c) 2008-2014 Event Espresso  All Rights Reserved.
 * @ license		http://eventespresso.com/support/terms-conditions/   * see Plugin Licensing *
 * @ link				http://www.eventespresso.com
 * @ version		$VID:$
 *
 * ------------------------------------------------------------------------
 */
/**
 * Class  EED_Event_App_Customization_REST_API
 * Modifies the wp-json/ee/v4.8.36/site_info route to also have app customization info
 *
 * @package			Event Espresso
 * @subpackage		eea-event-app-customization
 * @author 				Brent Christensen
 *
 * ------------------------------------------------------------------------
 */
class EED_Event_App_Customization_REST_API extends EED_Module {

	 /**
	  * 	set_hooks - for hooking into EE Core, other modules, etc
	  *
	  *  @access 	public
	  *  @return 	void
	  */
	 public static function set_hooks() {
		 add_filter( 'rest_post_dispatch', array( 'EED_Event_App_Customization_REST_API', 'filter_site_info_response' ), 10, 3 );
	 }

	 /**
	  * 	set_hooks_admin - for hooking into EE Admin Core, other modules, etc
	  *
	  *  @access 	public
	  *  @return 	void
	  */
	 public static function set_hooks_admin() {
	 }
	 
	 public static function filter_site_info_response( WP_REST_Response $response, WP_REST_Server $server, WP_REST_Request $request ) {
		  $handler = $response->get_matched_handler();
		  if( isset( $handler[ 'callback' ] )
			  && is_array( $handler[ 'callback' ] )
			  && isset( $handler[ 'callback' ][0] )
				&& isset( $handler[ 'callback' ][1] )
				&& $handler[ 'callback' ][0] === 'EventEspresso\\core\\libraries\\rest_api\\controllers\\config\\Read'
				&& $handler[ 'callback' ][1] === 'handle_request_site_info' ) {
			  $data = $response->get_data();
			  $branding_config = EE_Config::instance()->addons->EE_Event_App_Customization;
			  if( $branding_config instanceof EE_Event_App_Customization_Config ) {
				  $powered_by = $branding_config->powered_by;
				  $image_url = $branding_config->logo_image_url;
				  $image_id = EED_Event_App_Customization_Image::get_attachment_id_by_url( $image_url );
			  } else {
				  $powered_by = '';
				  $image_id = 0;
			  }
			  $sizes = array( 'thumbnail', 'medium', 'large', 'full' );
			  foreach( $sizes as $size ) {
				  $image_data[ 'image_' . $size ] = EED_Event_App_Customization_REST_API::get_image_data_for_api( $image_id, $size );
			  }
			  $data[ 'custom_branding' ] = array(
				  'logo_image' => $image_data,
				  'powered_by' => $powered_by,
			  );
			  $response->set_data( $data );
			
		  }
		 return $response;
	 }	
	 
	 protected static function get_image_data_for_api( $attachment_id, $image_size ) {
		 $data = wp_get_attachment_image_src( $attachment_id, $image_size );
		if( ! $data ) {
			return null;
		}
		/*if( isset( $data[ 3] ) ) {
			$generated = $data[ 3 ];
		} else {
			$generated = true;
		}*/
		return array(
			'url' => $data[ 0 ],
			'width' => $data[ 1 ],
			'height' => $data[ 2 ],
			//'generated' => $generated
		);
	 }

	 /**
	  *    run - initial module setup
	  *
	  * @access    public
	  * @param  WP $WP
	  * @return    void
	  */
	 public function run( $WP ) {
	 }
 }
// End of file EED_Event_App_Customization.module.php
// Location: /wp-content/plugins/eea-event-app-customization/EED_Event_App_Customization.module.php
