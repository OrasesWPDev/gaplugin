# /start-ticket

Begin work on a specific development ticket.

**Usage:**
```
/start-ticket <ticket-id>
```

**Parameters:**
- `<ticket-id>`: Ticket ID (e.g., US-02.1, TT-02.1)

**What it does:**
1. Locates ticket file
2. Displays ticket details and metadata
3. Shows acceptance criteria and tasks
4. Checks for blocking dependencies
5. Creates todo list from tasks
6. Sets up work context

**Example:**
```
/start-ticket US-02.1
```

**Checks before starting:**
- ✅ All blocking tickets completed
- ✅ Related epic dependencies satisfied
- ✅ Development environment ready
- ✅ No conflicting work in progress

**Output includes:**
- Ticket title and metadata (priority, story points, time)
- Full acceptance criteria checklist
- Implementation tasks with estimates
- Blocking dependencies
- Definition of done checklist

**Next steps after starting:**
- Follow implementation tasks in order
- Verify acceptance criteria as you work
- Test completion before using /complete-ticket

**See also:**
- [Ticket System](../docs/development/ticket-system.md)
- [Quality Checklist](../docs/development/quality-checklist.md)
- [Command Reference](../docs/commands/reference.md)
