<?php

/**
 * Table Rate Shipping update routines.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Sets the sort order for "Rest of the World" tables such that they are
 * always matched last.
 */
function wcv_trs_update_120_rest_of_the_world_tables() {
    global $wpdb;

    $table_query = <<<SQL
SELECT table_id
FROM {$wpdb->prefix}wcv_trs_tables
WHERE `table_name` = %s
SQL;

    $table_ids = $wpdb->get_col(
        $wpdb->prepare(
            $table_query,
            __( 'Rest of the World', 'wcv-table-rate-shipping' )
        )
    );

    foreach ( $table_ids as $table_id ) {
        $table = new WCV_TRS_Table( $table_id );
        $table->set_table_order( 1000 );
        $table->save();
    }
}

/**
 * Adds the wildcard location to tables with no countries selected.
 */
function wcv_trs_update_120_wildcard_tables() {
    global $wpdb;

    $query = <<<SQL
SELECT T.table_id
FROM {$wpdb->prefix}wcv_trs_tables T
WHERE NOT EXISTS (
	SELECT *
	FROM {$wpdb->prefix}wcv_trs_table_locations L
	WHERE L.table_id = T.table_id
)
SQL;

    $table_ids = $wpdb->get_col( $query );

    foreach ( $table_ids as $table_id ) {
        $table = new WCV_TRS_Table( $table_id );
        $table->add_location( '*', 'country' );
        $table->save();
    }
}

/**
 * Sets the wcv_trs_tables_saved flag for all vendors with shipping tables.
 */
function wcv_trs_update_120_set_tables_saved_flag() {
    global $wpdb;

    $query = <<<SQL
SELECT DISTINCT user_id
FROM {$wpdb->prefix}wcv_trs_tables
SQL;

    $vendor_ids = $wpdb->get_col( $query );

    foreach ( $vendor_ids as $vendor_id ) {
        update_user_meta( $vendor_id, 'wcv_trs_tables_saved', true );
    }
}

/**
 * Adds 'Items' meta data to all existing TRS shipping items.
 */
function wcv_trs_update_131_items_meta() {
    global $wpdb;

    $items_string = __( 'Items', 'woocommerce' );

    $query = <<<SQL
INSERT INTO `{$wpdb->prefix}woocommerce_order_itemmeta` (
  `order_item_id`, `meta_key`, `meta_value`
) 
SELECT 
  shipping.order_item_id, 
  '{$items_string}', 
  GROUP_CONCAT(
    CONCAT_WS(
      ' &times; ', product.order_item_name, 
      m3.meta_value
    ) SEPARATOR ', '
  ) AS items 
FROM 
  `{$wpdb->prefix}woocommerce_order_items` shipping, 
  `{$wpdb->prefix}woocommerce_order_items` product, 
  `{$wpdb->prefix}woocommerce_order_itemmeta` m1, 
  `{$wpdb->prefix}woocommerce_order_itemmeta` m2, 
  `{$wpdb->prefix}woocommerce_order_itemmeta` m3, 
  `{$wpdb->prefix}posts` post 
WHERE 
  shipping.order_item_id NOT IN (
    SELECT 
      order_item_id 
    FROM 
      `{$wpdb->prefix}woocommerce_order_itemmeta` 
    WHERE 
      meta_key = '{$items_string}'
  ) 
  AND shipping.order_id = product.order_id 
  AND product.order_item_id = m1.order_item_id 
  AND m1.meta_key = '_product_id' 
  AND m1.meta_value = post.ID 
  AND shipping.order_item_id = m2.order_item_id 
  AND m2.meta_key = 'vendor_id' 
  AND m2.meta_value = post.post_author 
  AND product.order_item_id = m3.order_item_id 
  AND m3.meta_key = '_qty' 
GROUP BY 
  post.post_author, 
  shipping.order_item_id;
SQL;

    $wpdb->query( $query );
}
