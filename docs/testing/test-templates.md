# Test Report Templates

Standard formats for documenting test results.

## Complete Test Report Template

```markdown
# Test Report: Epic XX

**Environment:** Local WordPress (http://localhost:10029/)
**Date:** YYYY-MM-DD HH:MM:SS
**Tester:** [Name]
**Status:** ✅ PASSED / ❌ FAILED

## Summary

| Category | Tests | Pass | Fail | Status |
|----------|-------|------|------|--------|
| Deployment | X | X | 0 | ✅ |
| Activation | X | X | 0 | ✅ |
| CPT | X | X | 0 | ✅ |
| Meta Fields | X | X | 0 | ✅ |
| Frontend | X | X | 0 | ✅ |
| Conflicts | X | X | 0 | ✅ |
| Security | X | X | 0 | ✅ |
| Standards | X | X | 0 | ✅ |

**Total:** XX/XX tests passed

## Detailed Results

### Deployment ✅
- [X] Files synced to local
- [X] Backup created
- [X] Permissions correct

### Activation ✅
- [X] Plugin activates
- [X] No errors in debug.log
- [X] Menu appears

[Additional categories...]

## Issues Found

None

## Approval

✅ **APPROVED FOR PR CREATION**

All quality gates passed.
Ready for code review.

---
*Generated: 2025-10-16*
*Report ID: epic-XX-20251016-000000*
```

## Quick Pass/Fail Template

```markdown
# Test Summary: Epic XX

**Status:** ✅ PASSED
**Tests:** 49/49 passed
**Issues:** 0

Approved for PR creation.
```

## Failure Report Template

```markdown
# Test Report: Epic XX

**Status:** ❌ FAILED
**Tests:** 45/49 passed (5 failed, 1 warning)
**Issues:** 3

## Failed Tests

❌ Meta Fields: Scope selector not hiding page selector
- Expected: Page selector hidden when Global selected
- Actual: Page selector always visible
- Recommendation: Check JavaScript event handler

❌ Frontend Output: body_top placement outputting in footer
- Expected: Scripts in <body> tag
- Actual: Scripts appearing before </body>
- Files: includes/class-gap-frontend.php (line 45)

❌ Conflict Detection: HTML comments not present
- Expected: <!-- GAP: [reason] -->
- Actual: No HTML comments in output
- Files: includes/class-gap-frontend.php

## Warnings

⚠️ Coding Standards: 15 PHPCS warnings (spacing, formatting)
- Recommendation: Fix before next PR

## Blocking Issues

This PR is **BLOCKED** until:
1. [ ] Meta fields scope selector fixed
2. [ ] Frontend output placement corrected
3. [ ] Conflict detection HTML comments added

Run tests again after fixes.
```

## See Also

- [Quality Gates](quality-gates.md) - Requirements that must pass
- [Test Procedures](test-procedures.md) - How to run tests
