<?php

namespace WCV_Settings\v1_0_5;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Legacy_Settings_API
 *
 * Settings API for WC Vendors < 2.0.
 *
 * @package WCV_Util
 */
class Legacy_Settings_API {

	const SETTINGS_PAGE = 'wc_prd_vendor';

	/**
	 * Map from new settings to legacy settings.
	 *
	 * @param array
	 */
	protected static $settings_map = [
		'wcvendors_capability_product_types'           => 'hide_product_types',
		'wcvendors_capability_product_type_options'    => 'hide_product_type_options',
		'wcvendors_capability_product_data_tabs'       => 'hide_product_panel',
		'wcvendors_capability_products_enabled'        => 'can_submit_products',
		'wcvendors_capability_products_edit'           => 'can_edit_published_products',
		'wcvendors_capability_products_live'           => 'can_submit_live_products',
		'wcvendors_capability_orders_enabled'          => 'can_show_orders',
		'wcvendors_capability_orders_export'           => 'can_export_csv',
		'wcvendors_capability_order_customer_email'    => 'can_view_order_emails',
		'wcvendors_capability_order_read_notes'        => 'can_view_order_comments',
		'wcvendors_capability_order_update_notes'      => 'can_submit_order_comments',
		'wcvendors_capability_frontend_reports'        => 'can_view_frontend_reports',
		'wcvendors_vendor_commission_rate'             => 'default_commission',
		'wcvendors_display_label_sold_by_enable'       => 'sold_by',
		'wcvendors_label_sold_by'                      => 'sold_by_label',
		'wcvendors_display_label_store_info'           => 'seller_info_label',
		'wcvendors_vendor_dashboard_page_id'           => 'vendor_dashboard_page',
		'wcvendors_shop_settings_page_id'              => 'shop_settings_page',
		'wcvendors_product_orders_page_id'             => 'product_orders_page',
		'wcvendors_vendor_terms_page_id'               => 'terms_to_apply_page',
		'wcvendors_display_shop_headers'               => 'shop_headers_enabled',
		'wcvendors_display_shop_description_html'      => 'shop_html_enabled',
		'wcvendors_display_shop_display_name'          => 'vendor_display_name',
		'wcvendors_vendor_shop_permalink'              => 'vendor_shop_permalink',
		'wcvendors_display_advanced_stylesheet'        => 'product_page_css',
		'wcvendors_vendor_allow_registration'          => 'show_vendor_registration',
		'wcvendors_vendor_approve_registration'        => 'manual_vendor_registration',
		'wcvendors_vendor_give_taxes'                  => 'give_tax',
		'wcvendors_vendor_give_shipping'               => 'give_shipping',
		'wcvendors_payments_paypal_instantpay_enable'  => 'instapay',
		'wcvendors_payments_paypal_schedule'           => 'schedule',
		'wcvendors_payments_paypal_email_enable'       => 'mail_mass_pay_results',
		'wcvendors_dashboard_page_id'                  => 'dashboard_page_id',
		'wcvendors_vendor_store_header_type'           => 'vendor_store_header_type',
		'wcvendors_store_shop_headers'                 => 'store_shop_headers',
		'wcvendors_store_single_headers'               => 'store_single_headers',
		'wcvendors_disable_wp_admin_vendors'           => 'disable_wp_admin_vendors',
		'wcvendors_vendor_dashboard_notice'            => 'vendor_dashboard_notice',
		'wcvendors_allow_form_markup'                  => 'allow_form_markup',
		'wcvendors_single_product_tools'               => 'single_product_tools',
		'wcvendors_product_management_cap'             => 'product_management_cap',
		'wcvendors_order_management_cap'               => 'order_management_cap',
		'wcvendors_shop_coupon_management_cap'         => 'shop_coupon_management_cap',
		'wcvendors_settings_management_cap'            => 'settings_management_cap',
		'wcvendors_ratings_management_cap'             => 'ratings_management_cap',
		'wcvendors_shipping_management_cap'            => 'shipping_management_cap',
		'wcvendors_view_store_cap'                     => 'view_store_cap',
		'wcvendors_capability_product_delete'          => 'delete_product_cap',
		'wcvendors_capability_product_duplicate'       => 'duplicate_product_cap',
		'wcvendors_capability_products_approved'       => 'can_edit_approved_products',
		'wcvendors_dashboard_date_range'               => 'dashboard_date_range',
		'wcvendors_orders_sales_range'                 => 'orders_sales_range',
		'wcvendors_products_per_page'                  => 'products_per_page',
		'wcvendors_coupons_per_page'                   => 'coupons_per_page',
		'wcvendors_capability_order_customer_name'     => 'hide_order_customer_name',
		'wcvendors_capability_order_customer_shipping' => 'hide_order_customer_shipping_address',
		'wcvendors_capability_order_customer_billling' => 'hide_order_customer_billing_address',
		'wcvendors_capability_order_customer_phone'    => 'hide_order_customer_phone',
		'wcvendors_hide_order_view_details'            => 'hide_order_view_details',
		'wcvendors_hide_order_shipping_label'          => 'hide_order_shipping_label',
		'wcvendors_hide_order_order_note'              => 'hide_order_order_note',
		'wcvendors_hide_order_tracking_number'         => 'hide_order_tracking_number',
		'wcvendors_hide_order_mark_shipped'            => 'hide_order_mark_shipped',
		'wcvendors_vendor_product_trash'               => 'vendor_product_trash',
		'wcvendors_vendor_coupon_trash'                => 'vendor_coupon_trash',
		'wcvendors_default_store_banner_src'           => 'default_store_banner_src',
		'wcvendors_verified_vendor_label'              => 'verified_vendor_label',
		'wcvendors_disable_select2'                    => 'disable_select2',
		'wcvendors_feedback_page_id'                   => 'feedback_page_id',
		'wcvendors_vendor_ratings_label'               => 'vendor_ratings_label',
		'wcvendors_feedback_system'                    => 'feedback_system',
		'wcvendors_feedback_display'                   => 'feedback_display',
		'wcvendors_feedback_sort_order'                => 'feedback_sort_order',
		'wcvendors_feedback_order_status'              => 'feedback_order_status',
		'wcvendors_commission_coupon_action'           => 'commission_coupon_action',
		'wcvendors_commission_type'                    => 'commission_type',
		'wcvendors_commission_percent'                 => 'commission_percent',
		'wcvendors_commission_amount'                  => 'commission_amount',
		'wcvendors_commission_fee'                     => 'commission_fee',
		'wcvendors_product_form_template'              => 'product_form_template',
		'wcvendors_save_product_redirect'              => 'save_product_redirect',
		'wcvendors_product_form_cap'                   => 'product_form_cap',
		'wcvendors_category_display'                   => 'category_display',
		'wcvendors_hide_categories_list'               => 'hide_categories_list',
		'wcvendors_category_limit'                     => 'category_limit',
		'wcvendors_tag_display'                        => 'tag_display',
		'wcvendors_tag_separator'                      => 'tag_separator',
		'wcvendors_file_display'                       => 'file_display',
		'wcvendors_hide_attributes_list'               => 'hide_attributes_list',
		'wcvendors_vendor_image_prefix'                => 'vendor_image_prefix',
		'wcvendors_product_max_gallery_count'          => 'product_max_gallery_count',
		'wcvendors_product_max_image_width'            => 'product_max_image_width',
		'wcvendors_product_max_image_height'           => 'product_max_image_height',
		'wcvendors_product_min_image_width'            => 'product_min_image_width',
		'wcvendors_product_min_image_height'           => 'product_min_image_height',
		'wcvendors_hide_settings_general'              => 'hide_settings_general',
		'wcvendors_hide_settings_store'                => 'hide_settings_store',
		'wcvendors_hide_settings_payment'              => 'hide_settings_payment',
		'wcvendors_hide_settings_branding'             => 'hide_settings_branding',
		'wcvendors_hide_settings_shipping'             => 'hide_settings_shipping',
		'wcvendors_hide_settings_social'               => 'hide_settings_social',
		'wcvendors_hide_signup_general'                => 'hide_signup_general',
		'wcvendors_hide_signup_store'                  => 'hide_signup_store',
		'wcvendors_hide_signup_payment'                => 'hide_signup_payment',
		'wcvendors_hide_signup_branding'               => 'hide_signup_branding',
		'wcvendors_hide_signup_shipping'               => 'hide_signup_shipping',
		'wcvendors_hide_signup_social'                 => 'hide_signup_social',
		'wcvendors_vendor_signup_notice'               => 'vendor_signup_notice',
		'wcvendors_vendor_pending_notice'              => 'vendor_pending_notice',
	];

	/**
	 * Map from new settings tabs to legacy settings tabs.
	 *
	 * @param array
	 */
	protected static $tab_map = [];

	/**
	 * Settings sections keyed by tab ID.
	 *
	 * @var array
	 */
	protected static $tabs = [];

	/**
	 * Current settings section.
	 */
	protected static $section = '';

    /**
     * Has the settings API been initialized?
     *
     * @var bool
     */
    protected static $initialized = false;

	/**
	 * Initializes the tab map and registers hooks.
	 */
	public static function init() {
		if ( self::$initialized ) {
		    return;
        }

	    self::init_tab_map();

		add_action( 'plugins_loaded', array( __CLASS__, 'reload_settings' ), 20 );
		add_filter( 'wc_prd_vendor_options_on_update', array( __CLASS__, 'save_legacy_options' ), 10, 2 );
		add_action( 'admin_init', array( __CLASS__, 'set_section' ) );
		add_action( 'wc_prd_vendor_options_type_multiselect', array( __CLASS__, 'output_multiselect' ) );
		add_filter( 'geczy_sanitize_multiselect', array( __CLASS__, 'sanitize_multiselect' ), 10, 2 );
		add_action( 'init', array( __CLASS__, 'fix_multiselect_issue' ), 100 );

		self::$initialized = true;
	}

    /**
     * Fix: Multi-select options are rendered twice when multiple versions of the settings API are in use.
     */
    public static function fix_multiselect_issue() {
        for ( $patch = 1; $patch <= 4; $patch++ ) {
            $class    = sprintf( 'WCV_Settings\v1_0_%d\Legacy_Settings_API', $patch );
            $callable = array( $class, 'output_multiselect' );

            if ( has_action( 'wc_prd_vendor_options_type_multiselect', $callable ) ) {
                remove_action( 'wc_prd_vendor_options_type_multiselect', $callable );
            }
        }
    }

	/**
	 * Initializes the tab map.
	 */
	private static function init_tab_map() {
		self::$tab_map = [
			'general'      => __( 'General', 'wcvendors' ),
			'commission'   => __( 'Commissions', 'wcvendors' ),
			'capabilities' => [
				''        => __( 'Capabilities', 'wcvendors' ),
				'product' => __( 'Products', 'wcvendors' ),
				'orders'  => __( 'Pro', 'wcvendors' ),
				'trash'   => __( 'Pro', 'wcvendors' ),
			],
			'display'      => [
				''              => __( 'General', 'wcvendors' ),
				'labels'        => __( 'General', 'wcvendors' ),
				'pro_dashboard' => __( 'Pro', 'wcvendors' ),
				'branding'      => __( 'Pro', 'wcvendors' ),
				'notices'       => __( 'Pro', 'wcvendors' ),
			],
			'payments'     => __( 'Payments', 'wcvendors' ),
			'forms'        => [
				'product'  => __( 'Product Form', 'wcvendors' ),
				'settings' => __( 'Settings Form', 'wcvendors' ),
				'signup'   => __( 'Signup Form', 'wcvendors' ),
			],
			'ratings'      => __( 'Vendor Ratings', 'wcvendors' ),
		];
    }

	/**
	 * Reload the WC Vendors settings after all plugins are loaded. This allows
	 * consumers to add settings on plugins_loaded.
	 */
	public static function reload_settings() {
		/** @var \WC_Vendors $wc_vendors */
		global $wc_vendors;

		remove_action( 'admin_enqueue_scripts', array( \WC_Vendors::$pv_options, 'admin_enqueue_scripts' ) );
		remove_action( 'admin_init', array( \WC_Vendors::$pv_options, 'register_options' ) );
		remove_action( 'admin_menu', array( \WC_Vendors::$pv_options, 'create_menu' ) );
		remove_filter( 'plugin_action_links', array( \WC_Vendors::$pv_options, 'add_settings_link' ) );

		\WC_Vendors::$pv_options = null;

		$wc_vendors->load_settings();
	}

	/**
	 * Saves legacy options under new keys to ensure a seamless update.
	 *
	 * @param array $clean
	 * @param string $tab
	 *
	 * @return array
	 */
	public static function save_legacy_options( $clean, $tab ) {
		$options = \WC_Vendors::$pv_options->tabs[ $tab ];

		foreach ( $options as $option ) {
			if ( ! isset( $option['id'], $option['type'] ) ) {
				continue;
			}

			$id = sanitize_text_field( strtolower( $option['id'] ) );

			if ( ! array_key_exists( $id, $clean ) ) {
			    continue;
            }

			// Save builtin options under their old keys when applicable
			if ( array_key_exists( $id, self::$settings_map ) ) {
				$clean[ self::$settings_map[ $id ] ] = $clean[ $id ];
			}

			// Save user added options under their new keys
            if ( false === array_search( $id, self::$settings_map ) ) {
	            if ( 'checkbox' === $option['type'] ) {
		            $value = $clean[ $id ] ? 'yes' : 'no';
	            } else {
		            $value = $clean[ $id ];
	            }
	            update_option( $id, $value );
            }
		}

        // Trigger new save actions after legacy options are saved
        $on_updated = function( $old_value, $new_value ) use( $tab, &$on_updated ) {
            \WC_Vendors::$pv_options->current_options = $new_value;

            // Prevent loops
            remove_action( 'update_option_wc_prd_vendor_options', $on_updated );

            self::trigger_save_actions( $tab );
        };

        add_action( 'update_option_wc_prd_vendor_options', $on_updated, 10, 2 );

		return $clean;
	}

    /**
     * Triggers the new wcvendors_update_options actions when the settings are saved.
     *
     * @param string $tab
     */
    public static function trigger_save_actions( $tab ) {
        $current_section = self::$section;

        if ( $current_section ) {
            do_action( "wcvendors_update_options_{$tab}_{$current_section}" );
        }

        do_action( "wcvendors_update_options_{$tab}" );

        do_action( 'wcvendors_update_options' );
    }

	/**
	 * Saves the current settings section and populates the fields for the
	 * current tab if the settings are being saved.
	 */
	public static function set_section() {
        self::$section = isset( $_REQUEST['section'] ) ? $_REQUEST['section' ] : '';

        // Ensure that the section query variable is not included in tab URLs
        if ( isset( $_GET['section'] ) ) {
            unset( $_GET['section'] );
        }

		$updating = isset( $_POST['currentTab'], $_POST['update'] );

		if ( $updating ) {
			$tab     = $_POST['currentTab'];
			$section = self::$section;

			if ( isset( self::$tabs[ $tab ], self::$tabs[ $tab ][ $section ] ) ) {
				$tabs = array_merge( \WC_Vendors::$pv_options->tabs, [
					$tab => self::$tabs[ $tab ][ $section ]['fields']
				] );

				\WC_Vendors::$pv_options->tabs = $tabs;
			}
		}
	}

	/**
	 * Adds a settings tab.
	 *
	 * @param string $id
	 * @param string $name
	 */
	public static function add_tab( $id, $name ) {
		add_filter( 'wc_prd_vendor_options', function( $options ) use ( $id, $name ) {
			$options[] = [
				'type' => 'heading',
				'name' => $name,
				'id'   => $id
			];
			return $options;
		} );
	}

	/**
	 * Removes a settings tab.
	 *
	 * @param string $id
	 */
	public static function remove_tab( $id ) {
		add_filter( 'wc_prd_vendor_options', function( $options ) use ( $id ) {
			list( $start, $end ) = self::find_tab_boundaries( $options, $id );
			if ( -1 !== $start && -1 !== $end ) {
				array_splice( $options, $start, $end - $start + 1 );
			}
			return $options;
		} );
	}

	/**
	 * Adds a settings section to a particular tab.
	 *
	 * @param array $config
	 *
	 * @return bool
	 */
	public static function add_section( array $config ) {
		add_filter( 'wc_prd_vendor_options', function( $options ) use ( $config ) {
			list( $start, $end ) = self::find_tab_boundaries( $options, $config['tab'] );

			if ( 0 > $start || 0 > $end ) {
				return $options;
			}

			$tab_id = self::find_tab_slug( $config['tab'] );

			if ( ! array_key_exists( $tab_id, self::$tabs ) ) {
				// Dummy field must be added to avoid undefined index errors
				$general_fields = array_splice( $options, $start + 1, $end - $start - 1, [
					[ 'type' => 'dummy' ],
				] );

				self::$tabs[ $tab_id ] = [
					'' => [
						'name'   => __( 'General', 'wcvendors' ),
						'fields' => $general_fields,
					]
				];

				add_action( "wc_prd_vendor_options_tab-{$tab_id}", function( $options = '' ) use ( $tab_id ) {
					$in_filter = is_array( $options );

					if ( $in_filter ) {
						// Returns empty array so we can handle outputting the fields for the tab
						return [];
					} else {
						self::output_sections( $tab_id );
					}
				} );
			}

			self::$tabs[ $tab_id ][ $config['id'] ] = [
				'name'   => $config['name'],
				'fields' => self::transform_options( $config['fields'] ),
			];

			return $options;
		} );

		return true;
	}

	/**
	 * Removes a settings section.
	 *
	 * @param string $tab_id
	 * @param string $section_id
	 */
	public static function remove_section( $tab_id, $section_id ) {
	    $tab_id = self::find_tab_slug( $tab_id );

		if ( array_key_exists( $tab_id, self::$tabs ) ) {
			if ( array_key_exists( $section_id, self::$tabs[ $tab_id ] ) ) {
				unset( self::$tabs[ $tab_id ][ $section_id ] );
			}
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
		self::transform_option( $field );

		$_tab_id = self::find_tab_slug( $tab_id );

		if ( isset( self::$tabs[ $_tab_id ], self::$tabs[ $_tab_id ][ $section_id ] ) ) {
			self::$tabs[ $_tab_id ][ $section_id ]['fields'][] = $field;
		} else {
			add_filter( 'wc_prd_vendor_options', function( $options ) use ( $tab_id, $section_id, $field )  {
				list( $start, $end ) = self::find_tab_boundaries( $options, $tab_id, $section_id );
				if ( -1 !== $start && -1 !== $end )
					array_splice( $options, $end, 0, [ $field ] );
				return $options;
			}, 20 );
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
	    $tab_id = self::find_tab_slug( $tab_id );

		if ( isset( self::$tabs[ $tab_id ], self::$tabs[ $tab_id ][ $section_id ] ) ) {
			$fields = self::$tabs[ $tab_id ][ $section_id ]['fields'];
			foreach ( $fields as $key => $field ) {
				if ( isset( $field['id'] ) && $field_id === $field['id'] ) {
					array_splice( $fields, $key, 1 );
					break;
				}
			}
		} else {
			if ( array_key_exists( $field_id, self::$settings_map ) ) {
				$field_id = self::$settings_map[ $field_id ];
			}
			add_filter( 'wc_prd_vendor_options', function( $options ) use ( $field_id )  {
				foreach ( $options as $key => $option ) {
					if ( isset( $option['id'] ) && $field_id === $option['id'] ) {
						array_splice( $options, $key, 1 );
						break;
					}
				}
				return $options;
			} );
		}
	}

	/**
	 * Find the start and end boundaries of a legacy tab.
	 *
	 * @param array $options
	 * @param string $tab New tab ID
	 * @param string $section New section
	 *
	 * @return array
	 */
	protected static function find_tab_boundaries( $options, $tab, $section = '' ) {
		$tab = self::find_tab_name( $tab, $section );

		$start = -1;
		$end   = -1;

		foreach ( $options as $index => $option ) {
			if ( -1 === $start ) {
				if ( isset( $option['id'] ) && $tab === $option['id'] ) {
					$start = $index;
				} elseif ( isset( $option['name'] ) && $tab === $option['name'] ) {
					$start = $index;
				}
			} elseif ( 'heading' === $option['type'] ) {
				$end = $index;
				break;
			}
		}

		if ( -1 === $end ) {
			$end = count( $options ) - 1;
		}
		return [ $start, $end ];
	}

	/**
	 * Find the name of the legacy tab corresponding to the given tab.
	 *
	 * @param string $tab_id
	 * @param string $section
	 *
	 * @return string
	 */
	protected static function find_tab_name( $tab_id, $section = '' ) {
		if ( array_key_exists( $tab_id, self::$tab_map ) ) {
			$old_tab = self::$tab_map[ $tab_id ];

			if ( is_array( $old_tab ) ) {
				if ( array_key_exists( $section, $old_tab ) )
					return $old_tab[ $section ];
				else
					return '';
			} else {
				return $old_tab;
			}
		} else {
			return '';
		}
	}

	/**
	 * Find the slug of the legacy tab corresponding to the given tab.
	 *
	 * @param $tab_id
	 *
	 * @return string
	 */
	protected static function find_tab_slug( $tab_id ) {
		return sanitize_title( self::find_tab_name( $tab_id ) );
	}

	/**
	 * Renders the HTML for the tab sections (legacy).
	 *
	 * @param string $tab
	 */
	public static function output_sections( $tab ) {
		$sections = self::$tabs[ $tab ];
		$current  = self::$section;

		$links = [];

		foreach ( $sections as $id => $section ) {
			$links[] = sprintf(
				'<a href="%s" class="%s">%s</a>',
				add_query_arg( [
					'section'          => $id,
					'settings-updated' => false
				] ),
				$current === $id ? 'current' : '',
				$section['name']
			);
		}

		echo '<ul class="subsubsub">';
		echo '<li>' . implode( ' | </li><li>', $links ) . '</li>';
		echo '</ul>';

		if ( array_key_exists( $current, self::$tabs[ $tab ] ) ) {
			$section_fields = self::$tabs[ $tab ][ $current ]['fields'];
			foreach ( $section_fields as $field )
				\WC_Vendors::$pv_options->settings_options_format( $field );
		}

		echo "<input type='hidden' name='section' value='$current'>";
	}

	/**
	 * Transforms options from the new format into the old format.
	 *
	 * @param array $options
	 *
	 * @return array
	 */
	protected static function transform_options( $options ) {
		foreach ( $options as &$option ) {
			self::transform_option( $option );
		}
		return $options;
	}

	/**
	 * Transforms a single option from the new format into the old format.
	 *
	 * @param array $option
	 */
	protected static function transform_option( &$option ) {
		$replacements = [
			'desc_tip' => 'tip',
			'title'    => 'name'
		];
		foreach ( $replacements as $find => $replace ) {
			if ( isset( $option[ $find ] ) ) {
				$option[ $replace ] = $option[ $find ];
				unset( $option[ $find ] );
			}
		}
	}

	/**
	 * Returns the value used for checked checkboxes.
	 *
	 * @return string
	 */
	public static function get_checkbox_value() {
		return 'on';
	}

	/**
	 * Adds a settings error.
	 *
	 * @param string $error_message
	 */
	public static function add_error( $error_message ) {
		add_settings_error( 'wc_prd_vendor_options', 'error', $error_message );
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
		if ( array_key_exists( $name, self::$settings_map ) ) {
			$name = self::$settings_map[ $name ];
		}
		return \WC_Vendors::$pv_options->get_option( $name, $default );
	}

	/**
	 * Set an option.
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public static function set( $name, $value ) {
		if ( array_key_exists( $name, self::$settings_map ) ) {
			$name = self::$settings_map[ $name ];
		}

		remove_filter( 'sanitize_option_wc_prd_vendor_options', array( \WC_Vendors::$pv_options, 'validate_options' ) );

		if ( isset( \WC_Vendors::$pv_options->current_options ) ) {
			\WC_Vendors::$pv_options->update_option( $name, $value );
		} else {
			$current_options = get_option( 'wc_prd_vendor_options', [] );
			$current_options = array( $name => $value ) + $current_options;

			update_option( 'wc_prd_vendor_options', $current_options );
		}

		add_filter( 'sanitize_option_wc_prd_vendor_options', array( \WC_Vendors::$pv_options, 'validate_options' ) );
	}

	/**
	 * Determine whether a checkbox option is enabled.
	 *
	 * @param string $name
	 *
	 * @return bool
	 */
	public static function is_enabled( $name ) {
		return 1 === self::get( $name );
	}

	/**
	 * Outputs a multiselect dropdown.
	 *
	 * @param array $setting
	 */
	public static function output_multiselect( $setting ) {
		$description = $setting['desc'] && ! $setting['grouped']
			? '<br /><small>' . $setting['desc'] . '</small>'
			: '<label for="' . $setting['id'] . '"> ' . $setting['desc'] . '</label>';

		$selected = ( $setting['value'] !== false ) ? $setting['value'] : $setting['std'];
		$options  = apply_filters( 'wc_prd_vendor_select_options', $setting['options'], $setting ); ?>

		<select id="<?php echo $setting['id']; ?>"
		        class="<?php echo $setting['class']; ?>"
		        style="<?php echo $setting['css']; ?>"
		        name="<?php echo $setting['name']; ?>[]"
		        multiple="multiple">

			<?php foreach ( $options as $key => $val ) : ?>
				<option
					value="<?php echo $key; ?>" <?php self::selected( $selected, $key ); ?>><?php echo $val; ?></option>
			<?php endforeach; ?>
		</select>

		<?php echo $description; ?>

		<script type="text/javascript">
            jQuery(function () {
                jQuery("#<?php echo $setting['id']; ?>").select2({ width: '350px' });
            });
		</script>
		<?php
	}

	/**
	 * Array-friendly implementation of WP's selected method.
	 *
	 * @param mixed $selected
	 * @param mixed $current
	 *
	 * @return string
	 */
	private static function selected( $selected, $current ) {
		if ( is_array( $selected ) ) {
			return selected( in_array( $current, $selected ),true );
		} else {
			return selected( $selected, $current );
		}
	}

	/**
	 * Sanitize a multiselect field before saving it to the database.
	 *
	 * @param mixed $input
	 * @param array $option
	 *
	 * @return string
	 */
	public static function sanitize_multiselect( $input, $option ) {
		$output = $input;

		foreach ( $input as $key => $value ) {
			if ( ! array_key_exists( $value, $option['options'] ) ) {
				unset( $output[ $key ] );
			}
		}

		return $output;
	}
}