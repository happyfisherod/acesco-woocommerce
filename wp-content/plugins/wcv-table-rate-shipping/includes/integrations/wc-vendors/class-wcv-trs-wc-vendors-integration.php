<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WCV_TRS_WC_Vendors_Integration {

	/**
	 * Singleton class instance.
	 */
	protected static $_instance = null;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return WCV_TRS_WC_Vendors_Integration
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
	 * WCV_TRS_WC_Vendors_Integration constructor.
	 */
	public function __construct() {
		require_once 'class-wcv-trs-commissions.php';

		add_action( 'wcv_trs_init', array( $this, 'init' ) );
	}

	/**
	 * Runs when the TRS shipping method is enabled to initialize the integration.
	 */
	public function init() {
		$this->init_submodules();
		$this->add_hooks();
	}

	/**
	 * Initializes the integration's submodules.
	 */
	private function init_submodules() {
		require_once 'class-wcv-trs-frontend.php';
	}

	/**
	 * Adds the integration's hooks.
	 */
	private function add_hooks() {
		add_action( 'wcv_trs_install', array( $this, 'create_edit_shipping_tables_page' ) );
		add_filter( 'wcv_trs_is_user_vendor', array( $this, 'is_user_vendor' ), 10, 2 );
		add_filter( 'wcv_trs_vendor_admin_page_slug', array( $this, 'set_admin_page_slug' ) );

		if ( $this->is_wcvendors_pro() ) {
			add_action( 'wcv_trs_install', array( $this, 'disable_pro_shipping_method' ) );
		}

		if ( ! $this->is_wcvendors_pro( '1.4.0', '>=' ) ) {
			add_filter( 'wcv_trs_split_cart_shipping_packages', '__return_true' );
			add_filter(
				'wcv_trs_vendor_shipping_package_title',
				array( $this, 'rename_vendor_shipping_package' ),
				10,
				4
			);
		}
	}

	/**
	 * Creates an "Edit Shipping Tables" page in the WC Vendors lite vendor dashboard on plugin installation.
	 */
	public function create_edit_shipping_tables_page() {
		$table_page_id = get_option( 'wcv_trs_shipping_tables_page' );

		if ( $table_page_id > 0 && get_post( $table_page_id ) ) {
			// Shipping tables page already exists. We're done here.
			return;
		}

		if ( version_compare( WCV_VERSION, '2.0', '<' ) ) {
			$vendor_page_id = WC_Vendors::$pv_options->get_option( 'vendor_dashboard_page' );
		} else {
			$vendor_page_id = get_option( 'wcvendors_vendor_dashboard_page_id' );
		}

		if ( $vendor_page_id < 0 || ! get_post( $vendor_page_id ) ) {
			// Parent page doesn't exist. Nothing we can do for now.
			return;
		}

		$_page = array(
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'post_author'    => 1,
			'post_name'      => 'shipping_tables',
			'post_title'     => __( 'Shipping Tables', 'wcv-table-rate-shipping' ),
			'post_content'   => '[wcv_trs_shipping_tables]',
			'post_parent'    => $vendor_page_id,
			'comment_status' => 'closed',
		);

		$insert_id = wp_insert_post( $_page );

		update_option( 'wcv_trs_shipping_tables_page', $insert_id );
	}

	/**
	 * Disables the WC Vendors Pro "Vendor Shipping" method on plugin installation.
	 */
	public function disable_pro_shipping_method() {
		$settings = get_option( 'woocommerce_wcv_pro_vendor_shipping_settings' );

		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		$settings['enabled'] = 'no';

		update_option( 'woocommerce_wcv_pro_vendor_shipping_settings', $settings );
	}

	/**
	 * Checks whether WC Vendors Pro is installed.
	 *
	 * @param string $ver Optional version to check for (default: '').
	 * @param string $operator Optional operator to use for version comparison (default: =).
	 *
	 * @return bool
	 */
	public function is_wcvendors_pro( $ver = '', $operator = '=' ) {
		global $wcvendors_pro;

		if ( ! isset( $wcvendors_pro ) ) {
			return false;
		}

		if ( $ver && $operator ) {
			return version_compare( $wcvendors_pro->get_version(), $ver, $operator );
		}

		return true;
	}

	/**
	 * Renames the cart shipping packages based on the vendor sold by.
	 *
	 * @param string $title Package title.
	 * @param int $count Package index.
	 * @param array $package Shipping package.
	 * @param string $vendor_sold_by Vendor name for package.
	 *
	 * @return string
	 *
	 * @see WCVendors_Pro_Shipping_Controller::rename_vendor_shipping_package()
	 */
	public function rename_vendor_shipping_package( $title, $count, $package, $vendor_sold_by ) {
		$title = sprintf(
			__( '%s Shipping', 'wcv-table-rate-shipping' ),
			WCV_Vendors::get_vendor_sold_by( $package['vendor_id'] )
		);

		return apply_filters( 'wcv_vendor_shipping_package_title', $title, $count, $package, $vendor_sold_by );
	}

	/**
	 * Checks whether a user is a vendor.
	 *
	 * @param bool $is_vendor Is the user a vendor?
	 * @param int $user_id User ID.
	 *
	 * @return bool
	 */
	public function is_user_vendor( $is_vendor, $user_id ) {
		return WCV_Vendors::is_vendor( $user_id );
	}

	/**
	 * Sets the parent slug for the Edit Shipping Tables page in WP admin.
	 *
	 * @return string
	 */
	public function set_admin_page_slug() {
		return 'wcv-vendor-shopsettings';
	}

}

WCV_TRS_WC_Vendors_Integration::instance();
