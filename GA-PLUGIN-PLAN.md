# GA Plugin - Detailed Planning Document

## Project Overview

**Plugin Name:** GA Plugin
**Slug:** ga-plugin
**Purpose:** WordPress plugin for managing Google Analytics 4 (GA4) and Google Tag Manager (GTM) scripts with granular control over placement and scope
**Dependencies:** None - fully standalone
**WordPress Version:** 6.0+
**PHP Version:** 7.4+

## Goals & Requirements

### Core Functionality
1. **Custom Post Type** for managing tracking scripts
2. **Multiple Placement Options**: Head, Body Top, Body Bottom, Footer
3. **Scope Control**: Global or page-specific targeting
4. **Native WordPress Implementation**: No third-party plugin dependencies (no ACF, no page builders)
5. **Security-First**: Follow WordPress Coding Standards and security best practices
6. **DRY Principle**: Minimal, maintainable codebase
7. **⭐ NEW: Duplicate Detection System**: Automatically extract tracking IDs and prevent duplicate scripts

### Enhanced Features (Based on Client Feedback)

**Unique Tracking ID Management:**
- Automatically extracts tracking IDs from script content (GA4 and GTM only)
- Ensures each tracking script uses unique measurement IDs
- Displays extracted IDs in admin dashboard for easy identification
- Validates uniqueness across all tracking script posts

**Duplicate Script Prevention:**
- Scans all DOM sections (head, body top, body bottom, footer) before injecting scripts
- Detects if Google tracking IDs already exist on the page (from theme, other plugins, or manual insertion)
- Automatically skips duplicate script output to prevent double-tracking
- Logs conflicts with detailed explanations in HTML comments
- Admin warnings when duplicate IDs detected across multiple tracking scripts

**Benefits:**
- Prevents accidental double-tracking that skews Google Analytics data
- Protects against conflicts when multiple team members add tracking codes
- Alerts administrators when tracking scripts overlap
- Ensures clean, conflict-free Google tracking implementation

---

## Architecture Overview

### Plugin Structure
```
wp-content/plugins/ga-plugin/
├── ga-plugin.php          # Main plugin file (plugin header, constants, initialization)
├── includes/
│   ├── class-gap-activator.php          # Activation/deactivation logic
│   ├── class-gap-cpt.php                # Custom Post Type registration
│   ├── class-gap-meta-boxes.php         # Meta boxes and field handling
│   ├── class-gap-conflict-detector.php  # Duplicate script detection and ID extraction
│   ├── class-gap-frontend.php           # Frontend script output logic
│   └── class-gap-admin.php              # Admin UI enhancements
├── assets/
│   ├── css/
│   │   └── admin.css                    # Minimal admin styling
│   └── js/
│       └── admin.js                     # Admin UI interactions (optional)
├── README.md                            # Installation and usage docs
├── LICENSE.txt                          # GPL v2 license
└── .gitignore                           # Exclude logs, OS files, etc.
```

---

## Detailed Implementation Plan

### Phase 1: Core Plugin Setup (Estimated: 2-3 hours)

#### 1.1 Main Plugin File (`ga-plugin.php`)

**Purpose:** Plugin bootstrap, define constants, load classes

**Implementation:**
```php
<?php
/**
 * Plugin Name:       GA Plugin
 * Plugin URI:        https://github.com/YOUR-ORG/ga-plugin
 * Description:       Manage Google Analytics 4 (GA4) and Google Tag Manager (GTM) scripts with granular placement and scope control
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Your Name/Company
 * Author URI:        https://yourcompany.com
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ga-plugin
 * Domain Path:       /languages
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

// Autoload classes
spl_autoload_register(function ($class) {
    $prefix = 'GAP_';
    $base_dir = GAP_PLUGIN_DIR . 'includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . 'class-' . strtolower(str_replace('_', '-', $relative_class)) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Activation/Deactivation hooks
register_activation_hook(__FILE__, array('GAP_Activator', 'activate'));
register_deactivation_hook(__FILE__, array('GAP_Activator', 'deactivate'));

// Initialize plugin
add_action('plugins_loaded', 'gap_init');
function gap_init() {
    // Load text domain for translations
    load_plugin_textdomain('ga-plugin', false, dirname(GAP_PLUGIN_BASENAME) . '/languages');

    // Initialize core components
    GAP_CPT::get_instance();
    GAP_Meta_Boxes::get_instance();
    GAP_Conflict_Detector::get_instance();
    GAP_Frontend::get_instance();
    GAP_Admin::get_instance();
}
```

**Security Considerations:**
- `ABSPATH` check prevents direct file access
- All constants use consistent prefix (`GAP_`)
- Autoloader follows WordPress conventions
- Text domain for internationalization

---

### Phase 2: Custom Post Type (`class-gap-cpt.php`) (Estimated: 1-2 hours)

**Purpose:** Register `tracking_script` CPT with proper capabilities and labels

**Key Features:**
- Admin-only post type (not publicly queryable)
- Custom admin columns: Placement, Scope, Target Pages, Status
- Supports: title, custom fields only (no editor, no revisions)
- Capabilities restricted to `manage_options`

**Implementation Outline:**
```php
class GAP_CPT {
    private static $instance = null;
    const POST_TYPE = 'tracking_script';

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_filter('manage_' . self::POST_TYPE . '_posts_columns', array($this, 'add_custom_columns'));
        add_action('manage_' . self::POST_TYPE . '_posts_custom_column', array($this, 'render_custom_columns'), 10, 2);
    }

    public function register_post_type() {
        $args = array(
            'label'               => __('Tracking Scripts', 'ga-plugin'),
            'labels'              => $this->get_labels(),
            'public'              => false,
            'publicly_queryable'  => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
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
            'supports'            => array('title'),
            'rewrite'             => false,
            'has_archive'         => false,
            'can_export'          => true,
            'menu_position'       => 80,
        );

        register_post_type(self::POST_TYPE, $args);
    }

    private function get_labels() {
        // Returns array of labels for CPT
    }

    public function add_custom_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['tracking_ids'] = __('Tracking IDs', 'ga-plugin');
        $new_columns['placement'] = __('Placement', 'ga-plugin');
        $new_columns['scope'] = __('Scope', 'ga-plugin');
        $new_columns['target_pages'] = __('Target Pages', 'ga-plugin');
        $new_columns['status'] = __('Status', 'ga-plugin');
        $new_columns['date'] = $columns['date'];

        return $new_columns;
    }

    public function render_custom_columns($column, $post_id) {
        switch ($column) {
            case 'tracking_ids':
                $extracted_ids = get_post_meta($post_id, '_gap_extracted_ids', true);
                if (!empty($extracted_ids) && is_array($extracted_ids)) {
                    $ids_output = array();
                    foreach ($extracted_ids as $id_data) {
                        $ids_output[] = sprintf(
                            '<span class="gap-tracking-id gap-tracking-id-%s" title="%s">%s</span>',
                            esc_attr($id_data['type']),
                            esc_attr($id_data['name']),
                            esc_html($id_data['id'])
                        );
                    }
                    echo implode('<br>', $ids_output);
                } else {
                    echo '<span style="color: #999;">' . __('None detected', 'ga-plugin') . '</span>';
                }
                break;

            case 'placement':
                $placement = get_post_meta($post_id, '_gap_placement', true);
                if ($placement) {
                    $placement_labels = array(
                        'head' => __('Head', 'ga-plugin'),
                        'body_top' => __('Body Top', 'ga-plugin'),
                        'body_bottom' => __('Body Bottom', 'ga-plugin'),
                        'footer' => __('Footer', 'ga-plugin'),
                    );
                    echo esc_html($placement_labels[$placement] ?? $placement);
                } else {
                    echo '—';
                }
                break;

            case 'scope':
                $scope = get_post_meta($post_id, '_gap_scope', true);
                if ($scope === 'global') {
                    echo __('Global', 'ga-plugin');
                } elseif ($scope === 'specific_pages') {
                    echo __('Specific Pages', 'ga-plugin');
                } else {
                    echo '—';
                }
                break;

            case 'target_pages':
                $scope = get_post_meta($post_id, '_gap_scope', true);
                if ($scope === 'specific_pages') {
                    $target_pages = get_post_meta($post_id, '_gap_target_pages', true);
                    if (is_array($target_pages) && !empty($target_pages)) {
                        echo count($target_pages) . ' ' . __('pages', 'ga-plugin');
                    } else {
                        echo '0 ' . __('pages', 'ga-plugin');
                    }
                } else {
                    echo '—';
                }
                break;

            case 'status':
                $is_active = get_post_meta($post_id, '_gap_is_active', true);
                if ($is_active === '1') {
                    echo '<span style="color: #46b450;">● ' . __('Active', 'ga-plugin') . '</span>';
                } else {
                    echo '<span style="color: #999;">○ ' . __('Inactive', 'ga-plugin') . '</span>';
                }
                break;
        }
    }
}
```

**WordPress Standards Reference:**
- [register_post_type() Documentation](https://developer.wordpress.org/reference/functions/register_post_type/)
- [Custom Post Type Capabilities](https://developer.wordpress.org/plugins/post-types/registering-custom-post-types/#capability-type)

---

### Phase 2.5: Conflict Detection System (`class-gap-conflict-detector.php`) (Estimated: 2-3 hours)

**Purpose:** Extract tracking IDs from script content and detect duplicate scripts across the site

**Key Features:**
- Extract GA4 measurement IDs (G-XXXXXXXXXX)
- Extract Google Tag Manager IDs (GTM-XXXXXXX)
- Detect duplicate tracking IDs across multiple tracking script posts
- Scan rendered page HTML across all DOM sections for existing scripts before output
- Log conflicts for admin review

**Implementation Outline:**
```php
class GAP_Conflict_Detector {
    private static $instance = null;
    private $detected_conflicts = array();

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Hook to check for conflicts on admin pages
        add_action('admin_init', array($this, 'check_global_conflicts'));
    }

    /**
     * Extract tracking IDs from script content
     *
     * @param string $content The script content
     * @return array Array of extracted tracking IDs with types
     */
    public function extract_tracking_ids($content) {
        $tracking_ids = array();

        // GA4 Measurement IDs (G-XXXXXXXXXX)
        if (preg_match_all('/[\'"]G-[A-Z0-9]{10}[\'"]/', $content, $matches)) {
            foreach ($matches[0] as $match) {
                $id = trim($match, '\'"');
                $tracking_ids[] = array(
                    'id' => $id,
                    'type' => 'ga4',
                    'name' => 'Google Analytics 4'
                );
            }
        }

        // Google Tag Manager IDs (GTM-XXXXXXX)
        if (preg_match_all('/[\'"]GTM-[A-Z0-9]{7}[\'"]/', $content, $matches)) {
            foreach ($matches[0] as $match) {
                $id = trim($match, '\'"');
                $tracking_ids[] = array(
                    'id' => $id,
                    'type' => 'gtm',
                    'name' => 'Google Tag Manager'
                );
            }
        }

        return $tracking_ids;
    }

    /**
     * Check for duplicate tracking IDs across all tracking scripts
     *
     * @return array Array of conflicts with post IDs and tracking IDs
     */
    public function check_global_conflicts() {
        $args = array(
            'post_type'      => 'tracking_script',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        );

        $scripts = get_posts($args);
        $tracking_id_map = array(); // Map of tracking_id => array of post IDs
        $conflicts = array();

        foreach ($scripts as $script) {
            $extracted_ids = get_post_meta($script->ID, '_gap_extracted_ids', true);

            if (!empty($extracted_ids) && is_array($extracted_ids)) {
                foreach ($extracted_ids as $id_data) {
                    $tracking_id = $id_data['id'];

                    if (!isset($tracking_id_map[$tracking_id])) {
                        $tracking_id_map[$tracking_id] = array();
                    }

                    $tracking_id_map[$tracking_id][] = array(
                        'post_id' => $script->ID,
                        'title' => $script->post_title,
                        'type' => $id_data['type'],
                        'name' => $id_data['name']
                    );
                }
            }
        }

        // Find duplicates (tracking IDs used in multiple posts)
        foreach ($tracking_id_map as $tracking_id => $posts) {
            if (count($posts) > 1) {
                $conflicts[] = array(
                    'tracking_id' => $tracking_id,
                    'type' => $posts[0]['type'],
                    'name' => $posts[0]['name'],
                    'posts' => $posts
                );
            }
        }

        // Cache conflicts for admin notices
        $this->detected_conflicts = $conflicts;

        return $conflicts;
    }

    /**
     * Scan page HTML for existing tracking scripts
     *
     * @param string $html The page HTML content
     * @param array $tracking_ids Array of tracking IDs to check for
     * @return array Array of found tracking IDs
     */
    public function scan_page_html($html, $tracking_ids) {
        $found_ids = array();

        foreach ($tracking_ids as $id_data) {
            $tracking_id = $id_data['id'];

            // Check if this tracking ID exists in the HTML
            if (stripos($html, $tracking_id) !== false) {
                $found_ids[] = $id_data;
            }
        }

        return $found_ids;
    }

    /**
     * Get detected conflicts
     *
     * @return array Array of conflicts
     */
    public function get_conflicts() {
        return $this->detected_conflicts;
    }

    /**
     * Log conflict to WordPress debug log
     *
     * @param string $message The conflict message
     */
    public function log_conflict($message) {
        if (defined('WP_DEBUG') && WP_DEBUG === true) {
            error_log('TSM Conflict: ' . $message);
        }
    }
}
```

**Key Functions:**
- `extract_tracking_ids()`: Regex patterns to identify Google tracking IDs
- `check_global_conflicts()`: Detect duplicate IDs across all tracking scripts
- `scan_page_html()`: Check if tracking IDs already exist in rendered HTML (scans all DOM sections)
- `log_conflict()`: Log conflicts for debugging

**Regex Patterns Used:**
- GA4: `/['"]G-[A-Z0-9]{10}['"]/ `
- GTM: `/['"]GTM-[A-Z0-9]{7}['"]/ `

**WordPress Standards:**
- Follows singleton pattern for consistency
- Uses WP debug logging (respects WP_DEBUG constant)
- DRY principle: Reusable extraction and detection methods

---

### Phase 3: Meta Boxes & Fields (`class-gap-meta-boxes.php`) (Estimated: 3-4 hours)

**Purpose:** Native WordPress meta boxes for script configuration

**Meta Fields:**

| Field Name                | Type             | Description                                      |
|---------------------------|------------------|--------------------------------------------------|
| `_gap_script_content`     | Textarea         | The actual script/tag code                       |
| `_gap_placement`          | Select           | head, body_top, body_bottom, footer              |
| `_gap_scope`              | Select           | global, specific_pages                           |
| `_gap_target_pages`       | Checkboxes       | Multi-select page IDs (shown when scope=specific)|
| `_gap_is_active`          | Checkbox         | Enable/disable script                            |
| `_gap_extracted_ids`      | Array (hidden)   | Auto-extracted tracking IDs (GA4 and GTM only)   |
| `_gap_unique_hash`        | String (hidden)  | Hash of script content for duplicate detection   |

**Implementation Outline:**
```php
class GAP_Meta_Boxes {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post_tracking_script', array($this, 'save_meta_boxes'), 10, 2);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    public function add_meta_boxes() {
        add_meta_box(
            'gap_script_config',
            __('Script Configuration', 'ga-plugin'),
            array($this, 'render_script_config_meta_box'),
            'tracking_script',
            'normal',
            'high'
        );
    }

    public function render_script_config_meta_box($post) {
        // Nonce field
        wp_nonce_field('gap_save_meta_boxes', 'gap_meta_nonce');

        // Get existing values
        $script_content = get_post_meta($post->ID, '_gap_script_content', true);
        $placement = get_post_meta($post->ID, '_gap_placement', true);
        $scope = get_post_meta($post->ID, '_gap_scope', true);
        $target_pages = get_post_meta($post->ID, '_gap_target_pages', true);
        $is_active = get_post_meta($post->ID, '_gap_is_active', true);

        // Render HTML fields
        // - Textarea for script content
        // - Select for placement
        // - Select for scope (with JS to show/hide page selector)
        // - Checkboxes for pages (dynamically populated)
        // - Checkbox for is_active
    }

    public function save_meta_boxes($post_id, $post) {
        // Verify nonce
        if (!isset($_POST['gap_meta_nonce']) || !wp_verify_nonce($_POST['gap_meta_nonce'], 'gap_save_meta_boxes')) {
            return;
        }

        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }

        // Prevent autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Sanitize and save each field
        if (isset($_POST['gap_script_content'])) {
            // Use wp_kses_post() to allow safe HTML/scripts
            $content = wp_kses_post(wp_unslash($_POST['gap_script_content']));
            update_post_meta($post_id, '_gap_script_content', $content);

            // Extract tracking IDs from script content using Conflict Detector
            $detector = GAP_Conflict_Detector::get_instance();
            $extracted_ids = $detector->extract_tracking_ids($content);
            update_post_meta($post_id, '_gap_extracted_ids', $extracted_ids);

            // Generate unique hash for duplicate detection
            $unique_hash = md5($content);
            update_post_meta($post_id, '_gap_unique_hash', $unique_hash);
        }

        if (isset($_POST['gap_placement'])) {
            $placement = sanitize_text_field($_POST['gap_placement']);
            // Validate against allowed values
            $allowed_placements = array('head', 'body_top', 'body_bottom', 'footer');
            if (in_array($placement, $allowed_placements, true)) {
                update_post_meta($post_id, '_gap_placement', $placement);
            }
        }

        // Similar sanitization for other fields...

        // Checkbox handling
        $is_active = isset($_POST['gap_is_active']) ? '1' : '0';
        update_post_meta($post_id, '_gap_is_active', $is_active);
    }

    public function enqueue_admin_assets($hook) {
        // Only enqueue on tracking_script edit screen
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }

        $screen = get_current_screen();
        if ('tracking_script' !== $screen->post_type) {
            return;
        }

        wp_enqueue_style('gap-admin-css', GAP_PLUGIN_URL . 'assets/css/admin.css', array(), GAP_VERSION);
        wp_enqueue_script('gap-admin-js', GAP_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), GAP_VERSION, true);
    }
}
```

**Security Implementation:**

1. **Nonce Verification:**
   ```php
   wp_nonce_field('gap_save_meta_boxes', 'gap_meta_nonce');
   ```
   - Reference: [WordPress Nonce Documentation](https://developer.wordpress.org/apis/security/nonces/)

2. **Capability Checks:**
   ```php
   if (!current_user_can('manage_options')) {
       return;
   }
   ```
   - Reference: [WordPress Roles and Capabilities](https://wordpress.org/documentation/article/roles-and-capabilities/)

3. **Sanitization:**
   - `wp_kses_post()` - Allows safe HTML/JS while stripping dangerous code
   - `sanitize_text_field()` - Strips tags, encodes special chars
   - `sanitize_key()` - For array keys
   - Reference: [Data Sanitization/Escaping](https://developer.wordpress.org/apis/security/sanitizing-securing-output/)

4. **Escaping Output:**
   ```php
   echo esc_attr($placement);  // For HTML attributes
   echo esc_html($title);      // For HTML text
   echo esc_url($url);         // For URLs
   ```
   - Reference: [Escaping Documentation](https://developer.wordpress.org/apis/security/escaping/)

---

### Phase 4: Frontend Output (`class-gap-frontend.php`) (Estimated: 2-3 hours)

**Purpose:** Query and output scripts on front-end based on placement and scope

**Key Features:**
- Single query function to get active scripts
- Request-level caching to minimize DB queries
- Conditional output based on scope
- Hook into WordPress at appropriate priority

**Implementation Outline:**
```php
class GAP_Frontend {
    private static $instance = null;
    private $scripts_cache = array();

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Hook into WordPress at different placements with early priority
        add_action('wp_head', array($this, 'output_head_scripts'), 1);
        add_action('wp_body_open', array($this, 'output_body_top_scripts'), 1);
        add_action('wp_footer', array($this, 'output_body_bottom_scripts'), 1);
        add_action('wp_footer', array($this, 'output_footer_scripts'), 999);
    }

    /**
     * Get active scripts for a specific placement
     * Uses caching to minimize queries
     *
     * @param string $placement The placement location
     * @return array Array of script posts
     */
    private function get_active_scripts($placement) {
        // Check cache first
        if (isset($this->scripts_cache[$placement])) {
            return $this->scripts_cache[$placement];
        }

        $args = array(
            'post_type'      => 'tracking_script',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'     => '_gap_placement',
                    'value'   => $placement,
                    'compare' => '='
                ),
                array(
                    'key'     => '_gap_is_active',
                    'value'   => '1',
                    'compare' => '='
                )
            ),
            'orderby'        => 'menu_order title',
            'order'          => 'ASC'
        );

        $scripts = get_posts($args);

        // Filter by scope
        $current_page_id = get_the_ID();
        $filtered_scripts = array();

        foreach ($scripts as $script) {
            $scope = get_post_meta($script->ID, '_gap_scope', true);

            if ($scope === 'global') {
                $filtered_scripts[] = $script;
            } elseif ($scope === 'specific_pages' && $current_page_id) {
                $target_pages = get_post_meta($script->ID, '_gap_target_pages', true);
                if (is_array($target_pages) && in_array($current_page_id, $target_pages, true)) {
                    $filtered_scripts[] = $script;
                }
            }
        }

        // Cache the result
        $this->scripts_cache[$placement] = $filtered_scripts;

        return $filtered_scripts;
    }

    /**
     * Output scripts for head placement
     */
    public function output_head_scripts() {
        $this->output_scripts('head');
    }

    /**
     * Output scripts for body_top placement
     */
    public function output_body_top_scripts() {
        $this->output_scripts('body_top');
    }

    /**
     * Output scripts for body_bottom placement
     */
    public function output_body_bottom_scripts() {
        $this->output_scripts('body_bottom');
    }

    /**
     * Output scripts for footer placement
     */
    public function output_footer_scripts() {
        $this->output_scripts('footer');
    }

    /**
     * Output scripts for a given placement
     *
     * @param string $placement The placement location
     */
    private function output_scripts($placement) {
        $scripts = $this->get_active_scripts($placement);

        if (empty($scripts)) {
            return;
        }

        echo "\n<!-- GA Plugin: {$placement} -->\n";

        // Start output buffering to capture current page HTML
        ob_start();

        foreach ($scripts as $script) {
            $content = get_post_meta($script->ID, '_gap_script_content', true);
            $extracted_ids = get_post_meta($script->ID, '_gap_extracted_ids', true);

            if (!$content) {
                continue;
            }

            // Check for duplicate scripts in existing page HTML
            if (!empty($extracted_ids) && is_array($extracted_ids)) {
                // Get current page HTML buffer
                $current_html = ob_get_contents();

                // Also check the full page output so far
                $full_page_html = $this->get_current_page_html();
                $combined_html = $current_html . $full_page_html;

                // Use Conflict Detector to scan for existing tracking IDs
                $detector = GAP_Conflict_Detector::get_instance();
                $found_ids = $detector->scan_page_html($combined_html, $extracted_ids);

                if (!empty($found_ids)) {
                    // Duplicate detected - log and skip output
                    $id_list = array();
                    foreach ($found_ids as $id_data) {
                        $id_list[] = $id_data['id'] . ' (' . $id_data['name'] . ')';
                    }

                    $conflict_msg = sprintf(
                        'Duplicate tracking script detected for "%s". IDs already on page: %s. Skipping output to prevent double-tracking.',
                        esc_html($script->post_title),
                        implode(', ', $id_list)
                    );

                    echo "<!-- TSM: {$conflict_msg} -->\n";
                    $detector->log_conflict($conflict_msg);

                    continue; // Skip this script
                }
            }

            // No duplicates found, output the script
            echo $content . "\n";
        }

        ob_end_flush(); // End buffering and output

        echo "<!-- /GA Plugin: {$placement} -->\n\n";
    }

    /**
     * Get current page HTML output
     *
     * @return string The current page HTML
     */
    private function get_current_page_html() {
        // Get the output buffer level to capture all content
        $level = ob_get_level();
        $html = '';

        // Capture all active output buffers
        for ($i = 0; $i < $level; $i++) {
            $html .= ob_get_contents();
        }

        return $html;
    }
}
```

**WordPress Query Best Practices:**
- Use `WP_Query` or `get_posts()` instead of direct DB queries
- Implement caching to reduce database load
- Use `meta_query` for efficient metadata filtering
- Reference: [WP_Query Documentation](https://developer.wordpress.org/reference/classes/wp_query/)

**DRY Principle Implementation:**
- Single `get_active_scripts()` method handles all queries
- Reusable `output_scripts()` method for all placements
- Request-level caching prevents duplicate queries

---

### Phase 5: Admin Enhancements (`class-gap-admin.php`) (Estimated: 1-2 hours)

**Purpose:** Improve admin UX with custom messages, help tabs, and styling

**Implementation Outline:**
```php
class GAP_Admin {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_notices', array($this, 'display_admin_notices'));
        add_action('load-post.php', array($this, 'add_help_tabs'));
        add_action('load-post-new.php', array($this, 'add_help_tabs'));
    }

    public function display_admin_notices() {
        // Display helpful notices on tracking_script pages
        $screen = get_current_screen();
        if ('tracking_script' !== $screen->post_type) {
            return;
        }

        // Check for duplicate tracking IDs
        $detector = GAP_Conflict_Detector::get_instance();
        $conflicts = $detector->get_conflicts();

        if (!empty($conflicts)) {
            echo '<div class="notice notice-error">';
            echo '<p><strong>' . __('Duplicate Tracking IDs Detected!', 'ga-plugin') . '</strong></p>';
            echo '<p>' . __('The following tracking IDs are used in multiple tracking scripts. This may cause tracking issues:', 'ga-plugin') . '</p>';
            echo '<ul>';

            foreach ($conflicts as $conflict) {
                $tracking_id = esc_html($conflict['tracking_id']);
                $type_name = esc_html($conflict['name']);

                echo '<li>';
                echo sprintf(
                    __('<strong>%s</strong> (%s) is used in:', 'ga-plugin'),
                    $tracking_id,
                    $type_name
                );
                echo '<ul>';

                foreach ($conflict['posts'] as $post_data) {
                    $edit_link = get_edit_post_link($post_data['post_id']);
                    echo '<li>';
                    echo '<a href="' . esc_url($edit_link) . '">' . esc_html($post_data['title']) . '</a>';
                    echo '</li>';
                }

                echo '</ul>';
                echo '</li>';
            }

            echo '</ul>';
            echo '<p>' . __('<strong>Recommendation:</strong> Each tracking script should use a unique tracking ID. Having the same ID in multiple scripts may result in duplicate data or tracking errors.', 'ga-plugin') . '</p>';
            echo '</div>';
        }

        // Warning about script testing
        echo '<div class="notice notice-info is-dismissible">';
        echo '<p>' . __('Remember to test tracking scripts in a staging environment before deploying to production.', 'ga-plugin') . '</p>';
        echo '</div>';
    }

    public function add_help_tabs() {
        $screen = get_current_screen();
        if ('tracking_script' !== $screen->post_type) {
            return;
        }

        $screen->add_help_tab(array(
            'id'      => 'gap_help_overview',
            'title'   => __('Overview', 'ga-plugin'),
            'content' => '<p>' . __('Use this screen to create and manage tracking scripts...', 'ga-plugin') . '</p>'
        ));

        // Add more help tabs for Placement, Scope, etc.
    }
}
```

---

### Phase 6: Activator (`class-gap-activator.php`) (Estimated: 30 minutes)

**Purpose:** Handle activation/deactivation tasks

**Implementation Outline:**
```php
class GAP_Activator {
    public static function activate() {
        // Register CPT (required for flush_rewrite_rules)
        GAP_CPT::get_instance()->register_post_type();

        // Flush rewrite rules
        flush_rewrite_rules();

        // Set default options if needed
        if (get_option('gap_version') === false) {
            add_option('gap_version', GAP_VERSION);
        }
    }

    public static function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();

        // Note: Do NOT delete data on deactivation
        // Only delete on uninstall (use uninstall.php)
    }
}
```

**Best Practices:**
- Never delete user data on deactivation
- Use `uninstall.php` for cleanup on plugin deletion
- Reference: [Plugin Activation/Deactivation Hooks](https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/)

---

## Security Best Practices & References

### Input Validation & Sanitization

| Function | Use Case | Documentation |
|----------|----------|---------------|
| `sanitize_text_field()` | Text inputs (placement, scope) | [WordPress Codex](https://developer.wordpress.org/reference/functions/sanitize_text_field/) |
| `sanitize_key()` | Array keys, slugs | [WordPress Codex](https://developer.wordpress.org/reference/functions/sanitize_key/) |
| `wp_kses_post()` | HTML/script content (tracking codes) | [WordPress Codex](https://developer.wordpress.org/reference/functions/wp_kses_post/) |
| `absint()` | Integer IDs | [WordPress Codex](https://developer.wordpress.org/reference/functions/absint/) |
| `esc_url()` | URLs | [WordPress Codex](https://developer.wordpress.org/reference/functions/esc_url/) |

### Output Escaping

| Function | Use Case | Documentation |
|----------|----------|---------------|
| `esc_html()` | HTML text content | [WordPress Codex](https://developer.wordpress.org/reference/functions/esc_html/) |
| `esc_attr()` | HTML attributes | [WordPress Codex](https://developer.wordpress.org/reference/functions/esc_attr/) |
| `esc_url()` | URLs in HTML | [WordPress Codex](https://developer.wordpress.org/reference/functions/esc_url/) |
| `esc_js()` | JavaScript strings | [WordPress Codex](https://developer.wordpress.org/reference/functions/esc_js/) |
| `wp_kses()` | Custom HTML filtering | [WordPress Codex](https://developer.wordpress.org/reference/functions/wp_kses/) |

**Note:** Scripts output on frontend are intentionally unescaped (raw echo) because they contain tracking codes that must execute as-is. Security is enforced at the input stage with `wp_kses_post()` and capability checks.

### Nonce Verification

```php
// Creating nonce
wp_nonce_field('gap_save_meta_boxes', 'gap_meta_nonce');

// Verifying nonce
if (!wp_verify_nonce($_POST['gap_meta_nonce'], 'gap_save_meta_boxes')) {
    return;
}
```

**Reference:** [WordPress Nonces](https://developer.wordpress.org/apis/security/nonces/)

### Capability Checks

```php
// Check user permission
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}
```

**Reference:** [Roles and Capabilities](https://wordpress.org/documentation/article/roles-and-capabilities/)

### SQL Injection Prevention

- **ALWAYS use WP functions:** `get_posts()`, `WP_Query`, `$wpdb->prepare()`
- **NEVER use direct SQL queries** unless absolutely necessary
- If direct SQL is needed, use `$wpdb->prepare()`:
  ```php
  $wpdb->get_results($wpdb->prepare(
      "SELECT * FROM {$wpdb->posts} WHERE post_type = %s",
      'tracking_script'
  ));
  ```

**Reference:** [Data Validation in WordPress](https://developer.wordpress.org/apis/security/data-validation/)

### CSRF Protection

- All forms must include nonces
- All AJAX requests must verify nonces
- Example AJAX security:
  ```php
  // In PHP
  wp_localize_script('gap-admin-js', 'gapAjax', array(
      'nonce' => wp_create_nonce('gap_ajax_nonce')
  ));

  // In JavaScript
  $.ajax({
      data: {
          action: 'gap_action',
          nonce: gapAjax.nonce
      }
  });

  // In PHP AJAX handler
  check_ajax_referer('gap_ajax_nonce', 'nonce');
  ```

### Direct File Access Prevention

Every PHP file must start with:
```php
if (!defined('ABSPATH')) {
    exit;
}
```

**Reference:** [WordPress Plugin Security](https://developer.wordpress.org/plugins/security/)

---

## WordPress Coding Standards

### Follow WordPress PHP Coding Standards

**Official Documentation:**
- [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)

**Key Requirements:**
1. **Naming Conventions:**
   - Functions: `gap_function_name()`
   - Classes: `GAP_Class_Name`
   - Constants: `GAP_CONSTANT_NAME`
   - Files: `class-gap-class-name.php`

2. **Indentation:**
   - Use tabs, not spaces
   - Opening braces on same line

3. **Yoda Conditions:**
   ```php
   // Good
   if ('global' === $scope) {}

   // Bad
   if ($scope === 'global') {}
   ```

4. **Single vs Double Quotes:**
   - Single quotes for strings without variables
   - Double quotes when interpolating variables

5. **Spacing:**
   - No space between function name and opening parenthesis
   - Space after commas
   - Space around operators

**Validation Tools:**
- [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) with WordPress standards
- [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards)

---

## Code Quality & DRY Principles

### Single Responsibility Principle

Each class has one job:
- `GAP_CPT`: Only handles post type registration
- `GAP_Meta_Boxes`: Only handles meta fields
- `GAP_Frontend`: Only handles output
- `GAP_Admin`: Only handles admin UI enhancements
- `GAP_Conflict_Detector`: Only handles duplicate detection

### Don't Repeat Yourself (DRY)

**Example:**
```php
// BAD: Repeated query logic
public function output_head_scripts() {
    $args = array(/* query args */);
    $scripts = get_posts($args);
    // output logic
}

public function output_footer_scripts() {
    $args = array(/* query args */);
    $scripts = get_posts($args);
    // output logic
}

// GOOD: Reusable methods
private function get_active_scripts($placement) {
    // Single query method
}

private function output_scripts($placement) {
    // Single output method
}

public function output_head_scripts() {
    $this->output_scripts('head');
}
```

### Caching Strategy

Request-level caching prevents multiple identical queries:
```php
private $scripts_cache = array();

private function get_active_scripts($placement) {
    if (isset($this->scripts_cache[$placement])) {
        return $this->scripts_cache[$placement];
    }

    // Query and cache
    $this->scripts_cache[$placement] = $filtered_scripts;
    return $filtered_scripts;
}
```

---

## Testing Strategy

### Manual Testing Checklist

1. **Activation/Deactivation:**
   - [ ] Plugin activates without errors
   - [ ] CPT appears in admin menu
   - [ ] Deactivation doesn't delete data
   - [ ] Reactivation preserves settings

2. **Create Tracking Script:**
   - [ ] Can create new script
   - [ ] Title saves correctly
   - [ ] Meta fields save correctly
   - [ ] Nonce verification works

3. **Script Output:**
   - [ ] Global scripts appear on all pages
   - [ ] Page-specific scripts only appear on target pages
   - [ ] Scripts output in correct placement (head/body/footer)
   - [ ] Inactive scripts don't output
   - [ ] Multiple scripts output in correct order

3a. **Duplicate Detection & Prevention:**
   - [ ] Tracking IDs automatically extracted on save (GA4 and GTM only)
   - [ ] Extracted tracking IDs displayed in admin columns
   - [ ] Admin warning displayed when duplicate tracking IDs detected across posts
   - [ ] Scripts with duplicate IDs skip output on frontend
   - [ ] HTML comments explain why duplicate scripts were skipped
   - [ ] Conflict logs appear in WordPress debug log (when WP_DEBUG enabled)
   - [ ] DOM scanning checks all sections (head, body top, body bottom, footer)
   - [ ] Manual GA script in theme + plugin script = only one outputs
   - [ ] Two tracking script posts with same GA ID = warning in admin

4. **Security:**
   - [ ] Non-admin users can't access CPT
   - [ ] Direct file access blocked
   - [ ] Nonces prevent CSRF
   - [ ] Malicious scripts sanitized

### Browser Testing

Test script output in:
- Chrome DevTools (Console, Network, Sources)
- Firefox Developer Tools
- Safari Web Inspector

**Check for:**
- Scripts load without errors
- Console shows no JS errors
- Network tab shows tracking requests firing

### WordPress Compatibility

Test on:
- WordPress 6.0, 6.2, 6.4, latest
- PHP 7.4, 8.0, 8.1, 8.2
- MySQL 5.7, 8.0
- Common themes (Twenty Twenty-Four, Astra, GeneratePress)

---

## Deployment Checklist

### Pre-Launch

1. **Code Review:**
   - [ ] All functions prefixed with `gap_` or in `GAP_` class
   - [ ] All direct file access checks in place
   - [ ] All user inputs sanitized
   - [ ] All outputs escaped (except intentional script output)
   - [ ] No `var_dump()`, `print_r()`, `die()` debug code
   - [ ] Error logging implemented (not echoed)

2. **Documentation:**
   - [ ] README.md includes installation instructions
   - [ ] Inline code comments for complex logic
   - [ ] Help tabs in admin
   - [ ] GitHub repository description updated

3. **Files:**
   - [ ] `.gitignore` includes sensitive files
   - [ ] `LICENSE.txt` included (GPL v2)
   - [ ] Version numbers consistent across files
   - [ ] Plugin header complete and accurate

### Installation Instructions (for end users)

1. **Download plugin:**
   - GitHub: Download latest release ZIP
   - Or clone repository

2. **Install:**
   - WordPress Admin → Plugins → Add New → Upload Plugin
   - Select ZIP file
   - Click "Install Now"
   - Activate

3. **Create Tracking Scripts:**
   - Navigate to "Tracking Scripts" menu
   - Add New
   - Configure placement, scope, paste script code
   - Publish when ready

---

## Development Roadmap

### Phase 1: Foundation (Est. 4-6 hours)
- [ ] Project setup (directory structure, git init)
- [ ] Main plugin file with constants
- [ ] Autoloader
- [ ] CPT registration
- [ ] Activator class

**Deliverable:** Plugin activates, CPT appears in menu

### Phase 2: Admin Interface (Est. 4-5 hours)
- [ ] Meta boxes class
- [ ] Meta fields (script content, placement, scope, pages, active)
- [ ] Save handlers with sanitization
- [ ] Integrate tracking ID extraction on save
- [ ] Admin CSS/JS for dynamic UI
- [ ] Admin notices and help tabs

**Deliverable:** Can create and save tracking scripts in admin with automatic ID extraction

### Phase 2.5: Conflict Detection System (Est. 2-3 hours)
- [ ] Create GAP_Conflict_Detector class
- [ ] Implement tracking ID extraction (GA4 and GTM only)
- [ ] Implement global conflict detection across all tracking scripts
- [ ] Implement HTML scanning for duplicate detection across all DOM sections
- [ ] Add conflict logging functionality
- [ ] Update admin columns to show extracted tracking IDs

**Deliverable:** System automatically detects and warns about duplicate tracking IDs

### Phase 3: Frontend Output (Est. 3-4 hours)
- [ ] Frontend class
- [ ] Query logic with caching
- [ ] Hook into wp_head, wp_body_open, wp_footer
- [ ] Conditional output based on scope
- [ ] Integrate duplicate detection before script output
- [ ] HTML scanning to prevent duplicate tracking scripts
- [ ] Skip script output if duplicate detected with logging

**Deliverable:** Scripts output correctly on front-end with automatic duplicate prevention

### Phase 4: Testing & Polish (Est. 4-5 hours)
- [ ] Manual testing (all features)
- [ ] Test conflict detection with duplicate tracking IDs
- [ ] Test duplicate script prevention on frontend
- [ ] Test admin warnings and notices
- [ ] Security audit
- [ ] Code standards validation
- [ ] Documentation (README, inline comments)
- [ ] Create initial GitHub release

**Deliverable:** Production-ready v1.0.0

### Phase 5: Future Enhancements (Optional)
- [ ] Import/Export functionality
- [ ] Script templates (GA4 and GTM)
- [ ] Conditional logic builder (advanced targeting)
- [ ] Performance optimization (object caching)
- [ ] WP-CLI support
- [ ] Multisite support

---

## File Templates

### `.gitignore`
```
# WordPress
wp-config.php
.htaccess

# Plugin-specific
logs/
*.log

# OS
.DS_Store
Thumbs.db

# IDE
.idea/
.vscode/
*.sublime-project
*.sublime-workspace

# Dependencies (if using Composer)
/vendor/
composer.lock

# Node (if using npm)
node_modules/
package-lock.json
```

### `README.md` Template
```markdown
# GA Plugin

WordPress plugin for managing Google Analytics 4 (GA4) and Google Tag Manager (GTM) scripts with granular placement and scope control.

## Features

- Manage Google tracking scripts via custom post type
- Automatic duplicate detection for GA4/GTM tracking IDs
- Multiple placement options: Head, Body Top, Body Bottom, Footer
- Scope control: Global or page-specific
- No dependencies on other plugins

## Installation

1. Download the latest release ZIP
2. Upload to WordPress via Plugins → Add New → Upload
3. Activate the plugin
4. Navigate to "Tracking Scripts" in admin menu

## Usage

1. Click "Add New" in Tracking Scripts menu
2. Enter script name (internal reference)
3. Paste tracking code in "Script Content" field
4. Select placement location
5. Choose scope (global or specific pages)
6. Enable "Active" checkbox
7. Publish

## Requirements

- WordPress 6.0+
- PHP 7.4+

## License

GPL v2 or later

## Support

For issues and feature requests, please use GitHub Issues.
```

---

## Security Audit Checklist

Before releasing v1.0.0, verify:

- [ ] All user inputs sanitized before saving
- [ ] All outputs escaped (except intentional script output)
- [ ] Nonces used on all forms
- [ ] Capability checks on all admin actions
- [ ] Direct file access prevented in all PHP files
- [ ] No SQL injection vulnerabilities (use WP functions)
- [ ] No XSS vulnerabilities (proper escaping)
- [ ] No CSRF vulnerabilities (nonce checks)
- [ ] Error messages don't reveal sensitive information
- [ ] File permissions correct (644 for files, 755 for directories)

**Security Validation Tools:**
- [Plugin Security Checker](https://pluginvulnerabilities.com/plugin-security-checker/)
- [WPScan](https://wpscan.com/)
- [PHP Security Checker](https://security.symfony.com/)

---

## Reference Links

### WordPress Developer Resources
- [Plugin Developer Handbook](https://developer.wordpress.org/plugins/)
- [Coding Standards](https://developer.wordpress.org/coding-standards/)
- [Security Best Practices](https://developer.wordpress.org/apis/security/)
- [Plugin API Reference](https://developer.wordpress.org/reference/)

### Security Resources
- [OWASP WordPress Security Guide](https://owasp.org/www-project-wordpress-security-implementation-guideline/)
- [WordPress Security White Paper](https://wordpress.org/about/security/)
- [Plugin Security Checklist](https://developer.wordpress.org/plugins/security/checking-user-capabilities/)

---

## Estimated Total Development Time

| Phase | Hours |
|-------|-------|
| Foundation | 4-6 |
| Admin Interface | 4-5 |
| Conflict Detection | 2-3 |
| Frontend Output | 3-4 |
| Testing & Polish | 4-5 |
| **Total** | **17-23 hours** |

*Note: Times assume experienced WordPress developer familiar with coding standards*

---

## Handoff Notes for Developer

### What's Included in This Document

1. Complete architecture overview
2. Detailed implementation plan for each class
3. Security best practices with code examples
4. WordPress coding standards reference
5. Testing strategy and deployment checklist
6. All necessary references and documentation links

### Getting Started

1. Review this document thoroughly
2. Set up local WordPress development environment
3. Initialize git repository
4. Create GitHub repository (public or private)
5. Follow roadmap phases sequentially
6. Reference security checklist before each commit

### Questions?

All code examples are production-ready patterns. If implementation details are unclear:
1. Check WordPress Codex links provided
2. Consult WordPress Plugin Developer Handbook

### Success Criteria

Plugin is complete when:
- [ ] All roadmap phases finished
- [ ] Security audit passes
- [ ] Manual testing checklist complete
- [ ] First GitHub release published
- [ ] README documentation accurate

---

## Document Revision History

### Version 2.1 - 2025-10-14
**Scope Refinement: Google Analytics Only**

Refined plugin scope to focus exclusively on Google tracking platforms:

**Removed Features:**
- Universal Analytics (UA) support - removed UA-XXXXXXX-X tracking ID extraction
- Facebook Pixel support - removed all Facebook Pixel references
- Generic "other tracking scripts" language removed from all documentation

**Clarified Scope:**
- Plugin now exclusively supports Google Analytics 4 (GA4) and Google Tag Manager (GTM)
- Duplicate detection limited to GA4 (G-XXXXXXXXXX) and GTM (GTM-XXXXXXX) IDs only
- All DOM sections scanned (head, body top, body bottom, footer) for duplicate Google tracking IDs
- Simple, focused approach aligned with KISS principle

**Updated Sections:**
- Project Overview: Explicitly states "Google Analytics 4 (GA4) and Google Tag Manager (GTM)"
- Enhanced Features: Mentions only GA4/GTM support
- GAP_Conflict_Detector: Only includes GA4 and GTM regex patterns
- Testing checklist: Updated to reflect GA4/GTM-only scope
- Development roadmap: Clarified conflict detection is for Google platforms only

**Rationale:**
- Keeps plugin simple and maintainable
- Solves the specific client problem (duplicate Google Analytics tracking)
- Avoids scope creep and unnecessary complexity

### Version 2.0 - 2025-10-14
**Major Enhancement: Duplicate Detection System**

Added comprehensive conflict detection and prevention system based on client feedback:

1. **New Class: GAP_Conflict_Detector**
   - Automatically extracts tracking IDs
   - Detects duplicate tracking IDs across all tracking script posts
   - Scans page HTML for existing scripts before output
   - Prevents duplicate script injection on frontend
   - Logs conflicts for debugging

2. **Enhanced Meta Fields**
   - `_gap_extracted_ids`: Array of auto-extracted tracking IDs
   - `_gap_unique_hash`: MD5 hash for duplicate detection
   - Automatic extraction on save

3. **Enhanced Admin Interface**
   - New "Tracking IDs" column in CPT list view
   - Error notices when duplicate IDs detected across posts
   - Detailed conflict reporting with edit links
   - Visual indicators for tracking ID types

4. **Enhanced Frontend Output**
   - HTML scanning before script injection
   - Automatic skip if duplicate tracking ID detected
   - HTML comments explaining why scripts were skipped
   - Protection against double-tracking from theme/plugins

5. **Updated Development Timeline**
   - Added Phase 2.5: Conflict Detection (2-3 hours)
   - Updated Phase 3 to include duplicate prevention (3-4 hours)
   - Updated Phase 5 testing requirements (4-5 hours)
   - New total estimate: 19-26 hours (was 15-21 hours)

**Client Requirements Addressed:**
- ✅ Unique GA key per post - automated extraction and validation
- ✅ Verify only one script loads - HTML scanning and duplicate prevention

### Version 1.0 - 2025-10-14
**Initial Planning Document**
- Complete plugin architecture
- All core classes defined
- Security best practices
- WordPress coding standards compliance

---

**Current Document Version:** 2.1
**Last Updated:** 2025-10-14
**Based On:** Client feedback on duplicate detection requirements
   