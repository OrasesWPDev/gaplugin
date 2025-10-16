# EPIC-03: Conflict Detection System

**Epic ID:** EPIC-03
**Epic Name:** Conflict Detection System
**Status:** Not Started
**Priority:** P0 (Critical)
**Estimated Time:** 2-3 hours
**Dependencies:** EPIC-01 (Foundation), EPIC-02 (Admin Interface)

---

## Overview

Implement the duplicate detection and conflict prevention system that automatically extracts Google Analytics tracking IDs (GA4 and GTM) from script content, detects duplicates across tracking script posts, scans page HTML for existing scripts, and prevents double-tracking on the frontend.

This is a **key differentiator** for the GA Plugin and addresses the core client requirement for preventing duplicate tracking.

## Objectives

1. Extract GA4 and GTM tracking IDs from script content using regex
2. Detect duplicate tracking IDs across all tracking script posts
3. Store extracted IDs in post meta for display and comparison
4. Provide conflict reporting in admin interface
5. Enable HTML scanning for duplicate detection on frontend
6. Implement conflict logging for debugging

## Success Criteria

- [ ] GA4 IDs (G-XXXXXXXXXX) extracted correctly
- [ ] GTM IDs (GTM-XXXXXXX) extracted correctly
- [ ] Extracted IDs stored in _gap_extracted_ids meta field
- [ ] Duplicate IDs detected across multiple posts
- [ ] Admin warnings display when duplicates found
- [ ] HTML scanning detects existing tracking scripts
- [ ] Conflicts logged to WordPress debug log
- [ ] Tracking IDs display in admin columns

---

## User Stories

### US-03.1: As a developer, I need to extract tracking IDs from script content
**Priority:** P0
**Story Points:** 5

**Acceptance Criteria:**
- [ ] Regex pattern correctly matches GA4 IDs (G-XXXXXXXXXX)
- [ ] Regex pattern correctly matches GTM IDs (GTM-XXXXXXX)
- [ ] extract_tracking_ids() returns array of ID objects
- [ ] Each ID object contains: id, type, name
- [ ] Function handles multiple IDs in single script
- [ ] Function returns empty array if no IDs found

**Tasks:**
- [ ] Create GAP_Conflict_Detector class skeleton (20 min)
- [ ] Implement extract_tracking_ids() method (60 min)
- [ ] Write regex pattern for GA4 IDs (20 min)
- [ ] Write regex pattern for GTM IDs (20 min)
- [ ] Test with sample GA4 code (15 min)
- [ ] Test with sample GTM code (15 min)
- [ ] Test with mixed GA4+GTM code (10 min)

**Regex Patterns:**
```php
// GA4: Matches G-XXXXXXXXXX (10 alphanumeric characters)
/['\"]G-[A-Z0-9]{10}['\""]/

// GTM: Matches GTM-XXXXXXX (7 alphanumeric characters)
/['\"]GTM-[A-Z0-9]{7}['\""]/
```

**Return Format:**
```php
[
    [
        'id' => 'G-ABC1234567',
        'type' => 'ga4',
        'name' => 'Google Analytics 4'
    ],
    [
        'id' => 'GTM-ABC1234',
        'type' => 'gtm',
        'name' => 'Google Tag Manager'
    ]
]
```

---

### US-03.2: As an administrator, I need tracking IDs automatically extracted when I save a script
**Priority:** P0
**Story Points:** 3

**Acceptance Criteria:**
- [ ] IDs extracted on every save_post_tracking_script action
- [ ] Extracted IDs stored in _gap_extracted_ids post meta
- [ ] Content hash stored in _gap_unique_hash post meta
- [ ] Extraction happens before meta boxes save completes
- [ ] Multiple saves update IDs correctly

**Tasks:**
- [ ] Hook into save_post_tracking_script in Meta_Boxes class (15 min)
- [ ] Call extract_tracking_ids() with script content (10 min)
- [ ] Store extracted IDs in post meta (15 min)
- [ ] Generate and store MD5 hash of content (10 min)
- [ ] Test extraction on save (15 min)

**Integration Point (in GAP_Meta_Boxes):**
```php
// After saving script content
$content = wp_kses_post(wp_unslash($_POST['gap_script_content']));
update_post_meta($post_id, '_gap_script_content', $content);

// Extract tracking IDs
$detector = GAP_Conflict_Detector::get_instance();
$extracted_ids = $detector->extract_tracking_ids($content);
update_post_meta($post_id, '_gap_extracted_ids', $extracted_ids);

// Generate unique hash
$unique_hash = md5($content);
update_post_meta($post_id, '_gap_unique_hash', $unique_hash);
```

---

### US-03.3: As an administrator, I need to see extracted tracking IDs in admin columns
**Priority:** P1
**Story Points:** 2

**Acceptance Criteria:**
- [ ] Tracking IDs column displays extracted IDs
- [ ] Each ID shows type badge (GA4 or GTM)
- [ ] Multiple IDs displayed on separate lines
- [ ] "None detected" shown if no IDs found
- [ ] IDs are color-coded by type

**Tasks:**
- [ ] Update render_custom_columns() in GAP_CPT (30 min)
- [ ] Add CSS for ID type badges (20 min)
- [ ] Test with various ID combinations (15 min)

**Display Format:**
```
G-ABC1234567 (Google Analytics 4)
GTM-XYZ7890 (Google Tag Manager)
```

---

### US-03.4: As an administrator, I need warnings when duplicate tracking IDs are detected
**Priority:** P0
**Story Points:** 5

**Acceptance Criteria:**
- [ ] check_global_conflicts() scans all published tracking scripts
- [ ] Detects when same tracking ID used in multiple posts
- [ ] Admin notice displays on tracking_script admin pages
- [ ] Notice lists all posts using duplicate IDs
- [ ] Notice includes edit links for each post
- [ ] Recommendation provided to fix conflicts

**Tasks:**
- [ ] Implement check_global_conflicts() method (75 min)
- [ ] Create admin notice in GAP_Admin class (45 min)
- [ ] Hook admin_init to run conflict check (10 min)
- [ ] Add CSS for error notice styling (15 min)
- [ ] Test with duplicate IDs (20 min)

**check_global_conflicts() Logic:**
1. Query all published tracking_script posts
2. Loop through and get _gap_extracted_ids for each
3. Build map: tracking_id => array of post IDs using it
4. Find tracking IDs used by 2+ posts
5. Return array of conflicts

**Admin Notice Format:**
```
⚠️ Duplicate Tracking IDs Detected!

The following tracking IDs are used in multiple tracking scripts:

• G-ABC1234567 (Google Analytics 4) is used in:
  - Homepage GA4 Tracking (edit)
  - Global GA4 Script (edit)

Recommendation: Each tracking script should use a unique tracking ID.
```

---

### US-03.5: As a developer, I need to scan page HTML for existing tracking scripts
**Priority:** P0
**Story Points:** 3

**Acceptance Criteria:**
- [ ] scan_page_html() accepts HTML string and tracking IDs
- [ ] Searches HTML for each tracking ID (case-insensitive)
- [ ] Returns array of found IDs
- [ ] Handles large HTML strings efficiently
- [ ] Works across all DOM sections (head, body, footer)

**Tasks:**
- [ ] Implement scan_page_html() method (45 min)
- [ ] Use stripos() for case-insensitive search (15 min)
- [ ] Test with sample HTML containing GA4 (15 min)
- [ ] Test with sample HTML containing GTM (15 min)
- [ ] Test with empty HTML (10 min)

**Method Signature:**
```php
/**
 * Scan page HTML for existing tracking scripts
 *
 * @param string $html The page HTML content
 * @param array $tracking_ids Array of tracking IDs to check for
 * @return array Array of found tracking IDs
 */
public function scan_page_html($html, $tracking_ids)
```

---

### US-03.6: As a developer, I need conflict logging for debugging
**Priority:** P1
**Story Points:** 1

**Acceptance Criteria:**
- [ ] log_conflict() logs to WordPress debug log
- [ ] Only logs when WP_DEBUG is true
- [ ] Log messages prefixed with "GAP Conflict:"
- [ ] Logs include relevant context (script name, IDs)

**Tasks:**
- [ ] Implement log_conflict() method (20 min)
- [ ] Check WP_DEBUG constant (5 min)
- [ ] Use error_log() for logging (5 min)
- [ ] Test logging with WP_DEBUG enabled (10 min)

**Log Format:**
```
GAP Conflict: Duplicate tracking script detected for "Homepage GA4". IDs already on page: G-ABC1234567 (Google Analytics 4). Skipping output to prevent double-tracking.
```

---

## Technical Tasks

### TT-03.1: Implement GAP_Conflict_Detector Class
**Estimated Time:** 2 hours
**Assignee:** TBD
**File:** `includes/class-gap-conflict-detector.php`

**Implementation Steps:**
1. Create class with singleton pattern
2. Add private $detected_conflicts property
3. Implement extract_tracking_ids() method
4. Implement check_global_conflicts() method
5. Implement scan_page_html() method
6. Implement get_conflicts() method
7. Implement log_conflict() method
8. Hook admin_init for conflict checking

**Class Structure:**
```php
class GAP_Conflict_Detector {
    private static $instance = null;
    private $detected_conflicts = array();

    public static function get_instance() { }
    private function __construct() { }
    public function extract_tracking_ids($content) { }
    public function check_global_conflicts() { }
    public function scan_page_html($html, $tracking_ids) { }
    public function get_conflicts() { }
    public function log_conflict($message) { }
}
```

---

### TT-03.2: Integrate with Meta Boxes Save Handler
**Estimated Time:** 30 minutes
**Assignee:** TBD
**File:** `includes/class-gap-meta-boxes.php`

**Implementation Steps:**
1. Call Conflict_Detector after saving script content
2. Extract IDs and save to post meta
3. Generate content hash and save to post meta
4. Ensure extraction happens on every save

---

### TT-03.3: Implement Admin Conflict Notices
**Estimated Time:** 1 hour
**Assignee:** TBD
**File:** `includes/class-gap-admin.php`

**Implementation Steps:**
1. Hook admin_notices action
2. Check if on tracking_script admin page
3. Get conflicts from Conflict_Detector
4. Render admin notice HTML
5. Include edit links for affected posts
6. Add inline CSS for styling

---

## Definition of Done

- [ ] GAP_Conflict_Detector class fully implemented
- [ ] All methods tested with sample data
- [ ] Tracking ID extraction works for GA4 and GTM
- [ ] Duplicate detection across posts working
- [ ] Admin warnings display correctly
- [ ] HTML scanning implemented
- [ ] Conflict logging functional
- [ ] Integration with Meta_Boxes complete
- [ ] Admin columns show extracted IDs
- [ ] All code follows WordPress coding standards
- [ ] Inline documentation complete
- [ ] Manual testing completed
- [ ] Code review completed
- [ ] Committed to version control

---

## Dependencies

**Upstream Dependencies:**
- EPIC-01: Foundation (requires autoloader)
- EPIC-02: Admin Interface (requires meta boxes for integration)

**Downstream Dependencies:**
- EPIC-04: Frontend Output (will use scan_page_html() and conflict logging)

---

## Testing Requirements

### Unit Testing (Manual)

#### extract_tracking_ids() Tests
- [ ] GA4 ID extraction: `'G-ABC1234567'` → extracted
- [ ] GTM ID extraction: `'GTM-ABC1234'` → extracted
- [ ] Multiple IDs: Both GA4 and GTM → both extracted
- [ ] No IDs: Empty script → empty array returned
- [ ] Malformed IDs: `'G-TOOLONG123'` → not extracted
- [ ] Case sensitivity: Works with single/double quotes

**Test Data:**
```javascript
// GA4 Script
<script>
gtag('config', 'G-ABC1234567');
</script>

// GTM Script
<script>
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-ABC1234');
</script>

// Mixed
<script>
gtag('config', 'G-ABC1234567');
gtag('config', 'GTM-XYZ7890');
</script>
```

#### check_global_conflicts() Tests
- [ ] No duplicates: Returns empty array
- [ ] One duplicate: Returns conflict with 2 posts
- [ ] Multiple duplicates: Returns multiple conflicts
- [ ] Same ID in 3+ posts: All posts listed
- [ ] Different IDs: No conflicts

#### scan_page_html() Tests
- [ ] ID exists in HTML: Found and returned
- [ ] ID not in HTML: Not returned
- [ ] Multiple IDs: All found
- [ ] Case insensitive: 'g-abc' finds 'G-ABC'
- [ ] Empty HTML: Returns empty array

---

### Integration Testing

#### Meta Box Integration
- [ ] Create tracking script with GA4 code
- [ ] Save post
- [ ] Verify _gap_extracted_ids contains GA4 ID
- [ ] Verify _gap_unique_hash contains MD5 hash
- [ ] Edit script and save again
- [ ] Verify IDs updated correctly

#### Admin Notice Integration
- [ ] Create two scripts with same GA4 ID
- [ ] Navigate to tracking scripts list
- [ ] Verify error notice displays
- [ ] Verify both scripts listed in notice
- [ ] Click edit link
- [ ] Verify navigates to correct post

#### Admin Column Integration
- [ ] Create script with GA4 ID
- [ ] View tracking scripts list
- [ ] Verify Tracking IDs column shows GA4 ID
- [ ] Create script with GTM ID
- [ ] Verify Tracking IDs column shows GTM ID
- [ ] Create script with both
- [ ] Verify both IDs displayed

---

## Regex Pattern Validation

### GA4 Pattern: `/['\"]G-[A-Z0-9]{10}['\""]/`

**Valid Matches:**
- `'G-ABC1234567'`
- `"G-ABC1234567"`
- `'G-1234567890'`
- `"G-ABCDEFGHIJ"`

**Invalid (should not match):**
- `G-ABC1234567` (no quotes)
- `'G-ABC123'` (too short)
- `'G-ABC123456789'` (too long)
- `'GA-ABC1234567'` (wrong prefix)

### GTM Pattern: `/['\"]GTM-[A-Z0-9]{7}['\""]/`

**Valid Matches:**
- `'GTM-ABC1234'`
- `"GTM-ABC1234"`
- `'GTM-1234567'`

**Invalid (should not match):**
- `GTM-ABC1234` (no quotes)
- `'GTM-ABC'` (too short)
- `'GTM-ABC12345'` (too long)

---

## WordPress Coding Standards Compliance

### Method Documentation
- [ ] All methods have docblocks
- [ ] @param tags for all parameters
- [ ] @return tags for return values
- [ ] @since tags indicate version

### Security
- [ ] ABSPATH check at top of file
- [ ] No direct SQL queries (using get_posts)
- [ ] Post meta properly escaped when displayed
- [ ] stripos() used for safe string searching

### Performance
- [ ] Conflicts cached in class property
- [ ] Not recalculated on every admin_notices call
- [ ] scan_page_html() uses efficient stripos()
- [ ] Regex patterns compiled once

---

## Risks & Mitigations

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Regex pattern too strict/loose | High | Medium | Test with real GA4/GTM code samples |
| Performance with many posts | Medium | Low | Use efficient WP_Query, cache results |
| HTML scanning too slow | Medium | Low | Use stripos (fast), limit search scope |
| False positives in ID detection | High | Low | Use strict regex with quote requirements |
| Logging fills up disk | Low | Very Low | Only log when WP_DEBUG enabled |

---

## Sample Test Cases

### Test Case 1: Extract GA4 ID
**Input:**
```html
<script async src="https://www.googletagmanager.com/gtag/js?id=G-ABC1234567"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-ABC1234567');
</script>
```

**Expected Output:**
```php
[
    [
        'id' => 'G-ABC1234567',
        'type' => 'ga4',
        'name' => 'Google Analytics 4'
    ]
]
```

---

### Test Case 2: Extract GTM ID
**Input:**
```html
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-XYZ7890');</script>
```

**Expected Output:**
```php
[
    [
        'id' => 'GTM-XYZ7890',
        'type' => 'gtm',
        'name' => 'Google Tag Manager'
    ]
]
```

---

### Test Case 3: Detect Duplicate Across Posts
**Setup:**
- Post 1: "Homepage GA4" with G-ABC1234567
- Post 2: "Global GA4" with G-ABC1234567

**Expected Conflict:**
```php
[
    [
        'tracking_id' => 'G-ABC1234567',
        'type' => 'ga4',
        'name' => 'Google Analytics 4',
        'posts' => [
            ['post_id' => 1, 'title' => 'Homepage GA4'],
            ['post_id' => 2, 'title' => 'Global GA4']
        ]
    ]
]
```

---

## Notes

- This epic is critical for product differentiation
- Regex patterns must be precise to avoid false positives/negatives
- Consider adding support for UA (Universal Analytics) in future
- HTML scanning will be used heavily by EPIC-04 (Frontend Output)
- Conflict logging helps debugging without exposing to users
- Admin notices should be dismissible in future version

---

## Future Enhancements (Post-MVP)

- Support for Universal Analytics (UA-XXXXXXXX-X)
- Support for Facebook Pixel detection
- Conflict resolution wizard in admin
- Automatic ID deduplication suggestions
- Export conflict reports
- Email notifications for new conflicts

---

## Related Documents

- [PHP preg_match_all() Documentation](https://www.php.net/manual/en/function.preg-match-all.php)
- [GA4 Measurement ID Format](https://support.google.com/analytics/answer/9539598)
- [GTM Container ID Format](https://support.google.com/tagmanager/answer/6103696)
- [GA Plugin Planning Document](../GA-PLUGIN-PLAN.md#phase-25-conflict-detection-system)

---

**Created:** 2025-01-16
**Last Updated:** 2025-01-16
**Epic Owner:** TBD
**Status:** Ready for Development
