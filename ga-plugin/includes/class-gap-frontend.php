<?php
/**
 * Frontend Script Output
 *
 * @package GA_Plugin
 * @since   1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * GAP_Frontend Class
 *
 * Manages frontend output of tracking scripts. Handles querying active scripts,
 * filtering by scope, detecting duplicates, and injecting scripts at appropriate
 * locations in the DOM.
 *
 * @since 1.0.0
 */
class GAP_Frontend {

	/**
	 * Singleton instance
	 *
	 * @since 1.0.0
	 * @var GAP_Frontend
	 */
	private static $instance = null;

	/**
	 * Request-level script cache
	 *
	 * Caches scripts by placement to prevent duplicate database queries
	 * during a single page request.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $scripts_cache = array();

	/**
	 * Get singleton instance
	 *
	 * @since 1.0.0
	 * @return GAP_Frontend
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
	 * Registers WordPress action hooks for script output at different placements.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		// Hook into wp_head for head placement (priority 1 - early)
		add_action( 'wp_head', array( $this, 'output_head_scripts' ), 1 );

		// Hook into wp_body_open for body_top placement (priority 1 - early)
		add_action( 'wp_body_open', array( $this, 'output_body_top_scripts' ), 1 );

		// Hook into wp_footer for body_bottom placement (priority 1 - early)
		add_action( 'wp_footer', array( $this, 'output_body_bottom_scripts' ), 1 );

		// Hook into wp_footer for footer placement (priority 999 - late)
		add_action( 'wp_footer', array( $this, 'output_footer_scripts' ), 999 );
	}

	/**
	 * Get active tracking scripts for a specific placement
	 *
	 * Queries the database for published tracking_script posts that match the
	 * specified placement and are marked as active. Results are cached at the
	 * request level to prevent duplicate queries. Filters scripts by scope to
	 * ensure they should appear on the current page.
	 *
	 * @since 1.0.0
	 * @param string $placement The placement location (head, body_top, body_bottom, footer)
	 * @return array Array of WP_Post objects for active scripts
	 */
	private function get_active_scripts( $placement ) {
		// Check cache first
		if ( isset( $this->scripts_cache[ $placement ] ) ) {
			return $this->scripts_cache[ $placement ];
		}

		// Query active scripts for this placement
		$args = array(
			'post_type'      => 'tracking_script',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => '_gap_placement',
					'value'   => $placement,
					'compare' => '=',
				),
				array(
					'key'     => '_gap_is_active',
					'value'   => '1',
					'compare' => '=',
				),
			),
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
		);

		$scripts = get_posts( $args );

		// Filter by scope (global vs. specific pages)
		$filtered_scripts = array();
		$current_page_id = get_the_ID();

		foreach ( $scripts as $script ) {
			$scope = get_post_meta( $script->ID, '_gap_scope', true );

			if ( $scope === 'global' ) {
				// Global scripts always included
				$filtered_scripts[] = $script;
			} elseif ( $scope === 'specific_pages' && $current_page_id ) {
				// Check if current page is in target pages
				$target_pages = get_post_meta( $script->ID, '_gap_target_pages', true );
				if ( is_array( $target_pages ) && in_array( $current_page_id, $target_pages, true ) ) {
					$filtered_scripts[] = $script;
				}
			}
		}

		// Cache the filtered results
		$this->scripts_cache[ $placement ] = $filtered_scripts;

		return $filtered_scripts;
	}

	/**
	 * Get current page HTML from all output buffers
	 *
	 * Captures the combined HTML content from all active output buffers to enable
	 * duplicate detection of tracking scripts added by themes or other plugins.
	 *
	 * @since 1.0.0
	 * @return string Combined HTML content from all output buffers
	 */
	private function get_current_page_html() {
		$html = '';
		$level = ob_get_level();

		// Capture contents of all active buffers
		for ( $i = 0; $i < $level; $i++ ) {
			$contents = ob_get_contents();
			if ( $contents !== false ) {
				$html .= $contents;
			}
		}

		return $html;
	}

	/**
	 * Output tracking scripts for a specific placement
	 *
	 * Main output method that handles script retrieval, duplicate detection,
	 * and HTML output. Uses output buffering to capture incremental page HTML
	 * for duplicate scanning. Skips scripts with duplicate tracking IDs and
	 * adds HTML comments for debugging.
	 *
	 * @since 1.0.0
	 * @param string $placement The placement location (head, body_top, body_bottom, footer)
	 */
	private function output_scripts( $placement ) {
		// Get active scripts for this placement
		$scripts = $this->get_active_scripts( $placement );

		if ( empty( $scripts ) ) {
			return;
		}

		// Output opening comment
		echo "\n<!-- GA Plugin: {$placement} -->\n";

		// Start output buffering to capture incremental HTML
		ob_start();

		foreach ( $scripts as $script ) {
			// Get script content
			$content = get_post_meta( $script->ID, '_gap_script_content', true );

			if ( empty( $content ) ) {
				continue;
			}

			// Get extracted tracking IDs for duplicate detection
			$extracted_ids = get_post_meta( $script->ID, '_gap_extracted_ids', true );

			// Check for duplicates if tracking IDs exist
			if ( ! empty( $extracted_ids ) && is_array( $extracted_ids ) ) {
				// Get current output buffer content
				$current_buffer = ob_get_contents();
				if ( $current_buffer === false ) {
					$current_buffer = '';
				}

				// Get full page HTML so far
				$full_page_html = $this->get_current_page_html();

				// Combine both for comprehensive duplicate detection
				$combined_html = $current_buffer . $full_page_html;

				// Extract just the ID strings for scanning
				$tracking_id_strings = array();
				foreach ( $extracted_ids as $id_data ) {
					if ( isset( $id_data['id'] ) ) {
						$tracking_id_strings[] = $id_data['id'];
					}
				}

				// Scan for duplicates using Conflict_Detector
				$detector = GAP_Conflict_Detector::get_instance();
				$found_ids = $detector->scan_page_html( $combined_html, $tracking_id_strings );

				// If duplicates found, skip this script
				if ( ! empty( $found_ids ) ) {
					$script_title = get_the_title( $script->ID );
					$found_ids_list = implode( ', ', $found_ids );
					$current_page_id = get_the_ID();

					// Output HTML comment explaining why script was skipped
					echo "<!-- GA Plugin: Skipping '{$script_title}' - Duplicate tracking IDs detected: {$found_ids_list} -->\n";

					// Log conflict for debugging
					$log_message = "Duplicate tracking IDs detected on page (ID: {$current_page_id}). Script '{$script_title}' (Post ID: {$script->ID}) skipped. Found IDs: {$found_ids_list}";
					$detector->log_conflict( $log_message );

					continue;
				}
			}

			// No duplicates found, output the script
			echo $content . "\n";
		}

		// End buffering and flush output
		ob_end_flush();

		// Output closing comment
		echo "<!-- /GA Plugin: {$placement} -->\n\n";
	}

	/**
	 * Output scripts in head placement
	 *
	 * Wrapper method for outputting scripts in the <head> section.
	 * Hooked to wp_head with priority 1.
	 *
	 * @since 1.0.0
	 */
	public function output_head_scripts() {
		$this->output_scripts( 'head' );
	}

	/**
	 * Output scripts in body_top placement
	 *
	 * Wrapper method for outputting scripts after the opening <body> tag.
	 * Hooked to wp_body_open with priority 1.
	 *
	 * @since 1.0.0
	 */
	public function output_body_top_scripts() {
		$this->output_scripts( 'body_top' );
	}

	/**
	 * Output scripts in body_bottom placement
	 *
	 * Wrapper method for outputting scripts before the closing </body> tag.
	 * Hooked to wp_footer with priority 1.
	 *
	 * @since 1.0.0
	 */
	public function output_body_bottom_scripts() {
		$this->output_scripts( 'body_bottom' );
	}

	/**
	 * Output scripts in footer placement
	 *
	 * Wrapper method for outputting scripts in the footer area.
	 * Hooked to wp_footer with priority 999 (very late).
	 *
	 * @since 1.0.0
	 */
	public function output_footer_scripts() {
		$this->output_scripts( 'footer' );
	}
}
