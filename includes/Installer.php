<?php

namespace Address\Book;

/**
 * Installer class
 */
class Installer {

    /**
     * Run the installer
     */
    public function run() {
        $this->add_version();
        $this->create_table();
    }

    /**
     * Check plugin is installed if not insert
     * install time, and add plugin version
     *
     * @return void
     */
    public function add_version() {
        $installed = get_option( 'ab_installed' );

        if ( ! $installed ) {
            update_option( 'ab_installed', time() );
        }

        update_option( 'ab_version', ADDRESS_BOOK_VERSION );
    }

    /**
     * Create necessary database tables
     *
     * @return void
     */
    public function create_table() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $schema = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ab_addresses` (
          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
          `name` varchar(100) NOT NULL,
          `address` varchar(255) DEFAULT NULL,
          `phone` varchar(30) DEFAULT NULL,
          `created_by` bigint(20) UNSIGNED NOT NULL,
          `created_at` datetime NOT NULL,
          PRIMARY KEY (`id`)
        ) $charset_collate";

        if ( ! function_exists('dbDelta') ) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        dbDelta( $schema );
    }

}