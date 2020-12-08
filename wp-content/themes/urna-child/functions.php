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

add_action( 'wp_head', 'remove_actions' );
function remove_actions() {
    remove_action( 'urna_custom_woocommerce_register_form_end', 'urna_tbay_register_vendor_dokan_popup', 5 );
}

add_filter ( 'woocommerce_account_menu_items', 'misha_remove_my_account_links' );
function misha_remove_my_account_links( $menu_links ){
	unset( $menu_links['following'] ); // Remove Dashboard
	unset( $menu_links['downloads'] ); // Remove Dashboard
	return $menu_links;
 
}
remove_action( 'woocommerce_after_my_account', array( Dokan_Pro::init(), 'dokan_account_migration_button' ) );


add_action('wp_enqueue_scripts', 'urna_child_enqueue_styles', 10000);
function urna_child_enqueue_styles() {
	$parent_style = 'urna-style';
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'urna-child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), date('His')
    );

    wp_enqueue_script( 'customScipts', get_stylesheet_directory_uri().'/customscript.js', array('jquery'), date('His'), true );

    if(is_page_template('page-carro.php')){

        wp_enqueue_script( 'mapsScipts', get_stylesheet_directory_uri().'/maps.js', array('jquery'), date('His'), true );
        wp_enqueue_script( 'googleMaps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCOgWa1hBclUwMcliQxLA0Ao7bgmunfsLU&libraries=places,geometry&callback=initMap', array('jquery'), date('His'), true );
        //wp_enqueue_script( 'googleMaps', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyD3tnZbTCmzZd78uZkccvgsTp1KUOmV7YY&libraries=places,geometry&callback=initMap', array('jquery'), date('His'), true );

    }


}


function _new_updated_query( $query ) {
    if ( is_shop() && $query->is_main_query() ) {

        $args = array(
            'fields' => 'ID',
            'role__in' => [ 'contribuidor', 'administrator']
        );
        $users = get_users( $args );

        $query->set( 'author__in', $users );
        $query->set( 'posts_per_page', -1 );
    }elseif ( is_product_category() && $query->is_main_query() ) {
        $args = array(
            'fields' => 'ID',
            'role__in' => [ 'contribuidor', 'administrator']
        );
        $users = get_users( $args );

        $query->set( 'author__in', $users  );
        $query->set( 'posts_per_page', -1 );
    }elseif ( is_search() && $query->is_main_query() ) {
        $args = array(
            'fields' => 'ID',
            'role__in' => [ 'contribuidor', 'administrator']
        );
        $users = get_users( $args );

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

if(isset($_GET['proID']) ){
    $post_author_id = get_post_field( 'post_author', $_GET['proID'] );
    $user_meta=get_userdata($post_author_id);
    $user_roles=$user_meta->roles;
    if($user_roles[0] != 'seller'){
        add_action('init', 'addTocartFakeVariable');
    
    }else{

        add_action('init', 'addTocartFakeDist');

    }
    
}

function addTocartFakeDist(){
    global $wpdb, $product;
    
    if(isset( $_GET['varID']) && isset($_GET['proID']) && isset($_GET['cant']) ){
        $variationId = $_GET['varID'];
        $product_id = $_GET['proID'];
        $cant = $_GET['cant'];
    }elseif(!isset( $_GET['varID']) && isset($_GET['proID']) && isset($_GET['cant'])){
        $product_id = $_GET['proID'];
        $cant = $_GET['cant'];
    }
    if(isset( $_GET['varID'])){
        $variationSku= get_post_meta( $variationId, '_sku', true );
    }

    $has_multivendor = get_post_meta( $product_id, '_has_multi_vendor', true );

    $sql     = "SELECT `product_id` FROM `{$wpdb->prefix}dokan_product_map` WHERE `map_id`= '$has_multivendor' AND `product_id` != $product_id AND `is_trash` = 0";
    $results = $wpdb->get_results( $sql );
    if ( $results ) {
        foreach ( $results as $key => $list ):

            $product_obj    = new WC_Product( $list->product_id );
            $post_author_id = get_post_field( 'post_author', $product_obj->get_id() );


            $user_meta=get_userdata($post_author_id);
            $user_roles=$user_meta->roles;

            if($user_roles[0] != 'seller'){
                $producto = new WC_Product_Variable($product_obj->get_id()); 
                if($producto->get_children()){
                    foreach($producto->get_children() as $gd){
                        $key_1_value = get_post_meta( $gd, '_sku', true );
    
                        if(strpos($variationSku, $key_1_value) !== false){
                            addTocartFakeVariableDist($product_obj->get_id(), $key_1_value, $cant);
                        }
                    }

                }else{

                    addTocartFakeVariableDist($product_obj->get_id(), '', $cant);
                }

            }
        endforeach;
    }


}
function addTocartFakeVariableDist($product_ID, $variable_SKU, $cantidad){
    global $wpdb, $product;

    if($product_ID !='' && $variable_SKU != '' && $cantidad != ''){
        $variationId = $variable_SKU;
        $product_id = $product_ID;
        $cant = $cantidad;
    }elseif($product_ID !='' && $cantidad != ''){
        $product_id = $product_ID;
        $cant = $cantidad;
    }
    if (!isset($_COOKIE['cartCookie'])) {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    
        $title = substr(str_shuffle($permitted_chars), 0, 10);
        if(isset( $_GET['varID']) && $product_ID =='' && $variable_SKU == '' && $cantidad == ''){
            $variationSku= get_post_meta( $variationId, '_sku', true );
        }

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
                $productStatus = get_post_status($product_obj->get_id());

                $variacion = '';
                if(isset( $_GET['varID']) && $product_ID =='' && $variable_SKU == '' && $cantidad == ''){
                    foreach($producto->get_children() as $gd){

                        $key_1_value = get_post_meta( $gd, '_sku', true );
                        if (strpos($key_1_value, $variationSku) !== false) {
                            if($key_1_value === $variationSku){
 
                            }else{
                                
                                
                                $status = wc_get_product($gd)->get_status();

                                if($status != 'private'){
                                    $variacion= $gd;

                                }
    
                            }
                        }
                    }

                }elseif($variable_SKU != ''){
                    foreach($producto->get_children() as $gd){

                        $key_1_value = get_post_meta( $gd, '_sku', true );

                        if (strpos($key_1_value, $variable_SKU) !== false) {
                            if($key_1_value === $variable_SKU){
                            }else{
                                
                                $status = wc_get_product($gd)->get_status();

                                if($status != 'private'){
                                    $variacion= $gd;

                                }
    
                            }
                        }
                    }
                }
                if($variacion != ''){
                    $vairationObj = new WC_Product_Variation($variacion);

                    if($vairationObj->variation_is_visible() == true){

                        if($productStatus == 'publish'){
                            $tableName = $wpdb->prefix.'fake_cart_products';
                            $wpdb->insert(
                                $tableName,
                                array(
                                    'id'     => 'schedule_notification',
                                    'cookie_id' => $title,
                                    'parent_product' => $product_id,
                                    'vendor' => $post_author_id,
                                    'product' => $product_obj->get_id(),
                                    'qty' => $cant,
                                    'variation' => $variacion,
                                ),
                            array( '%s','%s', '%s', '%s', '%s', '%s')
                            );
                        }

                    }
                }

            endforeach;
        }
        setcookie("cartCookie", $title, (time()+3600*2), "/");
    }else{
        $cookie = $_COOKIE['cartCookie'];
        if(isset( $_GET['varID'])){
            $variationSku= get_post_meta( $variationId, '_sku', true );
        }

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
            $productStatus = get_post_status($product_obj->get_id());


            $variacion = '';
            if($variable_SKU != ''){
                foreach($producto->get_children() as $gd){
                    $key_1_value = get_post_meta( $gd, '_sku', true );
                    if (strpos($key_1_value, $variable_SKU) !== false) {
                        if($key_1_value === $variable_SKU){
                            
                        }else{

                            $status = wc_get_product($gd)->get_status();

                            if($status != 'private'){
                                $variacion= $gd;

                            }

                        }
                    }
                }
            }

            if($productStatus == 'publish'){
                $tableName = $wpdb->prefix.'fake_cart_products';

                $ProductID = $product_obj->get_id();

                $prepare = $wpdb->prepare( "SELECT * FROM $tableName WHERE cookie_id = '$cookie' AND product LIKE '$ProductID' AND variation LIKE '$variacion'"  );
                $datatable = $wpdb->get_results( $prepare );
                
                if($datatable){

                    foreach($datatable as $dt){

                        $oldQty = $dt->qty;
                        $newCant = $cant + $oldQty;


                        
                        $wpdb->update(
                            $tableName,
                            array(
                                'qty' => $newCant,
                            ),
                            array( 'id'=>$dt->id),
                            array( '%s','%s', '%s', '%s', '%s', '%s', '%s', '%s')
                        );
                        
    
                    }
                }else{

                    if($variacion != ''){
                        $wpdb->insert(
                            $tableName,
                            array(
                                'id'     => 'schedule_notification',
                                'cookie_id' => $cookie,
                                'parent_product' => $product_id,
                                'vendor' => $post_author_id,
                                'product' => $product_obj->get_id(),
                                'qty' => $cant,
                                'variation' => $variacion,
                            ),
                        array( '%s','%s', '%s', '%s', '%s', '%s')
                        );
                    }

                }
                


            }
        endforeach;

    }


}
function removeCart($product_ID, $variable_SKU){
    global $wpdb, $product;
    $cook = $_COOKIE['cartCookie'];
    if($product_ID !='' && $variable_SKU != ''){
        $variationId = $variable_SKU;
        $product_id = $product_ID;
    }elseif($product_ID !=''){
        $product_id = $product_ID;
    }
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    
        $title = substr(str_shuffle($permitted_chars), 0, 10);
        if(isset( $_GET['varID']) && $product_ID =='' && $variable_SKU == '' && $cantidad == ''){
            $variationSku= get_post_meta( $variationId, '_sku', true );
        }

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
                $productStatus = get_post_status($product_obj->get_id());

                $variacion = '';
                if(isset( $_GET['varID']) && $product_ID =='' && $variable_SKU == '' && $cantidad == ''){
                    foreach($producto->get_children() as $gd){

                        $key_1_value = get_post_meta( $gd, '_sku', true );
                        if (strpos($key_1_value, $variationSku) !== false) {
                            if($key_1_value === $variationSku){
 
                            }else{
                                
                                
                                $status = wc_get_product($gd)->get_status();

                                if($status != 'private'){
                                    $variacion= $gd;

                                }
    
                            }
                        }
                    }

                }elseif($variable_SKU != ''){
                    foreach($producto->get_children() as $gd){

                        $key_1_value = get_post_meta( $gd, '_sku', true );

                        if (strpos($key_1_value, $variable_SKU) !== false) {
                            if($key_1_value === $variable_SKU){
                            }else{
                                
                                $status = wc_get_product($gd)->get_status();

                                if($status != 'private'){
                                    $variacion= $gd;

                                }
    
                            }
                        }
                    }
                }
                if($variacion != ''){
                $vairationObj = new WC_Product_Variation($variacion);

                if($vairationObj->variation_is_visible() == true){

                    if($productStatus == 'publish'){
                        $tableName = $wpdb->prefix.'fake_cart_products';
                        
                        $wpdb->delete( $tableName, array('cookie_id' => $cook, 'product' => $product_obj->get_id(), 'variation' => $variacion));
                    }

                }
                }

            endforeach;
        }
        
}
function addTocartFakeVariable(){
    global $wpdb, $product;

    if($product_ID !='' && $variable_SKU != '' && $cantidad != ''){
        $variationId = $variable_SKU;
        $product_id = $product_ID;
        $cant = $cantidad;
    }elseif($product_ID !='' && $variable_SKU == '' && $cantidad != ''){
        $product_id = $product_ID;
        $cant = $cantidad;
    }elseif(isset( $_GET['varID']) && isset($_GET['proID']) && isset($_GET['cant']) && $product_ID =='' && $variable_SKU == '' && $cantidad == ''){
        $variationId = $_GET['varID'];
        $product_id = $_GET['proID'];
        $cant = $_GET['cant'];
    }elseif(!isset( $_GET['varID']) && isset($_GET['proID']) && isset($_GET['cant']) && $product_ID =='' && $variable_SKU == '' && $cantidad == ''){
        $product_id = $_GET['proID'];
        $cant = $_GET['cant'];
    }
    if (!isset($_COOKIE['cartCookie'])) {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    
        $title = substr(str_shuffle($permitted_chars), 0, 10);
        if(isset( $_GET['varID']) && $product_ID =='' && $variable_SKU == '' && $cantidad == ''){
            $variationSku= get_post_meta( $variationId, '_sku', true );
        }

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
                $productStatus = get_post_status($product_obj->get_id());

                $variacion = '';
                if(isset( $_GET['varID']) && $product_ID =='' && $variable_SKU == '' && $cantidad == ''){
                    foreach($producto->get_children() as $gd){

                        $key_1_value = get_post_meta( $gd, '_sku', true );
                        if (strpos($key_1_value, $variationSku) !== false) {
                            if($key_1_value === $variationSku){
 
                            }else{

                                $status = wc_get_product($gd)->get_status();

                                if($status != 'private'){
                                    $variacion= $gd;

                                }
                                
    
                            }
                        }
                    }

                }elseif($variable_SKU != ''){
                    foreach($producto->get_children() as $gd){

                        $key_1_value = get_post_meta( $gd, '_sku', true );

                        if (strpos($key_1_value, $variable_SKU) !== false) {
                            if($key_1_value === $variable_SKU){
                            }else{
                                $status = wc_get_product($gd)->get_status();
                                if($status != 'private'){
                                    $variacion= $gd;

                                }
    
                            }
                        }
                    }
                }
                if($variacion != ''){
                $vairationObj = new WC_Product_Variation($variacion);

                if($vairationObj->variation_is_visible() == true){

                    if($productStatus == 'publish'){
                        $tableName = $wpdb->prefix.'fake_cart_products';
                        $wpdb->insert(
                            $tableName,
                            array(
                                'id'     => 'schedule_notification',
                                'cookie_id' => $title,
                                'parent_product' => $product_id,
                                'vendor' => $post_author_id,
                                'product' => $product_obj->get_id(),
                                'qty' => $cant,
                                'variation' => $variacion,
                            ),
                        array( '%s','%s', '%s', '%s', '%s', '%s')
                        );
                    }

                }
                }

            endforeach;
        }
        setcookie("cartCookie", $title, (time()+3600*2), "/");
    }else{
        $cookie = $_COOKIE['cartCookie'];
        if(isset( $_GET['varID'])){
            $variationSku= get_post_meta( $variationId, '_sku', true );
        }

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
            $productStatus = get_post_status($product_obj->get_id());
            
            $variacion = '';
                foreach($producto->get_children() as $gd){
                    $key_1_value = get_post_meta( $gd, '_sku', true );
                   

                    if (strpos($key_1_value, $variationSku) !== false) {
                        if($key_1_value === $variationSku){
                            
                        }else{

                            $status = wc_get_product($gd)->get_status();
                            if($status != 'private'){
                                $variacion= $gd;

                            }

                        }
                    }
                }

            if($productStatus == 'publish'){
                $tableName = $wpdb->prefix.'fake_cart_products';

                $ProductID = $product_obj->get_id();

                $prepare = $wpdb->prepare( "SELECT * FROM $tableName WHERE cookie_id = '$cookie' AND product LIKE '$ProductID' AND variation LIKE '$variacion'"  );
                $datatable = $wpdb->get_results( $prepare );
                
                if($datatable){

                    foreach($datatable as $dt){

                        $oldQty = $dt->qty;
                        $newCant = $cant + $oldQty;


                        
                        $wpdb->update(
                            $tableName,
                            array(
                                'qty' => $newCant,
                            ),
                            array( 'id'=>$dt->id),
                            array( '%s','%s', '%s', '%s', '%s', '%s', '%s', '%s')
                        );
                        
    
                    }
                }else{
                    if($variacion != ''){

                        $wpdb->insert(
                            $tableName,
                            array(
                                'id'     => 'schedule_notification',
                                'cookie_id' => $cookie,
                                'parent_product' => $product_id,
                                'vendor' => $post_author_id,
                                'product' => $product_obj->get_id(),
                                'qty' => $cant,
                                'variation' => $variacion,
                            ),
                        array( '%s','%s', '%s', '%s', '%s', '%s')
                        );

                    }
                    

                     
                }
                


            }
        endforeach;

    }


}


add_action('wp_ajax_nopriv_addtocart', 'addtocart');
add_action('wp_ajax_addtocart', 'addtocart');
function addtocart(){
    global $woocommerce;

    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    
    $chars = substr(str_shuffle($permitted_chars), 0, 10);

    $product_id = $_POST['product_id'];
    $quantity = $_POST['cant'];
    $variation_id = $_POST['var_id'];


    

    WC()->cart->add_to_cart( $product_id, $quantity, $variation_id );

    $cart_url = $woocommerce->cart->get_cart_url().'/?'. $chars;

    echo $cart_url;
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



add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab', 98 );
/**
 * Add 2 custom product data tabs
 */
function woo_new_product_tab( $tabs ) {
    unset($tabs['more_seller_product']);
    unset($tabs['shipping']);
    unset($tabs['seller']);
    unset($tabs['trs_policies']);

	// Adds the new tab
	$tabs['ingredient_tab'] = array(
		'title' 	=> __( 'Documentación', 'woocommerce' ),
		'priority' 	=> 15,
		'callback' 	=> 'infoAdicionalCallback'
        );


	return $tabs;

}


function infoAdicionalCallback() {
?>
    <div class="content-aditional"> 
        <ul>
        <?php if(get_field('ficha_tecnica', get_the_ID()) != ''){?> 
            <li>
                <div class="box-content">
                    <div class="icon"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ico-prod-1-8.png" alt=""></div>
                    <h4>Ficha Técnica</h4>
                    <div class="box-controls"><a target="_blank" href="<?php echo get_field('ficha_tecnica', get_the_ID()); ?>" class="btn-download">Descargar</a></div>
                </div>
            </li>
        <?php } ?>
        <?php if(get_field('manual_de_instalacion', get_the_ID()) != ''){?> 
            <li>
                <div class="box-content">
                    <div class="icon"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icono-tutorial-instalacion.png" alt=""></div>
                    <h4>Manual de Instalación</h4>
                    <div class="box-controls"><a target="_blank" href="<?php echo get_field('manual_de_instalacion', get_the_ID()); ?>" class="btn-download">Descargar</a></div>

                </div>
            </li>
        <?php } ?>
        <?php if(get_field('manual_de_resistencia_al_fuego', get_the_ID()) != ''){ ?> 
            <li>
                <div class="box-content">
                    <div class="icon"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icono-manual-de-resistencia-al-fuego.png" alt=""></div>
                    <h4>Manual de resistencia al fuego</h4>
                    <div class="box-controls"><a target="_blank" href="<?php echo get_field('manual_de_resistencia_al_fuego', get_the_ID()); ?>" class="btn-download">Descargar</a></div>

                </div>
            </li>
        <?php } ?>
        <?php if(get_field('autodeclaracion_ambiental', get_the_ID()) != ''){?> 
            <li>
                <div class="box-content">
                    <div class="icon"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ico-prod-4-8.png" alt=""></div>
                    <h4>Autodeclaración Ambiental</h4>
                    <div class="box-controls"><a target="_blank" href="<?php echo get_field('autodeclaracion_ambiental', get_the_ID()); ?>" class="btn-download">Descargar</a></div>

                </div>
            </li>
        <?php } ?>
        <?php if(get_field('garantia_acesco', get_the_ID()) != ''){ ?> 
        
        
            <li>
                <div class="box-content">
                    <div class="icon"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/icono-garantia-acesco.png" alt=""></div>
                    <h4>Garantía Acesco</h4>
                    <div class="box-controls"><a target="_blank" href="<?php echo get_field('garantia_acesco', get_the_ID()); ?>" class="btn-download">Descargar</a></div>

                </div>
            </li>
        <?php } ?>
        
        </ul>
    
    </div>

<?php
}

class Home_Rollover_Widget extends WP_Widget{

  public function __construct()
  {
    parent::__construct(
      'home-rollover-widget',
      'Home Rollover Widget',
      array(
        'description' => 'Home rollover widget'
      )
    );
  }

  public function widget( $args, $instance )  {
    // basic output just for this example
    if(is_shop()){
        echo $args['before_widget'];

        echo '<img src="'.esc_url($instance['image_uri']).'" />';
        echo $args['after_widget'];

    }
  }

  public function form( $instance )  {
    // removed the for loop, you can create new instances of the widget instead
    ?>
    <p>
      <label for="<?php echo $this->get_field_id('text'); ?>">Text</label><br />
      <input type="text" name="<?php echo $this->get_field_name('text'); ?>" id="<?php echo $this->get_field_id('text'); ?>" value="<?php echo $instance['text']; ?>" class="widefat" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('image_uri'); ?>">Image</label><br />
      <input type="text" class="img" name="<?php echo $this->get_field_name('image_uri'); ?>" id="<?php echo $this->get_field_id('image_uri'); ?>" value="<?php echo $instance['image_uri']; ?>" />
      <input type="button" class="select-img" value="Select Image" />
    </p>
    <?php
  }


} 
// end class

// init the widget
add_action( 'widgets_init', create_function('', 'return register_widget("Home_Rollover_Widget");') );

// queue up the necessary js
function hrw_enqueue(){
    wp_enqueue_media();
  wp_enqueue_script('media-upload');
  wp_enqueue_script('thickbox');
  // moved the js to an external file, you may want to change the path
  wp_enqueue_script('hrw', get_stylesheet_directory_uri().'/widget.js', null, null, true);
}

add_action('admin_enqueue_scripts', 'hrw_enqueue');

function my_admin_styles(){
    wp_enqueue_style('thickbox');
  }
add_action('admin_print_styles',  'my_admin_styles');


add_action( 'wp', 'redirectDist' );
function redirectDist() {

    $user_meta=get_userdata(get_current_user_id());
    $user_roles=$user_meta->roles;
    if(( get_query_var( 'edit' ) && is_singular( 'product' ) )){
        //echo 'dash';
    }elseif(( !get_query_var( 'edit' ) && is_singular( 'product' ) )){
   
        global $wpdb, $product;
            $product_id = get_the_ID();
            $has_multivendor = get_post_meta( $product_id, '_has_multi_vendor', true );
        
            $sql     = "SELECT `product_id` FROM `{$wpdb->prefix}dokan_product_map` WHERE `map_id`= '$has_multivendor' AND `product_id` != $product_id AND `is_trash` = 0";
            $results = $wpdb->get_results( $sql );

            if($results){
                foreach ( $results as $key => $list ){
                    $product_obj    = new WC_Product( $list->product_id );
                    $post_author_id = get_post_field( 'post_author',$list->product_id );
                    $user_meta=get_userdata($post_author_id);
                    $user_roles=$user_meta->roles;


                    if($user_roles[0] != 'seller'){
                        /*wp_safe_redirect( get_permalink($list->product_id) );*/
                        /*exit;*/



                    }

                }
            }

    }
}






add_action( 'wp_logout', 'auto_redirect_external_after_logout');
function auto_redirect_external_after_logout(){
  wp_redirect( get_site_url() );
  exit();
}



function my_login_logo() { ?>
    <style type="text/css">
        body.login{
            background:#2a364a;
        }
        .login #backtoblog a, .login #nav a {
            text-decoration: none;
            color: #fff !important;
        }
        .login .privacy-policy-page-link{
            display:none;
        }
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/construyamos-logo-acesco.png);
            height:65px;
            width:320px;
            background-size: contain;
            background-repeat: no-repeat;
        }
        body.login .button-primary {
            float: right;
            background-color: #ffcc00 !important;
            color: #000 !important;
            text-transform: uppercase;
            border-radius:0 !important;
            border: none !important;
        }
        .login form .input, .login input[type=password], .login input[type=text] {
            font-size: 24px;
            line-height: 1.33333333;
            width: 100%;
            border-width: .0625rem;
            padding: .1875rem .3125rem;
            margin: 0 6px 16px 0;
            min-height: 40px;
            max-height: none;
            border-radius: 0 !important;
            border-color: #000;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );


add_filter( 'dokan_get_dashboard_nav', 'prefix_dokan_add_seller_nav' );
function prefix_dokan_add_seller_nav( $urls ) {
	  
	  unset( $urls['return-request'] );

    return $urls;
}

add_action( 'woocommerce_register_form', 'add_terms_and_conditions_to_registration', 20 );
function add_terms_and_conditions_to_registration() {

    if ( wc_get_page_id( 'terms' ) > 0 && is_account_page() ) {
        ?>
        <p class="form-row terms wc-terms-and-conditions">
            <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); ?> id="terms" /> <span> He leído y acepto los <a href="https://construyamos.com/colombia/terminos-y-condiciones/" target="_blank">Términos y Condiciones</a>  y <a href="https://construyamos.com/colombia/aviso-de-privacidad/" target="_blank">Políticas de Privacidad</a></span> <span class="required">*</span>
            </label>
            <input type="hidden" name="terms-field" value="1" />
        </p>
    <?php
    }
}

// Validate required term and conditions check box
add_action( 'woocommerce_register_post', 'terms_and_conditions_validation', 20, 3 );
function terms_and_conditions_validation( $username, $email, $validation_errors ) {
    if ( ! isset( $_POST['terms'] ) )
        $validation_errors->add( 'terms_error', __( 'Debes aceptar los Términos y Condiciones y Políticas de Privacidad', 'woocommerce' ) );

    return $validation_errors;
}

add_action( 'user_register', 'myplugin_registration_save', 10, 1 );

function myplugin_registration_save( $user_id ) {

    if ( isset( $_POST['terms'] ) )
        update_field('terminos_y_condiciones_aceptados', 1, 'user_'.$user_id);
        

}

add_filter( 'auth_cookie_expiration', 'keep_me_logged_in_for_1_year' );

function keep_me_logged_in_for_1_year( $expirein ) {
    return YEAR_IN_SECONDS; // 1 year in seconds
}

add_filter('wc_session_expiring', 'so_26545001_filter_session_expiring' );
function so_26545001_filter_session_expiring($seconds) {
    return 60 * 60 * 2; // 23 hours
}

add_filter('wc_session_expiration', 'so_26545001_filter_session_expired' );

function so_26545001_filter_session_expired($seconds) {
    return 60 * 60 * 2; // 24 hours
}

add_filter( 'woocommerce_order_formatted_billing_address' , 'th56t_woo_custom_order_formatted_billing_address', 10, 2 );
function th56t_woo_custom_order_formatted_billing_address( $address, $WC_Order ) {
     $address = array(
        'first_name'    => $WC_Order->billing_first_name,
        'last_name'     => $WC_Order->billing_last_name,
        'nittype'   => $WC_Order->billing_nittype,
        'numero_identificacion'  => $WC_Order->billing_numero_identificacion,
        'address_1'     => $WC_Order->billing_address_1,
        'address_2'     => $WC_Order->billing_address_2,
        'city'          => $WC_Order->billing_city,
        'state'         => $WC_Order->billing_state,
        'postcode'      => $WC_Order->billing_postcode,
        'country'       => $WC_Order->billing_country
        );

    return $address;



}

add_filter( 'woocommerce_formatted_address_replacements', function( $replacements, $args ){

    $replacements['{nittype}'] = $args['nittype'];
    $replacements['{numero_identificacion}'] = $args['numero_identificacion'];
    return $replacements;

}, 10, 2 );

function woo_includes_address_formats($address_formats) {

    $address_formats['default'] = "{name}\n{nittype}\n{nif}\n{address_1}\n{address_2}\n{city}\n{state}\n{postcode}\n{country}";

    return $address_formats;

}

/* THIS CODE IS FOR CHANGING ALERT MESSAGE ON PRODUCT PAGE */
add_filter( 'gettext', 'customizing_variable_product_message', 97, 3 );
function customizing_variable_product_message( $translated_text, $untranslated_text, $domain )
{
    if ($untranslated_text == 'Please select some product options before adding this product to your cart.') {
        $translated_text = __( 'Debe elegir todas las opciones del producto (Acabado, ancho, largo etc..) antes de añadir este producto a tu carrito.', $domain );
    }
    return $translated_text;
}

add_action( 'woocommerce_before_add_to_cart_form', 'misha_before_add_to_cart_btn' );
 
function misha_before_add_to_cart_btn(){
	echo 'Seleccione las opciones:';
}