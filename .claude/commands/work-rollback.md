# /work-rollback

Rollback orchestration to a previous state for retry or recovery.

**Usage:**
```
/work-rollback [<ticket-id>] [--count=N] [--epic]
```

**Parameters:**
- `<ticket-id>` - Rollback specific ticket (e.g., US-01.3)
- `--count=N` - Rollback last N tickets
- `--epic` - Rollback entire epic

**What It Does:**
1. Reverts code changes
2. Removes commits
3. Resets branch to previous state
4. Preserves ticket files for retry
5. Allows fixing issues and retrying

**Examples:**

```bash
# Rollback one ticket and retry
/work-rollback US-01.3

# Rollback last 3 tickets
/work-rollback --count=3

# Rollback entire epic (full restart)
/work-rollback --epic
```

**Output Shows:**
- What's being reverted
- Changes removed
- Time restored
- Ready for retry instructions

**See also:**
- [work-pause](work-pause.md)
- [Failure Recovery](../docs/orchestration/failure-recovery.md)
