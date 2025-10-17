---
description: Execute all 9 tasks in EPIC-02 (Admin Interface & Settings)
---

You are being instructed to execute EPIC-02 development. Follow these instructions immediately:

**⚠️ CRITICAL PRE-FLIGHT VALIDATION:**

BEFORE invoking the Task tool, you MUST validate the workflow:

1. Check current branch: `git branch --show-current`
2. If on "main" → STOP and ERROR:
   ```
   ❌ ERROR: Cannot execute EPIC on main branch
   ✅ Required: Use /start-epic 02 first
   ```
3. If NOT on "epic-02-admin-interface" → Check if branch exists
   - If exists: `git checkout epic-02-admin-interface`
   - If not: STOP and ERROR:
     ```
     ❌ ERROR: Epic branch not found
     ✅ Required: Use /start-epic 02 first
     ```
4. Only proceed if on epic-02-admin-interface branch

After validation passes, use the Task tool:
- subagent_type: "general-purpose"
- description: "Execute EPIC-02 Admin Interface & Settings"
- prompt:

```
Execute EPIC-02 (Admin Interface & Settings) completely. This is ACTUAL EXECUTION.

## Tasks (9 total)
- US-02.1: Custom Post Type Registration
- US-02.2: Add Meta Box UI
- US-02.3: Save Meta Box Data
- US-02.4: Settings Page UI
- US-02.5: Settings Validation
- TT-02.1: Register tracking_script CPT
- TT-02.2: Create Meta Box Class
- TT-02.3: Implement Save Handler
- TT-02.4: Create Settings Page

Reference: docs/tickets/EPIC-02-admin-interface/
Process: Initialize → Execute each task → Validate → Report
Start immediately.
```

**Invoke Task tool now.**