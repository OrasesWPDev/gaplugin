<?php
/**
 * Plugin Name:       GA Plugin
 * Plugin URI:        https://github.com/OrasesWPDev/gaplugin
 * Description:       Manage Google Analytics 4 (GA4) and Google Tag Manager (GTM) scripts with granular placement and scope control
 * Version:           1.0.0
 * Requires at least: 6.0
 * Tested up to:      6.8
 * Requires PHP:      7.4
 * Author:            Orases
 * Author URI:        https://orases.com
 * Text Domain:       ga-plugin
 * Domain Path:       /languages
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://github.com/OrasesWPDev/gaplugin
 * Network:           false
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Plugin version constant
 *
 * Used for cache busting and version tracking.
 *
 * @since 1.0.0
 */
define('GAP_VERSION', '1.0.0');

/**
 * Plugin main file constant
 *
 * References the main plugin file.
 *
 * @since 1.0.0
 */
define('GAP_PLUGIN_FILE', __FILE__);

/**
 * Plugin directory path constant
 *
 * Absolute path to the plugin directory with trailing slash.
 *
 * @since 1.0.0
 */
define('GAP_PLUGIN_DIR', plugin_dir_path(__FILE__));

/**
 * Plugin directory URL constant
 *
 * URL to the plugin directory with trailing slash.
 *
 * @since 1.0.0
 */
define('GAP_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Plugin basename constant
 *
 * Used for activation hooks and plugin identification.
 *
 * @since 1.0.0
 */
define('GAP_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * PSR-4 compliant autoloader
 *
 * Automatically loads GAP_ prefixed classes from the includes directory.
 * Converts class names to WordPress file naming convention:
 * - GAP_CPT → class-gap-cpt.php
 * - GAP_Meta_Boxes → class-gap-meta-boxes.php
 *
 * @since 1.0.0
 * @param string $class The fully-qualified class name.
 */
spl_autoload_register(function ($class) {
    // Only load GAP_ prefixed classes
    if (strpos($class, 'GAP_') !== 0) {
        return;
    }

    // Convert class name to file name
    // GAP_Meta_Boxes → class-gap-meta-boxes.php
    $class_file = 'class-' . str_replace('_', '-', strtolower($class)) . '.php';
    $file_path = GAP_PLUGIN_DIR . 'includes/' . $class_file;

    // Load file if it exists
    if (file_exists($file_path)) {
        require_once $file_path;
    }
});

/**
 * Plugin activation hook
 *
 * Runs when the plugin is activated.
 * Handled by GAP_Activator::activate()
 *
 * @since 1.0.0
 */
register_activation_hook(__FILE__, array('GAP_Activator', 'activate'));

/**
 * Plugin deactivation hook
 *
 * Runs when the plugin is deactivated.
 * Handled by GAP_Activator::deactivate()
 *
 * @since 1.0.0
 */
register_deactivation_hook(__FILE__, array('GAP_Activator', 'deactivate'));

/**
 * Initialize the plugin
 *
 * Loads text domain for internationalization and initializes all core components.
 * Hooked to plugins_loaded to ensure WordPress core is fully loaded.
 *
 * @since 1.0.0
 */
function gap_init() {
    // Load text domain for internationalization
    load_plugin_textdomain('ga-plugin', false, dirname(GAP_PLUGIN_BASENAME) . '/languages');

    // Initialize core components using singleton pattern
    GAP_CPT::get_instance();
    GAP_Meta_Boxes::get_instance();
    GAP_Conflict_Detector::get_instance();
    GAP_Frontend::get_instance();
    GAP_Admin::get_instance();
}

// Hook initialization to plugins_loaded
add_action('plugins_loaded', 'gap_init');
