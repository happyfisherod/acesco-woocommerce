<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$store_user   = get_userdata( get_query_var( 'author' ) );
$store_info   = dokan_get_store_info( $store_user->ID );
$map_location = isset( $store_info['location'] ) ? $store_info['location'] : '';

get_header();

$content_class = '';
if ( is_active_sidebar('sidebar-store') ) {
    $content_class  .= 'col-md-9';
    $content_class  .= ' pull-right';
} 

 if( is_shop() || is_product_category() ) {
    wp_enqueue_style('sumoselect');
    wp_enqueue_script('jquery-sumoselect'); 
 }

?>

<?php do_action( 'urna_woo_template_main_before' ); ?>

<section id="main-container" class="main-container <?php echo apply_filters('urna_dokan_content_class', 'container');?>">

    <div class="row">

        <div id="main-content" class="archive-shop col-xs-12 <?php echo esc_attr($content_class); ?>">
            <div id="dokan-primary" class="dokan-single-store">
                <div id="dokan-content" class="store-page-wrap woocommerce site-content" role="main">
            
                    <?php dokan_get_template_part( 'store-header' ); ?>

                    <div id="store-toc-wrapper">
                        <div id="store-toc">
                            <?php
                            if( isset( $store_info['store_tnc'] ) ):
                            ?>
                                <h2 class="headline"><?php esc_html_e( 'Terms And Conditions', 'urna' ); ?></h2>
                                <div>
                                    <?php
                                    echo nl2br($store_info['store_tnc']);
                                    ?>
                                </div>
                            <?php
                            endif;
                            ?>
                        </div><!-- #store-toc -->
                    </div><!-- #store-toc-wrap -->
                </div><!-- #content -->
            </div><!-- #primary -->
        </div><!-- #main-content -->
        
        <?php if ( is_active_sidebar('sidebar-store') ) : ?>
            <div id="dokan-secondary" class="col-md-3 col-xs-12">
                <aside class="sidebar sidebar-left" itemscope="itemscope" itemtype="http://schema.org/WPSideBar">
                    <?php dynamic_sidebar( 'sidebar-store'); ?>
                </aside>
            </div>
        <?php endif; ?>

    </div>
</section>
<?php

get_footer();