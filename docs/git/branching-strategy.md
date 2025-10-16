# Git Branching Strategy

Reference for branch naming conventions, when to create branches, and branch lifecycle.

## Branch Structure

### Main Branch
- **Name**: `main`
- **Purpose**: Production-ready code, release branch
- **Protection**: Only merge via PR with approvals
- **Rule**: Never commit directly to main

### Epic Branches
- **Naming**: `epic-XX-name`
- **Purpose**: Contains all work for one epic
- **Lifetime**: Lives until epic is merged to main
- **Supports**: Multiple parallel development

---

## Epic Branch Naming Convention

### Format
```
epic-XX-name
```

Where:
- `epic` = literal prefix
- `XX` = two-digit epic number (00-05)
- `name` = full epic name in kebab-case

### Epic Mapping

| Epic | Branch Name | Description |
|------|-----------|-------------|
| EPIC-00 | `epic-00-project-setup` | Project infrastructure and setup |
| EPIC-01 | `epic-01-foundation` | Core plugin foundation and classes |
| EPIC-02 | `epic-02-admin-interface` | Admin UI and CPT management |
| EPIC-03 | `epic-03-conflict-detection` | Duplicate script detection system |
| EPIC-04 | `epic-04-frontend-output` | Frontend script output injection |
| EPIC-05 | `epic-05-testing-launch` | Testing, documentation, launch |

### Examples
✅ `epic-02-admin-interface`
✅ `epic-03-conflict-detection`
✅ `epic-04-frontend-output`

❌ `epic-2-admin-interface` (should be two digits)
❌ `epic-02-admin_interface` (should use hyphens)
❌ `epic-02-Admin Interface` (should be kebab-case)

---

## Branch Lifecycle

### 1. Creating Epic Branch

**When**: Starting work on a new epic
**From**: Latest main branch

```bash
git checkout main
git fetch origin
git pull origin main
git checkout -b epic-XX-name
git push -u origin epic-XX-name
```

**Verify:**
- [ ] Branch created from latest main
- [ ] Branch pushed to remote with upstream tracking
- [ ] No uncommitted changes before creating

### 2. Working on Epic Branch

**Multiple agents** can work on same branch:
- Each ticket = one commit
- Agents commit independently
- Frequent pulls from remote to stay in sync

```bash
# Before starting new ticket
git pull origin epic-XX-name

# After completing ticket
git add .
git commit -m "[type](epic-XX): [US-XX.X] - [description]"
git push
```

**Key Points:**
- Always pull before starting new work
- One commit per completed ticket
- Push regularly (not just at end)
- Pull often to avoid large conflicts

### 3. Keeping Branch Updated

**When**: Main receives merged PRs from other epics
**How**: Periodically merge main into epic branch

```bash
git fetch origin
git merge origin/main
# Resolve any conflicts
git push
```

**When to sync:**
- Before creating PR (always)
- Weekly during long-running development
- After other epic merges to main

### 4. Creating Pull Request

**When**: All epic work is complete
**Process**: Automated via git-workflow-specialist agent

PR created from `epic-XX-name` → `main`

### 5. Merging Pull Request

**When**: PR approved and all tests pass
**How**: Squash merge to main

```bash
gh pr merge [PR-number] --squash --delete-branch
git checkout main
git pull origin main
```

**After merge:**
- [ ] Epic branch deleted
- [ ] Main updated locally
- [ ] Ready to start next epic

---

## Special Branches

### Revert Branches
- **Name**: `revert-epic-XX`
- **Use**: If epic needs rollback after merge
- **Created from**: main
- **Merged to**: main as new PR

---

## File Ownership by Epic

Important: **Don't modify files owned by other epics while in parallel development**

| Epic | Creates | Modifies | Protected From |
|------|---------|----------|---|
| EPIC-00 | Project structure, .gitignore, README | All setup files | Other epics |
| EPIC-01 | Main plugin file, autoloader, base classes | Core framework | EPIC-02,03,04,05 |
| EPIC-02 | CPT class, meta boxes, admin CSS/JS | Admin files | EPIC-00,01,03,04,05 |
| EPIC-03 | Conflict detection class | Core detection logic | EPIC-00,01,02,04,05 |
| EPIC-04 | Frontend class | Frontend output logic | EPIC-00,01,02,03,05 |
| EPIC-05 | Tests, docs | No new classes | EPIC-00,01,02,03,04 |

### Conflict Prevention Rules

**Safe to develop in parallel:**
- EPIC-02 and EPIC-03 (different files)
- EPIC-02 and EPIC-04 (after merge, coordinate frontend changes)

**Requires coordination:**
- If EPIC-04 needs changes to EPIC-02 files
- Communicate and coordinate merge order

**Never allowed:**
- Both modify same file while in development
- EPIC-04 modifying EPIC-02 files before merge

---

## Parallel Development Example

### Scenario: Two epics in progress

```
main
  ↓
  ├─→ epic-02-admin-interface (5 commits)
  │
  └─→ epic-03-conflict-detection (3 commits)
```

Both can develop safely because:
- EPIC-02 creates: class-gap-cpt.php, class-gap-meta-boxes.php, admin CSS/JS
- EPIC-03 creates: class-gap-conflict-detector.php
- No file overlap

When either is ready:
1. Sync with main (may be behind if other epic merged)
2. Create PR
3. After approval, squash merge to main
4. Other epic pulls latest main if needed

---

## Branch Status Commands

```bash
# See all epic branches
git fetch --all
git branch -r --list "origin/epic-*"

# See commits on epic branch ahead of main
git log --oneline epic-XX-name ^main

# See if branch is stale
git log -1 --pretty=format:"%ai" origin/epic-XX-name

# Check for uncommitted changes
git status

# See branches not yet merged
git branch -r --no-merged origin/main
```

---

## Quick Reference

| Task | Command |
|------|---------|
| Create epic branch | `git checkout -b epic-XX-name && git push -u origin epic-XX-name` |
| Switch to epic branch | `git checkout epic-XX-name` |
| Pull latest epic work | `git pull origin epic-XX-name` |
| Sync epic with main | `git merge origin/main && git push` |
| See commits ahead of main | `git log --oneline main..HEAD` |
| See all branches | `git branch -r` |
| Delete local branch | `git branch -d epic-XX-name` |

---

## Best Practices

### DO ✅
- ✅ Create branches for each epic
- ✅ Keep epic branch synced with main
- ✅ Use correct naming convention
- ✅ Push regularly during development
- ✅ Pull before starting new work
- ✅ Communicate file ownership
- ✅ Review merge order before merging

### DON'T ❌
- ❌ Work directly on main
- ❌ Use non-standard branch names
- ❌ Leave branches unmerged for > 2 weeks (without PR)
- ❌ Modify other epic's files while in development
- ❌ Force push to main (ever)
- ❌ Merge without approval
- ❌ Merge without testing

---

## Troubleshooting

### Branch out of sync with main
```bash
git fetch origin
git merge origin/main
# Fix conflicts
git push
```

### Accidentally committed to main
```bash
git log main --oneline  # Find commit hash
git revert [commit-hash]  # Create revert commit
git push
```

### Need to recover deleted branch
```bash
git reflog | grep epic-XX-name  # Find commit
git checkout -b epic-XX-name-recovered [commit-hash]
git push -u origin epic-XX-name-recovered
```

### Branch has too many conflicts to merge
- Talk to other epic owner
- Coordinate manual conflict resolution
- Update documentation about file ownership
- Consider merge order changes
