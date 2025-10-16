# /generate-tickets

Unified automated ticket generation for all epics - generates individual user story and technical task files.

**Usage:**
```bash
/generate-tickets [options]
```

**Parameters:**
- `--epic=<number>` or `--epic=all` - Which epic(s) to process (default: all)
- `--force` - Regenerate existing tickets (will overwrite)
- `--validate-only` - Check without generating files
- `--verbose` - Show detailed progress

**Examples:**

```bash
# Generate missing tickets for all epics
/generate-tickets

# Generate or regenerate only EPIC-03
/generate-tickets --epic=03

# Check what would be generated without creating files
/generate-tickets --validate-only

# Regenerate all tickets from scratch (will overwrite)
/generate-tickets --force

# Process multiple epics
/generate-tickets --epic=03 --epic=04 --epic=05

# Verbose output with all details
/generate-tickets --verbose
```

---

## What Happens

When you run `/generate-tickets`, this command:

1. **Parses your options** (which epic, force flag, etc.)
2. **Invokes ticket-generator-executor agent** via the Task tool
3. **Agent performs:**
   - Scans all EPIC.md files
   - Identifies missing ticket files
   - Generates individual `.md` files for each missing ticket
   - Validates quality of generated files
   - Updates EPIC-BREAKDOWN-STATUS.md
4. **Displays results** showing what was created

---

## Implementation Details

### How the Command Works

The `/generate-tickets` command is a thin wrapper that:

1. **Extracts parameters** from your input
2. **Builds a task prompt** for the ticket-generator-executor
3. **Invokes the agent** to perform the actual work
4. **Monitors progress** and displays real-time feedback
5. **Reports final results** with summary statistics

### Architecture

```
You run:
  /generate-tickets --epic=03 --verbose

↓ Command parses:
  epic: "03"
  verbose: true
  force: false
  validate_only: false

↓ Invokes via Task tool:
  ticket-generator-executor (with parameters)

↓ Agent performs:
  ✅ Scans EPIC-03/EPIC.md
  ✅ Finds 9 missing tickets
  ✅ Generates us-03.1 through us-03.6, tt-03.1 through tt-03.3
  ✅ Validates all 9 files
  ✅ Updates status document

↓ Returns results:
  Generated 9 tickets
  Quality: PASS
  Status: Updated
```

---

## Output & Results

### Successful Generation

```
═══════════════════════════════════════════════════════════
📊 TICKET GENERATION COMPLETE
═══════════════════════════════════════════════════════════

Scope: EPIC-03 only
Mode: Generate missing tickets
Status: ✅ SUCCESS

Generated:
  🆕 9 new tickets
  📁 docs/tickets/EPIC-03-conflict-detection/
     ├── user-stories/ (6 files: us-03.1 through us-03.6)
     └── technical-tasks/ (3 files: tt-03.1 through tt-03.3)

Quality Validation:
  ✅ 9/9 files pass
  ✅ Markdown syntax valid
  ✅ No placeholder text
  ✅ All sections complete

Status Updates:
  ✅ EPIC-BREAKDOWN-STATUS.md updated
  ✅ EPIC-03 marked as Ready for Dev

Time: 2.3 seconds
Manual Equivalent: ~30 minutes of work

Next Steps:
  1. Review: /epic-status 03
  2. Start working: /work-epic 03
  3. Begin first ticket: /start-ticket US-03.1
═══════════════════════════════════════════════════════════
```

### With `--validate-only`

Shows what WOULD be created without modifying any files:

```
═══════════════════════════════════════════════════════════
📋 VALIDATION ONLY (No files created)
═══════════════════════════════════════════════════════════

Would generate for EPIC-03:
  🆕 9 tickets total
     ├── 6 user stories
     └── 3 technical tasks

Quality check (if created):
  ✅ Would PASS all validations

Safe to run: /generate-tickets --epic=03
═══════════════════════════════════════════════════════════
```

---

## Behavior By Option

### Default: `--epic=all` (Generate Missing)
- **Scope:** All 6 epics (EPIC-00 through EPIC-05)
- **Action:** Only creates missing ticket files
- **Result:** EPIC-00,01,02 skip (already complete); EPIC-03,04,05 generate 28 tickets
- **Safety:** Non-destructive (doesn't overwrite existing files)
- **Time:** ~12-15 seconds

### Single Epic: `--epic=03`
- **Scope:** Only EPIC-03
- **Result:** Generates just 9 tickets for that epic
- **Time:** ~2-3 seconds
- **Use Case:** Working on specific epic and need fresh tickets

### Regenerate All: `--force`
- **Scope:** All tickets in all epics
- **Action:** Overwrites existing files with fresh generation from EPIC.md
- **Warning:** ⚠️ Will overwrite any manual edits to existing ticket files
- **Use Case:** When EPIC.md was updated and tickets need refresh
- **Confirmation:** Agent asks before overwriting

### Check First: `--validate-only`
- **Scope:** Depends on other flags (all by default)
- **Action:** Scans and checks without creating/modifying files
- **Result:** Shows exactly what would be created
- **Time:** ~5-10 seconds
- **Safety:** 100% safe - nothing is modified

### Debug: `--verbose`
- **Scope:** Can combine with any other flag
- **Action:** Shows detailed progress for each ticket
- **Output:** Line-by-line logging and debug info
- **Use Case:** Troubleshooting or understanding the process

---

## Common Workflows

### Generate All Tickets (First Run)
```bash
/generate-tickets
```
Generates all 28 missing tickets for EPIC-03, 04, 05 in ~15 seconds.

### Generate for Specific Epic
```bash
/generate-tickets --epic=03
```
Generates 9 tickets for EPIC-03 only in ~2 seconds.

### Check Before Generating
```bash
/generate-tickets --validate-only
```
Shows what would be created without any file modifications.

### Refresh After Epic Update
```bash
/generate-tickets --force --verbose
```
Regenerates all tickets from scratch with detailed logging.

---

## See Also

- [ticket-generator-executor](../../.claude/agents/ticket-generator-executor.md) - Implementation agent
- [ticket-automation-agent](../../.claude/agents/ticket-automation-agent.md) - Original automation design
- [Ticket System](../../docs/development/ticket-system.md) - Ticket format conventions
- [/epic-status](./epic-status.md) - View status of all tickets
- [/work-epic](./work-epic.md) - Start working on an epic
- [/start-ticket](./start-ticket.md) - Begin a specific ticket

---

**Created:** 2025-10-16
**Updated:** 2025-10-16
**Status:** ✅ Functional - Ready to use
