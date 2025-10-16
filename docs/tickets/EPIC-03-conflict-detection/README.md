# EPIC-03: Conflict Detection System - Ticket Breakdown

**Epic ID:** EPIC-03
**Epic Name:** Conflict Detection System
**Status:** Not Started
**Priority:** P0 (Critical)
**Total Estimated Time:** 3.5-4 hours
**Dependencies:** EPIC-01 (Foundation), EPIC-02 (Admin Interface)

---

## Overview

This epic implements the duplicate detection and conflict prevention system that automatically extracts Google Analytics tracking IDs (GA4 and GTM) from script content, detects duplicates across tracking script posts, scans page HTML for existing scripts, and prevents double-tracking on the frontend.

This is a **key differentiator** for the GA Plugin and addresses the core client requirement for preventing duplicate tracking.

---

## Epic Objectives

1. Extract GA4 and GTM tracking IDs from script content using regex
2. Detect duplicate tracking IDs across all tracking script posts
3. Store extracted IDs in post meta for display and comparison
4. Provide conflict reporting in admin interface
5. Enable HTML scanning for duplicate detection on frontend
6. Implement conflict logging for debugging

---

## Tickets Summary

### User Stories (6 tickets)

| Ticket ID | Title | Priority | Story Points | Status |
|-----------|-------|----------|--------------|--------|
| US-03.1 | Extract tracking IDs from script content | P0 | 5 | Not Started |
| US-03.2 | Auto-extract IDs when saving script | P0 | 3 | Not Started |
| US-03.3 | Display tracking IDs in admin columns | P1 | 2 | Not Started |
| US-03.4 | Warn admin of duplicate tracking IDs | P0 | 5 | Not Started |
| US-03.5 | Scan page HTML for existing scripts | P0 | 3 | Not Started |
| US-03.6 | Log conflicts for debugging | P1 | 1 | Not Started |

**Total Story Points:** 19

### Technical Tasks (3 tickets)

| Ticket ID | Title | Estimated Time | Status |
|-----------|-------|----------------|--------|
| TT-03.1 | Implement GAP_Conflict_Detector Class | 2 hours | Not Started |
| TT-03.2 | Integrate with Meta Boxes Save Handler | 30 minutes | Not Started |
| TT-03.3 | Implement Admin Conflict Notices | 1 hour | Not Started |

**Total Estimated Time:** 3.5 hours

---

## User Story Details

### US-03.1: Extract Tracking IDs from Script Content
**File:** [user-stories/us-03.1-extract-tracking-ids.md](./user-stories/us-03.1-extract-tracking-ids.md)
**Priority:** P0 | **Story Points:** 5

Implement regex patterns to extract GA4 (G-XXXXXXXXXX) and GTM (GTM-XXXXXXX) tracking IDs from script content.

**Key Deliverables:**
- Regex pattern for GA4 ID extraction
- Regex pattern for GTM ID extraction
- extract_tracking_ids() method returning array of ID objects
- Handle multiple IDs in single script
- Return empty array if no IDs found

**Regex Patterns:**
```php
// GA4: Matches G-XXXXXXXXXX (10 alphanumeric characters)
/['\"]G-[A-Z0-9]{10}['\""]/

// GTM: Matches GTM-XXXXXXX (7 alphanumeric characters)
/['\"]GTM-[A-Z0-9]{7}['\""]/
```

---

### US-03.2: Auto-Extract IDs When Saving Script
**File:** [user-stories/us-03.2-auto-extract-on-save.md](./user-stories/us-03.2-auto-extract-on-save.md)
**Priority:** P0 | **Story Points:** 3

Automatically extract and store tracking IDs whenever a tracking script is saved.

**Key Deliverables:**
- Hook into save_post_tracking_script action
- Extract IDs using GAP_Conflict_Detector
- Store in _gap_extracted_ids post meta
- Generate MD5 hash and store in _gap_unique_hash
- Handle multiple saves correctly

---

### US-03.3: Display Tracking IDs in Admin Columns
**File:** [user-stories/us-03.3-admin-column-display.md](./user-stories/us-03.3-admin-column-display.md)
**Priority:** P1 | **Story Points:** 2

Display extracted tracking IDs in the admin post listing with type badges.

**Key Deliverables:**
- New "Tracking IDs" column in admin list
- Type badges (GA4, GTM)
- Multiple IDs on separate lines
- "None detected" when no IDs found
- Color-coded by type

---

### US-03.4: Admin Warnings for Duplicate IDs
**File:** [user-stories/us-03.4-admin-warnings.md](./user-stories/us-03.4-admin-warnings.md)
**Priority:** P0 | **Story Points:** 5

Display admin notices when duplicate tracking IDs are detected across multiple scripts.

**Key Deliverables:**
- check_global_conflicts() scans all published scripts
- Detect when same ID used in multiple posts
- Admin notice on tracking_script pages
- List all posts using duplicate ID
- Include edit links for each post

---

### US-03.5: Scan Page HTML for Existing Scripts
**File:** [user-stories/us-03.5-html-scanning.md](./user-stories/us-03.5-html-scanning.md)
**Priority:** P0 | **Story Points:** 3

Scan page HTML for existing tracking scripts to prevent frontend duplicates.

**Key Deliverables:**
- scan_page_html() method accepts HTML and tracking IDs
- Case-insensitive search across all DOM sections
- Returns array of found IDs
- Efficient implementation using stripos()

---

### US-03.6: Conflict Logging for Debugging
**File:** [user-stories/us-03.6-conflict-logging.md](./user-stories/us-03.6-conflict-logging.md)
**Priority:** P1 | **Story Points:** 1

Log conflicts to WordPress debug log for troubleshooting.

**Key Deliverables:**
- log_conflict() method for logging
- Only log when WP_DEBUG is true
- Log messages prefixed with "GAP Conflict:"
- Include relevant context (script name, IDs)

---

## Technical Task Details

### TT-03.1: Implement GAP_Conflict_Detector Class
**File:** [technical-tasks/tt-03.1-conflict-detector-class.md](./technical-tasks/tt-03.1-conflict-detector-class.md)
**Estimated Time:** 2 hours
**Assignee:** TBD

Create the core conflict detection class using singleton pattern.

**Implementation Steps:**
1. Create class with singleton pattern
2. Add private $detected_conflicts property
3. Implement extract_tracking_ids() method
4. Implement check_global_conflicts() method
5. Implement scan_page_html() method
6. Implement get_conflicts() method
7. Implement log_conflict() method
8. Hook admin_init for conflict checking

---

### TT-03.2: Integrate with Meta Boxes Save Handler
**File:** [technical-tasks/tt-03.2-meta-boxes-integration.md](./technical-tasks/tt-03.2-meta-boxes-integration.md)
**Estimated Time:** 30 minutes
**Assignee:** TBD

Integrate conflict detection into the meta box save handler.

**Implementation Steps:**
1. Call Conflict_Detector after saving script content
2. Extract IDs and save to post meta
3. Generate content hash and save to post meta
4. Ensure extraction happens on every save

---

### TT-03.3: Implement Admin Conflict Notices
**File:** [technical-tasks/tt-03.3-admin-notices.md](./technical-tasks/tt-03.3-admin-notices.md)
**Estimated Time:** 1 hour
**Assignee:** TBD

Create admin notices to warn users about duplicate tracking IDs.

**Implementation Steps:**
1. Hook admin_notices action
2. Check if on tracking_script admin page
3. Get conflicts from Conflict_Detector
4. Render admin notice HTML
5. Include edit links for affected posts
6. Add CSS for styling

---

## Dependencies

### Upstream Dependencies
- **EPIC-01:** Foundation (requires autoloader and class initialization)
- **EPIC-02:** Admin Interface (requires meta boxes for integration)

### Downstream Dependencies
- **EPIC-04:** Frontend Output (uses scan_page_html() and conflict logging)

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

## Testing Requirements

### Functional Testing
- [ ] Extract GA4 IDs from script content
- [ ] Extract GTM IDs from script content
- [ ] Extract multiple IDs from single script
- [ ] Handle empty scripts (return empty array)
- [ ] Detect duplicates across posts
- [ ] Display admin warnings correctly
- [ ] Scan HTML for existing scripts
- [ ] Log conflicts to debug log

### Integration Testing
- [ ] IDs extracted on post save
- [ ] IDs stored in post meta
- [ ] Hash stored in post meta
- [ ] Admin notice displays on conflict
- [ ] Edit links work correctly

### Security Testing
- [ ] No SQL injection in queries
- [ ] No XSS in admin output
- [ ] Proper capability checks
- [ ] Nonce verification in forms

---

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

## Quick Links

### User Stories
- [US-03.1: Extract Tracking IDs](./user-stories/us-03.1-extract-tracking-ids.md)
- [US-03.2: Auto-Extract on Save](./user-stories/us-03.2-auto-extract-on-save.md)
- [US-03.3: Admin Column Display](./user-stories/us-03.3-admin-column-display.md)
- [US-03.4: Admin Warnings](./user-stories/us-03.4-admin-warnings.md)
- [US-03.5: HTML Scanning](./user-stories/us-03.5-html-scanning.md)
- [US-03.6: Conflict Logging](./user-stories/us-03.6-conflict-logging.md)

### Technical Tasks
- [TT-03.1: Conflict Detector Class](./technical-tasks/tt-03.1-conflict-detector-class.md)
- [TT-03.2: Meta Boxes Integration](./technical-tasks/tt-03.2-meta-boxes-integration.md)
- [TT-03.3: Admin Notices](./technical-tasks/tt-03.3-admin-notices.md)

### Related Documents
- [EPIC-03 Main Document](./EPIC.md)
- [GA4 Measurement ID Format](https://support.google.com/analytics/answer/9539598)
- [GTM Container ID Format](https://support.google.com/tagmanager/answer/6103696)

---

**Created:** 2025-10-16
**Last Updated:** 2025-10-16
**Epic Owner:** TBD
**Status:** Ready for Development
