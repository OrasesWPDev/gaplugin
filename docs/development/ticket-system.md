# Ticket System Reference

This document defines the format, structure, and conventions for tickets in the GA Plugin development workflow.

## Ticket ID Format

### User Stories: `US-XX.Y`
- `XX` = Epic number (00-05)
- `Y` = Story sequence number (1, 2, 3, ...)

**Examples:**
- `US-00.1` - First user story in EPIC-00
- `US-02.5` - Fifth user story in EPIC-02
- `US-05.2` - Second user story in EPIC-05

### Technical Tasks: `TT-XX.Y`
- `XX` = Epic number (00-05)
- `Y` = Task sequence number (1, 2, 3, ...)

**Examples:**
- `TT-00.1` - First technical task in EPIC-00
- `TT-02.3` - Third technical task in EPIC-02
- `TT-05.1` - First technical task in EPIC-05

---

## Directory Structure

Tickets are organized hierarchically under `docs/tickets/`:

```
docs/tickets/EPIC-XX-name/
├── README.md                    # Epic overview with ticket list
├── user-stories/
│   ├── us-xx.1-kebab-case-title.md
│   ├── us-xx.2-kebab-case-title.md
│   └── ...
└── technical-tasks/
    ├── tt-xx.1-kebab-case-title.md
    ├── tt-xx.2-kebab-case-title.md
    └── ...
```

### Directory Naming
- Epic directory: `EPIC-XX-full-name` (kebab-case)
- Subdirectories: `user-stories/` and `technical-tasks/`

### File Naming

Convert ticket titles to kebab-case filenames:

**User Story Examples:**
- Title: "As an administrator, I need to manage tracking scripts"
- File: `us-02.1-manage-tracking-scripts.md`

- Title: "Create custom admin columns for tracking data"
- File: `us-02.2-create-custom-admin-columns.md`

**Technical Task Examples:**
- Title: "Implement GAP_CPT Class"
- File: `tt-02.1-implement-gap-cpt-class.md`

- Title: "Create admin JavaScript for dynamic UI"
- File: `tt-02.3-create-admin-javascript.md`

**Conversion Rules:**
1. Lowercase all letters
2. Replace spaces with hyphens
3. Remove special characters (except hyphens)
4. Keep the ticket ID prefix (e.g., `us-02.1-`)
5. Max 50 characters after ticket ID

---

## Ticket ID Mapping by Epic

| Epic ID | Epic Name | Branch | Example User Stories | Example Tasks |
|---------|-----------|--------|----------------------|---|
| EPIC-00 | Project Setup | `epic-00-project-setup` | US-00.1, US-00.2 | TT-00.1, TT-00.2 |
| EPIC-01 | Foundation | `epic-01-foundation` | US-01.1 - US-01.3 | TT-01.1 - TT-01.2 |
| EPIC-02 | Admin Interface | `epic-02-admin-interface` | US-02.1 - US-02.5 | TT-02.1 - TT-02.4 |
| EPIC-03 | Conflict Detection | `epic-03-conflict-detection` | US-03.1 - US-03.2 | TT-03.1 - TT-03.3 |
| EPIC-04 | Frontend Output | `epic-04-frontend-output` | US-04.1 - US-04.3 | TT-04.1 - TT-04.2 |
| EPIC-05 | Testing & Launch | `epic-05-testing-launch` | US-05.1 - US-05.2 | TT-05.1 - TT-05.2 |

---

## Ticket Properties

### Priority Levels

| Priority | Label | Meaning | Release Requirement |
|----------|-------|---------|----------------------|
| P0 | Critical | Must have for release | Yes, blocking |
| P1 | High | Should have for release | Yes, preferred |
| P2 | Medium | Nice to have | No, can defer |
| P3 | Low | Future enhancement | No, consider later |

### Story Points (User Stories Only)

Uses Fibonacci sequence for estimation:
```
1, 2, 3, 5, 8, 13
```

- **1-2 points**: Very simple, < 30 minutes
- **3 points**: Simple, < 1 hour
- **5 points**: Medium complexity, 1-2 hours
- **8 points**: Complex, 2-4 hours
- **13 points**: Very complex, 4+ hours

**Selection Tips:**
- Compare against similar completed stories
- Consider dependencies and blockers
- Account for testing and review time

### Time Estimates (Technical Tasks Only)

Format: `"X hours"` or `"X minutes"`

**Examples:**
- 30 min
- 1.5 hours
- 3 hours

Always include individual time estimates for each implementation task.

---

## Ticket Template Structure

All tickets use the standard template with these sections:

1. **Header**: Title with metadata (ID, Priority, Points, Time, Status)
2. **Description**: What needs to be done
3. **Acceptance Criteria**: How to verify it's done (checkbox list)
4. **Implementation Tasks**: Step-by-step tasks with time estimates
5. **Dependencies**: Blockers and related tickets
6. **Technical Details**: Code patterns, file locations, specifications
7. **Testing Requirements**: How to test this specific ticket
8. **Definition of Done**: Checklist for completion
9. **Notes**: Additional context

See `template-variables.md` for detailed variable reference.

---

## Ticket Relationships

### Blocking Dependencies (Sequent work required)
- User stories can be blocked by technical tasks
- Technical tasks can be blocked by other technical tasks
- Blockers must be listed in Dependencies section

**Format:**
```
**Blockers:**
- TT-02.1: Implement GAP_CPT Class
```

### Related Tickets (Can work in parallel)
- Referenced but don't block work
- Helpful context about related features

**Format:**
```
**Related Tickets:**
- TT-02.2: Implement GAP_Meta_Boxes Class (related functionality)
```

### Epic Dependencies
- User stories in EPIC-02 depend on EPIC-01 being complete
- EPIC-04 depends on EPIC-02 and EPIC-03

**Format in ticket:**
```
**Epic Dependencies:**
- Upstream: EPIC-01 (must be merged first)
- Downstream: EPIC-04 (depends on this epic)
```

---

## Status Values

- **Not Started**: Ticket not yet claimed
- **In Progress**: Currently being worked on (with % complete)
- **Completed**: All acceptance criteria met, tested, merged
- **On Hold**: Blocked or delayed (include reason)

---

## File Ownership

Tickets should clearly specify which files they modify:

**Format:**
```
## Files Modified
- `includes/class-gap-cpt.php` - Custom post type implementation
- `assets/css/admin.css` - Admin styling
```

Use relative paths from project root.

---

## Cross-Epic Coordination

When a ticket affects files managed by another epic:

1. **List** the epic and files in Dependencies
2. **Communicate** with the other epic owner
3. **Coordinate** merge order to avoid conflicts
4. **Test** integration after both epics merge

---

## Quality Checklist

Before finalizing a ticket:

- [ ] All acceptance criteria are testable and specific
- [ ] All tasks have realistic time estimates
- [ ] Dependencies are clearly stated
- [ ] Technical details are sufficient for implementation
- [ ] Testing requirements are specific and measurable
- [ ] File naming follows kebab-case convention
- [ ] No placeholder text like "TBD" in critical sections
- [ ] Ticket ID format is correct (US-XX.Y or TT-XX.Y)
- [ ] File path is correct (user-stories/ or technical-tasks/)

---

## Quick Reference

| Component | Format | Example |
|-----------|--------|---------|
| Ticket ID (US) | US-XX.Y | US-02.3 |
| Ticket ID (TT) | TT-XX.Y | TT-02.1 |
| Priority | P0, P1, P2, P3 | P0 |
| Story Points | 1, 2, 3, 5, 8, 13 | 5 |
| Time Estimate | "Xh", "Xmin" | "2 hours" |
| Filename | [id]-[kebab-case-title].md | us-02.3-configure-via-meta-fields.md |
| Directory | docs/tickets/EPIC-XX-name/[type]/ | docs/tickets/EPIC-02-admin-interface/user-stories/ |
