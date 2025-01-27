<?php
// Add admin menu
add_action('admin_menu', 'ctd_add_admin_menu');

function ctd_add_admin_menu() {
    add_menu_page(
        'CSV Today Data', // Page title
        'CSV Data', // Menu title
        'manage_options', // Capability
        'csv-today-data', // Menu slug
        'ctd_admin_page_html', // Callback function
        'dashicons-calendar-alt', // Icon (optional)
        6 // Position (optional, 6 = after Posts)
    );
}

// Admin page HTML and logic
function ctd_admin_page_html() {
    // Security check
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    // Handle CSV upload
    if (isset($_POST['submit_csv'])) {
        if (!isset($_POST['ctd_nonce']) || !wp_verify_nonce($_POST['ctd_nonce'], 'ctd_upload_csv')) {
            wp_die('Security check failed!');
        }

        if (!empty($_FILES['csv_file']['tmp_name'])) {
            $file = $_FILES['csv_file']['tmp_name'];
            $handle = fopen($file, 'r');
            global $wpdb;
            $table = $wpdb->prefix . 'taha_salah_time';

            // Skip header row (adjust if your CSV has no headers)
            fgetcsv($handle);

            // Process rows
            while (($row = fgetcsv($handle)) !== FALSE) {
                // Parse date (adjust format to match your CSV)
                $date = DateTime::createFromFormat('Y-m-d', trim($row[0]));
                if (!$date) {
                    continue; // Skip invalid dates
                }

                $wpdb->insert($table, array(
                    'entry_date' => $date->format('Y-m-d'),
                    'date_day' => sanitize_text_field($row[1]),
                    'fajr_begins' => sanitize_text_field($row[2]),
                    'fajr_jamaah' => sanitize_text_field($row[3]),
                    'zuhr_begins' => sanitize_text_field($row[4]),
                    'zuhr_jamaah' => sanitize_text_field($row[5]),
                    'asr_begins' => sanitize_text_field($row[6]),
                    'asr_jamaah' => sanitize_text_field($row[7]),
                    'maghrib_begins' => sanitize_text_field($row[8]),
                    'maghrib_jamaah' => sanitize_text_field($row[9]),
                    'isha_begins' => sanitize_text_field($row[10]),
                    'isha_jamaah' => sanitize_text_field($row[11]),
                ));
            }

            fclose($handle);
            echo '<div class="notice notice-success"><p>CSV data imported successfully!</p></div>';
        }
    }

    // Display upload form
    ?>
    <div class="wrap">
        <h1>Upload CSV</h1>
        <form method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('ctd_upload_csv', 'ctd_nonce'); ?>
            <p>
                <input type="file" name="csv_file" accept=".csv" required>
            </p>
            <p>
                <input type="submit" name="submit_csv" class="button button-primary" value="Upload CSV">
            </p>
        </form>
    </div>
    <?php
}