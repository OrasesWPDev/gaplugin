# Checkpoint Guide

Configure how often the orchestrator pauses for your review during epic execution.

---

## Quick Start

```bash
# No checkpoints - full automation
/work-epic 01

# Pause every user story
/work-epic 01 --checkpoint=US

# Pause every 3 tickets
/work-epic 01 --checkpoint=3

# Wait for manual /next-ticket command each time
/work-epic 01 --checkpoint=manual

# Pause every 5 tickets
/work-epic 01 --checkpoint=5
```

---

## Checkpoint Options

### `--checkpoint=none` (Default)
**Best For:** Trusted epics, non-critical code, simple features

**Behavior:**
- No pauses
- Continuous execution to completion
- Fastest path to PR
- Real-time updates via TodoWrite

**When to Use:**
- After successfully completing similar epics
- Straightforward, low-risk work
- High confidence in quality gates
- When speed is priority

**Time Saved:** Full time savings from parallelization realized

---

### `--checkpoint=manual`
**Best For:** Learning, high-risk work, experimentation

**Behavior:**
- Completes each ticket
- Waits for `/next-ticket` command from user
- Doesn't proceed until you approve
- Full control over execution

**When to Use:**
- First few epics (learning the system)
- Highly complex or risky tickets
- Experimental features
- When understanding process is important

**Example Flow:**
```
/work-epic 01
→ Orchestrator: Plan ready, approve? [Approve]
→ 🔄 US-01.1 complete
→ Waiting for /next-ticket command
→ You review code, run tests manually
→ /next-ticket
→ 🔄 US-01.2 complete
→ Waiting for /next-ticket command
...
```

---

### `--checkpoint=US`
**Best For:** Balanced control, phase-level review

**Behavior:**
- Completes all related tickets for each user story
- Pauses after each user story done
- Let's you verify story-level functionality
- Good safety without excessive pauses

**When to Use:**
- Mid-risk features
- When you want to verify story completion
- Learning but not needing per-ticket review
- Standard operating procedure

**Example Flow:**
```
PHASE 1: User Stories
├─ US-01.1 complete ✅
├─ US-01.2 complete ✅
├─ US-01.3 complete ✅
└─ [CHECKPOINT] Review all 3 user stories? [Continue]

PHASE 2: Supporting Tasks
├─ TT-01.1 complete ✅
├─ TT-01.2 complete ✅
└─ [CHECKPOINT] Review 2 technical tasks? [Continue]
...
```

---

### `--checkpoint=N` (e.g., `--checkpoint=3`)
**Best For:** Large epics, milestone reviews

**Behavior:**
- Completes N tickets
- Pauses for checkpoint
- Allows you to verify progress
- Balances speed with oversight

**Options:**
- `--checkpoint=1` - After every ticket (equivalent to manual)
- `--checkpoint=3` - Every 3 tickets (good for 9-ticket epics)
- `--checkpoint=5` - Every 5 tickets (good for 20+ ticket epics)

**When to Use:**
- Large epics with 10+ tickets
- Mixed-risk content (some simple, some complex)
- Standard practice for most epics
- When you want periodic but not constant oversight

**Example: --checkpoint=3**
```
[CHECKPOINT 1] After 3 tickets (33%)
├─ US-01.1 ✅
├─ US-01.2 ✅
├─ US-01.3 ✅
→ Review? [Continue] [Pause] [Rollback]

[CHECKPOINT 2] After 6 tickets (66%)
├─ US-01.4 ✅
├─ US-01.5 ✅
├─ TT-01.1 ✅
→ Review? [Continue] [Pause] [Rollback]

[COMPLETION] All 9 tickets done
→ Merge to PR? [Create PR] [Hold]
```

---

## Checkpoint Decision Point

**What You See at Each Checkpoint:**

```
🛑 CHECKPOINT: 3 Tickets Complete (33%)

═══════════════════════════════════════════
SUMMARY
═══════════════════════════════════════════
Epic: EPIC-01 (Foundation & Core Plugin)
Progress: 3/9 tickets | 33% complete
Time Elapsed: 3h 15m | On Schedule: ✅

COMPLETED TICKETS
✅ US-01.1: Plugin Activation (2h 5m)
✅ US-01.2: Plugin Constants (28m)
✅ US-01.3: Autoloader (58m)

QUALITY GATES
✓ WordPress Standards: 3/3
✓ Security Audit: 3/3
✓ Tests Passing: 3/3
✓ All Commits Done: 3/3

NEXT PHASE (3 hours remaining)
⏳ US-01.4: Activation/Deactivation [1.5h]
⏳ US-01.5: Plugin Initialization [1h]
⏳ TT-01.1: Activator Class [1.5h]

═══════════════════════════════════════════
ACTIONS
═══════════════════════════════════════════
[▶️ Continue] [⏸️ Pause] [👀 Review] [🔙 Rollback] [❌ Abort]
```

### Your Options at Checkpoint

**[▶️ Continue]** - Resume to next checkpoint
- Fastest option
- Orchestrator continues execution
- Next checkpoint reached automatically

**[⏸️ Pause]** - Pause here, stay at this point
- Gives you time to review
- Review code in detail
- Test manually if desired
- Use `/resume` when ready

**[👀 Review]** - Detailed review view
- See all commits made
- View test reports
- Check code changes
- Return to checkpoint decisions

**[🔙 Rollback]** - Undo last ticket(s)
- Reverts last completed ticket
- Fixes issue and retries
- Or skip this ticket

**[❌ Abort]** - Stop execution
- Halts all work immediately
- Preserves what's done
- Return to main branch

---

## Recommended Checkpoint Strategy

### For Different Situations

**Situation 1: First Epic (Learning)**
```bash
/work-epic 00 --checkpoint=manual
```
- Pause after every ticket
- Learn the system
- Understand what each agent does
- Build confidence

**Situation 2: Standard Epic (Normal Work)**
```bash
/work-epic 01 --checkpoint=US
```
- Pause after user stories
- Verify functionality
- Move through efficiently
- Good balance

**Situation 3: Large Epic (20+ Tickets)**
```bash
/work-epic 02 --checkpoint=5
```
- Every 5 tickets = ~3 checkpoints
- Not too frequent
- Enough oversight
- Fast progress

**Situation 4: Well-Known Pattern (Repeatable)**
```bash
/work-epic 03
```
- No checkpoints (default)
- Full automation
- Fastest execution
- After you've done similar epics

**Situation 5: High Risk (Complex/Critical)**
```bash
/work-epic 04 --checkpoint=1
```
- Same as `--checkpoint=manual`
- Review every single ticket
- Maximum oversight
- For mission-critical code

---

## Changing Checkpoint During Execution

**At Any Checkpoint:**

```bash
# While paused at checkpoint, change strategy
/work-epic 01 --checkpoint=3

# Will recalculate remaining phases
# Next checkpoint will be 3 tickets from now
```

---

## Monitoring Without Checkpoints

**Even with `--checkpoint=none`, you get:**

```
Real-Time Updates (Every 30 seconds):
- Current ticket being worked on
- Estimated time remaining
- Quality gate status
- Any failures or issues

View Current Status:
/work-status

Pause Anytime:
/work-pause

Get Details:
/work-review [ticket-id]
```

---

## Checkpoint Best Practices

✅ **DO:**
- Start with `--checkpoint=manual` or `--checkpoint=US` for learning
- Move to `--checkpoint=none` after understanding the system
- Use high-risk checkpoints for complex work
- Review test reports at checkpoints
- Adjust checkpoints based on comfort level

❌ **DON'T:**
- Always use manual (slows down execution unnecessarily)
- Ignore checkpoints if you're uncertain
- Skip reviewing quality gate failures
- Use `--checkpoint=none` on critical production code in early epics

---

**Last Updated:** 2025-10-16 | **Owner:** subagent-orchestrator | **Related:** [Orchestration Workflow](orchestration-workflow.md)
