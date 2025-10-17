<?php
/**
 * Plugin Activation and Deactivation Handler
 *
 * @package GA_Plugin
 * @since   1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * GAP_Activator Class
 *
 * Handles plugin activation and deactivation tasks.
 *
 * @since 1.0.0
 */
class GAP_Activator {

    /**
     * Plugin activation handler
     *
     * Runs when the plugin is activated. Flushes rewrite rules to ensure
     * the tracking_script custom post type is properly registered.
     *
     * @since 1.0.0
     */
    public static function activate() {
        // Register the custom post type so its rewrite rules are known
        if (class_exists('GAP_CPT')) {
            GAP_CPT::register_post_type();
        }

        // Flush rewrite rules to ensure CPT URLs work
        flush_rewrite_rules();

        // Set default options if needed
        if (get_option('gap_version') === false) {
            add_option('gap_version', GAP_VERSION);
        }
    }

    /**
     * Plugin deactivation handler
     *
     * Runs when the plugin is deactivated. Flushes rewrite rules to clean up
     * any custom post type rewrite rules.
     *
     * @since 1.0.0
     */
    public static function deactivate() {
        // Flush rewrite rules to clean up
        flush_rewrite_rules();
    }
}
