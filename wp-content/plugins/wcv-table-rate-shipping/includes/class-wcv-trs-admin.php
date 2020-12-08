<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WCV_TRS_Admin
 *
 * Outputs the table management interface in the WordPress dashboard.
 */
class WCV_TRS_Admin {

    /**
     * Constructor.
     *
     * Registers action hooks and filters.
     */
    public function __construct() {
        add_filter( 'user_row_actions', array( $this, 'add_edit_tables_action' ), 12, 2 );
        add_action( 'admin_menu', array( $this, 'add_pages' ), 20 );
    }

    /**
     * Add a "Edit Tables" row action in the WP > Users table.
     *
     * @param array $actions Array of action links to be displayed.
     * @param WP_User $user WP_User object for the currently-listed user.
     *
     * @return array $actions
     */
    public function add_edit_tables_action( $actions, $user ) {
        if ( wcv_trs_is_user_vendor( $user->ID ) && current_user_can( 'manage_options' ) ) {
            $page_url = add_query_arg(
                [
                    'page'    => 'edit_shipping_tables',
                    'user_id' => $user->ID,
                ],
                admin_url( 'user-edit.php' )
            );

            $actions = array_merge(
                [
                    'edit-shipping' => "<a href='$page_url'>" . __( 'Edit Tables', 'wcv-table-rate-shipping' ) . "</a>",
                ],
                $actions
            );
        }
        return $actions;
    }

    /**
     * Register admin pages.
     */
    public function add_pages() {
    	// Integrations can filter this and return a page slug to enable the Edit Shipping Tables screen in WP admin
	    $vendor_page_slug = apply_filters( 'wcv_trs_vendor_admin_page_slug', '' );

	    if ( $vendor_page_slug ) {
		    add_submenu_page(
			    $vendor_page_slug,
			    'Edit Shipping Tables',
			    'Shipping Tables',
			    'manage_product',
			    'vendor_edit_shipping_tables',
			    array( $this, 'output_table_list' )
		    );
	    }

        // For admins
        add_submenu_page(
            'user-edit.php', // Don't show page in menu
            'Edit Shipping Tables',
            'Edit Shipping Tables',
            'manage_options',
            'edit_shipping_tables',
            array( $this, 'output_table_list' )
        );
    }

    /**
     * Outputs a list of shipping tables for a user.
     */
    public function output_table_list() {
        $user_id = isset( $_REQUEST['user_id'] ) ? absint( $_REQUEST['user_id'] ) : get_current_user_id();

        if ( ! wcv_trs_is_user_vendor( $user_id ) || ! current_user_can( 'edit_user', $user_id ) ) {
            wp_die( __( 'You do not have permission to access this page.', 'wcv-table-rate-shipping' ) );
        }

        $table_list = new WCV_TRS_Tables_List( $user_id );
        $table_list->output( 'admin' );
    }

}

new WCV_TRS_Admin();
