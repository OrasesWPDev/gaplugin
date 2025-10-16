# /complete-ticket

Mark a ticket as complete after verifying all criteria.

**Usage:**
```
/complete-ticket <ticket-id>
```

**Parameters:**
- `<ticket-id>`: Ticket ID (e.g., US-02.1, TT-02.1)

**What it does:**
1. Reads ticket file
2. Verifies all acceptance criteria met
3. Checks definition of done
4. Updates ticket status to "Completed"
5. Records completion date
6. Creates git commit automatically
7. Shows next available tickets

**Example:**
```
/complete-ticket US-02.1
```

**Verification before completing:**
- ✅ All acceptance criteria checked off
- ✅ All implementation tasks completed
- ✅ Code follows WordPress standards
- ✅ Security checks passed
- ✅ Manual testing completed
- ✅ Code committed to git
- ✅ Documentation updated

**Output shows:**
- Verification results
- Status change (Not Started → Completed)
- Completion date
- Epic progress percentage
- Next available tickets to work on

**Auto-generated commit:**
- Includes ticket ID and title
- Extracts key implementation details
- Sets Status: Completed
- Marks for git review

**See also:**
- [Quality Checklist](../docs/development/quality-checklist.md)
- [Commit Format](../docs/git/commit-format.md)
- [Command Reference](../docs/commands/reference.md)
