---
description: Execute all 7 tasks in EPIC-00 (Project Setup & Infrastructure) automatically
---

You are being instructed to execute EPIC-00 development. Follow these instructions immediately:

**⚠️ CRITICAL PRE-FLIGHT VALIDATION:**

BEFORE invoking the Task tool, you MUST validate the workflow:

1. Check current branch: `git branch --show-current`
2. If on "main" → STOP and ERROR:
   ```
   ❌ ERROR: Cannot execute EPIC on main branch
   ✅ Required: Use /start-epic 00 first
   ```
3. If NOT on "epic-00-project-setup" → Check if branch exists
   - If exists: `git checkout epic-00-project-setup`
   - If not: STOP and ERROR:
     ```
     ❌ ERROR: Epic branch not found
     ✅ Required: Use /start-epic 00 first
     ```
4. Only proceed if on epic-00-project-setup branch

After validation passes:

1. Use the Task tool with these parameters:
   - subagent_type: "general-purpose"
   - description: "Execute EPIC-00 Project Setup"
   - prompt: See below

2. Prompt for the Task tool:

```
You are the epic-orchestrator-executor agent. Execute EPIC-00 (Project Setup & Infrastructure) completely.

CRITICAL: This is ACTUAL EXECUTION. You will create files, write code, make commits.

## Your Mission
Execute all 7 tasks in EPIC-00 sequentially:
- US-00.1: Git Repository Setup
- US-00.2: Directory Structure
- US-00.3: Essential Project Files
- US-00.4: Development Workflow
- TT-00.1: Configure Git Repository
- TT-00.2: Create Directory Structure
- TT-00.3: Create Project Files

## Reference Files
- Agent instructions: .claude/agents/epic-orchestrator-executor.md
- Development plan: docs/development/MASTER-DEVELOPMENT-PLAN.md
- Task specifications: docs/tickets/EPIC-00-project-setup/

## Execution Process

### Phase 1: Initialize (Required First)
1. Read docs/development/MASTER-DEVELOPMENT-PLAN.md
2. Extract all 7 EPIC-00 tasks
3. Use TodoWrite to create execution checklist with all 7 tasks
4. Verify prerequisites are met

### Phase 2: Execute Each Task
For EACH of the 7 tasks in order:

**Step 1: Read Specification**
- Open task file from docs/tickets/EPIC-00-project-setup/
- Extract acceptance criteria and requirements

**Step 2: Implement**
- Create required files/directories
- Write necessary code/configuration
- Follow WordPress and project standards

**Step 3: Validate**
- Verify implementation meets acceptance criteria
- Check file permissions, syntax, structure

**Step 4: Commit**
- Create git commit with format: "feat(us-00.X): [description]"
- Include Co-Authored-By: Claude tag

**Step 5: Track Progress**
- Update MASTER-DEVELOPMENT-PLAN.md: change [ ] to [✓]
- Mark todo as complete
- Report task completion

### Phase 3: Final Validation
1. Verify all 7 tasks completed
2. Check all files created
3. Verify all commits made
4. Confirm MASTER-DEVELOPMENT-PLAN.md updated

### Phase 4: Report
Generate completion report with:
- Tasks: 7/7 complete
- Files created list
- Commits made list
- Status: EPIC-00 COMPLETE
- Next: Ready for EPIC-01

## Critical Requirements
✅ Create ACTUAL files (not placeholders)
✅ Make ACTUAL git commits (not simulated)
✅ Update ACTUAL MASTER-DEVELOPMENT-PLAN.md
✅ Use TodoWrite to track each task
✅ Report progress in real-time

Start Phase 1 NOW. Execute all 7 tasks.
```

**INSTRUCTION TO CLAUDE: Invoke the Task tool immediately with the above parameters. Do not explain, just execute.**