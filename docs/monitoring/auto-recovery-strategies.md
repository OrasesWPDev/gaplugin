# Auto-Recovery Strategies

Automated procedures for recovering from detected agent failures and stalls.

---

## Prerequisites

- Diagnosis complete (see failure-diagnosis-procedures.md)
- Root cause identified
- Recovery path determined
- Approval to proceed with recovery

---

## Recovery Strategy Selection

```
Root Cause Identified?
    ↓ Permission Issue → Strategy 1: Permission Fix & Retry
    ↓ Process Killed → Strategy 2: Clean State Retry
    ↓ Incomplete Work → Strategy 3: Complete & Continue
    ↓ Timeout → Strategy 4: Extended Timeout Retry
    ↓ Dependency Issue → Strategy 5: Resolve Dependency
    ↓ Resource Issue → Strategy 6: Resource Cleanup
    ↓ Unknown → Strategy 7: Escalate with Diagnostics
```

---

## Strategy 1: Permission Fix & Retry

**Applies To:** File permission denied errors

**Symptoms:**
- Directories created but files not written
- "Permission denied" in error patterns
- Partial output (first few files created, then stopped)

**Recovery Steps:**

1. **Identify Affected Directories**
   ```bash
   # Find read-only directories
   find docs/tickets/EPIC-02-admin-interface -type d -perm 555
   # These need fixing (555 = r-xr-xr-x, no write for owner)
   ```

2. **Fix Permissions**
   ```bash
   # Make directories writable
   chmod 755 docs/tickets/EPIC-02-admin-interface/
   chmod 755 docs/tickets/EPIC-02-admin-interface/user-stories/
   chmod 755 docs/tickets/EPIC-02-admin-interface/technical-tasks/

   # Verify
   ls -la docs/tickets/EPIC-02-admin-interface/
   # Should show: drwxr-xr-x (755)
   ```

3. **Clean Partial Output**
   ```bash
   # Remove incomplete/partial files if any
   rm -f docs/tickets/EPIC-02-admin-interface/README.md
   rm -f docs/tickets/EPIC-02-admin-interface/user-stories/us-*.md
   rm -f docs/tickets/EPIC-02-admin-interface/technical-tasks/tt-*.md

   # Keep EPIC.md as reference (not re-generated)
   ```

4. **Retry Command with Fresh Context**
   ```bash
   /break-down-epic 02
   ```

5. **Verify Output**
   ```bash
   # Check all expected files created
   ls docs/tickets/EPIC-02-admin-interface/
   ls docs/tickets/EPIC-02-admin-interface/user-stories/ | wc -l
   # Should show: 5 user story files
   ls docs/tickets/EPIC-02-admin-interface/technical-tasks/ | wc -l
   # Should show: 4 technical task files
   ```

**Success Criteria:**
- ✓ All directories writable (755 permissions)
- ✓ All expected ticket files created
- ✓ Each file contains complete content
- ✓ Total file count matches expectation

**Fallback If Fails:**
- Proceed to Strategy 2 (Clean State Retry)
- Or Strategy 7 (Escalate)

---

## Strategy 2: Clean State Retry

**Applies To:** Process killed, crashed, or unknown state

**Symptoms:**
- Agent process no longer running but task incomplete
- Unclear why task stopped
- Permission fixes didn't help

**Recovery Steps:**

1. **Clear All Partial Work**
   ```bash
   # Delete entire epic ticket directory (start fresh)
   rm -rf docs/tickets/EPIC-02-admin-interface/

   # Verify deleted
   [ ! -d docs/tickets/EPIC-02-admin-interface/ ] && echo "✓ Cleared"
   ```

2. **Verify Source Document Still Valid**
   ```bash
   # Ensure source epic document exists and is complete
   grep -c "### US-02\|### TT-02" docs/GA-PLUGIN-PLAN.md
   # Should find: 5 US + 4 TT = 9 patterns
   ```

3. **Retry with Fresh Context**
   ```bash
   # Retry the command - agent starts from scratch
   /break-down-epic 02
   ```

4. **Monitor Closely**
   ```bash
   # Check every 2 minutes
   watch -n 120 'find docs/tickets/EPIC-02-admin-interface/ -type f | wc -l'
   # Should grow from 0 to 10 (9 tickets + 1 README)
   ```

5. **Verify Complete Output**
   ```bash
   # Verify all files created and have content
   for f in docs/tickets/EPIC-02-admin-interface/**/*.md; do
     [ -s "$f" ] || echo "Empty: $f"
   done
   # Should show no empty files
   ```

**Success Criteria:**
- ✓ All partial work cleared
- ✓ Fresh start, no artifacts
- ✓ All expected files created completely
- ✓ No empty/corrupted files

**Fallback If Fails:**
- Proceed to Strategy 6 (Resource Cleanup)
- Or Strategy 7 (Escalate)

---

## Strategy 3: Complete & Continue

**Applies To:** Incomplete work that can be finished

**Symptoms:**
- Most work done, just last few items missing
- Known what's left to do
- Agent didn't finish for non-blocking reason

**Recovery Steps:**

1. **Identify Missing Work**
   ```bash
   # Expected: us-02.1 through us-02.5, tt-02.1 through tt-02.4
   # Compare to existing files
   ls docs/tickets/EPIC-02-admin-interface/user-stories/ | sort

   # Identify missing (e.g., us-02.4 and us-02.5)
   ```

2. **Generate Missing Files**
   ```bash
   # Use agent-health-monitor or manual script
   # Create from template for each missing ticket
   # Can be done manually if just 1-2 files
   ```

3. **Validate All Files**
   ```bash
   # Check each file has content
   for dir in user-stories technical-tasks; do
     ls docs/tickets/EPIC-02-admin-interface/$dir/*.md | while read f; do
       lines=$(wc -l < "$f")
       [ $lines -gt 20 ] || echo "Warning: $f only has $lines lines"
     done
   done
   ```

4. **Mark as Complete**
   ```bash
   # Update epic status
   # Mark EPIC-02 ready for development
   ```

**Success Criteria:**
- ✓ All expected files now exist
- ✓ Each file has substantial content (>20 lines)
- ✓ No files missing
- ✓ Ready to proceed to next phase

**Fallback If Fails:**
- Proceed to Strategy 2 (Clean State Retry)

---

## Strategy 4: Extended Timeout Retry

**Applies To:** Slow tasks that may need more time

**Symptoms:**
- Agent still running but approaching timeout
- No clear error, just slow
- Task was estimated to take 15min, currently at 12min

**Recovery Steps:**

1. **Verify Agent Still Running**
   ```bash
   ps aux | grep "break-down-epic\|epic-ticket"
   # If present → agent still working, don't interrupt
   # If absent → use Strategy 2 instead
   ```

2. **Check Progress**
   ```bash
   # Monitor file creation rate
   find docs/tickets/EPIC-02-admin-interface/ -type f -mmin -1
   # -1 means modified in last 1 minute
   # If files changing → agent still working
   ```

3. **Wait Patiently**
   ```bash
   # Don't interrupt - give it full estimate + 50%
   # Original estimate: 15 minutes
   # New timeout: 22 minutes (15 * 1.5)
   # Monitor every 3 minutes
   ```

4. **If Still Running After New Timeout**
   ```bash
   # Something likely wrong, escalate
   # But get full diagnostics first
   ps aux | grep "break-down-epic"
   # Check what file it's working on
   ls -la docs/tickets/EPIC-02-admin-interface/
   ```

5. **Verify When Completed**
   ```bash
   # Once process finishes, validate output
   # Check all files created and have content
   find docs/tickets/EPIC-02-admin-interface/ -type f -exec wc -l {} +
   ```

**Success Criteria:**
- ✓ Process completed within extended timeout
- ✓ All expected files created
- ✓ No truncated or corrupted files
- ✓ Task marked complete

**Fallback If Fails:**
- Proceed to Strategy 2 (Clean State Retry)
- Or Strategy 6 (Resource Cleanup)

---

## Strategy 5: Resolve Dependency

**Applies To:** Upstream blocker preventing progress

**Symptoms:**
- Blocker ticket not completed
- Dependent agent waiting indefinitely
- Blocker in failed state

**Recovery Steps:**

1. **Identify Blocker**
   ```bash
   # Check epic for dependencies
   # EPIC-02 requires: EPIC-01 foundation
   # EPIC-02 requires: GA-PLUGIN-PLAN.md file
   ```

2. **Verify Blocker Status**
   ```bash
   # Check if EPIC-01 complete
   [ -f docs/tickets/EPIC-01-foundation/README.md ] && echo "✓ EPIC-01 ready"

   # Check if plan file exists
   [ -f docs/GA-PLUGIN-PLAN.md ] && echo "✓ Plan exists"
   ```

3. **Complete Blocker If Needed**
   ```bash
   # If EPIC-01 tickets not generated:
   /break-down-epic 01

   # If plan file not in expected location:
   # Check alternative locations or generate from template
   ```

4. **Verify Blocker Resolution**
   ```bash
   # Confirm all prerequisites now met
   [ -f docs/tickets/EPIC-01-foundation/README.md ] && echo "✓"
   [ -f docs/GA-PLUGIN-PLAN.md ] && echo "✓"
   ```

5. **Retry Dependent Task**
   ```bash
   /break-down-epic 02
   ```

**Success Criteria:**
- ✓ All upstream blockers resolved
- ✓ Dependencies properly satisfied
- ✓ Task now progresses normally

**Fallback If Fails:**
- Proceed to Strategy 2 (Clean State Retry)

---

## Strategy 6: Resource Cleanup

**Applies To:** System resource issues

**Symptoms:**
- Disk space running low (<5%)
- Memory exhausted
- CPU maxed out

**Recovery Steps:**

1. **Check Disk Space**
   ```bash
   df -h | grep "/$"
   # If <5% free → need cleanup
   ```

2. **Clear Cache/Temp Files**
   ```bash
   # Clear system temp files (if permissions allow)
   # Usually safe operations
   du -sh /tmp
   ```

3. **Check for Large Files**
   ```bash
   # Find unusually large files in project
   find docs -type f -size +10M

   # Can safely delete:
   # - .log files
   # - Backup files
   # - Old test data
   ```

4. **Free Up Space**
   ```bash
   # Example: Delete old test data (if safe)
   rm -f tests/fixtures/*.tar.gz

   # Run cleanup
   df -h | grep "/$"
   # Should show >10% free now
   ```

5. **Retry Task**
   ```bash
   /break-down-epic 02
   ```

**Success Criteria:**
- ✓ Disk space >10% free
- ✓ Memory available
- ✓ Task completes successfully

**Fallback If Fails:**
- Proceed to Strategy 7 (Escalate)

---

## Strategy 7: Escalate with Diagnostics

**Applies To:** Unknown failures, multi-step required, or all auto-recovery failed

**When To Escalate:**

- [ ] All 6 strategies attempted without success
- [ ] Stall duration >30 minutes
- [ ] Unknown root cause
- [ ] Blocking critical path
- [ ] Requires manual human judgment

**Escalation Information to Provide:**

```markdown
## ESCALATION REPORT

**Agent:** epic-ticket-generator
**Task:** EPIC-02 ticket generation
**Status:** STUCK (20+ minutes, strategies 1-6 failed)

### Problem Summary
- Expected: 5 user stories + 4 technical tasks
- Actual: Only EPIC.md created
- Duration: 20 minutes (exceeded estimate by 33%)

### Diagnostics Completed
- [x] Permission fix & retry → FAILED
- [x] Clean state retry → FAILED
- [x] Check dependencies → OK
- [x] Resource check → OK
- [x] Extended timeout → FAILED

### Evidence
- EPIC.md structure: Valid
- Directory permissions: 755 (correct)
- Source document: Found and valid
- Disk space: 87GB free (adequate)
- Memory: 8GB available (adequate)

### Last Error/State
[Exact error message or description of last state]

### Recommended Next Steps
1. Manual review of agent logic
2. Template validation
3. Debug output analysis
4. Possible bug in ticket generation logic

### Files for Review
- .claude/agents/epic-ticket-generator.md
- docs/tickets/EPIC-02-admin-interface/EPIC.md
- .claude/templates/ticket-template.md
```

---

## Recovery Monitoring

### During Recovery

**Monitor Every 2-5 Minutes:**
```bash
# Track file creation
find docs/tickets/EPIC-XX-admin-interface/ -type f -mmin -2

# Check for errors
grep -i "error\|failed\|exception" *.log 2>/dev/null

# Verify permissions unchanged
ls -la docs/tickets/EPIC-XX-admin-interface/
```

### After Recovery

**Immediate Verification:**
- [ ] All expected files created
- [ ] Each file has substantial content
- [ ] File timestamps recent (last 5 minutes)
- [ ] No error files or logs

**Post-Recovery Checklist:**
- [ ] Document what failed and why
- [ ] Document recovery strategy used
- [ ] Test dependent tasks work
- [ ] Update monitoring dashboard
- [ ] Review for prevention measures

---

## Recovery Decision Matrix

| Scenario | Strategy | Time | Success Rate |
|----------|----------|------|--------------|
| Permission denied | Strategy 1 | 5 min | 95% |
| Process killed | Strategy 2 | 15 min | 85% |
| Incomplete work | Strategy 3 | 10 min | 90% |
| Timeout (slow) | Strategy 4 | +50% time | 70% |
| Blocker issue | Strategy 5 | Varies | 80% |
| Resource issue | Strategy 6 | 10 min | 88% |
| Unknown cause | Strategy 7 | Escalate | 100% |

---

## Prevention

### After Successful Recovery:

1. **Root Cause Analysis**
   - Why did this failure occur?
   - Was it preventable?
   - What changed?

2. **Process Improvement**
   - Add monitoring for this pattern?
   - Change timeout estimates?
   - Better error handling?

3. **Documentation**
   - Add new pattern to health monitoring
   - Update diagnosis procedures
   - Create recovery script if applicable

---

**Last Updated:** 2025-10-16 | **Owner:** agent-health-monitor | **Related:** [Health Monitoring](agent-health-monitoring.md), [Failure Diagnosis](failure-diagnosis-procedures.md)
