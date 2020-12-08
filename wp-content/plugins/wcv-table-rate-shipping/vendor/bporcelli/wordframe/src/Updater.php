<?php

namespace WordFrame\v1_1_3;

use WordFrame\v1_1_3\Updates\LicenseManager;
use WordFrame\v1_1_3\Updates\UpdateCheckerFactory;

/**
 * Provides automatic plugin updates and license checking capabilities.
 *
 * @package WordFrame\v1_1_3
 */
class Updater {

	/**
	 * Initializes a plugin updater instance based on the provided configuration.
	 *
	 * @param Plugin $plugin
	 * @param array  $config
	 */
	public static function init( $plugin, $config ) {
		$options = array_merge(
			[
				'plugin_file'    => $plugin->file,
				'version'        => $plugin->version,
				'license_option' => self::get_default_license_option( $plugin->file ),
				'url'            => home_url(),
			],
			$config['options']
		);

		$checker = UpdateCheckerFactory::build( $config['checker'], $options );

		if ( is_null( $checker ) ) {
			error_log( 'WordFrame error: update checker not found.' );
		} else {
			if ( $checker->supports( 'licensing' ) ) {
				new LicenseManager( $plugin->file, $checker );
			}
			$checker->check_updates();
		}
	}

	/**
	 * Gets the default license option name.
	 *
	 * @param string $plugin_file
	 *
	 * @return string
	 */
	private static function get_default_license_option( $plugin_file ) {
		$normalized_name = str_replace( [ ' ', '-' ], [ '', '_' ], basename( $plugin_file, '.php' ) );

		return "{$normalized_name}_license";
	}

}
