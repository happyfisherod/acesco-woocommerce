<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WCV_TRS_Dokan_Frontend
 *
 * Outputs the table management interface in the Dokan vendor dashboard.
 */
class WCV_TRS_Dokan_Frontend {

	/**
	 * Constructor.
	 *
	 * Registers action hooks and filters.
	 */
	public function __construct() {
		add_filter( 'dokan_get_dashboard_settings_nav', array( $this, 'add_settings_nav_link' ), 15 );
		add_action( 'dokan_settings_content_area_header', array( $this, 'render_shipping_settings_notice' ), 25 );

		if ( ! isset( $_GET['dokan'] ) ) {
			add_action( 'dokan_enqueue_scripts', array( $this, 'dequeue_pro_shipping_scripts' ) );
			add_action( 'template_redirect', array( $this, 'hide_default_settings_tab_content' ) );
			add_action( 'dokan_render_settings_content', array( $this, 'load_settings_tab_content' ), 5 );
			add_filter( 'dokan_dashboard_settings_heading_title', array( $this, 'set_settings_heading_title' ), 15, 2 );
			add_filter( 'dokan_dashboard_settings_helper_text', array( $this, 'set_settings_helper_text' ), 15, 2 );
		}

		if ( apply_filters( 'trs_show_dokan_policy_fields', true ) ) {
			add_filter( 'trs_table_list_view_path', array( $this, 'filter_table_list_view_path' ) );
			add_action( 'wp_ajax_dokan_settings', array( $this, 'ajax_save_policy_fields' ), 5 );
		}
	}

	/**
	 * Adds a "Shipping" link to the Dokan settings submenu.
	 *
	 * @param array $links
	 *
	 * @return array
	 */
	public function add_settings_nav_link( $links ) {
		$disable_woo_shipping = get_option( 'woocommerce_ship_to_countries' );

		if ( ! isset( $links['shipping'] ) && 'disabled' !== $disable_woo_shipping ) {
			$links['shipping'] = [
				'title'      => __( 'Shipping', 'dokan' ),
				'icon'       => '<i class="fa fa-truck"></i>',
				'url'        => dokan_get_navigation_url( 'settings/shipping' ),
				'pos'        => 70,
				'permission' => 'dokan_view_store_shipping_menu'
			];
		}

		return $links;
	}

	/**
	 * Dequeues the scripts and styles used by the Dokan Pro shipping system.
	 */
	public function dequeue_pro_shipping_scripts() {
		wp_dequeue_style( 'dokan-vue-bootstrap' );
		wp_dequeue_style( 'dokan-pro-vue-frontend-shipping' );
		wp_dequeue_script( 'dokan-pro-vue-frontend-shipping' );
	}

	/**
	 * Hides the default Shipping settings tab content.
	 */
	public function hide_default_settings_tab_content() {
		if ( 'shipping' !== get_query_var( 'settings' ) ) {
			return;
		}
		
		global $wp_filter;

		if ( isset( $wp_filter['dokan_render_settings_content'] ) ) {
			foreach ( $wp_filter['dokan_render_settings_content']->callbacks as $priority => $callbacks ) {
				foreach ( $callbacks as $callback ) {
					$func = $callback['function'];
					if ( ! is_array( $func ) ) {
						continue;
					}
					list( $object, $method ) = $func;
					if (
						( is_a( $object, 'WeDevs\DokanPro\Settings' ) || is_a( $object, 'Dokan_Pro_Settings' ) )
						&& 'load_settings_content' === $method
					) {
						remove_action( 'dokan_render_settings_content', $func );
						break 2;
					}
				}
			}
		}
	}

	/**
	 * Outputs the markup for the Shipping settings tab.
	 *
	 * @param array $query_vars
	 */
	public function load_settings_tab_content( $query_vars ) {
		if ( isset( $query_vars['settings'] ) && $query_vars['settings'] == 'shipping' ) {
			if ( ! current_user_can( 'dokan_view_store_shipping_menu' ) ) {
				dokan_get_template_part( 'global/dokan-error', '', array(
					'deleted' => false,
					'message' => __( 'You have no permission to view this page', 'wcv-table-rate-shipping' )
				) );
			} else {
				$disable_woo_shipping = get_option( 'woocommerce_ship_to_countries' );

				if ( 'disabled' == $disable_woo_shipping ) {
					dokan_get_template_part( 'global/dokan-error', '', array(
						'deleted' => false,
						'message' => __( 'Shipping functionality is currently disabled by site owner', 'wcv-table-rate-shipping' )
					) );
				} else {
					$table_list = new WCV_TRS_Tables_List( get_current_user_id() );
					$table_list->output( 'dashboard' );
				}
			}
		}
	}

	/**
	 * Sets the title for the Shipping settings page.
	 *
	 * @param string $title
	 * @param string $page
	 *
	 * @return string
	 */
	public function set_settings_heading_title( $title, $page ) {
		if ( 'shipping' === $page ) {
			$title = __( 'Shipping Settings', 'wcv-table-rate-shipping' );
		}

		return $title;
	}

	/**
	 * Sets the helper text for the Shipping settings page.
	 *
	 * @param string $help_text
	 * @param string $page
	 *
	 * @return string
	 */
	public function set_settings_helper_text( $help_text, $page ) {
		if ( 'shipping' === $page ) {
			$help_text = '';
		}

		return $help_text;
	}

	/**
	 * Renders a notice linking the user to the TRS or Dokan shipping settings.
	 */
	public function render_shipping_settings_notice() {
		$is_shipping_settings_page = 'shipping' === get_query_var( 'settings' );

		if ( ! apply_filters( 'trs_display_dokan_settings_notice', $is_shipping_settings_page ) ) {
			return;
		}

		if ( isset( $_GET['dokan'] ) ) {
			// Link to TRS settings page
			$message_text = sprintf(
				__( 'Ready to set up table rate shipping? <a href="%1$s">Click here</a>', 'wcv-table-rate-shipping' ),
				dokan_get_navigation_url( 'settings/shipping' )
			);
		} else {
			// Link to Dokan settings page
			$message_text = sprintf(
				__( 'Looking for your old shipping settings? <a href="%1$s">Click here</a>', 'wcv-table-rate-shipping' ),
				add_query_arg( 'dokan', '1', dokan_get_navigation_url( 'settings/shipping' ) )
			);
		}

		dokan_get_template_part( 'global/dokan-help', '', array( 'help_text' => $message_text ) );
	}

	/**
	 * Changes the path for the table list view when the policy fields are enabled.
	 *
	 * @return string Path to html-shipping-table-list.php view.
	 */
	public function filter_table_list_view_path() {
		return __DIR__ . '/templates/html-shipping-table-list.php';
	}

	/**
	 * Saves the shipping and return policy fields.
	 */
	public function ajax_save_policy_fields() {
		if ( ! isset( $_POST['dokan_update_trs_policies'], $_POST['_wpnonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'dokan_trs_policies_nonce' ) ) {
			wp_send_json_error( __( 'Are you cheating?', 'wcv-table-rate-shipping' ) );
		}

		$user_id         = dokan_get_current_user_id();
		$processing_time = isset( $_POST['processing_time'] ) ? absint( $_POST['processing_time'] ) : '';
		$shipping_policy = isset( $_POST['shipping_policy'] ) ? sanitize_text_field( $_POST['shipping_policy'] ) : '';
		$refund_policy   = isset( $_POST['refund_policy'] ) ? sanitize_text_field( $_POST['refund_policy'] ) : '';

		update_user_meta( $user_id, '_dps_pt', $processing_time );
		update_user_meta( $user_id, '_dps_ship_policy', $shipping_policy );
		update_user_meta( $user_id, '_dps_refund_policy', $refund_policy );

		$response = array(
			'msg' => __( 'Settings saved successfully', 'wcv-table-rate-shipping' ),
		);

		wp_send_json_success( $response );
	}

}

new WCV_TRS_Dokan_Frontend();
