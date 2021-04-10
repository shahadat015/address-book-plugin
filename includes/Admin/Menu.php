<?php

 namespace Address\Book\Admin;

/**
 * The menu handler class
 */

class Menu {

    public $addressbook;

    /**
     * Initialize the class
     */
    public function __construct( $addressbook ) {
        $this->addressbook = $addressbook;
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    /**
     * Register admin menu
     * 
     * @return void
     */
    public function admin_menu() {
        $capability = 'manage_options';
        $parent_slug = 'address-book';

        $hook = add_menu_page( __('Address Book', 'address-book'), __('Address Book', 'address-book'), $capability, $parent_slug, [ $this->addressbook, 'plugin_page' ], 'dashicons-book', 25);

        add_submenu_page($parent_slug, __('All Address', 'address-book'), __('All Address', 'address-book'), $capability, $parent_slug, [ $this->addressbook, 'plugin_page' ] );

        add_submenu_page($parent_slug, __('Add New', 'address-book'), __('Add New', 'address-book'), $capability, 'address-book&action=new', [ $this->addressbook, 'plugin_page' ] );

        add_submenu_page($parent_slug, __('Settings', 'address-book'), __('Settings', 'address-book'), $capability, 'address-book&action=settings', [ $this->addressbook, 'plugin_page' ] );

        add_action( 'admin_head-' . $hook, [ $this, 'enqueue_assets' ] );
    }

    /**
     * Enqueue address book script
     * 
     * @return void
     */
    public function enqueue_assets() {
        wp_enqueue_style( 'select2' );
        wp_enqueue_script( 'select2' );
        wp_enqueue_script( 'address-book-script' );
    }

}