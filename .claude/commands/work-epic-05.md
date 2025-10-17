---
description: Execute all 11 tasks in EPIC-05 (Testing, Security & Launch Prep)
---

You are being instructed to execute EPIC-05 development. Follow these instructions immediately:

**⚠️ CRITICAL PRE-FLIGHT VALIDATION:**

BEFORE invoking the Task tool, you MUST validate the workflow:

1. Check current branch: `git branch --show-current`
2. If on "main" → STOP and ERROR:
   ```
   ❌ ERROR: Cannot execute EPIC on main branch
   ✅ Required: Use /start-epic 05 first
   ```
3. If NOT on "epic-05-testing-launch" → Check if branch exists
   - If exists: `git checkout epic-05-testing-launch`
   - If not: STOP and ERROR:
     ```
     ❌ ERROR: Epic branch not found
     ✅ Required: Use /start-epic 05 first
     ```
4. Only proceed if on epic-05-testing-launch branch

After validation passes, use the Task tool:
- subagent_type: "general-purpose"
- description: "Execute EPIC-05 Testing, Security & Launch Prep"
- prompt:

```
Execute EPIC-05 (Testing, Security & Launch Prep) completely. This is ACTUAL EXECUTION.

## Tasks (11 total)
- US-05.1: Write Unit Tests
- US-05.2: Manual QA Testing
- US-05.3: Security Audit
- US-05.4: Performance Testing
- US-05.5: Accessibility Review
- TT-05.1: Setup PHPUnit
- TT-05.2: Write Core Tests
- TT-05.3: Test Conflict Detection
- TT-05.4: Security Hardening
- TT-05.5: WordPress.org Compliance
- TT-05.6: Final Documentation

Reference: docs/tickets/EPIC-05-testing-launch/
Process: Initialize → Execute each task → Validate → Report
Start immediately.
```

**Invoke Task tool now.**