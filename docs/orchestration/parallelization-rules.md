# Parallelization Rules

When and how the orchestrator safely runs multiple tickets in parallel.

---

## Quick Rules

✅ **CAN RUN IN PARALLEL:**
- Different files being modified
- Different classes being created
- Independent responsibilities
- No shared resource access
- Different epic branches

❌ **CANNOT RUN IN PARALLEL:**
- Same file modifications (merge conflicts)
- Blocking dependencies
- Shared resource modifications
- Same database operations

---

## File Ownership Analysis

### EPIC-00: Project Setup
**Owns:**
- `ga-plugin.php` (main file)
- `includes/` (all class files)
- `.gitignore`, `LICENSE.txt`, `README.md`
- Directory structure

**Cannot Parallel With:** EPIC-01, EPIC-02, EPIC-03, EPIC-04 (EPIC-00 must complete first)

### EPIC-01: Foundation & Core
**Owns:**
- `ga-plugin.php` (constants, autoloader, initialization)
- `includes/class-gap-activator.php`
- `includes/class-gap-cpt.php`
- `includes/class-gap-meta-boxes.php`
- `includes/class-gap-conflict-detector.php`
- `includes/class-gap-frontend.php`
- `includes/class-gap-admin.php`

**Can Parallel Within:**
- US-01.1, US-01.2, US-01.3 (different classes, independent)
- TT-01.2, TT-01.3, TT-01.4 (different files, independent)

**Cannot Parallel With:**
- EPIC-02 (modifies `ga-plugin.php` - shared ownership)
- EPIC-03 (modifies `ga-plugin.php` - shared ownership)

---

## Dependency Mapping

### Direct Blocking Dependencies

**Blocker → Blocked By:**

```
US-01.1 (Plugin Activation)
  └─ No blockers (can start immediately)

US-01.2 (Plugin Constants)
  └─ No blockers (can start immediately)

US-01.3 (Autoloader)
  └─ No blockers (can start immediately)

US-01.4 (Activation/Deactivation)
  └─ BLOCKED BY: US-01.1 (needs plugin header from US-01.1)

US-01.5 (Plugin Initialization)
  └─ BLOCKED BY: US-01.4 (needs activator class)

TT-01.1 (Main Plugin File)
  └─ BLOCKED BY: US-01.1 (implements the header)

TT-01.2 (Activator Class)
  └─ BLOCKED BY: US-01.4 (implements the class)
```

---

## Parallelization Strategy

### Phase-Based Execution

**PHASE 1: Independent Foundation (Parallel)**
```
Start Conditions: EPIC-01 approved
Dependencies: None
Can Parallel: YES

Tickets (All can run in parallel):
├─ US-01.1: Plugin Activation
├─ US-01.2: Plugin Constants
└─ US-01.3: Autoloader

Execution:
Worker 1: US-01.1 → standards → commit
Worker 2: US-01.2 → standards → commit
Worker 3: US-01.3 → standards → commit

(All 3 work simultaneously, each takes 30-120 minutes)

Merge Point: All 3 complete + all standards pass → Phase 2
```

**PHASE 2: Dependent Lifecycle (Sequential)**
```
Start Conditions: Phase 1 complete
Dependencies: US-01.1 complete
Can Parallel: No (depends on Phase 1)

Tickets (Must sequence):
├─ US-01.4: Activation/Deactivation (depends on US-01.1)
└─ TT-01.2: Activator Class (depends on US-01.4)

Execution:
Worker 1: US-01.4 → standards → commit
Worker 1: TT-01.2 → standards → commit

Merge Point: All complete + all standards pass → Phase 3
```

**PHASE 3: Final Integration (Mostly Sequential)**
```
Start Conditions: Phase 2 complete
Dependencies: US-01.4 complete
Can Parallel: Partially

Tickets:
├─ US-01.5: Plugin Initialization
└─ TT-01.3: Placeholder Classes
└─ TT-01.4: Autoloader Testing

Can Parallel: TT-01.3 and TT-01.4 (different files)
But after US-01.5 completes

Execution:
Worker 1: US-01.5 → standards → commit
Worker 1: TT-01.3 → standards → commit (parallel with)
Worker 2: TT-01.4 → standards → commit (parallel with)

Merge Point: All complete → Phase 4
```

---

## Safety Checks

### Before Parallelizing, Orchestrator Verifies

**1. File Ownership Check**
```
Ticket 1 modifies: ga-plugin.php
Ticket 2 modifies: includes/class-gap-cpt.php

Conflict? NO → Safe to parallel
```

```
Ticket 1 modifies: ga-plugin.php
Ticket 2 modifies: ga-plugin.php

Conflict? YES → Cannot parallel
```

**2. Dependency Check**
```
Ticket 1 creates: autoloader function
Ticket 2 needs: autoloader function

Depends? YES → Cannot parallel
```

```
Ticket 1 creates: GAP_CPT class
Ticket 2 creates: GAP_Meta_Boxes class

Depends? NO → Safe to parallel
```

**3. Resource Check**
```
Ticket 1 accesses: WordPress options table
Ticket 2 accesses: different functionality

Shared Resource? NO → Safe to parallel
```

```
Ticket 1 initializes: Plugin version option
Ticket 2 reads: Plugin version option

Shared Resource? YES → May need sequencing
```

---

## Real-World Parallelization Example

### EPIC-01 Execution Plan

```
ORIGINAL SEQUENTIAL TIMELINE:
└─ All tickets run one at a time
   └─ Estimated: 6 hours

ORCHESTRATOR OPTIMIZED TIMELINE:
├─ PHASE 1 (Parallel - 0-2h)
│  ├─ Worker A: US-01.1 (2h) ████████████████████
│  ├─ Worker B: US-01.2 (0.5h) ██
│  ├─ Worker B: US-01.3 (1h after US-01.2)    ████
│  └─ All standards validation
│
├─ PHASE 2 (Sequential - 2-3.5h)
│  ├─ Worker A: US-01.4 (1.5h) ████████
│  ├─ Worker A: TT-01.2 (1.5h) ████████
│  └─ All standards validation
│
├─ PHASE 3 (Partial Parallel - 3.5-4.5h)
│  ├─ Worker A: US-01.5 (1h) ████
│  ├─ Worker A: TT-01.3 (1h) ████ (parallel with)
│  ├─ Worker B: TT-01.4 (0.5h) ██ (parallel with)
│  └─ All standards validation
│
└─ PHASE 4 (Quality & PR - 4.5-5h)
   ├─ Full testing
   ├─ Security audit
   └─ PR creation

TIME SAVINGS: 6h - 5h = 1 hour saved (17% faster)
```

---

## Parallelization Limits

### Maximum Parallel Workers

**Default:** 3 concurrent workers
- Prevents resource exhaustion
- Maintains code review quality
- Allows effective monitoring

**Can Increase To:** 5 workers
- For larger epics (20+ tickets)
- When infrastructure supports it
- Risk: Harder to track all threads

**Configuration:**
```json
{
  "parallelization": {
    "enabled": true,
    "max_parallel": 3,
    "can_override": false
  }
}
```

---

## Monitoring Parallel Execution

**Real-Time View:**

```
EPIC-01 PARALLEL EXECUTION

Worker 1:  ████████████ US-01.1 (Plugin Activation) [2h 5m]
Worker 2:  ██ US-01.2 (Plugin Constants) [28m complete]
Worker 3:  ████ US-01.3 (Autoloader) [58m in progress]

Standards Validation:
├─ US-01.1: ✅ Approved
├─ US-01.2: ✅ Approved
└─ US-01.3: 🔄 In progress (2m remaining)

Next Phase Starts When: All 3 standard validations pass
ETA: ~3 minutes

File Conflicts Detected: NONE ✓
Dependency Issues: NONE ✓
```

---

## Fallback to Sequential

**Orchestrator Automatically Sequences When:**

1. File conflicts detected
2. Blocking dependency found
3. Shared resource access
4. Test infrastructure timeout
5. Agent capacity exceeded

**Example:**
```
PLANNED PARALLEL:
├─ US-01.4 (Parallel group 1)
└─ TT-01.3 (Parallel group 1)

ERROR: TT-01.3 depends on US-01.4 completion
→ Cascading modification detected

FALLBACK TO SEQUENTIAL:
├─ US-01.4 starts
├─ US-01.4 completes ✅
└─ TT-01.3 starts
```

---

## Optimization Over Time

**Orchestrator Learns:**
- Which tickets truly can run parallel
- Which dependencies were unknown
- Actual vs estimated times
- Resource bottlenecks

**Future Improvements:**
- Auto-generates parallelization map for similar epics
- Predicts dependencies more accurately
- Optimizes worker allocation

---

**Last Updated:** 2025-10-16 | **Owner:** subagent-orchestrator | **Related:** [Orchestration Workflow](orchestration-workflow.md), [Agent Coordination](agent-coordination.md)
