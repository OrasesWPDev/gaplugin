---
description: Index of epic execution commands - use specific /work-epic-XX commands to execute
---
# /work-epic

This command is now split into individual epic-specific commands for reliable execution.

## Available Commands

Execute specific epics using these commands:

### EPIC-00: Project Setup & Infrastructure (7 tasks)
```
/work-epic-00
```
**Status:** Ready to execute
**Duration:** ~1.6 hours
**Dependencies:** None (starting point)

### EPIC-01: Foundation & Core Plugin (9 tasks)
```
/work-epic-01
```
**Status:** Blocked until EPIC-00 complete
**Duration:** ~2.5 hours
**Dependencies:** EPIC-00

### EPIC-02: Admin Interface & Settings (9 tasks)
```
/work-epic-02
```
**Status:** Blocked until EPIC-01 complete
**Duration:** ~4.5 hours
**Dependencies:** EPIC-01

### EPIC-03: Conflict Detection & Resolution (9 tasks)
```
/work-epic-03
```
**Status:** Blocked until EPIC-01 complete
**Duration:** ~5.5 hours
**Dependencies:** EPIC-01

### EPIC-04: Frontend Output & Script Injection (8 tasks)
```
/work-epic-04
```
**Status:** Blocked until EPIC-01 complete
**Duration:** ~4.0 hours
**Dependencies:** EPIC-01

### EPIC-05: Testing, Security & Launch Prep (11 tasks)
```
/work-epic-05
```
**Status:** Blocked until EPIC-02, 03, 04 complete
**Duration:** ~12.5 hours
**Dependencies:** EPIC-02, EPIC-03, EPIC-04

## Execution Sequence

The recommended execution order:

```
1. /work-epic-00  (Required first - sets up project structure)
   ↓
2. /work-epic-01  (Required second - creates core plugin)
   ↓
3. Run in parallel (all depend on EPIC-01):
   - /work-epic-02  (Admin interface)
   - /work-epic-03  (Conflict detection)
   - /work-epic-04  (Frontend output)
   ↓
4. /work-epic-05  (Final - testing & launch)
```

## What Each Command Does

When you run `/work-epic-XX`, it:

1. ✅ **Invokes epic-orchestrator-executor agent** automatically
2. ✅ **Reads all task specifications** from docs/tickets/EPIC-XX/
3. ✅ **Implements actual code** and creates files
4. ✅ **Creates git commits** for each completed task
5. ✅ **Updates MASTER-DEVELOPMENT-PLAN.md** with [✓] marks
6. ✅ **Reports completion** with full status

## Monitoring Progress

While an epic executes, monitor progress with:

```bash
# Real-time dashboard
python3 tools/agent_visualizer.py --refresh-rate 2 --no-clear

# Or check status
/epic-status XX
```

## Troubleshooting

If a command doesn't execute:
1. Verify dependencies are met (check MASTER-DEVELOPMENT-PLAN.md)
2. Ensure you're using the correct command format: `/work-epic-00` (not `/work-epic 00`)
3. Check that previous epics completed successfully
4. Review git log to verify commits were created

## See Also

- [Epic Orchestrator Executor](../agents/epic-orchestrator-executor.md) - Agent that executes tasks
- [Master Development Plan](../../docs/development/MASTER-DEVELOPMENT-PLAN.md) - All task specifications
- [Agent Registry](../agents/agent-registry.json) - Available agents
