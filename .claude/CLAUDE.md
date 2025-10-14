# GA Plugin - Development Orchestration

## Quick Links
- **Master Plan:** @TRACKING-SCRIPT-MANAGER-PLAN.md
- **Phase Plans:** @planning/
- **Agents:** @.claude/agents/
- **Commands:** @.claude/commands/

## Plugin Information
- **Name:** GA Plugin
- **Slug:** ga-plugin
- **Prefix:** GAP_ (classes), gap_ (functions)
- **Text Domain:** ga-plugin
- **Main File:** ga-plugin.php
- **Location:** ~/projects/gaplugin

## Workflow Strategies

### Primary Approach: Parallel Development
- **Phase 1 (Foundation):** Sequential - blocks everything else
  - Main plugin file, constants, autoloader, CPT registration
  - Must complete before any other phase begins

- **Phase 2 (Admin) + Phase 2.5 (Conflict Detection):** Parallel
  - These phases are independent and can be developed simultaneously
  - Phase 2: Meta boxes, admin interface, field handling
  - Phase 2.5: Conflict detector class, regex patterns, HTML scanning

- **Phase 3 (Frontend):** Sequential after 1, 2, and 2.5
  - Depends on foundation (Phase 1) and conflict detector (Phase 2.5)
  - Frontend output, caching, scope filtering, script injection

- **Phase 4 (Testing):** Sequential - final phase
  - Complete testing checklist
  - Security audit
  - Deployment preparation

### Fallback Approach: Sequential Development
If parallel development creates conflicts or coordination issues:
1. Phase 1 (Foundation)
2. Phase 2 (Admin)
3. Phase 2.5 (Conflict Detection)
4. Phase 3 (Frontend)
5. Phase 4 (Testing)

Use `/switch-to-sequential` command to activate fallback approach.

## Git & GitHub Workflow

### Overview

All development work uses Git feature branches with pull request workflow. This prevents merge conflicts, protects work in progress, and enables code review before integration.

**Core Principle:** Never work directly on `main` branch. Always use feature branches.

### Branch Strategy

Each phase has its own feature branch:

| Phase | Branch Name | Created From | Merges To | Dependencies |
|-------|-------------|--------------|-----------|--------------|
| 1 | `phase-1-foundation` | `main` | `main` | None |
| 2 | `phase-2-admin` | `main` | `main` | Phase 1 merged |
| 2.5 | `phase-2.5-conflict-detection` | `main` | `main` | Phase 1 merged |
| 3 | `phase-3-frontend` | `main` | `main` | Phase 1, 2, 2.5 merged |
| 4 | `phase-4-testing` | `main` | `main` | All previous merged |

**Parallel Development:** Phase 2 and 2.5 can be developed simultaneously on separate branches, both created from `main` after Phase 1 merges.

### Pre-Phase Workflow

Before starting any phase:

1. **Update main branch:**
   ```bash
   git checkout main
   git pull origin main
   ```

2. **Create feature branch:**
   ```bash
   git checkout -b phase-[number]-[name]
   ```

3. **Push and set upstream:**
   ```bash
   git push -u origin phase-[number]-[name]
   ```

**Automation:** Use `/start-phase [number]` command to automate this workflow.

### During-Phase Workflow

**Commit Strategy:** Commit after each logical unit of work:
- Completing a class file
- Implementing a major feature
- Completing a refactor
- Before switching contexts
- Before requesting review

**Commit Message Format:**
```
[type]([scope]): [short description]

- [Detail 1]
- [Detail 2]

Addresses: Phase [X] deliverable [Y]
```

**Types:** feat, fix, refactor, docs, test, chore
**Scopes:** cpt, meta, conflict, frontend, admin, security, setup

**Example:**
```bash
git add includes/class-gap-post-type.php
git commit -m "feat(cpt): add tracking script custom post type

- Register gap_tracking post type
- Add custom admin columns
- Implement column rendering

Addresses: Phase 1 deliverable (CPT registration)"
git push
```

### Post-Phase Workflow

When phase work is complete:

1. **Verify all completion criteria met** (check phase planning.md)

2. **Commit final changes:**
   ```bash
   git add .
   git commit -m "chore: final changes for Phase [X]"
   git push
   ```

3. **Create pull request:**
   ```bash
   gh pr create --title "Phase [X]: [Name]" --body "[description]"
   ```

**Automation:** Use `/finish-phase [number]` command to automate PR creation.

### Review & Merge Process

**Review Requirements:**

| Phase | Required Reviewers | Focus |
|-------|-------------------|-------|
| 1 | wp-code-reviewer | CPT, autoloader, standards |
| 2 | wp-security-scanner, wp-code-reviewer | Security, sanitization |
| 2.5 | wp-code-reviewer | Regex, performance |
| 3 | wp-security-scanner, frontend-output-specialist | Output security, conflicts |
| 4 | wp-security-scanner, wp-code-reviewer | Complete audit |

**Merge Order:**
```
Phase 1
  ↓
  ├─→ Phase 2
  └─→ Phase 2.5
      ↓
    Phase 3
      ↓
    Phase 4
```

**Merging:**
1. Wait for review approval
2. Use "Squash and merge"
3. Delete feature branch
4. Update local main: `git checkout main && git pull origin main`

### Parallel Phase Coordination

**Phase 2 and 2.5 File Ownership:**

| Phase | Owns These Files | Cannot Touch |
|-------|-----------------|--------------|
| Phase 2 | `includes/admin/class-gap-meta-box.php`, admin assets | Phase 2.5 files |
| Phase 2.5 | `includes/class-gap-conflict-detector.php` | Phase 2 files |

**Rules:**
- Each phase on separate branch
- No cross-phase file modifications
- Both merge independently to main
- Phase 3 waits for both merges

### Conflict Prevention

**DO:**
- ✅ Create feature branch for each phase
- ✅ Push to remote regularly
- ✅ Commit frequently
- ✅ Wait for dependency phases to merge
- ✅ Follow file ownership rules

**DON'T:**
- ❌ Work directly on main
- ❌ Modify files owned by other phases
- ❌ Force push to main
- ❌ Merge without reviews
- ❌ Skip PR workflow

### Quick Git Commands

**Start phase:**
```bash
/start-phase [number]
# Or manually:
git checkout main && git pull origin main
git checkout -b phase-[X]-[name]
git push -u origin phase-[X]-[name]
```

**During development:**
```bash
git add [files]
git commit -m "[type]([scope]): [description]"
git push
```

**Complete phase:**
```bash
/finish-phase [number]
# Or manually:
git push
gh pr create --title "Phase [X]: [Name]"
```

**After PR merged:**
```bash
git checkout main
git pull origin main
git branch -d phase-[X]-[name]
```

### Complete Workflow Reference

For complete Git/GitHub workflow documentation, see: `planning/GIT-WORKFLOW.md`

This includes:
- Detailed branching strategy
- Commit message conventions
- PR templates for each phase
- Conflict resolution procedures
- Emergency rollback procedures
- GitHub CLI setup instructions

## Session Management

### Parallel Session Strategy

**Session 1: Foundation Builder**
- Focus: Phase 1 only
- Agents: cpt-specialist
- Status: Blocks all other sessions
- Completion signal: CPT registered, autoloader working, plugin activates

**Session 2: Admin Builder** (starts after Phase 1)
- Focus: Phase 2
- Agents: meta-box-specialist, wp-code-reviewer
- Works independently of Session 3
- Completion signal: Meta boxes rendering, fields saving correctly

**Session 3: Conflict Detector Builder** (starts after Phase 1)
- Focus: Phase 2.5
- Agents: conflict-detector-specialist, wp-code-reviewer
- Works independently of Session 2
- Completion signal: Regex patterns detecting GA4/GTM, HTML scanner working

**Session 4: Frontend Builder** (starts after Phase 1, 2, and 2.5)
- Focus: Phase 3
- Agents: frontend-output-specialist, conflict-detector-specialist
- Depends on conflict detector from Session 3
- Completion signal: Scripts output to frontend with proper scope filtering

**Session 5: Testing & Security** (final phase)
- Focus: Phase 4
- Agents: wp-security-scanner, wp-code-reviewer
- Reviews all previous work
- Completion signal: All tests pass, security audit clean

### Sequential Session Strategy (Fallback)
Single session works through phases 1 → 2 → 2.5 → 3 → 4 in order, using appropriate agents for each phase.

## Agent Deployment

### When to Use Each Agent

**wp-security-scanner** (Read, Grep, Glob only)
- Use: After completing any phase for security review
- Focus: Nonces, capability checks, sanitization, escaping, CSRF protection
- Trigger: `/review-phase [phase-number]` command

**wp-code-reviewer** (Read, Grep, Glob only)
- Use: After completing any component for code quality review
- Focus: WordPress Coding Standards, DRY violations, KISS principle adherence
- Trigger: `/review-phase [phase-number]` command

**cpt-specialist** (Read, Write, Edit)
- Use: Phase 1 - Custom Post Type registration
- Focus: CPT registration, admin columns, labels, capabilities
- Triggers: `/build-phase 1` command

**conflict-detector-specialist** (Read, Write, Edit, Grep)
- Use: Phase 2.5 - Conflict detection system
- Focus: Regex pattern creation, HTML scanning, duplicate detection
- Triggers: `/build-phase 2.5` command

**meta-box-specialist** (Read, Write, Edit)
- Use: Phase 2 - Admin interface
- Focus: Meta box registration, field handling, sanitization, nonce verification
- Triggers: `/build-phase 2` command

**frontend-output-specialist** (Read, Write, Edit)
- Use: Phase 3 - Frontend output
- Focus: wp_head/wp_footer hooks, caching strategy, scope filtering, script output
- Triggers: `/build-phase 3` command

## Fallback Procedures

### When to Switch to Sequential
Switch to sequential development if you encounter:
1. Merge conflicts between parallel sessions
2. Coordination overhead exceeds development speed gains
3. Difficulty tracking which session is responsible for which code
4. Agent context confusion between parallel sessions

### How to Switch
1. Complete any in-progress work in all parallel sessions
2. Run `/switch-to-sequential` command
3. Consolidate to a single session
4. Continue from the next incomplete phase

### Recovery from Issues
- **Build failures:** Run `/test-component [component-name]` to isolate issue
- **Security concerns:** Run `/review-phase [phase-number]` for targeted audit
- **Code quality issues:** Invoke wp-code-reviewer agent manually
- **Conflicts detected:** Consolidate sessions, review recent changes, re-run conflict detector

## Development Principles

All agents and sessions must adhere to:
- **DRY (Don't Repeat Yourself):** No code duplication
- **KISS (Keep It Simple, Stupid):** Simplest solution that works
- **WordPress Coding Standards:** Yoda conditions, naming conventions, hook patterns
- **Security First:** Every input sanitized, every output escaped, all actions nonce-verified
- **Least Privilege:** Capability checks on all admin functionality
- **Single Responsibility:** Each class/function does one thing well

## Quick Commands Reference

### Git Workflow Commands
- `/start-phase [number]` - Prepare Git environment and create feature branch
- `/finish-phase [number]` - Complete phase and create pull request

### Development Commands
- `/build-phase [number]` - Build specific phase with appropriate agents
- `/review-phase [number]` - Security + code review for completed phase
- `/test-component [name]` - Test individual component

### Workflow Management
- `/switch-to-sequential` - Activate fallback sequential workflow

## Phase Completion Checklist

Each phase must meet these criteria before moving to the next:

**Phase 1:**
- [ ] Plugin activates without errors
- [ ] CPT registers correctly
- [ ] Autoloader works
- [ ] Constants defined

**Phase 2:**
- [ ] Meta boxes render in admin
- [ ] Fields save correctly
- [ ] Nonce verification working
- [ ] Data sanitized on save

**Phase 2.5:**
- [ ] Regex patterns detect GA4/GTM
- [ ] HTML scanner finds duplicates
- [ ] Conflict warnings display
- [ ] Detection is performant

**Phase 3:**
- [ ] Scripts output to frontend
- [ ] Scope filtering works
- [ ] Caching implemented
- [ ] No conflicts with existing scripts

**Phase 4:**
- [ ] All automated tests pass
- [ ] Security audit clean
- [ ] Performance acceptable
- [ ] Documentation complete
