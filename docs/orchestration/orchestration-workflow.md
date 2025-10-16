# Orchestration Workflow

Complete reference for the SWARM-based orchestration process that autonomously manages epic development.

---

## Quick Overview

```
Epic Request → Analysis & Plan → User Approval → Execution → Quality Gates → PR Creation
                                        ↑
                                  [Configurable Checkpoints]
```

---

## Detailed Workflow Steps

### Step 1: Epic Request Received

**Input:** `/work-epic <number> [--checkpoint=<option>]`

**What Happens:**
- Orchestrator validates epic exists
- Loads epic file and all requirements
- Identifies all User Stories and Technical Tasks
- Extracts dependencies and metadata

**Checkpoint Options:**
- `--checkpoint=none` - No pauses, full automation (fastest)
- `--checkpoint=manual` - Wait for `/next-ticket` command each time
- `--checkpoint=US` - Pause after each User Story
- `--checkpoint=3` - Pause every 3 tickets
- `--checkpoint=5` - Pause every 5 tickets
- Default: none (fully autonomous)

---

### Step 2: Analysis & Planning

**Orchestrator Performs:**

1. **Dependency Analysis**
   - Map all ticket dependencies (blockers)
   - Identify User Stories that block Technical Tasks
   - Identify cross-ticket dependencies
   - Build dependency graph

2. **Parallelization Analysis**
   - Identify independent tickets (can run in parallel)
   - Group tickets by dependency level
   - Optimize execution order
   - Estimate parallelization savings

3. **Resource Planning**
   - Determine which agent handles each ticket
   - Identify specialized agent needs
   - Plan skill-based delegation
   - Create capacity plan

4. **Risk Assessment**
   - Identify high-risk tickets
   - Flag cross-file modifications
   - Highlight integration points
   - Assess security requirements

---

### Step 3: Plan Presentation

**User Sees:**

```
📋 EPIC-01 Execution Plan

EPIC: Foundation & Core Plugin (EPIC-01)
Status: Ready for Execution
Dependencies: EPIC-00 (✓ Complete)

=== PARALLELIZATION ANALYSIS ===
Sequential Baseline: 6 hours
Parallel Execution: 4 hours
Time Savings: 2 hours (33%)

=== EXECUTION PHASES ===

PHASE 1: Parallel Foundation (0-2h)
├─ US-01.1: Plugin Activation [2h]
│  └─ TT-01.1: Main Plugin File [2h]
├─ US-01.2: Plugin Constants [30m]
└─ US-01.3: Autoloader [1h]

PHASE 2: Sequential - Depends on Phase 1 (2-3.5h)
├─ US-01.4: Activation/Deactivation [1.5h]
│  └─ TT-01.2: Activator Class [1.5h]
└─ TT-01.3: Placeholder Classes [1h]

PHASE 3: Final Integration (3.5-4h)
├─ US-01.5: Plugin Initialization [1h]
└─ TT-01.4: Autoloader Testing [30m]

PHASE 4: Quality Assurance & PR (4-4.5h)
├─ Security Audit [20m]
├─ WordPress Standards Check [15m]
├─ Automated Tests [15m]
└─ Create PR [10m]

=== QUALITY GATES (All Must Pass) ===
✓ WordPress Coding Standards
✓ Security Audit
✓ Automated Tests
✓ No Debug Code
✓ PHPCS Compliance

=== AGENTS ASSIGNED ===
- wordpress-developer → Implementation
- wordpress-standards-validator → Code quality
- local-testing-specialist → Testing
- git-workflow-specialist → Version control

=== DELIVERABLES ===
- 5 User Stories (16 story points)
- 4 Technical Tasks (5 hours)
- 1 Epic completion with PR

[📝 View Detailed Plan] [✅ Approve] [🔄 Modify] [❌ Cancel]
```

---

### Step 4: User Decision Point

**User Choices:**

**Option A: Approve**
- Full automation begins
- Real-time progress updates
- Checkpoints activate if configured

**Option B: Modify**
- Reorder tickets
- Skip specific tickets
- Adjust checkpoint strategy
- Then re-present plan

**Option C: View Details**
- Expand any phase
- See specific task estimates
- View agent assignments
- See file ownership details

**Option D: Cancel**
- No execution
- No changes made
- Return to planning

---

### Step 5: Epic Branch Creation

**After Approval:**

1. **Git Workflow Specialist Creates Branch**
   ```bash
   git checkout -b epic-01-foundation
   git push -u origin epic-01-foundation
   ```

2. **Orchestrator Creates TodoList**
   - One todo per ticket
   - Status: pending
   - Links to ticket file
   - Tracks completion

---

### Step 6: Development Phase (SWARM Execution)

#### Parallel Group Processing

**Example: Phase 1 (Parallel Execution)**

```
PARALLEL GROUP 1 (All Independent - Can run simultaneously)
├─ wordpress-developer works on US-01.1 + TT-01.1
├─ wordpress-developer works on US-01.2
└─ wordpress-developer works on US-01.3

WHEN ALL 3 COMPLETE:
├─ wordpress-standards-validator validates all code
├─ Combine all commits into logical sequence
└─ Proceed to Phase 2
```

#### Per-Ticket Workflow

**For Each Ticket:**

1. **Agent Implementation**
   - wordpress-developer implements ticket
   - Follows acceptance criteria
   - Creates code
   - Adds tests

2. **Standards Validation** (After each ticket)
   - wordpress-standards-validator checks code
   - Validates WordPress naming
   - Checks security practices
   - Verifies documentation

3. **Commit** (When standards pass)
   - git-workflow-specialist creates commit
   - Format: `[type](epic-01): [US-XX.X] - [description]`
   - One commit per ticket
   - Pushes to epic branch

4. **Mark Complete**
   - Orchestrator updates TodoList
   - Records completion time
   - Checks if checkpoint reached

---

### Step 7: Checkpoint Logic

**If Configured Checkpoint Reached:**

```
🛑 CHECKPOINT: 3 Tickets Complete

PHASE 1 STATUS:
✅ US-01.1: Plugin Activation (2h 05m)
✅ US-01.2: Plugin Constants (28m)
✅ US-01.3: Autoloader (58m)

Progress: 3/9 tickets | 33% complete | On schedule

All Quality Gates Passed ✓

[▶️ Continue] [⏸️ Review] [🔙 Rollback] [❌ Abort]
```

**User Options:**
- **Continue:** Resume to next checkpoint or completion
- **Review:** Show detailed status of all tickets
- **Rollback:** Revert last ticket and retry
- **Abort:** Stop execution, preserve work

---

### Step 8: Quality Gate Enforcement

**After Each Ticket Completes:**

1. **WordPress Coding Standards Check**
   ```
   ✓ PHPCS WordPress-VIP-Go ruleset
   ✓ Function naming (gap_function_name)
   ✓ Class naming (GAP_Class_Name)
   ✓ File naming (class-gap-name.php)
   ✓ Security checks (ABSPATH, sanitization)
   ```

2. **Security Audit**
   ```
   ✓ No direct access vulnerabilities
   ✓ Proper capability checks
   ✓ Nonce validation patterns
   ✓ Input sanitization
   ✓ Output escaping
   ✓ No hardcoded credentials
   ```

3. **Testing**
   ```
   ✓ Unit tests pass (if applicable)
   ✓ Integration tests pass
   ✓ No PHP errors
   ✓ No PHP warnings
   ```

**If Any Gate Fails:**
```
❌ QUALITY GATE FAILED

Gate: WordPress Coding Standards
Issue: Non-prefixed function: get_custom_value()
File: includes/class-gap-meta-boxes.php:45

Recommendation: Rename to gap_get_custom_value()

Action:
[🔧 Auto-Fix] [📋 View Details] [🔙 Reassign]
```

---

### Step 9: Epic Completion

**When All Tickets Complete:**

1. **Final Quality Audit**
   - Full codebase standards check
   - Security scan (all files)
   - Integration test suite
   - No debug code found

2. **Branch Sync**
   - Fetch latest main
   - Merge main into epic branch
   - Resolve any conflicts
   - Verify no regression

3. **PR Creation**
   - git-workflow-specialist creates PR
   - PR includes:
     - Epic summary
     - All ticket summaries
     - Test reports
     - Quality gate results
   - PR marked for review

---

### Step 10: PR Completion

**Orchestrator Monitors:**

1. **PR Review Status**
   - Waits for approvals
   - Tracks reviewer feedback
   - Coordinates fixes if needed

2. **Final Tests**
   - Re-run full test suite on PR
   - Verify no regressions
   - Confirm all gates pass

3. **Merge & Cleanup**
   - git-workflow-specialist squash-merges to main
   - Epic branch cleanup
   - Tags release if applicable

---

## Dependency Resolution

### Blocking Patterns

**Type 1: Direct Blocker**
```
US-01.4 (Activation/Deactivation)
  BLOCKED BY → US-01.1 (Plugin Activation must complete first)
```

**Type 2: Resource Dependency**
```
US-02.1 (Manage Tracking Scripts)
  BLOCKED BY → EPIC-01 (Core plugin must be complete)
```

**Type 3: File Ownership Conflict**
```
TT-02.1 (Implement GAP_CPT)
  CAN PARALLEL → TT-02.2 (Implement GAP_Meta_Boxes)
  CONFLICTS WITH → TT-03.1 (Modify ga-plugin.php - owned by EPIC-03)
```

---

## Parallelization Decisions

### Safe to Parallelize

✅ Different classes (independent implementations)
✅ Different files (no merge conflicts)
✅ Different responsibilities (low coupling)
✅ No blocking dependencies

### Not Safe to Parallelize

❌ Same file modifications (merge conflicts)
❌ Blocking dependencies (must complete first)
❌ Shared database operations
❌ Cache invalidation sequences

---

## Error Handling & Recovery

### At Quality Gate

```
❌ Test Failed: Autoloader not loading GAP_CPT

Current Ticket: US-01.3
Blocker: Autoloader implementation incomplete

Options:
[🔙 Revert & Retry] [🔧 Developer Review] [📌 Manual Fix]
```

### At Agent Handoff

```
❌ Agent Unavailable: wordpress-developer offline

Current Ticket: US-01.1 (50% complete)
Status: Implementation in progress

Options:
[⏸️ Pause] [🚀 Continue Async] [🔄 Auto-Retry in 5min]
```

### At Git Operation

```
❌ Merge Conflict: epic-01-foundation vs main

Conflicts: ga-plugin.php (3 conflicts)
Blocker: Cannot proceed with PR

Options:
[👥 Manual Review] [🔄 Resolve Automatically] [🔙 Rollback Merge]
```

---

## Real-Time Status

**During Execution:**

```
🔄 EPIC-01 EXECUTION IN PROGRESS

Current Ticket: US-01.3 (Autoloader)
Status: Implementation Complete → Standards Validation
Elapsed: 58 minutes (estimated: 60 min)

Completed:
✅ US-01.1 (2h 5m)
✅ US-01.2 (28m)

In Progress:
🔄 US-01.3 (58m) - wordpress-standards-validator

Queued:
⏳ US-01.4 (depends on US-01.1 ✓)
⏳ US-01.5 (depends on US-01.4)

Overall Progress:
████████░░░░░░░░░░ 33% (3/9 tickets)

Next Checkpoint: After US-01.4 (4/9 tickets)
ETA to Checkpoint: ~45 minutes
```

---

**Last Updated:** 2025-10-16 | **Owner:** subagent-orchestrator | **Related:** [Checkpoint Guide](checkpoint-guide.md), [Agent Coordination](agent-coordination.md)
