# Git & GitHub Workflow Guide

## Overview

This document defines the complete Git and GitHub workflow for the GA Plugin v2 epic-based development process. All epics, agents, and commands must follow these guidelines to prevent merge conflicts, protect work, and maintain a clean development history.

**Repository:** git@github.com:OrasesWPDev/gaplugin-v2.git

---

## Branch Strategy

### Branch Naming Conventions

All feature branches use the pattern: `epic-[number]-[name]`

| Epic | Branch Name | Description |
|------|-------------|-------------|
| EPIC-00 | `epic-00-project-setup` | Project infrastructure setup |
| EPIC-01 | `epic-01-foundation` | Core plugin foundation |
| EPIC-02 | `epic-02-admin-interface` | CPT and admin UI |
| EPIC-03 | `epic-03-conflict-detection` | Duplicate detection system |
| EPIC-04 | `epic-04-frontend-output` | Frontend script output |
| EPIC-05 | `epic-05-testing-launch` | Testing, security, launch prep |

### Branch Rules

**NEVER:**
- Work directly on `main` branch
- Force push to main
- Merge without PR review
- Rebase branches after pushing to remote
- Delete branches before PR is merged

**ALWAYS:**
- Create feature branches from up-to-date `main`
- Push feature branches to remote immediately
- Keep branches focused on single phase
- Use descriptive commit messages
- Request review before merging

---

## Pre-Epic Workflow

### Before Starting Any Epic

⚠️ **IMPORTANT:** Only the **git-workflow-specialist** agent may execute these commands.

1. **Ensure main is up to date:**
   ```bash
   git checkout main
   git fetch origin
   git pull origin main
   ```

2. **Create epic branch:**
   ```bash
   git checkout -b epic-[number]-[name]
   ```

3. **Push to remote and set upstream:**
   ```bash
   git push -u origin epic-[number]-[name]
   ```

4. **Verify branch:**
   ```bash
   git status
   # Should show: On branch epic-[number]-[name]
   # Your branch is up to date with 'origin/epic-[number]-[name]'
   ```

---

## During-Epic Workflow

### Commit Strategy

**One commit per completed ticket** (User Story or Technical Task)

Commit when:
- A ticket is marked complete
- All acceptance criteria for ticket met
- Ticket has been tested locally
- Before requesting epic PR

**Managed by:** git-workflow-specialist agent (automatically triggered on ticket completion)

### Commit Message Format

```
[type]([epic-scope]): [ticket-id] - [short description]

- [Implementation detail 1]
- [Implementation detail 2]
- [Implementation detail 3]

Ticket: [TICKET-ID]
Epic: [EPIC-ID]
Status: Completed
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `refactor`: Code restructure without functionality change
- `docs`: Documentation only
- `test`: Test addition or modification
- `chore`: Maintenance tasks (build, dependencies)

**Epic Scopes:**
- `epic-00`: Project setup
- `epic-01`: Foundation
- `epic-02`: Admin interface
- `epic-03`: Conflict detection
- `epic-04`: Frontend output
- `epic-05`: Testing and launch

**Examples:**
```
feat(epic-02): US-02.1 - manage tracking scripts via CPT

- Implemented GAP_CPT class with singleton pattern
- Registered tracking_script custom post type
- Added custom admin columns for tracking details
- Set capabilities to manage_options only

Ticket: US-02.1
Epic: EPIC-02
Status: Completed
```

```
feat(epic-02): TT-02.1 - implement GAP_CPT class

- Created class with singleton pattern
- Implemented register_post_type() method
- Added custom column rendering
- Hooked to WordPress init action

Ticket: TT-02.1
Epic: EPIC-02
Status: Completed
```

### Pushing Changes

⚠️ **Managed by git-workflow-specialist agent only**

Push to remote after each ticket commit:

```bash
# Automatically executed by git-workflow-specialist
git add [ticket-modified-files]
git commit -m "[generated-commit-message]"
git push
```

**Developers:** Use `/complete-ticket` command which triggers automatic commit and push.

---

## Post-Epic Workflow

### Creating Pull Requests

When epic work is complete (all tickets done):

1. **Verify all completion criteria met** (check epic EPIC.md)

2. **Request local testing approval:**
   - local-testing-specialist must approve
   - All tests must pass
   - No PHP errors or warnings

3. **git-workflow-specialist creates PR:**
   ```bash
   # Automatically executed by git-workflow-specialist
   git status  # Verify clean
   gh pr create --title "Epic [X]: [Name]" --body "[generated-body]"
   ```

4. **PR includes:**
   - List of all completed tickets
   - Test results from local-testing-specialist
   - Files changed summary
   - Dependencies and blockers
   - Security review checklist

### PR Template Structure

```markdown
## Epic [X]: [Name]

### Overview
[Epic description from EPIC.md]

### Completed Tickets
**User Stories:**
- [x] US-XX.1: [Title] (Commit: abc1234)
- [x] US-XX.2: [Title] (Commit: def5678)

**Technical Tasks:**
- [x] TT-XX.1: [Title] (Commit: ghi9012)
- [x] TT-XX.2: [Title] (Commit: jkl3456)

### Files Changed
- `includes/class-gap-*.php` - [Description]
- `assets/css/admin.css` - [Description]

### Testing Results
✅ All tests passed (see local-testing-specialist report)

**Test Summary:**
- Plugin Activation: ✅ PASSED
- Meta Fields: ✅ PASSED
- Frontend Output: ✅ PASSED
- Security Audit: ✅ PASSED

### Dependencies
**Requires:** [Epic IDs that must be merged first]
**Blocks:** [Epic IDs that depend on this]

### Review Focus
**Security:**
- Nonce verification on all forms
- Capability checks (manage_options)
- Input sanitization
- Output escaping

**Code Quality:**
- WordPress coding standards
- GAP_ namespace prefix
- Inline documentation

---
🤖 Generated with Claude Code
Epic: EPIC-XX
```

---

## Merge Strategy

### Merge Order

Epics must merge in dependency order:

```
EPIC-00 (project-setup)
    ↓
EPIC-01 (foundation)
    ↓
    ├─→ EPIC-02 (admin-interface)
    └─→ EPIC-03 (conflict-detection)
            ↓
        EPIC-04 (frontend-output) ← requires both 02 and 03
            ↓
        EPIC-05 (testing-launch)
```

**Rules:**
1. EPIC-00 must merge first (sets up repository structure)
2. EPIC-01 must merge before EPIC-02 or EPIC-03 can start
3. EPIC-02 and EPIC-03 can develop in parallel (different files)
4. Both EPIC-02 AND EPIC-03 must merge before EPIC-04 starts
5. EPIC-04 must merge before EPIC-05 starts

### Review Requirements

Each PR requires approval from:

| Epic | Required Approvals | Focus |
|------|-------------------|-------|
| EPIC-00 | Code review | Directory structure, .gitignore, README |
| EPIC-01 | Code review | CPT registration, autoloader, standards |
| EPIC-02 | Security + Code review | Nonces, sanitization, capabilities |
| EPIC-03 | Code review | Regex patterns, performance |
| EPIC-04 | Security + Code review | Output escaping, conflict prevention |
| EPIC-05 | Security + Code review | Complete security audit |

**Plus:** local-testing-specialist approval (all tests passed) for ALL epics

### Merging PRs

⚠️ **Managed by git-workflow-specialist agent only**

**Via GitHub CLI:**
```bash
# Automatically executed by git-workflow-specialist
gh pr merge [PR-number] --squash --delete-branch
git checkout main
git pull origin main
```

**Manual Steps (if needed):**
1. Wait for all reviews to approve
2. Wait for test results approval
3. git-workflow-specialist merges via gh CLI
4. Branch automatically deleted
5. Main branch updated locally

---

## Parallel Development Guidelines

### EPIC-02 and EPIC-03

These epics can run in parallel safely if:

1. **Separate branches:** Each on own epic branch
2. **No file overlap:** EPIC-02 creates admin files, EPIC-03 creates conflict detector
3. **No shared code:** Classes are independent
4. **Independent testing:** Can test separately

### File Ownership

| Epic | Files Created | Other Epics Cannot Touch |
|------|---------------|--------------------------|
| EPIC-00 | Project structure, .gitignore, README | All |
| EPIC-01 | `ga-plugin.php`, `class-gap-activator.php`, autoloader | 02-05 |
| EPIC-02 | `class-gap-cpt.php`, `class-gap-meta-boxes.php`, admin assets | 00,01,03-05 |
| EPIC-03 | `class-gap-conflict-detector.php` | 00-02,04-05 |
| EPIC-04 | `class-gap-frontend.php` | 00-03,05 |
| EPIC-05 | Test files, documentation, no new classes | 00-04 |

### Conflict Prevention Rules

1. **Never modify files created by another epic** while both are in development
2. **If integration needed**, wait for dependency epic to merge first
3. **If urgent integration needed**, coordinate via GitHub issue/comment
4. **Always merge both parallel epics before starting dependent epic**
5. **git-workflow-specialist** enforces file ownership rules

---

## Conflict Resolution

### If Merge Conflict Occurs

1. **DO NOT force merge or force push**

2. **Understand the conflict:**
   ```bash
   git status  # See conflicting files
   ```

3. **For simple conflicts:**
   ```bash
   # Update your branch with latest main
   git checkout phase-[X]-[name]
   git fetch origin
   git merge origin/main

   # Resolve conflicts in editor
   # Look for <<<<<<, ======, >>>>>>

   # Stage resolved files
   git add [resolved-files]

   # Complete merge
   git commit -m "merge: resolve conflicts with main"

   # Push
   git push
   ```

4. **For complex conflicts:**
   - Comment on PR with conflict details
   - Tag relevant phase specialist
   - Coordinate resolution
   - Consider reordering merges

### Prevention

- Merge dependent phases promptly
- Don't let feature branches get stale
- Update feature branches regularly if main advances
- Follow file ownership rules

---

## Emergency Procedures

### Rollback a Merged Phase

If critical issue found after merge:

1. **Create revert branch:**
   ```bash
   git checkout main
   git pull origin main
   git checkout -b revert-phase-[X]
   ```

2. **Revert the merge commit:**
   ```bash
   git revert -m 1 [merge-commit-hash]
   ```

3. **Push and create PR:**
   ```bash
   git push -u origin revert-phase-[X]
   gh pr create --title "Revert Phase [X]: [Reason]" --body "[Explanation]"
   ```

4. **Never rewrite history on main**

### Recover Lost Work

If work not committed:
- Check `git reflog` for recent states
- Check `.git/ORIG_HEAD` for previous position
- Look in editor backup files

If work committed but branch deleted:
```bash
# Find commit hash
git reflog

# Create new branch from that commit
git checkout -b recovered-phase-[X] [commit-hash]
```

---

## Quick Reference Commands

### Starting a Phase
```bash
git checkout main
git pull origin main
git checkout -b phase-[X]-[name]
git push -u origin phase-[X]-[name]
```

### During Development
```bash
git add [files]
git commit -m "[type]([scope]): [description]"
git push
```

### Completing a Phase
```bash
git status  # Verify all committed
gh pr create --title "Phase [X]: [Name]"
```

### After PR Merged
```bash
git checkout main
git pull origin main
git branch -d phase-[X]-[name]  # Delete local branch
```

### Check Status
```bash
git status           # Current branch, uncommitted changes
git log --oneline -5 # Recent commits
git branch -vv       # All branches with tracking info
```

---

## GitHub CLI (gh) Setup

### Installation

**macOS:**
```bash
brew install gh
```

**Linux:**
```bash
# Debian/Ubuntu
sudo apt install gh

# Fedora
sudo dnf install gh
```

### Authentication
```bash
gh auth login
# Follow prompts to authenticate
```

### Useful Commands
```bash
# Create PR
gh pr create --title "Title" --body "Description"

# View PRs
gh pr list

# Check PR status
gh pr view [PR-number]

# Merge PR
gh pr merge [PR-number] --squash --delete-branch

# View PR diff
gh pr diff [PR-number]
```

---

## Best Practices Summary

**DO:**
- ✅ Always work on feature branches
- ✅ Commit frequently with descriptive messages
- ✅ Push to remote regularly
- ✅ Create PRs when phase complete
- ✅ Wait for reviews before merging
- ✅ Follow merge order (dependencies)
- ✅ Keep branches focused on single phase
- ✅ Update main after merges

**DON'T:**
- ❌ Work directly on main
- ❌ Force push to main
- ❌ Merge without reviews
- ❌ Modify files owned by other phases
- ❌ Let branches get stale
- ❌ Skip commit messages
- ❌ Merge parallel branches out of order
- ❌ Rewrite history after pushing

---

## Integration with Development Workflow

### Command Integration

- **`/start-phase [X]`** - Automates pre-phase workflow
- **`/build-phase [X]`** - Includes commit checkpoints
- **`/finish-phase [X]`** - Automates PR creation
- **`/review-phase [X]`** - Prepares for PR review

### Agent Integration

Each specialized agent knows:
- Which files they can modify
- When to suggest commits
- Commit message format for their scope
- How to avoid conflicts with other agents

### Phase Planning Integration

Each phase planning document includes:
- Branch name to use
- Commit checkpoints
- PR template
- Merge dependencies

---

## Troubleshooting

### "Your branch is behind 'origin/main'"
```bash
git checkout main
git pull origin main
git checkout phase-[X]-[name]
git merge main
```

### "Your branch has diverged from 'origin/phase-[X]-[name]'"
```bash
# If you haven't pushed work to remote yet
git fetch origin
git reset --hard origin/phase-[X]-[name]

# If you have work not on remote
git fetch origin
git rebase origin/phase-[X]-[name]
```

### "fatal: refusing to merge unrelated histories"
```bash
# This shouldn't happen if following workflow correctly
# If it does, check you created branch from correct base
git fetch origin
git log --oneline --graph --all  # Visualize branch structure
```

### Can't find gh command
```bash
# Install gh CLI (see installation section above)
# Or use GitHub web interface for PR creation
```

---

## Additional Resources

- [GitHub Flow Documentation](https://docs.github.com/en/get-started/quickstart/github-flow)
- [Conventional Commits](https://www.conventionalcommits.org/)
- [GitHub CLI Manual](https://cli.github.com/manual/)
- [Git Branching Strategies](https://git-scm.com/book/en/v2/Git-Branching-Branching-Workflows)
- [Resolving Merge Conflicts](https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/addressing-merge-conflicts)

---

**Version:** 1.0
**Last Updated:** 2025-10-14
**Maintained By:** GA Plugin Development Team
