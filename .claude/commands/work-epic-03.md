---
description: Execute all 9 tasks in EPIC-03 (Conflict Detection & Resolution)
---

You are being instructed to execute EPIC-03 development. Follow these instructions immediately:

**⚠️ CRITICAL PRE-FLIGHT VALIDATION:**

BEFORE invoking the Task tool, you MUST validate the workflow:

1. Check current branch: `git branch --show-current`
2. If on "main" → STOP and ERROR:
   ```
   ❌ ERROR: Cannot execute EPIC on main branch
   ✅ Required: Use /start-epic 03 first
   ```
3. If NOT on "epic-03-conflict-detection" → Check if branch exists
   - If exists: `git checkout epic-03-conflict-detection`
   - If not: STOP and ERROR:
     ```
     ❌ ERROR: Epic branch not found
     ✅ Required: Use /start-epic 03 first
     ```
4. Only proceed if on epic-03-conflict-detection branch

After validation passes, use the Task tool:
- subagent_type: "general-purpose"
- description: "Execute EPIC-03 Conflict Detection & Resolution"
- prompt:

```
Execute EPIC-03 (Conflict Detection & Resolution) completely. This is ACTUAL EXECUTION.

## Tasks (9 total)
- US-03.1: Detect Existing GA Tags
- US-03.2: Display Conflict Warnings
- US-03.3: Conflict Resolution Options
- US-03.4: Test Conflict Detection
- US-03.5: Handle Edge Cases
- US-03.6: Performance Optimization
- TT-03.1: Create Conflict_Detector Class
- TT-03.2: Integrate Meta Boxes
- TT-03.3: Add Resolution Handler

Reference: docs/tickets/EPIC-03-conflict-detection/
Process: Initialize → Execute each task → Validate → Report
Start immediately.
```

**Invoke Task tool now.**