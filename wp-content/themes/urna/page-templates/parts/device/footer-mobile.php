<?php   global $woocommerce; 

    if( defined('URNA_WOOCOMMERCE_CATALOG_MODE_ACTIVED') || !defined('URNA_WOOCOMMERCE_ACTIVED') || is_product() || is_cart() || is_checkout() ) return;

?>

<?php
    /**
     * urna_before_topbar_mobile hook
     */
    do_action( 'urna_before_footer_mobile' );
?>
<div class="footer-device-mobile hidden-md hidden-lg clearfix">

    <?php
        /**
        * urna_before_footer_mobile hook
        */
        do_action( 'urna_before_footer_mobile' );

        /**
        * Hook: urna_footer_mobile_content.
        *
        * @hooked urna_the_icon_home_footer_mobile - 5
        * @hooked urna_the_icon_wishlist_footer_mobile - 10
        * @hooked urna_the_icon_order_footer_mobile - 15
        * @hooked urna_the_icon_account_footer_mobile - 20
        */

        do_action( 'urna_footer_mobile_content' );

        /**
        * urna_after_footer_mobile hook
        */
        do_action( 'urna_after_footer_mobile' );
    ?>

</div>