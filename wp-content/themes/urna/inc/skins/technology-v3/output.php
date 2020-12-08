<?php if ( ! defined('URNA_THEME_DIR')) exit('No direct script access allowed');

$theme_primary = require_once( get_parent_theme_file_path( URNA_INC . '/class-primary-color.php') );

/*For example $main_color_skin 	= '.top-info > .widget'; */
$main_color_skin 	= '.has-after:hover,button.btn-close:hover,.new-input + span:before,.new-input + label:before,.recent-view h3:hover , .navbar-nav.tbay-menu-category > li:hover > a , .tbay-addon-features .fbox-icon';  
$main_bg_skin 		= '.has-after:after , .btn-theme-2 , #tbay-header .header-mainmenu , #tbay-header .category-menu-wrapper , .tbay-addon.tbay-addon-newsletter .input-group-btn,.tbay-addon ul.list-tags li a:hover , .tbay-addon.tbay-addon-product-tabs .nav-tabs > li.active > a,.tbay-addon.tbay-addon-product-tabs .nav-tabs > li.active > a:hover,.tbay-addon.tbay-addon-product-tabs .nav-tabs > li.active > a:focus,.tbay-addon.tbay-addon-product-tabs .nav-tabs > li:hover > a,.tbay-addon.tbay-addon-product-tabs .nav-tabs > li:hover > a:hover,.tbay-addon.tbay-addon-product-tabs .nav-tabs > li:hover > a:focus,.tbay-addon.tbay-addon-categoriestabs .nav-tabs > li.active > a,.tbay-addon.tbay-addon-categoriestabs .nav-tabs > li.active > a:hover,.tbay-addon.tbay-addon-categoriestabs .nav-tabs > li.active > a:focus,.tbay-addon.tbay-addon-categoriestabs .nav-tabs > li:hover > a,.tbay-addon.tbay-addon-categoriestabs .nav-tabs > li:hover > a:hover,.tbay-addon.tbay-addon-categoriestabs .nav-tabs > li:hover > a:focus,#tbay-main-content .tbay-addon.tbay-addon-flash-sales .tbay-addon-content .owl-carousel:before,#tbay-main-content .tbay-addon.tbay-addon-flash-sales .tbay-addon-content .owl-carousel:after,#tbay-main-content .tbay-addon.product-countdown .tbay-addon-content .owl-carousel:before,#tbay-main-content .tbay-addon.product-countdown .tbay-addon-content .owl-carousel:after';
$main_border_skin 	= '.btn-theme-2,.tbay-addon ul.list-tags li a:hover,.tparrows.revo-tbay:hover , .tbay-addon.tbay-addon-product-tabs .nav-tabs > li.active > a,.tbay-addon.tbay-addon-product-tabs .nav-tabs > li.active > a:hover,.tbay-addon.tbay-addon-product-tabs .nav-tabs > li.active > a:focus,.tbay-addon.tbay-addon-product-tabs .nav-tabs > li:hover > a,.tbay-addon.tbay-addon-product-tabs .nav-tabs > li:hover > a:hover,.tbay-addon.tbay-addon-product-tabs .nav-tabs > li:hover > a:focus,.tbay-addon.tbay-addon-categoriestabs .nav-tabs > li.active > a,.tbay-addon.tbay-addon-categoriestabs .nav-tabs > li.active > a:hover,.tbay-addon.tbay-addon-categoriestabs .nav-tabs > li.active > a:focus,.tbay-addon.tbay-addon-categoriestabs .nav-tabs > li:hover > a,.tbay-addon.tbay-addon-categoriestabs .nav-tabs > li:hover > a:hover,.tbay-addon.tbay-addon-categoriestabs .nav-tabs > li:hover > a:focus,#tbay-main-content .tbay-addon.tbay-addon-flash-sales .tbay-addon-content,#tbay-main-content .tbay-addon.product-countdown .tbay-addon-content';


$main_color 			= $theme_primary['color']; 
$main_bg 				= $theme_primary['background'];
$main_border 			= $theme_primary['border'];
$main_top_border 		= $theme_primary['border-top-color'];
$main_right_border 		= $theme_primary['border-right-color'];
$main_bottom_border 	= $theme_primary['border-bottom-color'];
$main_left_border 		= $theme_primary['border-left-color'];

if( !empty($main_color_skin) ) {
	$main_color 	= $main_color . ',' . $main_color_skin; 
}
if( !empty($main_bg_skin) ) {
	$main_bg 	= $main_bg. ',' .$main_bg_skin; 
}
if( !empty($main_border_skin) ) {
	$main_border 	= $main_border. ',' .$main_border_skin; 
}


/**
 * ------------------------------------------------------------------------------------------------
 * Prepare CSS selectors for theme settions (colors, borders, typography etc.)
 * ------------------------------------------------------------------------------------------------
 */

$output = array();


/*CustomMain color*/
$output['main_color'] = array( 
	'color' => urna_texttrim($main_color),
	'background-color' => urna_texttrim($main_bg),
	'border-color' => urna_texttrim($main_border),
);
if( !empty($main_top_border) ) {

	$bordertop = array(
		'border-top-color' => urna_texttrim($main_top_border),
	);

	$output['main_color'] = array_merge($output['main_color'],$bordertop);
}
if( !empty($main_right_border) ) {
	
	$borderright = array(
		'border-right-color' => urna_texttrim($main_right_border),
	);

	$output['main_color'] = array_merge($output['main_color'],$borderright);
}
if( !empty($main_bottom_border) ) {
	
	$borderbottom = array(
		'border-bottom-color' => urna_texttrim($main_bottom_border),
	);

	$output['main_color'] = array_merge($output['main_color'],$borderbottom);
}
if( !empty($main_left_border) ) {
	
	$borderleft = array(
		'border-left-color' => urna_texttrim($main_left_border),
	);

	$output['main_color'] = array_merge($output['main_color'],$borderleft);
}
/*Theme color second*/
$output['main_color_second'] = array( 
	'color' => urna_texttrim('.color-2 , .tbay-addon-flash-sales .flash-sales-date .times'),
	'background-color' => urna_texttrim('.top-wishlist .count_wishlist,.cart-dropdown .cart-icon .mini-cart-items,.tbay-addon-flash-sales .flash-sales-date .times > div span')
);

/*Custom Fonts*/
$output['primary-font'] = array('body, p, .btn, .button, .rev-btn, .rev-btn:visited, h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .widget-title');

//*Custom Header*/
$output['header_bg'] 					= array(
	'background'=> urna_texttrim('#tbay-header .menu-category-menu-image-container, #tbay-header .header-main')
);
$output['header_text_color'] 			= array('');
$output['header_link_color'] 			= array('#track-order a, .tbay-login .account-button, .topbar-right i, .tbay-custom-language .select-button, .tbay-custom-language .select-button:after, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont > span, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont label i:after, .top-wishlist a, .cart-dropdown .cart-icon, .cart-dropdown .text-cart, .category-inside-title, .navbar-nav.megamenu>li>a, .recent-view h3');

$output['header_link_color_active'] = array( 
	'color' => urna_texttrim('.tbay-login .account-button:hover, #track-order a:hover, .topbar-right a:hover i, .tbay-custom-language li:hover .select-button, .tbay-custom-language .select-button:hover, .tbay-custom-language li:hover .select-button:after, .woocommerce-currency-switcher-form .SumoSelect>.CaptionCont:hover label i:after, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont > span:hover, .top-wishlist a:hover, .cart-dropdown:hover .cart-icon, .cart-dropdown:hover .text-cart, #tbay-header .category-inside-title:hover, #tbay-header .category-inside-title:focus, .navbar-nav.megamenu>li:focus>a, .navbar-nav.megamenu>li:hover>a, .navbar-nav.megamenu>li.active>a, .recent-view h3:hover')
);

/*Custom Top Bar color*/
$output['topbar_bg'] 					= array(
	'background'=> urna_texttrim('')
);
$output['topbar_text_color'] 			= array('');
$output['topbar_link_color'] 			= array('');

$output['topbar_link_color_hover'] = array( 
	'color' => urna_texttrim(''),
	'background-color' => urna_texttrim(''),
);

/*Custom Main Menu*/
$output['main_menu_bg'] 				= array(
	'background'=> urna_texttrim('.tbay-offcanvas-main')
);
$output['main_menu_link_color'] 		= array('.offcanvas-head .btn-toggle-canvas, .tbay-offcanvas-main .navbar-nav > li > a');
$output['main_menu_link_color_active'] 	= array('.tbay-offcanvas-main .navbar-nav.megamenu > li.active > a, .tbay-offcanvas-main .navbar-nav > li:hover > a');


/*Custom Footer*/
$output['footer_bg'] 					= array(
	'background'=> urna_texttrim('.tbay-footer')
);
$output['footer_heading_color'] 		= array('.tbay-footer .tbay-addon .tbay-addon-title');
$output['footer_text_color'] 			= array('.tbay-footer .tbay-addon .tbay-addon-title .subtitle, .contact-info li');
$output['footer_link_color'] 			= array('.contact-info a, .tbay-footer .menu li > a, .copyright a');
$output['footer_link_color_hover'] 		= array('.contact-info a:hover, .tbay-footer .menu li > a:hover, .copyright a:hover');

/*Custom Copyright*/
$output['copyright_bg'] 				= array(
	'background'=> urna_texttrim('.tbay-footer .tbay-copyright')
);
$output['copyright_text_color'] 		= array('.copyright');
$output['copyright_link_color'] 		= array('.copyright a');
$output['copyright_link_color_hover'] 	= array('.copyright a:hover');

/*Background hover*/
$output['background_hover']  	= $theme_primary['background_hover'];
/*Tablet*/
$output['tablet_color'] 	 	= $theme_primary['tablet_color'];
$output['tablet_background'] 	= $theme_primary['tablet_background'];
$output['tablet_border'] 		= $theme_primary['tablet_border'];
/*Mobile*/
$output['mobile_color'] 		= $theme_primary['mobile_color'];
$output['mobile_background'] 	= $theme_primary['mobile_background'];
$output['mobile_border'] 		= $theme_primary['mobile_border'];

return apply_filters( 'urna_get_output', $output);
