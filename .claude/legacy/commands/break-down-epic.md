# /break-down-epic

Break down an epic into individual development tickets.

**Usage:**
```
/break-down-epic <epic-number>
```

**Parameters:**
- `<epic-number>`: Epic number (00, 01, 02, 03, 04, 05)

**What it does:**
1. Reads epic document
2. Creates epic ticket directory
3. Generates individual user story tickets
4. Generates individual technical task tickets
5. Creates epic README with overview

**Example:**
```
/break-down-epic 02
```

**Output:**
```
docs/tickets/EPIC-02-admin-interface/
├── README.md
├── user-stories/
│   ├── us-02.1-title.md
│   ├── us-02.2-title.md
│   └── ...
└── technical-tasks/
    ├── tt-02.1-title.md
    ├── tt-02.2-title.md
    └── ...
```

**See also:**
- [Ticket System](../docs/development/ticket-system.md)
- [Ticket Examples](../docs/development/ticket-examples.md)
- [Command Reference](../docs/commands/reference.md)
