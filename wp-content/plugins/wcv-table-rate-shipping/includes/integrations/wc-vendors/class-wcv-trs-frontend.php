<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WCV_TRS_Frontend
 *
 * Outputs the table management interface in the WC Vendors dashboard.
 */
class WCV_TRS_Frontend {

    /**
     * Constructor.
     *
     * Registers action hooks and filters.
     */
    public function __construct() {
        add_action( 'wcvendors_after_links', array( $this, 'add_shipping_settings_link_old' ) );
        add_filter( 'wcv_dashboard_nav_items', array( $this, 'add_shipping_settings_link' ) );
        add_filter( 'wcv_store_tabs', array( $this, 'add_vendor_menu_item' ), 20 );
	    add_action( 'wcv_form_submit_before_store_save_button', array( $this, 'output_shipping_tab' ) );
    }

    /**
     * Adds a "Shipping Settings" link to free vendor dashboard menu (WC Vendors < 2.1.5).
     */
    public function add_shipping_settings_link_old() {
        $table_page_id = get_option( 'wcv_trs_shipping_tables_page' );

        if ( $table_page_id > 0 && ( $page_url = get_permalink( $table_page_id ) ) ) {
            printf(
                '<a href="%s" class="button">%s</a>',
                $page_url,
                __( 'Shipping Settings', 'wcv-table-rate-shipping' )
            );
        }
    }

	/**
	 * Adds a "Shipping Settings" link to the free vendor dashboard menu (WC Vendors 2.1.5+).
	 *
	 * @param array $nav_items
	 *
	 * @return array
	 */
	public function add_shipping_settings_link( $nav_items ) {
		$table_page_id = get_option( 'wcv_trs_shipping_tables_page' );

		if ( $table_page_id > 0 && ( $page_url = get_permalink( $table_page_id ) ) ) {
			$nav_items['shipping_settings'] = [
				'url'   => $page_url,
				'label' => __( 'Shipping Settings', 'wcv-table-rate-shipping' ),
			];
		}

		return $nav_items;
	}

    /**
     * Add "Shipping" tab on vendor settings page after "Store" tab.
     *
     * @param array $tabs Current tabs.
     *
     * @return array $tabs
     */
    public function add_vendor_menu_item( $tabs ) {
        $user_id = get_current_user_id();

        if ( WCV_Vendors::is_vendor( $user_id ) ) // PREVENT TAB FROM BEING DISPLAYED DURING SIGN UP
        {
            // Remove existing tab
            if ( isset( $tabs['shipping'] ) ) {
                unset( $tabs['shipping'] );
            }

            // Insert new tab
            $new_tab = array(
                'shipping-custom' => array(
                    'label'  => 'Shipping',
                    'target' => 'shipping-custom',
                    'class'  => array(),
                ),
            );

            $tabs = array_slice( $tabs, 0, 1, true ) + $new_tab + array_slice( $tabs, 1, null, true );
        }

        return $tabs;
    }

    /**
     * Outputs the markup for the Pro Shipping tab.
     */
    public function output_shipping_tab() {
	    echo '<div class="tabs-content hide-all" id="shipping-custom">';

    	$table_list = new WCV_TRS_Tables_List( get_current_user_id() );
    	$table_list->output( 'dashboard' );

	    echo '</div>';
    }

}

new WCV_TRS_Frontend();