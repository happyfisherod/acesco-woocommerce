<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WCV_TRS_Commissions
 *
 * Sets and updates the shipping commission due to each vendor.
 */
class WCV_TRS_Commissions {

    /**
     * The ID of the order that is being processed.
     *
     * @param int $order_id
     */
    private $current_order_id = 0;

    /**
     * Stores the unallocated shipping and number of items remaining for each vendor in the current order.
     *
     * @var array
     */
    private $shipping_details = [];

    /**
     * Constructor.
     *
     * Registers action hooks and filters.
     */
    public function __construct() {
        add_filter( 'wcvendors_shipping_due', array( $this, 'get_shipping_due' ), 10, 4 );
    }

    /**
     * Gets the total shipping for a vendor.
     *
     * @param WC_Order $order
     * @param int $vendor_id
     *
     * @return array Array with keys 'tax' and 'amount'
     */
    protected function get_vendor_shipping( $order, $vendor_id ) {
        foreach ( $order->get_shipping_methods() as $shipping_method ) {
            $method_vendor_id = $shipping_method->get_meta( 'vendor_id', true, 'edit' );

            if ( $method_vendor_id == $vendor_id ) {
                return [
                    'amount' => $shipping_method->get_total(),
                    'tax'    => $shipping_method->get_total_tax(),
                ];
            }
        }

        return [
            'amount' => 0,
            'tax'    => 0,
        ];
    }

    /**
     * Returns the shipping due to a vendor for a given order and product.
     *
     * @param array $shipping_costs Array with keys 'tax' and 'amount.'
     * @param int $order_id
     * @param WC_Order_Item $product
     * @param int $vendor_id
     *
     * @return array $shipping_costs
     */
    public function get_shipping_due( $shipping_costs, $order_id, $product, $vendor_id ) {
	    // Bail if the shipping amount was already set
    	if ( $shipping_costs['amount'] > 0 ) {
            return $shipping_costs;
        }

        // Reset if the order ID changes
        if ( $order_id !== $this->current_order_id ) {
            $this->current_order_id = $order_id;
            $this->shipping_details = [];
        }

        // Bail if we've already calculated the shipping for this product during this request
	    $item_id = $product->get_id();

	    if ( isset( $this->shipping_details[ $vendor_id ] ) ) {
            $calculated_costs = $this->shipping_details[ $vendor_id ]['shipping_costs'];
            if ( isset( $calculated_costs[ $item_id ] ) ) {
                return $calculated_costs[ $item_id ];
	        }
	    }

        $order = wc_get_order( $order_id );

        // Update the shipping amount and tax if the vendor shipped via TRS
	    $vendor_shipping = $this->get_vendor_shipping( $order, $vendor_id );

	    if ( $vendor_shipping['amount'] > 0 ) {
            if ( ! isset( $this->shipping_details[ $vendor_id ] ) ) {
                $this->shipping_details[ $vendor_id ] = [
                    'items_remaining' => $this->count_vendor_order_items( $order, $vendor_id ),
                    'unallocated'     => $vendor_shipping,
	                'shipping_costs'  => [],
                ];
            }

            $shipping_details     = $this->shipping_details[ $vendor_id ];
            $unallocated_shipping = $shipping_details['unallocated'];
            $items_remaining      = $shipping_details['items_remaining'];

            if ( 0 === $items_remaining ) {
                $items_remaining = 1;
            }

            $shipping                 = round( $unallocated_shipping['amount'] / $items_remaining, 2 );
            $shipping_costs['amount'] += $shipping;
            $shipping_tax             = round( $unallocated_shipping['tax'] / $items_remaining, 2 );
            $shipping_costs['tax']    += $shipping_tax;

            $this->shipping_details[ $vendor_id ] = [
                'items_remaining' => $items_remaining - 1,
                'unallocated'     => [
                    'amount' => $unallocated_shipping['amount'] - $shipping,
                    'tax'    => $unallocated_shipping['tax'] - $shipping_tax,
                ],
                'shipping_costs'  => $shipping_details['shipping_costs'] + [ $item_id => $shipping_costs ],
            ];
        }

        return $shipping_costs;
    }

    /**
     * Counts the number of items from a vendor in an order.
     *
     * @param WC_Order $order WooCommerce order object.
     * @param int $vendor_id Vendor ID.
     *
     * @return int The number of items the vendor has in the given order.
     */
    private function count_vendor_order_items( $order, $vendor_id ) {
        $num_items = 0;

        foreach ( $order->get_items() as $item ) {
            $item_vendor_id = get_post_field( 'post_author', $item->get_product_id() );

            if ( $item_vendor_id == $vendor_id ) {
                $num_items++;
            }
        }

        return $num_items;
    }

}

new WCV_TRS_Commissions();
