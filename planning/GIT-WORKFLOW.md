# Git & GitHub Workflow Guide

## Overview

This document defines the complete Git and GitHub workflow for the GA Plugin multi-session development process. All phases, agents, and commands must follow these guidelines to prevent merge conflicts, protect work, and maintain a clean development history.

---

## Branch Strategy

### Branch Naming Conventions

All feature branches use the pattern: `phase-[number]-[name]`

| Phase | Branch Name | Description |
|-------|-------------|-------------|
| Phase 1 | `phase-1-foundation` | Foundation, CPT, autoloader |
| Phase 2 | `phase-2-admin` | Admin interface, meta boxes |
| Phase 2.5 | `phase-2.5-conflict-detection` | Conflict detector system |
| Phase 3 | `phase-3-frontend` | Frontend output handler |
| Phase 4 | `phase-4-testing` | Testing, security, deployment |

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

## Pre-Phase Workflow

### Before Starting Any Phase

1. **Ensure main is up to date:**
   ```bash
   git checkout main
   git fetch origin
   git pull origin main
   ```

2. **Create feature branch:**
   ```bash
   git checkout -b phase-[number]-[name]
   ```

3. **Push to remote and set upstream:**
   ```bash
   git push -u origin phase-[number]-[name]
   ```

4. **Verify branch:**
   ```bash
   git status
   # Should show: On branch phase-[number]-[name]
   # Your branch is up to date with 'origin/phase-[number]-[name]'
   ```

---

## During-Phase Workflow

### Commit Strategy

Commit after completing each logical unit:
- A complete class implementation
- A major feature implementation
- A significant refactor
- Before requesting review
- Before switching contexts
- At end of each work session

### Commit Message Format

```
[type]([scope]): [short description]

- [Detailed point 1]
- [Detailed point 2]
- [Detailed point 3]

Addresses: Phase [X] deliverable [Y]
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `refactor`: Code restructure without functionality change
- `docs`: Documentation only
- `test`: Test addition or modification
- `chore`: Maintenance tasks (build, dependencies)

**Scopes:**
- `cpt`: Custom Post Type
- `meta`: Meta boxes
- `conflict`: Conflict detector
- `frontend`: Frontend output
- `admin`: Admin UI
- `security`: Security features
- `setup`: Plugin setup/config

**Examples:**
```
feat(cpt): add tracking script custom post type

- Register gap_tracking post type
- Add custom admin columns
- Implement column rendering

Addresses: Phase 1 deliverable (CPT registration)
```

```
fix(meta): correct nonce verification logic

- Move nonce check before capability check
- Add DOING_AUTOSAVE check
- Improve error handling

Addresses: Phase 2 security requirements
```

### Pushing Changes

Push to remote after each commit or set of related commits:

```bash
# Stage specific files
git add includes/class-gap-post-type.php

# Commit with descriptive message
git commit -m "feat(cpt): add tracking script custom post type

- Register gap_tracking post type
- Add custom admin columns
- Implement column rendering

Addresses: Phase 1 deliverable (CPT registration)"

# Push to remote
git push
```

---

## Post-Phase Workflow

### Creating Pull Requests

When phase work is complete:

1. **Verify all completion criteria met** (check phase planning.md)

2. **Ensure all work is committed and pushed:**
   ```bash
   git status  # Should show: nothing to commit, working tree clean
   git push    # Ensure remote is up to date
   ```

3. **Create PR using gh CLI:**
   ```bash
   gh pr create \
     --title "Phase [X]: [Name]" \
     --body-file .github/pr-templates/phase-[X]-template.md
   ```

4. **If gh CLI not available, use GitHub web interface:**
   - Navigate to repository
   - Click "New pull request"
   - Select: base: `main` ← compare: `phase-[X]-[name]`
   - Use PR template from phase planning document

### PR Template Structure

```markdown
## Phase [X]: [Name]

### Overview
[Brief description of phase purpose]

### Deliverables
- [ ] [Deliverable 1]
- [ ] [Deliverable 2]
- [ ] [Deliverable 3]

### Files Changed
- `path/to/file1.php` - [Brief description]
- `path/to/file2.php` - [Brief description]

### Testing Completed
- [ ] [Test item 1]
- [ ] [Test item 2]

### Dependencies
**Requires:** [Phase numbers that must be merged first]
**Blocks:** [Phase numbers that depend on this]

### Review Focus
**Security Concerns:**
- [Specific security review areas]

**Code Quality Concerns:**
- [Specific code quality areas]

### Completion Checklist
- [ ] All phase completion criteria met
- [ ] Code follows WordPress standards
- [ ] Security best practices implemented
- [ ] No debug code (var_dump, print_r, die)
- [ ] All functions/classes use GAP_ prefix
- [ ] Text domain is 'ga-plugin'
```

---

## Merge Strategy

### Merge Order

Phases must merge in dependency order:

```
Phase 1 (foundation)
    ↓
    ├─→ Phase 2 (admin)
    └─→ Phase 2.5 (conflict detection)
            ↓
        Phase 3 (frontend) ← requires both 2 and 2.5
            ↓
        Phase 4 (testing)
```

**Rules:**
1. Phase 1 must merge before Phase 2 or 2.5 can start
2. Phase 2 and 2.5 can be developed in parallel (separate branches)
3. Both Phase 2 AND 2.5 must merge before Phase 3 starts
4. Phase 3 must merge before Phase 4 starts

### Review Requirements

Each PR requires review from:

| Phase | Required Reviewers | Focus |
|-------|-------------------|-------|
| Phase 1 | wp-code-reviewer | CPT registration, autoloader, standards |
| Phase 2 | wp-security-scanner, wp-code-reviewer | Nonces, sanitization, capabilities |
| Phase 2.5 | wp-code-reviewer | Regex patterns, performance |
| Phase 3 | wp-security-scanner, frontend-output-specialist | Output escaping, conflict prevention |
| Phase 4 | wp-security-scanner, wp-code-reviewer | Complete security audit |

### Merging PRs

**Via GitHub CLI:**
```bash
# After approval, merge PR
gh pr merge [PR-number] --squash --delete-branch

# Update local main
git checkout main
git pull origin main
```

**Via GitHub Web:**
1. Wait for all reviews to approve
2. Click "Squash and merge"
3. Confirm merge
4. Delete branch after merge
5. Update local main:
   ```bash
   git checkout main
   git pull origin main
   ```

---

## Parallel Development Guidelines

### Phase 2 and Phase 2.5

These phases can run in parallel safely if:

1. **Separate branches:** Each on own feature branch
2. **No file overlap:** Phase 2 creates admin files, 2.5 creates conflict detector
3. **No shared code:** Classes are independent
4. **Independent testing:** Can test separately

### File Ownership

| Phase | Files Created | Other Phases Cannot Touch |
|-------|---------------|---------------------------|
| Phase 1 | `ga-plugin.php`, `class-gap-activator.php`, `class-gap-post-type.php` | 2, 2.5, 3, 4 |
| Phase 2 | `admin/class-gap-meta-box.php`, admin assets | 1, 2.5, 3, 4 |
| Phase 2.5 | `class-gap-conflict-detector.php` | 1, 2, 3, 4 |
| Phase 3 | `frontend/class-gap-frontend-output.php` | 1, 2, 2.5, 4 |
| Phase 4 | Test files, documentation | No new class files |

### Conflict Prevention Rules

1. **Never modify files created by another phase** while both are in development
2. **If integration needed**, wait for dependency phase to merge first
3. **If urgent integration needed**, coordinate via GitHub issue/comment
4. **Always merge both parallel branches before starting dependent phase**

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
