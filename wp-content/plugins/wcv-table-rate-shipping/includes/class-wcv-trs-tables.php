<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WCV_TRS_Tables
 *
 * Facilitates the retrieval and management of vendor shipping tables.
 */
class WCV_TRS_Tables {

    /**
     * The vendor ID for default tables.
     */
    const DEFAULT_VENDOR = 0;

    /**
     * Register AJAX handlers.
     */
    public static function init() {
        add_action( 'wp_ajax_nopriv_wcv_trs_save_changes', array( __CLASS__, 'trs_save_changes' ) );
        add_action( 'wp_ajax_wcv_trs_save_changes', array( __CLASS__, 'trs_save_changes' ) );
	    add_action( 'wp_ajax_wcv_trs_keep_default_tables', array( __CLASS__, 'keep_default_tables' ) );
	    add_action( 'wp_ajax_wcv_trs_delete_default_tables', array( __CLASS__, 'delete_default_tables' ) );
    }

    /**
     * Returns a vendor's shipping tables formatted for output.
     *
     * If the vendor has not configured any shipping tables, the default
     * tables for the store are returned.
     *
     * @param int $vendor_id
     *
     * @return array
     */
    public static function get_tables( $vendor_id ) {
        $tables = [];

        $data_store   = new WCV_Table_Data_Store();
        $raw_tables   = $data_store->get_tables( $vendor_id );
        $use_defaults = empty( $raw_tables ) && ! get_user_meta( $vendor_id, 'wcv_trs_tables_saved', true );

        if ( $use_defaults ) {
            $raw_tables = $data_store->get_tables( self::DEFAULT_VENDOR );
        }

        foreach ( $raw_tables as $index => $raw_table ) {
            $table = new WCV_TRS_Table( $raw_table );

            // Reset IDs for default tables to avoid overwriting the original
            if ( $use_defaults && $table->is_default() ) {
                $table_id = 'new-' . $index;
            } else {
                $table_id = $table->get_id();
            }

            $table_data = array_merge(
                $table->get_data(),
                [
                    'table_id'                 => $table_id,
                    'formatted_table_location' => $table->get_formatted_location(),
                    'formatted_table_rates'    => $table->get_formatted_rates(),
                    'is_enabled'               => $table->get_formatted_enabled(),
                ]
            );

            // Postcodes should be stored under a separate key as a newline-separated string
            $locations = array();
            $postcodes = array();
            foreach ( $table_data['table_locations'] as $location ) {
                if ( in_array( $location->type, array( 'continent', 'country', 'state' ) ) ) {
                    $locations[] = $location;
                } elseif ( 'postcode' === $location->type ) {
                    $postcodes[] = $location->code;
                }
            }

            $table_data['table_locations'] = $locations;
            $table_data['table_postcodes'] = implode( "\n", $postcodes );

            $tables[ $table_id ] = $table_data;
        }

        return $tables;
    }

    /**
     * Finds the table to use for a given vendor and shipping location.
     *
     * @param int   $vendor_id Vendor user ID.
     * @param array $location  Array with keys 'country', 'state', and 'postcode'.
     *
     * @return WCV_TRS_Table|null
     */
    public static function get_table_for_location( $vendor_id, $location = array() ) {
        if ( empty( $location ) ) {
            // Use shop base as default location
            $location = array(
                'country'  => WC()->countries->get_base_country(),
                'state'    => WC()->countries->get_base_state(),
                'postcode' => WC()->countries->get_base_postcode(),
            );
        } else {
            $location_defaults = array(
                'country'  => '',
                'state'    => '',
                'postcode' => '',
            );
            $location          = wp_parse_args( $location, $location_defaults );
        }

        $country           = strtoupper( wc_clean( $location['country'] ) );
        $state             = strtoupper( wc_clean( $location['state'] ) );
        $postcode          = wc_normalize_postcode( wc_clean( $location['postcode'] ) );
        $key_suffix        = 'trs_shipping_table_' . md5( sprintf( '%d+%s+%s+%s', $vendor_id, $country, $state, $postcode ) );
        $cache_key         = WC_Cache_Helper::get_cache_prefix( 'shipping_tables' ) . $key_suffix;
        $matching_table_id = wp_cache_get( $cache_key, 'shipping_tables' );

        if ( false === $matching_table_id ) {
            $data_store        = new WCV_Table_Data_Store();
            $matching_table_id = $data_store->get_table_id_from_location( $vendor_id, $location );
            wp_cache_set( $cache_key, $matching_table_id, 'shipping_tables' );
        }

        return ! is_null( $matching_table_id ) ? new WCV_TRS_Table( $matching_table_id ) : null;
    }

    /**
     * Returns the table for the given vendor and country, else NULL if no
     * such table exists.
     *
     * @param int $vendor_id
     * @param string $country_code
     *
     * @return WCV_TRS_Table|null
     */
    public static function get_table_for_country( $vendor_id, $country_code ) {
        _deprecated_function( __METHOD__, '2.0.0', 'WCV_TRS_Tables::get_table_for_location' );

        return self::get_table_for_location( $vendor_id, array( 'country' => $country_code ) );
    }

    /**
     * Delete a table from the DB.
     *
     * @param int $table_id
     */
    public static function delete_table( $table_id ) {
        $table = new WCV_TRS_Table( $table_id );
        $table->delete();
    }

    /**
     * Handle submissions from "Tables" Backbone model.
     */
    public static function trs_save_changes() {
        check_ajax_referer( 'wcv_trs_nonce', 'wcv_trs_nonce' );

        if ( ! isset( $_POST['changes'], $_POST['user_id'] ) ) {
            wp_send_json_error( 'missing_fields' );
        }

        $user_id = absint( $_POST['user_id'] );

        if ( ! current_user_can( 'edit_user', $user_id ) ) {
            wp_send_json_error( 'missing_capabilities' );
        }

        foreach ( $_POST['changes'] as $table_id => $data ) {
            if ( isset( $data['deleted'] ) ) {
                if ( isset( $data['newRow'] ) ) {
                    // So the user added and deleted a new row.
                    // That's fine, it's not in the database anyways. NEXT!
                    continue;
                }
                self::delete_table( $table_id );
                continue;
            }

            if ( ! isset( $data['table_id'] ) ) {
                continue;
            }

            $table = new WCV_TRS_Table( $data['table_id'], $user_id );

            if ( isset( $data['table_name'] ) ) {
                $table->set_table_name( $data['table_name'] );
            }

            if ( isset( $data['table_order'] ) ) {
                $table->set_table_order( $data['table_order'] );
            }

            if ( isset( $data['table_locations'] ) ) {
                $table->clear_locations( array( 'continent', 'country', 'state' ) );

                $locations = array_filter( array_map( 'wc_clean', (array) $data['table_locations'] ) );

                if ( empty( $locations ) ) {
                    // Table applies to all shipping destinations
                    $table->add_location( '*', 'country' );
                } else {
                    foreach ( $locations as $location ) {
                        $first_colon_i = strpos( $location, ':' );
                        if ( false !== $first_colon_i ) {
                            $type = substr( $location, 0, $first_colon_i );
                            $code = substr( $location, $first_colon_i + 1 );
                            if ( $code ) {
                                $table->add_location( $code, $type );
                            }
                        }
                    }
                }
            }

            if ( isset( $data['table_postcodes'] ) ) {
                $table->clear_locations( 'postcode' );
                $postcodes = array_filter(
                    array_map( 'strtoupper', array_map( 'wc_clean', explode( "\n", $data['table_postcodes'] ) ) )
                );
                foreach ( $postcodes as $postcode ) {
                    $table->add_location( $postcode, 'postcode' );
                }
            }

            if ( isset( $data['table_fee'] ) ) {
                $table->set_table_fee( $data['table_fee'] );
            }

            if ( isset( $data['table_method'] ) ) {
                $table->set_table_method( $data['table_method'] );
            }

            if ( isset( $data['is_enabled'] ) ) {
                $table->set_is_enabled( wc_string_to_bool( $data['is_enabled'] ) );
            }

            if ( isset( $data['formatted_table_rates'] ) ) {
                $table->clear_rates();

                $rates = array_filter( array_map( 'wc_clean', (array) $data['formatted_table_rates'] ) );

                foreach ( $rates as $rate ) {
                    $table->add_rate( $rate['rate'], $rate['threshold'], 'yes' === $rate['is_percent'] );
                }
            }

            $table->save();
        }

        update_user_meta( $user_id, 'wcv_trs_tables_saved', true );

        wp_send_json_success(
            [
                'tables' => self::get_tables( $user_id ),
            ]
        );
    }

	/**
	 * Records a user's decision to keep the default shipping tables.
	 */
	public static function keep_default_tables() {
		$changes = [];

		if ( isset( $_POST['user_id'] ) ) {
			$user_id = absint( $_POST['user_id'] );
			$to_copy = [
				'table_id'              => null,
				'table_name'            => null,
				'table_order'           => null,
				'table_locations'       => function ( $location, $key ) {
					return $location->type . ':' . $location->code;
				},
				'table_postcodes'       => null,
				'table_method'          => null,
				'table_fee'             => null,
				'is_enabled'            => null,
				'formatted_table_rates' => function ( $rate, $key ) {
					$rate['rate_id'] = 'new-' . $key;

					return $rate;
				}
			];
			$tables  = self::get_tables( $user_id );

			foreach ( $tables as $table_id => $table ) {
				$changes[ $table_id ] = [];
				foreach ( $to_copy as $prop => $map_func ) {
					if ( is_null( $map_func ) ) {
						$changes[ $table_id ][ $prop ] = $table[ $prop ];
					} else {
						$changes[ $table_id ][ $prop ] = array_map(
							$map_func,
							$table[ $prop ],
							array_keys( $table[ $prop ] )
						);
					}
				}
			}
		}

		$_POST['changes'] = $changes;

		self::trs_save_changes();
	}

	/**
	 * Records a user's decision to delete the default shipping tables.
	 */
	public static function delete_default_tables() {
		$_POST['changes'] = [];

		self::trs_save_changes();
	}

}

WCV_TRS_Tables::init();
