<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wanderlust_Meli_i18n_Mp {

	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wanderlust-meli-mp',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}