# EPIC-02: Custom Post Type & Admin Interface

**Epic ID:** EPIC-02
**Epic Name:** Custom Post Type & Admin Interface
**Status:** Not Started
**Priority:** P0 (Critical)
**Estimated Time:** 4-5 hours
**Dependencies:** EPIC-01 (Foundation)

---

## Overview

Implement the custom post type "tracking_script" with comprehensive admin interface including meta boxes, custom fields, admin columns, and field validation. This epic provides the core user interface for managing Google Analytics tracking scripts.

## Objectives

1. Register tracking_script custom post type with proper capabilities
2. Create meta boxes for tracking script configuration
3. Implement all meta fields with proper sanitization
4. Add custom admin columns for better visibility
5. Implement JavaScript for dynamic UI interactions
6. Add admin CSS for enhanced user experience
7. Ensure all security best practices are followed

## Success Criteria

- [ ] Custom post type appears in WordPress admin menu
- [ ] Only administrators can access tracking scripts
- [ ] Can create, edit, and delete tracking scripts
- [ ] All meta fields save correctly with proper sanitization
- [ ] Custom admin columns display correct information
- [ ] Scope selector shows/hides page selector dynamically
- [ ] All inputs validated before saving
- [ ] No security vulnerabilities (XSS, CSRF, SQL injection)

---

## User Stories

### US-02.1: As an administrator, I need to manage tracking scripts via custom post type
**Priority:** P0
**Story Points:** 5

**Acceptance Criteria:**
- [ ] Custom post type "tracking_script" registered
- [ ] Appears in admin menu with analytics icon
- [ ] Menu label is "Tracking Scripts"
- [ ] Only users with manage_options capability can access
- [ ] Supports only title field (no editor)
- [ ] Post type is not publicly queryable
- [ ] Can export tracking scripts

**Tasks:**
- [ ] Create GAP_CPT class skeleton (20 min)
- [ ] Implement register_post_type() method (45 min)
- [ ] Define all CPT labels (30 min)
- [ ] Set capabilities to manage_options (15 min)
- [ ] Add dashicons-analytics icon (5 min)
- [ ] Hook registration to init action (10 min)
- [ ] Test CPT appears in admin (10 min)

**CPT Configuration:**
```php
'public'              => false,
'publicly_queryable'  => false,
'show_ui'             => true,
'show_in_menu'        => true,
'menu_icon'           => 'dashicons-analytics',
'capability_type'     => 'post',
'capabilities'        => [
    'edit_post'          => 'manage_options',
    'read_post'          => 'manage_options',
    'delete_post'        => 'manage_options',
    'edit_posts'         => 'manage_options',
    'edit_others_posts'  => 'manage_options',
    'delete_posts'       => 'manage_options',
    'publish_posts'      => 'manage_options',
    'read_private_posts' => 'manage_options',
],
'supports'            => array('title'),
```

---

### US-02.2: As an administrator, I need custom admin columns to view tracking script details
**Priority:** P1
**Story Points:** 3

**Acceptance Criteria:**
- [ ] Tracking IDs column shows extracted GA4/GTM IDs
- [ ] Placement column shows script placement location
- [ ] Scope column shows global or specific pages
- [ ] Target Pages column shows page count (if applicable)
- [ ] Status column shows active/inactive with visual indicator
- [ ] All columns display appropriate values or "—" for empty

**Tasks:**
- [ ] Implement add_custom_columns() filter (30 min)
- [ ] Implement render_custom_columns() action (60 min)
- [ ] Add CSS for visual status indicators (20 min)
- [ ] Test all column outputs (20 min)

**Custom Columns:**
1. **Tracking IDs** - Display extracted GA4/GTM IDs with type labels
2. **Placement** - head, body_top, body_bottom, or footer
3. **Scope** - Global or Specific Pages
4. **Target Pages** - Number of pages (if scope is specific)
5. **Status** - Active (green) or Inactive (gray)

---

### US-02.3: As an administrator, I need to configure tracking scripts via meta fields
**Priority:** P0
**Story Points:** 8

**Acceptance Criteria:**
- [ ] Script Content textarea accepts GA4/GTM code
- [ ] Placement dropdown with 4 options
- [ ] Scope dropdown with 2 options
- [ ] Page selector appears when scope is "specific_pages"
- [ ] Active/Inactive checkbox
- [ ] All fields have proper labels and descriptions
- [ ] All fields save correctly
- [ ] All inputs sanitized before saving

**Tasks:**
- [ ] Create GAP_Meta_Boxes class (30 min)
- [ ] Implement add_meta_boxes() action (20 min)
- [ ] Create render_script_config_meta_box() method (120 min)
- [ ] Implement save_meta_boxes() with sanitization (90 min)
- [ ] Add nonce verification (15 min)
- [ ] Add capability checks (10 min)
- [ ] Test all fields save correctly (30 min)

**Meta Fields:**
| Field Key | Type | Options | Required |
|-----------|------|---------|----------|
| _gap_script_content | textarea | - | Yes |
| _gap_placement | select | head, body_top, body_bottom, footer | Yes |
| _gap_scope | select | global, specific_pages | Yes |
| _gap_target_pages | checkboxes | All published pages | Conditional |
| _gap_is_active | checkbox | - | No |

---

### US-02.4: As an administrator, I need dynamic UI for better user experience
**Priority:** P1
**Story Points:** 3

**Acceptance Criteria:**
- [ ] Page selector hidden when scope is "global"
- [ ] Page selector shown when scope is "specific_pages"
- [ ] Transition is smooth (no page reload)
- [ ] JavaScript loads only on tracking_script edit screen
- [ ] No JavaScript errors in console

**Tasks:**
- [ ] Create assets/js/admin.js (45 min)
- [ ] Implement scope selector change handler (30 min)
- [ ] Show/hide page selector based on scope (20 min)
- [ ] Enqueue script only on tracking_script pages (20 min)
- [ ] Test UI interactions (15 min)

**JavaScript Functionality:**
```javascript
jQuery(document).ready(function($) {
    // Show/hide page selector based on scope
    $('#gap_scope').on('change', function() {
        if ($(this).val() === 'specific_pages') {
            $('.gap-pages-selector').show();
        } else {
            $('.gap-pages-selector').hide();
        }
    }).trigger('change');
});
```

---

### US-02.5: As an administrator, I need styled admin interface
**Priority:** P2
**Story Points:** 2

**Acceptance Criteria:**
- [ ] Meta box has clear visual hierarchy
- [ ] Form fields properly spaced
- [ ] Labels aligned consistently
- [ ] Status indicators use appropriate colors
- [ ] Responsive layout for different screen sizes
- [ ] CSS loads only on tracking_script pages

**Tasks:**
- [ ] Create assets/css/admin.css (60 min)
- [ ] Style meta box layout (30 min)
- [ ] Style status indicators (15 min)
- [ ] Add responsive styles (15 min)
- [ ] Enqueue CSS only on tracking_script pages (10 min)
- [ ] Test on different screen sizes (15 min)

---

## Technical Tasks

### TT-02.1: Implement GAP_CPT Class
**Estimated Time:** 2 hours
**Assignee:** TBD
**File:** `includes/class-gap-cpt.php`

**Implementation Steps:**
1. Create class with singleton pattern
2. Define POST_TYPE constant
3. Implement register_post_type() method
4. Create get_labels() helper method
5. Implement add_custom_columns() filter
6. Implement render_custom_columns() action
7. Hook to WordPress init action

**Methods to Implement:**
- `get_instance()` - Singleton pattern
- `__construct()` - Register hooks
- `register_post_type()` - CPT registration
- `get_labels()` - Return array of labels
- `add_custom_columns()` - Modify column headers
- `render_custom_columns()` - Render column content

**Testing:**
- CPT appears in admin menu
- Only admins can access
- Custom columns display correctly
- All labels display properly

---

### TT-02.2: Implement GAP_Meta_Boxes Class
**Estimated Time:** 2.5 hours
**Assignee:** TBD
**File:** `includes/class-gap-meta-boxes.php`

**Implementation Steps:**
1. Create class with singleton pattern
2. Implement add_meta_boxes() action hook
3. Create render_script_config_meta_box() method
4. Implement save_meta_boxes() with full sanitization
5. Add enqueue_admin_assets() method
6. Hook to WordPress actions

**Methods to Implement:**
- `get_instance()` - Singleton pattern
- `__construct()` - Register hooks
- `add_meta_boxes()` - Register meta box
- `render_script_config_meta_box()` - Render HTML
- `save_meta_boxes()` - Save with sanitization
- `enqueue_admin_assets()` - Load CSS/JS

**Security Implementation:**
1. Nonce verification
2. Capability checks (manage_options)
3. Autosave prevention
4. Input sanitization:
   - wp_kses_post() for script content
   - sanitize_text_field() for placement/scope
   - array_map('absint', ...) for page IDs
5. Validation against allowed values

**Field Rendering:**
```php
// Script Content
<textarea name="gap_script_content" rows="10"><?php echo esc_textarea($script_content); ?></textarea>

// Placement
<select name="gap_placement">
    <option value="head">Head</option>
    <option value="body_top">Body Top</option>
    <option value="body_bottom">Body Bottom</option>
    <option value="footer">Footer</option>
</select>

// Scope
<select name="gap_scope" id="gap_scope">
    <option value="global">Global</option>
    <option value="specific_pages">Specific Pages</option>
</select>

// Target Pages (checkboxes)
<div class="gap-pages-selector">
    <?php foreach ($pages as $page): ?>
        <label>
            <input type="checkbox" name="gap_target_pages[]" value="<?php echo $page->ID; ?>">
            <?php echo esc_html($page->post_title); ?>
        </label>
    <?php endforeach; ?>
</div>

// Active Checkbox
<label>
    <input type="checkbox" name="gap_is_active" value="1" <?php checked($is_active, '1'); ?>>
    Active
</label>
```

---

### TT-02.3: Create Admin JavaScript
**Estimated Time:** 1 hour
**Assignee:** TBD
**File:** `assets/js/admin.js`

**Implementation Steps:**
1. Create admin.js file
2. Wrap in document ready handler
3. Implement scope selector change handler
4. Show/hide page selector based on selection
5. Trigger initial state on page load
6. Test all interactions

**Features:**
- Show/hide page selector based on scope
- Smooth transitions
- No page reload required
- Preserve user selections

---

### TT-02.4: Create Admin CSS
**Estimated Time:** 1 hour
**Assignee:** TBD
**File:** `assets/css/admin.css`

**Implementation Steps:**
1. Create admin.css file
2. Style meta box layout
3. Style form fields
4. Add status indicator styles
5. Add responsive breakpoints
6. Test on different screen sizes

**CSS Components:**
- Meta box layout and spacing
- Form field alignment
- Status indicators (colors, icons)
- Page selector checkbox grid
- Responsive adjustments

**Color Scheme:**
- Active status: #46b450 (green)
- Inactive status: #999 (gray)
- GA4 badge: #4285f4 (blue)
- GTM badge: #ff6f00 (orange)

---

## Definition of Done

- [ ] GAP_CPT class fully implemented
- [ ] GAP_Meta_Boxes class fully implemented
- [ ] Custom post type registered and visible
- [ ] All meta fields render correctly
- [ ] All meta fields save correctly
- [ ] Nonce verification working
- [ ] Capability checks in place
- [ ] Input sanitization implemented
- [ ] Custom admin columns display correctly
- [ ] JavaScript for dynamic UI working
- [ ] Admin CSS applied correctly
- [ ] All code follows WordPress coding standards
- [ ] Inline documentation complete
- [ ] Manual testing completed
- [ ] Code review completed
- [ ] Committed to version control

---

## Dependencies

**Upstream Dependencies:**
- EPIC-01: Foundation (requires autoloader, initialization)

**Downstream Dependencies:**
- EPIC-03: Conflict Detection (meta fields will trigger ID extraction)
- EPIC-04: Frontend Output (meta fields control script output)

---

## Testing Requirements

### Manual Testing Checklist

#### CPT Registration
- [ ] Tracking Scripts menu appears in admin
- [ ] Menu icon is analytics dashicon
- [ ] Non-admin users cannot access
- [ ] Admin users can access
- [ ] "Add New" button works
- [ ] Post list displays correctly

#### Meta Fields
- [ ] Can create new tracking script
- [ ] Script content field accepts GA4 code
- [ ] Script content field accepts GTM code
- [ ] Placement dropdown has 4 options
- [ ] Scope dropdown has 2 options
- [ ] Active checkbox works
- [ ] All fields save on publish
- [ ] All fields persist after save

#### Page Selector
- [ ] Hidden when scope is "global"
- [ ] Shown when scope is "specific_pages"
- [ ] Lists all published pages
- [ ] Can select multiple pages
- [ ] Selected pages persist after save
- [ ] Shows correct count in admin column

#### Custom Admin Columns
- [ ] Tracking IDs column (empty for now)
- [ ] Placement column shows selected placement
- [ ] Scope column shows selected scope
- [ ] Target Pages column shows count or "—"
- [ ] Status column shows active/inactive

#### JavaScript Functionality
- [ ] Scope selector triggers show/hide
- [ ] Transition is smooth
- [ ] No JavaScript errors in console
- [ ] Works after form validation errors
- [ ] Initial state correct on page load

#### Security Testing
- [ ] Nonce verification blocks tampering
- [ ] Non-admin cannot save (capability check)
- [ ] Script tags allowed in content field (wp_kses_post)
- [ ] Malicious HTML stripped from fields
- [ ] Direct access to meta boxes file blocked

---

## WordPress Coding Standards Compliance

### Class Structure
- [ ] Singleton pattern implemented correctly
- [ ] Private constructor
- [ ] Static get_instance() method
- [ ] Proper hook registration in constructor

### Method Documentation
- [ ] All methods have docblocks
- [ ] Parameters documented with @param
- [ ] Return values documented with @return
- [ ] Complex logic has inline comments

### Naming Conventions
- [ ] Class: GAP_CPT, GAP_Meta_Boxes
- [ ] Methods: snake_case
- [ ] Constants: UPPER_CASE
- [ ] Meta keys: _gap_field_name

### Security
- [ ] Nonce field: wp_nonce_field()
- [ ] Nonce verification: wp_verify_nonce()
- [ ] Capability check: current_user_can()
- [ ] Input sanitization: wp_kses_post(), sanitize_text_field()
- [ ] Output escaping: esc_html(), esc_attr(), esc_textarea()

---

## Sanitization & Validation Reference

### Script Content Field
```php
$content = wp_kses_post(wp_unslash($_POST['gap_script_content']));
```
- Allows safe HTML and scripts
- Strips dangerous attributes
- Prevents XSS attacks

### Placement Field
```php
$placement = sanitize_text_field($_POST['gap_placement']);
$allowed = array('head', 'body_top', 'body_bottom', 'footer');
if (in_array($placement, $allowed, true)) {
    update_post_meta($post_id, '_gap_placement', $placement);
}
```
- Sanitizes input
- Validates against whitelist
- Strict comparison with in_array

### Scope Field
```php
$scope = sanitize_text_field($_POST['gap_scope']);
$allowed = array('global', 'specific_pages');
if (in_array($scope, $allowed, true)) {
    update_post_meta($post_id, '_gap_scope', $scope);
}
```

### Target Pages Field
```php
$target_pages = array();
if (isset($_POST['gap_target_pages']) && is_array($_POST['gap_target_pages'])) {
    $target_pages = array_map('absint', $_POST['gap_target_pages']);
}
update_post_meta($post_id, '_gap_target_pages', $target_pages);
```
- Converts all values to positive integers
- Prevents SQL injection
- Validates page IDs exist (optional)

### Active Checkbox
```php
$is_active = isset($_POST['gap_is_active']) ? '1' : '0';
update_post_meta($post_id, '_gap_is_active', $is_active);
```

---

## Risks & Mitigations

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Script content sanitization too strict | High | Medium | Use wp_kses_post() which allows scripts |
| Page selector with 1000+ pages | Medium | Low | Add pagination or search functionality |
| Capability conflicts with other plugins | Low | Low | Use standard manage_options capability |
| JavaScript conflicts | Medium | Low | Namespace all JS code, use jQuery no-conflict |
| Meta box UI too complex | Medium | Low | Keep UI simple, add help text |

---

## Notes

- Script content field must allow `<script>` tags for GA/GTM code
- wp_kses_post() is the right balance for sanitization
- Page selector may need optimization for sites with many pages
- Consider adding "Select All" / "Deselect All" for page selector
- Custom columns will show "None detected" until EPIC-03 implemented
- Active checkbox defaults to unchecked (inactive) for safety

---

## Performance Considerations

- Meta box only loads on tracking_script edit screen
- CSS/JS only enqueued when needed (hook check)
- Page selector query uses get_posts() with minimal fields
- Custom column queries use get_post_meta() (already cached)

---

## Accessibility Considerations

- All form fields have associated labels
- Checkboxes have visible labels
- Color not sole indicator for status (use icons too)
- Keyboard navigation supported
- Screen reader friendly labels

---

## Related Documents

- [WordPress register_post_type()](https://developer.wordpress.org/reference/functions/register_post_type/)
- [WordPress Meta Boxes](https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/)
- [Data Sanitization](https://developer.wordpress.org/apis/security/sanitizing-securing-output/)
- [GA Plugin Planning Document](../GA-PLUGIN-PLAN.md#phase-2-custom-post-type)

---

**Created:** 2025-01-16
**Last Updated:** 2025-01-16
**Epic Owner:** TBD
**Status:** Ready for Development
