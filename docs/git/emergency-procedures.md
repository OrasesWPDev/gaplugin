# Emergency Procedures

Reference for handling critical git situations.

## Rollback Merged Epic

**Situation**: An epic was merged to main but critical issues discovered

### Procedure

1. **Create rollback branch from main:**
```bash
git checkout main
git pull origin main
git checkout -b revert-epic-XX
```

2. **Create revert commit:**
```bash
git revert -m 1 [merge-commit-hash]
# -m 1 means "mainline parent" for squash merge
```

3. **Verify revert:**
```bash
git log --oneline -3  # See the revert commit
git diff main..HEAD   # Verify changes being reverted
```

4. **Push and create PR:**
```bash
git push -u origin revert-epic-XX
gh pr create --title "Revert EPIC-XX: [Reason]" --body "Reverting due to [critical issue]"
```

5. **After revert merges:**
```bash
git checkout main
git pull origin main
# Epic is now reverted from production
```

### Important Notes
- ⚠️ NEVER rewrite history on main (no force push)
- ✅ Revert preserves full history
- ✅ Other developers can continue working
- ✅ Original epic can be fixed and re-merged

### What Happens After Revert
1. Reverted epic is removed from main
2. Code is safe to deploy without the epic
3. Epic owner fixes issues on the epic branch
4. After fixes, epic can be re-merged to main
5. History shows both merge and revert

---

## Recover Lost Work

### Scenario 1: Uncommitted Work Lost

**Problem**: Changes were lost before committing

```bash
# Check stash first
git stash list

# If found, restore it
git stash pop

# If not in stash, check reflog
git reflog
# Output shows recent states
```

### Scenario 2: Branch Accidentally Deleted

**Problem**: Epic branch was deleted before merging

```bash
# Find the branch in reflog
git reflog | grep epic-XX-name
# Example output: "abc1234 HEAD@{5}: checkout: moving from epic-02 to main"

# Recover the branch
git checkout -b epic-XX-name-recovered abc1234

# Push recovered branch
git push -u origin epic-XX-name-recovered

# Verify commits are there
git log --oneline epic-XX-name-recovered
```

### Scenario 3: Commit Deleted Accidentally

**Problem**: Wanted commit was deleted

```bash
# Find commit in reflog
git reflog | grep [partial-message]

# Recover commit to new branch
git checkout -b recovered-commit [commit-hash]

# Cherry-pick to current branch if needed
git checkout epic-XX-name
git cherry-pick [commit-hash]
```

---

## Resolve Merge Conflicts

**Situation**: Two epics modified the same file

### Identify Conflicts
```bash
# See all conflicting files
git status

# Detailed conflict info
git diff --name-only --diff-filter=U
```

### Types of Conflicts

#### 1. Both Modified Same Lines
```
<<<<<<< HEAD
// Version from current branch
=======
// Version from incoming branch
>>>>>>> epic-XX-name
```

**Resolution:**
- Choose one version, OR
- Manually combine both
- Remove conflict markers (`<<<<`, `====`, `>>>>`)

#### 2. One Deleted, One Modified
```
deleted by us:    includes/class-gap-old.php
modified by them: includes/class-gap-old.php
```

**Resolution:**
```bash
# Keep the modified version
git add includes/class-gap-old.php

# Or remove completely
git rm includes/class-gap-old.php
```

#### 3. Both Added File
```
both added:       includes/class-gap-new.php
```

**Resolution:**
- Check both versions
- Determine correct implementation
- Keep one version
- Delete the other

### Resolving Large Conflicts

**For conflicts in critical files:**

1. **Talk to both epic owners** before resolving
2. **Create separate branch** for conflict resolution
3. **Document the decision** in commit message

```bash
# Create resolution branch
git checkout -b resolve-epic-XX-conflicts

# Work through each conflict
# Test thoroughly
# Commit with explanation

git commit -m "fix: resolve conflicts between epic-02 and epic-04

Coordinated with both epic owners.
- Kept GAP_Meta_Boxes implementation from EPIC-02
- Added frontend hooks from EPIC-04
- Tested both features together

Conflicts Resolved: 3 files
"
```

### Auto-Conflict Resolution Tools

```bash
# Prefer current (if current branch is correct)
git checkout --ours [file]

# Prefer incoming (if other branch is correct)
git checkout --theirs [file]

# Abort entire merge and start over
git merge --abort
```

---

## Clean Up Branches

**Situation**: Old branches cluttering repository

### Find Branches to Delete

```bash
# See all branches and last update time
git for-each-ref --sort=-committerdate --format='%(refname:short) %(committerdate:short)' refs/heads/

# Find merged branches
git branch --merged main

# Find branches older than 2 weeks
git branch -r --no-merged main | while read branch; do
  date=$(git log -1 --format='%at' $branch)
  echo "$(date -d @$date '+%Y-%m-%d') $branch"
done | sort
```

### Delete Local Branch

```bash
# Delete after merge
git branch -d epic-02-admin-interface

# Force delete (even if not merged - dangerous!)
git branch -D epic-old-work
```

### Delete Remote Branch

```bash
# After PR merge (usually automatic)
git push origin --delete epic-02-admin-interface

# Or using gh
gh api repos/OrasesWPDev/gaplugin-v2/git/refs/heads/epic-02 -X DELETE
```

### Clean Up All Merged Branches

```bash
# Remove all local branches merged to main
git branch --merged main | grep -v main | xargs -r git branch -d

# Remove stale tracking branches
git remote prune origin
```

---

## Fix Recent Commits

### Scenario 1: Wrong Commit Message

```bash
# If not pushed yet
git commit --amend -m "new message"

# If already pushed (use caution)
git commit --amend -m "new message"
git push --force-with-lease
```

### Scenario 2: Forgot to Include Files

```bash
# Add forgotten file to last commit
git add forgotten-file.php
git commit --amend --no-edit
```

### Scenario 3: Wrong Branch

```bash
# Committed to main instead of epic-02-name?

# Get commit hash
git log --oneline -5

# Create epic branch from commit
git checkout -b epic-02-admin-interface [commit-hash]

# Go back to main and remove commit
git checkout main
git revert [commit-hash]  # or reset if not pushed
```

---

## Undo Changes

### Unstage Changes (Before Commit)

```bash
# Unstage specific file
git reset HEAD file.php

# Unstage all changes
git reset HEAD

# Discard unstaged changes (dangerous!)
git checkout -- file.php

# Or newer syntax
git restore file.php
```

### Revert Committed Changes

```bash
# Keep history, create new commit that undoes changes
git revert [commit-hash]

# Reset to before commit (lose history, dangerous!)
git reset --soft HEAD~1  # Keep changes staged
git reset --mixed HEAD~1  # Keep changes unstaged
git reset --hard HEAD~1   # Discard changes (dangerous!)
```

### Revert to Specific Commit

```bash
# Go back to specific commit
git checkout [commit-hash]

# Create new branch from there
git checkout -b fix-branch

# Or reset current branch
git reset --hard [commit-hash]  # Dangerous if pushed!
```

---

## Sync Out-of-Sync Branch

**Situation**: Epic branch is days behind main with conflicts

### Option 1: Merge Main Into Epic (Preferred)

```bash
git fetch origin
git merge origin/main

# Resolve conflicts
git add .
git commit -m "Merge main into epic-02 to resolve conflicts"
git push
```

**Result**: Epic branch includes all latest main changes

### Option 2: Rebase Epic On Main (Advanced)

```bash
git fetch origin
git rebase origin/main

# Resolve conflicts one commit at a time
git add .
git rebase --continue

# After rebase done
git push --force-with-lease
```

**Result**: Epic commits on top of main (cleaner history)

**Caution**: Only use if epic branch hasn't been shared heavily

### Option 3: Squash and Re-apply

```bash
# Create new branch from latest main
git checkout -b epic-02-admin-interface-v2 origin/main

# Cherry-pick our commits
git cherry-pick epic-02-admin-interface

# Replace old branch
git branch -D epic-02-admin-interface
git branch -m epic-02-admin-interface-v2 epic-02-admin-interface
```

---

## Emergency Communication

### If Critical Issue Found Post-Merge

1. **Immediately notify team** (Slack, email)
2. **Assess severity** (Can it be fixed in hotfix? Revert needed?)
3. **If revert needed**: Follow "Rollback Merged Epic" procedure
4. **If hotfix**: Create emergency branch for critical fix only
5. **Document** what went wrong (post-mortem)

### Emergency Hotfix Process

```bash
# Create hotfix branch from main
git checkout main
git pull origin main
git checkout -b hotfix/critical-issue

# Make minimal fix
git add fixed-file.php
git commit -m "fix(critical): [specific issue description]"

# Create PR marked urgent
git push -u origin hotfix/critical-issue
gh pr create --title "URGENT: Critical issue fix" --body "[description]"

# After review/approval
gh pr merge [PR-number] --squash
```

---

## Prevent Future Emergencies

### Pre-PR Checklist
- [ ] All tests passing
- [ ] Code review completed
- [ ] No PHP errors or warnings
- [ ] All acceptance criteria met
- [ ] Security audit completed
- [ ] Manual testing passed
- [ ] Merge conflicts resolved

### Pre-Merge Checklist
- [ ] PR approved by reviewer
- [ ] All CI checks passing
- [ ] Branch synced with main
- [ ] No conflicts detected
- [ ] Testing specialist approval

### Post-Merge Checklist
- [ ] Monitor for issues (first hour)
- [ ] User testing validation
- [ ] Performance monitoring
- [ ] Error log monitoring

---

## Getting Help

### Check Git Status
```bash
# Show everything
git status
git log --oneline -10
git reflog
git branch -a
```

### Check Remote Status
```bash
# Is your local in sync?
git fetch origin
git status

# See what's on remote
git log origin/main --oneline -5
```

### When In Doubt
1. **Stop and don't push** (unless already pushed)
2. **Create a new branch** before trying fixes
3. **Ask for help** - document what you tried
4. **Keep the original branch** until resolved
5. **Never force push to main**

---

## Recovery Examples

### Example 1: Recovered Deleted Feature Branch

```bash
$ git reflog | grep feature-auth
abc1234 HEAD@{12}: checkout: moving from main to feature-auth
def5678 HEAD@{30}: commit: Implement authentication

$ git checkout -b feature-auth-recovered abc1234
$ git log --oneline feature-auth-recovered  # Verify commits
$ git push -u origin feature-auth-recovered
```

### Example 2: Reverted Bad Epic

```bash
$ git log main --oneline | head -5
# xyz7890 Merge pull request #15 from epic-02-admin-interface
# abc1234 Some other commit

$ git checkout -b revert-epic-02
$ git revert -m 1 xyz7890  # Revert the merge commit
$ git push -u origin revert-epic-02

# Create PR for revert
```

### Example 3: Resolved Complex Conflicts

```bash
$ git merge epic-04-frontend-output
# CONFLICT in includes/class-gap-cpt.php

$ git status  # See conflicting files
$ # Edit files to resolve conflicts
$ git add .
$ git commit -m "Merge epic-04 with conflict resolution"
$ git push
```

---

## Reference

**Don't use in emergency without understanding:**
- `git reset --hard` (destructive)
- `git push --force` (rewrites history)
- `git revert -m` (complex merge reverts)

**Always safe:**
- `git revert [commit]` (creates undo commit)
- `git merge --abort` (cancels merge)
- `git checkout -b` (creates safe branch)
- `git stash` (temporary storage)
