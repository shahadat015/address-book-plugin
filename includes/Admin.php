<?php

namespace Address\Book;

/**
 * The admin class
 */
class Admin {

    /**
     * Class constructor
     */
    public function __construct() {
        $addressbook = new Admin\Addressbook();
        $this->dispatch_actions( $addressbook );
        new Admin\Menu( $addressbook );
    }

    public function dispatch_actions( $addressbook ) {
        $settings = new Admin\Settings();
        add_action( 'admin_init', [ $addressbook, 'form_handler' ] );
        add_action( 'admin_init', [ $settings, 'register' ] );
        //add_action( 'admin_post_delete-address', [ $addressbook, 'delete_address' ] );
    }
}