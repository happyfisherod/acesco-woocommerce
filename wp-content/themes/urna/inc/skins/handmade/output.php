<?php if ( ! defined('URNA_THEME_DIR')) exit('No direct script access allowed');

$theme_primary = require_once( get_parent_theme_file_path( URNA_INC . '/class-primary-color.php') );

/*For example $main_color_skin 	= '.top-info > .widget'; */
$main_color_skin 	= '.has-after:hover,.btn-theme:hover,.btn-theme:focus,.btn-theme:active:hover,button.btn-close:hover,.tbay-addon-social .social.style3 > li a:hover,.tbay-addon-categories .item-cat:hover a , .layout-product-v11 .product-block .add-cart a';  
$main_bg_skin 		= '.has-after:after , .btn-theme-2';
$main_border_skin 	= '.btn-theme:hover,.btn-theme:focus,.btn-theme:active:hover , .btn-theme-2';


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
	'background'=> urna_texttrim('#tbay-header .header-main, #tbay-header .header-mainmenu')
);
$output['header_text_color'] 			= array(
	'color' => urna_texttrim('.top-contact .content'),
	'background-color' => urna_texttrim(''),
);
$output['header_link_color'] 			= array(
	'color' => urna_texttrim('.top-contact .content a, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont > label, .tbay-login >a, .top-wishlist a, .cart-dropdown .cart-icon, .tbay-custom-language .select-button, .navbar-nav.megamenu > li > a, #track-order a, .tbay-search-form button i, .canvas-menu-sidebar a i'),
	'background-color' => urna_texttrim(''),
);

$output['header_link_color_active'] = array( 
	'color' => urna_texttrim('.top-contact .content a:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover, .tbay-login a:hover span, .tbay-login a:hover i, .SumoSelect>.optWrapper>.options>li.opt:hover, .top-wishlist a:hover, .cart-dropdown .cart-icon:hover, .cart-dropdown .text-cart:hover, .tbay-custom-language .select-button:hover, .tbay-search-form button i:hover, .canvas-menu-sidebar a i:hover, .navbar-nav.megamenu > li.active > a,.recent-view h3:hover,.navbar-nav.megamenu > li:hover > a,.navbar-nav.megamenu > li > a:hover'),
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
	'background'=> urna_texttrim(' #tbay-header .header-mainmenu')
);
$output['main_menu_link_color'] 		= array('.navbar-nav.megamenu > li > a');
$output['main_menu_link_color_active'] 	= array('.navbar-nav.megamenu > li.active > a,.navbar-nav.megamenu > li:hover > a,.navbar-nav.megamenu > li > a:hover');


/*Custom Footer*/
$output['footer_bg'] 					= array(
	'background'=> urna_texttrim('.tbay-footer, .tbay-footer .tbay-copyright')
);
$output['footer_heading_color'] 		= array('.tbay-footer .tbay-addon .tbay-addon-title');
$output['footer_text_color'] 			= array('.tbay-footer .wpb_text_column p');
$output['footer_link_color'] 			= array('.contact-info a, .tbay-footer .menu li > a, .tbay-addon-social .social.style3 > li a, .tbay-footer .tbay-copyright .tbay-addon-newsletter .input-group-btn:before');
$output['footer_link_color_hover'] 		= array('.contact-info a:hover, .tbay-footer .menu li > a:hover, .tbay-addon-social .social.style3 > li a:hover, .tbay-addon-social .social.style3 li a:hover i, .tbay-footer ul.menu li.active > a, .tbay-footer .tbay-copyright .tbay-addon-newsletter .input-group-btn:hover:before');

/*Custom Copyright*/
$output['copyright_bg'] 				= array(
	'background'=> urna_texttrim('.tbay-footer .tbay-copyright')
);
$output['copyright_text_color'] 		= array('.tbay-footer .tbay-copyright .wpb_text_column p');
$output['copyright_link_color'] 		= array('.copyright a, .tbay-footer .tbay-copyright .tbay-addon-newsletter .input-group-btn:before');
$output['copyright_link_color_hover'] 	= array('.copyright a:hover, .tbay-footer .tbay-copyright .tbay-addon-newsletter .input-group-btn:hover:before');

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
