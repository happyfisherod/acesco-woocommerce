<?php
/**
 * Shipping tables list template.
 *
 * @global string $context The context in which the table is being displayed. Can be 'admin' or 'dashboard'.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<?php do_action( "wcv_trs_{$context}_shipping_tables_before" ); ?>
<?php do_action( 'wcv_trs_shipping_tables_before', $context ); ?>

<table class="wc-shipping-zones wcv-trs-table wcv-trs-table-list widefat">
    <thead>
    <tr>
        <th class="wc-shipping-zone-sort"></th>
        <th class="wc-shipping-zone-name">
            <?php esc_html_e( 'Table Name', 'wcv-table-rate-shipping' ); ?>
        </th>
        <th class="wc-shipping-zone-region">
            <?php esc_html_e( 'Region(s)', 'wcv-table-rate-shipping' ); ?>
        </th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th colspan="3">
            <?php do_action( 'wcv_trs_shipping_tables_before_footer' ); ?>
            <a class="button button-secondary wc-shipping-zone-add" href="#">
                <?php esc_html_e( 'Add shipping table', 'wcv-table-rate-shipping' ); ?>
            </a>
        </th>
    </tr>
    </tfoot>
    <tbody class="wc-shipping-zone-rows"></tbody>
</table>

<?php do_action( "wcv_trs_{$context}_shipping_tables_after" ); ?>
<?php do_action( 'wcv_trs_shipping_tables_after', $context ); ?>

<script type="text/html" id="tmpl-wc-shipping-zone-row-blank">
    <tr>
        <td class="wc-shipping-zones-blank-state" colspan="3">
            <p class="main"><?php echo __( "You don't have any shipping tables.", 'wcv-table-rate-shipping' ); ?></p>
            <p>
                <?php
                esc_html_e(
                    'Add as many tables as you need &ndash; customers will be charged using the first table that applies to their shipping address.',
                    'wcv-table-rate-shipping'
                );
                ?>
            </p>
            <a class="button button-primary wc-shipping-zone-add">
                <?php esc_html_e( 'Add shipping table', 'wcv-table-rate-shipping' ); ?>
            </a>
        </td>
    </tr>
</script>

<script type="text/html" id="tmpl-wc-shipping-zone-row">
    <tr data-id="{{ data.table_id }}">
        <td width="1%" class="wc-shipping-zone-sort"></td>
        <td class="wc-shipping-zone-name">
            <a href="#" class="wc-shipping-zone-edit">{{ data.table_name }}</a>
            <div class="row-actions">
                <a class="wc-shipping-zone-edit" href="#">
                    <?php _e( 'Edit', 'wcv-table-rate-shipping' ); ?>
                </a>
                &nbsp;|&nbsp;
                <a href="#" class="wc-shipping-zone-delete">
                    <?php _e( 'Remove', 'wcv-table-rate-shipping' ); ?>
                </a>
            </div>
        </td>
        <td class="wc-shipping-zone-region">
            {{ data.formatted_table_location }}
        </td>
    </tr>
</script>

<script type="text/template" id="tmpl-wc-modal-shipping-table">
    <div class="wc-backbone-modal wc-backbone-modal-shipping-table">
        <div class="wc-backbone-modal-content">
            <section class="wc-backbone-modal-main" role="main">
                <header class="wc-backbone-modal-header">
                    <h1>
                        <# if ( 0 === data.table_id.toString().indexOf( 'new-' ) ) { #>
                            <?php esc_html_e( 'Add Shipping Table', 'wcv-table-rate-shipping' ); ?>
                        <# } else { #>
                            <?php
                            printf(
                                __( 'Shipping Tables > %s', 'wcv-table-rate-shipping' ),
                                '{{data.table.table_name}}'
                            );
                            ?>
                        <# } #>
                    </h1>
                    <button class="modal-close modal-close-link dashicons dashicons-no-alt">
                        <span class="screen-reader-text">
                            <?php esc_html_e( 'Close modal panel', 'wcv-table-rate-shipping' ); ?>
                        </span>
                    </button>
                </header>
                <article class="wc-modal-shipping-table">
                    <form action="" method="post" class="wcv-form">
                        <h3><?php _e( 'Table Options', 'wcv-table-rate-shipping' ); ?></h3>

                        <table class="form-table wcv-trs-table">
                            <tbody>
                            <tr>
                                <td>
                                    <label for="wcv_trs_table_name">
                                        <?php esc_html_e( 'Table Name', 'wcv-table-rate-shipping' ); ?>
                                    </label>
                                </td>
                                <td>
                                    <input type="text" name="wcv_trs_table_name" id="wcv_trs_table_name"
                                           value="{{data.table.table_name}}"
                                           placeholder="<?php esc_attr_e( 'Table Name', 'wcv-table-rate-shipping' ); ?>">
                               </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="wcv_trs_table_locations">
                                        <?php esc_html_e( 'Table Regions', 'wcv-table-rate-shipping' ); ?>
                                    </label>
                                </td>
                                <td>
                                    <select multiple="multiple" name="wcv_trs_table_locations[]" id="wcv_trs_table_locations"
                                            data-placeholder="<?php _e( 'Select regions for this table', 'wcv-table-rate-shipping' ); ?>"
                                            class="wc-enhanced-select">
                                        <?php
                                        foreach ( $shipping_continents as $continent_code => $continent ) {
                                            printf(
                                                '<option value="continent:%1$s">%2$s</option>',
                                                esc_attr( $continent_code ),
                                                esc_html( $continent['name'] )
                                            );

                                            $countries = array_intersect( array_keys( $allowed_countries ), $continent['countries'] );

                                            foreach ( $countries as $country_code ) {
                                                printf(
                                                    '<option value="country:%1$s">&nbsp;&nbsp; %2$s</option>',
                                                    esc_attr( $country_code ),
                                                    esc_html( $allowed_countries[ $country_code ] )
                                                );

                                                $states = WC()->countries->get_states( $country_code );

                                                if ( $states ) {
                                                    foreach ( $states as $state_code => $state_name ) {
                                                        printf(
                                                            '<option value="state:%1$s:%2$s">&nbsp;&nbsp;&nbsp;&nbsp; %3$s, %4$s</option>',
                                                            esc_attr( $country_code ),
                                                            esc_attr( $state_code ),
                                                            esc_html( $state_name ),
                                                            esc_html( $allowed_countries[ $country_code ] )
                                                        );
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                    <# if ( 0 === data.table.table_postcodes.length ) { #>
                                        <a class="trs-table-postcodes-toggle" href="#">
                                            <?php esc_html_e( 'Limit to specific ZIP/postcodes', 'wcv-table-rate-shipping' ); ?>
                                        </a>
                                    <# } #>
                                </td>
                            </tr>
                            <tr class="trs-table-postcodes"<# if ( 0 === data.table.table_postcodes.length ) { #> style="display: none;"<# } #>>
                                <td>
                                    <label for="wcv_trs_table_postcodes">
                                        <?php esc_html_e( 'Table Postcodes', 'wcv-table-rate-shipping' ); ?>
                                    </label>
                                </td>
                                <td>
                                    <textarea name="wcv_trs_table_postcodes" id="wcv_trs_table_postcodes"
                                              placeholder="<?php esc_attr_e( 'List 1 postcode per line', 'wcv-table-rate-shipping' ); ?>"
                                              class="input-text large-text" cols="25" rows="5">{{ data.table.table_postcodes }}</textarea>
                                    <span class="description">
                                        <?php
                                        esc_html_e(
                                            'Postcodes containing wildcards (e.g. CB23*) or fully numeric ranges (e.g. <code>90210...99000</code>) are also supported.',
                                            'wcv-table-rate-shipping'
                                        );
                                        ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="wcv_trs_is_enabled">
                                        <?php esc_html_e( 'Use Table', 'wcv-table-rate-shipping' ); ?>
                                    </label>
                                </td>
                                <td>
                                    <select name="wcv_trs_is_enabled" id="wcv_trs_is_enabled"
                                            class="wc-enhanced-select">
                                        <option value="yes">
                                            <?php esc_html_e( 'Yes', 'wcv-table-rate-shipping' ); ?>
                                        </option>
                                        <option value="no">
                                            <?php esc_html_e( 'No', 'wcv-table-rate-shipping' ); ?>
                                        </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="wcv_trs_table_fee">
                                        <?php esc_html_e( 'Handling Fee', 'wcv-table-rate-shipping' ); ?>
                                    </label>
                                    <?php
                                    echo wc_help_tip(
                                        esc_html__(
                                            'Handling fee excluding tax. Enter an amount, e.g. 2.50, or a percentage, e.g. 5%. Leave blank to disable.',
                                            'wcv-table-rate-shipping'
                                        )
                                    );
                                    ?>
                                </td>
                                <td>
                                    <input type="text" name="wcv_trs_table_fee" id="wcv_trs_table_fee"
                                           value="{{data.table.table_fee}}" placeholder="0.00">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="wcv_trs_table_method">
                                        <?php esc_html_e( 'Calculation Method', 'wcv-table-rate-shipping' ); ?>
                                    </label>
                                </td>
                                <td>
                                    <select name="wcv_trs_table_method" id="wcv_trs_table_method"
                                            class="wc-enhanced-select">
                                        <?php
                                        $options = array(
                                            'subtotal'    => __( 'Cart Subtotal', 'wcv-table-rate-shipping' ),
                                            'itemcount'   => __( 'Number of Items', 'wcv-table-rate-shipping' ),
                                            'weighttotal' => __( 'Total Weight', 'wcv-table-rate-shipping' ),
                                        );

                                        foreach ( $options as $option => $text ) {
                                            echo "<option value='$option'>$text</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <h3><?php _e( 'Table Rates', 'wcv-table-rate-shipping' ); ?></h3>

                        <table class="wc-shipping-zones widefat wcv-trs-table">
                            <tfoot>
                            <tr>
                                <th colspan="2">
                                    <a class="button button-secondary wcv-trs-rate-add" href="#">
                                        <?php esc_html_e( 'Add rate', 'wcv-table-rate-shipping' ); ?>
                                    </a>
                                </th>
                            </tr>
                            </tfoot>
                            <thead>
                            <tr>
                                <th><span class="wcv-trs-calc-method"><?php _e(
                                            'Value',
                                            'wcv-table-rate-shipping'
                                        ); ?></span> <?php _e( 'Equal To and Above', 'wcv-table-rate-shipping' ); ?>
                                </th>
                                <th><?php _e( 'Shipping Cost', 'wcv-table-rate-shipping' ); ?></th>
                            </tr>
                            </thead>
                            <tbody class="wcv-trs-rates-rows wc-shipping-zone-rows"></tbody>
                        </table>

                        <input type="hidden" name="table_id" value="{{ data.table_id }}">
                        <input type="hidden" name="wcv_trs_table_order" value="{{ data.table.table_order }}">
                    </form>
                </article>
                <footer>
                    <div class="inner">
                        <button id="btn-ok" class="button button-primary button-large"><?php _e(
                                'Save changes',
                                'wcv-table-rate-shipping'
                            ); ?></button>
                    </div>
                </footer>
            </section>
        </div>
    </div>
    <div class="wc-backbone-modal-backdrop modal-close"></div>
</script>

<script type="text/html" id="tmpl-wcv-trs-rate-row-blank">
    <tr>
        <td class="wcv-trs-rates-blank-state wc-shipping-zones-blank-state" colspan="3">
            <p class="main"><?php esc_html_e( "There are no rates for this table.", 'wcv-table-rate-shipping' ); ?></p>
        </td>
    </tr>
</script>

<script type="text/html" id="tmpl-wcv-trs-rate-row">
    <tr data-id="{{ data.rate_id }}">
        <td class="wcv-trs-rate-threshold">
            <span class="wcv-trs-rate-threshold-before"></span>
            <input type="number" name="threshold[{{ data.rate_id }}]" value="{{ data.threshold }}"
                   placeholder="<?php echo wc_format_decimal( 0, wc_get_price_decimals() ); ?>"
                   class="wcv-trs-rate-threshold-input input-text" min="0">
            <span class="wcv-trs-rate-threshold-after"></span>
            <div class="row-actions">
                <a href="#" class="wcv-trs-rate-delete"><?php _e( 'Remove', 'wcv-table-rate-shipping' ); ?></a>
            </div>
        </td>
        <td class="wcv-trsrates">
            <select name="is_percent[{{ data.rate_id }}]">
                <option value="no"><?php echo get_woocommerce_currency_symbol(); ?></option>
                <option value="yes">%</option>
            </select>
            <input type="number" name="rate[{{ data.rate_id }}]" value="{{ data.rate }}"
                   placeholder="<?php echo wc_format_decimal( 0, wc_get_price_decimals() ); ?>" min="0"
                   class="input-text">
        </td>
    </tr>
</script>
