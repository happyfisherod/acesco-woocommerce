<?php

namespace WordFrame\v1_1_3\Updates;

/**
 * Class EDDUpdateChecker
 *
 * Plugin update checker based on EDD_SL_Plugin_Updater.
 *
 * @package WordFrame\v1_1_3\Updates
 */
class EDDUpdateChecker extends UpdateChecker {

	public function __construct( $options = array() ) {
		parent::__construct( $options );

		// EDD doesn't set the plugin icons, so we set them ourselves
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'add_plugin_icons' ), 100 );
	}

	public function get_supported_features() {
		return [ 'licensing' ];
	}

	public function activate_license( $license_key ) {
		$result = $this->api_request( 'activate_license', $license_key );

		if ( ! is_wp_error( $result ) ) {
			$this->set_license( $license_key );
			$this->set_license_status( 'valid' );
		}

		return $result;
	}

	public function deactivate_license( $license_key ) {
		$result  = $this->api_request( 'deactivate_license', $license_key );
		$ignored = [ 'expired', 'revoked', 'missing', 'site_invalid', 'inactive' ];

		if ( is_wp_error( $result ) && ! in_array( $result->get_error_code(), $ignored ) ) {
			return $result;
		} else {
			$this->set_license_status( 'deactivated' );

			return true;
		}
	}

	private function api_request( $edd_action, $license_key ) {
		$api_params = [
			'edd_action' => $edd_action,
			'license'    => $license_key,
			'item_id'    => $this->options['item_id'],
			'url'        => $this->options['url']
		];

		$response = wp_remote_post(
			$this->options['store_url'],
			[
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params
			]
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $response_code ) {
			return new \WP_Error( $response_code, 'Unexpected response code.' );
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( false === $license_data->success ) {
			return new \WP_Error( $license_data->error );
		} else {
			return true;
		}
	}

	public function check_updates() {
		if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
			require __DIR__ . '/../../lib/easy-digital-downloads/EDD_SL_Plugin_Updater.php';
		}
		new \EDD_SL_Plugin_Updater(
			$this->options['store_url'],
			$this->options['plugin_file'],
			[
				'version' => $this->options['version'],
				'license' => $this->get_license(),
				'item_id' => $this->options['item_id'],
				'author'  => $this->options['author'],
				'url'     => $this->options['url'],
				'beta'    => $this->options['beta']
			]
		);
	}

	public function add_plugin_icons( $_transient_data ) {
		$plugin_name = plugin_basename( $this->options['plugin_file'] );

		if ( ! isset( $_transient_data->response, $_transient_data->response[ $plugin_name ] ) ) {
			return $_transient_data;
		}

		if ( ! isset( $_transient_data->response[ $plugin_name ]->icons ) ) {
			$icons = array_filter(
				[
					'svg' => $this->get_icon_url( 'svg' ),
					'1x'  => $this->get_icon_url( '1x' ),
					'2x'  => $this->get_icon_url( '2x' ),
				]
			);

			$_transient_data->response[ $plugin_name ]->icons = $icons;
		}

		return $_transient_data;
	}

	private function get_icon_url( $size ) {
		$files = [];

		switch ( $size ) {
			case 'svg':
				$files = [ 'icon.svg' ];
				break;
			case '1x':
				$files = [ 'icon-128x128.png', 'icon-128x128.jpg' ];
				break;
			case '2x':
				$files = [ 'icon-256x256.png', 'icon-256x256.jpg' ];
				break;
		}

		foreach ( $files as $file ) {
			$path = dirname( $this->options['plugin_file'] ) . "/assets/{$file}";

			if ( file_exists( $path ) ) {
				return plugin_dir_url( $this->options['plugin_file'] ) . "assets/{$file}";
			}
		}

		return '';
	}
}