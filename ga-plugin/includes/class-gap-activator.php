<?php
/**
 * Plugin Activator
 *
 * Handles plugin activation and deactivation lifecycle events.
 * This class manages plugin setup on activation and cleanup on deactivation
 * while preserving all plugin data (data is only removed on uninstall).
 *
 * @package GA_Plugin
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class GAP_Activator
 *
 * Handles plugin activation and deactivation hooks.
 */
class GAP_Activator {

    /**
     * Plugin activation
     *
     * Runs when the plugin is activated.
     * - Flushes rewrite rules to ensure CPT permalinks work correctly
     * - Saves plugin version to options for future upgrade routines
     * - Records activation timestamp for analytics
     *
     * @since 1.0.0
     */
    public static function activate() {
        // Flush rewrite rules to ensure CPT permalinks work
        flush_rewrite_rules();

        // Save plugin version to options for future upgrades
        update_option('gap_version', GAP_VERSION);

        // Save activation timestamp for reference
        update_option('gap_activated', current_time('timestamp'));
    }

    /**
     * Plugin deactivation
     *
     * Runs when the plugin is deactivated.
     * - Flushes rewrite rules to clean up CPT permalinks
     * - Preserves ALL plugin data (data should only be removed on uninstall)
     *
     * @since 1.0.0
     */
    public static function deactivate() {
        // Flush rewrite rules to clean up CPT permalinks
        flush_rewrite_rules();

        // DO NOT delete any data - data should only be removed on uninstall
        // This allows users to reactivate the plugin without data loss
    }
}
