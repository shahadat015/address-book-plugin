<?php

/**
 * Insert or update address
 * 
 * @param  array  $args
 * 
 * @return int|WP_Error
 */
function ab_insert_address( $args = [] ) {
    global $wpdb;

    $defaults = [
        'name'       => '',
        'phone'      => '',
        'address'    => '',
        'created_by' => get_current_user_id(),
        'created_at' => current_time( 'mysql' ),
    ];

    $data = wp_parse_args( $args, $defaults );

    if ( isset($data['id']) ) {
        $id = $data['id'];
        unset( $data['id'] );
        $updated = $wpdb->update(
            "{$wpdb->prefix}ab_addresses",
            $data,
            [ 'id' => $id ],
            [
                '%s',
                '%s',
                '%s',
                '%d',
                '%s',
            ],
            [ '%d' ]
        );

        ab_address_purge_cache( $id );

        return $updated;
    }else{
        $inserted = $wpdb->insert(
            "{$wpdb->prefix}ab_addresses",
            $data,
            [
                '%s',
                '%s',
                '%s',
                '%d',
                '%s',
            ],
        );

        if ( ! $inserted ) {
            return new \WP_Error( 'filed-to-insert', __( 'Failed to insert data' , 'address-book' ) );
        }

        ab_address_purge_cache();

        return $wpdb->insert_id;
    }
}

/**
 * Get address
 * 
 * @return Object
 */
function ab_get_addresses( $args = [] ) {
    global $wpdb;

    $defaults = [
        'per_page'  => 20,
        'offset'  => 0,
        'orderby' => 'id',
        'order'   => 'DESC'
    ];

    $key = md5( serialize( array_diff_assoc( $args, $defaults ) ) );
    $last_changed = wp_cache_get_last_changed( 'address' );
    $cache_key = "items:$key:$last_changed";
    $items = wp_cache_get( $cache_key, "address" );

    if ( false === $items ) {
        $args = wp_parse_args( $args, $defaults );

        $sql = $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}ab_addresses
            ORDER BY {$args['orderby']} {$args['order']}
            LIMIT %d, %d",
            $args['offset'], $args['per_page']
        );

        $items = $wpdb->get_results( $sql );
        wp_cache_set($cache_key, $items,  'address' );
    }

    return $items;
}

/**
 * Fetch the count of total addresses
 * @return int
 */
function ab_address_count() {
    global $wpdb;

    $count = wp_cache_get( 'count', 'address' );

    if ( false === $count ) {
        $count = (int) $wpdb->get_var( "SELECT count(id) FROM {$wpdb->prefix}ab_addresses");
        wp_cache_set( 'count', $count,  'address' );

    }

    return $count;
}

/**
 * Fetch single contact from database
 * 
 * @param  int $id
 * 
 * @return object
 */
function ab_get_address( $id ) {
    global $wpdb;

    $address = wp_cache_get( 'book-' . $id, 'address' );

    if ( false === $address ) {
        $address = $wpdb->get_row(
            $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ab_addresses WHERE id = %d", $id)
        );
        wp_cache_set( 'book-' . $id, $address,  'address' );

    }

    return $address;
}

/**
 * Delete an address
 * 
 * @param  int $id
 * 
 * @return int|boolean
 */
function ab_delete_address( $id ) {
    global $wpdb;

    ab_address_purge_cache( $id );

    return $wpdb->delete(
        $wpdb->prefix . 'ab_addresses',
        [ 'id' => $id ],
        [ '%d' ]
    );
}

/**
 * Purge the cache for books
 *
 * @param  int $book_id
 *
 * @return void
 */
function ab_address_purge_cache( $book_id = null ) {
    $group = 'address';

    if ( $book_id ) {
        wp_cache_delete( 'book-' . $book_id, $group );
    }

    wp_cache_delete( 'count', $group );
    wp_cache_set( 'last_changed', microtime(), $group );
}