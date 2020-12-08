<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;




global $product;



$styles   				= 	apply_filters( 'woo_class_single_product', 10, 2 );

$sidebar_configs  		= urna_tbay_get_woocommerce_layout_configs();

$product_single_layout  =   ( isset($_GET['product_single_layout']) )   ?   $_GET['product_single_layout'] :  urna_get_single_select_layout();

$product_single_sidebar_position    =  ( isset($_GET['product_single_sidebar_position']) )   ?   $_GET['product_single_sidebar_position'] :  urna_tbay_get_config('product_single_sidebar_position', 'inner-sidebar');
?>

<?php if(isset($_GET['proID']) ){ ?>
	<div class="woocommerce-notices-wrapper">
		<div class="woocommerce-message" role="alert">

			 “<?php echo $product->name; ?>” se agregó a tu selección.<a href="<?php echo get_site_url(); ?>/carro-2/" tabindex="1" class="button wc-forward cartPage">Ver mi selección de productos</a>	</div>
	</div>

<?php } ?>


<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 * @hooked urna_woo_product_single_time_countdown - 20
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( $styles, $product ); ?>>

	<?php 
		
		switch ($product_single_layout) {
			case 'full-width-centered':
			case 'full-width-carousel':
			case 'full-width-stick':
			case 'full-width-gallery':
			case 'full-width-horizontal':
				wc_get_template( 'single-product/contents/'.$product_single_layout.'.php');
				break;
			
			default:
				wc_get_template( 'single-product/contents/normal.php', array('sidebar_configs' => $sidebar_configs, 'inner_sidebar' => $product_single_sidebar_position ));
				break;
		}
		
	?>

</div><!-- #product-<?php the_ID(); ?> -->
<script>
jQuery('.variation_id').on('change', function(){
	var variationID = jQuery(this).val();
	var url = '<?php echo get_permalink($product->ID); ?>';
	var pid = '<?php the_ID(); ?>';
	var cant = jQuery('.input-text.qty.text').val();
	var finalUrl = url+'?varID='+variationID+'&proID='+ pid +'&cant=' + cant;
	$('.cartButton').attr('href', finalUrl);
});


jQuery('.input-text.qty.text').on('change', function(){
	$('.cartButton').attr('href','');
	var variationID = jQuery('.variation_id').val();
	var url = '<?php echo get_permalink($product->ID); ?>';
	var pid = '<?php the_ID(); ?>';
	var cant = jQuery(this).val();
	var finalUrl = url+'?varID='+variationID+'&proID='+ pid +'&cant=' + cant;

	$('.cartButton').attr('href', finalUrl);
});


</script>

<?php do_action( 'woocommerce_after_single_product' ); 