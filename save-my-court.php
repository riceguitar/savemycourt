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

// Hook activation and deactivation functions
// register_activation_hook(__FILE__, 'smc_activate');
// register_deactivation_hook(__FILE__, 'smc_deactivate');