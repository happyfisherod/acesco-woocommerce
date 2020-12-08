<?php

/**

 * Dokan shipping table list template.

 *

 * @author  Brett Porcelli <brett@thepluginpros.com>

 * @package wcv-table-rate-shipping

 */



if ( ! defined( 'ABSPATH' ) ) {

    return;

}



$user_id         = dokan_get_current_user_id();

$processing_time = get_user_meta( $user_id, '_dps_pt', true );

$shipping_policy = get_user_meta( $user_id, '_dps_ship_policy', true );

$refund_policy   = get_user_meta( $user_id, '_dps_refund_policy', true );



?>

<form action="" method="post" id="settings-form" class="dokan-form-horizontal trs-form">

    <h2 class="trs-form-heading" id="trs_shipping_policies_heading">

        <?php esc_html_e( 'Tiempo de entrega', 'wcv-table-rate-shipping' ); ?>        

    </h2>



    <div class="dokan-form-group">

        <div class="dokan-w3">

            <label for="processing_time" class="dokan-control-label">

                <?php esc_html_e( 'Tiempo de procesamiento', 'wcv-table-rate-shipping' ); ?>

            </label>

            <span class="dokan-tooltips-help tips" title="<?php esc_attr_e( '¿Cuánto tiempo generalmente te toma preparar los productos para realizar el envío?', 'wcv-table-rate-shipping' ); ?>">

                <i class="fa fa-question-circle"></i>

            </span>

        </div>

        <div class="dokan-w9">

            <select name="processing_time" id="processing_time" class="dokan-form-control">

                <?php foreach ( dokan_get_shipping_processing_times() as $value => $label ): ?>

                    <option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $processing_time ); ?>>

                        <?php echo esc_html( $label ); ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

    </div>



    <div class="dokan-form-group">

        <div class="dokan-w3">

            <label for="shipping_policy" class="dokan-control-label">

                <?php esc_html_e( 'Shipping Policy', 'wcv-table-rate-shipping' ); ?>

            </label>

            <span class="dokan-tooltips-help tips" title="<?php esc_attr_e( 'Tell customers more about how and when your products are shipped', 'wcv-table-rate-shipping' ); ?>">

                <i class="fa fa-question-circle"></i>

            </span>

        </div>

        <div class="dokan-w9">

            <?php printf(

                '<textarea name="shipping_policy" id="shipping_policy" class="dokan-form-control">%s</textarea>',

                esc_html( $shipping_policy )

            ); ?>

        </div>

    </div>



    <div class="dokan-form-group">

        <div class="dokan-w3">

            <label for="refund_policy" class="dokan-control-label">

                <?php esc_html_e( 'Refund Policy', 'wcv-table-rate-shipping' ); ?>

            </label>

            <span class="dokan-tooltips-help tips" title="<?php esc_attr_e( 'Write your terms, conditions, and instructions for refunds', 'wcv-table-rate-shipping' ); ?>">

                <i class="fa fa-question-circle"></i>

            </span>

        </div>

        <div class="dokan-w9">

            <?php printf(

                '<textarea name="refund_policy" id="refund_policy" class="dokan-form-control">%s</textarea>',

                esc_html( $refund_policy )

            ); ?>

        </div>

    </div>



    <div class="dokan-form-group">

        <div class="dokan-w12 ajax_prev">

            <?php wp_nonce_field( 'dokan_trs_policies_nonce' ); ?>

            <button type="submit" name="dokan_update_trs_policies" class="dokan-btn dokan-btn-info">

                <?php esc_html_e( 'Guardar', 'wcv-table-rate-shipping' ); ?>

            </button>

        </div>

    </div>

</form>



<h2 class="trs-form-heading" id="trs_shipping_tables_heading">

    <?php esc_html_e( 'Shipping Tables', 'wcv-table-rate-shipping' ); ?>

</h2>



<?php require wcv_trs()->path( 'includes/views/html-shipping-table-list.php' ); ?>

