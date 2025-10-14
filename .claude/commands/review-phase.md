# Review Phase Command

Review a completed phase for security and code quality issues.

## Usage
```
/review-phase [phase-number]
```

## What This Does

This command triggers both security scanning and code quality review for a specific phase:

1. **Security Scan** - Invokes `wp-security-scanner` agent to check for:
   - Missing nonce verification
   - Missing capability checks
   - Unsanitized input
   - Unescaped output
   - SQL injection vulnerabilities
   - CSRF issues

2. **Code Quality Review** - Invokes `wp-code-reviewer` agent to check for:
   - WordPress Coding Standards violations
   - DRY principle violations
   - KISS principle violations
   - Function complexity issues
   - Class responsibility issues

## Examples

```bash
# Review Phase 1 (Foundation)
/review-phase 1

# Review Phase 2 (Admin)
/review-phase 2

# Review Phase 2.5 (Conflict Detection)
/review-phase 2.5

# Review Phase 3 (Frontend)
/review-phase 3

# Review Phase 4 (Testing)
/review-phase 4
```

## Phase Review Scope

### Phase 1 - Foundation
**Files to review:**
- `ga-plugin.php` (main plugin file)
- `includes/class-gap-post-type.php` (CPT registration)
- `includes/class-gap-autoloader.php` (autoloader)

**Focus:**
- Plugin header security
- CPT registration best practices
- Autoloader implementation
- Constants definition

### Phase 2 - Admin
**Files to review:**
- `includes/admin/class-gap-meta-box.php` (meta boxes)
- `includes/admin/class-gap-meta-handler.php` (save handlers)
- `assets/js/admin-meta-box.js` (admin JS)
- `assets/css/admin-meta-box.css` (admin CSS)

**Focus:**
- Nonce verification on all save operations
- Capability checks on all admin actions
- Input sanitization on all fields
- Output escaping in meta box rendering

### Phase 2.5 - Conflict Detection
**Files to review:**
- `includes/class-gap-conflict-detector.php` (detector class)

**Focus:**
- Regex pattern robustness
- HTML parsing security
- Cache implementation
- Performance optimization

### Phase 3 - Frontend
**Files to review:**
- `includes/frontend/class-gap-frontend-output.php` (output handler)

**Focus:**
- Script output escaping
- Scope filtering logic
- Caching strategy
- Hook usage correctness

### Phase 4 - Testing
**Files to review:**
- All files (comprehensive review)
- Any testing files created

**Focus:**
- Complete security audit
- Performance testing results
- Code quality across entire plugin
- Documentation completeness

## Implementation

When this command is executed, perform the following steps:

1. **Validate phase number:**
   ```
   Valid phases: 1, 2, 2.5, 3, 4
   ```

2. **Launch security scanner agent:**
   ```
   Use Task tool to launch wp-security-scanner agent
   Point it to the appropriate files for this phase
   Wait for security report
   ```

3. **Launch code reviewer agent:**
   ```
   Use Task tool to launch wp-code-reviewer agent
   Point it to the appropriate files for this phase
   Wait for code review report
   ```

4. **Compile results:**
   ```
   Combine both reports
   Highlight critical issues
   Provide actionable next steps
   ```

5. **Output summary:**
   ```
   # Phase [X] Review Summary

   ## Security Issues
   - Critical: [count]
   - High: [count]
   - Medium: [count]
   - Low: [count]

   ## Code Quality Issues
   - Critical: [count]
   - Style: [count]
   - Refactoring: [count]

   ## Recommendations
   [List of recommended fixes]

   ## Next Steps
   [What to do with these findings]
   ```

## Success Criteria

A phase review is complete when:
- [ ] Security scanner has checked all files in scope
- [ ] Code reviewer has checked all files in scope
- [ ] All critical and high-priority issues are documented
- [ ] Specific fixes are recommended for each issue
- [ ] Summary report is generated

## When to Use

Use this command:
- **After completing any phase** - Before moving to the next phase
- **Before marking phase as done** - Ensure quality standards met
- **When debugging issues** - May uncover root cause
- **Before deployment** - Final check of entire plugin

## Notes

- This is a **read-only** operation - agents won't modify code
- Both agents use only Read, Grep, and Glob tools
- Review focuses on WordPress-specific patterns
- All issues include specific line numbers
- Fixes are recommended but not implemented

## Related Commands

- `/test-component` - Test individual components
- `/build-phase` - Build a specific phase
- `/switch-to-sequential` - Change workflow approach
