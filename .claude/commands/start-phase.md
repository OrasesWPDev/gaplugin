# Start Phase Command

Prepare the development environment and Git workflow before starting a new phase.

## Usage
```
/start-phase [phase-number]
```

## What This Does

This command automates the pre-phase setup process by:

1. **Validating phase number** - Ensures valid phase (1, 2, 2.5, 3, or 4)
2. **Checking dependencies** - Verifies prerequisite phases are complete
3. **Updating main branch** - Ensures local main is synchronized with remote
4. **Creating feature branch** - Creates properly named branch for the phase
5. **Pushing to remote** - Sets up remote tracking
6. **Loading phase plan** - Displays phase planning document
7. **Showing workflow reminders** - Displays commit strategy and Git guidelines

## Phase Information

### Phase 1 - Foundation
```bash
/start-phase 1
```

**Branch:** `phase-1-foundation`
**Dependencies:** None (foundation phase)
**Files:** `ga-plugin.php`, `includes/class-gap-activator.php`, `includes/class-gap-post-type.php`

### Phase 2 - Admin Interface
```bash
/start-phase 2
```

**Branch:** `phase-2-admin`
**Dependencies:** Phase 1 must be merged to main
**Files:** `includes/admin/class-gap-meta-box.php`, admin assets

### Phase 2.5 - Conflict Detection
```bash
/start-phase 2.5
```

**Branch:** `phase-2.5-conflict-detection`
**Dependencies:** Phase 1 must be merged to main
**Files:** `includes/class-gap-conflict-detector.php`
**Note:** Can run parallel to Phase 2

### Phase 3 - Frontend Output
```bash
/start-phase 3
```

**Branch:** `phase-3-frontend`
**Dependencies:** Phase 1, 2, and 2.5 must be merged to main
**Files:** `includes/frontend/class-gap-frontend-output.php`

### Phase 4 - Testing & Security
```bash
/start-phase 4
```

**Branch:** `phase-4-testing`
**Dependencies:** Phase 1, 2, 2.5, and 3 must be merged to main
**Files:** Test files, documentation updates

---

## Implementation

When this command is executed, perform the following steps in order:

### 1. Validate Phase Number
```bash
# Valid phases: 1, 2, 2.5, 3, 4
if [[ ! "$PHASE" =~ ^(1|2|2\.5|3|4)$ ]]; then
    echo "ERROR: Invalid phase number"
    echo "Valid phases: 1, 2, 2.5, 3, 4"
    exit 1
fi
```

### 2. Check Phase Dependencies

**Phase 1:**
- No dependencies (foundation)

**Phase 2:**
- Check if Phase 1 merged to main:
  ```bash
  if ! git log main --oneline | grep -q "Phase 1"; then
      echo "ERROR: Phase 1 not complete"
      echo "Phase 1 must be merged to main before starting Phase 2"
      exit 1
  fi
  ```

**Phase 2.5:**
- Check if Phase 1 merged to main (same check as Phase 2)

**Phase 3:**
- Check if Phase 1, 2, and 2.5 all merged to main:
  ```bash
  for phase in "Phase 1" "Phase 2" "Phase 2.5"; do
      if ! git log main --oneline | grep -q "$phase"; then
          echo "ERROR: $phase not complete"
          echo "Phase 3 requires Phase 1, 2, and 2.5 to be merged"
          exit 1
      fi
  done
  ```

**Phase 4:**
- Check if all previous phases merged to main

### 3. Update Main Branch

```bash
echo "📥 Updating local main branch..."

# Save current branch
CURRENT_BRANCH=$(git branch --show-current)

# Switch to main
git checkout main

# Fetch from remote
git fetch origin

# Check if behind
BEHIND=$(git rev-list --count main..origin/main)
if [ "$BEHIND" -gt 0 ]; then
    echo "⚠️  Main branch is $BEHIND commits behind origin/main"
    echo "📥 Pulling changes..."
    git pull origin main
    echo "✅ Main branch updated"
else
    echo "✅ Main branch already up to date"
fi
```

### 4. Create Feature Branch

```bash
# Determine branch name based on phase
case "$PHASE" in
    1)
        BRANCH_NAME="phase-1-foundation"
        ;;
    2)
        BRANCH_NAME="phase-2-admin"
        ;;
    2.5)
        BRANCH_NAME="phase-2.5-conflict-detection"
        ;;
    3)
        BRANCH_NAME="phase-3-frontend"
        ;;
    4)
        BRANCH_NAME="phase-4-testing"
        ;;
esac

echo "🌿 Creating feature branch: $BRANCH_NAME"

# Check if branch already exists
if git show-ref --verify --quiet refs/heads/$BRANCH_NAME; then
    echo "⚠️  Branch $BRANCH_NAME already exists locally"
    read -p "Switch to existing branch? (y/n) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        git checkout $BRANCH_NAME
    else
        echo "Aborted"
        exit 1
    fi
else
    # Create new branch from main
    git checkout -b $BRANCH_NAME
    echo "✅ Created branch: $BRANCH_NAME"
fi
```

### 5. Push to Remote

```bash
echo "📤 Pushing branch to remote..."

# Push and set upstream tracking
if git push -u origin $BRANCH_NAME; then
    echo "✅ Branch pushed to origin"
    echo "✅ Remote tracking configured"
else
    echo "⚠️  Failed to push to remote"
    echo "    You may need to push manually: git push -u origin $BRANCH_NAME"
fi
```

### 6. Display Phase Planning Document

```bash
echo ""
echo "📋 Loading Phase $PHASE planning document..."
echo "=============================================="
echo ""

case "$PHASE" in
    1)
        cat planning/phase-1-foundation/planning.md
        ;;
    2)
        cat planning/phase-2-admin/planning.md
        ;;
    2.5)
        cat planning/phase-2.5-conflict-detection/planning.md
        ;;
    3)
        cat planning/phase-3-frontend/planning.md
        ;;
    4)
        cat planning/phase-4-testing/planning.md
        ;;
esac
```

### 7. Show Workflow Reminders

```bash
echo ""
echo "🎯 Phase $PHASE Environment Ready!"
echo "====================================="
echo ""
echo "✅ Main branch updated"
echo "✅ Feature branch created: $BRANCH_NAME"
echo "✅ Remote tracking configured"
echo ""
echo "📝 Commit Strategy:"
echo "   - Commit after each logical unit (class, feature, fix)"
echo "   - Use format: [type]([scope]): [description]"
echo "   - Push regularly: git push"
echo ""
echo "📖 Reference:"
echo "   - Phase Plan: planning/phase-$PHASE-*/planning.md"
echo "   - Git Workflow: planning/GIT-WORKFLOW.md"
echo ""
echo "🚀 Next Steps:"
echo "   1. Review phase planning document above"
echo "   2. Start implementing deliverables"
echo "   3. Commit after each completed component"
echo "   4. Run '/build-phase $PHASE' for guided implementation"
echo "   5. Run '/finish-phase $PHASE' when phase complete"
echo ""
```

---

## Success Criteria

Phase start is successful when:
- [ ] Valid phase number provided
- [ ] All dependencies met (prerequisite phases merged)
- [ ] Main branch updated to latest
- [ ] Feature branch created with correct name
- [ ] Feature branch pushed to remote with tracking
- [ ] Phase planning document displayed
- [ ] Workflow reminders shown

---

## Error Handling

### Invalid Phase Number
```
ERROR: Invalid phase number
Valid phases: 1, 2, 2.5, 3, 4

USAGE: /start-phase [phase-number]
```

### Missing Dependencies
```
ERROR: Phase 1 not complete
Phase 2 requires Phase 1 to be merged to main before starting

SOLUTION:
1. Complete Phase 1 implementation
2. Run /finish-phase 1 to create PR
3. Merge Phase 1 PR to main
4. Retry /start-phase 2
```

### Main Branch Behind
```
⚠️  Main branch is 3 commits behind origin/main
📥 Pulling changes...
✅ Main branch updated

Continuing with phase start...
```

### Branch Already Exists
```
⚠️  Branch phase-2-admin already exists locally
Switch to existing branch? (y/n)

If 'y': Switches to existing branch
If 'n': Aborts command
```

### Failed to Push
```
⚠️  Failed to push to remote
You may need to push manually: git push -u origin phase-2-admin

POSSIBLE CAUSES:
- Network connection issue
- GitHub authentication needed
- Branch already exists on remote with different history
```

---

## Related Commands

- **`/build-phase [X]`** - Guided implementation with agent orchestration
- **`/finish-phase [X]`** - Complete phase and create PR
- **`/review-phase [X]`** - Security and code quality review
- **`/test-component [name]`** - Test individual component

---

## Notes

- This command must be run BEFORE starting any phase development
- Always start phases from an up-to-date main branch
- Phase 2 and 2.5 can be started in parallel (both depend only on Phase 1)
- Phase 3 cannot start until both Phase 2 AND 2.5 are complete
- If switching between parallel phases, use `git checkout [branch-name]`
- Always check `git status` to verify you're on correct branch before working

---

## Example Workflow

### Starting Phase 1 (Foundation)
```bash
# Start phase 1
/start-phase 1

# Output shows:
# - Main branch updated
# - Branch phase-1-foundation created and pushed
# - Phase 1 planning document displayed
# - Workflow reminders shown

# Begin development
# ... implement CPT, autoloader, etc ...

# Complete phase
/finish-phase 1
```

### Starting Phase 2 (Admin) After Phase 1 Merged
```bash
# Start phase 2
/start-phase 2

# If Phase 1 not merged:
# ERROR: Phase 1 not complete
# Phase 1 must be merged to main before starting Phase 2

# If Phase 1 merged:
# - Main branch updated (includes Phase 1 changes)
# - Branch phase-2-admin created
# - Ready to develop admin interface
```

### Starting Parallel Phases
```bash
# In Session A: Start Phase 2
/start-phase 2
# Work on admin interface in phase-2-admin branch

# In Session B: Start Phase 2.5
/start-phase 2.5
# Work on conflict detector in phase-2.5-conflict-detection branch

# Both sessions work independently on separate branches
# Both can merge to main independently when complete
```

---

## Phase Dependency Matrix

| Starting Phase | Required Phases | Optional Parallel | Blocked Until |
|----------------|----------------|-------------------|---------------|
| 1 | None | None | N/A |
| 2 | 1 | 2.5 | 1 merged |
| 2.5 | 1 | 2 | 1 merged |
| 3 | 1, 2, 2.5 | None | 1, 2, AND 2.5 merged |
| 4 | 1, 2, 2.5, 3 | None | All previous merged |

---

## Git Workflow Integration

This command implements Step 1 of the Git workflow:
1. **`/start-phase`** ← Prepares environment (YOU ARE HERE)
2. **`/build-phase`** ← Guided implementation with commits
3. **`/finish-phase`** ← Creates PR
4. **PR Review** ← Security and code quality checks
5. **Merge** ← Integration to main

**Reference:** See `planning/GIT-WORKFLOW.md` for complete workflow documentation

---

**Version:** 1.0
**Created:** 2025-10-14
**Integration:** Git & GitHub workflow implementation
