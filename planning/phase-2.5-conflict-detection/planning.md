# Phase 2.5: Conflict Detection System

**Estimated Time:** 2-3 hours

**Status:** Not Started

## Overview

This phase creates a comprehensive conflict detection system that automatically extracts tracking IDs (GA4 and GTM only) from script content and prevents duplicate scripts across the site.

## Dependencies

- **Phase 1** must be complete (CPT registered, autoloader working)
- **Can run in parallel with Phase 2** (independent implementation)

## Deliverables

- Conflict detector class (`GAP_Conflict_Detector`)
- Regex patterns for GA4 (G-XXXXXXXXXX) and GTM (GTM-XXXXXXX)
- Tracking ID extraction functionality
- HTML scanning to detect existing scripts
- Caching system for performance
- Conflict logging

## Completion Criteria

- [ ] GA4 measurement IDs detected correctly
- [ ] GTM container IDs detected correctly
- [ ] Regex patterns handle variations
- [ ] HTML scanning finds existing scripts
- [ ] Caching implemented
- [ ] Cache clears on script changes
- [ ] Conflict warnings display in admin
- [ ] Logging works with WP_DEBUG

---

## Key Features

### 1. Tracking ID Extraction

Automatically detect and extract:

- **GA4 Measurement IDs:** `G-XXXXXXXXXX` format (10 alphanumeric chars after G-)
- **GTM Container IDs:** `GTM-XXXXXXX` format (7 alphanumeric chars after GTM-)

### 2. Duplicate Prevention

- Scan rendered page HTML before injecting scripts
- Check all DOM sections (head, body)
- Skip output if duplicate tracking ID found
- Log conflicts with HTML comments

### 3. Performance Optimization

- Cache scan results (1 hour default)
- Clear cache when tracking scripts change
- Use transients for temporary storage
- Minimize regex operations

---

## File Structure

```
wp-content/plugins/ga-plugin/
└── includes/
    └── class-gap-conflict-detector.php
```

---

## Implementation Reference

**See master plan for complete implementation code:**

- **Master Plan Location:** `@TRACKING-SCRIPT-MANAGER-PLAN.md` (Lines 239-417)
- **Key Section:** Phase 2.5: Conflict Detection System (`class-gap-conflict-detector.php`)

**Important Notes for Implementation:**

1. **Update all TSM references to GAP:**
   - Class name: `GAP_Conflict_Detector` (not GAP_Conflict_Detector)
   - Function prefix: `gap_` (not `gap_`)
   - Transient key: `gap_script_scan_results` (not `gap_...`)
   - Text domain: `ga-plugin` (not `ga-plugin`)

2. **Meta Key Changes:**
   - Use `_gap_` prefix instead of `_gap_`
   - Example: `_gap_extracted_ids` instead of `_gap_extracted_ids`

3. **Post Type Reference:**
   - Use `gap_tracking` instead of `tracking_script`

---

## Regex Patterns

### GA4 Pattern

```php
// Matches: G-XXXXXXXXXX (10 alphanumeric chars)
$pattern = '/[\'"]G-[A-Z0-9]{10}[\'"]/ ';

// Example matches:
// "G-ABC1234567"
// 'G-TEST123456'
// "G-1234567890"
```

### GTM Pattern

```php
// Matches: GTM-XXXXXXX (7 alphanumeric chars)
$pattern = '/[\'"]GTM-[A-Z0-9]{7}[\'"]/ ';

// Example matches:
// "GTM-ABC1234"
// 'GTM-TEST123'
// "GTM-1234567"
```

---

## Key Functions

### extract_tracking_ids()

```php
/**
 * Extract tracking IDs from script content
 *
 * @param string $content The script content
 * @return array Array of extracted tracking IDs with types
 */
public function extract_tracking_ids($content) {
    // Returns array like:
    // [
    //     ['id' => 'G-ABC1234567', 'type' => 'ga4', 'name' => 'Google Analytics 4'],
    //     ['id' => 'GTM-ABC1234', 'type' => 'gtm', 'name' => 'Google Tag Manager']
    // ]
}
```

### scan_page_html()

```php
/**
 * Scan page HTML for existing tracking scripts
 *
 * @param string $html The page HTML content
 * @param array $tracking_ids Array of tracking IDs to check for
 * @return array Array of found tracking IDs
 */
public function scan_page_html($html, $tracking_ids) {
    // Checks if any tracking IDs exist in HTML
    // Returns those that were found
}
```

### check_global_conflicts()

```php
/**
 * Check for duplicate tracking IDs across all tracking scripts
 *
 * @return array Array of conflicts with post IDs and tracking IDs
 */
public function check_global_conflicts() {
    // Queries all published tracking scripts
    // Builds map of tracking ID => post IDs using that ID
    // Returns conflicts where same ID used in multiple posts
}
```

---

## Caching Strategy

### Cache Keys

```php
const CACHE_KEY = 'gap_script_scan_results';
const CACHE_DURATION = 3600; // 1 hour
```

### Cache Usage

```php
// Check cache
$cached = get_transient(self::CACHE_KEY);
if (false !== $cached) {
    return $cached;
}

// Perform scan
$results = $this->perform_scan();

// Cache results
set_transient(self::CACHE_KEY, $results, self::CACHE_DURATION);
```

### Cache Clearing

```php
// Clear on script save
add_action('save_post_gap_tracking', array($this, 'gap_clear_cache'));

// Clear manually
public function gap_clear_cache() {
    delete_transient(self::CACHE_KEY);
}
```

---

## Security & WordPress Standards

- Singleton pattern for consistency
- Uses WP debug logging (respects `WP_DEBUG` constant)
- DRY principle: Reusable extraction and detection methods
- No direct database queries (uses `get_posts()`, `get_post_meta()`)

---

## Testing Checklist

### Pattern Testing

- [ ] GA4 pattern detects `G-XXXXXXXXXX`
- [ ] GTM pattern detects `GTM-XXXXXXX`
- [ ] Patterns handle single and double quotes
- [ ] Patterns case-insensitive
- [ ] Invalid formats rejected

### Extraction Testing

- [ ] Extracts GA4 IDs correctly
- [ ] Extracts GTM IDs correctly
- [ ] Handles multiple IDs in same script
- [ ] Returns correct type for each ID
- [ ] Empty content returns empty array

### Scanning Testing

- [ ] Finds GA4 scripts in HTML
- [ ] Finds GTM scripts in HTML
- [ ] Scans both head and body
- [ ] Handles malformed HTML
- [ ] Case-insensitive matching

### Caching Testing

- [ ] Results cached after first scan
- [ ] Cache returns on subsequent scans
- [ ] Cache clears on script save
- [ ] Cache expires after 1 hour
- [ ] Manual cache clear works

### Conflict Detection

- [ ] Detects duplicate IDs across posts
- [ ] Reports which posts have duplicates
- [ ] Admin notices display correctly
- [ ] Logging works with WP_DEBUG

---

## Agent Responsibilities

**Primary Agent:** `conflict-detector-specialist`

- Creates conflict detector class
- Implements regex patterns
- Implements HTML scanning
- Implements caching strategy
- Ensures performance optimization

**Review Agent:** `wp-code-reviewer`

- Reviews regex pattern robustness
- Checks caching implementation
- Verifies WordPress standards
- Confirms DRY/KISS principles

**Review Agent:** `wp-security-scanner`

- Checks for SQL injection risks
- Verifies input sanitization
- Confirms output escaping

---

## Git Workflow for This Phase

### Branch Information
- **Branch name:** `phase-2.5-conflict-detection`
- **Created from:** `main` (after Phase 1 merged)
- **Merges to:** `main`
- **Merge dependencies:** Phase 1 must be merged first
- **Can run parallel with:** Phase 2
- **Unblocks:** Phase 3 (along with Phase 2)

### Starting This Phase
```bash
/start-phase 2.5
```

### Commit Strategy

- [ ] **Commit 1:** Conflict detector class structure
  ```bash
  git add includes/class-gap-conflict-detector.php
  git commit -m "feat(conflict): create conflict detector class

  - Add GAP_Conflict_Detector class
  - Implement singleton pattern
  - Add class structure

  Addresses: Phase 2.5 deliverable (conflict detector class)"
  git push
  ```

- [ ] **Commit 2:** Tracking ID extraction
  ```bash
  git add includes/class-gap-conflict-detector.php
  git commit -m "feat(conflict): implement tracking ID extraction

  - Add GA4 extraction (G-XXXXXXXXXX)
  - Add GTM extraction (GTM-XXXXXXX)
  - Add regex validation

  Addresses: Phase 2.5 deliverable (ID extraction)"
  git push
  ```

- [ ] **Commit 3:** Duplicate detection
  ```bash
  git add includes/class-gap-conflict-detector.php
  git commit -m "feat(conflict): implement duplicate detection

  - Add cross-post duplicate checking
  - Add conflict logging
  - Implement detection logic

  Addresses: Phase 2.5 deliverable (duplicate detection)"
  git push
  ```

- [ ] **Commit 4:** HTML scanning and caching
  ```bash
  git add includes/class-gap-conflict-detector.php
  git commit -m "feat(conflict): add HTML scanning and caching

  - Implement HTML page scanning
  - Add transient-based caching
  - Add cache invalidation

  Addresses: Phase 2.5 deliverable (HTML scanning, caching)"
  git push
  ```

### PR Template
**Title:** `Phase 2.5: Conflict Detection System`

Use `/finish-phase 2.5` to auto-generate PR description.

### File Ownership
**Phase 2.5 owns:**
- `includes/class-gap-conflict-detector.php`

**Do not modify Phase 2 files:**
- `includes/admin/class-gap-meta-box.php`
- Admin assets

---

## Next Steps

After this phase is complete:

1. Run `/review-phase 2.5` to check security and code quality
2. Run `/test-component conflict-detector` to verify functionality
3. Run `/finish-phase 2.5` to create pull request
4. **Phase 3 cannot start until both Phase 2 AND Phase 2.5 are merged**

**Note:** Phase 3 depends on this conflict detector for duplicate prevention.

---

## References

- Master Plan: `@TRACKING-SCRIPT-MANAGER-PLAN.md` (Lines 239-417)
- PHP Regex Reference: https://www.php.net/manual/en/reference.pcre.pattern.syntax.php
- WordPress Transients API: https://developer.wordpress.org/apis/transients/
- WordPress Debugging: https://wordpress.org/documentation/article/debugging-in-wordpress/
