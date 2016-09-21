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
 * Class  EED_Event_App_Customization_Image
 * Modifies the wp-json/ee/v4.8.36/site_info route to also have app customization info
 *
 * @package			Event Espresso
 * @subpackage		eea-event-app-customization
 * @author 				Brent Christensen
 *
 * ------------------------------------------------------------------------
 */
class EED_Event_App_Customization_Image extends EED_Module {

	 /**
	  * 	set_hooks - for hooking into EE Core, other modules, etc
	  *
	  *  @access 	public
	  *  @return 	void
	  */
	 public static function set_hooks() {
	 }

	 /**
	  * 	set_hooks_admin - for hooking into EE Admin Core, other modules, etc
	  *
	  *  @access 	public
	  *  @return 	void
	  */
	 public static function set_hooks_admin() {
		 add_action ( 'init', array( 'EED_Event_App_Customization_Image', 'check_background_image_set' ) );
	 }
	 
	 /**
	  * Verifies the config's background image is set. If not, sets it to the 
	  * organization's image (also verifies the org's image is a real image attachment)
	  */
	 public static function check_background_image_set() {
		 if( 
			EE_Config::instance()->addons->EE_Event_App_Customization instanceof EE_Event_App_Customization_Config
			&& empty( EE_Config::instance()->addons->EE_Event_App_Customization->logo_image_url ) 
		) {
			$attachment_id = EED_Event_App_Customization_Image::get_attachment_id_by_url( EE_Config::instance()->organization->logo_url );
			if( ! $attachment_id ) {
				try{
					$attachment_id = EED_Event_App_Customization_Image::create_image_attachment_from_GUID( EE_Config::instance()->organization->logo_url );
				} catch( EE_Error $e ) {
					error_log( $e->getMessage() );
					return;
				}
			}
			$image_url = wp_get_attachment_url( $attachment_id );
			EE_Config::instance()->addons->EE_Event_App_Customization->logo_image_url = $image_url;
			EE_Config::instance()->update_espresso_config();
		}
		 
	 }
	 
	/**
	 * Gets the attachment post type ID by the provided URL
	 * props to https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
	 * @global type $wpdb
	 * @param string $url
	 * @return int|false
	 */
	public static function get_attachment_id_by_url( $url ) {
		if( empty( $url ) ) {
			return false;
		}
		global $wpdb;
		return $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s' and post_type='attachment' LIMIT 1;", $url )); 
	}
	
	/**
	 * Creates an image attachment post for the GUID. If the GUID points to a remote image,
	 * we download it to our uploads directory so that it can be properly processed (eg, creates different sizes of thumbnails)
	 * @param type $guid
	 * @param EE_Data_Migration_Script_Stage $migration_stage
	 * @return int new attachment ID
	 * @throws EE_Error if there was a problem
	 */
	public static function create_image_attachment_from_GUID( $guid ){
		if ( ! $guid ){
			throw new EE_Error( esc_html__( 'No image url provided when trying to create an image attachment.', 'event_espresso') );
		}
		$wp_filetype = wp_check_filetype(basename($guid), null );
		$wp_upload_dir = wp_upload_dir();
		//if the file is located remotely, download it to our uploads DIR, because wp_genereate_attachmnet_metadata needs the file to be local
		if(strpos($guid,$wp_upload_dir['url']) === FALSE){
			//image is located remotely. download it and place it in the uploads directory
			if( ! is_readable($guid)){
				throw new EE_Error( 
					sprintf( esc_html__( 'The image at %1$s is not readable.', 'event_espresso' ), $guid ) );
			}
			$contents= file_get_contents($guid);
			if($contents === FALSE){
				throw new EE_Error( 
					sprintf( 
						esc_html__( "Could not read image at %s, and therefore couldnt create an attachment post for it.", "event_espresso"), 
						$guid 
					)
				);
			}
			$local_filepath  = $wp_upload_dir['path'].DS.basename($guid);
			$savefile = fopen($local_filepath, 'w');
			fwrite($savefile, $contents);
			fclose($savefile);
			$guid = str_replace($wp_upload_dir['path'],$wp_upload_dir['url'],$local_filepath);
		}else{
			$local_filepath = str_replace($wp_upload_dir['url'],$wp_upload_dir['path'],$guid);
		}

		$attachment = array(
		   'guid' => $guid,
		   'post_mime_type' => $wp_filetype['type'],
		   'post_title' => preg_replace('/\.[^.]+$/', '', basename($guid)),
		   'post_content' => '',
		   'post_status' => 'inherit'
		);
		$attach_id = wp_insert_attachment( $attachment, $guid );
		if( ! $attach_id ){
			throw new EE_Error( 
				sprintf( 
					esc_html__("Could not create image attachment post from image '%s'. Attachment data was %s.", "event_espresso"),
					$guid,$this->_json_encode( $attachment ) 
				)
			);
		}

		// you must first include the image.php file
		// for the function wp_generate_attachment_metadata() to work
		require_once(ABSPATH . 'wp-admin/includes/image.php');

		$attach_data = wp_generate_attachment_metadata( $attach_id, $local_filepath );
		if( ! $attach_data){
			$migration_stage->add_error(sprintf(esc_html__("Could not genereate attachment metadata for attachment post %d with filepath %s and GUID %s. Please check the file was downloaded properly.", "event_espresso"),$attach_id,$local_filepath,$guid));
			return $attach_id;
		}
		$metadata_save_result = wp_update_attachment_metadata( $attach_id, $attach_data );
		if( ! $metadata_save_result ){
			$migration_stage->add_error(sprintf(esc_html__("Could not update attachment metadata for attachment %d with data %s", "event_espresso"),$attach_id,$this->_json_encode($attach_data)));
		}
		return $attach_id;
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
