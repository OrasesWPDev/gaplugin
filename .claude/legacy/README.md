# Legacy & Deprecated Systems

**This directory contains deprecated/superseded systems.**

Files here are kept for historical reference and emergency fallback only.
**Do not use these for new workflows.**

---

## What's Here

### Agents (obsolete)
- **`agents/epic-ticket-generator.md`** - Deprecated by `ticket-automation-agent`
  - Old: Generated tickets one at a time
  - New: Unified system in `ticket-automation-agent`

### Commands (obsolete)
- **`commands/break-down-epic.md`** - Deprecated by `/auto-tickets`
  - Old: Generated tickets by epic number
  - New: Unified `/auto-tickets` command

---

## Why This Exists

During the GA Plugin development evolution, we consolidated multiple ticket generation approaches into a single, unified system:

### Before (Fragmented)
- Multiple agents doing similar work
- Multiple commands for same purpose
- Unclear which to use
- Maintenance nightmare

### After (Unified)
- Single agent: `ticket-automation-agent`
- Single command: `/auto-tickets`
- Clear workflows
- Easier to maintain

---

## Migration Path

If you're still using the old system:

### Old Way (Don't use)
```bash
/break-down-epic 03
```

### New Way (Use this)
```bash
/auto-tickets
```

Or for specific epic:
```bash
/auto-tickets --epic=03
```

---

## When to Reference Legacy Files

1. **Historical understanding** - Want to understand how old system worked
2. **Emergency fallback** - New system broken (emergency only)
3. **Code archaeology** - Studying evolution of the codebase

## When NOT to Use Legacy Files

❌ For new workflows
❌ For ticket generation
❌ For ongoing development
❌ For training new team members

---

## Current Active System

### Ticket Generation
**Command:** `/auto-tickets`
**Location:** `.claude/commands/auto-tickets.md`
**Agent:** `.claude/agents/ticket-automation-agent.md`

**Features:**
- Scans all epics
- Identifies missing tickets
- Generates individual files
- Validates quality
- Updates status
- ~15 seconds for all 28 tickets

### Documentation
**Location:** `docs/development/ticket-automation.md`
**Quick Ref:** `.claude/TICKET-AUTOMATION-QUICK-REF.md`
**Guide:** `TICKET-AUTOMATION-GUIDE.md`

---

## File Organization

```
.claude/
├── agents/                       (Active agents)
│   ├── ticket-automation-agent.md (CURRENT)
│   ├── wordpress-developer.md
│   ├── git-workflow-specialist.md
│   └── ...
├── commands/                     (Active commands)
│   ├── auto-tickets.md (CURRENT)
│   ├── work-epic.md
│   ├── epic-status.md
│   └── ...
└── legacy/                       (Deprecated systems)
    ├── agents/
    │   └── epic-ticket-generator.md
    └── commands/
        └── break-down-epic.md
```

---

## Questions?

- **How do I generate tickets?** → Use `/auto-tickets`
- **Where's the new system?** → `.claude/commands/auto-tickets.md`
- **Why was this changed?** → Consolidation and simplification
- **Can I still use the old way?** → Files preserved in `legacy/` but not recommended

---

## Version History

| System | Status | Location | Date Deprecated |
|--------|--------|----------|-----------------|
| epic-ticket-generator | Deprecated | `legacy/agents/` | 2025-10-16 |
| break-down-epic | Deprecated | `legacy/commands/` | 2025-10-16 |
| ticket-automation-agent | **Current** | `.claude/agents/` | Active |
| auto-tickets | **Current** | `.claude/commands/` | Active |

---

**Last Updated:** 2025-10-16
**Purpose:** Archive deprecated systems
**Status:** Maintained for reference only
