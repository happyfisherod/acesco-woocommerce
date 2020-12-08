<?php if ( ! defined('URNA_THEME_DIR')) exit('No direct script access allowed');

$theme_primary = require_once( get_parent_theme_file_path( URNA_INC . '/class-primary-color.php') );

/*For example $main_color_skin 	= '.top-info > .widget'; */
$main_color_skin 	= '.btn-link:hover,.btn-link:focus,.has-after:hover,button.btn-close:hover,.new-input + span:before,.new-input + label:before,.topbar .menu li a:hover , .topbar .top-flashsale li a,.recent-view .urna-recent-viewed-products h3:hover , .category-inside .tbay-vertical > li:hover > a,.category-inside .tbay-vertical > li:hover > a i , .category-inside .tbay-vertical .sub-menu li a:hover,.contact-info li.contact,.tbay-addon-tags .tag-img .content a:hover';  
$main_bg_skin 		= '.has-after:after , .btn-theme-2 , #tbay-header .header-mainmenu,.tbay-footer .menu.treeview li > a:hover:before , .tbay-to-top a:hover,.tparrows.revo-tbay:hover,.tbay-addon-tags .tag-img .content a:hover:before,.tbay-addon-flash-sales .tbay-addon-title + .flash-sales-date,.product-block.v10 .name a:hover:before';
$main_border_skin 	= '.btn-theme-2,.product-block.v10:hover .image,.product-block.v10:hover .image.has-slider-gallery > a,.tbay-addon-tags .tag-img > a:hover';


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
/*Custom Fonts*/
$output['primary-font'] = array('body, p, .btn, .button, .rev-btn, .rev-btn:visited, h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .widget-title');

/*Custom Header*/
$output['header_bg'] 					= array(
	'background'=> urna_texttrim('#tbay-header .header-main, .topbar, #tbay-header .header-mainmenu')
);
$output['header_text_color'] 			= array('.top-contact .content');
$output['header_link_color'] 			= array('.woocommerce-currency-switcher-form .SumoSelect > .CaptionCont, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont > label, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont > label i:after, .tbay-login >a, .top-wishlist a, .cart-dropdown .cart-icon, .tbay-custom-language a.select-button, .tbay-custom-language .select-button:after, .tbay-custom-language li:hover .select-button:after, #tbay-header .category-inside-title, .navbar-nav.megamenu > li > a, .navbar-nav .caret, .yith-compare-header a, #track-order a, .recent-view .urna-recent-viewed-products h3');

$output['header_link_color_active'] = array( 
	'color' => urna_texttrim('.woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover label i:after, .tbay-login >a:hover, .top-wishlist a:hover, .cart-dropdown:hover .cart-icon, .tbay-custom-language .select-button:hover, .tbay-custom-language li:hover .select-button, .tbay-custom-language li:hover .select-button:after, .tbay-custom-language .select-button:hover:after, #tbay-header .category-inside-title:hover, .navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li.active > a .caret, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:hover > a .caret,.navbar-nav.megamenu > li > a:hover, .navbar-nav.megamenu > li > a:hover .caret, .yith-compare-header a:hover, #track-order a:hover, .recent-view .urna-recent-viewed-products h3:hover'),
	'background-color' => urna_texttrim(''),
);

/*Custom Top Bar color*/
$output['topbar_bg'] 					= array(
	'background'=> urna_texttrim('.topbar')
);
$output['topbar_text_color'] 			= array('');
$output['topbar_link_color'] 			= array('.woocommerce-currency-switcher-form .SumoSelect > .CaptionCont, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont > label, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont > label i:after, .tbay-login >a, .tbay-custom-language a.select-button, .tbay-custom-language .select-button:after, .tbay-custom-language li:hover .select-button:after, #track-order a, .recent-view .urna-recent-viewed-products h3');

$output['topbar_link_color_hover'] = array( 
	'color' => urna_texttrim('.woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover label i:after, .tbay-login >a:hover, .tbay-custom-language .select-button:hover, .tbay-custom-language li:hover .select-button, .tbay-custom-language li:hover .select-button:after, .tbay-custom-language .select-button:hover:after, .navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li.active > a .caret, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:hover > a .caret,.navbar-nav.megamenu > li > a:hover, .navbar-nav.megamenu > li > a:hover .caret, .yith-compare-header a:hover, #track-order a:hover, .recent-view .urna-recent-viewed-products h3:hover'),
	'background-color' => urna_texttrim(''),
);

/*Custom Main Menu*/
$output['main_menu_bg'] 				= array(
	'background'=> urna_texttrim('#tbay-header .tbay-mainmenu')
);
$output['main_menu_link_color'] 		= array('.navbar-nav.megamenu > li > a, .navbar-nav .caret');
$output['main_menu_link_color_active'] 	= array('.navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li.active > a .caret, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:hover > a .caret,.navbar-nav.megamenu > li > a:hover, .navbar-nav.megamenu > li > a:hover .caret');


/*Custom Footer*/
$output['footer_bg'] 					= array(
	'background'=> urna_texttrim('.tbay-footer')
);
$output['footer_heading_color'] 		= array('.tbay-footer .tbay-addon .tbay-addon-title');
$output['footer_text_color'] 			= array('.tbay-footer .tbay-addon .tbay-addon-title .subtitle, .contact-info li, .copyright');
$output['footer_link_color'] 			= array('.contact-info a, .tbay-footer .menu li > a, .copyright a');
$output['footer_link_color_hover'] 		= array(
	'color' => urna_texttrim('.contact-info a:hover, .tbay-footer ul.menu li > a:hover, .copyright a:hover, .tbay-footer ul.menu li.active > a'),
	'background-color' => urna_texttrim('.tbay-footer .menu.treeview li > a:hover:before'),
);

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
