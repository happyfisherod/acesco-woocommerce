<?php

namespace WordFrame\v1_1_3;

/**
 * Checks whether all platform and plugin dependencies for a plugin are met.
 *
 * @package WordFrame
 */
class DependencyChecker {

	/**
	 * @var Plugin Plugin instance
	 */
	private $plugin;

	/**
	 * @var array Declared dependencies
	 */
	private $dependencies;

	/**
	 * Initializes the dependency checker for a plugin.
	 *
	 * @param Plugin $plugin
	 * @param array  $dependencies
	 *
	 * @return DependencyChecker
	 */
	public static function init( $plugin, $dependencies ) {
		return new self( $plugin, $dependencies );
	}

	/**
	 * Constructor.
	 *
	 * @param Plugin $plugin       Plugin instance
	 * @param array  $dependencies Declared dependencies
	 */
	public function __construct( $plugin, $dependencies ) {
		$this->plugin       = $plugin;
		$this->dependencies = $dependencies;

		add_action( 'plugins_loaded', array( $this, 'check_dependencies' ) );
	}

	/**
	 * Executes the plugin load callback if all dependencies are met, or the
	 * error callback otherwise.
	 */
	public function check_dependencies() {
		foreach ( $this->dependencies as $type => $type_dependencies ) {
			$violations = [];

			if ( 'php' === $type ) {
				$violations = $this->check_php( $type_dependencies );
			} elseif ( 'plugins' === $type ) {
				$violations = $this->check_plugins( $type_dependencies );
			}

			if ( count( $violations ) > 0 ) {
				$this->plugin->error( $type, $violations );

				return;
			}
		}

		$this->plugin->load();
	}

	/**
	 * Check for violated PHP constraints.
	 *
	 * In the special case where a string is provided as the only constraint, we
	 * assume it specifies the minimum required PHP version.
	 *
	 * @param array|string $constraints
	 *
	 * @return array
	 */
	private function check_php( $constraints ) {
		$violations = array();

		if ( is_string( $constraints ) ) {
			$constraints = array( 'version' => $constraints );
		}

		if ( isset( $constraints['version'] ) ) {
			$min_version = $constraints['version'];

			if ( version_compare( PHP_VERSION, $min_version, '<' ) ) {
				$violations['version'] = array(
					'type' => 'wrong_version',
					'data' => array(
						'required'  => $min_version,
						'installed' => PHP_VERSION,
					),
				);
			}
		}
		if ( isset( $constraints['extensions'] ) ) {
			foreach ( $constraints['extensions'] as $name ) {
				if ( extension_loaded( $name ) ) {
					continue;
				}
				$violations['extensions'] = array(
					'type' => 'missing_extension',
					'data' => array(
						'missing'  => $name,
						'required' => $constraints['extensions'],
					),
				);
			}
		}

		return $violations;
	}

	/**
	 * Check for violated plugin constraints.
	 *
	 * @param array $required_plugins Array of required plugins.
	 *
	 * @return array
	 */
	private function check_plugins( array $required_plugins ) {
		$violations = array();

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		foreach ( $required_plugins as $slug => $requirements ) {
			$plugin_file = WP_PLUGIN_DIR . '/' . $slug;

			if ( ! file_exists( $plugin_file ) ) {
				$violation = 'not_installed';
			} else {
				$plugin = get_plugin_data( $plugin_file );

				if ( version_compare( $plugin['Version'], $requirements['version'], '<' ) ) {
					$violation = 'wrong_version';
				} elseif ( ! is_plugin_active( $slug ) ) {
					$violation = 'inactive';
				}
			}

			if ( isset( $violation ) ) {
				$violations[ $slug ] = array(
					'type' => $violation,
					'data' => $requirements,
				);
			}
		}

		return $violations;
	}

}
