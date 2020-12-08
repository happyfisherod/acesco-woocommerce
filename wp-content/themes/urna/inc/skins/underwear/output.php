<?php if ( ! defined('URNA_THEME_DIR')) exit('No direct script access allowed');

$theme_primary = require_once( get_parent_theme_file_path( URNA_INC . '/class-primary-color.php') );

/*For example $main_color_skin 	= '.top-info > .widget'; */
$main_color_skin 	= '.has-after:hover,button.btn-close:hover,.new-input + span:before,.new-input + label:before,.top-wishlist i:hover,.cart-dropdown .cart-icon i:hover,.search i:hover,.canvas-menu-sidebar i:hover,.navbar-nav > li > a.active,.navbar-nav > li > a:hover,.navbar-nav > li > a:focus,.navbar-nav > li.active > a,.navbar-nav > li:hover > a,.navbar-nav > li:focus > a,.tbay-footer .menu li > a.active,.tbay-footer .menu li > a:focus,.tbay-footer .menu li:hover > a,.tbay-footer .menu li.active > a,.tbay-footer .menu li:focus > a,.special-testi .tbay-addon-newsletter.tbay-addon .input-group .input-group-btn:before,.product-block.v10 .tbay-product-slider-gallery .slick-arrow:hover,.tbay-addon .owl-carousel > .slick-arrow:hover,.tbay-addon .owl-carousel > .slick-arrow:focus,.testimonial-meta > span.name-client,.header-main .tbay-search-form .button-group:before,.tbay-addon.tbay-addon-features .ourservice-heading:hover,#tbay-search-form-canvas.v4 button:hover,#tbay-search-form-canvas.v4 button:hover i,#tbay-search-form-canvas.v4 .sidebar-canvas-search .sidebar-content .select-category .optWrapper .options li:hover label , #tbay-search-form-canvas.v4 .autocomplete-suggestions > div .suggestion-group:hover .suggestion-title';  
$main_bg_skin 		= '.has-after:after , .btn-theme-2,.tbay-homepage-demo .tbay-addon-text-heading .action a:before,.tbay-homepage-demo .tbay-addon .action a:before,.tbay-to-top a:hover';
$main_border_skin 	= '.btn-theme-2,.tbay-to-top a:hover,.tbay-homepage-demo .tbay-addon-text-heading .action a,.tbay-homepage-demo .tbay-addon .action a , #tbay-search-form-canvas.v4 .tbay-loading:after ';
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
	'background'=> urna_texttrim('#tbay-header .header-main')
);
$output['header_text_color'] 			= array('#tbay-header .header-main p');
$output['header_link_color'] 			= array('.navbar-nav > li > a, .search .btn-search-icon i,.tbay-login > a,.top-wishlist a,.cart-dropdown > a,.canvas-menu-sidebar > a, .top-wishlist i, .cart-dropdown .cart-icon i, .search i, .canvas-menu-sidebar i');

$output['header_link_color_active'] = array( 
	'color' => urna_texttrim('.navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:focus > a,.search:hover .btn-search-icon i,.tbay-login > a:hover,.top-wishlist a:hover,.cart-dropdown > a:hover,.canvas-menu-sidebar > a:hover, .navbar-nav > li > a.active, .navbar-nav > li > a:hover, .navbar-nav > li > a:focus, .top-wishlist i:hover, .cart-dropdown .cart-icon i:hover, .search i:hover'),
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
	'background'=> urna_texttrim('#tbay-header .tbay-mainmenu')
);
$output['main_menu_link_color'] 		= array('.navbar-nav > li > a');
$output['main_menu_link_color_active'] 	= array('.navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:focus > a, .navbar-nav > li > a.active, .navbar-nav > li > a:hover, .navbar-nav > li > a:focus');


/*Custom Footer*/
$output['footer_bg'] 					= array(
	'background'=> urna_texttrim('#tbay-footer')
);
$output['footer_heading_color'] 		= array('');
$output['footer_text_color'] 			= array('.tbay-footer .tbay-copyright p,.tbay-footer p');
$output['footer_link_color'] 			= array('.tbay-footer .tbay-addon-social .style1 > li > a, .tbay-footer .menu li > a, .tbay-copyright .wpb_text_column a');
$output['footer_link_color_hover'] 		= array('.tbay-footer .tbay-addon-social .style1 > li > a:hover,.tbay-footer .tbay-addon-social .style1 > li:hover > a,.tbay-footer .tbay-addon-social .style1 > li.active > a,.tbay-footer .menu li > a:hover, .tbay-copyright .wpb_text_column a:hover');

/*Custom Copyright*/
$output['copyright_bg'] 				= array(
	'background'=> urna_texttrim('.tbay-footer .tbay-copyright')
);
$output['copyright_text_color'] 		= array('.tbay-footer .tbay-copyright p');
$output['copyright_link_color'] 		= array('.tbay-footer .tbay-copyright a');
$output['copyright_link_color_hover'] 	= array('.tbay-footer .tbay-copyright a:hover');


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
