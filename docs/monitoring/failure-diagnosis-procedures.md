# Failure Diagnosis Procedures

Step-by-step procedures for diagnosing stuck agents and determining root causes.

---

## Prerequisites

- Access to file system and logs
- Understanding of agent responsibilities
- Familiarity with project structure
- Basic command-line proficiency

---

## Diagnosis Flowchart

```
Agent Reported Stuck?
    ↓
Step 1: Verify Stall (Compare expected vs actual)
    ↓ Confirmed Stall
Step 2: Check Output Files (Are files being created?)
    ↓ No Files → File Creation Issue
    ↓ Partial Files → Incomplete Processing
    ↓ Complete Files → Different Issue
Step 3: Analyze Error Patterns
    ↓
Step 4: Check Dependencies & Blockers
    ↓
Step 5: Examine System Resources
    ↓
Step 6: Implement Recovery or Escalate
```

---

## Step-by-Step Diagnosis

### Step 1: Verify Stall (5 minutes)

**Objective:** Confirm task is actually stuck, not just slow

**Procedure:**

1. **Check Task Status**
   ```bash
   # Get last update timestamp
   ls -la docs/tickets/EPIC-02-admin-interface/
   # Compare to current time - should be recent
   ```

2. **Check Process State**
   ```bash
   # Check if agent command still running
   ps aux | grep "break-down-epic\|epic-ticket"
   # If no process but task shows in_progress = stalled
   ```

3. **Verify Time Estimate vs Reality**
   - Task: EPIC-02 ticket generation
   - Estimated: 10-15 minutes
   - Actual elapsed: 12 minutes
   - Assessment: Right at edge of estimate, wait or investigate?

4. **Decision Points**
   ```
   - If <5 min over estimate → Wait (may just be slow)
   - If >2x estimate → Likely stuck, proceed to Step 2
   - If no process running but in_progress → Definitely stuck, proceed to Step 2
   ```

### Step 2: Check Output Files (10 minutes)

**Objective:** Determine what was actually created vs what should exist

**Procedure:**

1. **Expected vs Actual**
   ```
   EPIC-02 should create:
   ✓ docs/tickets/EPIC-02-admin-interface/EPIC.md
   ✓ docs/tickets/EPIC-02-admin-interface/README.md
   ✓ docs/tickets/EPIC-02-admin-interface/user-stories/ (directory)
     ✓ us-02.1-cpt-registration.md
     ✓ us-02.2-custom-admin-columns.md
     ✓ us-02.3-meta-fields-configuration.md
     ✓ us-02.4-dynamic-ui.md
     ✓ us-02.5-admin-styling.md
   ✓ docs/tickets/EPIC-02-admin-interface/technical-tasks/ (directory)
     ✓ tt-02.1-gap-cpt-class.md
     ✓ tt-02.2-gap-meta-boxes-class.md
     ✓ tt-02.3-admin-javascript.md
     ✓ tt-02.4-admin-css.md
   ```

2. **Actual Audit**
   ```bash
   find docs/tickets/EPIC-02-admin-interface/ -type f | sort
   # CURRENT OUTPUT: Only shows EPIC.md exists
   # Missing: README.md, all user-stories/*.md, all technical-tasks/*.md
   ```

3. **Analysis**
   ```
   Partial Completion Pattern:
   - Directories created: ✓ (shows agent had permissions)
   - Main EPIC.md created: ✓ (shows agent started)
   - Individual tickets: ✗ (agent stopped mid-process)

   Diagnosis: INCOMPLETE PROCESSING
   ```

### Step 3: Analyze Error Patterns (15 minutes)

**Objective:** Identify what caused incomplete processing

**Procedure:**

1. **Check for Explicit Errors**
   ```bash
   # Look for error logs/files
   find . -name "*.log" -o -name "*error*" | grep -i epic
   # Check if agent left error messages
   grep -r "ERROR\|error\|failed\|FAILED" docs/tickets/EPIC-02-admin-interface/
   ```

2. **Analyze Agent Constraints**
   - **epic-ticket-generator** has: Read, Write tools only
   - No Edit (can't modify existing files)
   - No Bash (can't execute scripts)
   - No Task (can't delegate)

   ```
   Potential Issues:
   - ✓ Can create files: Has Write
   - ✓ Can read EPIC.md: Has Read
   - ✓ Can create directories: Has Write
   - Question: Can create multiple files in sequence?
   ```

3. **Check for Known Failure Patterns**

   **Pattern A: Permission Issue**
   ```bash
   ls -la docs/tickets/EPIC-02-admin-interface/user-stories/
   # If showing: drwxr-xr-x (read-only for agent)
   # Then: Permission denied when trying to write files
   ```

   **Pattern B: Template Loading Issue**
   ```bash
   # Check if template file exists
   ls -la .claude/templates/ticket-template.md
   # If missing or corrupted → agent can't generate tickets
   ```

   **Pattern C: Document Format Issue**
   ```bash
   # Check if EPIC-02.md has expected structure
   grep "### US-02\|### TT-02" docs/GA-PLUGIN-PLAN.md
   # If not found → agent can't parse tickets from document
   ```

4. **Root Cause Hypothesis**
   ```
   Most Likely: File Permission or Template Issue
   Evidence:
   - EPIC.md was created (permissions OK at that point)
   - Individual files never created (permissions failed or template issue)
   - Pattern matches known "incomplete file creation" failure
   ```

### Step 4: Check Dependencies & Blockers (5 minutes)

**Objective:** Determine if external blockers are preventing progress

**Procedure:**

1. **Epic Dependencies**
   ```
   EPIC-02 requires:
   - EPIC-01 (Foundation) - Should be complete ✓
   - GA-PLUGIN-PLAN.md - Should exist ✓
   - Ticket template - Should exist at .claude/templates/ticket-template.md
   ```

2. **Blocker Analysis**
   ```bash
   # Verify all prerequisites exist
   [ -f docs/GA-PLUGIN-PLAN.md ] && echo "✓ Plan exists" || echo "✗ Plan missing"
   [ -f .claude/templates/ticket-template.md ] && echo "✓ Template exists" || echo "✗ Template missing"
   [ -d docs/tickets/EPIC-01-foundation ] && echo "✓ EPIC-01 exists" || echo "✗ EPIC-01 missing"
   ```

3. **Assessment**
   ```
   Blockers Found: None
   Dependencies Met: Yes
   Conclusion: Not a dependency issue
   ```

### Step 5: Examine System Resources (5 minutes)

**Objective:** Verify system is not resource-constrained

**Procedure:**

1. **Disk Space Check**
   ```bash
   df -h | grep -E "/$|Filesystem"
   # Need: >100MB free
   # If <5% free → likely disk full issue
   ```

2. **Memory Check**
   ```bash
   # Check if agent process was killed by OOM
   dmesg | tail -20 | grep -i "killed\|memory"
   # Or check current memory
   free -h
   ```

3. **CPU Check**
   ```bash
   # Check if CPU was maxed out
   top -b -n 1 | head -10
   # Usually not an issue for text processing
   ```

4. **Assessment**
   ```
   Disk: 87GB free ✓
   Memory: 8GB available ✓
   CPU: 15% average ✓
   Conclusion: Resources adequate
   ```

### Step 6: Formulate Diagnosis (5 minutes)

**Objective:** Create definitive root cause analysis

**Procedure:**

1. **Compile Evidence**
   ```
   DIAGNOSIS REPORT - EPIC-02 Ticket Generation Stall

   Time Stuck: 12+ minutes
   Expected Duration: 10-15 minutes
   Actual Output: EPIC.md only (50% complete)

   Evidence:
   1. Directories created → Write permissions OK initially
   2. EPIC.md created → File parsing OK, template access OK
   3. Individual tickets NOT created → File creation failed mid-process
   4. No error files left behind → Silent failure (not thrown error)
   5. Process no longer running → Task completed abnormally

   Similar to Known Pattern: "Incomplete File Creation"
   ```

2. **Likely Root Causes (in order)**
   1. **Permission Issue** - Permissions changed after first file
   2. **File Handle Exhaustion** - Too many files opened, can't open more
   3. **Template Corruption** - Template valid for first file, invalid after
   4. **Process Killed** - System killed agent process mid-task
   5. **Infinite Loop** - Agent stuck processing, no output

3. **Confidence Level**
   ```
   High Confidence: Permission issue (50%)
   Medium Confidence: Process killed (30%)
   Lower Confidence: Template/handle issues (20%)
   ```

---

## Verification Checklist

### When Diagnosing Any Stuck Agent:

- [ ] Confirmed task is actually stuck (not just slow)
- [ ] Compared expected vs actual output
- [ ] Checked for explicit error messages
- [ ] Verified all prerequisites/dependencies met
- [ ] Checked system resources
- [ ] Identified most likely root cause
- [ ] Documented findings with evidence
- [ ] Ready to implement recovery or escalate

---

## Common Issues & Quick Diagnosis

### Issue: "Only EPIC.md created"

**Quick Diagnosis:**
1. Check directory permissions: `ls -la docs/tickets/EPIC-XX-*/`
2. If shows `dr-xr-xr-x` (read-only) → **Permission Issue**
3. If shows `drwxr-xr-x` (writable) → **Template or Process Issue**

**Typical Recovery:**
```bash
# Fix permissions
chmod 755 docs/tickets/EPIC-XX-*/
chmod 755 docs/tickets/EPIC-XX-*/user-stories/
chmod 755 docs/tickets/EPIC-XX-*/technical-tasks/

# Retry agent
/break-down-epic XX
```

### Issue: "No files created at all"

**Quick Diagnosis:**
1. Check if directory created: `ls -d docs/tickets/EPIC-XX-admin-interface/`
2. If directory exists → Directory creation works, files don't
3. If directory missing → Entire operation failed early

**Typical Recovery:**
- If directory missing: Check EPIC exists in plan document
- If directory present: Permissions or file write issue

### Issue: "Incomplete file content"

**Quick Diagnosis:**
1. Check file size: `wc -l docs/tickets/EPIC-XX-admin-interface/us-XX.1-*.md`
2. Compare to similar file from working epic
3. If much smaller → Truncated during write

**Typical Recovery:**
- Delete incomplete files
- Retry with fresh start
- May need to increase timeout

---

## Escalation Decision Tree

```
Is Root Cause Known?
├─ YES → Implement Recovery (see auto-recovery-strategies.md)
└─ NO
   ├─ Agent stalled >30 min?
   │  ├─ YES → Escalate to orchestrator with full diagnostics
   │  └─ NO → Wait 5 more minutes, re-diagnose
   ├─ Auto-recovery options exist?
   │  ├─ YES → Attempt auto-recovery
   │  └─ NO → Escalate with "no recovery path" flag
   └─ Is this blocking other work?
      ├─ YES → Escalate immediately
      └─ NO → Monitor and diagnose more before escalating
```

---

## Diagnosis Template

Use this template when documenting a stuck agent:

```markdown
## DIAGNOSIS: [Agent Name] - [Task Description]

**Time Detected:** [timestamp]
**Duration Stuck:** [minutes]
**Expected Duration:** [minutes]
**Stall Percentage:** [% over estimate]

### Evidence
- Expected Output: [list of files/changes expected]
- Actual Output: [list of files/changes actually created]
- Partial Completion: [describe what was completed]
- Error Messages: [any errors found]

### Root Cause Analysis
**Most Likely:** [diagnosis with confidence %]
**Supporting Evidence:** [evidence list]

**Alternative Causes:** [list other possibilities]

### Recommended Recovery
**Primary Strategy:** [recovery approach]
**Fallback Strategy:** [if primary fails]
**Escalation Trigger:** [conditions requiring escalation]

### Implementation
- [ ] Apply recovery
- [ ] Monitor progress
- [ ] Verify completion
- [ ] Document outcome
```

---

**Last Updated:** 2025-10-16 | **Owner:** agent-health-monitor | **Related:** [Health Monitoring](agent-health-monitoring.md), [Auto-Recovery](auto-recovery-strategies.md)
