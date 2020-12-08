<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WCV_TRS_Table
 *
 * Represents a single shipping table.
 *
 * @author  Brett Porcelli
 * @package WCV_Table_Rate_Shipping
 */
class WCV_TRS_Table extends WC_Data {

    /**
     * @var int|null Table ID.
     */
    protected $id = null;

    /**
     * @var string Name of this object type.
     */
    protected $object_type = 'trs_table';

    /**
     * @var array Core table data.
     */
    protected $data = array(
        'user_id'         => 0,
        'table_id'        => null,
        'table_name'      => '',
        'table_order'     => 0,
        'table_fee'       => '',
        'table_method'    => 'subtotal',
        'table_rates'     => array(),
        'table_locations' => array(),
        'is_enabled'      => true,
    );

    /**
     * Constructor for tables.
     *
     * @param int|object $table ID of table or already queried data.
     * @param int $user_id
     */
    public function __construct( $table = null, $user_id = 0 ) {
        parent::__construct();

        if ( is_numeric( $table ) && ! empty( $table ) ) {
            $this->set_id( $table );
        } elseif ( is_object( $table ) ) {
            $this->set_id( $table->table_id );
        } else {
            $this->set_user_id( $user_id );
            $this->set_object_read( true );
        }

        $this->data_store = new WCV_Table_Data_Store();
        if ( $this->get_id() > 0 ) {
            $this->data_store->read( $this );
        }
    }

    /*
     |--------------------------------------------------------------------------
     | Getters
     |--------------------------------------------------------------------------
     */

    /**
     * Get user ID.
     *
     * @param string $context
     *
     * @return int
     */
    public function get_user_id( $context = 'view' ) {
        return intval( $this->get_prop( 'user_id', $context ) );
    }

    /**
     * Get table ID.
     *
     * @param string $context
     *
     * @return int
     */
    public function get_table_id( $context = 'view' ) {
        return intval( $this->get_prop( 'table_id', $context ) );
    }

    /**
     * Get table name.
     *
     * @param string $context
     *
     * @return string
     */
    public function get_table_name( $context = 'view' ) {
        return $this->get_prop( 'table_name', $context );
    }

    /**
     * Get table fee.
     *
     * @param string $context
     *
     * @return string
     */
    public function get_table_fee( $context = 'view' ) {
        return $this->get_prop( 'table_fee', $context );
    }

    /**
     * Get table method.
     *
     * @param string $context
     *
     * @return string
     */
    public function get_table_method( $context = 'view' ) {
        return $this->get_prop( 'table_method', $context );
    }

    /**
     * Get table order.
     *
     * @param string $context
     *
     * @return int
     */
    public function get_table_order( $context = 'view' ) {
        return intval( $this->get_prop( 'table_order', $context ) );
    }

    /**
     * Get table rates.
     *
     * @param string $context
     *
     * @return array of table rates.
     */
    public function get_table_rates( $context = 'view' ) {
        return $this->get_prop( 'table_rates', $context );
    }

    /**
     * Get table locations.
     *
     * @param string $context
     *
     * @return array of table locations.
     */
    public function get_table_locations( $context = 'view' ) {
        return $this->get_prop( 'table_locations', $context );
    }

    /**
     * Is this table enabled?
     *
     * @param string $context
     *
     * @return bool
     */
    public function is_enabled( $context = 'view' ) {
        return $this->get_prop( 'is_enabled', $context );
    }

    /**
     * Is this a default table?
     *
     * @return bool
     */
    public function is_default() {
        return 0 === $this->get_prop( 'user_id', 'edit' );
    }

    /*
     |--------------------------------------------------------------------------
     | Setters
     |--------------------------------------------------------------------------
     */

    /**
     * Set user ID.
     *
     * @param int $user_id
     */
    public function set_user_id( $user_id ) {
        $this->set_prop( 'user_id', absint( $user_id ) );
    }

    /**
     * Set table ID.
     *
     * @param int $set
     */
    public function set_table_id( $set ) {
        $this->set_prop( 'table_id', is_null( $set ) ? null : absint( $set ) );
    }

    /**
     * Set table name.
     *
     * @param string $set
     */
    public function set_table_name( $set ) {
        $this->set_prop( 'table_name', wc_clean( $set ) );
    }

    /**
     * Set handling fee.
     *
     * @param string $fee
     */
    public function set_table_fee( $fee ) {
        $this->set_prop( 'table_fee', $fee );
    }

    /**
     * Set calculation method.
     *
     * @param string $method Must be one of 'weighttotal', 'itemcount', 'subtotal'
     */
    public function set_table_method( $method ) {
        if ( $this->is_valid_calculation_method( $method ) ) {
            $this->set_prop( 'table_method', $method );
        }
    }

    /**
     * Set table order.
     *
     * @param int $set
     */
    public function set_table_order( $set ) {
        $this->set_prop( 'table_order', absint( $set ) );
    }

    /**
     * Set enabled flag.
     *
     * @param bool $enabled
     */
    public function set_is_enabled( $enabled ) {
        $this->set_prop( 'is_enabled', boolval( $enabled ) );
    }

    /*
     |--------------------------------------------------------------------------
     | Other Methods
     |--------------------------------------------------------------------------
     */

    /**
     * Save table data to the database.
     */
    public function save() {
        if ( empty( $this->get_table_name() ) ) {
            $this->set_table_name( $this->generate_table_name() );
        }

        parent::save();
    }

    /**
     * Generate a table name based on location.
     *
     * @return string
     */
    protected function generate_table_name() {
        $table_name = $this->get_formatted_location();

        if ( empty( $table_name ) ) {
            $table_name = __( 'Table', 'wcv-table-rate-shipping' );
        }

        return $table_name;
    }

    /**
     * Return a text string representing what this table is for.
     *
     * @param int $max
     * @param string $context
     *
     * @return string
     */
    public function get_formatted_location( $max = 10, $context = 'view' ) {
        $locations   = $this->get_table_locations( $context );
        $is_wildcard = sizeof( $locations ) === 1 && '*' === $locations[0]->code;

        if ( $is_wildcard ) {
            return __( 'All shipping destinations', 'wcv-table-rate-shipping' );
        }

        // Borrowed from WC_Shipping_Zone::get_formatted_location()
        $location_parts = array();
        $all_continents = WC()->countries->get_continents();
        $all_countries  = WC()->countries->get_countries();
        $all_states     = WC()->countries->get_states();
        $continents     = array_filter( $locations, array( $this, 'location_is_continent' ) );
        $countries      = array_filter( $locations, array( $this, 'location_is_country' ) );
        $states         = array_filter( $locations, array( $this, 'location_is_state' ) );
        $postcodes      = array_filter( $locations, array( $this, 'location_is_postcode' ) );

        foreach ( $continents as $location ) {
            $location_parts[] = $all_continents[ $location->code ]['name'];
        }

        foreach ( $countries as $location ) {
            $location_parts[] = $all_countries[ $location->code ];
        }

        foreach ( $states as $location ) {
            $location_codes   = explode( ':', $location->code );
            $location_parts[] = $all_states[ $location_codes[0] ][ $location_codes[1] ];
        }

        foreach ( $postcodes as $location ) {
            $location_parts[] = $location->code;
        }

        // Fix display of encoded characters.
        $location_parts = array_map( 'html_entity_decode', $location_parts );

        if ( count( $location_parts ) > $max ) {
            $remaining = count( $location_parts ) - $max;
            return sprintf(
                _n( '%s and %d other region', '%s and %d other regions', $remaining, 'wcv-table-rate-shipping' ),
                implode( ', ', array_splice( $location_parts, 0, $max ) ),
                $remaining
            );
        } elseif ( ! empty( $location_parts ) ) {
            return implode( ', ', $location_parts );
        } else {
            return __( 'Everywhere', 'wcv-table-rate-shipping' );
        }
    }

    /**
     * Location type detection.
     *
     * @param  object $location Location to check.
     * @return boolean
     */
    private function location_is_continent( $location ) {
        return 'continent' === $location->type;
    }

    /**
     * Location type detection.
     *
     * @param  object $location Location to check.
     * @return boolean
     */
    private function location_is_country( $location ) {
        return 'country' === $location->type;
    }

    /**
     * Location type detection.
     *
     * @param  object $location Location to check.
     * @return boolean
     */
    private function location_is_state( $location ) {
        return 'state' === $location->type;
    }

    /**
     * Location type detection.
     *
     * @param  object $location Location to check.
     * @return boolean
     */
    private function location_is_postcode( $location ) {
        return 'postcode' === $location->type;
    }

    /**
     * Is passed location type valid?
     *
     * @param string $type
     *
     * @return bool
     */
    public function is_valid_location_type( $type ) {
        return in_array( $type, array( 'country', 'continent', 'state', 'postcode' ) );
    }

    /**
     * Is passed calculation method valid?
     *
     * @param string $method
     *
     * @return bool
     */
    public function is_valid_calculation_method( $method ) {
        return in_array( $method, array( 'itemcount', 'weighttotal', 'subtotal' ) );
    }

    /**
     * Add a location for this table.
     *
     * @param string $code Location code.
     * @param string $type Location type.
     */
    public function add_location( $code, $type ) {
        if ( $this->is_valid_location_type( $type ) ) {
            $location    = array(
                'code' => wc_clean( $code ),
                'type' => wc_clean( $type ),
            );
            $locations   = $this->get_prop( 'table_locations', 'edit' );
            $locations[] = (object) $location;
            $this->set_prop( 'table_locations', $locations );
        }
    }

    /**
     * Clear all locations for this table.
     *
     * @param string|string[] $types Types of locations to clear.
     */
    public function clear_locations( $types = array( 'postcode', 'state', 'country', 'continent' ) ) {
        if ( ! is_array( $types ) ) {
            $types = array( $types );
        }
        $table_locations = $this->get_prop( 'table_locations', 'edit' );
        foreach ( $table_locations as $key => $values ) {
            if ( in_array( $values->type, $types, true ) ) {
                unset( $table_locations[ $key ] );
            }
        }
        $table_locations = array_values( $table_locations ); // reindex.
        $this->set_prop( 'table_locations', $table_locations );
    }

    /**
     * Add a rate for this table.
     *
     * @param float $rate The rate.
     * @param float $threshold Threshold at or above which the rate will be applied.
     * @param bool $is_percent Is the rate a percentage?
     */
    public function add_rate( $rate, $threshold, $is_percent ) {
        $rate    = array(
            'rate'       => $rate,
            'threshold'  => $threshold,
            'is_percent' => $is_percent,
        );
        $rates   = $this->get_prop( 'table_rates', 'edit' );
        $rates[] = (object) $rate;
        $this->set_prop( 'table_rates', $rates );
    }

    /**
     * Clear the rates for this table.
     */
    public function clear_rates() {
        $this->set_prop( 'table_rates', array() );
    }

    /**
     * Get rates formatted for display.
     *
     * @return array of formatted rates
     */
    public function get_formatted_rates() {
        $rates = array();

        foreach ( $this->get_table_rates( 'edit' ) as $key => $rate ) {
            $_rate = array(
                'rate_id'    => $key,
                'rate'       => $rate->rate,
                'threshold'  => $rate->threshold,
                'is_percent' => $rate->is_percent ? 'yes' : 'no',
            );

            $rates[] = $_rate;
        }

        return $rates;
    }

    /**
     * Return a string 'yes' or 'no' indicating whether the table is enabled.
     *
     * @return string
     */
    public function get_formatted_enabled() {
        return $this->is_enabled() ? 'yes' : 'no';
    }

    /**
     * Gets the table calculation method formatted for output.
     *
     * @return string
     */
    public function get_formatted_table_method() {
        switch ( $this->get_table_method() ) {
            case 'weighttotal':
                return __( 'Weight', 'wcv-table-rate-shipping' );
            case 'subtotal':
                return __( 'Subtotal', 'wcv-table-rate-shipping' );
            case 'itemcount':
                return __( 'Quantity', 'wcv-table-rate-shipping' );
            default:
                return '';
        }
    }

}
