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
        'supports'           => array('title', 'editor', 'custom-fields', 'thumbnail'),
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



