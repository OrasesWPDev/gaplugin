<?php
/**
 * Meta Boxes Manager
 *
 * Handles all meta boxes for the tracking scripts CPT.
 * Will manage script content, placement settings, scope configuration,
 * and conflict detection UI in EPIC-02.
 *
 * @package GA_Plugin
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class GAP_Meta_Boxes
 *
 * Manages meta boxes for script configuration and settings.
 */
class GAP_Meta_Boxes {
    /**
     * Singleton instance
     *
     * @var GAP_Meta_Boxes|null
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * Returns the singleton instance of this class.
     * Creates a new instance if one doesn't exist.
     *
     * @since 1.0.0
     * @return GAP_Meta_Boxes
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
        // Meta box registration will be implemented in EPIC-02
        // This includes:
        // - Script content meta box
        // - Placement settings (head, body, footer)
        // - Scope configuration (all pages, specific pages, exclusions)
        // - Tracking ID display
        // - Conflict warnings
    }
}
