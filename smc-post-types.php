<?php
// Register Custom Post Types
function smc_register_custom_post_types() {
    // Define a custom top-level menu slug
    $custom_menu_slug = 'smc_custom_menu';

    // Register Court Post Type
    $args = array(
        'label'                 => 'Court',
        'public'                => true,
        'supports'              => array( 'title', 'editor', 'thumbnail' ),
        'has_archive'           => true,
        'capability_type'       => 'post',
        'show_in_menu'          => $custom_menu_slug,
    );
    register_post_type( 'court', $args );

    // Register Location Post Type
    $args = array(
        'label'                 => 'Location',
        'public'                => true,
        'supports'              => array( 'title', 'editor', 'thumbnail' ),
        'has_archive'           => true,
        'capability_type'       => 'post',
        'show_in_menu'          => $custom_menu_slug,
    );
    register_post_type( 'location', $args );

    // Register Reservation Post Type
    $args = array(
        'label'                 => 'Reservation',
        'public'                => true,
        'supports'              => array( 'title', 'editor', 'thumbnail' ),
        'has_archive'           => true,
        'capability_type'       => 'post',
        'show_in_menu'          => $custom_menu_slug,
    );
    register_post_type( 'reservation', $args );
}

// Add a top-level menu for custom post types
function smc_custom_menu() {
    add_menu_page(
        'Save My Court',        // Page title
        'Save My Court',        // Menu title
        'manage_options',       // Capability
        'smc_custom_menu',      // Menu slug
        '',                     // Callback function
        'dashicons-admin-multisite', // Icon
        6                       // Position
    );
}

add_action( 'init', 'smc_register_custom_post_types' );
add_action( 'admin_menu', 'smc_custom_menu' );





$court_meta_data = array(
    array(
        'name' => 'location',
        'type' => 'text',
        'description' => 'Enter the location of the court.',
    ),
    array(
        'name' => 'picture',
        'type' => 'file',
        'description' => 'Upload a picture of the court.',
    ),
);

function smc_register_court_meta() {
    global $court_meta_data;
    foreach ($court_meta_data as $meta) {
        add_meta_box(
            'smc_meta_' . $meta['name'], // Unique ID
            $meta['name'], // Box title
            'smc_court_meta_callback', // Content callback
            'court', // Post type
            'normal', // Context
            'high', // Priority
            $meta // Callback args
        );
    }
}
add_action('add_meta_boxes', 'smc_register_court_meta');

function smc_court_meta_callback($post, $args) {
    $meta = $args['args'];
    $value = get_post_meta($post->ID, $meta['name'], true);

    echo '<label for="' . esc_attr($meta['name']) . '">' . esc_html($meta['description']) . '</label><br>';
    switch ($meta['type']) {
        case 'text':
            echo '<input type="text" id="' . esc_attr($meta['name']) . '" name="' . esc_attr($meta['name']) . '" value="' . esc_attr($value) . '" size="25" />';
            break;
        case 'file':
            echo '<input type="file" id="' . esc_attr($meta['name']) . '" name="' . esc_attr($meta['name']) . '" />';
            if (!empty($value)) {
                echo '<br><img src="' . esc_url($value) . '" alt="' . esc_attr($meta['name']) . '" style="max-width:100%;height:auto;" />';
            }
            break;
    }
}

function smc_save_court_meta($post_id) {
    global $court_meta_data;
    foreach ($court_meta_data as $meta) {
        if ($meta['type'] === 'text' && isset($_POST[$meta['name']])) {
            update_post_meta($post_id, $meta['name'], sanitize_text_field($_POST[$meta['name']]));
        }
        // Handle file upload if necessary
        if ($meta['type'] === 'file' && !empty($_FILES[$meta['name']]['name'])) {
            $uploaded_file = $_FILES[$meta['name']];
            $upload = wp_handle_upload($uploaded_file, array('test_form' => false));
            if (isset($upload['url'])) {
                update_post_meta($post_id, $meta['name'], esc_url($upload['url']));
            }
        }
    }
}
add_action('save_post', 'smc_save_court_meta');


?>