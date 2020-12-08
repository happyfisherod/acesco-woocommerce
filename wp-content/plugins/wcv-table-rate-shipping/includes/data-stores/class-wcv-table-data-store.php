<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WCV_Table_Data_Store
 *
 * Data store for vendor shipping tables.
 *
 * @author  Brett Porcelli
 * @package WCV_Table_Rate_Shipping
 */
class WCV_Table_Data_Store {

    /**
     * Method to create a new shipping table.
     *
     * @param WCV_TRS_Table $table
     */
    public function create( &$table ) {
        global $wpdb;

        $wpdb->insert(
            $wpdb->prefix . 'wcv_trs_tables',
            array(
                'user_id'      => $table->get_user_id(),
                'table_name'   => $table->get_table_name(),
                'table_order'  => $table->get_table_order(),
                'table_fee'    => $table->get_table_fee(),
                'table_method' => $table->get_table_method(),
                'is_default'   => $table->is_default(),
                'is_enabled'   => $table->is_enabled(),
            )
        );

        $table->set_id( $wpdb->insert_id );
        $this->save_rates( $table );
        $this->save_locations( $table );
        $table->apply_changes();

        WC_Cache_Helper::incr_cache_prefix( 'shipping_tables' );
        WC_Cache_Helper::get_transient_version( 'shipping', true );
    }

    /**
     * Update table in the database.
     *
     * @param WCV_TRS_Table $table
     */
    public function update( &$table ) {
        global $wpdb;

        if ( $table->get_id() ) {
            $wpdb->update(
                $wpdb->prefix . 'wcv_trs_tables',
                array(
                    'user_id'      => $table->get_user_id(),
                    'table_name'   => $table->get_table_name(),
                    'table_order'  => $table->get_table_order(),
                    'table_fee'    => $table->get_table_fee(),
                    'table_method' => $table->get_table_method(),
                    'is_default'   => $table->is_default(),
                    'is_enabled'   => $table->is_enabled(),
                ),
                array( 'table_id' => $table->get_id() )
            );
        }

        $this->save_rates( $table );
        $this->save_locations( $table );
        $table->apply_changes();

        WC_Cache_Helper::incr_cache_prefix( 'shipping_tables' );
        WC_Cache_Helper::get_transient_version( 'shipping', true );
    }

    /**
     * Method to read a shipping table from the database.
     *
     * @param WCV_TRS_Table $table
     */
    public function read( &$table ) {
        global $wpdb;

        if ( $table_data = $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wcv_trs_tables WHERE table_id = %d", $table->get_id() )
        ) ) {
            $table->set_id( $table_data->table_id );
            $table->set_user_id( $table_data->user_id );
            $table->set_table_name( $table_data->table_name );
            $table->set_table_order( $table_data->table_order );
            $table->set_table_fee( $table_data->table_fee );
            $table->set_table_method( $table_data->table_method );
            $table->set_is_enabled( $table_data->is_enabled );
            $this->read_locations( $table );
            $this->read_rates( $table );
            $table->set_object_read( true );
        }
    }

    /**
     * Deletes a shipping table from the database.
     *
     * @param  WCV_TRS_Table $table
     * @param  array $args Array of args to pass to the delete method.
     */
    public function delete( &$table, $args = array() ) {
        if ( $table->get_id() ) {
            global $wpdb;

            $wpdb->delete( $wpdb->prefix . 'wcv_trs_tables', array( 'table_id' => $table->get_id() ) );
            $wpdb->delete( $wpdb->prefix . 'wcv_trs_table_locations', array( 'table_id' => $table->get_id() ) );
            $wpdb->delete( $wpdb->prefix . 'wcv_trs_table_rates', array( 'table_id' => $table->get_id() ) );

            $table->set_id( null );

            WC_Cache_Helper::incr_cache_prefix( 'shipping_tables' );
            WC_Cache_Helper::get_transient_version( 'shipping', true );
        }
    }

    /**
     * Read table locations from database.
     *
     * @param WCV_TRS_Table $table
     */
    private function read_locations( &$table ) {
        global $wpdb;

        if ( $locations = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}wcv_trs_table_locations WHERE table_id = %d",
                $table->get_id()
            )
        ) ) {
            foreach ( $locations as $location ) {
                $table->add_location( $location->location_code, $location->location_type );
            }
        }
    }

    /**
     * Save table locations.
     *
     * @param WCV_TRS_Table $table
     */
    private function save_locations( &$table ) {
        $changed_props = array_keys( $table->get_changes() );
        if ( ! in_array( 'table_locations', $changed_props ) ) {
            return;
        }

        global $wpdb;

        // Delete existing locations
        $wpdb->delete( $wpdb->prefix . 'wcv_trs_table_locations', array( 'table_id' => $table->get_id() ) );

        // Save new locations
        foreach ( $table->get_table_locations( 'edit' ) as $location ) {
            $wpdb->insert(
                $wpdb->prefix . 'wcv_trs_table_locations',
                array(
                    'table_id'      => $table->get_id(),
                    'location_code' => $location->code,
                    'location_type' => $location->type,
                )
            );
        }
    }

    /**
     * Read table rates from database.
     *
     * @param WCV_TRS_Table $table
     */
    private function read_rates( &$table ) {
        global $wpdb;

        if ( $rates = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}wcv_trs_table_rates WHERE table_id = %d ORDER BY threshold ASC;",
                $table->get_id()
            )
        ) ) {
            foreach ( $rates as $rate ) {
                $table->add_rate( $rate->rate, $rate->threshold, $rate->is_percent );
            }
        }
    }

    /**
     * Save table rates.
     *
     * @param WCV_TRS_Table $table
     */
    private function save_rates( &$table ) {
        $changed_props = array_keys( $table->get_changes() );
        if ( ! in_array( 'table_rates', $changed_props ) ) {
            return;
        }

        global $wpdb;

        // Delete existing rates
        $wpdb->delete( $wpdb->prefix . 'wcv_trs_table_rates', array( 'table_id' => $table->get_id() ) );

        // Save new rates
        foreach ( $table->get_table_rates( 'edit' ) as $rate ) {
            $wpdb->insert(
                $wpdb->prefix . 'wcv_trs_table_rates',
                array(
                    'table_id'   => $table->get_id(),
                    'rate'       => $rate->rate,
                    'threshold'  => $rate->threshold,
                    'is_percent' => $rate->is_percent,
                )
            );
        }
    }

    /**
     * Returns an ordered list of tables for a vendor.
     *
     * @param int $vendor_id
     *
     * @return array An array of table objects.
     */
    public function get_tables( $vendor_id ) {
        global $wpdb;

        $query = <<<QUERY
SELECT * FROM {$wpdb->prefix}wcv_trs_tables
WHERE user_id = %d
ORDER BY table_order ASC;
QUERY;

        return $wpdb->get_results( $wpdb->prepare( $query, $vendor_id ) );
    }

    /**
     * Get the ID of the table to use for a given vendor and shipping location.
     *
     * Adapted from WC_Shipping_Zone_Data_Store::get_zone_id_from_package().
     *
     * @param int   $vendor_id Vendor user ID.
     * @param array $location  Array with keys 'country', 'state', and 'postcode'.
     *
     * @return int|null Table ID or null if no matching table is found.
     */
    public function get_table_id_from_location( $vendor_id, $location ) {
        global $wpdb;

        $country      = strtoupper( wc_clean( $location['country'] ) );
        $state        = strtoupper( wc_clean( $location['state'] ) );
        $continent    = strtoupper( wc_clean( WC()->countries->get_continent_code_for_country( $country ) ) );
        $postcode     = wc_normalize_postcode( wc_clean( $location['postcode'] ) );
        $use_defaults = ! get_user_meta( $vendor_id, 'wcv_trs_tables_saved', true );

        // Work out criteria for our table search.
        $order_by   = '';
        $criteria   = array();
        $criteria[] = $wpdb->prepare( "( ( location_type = 'country' AND location_code = %s )", $country );
        $criteria[] = $wpdb->prepare( "OR ( location_type = 'state' AND location_code = %s )", $country . ':' . $state );
        $criteria[] = $wpdb->prepare( "OR ( location_type = 'continent' AND location_code = %s )", $continent );
        $criteria[] = 'OR ( location_type IS NULL )';
        $criteria[] = "OR ( location_type = 'country' AND location_code = '*' ) )";

        if ( $use_defaults ) {
            $criteria[] = $wpdb->prepare( 'AND ( tables.user_id = %d OR tables.user_id = 0 )', $vendor_id );
            $order_by   = 'tables.is_default ASC,';  // Give default tables lower priority
        } else {
            $criteria[] = $wpdb->prepare( 'AND ( tables.user_id = %d )', $vendor_id );
        }

        $criteria[] = 'AND tables.is_enabled = 1';

        // Postcode range and wildcard matching.
        $postcode_locations = $wpdb->get_results( "SELECT table_id, location_code FROM {$wpdb->prefix}wcv_trs_table_locations WHERE location_type = 'postcode';" );

        if ( $postcode_locations ) {
            $table_ids_with_postcode_rules = array_map( 'absint', wp_list_pluck( $postcode_locations, 'table_id' ) );
            $matches                       = wc_postcode_location_matcher( $postcode, $postcode_locations, 'table_id', 'location_code', $country );
            $do_not_match                  = array_unique( array_diff( $table_ids_with_postcode_rules, array_keys( $matches ) ) );

            if ( ! empty( $do_not_match ) ) {
                $criteria[] = 'AND tables.table_id NOT IN (' . implode( ',', $do_not_match ) . ')';
            }
        }

        /**
         * Get shipping table criteria
         *
         * @since 2.0.0
         *
         * @param array $criteria Get table criteria.
         * @param array $location Location information.
         * @param array $postcode_locations Postcode range and wildcard matching.
         */
        $criteria = apply_filters( 'trs_get_table_criteria', $criteria, $location, $postcode_locations );

        // Get matching tables.
        return $wpdb->get_var(
            "SELECT tables.table_id FROM {$wpdb->prefix}wcv_trs_tables as tables
            LEFT OUTER JOIN {$wpdb->prefix}wcv_trs_table_locations as locations ON tables.table_id = locations.table_id AND location_type != 'postcode'
            WHERE " . implode( ' ', $criteria ) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
            . " ORDER BY {$order_by} tables.table_order ASC LIMIT 1"
        );
    }

    /*
     |--------------------------------------------------------------------------
     | Unused Methods
     |--------------------------------------------------------------------------
     */

    /**
     * Returns an array of meta for an object.
     *
     * @param WC_Data &$data
     *
     * @return array
     */
    public function read_meta( &$data ) {
        return [];
    }

    /**
     * Deletes meta based on meta ID.
     *
     * @param WC_Data &$data
     * @param object $meta (containing at least ->id)
     *
     * @return array
     */
    public function delete_meta( &$data, $meta ) {
        return [];
    }

    /**
     * Add new piece of meta.
     *
     * @param WC_Data &$data
     * @param object $meta (containing ->key and ->value)
     *
     * @return int meta ID
     */
    public function add_meta( &$data, $meta ) {
        return -1;
    }

    /**
     * Update meta.
     *
     * @param WC_Data &$data
     * @param object $meta (containing ->id, ->key and ->value)
     */
    public function update_meta( &$data, $meta ) {
    }

}
