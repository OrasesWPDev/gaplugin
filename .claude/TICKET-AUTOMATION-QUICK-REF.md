# Ticket Automation - Quick Reference Card

Keep this handy for quick command reference.

## One Command to Rule Them All

```bash
/generate-tickets
```

Generates all 28 missing tickets in ~15 seconds.

---

## Common Commands

| Command | What It Does |
|---------|-------------|
| `/generate-tickets` | Generate all missing tickets |
| `/generate-tickets --epic=03` | Generate EPIC-03 only |
| `/generate-tickets --force` | Regenerate everything |
| `/generate-tickets --validate-only` | Check without creating |
| `/generate-tickets --verbose` | Show detailed output |

---

## What Gets Generated

After running `/generate-tickets`, you'll have:

```
docs/tickets/EPIC-03-conflict-detection/
├── EPIC.md                          (existing)
├── README.md                        (existing)
├── user-stories/
│   ├── us-03.1-extract-tracking-ids.md
│   ├── us-03.2-auto-extract-on-save.md
│   ├── us-03.3-admin-column-display.md
│   ├── us-03.4-admin-warnings.md
│   ├── us-03.5-html-scanning.md
│   └── us-03.6-conflict-logging.md
└── technical-tasks/
    ├── tt-03.1-conflict-detector-class.md
    ├── tt-03.2-meta-boxes-integration.md
    └── tt-03.3-admin-notices.md
```

Same for EPIC-04 and EPIC-05.

---

## Files You'll Be Using

| File | Purpose |
|------|---------|
| `.claude/commands/generate-tickets.md` | Main command |
| `.claude/agents/ticket-automation-agent.md` | Automation engine |
| `docs/development/ticket-automation.md` | Full documentation |
| `TICKET-AUTOMATION-GUIDE.md` | Implementation guide |

---

## After Generation

```bash
# Check status
/epic-status 03

# View a ticket
cat docs/tickets/EPIC-03-conflict-detection/user-stories/us-03.1-extract-tracking-ids.md

# Start development
/work-epic 03

# Reference in git commit
git commit -m "feat(US-03.1): Extract GA4 and GTM IDs"
```

---

## Workflow

```
/generate-tickets
     ↓
Generate all 28 tickets
     ↓
/epic-status 03
     ↓
View available work
     ↓
/work-epic 03
     ↓
Development begins
```

---

## Troubleshooting

**Nothing generated?**
```bash
# Verbose mode to see what happened
/generate-tickets --verbose

# Check individual ticket status
ls docs/tickets/EPIC-03-conflict-detection/user-stories/
```

**Want to regenerate?**
```bash
/generate-tickets --force
```

**Just want to check?**
```bash
/generate-tickets --validate-only
```

---

## Time Saved

- Manual generation per epic: ~70 minutes
- Automated generation for all: ~15 seconds
- **Total savings: 6+ hours**

---

## Remember

✅ Use `/generate-tickets` to generate all tickets
✅ All validation is automatic
✅ Status document updates automatically
✅ Safe to run multiple times
✅ Integrates with existing workflow

---

**Ready to generate?** → `/generate-tickets`
