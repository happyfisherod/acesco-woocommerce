<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WCV_TRS_Product_Tab
 *
 * Adds a 'Shipping Rates' tab to the single product page.
 */
class WCV_TRS_Product_Tab {

    /**
     * Initializes the product tab.
     */
    public static function init() {
        static $instance = null;

        if ( is_null( $instance ) ) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Constructor.
     *
     * Registers action hooks and filters.
     */
    public function __construct() {
        if ( wcv_trs()->method->is_tab_enabled() ) {
            add_action( 'wp_ajax_trs_change_destination', array( $this, 'ajax_change_destination' ) );
            add_action( 'wp_ajax_nopriv_trs_change_destination', array( $this, 'ajax_change_destination' ) );
            add_filter( 'woocommerce_product_tabs', array( $this, 'register_tab' ), 20 );
        }
    }

    /**
     * Registers the 'Shipping Rates' tab if the current product needs shipping.
     *
     * @param array $tabs
     *
     * @return array
     */
    public function register_tab( $tabs ) {
        global $product;

        $vendor_id     = $this->get_product_vendor_id( $product->get_id() );
        $vendor_tables = WCV_TRS_Tables::get_tables( $vendor_id );

        if ( $product->needs_shipping() && 0 < count( $vendor_tables ) ) {
            $tabs['shipping'] = [
                'title'    => __( 'Shipping Rates', 'wcv-table-rate-shipping' ),
                'priority' => 40,
                'callback' => array( $this, 'output' ),
            ];
        }
        return $tabs;
    }

    /**
     * Outputs the tab.
     */
    public function output() {
    	global $product;

        wp_enqueue_style( 'wcv-table-rate-shipping.trs-front' );

        wcv_trs()->assets->enqueue( 'style', 'woocommerce.select2' );

        wcv_trs()->assets->enqueue(
            'script',
            'wcv-table-rate-shipping.product-tab',
            [
                'deps'     => [
                    'jquery',
                    'woocommerce.selectWoo/selectWoo.full',
                    'woocommerce.jquery-blockui/jquery.blockUI',
                ],
                'localize' => [
                    'trs_shipping_tab_data' => [
                        'nonce'      => wp_create_nonce( 'trs-save-location' ),
                        'ajax_url'   => admin_url( 'admin-ajax.php' ),
	                    'product_id' => $product->get_id(),
                    ],
                ],
	            'ver'      => wcv_trs()->version
            ]
        );

        $this->get_shipping_tab_template( $product->get_id() );
    }

	/**
	 * Gets the shipping tab template.
	 *
	 * @param int $product_id
	 */
    private function get_shipping_tab_template( $product_id ) {
        $vendor_id             = $this->get_product_vendor_id( $product_id );
        $destination           = $this->get_destination();
        $address               = $this->get_address_from_destination( $destination );
        $table                 = WCV_TRS_Tables::get_table_for_location( $vendor_id, $address );
        $formatted_destination = $this->format_address( $address );

        wc_get_template(
            'shipping-tab.php',
            [
                'destination'           => $destination,
                'formatted_destination' => $formatted_destination,
                'allowed_countries'     => WC()->countries->get_shipping_countries(),
                'table'                 => $table,
            ],
            'table-rate-shipping/',
            wcv_trs()->path( 'templates/' )
        );
    }

    /**
     * Builds a shipping address from the selected destination.
     *
     * @param array $destination Associative array with keys 'region' and 'postcode'.
     *
     * @return array Associative array with keys 'country', 'state', and 'postcode'.
     */
    private function get_address_from_destination( $destination ) {
        $address       = array(
            'country'  => '',
            'state'    => '',
            'postcode' => $destination['postcode'],
        );
        $region        = $destination['region'];
        $first_colon_i = strpos( $region, ':' );

        if ( $first_colon_i ) {
            $location_type = substr( $region, 0, $first_colon_i );
            $location_code = substr( $region, $first_colon_i + 1 );
            if ( 'state' === $location_type ) {
                list( $country_code, $state_code ) = explode( ':', $location_code );
                if ( ! empty( $country_code ) ) {
                    $address['country'] = $country_code;
                }
                if ( ! empty( $state_code ) ) {
                    $address['state']   = $state_code;
                }
            } elseif ( 'country' === $location_type ) {
                $address['country'] = $location_code;
            }
        }

        return $address;
    }

    /**
     * Formats an address for display.
     *
     * @param array $address Associative array with keys 'country', 'state', and 'postcode'.
     *
     * @return string
     */
    private function format_address( $address ) {
        /*
         * By default Woo won't show the country if it's the same as the shop base country.
         * We always want to show the country so we temporarily add this filter.
         */
        add_filter( 'woocommerce_formatted_address_force_country_display', '__return_true' );

        if ( version_compare( WC_VERSION, '3.5', '>=' ) ) {
            $formatted_address = WC()->countries->get_formatted_address( $address, ', ' );
        } else {
            $formatted_address = str_replace( '<br/>', ', ', WC()->countries->get_formatted_address( $address ) );
        }

        remove_filter( 'woocommerce_formatted_address_force_country_display', '__return_true' );

        return $formatted_address;
    }

    /**
     * Returns the selected shipping destination.
     */
    private function get_destination() {
        $destination = WC()->session->get( 'trs_shipping_destination', array() );

        if ( is_string( $destination ) ) {
            $destination = array(
                'region'   => "country:{$destination}",
                'postcode' => '',
            );
        }

        if ( empty( $destination ) ) {
            // Use shop base country as shipping destination by default
            $destination = array(
                'region'   => 'country:' . WC()->countries->get_base_country(),
                'postcode' => '',
            );

            if ( WC()->customer->get_shipping_country() ) {
                $region = 'country:' . WC()->customer->get_shipping_country();
                if ( WC()->customer->get_shipping_state() ) {
                    $region = sprintf(
                        'state:%1$s:%2$s',
                        WC()->customer->get_shipping_country(),
                        WC()->customer->get_shipping_state()
                    );
                }
                $destination = array(
                    'region'   => $region,
                    'postcode' => WC()->customer->get_shipping_postcode(),
                );
            }

            $this->set_destination( $destination );
        }

        return $destination;
    }

    /**
     * Sets the shipping destination in the session.
     *
     * @param array $destination New shipping destination.
     */
    private function set_destination( $destination ) {
        $defaults = array(
            'region'   => '',
            'postcode' => '',
        );

        WC()->session->set( 'trs_shipping_destination', wp_parse_args( $destination, $defaults ) );
    }

    /**
     * Changes the destination country and returns the updated tab markup.
     */
    public function ajax_change_destination() {
        check_ajax_referer( 'trs-save-location', 'nonce' );

        if ( isset( $_POST['region'], $_POST['postcode'], $_POST['product_id'] ) ) {
            $this->set_destination( array(
                'region'   => sanitize_text_field( $_POST['region'] ),
                'postcode' => wc_normalize_postcode( sanitize_text_field( $_POST['postcode'] ) ),
            ) );
            $this->get_shipping_tab_template( absint( $_POST['product_id'] ) );
        }

        die;
    }

    /**
     * Gets the ID of the vendor that sells a product.
     *
     * @param int $product_id Product ID
     *
     * @return int Vendor ID
     */
	private function get_product_vendor_id( $product_id ) {
		$parent = get_post_ancestors( $product_id );

		if ( $parent ) {
			$product_id = $parent[0];
		}

		$author = get_post_field( 'post_author', $product_id );

		return apply_filters( 'wcv_trs_product_vendor_id', $author, $product_id );
    }

}

WCV_TRS_Product_Tab::init();
