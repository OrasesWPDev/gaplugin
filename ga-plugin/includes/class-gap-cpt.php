<?php
/**
 * Custom Post Type Manager
 *
 * Manages the custom post type for tracking scripts.
 * Will register the 'gap_script' CPT with appropriate labels,
 * capabilities, and supports options in EPIC-02.
 *
 * @package GA_Plugin
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class GAP_CPT
 *
 * Handles registration and management of the tracking scripts custom post type.
 */
class GAP_CPT {
    /**
     * Singleton instance
     *
     * @var GAP_CPT|null
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * Returns the singleton instance of this class.
     * Creates a new instance if one doesn't exist.
     *
     * @since 1.0.0
     * @return GAP_CPT
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     *
     * Private constructor to enforce singleton pattern.
     * Initialization code will be implemented in EPIC-02.
     *
     * @since 1.0.0
     */
    private function __construct() {
        // Custom post type registration will be implemented in EPIC-02
        // This includes:
        // - Registering 'gap_script' CPT
        // - Setting up labels and capabilities
        // - Configuring supports (title, editor)
        // - Setting up admin columns
    }
}
