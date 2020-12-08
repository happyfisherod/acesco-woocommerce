<?php

/**
 * Template for the Shipping Rates product tab.
 *
 * @global array         $destination           Associative array with keys 'region' and 'postcode'.
 * @global string        $formatted_destination Formatted shipping destination, e.g. New York, United States.
 * @global array         $allowed_countries     Allowed shipping countries.
 * @global WCV_TRS_Table $table                 Shipping table for selected destination.
 *
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<h2><?php _e( 'Shipping Rates', 'wcv-table-rate-shipping' ); ?></h2>

<div id="trs_customer_location">
    <h3 id="trs_shipping_destination_heading">
        <?php esc_html_e( 'Shipping Destination', 'wcv-table-rate-shipping' ); ?>
    </h3>
    <div class="view">
        <span class="trs-destination"><?php echo $formatted_destination; ?></span>
        <span class="trs-change-destination">
            <i class="dashicons dashicons-edit" aria-label="<?php _e( 'Change', 'wcv-table-rate-shipping' ); ?>"
               title="<?php _e( 'Change', 'wcv-table-rate-shipping' ); ?>"></i>
        </span>
    </div>
    <div class="edit" style="display: none;">
        <form action="" method="post" id="trs_location_form">
            <div class="trs-form-group">
                <label for="trs_region_select" class="screen-reader-text">
                    <?php esc_html_e( 'Region', 'wcv-table-rate-shipping' ); ?>
                </label>
                <select id="trs_region_select" class="wc-enhanced-select" name="region"
                        data-placeholder="<?php esc_attr_e( 'Region', 'wcv-table-rate-shipping' ); ?>">
                    <?php

                    foreach ( $allowed_countries as $country_code => $country_name ) {
                        $country_value = "country:{$country_code}";
                        printf(
                            '<option value="%1$s" %2$s>%3$s</option>',
                            esc_attr( $country_value ),
                            selected( $country_value, $destination['region'], false ),
                            esc_html( $country_name )
                        );

                        $states = WC()->countries->get_states( $country_code );

                        if ( $states ) {
                            foreach ( $states as $state_code => $state_name ) {
                                $state_value = "state:{$country_code}:{$state_code}";
                                printf(
                                    '<option value="%1$s" %2$s>&nbsp;&nbsp; %3$s, %4$s</option>',
                                    esc_attr( $state_value ),
                                    selected( $state_value, $destination['region'], false ),
                                    esc_html( $state_name ),
                                    esc_html( $country_name )
                                );
                            }
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="trs-form-group">
                <label for="trs_postcode" class="screen-reader-text">
                    <?php esc_html_e( 'Postcode', 'wcv-table-rate-shipping' ); ?>
                </label>
                <input type="text" name="postcode" id="trs_postcode"
                       value="<?php echo esc_attr( $destination['postcode'] ); ?>"
                       placeholder="<?php esc_attr_e( 'Postal code', 'wcv-table-rate-shipping' ); ?>">
            </div>
            <button type="submit" class="trs-save-location">
                <?php esc_html_e( 'Update', 'wcv-table-rate-shipping' ); ?>
            </button>
        </form>
    </div>
</div>

<?php if ( is_null( $table ) ): ?>

    <p><?php esc_html_e( 'Shipping is not available for the selected destination.', 'wcv-table-rate-shipping' ); ?></p>

<?php else: ?>

    <table class="shop_attributes">
        <thead>
        <tr>
            <th>
                <?php printf(
                    __( '%s Equal To and Above', 'wcv-table-rate-shipping' ),
                    $table->get_formatted_table_method()
                ); ?>
            </th>
            <th>
                <?php _e( 'Shipping Cost', 'wcv-table-rate-shipping' ); ?>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ( $table->get_table_rates() as $rate ): ?>
            <tr>
                <td>
                    <?php
                    if ( 'weighttotal' === $table->get_table_method() ) {
                        if ( 0 == $rate->threshold ) {
                            echo $rate->threshold . ' ' . get_option( 'woocommerce_weight_unit' );
                        } else {
                            echo wc_format_weight( $rate->threshold );
                        }
                    } elseif ( 'subtotal' === $table->get_table_method() ) {
                        echo wc_price( $rate->threshold );
                    } else {
                        echo $rate->threshold;
                    }
                    ?>
                </td>
                <td>
                    <?php
                    $handling_fee  = $table->get_table_fee();
                    $shipping_cost = $rate->rate;
                    $suffix        = '';

                    if ( $rate->is_percent ) {
                        $shipping_cost .= '%';
                        if ( $handling_fee ) {
                            $suffix = sprintf(
                                __( " + %s handling fee", 'wcv-table-rate-shipping' ),
                                wc_price( $handling_fee )
                            );
                        }
                    } else {
                        $shipping_cost = $rate->rate;
                        if ( $handling_fee ) {
                            $shipping_cost += $handling_fee;
                            $suffix        = sprintf(
                                __( ' (incl. %s handling fee)', 'wcv-table-rate-shipping' ),
                                wc_price( $handling_fee )
                            );
                        }
                        $shipping_cost = wc_price( $shipping_cost );
                    }

                    echo $shipping_cost . $suffix;
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>
