<?php

/**
 * Plugin Name: Address Book
 * Description: Create address list for your website
 * Plugin URI: https://wordpress.org/address-book
 * Author: Shahadat
 * Author URI: https://shahadat.com
 * Version: 1.0.0
 * License: GPL2
 * Text Domain: address-book
 * Domain Path: /languages/
 */

// Don't call the file directly
if ( ! defined('ABSPATH') ) {
    exit;
}

/**
 * Address_Book class
 *
 * @class Address_Book the class that hold the entire Address_Book plugin
 */
final class Address_Book {

    /**
     * Plugin version
     * 
     * @var string
     */
    public $version = '1.0.0';

    /**
     * Instance of self
     * 
     * @var Address_Book
     */
    public static $instance = null;

    /**
     * Constructor for the Address_Book class
     *
     * Setup all the specific hooks and actions 
     * within our plugin
     */
    private function __construct() {
        require_once __DIR__ . '/vendor/autoload.php';
        $this->define_constants();
        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
        add_action( 'init', [ $this, 'localization_setup' ] );
    }

    /**
     * Initialize the Address_Book class
     * 
     * Check for existing Address_Book instance
     * and if it doesn't find one, create it
     */
    public static function init() {
        if ( self::$instance == null ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Define required plugin constants
     * 
     * @return void
     */
    public function define_constants() {
        define( 'ADDRESS_BOOK_VERSION', $this->version );
        define( 'ADDRESS_BOOK_FILE', __FILE__ );
        define( 'ADDRESS_BOOK_PATH', __DIR__ );
        define( 'ADDRESS_BOOK_URL', plugins_url( '', ADDRESS_BOOK_FILE ) );
        define( 'ADDRESS_BOOK_ASSETS', ADDRESS_BOOK_URL . '/assets' );
    }

    /**
     * Do stuff upon plugin activation
     * 
     * @return void
     */
    public function activate() {
        $installer = new Address\Book\Installer();
        $installer->run();
    }

    /**
     * Initialize the plugin
     * @return 
     */
    public function init_plugin() {
        new Address\Book\Assets();

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            new Address\Book\Ajax();
        }

        if ( is_admin() ) {
            return new Address\Book\Admin();
        }
    }

    public function localization_setup() {
        load_plugin_textdomain( 'address-book', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

}

/**
 * Load Address_Book plugin when all plugin loaded
 * 
 * @return Address_Book
 */
function address_book() {
    return Address_Book::init();
}

// let's go..
address_book();