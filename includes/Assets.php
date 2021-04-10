<?php

namespace Address\Book;

/**
 * Assets handler class
 */
class Assets {

    /**
     * Class constructor
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );
    }

    /**
     * Define scripts
     * 
     * @return array
     */
    public function get_scripts() {
        return [
            'address-book-script' => [
                'src'     => ADDRESS_BOOK_ASSETS . '/js/address-book.js',
                'version' => filemtime( ADDRESS_BOOK_PATH . '/assets/js/address-book.js' ),
                'deps'    => [ 'jquery', 'wp-util' ]
            ],
            'select2' => [
                'src'     => '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'version' => '4.1.0-rc.0',
            ]
        ];
    }

    /**
     * Define Styles
     * 
     * @return array
     */
    public function get_styles() {
        return [
            'select2' => [
                'src'     => '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                'version' => '4.1.0-rc.0',
            ]
        ];
    }

    /**
     * Register admin scripts
     * 
     * @return void
     */
    public function register_assets() {
        $styles = $this->get_styles();
        $scripts = $this->get_scripts();

        foreach ($styles as $handle => $style) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;
            wp_register_style( $handle, $style['src'], $deps, $style['version'] );
        }

        foreach ($scripts as $handle => $script) {
            $deps = isset( $script['deps'] ) ? $script['deps'] : false;
            wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
        }

        wp_localize_script( 'address-book-script', 'addressBook', [
            'nonce'   => wp_create_nonce('delete-address'),
            'confirm' => __( 'Are you sure?', 'address-book' ),
            'error'   => __( 'Something went wrong', 'address-book' ),
        ] );
    }
}