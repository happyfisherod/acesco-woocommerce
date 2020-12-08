<?php

global $product;

wp_dequeue_script( 'jquery-flexslider' );
wp_dequeue_script( 'flexslider' );
wp_dequeue_script( 'zoom' );


//check Enough number image thumbnail
$attachment_ids 		= $product->get_gallery_image_ids();
$count 					= count( $attachment_ids);

if( isset($count) && $count > 0 ) {
	wp_enqueue_script( 'hc-sticky' );
}

$columns           		= apply_filters( 'woocommerce_product_thumbnails_columns', 2 );

$post_thumbnail_id 		= $product->get_image_id();
$placeholder       		= has_post_thumbnail() ? 'with-images' : 'without-images';
$wrapper_classes   		= apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'woocommerce-product-gallery--' . $placeholder,
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
) );

?>



<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">

	<figure class="woocommerce-product-gallery__wrapper">
		<?php
		if ( $product->get_image_id() ) {
  			$html = wc_get_gallery_image_html( $post_thumbnail_id, true );
  		} else {
			$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
			$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_attr__( 'Awaiting product image', 'urna' ) );
			$html .= '</div>';
		}

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
		do_action( 'woocommerce_product_thumbnails' );
		?>
	</figure>
</div>
