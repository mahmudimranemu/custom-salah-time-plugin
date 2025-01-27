<?php
/*
Plugin Name: Taha Salah Time
Description: Upload CSV data and display today's entries.
Version: 1.0
Author: Mahmud Imran
Author URI: https://github.com/mahmudimranemu
*/

// Include admin and frontend files
if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'includes/admin-page.php';
}
require_once plugin_dir_path(__FILE__) . 'includes/shortcode.php';



// Create database table on activation
register_activation_hook(__FILE__, 'ctd_create_table');

function ctd_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'taha_salah_time';
    $charset = $wpdb->get_charset_collate();

    // Drop the existing table if it exists
    $wpdb->query("DROP TABLE IF EXISTS $table_name");

    // Create the new table
    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        entry_date DATE NOT NULL,
        fajr_begins VARCHAR(255),
        fajr_jamaah VARCHAR(255),
        zuhr_begins VARCHAR(255),
        zuhr_jamaah VARCHAR(255),
        asr_begins VARCHAR(255),
        asr_jamaah VARCHAR(255),
        maghrib_begins VARCHAR(255),
        maghrib_jamaah VARCHAR(255),
        isha_begins VARCHAR(255),
        isha_jamaah VARCHAR(255),
        PRIMARY KEY (id)
    ) $charset;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Add styles
add_action('wp_enqueue_scripts', 'ctd_enqueue_styles');

function ctd_enqueue_styles() {
    wp_enqueue_style('ctd-styles', plugin_dir_url(__FILE__) .'css/styles.css');
}