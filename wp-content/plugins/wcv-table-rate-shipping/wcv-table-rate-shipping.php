<?php

/**
 * Plugin Name:          WCV Table Rate Shipping
 * Description:          Allow vendors to set shipping prices based on the quantity, weight, or total cost of items purchased.
 * Author:               The Plugin Pros
 * Author URI:           https://thepluginpros.com
 * Version:              2.0.2
 * Requires at least:    4.4.0
 * Tested up to:         5.5.0
 * WC requires at least: 3.0.0
 * WC tested up to:      4.4.0
 * Text Domain:          wcv-table-rate-shipping
 * Domain Path:          /languages/
 *
 * @category             Plugin
 * @copyright            Copyright © 2020 The Plugin Pros
 * @author               Brett Porcelli
 * @package              WCV_Table_Rate_Shipping
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Returns the single Table Rate Shipping instance.
 *
 * @return WCV_Table_Rate_Shipping
 */
function wcv_trs() {
    return WCV_Table_Rate_Shipping::init(
        __FILE__,
        [
            'requires' => [
                'plugins' => [
                    'woocommerce/woocommerce.php' => [
                        'name'    => 'WooCommerce',
                        'version' => '3.0',
                    ]
                ],
            ],
            'updates'  => [
                'checker' => 'EDD_SL',
                'options' => [
                    'store_url'      => 'http://thepluginpros.com',
                    'license_option' => 'wcv_trs_license_key',
                    'item_id'        => 84,
                    'author'         => 'The Plugin Pros',
                    'beta'           => false,
                ],
            ],
        ]
    );
}

wcv_trs();
