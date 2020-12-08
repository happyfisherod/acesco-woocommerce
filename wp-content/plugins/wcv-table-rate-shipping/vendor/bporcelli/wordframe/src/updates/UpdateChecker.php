<?php

namespace WordFrame\v1_1_3\Updates;

/**
 * Class UpdateChecker
 *
 * Abstract class implemented by all supported plugin update checkers.
 *
 * @package WordFrame\v1_1_3\Updates
 */
abstract class UpdateChecker {

	/**
	 * Options for update checker.
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * UpdateChecker constructor.
	 *
	 * @param array $options
	 */
	public function __construct( $options = array() ) {
		$this->options = $options;
	}

	/**
	 * Returns a boolean indicating whether a feature is supported by this
	 * update checker.
	 *
	 * @param string $feature
	 *
	 * @return bool
	 */
	public function supports( $feature ) {
		return in_array( $feature, $this->get_supported_features() );
	}

	/**
	 * Returns the list of features supported by this update checker.
	 *
	 * @return string[]
	 */
	public function get_supported_features() {
		return [];
	}

	/**
	 * Returns a boolean indicating whether a license key is available for the
	 * plugin.
	 *
	 * @return bool
	 */
	public function has_license() {
		return ! empty( $this->get_license() );
	}

	/**
	 * Returns the license key for the plugin.
	 *
	 * @return string
	 */
	public function get_license() {
		return get_option( $this->options['license_option'], '' );
	}

	/**
	 * Sets the license key for the plugin.
	 *
	 * @param string $license_key
	 */
	public function set_license( $license_key ) {
		update_option( $this->options['license_option'], trim( $license_key ) );
	}

	/**
	 * Returns the status of the current license.
	 *
	 * @return string 'valid, 'invalid', or 'deactivated'
	 */
	public function get_license_status() {
		$status = get_option( $this->license_status_key() );

		if ( false === $status ) {
			return $this->has_license() ? 'valid' : 'invalid';
		}

		return $status;
	}

	/**
	 * Updates the status of the current license.
	 *
	 * @param string $status 'valid, 'invalid', or 'deactivated'
	 */
	public function set_license_status( $status ) {
		update_option( $this->license_status_key(), $status );
	}

	/**
	 * Returns the key for the license status option.
	 */
	private function license_status_key() {
		return $this->options['license_option'] . '_status';
	}

	/**
	 * Activates the given license key.
	 *
	 * This method MUST be overridden by all update checkers that support the
	 * 'licensing' feature.
	 *
	 * @param string $license_key
	 *
	 * @return bool|\WP_Error True on success, else an instance of WP_Error
	 */
	public function activate_license( $license_key ) {
		return new \WP_Error( 'not_implemented' );
	}

	/**
	 * Deactivates the given license key.
	 *
	 * This method MUST be overridden by all update checkers that support the
	 * 'licensing' feature.
	 *
	 * @param string $license_key
	 *
	 * @return bool|\WP_Error True on success, else an instance of WP_Error
	 */
	public function deactivate_license( $license_key ) {
		return new \WP_Error( 'not_implemented' );
	}

	/**
	 * Checks for plugin updates.
	 *
	 * @return void
	 */
	public abstract function check_updates();
}