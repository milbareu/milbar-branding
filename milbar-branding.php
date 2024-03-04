<?php
/*
Plugin Name: MilBar Branding
Plugin URI: https://milbar.eu
Description: Branding for WordPress sites made by Milan Bartalovics.
Version: 2.0.0
Author: Milan Bartalovics
Author URI:
License: MIT
*/

// Define constants
define('MILBAR_BRANDING_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MILBAR_BRANDING_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MILBAR_BRANDING_PLUGIN_VERSION', '2.0.0');

/**
 * Remove WordPress admin bar menu
 */
add_action('admin_bar_menu', function ($wp_admin_bar) {
    $wp_admin_bar->remove_node('wp-logo');
}, 999);

/**
 * Admin style
 */
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('milbar-branding/admin', MILBAR_BRANDING_PLUGIN_URL . 'assets/css/admin.css', false, MILBAR_BRANDING_PLUGIN_VERSION);
});

/**
 * Replace login screen style
 */
add_action('login_enqueue_scripts', function () {
    wp_enqueue_style('milbar-branding/login', MILBAR_BRANDING_PLUGIN_URL . 'assets/css/login.css', false, MILBAR_BRANDING_PLUGIN_VERSION);
});

/**
 * Replace login screen logo link
 */
add_filter('login_headerurl', function ($url) {
    return 'https://milbar.eu';
}, 10, 1);

/**
 * Replace login screen logo text
 */
add_filter('login_headertext', function () {
    return 'Made with <3 by Milan Bartalovics';
});

add_filter('admin_footer_text', function ($content) {
    return 'Made with &#10084; by <a href="https://milbar.eu">Milan Bartalovics</a>';
}, 999, 1);
