<?php
/**
 * Admin Interface Management
 *
 * @package GA_Plugin
 * @since   1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * GAP_Admin Class
 *
 * Manages admin-specific functionality including admin notices,
 * conflict warnings, and admin-only UI features.
 *
 * @since 1.0.0
 */
class GAP_Admin {

	/**
	 * Singleton instance
	 *
	 * @since 1.0.0
	 * @var GAP_Admin
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @since 1.0.0
	 * @return GAP_Admin
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
	 * Registers hooks for admin functionality.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		add_action( 'admin_notices', array( $this, 'display_conflict_notices' ) );
	}

	/**
	 * Display admin notices for tracking ID conflicts
	 *
	 * Shows warning notices when duplicate tracking IDs are detected across
	 * multiple tracking script posts.
	 *
	 * @since 1.0.0
	 */
	public function display_conflict_notices() {
		// Only show on tracking_script admin pages
		$screen = get_current_screen();
		if ( ! $screen || $screen->post_type !== 'tracking_script' ) {
			return;
		}

		// Get conflicts from conflict detector
		$detector = GAP_Conflict_Detector::get_instance();
		$conflicts = $detector->get_conflicts();

		if ( empty( $conflicts ) ) {
			return;
		}

		// Display conflict notice
		?>
		<div class="notice notice-error gap-conflict-notice">
			<h3><?php _e( 'Duplicate Tracking IDs Detected!', 'ga-plugin' ); ?></h3>
			<p><?php _e( 'The following tracking IDs are used in multiple tracking scripts:', 'ga-plugin' ); ?></p>

			<ul>
				<?php foreach ( $conflicts as $conflict ) : ?>
					<li>
						<span class="gap-conflict-id">
							<?php echo esc_html( $conflict['tracking_id'] ); ?>
						</span>
						(<?php echo esc_html( $conflict['name'] ); ?>)
						<?php _e( 'is used in:', 'ga-plugin' ); ?>

						<ul class="gap-conflict-posts">
							<?php foreach ( $conflict['posts'] as $post_data ) : ?>
								<li>
									<?php echo esc_html( $post_data['title'] ); ?>
									<a href="<?php echo esc_url( get_edit_post_link( $post_data['post_id'] ) ); ?>" class="gap-edit-link">
										(<?php _e( 'edit', 'ga-plugin' ); ?>)
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php endforeach; ?>
			</ul>

			<p>
				<strong><?php _e( 'Recommendation:', 'ga-plugin' ); ?></strong>
				<?php _e( 'Each tracking script should use a unique tracking ID to prevent duplicate tracking and data corruption.', 'ga-plugin' ); ?>
			</p>
		</div>
		<?php
	}
}
