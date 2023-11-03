<?php
/*
Plugin Name: Save My Court
Description: A reservation system for pickleball and tennis courts.
Version: 1.0
Author: David Sudarma
*/

// Define constants
define('SMC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SMC_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
include_once(SMC_PLUGIN_DIR . 'includes/smc-post-types.php');
include_once(SMC_PLUGIN_DIR . 'includes/smc-acf.php');
include_once(SMC_PLUGIN_DIR . 'includes/smc-field-setup.php');
include_once(SMC_PLUGIN_DIR . 'includes/smc-shortcodes.php');

// Enqueue your CSS file
function smc_enqueue_styles() {
    wp_enqueue_style('smc-style', plugin_dir_url(__FILE__) . 'assets/save-my-court.css');
}
add_action('wp_enqueue_scripts', 'smc_enqueue_styles');

// Hook activation and deactivation functions
// register_activation_hook(__FILE__, 'smc_activate');
// register_deactivation_hook(__FILE__, 'smc_deactivate');