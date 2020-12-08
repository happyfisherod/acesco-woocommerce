<?php

namespace WordFrame\v1_1_3;

/**
 * Class AssetManager
 *
 * Provides methods for enqueueing and registering scripts and stylesheets.
 *
 * @package WordFrame
 */
class AssetManager {

	/**
	 * @var string Path to plugin directory.
	 */
	private $plugin_dir_path = '';

	/**
	 * Constructor.
	 *
	 * Sets the default assets path.
	 *
	 * @param string $file
	 */
	public function __construct( $file ) {
		$this->plugin_dir_path = dirname( $file );
	}

	/**
	 * Register a script or stylesheet.
	 *
	 * @param string $type    'style' or 'script'.
	 * @param string $slug    Asset slug.
	 * @param array  $options Additional options.
	 */
	public function register( $type, $slug, array $options = array() ) {
		$this->register_or_enqueue( 'register', $type, $slug, $options );
	}

	/**
	 * Enqueue a script or stylesheet.
	 *
	 * @param string $type    'style' or 'script'.
	 * @param string $slug    Asset slug.
	 * @param array  $options Additional options.
	 */
	public function enqueue( $type, $slug, array $options = array() ) {
		$this->register_or_enqueue( 'enqueue', $type, $slug, $options );
	}

	/**
	 * Register or enqueue an asset.
	 *
	 * @param string $action 'register' or 'enqueue'
	 * @param string $type   'script' or 'style'
	 * @param string $slug
	 * @param array  $options
	 *
	 * @return string Asset handle.
	 */
	private function register_or_enqueue( $action, $type, $slug, array $options ) {
		$options = array_merge(
			[
				'deps'      => [],
				'ver'       => '1.0.0', // todo: use plugin version
				'media'     => 'all',
				'in_footer' => false,
				'localize'  => [],
			],
			$options
		);

		$url  = $this->find_asset_url( $slug, $type );
		$func = "wp_{$action}_{$type}";

		if ( 'style' === $type ) {
			$func( $slug, $url, $options['deps'], $options['ver'], $options['media'] );
		} else {
			if ( 'script' === $type ) {
				$func( $slug, $url, $options['deps'], $options['ver'], $options['in_footer'] );
			}
		}

		if ( ! empty( $options['localize'] ) ) {
			foreach ( $options['localize'] as $name => $data ) {
				wp_localize_script( $slug, $name, $data );
			}
		}

		return $slug;
	}

	/**
	 * Finds an asset URL based on the asset slug.
	 *
	 * @param string $slug Asset slug.
	 * @param string $type Asset type. Can be 'script' or 'style'.
	 *
	 * @return string
	 */
	private function find_asset_url( $slug, $type ) {
		$path = $this->find_asset_path( $slug, $type );

		return str_replace( WP_CONTENT_DIR, content_url(), $path );
	}

	/**
	 * Finds an asset path based on the asset slug.
	 *
	 * @param string $slug Asset slug
	 * @param string $type 'script' or 'style'
	 *
	 * @return string
	 */
	private function find_asset_path( $slug, $type ) {
		if ( false !== ( $i_dot = strpos( $slug, '.' ) ) ) {
			$plugin   = substr( $slug, 0, $i_dot );
			$filename = substr( $slug, $i_dot + 1 );
		} else {
			$plugin   = basename( $this->plugin_dir_path );
			$filename = $slug;
		}

		$plugin_dir  = PathUtils::join( WP_CONTENT_DIR, 'plugins', $plugin );
		$ext         = 'script' === $type ? 'js' : 'css';
		$is_rel_path = 0 !== strpos( $filename, '/' );

		if ( $is_rel_path ) {
			$file_path = PathUtils::join( $plugin_dir, 'assets', $ext, $filename );
		} else {
			$file_path = PathUtils::join( $plugin_dir, substr( $filename, 1 ) );
		}

		$file            = "{$file_path}.{$ext}";
		$compressed_file = "{$file_path}.min.{$ext}";
		$script_debug    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

		if ( file_exists( $compressed_file ) && ( ! file_exists( $file ) || ! $script_debug ) ) {
			return $compressed_file;
		} else {
			return $file;
		}
	}

}
