<?php
/**
 * urna functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Urna
 * @since Urna 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Urna 1.0
 */
define( 'URNA_THEME_VERSION', '1.0' );

/**
 * ------------------------------------------------------------------------------------------------
 * Define constants.
 * ------------------------------------------------------------------------------------------------
 */
define( 'URNA_THEME_DIR', 		get_template_directory_uri() );
define( 'URNA_THEMEROOT', 		get_template_directory() );
define( 'URNA_IMAGES', 			URNA_THEME_DIR . '/images' );
define( 'URNA_SCRIPTS', 		URNA_THEME_DIR . '/js' );

define( 'URNA_SCRIPTS_SKINS', 	URNA_SCRIPTS . '/skins' );
define( 'URNA_STYLES', 			URNA_THEME_DIR . '/css' );
define( 'URNA_STYLES_SKINS', 	URNA_STYLES . '/skins' );

define( 'URNA_INC', 				'inc' );
define( 'URNA_CLASSES', 			URNA_INC . '/classes' );
define( 'URNA_VENDORS', 			URNA_INC . '/vendors' );
define( 'URNA_ELEMENTOR', 		         URNA_THEMEROOT . '/inc/vendors/elementor' );
define( 'URNA_ELEMENTOR_TEMPLATES',     URNA_THEMEROOT . '/elementor_templates' );
define( 'URNA_VISUALCOMPOSER', 		URNA_THEMEROOT . '/inc/vendors/visualcomposer' );
define( 'URNA_WIDGETS', 			URNA_INC . '/widgets' );

define( 'URNA_ASSETS', 			URNA_THEME_DIR . '/inc/assets' );
define( 'URNA_ASSETS_IMAGES', 	URNA_ASSETS    . '/images' );

define( 'URNA_MIN_JS', 	'' );


if ( ! isset( $content_width ) ) {
	$content_width = 660;
}


if ( ! function_exists( 'urna_tbay_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Urna 1.0
 */
function urna_tbay_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on urna, use a find and replace
	 * to change 'urna' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'urna', URNA_THEMEROOT . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	add_theme_support( "post-thumbnails" );

	add_image_size('urna_avatar_post_carousel', 100, 100, true);

	// This theme styles the visual editor with editor-style.css to match the theme style.
	$font_source = urna_tbay_get_config('show_typography', false);
	if( !$font_source ) {
		add_editor_style( array( 'css/editor-style.css', urna_fonts_url() ) );
	}


	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );


	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	add_theme_support( "woocommerce" );
	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'audio'
	) );

	$color_scheme  = urna_tbay_get_color_scheme();
	$default_color = trim( $color_scheme[0], '#' );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'urna_custom_background_args', array(
		'default-color'      => $default_color,
		'default-attachment' => 'fixed',
	) ) );
	
	urna_tbay_get_load_plugins();
}
endif; // urna_tbay_setup
add_action( 'after_setup_theme', 'urna_tbay_setup', 10 );


/**
 * Enqueue scripts and styles.
 *
 * @since Urna 1.0
 */
function urna_tbay_scripts() { 
	$suffix = (urna_tbay_get_config('minified_js', false)) ? '.min' : URNA_MIN_JS;

	// load bootstrap style
	if( is_rtl() ){
		wp_enqueue_style( 'bootstrap', URNA_STYLES . '/bootstrap.rtl.css', array(), '3.3.7' );
	}else{
		wp_enqueue_style( 'bootstrap', URNA_STYLES . '/bootstrap.css', array(), '3.3.7' );
	}
	
	$skin = urna_tbay_get_theme();
	// Load our main stylesheet.
	if( is_rtl() ){
		$css_path =  URNA_STYLES . '/template.rtl.css';
		$css_skin =  URNA_STYLES_SKINS . '/'.$skin.'/type.rtl.css';
	}
	else{
		$css_path =  URNA_STYLES . '/template.css';
		$css_skin =  URNA_STYLES_SKINS . '/'.$skin.'/type.css';
	}

	$css_array = array();

    if( urna_is_elementor_activated() ) {
        array_push($css_array, 'elementor-frontend'); 
    } 

	wp_enqueue_style( 'urna-template', $css_path, $css_array,  date('His') );
	wp_enqueue_style( 'urna-skin', $css_skin, array(),  date('His') );
	

	$vc_style = urna_tbay_print_vc_style();
 
	wp_add_inline_style( 'urna-template', $vc_style );

	wp_enqueue_style( 'urna-style', URNA_THEME_DIR . '/style.css', array(), URNA_THEME_VERSION );
	//load font awesome
	wp_enqueue_style( 'font-awesome', URNA_STYLES . '/font-awesome.css', array(), '4.7.0' );
	
	//load font custom icon tbay
	wp_enqueue_style( 'font-tbay', URNA_STYLES . '/font-tbay-custom.css', array(), '1.0.0' );

	//load simple-line-icons
	wp_enqueue_style( 'simple-line-icons', URNA_STYLES . '/simple-line-icons.css', array(), '2.4.0' );

	//load linear icons
	wp_enqueue_style( 'linearicons', URNA_STYLES . '/linearicons.css', array(), '1.0.0' );

	//load material font icons
	wp_enqueue_style( 'material-design-iconic-font', URNA_STYLES . '/material-design-iconic-font.css', array(), date('His') );

	// load animate version 3.5.0
	wp_enqueue_style( 'animate-css', URNA_STYLES . '/animate.css', array(), '3.5.0' );

	
	wp_enqueue_script( 'urna-skip-link-fix', URNA_SCRIPTS . '/skip-link-fix' . $suffix . '.js', array(), URNA_THEME_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_dequeue_script('wpb_composer_front_js');
	wp_enqueue_script( 'wpb_composer_front_js');


	/*mmenu menu*/ 
	wp_register_script( 'jquery-mmenu', URNA_SCRIPTS . '/jquery.mmenu' . $suffix . '.js', array( 'jquery', 'underscore' ),'7.0.5', true );

	wp_register_script( 'jquery-instagramfeed', URNA_SCRIPTS . '/jquery.instagramFeed' . $suffix . '.js', array( 'jquery' ),'1.1.3', true );
	wp_register_script( 'jquery-timeago', URNA_SCRIPTS . '/jquery.timeago' . $suffix . '.js', array( 'jquery' ),'1.6.7', true );
 
	/*Treeview menu*/
	wp_enqueue_style( 'jquery-treeview',  URNA_STYLES . '/jquery.treeview.css', array(), '1.0.0' );

	/*hc sticky*/
	wp_register_script( 'hc-sticky', URNA_SCRIPTS . '/hc-sticky' . $suffix . '.js', array( 'jquery' ), '2.1.0', true );
	
	wp_enqueue_script( 'bootstrap', URNA_SCRIPTS . '/bootstrap' . $suffix . '.js', array( 'jquery' ), '3.3.5', true );

	/*slick jquery*/
    wp_register_script( 'slick', URNA_SCRIPTS . '/slick' . $suffix . '.js', array( 'jquery' ), '1.0.0', true );
    wp_register_script( 'urna-slick', URNA_SCRIPTS . '/custom-slick' . $suffix . '.js', array( 'jquery' ), URNA_THEME_VERSION, true );

	// Add js Sumoselect version 3.0.2
	wp_register_style('sumoselect', URNA_STYLES . '/sumoselect.css', array(), '1.0.0', 'all');
	wp_register_script('jquery-sumoselect', URNA_SCRIPTS . '/jquery.sumoselect' . $suffix . '.js', array( 'jquery' ), '3.0.2', TRUE);	

    wp_register_script( 'jquery-shuffle', URNA_SCRIPTS . '/jquery.shuffle' . $suffix . '.js', array( 'jquery' ), '3.0.0', true );  

    wp_register_script( 'jquery-autocomplete', URNA_SCRIPTS . '/jquery.autocomplete' . $suffix . '.js', array( 'jquery', 'urna-script' ), '1.0.0', true );     
    wp_enqueue_script('jquery-autocomplete');

    wp_register_style( 'magnific-popup', URNA_STYLES . '/magnific-popup.css', array(), '1.0.0' );
    wp_enqueue_style('magnific-popup');


	wp_register_script( 'jquery-countdowntimer', URNA_SCRIPTS . '/jquery.countdownTimer' . $suffix . '.js', array( 'jquery' ), '20150315', true );

	wp_register_style( 'jquery-fancybox', URNA_STYLES . '/jquery.fancybox.css', array(), '3.2.0' );
	wp_register_script( 'jquery-fancybox', URNA_SCRIPTS . '/jquery.fancybox' . $suffix . '.js', array( 'jquery' ), '2.1.7', true );

	wp_register_script( 'urna-script',  URNA_SCRIPTS . '/functions' . $suffix . '.js', array('bootstrap'),  URNA_THEME_VERSION,  true );

	wp_register_script( 'urna-skins-script', URNA_SCRIPTS_SKINS . '/'.$skin . $suffix . '.js', array( 'urna-script' ), URNA_THEME_VERSION, true );

	if ( wp_is_mobile() ) {
		wp_enqueue_script( 'jquery-fastclick', URNA_SCRIPTS . '/jquery.fastclick' . $suffix . '.js', array( 'jquery' ), '1.0.6', true );
	} 

	
	wp_enqueue_script( 'urna-skins-script' );

	if ( urna_tbay_get_config('header_js') != "" ) {
		wp_add_inline_script( 'urna-script', urna_tbay_get_config('header_js') );
	}

	global $wp_query; 

	
	
	$config = array(
		'active_theme' 		=> urna_tbay_get_theme(),
		'category_open' 	=> apply_filters( 'urna_category_inside_class', ''), 
		'lazyload' 			=> (bool) urna_tbay_get_config('enable_lazyloadimage', true),
		'quantity_minus'	=> apply_filters( 'tbay_quantity_minus', '<i class="linear-icon-minus"></i>'),
		'quantity_plus' 	=> apply_filters( 'tbay_quantity_plus', '<i class="linear-icon-plus"></i>'),
		'quantity_mode' 	=> (bool) apply_filters( 'urna_quantity_mode', 10,2 ),
		'cancel' 			=> esc_html__('cancel', 'urna'),
		'show_all_text' 	=> esc_html__('View all', 'urna'),
		'search' 			=> esc_html__('Search', 'urna'),
		'posts' 			=> json_encode( $wp_query->query_vars ), // everything about your loop is here
		'max_page'			=> $wp_query->max_num_pages,
		'mobile' 			=> wp_is_mobile(),
		'instagram_private' => esc_html__('This profile is private', 'urna'),
		'timeago'				=> array( 
			'suffixAgo'			=> esc_html__('ago', 'urna'),
			'suffixFromNow'		=> esc_html__('from now', 'urna'),
			'inPast'			=> esc_html__('any moment now', 'urna'),
			'seconds'			=> esc_html__('less than a minute', 'urna'),
			'minute'			=> esc_html__('about a minute', 'urna'),
			'minutes'			=> esc_html__('%d minutes', 'urna'),
			'hour'				=> esc_html__('about an hour', 'urna'),
			'hours'				=> esc_html__('about %d hours', 'urna'),
			'day'				=> esc_html__('a day', 'urna'),
			'days'				=> esc_html__('%d days', 'urna'),
			'month'				=> esc_html__('about a month', 'urna'),
			'months'			=> esc_html__('%d months', 'urna'),
			'year'				=> esc_html__('about a year', 'urna'),
			'years'				=> esc_html__('%d years', 'urna'),
		)
	);


	if( defined('URNA_WOOCOMMERCE_ACTIVED') && URNA_WOOCOMMERCE_ACTIVED ) {  
		$full_width 					= urna_check_full_width(); 

		$position 						= apply_filters( 'urna_cart_position', 10,2 );
		$woo_mode       				= urna_tbay_woocommerce_get_display_mode();
	    // loader gif
        $loader 						= apply_filters( 'tbay_quick_view_loader_gif', URNA_IMAGES . '/ajax-loader.gif' );
 
        $config['current_page'] 		= get_query_var( 'paged' ) ? get_query_var('paged') : 1;

        $config['popup_cart_icon'] 		= apply_filters( 'urna_popup_cart_icon', '<i class="linear-icon-cross"></i>',2 );
		$config['popup_cart_noti'] 		= esc_html__('was added to shopping cart.', 'urna');

		$config['cart_position'] 		= $position;
		$config['ajax_update_quantity'] = (bool) urna_tbay_get_config('ajax_update_quantity', true);

		$config['full_width'] 			= $full_width['active'];

		$config['display_mode'] 		= $woo_mode;

		$config['ajaxurl'] 				= admin_url( 'admin-ajax.php' );
		$config['loader'] 				= $loader;
	}

	/*Element ready default callback*/
	if( urna_is_elementor_activated() ) { 
		$config['elements_ready'] = array(
			'slick'               => urna_elements_ready_slick(),
			'countdowntimer'      => urna_elements_ready_countdown_timer(),	 
			'layzyloadimage'      => urna_elements_ready_layzyload_image(),	
			'instagram'      	  => urna_elements_ready_instagram(),	
			'treeview'      	  => urna_elements_ready_treeview(),	
		);
	}
	

	wp_localize_script( 'urna-script', 'urna_settings', $config );
}
add_action( 'wp_enqueue_scripts', 'urna_tbay_scripts', 100 );

function urna_tbay_footer_scripts() {
	if ( urna_tbay_get_config('footer_js') != "" ) {
		$footer_js = urna_tbay_get_config('footer_js');
		echo trim($footer_js);
	}
}
add_action('wp_footer', 'urna_tbay_footer_scripts');


add_action( 'login_enqueue_scripts', 'urna_tbay_load_admin_styles', 1000 );
add_action( 'admin_enqueue_scripts', 'urna_tbay_load_admin_styles', 1000 );
function urna_tbay_load_admin_styles() {
	wp_enqueue_style( 'material-design-iconic-font', URNA_STYLES . '/material-design-iconic-font.css', false, date('His') );
	wp_enqueue_style( 'urna-custom-admin', URNA_STYLES . '/admin/custom-admin.css', false, '1.0.0' );
}  

/**
 * Display descriptions in main navigation.
 *
 * @since Urna 1.0
 *
 * @param string  $item_output The menu item output.
 * @param WP_Post $item        Menu item object.
 * @param int     $depth       Depth of the menu.
 * @param array   $args        wp_nav_menu() arguments.
 * @return string Menu item with possible description.
 */
function urna_tbay_nav_description( $item_output, $item, $depth, $args ) {
	if ( 'primary' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-item-description">' . $item->description . '</div>' . $args->link_after . '</a>', $item_output );
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'urna_tbay_nav_description', 10, 4 );

/**Remove fonts scripts**/
if ( !function_exists('urna_tbay_remove_fonts_redux_url') ) {
	function urna_tbay_remove_fonts_redux_url() {  
		$show_typography  = urna_tbay_get_config('show_typography', false);
		if( !$show_typography ) {
			wp_dequeue_style( 'redux-google-fonts-urna_tbay_theme_options' );
		} 
	}
	add_action('wp_enqueue_scripts', 'urna_tbay_remove_fonts_redux_url', 9999);
}

/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Urna 1.0
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function urna_tbay_search_form_modify( $html ) {
	return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
}
add_filter( 'get_search_form', 'urna_tbay_search_form_modify', 10, 1);


function urna_tbay_get_config($name, $default = '') {
	global $urna_options;
    if ( isset($urna_options[$name]) ) {
        return $urna_options[$name];
    }
    return $default;
}


if ( ! function_exists( 'urna_time_link' ) ) :
/**
 * Gets a nicely formatted string for the published date.
 */
function urna_time_link() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	$time_string = sprintf( $time_string,
		get_the_date( DATE_W3C ), 
		get_the_date(),
		get_the_modified_date( DATE_W3C ),
		get_the_modified_date()
	);

	// Wrap the time string in a link, and preface it with 'Posted on'.
/*	return sprintf( 
		__( '%sPosted on%s %s', 'urna' ), '<span class="screen-reader-text">', '</span>',
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);*/
}
endif;

function urna_tbay_get_global_config($name, $default = '') {
	$options = get_option( 'urna_tbay_theme_options', array() );
	if ( isset($options[$name]) ) {
        return $options[$name];
    }
    return $default;
}

function urna_tbay_get_load_plugins() {

	$plugins[] =(array(
		'name'                     => esc_html__( 'Cmb2', 'urna' ),
	    'slug'                     => 'cmb2',
	    'required'                 => true,
	));

	$plugins[] =(array(
		'name'                     => esc_html__( 'WooCommerce', 'urna' ),
	    'slug'                     => 'woocommerce',
	    'required'                 => true,
	));

	$plugins[] =(array(
		'name'                     => esc_html__( 'MailChimp', 'urna' ),
	    'slug'                     => 'mailchimp-for-wp',
	    'required'                 =>  true
	));	

	$plugins[] =(array(
		'name'                     => esc_html__( 'Contact Form 7', 'urna' ),
	    'slug'                     => 'contact-form-7',
	    'required'                 => true,
	));

	$plugins[] =(array(
		'name'                     => esc_html__( 'WPBakery Visual Composer', 'urna' ),
	    'slug'                     => 'js_composer',
	    'required'                 => true,
	    'source'         		   => esc_url( 'https://bitbucket.org/devthembay/update-plugin/raw/master/plugins/js_composer.zip' ),
	));
	
	$plugins[] =(array(
		'name'                     => esc_html__( 'Urna Core', 'urna' ),
		'slug'                     => 'urna-core',
		'required'                 => true ,
		'source'         		   => esc_url( 'https://bitbucket.org/devthembay/update-plugin/raw/master/plugins/urna-core.zip' ),
	));

	$plugins[] =(array(
		'name'                     => esc_html__( 'WooCommerce Variation Swatches', 'urna' ),
	    'slug'                     => 'woo-variation-swatches',
	    'required'                 =>  true,
	    'source'         		   => esc_url( 'https://downloads.wordpress.org/plugin/woo-variation-swatches.zip' ),
	));		

	$plugins[] =(array(
		'name'                     => esc_html__( 'WooCommerce Products Filter', 'urna' ),
	    'slug'                     => 'woocommerce-products-filter',
	    'required'                 =>  true,
	    'source'         		   => esc_url( 'https://bitbucket.org/devthembay/update-plugin/raw/master/plugins/woocommerce-products-filter.zip' ),
	));	
	
	$plugins[] =(array(
		'name'                     => esc_html__( 'WooCommerce Photo Reviews', 'urna' ),
        'slug'                     => 'woo-photo-reviews',
        'required'                 => false,
	));		

	$plugins[] =(array(
		'name'                     => esc_html__( 'Revolution Slider', 'urna' ),
        'slug'                     => 'revslider',
        'required'                 => true ,
        'source'         		   => esc_url( 'https://bitbucket.org/devthembay/update-plugin/raw/master/plugins/revslider.zip' ),
	));

	$plugins[] =(array(
		'name'                     => esc_html__( 'Elementor Page Builder', 'urna' ),
        'slug'                     => 'elementor',
        'required'                 => true
	));	
	 
	tgmpa( $plugins );
}


require_once( get_parent_theme_file_path( URNA_INC . '/plugins/class-tgm-plugin-activation.php') );
require_once( get_parent_theme_file_path( URNA_INC . '/functions-helper.php') );
require_once( get_parent_theme_file_path( URNA_INC . '/functions-frontend.php') );
require_once( get_parent_theme_file_path( URNA_INC . '/functions-mobile.php') );
require_once( get_parent_theme_file_path( URNA_INC . '/skins/'.urna_tbay_get_theme().'/functions.php') );
/**
 * Implement the Custom Header feature.
 *
 */
require_once( get_parent_theme_file_path( URNA_INC . '/custom-header.php') );
/**
 * Classess file
 *
 */

/**
 * Implement the Custom Styles feature.
 *
 */
require_once( get_parent_theme_file_path( URNA_INC . '/custom-styles.php') );



/**
 * Register Sidebar
 *
 */
if ( !function_exists('urna_tbay_widgets_init') ) {
	function urna_tbay_widgets_init() {
		
		/* Check Redux */
		if( defined('URNA_CORE_ACTIVED') && URNA_CORE_ACTIVED ) {
			register_sidebar( array(
				'name'          => esc_html__( 'Newsletter Popup', 'urna' ),
				'id'            => 'newsletter-popup',
				'description'   => esc_html__( 'Add widgets here to appear in your site.', 'urna' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );				
		}
		/* End Check Redux */
		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar Default', 'urna' ),
			'id'            => 'sidebar-default',
			'description'   => esc_html__( 'Add widgets here to appear in your Sidebar.', 'urna' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
		
		/* Check Redux */
		if( defined('URNA_CORE_ACTIVED') && URNA_CORE_ACTIVED ) {

			register_sidebar( array(
				'name'          => esc_html__( 'Blog Archive Sidebar', 'urna' ),
				'id'            => 'blog-archive-sidebar',
				'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'urna' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );				
			register_sidebar( array(
				'name'          => esc_html__( 'Blog Single Sidebar', 'urna' ),
				'id'            => 'blog-single-sidebar',
				'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'urna' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );	
			register_sidebar( array(
				'name'          => esc_html__( 'Product Top Archive Product', 'urna' ),
				'id'            => 'product-top-archive',
				'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'urna' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );
			register_sidebar( array(
				'name'          => esc_html__( 'Product Archive Sidebar', 'urna' ),
				'id'            => 'product-archive',
				'description'   => esc_html__( 'Add widgets here to appear in Product archive left, right sidebar.', 'urna' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );			
			register_sidebar( array(
				'name'          => esc_html__( 'Product Single Inner Sidebar', 'urna' ),
				'id'            => 'product-single',
				'description'   => esc_html__( 'Add widgets here to appear in Product single left, right sidebar.', 'urna' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );			
			register_sidebar( array(
				'name'          => esc_html__( 'Product Single Normal Sidebar', 'urna' ),
				'id'            => 'product-single-normal',
				'description'   => esc_html__( 'Add widgets here to appear in Product single left, right sidebar.', 'urna' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );
			register_sidebar( array(
				'name'          => esc_html__( 'Product Sidebar Mobile', 'urna' ),
				'id'            => 'sidebar-mobile',
				'description'   => esc_html__( 'Add widgets here to appear in Product archive in mobile', 'urna' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );
		}
		/* End Check Redux */

		/* Check WPML */
		if ( function_exists('icl_object_id') ) {
			register_sidebar( array(
				'name'          => esc_html__( 'WPML Sidebar', 'urna' ),
				'id'            => 'wpml-sidebar',
				'description'   => esc_html__( 'Add widgets here to appear.', 'urna' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );
		}
		/* End check WPML */

		register_sidebar( array(
			'name'          => esc_html__( 'Footer', 'urna' ),
			'id'            => 'footer',
			'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'urna' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
		
	}
	add_action( 'widgets_init', 'urna_tbay_widgets_init' );
}


require_once( get_parent_theme_file_path( URNA_CLASSES . '/megamenu.php') );
require_once( get_parent_theme_file_path( URNA_CLASSES . '/custommenu.php') );
require_once( get_parent_theme_file_path( URNA_CLASSES . '/mmenu.php') );

/**
 * Custom template tags for this theme.
 *
 */

require_once( get_parent_theme_file_path( URNA_INC . '/template-tags.php') );
require_once( get_parent_theme_file_path( URNA_INC . '/template-hooks.php') );



require_once( get_parent_theme_file_path( URNA_VENDORS . '/cmb2/page.php') );
require_once( get_parent_theme_file_path( URNA_VENDORS . '/cmb2/post.php') );

if ( urna_is_Woocommerce_activated() ) {
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/woocommerce/wc-admin.php') );
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/woocommerce/skins/'.urna_tbay_get_theme().'.php') );
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/woocommerce/wc-functions.php') );
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/woocommerce/wc-recently-viewed.php') );
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/woocommerce/wc-shop.php') );
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/woocommerce/wc-single-functions.php') );
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/woocommerce/wc-ajax-auth.php') );
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/woocommerce/wc-hooks.php') );
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/woocommerce/compatible/wc_vendors.php') );
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/woocommerce/compatible/wc-dokan.php') );
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/woocommerce/compatible/wcfm_multivendor.php') );
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/woocommerce/compatible/wcmp_vendor.php') );
	
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/woocommerce/compatible/multi-step-checkout.php') );
}


require_once( get_parent_theme_file_path( URNA_VENDORS . '/visualcomposer/functions.php') );
require_once( get_parent_theme_file_path( URNA_VENDORS . '/visualcomposer/vc-maps.php') );

if ( urna_is_Woocommerce_activated() ) {
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/visualcomposer/vc-maps-woo.php') );
}


if( defined('URNA_CORE_ACTIVED') ) {
	require_once( get_parent_theme_file_path( URNA_WIDGETS . '/custom_menu.php') );
	require_once( get_parent_theme_file_path( URNA_WIDGETS . '/list-categories.php') );
	require_once( get_parent_theme_file_path( URNA_WIDGETS . '/popular_posts.php') );

	if ( function_exists( 'mc4wp_show_form' ) ) {
		require_once( get_parent_theme_file_path( URNA_WIDGETS . '/popup_newsletter.php') );
	}

	require_once( get_parent_theme_file_path( URNA_WIDGETS . '/posts.php') );
	require_once( get_parent_theme_file_path( URNA_WIDGETS . '/recent_comment.php') );
	require_once( get_parent_theme_file_path( URNA_WIDGETS . '/recent_post.php') ); 
	require_once( get_parent_theme_file_path( URNA_WIDGETS . '/single_image.php') );
	require_once( get_parent_theme_file_path( URNA_WIDGETS . '/banner_image.php') );
	require_once( get_parent_theme_file_path( URNA_WIDGETS . '/socials.php') );
	require_once( get_parent_theme_file_path( URNA_WIDGETS . '/top_rate.php') );
	require_once( get_parent_theme_file_path( URNA_WIDGETS . '/video.php') );
	require_once( get_parent_theme_file_path( URNA_WIDGETS . '/woo-carousel.php') );
	require_once( get_parent_theme_file_path( URNA_WIDGETS . '/yith-brand-image.php') );
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/redux-framework/redux-config.php') );
}


/**
 * Customizer additions.
 *
 */

require_once( get_parent_theme_file_path( URNA_INC . '/skins/'.urna_tbay_get_theme().'/customizer.php') );

if( urna_is_elementor_activated() ) {
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/elementor/class-elementor.php') );
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/elementor/class-elementor-pro.php') );
	require_once( get_parent_theme_file_path( URNA_VENDORS . '/elementor/icons/icons.php') );
}
