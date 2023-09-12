<?php


// Add Booking Custom Post Type
function smc_register_booking_post_type() {
    $labels = array(
        'name'               => 'Bookings',
        'singular_name'      => 'Booking',
        'menu_name'          => 'Bookings',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Booking',
        'edit_item'          => 'Edit Booking',
        'new_item'           => 'New Booking',
        'view_item'          => 'View Booking',
        'search_items'       => 'Search Bookings',
        'not_found'          => 'No bookings found',
        'not_found_in_trash' => 'No bookings found in trash',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-calendar',
        'supports'           => array('title', 'editor', 'custom-fields'),
    );

    register_post_type('booking', $args);
}
add_action('init', 'smc_register_booking_post_type');


// Add Complex Custom Post Type
function smc_register_complex_post_type() {
    $labels = array(
        'name'               => 'Complexes',
        'singular_name'      => 'Complex',
        'menu_name'          => 'Complexes',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Complex',
        'edit_item'          => 'Edit Complex',
        'new_item'           => 'New Complex',
        'view_item'          => 'View Complex',
        'search_items'       => 'Search Complexes',
        'not_found'          => 'No complexes found',
        'not_found_in_trash' => 'No complexes found in trash',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-location',
        'supports'           => array('title', 'editor', 'custom-fields'),
    );

    register_post_type('complex', $args);
}
add_action('init', 'smc_register_complex_post_type');


// Add Courts Custom Post Type
function smc_register_courts_post_type() {
    $labels = array(
        'name'               => 'Courts',
        'singular_name'      => 'Court',
        'menu_name'          => 'Courts',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Court',
        'edit_item'          => 'Edit Court',
        'new_item'           => 'New Court',
        'view_item'          => 'View Court',
        'search_items'       => 'Search Courts',
        'not_found'          => 'No courts found',
        'not_found_in_trash' => 'No courts found in trash',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-admin-site',
        'supports'           => array('title', 'editor', 'custom-fields'),
    );

    register_post_type('court', $args);
}
add_action('init', 'smc_register_courts_post_type');


// Add Metaboxes for Selecting court and Complex
function smc_add_booking_meta_boxes() {
    add_meta_box(
        'smc-court-meta-box',
        'Select Court',
        'smc_render_court_dropdown',
        'booking',
        'normal',
        'high'
    );

    add_meta_box(
        'smc-complex-meta-box',
        'Select Complex',
        'smc_render_complex_dropdown',
        'booking',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'smc_add_booking_meta_boxes');

// save-my-court.php

function smc_render_court_dropdown($post) {
    $selected_court = get_post_meta($post->ID, 'smc_selected_court', true);
    $courts = get_posts(array('post_type' => 'court', 'posts_per_page' => -1));

    echo '<label for="smc-selected-court">Select Court:</label>';
    echo '<select name="smc-selected-court" id="smc-selected-court">';
    echo '<option value="">Select a Court</option>';

    foreach ($courts as $court) {
        $court_id = $court->ID;
        $court_title = esc_html($court->post_title);
        $selected = ($selected_court == $court_id) ? 'selected' : '';
        echo "<option value='$court_id' $selected>$court_title</option>";
    }

    echo '</select>';
}

function smc_render_complex_dropdown($post) {
    $selected_complex = get_post_meta($post->ID, 'smc_selected_complex', true);
    $complexes = get_posts(array('post_type' => 'complex', 'posts_per_page' => -1));

    echo '<label for="smc-selected-complex">Select Complex:</label>';
    echo '<select name="smc-selected-complex" id="smc-selected-complex">';
    echo '<option value="">Select a Complex</option>';

    foreach ($complexes as $complex) {
        $complex_id = $complex->ID;
        $complex_title = esc_html($complex->post_title);
        $selected = ($selected_complex == $complex_id) ? 'selected' : '';
        echo "<option value='$complex_id' $selected>$complex_title</option>";
    }

    echo '</select>';
}

// save-my-court.php

function smc_save_booking_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if ($post_id && get_post_type($post_id) == 'booking') {
        if (isset($_POST['smc-selected-court'])) {
            update_post_meta($post_id, 'smc_selected_court', sanitize_text_field($_POST['smc-selected-court']));
        }

        if (isset($_POST['smc-selected-complex'])) {
            update_post_meta($post_id, 'smc_selected_complex', sanitize_text_field($_POST['smc-selected-complex']));
        }
    }
}
add_action('save_post', 'smc_save_booking_meta');

