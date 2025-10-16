# Command Reference Guide

All available commands for managing GA Plugin development workflow.

## Available Commands

### /generate-tickets
**[CURRENT - Unified Ticket Generation]**

Generate all missing tickets across all epics automatically.

**Usage:**
```
/generate-tickets [options]
```

**Parameters:**
- `--epic=<number>` - Process specific epic (00-05) or "all" (default: all)
- `--force` - Regenerate existing tickets
- `--validate-only` - Check without generating
- `--verbose` - Show detailed output

**What it does:**
1. Scans all epics
2. Identifies missing tickets
3. Generates individual .md files
4. Validates quality
5. Updates status document

**Example:**
```
/generate-tickets                # Generate all missing tickets
/generate-tickets --epic=03      # Generate EPIC-03 only
/generate-tickets --validate-only # Check before generating
```

**Output Structure:**
```
docs/tickets/EPIC-03-conflict-detection/
├── user-stories/
│   ├── us-03.1-extract-tracking-ids.md
│   ├── us-03.2-auto-extract-on-save.md
│   └── ...
└── technical-tasks/
    ├── tt-03.1-conflict-detector-class.md
    ├── tt-03.2-meta-boxes-integration.md
    └── ...
```

**See Also:**
- [Ticket Automation Guide](../../TICKET-AUTOMATION-GUIDE.md)
- [Ticket System](../development/ticket-system.md)

---

### /break-down-epic (DEPRECATED)
**[Legacy - Use /generate-tickets instead]**

This command is deprecated. Use `/generate-tickets` for all ticket generation.

**Why:** The new unified system is faster, more reliable, and handles all cases.

**Migration:** Replace `/break-down-epic <number>` with `/generate-tickets --epic=<number>`

**Reference:** See `.claude/legacy/commands/break-down-epic.md` for old documentation

---

### /start-ticket
Begin work on a specific development ticket.

**Usage:**
```
/start-ticket <ticket-id>
```

**Parameters:**
- `<ticket-id>`: Ticket ID (e.g., US-02.1, TT-02.1)

**What it does:**
1. Locates ticket file
2. Displays ticket overview
3. Checks dependencies
4. Creates todo list from tasks
5. Sets up work context

**Example:**
```
/start-ticket US-02.1
```

**Output includes:**
- Ticket title and metadata
- Full acceptance criteria
- Implementation tasks
- Dependencies and blockers
- Definition of done checklist

**Before starting:**
- [ ] Read acceptance criteria
- [ ] Check dependencies (blockers must be done)
- [ ] Review implementation tasks
- [ ] Verify environment is ready

**See Also:**
- [Quality Checklist](../development/quality-checklist.md)
- [Ticket System](../development/ticket-system.md)

---

### /complete-ticket
Mark a ticket as complete after verifying all criteria.

**Usage:**
```
/complete-ticket <ticket-id>
```

**Parameters:**
- `<ticket-id>`: Ticket ID (e.g., US-02.1, TT-02.1)

**What it does:**
1. Verifies all acceptance criteria met
2. Checks definition of done
3. Updates ticket status
4. Records completion date
5. Creates git commit automatically
6. Shows next available tickets

**Example:**
```
/complete-ticket US-02.1
```

**Verification checklist:**
Before completing, ensure:
- [ ] All acceptance criteria checked off
- [ ] All implementation tasks completed
- [ ] Code follows WordPress standards
- [ ] Security checks passed
- [ ] Manual testing completed
- [ ] Code committed to git
- [ ] Documentation updated

**Auto-Generated Commit:**
- Includes ticket ID and title
- Extracts key details from ticket
- Sets Status: Completed
- Can be customized if needed

**See Also:**
- [Commit Format](../git/commit-format.md)
- [Quality Checklist](../development/quality-checklist.md)

---

### /epic-status
Display current status and progress of an epic.

**Usage:**
```
/epic-status <epic-number>
```

**Parameters:**
- `<epic-number>`: Epic number (00, 01, 02, 03, 04, 05)

**What it does:**
1. Reads all tickets in epic
2. Calculates completion %
3. Shows ticket breakdown
4. Identifies blockers
5. Displays available work
6. Provides recommendations

**Example:**
```
/epic-status 02
```

**Output includes:**
- Overall progress percentage
- User stories status
- Technical tasks status
- Blocking issues
- Epic dependencies
- Available tickets to work on
- Time estimates and spent

**Shows:**
- ✅ Completed tickets
- 🔄 In progress tickets
- ⏸️ Not started tickets
- ⚠️ Blocked tickets

**See Also:** [Ticket System](../development/ticket-system.md)

---

## Command Quick Reference

| Command | Purpose | Example |
|---------|---------|---------|
| `/generate-tickets` | Generate all tickets | `/generate-tickets` |
| `/start-ticket` | Begin ticket work | `/start-ticket US-02.1` |
| `/complete-ticket` | Mark ticket done | `/complete-ticket US-02.1` |
| `/epic-status` | Check epic progress | `/epic-status 02` |
| `/break-down-epic` | (Deprecated) | See `/generate-tickets` |

---

## Typical Workflow

1. **Check status:**
   ```
   /epic-status 02
   ```

2. **Start new ticket:**
   ```
   /start-ticket US-02.1
   ```

3. **Do the work:**
   - Follow implementation tasks
   - Test acceptance criteria
   - Write code

4. **Complete ticket:**
   ```
   /complete-ticket US-02.1
   ```

5. **Repeat for next ticket:**
   ```
   /epic-status 02
   /start-ticket US-02.2
   ```

---

## Integration with Agents

### epic-ticket-generator
- **Role:** Creates individual tickets from epics
- **Triggered by:** `/break-down-epic` command
- **Produces:** Individual ticket files (.md)
- **Reference:** [epic-ticket-generator](./.claude/agents/epic-ticket-generator.md)

### git-workflow-specialist
- **Role:** Manages all git operations
- **Triggered by:** `/complete-ticket` command
- **Produces:** Git commits, branches, PRs
- **Reference:** [git-workflow-specialist](./.claude/agents/git-workflow-specialist.md)

### local-testing-specialist
- **Role:** Runs comprehensive test suite
- **Triggered by:** Before PR creation
- **Produces:** Test reports, quality gate verification
- **Reference:** [local-testing-specialist](./.claude/agents/local-testing-specialist.md)

---

## Error Handling

### "Ticket not found"
- Verify ticket ID format (US-02.1 or TT-02.1)
- Check epic number is correct (00-05)
- Verify ticket exists with `/epic-status`
- Create missing ticket with `/generate-tickets`

### "Dependencies not complete"
- Check `/start-ticket` output for blockers
- Complete blocking tickets first
- Cannot start ticket if blocker not done

### "Cannot complete - criteria not met"
- Review `/start-ticket` acceptance criteria
- Verify all checkboxes can be marked done
- Test acceptance criteria manually
- Ask for clarification if unclear

---

## Advanced Usage

### Creating Reusable Tickets
1. Use `/generate-tickets` to generate tickets
2. Review with team before starting
3. Adjust if needed (edit .md files)
4. Then use `/start-ticket` to begin work

### Parallel Development
1. Multiple developers use `/start-ticket` simultaneously
2. Each works on different ticket
3. Each uses `/complete-ticket` when done
4. Git workflow handles merging

### Checking Progress
- Use `/epic-status XX` frequently to track
- Shows which tickets are available next
- Identifies blockers early
- Helps with resource planning

---

## Support

**Questions about commands?**
1. Run command with `-h` for help (if supported)
2. Review relevant documentation
3. Ask the team

**Found a bug?**
1. Document the issue
2. Try workaround if possible
3. Report to team with reproduction steps
