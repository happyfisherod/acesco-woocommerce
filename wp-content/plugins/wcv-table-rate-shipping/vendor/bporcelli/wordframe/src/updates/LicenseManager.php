<?php

namespace WordFrame\v1_1_3\Updates;

use WordFrame\v1_1_3\PathUtils;

/**
 * Class LicenseManager
 *
 * Provides an interface for activating, deactivating, and managing a plugin
 * license.
 *
 * @package WordFrame\v1_1_3\Updates
 */
class LicenseManager {

	/**
	 * Absolute path to main plugin file.
	 *
	 * @var string
	 */
	private $plugin_file = '';

	/**
	 * Plugin update checker.
	 *
	 * @var UpdateChecker
	 */
	private $checker = null;

	/**
	 * Constructor.
	 *
	 * @param string        $plugin_file
	 * @param UpdateChecker $checker
	 */
	public function __construct( $plugin_file, $checker ) {
		$this->plugin_file = $plugin_file;
		$this->checker     = $checker;

		$this->hooks();
	}

	/**
	 * Adds required action hooks.
	 */
	private function hooks() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
		add_action( 'wp_ajax_wfr_activate_license', array( $this, 'ajax_manage_license' ) );
		add_action( 'wp_ajax_wfr_deactivate_license', array( $this, 'ajax_manage_license' ) );
		add_action( 'after_plugin_row_' . plugin_basename( $this->plugin_file ), array( $this, 'output_license_row' ) );
	}

	/**
	 * Enqueues the scripts and stylesheets for the license manager.
	 */
	public static function enqueue_assets() {
		$screen = get_current_screen();

		if ( 'plugins' !== $screen->id ) {
			return;
		}

		$assets_dir = PathUtils::relativize( __DIR__ . '/../../assets' );

		if ( ! wp_style_is( 'wfr-license-manager', 'enqueued' ) ) {
			wp_enqueue_style( 'wfr-license-manager', "{$assets_dir}/css/license-manager.css" );
		}

		if ( ! wp_script_is( 'wfr-license-manager', 'enqueued' ) ) {
			wp_enqueue_script( 'wfr-license-manager', "{$assets_dir}/js/license-manager.js", [ 'jquery' ] );

			wp_localize_script(
				'wfr-license-manager',
				'wfr_manager_data',
				[
					'nonce' => wp_create_nonce( 'wfr-manage-license' )
				]
			);
		}
	}

	/**
	 * Activates or deactivates a license key via AJAX.
	 */
	public function ajax_manage_license() {
		check_ajax_referer( 'wfr-manage-license', 'nonce' );

		$plugin_basename = esc_attr( $_POST['plugin'] );
		$plugin_file     = WP_PLUGIN_DIR . "/$plugin_basename";
		$license_key     = esc_attr( $_POST['license_key'] );

		if ( $plugin_file !== $this->plugin_file ) {
			return;
		}

		if ( did_action( 'wp_ajax_wfr_activate_license' ) ) {
			$result = $this->checker->activate_license( $license_key );
		} else {
			$result = $this->checker->deactivate_license( $license_key );
		}

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( [ 'error' => $result->get_error_code() ] );
		} else {
			wp_send_json_success( [ 'html' => $this->get_license_row_html() ] );
		}
	}

	/**
	 * Returns the HTML for the license management interface.
	 */
	private function get_license_row_html() {
		ob_start();
		$this->output_license_row();

		return ob_get_clean();
	}

	/**
	 * Outputs the license management interface after the plugin row.
	 */
	public function output_license_row() {
		$license_key     = $this->checker->get_license();
		$license_status  = $this->checker->get_license_status();
		$plugin_basename = plugin_basename( $this->plugin_file );
		$slug            = substr( $plugin_basename, 0, strpos( $plugin_basename, '/' ) );
		$file            = basename( $plugin_basename );

		require 'views/html-license-row.php';
	}
}