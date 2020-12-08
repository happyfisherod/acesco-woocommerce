<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="//gmpg.org/xfn/11" />
	<script>    			
            var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";	
	</script>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="wrapper-container" class="wrapper-container">
	<?php urna_tbay_get_page_templates_parts('device/offcanvas-smartmenu'); ?>
	<?php urna_tbay_the_topbar_mobile(); ?>
		<?php 
		if( urna_tbay_get_config('mobile_footer_icon',true) ) {
			urna_tbay_get_page_templates_parts('device/footer-mobile');
		}
	 ?>
	<?php get_template_part( 'page-templates/header' ); ?>
	<div id="tbay-main-content">