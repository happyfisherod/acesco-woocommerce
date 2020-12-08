<?php
/**
 * Table Rate Shipping helper functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Checks whether a user is a vendor.
 *
 * @param int $user_id User ID. Defaults to current user ID.
 *
 * @return bool
 */
function wcv_trs_is_user_vendor( $user_id = 0 ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	return apply_filters( 'wcv_trs_is_user_vendor', false, $user_id );
}
