<?php if ( ! defined('URNA_THEME_DIR')) exit('No direct script access allowed');

$theme_primary = require_once( get_parent_theme_file_path( URNA_INC . '/class-primary-color.php') );

/*For example $main_color_skin 	= '.top-info > .widget'; */
$main_color_skin 	= '.has-after:hover,button.btn-close:hover,.new-input + span:before,.new-input + label:before,.navbar-nav > li.active > a,.navbar-nav > li:hover > a,.navbar-nav > li:focus > a,#tbay-search-form-canvas button:hover,#tbay-search-form-canvas button:hover i,#tbay-search-form-canvas .sidebar-canvas-search .sidebar-content .select-category .optWrapper .options li:hover label , .autocomplete-suggestions > div .suggestion-group:hover .suggestion-title,.tbay-login a span:hover,#tbay-footer .tbay-addon-social .social.style3 li a:hover,.tbay-addon.tbay-addon-blog.relate-blog .post .entry-title a:hover,#tbay-search-form-canvas.v4 button:hover,#tbay-search-form-canvas.v4 button:hover i,#tbay-search-form-canvas.v4 .sidebar-canvas-search .sidebar-content .select-category .optWrapper .options li:hover label , #tbay-search-form-canvas.v4 .autocomplete-suggestions > div .suggestion-group:hover .suggestion-title';
$main_bg_skin 		= '.has-after:after , .btn-theme-2 , .skin-fashion-v2 .tbay-addon-newsletter .input-group-btn input,.tbay-instagram .tbay-addon-button a';
$main_border_skin 	= '.btn-theme-2 , .skin-fashion-v2 .tbay-addon-newsletter .input-group-btn input,.tparrows.revo-tbay:hover';
$main_border_top_skin 	= '.tbay-loading:after';

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
	'background'=> urna_texttrim('#tbay-header .header-main, #tbay-header .topbar')
);
$output['header_text_color'] 			= array('.top-contact .content');
$output['header_link_color'] 			= array('#tbay-header .social li a, .navbar-nav.megamenu > li > a, #tbay-search-form-canvas.v4 button i, .tbay-login >a, .top-wishlist a, .cart-dropdown > a, .recent-view h3, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont >label i:after, .tbay-custom-language .select-button, .tbay-custom-language .select-button:after, #track-order a');

$output['header_link_color_active'] = array( 
	'color' => urna_texttrim('#tbay-header .social li a:hover, .navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:focus > a, #tbay-search-form-canvas.v4 button:hover i,.tbay-login > a:hover, .top-wishlist a:hover, .cart-dropdown > a:hover, .recent-view h3:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover >label i:after, .tbay-custom-language .select-button:hover,.tbay-custom-language .select-button:hover:after, .tbay-custom-language li:hover .select-button, .tbay-custom-language li:hover .select-button:after, #track-order a:hover'),
	'background-color' => urna_texttrim(''),
);

/*Custom Top Bar color*/
$output['topbar_bg'] 					= array(
	'background'=> urna_texttrim('#tbay-header .topbar')
);
$output['topbar_text_color'] 			= array('.top-contact .content');
$output['topbar_link_color'] 			= array('#tbay-header .social li a, .recent-view h3, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont >label i:after, .tbay-custom-language .select-button, .tbay-custom-language .select-button:after, #track-order a');

$output['topbar_link_color_hover'] = array( 
	'color' => urna_texttrim('#tbay-header .social li a:hover, .recent-view h3:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover, .woocommerce-currency-switcher-form .SumoSelect > .CaptionCont:hover >label i:after, .tbay-custom-language .select-button:hover,.tbay-custom-language .select-button:hover:after, .tbay-custom-language li:hover .select-button, .tbay-custom-language li:hover .select-button:after, #track-order a:hover'),
	'background-color' => urna_texttrim(''),
);

/*Custom Main Menu*/
$output['main_menu_bg'] 				= array(
	'background'=> urna_texttrim('#tbay-header .tbay-mainmenu')
);
$output['main_menu_link_color'] 		= array('.navbar-nav.megamenu > li > a');
$output['main_menu_link_color_active'] 	= array('.navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:focus > a');


/*Custom Footer*/
$output['footer_bg'] 					= array(
	'background'=> urna_texttrim('.tbay-footer')
);
$output['footer_heading_color'] 		= array('.tbay-footer .tbay-addon .tbay-addon-title');
$output['footer_text_color'] 			= array('.tbay-footer .tbay-addon .tbay-addon-title .subtitle, .contact-info li, .tbay-footer .wpb_text_column p');
$output['footer_link_color'] 			= array('.contact-info a, .tbay-footer .menu li > a, #tbay-footer .tbay-addon-social .social.style3 li a, .tbay-copyright .none-menu .menu li a');
$output['footer_link_color_hover'] 		= array('.contact-info a:hover, .tbay-footer .menu li > a:hover, #tbay-footer .menu li.active > a, #tbay-footer .tbay-addon-social .social.style3 li a:hover,.tbay-copyright .none-menu .menu li a:hover');

/*Custom Copyright*/
$output['copyright_bg'] 				= array(
	'background'=> urna_texttrim('.tbay-copyright')
);
$output['copyright_text_color'] 		= array('.tbay-copyright .wpb_text_column, .copyright');
$output['copyright_link_color'] 		= array('.tbay-copyright .none-menu .menu li a,.copyright a');
$output['copyright_link_color_hover'] 	= array('.tbay-copyright .none-menu .menu li a:hover,.copyright a:hover');

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
