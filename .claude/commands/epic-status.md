# /epic-status

Display current status and progress of an epic.

**Usage:**
```
/epic-status <epic-number>
```

**Parameters:**
- `<epic-number>`: Epic number (00, 01, 02, 03, 04, 05)

**What it does:**
1. Reads epic directory and all ticket files
2. Calculates completion percentage
3. Shows user stories and technical tasks breakdown
4. Identifies blocking issues
5. Displays time estimates
6. Shows available tickets to work on

**Example:**
```
/epic-status 02
```

**Output shows:**
- Overall progress percentage and progress bar
- User stories status (completed, in progress, not started)
- Technical tasks status
- Time spent vs. estimated
- Available tickets with no blockers
- Upstream/downstream epic dependencies
- Blocking issues (if any)
- Recommendations for next work

**Status indicators:**
- ✅ Completed
- 🔄 In Progress (with % complete)
- ⏸️ Not Started
- ⚠️ Blocked

**Use for:**
- Planning what to work on next
- Understanding epic progress
- Identifying blockers
- Checking upstream dependencies
- Reporting status to team

**See also:**
- [Branching Strategy](../docs/git/branching-strategy.md)
- [Ticket System](../docs/development/ticket-system.md)
- [Command Reference](../docs/commands/reference.md)
