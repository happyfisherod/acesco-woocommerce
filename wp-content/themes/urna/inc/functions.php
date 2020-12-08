<?php
/**
 * @version    1.0
 * @package    urna
 * @author     Thembay Team <support@thembay.com>
 * @copyright  Copyright (C) 2019 Thembay.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: https://thembay.com
 */

//add_action( 'wp_head', 'remove_actions' );
function remove_actions() {
    remove_action( 'woocommerce_after_shop_loop_item_title', 'urna_wcmp_vendor_name', 0 );
    remove_action( 'woocommerce_single_product_summary', 'urna_wcmp_vendor_name', 5 );
    remove_action( 'woocommerce_after_shop_loop_item_title', 'urna_dokan_vendor_name', 0 );
    remove_action( 'woocommerce_single_product_summary', 'urna_dokan_vendor_name', 5 );
}


add_action('wp_enqueue_scripts', 'urna_child_enqueue_styles', 10000);
function urna_child_enqueue_styles() {
	$parent_style = 'urna-style';
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'urna-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );


    if(is_page_template('page-carro.php')){

        wp_enqueue_script( 'mapsScipts', get_stylesheet_directory_uri().'/maps.js', array('jquery'), date('His'), true );
        wp_enqueue_script( 'googleMaps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCOgWa1hBclUwMcliQxLA0Ao7bgmunfsLU&libraries=places,geometry&callback=initMap', array('jquery'), date('His'), true );

    }


}




function _new_updated_query( $query ) {
    if ( is_shop() && $query->is_main_query() ) {

        $args = array(
            'fields' => 'ID',
            'role__in' => [ 'contribuidores', 'administrator']
        );
        $users = get_users( $args );

        $query->set( 'author__in', $users );
        $query->set( 'posts_per_page', -1 );
    }
    if ( is_archive() && $query->is_main_query() ) {
        $query->set( 'author__in', $users  );
        $query->set( 'posts_per_page', -1 );
    }
}

if(!is_admin()){
    add_action( 'pre_get_posts', '_new_updated_query' );

}



// Register Custom Post Type
function custom_post_type() {

	$labels = array(
		'name'                  => _x( 'cookies', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'cookies', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'cookies', 'text_domain' ),
		'name_admin_bar'        => __( 'cookies', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'attributes'            => __( 'Item Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Items', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'view_items'            => __( 'View Items', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'cookies', 'text_domain' ),
		'description'           => __( 'Post Type Description', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'taxonomies'            => array( '' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'cookies_type', $args );

}
add_action( 'init', 'custom_post_type', 0 );


// Register Custom Post Type
function uses_custom_post_type() {

	$labels = array(
		'name'                  => _x( 'uses', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'uses', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'uses', 'text_domain' ),
		'name_admin_bar'        => __( 'uses', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'attributes'            => __( 'Item Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Items', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'view_items'            => __( 'View Items', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'uses', 'text_domain' ),
		'description'           => __( 'Post Type Description', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'taxonomies'            => array( '' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'uses_type', $args );

}
add_action( 'init', 'uses_custom_post_type', 0 );

if(isset( $_GET['varID']) && isset($_GET['proID']) ){
    add_action('init', 'addTocartFakeVariable');
}elseif(!isset( $_GET['varID']) && isset($_GET['proID']) ){
    add_action('init', 'addTocartFakeSingle');

}

function addTocartFakeVariable(){
    global $wpdb, $product;
    
    if(isset( $_GET['varID']) && isset($_GET['proID']) && isset($_GET['cant']) ){
        $variationId = $_GET['varID'];
        $product_id = $_GET['proID'];
        $cant = $_GET['cant'];
    }
    if (!isset($_COOKIE['cartCookie'])) {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    
        $title = substr(str_shuffle($permitted_chars), 0, 10);
        $post = array(
            'post_title' => $title,
            'post_type' => 'cookies_type',
            'post_status' => 'publish'
        );
        $post_id = wp_insert_post($post);
        
        $variationSku= get_post_meta( $variationId, '_sku', true );
    

        $has_multivendor = get_post_meta( $product_id, '_has_multi_vendor', true );
    
        $sql     = "SELECT `product_id` FROM `{$wpdb->prefix}dokan_product_map` WHERE `map_id`= '$has_multivendor' AND `product_id` != $product_id AND `is_trash` = 0";
        $results = $wpdb->get_results( $sql );

        if ( $results ) {
            
            $post_author_idArr = [];
            $uses = [];
            foreach ( $results as $key => $list ):

                $product_obj    = new WC_Product( $list->product_id );
                $post_author_id = get_post_field( 'post_author', $product_obj->get_id() );

                $post_author_idArr[] = $post_author_id;
    
                $producto = new WC_Product_Variable($product_obj->get_id()); 

                //genero un row por cada producto
                //$variation[$key]['id'] = $post_author_id;
                //$variation[$key]['producto'][] = $list->product_id;
                $variacion = '';
                foreach($producto->get_children() as $gd){
                    $key_1_value = get_post_meta( $gd, '_sku', true );
                    if (strpos($key_1_value, $variationSku) !== false) {
                        if($key_1_value === $variationSku){
                            
                        }else{

                            $variacion= $gd;

                        }
                    }
                }
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
                $titles = substr(str_shuffle($permitted_chars), 0, 10);
                $post = array(
                    'post_title' => $titles,
                    'post_type' => 'uses_type',
                    'post_status' => 'publish'
                );
                $uses_id = wp_insert_post($post);

                $uses[] = $uses_id;

                update_field('cantidad', $cant, $uses_id);
                update_field('cookie_id', $post_id, $uses_id);
                update_field('producto', $product_obj->get_id(), $uses_id);
                update_field('variacion', $variacion, $uses_id);
                update_field('author', $post_author_id, $uses_id);

                $row = array(
                    'distribuidor' => $post_author_id,
                    'data_p' => $uses_id,
                );
                add_row('data', $row, $post_id);





            endforeach;

            update_field('distribuidores', $post_author_idArr, $post_id);
            update_field('posts', $uses, $post_id);

        }
        setcookie("cartCookie", $post_id, (time()+3600*48), "/");
    }else{
        $cookie = $_COOKIE['cartCookie'];

        $autores = get_field('distribuidores', $cookie);
        $posts = get_field('posts', $cookie);

        $variationSku= get_post_meta( $variationId, '_sku', true );
    

        $has_multivendor = get_post_meta( $product_id, '_has_multi_vendor', true );
    
        $sql     = "SELECT `product_id` FROM `{$wpdb->prefix}dokan_product_map` WHERE `map_id`= '$has_multivendor' AND `product_id` != $product_id AND `is_trash` = 0";
        $results = $wpdb->get_results( $sql );
        

        $post_author_idArr = [];
        $uses = [];
        foreach ( $results as $key => $list ):

            $product_obj    = new WC_Product( $list->product_id );
            $post_author_id = get_post_field( 'post_author', $product_obj->get_id() );

            $post_author_idArr[] = (int)$post_author_id;

            $producto = new WC_Product_Variable($product_obj->get_id()); 

            //genero un row por cada producto
            //$variation[$key]['id'] = $post_author_id;
            //$variation[$key]['producto'][] = $list->product_id;
            $variacion = '';
            foreach($producto->get_children() as $gd){
                $key_1_value = get_post_meta( $gd, '_sku', true );
                if (strpos($key_1_value, $variationSku) !== false) {
                    if($key_1_value === $variationSku){
                        
                    }else{

                        $variacion= $gd;

                    }
                }
            }
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $titles = substr(str_shuffle($permitted_chars), 0, 10);
            $post = array(
                'post_title' => $titles,
                'post_type' => 'uses_type',
                'post_status' => 'publish'
            );
            $uses_id = wp_insert_post($post);

            $uses[] = $uses_id;


            update_field('cantidad', $cant, $uses_id);
            update_field('cookie_id', $cookie, $uses_id);
            update_field('producto', $product_obj->get_id(), $uses_id);
            update_field('variacion', $variacion, $uses_id);
            update_field('author', $post_author_id, $uses_id);

        endforeach;
        $authorResutlt = array_diff($post_author_idArr, $autores);
        $authorResutlt2 = array_merge($autores, $authorResutlt);
        $postsMerge = array_merge($posts, $uses);

        update_field('distribuidores', $authorResutlt2, $cookie);
        update_field('posts', $postsMerge, $cookie);
    }


}
function addTocartFakeSingle(){
    global $wpdb, $product;

    if(!isset( $_GET['varID']) && isset($_GET['proID']) ){
        $product_id = $_GET['proID'];
    }


    
    if (!isset($_COOKIE['cartCookie'])) {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    
        $title = substr(str_shuffle($permitted_chars), 0, 10);
        $post = array(
            'post_title' => $title,
            'post_type' => 'cookies_type',
            'post_status' => 'publish'
        );
        $post_id = wp_insert_post($post);
        setcookie("cartCookie", $post_id, (time()+3600), "/");

        $has_multivendor = get_post_meta( $product_id, '_has_multi_vendor', true );
    
        if ( empty( $has_multivendor ) ) {
            return false;
        }
    
        $sql     = "SELECT `product_id` FROM `{$wpdb->prefix}dokan_product_map` WHERE `map_id`= '$has_multivendor' AND `product_id` != $product_id AND `is_trash` = 0";
        $results = $wpdb->get_results( $sql );
    
        if ( $results ) {
    
            foreach ( $results as $key => $list ):
                $product_obj    = new WC_Product( $list->product_id );
                $post_author_id = get_post_field( 'post_author', $product_obj->get_id() );
    
                $row = array(
                    'distribuidor' => $post_author_id,
                    'producto'   => $product_obj->get_id(),
                    'variacion'   => '',
                );
                add_row('ditribuidores', $row, $post_id);
    
                if ( ! $product_obj->is_visible() ) {
                    continue;
                }
            endforeach;
    
        }
    }else{
        $cookie = $_COOKIE['cartCookie'];
        $has_multivendor = get_post_meta( $product_id, '_has_multi_vendor', true );
    
        if ( empty( $has_multivendor ) ) {
            return false;
        }
    
        $sql     = "SELECT `product_id` FROM `{$wpdb->prefix}dokan_product_map` WHERE `map_id`= '$has_multivendor' AND `product_id` != $product_id AND `is_trash` = 0";
        $results = $wpdb->get_results( $sql );
    
        if ( $results ) {
    
            foreach ( $results as $key => $list ):
                $product_obj    = new WC_Product( $list->product_id );
                $post_author_id = get_post_field( 'post_author', $product_obj->get_id() );
    
                $row = array(
                    'distribuidor' => $post_author_id,
                    'producto'   => $product_obj->get_id(),
                    'variacion'   => '',
                );
                add_row('ditribuidores', $row, $cookie);
    
                if ( ! $product_obj->is_visible() ) {
                    continue;
                }
            endforeach;
    
        }
    }




}

add_action('wp_ajax_nopriv_addtocart', 'addtocart');
add_action('wp_ajax_addtocart', 'addtocart');
function addtocart(){
    $product_id = $_POST['product_id'];
    $quantity = $_POST['cant'];
    $variation_id = $_POST['var_id'];

    WC()->cart->add_to_cart( $product_id, $quantity, $variation_id );

    echo 'ok';
    die();

}



add_action('init', 'cloneUserRole');
function cloneUserRole(){
    global $wp_roles;
    if (!isset($wp_roles))
    $wp_roles = new WP_Roles();
    $adm = $wp_roles->get_role('administrator');
    // Adding a new role with all admin caps.
    $wp_roles->add_role('contribuidor', 'Contribuidor', $adm->capabilities);
}


/*foreach( WC()->cart->get_cart() as $cart_item_key => $cart_item ){
    $product_id = $cart_item['product_id']; // Product ID
    $product_obj = wc_get_product($product_id); // Product Object
    $product_qty = $cart_item['quantity']; // Product quantity
    $product_price = $cart_item['data']->price; // Product price
    $product_total_stock = $cart_item['data']->total_stock; // Product stock
    $product_type = $cart_item['data']->product_type; // Product type
    $product_name = $cart_item['data']->post->post_title; // Product Title (Name)
    $product_slug = $cart_item['data']->post->post_name; // Product Slug
    $product_description = $cart_item['data']->post->post_content; // Product description
    $product_excerpt = $cart_item['data']->post->post_excerpt; // Product short description
    $product_post_type = $cart_item['data']->post->post_type; // Product post type

    $cart_line_total = $cart_item['line_total']; // Cart item line total
    $cart_line_tax = $cart_item['line_tax']; // Cart item line tax total
    $cart_line_subtotal = $cart_item['line_subtotal']; // Cart item line subtotal
    $cart_line_subtotal_tax = $cart_item['line_subtotal_tax']; // Cart item line tax subtotal

    // variable products
    $variation_id = $cart_item['variation_id']; // Product Variation ID
    if($variation_id != 0){
        $product_variation_obj = wc_get_product($variation_id); // Product variation Object
        $variation_array = $cart_item['variation']; // variation attributes + values
    }
}*/



add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_more_seller_product_tab', 98 );
    function wcs_woo_remove_more_seller_product_tab($tabs) {
    unset($tabs['more_seller_product']);
    unset($tabs['seller_enquiry_form']);
    unset($tabs['shipping']);
    unset($tabs['additional_information']);
    return $tabs;
}


add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );
/**
 * Add 2 custom product data tabs
 */
function woo_new_product_tab( $tabs ) {
	
	// Adds the new tab
	$tabs['ingredient_tab'] = array(
		'title' 	=> __( 'Información adicional', 'woocommerce' ),
		'priority' 	=> 15,
		'callback' 	=> 'infoAdicionalCallback'
        );


	return $tabs;

}


function infoAdicionalCallback() {
?>
    <div class="content-aditional"> 
        <ul>
            <li>
                <div class="box-content">
                    <div class="icon"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ico-prod-1-8.png" alt=""></div>
                    <h4>Ficha Técnica</h4>
                    <h4><?php if(get_field('ficha_tecnica', get_the_ID()) == ''){ echo '<br> <i>Próximamente</i>';} ?></h4>
                    <div class="box-controls"><a href="<?php if(get_field('ficha_tecnica', get_the_ID()) == ''){ echo '#';}else{ echo get_field('ficha_tecnica', get_the_ID());} ?>" class="btn-download">Descargar</a></div>
                </div>
            </li>
            <li>
                <div class="box-content">
                    <div class="icon"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ico-prod-2-8.png" alt=""></div>
                    <h4>Manual Técnico</h4>
                    <h4><?php if(get_field('manual_tecnico', get_the_ID()) == ''){ echo '<br> <i>Próximamente</i>';} ?></h4>
                    <div class="box-controls"><a href="<?php if(get_field('manual_tecnico', get_the_ID()) == ''){ echo '#';}else{ echo get_field('manual_tecnico', get_the_ID());} ?>" class="btn-download">Descargar</a></div>
                </div>
            </li>
            <li>
                <div class="box-content">
                    <div class="icon"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ico-prod-3-8.png" alt=""></div>
                    <h4>Manual de Instalación</h4>
                    <h4><?php if(get_field('manual_de_instalacion', get_the_ID()) == ''){ echo '<br> <i>Próximamente</i>';} ?></h4>
                    <div class="box-controls"><a href="<?php if(get_field('manual_de_instalacion', get_the_ID()) == ''){ echo '#';}else{ echo get_field('manual_de_instalacion', get_the_ID());} ?>" class="btn-download">Descargar</a></div>
                </div>
            </li>
            <li>
                <div class="box-content">
                    <div class="icon"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ico-prod-4-8.png" alt=""></div>
                    <h4>Autodeclaración Ambiental</h4>
                    <h4><?php if(get_field('autodeclaracion_ambiental', get_the_ID()) == ''){ echo '<br> <i>Próximamente</i>';} ?></h4>
                    <div class="box-controls"><a href="<?php if(get_field('autodeclaracion_ambiental', get_the_ID()) == ''){ echo '#';}else{ echo get_field('autodeclaracion_ambiental', get_the_ID());} ?>" class="btn-download">Descargar</a></div>
                </div>
            </li>
        </ul>
    
    </div>

<?php
}


