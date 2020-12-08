<?php if ( ! defined('URNA_THEME_DIR')) exit('No direct script access allowed');

$theme_primary = require_once( get_parent_theme_file_path( URNA_INC . '/class-primary-color.php') );

/*For example $main_color_skin 	= '.top-info > .widget'; */
$main_color_skin 	= '.has-after:hover,button.btn-close:hover,.new-input + span:before,.new-input + label:before , .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover,.tbay-custom-language .select-button:hover,#track-order a:hover,.navbar-nav > li > a:active , .navbar-nav > li:hover > a,.navbar-nav > li:focus > a,.navbar-nav > li.active > a,.navbar-nav > li:hover > a .caret:before,.navbar-nav > li:focus > a .caret:before,.navbar-nav > li.active > a .caret:before,.tbay-addon-notification .owl-carousel > .slick-arrow:hover.slick-next,.tbay-addon-notification .owl-carousel > .slick-arrow:hover.slick-prev,.tbay-addon-notification .owl-carousel > .slick-arrow:focus.slick-next,.tbay-addon-notification .owl-carousel > .slick-arrow:focus.slick-prev,.tbay-addon-categories .item-cat.cat-img a.shop-now';  
$main_bg_skin 		= '.has-after:after , .tbay-search-form .button-search.text , .btn-theme-2';
$main_border_skin 	= '.btn-theme-2';


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
	'background'=> urna_texttrim('.topbar, #tbay-header .header-main, #tbay-header .header-mainmenu ')
);
$output['header_text_color'] 			= array(
	'color' => urna_texttrim('.top-contact .content, .topbar-right .top-info span'),
	'background-color' => urna_texttrim(''),
);
$output['header_link_color'] 			= array(
	'color' => urna_texttrim('.top-contact .content a, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont > label, .tbay-login >a, .top-wishlist a, .cart-dropdown .cart-icon, .tbay-custom-language .select-button, .navbar-nav.megamenu > li > a, #track-order a, .navbar-nav .caret, .tbay-custom-language .select-button:after, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont > label i:after'),
	'background-color' => urna_texttrim('.top-contact .content a:before'),
);

$output['header_link_color_active'] = array( 
	'color' => urna_texttrim('.top-contact .content a:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover, .tbay-login >a:hover, .top-wishlist a:hover, .cart-dropdown:hover .cart-icon,  .navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:focus > a, .navbar-nav > li:hover > a .caret:before, .navbar-nav > li:focus > a .caret:before, .navbar-nav > li.active > a .caret:before, .tbay-custom-language li:hover .select-button, .tbay-custom-language li:hover .select-button:after, .tbay-custom-language .select-button:hover,.tbay-custom-language .select-button:hover:after, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover label i:after, #track-order a:hover'),
	'background-color' => urna_texttrim('.top-contact .content a:hover:before'),
);

/*Custom Top Bar color*/
$output['topbar_bg'] 					= array(
	'background'=> urna_texttrim('.topbar')
);
$output['topbar_text_color'] 			= array('.top-contact .content, .topbar-right .top-info span');
$output['topbar_link_color'] 			= array(
	'color' => urna_texttrim('.top-contact .content a, #track-order a'),
	'background-color' => urna_texttrim('.top-contact .content a:before'),
);

$output['topbar_link_color_hover'] = array( 
	'color' => urna_texttrim('.top-contact .content a:hover, #track-order a:hover'),
	'background-color' => urna_texttrim('.top-contact .content a:hover:before'),
);

/*Custom Main Menu*/
$output['main_menu_bg'] 				= array(
	'background'=> urna_texttrim('#tbay-header .tbay-mainmenu')
);
$output['main_menu_link_color'] 		= array('.navbar-nav.megamenu > li > a, .navbar-nav .caret');
$output['main_menu_link_color_active'] 	= array('navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:focus > a, .navbar-nav > li:hover > a .caret:before, .navbar-nav > li:focus > a .caret:before, .navbar-nav > li.active > a .caret:before');


/*Custom Footer*/
$output['footer_bg'] 					= array(
	'background'=> urna_texttrim('.tbay-footer, .tbay-footer .tbay-copyright')
);
$output['footer_heading_color'] 		= array('.tbay-footer .tbay-addon .tbay-addon-title, .tbay-footer .tbay-addon-features .ourservice-heading');
$output['footer_text_color'] 			= array('.tbay-footer .tbay-addon .tbay-addon-title .subtitle, .contact-info li, .tbay-footer .tbay-addon-features .description, .copyright');
$output['footer_link_color'] 			= array('.copyright a, .tbay-footer .menu li > a');
$output['footer_link_color_hover'] 		= array('.copyright a:hover, .tbay-footer .menu li > a:hover, .tbay-footer ul.menu li.active a');

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
