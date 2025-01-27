<?php
// Register shortcode
add_shortcode('today_salah_time', 'ctd_display_today_salah_time');

function ctd_display_today_salah_time() {
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

    $html = '<div class="st-container">';
    foreach ($results as $row) {

        // $html .= '<div class="st-todays-date">' . esc_html(date(get_option('date_format'))) . '</div>';
        $html .= '<div class="st-salah-times-grid">';
        $html .= '<div class="st-grid-item">  </div>';
        $html .= '<div class="st-grid-item st-bold"> Fajr </div>';
        $html .= '<div class="st-grid-item st-bold"> Zuhr </div>';
        $html .= '<div class="st-grid-item st-bold"> Asr </div>';
        $html .= '<div class="st-grid-item st-bold"> Maghrib </div>';
        $html .= '<div class="st-grid-item st-bold"> Isha </div>';
        $html .= '<div class="st-grid-item"> Begins </div>';
        $html .= '<div class="st-grid-item">' . esc_html($row->fajr_begins) . ' </div>';
        $html .= '<div class="st-grid-item">' . esc_html($row->zuhr_begins) . ' </div>';
        $html .= '<div class="st-grid-item">' . esc_html($row->asr_begins) . ' </div>';
        $html .= '<div class="st-grid-item">' . esc_html($row->maghrib_begins) . ' </div>';
        $html .= '<div class="st-grid-item">' . esc_html($row->isha_begins) . ' </div>';
        $html .= "<div class='st-grid-item'> Jama'ah </div>";
        $html .= '<div class="st-grid-item">' . esc_html($row->fajr_jamaah) . ' </div>';
        $html .= '<div class="st-grid-item">' . esc_html($row->zuhr_jamaah) . ' </div>';
        $html .= '<div class="st-grid-item">' . esc_html($row->asr_jamaah) . ' </div>';
        $html .= '<div class="st-grid-item">' . esc_html($row->maghrib_jamaah) . ' </div>';
        $html .= '<div class="st-grid-item">' . esc_html($row->isha_jamaah) . ' </div>';

        $html .= '</div>';
    }
    $html .= '</div>';

    return $html;
}

add_shortcode('all_salah_time', 'ctd_display_all_salah_time');

function ctd_display_all_salah_time() {
    global $wpdb;
    $table = $wpdb->prefix . 'taha_salah_time';

    // Get the selected month from the request
    $selected_month = isset($_GET['salah_month']) ? $_GET['salah_month'] : '';

    // Get all unique months from the database
    $months = $wpdb->get_col("SELECT DISTINCT DATE_FORMAT(entry_date, '%Y-%m') as month FROM $table ORDER BY month");

    // Prepare the query to filter by the selected month
    $query = "SELECT * FROM $table";
    if ($selected_month) {
        $query .= $wpdb->prepare(" WHERE DATE_FORMAT(entry_date, '%Y-%m') = %s", $selected_month);
    }

    $results = $wpdb->get_results($query);

    if (empty($results)) {
        return '<p>No data found.</p>';
    }

    // Generate the dropdown form
    $html = '<form method="GET">';
    $html .= '<select name="salah_month" onchange="this.form.submit()">';
    $html .= '<option value="">Select Month</option>';
    foreach ($months as $month) {
        $selected = ($month == $selected_month) ? 'selected' : '';
        $html .= '<option value="' . esc_attr($month) . '" ' . $selected . '>' . esc_html(date('F Y', strtotime($month))) . '</option>';
    }
    $html .= '</select>';
    $html .= '</form>';

    // Display the filtered data
    $html .= '<div class="st-container">';
    $html .= '<div class="st-monthly-salah-times">';
    $html .= '<p>Showing salah times for ' . esc_html(date('F Y', strtotime($selected_month))) . '</p>';
    $html .= '<table><tr>';
    $html .= '<th> Date </th>';
    $html .= '<th colspan="2"> Fajr </th>';
    $html .= '<th colspan="2"> Zuhr </th>';
    $html .= '<th colspan="2"> Asr </th>';
    $html .= '<th colspan="2"> Maghrib </th>';
    $html .= '<th colspan="2"> Isha </th>';
    $html .= '</tr>';
    foreach ($results as $row) {
        $html .= '<tr>';
        $html .= '<td>' . esc_html($row->entry_date) . '</td>';
        $html .= '<td>' . esc_html($row->fajr_begins) . ' </td>';
        $html .= '<td>' . esc_html($row->fajr_jamaah) . ' </td>';
        $html .= '<td>' . esc_html($row->zuhr_begins) . ' </td>';
        $html .= '<td>' . esc_html($row->zuhr_jamaah) . ' </td>';
        $html .= '<td>' . esc_html($row->asr_begins) . ' </td>';
        $html .= '<td>' . esc_html($row->asr_jamaah) . ' </td>';
        $html .= '<td>' . esc_html($row->maghrib_begins) . ' </td>';
        $html .= '<td>' . esc_html($row->maghrib_jamaah) . ' </td>';
        $html .= '<td>' . esc_html($row->isha_begins) . ' </td>';
        $html .= '<td>' . esc_html($row->isha_jamaah) . ' </td>';
        $html .= '</tr>';

    }
    $html .= '</table></div></div>';

    return $html;
}