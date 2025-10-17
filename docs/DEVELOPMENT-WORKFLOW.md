# GA Plugin v2 - Development Workflow

**Repository:** git@github.com:OrasesWPDev/gaplugin-v2.git
**Local Environment:** http://localhost:10029/
**Version:** 1.0.0
**Last Updated:** 2025-10-16

---

## Table of Contents

1. [Overview](#overview)
2. [Architecture](#architecture)
3. [Getting Started](#getting-started)
4. [Epic-Based Development](#epic-based-development)
5. [Ticket Workflow](#ticket-workflow)
6. [Testing Workflow](#testing-workflow)
7. [Git Workflow](#git-workflow)
8. [Agent Roles](#agent-roles)
9. [Commands Reference](#commands-reference)
10. [Best Practices](#best-practices)

---

## Overview

The GA Plugin v2 uses an **epic-based, multi-agent development workflow** designed for parallel development while maintaining code quality and preventing conflicts.

### Key Features

- **Epic-Based Structure:** Work organized into 6 epics with clear dependencies
- **Ticket-Level Granularity:** Each epic broken down into User Stories and Technical Tasks
- **Specialized Agents:** Dedicated agents for Git operations and local testing
- **Quality Gates:** Automated testing blocks PRs until all tests pass
- **Parallel Development:** Multiple epics can be worked on simultaneously

### Development Principles

1. **One branch per epic** (not per ticket)
2. **One commit per ticket** completion
3. **Test before PR** (all tests must pass)
4. **Git operations exclusive** to git-workflow-specialist
5. **No direct work on main** branch

---

## Architecture

### Epic Structure

```
docs/tickets/
├── EPIC-00-project-setup/
│   ├── EPIC.md                 # Epic overview and requirements
│   ├── user-stories/           # Individual US tickets
│   │   ├── us-00.1-*.md
│   │   └── us-00.2-*.md
│   └── technical-tasks/        # Individual TT tickets
│       ├── tt-00.1-*.md
│       └── tt-00.2-*.md
├── EPIC-01-foundation/
├── EPIC-02-admin-interface/
├── EPIC-03-conflict-detection/
├── EPIC-04-frontend-output/
└── EPIC-05-testing-launch/
```

### Epic Dependencies

```
EPIC-00 (project-setup)
    ↓
EPIC-01 (foundation)
    ↓
    ├─→ EPIC-02 (admin-interface)      ┐
    └─→ EPIC-03 (conflict-detection)   ├─ Can develop in parallel
            ↓                            ┘
        EPIC-04 (frontend-output) ← Both 02 and 03 must merge first
            ↓
        EPIC-05 (testing-launch)
```

### File Ownership

| Epic | Files | Protected From |
|------|-------|----------------|
| EPIC-00 | Project structure, docs | All other epics |
| EPIC-01 | ga-plugin.php, activator, autoloader | EPIC-02 through EPIC-05 |
| EPIC-02 | class-gap-cpt.php, class-gap-meta-boxes.php, admin assets | All except self |
| EPIC-03 | class-gap-conflict-detector.php | All except self |
| EPIC-04 | class-gap-frontend.php | All except self |
| EPIC-05 | Tests, docs, no new classes | All except self |

---

## Getting Started

### Prerequisites

- Local by Flywheel WordPress environment running
- GitHub CLI (`gh`) installed and authenticated
- Access to repository: git@github.com:OrasesWPDev/gaplugin-v2.git
- WP-CLI available in Local environment

### Initial Setup

1. **Clone Repository:**
   ```bash
   git clone git@github.com:OrasesWPDev/gaplugin-v2.git
   cd gaplugin-v2
   ```

2. **Verify Local Environment:**
   ```bash
   # Test WordPress is running
   curl -I http://localhost:10029/

   # Test admin access
   open http://localhost:10029/wp-admin/
   # Login: test1 / test1pass
   ```

3. **Review Documentation:**
   - Read `docs/GA-PLUGIN-PRD.md` - Product requirements
   - Read `docs/GA-PLUGIN-PLAN.md` - Technical plan
   - Read `docs/GIT-WORKFLOW.md` - Git workflow details

4. **Understand Epic Structure:**
   ```bash
   # View all epics
   ls -la docs/tickets/EPIC-*/

   # Read first epic
   cat docs/tickets/EPIC-00-project-setup/EPIC.md
   ```

---

## Epic-Based Development

### Starting an Epic

**Step 1: Choose Your Epic**

Review epic dependencies and choose an epic that:
- Has all upstream dependencies merged
- Is not currently being worked on by another developer
- Matches your skill set

**Step 2: Request Epic Branch**

The **git-workflow-specialist** agent will create the epic branch:

```
Request: "Start EPIC-02"

Agent executes:
- git checkout main
- git pull origin main
- git checkout -b epic-02-admin-interface
- git push -u origin epic-02-admin-interface
```

**Step 3: Review Epic Tickets**

```bash
# View all tickets for the epic
tree docs/tickets/EPIC-02-admin-interface/

# Read epic overview
cat docs/tickets/EPIC-02-admin-interface/EPIC.md
```

### Working Within an Epic

**Choose a Ticket:**
- Start with lowest numbered user story (US-XX.1)
- Or pick a technical task if it's not blocked
- Check ticket dependencies

**Start Ticket:**
```
Use command: /start-ticket US-02.1

This will:
- Display ticket details
- Check dependencies
- Create todo list from tasks
- Set up work context
```

**Complete Ticket:**
```
Use command: /complete-ticket US-02.1

This will:
- Verify acceptance criteria met
- Trigger git-workflow-specialist to commit
- Push changes to epic branch
- Update ticket status
```

### Epic Progress Tracking

**Check Status:**
```
Use command: /epic-status 02

Shows:
- Completed tickets
- In-progress tickets
- Available tickets
- Blocking issues
- Overall progress percentage
```

### Completing an Epic

**Step 1: Verify All Tickets Complete**

```bash
# Check epic status
/epic-status 02

# Ensure all tickets marked complete:
# User Stories: 5/5 ✅
# Technical Tasks: 4/4 ✅
```

**Step 2: Request Local Testing**

The **local-testing-specialist** will:
- Deploy code to local WordPress
- Run comprehensive test suite
- Generate test report
- Either approve or block PR

**Step 3: Create Pull Request**

If tests pass, **git-workflow-specialist** creates PR:
- Includes all ticket commits
- Attaches test report
- Lists dependencies
- Adds security checklist

**Step 4: Code Review**

PR reviewed by:
- Code reviewers
- Security reviewers (for security-critical epics)
- Test results verification

**Step 5: Merge**

Once approved, **git-workflow-specialist** merges:
- Squash-merge to main
- Delete epic branch
- Update local main

---

## Ticket Workflow

### Ticket Lifecycle

```
Not Started → In Progress → Testing → Completed → Committed
```

### User Story (US) Tickets

**Structure:**
- Ticket ID: US-XX.Y
- Priority: P0, P1, P2, P3
- Story Points: 1-13 (Fibonacci)
- Acceptance Criteria: Testable requirements
- Tasks: Implementation checklist with time estimates

**Example:**
```
US-02.1: As an administrator, I need to manage tracking scripts via CPT

Acceptance Criteria:
- [ ] Custom post type "tracking_script" registered
- [ ] Appears in admin menu with analytics icon
- [ ] Only administrators can access

Tasks:
- [ ] Create GAP_CPT class skeleton (20 min)
- [ ] Implement register_post_type() method (45 min)
...
```

### Technical Task (TT) Tickets

**Structure:**
- Ticket ID: TT-XX.Y
- Estimated Time: Hours/minutes
- File Path: Target file to modify
- Implementation Steps: Detailed technical instructions
- Code Examples: Reference implementations

**Example:**
```
TT-02.1: Implement GAP_CPT Class

File: includes/class-gap-cpt.php
Estimated Time: 2 hours

Implementation Steps:
1. Create class with singleton pattern
2. Implement register_post_type() method
3. Add custom columns
...
```

### Working on a Ticket

**1. Read Ticket Thoroughly**
```bash
cat docs/tickets/EPIC-XX-name/user-stories/us-XX.Y-*.md
```

**2. Understand Requirements**
- Review all acceptance criteria
- Check dependencies
- Note any blockers
- Review technical details

**3. Implement Solution**
- Follow WordPress coding standards
- Use GAP_ prefix for all functions/classes
- Add inline documentation
- Write secure code (nonces, sanitization, escaping)

**4. Test Locally**
- Deploy to local environment
- Test all acceptance criteria
- Verify no PHP errors
- Check WordPress debug.log

**5. Mark Complete**
```
/complete-ticket US-XX.Y
```

This triggers:
- Verification of acceptance criteria
- Automatic git commit via git-workflow-specialist
- Ticket status update
- Epic progress update

---

## Testing Workflow

### Local Testing Environment

**Environment Details:**
- URL: http://localhost:10029/
- Admin: http://localhost:10029/wp-admin/
- Username: `test1`
- Password: `test1pass`
- Plugin Path: `~/Local Sites/gap-testing/app/public/wp-content/plugins/ga-plugin`

### Deployment to Local

**Production Deployment (Recommended):**
```bash
# Deploy only production files (excludes development files)
./tools/deploy-to-local.sh
```

This script deploys ONLY production files:
- ✅ `ga-plugin.php` (main plugin file)
- ✅ `includes/` (core PHP classes)
- ✅ `assets/` (CSS and JS)
- ✅ `languages/` (translations)
- ✅ `LICENSE.txt`
- ✅ `README.md`
- ✅ `CHANGELOG.md`

Development files are EXCLUDED:
- ❌ `tests/` directory
- ❌ `vendor/` directory
- ❌ `composer.json`, `composer.lock`
- ❌ `phpunit.xml`, `phpcs.xml`
- ❌ Development reports (PHPCS, SECURITY, TESTING)
- ❌ Git and IDE files

**Quick Sync (During Development - Full Files):**
```
Request: "Deploy to local"

local-testing-specialist executes:
- rsync ALL files to Local WordPress (includes dev files)
- Verify deployment
- Report status
```

**Full Deployment (Before PR):**
```
Request: "Run full test suite for epic 02"

local-testing-specialist executes:
- Backup current plugin
- Deploy latest code
- Run all tests
- Generate comprehensive report
```

### Test Categories

**1. Plugin Activation**
- Activates without errors
- No PHP warnings
- Menu appears
- CPT registered

**2. Custom Post Type**
- List view loads
- Add New works
- Custom columns display
- Meta box renders

**3. Meta Fields**
- All fields save correctly
- Validation works
- Sanitization applied
- JavaScript functional

**4. Frontend Output**
- Scripts in correct placements
- Scope filtering works
- Active/inactive toggle works
- HTML comments present

**5. Conflict Detection**
- Admin warnings display
- Frontend scanning works
- Duplicates skipped
- Logging functional

**6. Security Audit**
- ABSPATH checks present
- Nonce verification working
- Capability checks enforced
- Input sanitized
- Output escaped
- No SQL injection
- No XSS vulnerabilities

**7. Coding Standards**
- PHPCS passes
- Naming conventions followed
- Documentation complete

### Quality Gates

**ALL tests must pass before PR creation:**
- ✅ Plugin activation: PASSED
- ✅ Meta fields: PASSED
- ✅ Frontend output: PASSED
- ✅ Security audit: PASSED
- ✅ Coding standards: PASSED

**If ANY test fails:**
- ❌ PR creation BLOCKED
- ❌ Fix issues
- ❌ Re-run tests

### Test Reports

Generated after every full test run:
```
~/test-reports/epic-XX-YYYYMMDD-HHMMSS.txt
```

Includes:
- Summary (pass/fail counts)
- Detailed results per category
- Failed test details
- Approval status
- Duration

---

## Git Workflow

### Branch Strategy

**Epic Branches:**
- Format: `epic-XX-name`
- One branch per epic
- Multiple ticket commits per branch
- Lives until epic PR merged

**Protected Branch:**
- `main` - No direct commits allowed
- All changes via PR
- Squash-merge only

### Commit Strategy

**One Commit Per Ticket:**
- Triggered automatically on ticket completion
- Generated by git-workflow-specialist
- Includes ticket ID in message
- References epic

**Commit Message Format:**
```
[type]([epic-scope]): [ticket-id] - [description]

- [Detail 1]
- [Detail 2]

Ticket: [TICKET-ID]
Epic: [EPIC-ID]
Status: Completed
```

**Example:**
```
feat(epic-02): US-02.1 - manage tracking scripts via CPT

- Implemented GAP_CPT class with singleton pattern
- Registered tracking_script custom post type
- Added custom admin columns
- Set capabilities to manage_options only

Ticket: US-02.1
Epic: EPIC-02
Status: Completed
```

### Pull Request Process

**1. Epic Complete**
- All tickets done
- All tests pass
- Code reviewed locally

**2. Request PR Creation**
```
Request: "Create PR for epic 02"
```

**3. git-workflow-specialist Creates PR**
- Verifies all commits pushed
- Generates PR body
- Includes test report
- Lists all tickets
- Creates PR via gh CLI

**4. PR Review**
- Code reviewers check code quality
- Security reviewers check vulnerabilities
- Test report verified

**5. Approval & Merge**
- All reviewers approve
- Tests confirmed passing
- git-workflow-specialist squash-merges
- Branch deleted
- Main updated

### Conflict Resolution

**Prevention:**
- Follow file ownership rules
- Merge dependencies in order
- Sync epic branches regularly

**If Conflicts Occur:**
- git-workflow-specialist detects conflicts
- Reports conflicting files
- Recommends resolution strategy
- Coordinates with other epic owners

---

## Agent Roles

### git-workflow-specialist

**Exclusive Responsibilities:**
- ALL Git operations (branch, commit, push, PR, merge)
- Branch creation and management
- Commit generation from ticket metadata
- PR creation with test reports
- Conflict detection and resolution
- Branch synchronization

**NO other agent may execute Git commands**

**Commands it executes:**
```bash
git checkout
git branch
git commit
git push
git merge
gh pr create
gh pr merge
```

### local-testing-specialist

**Responsibilities:**
- Deploy code to Local WordPress
- Run comprehensive test suites
- Generate test reports
- Gate PR creation (block if tests fail)
- Approve/reject PR requests

**Test Operations:**
```bash
rsync # Deploy files
wp plugin activate # WordPress operations
curl # Frontend testing
phpcs # Coding standards
```

**Does NOT execute Git commands**

### epic-ticket-generator

**Responsibilities:**
- Break down epics into individual tickets
- Generate user story tickets
- Generate technical task tickets
- Create epic README files
- Map dependencies

**Trigger:** `/break-down-epic XX`

### Development Specialists

**Future Specialists:**
- CPT specialist
- Meta box specialist
- Frontend output specialist
- Security scanner

**Rules:**
- Focus on their domain only
- NO Git operations
- Request commits via git-workflow-specialist
- Test via local-testing-specialist

---

## Commands Reference

### Epic Management

```bash
# Break down epic into tickets
/break-down-epic 02

# Check epic status
/epic-status 02

# Start working on epic (via git-workflow-specialist)
Request: "Start epic 02"
```

### Ticket Management

```bash
# Start a ticket
/start-ticket US-02.1

# Complete a ticket (triggers auto-commit)
/complete-ticket US-02.1

# View ticket details
cat docs/tickets/EPIC-XX-name/user-stories/us-XX.Y-*.md
```

### Testing

```bash
# Quick deploy
Request: "Deploy to local"

# Full test suite
Request: "Run full tests for epic 02"

# Specific test
Request: "Test plugin activation"
```

### Git Operations (via git-workflow-specialist only)

```bash
# Create epic branch
Request: "Start epic XX"

# Commit ticket
Request: "Commit ticket US-XX.Y"

# Create PR
Request: "Create PR for epic XX"

# Merge PR
Request: "Merge PR #XX"
```

---

## Best Practices

### Epic Development

**DO:**
- ✅ Follow epic dependency order
- ✅ Complete all tickets before requesting PR
- ✅ Test thoroughly in local environment
- ✅ Commit after each ticket completion
- ✅ Request reviews promptly

**DON'T:**
- ❌ Skip tickets or mark incomplete work as done
- ❌ Modify files owned by other epics
- ❌ Commit directly (use /complete-ticket)
- ❌ Create PRs without test approval
- ❌ Work on multiple epics simultaneously (unless coordinated)

### Ticket Development

**DO:**
- ✅ Read entire ticket before starting
- ✅ Check all acceptance criteria
- ✅ Follow WordPress coding standards
- ✅ Add inline documentation
- ✅ Test locally before marking complete

**DON'T:**
- ❌ Skip acceptance criteria
- ❌ Leave debug code (var_dump, print_r)
- ❌ Ignore security practices
- ❌ Use incorrect naming conventions
- ❌ Skip testing

### Testing

**DO:**
- ✅ Test every ticket in local environment
- ✅ Run full test suite before PR
- ✅ Fix all test failures before proceeding
- ✅ Verify no PHP errors in debug.log
- ✅ Test on fresh WordPress install periodically

**DON'T:**
- ❌ Skip local testing
- ❌ Ignore test failures
- ❌ Test only in development (not Local)
- ❌ Hardcode test credentials
- ❌ Approve PRs with failing tests

### Git Operations

**DO:**
- ✅ Always use git-workflow-specialist for Git commands
- ✅ Let automatic commits happen on ticket completion
- ✅ Trust the specialized agents
- ✅ Follow branch naming conventions
- ✅ Keep epic branches up to date

**DON'T:**
- ❌ Execute Git commands directly
- ❌ Work on main branch
- ❌ Force push
- ❌ Rewrite history after pushing
- ❌ Delete branches before merge

### Code Quality

**DO:**
- ✅ Use GAP_ prefix for all plugin code
- ✅ Add ABSPATH check to all PHP files
- ✅ Use nonces for all forms
- ✅ Check capabilities (manage_options)
- ✅ Sanitize all inputs
- ✅ Escape all outputs
- ✅ Document all functions/methods

**DON'T:**
- ❌ Use global variables
- ❌ Skip input validation
- ❌ Forget output escaping
- ❌ Leave TODO comments in production
- ❌ Use deprecated WordPress functions

---

## Quick Start Example

### Complete Workflow for EPIC-02

**1. Start Epic:**
```
Developer: "Start epic 02"
git-workflow-specialist: Creates branch epic-02-admin-interface
```

**2. First Ticket:**
```
Developer: /start-ticket US-02.1
Claude: [Displays ticket details, creates todo list]
Developer: [Implements GAP_CPT class]
Developer: [Tests in local environment]
Developer: /complete-ticket US-02.1
git-workflow-specialist: [Commits and pushes]
```

**3. Continue with Remaining Tickets:**
```
Repeat for: US-02.2, US-02.3, US-02.4, US-02.5
And: TT-02.1, TT-02.2, TT-02.3, TT-02.4
```

**4. Epic Complete:**
```
Developer: /epic-status 02
[Shows 9/9 tickets complete]

Developer: "Run full test suite for epic 02"
local-testing-specialist: [Runs all tests]
local-testing-specialist: ✅ ALL TESTS PASSED

Developer: "Create PR for epic 02"
git-workflow-specialist: [Creates PR #XX]
```

**5. Review & Merge:**
```
[Code reviewers approve]
[Security reviewers approve]

Developer: "Merge PR #XX"
git-workflow-specialist: [Squash-merges to main, deletes branch]
```

**6. Next Epic:**
```
Developer: "Start epic 03"
[Repeat process]
```

---

## Troubleshooting

### "Cannot start epic - dependencies not met"
- Check epic dependency chart
- Ensure upstream epics are merged to main
- Verify with: `git log main --oneline | grep "Epic"`

### "Tests failed - PR blocked"
- Review test report
- Fix failing tests
- Re-run test suite
- Only proceed when all tests pass

### "Merge conflict detected"
- git-workflow-specialist will report conflicts
- Coordinate with other epic owners
- Follow conflict resolution guidance
- Re-test after resolution

### "Ticket marked complete but not committed"
- Ensure git-workflow-specialist received request
- Check if all acceptance criteria actually met
- Verify no PHP errors in local testing
- Retry: `/complete-ticket TICKET-ID`

---

## Resources

- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [GitHub CLI Manual](https://cli.github.com/manual/)
- [Local by Flywheel Documentation](https://localwp.com/help-docs/)

---

**Version:** 1.0.0
**Last Updated:** 2025-10-16
**Maintained By:** GA Plugin Development Team
