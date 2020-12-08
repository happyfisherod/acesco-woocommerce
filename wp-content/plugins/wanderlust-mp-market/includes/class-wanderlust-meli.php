<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wanderlust Web Design Mercado Pago Marketplace
 * @subpackage wanderlust-meli/includes
 * @author     Conrado Galli <info@wanderlust-webdesign.com>
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

 
class Wanderlust_Meli_Mp {

	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {

		$this->plugin_name = 'Wanderlust Mercado Pago Market Place';
		$this->version = '0.0.1';
		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		add_action('woocommerce_api_'.strtolower(get_class($this)), array(&$this, 'handle_callback'));

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wanderlust-meli-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wanderlust-meli-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/meli.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'checkout/woocommerce-mercadopago.php';
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/index.php';
 
		$this->loader = new Wanderlust_Meli_Loader_Mp();

	}


	private function define_admin_hooks() {
		
		$plugin_admin = new Wanderlust_Meli_Admin_Mp( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );		
		$this->loader->add_filter( 'woocommerce_settings_tabs_array', $plugin_admin, 'add_settings_tab',50 );		
		$this->loader->add_action( 'woocommerce_settings_tabs_wanderlust_meli_mp',$plugin_admin,'settings_tab' );		
		$this->loader->add_action( 'woocommerce_update_options_wanderlust_meli_mp',$plugin_admin,'update_settings' );			
 		//$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'woocommerce_melibox_box_add_box' );	
		$this->loader->add_filter( 'woocommerce_account_menu_items', $plugin_admin, 'wanderlust_mp_account_menu_items',10,2 );		
  	$this->loader->add_action( 'init', $plugin_admin, 'my_account_mercadopago_endpoints' );		
		$this->loader->add_action( 'woocommerce_account_mercadopago_endpoint', $plugin_admin, 'mercadopago_endpoint_content' );		
   
	}
 
 
	private function define_public_hooks() {

	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}
		
	public function handle_callback(){ // ESCUCHA MELI API POR NOTIFICACIONES //
		global $wpdb, $woocommerce, $user;
		
		session_start();
    //header('HTTP/1.1 200 OK', true, 200);
    header( 'HTTP/1.1 200 OK' );
        
 		$postBodyRaw = file_get_contents('php://input');
 		$postBody = json_decode($postBodyRaw);
 		
    $mp_user = 'wanderlust_meli_mp_'.$postBody->user_id; 
           
    $wanderlust_meli_mp_user = get_option($mp_user);		
   
    if($wanderlust_meli_mp_user){
      MercadoPago\SDK::setAccessToken($wanderlust_meli_mp_user);
      
      $filters = array(
          "notification_url" => "https://construyamos.com/colombia/?wc-api=wanderlust_meli_mp",
          "status" => 'approved',
      );

      $payment = MercadoPago\Payment::search($filters);

      if($payment){
        foreach($payment as $payments){
          if($payments->external_reference){
            
            $order_id = str_replace('WC-', '', $payments->external_reference);
                
            if($payments->status == 'approved'){
              $order = wc_get_order($order_id);
              $data_js = serialize($payments);

              if($order->status != 'completed' && !empty($order->status)){
                $order->payment_complete();
                update_post_meta($order_id, 'mercado_pago_mp', $data_js);
              } else {
                update_post_meta($order_id, 'mercado_pago_mp', $data_js);            
              }
            }
          }
        }
      } 
    }
 		exit();
	}

}

 
 
