<?php
/**
 * The core plugin class.
 *
 * The admin-specific functionality of the plugin.
 *
 *
 * @since      1.0.0
 * @package    Wanderlust Web Design Mercado Pago Marketplace
 * @subpackage wanderlust-meli/includes
 * @author     Conrado Galli <info@wanderlust-webdesign.com>
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wanderlust_Meli_Admin_Mp {

	private $plugin_name;
	private $version;
	private $soap_url;
	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}


	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wanderlust-meli-admin.css', array(), $this->version, 'all' );

	}

	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wanderlust-meli-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	public function add_settings_tab( $settings_tabs ) {
        
		$settings_tabs['wanderlust_meli_mp'] = __( 'Mercado Pago Marketplace Settings', 'wanderlust-meli' );
		return $settings_tabs;
    
	}
	
  public static function settings_tab() {
	   woocommerce_admin_fields( self::get_settings() );
	}

  public static function update_settings() {
		woocommerce_update_options( self::get_settings() );
	}
		
	public function wanderlust_meli_mp_add_metaboxes(){
		//add_meta_box( 'wanderlust_meli_mp_metabox', __('Mercado Libre','wanderlust-meli'), array( $this, 'wanderlust_meli_mp_order_buttons' ), 'shop_order', 'side', 'core' );
	}
	
	public static function wanderlust_meli_mp_order_buttons(){
		
	}

  public static function get_settings() {
				global $woocommerce, $wp_session;
				session_start();
				$callback = get_site_url();
    
   
       
        $dates = get_option('fullupdate_meli');
      
 				$appid = get_option('wc_wanderlust_meli_mp_appid');
 				$secretkey = get_option('wc_wanderlust_meli_mp_secretkey');
	 			$access_token = get_option('wanderlust_meli_mp_auth_access_token');
 				$refresh_token = get_option('wanderlust_meli_mp_auth_refresh_token');
				$expires_in = get_option('wanderlust_meli_mp_auth_expires_in');		

			
				$meli = new Meli($appid, $secretkey, $access_token, $refresh_token);
			
				if($_GET['code'] || $access_token) {
					
					// If code exist and session is empty
					if($_GET['code'] && !($access_token)) {
						// If the code was in get parameter we authorize
						$user = $meli->authorize($_GET['code'], $callback .'/wp-admin/admin.php?page=wc-settings&tab=wanderlust_meli_mp');

						// Now we create the sessions with the authenticated user
						$access_token = $user['body']->access_token;
						$expires_in = time() + $user['body']->expires_in;
						$refresh_token = $user['body']->refresh_token;
 						
						update_option( 'wanderlust_meli_mp_auth_code', $_GET['code'] );
						update_option( 'wanderlust_meli_mp_auth_access_token', $user['body']->access_token );
						update_option( 'wanderlust_meli_mp_auth_expires_in', time() + $user['body']->expires_in );
						update_option( 'wanderlust_meli_mp_auth_refresh_token', $user['body']->refresh_token );

					} else {
						// We can check if the access token in invalid checking the time
						if($expires_in < time()) {
							try {
								// Make the refresh proccess
								$refresh = $meli->refreshAccessToken();

								// Now we create the sessions with the new parameters
								$access_token = $refresh['body']->access_token;
								$expires_in = time() + $refresh['body']->expires_in;
								$refresh_token = $refresh['body']->refresh_token;
 								update_option( 'wanderlust_meli_mp_auth_access_token', $access_token );
								update_option( 'wanderlust_meli_mp_auth_expires_in', $expires_in );
								update_option( 'wanderlust_meli_mp_auth_refresh_token', $refresh_token );
							} catch (Exception $e) {
									echo "Exception: ",  $e->getMessage(), "\n";
							}
						}
            
						$users = $meli->authorize($_GET['code'], $callback .'/wp-admin/admin.php?page=wc-settings&tab=wanderlust_meli_mp');
						 
					}	 
				}
			
	
	 			$params = array();
				$result = $meli->get('users/me?access_token='.$access_token, $params);
				$nickname = $result['body']->nickname;
				$user_id = $result['body']->id;			
 			  
    
				if(!empty($nickname)){ ?>
	 
				<? } else { ?>
		 
 
				<? }
			
        $settings = array(
            'section_title' => array(
											'name'     => __( 'Mercado Pago Marketplace Settings', 'wanderlust-meli' ),
											'type'     => 'title',
											'desc'     => '',
											'id'       => 'wc_wanderlust_meli_mp_section_title'
           			 ),
					'section_contactos' => array(
											'name'     => __( 'Instructions', 'wanderlust-meli' ),
											'type'     => 'title',
											'desc' => 	'<a href="https://applications.mercadolibre.com.co/home" target="_blank">1- Create APP on Mercado Pago</a> with this info:<br>
																	<strong>a.</strong> Redirect URI: <strong>https://construyamos.com/colombia/dashboard/withdraw/</strong><br>
																	<strong>b.</strong> Scopes: offline_access , read write<br>
																	<strong>c.</strong> Tópicos: items, created orders, payments<br>
																	<strong>d.</strong> Notifications Callback URL: <strong>'.$callback .'/?wc-api=wanderlust_meli_mp </strong>	<br><br>
											 2- Complete <strong>App ID y Secret Key</strong> provided by your APP.
											',
											'id'       => 'wc_wanderlust_meli_mp_section_contactos'
									),
            'appid' => array(
											'name' => __( 'App ID', 'wanderlust-meli' ),
											'type' => 'text',
											'desc' => __( 'App ID provided by Mercado Pago', 'wanderlust-meli' ),
											'id'   => 'wc_wanderlust_meli_mp_appid'
            			),
            'secretkey' => array(
											'name' => __( 'Secret Key', 'wanderlust-meli' ),
											'type' => 'password',
											'desc' => __( 'Secret Key provided by Mercado Pago', 'wanderlust-meli' ),
											'id'   => 'wc_wanderlust_meli_mp_secretkey'
            			),
            'sectionend' => array(
										 'type' => 'sectionend',
										 'id' => 'wc_wanderlust_meli_mp_section_title'
            			),
 						'section_contactob' => array(
											'name'     => __( 'Authorization', 'wanderlust-meli' ),
											'type'     => 'title',
											'desc' => 	'<div id="authorize_meli_info">
                                                        <strong>DATE..:</strong> '.$dates .' <br>

																	<strong>User.:</strong> '.$nickname .'  <strong>User ID.:</strong> '.$user_id.'<br>
																	<a id="unauthorize_meli" href="'.$callback .'/wp-admin/admin.php?page=wc-settings&tab=wanderlust_meli_mp&unauthorize=yes">REMOVE AUTHORIZATION</a> <br><br>
																	
																	</div><br><br>

																	<a id="authorize_meli" href="' . $meli->getAuthUrl($callback .'/wp-admin/admin.php?page=wc-settings&tab=wanderlust_meli_mp', Meli::$AUTH_URL['MLA']) . ' ">3- Authorize your site with the APP</a> <br>
											',
											'id'       => 'wc_wanderlust_meli_mp_section_contactob'
									),
						'section_contacto' => array(
											'name'     => __( 'Soporte', 'wanderlust-meli' ),
											'type'     => 'title',
											'desc' => 	'Soporte: <a href="https://wanderlust-webdesign.com/contact">https://wanderlust-webdesign.com/contact</a> <br>	',
											'id'       => 'wc_wanderlust_meli_mp_section_contacto'
									),
						'sectionend_contacto' => array(
											 'type' => 'sectionend',
											 'id' => 'wc_wanderlust_meli_mp_section_contacto'
									),
        );

        return apply_filters( 'wc_wanderlust_meli_mp', $settings );
  }
	 		
	public function woocommerce_melibox_box_add_box() {
		//add_meta_box( 'woocommerce-melibox-box', __('Mercado Libre','wanderlust-meli'), array( $this, 'woocommerce_melibox_box_create_box_content' ), 'shop_order', 'side', 'core' );
	}
	

	public static function woocommerce_melibox_box_create_box_content() {
		global $wpdb, $product, $post, $woocommerce, $user;
		
	}	
  
  public function wanderlust_mp_account_menu_items( $items ) {
      // Remove the logout menu item.
      $logout = $items['customer-logout'];
      unset( $items['customer-logout'] );

      // Insert your custom endpoint.
      $items['mercadopago'] = 'Mercado Pago';

      // Insert back the logout item.
      $items['customer-logout'] = $logout;

      return $items;    
    
  }
  
  public function my_account_mercadopago_endpoints() {
 	//  add_rewrite_endpoint( 'mercadopago', EP_ROOT | EP_PAGES );
   // flush_rewrite_rules(); 
 }
  
 public function mercadopago_endpoint_content() {
    
 }
  
}