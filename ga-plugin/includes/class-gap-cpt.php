<?php
/**
 * Custom Post Type Registration
 *
 * @package GA_Plugin
 * @since   1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * GAP_CPT Class
 *
 * Registers the tracking_script custom post type and manages custom admin columns.
 *
 * @since 1.0.0
 */
class GAP_CPT {

	/**
	 * Custom post type slug
	 *
	 * @since 1.0.0
	 * @var string
	 */
	const POST_TYPE = 'tracking_script';

	/**
	 * Singleton instance
	 *
	 * @since 1.0.0
	 * @var GAP_CPT
	 */
	private static $instance = null;

	/**
	 * Get singleton instance
	 *
	 * @since 1.0.0
	 * @return GAP_CPT
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
	 * Registers hooks for custom post type and admin columns.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( $this, 'add_custom_columns' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'render_custom_columns' ), 10, 2 );
	}

	/**
	 * Register the tracking_script custom post type
	 *
	 * Registers a custom post type with admin-only access for managing
	 * Google Analytics tracking scripts.
	 *
	 * @since 1.0.0
	 */
	public static function register_post_type() {
		$labels = self::get_labels();

		$args = array(
			'labels'              => $labels,
			'public'              => false,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 25,
			'menu_icon'           => 'dashicons-analytics',
			'capability_type'     => 'post',
			'capabilities'        => array(
				'edit_post'          => 'manage_options',
				'read_post'          => 'manage_options',
				'delete_post'        => 'manage_options',
				'edit_posts'         => 'manage_options',
				'edit_others_posts'  => 'manage_options',
				'delete_posts'       => 'manage_options',
				'publish_posts'      => 'manage_options',
				'read_private_posts' => 'manage_options',
			),
			'supports'            => array( 'title' ),
			'has_archive'         => false,
			'rewrite'             => false,
			'can_export'          => true,
			'delete_with_user'    => false,
		);

		register_post_type( self::POST_TYPE, $args );
	}

	/**
	 * Get custom post type labels
	 *
	 * @since 1.0.0
	 * @return array Array of labels for the custom post type
	 */
	private static function get_labels() {
		return array(
			'name'                  => _x( 'Tracking Scripts', 'Post Type General Name', 'ga-plugin' ),
			'singular_name'         => _x( 'Tracking Script', 'Post Type Singular Name', 'ga-plugin' ),
			'menu_name'             => __( 'Tracking Scripts', 'ga-plugin' ),
			'name_admin_bar'        => __( 'Tracking Script', 'ga-plugin' ),
			'archives'              => __( 'Tracking Script Archives', 'ga-plugin' ),
			'attributes'            => __( 'Tracking Script Attributes', 'ga-plugin' ),
			'parent_item_colon'     => __( 'Parent Tracking Script:', 'ga-plugin' ),
			'all_items'             => __( 'All Tracking Scripts', 'ga-plugin' ),
			'add_new_item'          => __( 'Add New Tracking Script', 'ga-plugin' ),
			'add_new'               => __( 'Add New', 'ga-plugin' ),
			'new_item'              => __( 'New Tracking Script', 'ga-plugin' ),
			'edit_item'             => __( 'Edit Tracking Script', 'ga-plugin' ),
			'update_item'           => __( 'Update Tracking Script', 'ga-plugin' ),
			'view_item'             => __( 'View Tracking Script', 'ga-plugin' ),
			'view_items'            => __( 'View Tracking Scripts', 'ga-plugin' ),
			'search_items'          => __( 'Search Tracking Script', 'ga-plugin' ),
			'not_found'             => __( 'No tracking scripts found', 'ga-plugin' ),
			'not_found_in_trash'    => __( 'No tracking scripts found in Trash', 'ga-plugin' ),
			'featured_image'        => __( 'Featured Image', 'ga-plugin' ),
			'set_featured_image'    => __( 'Set featured image', 'ga-plugin' ),
			'remove_featured_image' => __( 'Remove featured image', 'ga-plugin' ),
			'use_featured_image'    => __( 'Use as featured image', 'ga-plugin' ),
			'insert_into_item'      => __( 'Insert into tracking script', 'ga-plugin' ),
			'uploaded_to_this_item' => __( 'Uploaded to this tracking script', 'ga-plugin' ),
			'items_list'            => __( 'Tracking scripts list', 'ga-plugin' ),
			'items_list_navigation' => __( 'Tracking scripts list navigation', 'ga-plugin' ),
			'filter_items_list'     => __( 'Filter tracking scripts list', 'ga-plugin' ),
		);
	}

	/**
	 * Add custom columns to the tracking_script post list
	 *
	 * @since 1.0.0
	 * @param array $columns Existing columns
	 * @return array Modified columns
	 */
	public function add_custom_columns( $columns ) {
		// Remove date column
		unset( $columns['date'] );

		// Add custom columns
		$columns['tracking_ids'] = __( 'Tracking IDs', 'ga-plugin' );
		$columns['placement']    = __( 'Placement', 'ga-plugin' );
		$columns['scope']        = __( 'Scope', 'ga-plugin' );
		$columns['target_pages'] = __( 'Target Pages', 'ga-plugin' );
		$columns['status']       = __( 'Status', 'ga-plugin' );
		$columns['date']         = __( 'Date', 'ga-plugin' );

		return $columns;
	}

	/**
	 * Render custom column content
	 *
	 * @since 1.0.0
	 * @param string $column  Column name
	 * @param int    $post_id Post ID
	 */
	public function render_custom_columns( $column, $post_id ) {
		switch ( $column ) {
			case 'tracking_ids':
				$extracted_ids = get_post_meta( $post_id, '_gap_extracted_ids', true );
				if ( empty( $extracted_ids ) || ! is_array( $extracted_ids ) ) {
					echo '<span style="color: #999;">' . __( 'None detected', 'ga-plugin' ) . '</span>';
				} else {
					$output = array();
					foreach ( $extracted_ids as $id_data ) {
						$type_class = 'gap-id-' . esc_attr( $id_data['type'] );
						$type_label = esc_html( $id_data['type'] === 'ga4' ? 'GA4' : 'GTM' );
						$id_value = esc_html( $id_data['id'] );

						$output[] = sprintf(
							'<div class="gap-tracking-id %s"><span class="gap-id-badge">%s</span> %s</div>',
							$type_class,
							$type_label,
							$id_value
						);
					}
					echo implode( '', $output );
				}
				break;

			case 'placement':
				$placement = get_post_meta( $post_id, '_gap_placement', true );
				if ( ! empty( $placement ) ) {
					$placements = array(
						'head'         => __( 'Head', 'ga-plugin' ),
						'body_top'     => __( 'Body Top', 'ga-plugin' ),
						'body_bottom'  => __( 'Body Bottom', 'ga-plugin' ),
						'footer'       => __( 'Footer', 'ga-plugin' ),
					);
					echo esc_html( $placements[ $placement ] ?? $placement );
				} else {
					echo '—';
				}
				break;

			case 'scope':
				$scope = get_post_meta( $post_id, '_gap_scope', true );
				if ( ! empty( $scope ) ) {
					$scopes = array(
						'global'         => __( 'Global', 'ga-plugin' ),
						'specific_pages' => __( 'Specific Pages', 'ga-plugin' ),
					);
					echo esc_html( $scopes[ $scope ] ?? $scope );
				} else {
					echo '—';
				}
				break;

			case 'target_pages':
				$scope = get_post_meta( $post_id, '_gap_scope', true );
				if ( $scope === 'specific_pages' ) {
					$target_pages = get_post_meta( $post_id, '_gap_target_pages', true );
					if ( is_array( $target_pages ) && ! empty( $target_pages ) ) {
						echo esc_html( count( $target_pages ) . ' ' . _n( 'page', 'pages', count( $target_pages ), 'ga-plugin' ) );
					} else {
						echo '0 ' . __( 'pages', 'ga-plugin' );
					}
				} else {
					echo '—';
				}
				break;

			case 'status':
				$is_active = get_post_meta( $post_id, '_gap_is_active', true );
				if ( $is_active === '1' ) {
					echo '<span class="gap-status-active">' . __( 'Active', 'ga-plugin' ) . '</span>';
				} else {
					echo '<span class="gap-status-inactive">' . __( 'Inactive', 'ga-plugin' ) . '</span>';
				}
				break;
		}
	}
}
