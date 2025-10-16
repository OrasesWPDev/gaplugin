# /work-status

Show current orchestration status during epic execution.

**Usage:**
```
/work-status [--details] [--tickets] [--gates]
```

**Parameters:**
- `--details` - Show detailed breakdown
- `--tickets` - Show all ticket status
- `--gates` - Show quality gate results

**What It Does:**
1. Shows current epic execution status
2. Lists active tickets being worked on
3. Shows completion percentage
4. Lists next tickets in queue
5. Reports any issues or blockers
6. Shows time elapsed vs. estimate

**Example Output:**
```
🔄 EPIC-01 EXECUTION IN PROGRESS

Progress: 4/9 tickets (44%)
Time Elapsed: 3h 15m | Estimate: 5-6h | On Schedule: ✅

Completed:
✅ US-01.1: Plugin Activation (2h 5m)
✅ US-01.2: Plugin Constants (28m)
✅ US-01.3: Autoloader (58m)
✅ TT-01.1: Main Plugin File (2h)

Current Work:
🔄 US-01.4: Activation/Deactivation (45m into 1.5h estimate)
   Agent: wordpress-developer
   Status: Implementation complete, standards validation in progress

Quality Gates Passed: 4/4 ✅

Next Up:
⏳ US-01.5: Plugin Initialization
⏳ TT-01.2: Activator Class
⏳ TT-01.3: Placeholder Classes

Next Checkpoint: After US-01.4 (5/9 tickets)
ETA: ~30 minutes
```

**See also:**
- [Orchestration Workflow](../docs/orchestration/orchestration-workflow.md)
- [work-epic](work-epic.md)
