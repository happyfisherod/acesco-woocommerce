<?php
/**
 * Plugin license row template.
 *
 * @global string $license_status
 * @global string $license_key
 * @global string $plugin_basename
 * @global string $slug
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_active   = 'valid' === $license_status;
$notice_type = $is_active ? 'success' : 'error';

if ( $is_active ) {
	$status  = __( 'License active.' );
	$message = "<a href='#' class='wfr-deactivate-license'>" . __( 'Deactivate license' ) . "</a>.";
} else {
	$status  = __( 'License inactive.' );
	$message = sprintf(
		__( 'Please %1$sactivate your license%2$s to receive the latest updates.' ),
		"<a href='#' class='wfr-activate-license'>",
		"</a>"
	);
}

$update_plugins = get_site_transient( 'update_plugins' );
$has_update     = isset( $update_plugins->response[ $plugin_basename ] );

?>
<tr class="plugin-update-tr active" id="<?php echo $slug; ?>-license" data-plugin="<?php echo $plugin_basename; ?>"
    data-license="<?php echo $license_key; ?>">
    <td colspan="3" class="plugin-update plugin-license colspanchange">
        <div class="notice inline notice-<?php echo $notice_type; ?> notice-alt">
            <p><strong><?php echo $status; ?></strong> <span><?php echo $message; ?></span></p>

			<?php if ( ! $is_active ): ?>
                <div class="wfr-license-form" style="display: none;">
                    <label class="screen-reader-text" for="<?php echo $slug; ?>-license_key">
						<?php _e( 'License key' ); ?>
                    </label>

                    <input type="text" name="license_key" id="<?php echo $slug; ?>-license_key"
                           value="<?php echo $license_key; ?>"
                           placeholder="<?php esc_html_e( 'Enter your license key' ); ?>">

                    <button type="button" class="button button-primary wfr-activate">
						<?php _e( 'Activate License' ); ?>
                    </button>
                    <button type="button" class="button button-link wfr-activate-cancel">
						<?php _e( 'Cancel' ); ?>
                    </button>
                </div>
			<?php endif; ?>

            <style type="text/css">
                <?php if ( $has_update ): ?>

                .plugins .plugin-update-tr#wcv-payouts-license .plugin-update {
                    box-shadow: none;
                }

                <?php else: ?>

                .plugins [data-slug="<?php echo $slug; ?>"] td, .plugins [data-slug="<?php echo $slug; ?>"] th {
                    box-shadow: none;
                }

                <?php endif; ?>
            </style>
        </div>
    </td>
</tr>