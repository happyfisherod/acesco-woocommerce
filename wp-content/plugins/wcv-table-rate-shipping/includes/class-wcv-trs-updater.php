<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class WCV_TRS_Updater
 *
 * Runs required data updates in the background.
 */
class WCV_TRS_Updater extends WP_Background_Process {

    /**
     * @var string
     */
    protected $action = 'wcv_trs_data_update';

    /**
     * Dispatch updater.
     *
     * Updater will still run via cron job if this fails for any reason.
     */
    public function dispatch() {
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $dispatched = parent::dispatch();
        $logger     = new WC_Logger();

        if ( is_wp_error( $dispatched ) ) {
            $logger->add(
                'wcv_trs_db_updates',
                sprintf(
                    'Unable to dispatch Simple Sales Tax updater: %s',
                    $dispatched->get_error_message()
                )
            );
        }
    }

    /**
     * Handle cron healthcheck
     *
     * Restart the background process if not already running
     * and data exists in the queue.
     */
    public function handle_cron_healthcheck() {
        if ( $this->is_process_running() ) {
            // Background process already running.
            return;
        }

        if ( $this->is_queue_empty() ) {
            // No data to process.
            $this->clear_scheduled_event();

            return;
        }

        $this->handle();
    }

    /**
     * Schedule fallback event.
     */
    protected function schedule_event() {
        if ( ! wp_next_scheduled( $this->cron_hook_identifier ) ) {
            wp_schedule_event( time() + 10, $this->cron_interval_identifier, $this->cron_hook_identifier );
        }
    }

    /**
     * Is the updater running?
     *
     * @return boolean
     */
    public function is_updating() {
        return false === $this->is_queue_empty();
    }

    /**
     * Task
     *
     * Override this method to perform any actions required on each
     * queue item. Return the modified item for further processing
     * in the next pass through. Or, return false to remove the
     * item from the queue.
     *
     * @param string $callback Update callback function
     *
     * @return mixed
     */
    protected function task( $callback ) {
        if ( ! defined( 'WCV_TRS_UPDATING' ) ) {
            define( 'WCV_TRS_UPDATING', true );
        }

        $logger = new WC_Logger();

        include_once 'wcv-trs-update-functions.php';

        $return = false;

        if ( is_callable( $callback ) ) {
            $logger->add( 'wcv_trs_db_updates', sprintf( 'Running %s callback', $callback ) );
            $return = call_user_func( $callback );
            $logger->add( 'wcv_trs_db_updates', sprintf( 'Finished %s callback', $callback ) );
        } else {
            $logger->add( 'wcv_trs_db_updates', sprintf( 'Could not find %s callback', $callback ) );
        }

        return $return;
    }

    /**
     * Complete
     *
     * Override if applicable, but ensure that the below actions are
     * performed, or, call parent::complete().
     */
    protected function complete() {
        $logger = new WC_Logger();
        $logger->add( 'wcv_trs_db_updates', 'Data update complete' );

        update_option( 'wcv_trs_version', wcv_trs()->version );

        if ( ! class_exists( 'WC_Admin_Notices' ) ) {
            require WC()->plugin_path() . '/includes/admin/class-wc-admin-notices.php';
        }

        WC_Admin_Notices::remove_notice( 'wcv_trs_update' );
        WC_Admin_Notices::add_custom_notice( 'wcv_trs_update', $this->update_complete_notice() );

        parent::complete();
    }

    /**
     * Returns the markup for the notice displayed after the update is complete.
     */
    private function update_complete_notice() {
        return sprintf(
            '<p>%s</p>',
            __(
                'Table Rate Shipping update complete. Thanks for updating to the latest version!',
                'wcv-table-rate-shipping'
            )
        );
    }

}
