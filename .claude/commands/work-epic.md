# /work-epic

Execute complete epic development automatically - all tasks from start to finish with no manual intervention required.

**Usage:**
```
/work-epic <epic-number>
```

**Parameters:**
- `<epic-number>` - Epic number (00, 01, 02, 03, 04, or 05)

**What It Does:**

Invokes the **epic-orchestrator-executor** agent to automatically execute every task in the epic:

1. **Initialization** - Loads epic from MASTER-DEVELOPMENT-PLAN.md, verifies prerequisites
2. **Task Execution Loop** - For each task in epic (sequentially or safe-parallel):
   - Reads task specification file
   - Delegates to wordpress-developer for implementation
   - Validates with wordpress-standards-validator
   - Commits with git-workflow-specialist
   - Updates progress tracking
3. **Quality Assurance** - Runs comprehensive testing with local-testing-specialist
4. **Completion** - Updates status matrix, generates report, ready for next epic

**Result:** Complete epic with all tasks done, committed, and validated. Zero manual intervention needed.

**Examples:**

```bash
# Execute EPIC-00 (Project Setup) - all 7 tasks
/work-epic 00

# Execute EPIC-01 (Foundation) - all 9 tasks
/work-epic 01

# Execute EPIC-02 (Admin Interface) - all 8 tasks
/work-epic 02
```

**Execution Flow:**

```
/work-epic 00
    ↓
Invokes epic-orchestrator-executor agent
    ↓
📋 Initialize EPIC-00 (7 tasks)
    ↓
🚀 Execute US-00.1 (Git Repository Setup)
    ├─ Delegate to wordpress-developer
    ├─ Validate with standards-validator
    ├─ Commit with git-workflow-specialist
    ✓ Complete
    ↓
🚀 Execute US-00.2, US-00.3, US-00.4, TT-00.1, TT-00.2, TT-00.3
    [Same pattern for each task]
    ↓
✅ Quality Assurance (test all 7 tasks)
    ↓
📊 Update MASTER-DEVELOPMENT-PLAN.md (0/7 → 7/7)
    ↓
✅ EPIC-00 COMPLETE - Ready for EPIC-01
```

**Output Shows:**
- Real-time progress for each task
- Files created
- Git commits
- Validation results
- Any blockers or issues
- Final completion report

**See also:**
- [Epic Orchestrator Executor](../agents/epic-orchestrator-executor.md) - Agent that executes this command
- [Master Development Plan](../docs/development/MASTER-DEVELOPMENT-PLAN.md) - Task specifications
