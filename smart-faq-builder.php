<?php
/**
 * Plugin Name: Smart FAQ Builder
 * Plugin URI: 
 * Description: A simple and beautiful FAQ builder for WordPress
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: 
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: smart-faq-builder
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SFB_VERSION', '1.0.0');
define('SFB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SFB_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once SFB_PLUGIN_DIR . 'includes/class-sfb-post-type.php';
require_once SFB_PLUGIN_DIR . 'includes/class-sfb-shortcode.php';

// Initialize the plugin
function sfb_init() {
    // Initialize post type
    new SFB_Post_Type();
    
    // Initialize shortcode
    new SFB_Shortcode();
}
add_action('plugins_loaded', 'sfb_init');

// Activation hook
register_activation_hook(__FILE__, 'sfb_activate');
function sfb_activate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'sfb_deactivate');
function sfb_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
} 