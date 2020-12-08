<?php

use WordFrame\v1_1_3\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WCV_Table_Rate_Shipping
 *
 * Main plugin class.
 */
final class WCV_Table_Rate_Shipping extends Plugin {

    /**
     * @var string The current plugin version.
     */
    public $version = '2.0.2';

    /**
     * @var WCV_TRS_Shipping_Method Shipping method instance.
     */
    public $method = null;

    /**
     * Bootstraps the plugin after WooCommerce is loaded.
     */
    public function load() {
        parent::load();

        $this->load_submodules();
        $this->hooks();
        $this->init_installer();
    }

    /**
     * Registers action hooks and filters.
     */
    private function hooks() {
        add_filter( 'plugin_action_links_' . plugin_basename( $this->file ), array( $this, 'add_action_links' ) );
        add_filter( 'woocommerce_shipping_methods', array( $this, 'register_shipping_method' ), 12, 1 );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );

        if ( apply_filters( 'wcv_trs_split_cart_shipping_packages', false ) ) {
	        add_filter( 'woocommerce_cart_shipping_packages', array( $this, 'split_shipping_packages' ), 20, 1 );
	        add_filter( 'woocommerce_shipping_package_name', array( $this, 'rename_vendor_shipping_package' ), 10, 3 );
        }
    }

    /**
     * Loads the plugin's submodules.
     */
    private function load_submodules() {
	    require_once 'functions.php';

    	// Try to find the integration to load. If no compatible plugins are found, bail.
	    if ( class_exists( 'WC_Vendors' ) ) {
	        require_once 'integrations/wc-vendors/class-wcv-trs-wc-vendors-integration.php';
	    } elseif ( class_exists( 'weDevs_Dokan' ) ) {
	        require_once 'integrations/dokan/class-wcv-trs-dokan-integration.php';
	    } else {
	    	add_action( 'admin_notices', array( $this, 'plugin_inactive_notice' ) );

	    	return;
	    }

	    // Load submodules
	    require_once 'class-wcv-trs-tables.php';

        if ( ! class_exists( 'WCV_TRS_Shipping_Method' ) ) {
            require_once 'class-wcv-trs-method.php';
        }

        $this->method = new WCV_TRS_Shipping_Method();

        if ( $this->method->is_enabled() ) {
            require_once 'class-wcv-trs-admin.php';
	        require_once 'class-wcv-trs-shipping-tables-shortcode.php';
	        require_once 'class-wcv-trs-product-tab.php';

	        do_action( 'wcv_trs_init' );
        }
    }

    /**
     * Initializes the plugin installer.
     */
    private function init_installer() {
        if ( ! class_exists( 'WCV_TRS_Install' ) ) {
            require 'class-wcv-trs-install.php';
        }

        WCV_TRS_Install::init();
    }

    /**
     * Register our custom shipping method.
     *
     * @param array $methods
     *
     * @return array
     */
    public function register_shipping_method( $methods ) {
        $methods[] = $this->method;

        return $methods;
    }

    /**
     * Add a "Settings" link to the Plugins table.
     *
     * @param array $links Array of plugin action links.
     *
     * @return array $links
     */
    public function add_action_links( $links ) {
        $settings_url = add_query_arg(
            [
                'page'    => 'wc-settings',
                'tab'     => 'shipping',
                'section' => 'wcv_table_rate_shipping',
            ],
            admin_url( 'admin.php' )
        );

        $new_links = [
            'settings' => "<a href='$settings_url'>" . __( 'Settings', 'wcv-table-rate-shipping' ) . "</a>",
        ];

        return array_merge( $new_links, $links );
    }

    /**
     * Registers common scripts and styles.
     */
    public function register_assets() {
        if ( ! wp_style_is( 'woocommerce.admin', 'registered' ) ) {
            $this->assets->register( 'style', 'woocommerce.admin' );
        }

        if ( ! wp_script_is( 'woocommerce.jquery-blockui/jquery.blockUI', 'registered' ) ) {
            $this->assets->register(
                'script',
                'woocommerce.jquery-blockui/jquery.blockUI',
                [
                    'deps'      => [ 'jquery' ],
                    'ver'       => '2.70',
                    'in_footer' => true,
                ]
            );
        }

        if ( ! wp_script_is( 'woocommerce.jquery-tiptip/jquery.tipTip', 'registered' ) ) {
            $this->assets->register(
                'script',
                'woocommerce.jquery-tiptip/jquery.tipTip',
                [
                    'deps'      => [ 'jquery' ],
                    'ver'       => WC_VERSION,
                    'in_footer' => true,
                ]
            );
        }

        if ( ! wp_script_is( 'woocommerce.admin/backbone-modal', 'registered' ) ) {
            $this->assets->register(
                'script',
                'woocommerce.admin/backbone-modal',
                [
                    'deps' => [
                        'underscore',
                        'backbone',
                        'wp-util',
                    ],
                    'ver'  => WC_VERSION,
                ]
            );
        }

        if ( ! wp_script_is( 'woocommerce.admin/wc-enhanced-select', 'registered' ) ) {
            if ( version_compare( WC_VERSION, '3.2', '>=' ) ) {
                $select_lib = 'woocommerce.selectWoo/selectWoo.full';
            } else {
                $select_lib = 'woocommerce.select2/select2.full';
            }

            if ( ! wp_script_is( $select_lib, 'registered' ) ) {
                $this->assets->register(
                    'script',
                    $select_lib,
                    [
                        'deps' => [ 'jquery' ],
                        'ver'  => WC_VERSION,
                    ]
                );
            }

            $this->assets->register(
                'script',
                'woocommerce.admin/wc-enhanced-select',
                [
                    'deps'     => [ $select_lib ],
                    'ver'      => WC_VERSION,
                    'localize' => [
                        'wc_enhanced_select_params' => [
                            'i18n_matches_1'            => _x(
                                'One result is available, press enter to select it.',
                                'enhanced select',
                                'woocommerce',
                                'wcv-table-rate-shipping'
                            ),
                            'i18n_matches_n'            => _x(
                                '%qty% results are available, use up and down arrow keys to navigate.',
                                'enhanced select',
                                'woocommerce',
                                'wcv-table-rate-shipping'
                            ),
                            'i18n_no_matches'           => _x(
                                'No matches found',
                                'enhanced select',
                                'woocommerce',
                                'wcv-table-rate-shipping'
                            ),
                            'i18n_ajax_error'           => _x(
                                'Loading failed',
                                'enhanced select',
                                'woocommerce',
                                'wcv-table-rate-shipping'
                            ),
                            'i18n_input_too_short_1'    => _x(
                                'Please enter 1 or more characters',
                                'enhanced select',
                                'woocommerce',
                                'wcv-table-rate-shipping'
                            ),
                            'i18n_input_too_short_n'    => _x(
                                'Please enter %qty% or more characters',
                                'enhanced select',
                                'woocommerce',
                                'wcv-table-rate-shipping'
                            ),
                            'i18n_input_too_long_1'     => _x(
                                'Please delete 1 character',
                                'enhanced select',
                                'woocommerce',
                                'wcv-table-rate-shipping'
                            ),
                            'i18n_input_too_long_n'     => _x(
                                'Please delete %qty% characters',
                                'enhanced select',
                                'woocommerce',
                                'wcv-table-rate-shipping'
                            ),
                            'i18n_selection_too_long_1' => _x(
                                'You can only select 1 item',
                                'enhanced select',
                                'woocommerce',
                                'wcv-table-rate-shipping'
                            ),
                            'i18n_selection_too_long_n' => _x(
                                'You can only select %qty% items',
                                'enhanced select',
                                'woocommerce',
                                'wcv-table-rate-shipping'
                            ),
                            'i18n_load_more'            => _x(
                                'Loading more results&hellip;',
                                'enhanced select',
                                'woocommerce',
                                'wcv-table-rate-shipping'
                            ),
                            'i18n_searching'            => _x(
                                'Searching&hellip;',
                                'enhanced select',
                                'woocommerce',
                                'wcv-table-rate-shipping'
                            ),
                            'ajax_url'                  => admin_url( 'admin-ajax.php' ),
                            'search_products_nonce'     => wp_create_nonce( 'search-products' ),
                            'search_customers_nonce'    => wp_create_nonce( 'search-customers' ),
                        ],
                    ],
                ]
            );
        }

        $this->assets->register(
            'script',
            'wcv-table-rate-shipping.trs-tables',
            [
                'deps' => [
                    'jquery',
                    'wp-util',
                    'underscore',
                    'backbone',
                    'jquery-ui-sortable',
                    'woocommerce.jquery-blockui/jquery.blockUI',
                    'woocommerce.jquery-tiptip/jquery.tipTip',
                    'woocommerce.admin/wc-enhanced-select',
                    'woocommerce.admin/backbone-modal',
                ],
                'ver'  => wcv_trs()->version,
            ]
        );

        $this->assets->register(
            'style',
            'wcv-table-rate-shipping.trs-front',
            [
                'deps' => [ 'dashicons' ],
                'ver'  => wcv_trs()->version,
            ]
        );
    }

    /**
     * Split order into several shipping packages, one for each vendor.
     *
     * @param array $packages Array of shipping packages.
     *
     * @return array $packages
     */
    public function split_shipping_packages( $packages = array() ) {
	    $packages = array();

	    foreach ( WC()->cart->get_cart() as $item_key => $item ) {
		    if ( ! $item['data']->needs_shipping() ) {
			    continue;
		    }

		    $vendor_id = get_post_field( 'post_author', $item['product_id'] );

		    if ( ! array_key_exists( $vendor_id, $packages ) ) {
			    $packages[ $vendor_id ] = array(
				    'contents'        => array(),
				    'contents_cost'   => 0,
				    'applied_coupons' => WC()->cart->applied_coupons,
				    'vendor_id'       => $vendor_id,
				    'destination'     => array(
					    'country'   => WC()->customer->get_shipping_country(),
					    'state'     => WC()->customer->get_shipping_state(),
					    'postcode'  => WC()->customer->get_shipping_postcode(),
					    'city'      => WC()->customer->get_shipping_city(),
					    'address'   => WC()->customer->get_shipping_address(),
					    'address_2' => WC()->customer->get_shipping_address_2(),
				    ),
			    );
		    }

		    $packages[ $vendor_id ]['contents'][ $item_key ] = $item;
		    $packages[ $vendor_id ]['contents_cost']         += $item['line_total'];
	    }

        return $packages;
    }

    /**
     * Rename the shipping packages based on the vendor sold by.
     *
     * @param string $title the shipping package title
     * @param int $count the shipping package position
     * @param array $package the shipping package from the cart
     *
     * @return string $title the modified shipping package title
     */
    public function rename_vendor_shipping_package( $title, $count, $package ) {
        $vendor_sold_by = get_the_author_meta( 'display_name', $package['vendor_id'] );
        $title          = sprintf( __( '%s Shipping', 'wcv-table-rate-shipping' ), $vendor_sold_by );

        return apply_filters( 'wcv_trs_vendor_shipping_package_title', $title, $count, $package, $vendor_sold_by );
    }

    /**
     * Loads the plugin text domain.
     */
    public function load_text_domain() {
        load_plugin_textdomain( 'wcv-table-rate-shipping', false, basename( $this->path() ) . '/languages/' );
    }

    /**
     * Returns the text to display in the notice when a required plugin is missing.
     *
     * @param array $violation
     *
     * @return string
     */
    public function get_plugin_notice( array $violation ) {
        switch ( $violation['type'] ) {
            case 'wrong_version':
                return sprintf(
                /* translators: 1: required plugin name, 2: minimum version */
                    __(
                        '<strong>%1$s needs to be updated.</strong> WCV Payouts requires %1$s %2$s+.',
                        'wcv-payouts',
                        'wcv-table-rate-shipping'
                    ),
                    $violation['data']['name'],
                    $violation['data']['version']
                );
            case 'inactive':
            case 'not_installed':
                return sprintf(
                /* translators: 1: required plugin name */
                    __(
                        '<strong>%1$s not detected.</strong> Please install or activate %1$s to use WCV Payouts.',
                        'wcv-payouts',
                        'wcv-table-rate-shipping'
                    ),
                    $violation['data']['name']
                );
        }
        return '';
    }

    /**
     * Returns the text to display when a PHP requirement is not met.
     *
     * @param array $violation Information about the missing requirement.
     *
     * @return string
     */
    public function get_php_notice( $violation ) {
        if ( 'extensions' === $violation['type'] ) {
            $ext_list = implode( ', ', $violation['data']['required'] );
            /* translators: 1 - list of required PHP extensions */
            return sprintf(
                __(
                    '<strong>Required PHP extensions are missing.</strong> WCV Payouts requires %1$s.',
                    'wcv-payouts',
                    'wcv-table-rate-shipping'
                ),
                $ext_list
            );
        } elseif ( 'version' === $violation['type'] ) {
            /* translators: 1 - required php version */
            return sprintf(
                __(
                    '<strong>PHP needs to be updated.</strong> WCV Payouts requires PHP %1$s+.',
                    'wcv-payouts',
                    'wcv-table-rate-shipping'
                ),
                $violation['data']['required']
            );
        }
        return '';
    }

    /**
     * Runs on plugin deactivation.
     */
    public function deactivate() {
        WCV_TRS_Install::deactivate();
    }

    /**
     * Notice displayed in WP admin when no compatible plugins are detected.
     */
	public function plugin_inactive_notice() {
		?>
		<div class="notice notice-error">
			<?php
			printf(
				'<p><strong>%1$s</strong> %2$s</p>',
				__( 'Table Rate Shipping is not active.', 'wcv-table-rate-shipping' ),
				__(
					'Please install <a href="https://wordpress.org/plugins/wc-vendors/" target="_blank">WC Vendors</a> or <a href="https://wordpress.org/plugins/dokan-lite/" target="_blank">Dokan</a> to use Table Rate Shipping.',
					'wcv-table-rate-shipping'
				)
			);
			?>
		</div>
		<?php
    }

}
