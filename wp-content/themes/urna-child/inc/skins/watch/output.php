<?php if ( ! defined('URNA_THEME_DIR')) exit('No direct script access allowed');

$theme_primary = require_once( get_parent_theme_file_path( URNA_INC . '/class-primary-color.php') );

/*For example $main_color_skin 	= '.top-info > .widget'; */
$main_color_skin 	= '.has-after:hover,button.btn-close:hover,.new-input + span:before,.new-input + label:before,.tbay-login > a:hover,.top-cart .cart-icon:hover,.search .tbay-search-form .button-search.icon:hover , .navbar-nav > li:hover > a,.navbar-nav > li:focus > a,.navbar-nav > li.active > a,.tparrows.revo-tbay:hover::before,.tbay-addon .owl-carousel > .slick-arrow:hover,.tbay-homepage-demo #main-content .bg-black .tbay-addon-categories .owl-carousel > .slick-arrow:hover,.tbay-homepage-demo #main-content .tbay-addon-categories .item-cat:hover a,.tbay-addon-blog.carousel .tbay-addon-content .entry-header .entry-title a:hover,.tbay-addon-blog.grid .tbay-addon-content .entry-header .entry-title a:hover,.tbay-addon-blog.carousel .tbay-addon-content .entry-header .readmore:hover,.tbay-addon-blog.grid .tbay-addon-content .entry-header .readmore:hover';  
$main_bg_skin 		= '.has-after:after , .btn-theme-2,.show-all-blog a:hover,.tbay-to-top a:hover,.tbay-homepage-demo #main-content .show-all:hover';
$main_border_skin 	= '.btn-theme-2,.show-all-blog a:hover,.tbay-to-top a:hover,.tbay-homepage-demo #main-content .show-all:hover,.btn-insta a:hover,.tbay-addon-blog.carousel .tbay-addon-content .entry-header .readmore:hover,.tbay-addon-blog.grid .tbay-addon-content .entry-header .readmore:hover';


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
	'background'=> urna_texttrim('#tbay-header .header-main, .tbay-homepage-demo #tbay-header .header-main')
);
$output['header_text_color'] 			= array('');
$output['header_link_color'] 			= array('.navbar-nav > li > a, .tbay-login > a,.top-cart .cart-icon,
.tbay-search-form .button-search.icon');

$output['header_link_color_active'] = array( 
	'color' => urna_texttrim('.navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:focus > a,
	.tbay-login > a:hover,.top-cart .cart-icon:hover,.search .tbay-search-form .button-search.icon:hover'),
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
$output['main_menu_link_color_active'] 	= array('.navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:focus > a');


/*Custom Footer*/
$output['footer_bg'] 					= array(
	'background'=> urna_texttrim('.tbay-footer')
);
$output['footer_heading_color'] 		= array('.tbay-footer .tbay-addon:not(.tbay-addon-newsletter) .tbay-addon-title,.tbay-footer .tbay-addon-newsletter.tbay-addon > h3');
$output['footer_text_color'] 			= array('.tbay-footer .contact-info li,.tbay-footer .tbay-copyright p,.tbay-footer .contact-info li > i,.footer .title-text-footer');
$output['footer_link_color'] 			= array('.tbay-footer .menu.treeview li > a, .tbay-footer .social.style3 li a,.tbay-footer .tbay-copyright .none-menu .menu li a,.tbay-footer a,.tbay-footer .tbay-addon-newsletter.tbay-addon .input-group .input-group-btn input,.tbay-footer .link-footer');
$output['footer_link_color_hover'] 		= array('.tbay-footer .menu.treeview li > a:hover, .tbay-footer .social.style3 li a:hover,.tbay-footer .tbay-copyright .none-menu .menu li a:hover,.tbay-footer a:hover
,.tbay-footer .menu.treeview li:hover > a,.tbay-footer .social.style3 li:hover a,.tbay-footer .tbay-copyright .none-menu .menu li:hover a,.tbay-footer .tbay-addon-newsletter.tbay-addon .input-group .input-group-btn input:hover');

/*Custom Copyright*/
$output['copyright_bg'] 				= array(
	'background'=> urna_texttrim('.tbay-footer .tbay-copyright')
);
$output['copyright_text_color'] 		= array('.tbay-footer .tbay-copyright p');
$output['copyright_link_color'] 		= array('.tbay-footer .tbay-copyright a,.tbay-footer .tbay-copyright .none-menu .menu li a,.tbay-footer .link-footer');
$output['copyright_link_color_hover'] 	= array('.tbay-footer .tbay-copyright a:hover,.tbay-footer .tbay-copyright .none-menu .menu li a:hover,.tbay-footer .tbay-copyright .none-menu .menu li:hover a');

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
