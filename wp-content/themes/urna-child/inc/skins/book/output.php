<?php if ( ! defined('URNA_THEME_DIR')) exit('No direct script access allowed');

$theme_primary = require_once( get_parent_theme_file_path( URNA_INC . '/class-primary-color.php') );

/*For example $main_color_skin 	= '.top-info > .widget'; */
$main_color_skin 	= '.has-after:hover,button.btn-close:hover,.new-input + span:before,.new-input + label:before,.navbar-nav > li > a:hover,.navbar-nav > li > a:focus,.navbar-nav > li > a:active , .navbar-nav > li:hover > a,.navbar-nav > li:focus > a,.navbar-nav > li.active > a,.recent-view h3:hover,.tbay-addon-categories .item-menu li a:before,.tbay-addon-categories .item-menu li a:hover , .tbay-addon-products-menu-banner.has-banner ul li:hover a , .tbay-addon-products-menu-banner.has-banner ul li:hover:before , .tbay-addon-blog:not(.vertical):not(.relate-blog) .readmore';  
$main_bg_skin 		= '.has-after:after , .btn-theme-2 , .category-inside.vertical-menu,.tbay-addon-products:not(.tbay-addon-vertical).tbay-addon-flash-sales .tbay-addon-content .owl-carousel:before,.tbay-addon-products:not(.tbay-addon-vertical).tbay-addon-flash-sales .tbay-addon-content .owl-carousel:after,.tbay-addon-products:not(.tbay-addon-vertical).tbay-addon-flash-sales .tbay-addon-content .row.grid:before,.tbay-addon-products:not(.tbay-addon-vertical).tbay-addon-flash-sales .tbay-addon-content .row.grid:after,.tbay-addon-products:not(.tbay-addon-vertical) .product-countdown .tbay-addon-content .owl-carousel:before,.tbay-addon-products:not(.tbay-addon-vertical) .product-countdown .tbay-addon-content .owl-carousel:after,.tbay-addon-products:not(.tbay-addon-vertical) .product-countdown .tbay-addon-content .row.grid:before,.tbay-addon-products:not(.tbay-addon-vertical) .product-countdown .tbay-addon-content .row.grid:after';
$main_border_skin 	= '.btn-theme-2,.tparrows.revo-tbay:hover,.tbay-addon-products:not(.tbay-addon-vertical).tbay-addon-flash-sales .tbay-addon-content,.tbay-addon-products:not(.tbay-addon-vertical) .product-countdown .tbay-addon-content';


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
	'color' => urna_texttrim('.tbay-addon-social .social.style3 li a:hover,.tbay-homepage-demo .tbay-addon-features .fbox-icon i'),
	'background-color' => urna_texttrim('#tbay-header .header-mainmenu , .cart-dropdown .cart-icon .mini-cart-items , .top-wishlist .count_wishlist , .tbay-addon-flash-sales .progress-bar , .tbay-addon-blog .entry-category a'),
	'border-color' => urna_texttrim('')
);

/*Custom Fonts*/
$output['primary-font'] = array('body, p, .btn, .button, .rev-btn, .rev-btn:visited');
$output['secondary-font'] = array('.tbay-homepage-demo #tbay-main-content .tbay-addon .tbay-addon-title, .tbay-homepage-demo #tbay-main-content .tbay-addon .tbay-addon-heading');

/*Custom Header*/
$output['header_bg'] 					= array(
	'background'=> urna_texttrim('#tbay-header .header-main, .topbar, #tbay-header .header-mainmenu')
);
$output['header_text_color'] 			= array('.top-contact .content');
$output['header_link_color'] 			= array('.woocommerce-currency-switcher-form .SumoSelect > .CaptionCont, .tbay-custom-language .select-button, .menu-my-order-container a, .topbar a i, .tbay-login a span, .top-wishlist a, .cart-dropdown .cart-icon,.yith-compare-header i,.navbar-nav > li > a,.recent-view h3,.tbay-search-form .button-search.icon i, .tbay-custom-language .select-button:after');

$output['header_link_color_active'] = array( 
	'color' => urna_texttrim('.woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover label i:after, .tbay-custom-language .select-button:hover,.tbay-custom-language .select-button:hover:after, .tbay-custom-language li:hover .select-button, .tbay-custom-language li:hover .select-button:after, .menu-my-order-container a:hover, .topbar a:hover i, .tbay-login a:hover span, .top-wishlist a:hover, .cart-dropdown:hover .cart-icon,.yith-compare-header i:hover,.navbar-nav > li > a:hover,.navbar-nav > li:hover > a,.navbar-nav > li.active > a,.recent-view h3:hover,.tbay-custom-language .select-button:hover span,.tbay-search-form .button-search.icon i:hover'),
	'background-color' => urna_texttrim(''),
);

/*Custom Top Bar color*/
$output['topbar_bg'] 					= array(
	'background'=> urna_texttrim('.topbar')
);
$output['topbar_text_color'] 			= array('.top-contact .content');
$output['topbar_link_color'] 			= array('#tbay-header .social > li a, .tbay-custom-language .select-button:after, .tbay-custom-language .select-button, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont > label i:after, .tbay-login > a');

$output['topbar_link_color_hover'] = array( 
	'color' => urna_texttrim('#tbay-header .social > li a:hover, .tbay-custom-language li:hover .select-button, .tbay-custom-language li:hover .select-button:after,  .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover > label i:after, .tbay-login > a:hover'),
	'background-color' => urna_texttrim(''),
);

/*Custom Main Menu*/
$output['main_menu_bg'] 				= array(
	'background'=> urna_texttrim('#tbay-header .tbay-mainmenu')
);
$output['main_menu_link_color'] 		= array('.navbar-nav > li > a');
$output['main_menu_link_color_active'] 	= array('.navbar-nav > li > a:hover, .navbar-nav > li:hover > a, .navbar-nav > li:focus > a, .navbar-nav > li.active > a');


/*Custom Footer*/
$output['footer_bg'] 					= array(
	'background'=> urna_texttrim('.tbay-footer')
);
$output['footer_heading_color'] 		= array('.tbay-footer .tbay-addon .tbay-addon-title');
$output['footer_text_color'] 			= array('.tbay-footer .tbay-addon .tbay-addon-title .subtitle, .contact-info li, .tbay-footer .wpb_text_column p');
$output['footer_link_color'] 			= array('.contact-info a, .tbay-footer .menu.treeview li > a, .tbay-addon-social .social.style3 > li a,.tbay-footer .tbay-copyright .menu li a, .tbay-copyright .color');
$output['footer_link_color_hover'] 		= array('.contact-info a:hover, .tbay-footer .menu.treeview li > a:hover, #tbay-footer .menu li.active > a, .tbay-addon-social .social.style3 > li a:hover,.tbay-footer .tbay-copyright .menu li a:hover, .tbay-copyright .color:hover');

/*Custom Copyright*/
$output['copyright_bg'] 				= array(
	'background'=> urna_texttrim('.tbay-footer .tbay-copyright')
);
$output['copyright_text_color'] 		= array('.tbay-footer .tbay-copyright .wpb_text_column');
$output['copyright_link_color'] 		= array('.tbay-footer .tbay-copyright .menu li a, .tbay-copyright .color');
$output['copyright_link_color_hover'] 	= array('.tbay-footer .tbay-copyright .menu li a:hover, .tbay-copyright .color:hover');

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
