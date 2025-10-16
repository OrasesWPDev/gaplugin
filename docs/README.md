# GA Plugin Documentation

Complete reference documentation for GA Plugin development workflow, processes, and procedures.

## Quick Navigation

### 📋 Development Process
- **[Ticket System](development/ticket-system.md)** - Ticket ID formats, directory structure, organization
- **[Template Variables](development/template-variables.md)** - Complete reference for all ticket template variables
- **[Quality Checklist](development/quality-checklist.md)** - Pre-finalization and pre-commit quality checks
- **[Ticket Examples](development/ticket-examples.md)** - Well-written example tickets (user stories & technical tasks)
- **[Agent Creation](development/agent-creation.md)** - Creating new specialized agents following lean architecture
- **[Agent Template](development/agent-template.md)** - Template and examples for new agents

### 🌿 Git Workflow
- **[Branching Strategy](git/branching-strategy.md)** - Branch naming, creation, lifecycle, parallel development
- **[Commit Format](git/commit-format.md)** - Commit message standards and examples
- **[File Ownership](git/file-ownership.md)** - Which epic owns which files, conflict prevention
- **[Emergency Procedures](git/emergency-procedures.md)** - Rollback, recovery, conflict resolution

### 🧪 Testing
- **[Environment Setup](testing/environment-setup.md)** - Local WordPress configuration and paths
- **[Test Procedures](testing/test-procedures.md)** - Detailed testing steps for each component
- **[Quality Gates](testing/quality-gates.md)** - Requirements that block PR creation
- **[Test Templates](testing/test-templates.md)** - Test report formats and templates

### ⚙️ Commands & Tools
- **[Command Reference](commands/reference.md)** - All available commands and their usage

### 🤖 Orchestration (SWARM Methodology)
- **[Orchestration Workflow](orchestration/orchestration-workflow.md)** - Complete epic execution process
- **[Checkpoint Guide](orchestration/checkpoint-guide.md)** - Configure pause points for manual review
- **[Agent Coordination](orchestration/agent-coordination.md)** - How agents communicate and hand off
- **[Parallelization Rules](orchestration/parallelization-rules.md)** - When to run tasks in parallel
- **[Quality Gates](orchestration/quality-gates.md)** - Blocking requirements at each phase
- **[Agent Delegation Matrix](orchestration/agent-delegation-matrix.md)** - Who handles what work
- **[Failure Recovery](orchestration/failure-recovery.md)** - Handling errors and recovery strategies

---

## Workflow Overview

### 🚀 NEW: Automated Epic Orchestration (RECOMMENDED)

Use the new SWARM-based orchestrator for fully automated epic execution:

```bash
# Full automation - orchestrator handles everything
/work-epic 01

# With checkpoints - pause for manual review
/work-epic 01 --checkpoint=US

# Manual control - pause after every ticket
/work-epic 01 --checkpoint=manual
```

**What the Orchestrator Does:**
1. Analyzes epic and creates execution plan
2. Breaks epic into individual tickets
3. Develops in parallel where safe
4. Validates quality gates at each step
5. Creates PR when complete

**Time Savings:** SWARM parallelization saves 30-50% of execution time

See: [Orchestration Workflow](orchestration/orchestration-workflow.md) | [Checkpoint Guide](orchestration/checkpoint-guide.md)

---

### 1. Starting Work on an Epic (Manual - For Reference)
1. Read EPIC file in `docs/tickets/EPIC-XX-name/EPIC.md`
2. Review tickets in `/user-stories/` and `/technical-tasks/`
3. Use `/work-epic` command for automated orchestration
4. Or use `/break-down-epic` for manual ticket creation

**References:**
- [Branching Strategy](git/branching-strategy.md)
- [Ticket System](development/ticket-system.md)

### 2. Starting a Ticket
1. Use `/start-ticket US-XX.X` or `/start-ticket TT-XX.X`
2. Read ticket file with acceptance criteria
3. Review dependencies (blocking tickets)
4. Check quality with [Quality Checklist](development/quality-checklist.md)

**References:**
- [Ticket System](development/ticket-system.md)
- [Quality Checklist](development/quality-checklist.md)

### 3. Implementing Ticket
1. Follow ticket's implementation tasks
2. Verify acceptance criteria
3. Use [Test Procedures](testing/test-procedures.md) to test locally
4. Ensure [Quality Gates](testing/quality-gates.md) pass
5. Follow [File Ownership](git/file-ownership.md) guidelines

**References:**
- [Commit Format](git/commit-format.md)
- [Test Procedures](testing/test-procedures.md)
- [Quality Gates](testing/quality-gates.md)

### 4. Completing Ticket
1. Verify all acceptance criteria met
2. Run full test suite
3. Ensure no security issues
4. Use `/complete-ticket` command
5. Commit automatically generated with ticket reference

**References:**
- [Quality Checklist](development/quality-checklist.md)
- [Commit Format](git/commit-format.md)

### 5. Submitting PR
1. Ensure all epic tickets complete
2. Sync epic branch with main
3. PR created automatically by git-workflow-specialist
4. All tests must pass before merge approval

**References:**
- [Branching Strategy](git/branching-strategy.md)
- [Emergency Procedures](git/emergency-procedures.md)

---

## Key Documents by Role

### 👨‍💻 Developers
**Must Read:**
1. [Ticket System](development/ticket-system.md) - Understand ticket structure
2. [Quality Checklist](development/quality-checklist.md) - Verify ticket quality before starting
3. [Test Procedures](testing/test-procedures.md) - Know how to test your work
4. [Commit Format](git/commit-format.md) - Write proper commit messages

**Reference As Needed:**
- [File Ownership](git/file-ownership.md) - Avoid conflict-prone files
- [Template Variables](development/template-variables.md) - Fill ticket fields correctly
- [Emergency Procedures](git/emergency-procedures.md) - Recover from mistakes

### 📊 Epic Owners (Planning)
**Must Read:**
1. [Ticket System](development/ticket-system.md) - Understand ticket organization
2. [Quality Checklist](development/quality-checklist.md) - Review tickets before development
3. [Ticket Examples](development/ticket-examples.md) - See quality standards
4. [File Ownership](git/file-ownership.md) - Plan epic scope and dependencies

**Reference As Needed:**
- [Template Variables](development/template-variables.md) - Create high-quality tickets
- [Branching Strategy](git/branching-strategy.md) - Understand merge order
- [Test Templates](testing/test-templates.md) - Review testing approach

### 🔍 Reviewers/Testers
**Must Read:**
1. [Quality Gates](testing/quality-gates.md) - What gates block PRs
2. [Test Templates](testing/test-templates.md) - How to report tests
3. [Quality Checklist](development/quality-checklist.md) - Verify completeness
4. [Ticket Examples](development/ticket-examples.md) - Understand ticket quality

**Reference As Needed:**
- [Test Procedures](testing/test-procedures.md) - Detailed testing steps
- [File Ownership](git/file-ownership.md) - Identify potential conflicts
- [Emergency Procedures](git/emergency-procedures.md) - Handle critical issues

### 🛠️ DevOps/Infrastructure
**Must Read:**
1. [Environment Setup](testing/environment-setup.md) - Local environment configuration
2. [Branching Strategy](git/branching-strategy.md) - Branch management
3. [Emergency Procedures](git/emergency-procedures.md) - Incident response

### 🤖 Agent Developers (System/Infrastructure)
**Must Read:**
1. [Agent Creation](development/agent-creation.md) - Complete agent creation process
2. [Agent Template](development/agent-template.md) - Templates and examples
3. [Ticket System](development/ticket-system.md) - Understanding dependencies and workflow

**Reference As Needed:**
- [Quality Checklist](development/quality-checklist.md) - Verify agent architecture quality
- All domain docs (development, git, testing) - For creating domain-specific agents
- [Command Reference](commands/reference.md) - If creating new commands

---

## Common Tasks

### "I need to create a new ticket"
→ Read [Ticket System](development/ticket-system.md) + [Template Variables](development/template-variables.md)
→ Review [Ticket Examples](development/ticket-examples.md) for quality standards
→ Use [Quality Checklist](development/quality-checklist.md) before finalizing

### "I need to start working on a ticket"
→ Use `/start-ticket` command
→ Read the ticket file
→ Review [Quality Checklist](development/quality-checklist.md)
→ Check [File Ownership](git/file-ownership.md) to avoid conflicts

### "How do I test my work?"
→ Review [Test Procedures](testing/test-procedures.md)
→ Follow [Test Templates](testing/test-templates.md) to document
→ Verify [Quality Gates](testing/quality-gates.md) are met

### "I need to commit my changes"
→ Read [Commit Format](git/commit-format.md)
→ Use `/complete-ticket` for auto-generated commit message
→ Or manually follow the format exactly

### "I need to create a branch"
→ Read [Branching Strategy](git/branching-strategy.md)
→ Use format: `epic-XX-name`
→ Create from latest main

### "Something went wrong with git"
→ Read [Emergency Procedures](git/emergency-procedures.md)
→ Follow the recovery steps
→ Contact team if stuck

### "I need to create a new agent"
→ Read [Agent Creation](development/agent-creation.md) - Complete guide
→ Review [Agent Template](development/agent-template.md) - Examples and template
→ Use `/create-agent` command or call subagent-creator directly
→ Verify agent and docs follow lean architecture pattern

---

## Tools & Commands

### Agents (Autonomous Workers)
- **subagent-creator**: Creates new specialized agents with supporting documentation
  - See: [Agent Creation](development/agent-creation.md), [Agent Template](development/agent-template.md)
- **epic-ticket-generator**: Creates individual tickets from epic documents
  - See: [Ticket System](development/ticket-system.md)
- **git-workflow-specialist**: Manages all git operations
  - See: [Branching Strategy](git/branching-strategy.md), [Commit Format](git/commit-format.md)
- **local-testing-specialist**: Runs comprehensive testing suite
  - See: [Test Procedures](testing/test-procedures.md), [Quality Gates](testing/quality-gates.md)

### Commands (User-Initiated)

**Orchestration (New - RECOMMENDED):**
- `/work-epic` - Orchestrate complete epic execution (automated or with checkpoints)
- `/work-status` - Show current orchestration progress
- `/work-pause` - Pause orchestration immediately
- `/work-resume` - Resume paused orchestration
- `/work-rollback` - Rollback to previous state for retry

**Development (Manual workflow):**
- `/create-agent` - Create new specialized agent with documentation
- `/break-down-epic` - Create tickets from epic document
- `/start-ticket` - Begin work on a ticket
- `/complete-ticket` - Mark ticket complete and commit
- `/epic-status` - View epic progress

See [Command Reference](commands/reference.md) for detailed usage.

---

## Key Concepts

### Tickets
- **User Stories (US-XX.Y)**: Feature from end-user perspective
- **Technical Tasks (TT-XX.Y)**: Implementation work supporting user stories
- See: [Ticket System](development/ticket-system.md)

### Epics
- Large feature area, typically 1-2 weeks of work
- Contains 5-10 tickets (user stories + technical tasks)
- One branch per epic for parallel development
- See: [Branching Strategy](git/branching-strategy.md)

### File Ownership
- Each epic owns specific files
- Prevents conflicts during parallel development
- Cross-epic modifications require coordination
- See: [File Ownership](git/file-ownership.md)

### Quality Gates
- Tests that MUST pass before PR merge
- Block PR creation if failing
- Ensure code quality and security
- See: [Quality Gates](testing/quality-gates.md)

---

## Document Maintenance

### Adding New Documentation
1. Determine category (development, git, testing, commands)
2. Create `.md` file in appropriate directory
3. Add link to this README
4. Update search keywords
5. Reference other docs if related

### Updating Existing Documentation
1. Keep examples current
2. Update procedures if workflow changes
3. Verify all links still work
4. Check for duplicate information
5. Update related documents if needed

### Archiving Old Documentation
1. Move to `docs/archive/`
2. Add note about deprecation to README
3. Keep for historical reference
4. Link to replacement document

---

## Quick Reference Cards

### Ticket ID Format
```
US-XX.Y    User Story (XX = epic, Y = sequence)
TT-XX.Y    Technical Task (XX = epic, Y = sequence)
```

### Branch Naming
```
epic-XX-name    Epic branch (e.g., epic-02-admin-interface)
main            Production branch
```

### Priority Levels
```
P0    Critical (must have)
P1    High (should have)
P2    Medium (nice to have)
P3    Low (future)
```

### Estimation (Story Points)
```
1, 2, 3, 5, 8, 13    Fibonacci sequence
```

### Commit Types
```
feat       New feature
fix        Bug fix
refactor   Code restructure
docs       Documentation
test       Tests
chore      Maintenance
```

---

## Document Index

| Document | Size | Purpose |
|----------|------|---------|
| [Ticket System](development/ticket-system.md) | 8KB | Ticket formats, IDs, organization |
| [Template Variables](development/template-variables.md) | 12KB | Ticket template variable reference |
| [Quality Checklist](development/quality-checklist.md) | 10KB | Pre-finalization quality checks |
| [Ticket Examples](development/ticket-examples.md) | 15KB | Example well-written tickets |
| [Agent Creation](development/agent-creation.md) | 22KB | Creating new specialized agents |
| [Agent Template](development/agent-template.md) | 18KB | Agent and documentation templates |
| [Branching Strategy](git/branching-strategy.md) | 12KB | Branch naming and lifecycle |
| [Commit Format](git/commit-format.md) | 10KB | Commit message standards |
| [File Ownership](git/file-ownership.md) | 14KB | File ownership matrix |
| [Emergency Procedures](git/emergency-procedures.md) | 12KB | Recovery and troubleshooting |
| [Environment Setup](testing/environment-setup.md) | 8KB | Testing environment config |
| [Test Procedures](testing/test-procedures.md) | 20KB | Detailed test steps |
| [Quality Gates](testing/quality-gates.md) | 8KB | PR blocking requirements |
| [Test Templates](testing/test-templates.md) | 6KB | Test report formats |
| [Command Reference](commands/reference.md) | 6KB | Command usage guide |

**Total Documentation**: ~175KB

---

## Contributing to Documentation

### Guidelines
1. Use clear, concise language
2. Provide examples for each concept
3. Link to related documents
4. Keep formatting consistent
5. Update table of contents
6. Test all links work

### Document Template
```markdown
# [Document Title]

[Brief description of what's in this document]

## Section 1
[Content]

## Section 2
[Content]

## Quick Reference
[Summary table if applicable]

## See Also
- [Related Document 1](link)
- [Related Document 2](link)
```

---

## Contact & Support

### Questions About Documentation?
- Check the [FAQ](#faq) section below
- Review related documents
- Ask the team on Slack

### Found an Issue?
- Update the documentation (if simple)
- Create an issue with correction (if complex)
- Discuss with epic owner
- Update related documents

### Want to Improve Documentation?
- Suggest improvements
- Add missing sections
- Update outdated content
- Improve examples

---

## FAQ

### Q: When should I read this documentation?
**A:** Read the relevant section:
- Before starting a new ticket → Read [Quality Checklist](development/quality-checklist.md)
- Before committing → Read [Commit Format](git/commit-format.md)
- Before pushing PR → Read [Quality Gates](testing/quality-gates.md)
- When something breaks → Read [Emergency Procedures](git/emergency-procedures.md)

### Q: Do I need to read all documents?
**A:** No. Read your role's documents and reference others as needed.
See "Key Documents by Role" section above.

### Q: What if documentation is unclear?
**A:**
1. Check related documents for context
2. Review examples for clarification
3. Ask the team
4. Help improve the documentation

### Q: How often is documentation updated?
**A:** As workflow changes or issues discovered.
Last major update: 2025-10-16

---

**Documentation Version**: 1.0
**Last Updated**: 2025-10-16
**Maintained By**: Development Team
