<?php
/**
* Plugin Name: Wanderlust Mercado Pago Marketplace
* Description: Wanderlust Mercado Pago Marketplace integrates with WooCommerce 
* Version: 0.0.1
* Author: Wanderlust Web Design
* Author URI: https://wanderlust-webdesign.com
* Text Domain: wanderlust-meli-mp
* Domain Path: /languages/
*
* @author Wanderlust Web Design
* @package Wanderlust Web Design Mercado Pago Marketplace
* @version 0.0.1
*/

  // If this file is called directly, abort.
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

  require plugin_dir_path( __FILE__ ) . 'includes/class-wanderlust-meli.php';
  require plugin_dir_path( __FILE__ ) . 'lib/meli.php';
  require plugin_dir_path( __FILE__ ) . 'lib/vendor/autoload.php';


	function run_wanderlust_meli_mp() {

		$plugin = new Wanderlust_Meli_Mp();
		$plugin->run();

	}
	run_wanderlust_meli_mp();  


  add_filter('woocommerce_checkout_update_order_review', 'clear_wc_shipping_rates_cache');
  add_action('woocommerce_before_cart', 'clear_wc_shipping_rates_cache');

  function clear_wc_shipping_rates_cache(){
      $packages = WC()->cart->get_shipping_packages();

      foreach ($packages as $key => $value) {
          $shipping_session = "shipping_for_package_$key";

          unset(WC()->session->$shipping_session);
      }
  }


add_action( 'woocommerce_thankyou', 'misha_poll_form', 4 );
 
function misha_poll_form( $order_id ) {
 	 $data = json_encode($_GET);
	$order = wc_get_order($order_id);
 	if($_GET['collection_status'] == 'approved'){
		$order->add_order_note(
					'Mercado Pago: ' . __( 'Payment approved.', 'woocommerce-mercadopago' )
				);
		$order->add_order_note(
					'Mercado Pago: ' .$data
				);
				$order->payment_complete();
				 
	}
 	
}
  
	 

?>