# /work-epic

**INSTRUCTION: You are now executing the /work-epic command.**

**EPIC NUMBER REQUESTED:** {epic_number}

---

## YOUR TASK - EXECUTE NOW

You MUST invoke the epic-orchestrator-executor agent to autonomously execute all tasks in EPIC-{epic_number}.

**DO NOT JUST EXPLAIN WHAT WILL HAPPEN - ACTUALLY INVOKE THE AGENT AND EXECUTE THE WORK.**

### Execute Using Task Tool

Use the Task tool to invoke the general-purpose agent as the epic-orchestrator-executor:

**Tool Parameters:**
- subagent_type: "general-purpose"
- description: "Execute EPIC-{epic_number} - Autonomous Epic Development"
- prompt: See below

**Prompt for Agent:**

```
You are the epic-orchestrator-executor agent. Your task is to autonomously execute EPIC-{epic_number} from start to finish.

CRITICAL: You must actually execute all tasks. This is not a planning exercise - you will implement actual code, create files, and make git commits.

## Reference Material
- Agent Instructions: .claude/agents/epic-orchestrator-executor.md
- Development Plan: docs/development/MASTER-DEVELOPMENT-PLAN.md
- Epic Tasks Directory: docs/tickets/EPIC-{epic_number}-*/

## Execution Steps

### Phase 1: Initialize
1. Read docs/development/MASTER-DEVELOPMENT-PLAN.md
2. Find all tasks for EPIC-{epic_number} (both US-{epic_number}.X and TT-{epic_number}.X)
3. Verify dependencies are met
4. Create a mental execution plan

### Phase 2: Execute Each Task Sequentially

For EACH task in the epic:

1. **Read Task Specification**
   - Open the ticket file: docs/tickets/EPIC-{epic_number}-*/[user-stories|technical-tasks]/[task-file].md
   - Extract acceptance criteria, requirements, and implementation details

2. **Implement Task**
   - Use the Task tool to invoke wordpress-developer agent
   - Pass complete ticket specification
   - Ask developer to implement according to specs
   - Wait for completion

3. **Validate Quality**
   - Use the Task tool to invoke wordpress-standards-validator
   - Request code quality validation
   - Stop if validation fails
   - Continue if validation passes

4. **Commit Work**
   - Use the Task tool to invoke git-workflow-specialist
   - Request git commit of completed work
   - Include task ID in commit message

5. **Update Progress**
   - Mark task complete in docs/development/MASTER-DEVELOPMENT-PLAN.md
   - Change [ ] to [✓] for completed task
   - Update status tracking

6. **Report Task Complete**
   - Report task number, status, and outcome

### Phase 3: Quality Assurance (After All Tasks Complete)

1. Use the Task tool to invoke local-testing-specialist
2. Request comprehensive QA testing for EPIC-{epic_number}
3. Verify all quality gates passed
4. Report QA results

### Phase 4: Final Report

When EPIC-{epic_number} is completely done:

1. Update MASTER-DEVELOPMENT-PLAN.md status matrix
2. Mark EPIC-{epic_number} as complete (0/X → X/X)
3. Generate completion report showing:
   - All tasks completed (X/X)
   - All commits made
   - All files created
   - Quality gates passed
   - Next epic ready to start

## Success Criteria

✅ All tasks in EPIC-{epic_number} executed
✅ All code implemented and validated
✅ All commits created
✅ MASTER-DEVELOPMENT-PLAN.md updated
✅ Completion report generated
✅ Ready for next epic

## Start Execution NOW

Begin Phase 1 immediately. Execute all tasks. Report progress and completion.
```

---

## For User Reference (After Execution)

**Usage:**
```bash
/work-epic 00    # Execute EPIC-00 (Project Setup) - 7 tasks
/work-epic 01    # Execute EPIC-01 (Foundation) - 9 tasks
/work-epic 02    # Execute EPIC-02 (Admin Interface) - 8 tasks
/work-epic 03    # Execute EPIC-03 (Conflict Detection) - 9 tasks
/work-epic 04    # Execute EPIC-04 (Frontend Output) - 8 tasks
/work-epic 05    # Execute EPIC-05 (Testing & Launch) - 11 tasks
```

**What Happens:**
1. Epic-orchestrator-executor is invoked
2. All tasks in epic are executed sequentially
3. Each task: implement → validate → commit → track
4. QA testing runs after all tasks complete
5. Development plan is updated
6. Completion report is generated

**Expected Output:**
- Real-time progress for each task
- Files created
- Git commits made
- Validation results
- Final completion report

**Epic Dependency Chain:**
```
EPIC-00 → EPIC-01 → (EPIC-02, 03, 04 parallel) → EPIC-05
```

**Next Step After Epic Complete:**
- `/work-epic 01` - Execute next epic
- `/epic-status 00` - View completed epic status
- `/visualize --refresh-rate 2 --no-clear` - Monitor dashboard

---

**See Also:**
- [Epic Orchestrator Executor](../agents/epic-orchestrator-executor.md)
- [Master Development Plan](../docs/development/MASTER-DEVELOPMENT-PLAN.md)
- [Agent Registry](../agents/agent-registry.json)
