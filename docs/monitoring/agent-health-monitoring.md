# Agent Health Monitoring

Complete system for detecting, diagnosing, and recovering from agent failures and stalls.

---

## Health Check Intervals

| Interval | Action | Threshold |
|----------|--------|-----------|
| Every 1 minute | Poll agent status | Check if responding |
| Every 5 minutes | Detect stalls | No progress >5min = potential stall |
| Every 15 minutes | Deep diagnostics | File analysis, performance metrics |
| Every hour | Health report | Summary to orchestrator |

---

## Agent Health Metrics

### Per-Agent Tracking

```json
{
  "agent_id": "epic-ticket-generator",
  "status": "active|stalled|failed|recovered",
  "uptime_percent": 99.5,
  "tasks_completed": 45,
  "tasks_failed": 2,
  "avg_recovery_time": "3.5 minutes",
  "last_activity": "2025-10-16T15:30:42Z",
  "current_task": "US-02.1 generation",
  "task_start_time": "2025-10-16T15:25:00Z",
  "estimated_completion": "2025-10-16T15:35:00Z"
}
```

### System-Wide Metrics

```json
{
  "total_agents": 8,
  "healthy_agents": 8,
  "stalled_agents": 0,
  "total_tasks_today": 127,
  "tasks_completed": 125,
  "tasks_failed": 2,
  "avg_recovery_success_rate": 94.2,
  "system_uptime_percent": 98.7
}
```

---

## Detection Patterns

### Stall Detection

**Stall Pattern 1: No Output**
```
Condition: Agent running but no file changes, no status updates
Duration: >5 minutes
Action: Check if blocked on external resource
Recovery: Retry, check dependencies, escalate
```

**Stall Pattern 2: Incomplete Output**
```
Condition: Partial work but task not marked complete
Example: EPIC.md created but no tickets
Duration: >10 minutes (significant delay)
Action: Compare expected vs actual output
Recovery: Complete missing work, validate output structure
```

**Stall Pattern 3: Repeated Errors**
```
Condition: Same error repeated after fix applied
Duration: >3 retry cycles
Action: Analyze error pattern, check for validator bug
Recovery: Manual review, escalate for fix, skip if safe
```

**Stall Pattern 4: Resource Exhaustion**
```
Condition: Agent running but increasingly slow response
Metrics: High CPU, high memory, slow file operations
Duration: Degradation over 15+ minutes
Action: Monitor resource usage, identify bottleneck
Recovery: Clear caches, optimize queries, restart if needed
```

---

## Health Dashboard

### Real-Time Status View

```
AGENT HEALTH DASHBOARD - 2025-10-16 15:45:00

🟢 HEALTHY (7 agents)
├─ wordpress-developer ✅ (US-01.4: 67% complete, 18min remaining)
├─ wordpress-standards-validator ✅ (Validating US-01.4: 2min remaining)
├─ git-workflow-specialist ✅ (Ready for next commit)
├─ local-testing-specialist ✅ (Idle, awaiting work)
├─ requirements-analyzer ✅ (Idle, awaiting analysis)
├─ subagent-creator ✅ (Idle, awaiting agent creation)
└─ subagent-orchestrator ✅ (Coordinating EPIC-01 phase 3)

🟡 INVESTIGATING (1 agent)
└─ epic-ticket-generator ⚠️
   Status: STALLED (12 minutes no progress)
   Task: EPIC-02 ticket generation
   Expected: 5 user stories + 4 technical tasks
   Actual: Only EPIC.md created
   Issue: Ticket files not generated
   Recovery: Attempted auto-retry (in progress)

SYSTEM STATUS: 87.5% Optimal (1 stalled, 7 healthy)
```

---

## Common Health Issues

### Issue 1: File Permission Denied

**Symptoms:**
- "Permission denied" error in logs
- Directory created but no files written
- Different agent works fine

**Detection:**
```bash
ls -la docs/tickets/EPIC-02-admin-interface/
# Shows: drwxr-xr-x (readable but not writable by agent)
```

**Recovery:**
```bash
chmod 755 docs/tickets/EPIC-02-admin-interface/
chmod 755 docs/tickets/EPIC-02-admin-interface/user-stories/
chmod 755 docs/tickets/EPIC-02-admin-interface/technical-tasks/
# Retry agent task
```

### Issue 2: Missing Dependency

**Symptoms:**
- Agent waiting for upstream task
- Blocker marked but not tracked
- Agent polls indefinitely

**Detection:**
```
epic-ticket-generator waiting for: EPIC-02.md file
Blocker: File exists at docs/GA-PLUGIN-PLAN.md (different location)
Root Cause: Path mismatch in configuration
```

**Recovery:**
- Fix path configuration
- Resolve blocker dependency
- Retry agent task

### Issue 3: Rate Limiting

**Symptoms:**
- GitHub API calls return 429 Too Many Requests
- Network timeouts on git operations
- Increasing latency over time

**Detection:**
```
git-workflow-specialist: API rate limit: 60/3600 requests remaining
Last 10 operations: avg 3.2 seconds (usually <1 second)
```

**Recovery:**
- Batch git operations
- Add exponential backoff
- Retry after cooldown period
- Escalate if persists

### Issue 4: Configuration Mismatch

**Symptoms:**
- Agent following wrong procedure
- Configuration changed but agent not reloaded
- Inconsistent behavior vs documentation

**Detection:**
```
Agent config shows: claude-sonnet-3.5
Runtime actual: claude-sonnet-4-5
Root Cause: Configuration file not refreshed
```

**Recovery:**
- Reload agent configuration
- Verify all settings match documentation
- Restart agent with fresh context

---

## Health Check Procedures

### Quick Health Check (1 minute)

```bash
# Check all agent status files
for agent in .claude/agents/*.md; do
  name=$(basename "$agent" .md)
  # Check if agent has completed recent tasks
  # Check if any error logs present
  # Check if status updated in last 5 minutes
done
```

### Deep Health Diagnostics (15 minutes)

1. **Agent Status Verification**
   - Is agent responsive?
   - Any error messages in logs?
   - Is agent waiting for something?

2. **Task Progress Analysis**
   - Current task ID and start time
   - Expected vs actual completion time
   - Files created vs expected files

3. **Resource Monitoring**
   - CPU usage (should be <20% when running)
   - Memory usage (should be <500MB)
   - Disk I/O (check for disk full)

4. **Dependency Analysis**
   - Are all dependencies completed?
   - Any blockers preventing progress?
   - Circular dependencies?

5. **Output Validation**
   - Files created match expected structure?
   - Content completeness vs spec?
   - Any partial/corrupted files?

---

## Escalation Criteria

### Auto-Escalate to Orchestrator If:

1. **Stall Duration >15 minutes**
   - After 3 auto-recovery attempts
   - Provide full diagnostics

2. **Unrecognized Error Pattern**
   - Not in known failure types
   - Requires manual investigation

3. **Recovery Attempts Exhausted**
   - Tried all auto-recovery strategies
   - Agent still not progressing

4. **Resource Critical**
   - Disk space <5%
   - Memory exhaustion
   - CPU maxed out

5. **Security Concern**
   - Potential privilege escalation attempt
   - Unauthorized file access
   - Unusual network activity

---

## Audit Trail & History

### Event Log Entry

```json
{
  "timestamp": "2025-10-16T15:45:30Z",
  "agent_id": "epic-ticket-generator",
  "event": "stall_detected",
  "severity": "medium",
  "task": "EPIC-02 ticket generation",
  "duration_minutes": 12,
  "root_cause": "unknown",
  "recovery_attempt": 1,
  "recovery_action": "retry_with_fresh_context",
  "recovery_status": "in_progress"
}
```

### Recovery History

```
EPIC-02 Ticket Generation Recovery
├─ 15:25 - Task started
├─ 15:30 - Partial completion detected (EPIC.md only)
├─ 15:33 - Stall detected (5min no progress)
├─ 15:34 - Auto-recovery attempt 1: Retry (FAILED - same result)
├─ 15:38 - Auto-recovery attempt 2: Chmod permissions (COMPLETED)
├─ 15:39 - Retry with fresh context (IN PROGRESS)
└─ 15:45 - [Awaiting result]
```

---

## Monitoring Best Practices

✅ **DO:**
- Check health dashboard every 30 minutes during active work
- Review audit logs daily
- Monitor recovery success trends
- Alert on new error patterns
- Track MTTR (Mean Time To Recovery)

❌ **DON'T:**
- Ignore warnings until critical
- Kill agents without diagnostics
- Assume transient issues are fixed
- Skip health reports
- Lose historical data

---

**Last Updated:** 2025-10-16 | **Owner:** agent-health-monitor | **Related:** [Failure Diagnosis](failure-diagnosis-procedures.md), [Auto-Recovery](auto-recovery-strategies.md)
