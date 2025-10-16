# EPIC-04: Frontend Script Output

**Epic ID:** EPIC-04
**Epic Name:** Frontend Script Output
**Status:** Not Started
**Priority:** P0 (Critical)
**Estimated Time:** 3-4 hours
**Dependencies:** EPIC-01 (Foundation), EPIC-02 (Admin Interface), EPIC-03 (Conflict Detection)

---

## Overview

Implement the frontend script output system that injects Google Analytics tracking scripts into the correct DOM locations (head, body top, body bottom, footer) based on configuration, respects scope settings (global vs. specific pages), and prevents duplicate tracking by scanning existing page HTML before output.

This epic brings together all previous work to deliver the core functionality of the plugin.

## Objectives

1. Query active tracking scripts efficiently with caching
2. Output scripts at correct placement locations using WordPress hooks
3. Filter scripts by scope (global or page-specific)
4. Scan page HTML for duplicate tracking IDs before output
5. Skip duplicate scripts with explanatory HTML comments
6. Log conflicts for debugging
7. Ensure minimal performance impact

## Success Criteria

- [ ] Scripts output in correct DOM location (head, body, footer)
- [ ] Global scripts appear on all pages
- [ ] Page-specific scripts only on selected pages
- [ ] Inactive scripts never output
- [ ] Duplicate tracking IDs detected and prevented
- [ ] HTML comments indicate plugin activity
- [ ] Page load impact < 50ms
- [ ] Database queries ≤ 2 per page load

---

## User Stories

### US-04.1: As a site visitor, I need tracking scripts loaded in the correct location
**Priority:** P0
**Story Points:** 5

**Acceptance Criteria:**
- [ ] Scripts with "head" placement output in wp_head
- [ ] Scripts with "body_top" placement output in wp_body_open
- [ ] Scripts with "body_bottom" placement output in wp_footer (priority 1)
- [ ] Scripts with "footer" placement output in wp_footer (priority 999)
- [ ] HTML comments mark plugin output sections
- [ ] Scripts execute without JavaScript errors

**Tasks:**
- [ ] Create GAP_Frontend class skeleton (20 min)
- [ ] Hook wp_head for head placement (15 min)
- [ ] Hook wp_body_open for body_top placement (15 min)
- [ ] Hook wp_footer for body_bottom placement (15 min)
- [ ] Hook wp_footer for footer placement (15 min)
- [ ] Add HTML comment markers (10 min)
- [ ] Test script output in each location (30 min)

**WordPress Hooks:**
```php
add_action('wp_head', array($this, 'output_head_scripts'), 1);
add_action('wp_body_open', array($this, 'output_body_top_scripts'), 1);
add_action('wp_footer', array($this, 'output_body_bottom_scripts'), 1);
add_action('wp_footer', array($this, 'output_footer_scripts'), 999);
```

---

### US-04.2: As a developer, I need efficient database queries for script retrieval
**Priority:** P0
**Story Points:** 5

**Acceptance Criteria:**
- [ ] Single query retrieves scripts by placement and active status
- [ ] Results cached at request level (no duplicate queries)
- [ ] Query uses meta_query for efficient filtering
- [ ] Scope filtering happens in PHP (not SQL)
- [ ] Maximum 1 query per placement location

**Tasks:**
- [ ] Implement get_active_scripts() method (75 min)
- [ ] Add request-level caching with $scripts_cache property (20 min)
- [ ] Build meta_query for placement and active status (25 min)
- [ ] Implement scope filtering logic (30 min)
- [ ] Test query efficiency (20 min)

**Query Structure:**
```php
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
```

---

### US-04.3: As an administrator, I need scripts to respect scope settings
**Priority:** P0
**Story Points:** 3

**Acceptance Criteria:**
- [ ] Global scripts output on all pages
- [ ] Specific page scripts only on selected pages
- [ ] Scope checked using get_the_ID()
- [ ] No scripts output on non-singular pages (archives, search) unless global
- [ ] Target pages array compared with strict comparison

**Tasks:**
- [ ] Get current page ID with get_the_ID() (10 min)
- [ ] Filter global scripts (always included) (15 min)
- [ ] Filter specific_pages scripts (check page ID) (30 min)
- [ ] Test on homepage (20 min)
- [ ] Test on specific page (20 min)
- [ ] Test on archive page (15 min)

**Scope Filtering Logic:**
```php
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
```

---

### US-04.4: As a developer, I need duplicate tracking prevention on frontend
**Priority:** P0
**Story Points:** 8

**Acceptance Criteria:**
- [ ] Page HTML scanned before script output
- [ ] Tracking IDs extracted from current script
- [ ] Conflict_Detector->scan_page_html() called
- [ ] Duplicate scripts skipped entirely
- [ ] HTML comment explains why script skipped
- [ ] Conflict logged to debug log
- [ ] Scans all DOM sections (cumulative HTML)

**Tasks:**
- [ ] Get extracted IDs from post meta (10 min)
- [ ] Capture current page HTML with output buffering (45 min)
- [ ] Call scan_page_html() with combined HTML (20 min)
- [ ] Skip script output if duplicates found (15 min)
- [ ] Output HTML comment explaining skip (20 min)
- [ ] Call log_conflict() for debugging (10 min)
- [ ] Test with theme GA script (30 min)
- [ ] Test with multiple plugin scripts (20 min)

**Duplicate Detection Flow:**
```php
// Get extracted IDs
$extracted_ids = get_post_meta($script->ID, '_gap_extracted_ids', true);

if (!empty($extracted_ids) && is_array($extracted_ids)) {
    // Get current page HTML
    $current_html = ob_get_contents();
    $full_page_html = $this->get_current_page_html();
    $combined_html = $current_html . $full_page_html;

    // Scan for duplicates
    $detector = GAP_Conflict_Detector::get_instance();
    $found_ids = $detector->scan_page_html($combined_html, $extracted_ids);

    if (!empty($found_ids)) {
        // Skip script, log conflict
        echo "<!-- GAP: Duplicate detected, skipping... -->\n";
        $detector->log_conflict($conflict_msg);
        continue;
    }
}

// No duplicates, output script
echo $content . "\n";
```

---

### US-04.5: As a developer, I need reusable output methods following DRY
**Priority:** P1
**Story Points:** 3

**Acceptance Criteria:**
- [ ] Single output_scripts() method handles all placements
- [ ] Placement-specific methods call output_scripts()
- [ ] No duplicate query logic
- [ ] No duplicate output logic
- [ ] Code is maintainable and testable

**Tasks:**
- [ ] Create output_scripts($placement) method (60 min)
- [ ] Create output_head_scripts() wrapper (5 min)
- [ ] Create output_body_top_scripts() wrapper (5 min)
- [ ] Create output_body_bottom_scripts() wrapper (5 min)
- [ ] Create output_footer_scripts() wrapper (5 min)
- [ ] Test all placements (20 min)

**DRY Implementation:**
```php
private function output_scripts($placement) {
    // Unified output logic
}

public function output_head_scripts() {
    $this->output_scripts('head');
}

public function output_body_top_scripts() {
    $this->output_scripts('body_top');
}
// etc.
```

---

## Technical Tasks

### TT-04.1: Implement GAP_Frontend Class
**Estimated Time:** 3 hours
**Assignee:** TBD
**File:** `includes/class-gap-frontend.php`

**Implementation Steps:**
1. Create class with singleton pattern
2. Add private $scripts_cache property
3. Implement get_active_scripts($placement) method
4. Implement output_scripts($placement) method
5. Implement get_current_page_html() helper method
6. Create placement-specific wrapper methods
7. Register WordPress action hooks

**Class Structure:**
```php
class GAP_Frontend {
    private static $instance = null;
    private $scripts_cache = array();

    public static function get_instance() { }
    private function __construct() { }
    private function get_active_scripts($placement) { }
    private function output_scripts($placement) { }
    private function get_current_page_html() { }
    public function output_head_scripts() { }
    public function output_body_top_scripts() { }
    public function output_body_bottom_scripts() { }
    public function output_footer_scripts() { }
}
```

---

### TT-04.2: Implement Output Buffering for HTML Scanning
**Estimated Time:** 1 hour
**Assignee:** TBD

**Implementation Steps:**
1. Start output buffering at beginning of output_scripts()
2. Capture buffer contents before each script output
3. Combine with get_current_page_html() for full context
4. Scan combined HTML for duplicate IDs
5. End buffering and flush after all scripts processed

**Output Buffer Usage:**
```php
private function output_scripts($placement) {
    $scripts = $this->get_active_scripts($placement);

    if (empty($scripts)) {
        return;
    }

    echo "\n<!-- GA Plugin: {$placement} -->\n";

    ob_start();

    foreach ($scripts as $script) {
        // Get current buffer
        $current_html = ob_get_contents();

        // Duplicate detection here

        // Output script
        echo $content . "\n";
    }

    ob_end_flush();

    echo "<!-- /GA Plugin: {$placement} -->\n\n";
}
```

---

### TT-04.3: Implement Page HTML Capture
**Estimated Time:** 30 minutes
**Assignee:** TBD

**Implementation Steps:**
1. Create get_current_page_html() method
2. Use ob_get_level() to determine buffer depth
3. Loop through all active buffers
4. Capture contents of each buffer
5. Return combined HTML string

**get_current_page_html() Implementation:**
```php
private function get_current_page_html() {
    $level = ob_get_level();
    $html = '';

    for ($i = 0; $i < $level; $i++) {
        $html .= ob_get_contents();
    }

    return $html;
}
```

**Note:** This captures all output buffers to get the full page HTML rendered so far, enabling detection of scripts added by theme or other plugins.

---

## Definition of Done

- [ ] GAP_Frontend class fully implemented
- [ ] All WordPress action hooks registered
- [ ] Scripts output in correct placements
- [ ] Query caching implemented and working
- [ ] Scope filtering working correctly
- [ ] Duplicate detection prevents double-tracking
- [ ] HTML comments added for debugging
- [ ] Conflict logging functional
- [ ] All code follows WordPress coding standards
- [ ] Inline documentation complete
- [ ] Manual testing on live site
- [ ] Performance testing completed
- [ ] Code review completed
- [ ] Committed to version control

---

## Dependencies

**Upstream Dependencies:**
- EPIC-01: Foundation (requires autoloader, initialization)
- EPIC-02: Admin Interface (requires meta fields to be saved)
- EPIC-03: Conflict Detection (uses scan_page_html, log_conflict)

**Downstream Dependencies:**
- EPIC-05: Testing & Launch (final testing and validation)

---

## Testing Requirements

### Manual Testing Checklist

#### Script Output by Placement
- [ ] Create script with "head" placement
- [ ] View page source, verify script in `<head>`
- [ ] Create script with "body_top" placement
- [ ] View page source, verify script after `<body>`
- [ ] Create script with "body_bottom" placement
- [ ] View page source, verify script before `</body>`
- [ ] Create script with "footer" placement
- [ ] View page source, verify script at very end

#### Scope Testing
- [ ] Create global script
- [ ] Verify appears on homepage
- [ ] Verify appears on page
- [ ] Verify appears on post
- [ ] Create page-specific script (select one page)
- [ ] Verify appears only on selected page
- [ ] Verify NOT on other pages
- [ ] Verify NOT on homepage

#### Active/Inactive Testing
- [ ] Create active script
- [ ] Verify outputs on frontend
- [ ] Set script to inactive
- [ ] Verify does NOT output
- [ ] Reactivate script
- [ ] Verify outputs again

#### Duplicate Detection Testing
- [ ] Add GA4 script manually to theme header.php
- [ ] Create plugin script with same GA4 ID
- [ ] Load page
- [ ] Verify plugin script skipped (HTML comment present)
- [ ] Check debug.log for conflict message
- [ ] Remove theme script
- [ ] Verify plugin script now outputs

#### Multiple Scripts Testing
- [ ] Create 3 scripts with different placements
- [ ] All set to global, all active
- [ ] Verify all 3 output in correct locations
- [ ] Verify scripts execute without errors
- [ ] Check browser console for JS errors

---

### Performance Testing

#### Database Query Count
- [ ] Install Query Monitor plugin
- [ ] Load homepage
- [ ] Check query count (should be ≤ 2 queries)
- [ ] Load page with 4 script placements
- [ ] Verify queries don't increase (caching works)

#### Page Load Time Impact
- [ ] Measure baseline page load (no plugin)
- [ ] Activate plugin with 4 scripts
- [ ] Measure new page load time
- [ ] Calculate difference
- [ ] Verify impact < 50ms

**Testing Tools:**
- Query Monitor (WordPress plugin)
- Browser DevTools Network tab
- WebPageTest.org
- GTmetrix

---

### Browser Testing

**Test in:**
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)

**Verify:**
- [ ] Scripts load without errors
- [ ] Console shows no JavaScript errors
- [ ] Network tab shows GA/GTM requests firing
- [ ] Tracking data appears in GA4 dashboard

---

## WordPress Query Best Practices

### Using get_posts()
- More efficient than WP_Query for simple queries
- Automatically suppresses filters
- Returns array of post objects

### Meta Query Structure
```php
'meta_query' => array(
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
)
```

### Caching Strategy
```php
// Check cache first
if (isset($this->scripts_cache[$placement])) {
    return $this->scripts_cache[$placement];
}

// Query and cache
$scripts = get_posts($args);
$this->scripts_cache[$placement] = $filtered_scripts;

return $filtered_scripts;
```

---

## Output Buffer Considerations

### Why Output Buffering?
- Captures HTML generated so far by WordPress
- Enables scanning for existing tracking scripts
- Allows detection of theme/plugin scripts
- Critical for duplicate prevention

### Output Buffer Levels
- WordPress uses multiple buffer levels
- Theme may start buffers
- Plugins may start buffers
- Need to check all levels for complete HTML

### Potential Issues
- Some themes don't use wp_body_open hook
- Fallback: Use wp_footer with early priority
- Document theme compatibility issues

---

## WordPress Coding Standards Compliance

### Hook Priority Best Practices
- Early priority (1) for head and body_top
- Late priority (999) for footer
- Ensures scripts load before/after other plugins

### Output Escaping
- Script content intentionally NOT escaped
- Already sanitized during save (wp_kses_post)
- Must execute as-is for tracking to work
- HTML comments ARE escaped (esc_html)

### Commenting
- HTML comments indicate plugin sections
- Helps debugging in page source
- Format: `<!-- GA Plugin: {placement} -->`
- End marker: `<!-- /GA Plugin: {placement} -->`

---

## Risks & Mitigations

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Theme doesn't support wp_body_open | Medium | Medium | Fallback to wp_footer with early priority |
| Output buffering conflicts | High | Low | Test with common plugins, use unique buffer |
| Performance degradation | High | Low | Implement caching, limit queries |
| Script execution order issues | Medium | Medium | Use appropriate hook priorities |
| Duplicate detection false positives | High | Low | Test extensively with real GA/GTM code |

---

## Performance Optimization

### Request-Level Caching
- Cache scripts per placement in class property
- Prevents duplicate database queries
- Cleared automatically at end of request

### Efficient Querying
- Use meta_query instead of custom SQL
- Query only published, active scripts
- Order by menu_order for predictable output

### Minimal HTML Scanning
- Only scan when script has extracted IDs
- Use fast stripos() function
- Limit scan scope to existing page content

---

## Security Considerations

### Script Output Safety
- Scripts already sanitized during save
- Use wp_kses_post() in Meta_Boxes
- Only admins can create/edit scripts
- Direct echo on frontend is intentional

### No SQL Injection
- Using get_posts() (parameterized internally)
- No direct $wpdb queries
- All meta values retrieved safely

### XSS Prevention
- HTML comments use esc_html()
- Conflict messages use esc_html()
- Script content exempt (must execute)

---

## Future Enhancements (Post-MVP)

- Conditional logic builder (advanced targeting)
- Device-type targeting (mobile, desktop, tablet)
- User role targeting (logged in, logged out)
- Custom JavaScript events for script injection
- Performance dashboard in admin
- A/B testing support

---

## Related Documents

- [WordPress Action Hooks Reference](https://developer.wordpress.org/reference/hooks/)
- [wp_head Hook](https://developer.wordpress.org/reference/hooks/wp_head/)
- [wp_body_open Hook](https://developer.wordpress.org/reference/hooks/wp_body_open/)
- [wp_footer Hook](https://developer.wordpress.org/reference/hooks/wp_footer/)
- [WP_Query Documentation](https://developer.wordpress.org/reference/classes/wp_query/)
- [GA Plugin Planning Document](../GA-PLUGIN-PLAN.md#phase-4-frontend-output)

---

**Created:** 2025-01-16
**Last Updated:** 2025-01-16
**Epic Owner:** TBD
**Status:** Ready for Development
