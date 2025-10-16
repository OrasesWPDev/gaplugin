# Git Commit Message Format

Standard commit message format for GA Plugin development.

## Commit Message Structure

### Single-Line Format (For most commits)
```
[type]([epic-scope]): [ticket-id] - [short-description]
```

### Full Format (Recommended)
```
[type]([epic-scope]): [ticket-id] - [short-description]

- [Implementation detail 1]
- [Implementation detail 2]
- [Implementation detail 3]

Ticket: [TICKET-ID]
Epic: [EPIC-ID]
Status: [Completed/In Progress]
```

---

## Components

### Type
The type of change being made:

| Type | Use Case | Example |
|------|----------|---------|
| `feat` | New feature (most tickets) | `feat(epic-02): US-02.1 - add custom post type` |
| `fix` | Bug fix | `fix(epic-02): US-02.3 - correct nonce verification` |
| `refactor` | Code restructure without functionality change | `refactor(epic-02): improve CPT registration logic` |
| `docs` | Documentation only | `docs(epic-02): update meta field documentation` |
| `test` | Test addition or modification | `test(epic-02): add CPT registration tests` |
| `chore` | Maintenance tasks | `chore(epic-02): update dependencies` |

**Most Common**: `feat` (for implementing user stories and technical tasks)

### Epic Scope
The epic this work belongs to:

| Scope | Epic | Example |
|-------|------|---------|
| `epic-00` | Project Setup | `feat(epic-00): initialize project structure` |
| `epic-01` | Foundation | `feat(epic-01): implement autoloader` |
| `epic-02` | Admin Interface | `feat(epic-02): implement CPT` |
| `epic-03` | Conflict Detection | `feat(epic-03): detect duplicate scripts` |
| `epic-04` | Frontend Output | `feat(epic-04): inject scripts into frontend` |
| `epic-05` | Testing & Launch | `test(epic-05): add integration tests` |

### Ticket ID
The ticket ID being completed:

- User Stories: `US-XX.Y`
- Technical Tasks: `TT-XX.Y`

**Required for all development commits**

Example: `feat(epic-02): US-02.1 - manage tracking scripts`

### Short Description
Brief description of what was done (50 characters or less):

✅ `manage tracking scripts via custom post type`
✅ `implement GAP_CPT class with singleton pattern`
✅ `add custom admin columns to tracking scripts`

❌ `fix stuff` (too vague)
❌ `implement the custom post type registration for the tracking scripts admin interface that allows administrators to manage scripts` (too long)

---

## Implementation Details

Body of commit (optional but recommended):

```
- [Detail 1: What was changed]
- [Detail 2: How it was changed]
- [Detail 3: Why it was changed]
```

**Example:**
```
- Implemented GAP_CPT class with singleton pattern
- Registered tracking_script custom post type
- Added custom admin columns for tracking details
- Set capabilities to manage_options only
- Added dashicons-analytics menu icon
```

### Guidelines
- Use bullet points
- One detail per line
- Explain the "what" and "why"
- Keep each line under 72 characters
- Start with action verb (Implemented, Added, Fixed, etc.)

---

## Footer Format

At the end of detailed commits:

```
Ticket: [TICKET-ID]
Epic: [EPIC-ID]
Status: [Completed/In Progress]
```

**Example:**
```
Ticket: US-02.1
Epic: EPIC-02
Status: Completed
```

---

## Complete Examples

### User Story Completion
```
feat(epic-02): US-02.1 - manage tracking scripts via CPT

- Implemented GAP_CPT class with singleton pattern
- Registered tracking_script custom post type
- Added custom admin columns for tracking details
- Set capabilities to manage_options only
- Added dashicons-analytics menu icon
- Verified admin menu integration

Ticket: US-02.1
Epic: EPIC-02
Status: Completed
```

### Technical Task Completion
```
feat(epic-02): TT-02.1 - implement GAP_CPT class

- Created class with singleton pattern
- Implemented register_post_type() method
- Added get_labels() helper method
- Implemented add_custom_columns() filter
- Implemented render_custom_columns() action
- Hooked to WordPress init action
- Added comprehensive docblocks

Ticket: TT-02.1
Epic: EPIC-02
Status: Completed
```

### Bug Fix
```
fix(epic-02): US-02.3 - correct nonce verification order

- Move nonce check before capability check
- Add DOING_AUTOSAVE check before processing
- Improve error handling in save_meta_boxes()
- Add debug logging for verification failures

Ticket: US-02.3
Epic: EPIC-02
Status: Completed
```

### Refactoring
```
refactor(epic-02): improve GAP_CPT initialization

- Extract post type arguments to separate method
- Add constants for post type configuration
- Reduce register_post_type() complexity
- Add helper method for capability setup

Ticket: TT-02.1 (related)
Epic: EPIC-02
Status: In Progress
```

### Documentation
```
docs(epic-02): add meta field documentation

- Document all meta fields and their purposes
- Add examples for field retrieval and saving
- Explain data validation rules
- Add security considerations

Ticket: TT-02.2 (related)
Epic: EPIC-02
Status: Completed
```

---

## Testing Task Example

```
test(epic-02): add tests for CPT registration

- Test that CPT is registered on init hook
- Test that only admins can access CPT
- Test that post type has correct capabilities
- Test that admin menu appears with correct icon
- Test that non-admins don't see menu

Ticket: TT-02.5 (related)
Epic: EPIC-02
Status: Completed
```

---

## Edge Cases

### Multiple related tasks in one commit
If absolutely necessary to commit multiple tasks (not recommended):

```
feat(epic-02): US-02.2, TT-02.3 - add admin columns and styling

- Created custom admin columns structure
- Implemented column rendering
- Added CSS styling for columns
- Tested column display

Ticket: US-02.2, TT-02.3
Epic: EPIC-02
Status: Completed
```

**Better approach**: Make separate commits per ticket

### Incomplete work (in progress)
```
feat(epic-02): US-02.4 - dynamic UI (WIP)

- Implemented JavaScript event handlers
- Added form validation logic
- TODO: Add show/hide for scope selector

Ticket: US-02.4
Epic: EPIC-02
Status: In Progress
```

---

## Commit Guidelines

### DO ✅
- ✅ Include ticket ID in every commit
- ✅ Use descriptive, specific messages
- ✅ Keep first line under 50 characters
- ✅ Use imperative mood (e.g., "Add" not "Added")
- ✅ Add details for complex changes
- ✅ Reference related tickets in body
- ✅ Include Ticket, Epic, Status footer

### DON'T ❌
- ❌ Use vague messages ("fix stuff", "updates")
- ❌ Mix multiple unrelated changes
- ❌ Forget to include ticket ID
- ❌ Make commits too large (> 20 files if possible)
- ❌ Commit debug code (var_dump, console.log)
- ❌ Use commit messages to describe process (use PR description)

---

## Automatic Commit Messages

When using `/complete-ticket` command, commit message is auto-generated:

```bash
/complete-ticket US-02.1
```

Generates:
```
feat(epic-02): complete US-02.1 - manage tracking scripts via CPT

- [Extracted from ticket acceptance criteria]
- [Extracted from ticket implementation tasks]

Ticket: US-02.1
Epic: EPIC-02
Status: Completed
```

---

## Examples by Change Type

| Change | Type | Message |
|--------|------|---------|
| New feature | `feat` | `feat(epic-02): US-02.1 - add CPT for tracking scripts` |
| Bug fix | `fix` | `fix(epic-02): US-02.3 - prevent duplicate script injection` |
| Code cleanup | `refactor` | `refactor(epic-02): simplify CPT registration logic` |
| Add documentation | `docs` | `docs(epic-02): document meta field structures` |
| Add tests | `test` | `test(epic-02): add CPT registration tests` |
| Update dependencies | `chore` | `chore: update WordPress plugin dependencies` |

---

## Quick Reference

```bash
# Minimal commit
git commit -m "feat(epic-02): US-02.1 - add custom post type"

# Detailed commit
git commit -m "feat(epic-02): US-02.1 - add custom post type

- Implemented GAP_CPT class
- Registered tracking_script post type
- Set admin menu position and icon

Ticket: US-02.1
Epic: EPIC-02
Status: Completed"

# Interactive editor for long messages
git commit    # Opens editor for detailed message
```

---

## Integration with Pull Requests

Commit messages appear in PR:
- Each commit shown as separate item
- Commit message appears in PR description
- Allows reviewers to understand change history
- Provides searchable commit log

Example PR from 3 commits:
```
🔀 3 commits

- feat(epic-02): US-02.1 - manage tracking scripts via CPT
- feat(epic-02): TT-02.1 - implement GAP_CPT class
- feat(epic-02): TT-02.2 - implement GAP_Meta_Boxes class
```

Each commit can be viewed individually with full details.
