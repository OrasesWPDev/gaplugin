# Build Phase Command

Orchestrate building a specific phase with appropriate specialized agents.

## Usage
```
/build-phase [phase-number]
```

## What This Does

This command coordinates the development of a specific phase by:

1. **Reading phase planning document** - Loads requirements from `planning/phase-X-*/planning.md`
2. **Selecting appropriate agents** - Chooses specialized agents for the work
3. **Orchestrating development** - Launches agents in correct order
4. **Verifying completion** - Checks that phase goals are met

## Phase Overview

### Phase 1 - Foundation
```bash
/build-phase 1
```

**Agents used:**
- `cpt-specialist` - Creates Custom Post Type registration

**Deliverables:**
- Main plugin file (`ga-plugin.php`)
- Plugin constants defined
- Autoloader implemented
- CPT registered (`gap_tracking`)
- Meta fields registered

**Dependencies:**
- None (foundation phase)

**Completion criteria:**
- [ ] Plugin activates without errors
- [ ] CPT appears in admin menu
- [ ] Post meta is registered
- [ ] Autoloader loads classes

---

### Phase 2 - Admin Interface
```bash
/build-phase 2
```

**Agents used:**
- `meta-box-specialist` - Creates admin meta boxes
- `wp-code-reviewer` - Reviews code quality (optional)

**Deliverables:**
- Meta box class for tracking details
- Meta box class for settings
- Save handlers with security
- Admin styles
- Admin JavaScript

**Dependencies:**
- Phase 1 must be complete

**Completion criteria:**
- [ ] Meta boxes render in post editor
- [ ] Fields save correctly
- [ ] Nonce verification working
- [ ] All input sanitized
- [ ] All output escaped

---

### Phase 2.5 - Conflict Detection
```bash
/build-phase 2.5
```

**Agents used:**
- `conflict-detector-specialist` - Creates detection system
- `wp-code-reviewer` - Reviews regex patterns (optional)

**Deliverables:**
- Conflict detector class
- Regex patterns for GA4/GTM
- HTML scanning functionality
- Caching system
- Admin warnings

**Dependencies:**
- Phase 1 must be complete
- Can run parallel to Phase 2

**Completion criteria:**
- [ ] GA4 scripts detected
- [ ] GTM scripts detected
- [ ] Regex patterns tested
- [ ] Caching works
- [ ] Warnings display

---

### Phase 3 - Frontend Output
```bash
/build-phase 3
```

**Agents used:**
- `frontend-output-specialist` - Creates output handler
- `conflict-detector-specialist` - Integration (if needed)

**Deliverables:**
- Frontend output class
- Hook integration (wp_head, wp_footer)
- Scope filtering logic
- Script rendering (GA4, GTM, custom)
- Cache implementation

**Dependencies:**
- Phase 1 must be complete
- Phase 2.5 must be complete (needs conflict detector)

**Completion criteria:**
- [ ] Scripts output to frontend
- [ ] Scope filtering works
- [ ] Caching functions
- [ ] No duplicate scripts
- [ ] All output escaped

---

### Phase 4 - Testing & Security
```bash
/build-phase 4
```

**Agents used:**
- `wp-security-scanner` - Security audit
- `wp-code-reviewer` - Code quality review

**Deliverables:**
- Security audit report
- Code quality report
- Test checklist completion
- Documentation review
- Deployment preparation

**Dependencies:**
- All previous phases must be complete

**Completion criteria:**
- [ ] Security audit passes
- [ ] Code review passes
- [ ] All tests complete
- [ ] Documentation updated
- [ ] Ready for deployment

## Implementation

When this command is executed, perform the following steps:

### 0. Git Preparation (REQUIRED FIRST STEP)

**Before any development work:**

```bash
# Check current branch
git branch --show-current

# If not on correct phase branch, switch or create it
# Use /start-phase [number] to automate this

# Verify branch is up to date
git fetch origin
git status

# If behind origin, pull changes
git pull origin [branch-name]
```

**Branch Verification:**
- Phase 1: Must be on `phase-1-foundation`
- Phase 2: Must be on `phase-2-admin`
- Phase 2.5: Must be on `phase-2.5-conflict-detection`
- Phase 3: Must be on `phase-3-frontend`
- Phase 4: Must be on `phase-4-testing`

**If not on correct branch:**
```
ERROR: Not on correct phase branch
Current branch: main
Required branch: phase-[X]-[name]

SOLUTION: Run /start-phase [number] first
```

### 1. Validate Phase Number
```
Valid phases: 1, 2, 2.5, 3, 4
```

### 2. Check Dependencies
```php
// Example dependency check
if ( phase === 3 ) {
    // Check Phase 1 complete
    if ( ! file_exists( 'includes/class-gap-post-type.php' ) ) {
        return 'ERROR: Phase 1 not complete - CPT not implemented';
    }

    // Check Phase 2.5 complete
    if ( ! file_exists( 'includes/class-gap-conflict-detector.php' ) ) {
        return 'ERROR: Phase 2.5 not complete - Conflict detector required';
    }
}
```

### 3. Read Planning Document
```
$planning_file = "planning/phase-{$phase_number}-*/planning.md";
Read planning document to understand requirements
```

### 4. Launch Appropriate Agents
```
Use Task tool to launch specialized agents
Provide them with context from planning document
Monitor their progress
```

### 5. Verify Deliverables
```
Check that all expected files were created
Verify code compiles without errors
Run basic functionality tests
```

### 6. Commit Checkpoints During Development

**After each major deliverable, commit work:**

```bash
# Example: After creating CPT class (Phase 1)
git add includes/class-gap-post-type.php
git commit -m "feat(cpt): implement tracking script custom post type

- Register gap_tracking post type
- Add custom admin columns
- Implement column rendering

Addresses: Phase 1 deliverable (CPT registration)"
git push
```

**Commit Checkpoints by Phase:**

**Phase 1:**
- [ ] Commit after creating main plugin file
- [ ] Commit after implementing autoloader
- [ ] Commit after creating activator class
- [ ] Commit after implementing CPT class

**Phase 2:**
- [ ] Commit after creating meta box class structure
- [ ] Commit after implementing field rendering
- [ ] Commit after implementing save handlers
- [ ] Commit after adding admin assets

**Phase 2.5:**
- [ ] Commit after creating conflict detector class
- [ ] Commit after implementing ID extraction
- [ ] Commit after implementing duplicate detection
- [ ] Commit after implementing HTML scanning

**Phase 3:**
- [ ] Commit after creating frontend output class
- [ ] Commit after implementing hook integration
- [ ] Commit after implementing scope filtering
- [ ] Commit after implementing conflict prevention

**Phase 4:**
- [ ] Commit after each test category completion
- [ ] Commit after security audit fixes
- [ ] Commit after code quality improvements
- [ ] Commit after documentation updates

### 7. Report Completion
```
# Phase [X] Build Complete

## Agents Used
- [List of agents]

## Files Created
- [List of files]

## Tests Passed
- [List of passing tests]

## Git Status
- Branch: phase-[X]-[name]
- Commits: [number] commits pushed
- Status: Ready for PR creation

## Next Steps
1. Run /review-phase [X] for security and code review
2. Test component with /test-component [component-name]
3. Run /finish-phase [X] to create pull request
4. Wait for PR review and approval
5. Merge PR to main
```

## Parallel vs Sequential

### Parallel Build (Recommended)
When using parallel development:

```bash
# Session 1: Build Phase 1 (blocks others)
/build-phase 1

# Session 2: Build Phase 2 (after Phase 1)
/build-phase 2

# Session 3: Build Phase 2.5 (after Phase 1, parallel to Phase 2)
/build-phase 2.5

# Session 4: Build Phase 3 (after Phase 1 and 2.5)
/build-phase 3

# Session 5: Build Phase 4 (after all others)
/build-phase 4
```

### Sequential Build (Fallback)
When using sequential development:

```bash
# Single session: Build in order
/build-phase 1
/build-phase 2
/build-phase 2.5
/build-phase 3
/build-phase 4
```

## Agent Selection Matrix

| Phase | Primary Agent | Secondary Agent | Review Agent |
|-------|---------------|-----------------|--------------|
| 1 | cpt-specialist | - | wp-code-reviewer |
| 2 | meta-box-specialist | - | wp-code-reviewer |
| 2.5 | conflict-detector-specialist | - | wp-code-reviewer |
| 3 | frontend-output-specialist | conflict-detector-specialist | wp-code-reviewer |
| 4 | wp-security-scanner | wp-code-reviewer | - |

## Success Criteria

A phase build is successful when:
- [ ] All required files created
- [ ] Code follows WordPress standards
- [ ] No PHP errors or warnings
- [ ] Basic functionality tests pass
- [ ] Dependencies satisfied
- [ ] Planning document requirements met

## When to Use

Use this command:
- **Starting a new phase** - Initial development
- **After planning review** - Ready to implement
- **Coordinating agents** - Multiple agents needed
- **Following workflow** - Structured development

## Error Handling

### Missing Dependencies
```
ERROR: Cannot build Phase 3 - Phase 1 not complete
SOLUTION: Run /build-phase 1 first
```

### Agent Failures
```
ERROR: cpt-specialist failed to create class
SOLUTION: Check error message, fix issue, retry
```

### File Conflicts
```
WARNING: File already exists: includes/class-gap-post-type.php
SOLUTION: Review existing file, decide to keep or replace
```

## Related Commands

- `/review-phase` - Review completed phase
- `/test-component` - Test individual components
- `/switch-to-sequential` - Change to sequential workflow

## Notes

- This command orchestrates development, not implementation
- Agents do the actual coding work
- Planning documents guide agent behavior
- Each phase should be reviewed after building
- Dependencies are enforced automatically

## Example Workflow

Complete workflow for building the plugin:

```bash
# 1. Build foundation
/build-phase 1
/test-component cpt
/review-phase 1

# 2. Build admin (can run parallel to 2.5)
/build-phase 2
/test-component meta-box
/review-phase 2

# 3. Build conflict detection (parallel to 2)
/build-phase 2.5
/test-component conflict-detector
/review-phase 2.5

# 4. Build frontend (requires 1 and 2.5)
/build-phase 3
/test-component frontend-output
/review-phase 3

# 5. Final testing and security
/build-phase 4
/review-phase 4

# 6. Deploy
```

## Planning Document Reference

Each phase has its own planning document:

- Phase 1: `planning/phase-1-foundation/planning.md`
- Phase 2: `planning/phase-2-admin/planning.md`
- Phase 2.5: `planning/phase-2.5-conflict-detection/planning.md`
- Phase 3: `planning/phase-3-frontend/planning.md`
- Phase 4: `planning/phase-4-testing/planning.md`

These documents contain:
- Technical specifications
- Code examples
- WordPress standards
- Security requirements
- File structure
- Dependencies
