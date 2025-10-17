<?php
/**
 * PHPUnit Bootstrap File
 *
 * @package   GA_Plugin
 * @author    Orases
 * @copyright 2025 Orases
 * @license   GPL-2.0-or-later
 */

// Composer autoloader
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Bootstrap Brain Monkey for WordPress function mocking
\Brain\Monkey\setUp();

// Define WordPress constants for testing
if (!defined('ABSPATH')) {
    define('ABSPATH', '/tmp/wordpress/');
}

if (!defined('GAP_VERSION')) {
    define('GAP_VERSION', '1.0.0');
}

if (!defined('GAP_PLUGIN_DIR')) {
    define('GAP_PLUGIN_DIR', dirname(__DIR__) . '/');
}

if (!defined('GAP_PLUGIN_URL')) {
    define('GAP_PLUGIN_URL', 'http://localhost/wp-content/plugins/ga-plugin/');
}
