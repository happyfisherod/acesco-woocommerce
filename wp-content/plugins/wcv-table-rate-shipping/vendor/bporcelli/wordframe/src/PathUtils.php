<?php

namespace WordFrame\v1_1_3;

/**
 * Class PathUtils
 *
 * Contains static utilities for manipulating paths.
 *
 * @package WordFrame
 */
class PathUtils {

	/**
	 * Converts the absolute path `path` into a relative path rooted at
	 * directory `root_dir`.
	 *
	 * By default, the path will be relativized to the WP root directory.
	 *
	 * @param string $path
	 * @param string $root_dir
	 *
	 * @return string
	 */
	public static function relativize( $path, $root_dir = ABSPATH ) {
		return str_replace( $root_dir, '/', $path );
	}

	/**
	 * Joins one or more path components with a forward slash.
	 *
	 * @param array $components Path components
	 *
	 * @return string Joined components
	 */
	public static function join( ...$components ) {
		return implode( '/', $components );
	}

}