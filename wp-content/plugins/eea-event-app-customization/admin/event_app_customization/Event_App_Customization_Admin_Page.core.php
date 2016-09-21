<?php if ( ! defined( 'EVENT_ESPRESSO_VERSION' )) { exit('NO direct script access allowed'); }
/**
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @package		Event Espresso
 * @author			Event Espresso
 * @copyright 	(c) 2009-2014 Event Espresso All Rights Reserved.
 * @license			http://eventespresso.com/support/terms-conditions/  ** see Plugin Licensing **
 * @link				http://www.eventespresso.com
 * @version			EE4
 *
 * ------------------------------------------------------------------------
 *
 * Event_App_Customization_Admin_Page
 *
 * This contains the logic for setting up the Event_App_Customization Addon Admin related pages.  Any methods without PHP doc comments have inline docs with parent class.
 *
 *
 * @package			Event_App_Customization_Admin_Page (event_app_customization addon)
 * @subpackage 	admin/Event_App_Customization_Admin_Page.core.php
 * @author				Darren Ethier, Brent Christensen
 *
 * ------------------------------------------------------------------------
 */
class Event_App_Customization_Admin_Page extends EE_Admin_Page {


	protected function _init_page_props() {
		$this->page_slug = EVENT_APP_CUSTOMIZATION_PG_SLUG;
		$this->page_label = EVENT_APP_CUSTOMIZATION_LABEL;
		$this->_admin_base_url = EE_EVENT_APP_CUSTOMIZATION_ADMIN_URL;
		$this->_admin_base_path = EE_EVENT_APP_CUSTOMIZATION_ADMIN;
	}




	protected function _ajax_hooks() {}





	protected function _define_page_props() {
		$this->_admin_page_title = EVENT_APP_CUSTOMIZATION_LABEL;
		$this->_labels = array(
			'publishbox' => __('Update Settings', 'event_espresso')
		);
	}




	protected function _set_page_routes() {
		$this->_page_routes = array(
			'default' => '_basic_settings',
			'update_settings' => array(
				'func' => '_update_settings',
				'noheader' => TRUE
			),
			'usage' => '_usage'
		);
	}





	protected function _set_page_config() {

		$this->_page_config = array(
			'default' => array(
				'nav' => array(
					'label' => __('Settings', 'event_espresso'),
					'order' => 10
					),
				'metaboxes' => array_merge( $this->_default_espresso_metaboxes, array( '_publish_post_box') ),
				'require_nonce' => FALSE
			),
			'usage' => array(
				'nav' => array(
					'label' => __('Usage', 'event_espresso'),
					'order' => 30
					),
				'require_nonce' => FALSE
			)
		);
	}


	protected function _add_screen_options() {}
	protected function _add_screen_options_default() {}
	protected function _add_feature_pointers() {}

	public function load_scripts_styles() {
		wp_register_script( 'espresso_event_app_customization_admin', EE_EVENT_APP_CUSTOMIZATION_ADMIN_ASSETS_URL . 'espresso_event_app_customization_admin.js', array( 'espresso_core' ), EE_EVENT_APP_CUSTOMIZATION_VERSION, TRUE );
		wp_enqueue_script( 'espresso_event_app_customization_admin');
	}

	public function admin_init() {
		EE_Registry::$i18n_js_strings[ 'confirm_reset' ] = __( 'Are you sure you want to reset ALL your Event Espresso Event App Customization Information? This cannot be undone.', 'event_espresso' );
	}

	public function admin_notices() {}
	public function admin_footer_scripts() {}






	protected function _basic_settings() {
		$form = $this->_basic_settings_form();
		
		$this->_set_add_edit_form_tags( 'update_settings' );
		$this->_set_publish_post_box_vars( NULL, FALSE, FALSE, NULL, FALSE);
		$this->_template_args['admin_page_content'] = 
			$form->form_open( 
				EE_Admin_Page::add_query_args_and_nonce( 
					array( 
						'action' => 'update_settings',
					),
					EE_EVENT_APP_CUSTOMIZATION_ADMIN_URL
				), 
				'POST' 
			) .
			$form->get_html_and_js() .
			$form->form_close();
		$this->display_admin_page_with_sidebar();
		
	}

	protected function _basic_settings_form() {
		$customization_config = EE_Config::instance()->addons->EE_Event_App_Customization;
		return new EE_Form_Section_Proper(
			array(
				'name' => 'Event_App_Customization',
				'subsections' => array(
					
					'logo_image_url' => new EE_Admin_File_Uploader_Input(
						array(
							'html_label_text' => __( 'App Logo Image', 'event_espresso' ),
							'default' => $customization_config->logo_image_url,
							'html_help_text' => __( 'Image Recommendations: file type: .png or .gif, dimensions: 1200x800', 'event_espresso' )
						)
					),
					'powered_by' => new EE_Text_Input(
						array(
							'html_label_text' => __( 'Powered By Text', 'event_espresso' ),
							'default' => $customization_config->powered_by,
							'html_help_text' => __( 'Optional: Change the powered by text in login screen footer.', 'event_espresso' )
						)
					),
					'reset' => new EE_Yes_No_Input(
						array(
							'html_label_text' => __( 'Reset Settings', 'event_espresso' ),
							'default' => false,
							'html_help_text' => __( 'Restore default settings. Note this cannot be undone.', 'event_espresso' )
						))
				)
			)
		);
	}



	

	protected function _usage() {
		$this->_template_args['admin_page_content'] = EEH_Template::display_template( EE_EVENT_APP_CUSTOMIZATION_ADMIN_TEMPLATE_PATH . 'event_app_customization_usage_info.template.php', array(), TRUE );
		$this->display_admin_page_with_no_sidebar();
	}

	protected function _update_settings(){
		$form = $this->_basic_settings_form();
		$form->receive_form_submission( $this->_req_data );
		if( $form->is_valid() ){
			$customization_config = EE_Config::instance()->addons->EE_Event_App_Customization;
			if( ! $customization_config instanceof EE_Event_App_Customization_Config ){
				throw new EE_Error( esc_html__( 'Cannot update Event App Customization configuration data because it does not exist', 'event_espresso' ) );
			}
			if( $form->get_input_value( 'reset' ) == true ) {
				EE_Config::instance()->addons->EE_Event_App_Customization = new EE_Event_App_Customization_Config();
			} else {
				$customization_config->powered_by = $form->get_input_value( 'powered_by' );
				$customization_config->logo_image_url = $form->get_input_value( 'logo_image_url' );
			}
			EE_Config::instance()->update_espresso_config();
		}else{
			$count = 0;
		}
		$this->_redirect_after_action( $count, 'Settings', 'updated' );
	}
}
// End of file Event_App_Customization_Admin_Page.core.php
// Location: /wp-content/plugins/eea-event-app-customization/admin/event_app_customization/Event_App_Customization_Admin_Page.core.php