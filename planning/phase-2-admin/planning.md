# Phase 2: Admin Interface - Meta Boxes & Fields

**Estimated Time:** 3-4 hours

**Status:** Not Started

## Overview

This phase creates the admin interface for configuring tracking scripts: meta boxes for script configuration, save handlers with proper security, and admin styles/scripts.

## Dependencies

- **Phase 1** must be complete (CPT registered, autoloader working)

## Deliverables

- Meta box class for tracking script configuration
- Field rendering with proper escaping
- Save handlers with security (nonces, capabilities, sanitization)
- Admin CSS for meta box styling
- Admin JavaScript for dynamic UI

## Completion Criteria

- [ ] Meta boxes render in post editor
- [ ] Fields save correctly
- [ ] Nonce verification working
- [ ] All input sanitized
- [ ] All output escaped
- [ ] Validation catches invalid data
- [ ] Admin styles applied
- [ ] Dynamic field toggling works

---

## Meta Fields

| Field Name                | Type             | Description                                      |
|---------------------------|------------------|--------------------------------------------------|
| `_gap_tracking_code`      | Text input       | GA4 measurement ID or GTM container ID           |
| `_gap_script_type`        | Select           | ga4, gtm, custom                                 |
| `_gap_custom_code`        | Textarea         | Custom script code (for non-standard scripts)    |
| `_gap_placement`          | Select           | head, body                                       |
| `_gap_scope`              | Select           | global, posts_only, pages_only, etc.             |
| `_gap_enabled`            | Checkbox         | Enable/disable script                            |

---

## File Structure

```
wp-content/plugins/ga-plugin/
├── includes/
│   └── admin/
│       └── class-gap-meta-box.php
└── assets/
    ├── css/
    │   └── admin-meta-box.css
    └── js/
        └── admin-meta-box.js
```

---

## Implementation Reference

**See master plan for complete implementation code:**

- **Master Plan Location:** `@TRACKING-SCRIPT-MANAGER-PLAN.md` (Lines 420-578)
- **Key Section:** Phase 3: Meta Boxes & Fields (`class-tsm-meta-boxes.php`)

**Important Notes for Implementation:**

1. **Update all TSM references to GAP:**
   - Class name: `GAP_Meta_Box` (not TSM_Meta_Boxes)
   - Function prefix: `gap_` (not `tsm_`)
   - Nonce action: `gap_save_meta_box` (not `tsm_save_meta_boxes`)
   - Text domain: `ga-plugin` (not `tracking-script-manager`)

2. **Meta Key Changes:**
   - Use `_gap_` prefix instead of `_tsm_`
   - Example: `_gap_tracking_code` instead of `_tsm_script_content`

3. **File Naming:**
   - Main class: `includes/admin/class-gap-meta-box.php`
   - Assets: `assets/css/admin-meta-box.css`, `assets/js/admin-meta-box.js`

---

## Security Implementation

### 1. Nonce Verification

```php
// Render
wp_nonce_field('gap_save_meta_box', 'gap_meta_nonce');

// Verify
if (!wp_verify_nonce($_POST['gap_meta_nonce'], 'gap_save_meta_box')) {
    return;
}
```

Reference: [WordPress Nonce Documentation](https://developer.wordpress.org/apis/security/nonces/)

### 2. Capability Checks

```php
if (!current_user_can('edit_post', $post_id)) {
    return;
}
```

Reference: [WordPress Roles and Capabilities](https://wordpress.org/documentation/article/roles-and-capabilities/)

### 3. Sanitization

```php
$text  = sanitize_text_field(wp_unslash($_POST['field']));
$html  = wp_kses_post(wp_unslash($_POST['html_field']));
$email = sanitize_email($_POST['email']);
$url   = esc_url_raw($_POST['url']);
$int   = absint($_POST['number']);
```

Reference: [Data Sanitization/Escaping](https://developer.wordpress.org/apis/security/sanitizing-securing-output/)

### 4. Escaping Output

```php
<input value="<?php echo esc_attr($value); ?>" />
<p><?php echo esc_html($text); ?></p>
<textarea><?php echo esc_textarea($content); ?></textarea>
```

Reference: [Escaping Documentation](https://developer.wordpress.org/apis/security/escaping/)

---

## Security Checklist

Before saving any data, verify:

- [ ] Nonce verified
- [ ] Capability checked
- [ ] Not an autosave
- [ ] Data sanitized
- [ ] Data validated (allowed values)
- [ ] Output escaped in forms

---

## Testing Checklist

### Meta Box Rendering

- [ ] Meta boxes appear on edit screen
- [ ] All fields render correctly
- [ ] Field values populate from saved data
- [ ] Nonce field present
- [ ] Labels clear and translatable

### Save Functionality

- [ ] Data saves on post publish
- [ ] Data saves on post update
- [ ] Autosave doesn't trigger save
- [ ] Invalid data rejected
- [ ] Checkbox handling correct (checked/unchecked)

### Security Testing

- [ ] Non-admins can't access
- [ ] Nonce prevents CSRF
- [ ] Capability check enforced
- [ ] Malicious input sanitized
- [ ] XSS prevented via escaping

### UI/UX Testing

- [ ] Conditional fields show/hide correctly
- [ ] Admin styles applied
- [ ] Form layout responsive
- [ ] Help text clear

---

## Agent Responsibilities

**Primary Agent:** `meta-box-specialist`

- Creates meta box class
- Implements field rendering
- Implements save handlers
- Ensures security best practices
- Creates admin assets

**Review Agent:** `wp-security-scanner`

- Verifies nonce implementation
- Checks capability checks
- Validates sanitization
- Confirms output escaping

**Review Agent:** `wp-code-reviewer`

- Checks code quality
- Verifies WordPress standards
- Confirms DRY/KISS principles

---

## Git Workflow for This Phase

### Branch Information
- **Branch name:** `phase-2-admin`
- **Created from:** `main` (after Phase 1 merged)
- **Merges to:** `main`
- **Merge dependencies:** Phase 1 must be merged first
- **Can run parallel with:** Phase 2.5
- **Unblocks:** Phase 3 (along with Phase 2.5)

### Starting This Phase
```bash
/start-phase 2
```

### Commit Strategy

- [ ] **Commit 1:** Meta box class structure
  ```bash
  git add includes/admin/class-gap-meta-box.php
  git commit -m "feat(meta): create meta box class structure

  - Add GAP_Meta_Box class
  - Register meta boxes
  - Add nonce constants

  Addresses: Phase 2 deliverable (meta box class)"
  git push
  ```

- [ ] **Commit 2:** Field rendering implemented
  ```bash
  git add includes/admin/class-gap-meta-box.php
  git commit -m "feat(meta): implement field rendering

  - Add tracking code field
  - Add script type field
  - Add placement and scope fields
  - Add enable/disable checkbox
  - Proper output escaping

  Addresses: Phase 2 deliverable (field rendering)"
  git push
  ```

- [ ] **Commit 3:** Save handlers with security
  ```bash
  git add includes/admin/class-gap-meta-box.php
  git commit -m "feat(meta): implement secure save handlers

  - Add nonce verification
  - Add capability checks
  - Add autosave protection
  - Implement input sanitization
  - Add data validation

  Addresses: Phase 2 deliverable (save handlers)"
  git push
  ```

- [ ] **Commit 4:** Admin assets
  ```bash
  git add assets/css/admin-meta-box.css assets/js/admin-meta-box.js
  git commit -m "feat(admin): add meta box assets

  - Add admin CSS for styling
  - Add admin JS for dynamic fields
  - Enqueue assets correctly

  Addresses: Phase 2 deliverable (admin assets)"
  git push
  ```

### PR Template
**Title:** `Phase 2: Admin Interface - Meta Boxes & Fields`

Use `/finish-phase 2` to auto-generate PR description.

### File Ownership
**Phase 2 owns:**
- `includes/admin/class-gap-meta-box.php`
- `assets/css/admin-meta-box.css`
- `assets/js/admin-meta-box.js`

**Do not modify Phase 2.5 files:**
- `includes/class-gap-conflict-detector.php`

---

## Next Steps

After this phase is complete:

1. Run `/review-phase 2` to check security and code quality
2. Run `/test-component meta-box` to verify functionality
3. Run `/finish-phase 2` to create pull request
4. **Phase 3 cannot start until both Phase 2 AND Phase 2.5 are merged**

**Note:** Phase 2.5 (Conflict Detection) can run in parallel with this phase.

---

## References

- Master Plan: `@TRACKING-SCRIPT-MANAGER-PLAN.md` (Lines 420-578)
- WordPress Meta Box API: https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/
- Data Sanitization: https://developer.wordpress.org/apis/security/sanitizing-securing-output/
- Security Best Practices: https://developer.wordpress.org/apis/security/
