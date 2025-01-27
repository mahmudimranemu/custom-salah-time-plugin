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

    $sql = "CREATE TABLE $table_name (
        id INT NOT NULL AUTO_INCREMENT,
        entry_date DATE NOT NULL,
        date_day VARCHAR(255),
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