# Phase 1: Foundation - Core Plugin Setup

**Estimated Time:** 2-3 hours

**Status:** Not Started

## Overview

This phase establishes the plugin foundation: main plugin file, constants, autoloader, and Custom Post Type (CPT) registration.

## Dependencies

None - this is the foundation phase and blocks all other work.

## Deliverables

- Main plugin file (`ga-plugin.php`)
- Plugin constants defined
- Autoloader implemented
- CPT registered (`gap_tracking`)
- Activation/deactivation hooks

## Completion Criteria

- [ ] Plugin activates without errors
- [ ] CPT registers correctly and appears in admin menu
- [ ] Autoloader loads classes correctly
- [ ] Constants defined properly
- [ ] No PHP errors or warnings

---

## 1.1 Main Plugin File (`ga-plugin.php`)

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
    GAP_Post_Type::get_instance();
    GAP_Meta_Box::get_instance();
    GAP_Conflict_Detector::get_instance();
    GAP_Frontend_Output::get_instance();
    GAP_Admin::get_instance();
}
```

**Security Considerations:**

- `ABSPATH` check prevents direct file access
- All constants use consistent prefix (`GAP_`)
- Autoloader follows WordPress conventions
- Text domain for internationalization

---

## 1.2 Activator Class (`includes/class-gap-activator.php`)

**Purpose:** Handle activation/deactivation tasks

**Implementation:**

```php
<?php
/**
 * Plugin Activator
 *
 * @package GA_Plugin
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class GAP_Activator
 *
 * Handles plugin activation and deactivation.
 */
class GAP_Activator {
    /**
     * Activation hook
     */
    public static function activate() {
        // Register CPT (required for flush_rewrite_rules)
        GAP_Post_Type::get_instance()->register_post_type();

        // Flush rewrite rules
        flush_rewrite_rules();

        // Set default options if needed
        if (get_option('gap_version') === false) {
            add_option('gap_version', GAP_VERSION);
        }
    }

    /**
     * Deactivation hook
     */
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

## WordPress Standards Reference

- [register_post_type() Documentation](https://developer.wordpress.org/reference/functions/register_post_type/)
- [Custom Post Type Capabilities](https://developer.wordpress.org/plugins/post-types/registering-custom-post-types/#capability-type)
- [Plugin Developer Handbook](https://developer.wordpress.org/plugins/)
- [Coding Standards](https://developer.wordpress.org/coding-standards/)

---

## Security Checklist

- [ ] `ABSPATH` check in all PHP files
- [ ] Constants use `GAP_` prefix
- [ ] Autoloader follows WordPress naming conventions
- [ ] Text domain matches plugin slug (`ga-plugin`)
- [ ] No debug code (`var_dump`, `print_r`, `die`)

---

## Testing Checklist

### Activation Testing

- [ ] Plugin activates without errors
- [ ] No PHP warnings or notices
- [ ] Rewrite rules flushed properly
- [ ] Version option saved correctly

### Deactivation Testing

- [ ] Plugin deactivates without errors
- [ ] Data not deleted on deactivation
- [ ] Rewrite rules flushed

### Autoloader Testing

- [ ] Classes load correctly when needed
- [ ] No fatal errors about missing classes
- [ ] Proper file naming convention followed

---

## File Structure

```
wp-content/plugins/ga-plugin/
├── ga-plugin.php                    # Main plugin file
└── includes/
    └── class-gap-activator.php      # Activation/deactivation logic
```

---

## Git Workflow for This Phase

### Branch Information
- **Branch name:** `phase-1-foundation`
- **Created from:** `main`
- **Merges to:** `main`
- **Merge dependencies:** None (foundation phase)
- **Unblocks:** Phase 2 and Phase 2.5

### Starting This Phase
```bash
# Automated (recommended)
/start-phase 1

# Manual
git checkout main
git pull origin main
git checkout -b phase-1-foundation
git push -u origin phase-1-foundation
```

### Commit Strategy

Commit after completing each deliverable:

- [ ] **Commit 1:** Main plugin file created
  ```bash
  git add ga-plugin.php
  git commit -m "feat(setup): create main plugin file with header and constants

  - Add plugin header with metadata
  - Define plugin constants (GAP_VERSION, GAP_PLUGIN_DIR, etc.)
  - Add autoloader implementation
  - Add plugin initialization

  Addresses: Phase 1 deliverable (main plugin file)"
  git push
  ```

- [ ] **Commit 2:** Activator class implemented
  ```bash
  git add includes/class-gap-activator.php
  git commit -m "feat(setup): implement plugin activation/deactivation

  - Create GAP_Activator class
  - Add activation hook (flush rewrite rules)
  - Add deactivation hook
  - Set plugin version option

  Addresses: Phase 1 deliverable (activation hooks)"
  git push
  ```

- [ ] **Commit 3:** CPT class completed
  ```bash
  git add includes/class-gap-post-type.php
  git commit -m "feat(cpt): implement tracking script custom post type

  - Register gap_tracking post type
  - Add custom admin columns
  - Implement column rendering
  - Register post meta fields

  Addresses: Phase 1 deliverable (CPT registration)"
  git push
  ```

### Commit Message Format
```
[type]([scope]): [short description]

- [Detail 1]
- [Detail 2]
- [Detail 3]

Addresses: Phase 1 deliverable ([which one])
```

**Types for this phase:** feat, fix, refactor, docs
**Scopes for this phase:** setup, cpt

### Pull Request Template

When phase is complete, use `/finish-phase 1` or create PR manually with:

**Title:** `Phase 1: Foundation - Core Plugin Setup`

**Description:**
```markdown
## Phase 1: Foundation - Core Plugin Setup

### Overview
Establishes the plugin foundation with main plugin file, constants, autoloader, and Custom Post Type registration.

### Deliverables
- [x] Main plugin file (`ga-plugin.php`)
- [x] Plugin constants defined
- [x] Autoloader implemented
- [x] CPT registered (`gap_tracking`)
- [x] Activation/deactivation hooks
- [x] Activator class created

### Files Changed
- `ga-plugin.php` - Main plugin file
- `includes/class-gap-activator.php` - Activation logic
- `includes/class-gap-post-type.php` - CPT registration

### Testing Completed
- [x] Plugin activates without errors
- [x] CPT appears in admin menu
- [x] Autoloader works correctly
- [x] Constants defined properly
- [x] No PHP errors or warnings

### Dependencies
**Requires:** None (foundation phase)
**Blocks:** Phase 2, Phase 2.5

### Review Focus
- CPT registration follows WordPress standards
- Autoloader implements PSR-4 correctly
- File naming conventions (class-gap-*.php)
- ABSPATH checks in all PHP files

🤖 Generated with [Claude Code](https://claude.com/claude-code)
```

### Completing This Phase
```bash
# Automated (recommended)
/finish-phase 1

# Manual
git status  # Verify all committed
git push    # Ensure remote up to date
gh pr create --title "Phase 1: Foundation - Core Plugin Setup" --body "[use template above]"
```

### After PR Merged
```bash
git checkout main
git pull origin main
git branch -d phase-1-foundation  # Delete local branch

# Phase 2 and 2.5 can now start in parallel
```

### File Ownership
**Phase 1 owns:**
- `ga-plugin.php`
- `includes/class-gap-activator.php`
- `includes/class-gap-post-type.php`

**No other phase should modify these files during development.**

### Git Troubleshooting

**If you see uncommitted changes:**
```bash
git status
git add [files]
git commit -m "[message]"
git push
```

**If branch is behind origin:**
```bash
git fetch origin
git pull origin phase-1-foundation
```

**If you accidentally worked on main:**
```bash
# Stash your work
git stash

# Switch to correct branch
git checkout phase-1-foundation

# Apply your work
git stash pop
```

---

## Next Phase

Once this phase is complete and all tests pass, proceed to:

- **Phase 2:** Admin Interface (meta boxes and fields)
- **Phase 2.5:** Conflict Detection (can run parallel to Phase 2)

Both phases depend on this foundation being complete.

---

## Agent Responsibilities

**Primary Agent:** `cpt-specialist`

- Creates main plugin file
- Implements autoloader
- Creates activator class
- Implements CPT registration
- Ensures WordPress coding standards

**Review Agent:** `wp-code-reviewer`

- Reviews code quality
- Checks WordPress standards compliance
- Verifies DRY/KISS principles

---

## References

- Master Plan: `@TRACKING-SCRIPT-MANAGER-PLAN.md` (Lines 73-151)
- WordPress Plugin Basics: https://developer.wordpress.org/plugins/plugin-basics/
- Activation Hooks: https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
