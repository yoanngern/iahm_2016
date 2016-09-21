<?php use EventEspresso\core\exceptions\UnexpectedEntityException;

if ( !defined( 'EVENT_ESPRESSO_VERSION' ) ) {
	exit( 'No direct script access allowed' );
}
/**
 * Event Espresso
 *
 * Event Registration and Management Plugin for WordPress
 *
 * @ package 		Event Espresso
 * @ author 		Event Espresso
 * @ copyright 	(c) 2008-2011 Event Espresso  All Rights Reserved.
 * @ license 		{@link http://eventespresso.com/support/terms-conditions/}   * see Plugin Licensing *
 * @ link 				{@link http://www.eventespresso.com}
 * @ since 			4.0
 *
 */



/**
 * EE_Ticket class
 *
 * @package 			Event Espresso
 * @subpackage 	includes/classes/EE_Ticket.class.php
 * @author             Darren Ethier
 */
class EE_Ticket extends EE_Soft_Delete_Base_Class implements EEI_Line_Item_Object, EEI_Event_Relation, EEI_Has_Icon {

	/**
	 * The following constants are used by the ticket_status() method to indicate whether a ticket is on sale or not.
	 */
	const sold_out = 'TKS';

	/**
	 *
	 */
	const expired = 'TKE';

	/**
	 *
	 */
	const archived = 'TKA';

	/**
	 *
	 */
	const pending = 'TKP';

	/**
	 *
	 */
	const onsale = 'TKO';

	/**
	 * cached result from method of the same name
	 * @var float $_ticket_total_with_taxes
	 */
	private $_ticket_total_with_taxes = NULL;

	/**
	 *
	 * @param array $props_n_values  incoming values
	 * @param string $timezone  incoming timezone (if not set the timezone set for the website will be
	 *                          		used.)
	 * @param array $date_formats  incoming date_formats in an array where the first value is the
	 *                             		    date_format and the second value is the time format
	 * @return EE_Ticket
	 */
	public static function new_instance( $props_n_values = array(), $timezone = null, $date_formats = array() ) {
		$has_object = parent::_check_for_object( $props_n_values, __CLASS__, $timezone, $date_formats );
		return $has_object ? $has_object : new self( $props_n_values, false, $timezone, $date_formats );
	}



	/**
	 * @param array $props_n_values  incoming values from the database
	 * @param string $timezone  incoming timezone as set by the model.  If not set the timezone for
	 *                          		the website will be used.
	 * @return EE_Ticket
	 */
	public static function new_instance_from_db( $props_n_values = array(), $timezone = null ) {
		return new self( $props_n_values, TRUE, $timezone );
	}



	/**
	 * @return bool
	 */
	public function parent() {
		return $this->get( 'TKT_parent' );
	}



	/**
	 * return if a ticket has quantities available for purchase
	 * @param  int $DTT_ID the primary key for a particular datetime
	 * @return boolean
	 */
	public function available( $DTT_ID = 0 ) {
		// are we checking availability for a particular datetime ?
		if ( $DTT_ID ) {
			// get that datetime object
			$datetime = $this->get_first_related( 'Datetime', array( array( 'DTT_ID' => $DTT_ID ) ) );
			// if  ticket sales for this datetime have exceeded the reg limit...
			if ( $datetime instanceof EE_Datetime && $datetime->sold_out() ) {
				return FALSE;
			}
		}
		// datetime is still open for registration, but is this ticket sold out ?
		return $this->qty() < 1 || $this->qty() > $this->sold() ? TRUE : FALSE;
	}



	/**
	 * Using the start date and end date this method calculates whether the ticket is On Sale, Pending, or Expired
	 * @param bool $display true = we'll return a localized string, otherwise we just return the value of the relevant status const
	 * @return mixed(int|string) status int if the display string isn't requested
	 */
	public function ticket_status( $display = FALSE ) {
		if ( ! $this->is_remaining() ) {
			return $display ? EEH_Template::pretty_status( EE_Ticket::sold_out, FALSE, 'sentence' ) : EE_Ticket::sold_out;
		}
		if ( $this->get( 'TKT_deleted' ) ) {
			return $display ? EEH_Template::pretty_status( EE_Ticket::archived, FALSE, 'sentence' ) : EE_Ticket::archived;
		}
		if ( $this->is_expired() ) {
			return $display ? EEH_Template::pretty_status( EE_Ticket::expired, FALSE, 'sentence' ) : EE_Ticket::expired;
		}
		if ( $this->is_pending() ) {
			return $display ? EEH_Template::pretty_status( EE_Ticket::pending, FALSE, 'sentence' ) : EE_Ticket::pending;
		}
		if ( $this->is_on_sale() ) {
			return $display ? EEH_Template::pretty_status( EE_Ticket::onsale, FALSE, 'sentence' ) : EE_Ticket::onsale;
		}
		return '';
	}



	/**
	 * The purpose of this method is to simply return a boolean for whether there are any tickets remaining for sale considering ALL the factors used for figuring that out.
	 *
	 * @access public
	 * @param  int $DTT_ID if an int above 0 is included here then we get a specific dtt.
	 * @return boolean         true = tickets remaining, false not.
	 */
	public function is_remaining( $DTT_ID = 0 ) {
		$num_remaining = $this->remaining( $DTT_ID );
		if ( $num_remaining === 0 ) {
			return FALSE;
		}
		if ( $num_remaining > 0 && $num_remaining < $this->min() ) {
			return FALSE;
		}
		return TRUE;
	}



	/**
	 * return the total number of tickets available for purchase
	 * @param  int $DTT_ID the primary key for a particular datetime. set to null for
	 *                     all related datetimes
	 * @return int
	 */
	public function remaining( $DTT_ID = 0 ) {
		// are we checking availability for a particular datetime ?
		if ( $DTT_ID ) {
			// get array with the one requested datetime
			$datetimes = $this->get_many_related( 'Datetime', array( array( 'DTT_ID' => $DTT_ID ) ) );
		} else {
			// we need to check availability of ALL datetimes
			$datetimes = $this->get_many_related( 'Datetime', array( 'order_by' => array( 'DTT_EVT_start' => 'ASC' ) ) );
		}
		//		d( $datetimes );
		// if datetime reg limit is not unlimited
		if ( ! empty( $datetimes ) ) {
			// although TKT_qty and $datetime->spaces_remaining() could both be EE_INF
			// we only need to check for EE_INF explicitly if we want to optimize.
			// because EE_INF - x = EE_INF; and min(x,EE_INF) = x;
			$tickets_remaining = $this->qty() - $this->sold();
			foreach ( $datetimes as $datetime ) {
				if ( $datetime instanceof EE_Datetime ) {
					$tickets_remaining = min( $tickets_remaining, $datetime->spaces_remaining() );
				}
			}
			return $tickets_remaining;
		}
		return 0;
	}



	/**
	 * Gets min
	 * @return int
	 */
	function min() {
		return $this->get( 'TKT_min' );
	}



	/**
	 * return if a ticket is no longer available cause its available dates have expired.
	 * @return boolean
	 */
	public function is_expired() {
		return ( $this->get_raw( 'TKT_end_date' ) < time() );
	}



	/**
	 * Return if a ticket is yet to go on sale or not
	 * @return boolean
	 */
	public function is_pending() {
		return ( $this->get_raw( 'TKT_start_date' ) > time() );
	}



	/**
	 * Return if a ticket is on sale or not
	 * @return boolean
	 */
	public function is_on_sale() {
		return ( $this->get_raw( 'TKT_start_date' ) < time() && $this->get_raw( 'TKT_end_date' ) > time() );
	}



	/**
	 * This returns the chronologically last datetime that this ticket is associated with
	 * @param string 	$dt_frmt
	 * @param string 	$conjunction - conjunction junction what's your function ? this string joins the start date with the end date ie: Jan 01 "to" Dec 31
	 * @return array
	 */
	public function date_range( $dt_frmt = '', $conjunction = ' - ' ) {
		$first_date = $this->first_datetime() instanceof EE_Datetime ? $this->first_datetime()->start_date( $dt_frmt ) : '';
		$last_date = $this->last_datetime() instanceof EE_Datetime ? $this->last_datetime()->end_date( $dt_frmt ) : '';

		return $first_date && $last_date ? $first_date . $conjunction  . $last_date : '';
	}



	/**
	 * This returns the chronologically first datetime that this ticket is associated with
	 * @return EE_Datetime
	 */
	public function first_datetime() {
		$datetimes = $this->datetimes( array( 'limit' => 1 ) );
		return reset( $datetimes );
	}



	/**
	 * Gets all the datetimes this ticket can be used for attending.
	 * Unless otherwise specified, orders datetimes by start date.
	 * @param array $query_params see EEM_Base::get_all()
	 * @return EE_Datetime[]
	 */
	public function datetimes( $query_params = array() ) {
		if ( ! isset( $query_params[ 'order_by' ] ) ) {
			$query_params[ 'order_by' ][ 'DTT_order' ] = 'ASC';
		}
		return $this->get_many_related( 'Datetime', $query_params );
	}



	/**
	 * This returns the chronologically last datetime that this ticket is associated with
	 * @return EE_Datetime
	 */
	public function last_datetime() {
		$datetimes = $this->datetimes( array( 'limit' => 1, 'order_by' => array( 'DTT_EVT_start' => 'DESC' ) ) );
		return end( $datetimes );
	}



	/**
	 * This returns the total tickets sold depending on the given parameters.
	 * @param  string $what   Can be one of two options: 'ticket', 'datetime'.
	 *                        'ticket' = total ticket sales for all datetimes this ticket is related to
	 *                        'datetime' = total ticket sales for a specified datetime (required $dtt_id)
	 *                        'datetime' = total ticket sales in the datetime_ticket table. If $dtt_id is not given then we return an array of sales indexed by datetime.  If $dtt_id IS given then we return the tickets sold for that given datetime.
	 * @param  int    $dtt_id [optional] include the dtt_id with $what = 'datetime'.
	 * @return mixed (array|int)          how many tickets have sold
	 */
	public function tickets_sold( $what = 'ticket', $dtt_id = NULL ) {
		$total = 0;
		$tickets_sold = $this->_all_tickets_sold();
		switch ( $what ) {
			case 'ticket' :
				return $tickets_sold[ 'ticket' ];
				break;
			case 'datetime' :
				if ( empty( $tickets_sold[ 'datetime' ] ) ) {
					return $total;
				}
				if ( ! empty( $dtt_id ) && ! isset( $tickets_sold[ 'datetime' ][ $dtt_id ] ) ) {
					EE_Error::add_error( __( "You've requested the amount of tickets sold for a given ticket and datetime, however there are no records for the datetime id you included.  Are you SURE that is a datetime related to this ticket?", "event_espresso" ), __FILE__, __FUNCTION__, __LINE__ );
					return $total;
				}
				return empty( $dtt_id ) ? $tickets_sold[ 'datetime' ] : $tickets_sold[ 'datetime' ][ $dtt_id ];
				break;
			default:
				return $total;
		}
	}



	/**
	 * This returns an array indexed by datetime_id for tickets sold with this ticket.
	 * @return EE_Ticket[]
	 */
	protected function _all_tickets_sold() {
		$datetimes = $this->get_many_related( 'Datetime' );
		$tickets_sold = array();
		if ( ! empty( $datetimes ) ) {
			foreach ( $datetimes as $datetime ) {
				$tickets_sold[ 'datetime' ][ $datetime->ID() ] = $datetime->get( 'DTT_sold' );
			}
		}
		//Tickets sold
		$tickets_sold[ 'ticket' ] = $this->sold();
		return $tickets_sold;
	}



	/**
	 * This returns the base price object for the ticket.
	 *
	 * @access public
	 * @param  bool $return_array whether to return as an array indexed by price id or just the object.
	 * @return EE_Price
	 */
	public function base_price( $return_array = FALSE ) {
		$_where = array( 'Price_Type.PBT_ID' => EEM_Price_Type::base_type_base_price );
		return $return_array ? $this->get_many_related( 'Price', array( $_where ) ) : $this->get_first_related( 'Price', array( $_where ) );
	}



	/**
	 * This returns ONLY the price modifiers for the ticket (i.e. no taxes or base price)
	 *
	 * @access public
	 * @return EE_Price[]
	 */
	public function price_modifiers() {
		$query_params = array( 0 => array( 'Price_Type.PBT_ID' => array( 'NOT IN', array( EEM_Price_Type::base_type_base_price, EEM_Price_Type::base_type_tax ) ) ) );
		return $this->prices( $query_params );
	}



	/**
	 * Gets all the prices that combine to form the final price of this ticket
	 * @param array $query_params like EEM_Base::get_all
	 * @return EE_Price[]
	 */
	public function prices( $query_params = array() ) {
		return $this->get_many_related( 'Price', $query_params );
	}



	/**
	 * Gets all the ticket applicabilities (ie, relations between datetimes and tickets)
	 * @param array $query_params see EEM_Base::get_all()
	 * @return EE_Datetime_Ticket
	 */
	public function datetime_tickets( $query_params = array() ) {
		return $this->get_many_related( 'Datetime_Ticket', $query_params );
	}



	/**
	 * Gets all the datetimes from the db ordered by DTT_order
	 * @param boolean $show_expired
	 * @param boolean $show_deleted
	 * @return EE_Datetime[]
	 */
	public function datetimes_ordered( $show_expired = TRUE, $show_deleted = FALSE ) {
		return EEM_Datetime::instance( $this->_timezone )->get_datetimes_for_ticket_ordered_by_DTT_order( $this->ID(), $show_expired, $show_deleted );
	}



	/**
	 * Gets ID
	 * @return string
	 */
	function ID() {
		return $this->get( 'TKT_ID' );
	}




	/**
	 * get the author of the ticket.
	 *
	 * @since 4.5.0
	 *
	 * @return int
	 */
	public function wp_user() {
		return $this->get('TKT_wp_user');
	}



	/**
	 * Gets the template for the ticket
	 * @return EE_Ticket_Template
	 */
	public function template() {
		return $this->get_first_related( 'Ticket_Template' );
	}



	/**
	 * Simply returns an array of EE_Price objects that are taxes.
	 * @return EE_Price[]
	 */
	public function get_ticket_taxes_for_admin() {
		return EE_Taxes::get_taxes_for_admin();
	}



	/**
	 * @return bool
	 */
	public function ticket_price() {
		return $this->get( 'TKT_price' );
	}



	/**
	 * @return mixed
	 */
	public function pretty_price() {
		return $this->get_pretty( 'TKT_price' );
	}



	/**
	 * @return bool
	 */
	public function is_free() {
		return $this->get_ticket_total_with_taxes() == 0 ? TRUE : FALSE;
	}



	/**
	 * get_ticket_total_with_taxes
	 * @param bool $no_cache
	 * @return float
	 */
	public function get_ticket_total_with_taxes( $no_cache = FALSE ) {
		if ( ! isset( $this->_ticket_total_with_taxes ) || $no_cache ) {
			$this->_ticket_total_with_taxes = $this->get_ticket_subtotal() + $this->get_ticket_taxes_total_for_admin();
		}
		return (float)$this->_ticket_total_with_taxes;
	}



	public function ensure_TKT_Price_correct() {
		$this->set( 'TKT_price', EE_Taxes::get_subtotal_for_admin( $this ) );
		$this->save();
	}



	/**
	 * @return float
	 */
	public function get_ticket_subtotal() {
		return EE_Taxes::get_subtotal_for_admin( $this );
	}



	/**
	 * Returns the total taxes applied to this ticket
	 * @return float
	 */
	public function get_ticket_taxes_total_for_admin() {
		return EE_Taxes::get_total_taxes_for_admin( $this );
	}



	/**
	 * Sets name
	 * @param string $name
	 * @return boolean
	 */
	function set_name( $name ) {
		$this->set( 'TKT_name', $name );
	}



	/**
	 * Gets description
	 * @return string
	 */
	function description() {
		return $this->get( 'TKT_description' );
	}



	/**
	 * Sets description
	 * @param string $description
	 * @return boolean
	 */
	function set_description( $description ) {
		$this->set( 'TKT_description', $description );
	}



	/**
	 * Gets start_date
	 * @param string $dt_frmt
	 * @param string $tm_frmt
	 * @return string
	 */
	function start_date( $dt_frmt = '', $tm_frmt = '' ) {
		return $this->_get_datetime( 'TKT_start_date', $dt_frmt, $tm_frmt );
	}



	/**
	 * Sets start_date
	 * @param string $start_date
	 * @return void
	 */
	function set_start_date( $start_date ) {
		$this->_set_date_time( 'B', $start_date, 'TKT_start_date' );
	}



	/**
	 * Gets end_date
	 * @param string $dt_frmt
	 * @param string $tm_frmt
	 * @return string
	 */
	function end_date( $dt_frmt = '', $tm_frmt = '' ) {
		return $this->_get_datetime( 'TKT_end_date', $dt_frmt, $tm_frmt );
	}



	/**
	 * Sets end_date
	 * @param string $end_date
	 * @return void
	 */
	function set_end_date( $end_date ) {
		$this->_set_date_time( 'B', $end_date, 'TKT_end_date' );
	}



	/**
	 * Sets sell until time
	 *
	 * @since 4.5.0
	 *
	 * @param string $time a string representation of the sell until time (ex 9am or 7:30pm)
	 */
	function set_end_time( $time ) {
		$this->_set_time_for( $time, 'TKT_end_date' );
	}



	/**
	 * Sets min
	 * @param int $min
	 * @return boolean
	 */
	function set_min( $min ) {
		$this->set( 'TKT_min', $min );
	}



	/**
	 * Gets max
	 * @return int
	 */
	function max() {
		return $this->get( 'TKT_max' );
	}



	/**
	 * Sets max
	 * @param int $max
	 * @return boolean
	 */
	function set_max( $max ) {
		$this->set( 'TKT_max', $max );
	}



	/**
	 * Sets price
	 * @param float $price
	 * @return boolean
	 */
	function set_price( $price ) {
		$this->set( 'TKT_price', $price );
	}



	/**
	 * Gets sold
	 * @return int
	 */
	function sold() {
		return $this->get_raw( 'TKT_sold' );
	}



	/**
	 * increments sold by amount passed by $qty
	 * @param int $qty
	 * @return boolean
	 */
	function increase_sold( $qty = 1 ) {
		$sold = $this->sold() + $qty;
		$this->_increase_sold_for_datetimes( $qty );
		return $this->set_sold( $sold );
	}



	/**
	 * Increases sold on related datetimes
	 * @param int $qty
	 * @return boolean
	 */
	protected function _increase_sold_for_datetimes( $qty = 1 ) {
		$datetimes = $this->datetimes();
		if ( is_array( $datetimes ) ) {
			foreach ( $datetimes as $datetime ) {
				if ( $datetime instanceof EE_Datetime ) {
					$datetime->increase_sold( $qty );
					$datetime->save();
				}
			}
		}
	}



	/**
	 * Sets sold
	 * @param int $sold
	 * @return boolean
	 */
	function set_sold( $sold ) {
		// sold can not go below zero
		$sold = max( 0, $sold );
		$this->set( 'TKT_sold', $sold );
	}



	/**
	 * decrements (subtracts) sold by amount passed by $qty
	 * @param int $qty
	 * @return boolean
	 */
	function decrease_sold( $qty = 1 ) {
		$sold = $this->sold() - $qty;
		$this->_decrease_sold_for_datetimes( $qty );
		return $this->set_sold( $sold );
	}



	/**
	* Decreases sold on related datetimes
	*
	* @param int $qty
	* @return boolean
	*/
	protected function _decrease_sold_for_datetimes( $qty = 1 ) {
		$datetimes = $this->datetimes();
		if ( is_array( $datetimes ) ) {
			foreach ( $datetimes as $datetime ) {
				if ( $datetime instanceof EE_Datetime ) {
					$datetime->decrease_sold( $qty );
					$datetime->save();
				}
			}
		}
	}



	/**
	 * Gets ticket quantity
	 *
	 * @param string $context 	ticket quantity is somewhat subjective depending on the exact information sought
	 *                         	therefore $context can be one of three values: '', 'reg_limit', or 'saleable'
	 *                         	'' (default) quantity is the actual db value for TKT_qty, unaffected by other objects
	 *                         	REG LIMIT: caps qty based on DTT_reg_limit for ALL related datetimes
	 *                         	SALEABLE: also considers datetime sold and returns zero if ANY DTT is sold out, and
	 *                         	is therefore the truest measure of tickets that can be purchased at the moment
	 *
	 * @return int
	 */
	function qty( $context = '' ) {
		switch ( $context ) {
			case 'reg_limit' :
				return $this->real_quantity_on_ticket();
			case 'saleable' :
				return $this->real_quantity_on_ticket( 'saleable' );
			default:
				return $this->get_raw( 'TKT_qty' );
		}
	}



	/**
	 * Gets ticket quantity
	 *
	 * @param string $context     ticket quantity is somewhat subjective depending on the exact information sought
	 *                            therefore $context can be one of two values: 'reg_limit', or 'saleable'
	 *                            REG LIMIT: caps qty based on DTT_reg_limit for ALL related datetimes
	 *                            SALEABLE: also considers datetime sold and returns zero if ANY DTT is sold out, and
	 *                            is therefore the truest measure of tickets that can be purchased at the moment
	 *
	 * @return int
	 */
	function real_quantity_on_ticket( $context = 'reg_limit' ) {
		// start with the original db value for ticket quantity
		$raw = $this->get_raw( 'TKT_qty' );
		// return immediately if it's zero
		if ( $raw === 0 ) {
			return $raw;
		}
		// ensure qty doesn't exceed raw value for THIS ticket
		$qty = min( EE_INF, $raw );
		// NOW that we know the  maximum number of tickets available for the ticket
		// we need to calculate the maximum number of tickets available for the datetime
		// without really factoring this ticket into the calculations
		$datetimes = $this->datetimes();
		foreach ( $datetimes as $datetime ) {
			if ( $datetime instanceof EE_Datetime ) {
				// initialize with no restrictions for each datetime
				// but adjust datetime qty based on datetime reg limit
				$datetime_qty = min( EE_INF, $datetime->reg_limit() );
				// if we want the actual saleable amount, then we need to consider OTHER ticket sales
				// for this datetime, that do NOT include sales for this ticket (so we add THIS ticket's sales back in)
				if ( $context == 'saleable' ) {
					$datetime_qty = max( $datetime_qty - $datetime->sold() + $this->sold(), 0 );
					$datetime_qty = ! $datetime->sold_out() ? $datetime_qty : 0;
				}
				$qty = min( $datetime_qty, $qty );
			}

		}
		// we need to factor in the details for this specific ticket
		if ( $qty > 0 && $context == 'saleable' ) {
			// and subtract the sales for THIS ticket
			$qty = max( $qty - $this->sold(), 0 );
			//echo '&nbsp; $qty: ' . $qty . "<br />";
		}
		//echo '$qty: ' . $qty . "<br />";
		return $qty;
	}



	/**
	 * Sets qty - IMPORTANT!!! Does NOT allow QTY to be set higher than the lowest reg limit of any related datetimes
	 *
	 * @param int  $qty
	 * @return bool
	 * @throws \EE_Error
	 */
	function set_qty( $qty ) {
		$datetimes = $this->datetimes();
		foreach ( $datetimes as $datetime ) {
			if ( $datetime instanceof EE_Datetime ) {
				$qty = min( $qty, $datetime->reg_limit() );
			}
		}
		$this->set( 'TKT_qty', $qty );
	}



	/**
	 * Gets uses
	 * @return int
	 */
	function uses() {
		return $this->get( 'TKT_uses' );
	}



	/**
	 * Sets uses
	 * @param int $uses
	 * @return boolean
	 */
	function set_uses( $uses ) {
		$this->set( 'TKT_uses', $uses );
	}



	/**
	 * returns whether ticket is required or not.
	 * @return boolean
	 */
	public function required() {
		return $this->get( 'TKT_required' );
	}



	/**
	 * sets the TKT_required property
	 * @param boolean $required
	 * @return boolean
	 */
	public function set_required( $required ) {
		$this->set( 'TKT_required', $required );
	}



	/**
	 * Gets taxable
	 * @return boolean
	 */
	function taxable() {
		return $this->get( 'TKT_taxable' );
	}



	/**
	 * Sets taxable
	 * @param boolean $taxable
	 * @return boolean
	 */
	function set_taxable( $taxable ) {
		$this->set( 'TKT_taxable', $taxable );
	}



	/**
	 * Gets is_default
	 * @return boolean
	 */
	function is_default() {
		return $this->get( 'TKT_is_default' );
	}



	/**
	 * Sets is_default
	 * @param boolean $is_default
	 * @return boolean
	 */
	function set_is_default( $is_default ) {
		$this->set( 'TKT_is_default', $is_default );
	}



	/**
	 * Gets order
	 * @return int
	 */
	function order() {
		return $this->get( 'TKT_order' );
	}



	/**
	 * Sets order
	 * @param int $order
	 * @return boolean
	 */
	function set_order( $order ) {
		$this->set( 'TKT_order', $order );
	}



	/**
	 * Gets row
	 * @return int
	 */
	function row() {
		return $this->get( 'TKT_row' );
	}



	/**
	 * Sets row
	 * @param int $row
	 * @return boolean
	 */
	function set_row( $row ) {
		$this->set( 'TKT_row', $row );
	}



	/**
	 * Gets deleted
	 * @return boolean
	 */
	function deleted() {
		return $this->get( 'TKT_deleted' );
	}



	/**
	 * Sets deleted
	 * @param boolean $deleted
	 * @return boolean
	 */
	function set_deleted( $deleted ) {
		$this->set( 'TKT_deleted', $deleted );
	}



	/**
	 * Gets parent
	 * @return int
	 */
	function parent_ID() {
		return $this->get( 'TKT_parent' );
	}



	/**
	 * Sets parent
	 * @param int $parent
	 * @return boolean
	 */
	function set_parent_ID( $parent ) {
		$this->set( 'TKT_parent', $parent );
	}



	/**
	 * Gets a string which is handy for showing in gateways etc that describes the ticket.
	 * @return string
	 */
	function name_and_info() {
		$times = array();
		foreach ( $this->datetimes() as $datetime ) {
			$times[] = $datetime->start_date_and_time();
		}
		return $this->name() . " @ " . implode( ", ", $times ) . " for " . $this->pretty_price();
	}



	/**
	 * Gets name
	 * @return string
	 */
	function name() {
		return $this->get( 'TKT_name' );
	}



	/**
	 * Gets price
	 * @return float
	 */
	function price() {
		return $this->get( 'TKT_price' );
	}



	/**
	 * Gets all the registrations for this ticket
	 * @param array $query_params like EEM_Base::get_all's
	 * @return EE_Registration[]
	 */
	public function registrations( $query_params = array() ) {
		return $this->get_many_related( 'Registration', $query_params );
	}



	/**
	 * Updates the TKT_sold attribute (and saves) based on the number of APPROVED registrations for this ticket.
	 * into account
	 * @return int
	 */
	public function update_tickets_sold() {
		$count_regs_for_this_ticket = $this->count_registrations( array( array( 'STS_ID' => EEM_Registration::status_id_approved, 'REG_deleted' => 0 ) ) );
		$this->set_sold( $count_regs_for_this_ticket );
		$this->save();
		return $count_regs_for_this_ticket;
	}



	/**
	 * Counts the registrations for this ticket
	 * @param array $query_params like EEM_Base::get_all's
	 * @return int
	 */
	public function count_registrations( $query_params = array() ) {
		return $this->count_related('Registration', $query_params);
	}



	/**
	 * Implementation for EEI_Has_Icon interface method.
	 * @see EEI_Visual_Representation for comments
	 * @return string
	 */
	public function get_icon() {
		return '<span class="dashicons dashicons-tickets-alt"></span>';
	}



	/**
	 * Implementation of the EEI_Event_Relation interface method
	 * @see EEI_Event_Relation for comments
	 * @return EE_Event
	 */
	public function get_related_event() {
		//get one datetime to use for getting the event
		$datetime = $this->first_datetime();
		if ( ! $datetime instanceof \EE_Datetime ) {
			throw new UnexpectedEntityException(
				$datetime,
				'EE_Datetime',
				sprintf(
					__( "The ticket (%s) is not associated with any valid datetimes.", "event_espresso" ),
					$datetime->name()
				)
			);
		}
		$event = $datetime->event();
		if ( ! $event instanceof \EE_Event ) {
			throw new UnexpectedEntityException(
				$event,
				'EE_Event',
				sprintf(
					__( "The ticket (%s) is not associated with a valid event.", "event_espresso" ),
					$this->name()
				)
			);
		}
		return $event;
	}


	/**
	 * Implementation of the EEI_Event_Relation interface method
	 * @see EEI_Event_Relation for comments
	 * @return string
	 */
	public function get_event_name() {
		$event = $this->get_related_event();
		return $event instanceof EE_Event ? $event->name() : '';
	}


	/**
	 * Implementation of the EEI_Event_Relation interface method
	 * @see EEI_Event_Relation for comments
	 * @return int
	 */
	public function get_event_ID() {
		$event = $this->get_related_event();
		return $event instanceof EE_Event ? $event->ID() : 0;
	}


} //end EE_Ticket class
