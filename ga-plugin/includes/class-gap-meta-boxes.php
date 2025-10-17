<?php
/**
 * Meta Box Management
 *
 * @package GA_Plugin
 * @since   1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * GAP_Meta_Boxes Class
 *
 * Manages meta boxes for tracking script configuration including field rendering,
 * saving, sanitization, and validation.
 *
 * @since 1.0.0
 */
class GAP_Meta_Boxes {

	/**
	 * Singleton instance
	 *
	 * @since 1.0.0
	 * @var GAP_Meta_Boxes
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @since 1.0.0
	 * @return GAP_Meta_Boxes
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
	 * Registers hooks for meta boxes, saving, and admin assets.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_tracking_script', array( $this, 'save_meta_boxes' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * Register meta boxes
	 *
	 * @since 1.0.0
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'gap_script_config',
			__( 'Tracking Script Configuration', 'ga-plugin' ),
			array( $this, 'render_script_config_meta_box' ),
			'tracking_script',
			'normal',
			'high'
		);
	}

	/**
	 * Render tracking script configuration meta box
	 *
	 * @since 1.0.0
	 * @param WP_Post $post Current post object
	 */
	public function render_script_config_meta_box( $post ) {
		// Add nonce for security
		wp_nonce_field( 'gap_save_meta_boxes', 'gap_meta_box_nonce' );

		// Get current meta values
		$script_content = get_post_meta( $post->ID, '_gap_script_content', true );
		$placement      = get_post_meta( $post->ID, '_gap_placement', true );
		$scope          = get_post_meta( $post->ID, '_gap_scope', true );
		$target_pages   = get_post_meta( $post->ID, '_gap_target_pages', true );
		$is_active      = get_post_meta( $post->ID, '_gap_is_active', true );

		// Set defaults
		$placement    = ! empty( $placement ) ? $placement : 'head';
		$scope        = ! empty( $scope ) ? $scope : 'global';
		$target_pages = is_array( $target_pages ) ? $target_pages : array();
		?>

		<div class="gap-meta-box">
			<!-- Script Content -->
			<div class="gap-form-group">
				<label for="gap_script_content">
					<?php _e( 'Script Content', 'ga-plugin' ); ?>
					<span style="color: red;">*</span>
				</label>
				<p class="description">
					<?php _e( 'Paste your Google Analytics 4 (GA4) or Google Tag Manager (GTM) tracking code here.', 'ga-plugin' ); ?>
				</p>
				<textarea
					id="gap_script_content"
					name="gap_script_content"
					rows="10"
					style="width: 100%; font-family: 'Courier New', monospace;"
					required
				><?php echo esc_textarea( $script_content ); ?></textarea>
			</div>

			<!-- Placement -->
			<div class="gap-form-group">
				<label for="gap_placement">
					<?php _e( 'Script Placement', 'ga-plugin' ); ?>
					<span style="color: red;">*</span>
				</label>
				<p class="description">
					<?php _e( 'Choose where to inject the tracking script on the page.', 'ga-plugin' ); ?>
				</p>
				<select id="gap_placement" name="gap_placement" style="width: 100%;" required>
					<option value="head" <?php selected( $placement, 'head' ); ?>>
						<?php _e( 'Head (before closing </head> tag)', 'ga-plugin' ); ?>
					</option>
					<option value="body_top" <?php selected( $placement, 'body_top' ); ?>>
						<?php _e( 'Body Top (after opening <body> tag)', 'ga-plugin' ); ?>
					</option>
					<option value="body_bottom" <?php selected( $placement, 'body_bottom' ); ?>>
						<?php _e( 'Body Bottom (before closing </body> tag)', 'ga-plugin' ); ?>
					</option>
					<option value="footer" <?php selected( $placement, 'footer' ); ?>>
						<?php _e( 'Footer (wp_footer hook)', 'ga-plugin' ); ?>
					</option>
				</select>
			</div>

			<!-- Scope -->
			<div class="gap-form-group">
				<label for="gap_scope">
					<?php _e( 'Script Scope', 'ga-plugin' ); ?>
					<span style="color: red;">*</span>
				</label>
				<p class="description">
					<?php _e( 'Choose whether to apply this script globally or to specific pages only.', 'ga-plugin' ); ?>
				</p>
				<select id="gap_scope" name="gap_scope" style="width: 100%;" required>
					<option value="global" <?php selected( $scope, 'global' ); ?>>
						<?php _e( 'Global (all pages)', 'ga-plugin' ); ?>
					</option>
					<option value="specific_pages" <?php selected( $scope, 'specific_pages' ); ?>>
						<?php _e( 'Specific Pages', 'ga-plugin' ); ?>
					</option>
				</select>
			</div>

			<!-- Target Pages (shown only when scope is specific_pages) -->
			<div class="gap-pages-selector" style="display: none; margin-top: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background-color: #f9f9f9; max-height: 300px; overflow-y: auto;">
				<label>
					<strong><?php _e( 'Select Pages', 'ga-plugin' ); ?></strong>
				</label>
				<p class="description">
					<?php _e( 'Choose which pages should display this tracking script.', 'ga-plugin' ); ?>
				</p>
				<?php
				$pages = get_posts(
					array(
						'post_type'      => 'page',
						'posts_per_page' => -1,
						'post_status'    => 'publish',
						'orderby'        => 'title',
						'order'          => 'ASC',
					)
				);

				if ( ! empty( $pages ) ) {
					foreach ( $pages as $page ) {
						$checked = in_array( $page->ID, $target_pages ) ? 'checked' : '';
						?>
						<label style="display: block; margin: 8px 0;">
							<input
								type="checkbox"
								name="gap_target_pages[]"
								value="<?php echo esc_attr( $page->ID ); ?>"
								<?php echo $checked; ?>
							>
							<?php echo esc_html( $page->post_title ); ?>
						</label>
						<?php
					}
				} else {
					echo '<p>' . __( 'No published pages found.', 'ga-plugin' ) . '</p>';
				}
				?>
			</div>

			<!-- Active Status -->
			<div class="gap-form-group" style="margin-top: 20px;">
				<label>
					<input
						type="checkbox"
						name="gap_is_active"
						value="1"
						<?php checked( $is_active, '1' ); ?>
					>
					<?php _e( 'Active', 'ga-plugin' ); ?>
				</label>
				<p class="description">
					<?php _e( 'Check this box to activate the tracking script. Uncheck to disable it without deleting.', 'ga-plugin' ); ?>
				</p>
			</div>
		</div>

		<style>
			.gap-meta-box {
				padding: 12px;
			}
			.gap-form-group {
				margin-bottom: 20px;
			}
			.gap-form-group label {
				display: block;
				font-weight: 600;
				margin-bottom: 6px;
			}
			.gap-form-group .description {
				margin-top: 4px;
				font-size: 12px;
				color: #666;
			}
		</style>

		<?php
	}

	/**
	 * Save meta box data
	 *
	 * @since 1.0.0
	 * @param int     $post_id Post ID
	 * @param WP_Post $post    Post object
	 */
	public function save_meta_boxes( $post_id, $post ) {
		// Verify nonce
		if ( ! isset( $_POST['gap_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['gap_meta_box_nonce'], 'gap_save_meta_boxes' ) ) {
			return;
		}

		// Check user capabilities
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Prevent autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Verify post type
		if ( $post->post_type !== 'tracking_script' ) {
			return;
		}

		// Save Script Content
		if ( isset( $_POST['gap_script_content'] ) ) {
			$script_content = wp_kses_post( wp_unslash( $_POST['gap_script_content'] ) );
			update_post_meta( $post_id, '_gap_script_content', $script_content );

			// Extract tracking IDs and generate unique hash
			$detector = GAP_Conflict_Detector::get_instance();
			$extracted_ids = $detector->extract_tracking_ids( $script_content );
			update_post_meta( $post_id, '_gap_extracted_ids', $extracted_ids );

			// Generate unique hash for content comparison
			$unique_hash = md5( $script_content );
			update_post_meta( $post_id, '_gap_unique_hash', $unique_hash );
		} else {
			delete_post_meta( $post_id, '_gap_script_content' );
			delete_post_meta( $post_id, '_gap_extracted_ids' );
			delete_post_meta( $post_id, '_gap_unique_hash' );
		}

		// Save Placement
		if ( isset( $_POST['gap_placement'] ) ) {
			$placement = sanitize_text_field( $_POST['gap_placement'] );
			$allowed_placements = array( 'head', 'body_top', 'body_bottom', 'footer' );
			if ( in_array( $placement, $allowed_placements, true ) ) {
				update_post_meta( $post_id, '_gap_placement', $placement );
			}
		}

		// Save Scope
		if ( isset( $_POST['gap_scope'] ) ) {
			$scope = sanitize_text_field( $_POST['gap_scope'] );
			$allowed_scopes = array( 'global', 'specific_pages' );
			if ( in_array( $scope, $allowed_scopes, true ) ) {
				update_post_meta( $post_id, '_gap_scope', $scope );
			}
		}

		// Save Target Pages
		$target_pages = array();
		if ( isset( $_POST['gap_target_pages'] ) && is_array( $_POST['gap_target_pages'] ) ) {
			$target_pages = array_map( 'absint', $_POST['gap_target_pages'] );
			// Remove any zero values
			$target_pages = array_filter( $target_pages );
		}
		update_post_meta( $post_id, '_gap_target_pages', $target_pages );

		// Save Active Status
		$is_active = isset( $_POST['gap_is_active'] ) ? '1' : '0';
		update_post_meta( $post_id, '_gap_is_active', $is_active );
	}

	/**
	 * Enqueue admin assets (CSS and JavaScript)
	 *
	 * @since 1.0.0
	 * @param string $hook Current admin page hook
	 */
	public function enqueue_admin_assets( $hook ) {
		// Only load on tracking_script edit pages
		if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
			return;
		}

		global $post_type;
		if ( 'tracking_script' !== $post_type ) {
			return;
		}

		// Enqueue JavaScript
		wp_enqueue_script(
			'gap-admin-js',
			GAP_PLUGIN_URL . 'assets/js/admin.js',
			array( 'jquery' ),
			GAP_VERSION,
			true
		);

		// Enqueue CSS
		wp_enqueue_style(
			'gap-admin-css',
			GAP_PLUGIN_URL . 'assets/css/admin.css',
			array(),
			GAP_VERSION
		);

		// Add body class for JavaScript detection
		add_filter(
			'admin_body_class',
			function ( $classes ) {
				return $classes . ' post-type-tracking_script';
			}
		);
	}
}
