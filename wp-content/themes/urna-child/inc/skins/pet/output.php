<?php if ( ! defined('URNA_THEME_DIR')) exit('No direct script access allowed');

$theme_primary = require_once( get_parent_theme_file_path( URNA_INC . '/class-primary-color.php') );

/*For example $main_color_skin 	= '.top-info > .widget'; */
$main_color_skin 	= '.has-after:hover,button.btn-close:hover,.new-input + span:before,.new-input + label:before,#tbay-header .cart-dropdown .text-cart:hover,#tbay-header #track-order a:hover,#tbay-header .yith-compare-header a:hover , .tbay-search-form .button-group:before,#tbay-header .tbay-search-form .button-search.icon:hover,.tbay-addon.tbay-addon-features:not(.style-2) .ourservice-heading,.product-block.v3 .group-buttons > div a:hover,.product-block.v3 .group-buttons > div a.added,.product-block.v3 .group-buttons > div a.added:hover,#tbay-search-form-canvas.v4 button:hover,#tbay-search-form-canvas.v4 button:hover i,#tbay-search-form-canvas.v4 .sidebar-canvas-search .sidebar-content .select-category .optWrapper .options li:hover label , #tbay-search-form-canvas.v4 .autocomplete-suggestions > div .suggestion-group:hover .suggestion-title';  
$main_bg_skin 		= '.has-after:after , .btn-theme-2 , .header-mainmenu,.tbay-to-top a:hover,.tbay-addon .owl-carousel > .slick-arrow:hover,.tbay-addon .owl-carousel > .slick-arrow:focus,.pet-pharmacy > div .tbay-addon-text-heading .description i,.tbay-addon.tbay-addon-features:not(.style-2) .inner .fbox-icon:hover,.tbay-addon-flash-sales.tbay-bottom .show-all:hover';
$main_border_skin 	= '.btn-theme-2,.tbay-addon-testimonials .testimonials-body:hover,.tbay-addon-flash-sales.tbay-bottom .show-all:hover ';
$main_border_top_skin 	= '#tbay-search-form-canvas.v4 .tbay-loading:after';

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
if( !empty($main_border_top_skin) ) {
	$main_top_border 	= $main_top_border. ',' .$main_border_top_skin; 
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

/*Custom Fonts*/
$output['primary-font'] = array('body, p, .btn, .button, .rev-btn, .rev-btn:visited, h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6');
$output['secondary-font'] = array('.cart-dropdown .subtotal,.woocs_price_code, .woocommerce-grouped-product-list-item__price, .yith-wfbt-submit-block .price_text > span.total_price, .woocs_special_price_code, .woocommerce-order-received .woocommerce-order table.shop_table .woocommerce-Price-amount, .tbay-addon-testimonials .testimonial-meta .name-client, .tbay-homepage-demo .tbay-addon.tbay-addon-features:not(.style-2) .ourservice-heading, .tbay-homepage-demo .tbay-footer .tbay-addon:not(.tbay-addon-newsletter) .tbay-addon-title, .tbay-homepage-demo .contact-info a, .tbay-homepage-demo .title-about, .tbay-homepage-demo .pet-pharmacy > div .tbay-addon-text-heading .description, .tbay-homepage-demo #tbay-main-content .tbay-addon .tbay-addon-title, .tbay-homepage-demo #tbay-main-content .tbay-addon .tbay-addon-heading');

/*Custom Header*/
$output['header_bg'] 					= array(
	'background'=> urna_texttrim('#tbay-header .header-main,.header-mainmenu')
);
$output['header_text_color'] 			= array('#tbay-header p,.cart-dropdown .text-cart');
$output['header_link_color'] 			= array('#tbay-header .tbay-login > a,#tbay-header .cart-dropdown > a,#track-order a, .yith-compare-header a,.category-inside-title,.category-inside-title:focus,.category-inside-title:hover,.navbar-nav > li > a,.recent-view h3');

$output['header_link_color_active'] = array( 
	'color' => urna_texttrim('#tbay-header .tbay-login > a:hover,#tbay-header .cart-dropdown > a:hover,#track-order a:hover, .yith-compare-header a:hover,.navbar-nav > li > a:hover,.navbar-nav > li > a:focus,.navbar-nav > li:hover > a,.navbar-nav > li.active > a,.recent-view:hover h3'),
	'background-color' => urna_texttrim(''),
);

/*Custom Top Bar color*/
$output['topbar_bg'] 					= array(
	'background'=> urna_texttrim('')
);
$output['topbar_text_color'] 			= array('');
$output['topbar_link_color'] 			= array('');

$output['topbar_link_color_hover'] = array('');

/*Custom Main Menu*/
$output['main_menu_bg'] 				= array(
	'background'=> urna_texttrim('#tbay-header .header-mainmenu')
);
$output['main_menu_link_color'] 		= array('.navbar-nav > li > a,.category-inside-title,.category-inside-title:focus,.category-inside-title:hover,.recent-view h3');
$output['main_menu_link_color_active'] 	= array('.navbar-nav > li.active > a, .navbar-nav > li:hover > a, .navbar-nav > li:focus > a,.navbar-nav > li:focus > a:hover,.navbar-nav > li:focus > a:focus,.recent-view:hover h3');


/*Custom Footer*/
$output['footer_bg'] 					= array(
	'background'=> urna_texttrim('.tbay-footer,.tbay-footer .tbay-copyright')
);
$output['footer_heading_color'] 		= array('.tbay-addon .tbay-addon-title, .tbay-addon .tbay-addon-heading,.text-black');
$output['footer_text_color'] 			= array('.tbay-footer .tbay-copyright p,.tbay-footer p,.contact-info li');
$output['footer_link_color'] 			= array('.tbay-footer .menu li > a,.tbay-footer a,.tbay-footer .post .entry-date, .tbay-footer .post .comments-link, .tbay-footer .post .comments-link a');
$output['footer_link_color_hover'] 		= array('.tbay-footer .menu li > a:hover,.tbay-footer .menu li > a:focus,.tbay-footer .menu li:hover > a,.tbay-footer .menu li:focus > a,.tbay-footer .menu li.active > a,.tbay-footer a:hover,.tbay-footer a:focus,.wpb_text_column a, .wpb_text_column a:hover, .wpb_text_column a:focus,
.tbay-footer .post .entry-date:hover, .tbay-footer .post .comments-link:hover, .tbay-footer .post .comments-link a:hover,.tbay-footer .post .entry-date:focus, .tbay-footer .post .comments-link:focus, .tbay-footer .post .comments-link a:focus');

/*Custom Copyright*/
$output['copyright_bg'] 				= array(
	'background'=> urna_texttrim('.tbay-footer .tbay-copyright')
);
$output['copyright_text_color'] 		= array('.tbay-footer .tbay-copyright p');
$output['copyright_link_color'] 		= array('.tbay-footer .tbay-copyright a,.tbay-copyright .none-menu .menu li a');
$output['copyright_link_color_hover'] 	= array('.tbay-footer .tbay-copyright a:hover,.tbay-footer .tbay-copyright a:focus,.tbay-footer .tbay-copyright .wpb_text_column a,.tbay-footer .tbay-copyright .wpb_text_column a:hover,.tbay-footer .tbay-copyright .wpb_text_column a:focus,.tbay-copyright .none-menu .menu li a:hover,.tbay-copyright .none-menu .menu li a:focus,.tbay-copyright .none-menu .menu li:hover a,.tbay-copyright .none-menu .menu li:focus a,.tbay-copyright .none-menu .menu li.active a');

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
