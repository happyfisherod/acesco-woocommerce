<?php if ( ! defined('URNA_THEME_DIR')) exit('No direct script access allowed');

$theme_primary = require_once( get_parent_theme_file_path( URNA_INC . '/class-primary-color.php') );

/*For example $main_color_skin 	= '.top-info > .widget'; */
$main_color_skin 	= '.has-after:hover,button.btn-close:hover,.new-input + span:before,.new-input + label:before,.category-inside-title:hover , .tbay-addon-features .fbox-icon,.product-block.v5 .yith-wcwl-wishlistexistsbrowse.show a,.product-block.v5 .yith-wcwl-wishlistaddedbrowse.show a';  
$main_bg_skin 		= '.has-after:after , #tbay-header .header-mainmenu , .btn-theme-2,.tbay-addon .owl-carousel > .slick-arrow:hover,.tbay-addon .owl-carousel > .slick-arrow:focus,.tparrows.revo-tbay:hover,.tbay-addon-blog .readmore:before';
$main_border_skin 	= '.btn-theme-2 , .tp-bullets.revo-tbay .tp-bullet , .tbay-addon-flash-sales .owl-carousel.products.slick-initialized .slick-list,.tbay-addon-categories .item-cat:hover:before,.tbay-addon-categories .item-cat:hover';


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
	'color' => urna_texttrim(''),
	'background-color' => urna_texttrim('.cart-dropdown .cart-icon .mini-cart-items, .top-wishlist .count_wishlist, .progress-bar , .tbay-addon-blog .entry-category a '),
	'border-color' => urna_texttrim('')
);

/*Custom Fonts*/
$output['primary-font'] = array('body, p, .btn, .button, .rev-btn, .rev-btn:visited, h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .widget-title');

/*Custom Header*/
$output['header_bg'] 					= array(
	'background'=> urna_texttrim('#tbay-header .header-main, #tbay-header .header-mainmenu')
);
$output['header_text_color'] 			= array('');
$output['header_link_color'] 			= array('.category-inside-title, .tbay-login >a, .top-wishlist a, .cart-dropdown > a, .yith-compare-header a, .navbar-nav.megamenu > li > a, .navbar-nav .caret, .recent-view h3');

$output['header_link_color_active'] = array( 
	'color' => urna_texttrim('.category-inside-title:hover, .tbay-login a:hover span, .tbay-login >a:hover, .top-wishlist a:hover, .cart-dropdown > a:hover, .yith-compare-header a:hover, .navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:focus > a, .navbar-nav > li:hover > a .caret:before, .navbar-nav > li:focus > a .caret:before, .navbar-nav > li.active > a .caret:before, .recent-view h3:hover'),
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
);

/*Custom Main Menu*/
$output['main_menu_bg'] 				= array(
	'background'=> urna_texttrim('#tbay-header .tbay-mainmenu')
);
$output['main_menu_link_color'] 		= array('.navbar-nav.megamenu > li > a, .navbar-nav .caret');
$output['main_menu_link_color_active'] 	= array('.navbar-nav.megamenu > li.active > a, .navbar-nav.megamenu > li:hover > a, .navbar-nav.megamenu > li:focus > a, .navbar-nav > li:hover > a .caret:before, .navbar-nav > li:focus > a .caret:before, .navbar-nav > li.active > a .caret:before');


/*Custom Footer*/
$output['footer_bg'] 					= array(
	'background'=> urna_texttrim('.tbay-footer')
);
$output['footer_heading_color'] 		= array('.tbay-footer .tbay-addon .tbay-addon-title');
$output['footer_text_color'] 			= array('.tbay-footer .tbay-addon .tbay-addon-title .subtitle, .contact-info li, .tbay-footer .wpb_text_column');
$output['footer_link_color'] 			= array('.contact-info a, .tbay-footer .menu li > a, .tbay-addon-social .social.style3 > li a, .tbay-footer .tbay-copyright .none-menu li a, .copyright a');
$output['footer_link_color_hover'] 		= array('.contact-info a:hover, .tbay-footer ul.menu li > a:hover, .tbay-footer ul.menu li.active > a, .tbay-addon-social .social.style3 > li a:hover, .tbay-footer .tbay-copyright .none-menu li a:hover, .copyright a:hover');

/*Custom Copyright*/
$output['copyright_bg'] 				= array(
	'background'=> urna_texttrim('.tbay-footer .tbay-copyright')
);
$output['copyright_text_color'] 		= array('.tbay-footer .tbay-copyright .wpb_text_column');
$output['copyright_link_color'] 		= array('.tbay-footer .tbay-copyright .menu li a, .copyright a');
$output['copyright_link_color_hover'] 	= array('.tbay-footer .tbay-copyright .menu li a:hover, .copyright a:hover');

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
