<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WCV_TRS_Tables_List
 *
 * Outputs a list of shipping tables, either for the current user or for the
 * vendor whose tables are being edited.
 */
class WCV_TRS_Tables_List {

	/**
	 * The ID of the vendor whose tables are to be displayed.
	 *
	 * @var int
	 */
	protected $vendor_id = 0;

	/**
     * The shipping tables to display.
     *
     * @var array
     */
	protected $tables = [];

	/**
     * The context where the table is being outputted.
     *
     * @var string
     */
	protected $context = '';

	/**
	 * Constructor.
	 *
	 * @param int $vendor_id
	 */
	public function __construct( $vendor_id ) {
		$this->vendor_id = $vendor_id;
		$this->load_tables();

		add_action( 'wcv_trs_shipping_tables_before_footer', array( $this, 'save_button' ) );
		add_action( 'wcv_trs_admin_shipping_tables_before', array( $this, 'admin_before' ) );
		add_action( 'wcv_trs_admin_shipping_tables_after', array( $this, 'admin_after' ) );
		add_action( 'wcv_trs_shipping_tables_before', array( $this, 'default_tables_notice' ) );
	}

	/**
     * Loads the vendor's shipping tables.
     */
	private function load_tables() {
	    $this->tables = WCV_TRS_Tables::get_tables( $this->vendor_id );
	}

	/**
	 * Outputs the table list.
	 *
	 * @param string $context 'admin,' 'dashboard,' or 'settings'
	 * @param bool $echo Should the output be echoed?
	 *
	 * @return mixed
	 */
	public function output( $context, $echo = true ) {
	    $this->context = $context;

		if ( 'dashboard' === $context ) {
			wcv_trs()->assets->enqueue( 'style', 'wcv-table-rate-shipping.trs-front' );
		} else {
			wcv_trs()->assets->enqueue( 'style', 'wcv-table-rate-shipping.trs-admin', [
				'deps' => [
					'dashicons',
					'woocommerce.admin',
				],
				'ver'  => wcv_trs()->version,
			] );
		}

		wp_localize_script( 'wcv-table-rate-shipping.trs-tables', 'wcv_trs_localize', [
			'user_id'           => $this->vendor_id,
			'tables'            => $this->tables,
			'default_table'     => [
				'table_id'              => 0,
				'table_name'            => '',
				'table_order'           => null,
				'table_enabled'         => 'yes',
				'table_locations'       => '',
				'table_postcodes'       => '',
				'formatted_table_rates' => '',
			],
			'wcv_trs_nonce'     => wp_create_nonce( 'wcv_trs_nonce' ),
			'strings'           => [
				'unload_confirmation_msg' => __( 'Your changed data will be lost if you leave this page without saving.', 'wcv-table-rate-shipping' ),
				'save_failed'             => __( 'Your changes were not saved. Please retry.', 'wcv-table-rate-shipping' ),
				'yes'                     => __( 'Yes', 'wcv-table-rate-shipping' ),
				'no'                      => __( 'No', 'wcv-table-rate-shipping' ),
				'weight_unit'             => get_option( 'woocommerce_weight_unit' ),
				'currency_symbol'         => get_woocommerce_currency_symbol(),
				'item_count'              => __( 'Number of Items', 'wcv-table-rate-shipping' ),
				'subtotal'                => __( 'Subtotal', 'wcv-table-rate-shipping' ),
				'weight'                  => __( 'Weight', 'wcv-table-rate-shipping' ),
			],
			'ajaxurl'           => admin_url( 'admin-ajax.php' ),
		] );

		wcv_trs()->assets->enqueue( 'script', 'wcv-table-rate-shipping.trs-tables' );

		$allowed_countries   = WC()->countries->get_shipping_countries();
		$shipping_continents = WC()->countries->get_shipping_continents();

		if ( ! $echo ) {
		    ob_start();
		}

		require apply_filters( 'trs_table_list_view_path', 'views/html-shipping-table-list.php' );

		if ( ! $echo ) {
		    return ob_get_clean();
		}
	}

	/**
	 * Outputs the markup to display before the shipping table list in the admin
	 * context.
	 */
	public function admin_before() {
		?>
		<div class="wrap">
			<h1>
				<?php _e( 'Shipping Tables', 'wcv-table-rate-shipping' ); ?>

				<?php if ( current_user_can( 'manage_options' ) ): ?>
					<a href="<?php echo admin_url( 'users.php' ); ?>" class="page-title-action">
						<?php _e( 'Go Back', 'wcv-table-rate-shipping' ); ?>
					</a>
				<?php endif; ?>
			</h1>
		<?php
	}

	/**
	 * Outputs the markup to display after the shipping table list in the admin
	 * context.
	 */
	public function admin_after() {
		echo '</div>';
	}

	/**
	 * Outputs the markup for the Save button.
	 */
	public function save_button() {
		?>
		<input
			type="submit"
			name="save"
			class="button button-primary wc-shipping-zone-save"
			value="<?php esc_attr_e( 'Save changes', 'wcv-table-rate-shipping' ); ?>"
			disabled>
		<?php
	}

	/**
     * Outputs a notice when the store default tables are being displayed.
     *
	 * @param string $context 'admin' or 'dashboard'
     */
	public function default_tables_notice( $context ) {
		// Bail if the admin is editing the default tables
		if ( 0 === $this->vendor_id ) {
			return;
		}

	    $tables_saved   = get_user_meta( $this->vendor_id, 'wcv_trs_tables_saved', true );
	    $using_defaults = ! $tables_saved && 0 < sizeof( $this->tables );

	    if ( ! $using_defaults ) {
	        return;
        }

        if ( 'dashboard' === $context ) {
            $class = 'woocommerce-info';
        } else {
            $class = 'notice notice-info';
        }

        ?>
        <div id="wcv_trs_default_tables_notice" class="<?php echo $class; ?>">
            <p>
                <?php
                    printf(
                        __( "These shipping tables were generated for you automatically. Please choose to %1\$skeep them%2\$s or %3\$sdelete them%4\$s and start from scratch.", 'wcv-table-rate-shipping' ),
                        '<a href="#" class="wcv-trs-keep-defaults"><strong>',
                        '</strong></a>',
                        "<a href='#' class='wcv-trs-delete-defaults'><strong>",
                        '</strong></a>'
                    );
                ?>
            </p>
        </div>
        <?php
	}

}
