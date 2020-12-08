<?php if ( ! defined('URNA_THEME_DIR')) exit('No direct script access allowed');

$theme_primary = require_once( get_parent_theme_file_path( URNA_INC . '/class-primary-color.php') );

/*For example $main_color_skin 	= '.top-info > .widget'; */
$main_color_skin 	= '.btn-link:hover,.btn-link:focus,.has-after:hover,button.btn-close:hover,.new-input + span:before,.new-input + label:before';  
$main_bg_skin 		= '.has-after:after , .btn-theme-2 , #tbay-header .header-mainmenu';
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
/*Theme color second*/
$output['main_color_second'] = array( 
	'color' => urna_texttrim('.color-2'),
	'background-color' => urna_texttrim('.cart-dropdown .cart-icon .mini-cart-items,.top-wishlist .count_wishlist , ul.woof_list.woof_list_color li .woof_label_count,ul.woof_list.woof_list_label li .woof_label_count')
);

/*Custom Fonts*/
$output['primary-font'] = array('body, p, .btn, .button, .rev-btn, .rev-btn:visited, h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .widget-title');

/*Custom Header*/
$output['header_bg'] 					= array(
	'background'=> urna_texttrim('#tbay-header .topbar, #tbay-header .header-main, #tbay-header .header-mainmenu')
);
$output['header_text_color'] 			= array('.top-contact .content, .cart-dropdown .text-cart, .tbay-search-form .select-category.input-group-addon');
$output['header_link_color'] 			= array('#tbay-header .color,.woocommerce-currency-switcher-form .SumoSelect > .CaptionCont, .tbay-custom-language .select-button, .menu-my-order-container a, .topbar a i, .tbay-login > a, .top-wishlist a, .cart-dropdown .cart-icon,.yith-compare-header i,.navbar-nav > li > a,.recent-view h3,.tbay-search-form .button-search.icon i, .tbay-custom-language .select-button:after');

$output['header_link_color_active'] = array( 
	'color' => urna_texttrim('#tbay-header .color:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover label i:after, .tbay-custom-language .select-button:hover,.tbay-custom-language .select-button:hover:after, .tbay-custom-language li:hover .select-button, .tbay-custom-language li:hover .select-button:after, .menu-my-order-container a:hover, .topbar a:hover i, .tbay-login >a:hover, .top-wishlist a:hover, .cart-dropdown:hover .cart-icon,.yith-compare-header i:hover,.navbar-nav > li > a:hover,.navbar-nav > li:hover > a,.navbar-nav > li.active > a,.recent-view h3:hover,.tbay-custom-language a:hover span,.tbay-search-form .button-search.icon i:hover'),
	'background-color' => urna_texttrim(''),
);

/*Custom Top Bar color*/
$output['topbar_bg'] 					= array(
	'background'=> urna_texttrim('.topbar')
);
$output['topbar_text_color'] 			= array('.topbar p,.top-contact .content');
$output['topbar_link_color'] 			= array('.content a,.woocommerce-currency-switcher-form .SumoSelect > .CaptionCont, .tbay-custom-language .select-button, .menu-my-order-container a, .topbar a i, .tbay-login >a, .tbay-custom-language .select-button:after');

$output['topbar_link_color_hover'] = array( 
	'color' => urna_texttrim('.content a:hover, .topbar a:hover > i, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover label i:after, .tbay-custom-language .select-button:hover,.tbay-custom-language .select-button:hover:after, .tbay-custom-language li:hover .select-button, .tbay-custom-language li:hover .select-button:after, .menu-my-order-container a:hover, .topbar a:hover i, .tbay-login >a:hover, .tbay-custom-language a:hover span'),
	'background-color' => urna_texttrim(''),
);

/*Custom Main Menu*/
$output['main_menu_bg'] 				= array(
	'background'=> urna_texttrim('#tbay-header .header-mainmenu')
);
$output['main_menu_link_color'] 		= array('.navbar-nav.megamenu > li > a,.recent-view h3');
$output['main_menu_link_color_active'] 	= array('.navbar-nav.megamenu > li.active > a,.recent-view h3:hover,.navbar-nav.megamenu > li:hover > a,.navbar-nav.megamenu > li > a:hover');


/*Custom Footer*/
$output['footer_bg'] 					= array(
	'background'=> urna_texttrim('.tbay-footer')
);
$output['footer_heading_color'] 		= array('.tbay-addon .tbay-addon-title');
$output['footer_text_color'] 			= array('.tbay-addon .tbay-addon-title .subtitle, .contact-info li');
$output['footer_link_color'] 			= array('.contact-info a, .tbay-footer .menu li > a, .tbay-footer .tbay-addon-social .social > li a');
$output['footer_link_color_hover'] 		= array('.contact-info a:hover, .tbay-footer .menu li > a:hover, .tbay-footer .menu li.active > a, .tbay-footer .tbay-addon-social .social > li a:hover');

/*Custom Copyright*/
$output['copyright_bg'] 				= array(
	'background'=> urna_texttrim('.tbay-footer .tbay-copyright')
);
$output['copyright_text_color'] 		= array('.copyright');
$output['copyright_link_color'] 		= array('.copyright a,.tbay-copyright .none-menu .menu li a');
$output['copyright_link_color_hover'] 	= array('.copyright a:hover,.tbay-copyright .none-menu .menu li a:hover,.tbay-copyright .none-menu .menu li:hover a,.tbay-copyright .none-menu .menu li.active a');


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
