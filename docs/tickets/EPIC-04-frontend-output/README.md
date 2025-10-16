# EPIC-04: Frontend Script Output - Ticket Breakdown

**Epic ID:** EPIC-04
**Epic Name:** Frontend Script Output
**Status:** Not Started
**Priority:** P0 (Critical)
**Total Estimated Time:** 4.5-5 hours
**Dependencies:** EPIC-01 (Foundation), EPIC-02 (Admin Interface), EPIC-03 (Conflict Detection)

---

## Overview

This epic implements the frontend script output system that injects Google Analytics tracking scripts into the correct DOM locations (head, body top, body bottom, footer) based on configuration, respects scope settings (global vs. specific pages), and prevents duplicate tracking by scanning existing page HTML before output.

This epic brings together all previous work to deliver the core functionality of the plugin.

---

## Epic Objectives

1. Query active tracking scripts efficiently with caching
2. Output scripts at correct placement locations using WordPress hooks
3. Filter scripts by scope (global or page-specific)
4. Scan page HTML for duplicate tracking IDs before output
5. Skip duplicate scripts with explanatory HTML comments
6. Log conflicts for debugging
7. Ensure minimal performance impact

---

## Tickets Summary

### User Stories (5 tickets)

| Ticket ID | Title | Priority | Story Points | Status |
|-----------|-------|----------|--------------|--------|
| US-04.1 | Output scripts in correct DOM location | P0 | 5 | Not Started |
| US-04.2 | Implement efficient database queries | P0 | 5 | Not Started |
| US-04.3 | Respect scope settings (global vs. specific) | P0 | 3 | Not Started |
| US-04.4 | Prevent duplicate tracking on frontend | P0 | 8 | Not Started |
| US-04.5 | Create reusable output methods (DRY) | P1 | 3 | Not Started |

**Total Story Points:** 24

### Technical Tasks (3 tickets)

| Ticket ID | Title | Estimated Time | Status |
|-----------|-------|----------------|--------|
| TT-04.1 | Implement GAP_Frontend Class | 3 hours | Not Started |
| TT-04.2 | Implement Output Buffering for HTML Scanning | 1 hour | Not Started |
| TT-04.3 | Implement Page HTML Capture | 30 minutes | Not Started |

**Total Estimated Time:** 4.5 hours

---

## User Story Details

### US-04.1: Output Scripts in Correct DOM Location
**File:** [user-stories/us-04.1-correct-placement.md](./user-stories/us-04.1-correct-placement.md)
**Priority:** P0 | **Story Points:** 5

Implement WordPress hooks to output tracking scripts in the correct DOM locations.

**Key Deliverables:**
- Scripts with "head" placement output in wp_head
- Scripts with "body_top" placement output in wp_body_open
- Scripts with "body_bottom" placement output in wp_footer (priority 1)
- Scripts with "footer" placement output in wp_footer (priority 999)
- HTML comments mark plugin output sections
- Scripts execute without JavaScript errors

**WordPress Hooks:**
```php
add_action('wp_head', array($this, 'output_head_scripts'), 1);
add_action('wp_body_open', array($this, 'output_body_top_scripts'), 1);
add_action('wp_footer', array($this, 'output_body_bottom_scripts'), 1);
add_action('wp_footer', array($this, 'output_footer_scripts'), 999);
```

---

### US-04.2: Implement Efficient Database Queries
**File:** [user-stories/us-04.2-efficient-queries.md](./user-stories/us-04.2-efficient-queries.md)
**Priority:** P0 | **Story Points:** 5

Implement efficient database queries with request-level caching.

**Key Deliverables:**
- Single query retrieves scripts by placement and active status
- Results cached at request level (no duplicate queries)
- Query uses meta_query for efficient filtering
- Scope filtering happens in PHP (not SQL)
- Maximum 1 query per placement location

---

### US-04.3: Respect Scope Settings
**File:** [user-stories/us-04.3-scope-filtering.md](./user-stories/us-04.3-scope-filtering.md)
**Priority:** P0 | **Story Points:** 3

Filter scripts by scope (global or page-specific targeting).

**Key Deliverables:**
- Global scripts output on all pages
- Specific page scripts only on selected pages
- Scope checked using get_the_ID()
- No scripts output on non-singular pages (archives, search) unless global
- Target pages array compared with strict comparison

---

### US-04.4: Prevent Duplicate Tracking on Frontend
**File:** [user-stories/us-04.4-duplicate-prevention.md](./user-stories/us-04.4-duplicate-prevention.md)
**Priority:** P0 | **Story Points:** 8

Scan page HTML for duplicate tracking IDs and skip duplicate scripts.

**Key Deliverables:**
- Page HTML scanned before script output
- Tracking IDs extracted from current script
- Conflict_Detector->scan_page_html() called
- Duplicate scripts skipped entirely
- HTML comment explains why script skipped
- Conflict logged to debug log
- Scans all DOM sections (cumulative HTML)

---

### US-04.5: Create Reusable Output Methods (DRY)
**File:** [user-stories/us-04.5-dry-methods.md](./user-stories/us-04.5-dry-methods.md)
**Priority:** P1 | **Story Points:** 3

Implement DRY principle with reusable output methods.

**Key Deliverables:**
- Single output_scripts() method handles all placements
- Placement-specific methods call output_scripts()
- No duplicate query logic
- No duplicate output logic
- Code is maintainable and testable

---

## Technical Task Details

### TT-04.1: Implement GAP_Frontend Class
**File:** [technical-tasks/tt-04.1-frontend-class.md](./technical-tasks/tt-04.1-frontend-class.md)
**Estimated Time:** 3 hours
**Assignee:** TBD

Create the main frontend output class using singleton pattern.

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
**File:** [technical-tasks/tt-04.2-output-buffering.md](./technical-tasks/tt-04.2-output-buffering.md)
**Estimated Time:** 1 hour
**Assignee:** TBD

Implement output buffering to capture page HTML for duplicate scanning.

**Implementation Steps:**
1. Start output buffering at beginning of output_scripts()
2. Capture buffer contents before each script output
3. Combine with get_current_page_html() for full context
4. Scan combined HTML for duplicate IDs
5. End buffering and flush after all scripts processed

---

### TT-04.3: Implement Page HTML Capture
**File:** [technical-tasks/tt-04.3-html-capture.md](./technical-tasks/tt-04.3-html-capture.md)
**Estimated Time:** 30 minutes
**Assignee:** TBD

Capture current page HTML from all output buffers.

**Implementation Steps:**
1. Create get_current_page_html() method
2. Use ob_get_level() to determine buffer depth
3. Loop through all active buffers
4. Capture contents of each buffer
5. Return combined HTML string

---

## Dependencies

### Upstream Dependencies
- **EPIC-01:** Foundation (requires autoloader, initialization)
- **EPIC-02:** Admin Interface (requires meta fields to be saved)
- **EPIC-03:** Conflict Detection (uses scan_page_html, log_conflict)

### Downstream Dependencies
- **EPIC-05:** Testing & Launch (final testing and validation)

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

---

### Browser Testing
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

## Quick Links

### User Stories
- [US-04.1: Correct Placement](./user-stories/us-04.1-correct-placement.md)
- [US-04.2: Efficient Queries](./user-stories/us-04.2-efficient-queries.md)
- [US-04.3: Scope Filtering](./user-stories/us-04.3-scope-filtering.md)
- [US-04.4: Duplicate Prevention](./user-stories/us-04.4-duplicate-prevention.md)
- [US-04.5: DRY Methods](./user-stories/us-04.5-dry-methods.md)

### Technical Tasks
- [TT-04.1: Frontend Class](./technical-tasks/tt-04.1-frontend-class.md)
- [TT-04.2: Output Buffering](./technical-tasks/tt-04.2-output-buffering.md)
- [TT-04.3: HTML Capture](./technical-tasks/tt-04.3-html-capture.md)

### Related Documents
- [EPIC-04 Main Document](./EPIC.md)
- [WordPress Action Hooks Reference](https://developer.wordpress.org/reference/hooks/)
- [WP_Query Documentation](https://developer.wordpress.org/reference/classes/wp_query/)

---

**Created:** 2025-10-16
**Last Updated:** 2025-10-16
**Epic Owner:** TBD
**Status:** Ready for Development
