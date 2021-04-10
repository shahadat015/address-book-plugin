<?php

namespace Address\Book\Admin;
use Address\Book\Traits\Error;

/**
 * Addressbook handler class
 */
class Addressbook {

    use Error;

    public function plugin_page() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $id = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;

        switch ( $action ) {
            case 'new':
                $template = __DIR__ . '/views/address-new.php';
                break;

            case 'edit':
                $contact = ab_get_address( $id );
                $template = __DIR__ . '/views/address-edit.php';
                break;
            case 'settings':
                $contact = ab_get_address( $id );
                $template = __DIR__ . '/views/settings.php';
                break;            
            default:
                $template = __DIR__ . '/views/address-list.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }

    /**
     * Handle the form
     *
     * @return void
     */
    public function form_handler() {
        $data = [];

        if ( ! isset( $_POST['submit_address'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'new-address' ) ) {
            wp_die( __( "Are you cheating?", 'address-book' ) );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( "You have no permission", 'address-book' ) );
        }

        $data['name']    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
        $data['phone']   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
        $data['address'] = isset( $_POST['address'] ) ? sanitize_textarea_field( wp_unslash( $_POST['address'] ) ) : '';

        if ( isset( $_POST['id'] ) ) {
            $data['id'] = (int) $_POST['id'];
        }
        
        if ( empty( $data['name'] ) ) {
            $this->errors['name'] = __( "Please enter your name", 'address-book' );
        }

        if ( empty( $data['phone'] ) ) {
            $this->errors['phone'] = __( "Please enter your phone number", 'address-book' );
        }

        if ( ! empty( $this->errors ) ) {
            return;
        }

        $insert_id = ab_insert_address( $data );

        if ( is_wp_error( $insert_id ) ) {
            wp_die( $insert_id->get_error_message() );
        }

        if ( $data['id'] ) {
            $location = admin_url( 'admin.php?page=address-book&action=edi&address-updated=true&id='. $data['id'] );
        }else{
            $location = admin_url( 'admin.php?page=address-book&inserted=true' );
        }

        wp_safe_redirect( $location );

        exit;
    }

    /**
     * Delete address
     * 
     * @return void
     */
    public function delete_address() {
        
        if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'delete-address' ) ) {
            wp_die( __( "Are you cheating?", 'address-book' ) );
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( "You have no permission to delete", 'address-book') );
        }

        $id = isset( $_REQUEST['id'] ) ? (int) $_REQUEST['id'] : 0;

        if ( ab_delete_address( $id ) ) {
           $location = admin_url( 'admin.php?page=address-book&address-deleted=true' );
        }else{
            $location = admin_url( 'admin.php?page=address-book&address-deleted=false' );
        }

        return wp_safe_redirect( $location );
    }
}