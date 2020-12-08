<?php if ( ! defined('URNA_THEME_DIR')) exit('No direct script access allowed');

$theme_primary = require_once( get_parent_theme_file_path( URNA_INC . '/class-primary-color.php') );

/*For example $main_color_skin 	= '.top-info > .widget'; */
$main_color_skin 	= '.has-after:hover,button.btn-close:hover,.new-input + span:before,.new-input + label:before,.recent-view h3:hover,#track-order a:hover,#track-order a:hover:before , .tbay-custom-language li:hover > .select-button .native,.tbay-homepage-demo .tbay-addon-features .fbox-icon i,.tbay-addon-categories .item-menu li a:hover';  
$main_bg_skin 		= '.has-after:after , .progress-bar , .btn-theme-2,.navbar-nav.megamenu > li > a:before , .tbay-addon-newsletter .input-group-btn input';
$main_border_skin 	= '.btn-theme-2 , .tbay-addon-newsletter .input-group-btn input';


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
$output['main_color_second'] = array( 
	'color' => urna_texttrim('.flash-sales-date .times > div,.tbay-addon-blog:not(.vertical):not(.relate-blog) .readmore'),
	'background-color' => urna_texttrim(''),
	'border-color' => urna_texttrim('')
);

/*Custom Fonts*/
$output['primary-font'] = array('body, p, .btn, .button, .rev-btn, .rev-btn:visited');
$output['secondary-font'] = array('.tbay-homepage-demo #tbay-main-content .tbay-addon .tbay-addon-title, .tbay-homepage-demo #tbay-main-content .tbay-addon .tbay-addon-heading');

/*Custom Header*/
$output['header_bg'] 					= array(
	'background'=> urna_texttrim('#tbay-header .header-main, .topbar')
);
$output['header_text_color'] 			= array('.top-contact');
$output['header_link_color'] 			= array('.widget_urna_socials_widget .social li a, #tbay-header .color, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont,.woocommerce-currency-switcher-form .SumoSelect > .CaptionCont > label i:after, .tbay-custom-language .select-button .native, #track-order a, .recent-view h3, .tbay-login a i,.category-inside-title, .navbar-nav.megamenu > li > a, #tbay-search-form-canvas.v2 button i, .top-wishlist a, .cart-dropdown .cart-icon, .cart-dropdown .text-cart,.yith-compare-header i, .tbay-custom-language .select-button:after');

$output['header_link_color_active'] = array( 
	'color' => urna_texttrim('#tbay-header .color:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover label i:after, .tbay-custom-language .select-button:hover,.tbay-custom-language .select-button:hover:after, .tbay-custom-language li:hover .select-button .native, .tbay-custom-language li:hover .select-button:after, #track-order a:hover, .category-inside-title:hover, .category-inside-title:focus, .navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:focus > a, #tbay-search-form-canvas.v2 button i:hover, .tbay-login a:hover i, .yith-compare-header i:hover, .top-wishlist i:hover, .cart-dropdown .cart-icon i:hover, .recent-view h3:hover'),
	'background-color' => urna_texttrim('.navbar-nav.megamenu > li > a:before'),
);

/*Custom Top Bar color*/
$output['topbar_bg'] 					= array(
	'background'=> urna_texttrim('.topbar')
);
$output['topbar_text_color'] 			= array('.top-contact');
$output['topbar_link_color'] 			= array('.widget_urna_socials_widget .social li a, #tbay-header .color, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont,.woocommerce-currency-switcher-form .SumoSelect > .CaptionCont > label i:after, .tbay-custom-language .select-button .native, #track-order a, .recent-view h3, .tbay-custom-language .select-button:after');

$output['topbar_link_color_hover'] 		= array( 
	'color' => urna_texttrim('#tbay-header .color:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover label i:after, .tbay-custom-language .select-button:hover,.tbay-custom-language .select-button:hover:after, .tbay-custom-language li:hover .select-button .native, .tbay-custom-language li:hover .select-button:after, #track-order a:hover, .recent-view h3:hover'),
	'background-color' => urna_texttrim(''),
);

/*Custom Main Menu*/
$output['main_menu_bg'] 				= array(
	'background'=> urna_texttrim('#tbay-header .tbay-mainmenu')
);
$output['main_menu_link_color'] 		= array('.navbar-nav.megamenu > li > a');
$output['main_menu_link_color_active'] 	= array(
	'color' => urna_texttrim('.navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:focus > a'),
	'background-color' => urna_texttrim('.navbar-nav.megamenu > li > a:before'),
);


/*Custom Footer*/
$output['footer_bg'] 					= array(
	'background'=> urna_texttrim('.tbay-footer')
);
$output['footer_heading_color'] 		= array('.tbay-footer .tbay-addon .tbay-addon-title');
$output['footer_text_color'] 			= array('.tbay-footer .tbay-addon .tbay-addon-title .subtitle, .contact-info li, .tbay-footer p');
$output['footer_link_color'] 			= array('.contact-info a, .tbay-footer .menu li > a, .tbay-addon-social .social.style3 > li a, .copyright a, .tbay-footer .tbay-copyright .menu li > a');
$output['footer_link_color_hover'] 		= array('.contact-info a:hover, .tbay-footer .menu li > a:hover, #tbay-footer .menu li.active > a, .tbay-addon-social .social.style3 > li a:hover, .copyright a:hover, .tbay-footer .tbay-copyright .menu li > a:hover');

/*Custom Copyright*/
$output['copyright_bg'] 				= array(
	'background'=> urna_texttrim('.tbay-footer .tbay-copyright')
);
$output['copyright_text_color'] 		= array('.tbay-copyright p');
$output['copyright_link_color'] 		= array('.copyright a, .tbay-footer .tbay-copyright .menu li > a');
$output['copyright_link_color_hover'] 	= array('.copyright a:hover, .tbay-footer .tbay-copyright .menu li > a:hover');


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
