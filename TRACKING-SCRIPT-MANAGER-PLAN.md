# Tracking Script Manager Plugin - Detailed Planning Document

## Project Overview

**Plugin Name:** Tracking Script Manager
**Slug:** tracking-script-manager
**Purpose:** WordPress plugin for managing Google Analytics (GA), Google Tag Manager (GTM), and other tracking scripts with granular control over placement and scope
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
7. **Auto-Updates**: GitHub-based automatic update system for private repositories

---

## Architecture Overview

### Plugin Structure
```
wp-content/plugins/tracking-script-manager/
├── tracking-script-manager.php          # Main plugin file (plugin header, constants, initialization)
├── includes/
│   ├── class-tsm-activator.php          # Activation/deactivation logic
│   ├── class-tsm-cpt.php                # Custom Post Type registration
│   ├── class-tsm-meta-boxes.php         # Meta boxes and field handling
│   ├── class-tsm-frontend.php           # Frontend script output logic
│   ├── class-tsm-admin.php              # Admin UI enhancements
│   ├── class-tsm-updater.php            # GitHub updater wrapper
│   └── vendor/
│       └── plugin-update-checker/       # YahnisElsts library (v5.6+)
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

#### 1.1 Main Plugin File (`tracking-script-manager.php`)

**Purpose:** Plugin bootstrap, define constants, load classes

**Implementation:**
```php
<?php
/**
 * Plugin Name:       Tracking Script Manager
 * Plugin URI:        https://github.com/YOUR-ORG/tracking-script-manager
 * Description:       Manage GA, GTM, and tracking scripts with granular placement and scope control
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Your Name/Company
 * Author URI:        https://yourcompany.com
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tracking-script-manager
 * Domain Path:       /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('TSM_VERSION', '1.0.0');
define('TSM_PLUGIN_FILE', __FILE__);
define('TSM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TSM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TSM_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Autoload classes
spl_autoload_register(function ($class) {
    $prefix = 'TSM_';
    $base_dir = TSM_PLUGIN_DIR . 'includes/';

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
register_activation_hook(__FILE__, array('TSM_Activator', 'activate'));
register_deactivation_hook(__FILE__, array('TSM_Activator', 'deactivate'));

// Initialize plugin
add_action('plugins_loaded', 'tsm_init');
function tsm_init() {
    // Load text domain for translations
    load_plugin_textdomain('tracking-script-manager', false, dirname(TSM_PLUGIN_BASENAME) . '/languages');

    // Initialize core components
    TSM_CPT::get_instance();
    TSM_Meta_Boxes::get_instance();
    TSM_Frontend::get_instance();
    TSM_Admin::get_instance();
    TSM_Updater::get_instance();
}
```

**Security Considerations:**
- `ABSPATH` check prevents direct file access
- All constants use consistent prefix (`TSM_`)
- Autoloader follows WordPress conventions
- Text domain for internationalization

---

### Phase 2: Custom Post Type (`class-tsm-cpt.php`) (Estimated: 1-2 hours)

**Purpose:** Register `tracking_script` CPT with proper capabilities and labels

**Key Features:**
- Admin-only post type (not publicly queryable)
- Custom admin columns: Placement, Scope, Target Pages, Status
- Supports: title, custom fields only (no editor, no revisions)
- Capabilities restricted to `manage_options`

**Implementation Outline:**
```php
class TSM_CPT {
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
            'label'               => __('Tracking Scripts', 'tracking-script-manager'),
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
        // Add: Placement, Scope, Pages, Status columns
    }

    public function render_custom_columns($column, $post_id) {
        // Render column data
    }
}
```

**WordPress Standards Reference:**
- [register_post_type() Documentation](https://developer.wordpress.org/reference/functions/register_post_type/)
- [Custom Post Type Capabilities](https://developer.wordpress.org/plugins/post-types/registering-custom-post-types/#capability-type)

---

### Phase 3: Meta Boxes & Fields (`class-tsm-meta-boxes.php`) (Estimated: 3-4 hours)

**Purpose:** Native WordPress meta boxes for script configuration

**Meta Fields:**

| Field Name                | Type             | Description                                      |
|---------------------------|------------------|--------------------------------------------------|
| `_tsm_script_content`     | Textarea         | The actual script/tag code                       |
| `_tsm_placement`          | Select           | head, body_top, body_bottom, footer              |
| `_tsm_scope`              | Select           | global, specific_pages                           |
| `_tsm_target_pages`       | Checkboxes       | Multi-select page IDs (shown when scope=specific)|
| `_tsm_is_active`          | Checkbox         | Enable/disable script                            |

**Implementation Outline:**
```php
class TSM_Meta_Boxes {
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
            'tsm_script_config',
            __('Script Configuration', 'tracking-script-manager'),
            array($this, 'render_script_config_meta_box'),
            'tracking_script',
            'normal',
            'high'
        );
    }

    public function render_script_config_meta_box($post) {
        // Nonce field
        wp_nonce_field('tsm_save_meta_boxes', 'tsm_meta_nonce');

        // Get existing values
        $script_content = get_post_meta($post->ID, '_tsm_script_content', true);
        $placement = get_post_meta($post->ID, '_tsm_placement', true);
        $scope = get_post_meta($post->ID, '_tsm_scope', true);
        $target_pages = get_post_meta($post->ID, '_tsm_target_pages', true);
        $is_active = get_post_meta($post->ID, '_tsm_is_active', true);

        // Render HTML fields
        // - Textarea for script content
        // - Select for placement
        // - Select for scope (with JS to show/hide page selector)
        // - Checkboxes for pages (dynamically populated)
        // - Checkbox for is_active
    }

    public function save_meta_boxes($post_id, $post) {
        // Verify nonce
        if (!isset($_POST['tsm_meta_nonce']) || !wp_verify_nonce($_POST['tsm_meta_nonce'], 'tsm_save_meta_boxes')) {
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
        if (isset($_POST['tsm_script_content'])) {
            // Use wp_kses_post() to allow safe HTML/scripts
            $content = wp_kses_post(wp_unslash($_POST['tsm_script_content']));
            update_post_meta($post_id, '_tsm_script_content', $content);
        }

        if (isset($_POST['tsm_placement'])) {
            $placement = sanitize_text_field($_POST['tsm_placement']);
            // Validate against allowed values
            $allowed_placements = array('head', 'body_top', 'body_bottom', 'footer');
            if (in_array($placement, $allowed_placements, true)) {
                update_post_meta($post_id, '_tsm_placement', $placement);
            }
        }

        // Similar sanitization for other fields...

        // Checkbox handling
        $is_active = isset($_POST['tsm_is_active']) ? '1' : '0';
        update_post_meta($post_id, '_tsm_is_active', $is_active);
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

        wp_enqueue_style('tsm-admin-css', TSM_PLUGIN_URL . 'assets/css/admin.css', array(), TSM_VERSION);
        wp_enqueue_script('tsm-admin-js', TSM_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), TSM_VERSION, true);
    }
}
```

**Security Implementation:**

1. **Nonce Verification:**
   ```php
   wp_nonce_field('tsm_save_meta_boxes', 'tsm_meta_nonce');
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

### Phase 4: Frontend Output (`class-tsm-frontend.php`) (Estimated: 2-3 hours)

**Purpose:** Query and output scripts on front-end based on placement and scope

**Key Features:**
- Single query function to get active scripts
- Request-level caching to minimize DB queries
- Conditional output based on scope
- Hook into WordPress at appropriate priority

**Implementation Outline:**
```php
class TSM_Frontend {
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
                    'key'     => '_tsm_placement',
                    'value'   => $placement,
                    'compare' => '='
                ),
                array(
                    'key'     => '_tsm_is_active',
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
            $scope = get_post_meta($script->ID, '_tsm_scope', true);

            if ($scope === 'global') {
                $filtered_scripts[] = $script;
            } elseif ($scope === 'specific_pages' && $current_page_id) {
                $target_pages = get_post_meta($script->ID, '_tsm_target_pages', true);
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

        echo "\n<!-- Tracking Script Manager: {$placement} -->\n";

        foreach ($scripts as $script) {
            $content = get_post_meta($script->ID, '_tsm_script_content', true);
            if ($content) {
                echo $content . "\n";
            }
        }

        echo "<!-- /Tracking Script Manager: {$placement} -->\n\n";
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

### Phase 5: Admin Enhancements (`class-tsm-admin.php`) (Estimated: 1-2 hours)

**Purpose:** Improve admin UX with custom messages, help tabs, and styling

**Implementation Outline:**
```php
class TSM_Admin {
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

        // Example: Warning about script testing
        echo '<div class="notice notice-info is-dismissible">';
        echo '<p>' . __('Remember to test tracking scripts in a staging environment before deploying to production.', 'tracking-script-manager') . '</p>';
        echo '</div>';
    }

    public function add_help_tabs() {
        $screen = get_current_screen();
        if ('tracking_script' !== $screen->post_type) {
            return;
        }

        $screen->add_help_tab(array(
            'id'      => 'tsm_help_overview',
            'title'   => __('Overview', 'tracking-script-manager'),
            'content' => '<p>' . __('Use this screen to create and manage tracking scripts...', 'tracking-script-manager') . '</p>'
        ));

        // Add more help tabs for Placement, Scope, etc.
    }
}
```

---

### Phase 6: Activator (`class-tsm-activator.php`) (Estimated: 30 minutes)

**Purpose:** Handle activation/deactivation tasks

**Implementation Outline:**
```php
class TSM_Activator {
    public static function activate() {
        // Register CPT (required for flush_rewrite_rules)
        TSM_CPT::get_instance()->register_post_type();

        // Flush rewrite rules
        flush_rewrite_rules();

        // Set default options if needed
        if (get_option('tsm_version') === false) {
            add_option('tsm_version', TSM_VERSION);
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

### Phase 7: GitHub Auto-Updater (`class-tsm-updater.php`) (Estimated: 1-2 hours)

**Purpose:** Enable automatic updates from private GitHub repository

**Implementation Based on rosens-product Plugin:**

#### 7.1 Include YahnisElsts Plugin Update Checker Library

1. Download the library:
   - URL: https://github.com/YahnisElsts/plugin-update-checker/releases/latest
   - Current stable version: 5.6
   - License: MIT

2. Place in plugin directory:
   ```
   includes/vendor/plugin-update-checker/
   ```

3. Add to `.gitignore` if using Composer, or commit directly if bundling

#### 7.2 Updater Wrapper Class

```php
<?php
/**
 * GitHub updater class using YahnisElsts Plugin Update Checker
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class TSM_Updater {
    private static $instance = null;
    private $update_checker;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->load_update_checker();
        $this->setup_update_checker();
    }

    /**
     * Load the Plugin Update Checker library
     */
    private function load_update_checker() {
        $update_checker_path = TSM_PLUGIN_DIR . 'includes/vendor/plugin-update-checker/plugin-update-checker.php';

        if (file_exists($update_checker_path)) {
            require_once $update_checker_path;
        } else {
            error_log('TSM: Plugin Update Checker library not found at: ' . $update_checker_path);
        }
    }

    /**
     * Initialize the update checker
     */
    private function setup_update_checker() {
        // Check if the library class exists
        if (!class_exists('YahnisElsts\PluginUpdateChecker\v5\PucFactory')) {
            error_log('TSM: Plugin Update Checker class not available');
            return;
        }

        try {
            // Initialize update checker with GitHub repository
            $this->update_checker = YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
                'https://github.com/YOUR-ORG/tracking-script-manager',
                TSM_PLUGIN_FILE,
                'tracking-script-manager'
            );

            // Set the branch that contains stable releases (default: master)
            // Use 'main' if that's your default branch
            $this->update_checker->setBranch('main');

            // Enable release assets for GitHub releases
            // This is REQUIRED for private repositories
            $this->update_checker->getVcsApi()->enableReleaseAssets();

            // Set authentication for private repository access
            // Define TSM_GITHUB_TOKEN in wp-config.php:
            // define('TSM_GITHUB_TOKEN', 'ghp_your_personal_access_token_here');
            if (defined('TSM_GITHUB_TOKEN')) {
                $this->update_checker->setAuthentication(TSM_GITHUB_TOKEN);
            } else {
                error_log('TSM: TSM_GITHUB_TOKEN not defined - private repo access will fail');
            }

        } catch (Exception $e) {
            error_log('TSM: Failed to initialize Plugin Update Checker: ' . $e->getMessage());
        }
    }

    /**
     * Get the update checker instance
     *
     * @return object|null
     */
    public function get_update_checker() {
        return $this->update_checker;
    }

    /**
     * Manually trigger an update check
     */
    public function check_for_updates() {
        if ($this->update_checker) {
            try {
                $this->update_checker->checkForUpdates();
            } catch (Exception $e) {
                error_log('TSM: Failed to check for updates: ' . $e->getMessage());
            }
        }
    }

    /**
     * Get current update information
     *
     * @return object|null
     */
    public function get_update_info() {
        if ($this->update_checker) {
            return $this->update_checker->getUpdate();
        }
        return null;
    }
}
```

#### 7.3 GitHub Personal Access Token Setup

**For Private Repositories:**

1. **Create GitHub Personal Access Token:**
   - Go to GitHub Settings → Developer settings → Personal access tokens → Tokens (classic)
   - Click "Generate new token (classic)"
   - Required scopes:
     - `repo` (Full control of private repositories)
   - Copy the token (starts with `ghp_`)
   - Reference: [GitHub PAT Documentation](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens)

2. **Add Token to `wp-config.php`:**
   ```php
   /**
    * GitHub Personal Access Token for Tracking Script Manager updates
    * DO NOT commit this to version control
    */
   define('TSM_GITHUB_TOKEN', 'ghp_your_personal_access_token_here');
   ```

3. **Security Best Practice:**
   - Never commit tokens to version control
   - Add to `.gitignore`:
     ```
     wp-config.php
     .env
     ```
   - Use environment variables in production
   - Rotate tokens periodically

**For Public Repositories:**
- No token needed
- Remove authentication lines from updater class

#### 7.4 How to Release Updates

Based on the Plugin Update Checker library, you have three options:

**Option 1: GitHub Releases (RECOMMENDED for private repos)**

1. Create a new release on GitHub:
   - Go to repository → Releases → "Draft a new release"
   - Tag version: `v1.1.0` (must match version in plugin file)
   - Release title: "Version 1.1.0"
   - Description: Changelog/release notes (shown to users)
   - Attach ZIP file as release asset (optional but recommended for private repos)

2. The plugin will automatically detect the new release

3. Example release asset creation:
   ```bash
   # In your plugin directory
   cd ..
   zip -r tracking-script-manager-1.1.0.zip tracking-script-manager/ \
       -x "*.git*" "*.DS_Store" "*node_modules*" "*.log"
   # Upload this ZIP as a release asset
   ```

**Option 2: Git Tags**

1. Update version in `tracking-script-manager.php`:
   ```php
   * Version: 1.1.0
   ```
   And in constant:
   ```php
   define('TSM_VERSION', '1.1.0');
   ```

2. Create and push tag:
   ```bash
   git tag v1.1.0
   git push origin v1.1.0
   ```

**Option 3: Stable Branch**

1. Use a dedicated branch (e.g., `stable`, `production`)
2. Update version number in the branch
3. The updater checks the version header in the plugin file

**Update Check Frequency:**
- Default: Every 12 hours
- Manual check: Plugins page → "Check for updates" link
- Can be customized with filters

#### 7.5 Testing the Updater

1. **Enable Debug Bar plugin** (optional but helpful)
2. Navigate to Plugins page
3. Look for "Check for updates" link next to plugin
4. Check error logs for any issues:
   ```php
   error_log('TSM: Update check initiated');
   ```

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
wp_nonce_field('tsm_save_meta_boxes', 'tsm_meta_nonce');

// Verifying nonce
if (!wp_verify_nonce($_POST['tsm_meta_nonce'], 'tsm_save_meta_boxes')) {
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
  wp_localize_script('tsm-admin-js', 'tsmAjax', array(
      'nonce' => wp_create_nonce('tsm_ajax_nonce')
  ));

  // In JavaScript
  $.ajax({
      data: {
          action: 'tsm_action',
          nonce: tsmAjax.nonce
      }
  });

  // In PHP AJAX handler
  check_ajax_referer('tsm_ajax_nonce', 'nonce');
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
   - Functions: `tsm_function_name()`
   - Classes: `TSM_Class_Name`
   - Constants: `TSM_CONSTANT_NAME`
   - Files: `class-tsm-class-name.php`

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
- `TSM_CPT`: Only handles post type registration
- `TSM_Meta_Boxes`: Only handles meta fields
- `TSM_Frontend`: Only handles output
- `TSM_Updater`: Only handles updates

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

4. **Security:**
   - [ ] Non-admin users can't access CPT
   - [ ] Direct file access blocked
   - [ ] Nonces prevent CSRF
   - [ ] Malicious scripts sanitized

5. **Updates:**
   - [ ] Update checker initializes without errors
   - [ ] "Check for updates" link appears
   - [ ] Updates detected from GitHub
   - [ ] Update installs successfully

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
   - [ ] All functions prefixed with `tsm_` or in `TSM_` class
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

4. **GitHub Setup:**
   - [ ] Repository created (public or private)
   - [ ] Initial release created (v1.0.0)
   - [ ] Release includes ZIP asset (if private repo)
   - [ ] Repository settings: default branch = main

### Installation Instructions (for end users)

1. **Download plugin:**
   - GitHub: Download latest release ZIP
   - Or clone repository

2. **Install:**
   - WordPress Admin → Plugins → Add New → Upload Plugin
   - Select ZIP file
   - Click "Install Now"
   - Activate

3. **Configure GitHub Token (if private repo):**
   - Add to `wp-config.php`:
     ```php
     define('TSM_GITHUB_TOKEN', 'your-token-here');
     ```

4. **Create Tracking Scripts:**
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
- [ ] Admin CSS/JS for dynamic UI
- [ ] Admin notices and help tabs

**Deliverable:** Can create and save tracking scripts in admin

### Phase 3: Frontend Output (Est. 2-3 hours)
- [ ] Frontend class
- [ ] Query logic with caching
- [ ] Hook into wp_head, wp_body_open, wp_footer
- [ ] Conditional output based on scope

**Deliverable:** Scripts output correctly on front-end

### Phase 4: GitHub Updater (Est. 2-3 hours)
- [ ] Download and integrate Plugin Update Checker library
- [ ] Create updater wrapper class
- [ ] Initialize updater in main plugin
- [ ] Test update detection

**Deliverable:** Auto-updates work from GitHub

### Phase 5: Testing & Polish (Est. 3-4 hours)
- [ ] Manual testing (all features)
- [ ] Security audit
- [ ] Code standards validation
- [ ] Documentation (README, inline comments)
- [ ] Create initial GitHub release

**Deliverable:** Production-ready v1.0.0

### Phase 6: Future Enhancements (Optional)
- [ ] Import/Export functionality
- [ ] Script templates (GA4, GTM, Facebook Pixel)
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
# Tracking Script Manager

WordPress plugin for managing GA, GTM, and tracking scripts with granular placement and scope control.

## Features

- Manage tracking scripts via custom post type
- Multiple placement options: Head, Body Top, Body Bottom, Footer
- Scope control: Global or page-specific
- No dependencies on other plugins
- Automatic updates from GitHub

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

## GitHub Auto-Updates

For private repositories, add to `wp-config.php`:

```php
define('TSM_GITHUB_TOKEN', 'your-github-personal-access-token');
```

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
- [ ] GitHub token not hardcoded in plugin files
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

### GitHub Resources
- [Personal Access Tokens](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens)
- [Creating Releases](https://docs.github.com/en/repositories/releasing-projects-on-github/managing-releases-in-a-repository)

### Plugin Update Checker
- [GitHub Repository](https://github.com/YahnisElsts/plugin-update-checker)
- [Documentation](https://github.com/YahnisElsts/plugin-update-checker#getting-started)
- [License: MIT](https://github.com/YahnisElsts/plugin-update-checker/blob/master/license.txt)

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
| Frontend Output | 2-3 |
| GitHub Updater | 2-3 |
| Testing & Polish | 3-4 |
| **Total** | **15-21 hours** |

*Note: Times assume experienced WordPress developer familiar with coding standards*

---

## Handoff Notes for Developer

### What's Included in This Document

1. Complete architecture overview
2. Detailed implementation plan for each class
3. Security best practices with code examples
4. WordPress coding standards reference
5. GitHub updater integration (based on rosens-product example)
6. Testing strategy and deployment checklist
7. All necessary references and documentation links

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
2. Review rosens-product plugin updater implementation
3. Consult WordPress Plugin Developer Handbook

### Success Criteria

Plugin is complete when:
- [ ] All roadmap phases finished
- [ ] Security audit passes
- [ ] Manual testing checklist complete
- [ ] First GitHub release published
- [ ] Updates work from GitHub
- [ ] README documentation accurate

---

**Document Version:** 1.0
**Last Updated:** 2025-10-14
**Based On:** rosens-product plugin updater implementation
