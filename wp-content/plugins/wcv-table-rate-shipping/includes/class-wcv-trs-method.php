<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WCV_TRS_Shipping_Method
 *
 * Defines the Table Rate shipping method.
 */
class WCV_TRS_Shipping_Method extends WC_Shipping_Method {

    /**
     * Yes or no based on whether the shipping rates tab is enabled.
     *
     * @var string
     */
    private $tab_enabled = 'yes';

    /**
     * __construct function.
     *
     * @param int $instance_id
     */
    public function __construct( $instance_id = 0 ) {
        parent::__construct( $instance_id );

        $this->id                 = 'wcv_table_rate_shipping';
        $this->method_title       = __( 'Table Rate Shipping', 'wcv-table-rate-shipping' );
        $this->method_description = $this->get_method_description();
        $this->admin_page_heading = $this->method_title;

        add_filter( 'woocommerce_save_settings_shipping_' . $this->id, function() {
            return ( isset( $_GET['section'] ) && $this->id === $_GET['section'] ) && ! empty( $_POST );
        } );
        add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_settings_script' ) );
        add_filter( 'woocommerce_cart_shipping_method_full_label', array( $this, 'update_method_label' ), 10, 2 );
        add_filter( 'woocommerce_hidden_order_itemmeta', array( $this, 'hide_meta_data' ) );

        $this->init_form_fields();
        $this->init_settings();

        $this->enabled     = $this->get_option( 'enabled' );
        $this->title       = $this->get_option( 'title' );
        $this->tax_status  = $this->get_option( 'tax_status' );
        $this->tab_enabled = $this->get_option( 'show_shipping_rates_tab' );
    }

    /**
     * Initialize method form fields.
     */
    public function init_form_fields() {
        $this->form_fields = [
            'general_options'         => [
                'type'  => 'title',
                'title' => __( 'General options', 'wcv-table-rate-shipping' ),
            ],
            'enabled'                 => [
                'title'   => __( 'Enable/disable', 'wcv-table-rate-shipping' ),
                'label'   => __( 'Enable shipping method', 'wcv-table-rate-shipping' ),
                'type'    => 'checkbox',
                'default' => 'no',
            ],
            'tax_status'              => [
                'title'   => __( 'Tax status', 'wcv-table-rate-shipping' ),
                'class'   => 'wc-enhanced-select',
                'type'    => 'select',
                'options' => [
                    'taxable' => __( 'Taxable', 'wcv-table-rate-shipping' ),
                    'none'    => __( 'None', 'wcv-table-rate-shipping' ),
                ],
                'default' => 'none',
            ],
            'includetax'              => [
                'title'   => __( 'Include tax', 'wcv-table-rate-shipping' ),
                'label'   => __( 'Calculate shipping AFTER tax is applied', 'wcv-table-rate-shipping' ),
                'type'    => 'checkbox',
                'default' => 'no',
            ],
            'excludediscount'         => [
                'title'   => __( 'Exclude discount', 'wcv-table-rate-shipping' ),
                'label'   => __( 'Calculate shipping BEFORE discounts are applied', 'wcv-table-rate-shipping' ),
                'type'    => 'checkbox',
                'default' => 'no',
            ],
            'general_options_end'     => [
                'type' => 'sectionend',
            ],
            'display_options'         => [
                'type'  => 'title',
                'title' => __( 'Display options', 'wcv-table-rate-shipping' ),
            ],
            'title'                   => [
                'title'       => __( 'Method title', 'wcv-table-rate-shipping' ),
                'type'        => 'text',
                'default'     => __( 'Vendor Shipping', 'wcv-table-rate-shipping' ),
                'description' => __(
                    'This controls the title that customers see during checkout.',
                    'wcv-table-rate-shipping'
                ),
            ],
            'hide_method_title'       => [
                'type'    => 'checkbox',
                'title'   => __( 'Hide method title', 'wcv-table-rate-shipping' ),
                'label'   => __( "Don't show the method title at checkout", 'wcv-table-rate-shipping' ),
                'default' => 'no',
            ],
            'show_shipping_rates_tab' => [
                'type'    => 'checkbox',
                'title'   => __( 'Show shipping rates tab', 'wcv-table-rate-shipping' ),
                'label'   => __( "Show a 'Shipping Rates' tab on product pages", 'wcv-table-rate-shipping' ),
                'default' => 'yes',
            ],
            'display_options_end'     => [
                'type' => 'sectionend',
            ],
            'default_tables_heading'  => [
                'type'        => 'title',
                'title'       => __( 'Default tables', 'wcv-table-rate-shipping' ),
                'description' => __(
                    'These shipping tables will be used when a vendor has not entered their own shipping rates. Vendors can choose to build upon these tables or discard them and start anew.',
                    'wcv-table-rate-shipping'
                ),
            ],
            'shipping_tables'         => [
                'type' => 'table_list',
            ],
        ];
    }

    /**
     * Calculate the cost of shipping the given package. Each package will
     * contain the items for a single vendor.
     *
     * @param array $package (default: array())
     *
     * @return void
     */
    public function calculate_shipping( $package = array() ) {
        $shipping_cost = 0;
        $items         = $package['contents'];

        // Can't ship an empty package.
        if ( empty( $items ) ) {
            return;
        }

        $vendor_id         = $package['vendor_id'];
        $shipping_location = array(
            'country'  => isset( $package['destination']['country'] ) ? $package['destination']['country'] : '',
            'state'    => isset( $package['destination']['state'] ) ? $package['destination']['state'] : '',
            'postcode' => isset( $package['destination']['postcode'] ) ? $package['destination']['postcode'] : '',
        );
        $table             = WCV_TRS_Tables::get_table_for_location( $vendor_id, $shipping_location );

        // If no table was found, package can't be shipped.
        if ( is_null( $table ) ) {
            return;
        }

        $totals = array(
            'itemcount'   => 0,
            'weighttotal' => 0,
            'subtotal'    => 0,
        );

        foreach ( $items as $key => $item ) {
            /** @var WC_Product $_product */
            $_product = $item['data'];

            if ( $_product->needs_shipping() ) {
                $price_ex_tax = wc_get_price_excluding_tax( $_product );
                $quantity     = $item['quantity'];

                $totals['itemcount']   += $quantity;
                $totals['weighttotal'] += floatval( $_product->get_weight() ) * $quantity;
                $totals['subtotal']    += $price_ex_tax * $quantity;

                // Account for "Include Tax" and "Exclude Discount" settings.
                if ( 'yes' === $this->settings['includetax'] ) {
                    $totals['subtotal'] += $item['line_subtotal_tax'];
                }
                if ( 'no' === $this->settings['excludediscount'] ) {
                    $discount_per_item  = round(
                        ( $item['line_subtotal'] - $item['line_total'] ) / $quantity,
                        wc_get_price_decimals()
                    );
                    $totals['subtotal'] -= $discount_per_item * $quantity;
                }
            }
        }

        // Find the rate that applies
        $total = $totals[ $table->get_table_method() ];

        foreach ( $table->get_table_rates() as $rate ) {
            if ( $total >= $rate->threshold ) {
                if ( $rate->is_percent ) {
                    $shipping_cost = $totals['subtotal'] * ( $rate->rate / 100 );
                } else {
                    $shipping_cost = $rate->rate;
                }
            }
        }

        // Add fee if applicable
        $fee = $table->get_table_fee();
        if ( ( $pct_i = strpos( $fee, '%' ) ) !== false ) {
            $fee = $totals['subtotal'] * floatval( substr( $fee, 0, $pct_i ) / 100 );
        } else {
            $fee = floatval( $fee );
        }
        $shipping_cost += $fee;

        // If a product qualifies for free shipping, update the shipping cost
        $per_item_cost = $shipping_cost / count( $items );

        foreach ( $items as $cart_key => $item ) {
            if ( $item['data']->needs_shipping() && $this->product_has_free_shipping( $item['data'] ) ) {
                $shipping_cost -= $per_item_cost;
            }
        }

        $shipping_meta = array(
            'vendor_id'     => $vendor_id,
            'per_item_cost' => $per_item_cost,
        );

        // Register the rate
        $this->add_rate(
            array(
                'id'        => $this->id,
                'label'     => $this->settings['title'],
                'cost'      => $shipping_cost,
                'meta_data' => $shipping_meta,
                'package'   => $package,
            )
        );
    }

    /**
     * Does the given product qualify for free shipping?
     *
     * @param WC_Product $product Product to check.
     *
     * @return bool
     */
    private function product_has_free_shipping( $product ) {
        foreach ( WC()->cart->get_coupons() as $code => $coupon ) {
            /** @var WC_Coupon $coupon */
            if ( $coupon->get_free_shipping() && $coupon->is_valid_for_product( $product ) ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns the shipping method description.
     *
     * @return string
     */
    public function get_method_description() {
        $paragraphs = [
            __(
                "This shipping method enables vendors to specify their own shipping rates in the Vendor Dashboard. Rates can be set based on the quantity, weight, or subtotal of items in the customer's cart.",
                'wcv-table-rate-shipping'
            ),
            __(
                'Shipping rates are specified in one or more shipping tables, each of which applies to a specific set of destination countries. Tables can also be configured to apply to all allowed shipping destinations.',
                'wcv-table-rate-shipping'
            ),
            sprintf(
                __( 'For more information, please consult the %1$suser documentation%2$s.', 'wcv-table-rate-shipping' ),
                "<a href='https://thepluginpros.com/documentation/wcv-table-rate-shipping-user-documentation/' target='_blank'><strong>",
                '</strong></a>'
            ),
        ];
        return '<p>' . implode( '</p><p>', $paragraphs ) . '</p>';
    }

    /**
     * Generates the HTML for section ends.
     */
    public function generate_sectionend_html( $key, $value ) {
        echo '</table>';
    }

    /**
     * Generates the HTML for the default tables list.
     *
     * @param string $key
     * @param array $value
     *
     * @return string
     */
    public function generate_table_list_html( $key, $value ) {
        $table_list = new WCV_TRS_Tables_List( 0 );

        ob_start();
        ?>
        <tr id="default_tables" valign="top">
            <th colspan="2">
                <?php $table_list->output( 'settings' ); ?>
            </th>
        </tr>
        <?php

        return ob_get_clean();
    }

    /**
     * Enqueues the script for the method settings page.
     */
    public function enqueue_settings_script() {
        if ( isset( $_GET['section'] ) && $this->id === $_GET['section'] ) {
            wcv_trs()->assets->enqueue(
                'script',
                'wcv-table-rate-shipping.settings',
                [
                    'deps' => [ 'jquery' ],
                    'ver'  => wcv_trs()->version,
                ]
            );
        }
    }

    /**
     * Checks whether the shipping rates tab is enabled.
     *
     * @return bool
     */
    public function is_tab_enabled() {
        return 'yes' === $this->tab_enabled;
    }

    /**
     * Updates the shipping method label displayed at checkout.
     *
     * @param string $label
     * @param WC_Shipping_Rate $method
     *
     * @return string
     */
    public function update_method_label( $label, $method ) {
        if ( $this->id === $method->method_id ) {
            if ( $method->cost <= 0 ) {
                $label = __( 'Free shipping', 'wcv-table-rate-shipping' );
            } else {
                $hide_title = 'yes' === $this->get_option( 'hide_method_title' );

                if ( $hide_title ) {
                    $label = trim( str_replace( $method->get_label(), '', $label ), ' :' );
                }
            }
        }

        return $label;
    }

    /**
     * Tells WooCommerce to hide the shipping meta data added by this method.
     *
     * @param array $hidden_meta
     *
     * @return array
     */
    public function hide_meta_data( $hidden_meta ) {
        return array_merge( $hidden_meta, [ 'per_item_cost', 'vendor_id' ] );
    }

}
