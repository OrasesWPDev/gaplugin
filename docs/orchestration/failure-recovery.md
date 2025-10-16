# Failure Recovery & Troubleshooting

What to do when things go wrong during orchestration.

---

## Common Failures & Recovery

### Failure Type 1: Quality Gate Failure

**Symptoms:**
```
❌ QUALITY GATE FAILED: WordPress Standards

File: includes/class-gap-cpt.php:42
Issue: Function 'createPostType' should be 'gap_create_post_type'

Status: TICKET BLOCKED
```

**Automatic Recovery:**
1. Orchestrator stops ticket commit
2. Reports failure with details
3. Sends ticket back to wordpress-developer
4. Developer fixes issue
5. Re-runs through all gates
6. Retries commit

**Manual Options If Auto-Fix Fails:**
- `[🔧 Auto-Fix]` - Let orchestrator fix automatically
- `[✏️ Review]` - Manual code review before retry
- `[🔙 Reassign]` - Give to different developer

**Prevention:**
- Run standards locally before submitting
- Use IDE extensions that validate WordPress standards
- Review checklist before marking complete

---

### Failure Type 2: Test Failure

**Symptoms:**
```
❌ TEST FAILED: unit_test_autoloader

Expected: GAP_CPT loaded successfully
Got: Class not found error

Status: TICKET BLOCKED
```

**Automatic Recovery:**
1. Orchestrator reports test failure
2. Shows error stack trace
3. Asks wordpress-developer to fix
4. Developer debugs and fixes
5. Re-runs tests
6. Retries

**Manual Options:**
- `[🔙 Debug]` - Developer enters debug mode
- `[📋 Review]` - Manual review of failing test
- `[🔙 Reassign]` - Give to different developer

**Prevention:**
- Run all tests locally before submitting
- Ensure test environment matches production
- Check test data is properly created

---

### Failure Type 3: Git Merge Conflict

**Symptoms:**
```
❌ MERGE CONFLICT: epic-01-foundation vs main

File: ga-plugin.php
Conflicts: 3

Status: CANNOT PROCEED TO PR
```

**Automatic Recovery:**
1. git-workflow-specialist detects conflict
2. Reports conflict location
3. Offers automatic merge resolution
4. If successful, continues
5. If fails, requests manual intervention

**Manual Options:**
```
[🔄 Auto-Resolve]
├─ Uses last version (ours or theirs)
├─ Generally safe for non-critical code
└─ Verify afterwards

[👥 Manual Review]
├─ User reviews conflict
├─ Manually selects which version
└─ More controlled

[🔙 Rollback Merge]
├─ Revert to pre-merge state
├─ Fix upstream branch first
└─ Retry merge
```

**Prevention:**
- Regularly sync epic branch with main
- Don't work on files owned by other epics
- Communicate with other epic owners

---

### Failure Type 4: Agent Unavailable

**Symptoms:**
```
⚠️  AGENT OFFLINE: wordpress-developer unavailable

Current Ticket: US-01.1 (50% complete)
Status: Waiting for agent to reconnect

Auto-Retry: Every 5 minutes (up to 12 attempts = 1 hour)
```

**Automatic Recovery:**
1. Orchestrator detects agent offline
2. Pauses current work
3. Attempts reconnection every 5 minutes
4. After 1 hour, escalates

**Manual Options:**
```
[⏸️ Pause]
└─ Hold current work, wait for agent

[🚀 Continue Async]
└─ Assign ticket to another agent

[🔄 Auto-Retry in 5min]
└─ Wait for retry (default behavior)

[❌ Abort]
└─ Cancel this ticket, skip to next
```

**Prevention:**
- Monitor agent health dashboard
- Use failover agents if available
- Set realistic timeout expectations

---

### Failure Type 5: Dependency Blocker

**Symptoms:**
```
⚠️  DEPENDENCY BLOCKER: US-01.4 Cannot Start

Blocker: US-01.1 (Plugin Activation) NOT COMPLETE

Status: Waiting for upstream ticket
```

**Automatic Recovery:**
1. Orchestrator detects blocker
2. Pauses blocked ticket
3. Waits for blocking ticket to complete
4. Automatically starts blocked ticket once ready

**Manual Options:**
```
[⏳ Wait]
└─ Default - wait for blocker

[🔍 Verify Blocker]
└─ Check if blocker is actually blocked

[⏭️ Skip Blocker]
└─ Skip dependent ticket (not recommended)
```

**Prevention:**
- Run parallelizable tickets in parallel
- Don't create unnecessary dependencies
- Structure code to minimize blocking

---

## Error Recovery Strategies

### Strategy 1: Automatic Retry

**When:** Simple, transient errors
- Network timeouts
- Temporary resource exhaustion
- Temporary agent unavailability

**How:**
- Orchestrator retries 3 times
- Waits 30 seconds between retries
- If all 3 fail, escalates to user

### Strategy 2: Manual Fix & Retry

**When:** Code defects, logic errors
- Test failures
- Standards violations
- Logic bugs

**How:**
1. Developer fixes issue
2. Resubmits ticket
3. Runs through all gates again
4. Retries commit

### Strategy 3: Rollback & Retry

**When:** Integration failures, cascading errors
- Merge conflicts
- Regression introduced
- Cross-ticket issues

**How:**
1. Orchestrator reverts last ticket
2. Developer identifies root cause
3. Developer fixes issue
4. Resubmits and retries

### Strategy 4: Skip & Continue

**When:** Non-critical, optional work
- Optional documentation
- Nice-to-have features
- Experimental code

**How:**
1. User marks ticket as skipped
2. Orchestrator continues to next
3. Skipped ticket can be addressed later

### Strategy 5: Escalate & Review

**When:** Unclear failures, design questions
- Ambiguous requirements
- Architectural decisions
- Security concerns

**How:**
1. Orchestrator pauses execution
2. Requests manual review
3. User provides direction
4. Development resumes

---

## Failure Dashboard

**Real-Time Failure Monitoring:**

```
FAILURE RECOVERY STATUS - EPIC-01

Current Issue:
Type: Test Failure
Ticket: US-01.3 (Autoloader)
Severity: HIGH (blocks downstream)
Status: Being Resolved

Failed Test: test_autoloader_loads_gap_cpt_class
Error: Class GAP_CPT not found
File: tests/test-autoloader.php:24
Details: Autoloader not loading class from includes/class-gap-cpt.php

Attempted Fixes:
1. ✗ Auto-fix attempt 1: Check file exists (FAILED)
2. ✗ Auto-fix attempt 2: Check class name (FAILED)
3. 🔄 Auto-fix attempt 3: Validate autoloader logic (IN PROGRESS)

Recommendation: Manual developer review needed

Next Actions:
[🔍 View Details] [👥 Escalate] [⏸️ Pause] [🔙 Rollback]
```

---

## Recovery Checklist

**When Failures Occur:**

- [ ] Read error message completely
- [ ] Check failure type (standards? tests? git?)
- [ ] Review automatic retry results
- [ ] Check orchestrator recommendations
- [ ] Decide on recovery strategy
- [ ] Review recovered code
- [ ] Run manual verification
- [ ] Resume orchestration

---

## Prevention Best Practices

### Before Starting Epic:

- [ ] Read epic requirements carefully
- [ ] Understand acceptance criteria
- [ ] Map out dependencies
- [ ] Prepare test data
- [ ] Set up development environment
- [ ] Review similar completed epics

### During Development:

- [ ] Run standards check locally
- [ ] Run tests before submitting
- [ ] Keep commits small
- [ ] Test functionality manually
- [ ] Review code before submitting
- [ ] Follow checklist strictly

### After Each Ticket:

- [ ] Verify all tests pass
- [ ] Verify all standards pass
- [ ] Review git diff
- [ ] Check for debug code
- [ ] Verify security practices
- [ ] Document any gotchas

---

## Contacting Help

**If Automatic Recovery Fails:**

1. Check this guide for similar failures
2. Review Quality Gates documentation
3. Review Agent Delegation matrix
4. Provide orchestrator with detailed error report
5. Escalate for manual intervention

**Required Information for Help:**
- Epic number and name
- Current ticket ID
- Exact error message
- Timestamp of failure
- Any relevant code snippets
- Steps to reproduce

---

## Rollback Procedures

### Rollback One Ticket

```bash
/work-rollback US-01.3

This will:
1. Revert last commit for US-01.3
2. Remove code changes
3. Preserve ticket file
4. Pause execution
5. Allow developer to re-fix
```

### Rollback Multiple Tickets

```bash
/work-rollback --count=3

This will:
1. Revert last 3 tickets
2. Remove all code changes
3. Preserve ticket files
4. Pause execution
5. Reset to checkpoint 3 tickets back
```

### Rollback to Checkpoint

```bash
/work-rollback --checkpoint

This will:
1. Revert to last checkpoint
2. Remove all code since checkpoint
3. Preserve ticket files
4. Pause execution
5. Allow fixing checkpoint
```

### Full Epic Rollback

```bash
/work-rollback --epic

This will:
1. Delete epic branch
2. Revert all changes
3. Preserve all ticket files
4. Delete all commits
5. Return to planning phase
```

---

## Success Metrics for Recovery

✅ **Successful Recovery If:**
- Auto-retry succeeds first time
- Manual fixes are minimal
- Root cause identified and fixed
- No cascading failures
- Execution resumes without blocks
- Ticket completes within reasonable time

---

**Last Updated:** 2025-10-16 | **Owner:** subagent-orchestrator | **Related:** [Quality Gates](quality-gates.md), [Orchestration Workflow](orchestration-workflow.md)
