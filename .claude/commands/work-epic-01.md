---
description: Execute all 9 tasks in EPIC-01 (Foundation & Core Plugin)
---

You are being instructed to execute EPIC-01 development. Follow these instructions immediately:

**⚠️ CRITICAL PRE-FLIGHT VALIDATION:**

BEFORE invoking the Task tool, you MUST validate the workflow:

1. Check current branch: `git branch --show-current`
2. If on "main" → STOP and ERROR:
   ```
   ❌ ERROR: Cannot execute EPIC on main branch
   ✅ Required: Use /start-epic 01 first
   ```
3. If NOT on "epic-01-foundation" →Check if branch exists
   - If exists: `git checkout epic-01-foundation`
   - If not: STOP and ERROR:
     ```
     ❌ ERROR: Epic branch not found
     ✅ Required: Use /start-epic 01 first
     ```
4. Only proceed if on epic-01-foundation branch

After validation passes, use the Task tool with these parameters:

- subagent_type: "general-purpose"
- description: "Execute EPIC-01 Foundation & Core Plugin"
- prompt:

```
You are the epic-orchestrator-executor agent. Execute EPIC-01 (Foundation & Core Plugin) completely.

CRITICAL: This is ACTUAL EXECUTION. You will create files, write code, make commits.

## Your Mission
Execute all 9 tasks in EPIC-01 sequentially:
- US-01.1: Plugin Activation (Main Plugin File)
- US-01.2: Autoloader Implementation
- US-01.3: Main Plugin Class
- US-01.4: Admin Class Foundation
- US-01.5: Settings Storage & Retrieval
- TT-01.1: Create Main Plugin File
- TT-01.2: Implement PSR-4 Autoloader
- TT-01.3: Create Base Plugin Class
- TT-01.4: Create Admin Class

## Reference Files
- Agent instructions: .claude/agents/epic-orchestrator-executor.md
- Development plan: docs/development/MASTER-DEVELOPMENT-PLAN.md
- Task specifications: docs/tickets/EPIC-01-foundation/

Follow the same 4-phase execution process as EPIC-00:
1. Initialize: Read plan, create todo list
2. Execute: For each task - read spec, implement, validate, commit, track
3. Validate: Verify all complete
4. Report: Generate completion report

Start immediately. Execute all 9 tasks.
```

**INSTRUCTION TO CLAUDE: Invoke the Task tool immediately. Do not explain, just execute.**