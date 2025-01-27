<?php
// Register shortcode
add_shortcode('display_today_data', 'ctd_display_today_data');

function ctd_display_today_data() {
    global $wpdb;
    $table = $wpdb->prefix . 'taha_salah_time';
    $today = current_time('Y-m-d'); // WordPress timezone-aware

    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table WHERE entry_date = %s",
        $today
    ));

    if (empty($results)) {
        return '<p>No data found for today.</p>';
    }

    $html = '<div class="today-data-container">';
    foreach ($results as $row) {
        $html .= '<div>';
        $html .= '<strong>Date:</strong> ' . esc_html(date(get_option('date_format'))) . '<br>';
        $html .= '<strong>Day:</strong> ' . esc_html($row->date_day) . '<br>';
        $html .= '<strong>Fajr:</strong> ' . esc_html($row->fajr_jamaah) . '<br>';
        $html .= '<strong>Dhuhr:</strong> ' . esc_html($row->zuhr_jamaah) . '<br>';
        $html .= '<strong>Asr:</strong> ' . esc_html($row->asr_jamaah) . '<br>';
        $html .= '<strong>Maghrib:</strong> ' . esc_html($row->maghrib_jamaah) . '<br>';
        $html .= '<strong>Isha:</strong> ' . esc_html($row->isha_jamaah);
        $html .= '</div>';
    }
    $html .= '</div>';

    return $html;
}