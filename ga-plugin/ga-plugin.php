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

// Define plugin constants
define('GAP_VERSION', '1.0.0');
define('GAP_PLUGIN_FILE', __FILE__);
define('GAP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('GAP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('GAP_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Autoload GAP classes
 *
 * Automatically loads class files from the includes directory when a GAP_ prefixed
 * class is referenced. Converts class names to file names following WordPress naming
 * conventions (e.g., GAP_CPT becomes class-gap-cpt.php).
 *
 * @param string $class The fully-qualified class name.
 * @since 1.0.0
 */
spl_autoload_register(function ($class) {
    // Only load GAP_ prefixed classes
    if (strpos($class, 'GAP_') !== 0) {
        return;
    }

    // Convert class name to file name: GAP_CPT => class-gap-cpt.php
    $class_file = 'class-' . str_replace('_', '-', strtolower($class)) . '.php';
    $file_path = GAP_PLUGIN_DIR . 'includes/' . $class_file;

    // Load file if it exists
    if (file_exists($file_path)) {
        require_once $file_path;
    }
});

// Register activation/deactivation hooks
register_activation_hook(__FILE__, array('GAP_Activator', 'activate'));
register_deactivation_hook(__FILE__, array('GAP_Activator', 'deactivate'));

// Initialize plugin on plugins_loaded
add_action('plugins_loaded', 'gap_init');

/**
 * Initialize the GA Plugin
 *
 * Loads text domain for internationalization and initializes all core components.
 * This function is hooked to 'plugins_loaded' to ensure all WordPress functions
 * are available before initialization.
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
    GAP_Admin::get_instance();
    GAP_Frontend::get_instance();
}
