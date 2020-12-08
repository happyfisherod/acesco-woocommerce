<?php

namespace WordFrame\v1_1_3\Updates;

/**
 * Class UpdateCheckerFactory
 *
 * Factory for building UpdateChecker instances.
 *
 * @package WordFrame\v1_1_3\Updates
 */
class UpdateCheckerFactory {

	/**
	 * Registered update checkers.
	 *
	 * @var array
	 */
	private static $checkers = [
		'EDD_SL' => 'EDDUpdateChecker',
	];

	/**
	 * Builds and returns an instance of the update checker with ID `checker`.
	 *
	 * @param string $checker
	 * @param array  $options
	 *
	 * @return UpdateChecker|null
	 */
	public static function build( $checker, $options ) {
		if ( array_key_exists( $checker, self::$checkers ) ) {
			$classname = __NAMESPACE__ . '\\' . self::$checkers[ $checker ];

			return new $classname( $options );
		}

		return null;
	}

}