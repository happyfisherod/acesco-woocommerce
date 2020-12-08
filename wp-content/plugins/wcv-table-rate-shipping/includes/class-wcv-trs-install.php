<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WCV_TRS_Install
 *
 * Handles plugin installation, activation, and deactivation.
 */
class WCV_TRS_Install {

    /**
     * Update hooks.
     *
     * @var array
     */
    private static $updates = [
        '1.2.0' => [
            'wcv_trs_update_120_rest_of_the_world_tables',
            'wcv_trs_update_120_wildcard_tables',
            'wcv_trs_update_120_set_tables_saved_flag',
        ],
        '1.3.1' => [
            'wcv_trs_update_131_items_meta',
        ],
    ];

    /**
     * Background updater.
     *
     * @var WCV_TRS_Updater
     */
    private static $updater = null;

    /**
     * Registers action hooks for the installer.
     */
    public static function init() {
        add_action( 'init', array( __CLASS__, 'init_background_updater' ), 5 );
        add_action( 'init', array( __CLASS__, 'check_version' ), 5 );
        add_action( 'admin_init', array( __CLASS__, 'trigger_update' ) );
    }

    /**
     * Initializes the background updater.
     */
    public static function init_background_updater() {
        if ( ! class_exists( 'WCV_TRS_Updater' ) ) {
            require 'class-wcv-trs-updater.php';
        }
        self::$updater = new WCV_TRS_Updater();
    }

    /**
     * Compares the current plugin version to the version stored in the database
     * and installs the plugin if necessary.
     */
    public static function check_version() {
        if ( ! defined( 'IFRAME_REQUEST' ) && get_option( 'wcv_trs_version' ) !== wcv_trs()->version ) {
            self::install();
        }
    }

    /**
     * Remove the data update notice.
     */
    private static function remove_update_notice() {
        if ( ! class_exists( 'WC_Admin_Notices' ) ) {
            require WC()->plugin_path() . '/includes/admin/class-wc-admin-notices.php';
        }

        WC_Admin_Notices::remove_notice( 'wcv_trs_update' );
    }

    /**
     * Installs Table Rate Shipping and queues a data update if required.
     */
    private static function install() {
        // Install
        self::create_tables();
        self::generate_default_tables();

        // Prompt user to run update if required
        self::remove_update_notice();

        $db_version = get_option( 'wcv_trs_version' );

        if ( false !== $db_version && version_compare( $db_version, max( array_keys( self::$updates ) ), '<' ) ) {
            WC_Admin_Notices::add_custom_notice( 'wcv_trs_update', self::update_notice() );
        } else {
            update_option( 'wcv_trs_version', wcv_trs()->version );
        }

        do_action( 'wcv_trs_install' );
    }

    /**
     * Starts the data update when the user clicks the "Update" button in the
     * dashboard.
     */
    public static function trigger_update() {
        if ( ! empty( $_GET['do_trs_update'] ) ) {
            self::update();

            WC_Admin_Notices::remove_notice( 'wcv_trs_update' );
            WC_Admin_Notices::add_custom_notice( 'wcv_trs_update', self::update_notice() );
        }
    }

    /**
     * Returns the content for the data update notice.
     *
     * @return string
     */
    private static function update_notice() {
        $db_version = get_option( 'wcv_trs_version', '1.0.0' );

        if ( version_compare( $db_version, max( array_keys( self::$updates ) ), '<' ) ) {
            if ( self::$updater->is_updating() || ! empty( $_GET['do_trs_update'] ) ) {
                return sprintf( '<p>%s</p>', __( 'Table Rate Shipping is updating.', 'wcv-table-rate-shipping' ) );
            } else {
                return sprintf(
                    '<p>%s</p> <p class="submit"><a href="%s" class="wc-update-now button-primary">%s</a></p>',
                    __( 'Table Rate Shipping requires an update.', 'wcv-table-rate-shipping' ),
                    esc_url( add_query_arg( 'do_trs_update', 'true', admin_url() ) ),
                    __( 'Run the update', 'wcv-table-rate-shipping' )
                );
            }
        }
    }

    /**
     * Runs required data updates in the background.
     */
    private static function update() {
        $current_db_version = get_option( 'wcv_trs_version', '1.0.0' );
        $logger             = new WC_Logger();

        foreach ( self::$updates as $version => $update_callbacks ) {
            if ( version_compare( $current_db_version, $version, '>=' ) ) {
                continue;
            }
            foreach ( $update_callbacks as $update_callback ) {
                $logger->add(
                    'trs_db_updates',
                    sprintf( 'Queuing %s - %s', $version, $update_callback )
                );
                self::$updater->push_to_queue( $update_callback );
            }
        }

        self::$updater->save()->dispatch();
    }

    /**
     * Deactivates the plugin.
     */
    public static function deactivate() {
        self::remove_update_notice();
    }

    /**
     * Create database tables.
     *
     * The following tables will be created:
     * - wcv_trs_tables
     * - wcv_trs_table_locations
     * - wcv_trs_table_rates
     */
    private static function create_tables() {
        global $wpdb;

        $wpdb->hide_errors();

        if ( ! function_exists( 'dbDelta' ) ) {
            require ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        $collate = '';

        if ( $wpdb->has_cap( 'collation' ) ) {
            $collate = $wpdb->get_charset_collate();
        }

        $schema = <<<DDL
CREATE TABLE {$wpdb->prefix}wcv_trs_tables (
    table_id bigint(20) NOT NULL auto_increment,
    table_name varchar(255) NOT NULL,
    table_order bigint(20) NOT NULL,
    table_fee varchar(10) NOT NULL DEFAULT '0',
    table_method varchar(20) NOT NULL,
    is_enabled tinyint(1) NOT NULL DEFAULT '1',
    is_default tinyint(1) NOT NULL DEFAULT '0',
    user_id int NOT NULL,
    PRIMARY KEY (table_id),
    KEY user_id (user_id)
) $collate;
CREATE TABLE {$wpdb->prefix}wcv_trs_table_locations (
    location_id bigint(20) NOT NULL auto_increment,
    table_id bigint(20) NOT NULL,
    location_code varchar(255) NOT NULL,
    location_type varchar(40) NOT NULL,
    PRIMARY KEY  (location_id),
    KEY table_id (table_id),
    KEY location_id (location_id),
    KEY location_type (location_type),
    KEY location_type_code (location_type(40),location_code(90))
) $collate;
CREATE TABLE {$wpdb->prefix}wcv_trs_table_rates (
    rate_id bigint(20) NOT NULL auto_increment,
    table_id bigint(20) NOT NULL,
    rate real NOT NULL,
    threshold real NOT NULL,
    is_percent tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (rate_id),
    KEY table_id (table_id)
) $collate;
DDL;

        dbDelta( $schema );
    }

    /**
     * Generate the default Domestic and International shipping tables.
     */
    private static function generate_default_tables() {
        $already_generated = get_option( 'wcv_trs_default_tables_generated' );

        if ( ! $already_generated ) {
            $countries    = WC()->countries->get_shipping_countries();
            $base_country = WC()->countries->get_base_country();

            if ( empty( $base_country ) ) {
                $base_country = 'US';
            }

            self::add_table(
                __( 'Domestic', 'wcv-table-rate-shipping' ),
                0,
                [ $base_country ]
            );

            if ( count( $countries ) > 1 ) {
                self::add_table(
                    __( 'International', 'wcv-table-rate-shipping' ),
                    1,
                    [ '*' ]
                );
            }

            update_option( 'wcv_trs_default_tables_generated', true );
        }
    }

    /**
     * Add a shipping table with the name `name` and countries `countries`.
     *
     * @param string $name
     * @param int $order
     * @param array $countries
     */
    private static function add_table( $name, $order, $countries ) {
        $table = new WCV_TRS_Table();

        $table->set_table_name( $name );
        $table->set_table_order( $order );
        $table->set_table_method( 'subtotal' );
        $table->set_is_enabled( true );
        $table->set_user_id( 0 );

        foreach ( $countries as $country_code ) {
            $table->add_location( $country_code, 'country' );
        }

        $table->save();
    }

}
