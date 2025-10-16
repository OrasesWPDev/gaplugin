# Ticket Template Variables Reference

This document defines all variables used in the ticket template (`docs/templates/ticket-template.md`).

## Variable Reference

### Core Identifiers

#### `{{TICKET_ID}}`
The unique identifier for this ticket.
- **Format**: `US-XX.Y` or `TT-XX.Y`
- **Example**: `US-02.1` or `TT-02.3`
- **Where**: Appears in filename and document header
- **Used in**: Commit messages, PR descriptions, cross-references

#### `{{TICKET_TITLE}}`
Human-readable title of the ticket (without the ID).
- **Format**: Clear, descriptive title
- **Example**: `Manage Tracking Scripts via Custom Post Type`
- **User Stories**: Start with role perspective (optional)
- **Technical Tasks**: Action-oriented

#### `{{EPIC_ID}}`
The epic this ticket belongs to.
- **Format**: `EPIC-XX`
- **Example**: `EPIC-02`
- **Reference**: Use from `docs/tickets/EPIC-XX-name/`

#### `{{EPIC_NAME}}`
Full name of the epic.
- **Format**: Full epic title
- **Example**: `Admin Interface`
- **Matches**: Directory name `EPIC-XX-[name]`

### Classification

#### `{{TICKET_TYPE}}`
Type of ticket being created.
- **Values**: `"User Story"` or `"Technical Task"`
- **User Story**: Represents feature from end-user perspective
- **Technical Task**: Represents implementation work without direct user value

#### `{{PRIORITY}}`
Priority level for this ticket.
- **Values**: `P0`, `P1`, `P2`, `P3`
- **P0**: Critical - must have for release
- **P1**: High - should have for release
- **P2**: Medium - nice to have
- **P3**: Low - future enhancement

#### `{{STORY_POINTS}}`
Estimation points (User Stories only).
- **Values**: `1`, `2`, `3`, `5`, `8`, `13` (Fibonacci)
- **Empty/TBD**: For technical tasks (use `{{ESTIMATED_TIME}}` instead)
- **Reference**: See `ticket-system.md` for estimation guidelines

#### `{{ESTIMATED_TIME}}`
Time estimate for completion (Technical Tasks only).
- **Format**: `"X hours"` or `"X minutes"`
- **Examples**: `"30 min"`, `"1.5 hours"`, `"3 hours"`
- **User Stories**: Can also use "X hours total" for overall estimate
- **Include**: Time for implementation, testing, and review

### Description

#### `{{DESCRIPTION}}`
Detailed description of what needs to be done.
- **User Stories**: Include user perspective and goals
- **Technical Tasks**: Include technical requirements and context
- **Format**: 1-3 paragraphs, markdown supported
- **Include**: Why this is needed, any context

**Example (User Story):**
```
As an administrator, I need to manage tracking scripts through a
custom post type so that I can maintain tracking scripts alongside
other WordPress content.

This includes creating a CPT that appears in the admin menu and
provides a user-friendly interface for managing Google Analytics
and GTM tracking codes.
```

**Example (Technical Task):**
```
Implement the GAP_CPT class to register the tracking_script
custom post type. This class will handle CPT registration,
admin menu configuration, and custom columns.

The CPT should be restricted to administrators only and use
the dashicons-analytics icon for visual consistency.
```

### Criteria and Requirements

#### `{{ACCEPTANCE_CRITERIA}}`
Checkbox list of conditions that must be met for the ticket to be complete.
- **Format**: Markdown checkbox list
- **Each item**: Specific, testable, and verifiable
- **Examples**:
  - `- [ ] Custom post type "tracking_script" is registered`
  - `- [ ] Admin menu appears with correct icon and label`
  - `- [ ] Only administrators can access the CPT`

#### `{{TASKS}}`
List of implementation tasks with time estimates.
- **Format**: Numbered checklist with estimates
- **Each task**: Should take 15min - 2 hours
- **Break down**: Large tasks into smaller steps
- **Include time**: Always add estimated time per task

**Format:**
```
1. [ ] Create GAP_CPT class skeleton (15 min)
   - Add class docblock
   - Set up singleton pattern
   - Add __construct() method stub

2. [ ] Implement register_post_type() method (45 min)
   - Set post type arguments
   - Configure capabilities
   - Set admin menu position and icon

3. [ ] Add custom admin columns (30 min)
   - Add column headers
   - Render column data
```

### Dependencies

#### `{{BLOCKERS}}`
List of tickets that must be completed before this one.
- **Format**: Bullet list with ticket IDs
- **Include reason**: Why it's blocking
- **Empty**: `- None` if no blockers

**Example:**
```
- TT-02.1: Implement GAP_CPT Class (needed for meta box implementation)
- EPIC-01: Foundation must be merged (class autoloader required)
```

#### `{{RELATED_TICKETS}}`
Tickets related but not blocking.
- **Format**: Bullet list with ticket IDs
- **Use for**: Context, related features, parallel work
- **Empty**: `- None` if no related tickets

**Example:**
```
- TT-02.2: Implement GAP_Meta_Boxes Class (same epic)
- US-02.2: Custom Admin Columns (uses same CPT)
```

### Technical Details

#### `{{TECHNICAL_DETAILS}}`
Code patterns, file locations, and technical specifications.
- **Include**: Class names, functions, files to create/modify
- **Include**: WordPress hooks, filters, actions
- **Include**: Database structure, meta keys
- **Code examples**: Yes, include relevant snippets
- **Empty**: `- TBD` only if truly unknown

**Example:**
```
**Files to Create:**
- `includes/class-gap-cpt.php` - CPT registration class

**Files to Modify:**
- `ga-plugin.php` - Add include for GAP_CPT class

**Class Structure:**
```php
class GAP_CPT {
    private static $instance = null;

    public static function get_instance() { ... }
    public function register_post_type() { ... }
}
```

**WordPress Hooks:**
- `init` - Hook for registering post type
- `manage_tracking_script_posts_columns` - Add custom columns
- `manage_tracking_script_posts_custom_column` - Render columns

**Meta Keys:**
- `_gap_script_content` - Script code
- `_gap_placement` - Where to output (head, body_top, body_bottom, footer)
```

### Testing

#### `{{TESTING_REQUIREMENTS}}`
How to verify this ticket is complete and working.
- **Be specific**: Not just "test it works"
- **Include**: Steps to reproduce, expected outcomes
- **Include**: Edge cases to test
- **Include**: Security tests if applicable

**Format Example:**
```
**Manual Testing Steps:**
1. Navigate to Tracking Scripts menu in WordPress admin
2. Click "Add New" button
   - Verify form displays with all meta fields
   - Verify save button is present
3. Fill in test data (GA4 tracking code)
4. Click "Publish"
   - Verify post saves successfully
   - Verify custom columns display tracking ID

**Acceptance Tests:**
- [ ] CPT menu appears in WordPress admin
- [ ] Only administrators see the menu
- [ ] Add New page loads without errors
- [ ] Meta fields are editable
- [ ] Fields persist after save
```

### Completion

#### `{{DEFINITION_OF_DONE}}`
Checklist that MUST be complete before marking ticket done.
- **Format**: Checkbox list
- **Never skip**: These are quality gates
- **All must pass**: No exceptions

**Standard items:**
```
- [ ] All acceptance criteria met and tested
- [ ] All implementation tasks completed
- [ ] Code follows WordPress coding standards
- [ ] No debug code (var_dump, print_r, die)
- [ ] Security checks passed (nonces, capabilities, sanitization)
- [ ] Code committed with proper message
- [ ] Ticket updated with completion status
- [ ] All files follow project conventions
- [ ] No merge conflicts
- [ ] Ready for code review
```

### Metadata

#### `{{NOTES}}`
Additional context, references, or clarifications.
- **Optional**: Can be empty if nothing to add
- **Format**: Bullet points or paragraphs
- **Use for**: Links, context, edge cases, future considerations

**Examples:**
```
- This feature is blocked on GitHub issue #42
- Consider refactoring after EPIC-03 is complete
- Performance concerns if tracking 100+ scripts
- Related to legacy tracking code in theme (will remove in EPIC-05)
```

#### `{{DATE}}`
Current date in ISO format.
- **Format**: `YYYY-MM-DD`
- **Example**: `2025-10-16`
- **Set on**: Ticket creation and each update

---

## Filling Variables: Checklist

When creating a new ticket, ensure all required variables are filled:

- [ ] `{{TICKET_ID}}` - Correct format (US-XX.Y or TT-XX.Y)
- [ ] `{{TICKET_TITLE}}` - Clear, descriptive title
- [ ] `{{EPIC_ID}}` - Valid epic ID
- [ ] `{{EPIC_NAME}}` - Full epic name
- [ ] `{{TICKET_TYPE}}` - "User Story" or "Technical Task"
- [ ] `{{PRIORITY}}` - P0, P1, P2, or P3
- [ ] `{{STORY_POINTS}}` - For user stories (1-13)
- [ ] `{{ESTIMATED_TIME}}` - For technical tasks ("X hours" or "X min")
- [ ] `{{DESCRIPTION}}` - 1-3 sentences explaining what and why
- [ ] `{{ACCEPTANCE_CRITERIA}}` - Specific, testable checklist
- [ ] `{{TASKS}}` - Implementation steps with time estimates
- [ ] `{{BLOCKERS}}` - List any blocking tickets or epics
- [ ] `{{RELATED_TICKETS}}` - List related but non-blocking tickets
- [ ] `{{TECHNICAL_DETAILS}}` - File names, classes, functions, hooks
- [ ] `{{TESTING_REQUIREMENTS}}` - How to verify it works
- [ ] `{{DEFINITION_OF_DONE}}` - Completion checklist
- [ ] `{{NOTES}}` - Any additional context (can be empty)
- [ ] `{{DATE}}` - Today's date in YYYY-MM-DD format

**Never use placeholder text like "TBD" in required fields** - if you're unsure, discuss with the team first.
