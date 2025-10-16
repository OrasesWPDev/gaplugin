# /work-pause

Pause orchestration immediately at current point.

**Usage:**
```
/work-pause [--reason="reason"]
```

**Parameters:**
- `--reason` - Optional reason for pause

**What It Does:**
1. Stops execution immediately
2. Preserves current state
3. Marks all work completed so far
4. Provides resume instructions

**Example Output:**
```
⏸️ ORCHESTRATION PAUSED

Epic: EPIC-01
Status: Paused at US-01.4 (implementation complete)
Reason: User requested pause

Current State:
├─ Completed: 4/9 tickets (44%)
├─ Time Elapsed: 3h 15m
├─ Last Commit: 4a1b2c3d (US-01.4 implementation)
└─ All Quality Gates Passed: ✅

To Resume:
/work-resume

To Abort:
/work-rollback --epic
```

**See also:**
- [work-resume](work-resume.md)
- [work-rollback](work-rollback.md)
