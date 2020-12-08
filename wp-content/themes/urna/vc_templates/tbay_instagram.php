<?php

if ( !(defined('URNA_CORE_ACTIVED') && URNA_CORE_ACTIVED) ) return;

$_id = urna_tbay_random_key();
wp_enqueue_script( 'slick' );
wp_enqueue_script( 'urna-slick' );

$link = $style = $columns = $screen_desktop = $screen_desktopsmall = $screen_tablet = $screen_landscape_mobile = $screen_mobile = $rows = $nav_type = $pagi_type = $loop_type = $auto_type = $autospeed_type = $disable_mobile = $el_class = $css = $css_animation = $disable_mobile = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$loop_type = $auto_type = $autospeed_type = '';
extract( $atts );

//parse link
$link = ( '||' === $link ) ? '' : $link; 
$link = vc_build_link( $link );

$btn_follow              =      isset($btn_follow) ? $btn_follow : false;
$rows_count = $rows;

$data_responsive  = urna_tbay_checK_data_responsive_grid($columns, $screen_desktop, $screen_desktopsmall, $screen_tablet, $screen_landscape_mobile, $screen_mobile);

$data_infor = ' data-number="'. $number .'"  data-username="'. $username .'" data-image_size="'. $size .'"  data-id="#instagram-feed'. $_id .'" data-time_ago="true" data-like="true" data-comment="true" ';

$css = isset( $atts['css'] ) ? $atts['css'] : '';
$el_class = isset( $atts['el_class'] ) ? $atts['el_class'] : '';

$class_to_filter  = 'tbay-addon tbay-addon-instagram';
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class        = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

?>
<div class="<?php echo esc_attr($css_class); ?>">

    <?php if( (isset($subtitle) && $subtitle) || (isset($title) && $title)  ): ?>
        <h3 class="tbay-addon-title">
            <?php if ( isset($title) && $title ): ?>
                <span><?php echo trim( $title ); ?></span>
            <?php endif; ?>
            <?php if ( isset($subtitle) && $subtitle ): ?>
                <span class="subtitle"><?php echo trim($subtitle); ?></span>
            <?php endif; ?>
        </h3>
    <?php endif; ?>

    <?php 


    if ( !empty($username) ) {

        if( !function_exists( 'urna_core_scrape_instagram' ) ) return;

        
        $media_array = urna_core_scrape_instagram( $username );
 
        if ( is_wp_error( $media_array ) ) {

            wp_enqueue_script( 'jquery-timeago' );
            wp_enqueue_script( 'jquery-instagramfeed' );
            
            if( isset($layout_type) && $layout_type == 'carousel' ) : ?>

                <?php 

                    $data_carousel = urna_tbay_data_carousel($rows, $nav_type, $pagi_type, $loop_type, $auto_type, $autospeed_type, $disable_mobile); 
                    $responsive_carousel  = urna_tbay_checK_data_responsive_carousel($columns, $screen_desktop, $screen_desktopsmall, $screen_tablet, $screen_landscape_mobile, $screen_mobile);
                ?>
                <div id="instagram-feed<?php echo esc_attr($_id); ?>" class="owl-carousel instagram-feed slick-instagram" <?php echo trim($responsive_carousel); ?>  <?php echo trim($data_carousel); ?>  <?php echo trim($data_infor); ?>></div>
            <?php else : ?>
                <div id="instagram-feed<?php echo esc_attr($_id); ?>" class="row <?php echo esc_attr($layout_type); ?> instagram-feed" <?php echo trim($data_responsive); ?>  <?php echo trim($data_infor); ?>></div>
            <?php endif;
            if( $btn_follow == 'yes' ) : ?>
                <?php
                    $username   = trim( strtolower( $username ) );
                    $url        = 'https://instagram.com/' . str_replace( '@', '', $username );
                ?>

                <a class="btn-follow" href="<?php echo esc_url($url); ?>">
                    <?php echo esc_html_e('Follow ', 'urna'); ?><span>@<?php echo trim($username); ?></span>
                </a>
            <?php endif;

        } else {

            // filter for images only?
            if ( $images_only = apply_filters( 'urna_core_instagram_widget_images_only', FALSE ) ) {
                $media_array = array_filter( $media_array, 'urna_core_images_only' );
            }


            // slice list down to required number
            $media_array = array_slice( $media_array, 0, $number );

            if( isset($layout_type) && $layout_type == 'carousel' ) : ?>

                <?php 

                    $data_carousel = urna_tbay_data_carousel($rows, $nav_type, $pagi_type, $loop_type, $auto_type, $autospeed_type, $disable_mobile); 
                    $responsive_carousel  = urna_tbay_checK_data_responsive_carousel($columns, $screen_desktop, $screen_desktopsmall, $screen_tablet, $screen_landscape_mobile, $screen_mobile);
                ?>
                <div class="owl-carousel slick-instagram" <?php echo trim($responsive_carousel); ?>  <?php echo trim($data_carousel); ?> >
                    <?php 

                        foreach ( $media_array as $item ) { ?>

                        <div class="item">

                            <div class="instagram-item-inner">
                                <a href="<?php echo esc_url( $item['link'] ); ?>" class="tbay-image-loaded" target="<?php echo esc_attr( $target ); ?>">

                                    <span class="group-items"> 
                                            <span class="likes"><i class="linear-icon-heart"></i><?php echo esc_html($item['likes']);?></span>

                                            <span class="comments"><i class="linear-icon-bubbles"></i><?php echo esc_html($item['comments']);?></span>
                                    </span>
                                    <?php
                                        $time  = $item['time'];
                                    ?>
                                    <?php if( isset($time) && $time ) : ?>
                                        <span class="time elapsed-time"><?php  echo urna_core_time_ago($time,1); ?></span>
                                    <?php endif; ?>

                                    <?php urna_tbay_src_image_loaded($item[$size], array('alt'=> $item['description'] )); ?>
                                </a>
                            </div>

                        </div>

                    <?php } ?>
                </div>
            <?php else : ?>
                <div class="row <?php echo esc_attr($layout_type); ?>" <?php echo trim($data_responsive); ?>>
                    <?php
                    foreach ( $media_array as $item ) { ?>

                    <div class="item">
                        <div class="instagram-item-inner">
                            <a href="<?php echo esc_url( $item['link'] ); ?>" class=" tbay-image-loaded" target="<?php echo esc_attr( $target ); ?>">

                                <span class="group-items"> 
                                        <span class="likes"><i class="linear-icon-heart"></i><?php echo esc_html($item['likes']);?></span>

                                        <span class="comments"><i class="linear-icon-bubbles"></i><?php echo esc_html($item['comments']);?></span>
                                </span>
                                <?php
                                    $time  = $item['time'];
                                ?>
                                <?php if( isset($time) && $time ) : ?>
                                    <span class="time elapsed-time"><?php  echo urna_core_time_ago($time,1); ?></span>
                                <?php endif; ?>

                                <?php urna_tbay_src_image_loaded($item[$size], array('alt'=> $item['description'] )); ?>
                            </a>
                        </div>

                    </div>
                    <?php } ?>
                </div>
            <?php endif;
            if( $btn_follow == 'yes' ) : ?>
                <?php
                    $username   = trim( strtolower( $username ) );
                    $url        = 'https://instagram.com/' . str_replace( '@', '', $username );
                ?>

                <a class="btn-follow" href="<?php echo esc_url($url); ?>">
                    <?php echo esc_html_e('Follow ', 'urna'); ?><span>@<?php echo trim($username); ?></span>
                </a>
            <?php endif;
        }
    } ?>

</div>