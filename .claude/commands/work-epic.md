---
description: Execute all development tasks in a specific epic automatically
---
# /work-epic

! bash /Users/chadmacbook/projects/gaplugin-v2/tools/epic_executor.sh {epic_number}

**CRITICAL INSTRUCTION TO CLAUDE:**

If the bash script above outputs "EXECUTION TRIGGER CREATED", you MUST IMMEDIATELY invoke the Task tool with these exact parameters:

```
Tool: Task
Parameters:
  subagent_type: "general-purpose"
  description: "Execute EPIC-{epic_number} Development"
  prompt: """
You are the epic-orchestrator-executor agent. Execute EPIC-{epic_number} completely.

CRITICAL: This is ACTUAL EXECUTION, not planning. You will:
1. Create actual files
2. Write actual code
3. Make actual git commits
4. Update the MASTER-DEVELOPMENT-PLAN.md with [✓] marks

Reference files:
- Agent definition: .claude/agents/epic-orchestrator-executor.md
- Development plan: docs/development/MASTER-DEVELOPMENT-PLAN.md
- Epic tasks: docs/tickets/EPIC-{epic_number}-*/

## Phase 1: Initialize
1. Read MASTER-DEVELOPMENT-PLAN.md to get all tasks for EPIC-{epic_number}
2. Count total tasks (user stories + technical tasks)
3. Create execution todo list

## Phase 2: Execute Each Task
For EACH task in sequential order:

1. Read the task specification file
2. Implement the actual code/files required
3. Validate the implementation
4. Create a git commit with proper message
5. Update MASTER-DEVELOPMENT-PLAN.md - change [ ] to [✓]
6. Report task completion

## Phase 3: Quality Assurance
1. Run validation on all created files
2. Ensure all tasks are marked complete
3. Verify git commits were created

## Phase 4: Final Report
Generate completion report showing:
- Tasks completed (X/X)
- Files created
- Commits made
- Epic status: COMPLETE

Start immediately with Phase 1.
"""
```

**DO NOT** explain what will happen. **DO NOT** describe the process. **IMMEDIATELY INVOKE THE TASK TOOL** if the trigger was created.

---

## What This Command Does

The `/work-epic` command executes all development tasks in a specific epic:

**Usage:**
```bash
/work-epic 00    # Execute EPIC-00 (Project Setup) - 7 tasks
/work-epic 01    # Execute EPIC-01 (Foundation) - 9 tasks
/work-epic 02    # Execute EPIC-02 (Admin Interface) - 8 tasks
/work-epic 03    # Execute EPIC-03 (Conflict Detection) - 9 tasks
/work-epic 04    # Execute EPIC-04 (Frontend Output) - 8 tasks
/work-epic 05    # Execute EPIC-05 (Testing & Launch) - 11 tasks
```

**Execution Flow:**
1. Creates execution trigger via bash script
2. Invokes epic-orchestrator-executor agent
3. Agent reads all tasks from MASTER-DEVELOPMENT-PLAN.md
4. Executes each task sequentially:
   - Reads task specification
   - Implements code
   - Validates quality
   - Creates git commit
   - Updates progress
5. Runs quality assurance
6. Generates completion report

**Expected Results:**
- All task files created
- All code implemented
- All commits made
- MASTER-DEVELOPMENT-PLAN.md updated with [✓] marks
- Epic marked complete

**Dependencies:**
- EPIC-00 → EPIC-01 → (EPIC-02, 03, 04 parallel) → EPIC-05