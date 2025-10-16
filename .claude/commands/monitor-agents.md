# /monitor-agents

Monitor all active agents for failures, stalls, and performance issues. Detects stuck agents and implements automatic recovery procedures.

**Usage:**
```
/monitor-agents [options]
```

**Options:**
- `--full` - Full diagnostics and recovery (default)
- `--diagnose-only` - Only diagnose, don't recover
- `--strict` - Aggressive recovery (more risky)
- `--agent <id>` - Monitor specific agent only
- `--once` - Single check (don't continuous monitor)

**What it does:**
1. Polls all 9 active agents for current status
2. Detects stalls (no progress >5 minutes)
3. Analyzes root cause of any failures
4. Implements automatic recovery procedures
5. Escalates unrecoverable issues to orchestrator
6. Maintains real-time health dashboard
7. Logs all findings for audit trail

**Examples:**

```
# Full monitoring with auto-recovery
/monitor-agents

# Diagnose only (don't fix)
/monitor-agents --diagnose-only

# Check specific stuck agent
/monitor-agents --agent epic-ticket-generator

# Single check, no continuous monitoring
/monitor-agents --once
```

**Output shows:**

```
AGENT HEALTH DASHBOARD - 2025-10-16 15:45:00

🟢 HEALTHY AGENTS (8)
├─ wordpress-developer ✅ (67% complete)
├─ wordpress-standards-validator ✅ (Idle)
├─ git-workflow-specialist ✅ (Idle)
└─ [5 more healthy]

🟡 STALLED AGENTS (1)
└─ epic-ticket-generator ⚠️
   Status: STALLED (12 minutes no progress)
   Issue: Only EPIC.md created, missing tickets
   Recovery: Auto-retry with permission fix
   Status: IN PROGRESS

ACTIONS TAKEN:
✓ Strategy 1: Permission Fix & Retry
  └─ Fixed permissions on directories
  └─ Clearing partial output
  └─ Retrying command...

NEXT CHECK IN: 5 minutes
```

**Recovery Strategies Used:**

1. **Strategy 1** - Permission Fix & Retry (file permissions)
2. **Strategy 2** - Clean State Retry (process killed)
3. **Strategy 3** - Complete & Continue (incomplete work)
4. **Strategy 4** - Extended Timeout Retry (slow tasks)
5. **Strategy 5** - Resolve Dependency (blocked agents)
6. **Strategy 6** - Resource Cleanup (disk/memory issues)
7. **Strategy 7** - Escalate with Diagnostics (unknown)

**Success Metrics:**

- ✅ Detects stalls within 5 minutes
- ✅ Diagnoses root cause automatically
- ✅ 85%+ auto-recovery success rate
- ✅ <10 minute average recovery time
- ✅ Escalates unrecoverable issues
- ✅ Maintains full audit trail

**Integration:**

Works with agent-health-monitor to provide autonomous, continuous monitoring of all specialized agents. Auto-detects:

- Stuck epic-ticket-generator (current issue with EPIC-02)
- Timeout on wordpress-developer
- Failed validation on wordpress-standards-validator
- Git operation stalls
- Resource exhaustion
- Process crashes
- Dependency blockers

**See also:**
- [Agent Health Monitor](../agents/agent-health-monitor.md)
- [Health Monitoring Reference](../docs/monitoring/agent-health-monitoring.md)
- [Failure Diagnosis Procedures](../docs/monitoring/failure-diagnosis-procedures.md)
- [Auto-Recovery Strategies](../docs/monitoring/auto-recovery-strategies.md)
