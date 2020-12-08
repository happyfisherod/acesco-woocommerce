<?php

namespace WordFrame\v1_1_3;

/**
 * Base plugin class extended by WordFrame plugins.
 *
 * @package WordFrame\v1_1_3
 */
abstract class Plugin {

	/**
	 * @var string Path to the main plugin file
	 */
	public $file = '';

	/**
	 * @var string $version Plugin version
	 */
	public $version = '';

	/**
	 * @var AssetManager Asset manager instance
	 */
	public $assets = null;

	/**
	 * Initializes the plugin.
	 *
	 * @param string $plugin_file Path to main plugin file
	 * @param array  $config      Configuration options
	 *
	 * @return static The single plugin instance
	 */
	public static function init( $plugin_file, array $config ) {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$config   = array_merge(
				[
					'requires' => [],
					'updates'  => [],
				],
				$config
			);
			$instance = new static( $plugin_file, $config );
		}

		return $instance;
	}

	/**
	 * Prevents deserialization of the plugin.
	 */
	public function __wakeup() {
		return null;
	}

	/**
	 * Prevents cloning of the plugin.
	 */
	public function __clone() {
	}

	// todo: load text domain and set version based on specified plugin file

	/**
	 * Plugin constructor.
	 *
	 * This is intended for internal use only - please use Plugin::init() instead.
	 *
	 * @param string $plugin_file
	 * @param array  $config
	 */
	public function __construct( $plugin_file, $config ) {
		$this->file = $plugin_file;

		$this->load_text_domain();
		$this->init_default_hooks();

		if ( ! empty( $config['requires'] ) ) {
			DependencyChecker::init( $this, $config['requires'] );
		}
		if ( ! empty( $config['updates'] ) ) {
			Updater::init( $this, $config['updates'] );
		}
	}

	/**
	 * Initializes the default activation and deactivation hooks.
	 */
	private function init_default_hooks() {
		if ( is_callable( array( $this, 'activate' ) ) ) {
			register_activation_hook( $this->file, array( $this, 'activate' ) );
		}
		if ( is_callable( array( $this, 'deactivate' ) ) ) {
			register_deactivation_hook( $this->file, array( $this, 'deactivate' ) );
		}
	}

	/**
	 * Gets the full path to a file or directory in the plugin directory.
	 *
	 * @param string $path Relative path to file or directory
	 *
	 * @return string
	 */
	public function path( $path = '' ) {
		return plugin_dir_path( $this->file ) . $path;
	}

	/**
	 * Gets the URL of a file or directory in the plugin directory.
	 *
	 * @param string $path Relative path to file or directory
	 *
	 * @return string
	 */
	public function url( $path ) {
		return plugin_dir_url( $this->file ) . $path;
	}

	/**
	 * Runs when all plugin dependencies are met.
	 */
	public function load() {
		$this->assets = new AssetManager( $this->file );
	}

	/**
	 * Runs when plugin initialization fails due to missing dependencies.
	 *
	 * @param string $type       Type of violation - php or plugin
	 * @param array  $violations Information about missing dependencies
	 */
	public function error( $type, $violations ) {
		$print_notice = function () use ( $type, $violations ) {
			switch ( $type ) {
				case 'plugins':
					$this->plugins_notice( $violations );
					break;
				case 'php':
					$this->php_notice( $violations );
					break;
			}
		};
		add_action( 'admin_notices', $print_notice );
	}

	/**
	 * Displays a notice that one or more required plugins is missing.
	 *
	 * @param array $violations
	 */
	private function plugins_notice( array $violations ) {
		foreach ( $violations as $violation ) {
			?>
            <div class="notice notice-error">
                <p>
					<?php echo $this->get_plugin_notice( $violation ); ?>
                </p>
            </div>
			<?php
		}
	}

	/**
	 * Displays a notice when PHP requirements are not met.
	 *
	 * @param array $violations
	 */
	private function php_notice( array $violations ) {
		foreach ( $violations as $type => $violation ) {
			$violation['type'] = $type;

			?>
            <div class="notice notice-error">
                <p><?php echo $this->get_php_notice( $violation ); ?></p>
            </div>
			<?php
		}
	}

	/**
	 * Loads the plugin text domain.
	 */
	abstract function load_text_domain();

	/**
	 * Returns the text to display in the notice when a required plugin is missing.
	 *
	 * @param array $violation
	 *
	 * @return string
	 */
	abstract function get_plugin_notice( array $violation );

	/**
	 * Returns the text to display when a PHP requirement is not met.
	 *
	 * @param array $violation Information about the missing requirement.
	 *
	 * @return string
	 */
	abstract function get_php_notice( $violation );

}
