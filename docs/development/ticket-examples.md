# Ticket Examples

Reference examples of well-written tickets to understand the expected format and quality.

## Example User Story

### File Location
`docs/tickets/EPIC-02-admin-interface/user-stories/us-02.1-manage-tracking-scripts-via-cpt.md`

### Content

```markdown
# US-02.1: Manage Tracking Scripts via Custom Post Type

**Ticket ID:** US-02.1
**Epic:** EPIC-02 - Admin Interface
**Type:** User Story
**Priority:** P0
**Story Points:** 5
**Estimated Time:** 2 hours 15 minutes
**Status:** Not Started
**Assignee:** TBD

---

## Description

As an administrator, I need to manage tracking scripts through a custom post type
so that I can maintain tracking scripts alongside other WordPress content using
familiar WordPress patterns.

This allows administrators to leverage existing WordPress workflows for managing
tracking scripts without requiring custom UI development.

## Acceptance Criteria

- [ ] Custom post type "tracking_script" is registered
- [ ] Post type appears in WordPress admin menu with label "Tracking Scripts"
- [ ] Menu uses dashicons-analytics icon
- [ ] Only users with manage_options capability can access the post type
- [ ] Add New button allows creating new tracking scripts
- [ ] List view displays posts with table format
- [ ] Edit screen displays post title field and meta fields

## Implementation Tasks

1. [ ] Create GAP_CPT class skeleton with singleton pattern (15 min)
   - Add class docblock with @package and @since tags
   - Implement singleton with static instance and get_instance()

2. [ ] Implement register_post_type() method (45 min)
   - Set post type as non-public (supports admin only)
   - Configure labels (singular, plural, menu name)
   - Set appropriate capabilities based on manage_options

3. [ ] Configure admin menu integration (15 min)
   - Set menu_position to 20
   - Set menu_icon to dashicons-analytics
   - Verify menu appears in correct location

4. [ ] Test and verify functionality (30 min)
   - Test CPT appears in admin menu
   - Verify non-admin users cannot access
   - Verify Add New functionality works

## Dependencies

**Blockers:**
- TT-02.1: Implement GAP_CPT Class (this story depends on it)
- EPIC-01: Foundation must be merged (requires autoloader)

**Related Tickets:**
- US-02.2: Custom Admin Columns (builds on this CPT)
- TT-02.1: Implement GAP_CPT Class (technical implementation)

---

## Technical Details

**Files to Create:**
- `includes/class-gap-cpt.php` - CPT registration class

**Files to Modify:**
- `ga-plugin.php` - Include GAP_CPT class in main plugin file

**Class Structure:**
```php
class GAP_CPT {
    private static $instance = null;

    public static function get_instance() { ... }
    public function __construct() { ... }
    public function register_post_type() { ... }
}
```

**WordPress Hooks:**
- `init` hook for registering custom post type
- CPT arguments: `public` => false, `show_ui` => true, `show_in_menu` => true

**Constants:**
- Post type slug: `tracking_script`
- Capability: `manage_options`
- Menu position: 20
- Icon: `dashicons-analytics`

## Testing Requirements

**Step-by-Step Testing:**
1. Activate the plugin
2. Login as administrator
3. Verify "Tracking Scripts" menu appears in WordPress admin (below Tools, above Links)
4. Click "Tracking Scripts" menu
5. Verify post list page loads without errors
6. Click "Add New" button
7. Verify new post edit screen loads
8. Create a test post with title "Test Tracking Script"
9. Click "Publish"
10. Verify post appears in list view
11. Logout and login as subscriber
12. Verify "Tracking Scripts" menu does NOT appear
13. Verify debug.log shows no PHP errors

**Verification Checklist:**
- [ ] Menu appears in correct location (position 20)
- [ ] Menu icon is analytics (dashicons-analytics)
- [ ] Only admins can access CPT
- [ ] Posts can be created and published
- [ ] Posts appear in list view immediately
- [ ] Edit screen loads cleanly
- [ ] No PHP errors in debug log

## Definition of Done

- [ ] All acceptance criteria met and verified
- [ ] CPT class created with proper structure
- [ ] register_post_type() implemented correctly
- [ ] Menu position and icon configured
- [ ] Capability restrictions enforced
- [ ] Tested with admin and non-admin accounts
- [ ] Code follows WordPress coding standards
- [ ] No PHP errors or warnings
- [ ] Commit message references US-02.1
- [ ] Ready for code review

---

## Notes

- This is the foundation for EPIC-02; all other stories depend on it
- The CPT should not show on frontend (public=false)
- Use WordPress capabilities system, not custom roles
- Consider future need for custom columns in next story

---

**Created:** 2025-10-16
**Last Updated:** 2025-10-16
**Status:** Ready for Development
```

---

## Example Technical Task

### File Location
`docs/tickets/EPIC-02-admin-interface/technical-tasks/tt-02.1-implement-gap-cpt-class.md`

### Content

```markdown
# TT-02.1: Implement GAP_CPT Class

**Ticket ID:** TT-02.1
**Epic:** EPIC-02 - Admin Interface
**Type:** Technical Task
**Priority:** P0
**Estimated Time:** 1 hour 15 minutes
**Status:** Not Started
**Assignee:** TBD

---

## Description

Implement the GAP_CPT class to handle registration of the tracking_script
custom post type. This is the foundational class that enables administrators
to manage tracking scripts within WordPress.

The class will follow WordPress best practices including singleton pattern,
proper namespacing, and documentation standards.

## Acceptance Criteria

- [ ] GAP_CPT class exists in includes/class-gap-cpt.php
- [ ] Class uses singleton pattern with get_instance() method
- [ ] register_post_type() method successfully registers CPT
- [ ] CPT registration uses correct parameters for admin-only access
- [ ] Class is properly autoloaded
- [ ] All methods have proper docblocks

## Implementation Tasks

1. [ ] Create class file and singleton structure (20 min)
   - Create includes/class-gap-cpt.php
   - Add GPL header comment block
   - Add ABSPATH check
   - Define class with singleton get_instance()

2. [ ] Implement register_post_type() method (35 min)
   - Set post type arguments (public, show_ui, show_in_menu, etc.)
   - Configure labels (singular, plural, add_new, menu_name)
   - Set capabilities to manage_options
   - Set menu_position: 20, menu_icon: dashicons-analytics
   - Call register_post_type() WordPress function

3. [ ] Add initialization hook (10 min)
   - Hook to 'init' action
   - Ensure singleton is instantiated on init

4. [ ] Update main plugin file (10 min)
   - Add include statement for GAP_CPT class
   - Verify class is loaded before use

## Dependencies

**Blockers:**
- EPIC-01 must be merged (requires autoloader to be in place)

**Related Tickets:**
- US-02.1: Manage Tracking Scripts via CPT (this task implements it)

---

## Technical Details

**Files to Create:**
- `includes/class-gap-cpt.php`

**Files to Modify:**
- `ga-plugin.php` (add include)

**Class Template:**
```php
<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class GAP_CPT {
    private static $instance = null;

    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action( 'init', array( $this, 'register_post_type' ) );
    }

    public function register_post_type() {
        // Registration logic here
    }
}

// Initialize on plugin load
GAP_CPT::get_instance();
```

**Post Type Arguments:**
```php
$args = array(
    'public'      => false,
    'show_ui'     => true,
    'show_in_menu' => true,
    'menu_position' => 20,
    'menu_icon'   => 'dashicons-analytics',
    'capability_type' => 'post',
    'capabilities' => array(
        'create_posts' => 'manage_options',
        'edit_posts'   => 'manage_options',
        'edit_others_posts' => 'manage_options',
        'delete_posts' => 'manage_options',
        'publish_posts' => 'manage_options',
    ),
    'supports' => array( 'title', 'custom-fields' ),
    'labels' => array(
        'name'          => __( 'Tracking Scripts', 'ga-plugin' ),
        'singular_name' => __( 'Tracking Script', 'ga-plugin' ),
        'add_new'       => __( 'Add New Tracking Script', 'ga-plugin' ),
        'menu_name'     => __( 'Tracking Scripts', 'ga-plugin' ),
    ),
);
register_post_type( 'tracking_script', $args );
```

## Testing Requirements

**Code Structure Validation:**
- [ ] File exists at includes/class-gap-cpt.php
- [ ] File has proper GPL header
- [ ] ABSPATH check is first line in file
- [ ] Class has PHPCS compliance

**Functionality Testing:**
- [ ] Singleton pattern works (get_instance returns same instance)
- [ ] CPT registers on WordPress init hook
- [ ] CPT appears in admin menu at position 20
- [ ] Menu icon shows correctly (dashicons-analytics)
- [ ] Only manage_options users can access

**Integration Testing:**
- [ ] Main plugin file includes the class
- [ ] No PHP warnings or errors
- [ ] Autoloader loads the class successfully
- [ ] Works with rest of EPIC-02 stories

## Definition of Done

- [ ] GAP_CPT class created with proper structure
- [ ] Class follows WordPress coding standards
- [ ] Singleton pattern implemented correctly
- [ ] register_post_type() method functional
- [ ] All methods have docblocks
- [ ] ABSPATH security check in place
- [ ] Integrated into main plugin file
- [ ] No PHP errors or warnings
- [ ] Local testing passed (plugin activates)
- [ ] Commit created with proper message
- [ ] Ready for code review

---

## Notes

- Singleton pattern ensures single instance of CPT registration
- Use 'manage_options' capability to restrict to admins only
- CPT slug 'tracking_script' cannot have hyphens in register_post_type()
- Make sure menu_position 20 places it after settings menu

---

**Created:** 2025-10-16
**Last Updated:** 2025-10-16
**Status:** Ready for Development
```

---

## Key Differences: User Story vs Technical Task

| Aspect | User Story (US) | Technical Task (TT) |
|--------|-----------------|-------------------|
| **Perspective** | End-user value | Implementation |
| **Format** | "As a [role], I need..." | Action-oriented |
| **Estimation** | Story Points (Fibonacci) | Hours/Minutes |
| **Criteria** | Observable user behaviors | Testable code properties |
| **Tasks** | Implementation steps | Development steps |
| **Success** | User can do something | Code is correct and safe |

---

## Naming and File Organization

### User Story Filenames
```
us-02.1-manage-tracking-scripts-via-cpt.md
us-02.2-create-custom-admin-columns.md
us-02.3-configure-via-meta-fields.md
```

### Technical Task Filenames
```
tt-02.1-implement-gap-cpt-class.md
tt-02.2-implement-gap-meta-boxes-class.md
tt-02.3-create-admin-javascript.md
```

### Key Rules
- Filename matches ticket ID format (us-XX.Y or tt-XX.Y)
- Use kebab-case after ticket ID
- Keep under 50 characters after ID
- Directory structure: `docs/tickets/EPIC-XX-name/[user-stories|technical-tasks]/`

---

## When Examples Don't Match Reality

If you encounter a ticket that doesn't match these examples:
1. Use this as a template for refactoring
2. The quality checklist will identify issues
3. Fix before starting work on the ticket
4. Ask for clarification from epic owner if unsure

Use these examples as the gold standard for ticket quality.
