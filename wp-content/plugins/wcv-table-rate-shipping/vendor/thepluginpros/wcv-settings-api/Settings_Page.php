<?php

namespace WCV_Settings\v1_0_5;

/**
 * Generic settings page class.
 *
 * Used to add new settings tabs.
 *
 * @package WCV_Settings\v1_0_5
 */
class Settings_Page extends \WCVendors_Settings_Page {

    /**
     * Settings page constructor.
     *
     * @param string $id Settings page ID.
     * @param string $label Tab label.
     */
    public function __construct( $id, $label ) {
        $this->id    = $id;
        $this->label = $label;

        parent::__construct();
    }

    /**
     * Get settings array.
     *
     * @param string $current_section
     *
     * @return array
     */
    public function get_settings( $current_section = '' ) {
        return apply_filters( 'wcvendors_get_settings_' . $this->id, [], $current_section );
    }

}
