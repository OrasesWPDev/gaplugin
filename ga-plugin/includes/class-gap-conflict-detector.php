<?php
/**
 * Conflict Detection and Prevention
 *
 * @package GA_Plugin
 * @since   1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * GAP_Conflict_Detector Class
 *
 * Detects and prevents duplicate tracking scripts by extracting tracking IDs,
 * checking for conflicts across posts, and scanning page HTML for existing scripts.
 *
 * @since 1.0.0
 */
class GAP_Conflict_Detector {

	/**
	 * Singleton instance
	 *
	 * @since 1.0.0
	 * @var GAP_Conflict_Detector
	 */
	private static $instance = null;

	/**
	 * Detected conflicts cache
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $detected_conflicts = array();

	/**
	 * Get singleton instance
	 *
	 * @since 1.0.0
	 * @return GAP_Conflict_Detector
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * Registers hooks for conflict detection.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		add_action( 'admin_init', array( $this, 'check_global_conflicts' ) );
	}

	/**
	 * Extract tracking IDs from script content
	 *
	 * Extracts GA4 measurement IDs (G-XXXXXXXXXX) and GTM container IDs (GTM-XXXXXXX)
	 * from the provided script content using regex patterns.
	 *
	 * @since 1.0.0
	 * @param string $content Script content to analyze
	 * @return array Array of tracking ID objects with id, type, and name properties
	 */
	public function extract_tracking_ids( $content ) {
		$ids = array();

		// Extract GA4 Measurement IDs (G-XXXXXXXXXX - 10 alphanumeric characters)
		if ( preg_match_all( '/[\'"]G-[A-Z0-9]{10}[\'"]/', $content, $matches ) ) {
			foreach ( $matches[0] as $match ) {
				// Remove quotes from the matched string
				$id = trim( $match, '\'"' );
				$ids[] = array(
					'id'   => $id,
					'type' => 'ga4',
					'name' => 'Google Analytics 4',
				);
			}
		}

		// Extract GTM Container IDs (GTM-XXXXXXX - 6+ alphanumeric characters)
		if ( preg_match_all( '/[\'"]GTM-[A-Z0-9]{6,}[\'"]/', $content, $matches ) ) {
			foreach ( $matches[0] as $match ) {
				// Remove quotes from the matched string
				$id = trim( $match, '\'"' );

				// Check if this ID was already added as a GA4 ID (shouldn't happen, but be safe)
				$exists = false;
				foreach ( $ids as $existing_id ) {
					if ( $existing_id['id'] === $id ) {
						$exists = true;
						break;
					}
				}

				if ( ! $exists ) {
					$ids[] = array(
						'id'   => $id,
						'type' => 'gtm',
						'name' => 'Google Tag Manager',
					);
				}
			}
		}

		return $ids;
	}

	/**
	 * Check for global conflicts across all tracking scripts
	 *
	 * Scans all published tracking_script posts and detects when the same
	 * tracking ID is used in multiple posts.
	 *
	 * @since 1.0.0
	 * @return array Array of conflicts with tracking_id, type, name, and posts
	 */
	public function check_global_conflicts() {
		// Query all published tracking_script posts
		$posts = get_posts(
			array(
				'post_type'      => 'tracking_script',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'fields'         => 'ids',
			)
		);

		if ( empty( $posts ) ) {
			$this->detected_conflicts = array();
			return array();
		}

		// Build map: tracking_id => array of post IDs using it
		$tracking_map = array();

		foreach ( $posts as $post_id ) {
			$extracted_ids = get_post_meta( $post_id, '_gap_extracted_ids', true );

			if ( is_array( $extracted_ids ) && ! empty( $extracted_ids ) ) {
				foreach ( $extracted_ids as $id_data ) {
					$tracking_id = $id_data['id'];

					if ( ! isset( $tracking_map[ $tracking_id ] ) ) {
						$tracking_map[ $tracking_id ] = array(
							'type'  => $id_data['type'],
							'name'  => $id_data['name'],
							'posts' => array(),
						);
					}

					$tracking_map[ $tracking_id ]['posts'][] = array(
						'post_id' => $post_id,
						'title'   => get_the_title( $post_id ),
					);
				}
			}
		}

		// Find tracking IDs used by 2+ posts
		$conflicts = array();
		foreach ( $tracking_map as $tracking_id => $data ) {
			if ( count( $data['posts'] ) >= 2 ) {
				$conflicts[] = array(
					'tracking_id' => $tracking_id,
					'type'        => $data['type'],
					'name'        => $data['name'],
					'posts'       => $data['posts'],
				);
			}
		}

		$this->detected_conflicts = $conflicts;
		return $conflicts;
	}

	/**
	 * Scan page HTML for existing tracking scripts
	 *
	 * Searches the provided HTML content for any of the given tracking IDs
	 * using case-insensitive string search.
	 *
	 * @since 1.0.0
	 * @param string $html         The page HTML content to scan
	 * @param array  $tracking_ids Array of tracking ID strings to search for
	 * @return array Array of found tracking IDs
	 */
	public function scan_page_html( $html, $tracking_ids ) {
		$found_ids = array();

		if ( empty( $html ) || empty( $tracking_ids ) ) {
			return $found_ids;
		}

		foreach ( $tracking_ids as $tracking_id ) {
			// Use stripos for case-insensitive search
			if ( stripos( $html, $tracking_id ) !== false ) {
				$found_ids[] = $tracking_id;
			}
		}

		return $found_ids;
	}

	/**
	 * Get currently detected conflicts
	 *
	 * Returns the conflicts that were detected during the last check_global_conflicts() call.
	 *
	 * @since 1.0.0
	 * @return array Array of conflicts
	 */
	public function get_conflicts() {
		return $this->detected_conflicts;
	}

	/**
	 * Log conflict to WordPress debug log
	 *
	 * Logs conflict information to the WordPress debug log if WP_DEBUG is enabled.
	 * All messages are prefixed with "GAP Conflict:" for easy filtering.
	 *
	 * @since 1.0.0
	 * @param string $message The conflict message to log
	 */
	public function log_conflict( $message ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 'GAP Conflict: ' . $message );
		}
	}
}
