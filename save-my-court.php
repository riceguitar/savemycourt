<?php
/*
Plugin Name: Save My Court
Description: A reservation system for pickleball and tennis courts.
Version: 1.0
Author: David Sudarma
*/

// Register Custom Post Types
function smc_register_custom_post_types() {
    $custom_menu_slug = 'smc_custom_menu';

    $post_types = array(
        'court' => 'Court',
        'location' => 'Location',
        'reservation' => 'Reservation'
    );

    foreach ($post_types as $type => $label) {
        $args = array(
            'label'                 => $label,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => true,
            'rewrite'               => array('slug' => $type),
            'capability_type'       => 'post',
            'has_archive'           => true,
            'hierarchical'          => false,
            'supports'              => array('title', 'editor', 'thumbnail'),
            'menu_position'         => null,
            'show_in_menu'          => $custom_menu_slug,
        );
        register_post_type($type, $args);
    }
}
add_action('init', 'smc_register_custom_post_types');


// Add a top-level menu for custom post types
function smc_custom_menu() {
    add_menu_page(
        'Save My Court',
        'Save My Court',
        'manage_options',
        'smc_custom_menu',
        '',
        'dashicons-admin-multisite',
        6
    );
}
add_action('admin_menu', 'smc_custom_menu');

// Meta data fields for reservation
$reservation_meta_fields = array(
    'user_id' => array('type' => 'dropdown_user', 'description' => 'User ID'),
    'court_id' => array('type' => 'dropdown_court', 'description' => 'Court'),
    'reservation_time' => array('type' => 'time_group', 'description' => 'Reservation Time'),
    'amount_paid' => array('type' => 'number', 'description' => 'Amount Paid'),
    'payment_status' => array('type' => 'dropdown', 'description' => 'Payment Status', 'options' => array('Pending', 'Paid', 'Cancelled')),
    'special_requests' => array('type' => 'textarea', 'description' => 'Special Requests'),
    'number_of_players' => array('type' => 'number', 'description' => 'Number of Players'),
    'player_user_ids' => array('type' => 'dropdown_user_list', 'description' => 'Players')
);

// Register meta boxes for the Reservation post type
function smc_register_reservation_meta_boxes() {
    global $reservation_meta_fields;
    foreach ($reservation_meta_fields as $key => $field) {
        add_meta_box(
            'smc_meta_' . $key,
            $field['description'],
            'smc_reservation_meta_box_callback',
            'reservation',
            'normal',
            'high',
            array('field' => $field, 'key' => $key)
        );
    }
}
add_action('add_meta_boxes', 'smc_register_reservation_meta_boxes');

// Meta box callback function
function smc_reservation_meta_box_callback($post, $meta) {
    $field = $meta['args']['field'];
    $key = $meta['args']['key'];

    echo '<label for="' . esc_attr($key) . '">' . esc_html($field['description']) . '</label><br>';

    switch ($field['type']) {
        case 'text':
        case 'number':
        case 'date':
        case 'time':
            $value = get_post_meta($post->ID, $key, true);
            echo '<input type="' . $field['type'] . '" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '" size="25" />';
            break;
        case 'textarea':
            $value = get_post_meta($post->ID, $key, true);
            echo '<textarea id="' . esc_attr($key) . '" name="' . esc_attr($key) . '">' . esc_textarea($value) . '</textarea>';
            break;
        case 'checkbox':
            $value = get_post_meta($post->ID, $key, true);
            echo '<input type="checkbox" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" ' . checked($value, 'on', false) . ' />';
            break;
        case 'dropdown':
            $value = get_post_meta($post->ID, $key, true);
            echo '<select id="' . esc_attr($key) . '" name="' . esc_attr($key) . '">';
            foreach ($field['options'] as $option) {
                $selected = ($value == $option) ? 'selected="selected"' : '';
                echo '<option value="' . esc_attr($option) . '" ' . $selected . '>' . esc_html($option) . '</option>';
            }
            echo '</select>';
            break;
        case 'dropdown_user':
            $value = get_post_meta($post->ID, $key, true);
            $users = get_users(array('orderby' => 'display_name'));
            echo '<select id="' . esc_attr($key) . '" name="' . esc_attr($key) . '">';
            echo '<option value="">Select User</option>';
            foreach ($users as $user) {
                $selected = ($user->ID == $value) ? 'selected="selected"' : '';
                echo '<option value="' . esc_attr($user->ID) . '" ' . $selected . '>' . esc_html($user->display_name) . '</option>';
            }
            echo '</select>';
            break;
        case 'dropdown_court':
            $value = get_post_meta($post->ID, $key, true);
            $courts = get_posts(array('post_type' => 'court', 'numberposts' => -1));
            echo '<select id="' . esc_attr($key) . '" name="' . esc_attr($key) . '">';
            echo '<option value="">Select Court</option>';
            foreach ($courts as $court) {
                $selected = ($court->ID == $value) ? 'selected="selected"' : '';
                echo '<option value="' . esc_attr($court->ID) . '" ' . $selected . '>' . esc_html($court->post_title) . '</option>';
            }
            echo '</select>';
            break;
        case 'dropdown_user_list':
            $number_of_players = (int) get_post_meta($post->ID, 'number_of_players', true);
            $users = get_users(array('orderby' => 'display_name'));
            for ($i = 1; $i <= $number_of_players; $i++) {
                $player_key = 'player_user_id_' . $i;
                $player_value = get_post_meta($post->ID, $player_key, true);
                echo '<label for="' . esc_attr($player_key) . '">Player ' . $i . '</label><br>';
                echo '<select id="' . esc_attr($player_key) . '" name="' . esc_attr($player_key) . '">';
                echo '<option value="">Select Player</option>';
                foreach ($users as $user) {
                    $selected = ($user->ID == $player_value) ? 'selected="selected"' : '';
                    echo '<option value="' . esc_attr($user->ID) . '" ' . $selected . '>' . esc_html($user->display_name) . '</option>';
                }
                echo '</select><br>';
            }
            break;
        case 'time_group':
            $date = get_post_meta($post->ID, 'reservation_date', true);
            $start_time = get_post_meta($post->ID, 'start_time', true);
            $end_time = get_post_meta($post->ID, 'end_time', true);

            echo '<label for="reservation_date">Date</label><br>';
            echo '<input type="date" id="reservation_date" name="reservation_date" value="' . esc_attr($date) . '" /><br>';

            echo '<label for="start_time">Start Time</label><br>';
            echo '<input type="time" id="start_time" name="start_time" value="' . esc_attr($start_time) . '" /><br>';

            echo '<label for="end_time">End Time</label><br>';
            echo '<input type="time" id="end_time" name="end_time" value="' . esc_attr($end_time) . '" /><br>';
            break;
    }
}

// Save meta box data
function smc_save_reservation_meta($post_id) {
    global $reservation_meta_fields;
    foreach ($reservation_meta_fields as $key => $field) {
        if (isset($_POST[$key])) {
            if ($field['type'] == 'checkbox') {
                update_post_meta($post_id, $key, 'on');
            } else {
                update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
            }
        } else if ($field['type'] == 'checkbox') {
            update_post_meta($post_id, $key, '');
        }

        // Save player user IDs
        if ($key === 'number_of_players') {
            $number_of_players = (int) $_POST[$key];
            update_post_meta($post_id, $key, $number_of_players);
            for ($i = 1; $i <= $number_of_players; $i++) {
                $player_key = 'player_user_id_' . $i;
                if (isset($_POST[$player_key])) {
                    update_post_meta($post_id, $player_key, sanitize_text_field($_POST[$player_key]));
                }
            }
        }
    }

    // Save reservation date, start time, and end time
    if (isset($_POST['reservation_date'])) {
        update_post_meta($post_id, 'reservation_date', sanitize_text_field($_POST['reservation_date']));
    }
    if (isset($_POST['start_time'])) {
        update_post_meta($post_id, 'start_time', sanitize_text_field($_POST['start_time']));
    }
    if (isset($_POST['end_time'])) {
        update_post_meta($post_id, 'end_time', sanitize_text_field($_POST['end_time']));
    }
}
add_action('save_post', 'smc_save_reservation_meta');


// Function to include custom template for the reservation post type
function smc_include_template($template) {
    if (is_singular('reservation')) {
        // Look for template in plugin directory
        $plugin_template = plugin_dir_path(__FILE__) . 'templates/single-reservation.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter('template_include', 'smc_include_template');

?>
