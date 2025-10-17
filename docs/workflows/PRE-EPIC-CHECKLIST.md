# Pre-Epic Development Checklist

**Purpose:** Ensure workflow compliance before starting epic development

**When to Use:** Before invoking any `/work-epic-XX` command

---

## Critical Requirements

All items must be checked before epic development:

### 1. Branch Setup ✅

- [ ] **Epic branch created** (format: `epic-XX-name`)
- [ ] **Currently on epic branch** (NOT on main)
- [ ] **Remote tracking configured** (`git push -u origin epic-XX-name`)
- [ ] **Main branch synced** (latest from origin)

**How to Check:**
```bash
git branch --show-current  # Should show epic-XX-name
git branch -vv            # Should show tracking info
```

**How to Fix:**
```bash
# Use the /start-epic command:
/start-epic XX

# Or manually:
git checkout main
git pull origin main
git checkout -b epic-XX-name
git push -u origin epic-XX-name
```

---

### 2. Git Hooks Installed ✅

- [ ] **pre-commit hook active** (blocks commits on main)
- [ ] **pre-push hook active** (blocks pushes to main)
- [ ] **Hooks are executable** (chmod +x)

**How to Check:**
```bash
ls -la .git/hooks/pre-commit
ls -la .git/hooks/pre-push
# Both should show -rwxr-xr-x (executable)
```

**How to Fix:**
```bash
chmod +x .git/hooks/pre-commit
chmod +x .git/hooks/pre-push
```

---

### 3. Workflow Validation ✅

- [ ] **Validation script passes** (all checks green)
- [ ] **No uncommitted changes on main**
- [ ] **Epic tickets exist** in docs/tickets/

**How to Check:**
```bash
./tools/validate-workflow.sh
# Should show: ✅ All critical validations passed
```

**How to Fix:**
- Follow validation script output
- Fix any red ❌ items before proceeding

---

### 4. Epic Prerequisites ✅

- [ ] **Dependencies met** (previous epics merged)
- [ ] **Epic documented** (EPIC.md exists)
- [ ] **Tickets generated** (user stories + technical tasks)

**Dependencies:**
- EPIC-00: None (can start immediately)
- EPIC-01: Requires EPIC-00 merged
- EPIC-02: Requires EPIC-01 merged
- EPIC-03: Requires EPIC-01 merged
- EPIC-04: Requires EPIC-02 AND EPIC-03 merged
- EPIC-05: Requires EPIC-04 merged

**How to Check:**
```bash
# Check epic exists
ls docs/tickets/EPIC-XX-*/

# Check dependencies merged
git log main --oneline | grep "Epic"
```

---

### 5. Documentation Access ✅

- [ ] **Workflow docs available**
  - docs/GIT-WORKFLOW.md
  - docs/DEVELOPMENT-WORKFLOW.md
- [ ] **Epic specs available**
  - docs/tickets/EPIC-XX-*/

**How to Check:**
```bash
ls docs/*.md
ls docs/tickets/EPIC-XX-*/
```

---

## Quick Validation Command

Run this single command to check everything:

```bash
./tools/validate-workflow.sh
```

If all checks pass, you're ready to proceed!

---

## What Happens If You Skip This?

**If you try to work on main branch:**
- ❌ Git pre-commit hook will block commits
- ❌ Git pre-push hook will block pushes
- ❌ /work-epic commands will refuse to run

**If epic branch doesn't exist:**
- ❌ /work-epic commands will error
- ❌ You'll be directed to use /start-epic first

**If git hooks not installed:**
- ⚠️ You could accidentally commit to main
- ⚠️ Workflow validation will warn you

---

## Checklist Summary

Before running `/work-epic-XX`:

```
✅ Epic branch created and checked out
✅ Git hooks installed and executable
✅ Workflow validation passes
✅ Epic prerequisites met
✅ Documentation accessible
```

**If all ✅ → Proceed with `/work-epic-XX`**

**If any ❌ → Fix issues first, then proceed**

---

## Related Commands

- `/start-epic XX` - Create epic branch with validation
- `./tools/validate-workflow.sh` - Run validation checks
- `git branch --show-current` - Check current branch
- `git status` - Check working directory state

---

**Version:** 1.0
**Last Updated:** 2025-10-16
**Maintained By:** GA Plugin Development Team
