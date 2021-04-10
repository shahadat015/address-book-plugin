<?php

namespace Address\Book;

/**
 * Assets handler class
 */
class Ajax {

    /**
     * Class constructor
     */
    public function __construct() {
        add_action( 'wp_ajax_delete-address', [ $this, 'delete_address' ] );
    }

    /**
     * Define scripts
     * 
     * @return array
     */
    public function delete_address() {
         if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'delete-address' ) ) {
            wp_die( __( "Nonce is invalid?", 'address-book' ) );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( "You have no permission to delete", 'address-book') );
        }

        $id = isset( $_REQUEST['id'] ) ? (int) $_REQUEST['id'] : 0;

        if ( ab_delete_address( $id ) ) {
           wp_send_json_success( [
                'message' => 'Addrees has been deleted successfully',
           ] );
        }else{
            wp_send_json_error( [
                'message' => 'Ops! Something went wrong',
           ] );
        }
    }

}