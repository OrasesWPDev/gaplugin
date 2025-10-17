# /start-epic Command

**Purpose:** Initialize epic development with proper branch creation and workflow validation

**Usage:** `/start-epic XX` (where XX is epic number: 00, 01, 02, etc.)

**Category:** Workflow Management

---

## What This Command Does

This command ensures proper epic workflow by:

1. **Validating Prerequisites**
   - Main branch is up to date
   - Not currently on main branch
   - Git hooks are installed
   - Workflow validation passes

2. **Creating Epic Branch**
   - Creates branch: `epic-XX-name`
   - Pushes to remote with tracking
   - Switches to epic branch

3. **Invoking git-workflow-specialist**
   - Delegates all git operations to specialist agent
   - Ensures proper branch naming
   - Configures remote tracking

4. **Blocking Invalid Operations**
   - Prevents epic start on main branch
   - Prevents duplicate epic branches
   - Validates epic exists in tickets/

---

## Command Execution

When you invoke `/start-epic 01`, Claude will:

```bash
# Invoke git-workflow-specialist agent with this request:

"Start EPIC-01 development following established workflow:

VALIDATION REQUIREMENTS (MUST PASS):
1. Verify main branch is up to date with origin
2. Verify not currently on main branch
3. Verify git hooks are installed and active
4. Verify EPIC-01 exists in docs/tickets/

GIT OPERATIONS (EXECUTE):
1. Ensure main is current: git checkout main && git pull origin main
2. Create epic branch: git checkout -b epic-01-foundation
3. Push with tracking: git push -u origin epic-01-foundation
4. Verify branch active: git status

BLOCKING CONDITIONS:
- If on main → ERROR: Cannot start epic on main branch
- If epic branch exists → ERROR: Epic branch already exists
- If epic doesn't exist → ERROR: Epic not found in tickets/
- If git hooks missing → ERROR: Install hooks first

DOCUMENTATION:
- See docs/GIT-WORKFLOW.md for branch strategy
- See docs/DEVELOPMENT-WORKFLOW.md for epic workflow
- See docs/tickets/EPIC-01-foundation/ for epic details

OUTPUT:
Report epic branch creation status and next steps."
```

---

## Example Usage

```
User: /start-epic 01

Claude: I'll invoke the git-workflow-specialist to start EPIC-01.

[git-workflow-specialist executes:]
✅ Main branch updated
✅ Created branch: epic-01-foundation
✅ Pushed to origin with tracking
✅ Switched to epic-01-foundation

You can now begin EPIC-01 development.
Use /work-epic-01 to execute all tasks.
```

---

## Error Handling

**If on main branch:**
```
❌ ERROR: Cannot start epic while on main branch
✅ Fix: Already on main - just create the epic branch
```

**If epic branch exists:**
```
❌ ERROR: Epic branch already exists
✅ Fix: git checkout epic-01-foundation (switch to it)
```

**If git hooks missing:**
```
❌ ERROR: Git hooks not installed
✅ Fix: Run workflow validation script
```

---

## Epic Numbers Reference

| Epic | Branch Name | Description |
|------|-------------|-------------|
| 00 | epic-00-project-setup | Project infrastructure |
| 01 | epic-01-foundation | Core plugin foundation |
| 02 | epic-02-admin-interface | Admin UI & CPT |
| 03 | epic-03-conflict-detection | Conflict detection system |
| 04 | epic-04-frontend-output | Frontend script output |
| 05 | epic-05-testing-launch | Testing & launch prep |

---

## Integration

This command integrates with:
- **git-workflow-specialist** - Executes all git operations
- **tools/validate-workflow.sh** - Pre-flight validation
- **docs/GIT-WORKFLOW.md** - Branch strategy reference
- **/work-epic-XX** commands - Epic execution commands

---

## Best Practices

✅ **DO:**
- Run `/start-epic XX` before any epic work
- Verify validation passes before proceeding
- Let git-workflow-specialist handle git operations

❌ **DON'T:**
- Create epic branches manually
- Start epic while on main
- Skip validation checks

---

**Version:** 1.0
**Last Updated:** 2025-10-16
**Category:** Workflow Management
