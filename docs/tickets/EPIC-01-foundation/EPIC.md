# EPIC-01: Foundation & Core Plugin

**Epic ID:** EPIC-01
**Epic Name:** Foundation & Core Plugin
**Status:** Not Started
**Priority:** P0 (Critical)
**Estimated Time:** 4-6 hours
**Dependencies:** EPIC-00 (Project Setup)

---

## Overview

Develop the core plugin foundation including the main plugin file, constants, autoloader, activation/deactivation logic, and basic plugin initialization. This epic establishes the fundamental architecture that all other components will build upon.

## Objectives

1. Create main plugin file with WordPress-compliant header
2. Define plugin constants for paths and versioning
3. Implement PSR-4 compliant autoloader
4. Create activator class for plugin lifecycle management
5. Establish plugin initialization sequence
6. Ensure security best practices are followed

## Success Criteria

- [ ] Plugin successfully activates in WordPress admin
- [ ] No PHP errors or warnings on activation
- [ ] Plugin appears in WordPress plugins list with correct metadata
- [ ] Autoloader successfully loads all plugin classes
- [ ] Activation/deactivation hooks execute properly
- [ ] All security checks in place (ABSPATH, capability checks)
- [ ] Code follows WordPress coding standards

---

## User Stories

### US-01.1: As a WordPress administrator, I need to activate the plugin
**Priority:** P0
**Story Points:** 5

**Acceptance Criteria:**
- [ ] Plugin header contains all required WordPress metadata
- [ ] Plugin appears in WordPress admin under Plugins
- [ ] Activation completes without errors
- [ ] Plugin version displays correctly
- [ ] Plugin description is clear and accurate
- [ ] Author information is correct
- [ ] License information is present

**Tasks:**
- [ ] Create `ga-plugin.php` with WordPress plugin header (30 min)
- [ ] Add plugin metadata (name, description, version, author) (15 min)
- [ ] Add ABSPATH security check (5 min)
- [ ] Test activation in WordPress admin (10 min)
- [ ] Verify plugin metadata displays correctly (5 min)

**Plugin Header Fields:**
```php
/**
 * Plugin Name:       GA Plugin
 * Plugin URI:        https://github.com/OrasesWPDev/gaplugin
 * Description:       Manage Google Analytics 4 (GA4) and Google Tag Manager (GTM) scripts with granular placement and scope control
 * Version:           1.0.0
 * Requires at least: 6.0
 * Tested up to:      6.8
 * Requires PHP:      7.4
 * Author:            Orases
 * Author URI:        https://orases.com
 * Text Domain:       ga-plugin
 * Domain Path:       /languages
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://github.com/OrasesWPDev/gaplugin
 * Network:           false
 */
```

---

### US-01.2: As a developer, I need plugin constants for consistent path references
**Priority:** P0
**Story Points:** 2

**Acceptance Criteria:**
- [ ] All path constants defined using WordPress functions
- [ ] Version constant defined for cache busting
- [ ] Constants use consistent `GAP_` prefix
- [ ] Constants are documented with inline comments
- [ ] Constants available throughout plugin

**Tasks:**
- [ ] Define GAP_VERSION constant (5 min)
- [ ] Define GAP_PLUGIN_FILE constant (5 min)
- [ ] Define GAP_PLUGIN_DIR constant (5 min)
- [ ] Define GAP_PLUGIN_URL constant (5 min)
- [ ] Define GAP_PLUGIN_BASENAME constant (5 min)
- [ ] Add inline documentation for each constant (10 min)

**Constants to Define:**
```php
define('GAP_VERSION', '1.0.0');
define('GAP_PLUGIN_FILE', __FILE__);
define('GAP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('GAP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('GAP_PLUGIN_BASENAME', plugin_basename(__FILE__));
```

---

### US-01.3: As a developer, I need an autoloader to load plugin classes
**Priority:** P0
**Story Points:** 3

**Acceptance Criteria:**
- [ ] Autoloader follows PSR-4 conventions
- [ ] Class names map correctly to file names
- [ ] Autoloader only loads classes with GAP_ prefix
- [ ] File existence checked before requiring
- [ ] Autoloader registered with spl_autoload_register()
- [ ] No errors when loading non-existent classes

**Tasks:**
- [ ] Implement spl_autoload_register() function (45 min)
- [ ] Add class name to file name conversion logic (20 min)
- [ ] Add file existence check (10 min)
- [ ] Test with dummy class files (15 min)
- [ ] Document autoloader logic (10 min)

**Autoloader Requirements:**
- Prefix: `GAP_`
- Base directory: `includes/`
- File naming: `class-gap-class-name.php`
- Class naming: `GAP_Class_Name`

**Example:**
- Class: `GAP_CPT` → File: `includes/class-gap-cpt.php`
- Class: `GAP_Meta_Boxes` → File: `includes/class-gap-meta-boxes.php`

---

### US-01.4: As a WordPress administrator, I need proper plugin activation/deactivation
**Priority:** P0
**Story Points:** 3

**Acceptance Criteria:**
- [ ] Activation hook registered
- [ ] Deactivation hook registered
- [ ] GAP_Activator class created
- [ ] Flush rewrite rules on activation
- [ ] Plugin version saved to options on activation
- [ ] Data preserved on deactivation
- [ ] No errors during activation/deactivation

**Tasks:**
- [ ] Create `includes/class-gap-activator.php` (60 min)
- [ ] Implement `activate()` static method (30 min)
- [ ] Implement `deactivate()` static method (15 min)
- [ ] Register activation hook in main plugin file (10 min)
- [ ] Register deactivation hook in main plugin file (10 min)
- [ ] Test activation/deactivation cycle (15 min)

**GAP_Activator Methods:**
```php
class GAP_Activator {
    public static function activate() {
        // Flush rewrite rules
        // Save plugin version to options
        // Perform any initial setup
    }

    public static function deactivate() {
        // Flush rewrite rules
        // DO NOT delete data (only on uninstall)
    }
}
```

---

### US-01.5: As a developer, I need plugin initialization sequence
**Priority:** P0
**Story Points:** 3

**Acceptance Criteria:**
- [ ] Initialization function created
- [ ] Function hooked to plugins_loaded action
- [ ] Text domain loaded for translations
- [ ] All core components initialized
- [ ] Initialization order is correct
- [ ] No errors during initialization

**Tasks:**
- [ ] Create `gap_init()` function (30 min)
- [ ] Hook to `plugins_loaded` action (10 min)
- [ ] Load text domain for i18n (15 min)
- [ ] Initialize placeholder instances (15 min)
- [ ] Add inline documentation (10 min)
- [ ] Test initialization sequence (10 min)

**Initialization Sequence:**
```php
function gap_init() {
    // 1. Load text domain
    load_plugin_textdomain('ga-plugin', false, dirname(GAP_PLUGIN_BASENAME) . '/languages');

    // 2. Initialize core components
    GAP_CPT::get_instance();
    GAP_Meta_Boxes::get_instance();
    GAP_Conflict_Detector::get_instance();
    GAP_Frontend::get_instance();
    GAP_Admin::get_instance();
}
add_action('plugins_loaded', 'gap_init');
```

---

## Technical Tasks

### TT-01.1: Create Main Plugin File
**Estimated Time:** 2 hours
**Assignee:** TBD
**File:** `ga-plugin.php`

**Implementation Steps:**
1. Add WordPress plugin header with all metadata
2. Add ABSPATH security check
3. Define all plugin constants
4. Implement autoloader with spl_autoload_register()
5. Register activation/deactivation hooks
6. Create gap_init() function
7. Hook gap_init to plugins_loaded

**Code Structure:**
```php
<?php
/**
 * Plugin header here
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('GAP_VERSION', '1.0.0');
// ... other constants

// Autoload classes
spl_autoload_register(function ($class) {
    // Autoloader implementation
});

// Activation/Deactivation hooks
register_activation_hook(__FILE__, array('GAP_Activator', 'activate'));
register_deactivation_hook(__FILE__, array('GAP_Activator', 'deactivate'));

// Initialize plugin
add_action('plugins_loaded', 'gap_init');
function gap_init() {
    // Initialization code
}
```

---

### TT-01.2: Create Activator Class
**Estimated Time:** 1.5 hours
**Assignee:** TBD
**File:** `includes/class-gap-activator.php`

**Implementation Steps:**
1. Add ABSPATH security check
2. Create GAP_Activator class
3. Implement activate() method
4. Implement deactivate() method
5. Add inline documentation
6. Test activation/deactivation

**Security Checks:**
- ABSPATH defined check
- User capability checks (if needed)
- Nonce verification (if needed)

**Activation Tasks:**
- Flush rewrite rules
- Set plugin version option
- Create any necessary database tables (not needed for v1.0)

**Deactivation Tasks:**
- Flush rewrite rules
- DO NOT delete data (data persists after deactivation)

---

### TT-01.3: Create Placeholder Class Files
**Estimated Time:** 1 hour
**Assignee:** TBD

**Files to Create:**
- `includes/class-gap-cpt.php`
- `includes/class-gap-meta-boxes.php`
- `includes/class-gap-conflict-detector.php`
- `includes/class-gap-frontend.php`
- `includes/class-gap-admin.php`

**Placeholder Structure:**
```php
<?php
/**
 * Class description
 *
 * @package GA_Plugin
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class GAP_Class_Name
 */
class GAP_Class_Name {
    /**
     * Singleton instance
     *
     * @var GAP_Class_Name|null
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return GAP_Class_Name
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Initialization code (to be implemented in respective epics)
    }
}
```

---

### TT-01.4: Implement Autoloader Testing
**Estimated Time:** 30 minutes
**Assignee:** TBD

**Test Cases:**
1. Load class with correct prefix → Success
2. Load class without prefix → Ignored
3. Load non-existent class → No error
4. Load all placeholder classes → All loaded successfully
5. Verify file naming convention works

**Testing Steps:**
```php
// In wp-admin or test environment
var_dump(class_exists('GAP_CPT')); // Should be true after get_instance()
var_dump(class_exists('Non_GAP_Class')); // Should be false
```

---

## Definition of Done

- [ ] ga-plugin.php created with complete plugin header
- [ ] All plugin constants defined and documented
- [ ] Autoloader implemented and tested
- [ ] GAP_Activator class created with activate/deactivate methods
- [ ] All placeholder class files created
- [ ] Plugin activates without errors in WordPress admin
- [ ] Plugin metadata displays correctly in plugins list
- [ ] Deactivation works without deleting data
- [ ] All code follows WordPress coding standards
- [ ] All files have ABSPATH security check
- [ ] Inline documentation complete
- [ ] Code review completed
- [ ] Manual testing completed
- [ ] Committed to version control

---

## Dependencies

**Upstream Dependencies:**
- EPIC-00: Project Setup (requires directory structure)

**Downstream Dependencies:**
- EPIC-02: Admin Interface (requires autoloader and initialization)
- EPIC-03: Conflict Detection (requires autoloader and initialization)
- EPIC-04: Frontend Output (requires autoloader and initialization)

---

## Testing Requirements

### Manual Testing Checklist

#### Activation Testing
- [ ] Activate plugin in WordPress admin
- [ ] No PHP errors or warnings displayed
- [ ] Plugin appears in plugins list
- [ ] Plugin metadata correct (name, version, author)
- [ ] Check error_log for any PHP notices

#### Deactivation Testing
- [ ] Deactivate plugin
- [ ] No PHP errors displayed
- [ ] Plugin data not deleted
- [ ] Reactivate plugin
- [ ] Plugin still functions correctly

#### Autoloader Testing
- [ ] All GAP_ classes load successfully
- [ ] Non-GAP_ classes ignored by autoloader
- [ ] No file not found errors
- [ ] Class files load from correct paths

#### Constants Testing
- [ ] GAP_VERSION defined and correct
- [ ] GAP_PLUGIN_DIR points to correct directory
- [ ] GAP_PLUGIN_URL returns correct URL
- [ ] Constants accessible in all class files

### Security Testing
- [ ] Direct access to ga-plugin.php blocked
- [ ] Direct access to class files blocked
- [ ] No sensitive data exposed in constants
- [ ] Capability checks in place (where needed)

---

## WordPress Coding Standards Compliance

### Naming Conventions
- [ ] Functions: `gap_function_name()`
- [ ] Classes: `GAP_Class_Name`
- [ ] Constants: `GAP_CONSTANT_NAME`
- [ ] Files: `class-gap-class-name.php`

### Documentation
- [ ] All files have file-level docblocks
- [ ] All classes have docblocks
- [ ] All methods have docblocks
- [ ] Inline comments for complex logic

### Security
- [ ] All files have ABSPATH check
- [ ] No direct SQL queries (not applicable yet)
- [ ] Input sanitization (not applicable yet)
- [ ] Output escaping (not applicable yet)

### Code Style
- [ ] Tabs for indentation (not spaces)
- [ ] Opening braces on same line
- [ ] Yoda conditions where applicable
- [ ] Single quotes for strings (unless variable interpolation needed)

---

## Risks & Mitigations

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Autoloader conflicts with other plugins | High | Low | Use unique GAP_ prefix |
| Activation fails due to missing dependencies | High | Medium | Test on clean WordPress install |
| Rewrite rules not flushed | Medium | Low | Flush in both activate() and deactivate() |
| Version constant mismatch | Low | Medium | Use single source of truth |
| Internationalization issues | Medium | Low | Test with language files |

---

## Notes

- Keep placeholder classes minimal (just skeleton structure)
- Actual implementation will happen in subsequent epics
- Focus on establishing solid foundation
- Ensure all security best practices from start
- Document any WordPress version-specific behaviors
- Test on minimum required WordPress version (6.0)

---

## Performance Considerations

- Autoloader only loads classes when needed (lazy loading)
- Singleton pattern prevents multiple instantiations
- Minimal code executed on plugins_loaded hook
- No database queries during initialization (yet)

---

## Code Review Checklist

- [ ] All functions properly prefixed
- [ ] No global namespace pollution
- [ ] Singleton pattern correctly implemented
- [ ] ABSPATH check in all files
- [ ] WordPress coding standards followed
- [ ] Inline documentation complete
- [ ] No hardcoded values (use constants)
- [ ] Error handling in place
- [ ] No debugging code (var_dump, print_r, etc.)

---

## Related Documents

- [WordPress Plugin Header Requirements](https://developer.wordpress.org/plugins/plugin-basics/header-requirements/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [Plugin Activation/Deactivation Hooks](https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/)
- [GA Plugin Planning Document](../GA-PLUGIN-PLAN.md#phase-1-core-plugin-setup)

---

**Created:** 2025-01-16
**Last Updated:** 2025-01-16
**Epic Owner:** TBD
**Status:** Ready for Development
