<?php
/*
Plugin Name: MilBar Branding
Plugin URI: https://milbar.eu
Description: Branding for MilBar WordPress sites.
Version: 1.0.5
Author: Milan Bartalovics
Author URI:
License: MIT
*/

namespace MilBar\Branding;

/**
 * Set up autoloader
 */
require __DIR__ . '/vendor/autoload.php';

// Define constants
define('MILBAR_BRANDING_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MILBAR_BRANDING_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MILBAR_BRANDING_PLUGIN_VERSION', '1.0.5');

// Branding
$milbar_branding = new Init();
