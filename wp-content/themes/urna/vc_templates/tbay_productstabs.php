<?php

$el_class = $css = $css_animation = $disable_mobile = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$loop_type = $auto_type = $autospeed_type = '';
extract( $atts );

if ( $producttabs == '' ) return; 


$_id = urna_tbay_random_key();
$_count = 1;

$list_query = $this->getListQuery( $atts );

$responsive = urna_tbay_checK_data_responsive($screen_desktop, $screen_desktopsmall, $screen_tablet, $screen_landscape_mobile, $screen_mobile);

$show_des = ( isset($show_des) ) ? $show_des : false ;

$css = isset( $atts['css'] ) ? $atts['css'] : '';
$el_class = isset( $atts['el_class'] ) ? $atts['el_class'] : '';

$class_to_filter = 'tbay-addon tbay-addon-products tbay-addon-product-tabs products tbay-addon-'. $layout_type .' ';

if( isset($tab_title_center) && $tab_title_center == 'yes' ) {
	$class_to_filter .= 'title-center ';
}

$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation );
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );



if ( count($list_query) > 0 ) {
?>
	<div class="<?php echo esc_attr($css_class); ?>">
		<div class="tabs-container tab-heading <?php echo ( isset($title) && $title ) ? 'has-title' : ''; ?>">
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
			<ul class="tabs-list nav nav-tabs">
				<?php $__count=0; ?>
				<?php foreach ($list_query as $key => $li) { ?>
						<?php 
							$active = ($__count==0)? 'active':''; 
						?>
						 <li class="<?php echo esc_attr( $active ); ?>">
						 	<a href="#<?php echo esc_attr($key.'-'.$_id); ?>" data-toggle="tab" data-title="<?php echo esc_attr($li['title']);?>"><?php echo trim($li['title']);?></a>
						 </li>
					<?php $__count++; ?>
				<?php } ?>
			</ul>
		</div>


		<?php if(  $layout_type == 'carousel' ) { ?>

			<div class="tbay-addon-content tab-content woocommerce">
				<?php $__count=0; ?>
				<?php foreach ($list_query as $key => $li) { ?>
					<?php

						if (isset($categories) && !empty($categories) && strpos($categories, ',') !== false) {
						    $category = explode(',', $categories);
						    $category = urna_tbay_get_category_by_id($category);

						    $loop = urna_tbay_get_products( $category, $key, 1, $number ); 
						} else if( isset($categories) && !empty($categories) ) {
						    $category = get_term_by( 'id', $categories, 'product_cat' )->slug;

						    $loop = urna_tbay_get_products( array($category), $key, 1, $number ); 
						} else {

						    $loop = urna_tbay_get_products( '', $key, 1, $number ); 
						}
						
					?>

					<?php 
						$tab_active = ($__count == 0) ? ' active' : '';
					?>
					<div class="tab-pane <?php echo esc_attr( $tab_active ); ?>" id="<?php echo esc_attr($key).'-'.$_id; ?>">
						<div class="grid-wrapper">
							<?php

								if ( $loop->have_posts()) {

									wc_get_template( 'layout-products/'.$layout_type.'.php' , array( 'loop' => $loop, 'loop_type' => $loop_type, 'show_des' => $show_des, 'auto_type' => $auto_type, 'autospeed_type' => $autospeed_type, 'columns' => $columns, 'rows' => $rows, 'pagi_type' => $pagi_type, 'nav_type' => $nav_type,'responsive_type' => $responsive_type,'screen_desktop' => $responsive['desktop'],'screen_desktopsmall' => $responsive['desktopsmall'],'screen_tablet' => $responsive['tablet'], 'screen_landscape_mobile' => $responsive['landscape'], 'screen_mobile' => $responsive['mobile'], 'number' => $number, 'disable_mobile' => $disable_mobile ) );
								}
							?>
						</div>

					</div>
					<?php $__count++; ?>
				<?php } ?>
			</div>

		<?php } else { ?>

			<div class="tbay-addon-content tab-content woocommerce">
				<?php $__count=0; ?>
				<?php foreach ($list_query as $key => $li) { ?>

					<?php
						if (isset($categories) && !empty($categories) && strpos($categories, ',') !== false) {
						    $category = explode(',', $categories);
						    $category = urna_tbay_get_category_by_id($category);

						    $loop = urna_tbay_get_products( $category, $key, 1, $number ); 
						} else if( isset($categories) && !empty($categories) ) {
						    $category = get_term_by( 'id', $categories, 'product_cat' )->slug;

						    $loop = urna_tbay_get_products( array($category), $key, 1, $number ); 
						} else {

						    $loop = urna_tbay_get_products( '', $key, 1, $number ); 
						}
					?>

					<?php 
						$tab_active = ($__count == 0) ? ' active' : '';
					?>
					<div class="tab-pane <?php echo esc_attr( $tab_active ); ?>" id="<?php echo esc_attr($key).'-'.$_id; ?>">
						<div class="grid-wrapper products-grid">
							<?php

								if ( $loop->have_posts()) {
									
									wc_get_template( 'layout-products/'.$layout_type.'.php' , array( 'layout_type' => $layout_type, 'loop' => $loop, 'show_des' => $show_des, 'columns' => $columns, 'responsive_type' => $responsive_type, 'screen_desktop' => $responsive['desktop'],'screen_desktopsmall' => $responsive['desktopsmall'],'screen_tablet' => $responsive['tablet'], 'screen_landscape_mobile' => $responsive['landscape'],'screen_mobile' => $responsive['mobile'], 'number' => $number ) );
								}
							?>
						</div>

					</div>
					<?php $__count++; ?>
				<?php } ?>
			</div>		

            <?php if(isset($show_view_all) && $show_view_all == 'yes') : ?>
            	<?php 

                	$url = get_permalink( wc_get_page_id( 'shop' ) );

                ?>
            	<a href="<?php echo esc_url( $url ); ?>" class="show-all"><?php echo trim($button_text_view_all); ?></a>
            <?php endif; ?>	

		<?php } ?>

	</div>
<?php wp_reset_postdata(); ?>
<?php } ?>

