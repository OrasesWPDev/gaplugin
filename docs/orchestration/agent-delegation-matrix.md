# Agent Delegation Matrix

Which agent is responsible for each type of work in the orchestration system.

---

## At-a-Glance Matrix

| Task | Agent | Model | Color | Authority |
|------|-------|-------|-------|-----------|
| Epic Analysis & Planning | requirements-analyzer | Sonnet 4.5 | 🟢 Green | Ticket analysis |
| Epic Breakdown | epic-ticket-generator | Sonnet 4.5 | 🟣 Purple | Ticket generation |
| Implementation | wordpress-developer | Sonnet 4.5 | 🔵 Blue | Code writing |
| Standards Validation | wordpress-standards-validator | Haiku | 🟡 Yellow | Code quality |
| Git Operations | git-workflow-specialist | Sonnet 4.5 | 🔴 Red | Version control |
| Testing & Security | local-testing-specialist | Sonnet 4.5 | 🟠 Orange | Testing |
| Workflow Coordination | subagent-orchestrator | Sonnet 4.5 | 🟣 Purple | Orchestration |
| New Agent Creation | subagent-creator | Sonnet 4.5 | 🟣 Purple | Agent creation |

---

## Detailed Responsibilities

### 1. Subagent-Orchestrator (🟣 Purple)
**Role:** Workflow Commander
**Model:** Claude Sonnet 4.5
**Tools:** Read, Write, TodoWrite, Task, Bash, Edit
**Authority:** Exclusive workflow coordination

**Responsibilities:**
- Epic workflow management (analyze → delegate → monitor)
- Agent orchestration and delegation
- Quality gate enforcement
- Progress tracking and reporting
- Checkpoint management
- Failure handling and recovery
- PR creation coordination

**Does NOT:**
- Write business logic code
- Implement features
- Run tests directly
- Make git commits
- Validate code standards

**Delegates To:**
- requirements-analyzer (for analysis)
- epic-ticket-generator (for breakdown)
- wordpress-developer (for implementation)
- wordpress-standards-validator (for validation)
- local-testing-specialist (for testing)
- git-workflow-specialist (for git ops)
- subagent-creator (for new agents)

---

### 2. Requirements-Analyzer (🟢 Green)
**Role:** Epic Planner
**Model:** Claude Sonnet 4.5
**Tools:** Read, Write
**Authority:** Requirement analysis and planning

**Responsibilities:**
- Analyze epic documents
- Identify all tickets (US + TT)
- Map dependencies (blockers)
- Analyze parallelization opportunities
- Identify file ownership conflicts
- Estimate complexity and time
- Create execution plan with phases
- Risk assessment

**Does NOT:**
- Implement any code
- Modify files
- Run tests
- Create actual tickets (that's epic-ticket-generator)

**Output:**
- Structured execution plan
- Dependency graph
- Parallelization recommendations
- Risk assessment report

---

### 3. Epic-Ticket-Generator (🟣 Purple)
**Role:** Ticket Creator
**Model:** Claude Sonnet 4.5
**Tools:** Read, Write, Bash
**Authority:** Breaking epics into tickets

**Responsibilities:**
- Parse epic markdown documents
- Extract all user stories
- Extract all technical tasks
- Create individual ticket files
- Organize in directory structure
- Generate epic README
- Map ticket relationships
- Ensure no information loss

**Does NOT:**
- Implement code
- Run tests
- Validate standards
- Modify existing tickets
- Make decisions about tickets

**Output:**
- Individual `.md` ticket files
- Organized directory structure
- Epic README with overview
- Ready-for-development tickets

---

### 4. Wordpress-Developer (🔵 Blue)
**Role:** Code Implementer
**Model:** Claude Sonnet 4.5
**Tools:** Read, Write, Edit, Bash
**Authority:** PHP implementation and testing

**Responsibilities:**
- Implement ticket requirements
- Write WordPress-compliant PHP code
- Create and run unit tests
- Follow acceptance criteria
- Create test data as needed
- Add inline documentation
- Follow security best practices
- One ticket at a time (but multiple can run in parallel)

**Does NOT:**
- Validate standards (that's validator's job)
- Commit to git (that's git-specialist's job)
- Make high-level decisions
- Modify other tickets' code
- Create new agents

**Output:**
- Implemented code
- Tests (passing)
- Documentation
- Ticket marked complete

**Receives:**
- Ticket file with requirements
- Acceptance criteria list
- Blockers/dependencies info
- Definition of done checklist

---

### 5. Wordpress-Standards-Validator (🟡 Yellow)
**Role:** Code Quality Guardian
**Model:** Claude Haiku (fast validation)
**Tools:** Read, Bash
**Authority:** Code quality and standards

**Responsibilities:**
- Run PHPCS with WordPress-VIP-Go ruleset
- Validate security patterns
- Check naming conventions
- Verify documentation blocks
- Ensure no debug code
- Validate escaping/sanitization
- Check file structure
- Return pass/fail verdict

**Does NOT:**
- Modify code (developer must fix)
- Make business logic decisions
- Skip any validation
- Override own results

**Output:**
- Pass/Fail verdict
- List of violations (if failed)
- Specific line numbers and suggestions

**Receives:**
- List of files to validate
- Standards ruleset to use
- Ticket ID for reference

---

### 6. Git-Workflow-Specialist (🔴 Red)
**Role:** Version Control Manager
**Model:** Claude Sonnet 4.5
**Tools:** Bash, Read
**Authority:** EXCLUSIVE git operations

**Responsibilities:**
- Create epic branches
- Create commits (one per ticket)
- Format commit messages
- Push to remote
- Sync with main
- Detect conflicts
- Create PRs
- Handle merges
- Manage branch cleanup

**Does NOT:**
- Allow other agents to run git commands
- Modify code (that's developer's job)
- Make decisions about what to commit

**Output:**
- Branch created/updated
- Commits pushed to remote
- PR created with description
- Merge status updates

**Receives:**
- Ticket ID and description
- Files to commit
- Commit message template
- Merge instructions

**Key Constraint:** ONLY agent that runs git commands. All git requests routed here.

---

### 7. Local-Testing-Specialist (🟠 Orange)
**Role:** Quality Assurance & Security
**Model:** Claude Sonnet 4.5
**Tools:** Bash, Read
**Authority:** Testing and security validation

**Responsibilities:**
- Run automated test suite
- Execute security audit
- Verify plugin activation
- Test functionality
- Create test data
- Generate test reports
- Validate quality gates
- Check for regressions

**Does NOT:**
- Implement code
- Validate coding standards (that's validator's job)
- Make business decisions

**Output:**
- Test report with pass/fail
- Security audit results
- Coverage metrics
- Recommendations

**Receives:**
- Branch to test
- Test suite to run
- Security checklist
- Expected results

---

### 8. Subagent-Creator (🟣 Purple)
**Role:** Agent Factory
**Model:** Claude Sonnet 4.5
**Tools:** Read, Write, Edit
**Authority:** Creating new specialized agents

**Responsibilities:**
- Design new agents with ALL 6 required fields (Role, Authority, Model, Tools, Color, Status)
- Create agent documentation
- Write lean agent files (40-100 lines)
- Enforce architecture standards
- Verify DRY principle
- Quality gate new agents (mandatory blocking gates)
- Update agent-registry.json with complete entry
- Update master documentation

**MANDATORY Quality Gates (Block if failed):**
- ✅ Agent header has ALL 6 required fields present
- ✅ Model is Claude Sonnet 4.5 or Claude Haiku 4.5
- ✅ Tools allocation is minimal and necessary
- ✅ Color is emoji + color name format
- ✅ Agent file is <100 lines
- ✅ agent-registry.json entry has all 9 fields (id, name, role, model, color, tools, authority, file, status)
- ✅ No examples or procedures in agent (all in `/docs`)
- ✅ All reference material linked with correct paths

**Does NOT:**
- Implement domain logic
- Make orchestration decisions
- Run git commands

**Output:**
- New `.claude/agents/{name}.md` with complete 6-field header
- Supporting documentation in `/docs`
- Agent registry entry with all 9 fields (mandatory)

**Receives:**
- Agent specification with ALL 6 fields (mandatory)
- Reference materials needed
- Approval to proceed

---

## Work Flow by Phase

### Phase 1: Analysis & Planning

```
subagent-orchestrator
    ↓
requirements-analyzer (analyzes epic, creates plan)
    ↓
subagent-orchestrator (presents plan to user)
    ↓
[User approves]
    ↓
subagent-orchestrator (proceeds to Phase 2)
```

### Phase 2: Breakdown

```
subagent-orchestrator
    ↓
epic-ticket-generator (breaks down epic into tickets)
    ↓
subagent-orchestrator (generates execution todo list)
```

### Phase 3: Implementation

```
subagent-orchestrator
    ↓
git-workflow-specialist (creates epic branch)
    ↓
subagent-orchestrator
    ├─→ wordpress-developer[1] (Ticket 1)
    ├─→ wordpress-developer[2] (Ticket 2)
    └─→ wordpress-developer[3] (Ticket 3)
         ↓
    wordpress-standards-validator (validate all 3)
         ↓
    git-workflow-specialist (commit all 3)
```

### Phase 4: Quality Assurance

```
subagent-orchestrator
    ↓
local-testing-specialist (run full test suite)
    ↓
subagent-orchestrator (verify all gates pass)
    ↓
[If passed] → Phase 5
[If failed] → Reassign to wordpress-developer for fixes
```

### Phase 5: PR & Merge

```
subagent-orchestrator
    ↓
git-workflow-specialist (create PR)
    ↓
subagent-orchestrator (monitor until merged)
    ↓
git-workflow-specialist (squash-merge to main)
```

---

## Agent Communication Flow

```
Developer Agent                 Validator Agent                  Orchestrator
    ↓                              ↓                               ↓
[Implement Ticket]
    └─ Ready for validation ────────→
                                [Validate Code]
                                    └─ Pass/Fail ─────────────────→
                                                          [Decision]
                                                    [Route to Next]
```

---

## When to Create New Agents

**Subagent-Orchestrator decides to create new agent when:**

1. New type of work emerges (e.g., performance testing)
2. Existing agent overwhelmed (delegated multiple to one agent)
3. Specialized skill needed (e.g., API testing)
4. Time savings justifies creation
5. Clear, bounded responsibility

**Example:**
```
Orchestrator: "We need WordPress security auditing"
Subagent-Creator: "Creating wordpress-security-auditor..."
Subagent-Creator: "New agent ready in .claude/agents/"
Orchestrator: "Adding to registry and delegating security audits"
```

---

## Escalation & Override

### When Does Orchestrator Override Agent?

1. **Quality Gate Failure** - Agent's work doesn't pass gates
2. **Dependency Issue** - Blocked by missing upstream work
3. **Resource Exhaustion** - Too many parallel tasks
4. **Timeout** - Agent takes too long

### When Can User Override?

1. **At Checkpoint** - Review and approve/reject
2. **After Failure** - Decide to continue, fix, or rollback
3. **Process Change** - Modify checkpoint strategy

### When Can User NOT Override?

1. **Quality Gates** - Never skip gates
2. **Agent Decisions** - Can't tell agent to do something wrong
3. **Git Operations** - Can't run git commands directly (must route to specialist)

---

**Last Updated:** 2025-10-16 | **Owner:** subagent-orchestrator | **Related:** [Agent Coordination](agent-coordination.md), [Orchestration Workflow](orchestration-workflow.md)
