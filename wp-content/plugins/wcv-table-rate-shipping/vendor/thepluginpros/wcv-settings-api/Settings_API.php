<?php

namespace WCV_Settings\v1_0_5;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Settings_API
 *
 * Provides a backwards compatible settings API for WC Vendors.
 * 
 * @author Brett Porcelli
 */
class Settings_API extends Legacy_Settings_API {

	const DEFAULT_VALUE = 'a213dab';

	const SETTINGS_PAGE = 'wcv-settings';

	/**
	 * Initializes the legacy API if required.
	 */
	public static function init() {
		if ( self::use_legacy_api() ) {
			parent::init();
		}
	}

	/**
	 * Adds a settings tab.
	 *
	 * @param string $id
	 * @param string $name
	 */
	public static function add_tab( $id, $name ) {
		if ( self::use_legacy_api() ) {
			parent::add_tab( $id, $name );
		} else {
			add_filter( 'wcvendors_get_settings_pages', function( $pages ) use ( $name, $id ) {
			    $pages[] = new Settings_Page( $id, $name );

			    return $pages;
			}, 100 );
		}
	}

	/**
	 * Removes a settings tab.
	 *
	 * @param string $id
	 */
	public static function remove_tab( $id ) {
		if ( self::use_legacy_api() ) {
			parent::remove_tab( $id );
		} else {
			add_filter( 'wcvendors_settings_tabs_array', function( $tabs ) use ( $id ) {
				if ( array_key_exists( $id, $tabs ) ) {
					unset( $tabs[ $id ] );
				}
				return $tabs;
			} );
		}
	}

	/**
	 * Adds a settings section to a particular tab.
	 *
	 * @param array $config
	 *
	 * @return bool
	 */
	public static function add_section( array $config ) {
		$required = [ 'tab', 'id', 'name', 'fields' ];
		$missing  = array_diff( $required, array_keys( array_filter( $config ) ) );

		if ( count( $missing ) > 0 ) {
			return false;
		}

		if ( self::use_legacy_api() ) {
			return parent::add_section( $config );
		} else {
			add_filter( "wcvendors_get_sections_{$config['tab']}", function( $sections ) use( $config ) {
				$sections[ $config['id'] ] = $config['name'];
				return $sections;
			} );

			add_filter( "wcvendors_get_settings_{$config['tab']}", function( $settings, $section ) use ( $config ) {
				if ( $config['id'] !== $section )
					return $settings;
				else
					return $config['fields'];
			}, 10, 2 );

			return true;
		}
	}

	/**
	 * Removes a settings section.
	 *
	 * @param string $tab_id
	 * @param string $section_id
	 */
	public static function remove_section( $tab_id, $section_id ) {
		if ( self::use_legacy_api() ) {
			parent::remove_section( $tab_id, $section_id );
		} else {
			add_filter( "wcvendors_get_sections_{$tab_id}", function( $sections ) use( $section_id ) {
				if ( array_key_exists( $section_id, $sections ) ) {
					unset( $sections[ $section_id ] );
				}
				return $sections;
			} );
		}
	}

	/**
	 * Adds a settings field.
	 *
	 * @param string $tab_id
	 * @param string $section_id
	 * @param array $field
	 */
	public static function add_field( $tab_id, $section_id, $field ) {
		if ( self::use_legacy_api() ) {
			parent::add_field( $tab_id, $section_id, $field );
		} else {
			add_filter( "wcvendors_get_settings_{$tab_id}", function( $fields, $section ) use ( $field, $section_id ) {
				if ( $section_id === $section ) {
					$fields[] = $field;
				}
				return $fields;
			}, 10, 2 );
		}
	}

	/**
	 * Removes a settings field.
	 *
	 * @param string $tab_id
	 * @param string $section_id
	 * @param string $field_id
	 */
	public static function remove_field( $tab_id, $section_id, $field_id ) {
		if ( self::use_legacy_api() ) {
			parent::remove_field( $tab_id, $section_id, $field_id );
		} else {
			add_filter( "wcvendors_get_settings_{$tab_id}", function( $fields, $section ) use ( $field_id, $section_id ) {
				if ( $section_id === $section ) {
					foreach ( $fields as $key => $field ) {
						if ( isset( $field['id'] ) && $field_id === $field['id'] ) {
							unset( $fields[ $key ] );
							break;
						}
					}
				}
				return $fields;
			}, 10, 2 );
		}
	}

	/**
	 * Registers a callback to run when the settings for the given tab and
	 * section are saved.
	 *
	 * @param callable $callback
	 * @param string $tab_id
	 * @param string $section_id
	 */
	public static function on_saved( $callback, $tab_id = '', $section_id = '' ) {
	    if ( self::use_legacy_api() ) {
            $tab_name = parent::find_tab_name( $tab_id, $section_id );

            if ( ! empty( $tab_name ) ) {
                $tab_id     = sanitize_title( $tab_name );
                // Prior to 2.0, builtin tabs didn't have sections
                $section_id = '';
            }
        }

        if ( ! empty( $tab_id ) && ! empty( $section_id ) ) {
            add_action( "wcvendors_update_options_{$tab_id}_{$section_id}", $callback );
        } elseif ( ! empty( $tab_id ) ) {
            add_action( "wcvendors_update_options_{$tab_id}", $callback );
        } else {
            add_action( 'wcvendors_update_options', $callback );
        }
	}

	/**
	 * Get an option by name.
	 *
	 * @param string $name
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public static function get( $name, $default = false ) {
		if ( self::use_legacy_api() ) {
			$value = parent::get( $name, self::DEFAULT_VALUE );
		} else {
			$value = get_option( $name, self::DEFAULT_VALUE );
		}

		if ( self::DEFAULT_VALUE === $value ) {
			return $default;
		} else {
			return $value;
		}
	}

	/**
	 * Set an option.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public static function set( $name, $value ) {
		if ( self::use_legacy_api() ) {
			parent::set( $name, $value );
		}
		update_option( $name, $value );
	}

	/**
	 * Determine whether a checkbox option is enabled.
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public static function is_enabled( $name ) {
		if ( self::use_legacy_api() ) {
			return parent::is_enabled( $name );
		} else {
			return 'yes' === self::get( $name );
		}
	}

	/**
	 * Returns a boolean indicating whether the legacy settings API should be
	 * used.
	 *
	 * @return bool
	 */
	private static function use_legacy_api() {
		return version_compare( WCV_VERSION, '2.0', '<' );
	}

	/**
	 * Returns the value used for checked checkboxes.
	 *
	 * @return string
	 */
	public static function get_checkbox_value() {
		if ( self::use_legacy_api() )
			return parent::get_checkbox_value();
		else
			return '1';
	}

	/**
	 * Adds a settings error.
	 *
	 * @param string $error_message
	 */
	public static function add_error( $error_message ) {
		if ( self::use_legacy_api() )
			parent::add_error( $error_message );
		else
			\WC_Admin_Settings::add_error( $error_message );
	}

	/**
	 * Get the URL of a settings page.
	 *
	 * @param string $tab
	 * @param string $section
	 *
	 * @return string
	 */
	public static function get_settings_url( $tab = '', $section = '' ) {
		if ( self::use_legacy_api() ) {
			$page = parent::SETTINGS_PAGE;
		} else {
			$page = self::SETTINGS_PAGE;
		}

		$url = add_query_arg( 'page', $page, admin_url( 'admin.php' ) );

		if ( ! empty( $tab ) ) {
			$url = add_query_arg( 'tab', $tab, $url );
		}

		if ( ! empty( $section ) ) {
			$url = add_query_arg( 'section', $section, $url );
		}
		return $url;
	}
}