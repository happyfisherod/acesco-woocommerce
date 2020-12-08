<?php



if ( ! defined( 'ABSPATH' ) ) {

	exit;

}



class WCV_TRS_Dokan_Integration {



	/**

	 * Singleton class instance.

	 */

	protected static $_instance = null;



	/**

	 * Returns the single instance of this class.

	 *

	 * @return WCV_TRS_Dokan_Integration

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

	 * WCV_TRS_Dokan_Integration constructor.

	 */

	public function __construct() {

		add_filter( 'wcv_trs_is_user_vendor', array( $this, 'is_user_vendor' ), 10, 2 );

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

		// Give MarketShip control over the frontend if enabled

		if ( ! function_exists( 'marketship' ) ) {

			require_once 'class-wcv-trs-dokan-frontend.php';

		}

	}



	/**

	 * Adds the integration's hooks.

	 */

	private function add_hooks() {

		add_filter( 'woocommerce_cart_shipping_packages', array( $this, 'ensure_shipping_package_vendor_id' ), 11 );

		add_filter( 'woocommerce_product_tabs', array( $this, 'rename_dokan_shipping_tab' ), 15 );



		if ( ! $this->is_dokan_pro() ) {

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

	 * Checks whether Dokan Pro is installed.

	 *

	 * @param string $ver Optional version to check for (default: '').

	 * @param string $operator Optional operator to use for version comparison (default: =).

	 *

	 * @return bool

	 */

	public function is_dokan_pro( $ver = '', $operator = '=' ) {

		if ( ! function_exists( 'dokan_pro' ) ) {

			return false;

		}



		if ( $ver && $operator ) {

			return version_compare( dokan_pro()->version, $ver, $operator );

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

	 */

	public function rename_vendor_shipping_package( $title, $count, $package, $vendor_sold_by ) {

		$vendor = dokan_get_vendor( $package['vendor_id'] );



		if ( $vendor ) {

			$title = sprintf( __( '%s Shipping', 'wcv-table-rate-shipping' ), $vendor->get_shop_name() );

		}



		return $title;

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

		if ( function_exists( 'dokan_is_user_seller' ) ) {

			$is_vendor = dokan_is_user_seller( $user_id );

		}



		return $is_vendor;

	}



	/**

	 * Ensures that every WooCommerce cart shipping package has a vendor_id set.

	 *

	 * @param array $packages

	 *

	 * @return array

	 */

	public function ensure_shipping_package_vendor_id( $packages ) {

		foreach ( $packages as &$package ) {

			if ( ! isset( $package['vendor_id'] ) ) {

				$package['vendor_id'] = isset( $package['seller_id'] ) ? $package['seller_id'] : 0;

			}

		}



		return $packages;

	}



	/**

	 * Renames the Dokan Shipping product tab to Policies.

	 *

	 * NOTE: It is assumed that this runs after Dokan registers the Shipping

	 * tab and before TRS registers its Shipping Rates tab.

	 *

	 * @param array $tabs Registered WooCommerce product tabs.

	 *

	 * @return array

	 */

	public function rename_dokan_shipping_tab( $tabs ) {

		if ( isset( $tabs['shipping'] ) ) {

			$new_tab              = array(

				'title'    => __( 'Tiempo de envío', 'wcv-table-rate-shipping' ),

				'priority' => 39, // Just before Shipping Rates tab (priority 40).

			);

			$tabs['trs_policies'] = array_merge( $tabs['shipping'], $new_tab );

			unset( $tabs['shipping'] );

		}



		return $tabs;

	}



}



WCV_TRS_Dokan_Integration::instance();

