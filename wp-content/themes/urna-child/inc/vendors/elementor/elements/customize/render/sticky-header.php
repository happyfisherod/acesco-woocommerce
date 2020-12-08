<?php

if ( !function_exists('urna_before_render_sticky_header')) {
    function urna_before_render_sticky_header( $widget ) {

        if ( function_exists( 'is_product' ) ) {
            $menu_bar   =  apply_filters( 'woo_product_menu_bar', 10, 2 );
            $single_layout = ( isset($_GET['product_single_layout']) )   ?   $_GET['product_single_layout'] :  urna_tbay_get_config('product_single_layout', 'full-width-horizontal');

            if( is_product() &&  ($menu_bar || $single_layout === 'full-width-centered' )  )  return;
        }
 
        $settings = $widget->get_settings_for_display();
 
        if( !isset($settings['enable_sticky_headers']) ) return;

        if( $settings['enable_sticky_headers'] === 'yes' ) {
            $widget->add_render_attribute('_wrapper', 'class', 'element-sticky-header');
        }

    }

    add_action('elementor/frontend/section/before_render', 'urna_before_render_sticky_header', 10, 2 );
}

