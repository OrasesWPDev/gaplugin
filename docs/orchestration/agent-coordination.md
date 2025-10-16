# Agent Coordination Protocol

How specialized agents communicate, hand off work, and collaborate within the orchestration system.

---

## Communication Model

### Handoff Structure

```
Orchestrator
    ↓
[Task Delegation]
    ↓
Specialized Agent (e.g., wordpress-developer)
    ↓
[Implementation + Status Updates]
    ↓
Orchestrator [Validation + Routing]
    ↓
Next Agent (e.g., wordpress-standards-validator)
    ↓
[Results + Approval/Rejection]
    ↓
Orchestrator [Decision Making]
```

---

## Agent Roles & Responsibilities

### Subagent-Orchestrator
- **Authority:** Workflow coordination, decision making
- **Receives:** Epic request, user approval
- **Executes:** Plans, delegates, monitors
- **Delivers:** PR with all tests passing

**No Direct Code Implementation** - Orchestrator does not write business logic code

### Wordpress-Developer
- **Authority:** PHP implementation
- **Receives:** Ticket with acceptance criteria
- **Executes:** Write code, create tests
- **Delivers:** Complete, tested implementation
- **Output:** Ticket marked complete, ready for standards check

**Constraint:** Works on ONE ticket at a time (but multiple can run in parallel with other developers)

### Wordpress-Standards-Validator
- **Authority:** Code quality and standards
- **Receives:** Completed code from wordpress-developer
- **Executes:** Run PHPCS, security audit, naming conventions
- **Delivers:** Pass/fail verdict with specific issues
- **Output:** Approval for commit or list of required fixes

**Constraint:** Cannot modify code, only validate

### Requirements-Analyzer
- **Authority:** Ticket analysis and planning
- **Receives:** Epic or individual tickets
- **Executes:** Break down requirements, identify dependencies
- **Delivers:** Execution plan with parallelization
- **Output:** Structured plan ready for approval

**Constraint:** Works only with requirements, not implementation

### Git-Workflow-Specialist
- **Authority:** All Git operations (exclusive)
- **Receives:** Completed ticket from wordpress-developer + standards validator approval
- **Executes:** Create commits, manage branches, handle PRs
- **Delivers:** Code committed and pushed to epic branch
- **Output:** Commit hash, branch status

**Constraint:** Only agent that can run git commands

### Local-Testing-Specialist
- **Authority:** Testing and quality verification
- **Receives:** Completed tickets or final epic for testing
- **Executes:** Run test suite, security audit, functionality tests
- **Delivers:** Test report with pass/fail status
- **Output:** Approval for PR creation or list of required fixes

**Constraint:** No code modification, only testing

### Subagent-Creator
- **Authority:** New agent creation (called by orchestrator when needed)
- **Receives:** Agent specification request with ALL 6 required fields:
  - Role, Authority, Model, Tools, Color, Status
- **Executes:** Design, document, create new agent with 6-field header
- **Delivers:** New agent file + documentation + registry entry
- **Output:** Ready-to-use agent in `.claude/agents/` with complete metadata
- **Quality Gates:** ALL 6 fields required, <100 lines, agent-registry.json updated

**Constraint:** Only creates agents, doesn't execute domain logic

### Epic-Ticket-Generator
- **Authority:** Epic breakdown into tickets
- **Receives:** Epic number/file
- **Executes:** Parse epic, create individual tickets
- **Delivers:** All tickets organized in directory structure
- **Output:** Ready-for-development ticket files

**Constraint:** Works only with epic documents, not ticket implementation

---

## Handoff Protocols

### From Orchestrator → Requirements-Analyzer

**Input:**
```json
{
  "action": "analyze_epic",
  "epic_number": "01",
  "epic_file": "/path/to/EPIC-01.md",
  "include": ["dependencies", "parallelization", "risk_assessment"]
}
```

**Output:**
```json
{
  "status": "success",
  "epic": "EPIC-01",
  "tickets_total": 9,
  "phases": [
    {
      "phase": 1,
      "name": "Parallel Foundation",
      "tickets": ["US-01.1", "US-01.2", "US-01.3"],
      "can_parallel": true,
      "estimated_time": "2h"
    }
  ],
  "dependencies": [
    {"blocker": "US-01.1", "blocked_by": "none"},
    {"blocker": "US-01.4", "blocked_by": ["US-01.1", "TT-01.1"]}
  ]
}
```

### From Orchestrator → Wordpress-Developer

**Input:**
```json
{
  "action": "implement_ticket",
  "ticket_id": "US-01.1",
  "ticket_file": "/path/to/us-01.1-plugin-activation.md",
  "acceptance_criteria": [
    "Plugin header contains all required WordPress metadata",
    "Plugin appears in WordPress admin under Plugins",
    "Activation completes without errors"
  ],
  "blockers": "none",
  "start_time": "2025-10-16T10:00:00Z"
}
```

**Output:**
```json
{
  "status": "complete",
  "ticket_id": "US-01.1",
  "files_created": [
    "ga-plugin.php"
  ],
  "files_modified": [],
  "tests_passed": true,
  "acceptance_criteria_met": [true, true, true],
  "completion_time": "2025-10-16T12:05:00Z",
  "ready_for_validation": true
}
```

### From Wordpress-Developer → Wordpress-Standards-Validator

**Input:**
```json
{
  "action": "validate_code",
  "ticket_id": "US-01.1",
  "files": ["ga-plugin.php"],
  "checks": ["phpcs", "security", "naming", "documentation"]
}
```

**Output:**
```json
{
  "status": "pass|fail",
  "ticket_id": "US-01.1",
  "results": {
    "phpcs": {
      "status": "pass",
      "errors": 0,
      "warnings": 0
    },
    "security": {
      "status": "pass",
      "issues": []
    },
    "naming": {
      "status": "pass",
      "issues": []
    },
    "documentation": {
      "status": "pass",
      "issues": []
    }
  },
  "approved_for_commit": true
}
```

### From Wordpress-Standards-Validator → Git-Workflow-Specialist

**Input:**
```json
{
  "action": "commit_ticket",
  "ticket_id": "US-01.1",
  "branch": "epic-01-foundation",
  "message": "[feat](epic-01): US-01.1 - Plugin activation with WordPress header",
  "files": ["ga-plugin.php"]
}
```

**Output:**
```json
{
  "status": "success",
  "ticket_id": "US-01.1",
  "commit_hash": "a1b2c3d4e5f6g7h8i9j0",
  "branch": "epic-01-foundation",
  "files_committed": 1,
  "ready_for_next_ticket": true
}
```

### From Git-Workflow-Specialist → Local-Testing-Specialist

**Input:**
```json
{
  "action": "test_epic",
  "epic": "EPIC-01",
  "branch": "epic-01-foundation",
  "status": "complete",
  "all_tickets_committed": true,
  "test_types": ["security", "functionality", "standards"]
}
```

**Output:**
```json
{
  "status": "success",
  "epic": "EPIC-01",
  "test_results": {
    "security": { "status": "pass", "issues": [] },
    "functionality": { "status": "pass", "failures": 0 },
    "standards": { "status": "pass", "violations": 0 }
  },
  "approved_for_pr": true,
  "pr_ready": true
}
```

---

## Status Update Protocol

### Every 30 Minutes or Ticket Completion

**Orchestrator Sends to TodoWrite:**

```
Current Status: EPIC-01 Execution

Progress: 4/9 tickets (44%)

Recent Completions:
✅ US-01.1: Plugin Activation (2h 5m)
✅ US-01.2: Plugin Constants (28m)
✅ US-01.3: Autoloader (58m)
✅ TT-01.1: Main Plugin File (2h)

Currently Working:
🔄 US-01.4: Activation/Deactivation (45m into 1.5h estimate)

Next Up:
⏳ US-01.5: Plugin Initialization
⏳ TT-01.2: Activator Class
⏳ TT-01.3: Placeholder Classes

Quality Gate Passes:
✓ WordPress Standards: 4/4
✓ Security Audit: 4/4
✓ Tests: 4/4

Overall Timeline:
⏱️ Elapsed: 4h 10m
⏱️ Remaining: ~1h 50m
⏱️ On Schedule: YES ✓
```

---

## Failure Handling

### When Code Fails Standards

**Scenario:** wordpress-standards-validator rejects US-01.1

```
Orchestrator receives:
{
  "status": "fail",
  "ticket_id": "US-01.1",
  "reason": "PHPCS violations",
  "issues": [
    {
      "file": "ga-plugin.php",
      "line": 45,
      "issue": "Function name should be prefixed with gap_",
      "suggestion": "Rename get_value() to gap_get_value()"
    }
  ]
}

Orchestrator Action:
1. Stops commit for this ticket
2. Sends ticket back to wordpress-developer with issues
3. wordpress-developer fixes code
4. Re-runs wordpress-standards-validator
5. Repeat until approval
```

### When Tests Fail

**Scenario:** local-testing-specialist finds test failures on EPIC-01

```
Orchestrator receives:
{
  "status": "fail",
  "epic": "EPIC-01",
  "test_results": {
    "security": { "issues": ["Missing ABSPATH check in class-gap-cpt.php"] },
    "functionality": { "failures": 2 }
  }
}

Orchestrator Action:
1. Blocks PR creation
2. Reports issues to user with details
3. Offers options: [Fix] [Rollback] [Review]
4. Routes to appropriate agent for fixes
```

---

## Parallel Execution Coordination

### Multiple Agents Working Simultaneously

**Example: Phase 1 with 3 Independent Tickets**

```
Time    wordpress-developer[1]    wordpress-developer[2]    wordpress-developer[3]
10:00   └─ US-01.1 started        └─ US-01.2 started        └─ US-01.3 started
10:30   └─ US-01.1 in progress    └─ US-01.2 complete       └─ US-01.3 in progress
10:45   └─ US-01.1 complete       └─ Waiting...             └─ US-01.3 complete
11:00   └─ All 3 developers waiting for validator

        wordpress-standards-validator
11:00   └─ Starting validation of all 3 tickets
11:15   └─ All 3 tickets approved
11:20   └─ Orchestrator aggregates commits

        git-workflow-specialist
11:20   └─ Commits: US-01.1, US-01.2, US-01.3 in sequence
11:25   └─ All pushed to epic-01-foundation
11:30   └─ Phase 1 complete, Phase 2 begins
```

**Orchestrator Ensures:**
- Each agent gets one ticket at a time
- No race conditions on shared resources
- Sequential git operations (only one commit at a time)
- Quality gates run before moving to next phase

---

## Agent Discovery & Creation

### When Orchestrator Needs a New Agent

```
Orchestrator: "I need an agent for <specific task>"

Specification MUST include (mandatory):
- Role: One-line description
- Authority: Exclusive boundaries
- Model: Claude Sonnet 4.5 or Claude Haiku 4.5
- Tools: Minimal permissions (Read, Write, Edit, Bash, Task, TodoWrite)
- Color: Emoji + color name (🟣 Purple, 🔵 Blue, etc.)
- Status: Operational status description

Decision Logic:
1. Check if agent already exists
2. If not, call subagent-creator with complete specification (all 6 fields)
3. subagent-creator creates agent with 6-field header
4. subagent-creator updates agent-registry.json
5. Orchestrator verifies all fields present and correct
6. Orchestrator delegates to new agent
```

**Example:**
```
Orchestrator: "We need performance testing"
Specification:
- Role: Performance Monitoring & Optimization
- Authority: Performance testing and metrics collection
- Model: Claude Sonnet 4.5
- Tools: Read, Bash
- Color: 🟣 Purple
- Status: Testing GA Plugin performance metrics

→ subagent-creator creates performance-testing-agent with 6 fields
→ Agent registry updated with all metadata
→ Orchestrator delegates performance tests to new agent
```

---

## Success Metrics

### Agent Coordination Health

✅ **Handoff Success Rate** - All handoffs complete without errors
✅ **Quality Gate Pass Rate** - 100% of gates pass on first submission
✅ **Parallelization Efficiency** - Achieves estimated time savings
✅ **Error Recovery** - Issues resolved without user intervention
✅ **Communication Clarity** - All agents understand their role

---

**Last Updated:** 2025-10-16 | **Owner:** subagent-orchestrator | **Related:** [Orchestration Workflow](orchestration-workflow.md), [Checkpoint Guide](checkpoint-guide.md)
