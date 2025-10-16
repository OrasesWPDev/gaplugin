# EPIC-02: Custom Post Type & Admin Interface - Tickets

This directory contains all individual tickets (user stories and technical tasks) for EPIC-02: Custom Post Type & Admin Interface.

---

## Epic Overview

**Epic ID:** EPIC-02
**Epic Name:** Custom Post Type & Admin Interface
**Status:** Ready for Development
**Priority:** P0 (Critical)
**Estimated Time:** 4-5 hours
**Dependencies:** EPIC-01 (Foundation)

### Objectives

1. Register tracking_script custom post type with proper capabilities
2. Create meta boxes for tracking script configuration
3. Implement all meta fields with proper sanitization
4. Add custom admin columns for better visibility
5. Implement JavaScript for dynamic UI interactions
6. Add admin CSS for enhanced user experience
7. Ensure all security best practices are followed

### Success Criteria

- [ ] Custom post type appears in WordPress admin menu
- [ ] Only administrators can access tracking scripts
- [ ] Can create, edit, and delete tracking scripts
- [ ] All meta fields save correctly with proper sanitization
- [ ] Custom admin columns display correct information
- [ ] Scope selector shows/hides page selector dynamically
- [ ] All inputs validated before saving
- [ ] No security vulnerabilities (XSS, CSRF, SQL injection)

---

## User Stories (5 total)

All user stories focus on administrator functionality for managing tracking scripts through the WordPress admin interface.

### US-02.1: Manage Tracking Scripts via Custom Post Type
**File:** [user-stories/us-02.1-cpt-registration.md](user-stories/us-02.1-cpt-registration.md)
**Priority:** P0 (Critical)
**Story Points:** 5
**Description:** As an administrator, I need to manage tracking scripts via custom post type so that I have a centralized interface in WordPress admin.
**Status:** Not Started

### US-02.2: Custom Admin Columns
**File:** [user-stories/us-02.2-custom-admin-columns.md](user-stories/us-02.2-custom-admin-columns.md)
**Priority:** P1
**Story Points:** 3
**Description:** As an administrator, I need custom admin columns to view tracking script details at a glance.
**Status:** Not Started

### US-02.3: Meta Fields Configuration
**File:** [user-stories/us-02.3-meta-fields-configuration.md](user-stories/us-02.3-meta-fields-configuration.md)
**Priority:** P0 (Critical)
**Story Points:** 8
**Description:** As an administrator, I need to configure tracking scripts via meta fields so that I can specify script content, placement, scope, and target pages.
**Status:** Not Started

### US-02.4: Dynamic UI
**File:** [user-stories/us-02.4-dynamic-ui.md](user-stories/us-02.4-dynamic-ui.md)
**Priority:** P1
**Story Points:** 3
**Description:** As an administrator, I need dynamic UI that shows/hides the page selector based on scope selection.
**Status:** Not Started

### US-02.5: Admin Styling
**File:** [user-stories/us-02.5-admin-styling.md](user-stories/us-02.5-admin-styling.md)
**Priority:** P2
**Story Points:** 2
**Description:** As an administrator, I need a styled admin interface so that the tracking script configuration form is visually polished.
**Status:** Not Started

**Total Story Points:** 21

---

## Technical Tasks (4 total)

Technical tasks provide the implementation details for the user stories.

### TT-02.1: Implement GAP_CPT Class
**File:** [technical-tasks/tt-02.1-gap-cpt-class.md](technical-tasks/tt-02.1-gap-cpt-class.md)
**Estimated Time:** 2 hours
**Description:** Create the GAP_CPT class that registers the tracking_script custom post type and implements custom admin columns.
**Status:** Not Started

### TT-02.2: Implement GAP_Meta_Boxes Class
**File:** [technical-tasks/tt-02.2-gap-meta-boxes-class.md](technical-tasks/tt-02.2-gap-meta-boxes-class.md)
**Estimated Time:** 2.5 hours
**Description:** Create the GAP_Meta_Boxes class that manages all meta box functionality including field rendering, saving, sanitization, and validation.
**Status:** Not Started

### TT-02.3: Create Admin JavaScript
**File:** [technical-tasks/tt-02.3-admin-javascript.md](technical-tasks/tt-02.3-admin-javascript.md)
**Estimated Time:** 1 hour
**Description:** Create admin.js file that implements dynamic UI interactions for show/hide page selector based on scope selection.
**Status:** Not Started

### TT-02.4: Create Admin CSS
**File:** [technical-tasks/tt-02.4-admin-css.md](technical-tasks/tt-02.4-admin-css.md)
**Estimated Time:** 1 hour
**Description:** Create admin.css file that styles the tracking script configuration form with proper layout, spacing, colors, and responsive design.
**Status:** Not Started

**Total Estimated Time:** 6.5 hours

---

## Files to Create

### PHP Classes
- `includes/class-gap-cpt.php` - Custom post type registration
- `includes/class-gap-meta-boxes.php` - Meta box management

### Assets
- `assets/js/admin.js` - Dynamic UI JavaScript
- `assets/css/admin.css` - Form styling

### Total New Files:** 4

---

## Development Dependencies

### Upstream Dependencies
- **EPIC-01:** Foundation (requires autoloader, initialization, and main plugin file)

### Downstream Dependencies
- **EPIC-03:** Conflict Detection (meta fields will trigger ID extraction)
- **EPIC-04:** Frontend Output (meta fields control script output)

---

## Ticket Workflow

### Recommended Completion Order

1. **Phase 1: Infrastructure (TT-02.1, TT-02.2)**
   - Implement GAP_CPT class (2 hours)
   - Implement GAP_Meta_Boxes class (2.5 hours)
   - Total: 4.5 hours

2. **Phase 2: User Stories (US-02.1 → US-02.5)**
   - Test custom post type (US-02.1 depends on TT-02.1)
   - Verify custom columns work (US-02.2 depends on TT-02.1)
   - Verify meta fields save correctly (US-02.3 depends on TT-02.2)
   - Test dynamic UI (US-02.4 depends on TT-02.3)
   - Verify styling complete (US-02.5 depends on TT-02.4)

3. **Phase 3: Assets (TT-02.3, TT-02.4)**
   - Create admin.js (1 hour)
   - Create admin.css (1 hour)
   - Total: 2 hours

### Parallelization Opportunities

These tasks can run in parallel (no dependencies):
- TT-02.1 and TT-02.2 can start immediately
- TT-02.3 and TT-02.4 can start after TT-02.2

---

## Acceptance Criteria

### Code Quality
- [ ] All code follows WordPress coding standards
- [ ] All methods have proper docblocks
- [ ] Singleton pattern properly implemented
- [ ] All hooks properly registered
- [ ] No PHP errors or warnings

### Functionality
- [ ] Custom post type appears in admin menu
- [ ] Only admins can access (403 for non-admin)
- [ ] Can create, edit, delete tracking scripts
- [ ] All meta fields render correctly
- [ ] All meta fields save and persist correctly
- [ ] Custom columns display on post list
- [ ] Page selector shows/hides based on scope
- [ ] Form is properly styled and responsive

### Security
- [ ] Nonce verification implemented
- [ ] Capability checks in place
- [ ] Input sanitization complete
- [ ] Output escaping implemented
- [ ] XSS attempts fail
- [ ] CSRF protection in place

### Testing
- [ ] Manual testing completed and documented
- [ ] Security testing completed
- [ ] Cross-browser tested
- [ ] Responsive design tested
- [ ] All acceptance criteria passing

---

## Definition of Done

The epic is complete when:
- [ ] All 5 user stories complete
- [ ] All 4 technical tasks complete
- [ ] All 4 new files created and working
- [ ] All acceptance criteria passing
- [ ] All security testing complete
- [ ] Code review passed
- [ ] Ready for EPIC-03 (Conflict Detection)

---

## Related Documents

- [EPIC Definition](EPIC.md)
- [GA Plugin Planning Document](../../GA-PLUGIN-PLAN.md)
- [WordPress Coding Standards](https://developer.wordpress.org/plugins/wordpress-org/official-wordpress-plugin-guidelines/)
- [WordPress Security](https://developer.wordpress.org/plugins/security/)
- [WordPress Meta Boxes](https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/)

---

## Progress Tracking

### Ticket Completion

- [ ] US-02.1 - CPT Registration
- [ ] US-02.2 - Custom Admin Columns
- [ ] US-02.3 - Meta Fields Configuration
- [ ] US-02.4 - Dynamic UI
- [ ] US-02.5 - Admin Styling
- [ ] TT-02.1 - GAP_CPT Class
- [ ] TT-02.2 - GAP_Meta_Boxes Class
- [ ] TT-02.3 - Admin JavaScript
- [ ] TT-02.4 - Admin CSS

### Estimated Timeline

- **Phase 1:** 4.5 hours (Infrastructure)
- **Phase 2:** ~8 hours (User stories, testing, refinement)
- **Phase 3:** 2 hours (Assets, final testing)
- **Total:** ~14.5 hours (realistic with QA)

---

## Quick Links

### User Story Files
- [US-02.1 CPT Registration](user-stories/us-02.1-cpt-registration.md)
- [US-02.2 Custom Admin Columns](user-stories/us-02.2-custom-admin-columns.md)
- [US-02.3 Meta Fields Configuration](user-stories/us-02.3-meta-fields-configuration.md)
- [US-02.4 Dynamic UI](user-stories/us-02.4-dynamic-ui.md)
- [US-02.5 Admin Styling](user-stories/us-02.5-admin-styling.md)

### Technical Task Files
- [TT-02.1 GAP_CPT Class](technical-tasks/tt-02.1-gap-cpt-class.md)
- [TT-02.2 GAP_Meta_Boxes Class](technical-tasks/tt-02.2-gap-meta-boxes-class.md)
- [TT-02.3 Admin JavaScript](technical-tasks/tt-02.3-admin-javascript.md)
- [TT-02.4 Admin CSS](technical-tasks/tt-02.4-admin-css.md)

---

## Notes

- Script content field must allow `<script>` tags for GA/GTM code
- wp_kses_post() is the right balance for sanitization
- Page selector may need optimization for sites with many pages
- Consider adding "Select All" / "Deselect All" for page selector
- Custom columns will show "None detected" until EPIC-03 implemented
- Active checkbox defaults to unchecked (inactive) for safety

---

**Created:** 2025-10-16
**Last Updated:** 2025-10-16
**Total Tickets:** 9 (5 user stories + 4 technical tasks)
**Total Effort:** ~21 story points + 6.5 hours
**Epic Owner:** TBD
**Status:** Ready for Development
