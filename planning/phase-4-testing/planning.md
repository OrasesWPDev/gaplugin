# Phase 4: Testing & Security Audit

**Estimated Time:** 4-5 hours

**Status:** Not Started

## Overview

This is the final phase: comprehensive testing, security audit, code quality review, documentation verification, and deployment preparation.

## Dependencies

- **All previous phases (1, 2, 2.5, 3)** must be complete

## Deliverables

- Complete manual testing of all features
- Security audit report (from wp-security-scanner)
- Code quality review report (from wp-code-reviewer)
- Documentation review and updates
- Deployment checklist completion
- v1.0.0 release preparation

## Completion Criteria

- [ ] All manual tests pass
- [ ] Security audit clean (no critical/high issues)
- [ ] Code review clean (no critical issues)
- [ ] Documentation complete and accurate
- [ ] All naming uses GAP_/gap_/ga-plugin
- [ ] No debug code in production
- [ ] Ready for deployment

---

## Manual Testing Checklist

### 1. Activation/Deactivation

- [ ] Plugin activates without errors
- [ ] CPT appears in admin menu after activation
- [ ] Deactivation doesn't delete data
- [ ] Reactivation preserves all settings
- [ ] No PHP errors/warnings on activation
- [ ] No PHP errors/warnings on deactivation

### 2. Create Tracking Script

- [ ] Can create new tracking script
- [ ] Title saves correctly
- [ ] Tracking code field saves
- [ ] Script type selection saves
- [ ] Custom code field saves (for custom type)
- [ ] Placement selection saves
- [ ] Scope selection saves
- [ ] Enabled checkbox saves
- [ ] Nonce verification prevents CSRF

### 3. Tracking ID Extraction

- [ ] GA4 IDs automatically extracted on save
- [ ] GTM IDs automatically extracted on save
- [ ] Extracted IDs displayed in admin column
- [ ] Multiple IDs extracted from same script
- [ ] Invalid IDs not extracted

### 4. Duplicate Detection (Admin)

- [ ] Admin warning when duplicate IDs across posts
- [ ] Warning shows which posts have duplicates
- [ ] Warning displays tracking ID and type
- [ ] Edit links work in warning notices
- [ ] No warning when IDs are unique

### 5. Frontend Output

- [ ] **Global Scripts:**
  - [ ] Scripts output on all pages
  - [ ] Scripts in correct location (head or body)
  - [ ] Multiple global scripts all output

- [ ] **Scoped Scripts:**
  - [ ] Posts-only scripts only on single posts
  - [ ] Pages-only scripts only on pages
  - [ ] Home-only scripts only on homepage
  - [ ] Frontend-only excludes admin pages

- [ ] **Disabled Scripts:**
  - [ ] Disabled scripts don't output

- [ ] **Script Order:**
  - [ ] Multiple scripts output in correct order
  - [ ] Order matches menu_order/title sort

### 6. Duplicate Prevention (Frontend)

- [ ] Duplicate GA4 script skipped
- [ ] Duplicate GTM script skipped
- [ ] HTML comment explains skip
- [ ] Conflict logged (when WP_DEBUG enabled)
- [ ] Manual theme script + plugin script = only one outputs
- [ ] Two posts with same ID = warning + first one outputs

### 7. Script Format Accuracy

- [ ] GA4 scripts format correctly
- [ ] GTM head scripts format correctly
- [ ] GTM noscript outputs in body placement
- [ ] Custom scripts output as-is
- [ ] All tracking IDs properly escaped

---

## Security Audit

Use `/review-phase 4` to trigger security scanner on all files.

### Security Checklist

- [ ] All user inputs sanitized
- [ ] All outputs escaped (except intentional script output)
- [ ] Nonces on all forms
- [ ] Capability checks on all admin actions
- [ ] Direct file access prevented (`ABSPATH` check)
- [ ] No SQL injection vulnerabilities
- [ ] No XSS vulnerabilities
- [ ] No CSRF vulnerabilities
- [ ] Error messages don't reveal sensitive info
- [ ] File permissions correct (644 files, 755 dirs)

### Critical Security Areas

1. **Meta Box Save Handler:**
   - Nonce verification
   - Capability check
   - Autosave prevention
   - Input sanitization

2. **Frontend Output:**
   - Tracking ID escaping in URLs
   - Tracking ID escaping in JavaScript
   - Custom code already sanitized (wp_kses_post on save)

3. **Admin Interface:**
   - Output escaping in admin columns
   - Output escaping in meta boxes
   - URL escaping in admin links

---

## Code Quality Review

Use `/review-phase 4` to trigger code reviewer on all files.

### Code Quality Checklist

- [ ] All functions prefixed with `gap_` or in `GAP_` class
- [ ] No `TSM_` or `tsm_` references remain
- [ ] All text domains are `ga-plugin`
- [ ] WordPress Coding Standards followed
- [ ] Yoda conditions used
- [ ] DRY principle followed
- [ ] KISS principle followed
- [ ] Single Responsibility Principle followed
- [ ] No code duplication
- [ ] No complex nested logic
- [ ] Functions under 50 lines
- [ ] Classes focused on single purpose

### WordPress Standards

- [ ] Naming conventions correct
- [ ] Indentation with tabs (not spaces)
- [ ] Brace style correct
- [ ] Spacing correct
- [ ] Comments clear and helpful

---

## Browser Testing

Test frontend output in:

### Browsers

- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)

### Developer Tools Checks

- [ ] Scripts load without errors (Console)
- [ ] No JavaScript errors (Console)
- [ ] Tracking requests fire (Network tab)
- [ ] Scripts appear in correct location (Elements/Inspector)

---

## WordPress Compatibility

Test on:

### WordPress Versions

- [ ] WordPress 6.0
- [ ] WordPress 6.2
- [ ] WordPress 6.4
- [ ] WordPress latest

### PHP Versions

- [ ] PHP 7.4
- [ ] PHP 8.0
- [ ] PHP 8.1
- [ ] PHP 8.2

### Themes

Test with common themes:

- [ ] Twenty Twenty-Four
- [ ] Astra
- [ ] GeneratePress
- [ ] Hello Elementor

### Conflict Testing

- [ ] Works with common caching plugins
- [ ] Works with common SEO plugins
- [ ] No conflicts with other tracking plugins

---

## Documentation Review

### README.md

- [ ] Installation instructions accurate
- [ ] Usage examples clear
- [ ] Requirements listed
- [ ] License information correct
- [ ] Support/issues link provided

### Inline Code Comments

- [ ] Complex logic explained
- [ ] Function purposes documented
- [ ] Parameters documented
- [ ] Return values documented

### Plugin Header

- [ ] Plugin name correct
- [ ] Description accurate
- [ ] Version number correct
- [ ] Requirements correct
- [ ] Author information complete
- [ ] License information complete
- [ ] Text domain correct

---

## Deployment Checklist

### Pre-Release Code Review

- [ ] No `var_dump()`, `print_r()`, `die()` debug code
- [ ] No `error_log()` calls in production
- [ ] All `console.log()` removed from JavaScript
- [ ] No hardcoded URLs or paths
- [ ] No test data or placeholder content

### Files

- [ ] `.gitignore` includes sensitive files
- [ ] `LICENSE.txt` included (GPL v2)
- [ ] Version numbers consistent across files:
  - [ ] `ga-plugin.php` header
  - [ ] `GAP_VERSION` constant
  - [ ] `README.md`
- [ ] All TSM references replaced with GAP
- [ ] All `tracking-script-manager` replaced with `ga-plugin`

### Git & GitHub

- [ ] All changes committed
- [ ] Commit messages descriptive
- [ ] No uncommitted files
- [ ] Version tag created (v1.0.0)
- [ ] GitHub release created
- [ ] Release notes written

---

## Agent Responsibilities

**Primary Agent:** `wp-security-scanner`

- Scans all PHP files for security issues
- Generates security audit report
- Prioritizes issues by severity
- Provides specific fixes

**Primary Agent:** `wp-code-reviewer`

- Reviews all code for quality
- Checks WordPress standards compliance
- Identifies DRY/KISS violations
- Suggests refactoring opportunities

**Support:** All specialists available for fixes

- If security issues found, appropriate specialist fixes
- If code quality issues found, appropriate specialist refactors

---

## Known Issues / Future Enhancements

Document any known issues or planned enhancements:

### Known Limitations (v1.0.0)

- [ ] List any known limitations here
- [ ] Or features intentionally excluded from v1.0

### Future Enhancements (v2.0+)

From master plan Phase 5:

- [ ] Import/Export functionality
- [ ] Script templates (GA4 and GTM)
- [ ] Conditional logic builder (advanced targeting)
- [ ] Performance optimization (object caching)
- [ ] WP-CLI support
- [ ] Multisite support

---

## Release Preparation

### Version 1.0.0 Release Notes

```markdown
# GA Plugin v1.0.0

Initial release of GA Plugin - WordPress tracking script manager.

## Features

- Manage Google Analytics 4 (GA4) and Google Tag Manager (GTM) scripts
- Custom Post Type for tracking scripts
- Automatic tracking ID extraction (GA4 and GTM)
- Duplicate detection across tracking script posts
- Duplicate prevention on frontend output
- Multiple placement options (head, body)
- Scope filtering (global, posts only, pages only, home only, frontend only)
- Conflict logging with WP_DEBUG
- Native WordPress implementation (no dependencies)
- Security-first design (nonces, capabilities, sanitization, escaping)

## Requirements

- WordPress 6.0+
- PHP 7.4+

## Installation

1. Download latest release ZIP
2. Upload via WordPress admin (Plugins → Add New → Upload)
3. Activate plugin
4. Navigate to "Tracking Scripts" menu

## Support

For issues and feature requests:
https://github.com/YOUR-ORG/ga-plugin/issues
```

---

## Git Workflow for This Phase

### Branch Information
- **Branch name:** `phase-4-testing`
- **Created from:** `main` (after ALL previous phases merged)
- **Merges to:** `main`
- **Merge dependencies:** Phase 1, 2, 2.5, AND 3 must all be merged first
- **Final phase:** Unblocks v1.0.0 release

### Starting This Phase
```bash
/start-phase 4
```

**IMPORTANT:** Verify ALL dependencies merged:
```bash
git log main --oneline | grep -E "Phase (1|2|2.5|3)"
# Should show all four phases merged
```

### Commit Strategy

Phase 4 is different - commits are made as issues are discovered and fixed:

- [ ] **Commit after fixing bugs found during testing**
  ```bash
  git add [fixed-files]
  git commit -m "fix([scope]): [description of bug fix]

  - [What was wrong]
  - [How it was fixed]
  - [Test that now passes]

  Addresses: Phase 4 testing ([test category])"
  git push
  ```

- [ ] **Commit after security audit fixes**
  ```bash
  git add [fixed-files]
  git commit -m "security([scope]): [security fix description]

  - [Security issue found]
  - [Fix implemented]
  - [Verification method]

  Addresses: Phase 4 security audit"
  git push
  ```

- [ ] **Commit after code quality improvements**
  ```bash
  git add [improved-files]
  git commit -m "refactor([scope]): [improvement description]

  - [Code quality issue]
  - [Improvement made]
  - [Benefit]

  Addresses: Phase 4 code review"
  git push
  ```

- [ ] **Commit documentation updates**
  ```bash
  git add README.md [other-docs]
  git commit -m "docs: update documentation for v1.0.0

  - Update README
  - Add usage examples
  - Update installation instructions

  Addresses: Phase 4 documentation"
  git push
  ```

### PR Template
**Title:** `Phase 4: Testing & Security Audit`

Use `/finish-phase 4` to auto-generate PR description.

### File Ownership
**Phase 4 can modify ANY file to fix issues found during testing.**

This is the only phase allowed to modify files from previous phases if bugs are found.

### After This Phase Merges
```bash
# Create v1.0.0 release tag
git checkout main
git pull origin main
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0

# Create GitHub release
gh release create v1.0.0 \
  --title "GA Plugin v1.0.0" \
  --notes "Initial release of GA Plugin"
```

---

## Success Criteria

Plugin is ready for v1.0.0 release when:

- [ ] All manual tests pass
- [ ] Security audit shows no critical/high issues
- [ ] Code review shows no critical issues
- [ ] Browser testing complete
- [ ] WordPress compatibility verified
- [ ] Documentation complete
- [ ] Deployment checklist complete
- [ ] Phase 4 PR merged to main
- [ ] GitHub release v1.0.0 created

---

## References

- Master Plan: `@TRACKING-SCRIPT-MANAGER-PLAN.md` (Lines 1130-1463)
- WordPress Plugin Handbook: https://developer.wordpress.org/plugins/
- Security Best Practices: https://developer.wordpress.org/apis/security/
- Coding Standards: https://developer.wordpress.org/coding-standards/
- Plugin Review Guidelines: https://developer.wordpress.org/plugins/wordpress-org/detailed-plugin-guidelines/
