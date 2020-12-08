<?php if ( ! defined('URNA_THEME_DIR')) exit('No direct script access allowed');

$theme_primary = require_once( get_parent_theme_file_path( URNA_INC . '/class-primary-color.php') );

/*For example $main_color_skin 	= '.top-info > .widget'; */
$main_color_skin 	= '.has-after:hover,button.btn-close:hover,.new-input + span:before,.new-input + label:before , #tbay-header .vertical-menu .category-inside-title,#tbay-header .vertical-menu .category-inside-title:hover,#tbay-header .vertical-menu .category-inside-title:focus,#tbay-header .tbay-custom-language .select-button:hover,#tbay-header .tbay-custom-language .select-button:focus,#tbay-header .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover,#tbay-header .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:focus,#tbay-header .tbay-login > a:hover,#tbay-header .tbay-login > a:focus,#tbay-header .top-wishlist > a:hover,#tbay-header .top-wishlist > a:focus,#tbay-header .cart-dropdown > a:hover,#tbay-header .cart-dropdown > a:focus,#tbay-header .track-order a:hover,#tbay-header .track-order a:focus,#tbay-header .recent-view h3:hover,#tbay-header .recent-view h3:focus , #tbay-header .navbar-nav > li:hover > a,#tbay-header .navbar-nav > li:focus > a,#tbay-header .navbar-nav > li.active > a,#tbay-header .navbar-nav > li:active > a,#tbay-header .navbar-nav > li > a:hover,#tbay-header .navbar-nav > li > a:focus,.tbay-footer .tbay-addon-features .fbox-icon,#tbay-search-form-canvas.v4 button:hover,#tbay-search-form-canvas.v4 button:hover i,#tbay-search-form-canvas.v4 .sidebar-canvas-search .sidebar-content .select-category .optWrapper .options li:hover label , #tbay-search-form-canvas.v4 .autocomplete-suggestions > div .suggestion-group:hover .suggestion-title';  
$main_bg_skin 		= '.has-after:after , .btn-theme-2 , #tbay-header .tbay-search-form .button-search.icon , #tbay-header .tbay-vertical > li.view-all-menu > a,.owl-carousel > .slick-arrow:hover,.product-block:not(.vertical).v7 .group-buttons > div a:hover,.product-block:not(.vertical).v7 .group-buttons > div.add-cart a.added + a.added_to_cart,.product-block:not(.vertical).v7 .group-buttons > div .yith-wcwl-wishlistexistsbrowse a,.product-block:not(.vertical).v7 .group-buttons > div .yith-wcwl-wishlistaddedbrowse a,.product-block:not(.vertical).v7 .group-buttons > div.button-wishlist a.delete_item,.product-block:not(.vertical).v7 .group-buttons > div.yith-compare a.added';
$main_border_skin 	= '.btn-theme-2,.product-block:not(.vertical).v7 .group-buttons > div.add-cart a.added + a.added_to_cart,.product-block:not(.vertical).v7 .group-buttons > div .yith-wcwl-wishlistexistsbrowse a,.product-block:not(.vertical).v7 .group-buttons > div .yith-wcwl-wishlistaddedbrowse a,.product-block:not(.vertical).v7 .group-buttons > div.button-wishlist a.delete_item,.product-block:not(.vertical).v7 .group-buttons > div.yith-compare a.added,.product-block:not(.vertical).v7:hover';
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
$output['primary-font'] = array('body, p, .btn, .button, .rev-btn, .rev-btn:visited, h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .widget-title');

/*Custom Header*/
$output['header_bg'] 					= array(
	'background'=> urna_texttrim('#tbay-header,#tbay-header .header-main')
);
$output['header_text_color'] 			= array('');
$output['header_link_color'] 			= array('.tbay-custom-language .select-button, .tbay-custom-language .select-button:after, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont,.woocommerce-currency-switcher-form .SumoSelect > .CaptionCont > label i:after, .tbay-login > a,.top-wishlist > a,.cart-dropdown > a,.navbar-nav > li > a,.recent-view h3,.track-order a');

$output['header_link_color_active'] = array( 
	'color' => urna_texttrim('.tbay-custom-language .select-button:hover, .tbay-custom-language .select-button:hover:after, .tbay-custom-language li:hover .select-button, .tbay-custom-language li:hover .select-button:after, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover > label i:after, .tbay-login > a:hover, .top-wishlist > a:hover, .vertical-menu .category-inside-title,.vertical-menu .category-inside-title:hover,.vertical-menu .category-inside-title:focus,
	.cart-dropdown > a:hover, .navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:focus > a,.recent-view h3:hover,.track-order a:hover'),
	'background-color' => urna_texttrim(''),
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
	'background'=> urna_texttrim('#tbay-header .tbay-mainmenu')
);
$output['main_menu_link_color'] 		= array('.navbar-nav > li > a');
$output['main_menu_link_color_active'] 	= array('.navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:focus > a');


/*Custom Footer*/
$output['footer_bg'] 					= array(
	'background'=> urna_texttrim('.tbay-footer')
);
$output['footer_heading_color'] 		= array('.tbay-footer .tbay-addon:not(.tbay-addon-newsletter) .tbay-addon-title,.tbay-footer .tbay-addon.tbay-addon-newsletter > h3');
$output['footer_text_color'] 			= array('.contact-info li,.copyright,.tbay-footer .tbay-addon-newsletter.tbay-addon > h3 .subtitle');
$output['footer_link_color'] 			= array('.tbay-footer a,.tbay-footer .menu li > a,.tbay-copyright .none-menu .menu li a');
$output['footer_link_color_hover'] 		= array('.tbay-footer a:hover,.tbay-footer .menu li > a:hover,.tbay-footer .menu li:hover > a,.tbay-footer .menu li.active > a,.tbay-footer .menu li:focus > a,.tbay-copyright .none-menu .menu li a:hover,.tbay-copyright .none-menu .menu li:hover a,.tbay-copyright .none-menu .menu li.active a,.tbay-copyright .none-menu .menu li:focus a');

/*Custom Copyright*/
$output['copyright_bg'] 				= array(
	'background'=> urna_texttrim('.tbay-footer .tbay-copyright')
);
$output['copyright_text_color'] 		= array('.tbay-footer .tbay-copyright p,.tbay-footer .tbay-copyright .copyright,.tbay-footer .tbay-copyright');
$output['copyright_link_color'] 		= array('.tbay-footer .tbay-copyright a,.tbay-copyright .none-menu .menu li a');
$output['copyright_link_color_hover'] 	= array('.tbay-footer .tbay-copyright a:hover,.tbay-copyright .none-menu .menu li a:hover,.tbay-copyright .none-menu .menu li:hover a,.tbay-copyright .none-menu .menu li:focus a,.tbay-copyright .none-menu .menu li.active a');

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
