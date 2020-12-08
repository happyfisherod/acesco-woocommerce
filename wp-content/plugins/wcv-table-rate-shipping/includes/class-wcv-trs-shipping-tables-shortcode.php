<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WCV_TRS_Shipping_Tables_Shortcode {

	/**
	 * Singleton class instance.
	 */
	protected static $_instance = null;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return WCV_TRS_Shipping_Tables_Shortcode
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Prevents deserialization of the class.
	 */
	public function __wakeup() {
	}

	/**
	 * Prevents cloning of the class.
	 */
	public function __clone() {
	}

	/**
	 * WCV_TRS_Shipping_Tables_Shortcode constructor.
	 */
	public function __construct() {
		add_shortcode( 'wcv_trs_shipping_tables', array( $this, 'get_html' ) );
	}

	/**
	 * Generates the HTML for the [wcv_trs_shipping_tables] shortcode.
	 *
	 * @return string
	 */
	public function get_html() {
		if ( ! wcv_trs_is_user_vendor( get_current_user_id() ) ) {
			return __( 'You do not have permission to access this page.', 'wcv-table-rate-shipping' );
		} else {
			if ( wp_style_is( 'select2-css', 'registered' ) ) {
				wp_enqueue_style( 'select2-css' );
			} elseif ( wp_style_is( 'select2', 'registered' ) ) {
				wp_enqueue_style( 'select2' );
			}

			$table_list = new WCV_TRS_Tables_List( get_current_user_id() );

			return $table_list->output( 'dashboard', false );
		}
	}

}

WCV_TRS_Shipping_Tables_Shortcode::instance();
