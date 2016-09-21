<?php if ( ! defined('EVENT_ESPRESSO_VERSION')) exit('No direct script access allowed');
/**
 * class EE_Request
 *
 * @package         Event Espresso
 * @subpackage  /core/
 * @author          Brent Christensen
 *
 * ------------------------------------------------------------------------
 */
class EE_Request {

	/**
	 * @access private
	 * @var    array $_get $_GET parameters
	 */
	private $_get = array();

	/**
	 * @access private
	 * @var    array $_post $_POST parameters
	 */
	private $_post = array();

	/**
	 * @access private
	 * @var    array $_cookie $_COOKIE parameters
	 */
	private $_cookie = array();

	/**
	 * @access private
	 * @var    array $_params $_REQUEST parameters
	 */
	private $_params = array();

	/**
	 * whether current request is via AJAX
	 *
	 * @var    boolean
	 * @access public
	 */
	public $ajax = false;

	/**
	 * whether current request is via AJAX from the frontend of the site
	 *
	 * @var    boolean
	 * @access public
	 */
	public $front_ajax = false;



	/**
	 *    class constructor
	 *
	 * @access    public
	 * @param array $get
	 * @param array $post
	 * @param array $cookie
	 */
	public function __construct( $get, $post, $cookie ) {
		// grab request vars
		$this->_get    = $get;
		$this->_post   = $post;
		$this->_cookie = $cookie;
		$this->_params = array_merge( $get, $post );
		// AJAX ???
		$this->ajax       = defined( 'DOING_AJAX' ) ? true : false;
		$this->front_ajax = $this->is_set( 'ee_front_ajax' ) && $this->get( 'ee_front_ajax' ) == 1 ? true : false;
	}



	/**
	 * @return array
	 */
	public function get_params() {
		return $this->_get;
	}



	/**
	 * @return array
	 */
	public function post_params() {
		return $this->_post;
	}



	/**
	 * @return array
	 */
	public function cookie_params() {
		return $this->_cookie;
	}



	/**
	 * returns contents of $_REQUEST
	 * @return array
	 */
	public function params() {
		return $this->_params;
	}



	/**
	 *    setter
	 *
	 * @access    public
	 * @param      $key
	 * @param      $value
	 * @param bool $override_ee
	 * @return    void
	 */
	public function set( $key, $value, $override_ee = FALSE ) {
		// don't allow "ee" to be overwritten unless explicitly instructed to do so
		if ( $key != 'ee' || ( $key == 'ee' && empty( $this->_params['ee'] )) || ( $key == 'ee' && ! empty( $this->_params['ee'] ) && $override_ee )) {
			$this->_params[ $key ] = $value;
		}
	}



	/**
	 *    getter
	 *
	 * @access    public
	 * @param      $key
	 * @param null $default
	 * @return    mixed
	 */
	public function get( $key, $default = NULL ) {
		return isset( $this->_params[ $key ] ) ? $this->_params[ $key ] : $default;
	}



	/**
	 *    check if param exists
	 *
	 * @access    public
	 * @param $key
	 * @return    boolean
	 */
	public function is_set( $key ) {
		return isset( $this->_params[ $key ] ) ? TRUE : FALSE;
	}



	/**
	 *    remove param
	 *
	 * @access    public
	 * @param $key
	 * @param bool $unset_from_global_too
	 */
	public function un_set( $key, $unset_from_global_too = false ) {
		unset( $this->_params[ $key ] );
		if ( $unset_from_global_too ) {
			unset( $_REQUEST[ $key ] );
		}
	}




}
// End of file EE_Request.core.php
// Location: /core/request_stack/EE_Request.core.php