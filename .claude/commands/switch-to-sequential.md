# Switch to Sequential Command

Switch from parallel development to sequential development workflow.

## Usage
```
/switch-to-sequential
```

## What This Does

This command transitions the development workflow from parallel multi-session development to sequential single-session development:

1. **Assess current state** - Determine what's complete and what's in progress
2. **Consolidate sessions** - Merge work from multiple sessions
3. **Establish sequence** - Create linear progression plan
4. **Resume development** - Continue with sequential approach

## When to Use

Switch to sequential development when you encounter:

### 1. Merge Conflicts
Multiple sessions editing the same files:
```
ERROR: Conflict in includes/class-gap-conflict-detector.php
Session 2 (Admin): Modified for meta box integration
Session 3 (Conflict): Modified for core functionality

SOLUTION: Switch to sequential to avoid conflicts
```

### 2. Coordination Overhead
Too much time spent coordinating between sessions:
```
ISSUE: Phase 3 needs to know Phase 2.5 implementation details
       Waiting for Session 3 to finish before Session 4 can proceed
       Circular dependencies between sessions

SOLUTION: Switch to sequential for simpler coordination
```

### 3. Unclear Ownership
Confusion about which session handles which code:
```
QUESTION: Should conflict detector warnings be in meta box or detector class?
Session 2 (Admin): Added to meta box
Session 3 (Conflict): Added to detector class
Result: Duplicate code

SOLUTION: Switch to sequential for clear ownership
```

### 4. Development Speed
Parallel approach is slower than expected:
```
OBSERVATION: Time spent managing sessions > time saved by parallelism

SOLUTION: Switch to sequential for simpler, faster development
```

## Parallel vs Sequential

### Parallel Development (Original Plan)
```
Timeline:
├─ Phase 1 (Sequential) ────────┐
│                               │
├─ Phase 2 (Parallel) ──────────┤
├─ Phase 2.5 (Parallel) ────────┤
│                               │
├─ Phase 3 (Sequential) ────────┤
│                               │
└─ Phase 4 (Sequential) ────────┘

Sessions: 3-5 concurrent sessions
Coordination: High
Speed: Fast (if no conflicts)
Complexity: High
```

### Sequential Development (Fallback)
```
Timeline:
├─ Phase 1 ────┐
├─ Phase 2 ────┤
├─ Phase 2.5 ──┤
├─ Phase 3 ────┤
└─ Phase 4 ────┘

Sessions: 1 session
Coordination: Low
Speed: Moderate
Complexity: Low
```

## Implementation

When this command is executed, perform the following steps:

### 1. Assess Current State

Check completion status of each phase:

```bash
# Phase 1 - Foundation
[ ] Main plugin file
[ ] CPT registration
[ ] Autoloader
Status: [Complete/In Progress/Not Started]

# Phase 2 - Admin
[ ] Meta boxes
[ ] Save handlers
[ ] Admin styles/scripts
Status: [Complete/In Progress/Not Started]

# Phase 2.5 - Conflict Detection
[ ] Detector class
[ ] Regex patterns
[ ] Caching
Status: [Complete/In Progress/Not Started]

# Phase 3 - Frontend
[ ] Output handler
[ ] Hook integration
[ ] Scope filtering
Status: [Complete/In Progress/Not Started]

# Phase 4 - Testing
[ ] Security audit
[ ] Code review
[ ] Documentation
Status: [Complete/In Progress/Not Started]
```

### 2. Identify Conflicts

Check for file conflicts between sessions:

```bash
# Check git status
git status

# Look for:
- Modified files in multiple sessions
- Uncommitted changes
- Divergent branches
```

### 3. Consolidate Work

Merge work from parallel sessions:

```bash
# If using git branches
git checkout main
git merge session-2-admin
git merge session-3-conflict-detection

# Resolve any conflicts
git mergetool

# Commit consolidated work
git commit -m "Consolidate parallel sessions into sequential workflow"
```

### 4. Create Sequential Plan

Determine next phase to work on:

```php
function gap_determine_next_phase() {
    $phases = array(
        1   => 'Foundation',
        2   => 'Admin',
        2.5 => 'Conflict Detection',
        3   => 'Frontend',
        4   => 'Testing',
    );

    foreach ( $phases as $number => $name ) {
        if ( ! gap_is_phase_complete( $number ) ) {
            return $number;
        }
    }

    return null; // All complete
}
```

### 5. Resume Development

Continue with next incomplete phase:

```bash
# Example: If Phase 2 is incomplete
/build-phase 2
```

## Transition Checklist

Before switching to sequential:

- [ ] Commit all changes from parallel sessions
- [ ] Resolve any merge conflicts
- [ ] Identify next incomplete phase
- [ ] Close parallel session windows
- [ ] Update workflow documentation
- [ ] Inform team (if applicable)

## Expected Output

```
# Switching to Sequential Workflow

## Current State Assessment
Phase 1: ✓ Complete
Phase 2: ⚠ In Progress (60% complete)
Phase 2.5: ✓ Complete
Phase 3: ✗ Not Started
Phase 4: ✗ Not Started

## Conflicts Detected
- includes/class-gap-conflict-detector.php (modified in 2 sessions)

## Consolidation Plan
1. Merge session-2-admin branch
2. Merge session-3-conflict-detection branch
3. Resolve conflicts in class-gap-conflict-detector.php
4. Continue with Phase 2 completion

## Next Phase
Phase 2 (Admin Interface)

## Recommended Action
/build-phase 2

---

Sequential workflow activated. All future development will proceed
through phases 1 → 2 → 2.5 → 3 → 4 in a single session.
```

## Advantages of Sequential

### Simpler Coordination
- One session to manage
- No session synchronization needed
- Clear linear progression

### Fewer Conflicts
- No concurrent file modifications
- Easier to track changes
- Single source of truth

### Easier Debugging
- Single execution context
- Clearer error sources
- Simpler stack traces

### Lower Cognitive Load
- Focus on one phase at a time
- No context switching
- Clearer task priority

## Disadvantages of Sequential

### Slower Overall
- Can't parallelize independent work
- Must complete phases in order
- Longer total development time

### Dependency Blocking
- Phase 3 waits for Phase 2 AND 2.5
- No work-ahead possible
- Idle time if blocked

## Migration Path

### From Parallel to Sequential

```bash
# Current: Multiple sessions running
Session 1: Phase 2 - Admin
Session 2: Phase 2.5 - Conflict Detection
Session 3: Phase 3 - Frontend (waiting)

# After switch: Single session
Session 1: Complete Phase 2
Session 1: Complete Phase 2.5 (if not done)
Session 1: Start Phase 3
Session 1: Start Phase 4
```

### From Sequential to Parallel

If you want to switch back (not recommended mid-development):

```bash
# Assess which phases are independent
# Create separate branches
git checkout -b session-2-admin
git checkout -b session-3-conflict

# Work on each in separate windows
# Merge when both complete
```

## Success Criteria

Sequential switch is successful when:
- [ ] All parallel sessions consolidated
- [ ] No unresolved merge conflicts
- [ ] Clear next phase identified
- [ ] Single session active
- [ ] Development can proceed linearly

## Related Commands

- `/build-phase` - Build specific phase
- `/review-phase` - Review completed phase
- `/test-component` - Test individual components

## Notes

- **This is a one-way transition** - Switching back to parallel mid-development is not recommended
- **Complete in-progress work first** - Finish current parallel tasks before switching
- **Commit everything** - Ensure no uncommitted changes before consolidating
- **Update documentation** - Note the workflow change in project docs

## Decision Matrix

Should you switch to sequential?

| Factor | Stay Parallel | Switch Sequential |
|--------|--------------|-------------------|
| Merge conflicts | None | Frequent |
| Coordination time | < 10% dev time | > 25% dev time |
| Team size | 3+ developers | 1-2 developers |
| Phase independence | High | Low |
| Project complexity | High | Medium-Low |
| Development speed | Fast | Slow with parallel |

## Example Scenarios

### Scenario 1: Frequent Conflicts
```
PROBLEM: Sessions 2 and 3 keep modifying same files
DECISION: Switch to sequential
REASON: Conflicts > time saved by parallelism
```

### Scenario 2: Dependency Issues
```
PROBLEM: Phase 3 needs Phase 2.5 details, causing delays
DECISION: Switch to sequential
REASON: Dependencies causing blocking
```

### Scenario 3: Solo Development
```
PROBLEM: One developer managing multiple sessions is complex
DECISION: Switch to sequential
REASON: Cognitive overhead not worth parallelism benefits
```

### Scenario 4: Smooth Parallel
```
PROBLEM: None - parallel working great
DECISION: Stay parallel
REASON: If it ain't broke, don't fix it
```

## Recovery Steps

If issues arise after switching:

1. **Lost work:** Check git reflog
2. **Broken functionality:** Run tests, isolate issue
3. **Unclear state:** Review git log, rebuild context
4. **Need to parallelize again:** Create feature branches carefully

## Support

This is a **workflow management command** - it doesn't write code, it changes how you approach development.

For help with actual implementation, use:
- `/build-phase [X]` - To continue development
- `/review-phase [X]` - To check quality
- `/test-component [name]` - To verify functionality
