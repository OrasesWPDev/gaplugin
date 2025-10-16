# Agent Creation Guide

Complete reference for creating new specialized agents that follow the lean architecture pattern.

---

## Quick Start

1. Request new agent via `/create-agent` command or directly to subagent-creator
2. Define role, responsibilities, and reference materials needed
3. Subagent-creator handles documentation and agent file generation
4. Agent appears in `.claude/agents/{name}.md` with `/docs` structure created

---

## Lean Architecture Principles

### The Problem We Solve

Without lean architecture:
- Agents become 500+ line reference manuals
- Examples duplicated across multiple agents
- Information scattered - hard to maintain
- New agents written from scratch, no consistency

### The Solution: Lean Agents + Comprehensive Docs

**Agent File:** 40-100 lines
- Role and authority
- Key responsibilities
- Process overview
- Cross-references to `/docs`

**Documentation Files:** Organized by domain
- Examples and templates
- Detailed procedures
- Reference material
- Single source of truth for each topic

### Benefits

✅ Agents stay focused on role, not reference material
✅ Comprehensive documentation available when needed
✅ Easy to maintain - update docs once, all agents reference it
✅ New developers onboard faster with organized docs
✅ DRY principle: single source of truth
✅ Consistent structure across all agents

---

## Agent Structure

### File Location
```
.claude/agents/{agent-name}.md
```

### Lean Agent Format (40-100 lines)

```markdown
# [Agent Name]

**Role:** [One-line role description]
**Authority:** [What decisions/actions this agent has exclusive control over]
**Model:** [Claude Sonnet 4.5 or Claude Haiku 4.5]
**Tools:** [Comma-separated: Read, Write, Edit, Bash, Task, TodoWrite]
**Color:** [Emoji] [Color Name] (e.g., 🔵 Blue)
**Status:** [Current status or mode]

---

## Overview

[2-3 sentence overview of what agent does and why it matters]

---

## Key Responsibilities

1. **[Responsibility]** - [Brief description]
2. **[Responsibility]** - [Brief description]
3. **[Responsibility]** - [Brief description]

---

## Reference Documentation

See detailed reference materials in docs/:

- **[Doc Title](../../docs/path/file.md)** - [Purpose]
- **[Doc Title](../../docs/path/file.md)** - [Purpose]

---

## Integration with Other Agents

- **[Agent Name]:** [How they work together]
- **[Agent Name]:** [How they work together]

---

## Best Practices

✅ **DO:**
- [Practice 1]
- [Practice 2]

❌ **DON'T:**
- [Anti-pattern 1]
- [Anti-pattern 2]

---

**Version:** [version] | **Last Updated:** [date] | **Authority:** [area]
```

### What Goes WHERE

#### In Agent File (40-100 lines)
- Role statement
- Key responsibilities (5 items max)
- Integration with other agents
- Best practices (high-level)
- Cross-references to `/docs`

#### In `/docs` Files
- All examples (moved from agent)
- Detailed procedures (moved from agent)
- Templates and formats
- Reference material
- Specific instructions
- Background information

### Size Guidelines

- **Agent file:** 40-100 lines (no exceptions)
- **Full agent + reference docs:** Comprehensive coverage
- **Total agent size reduction:** 50-89% from original

---

## Documentation Standards

### Directory Structure

Organize docs by domain:

```
docs/
├── development/          # Development and tickets
│   ├── agent-creation.md
│   ├── ticket-system.md
│   ├── quality-checklist.md
│   └── ...
├── git/                  # Git and version control
│   ├── branching-strategy.md
│   ├── commit-format.md
│   └── ...
├── testing/              # Testing and quality assurance
│   ├── environment-setup.md
│   ├── test-procedures.md
│   └── ...
└── commands/             # Command reference
    └── reference.md
```

### Naming Convention

- **Files:** `kebab-case.md` (lowercase, hyphens)
- **Directories:** `domain-name` (lowercase, hyphens)
- **Links in agents:** Relative paths `../../docs/domain/file.md`

### Documentation File Format

```markdown
# [Title]

[1-2 sentence summary of what this doc covers]

---

## Quick Reference

[Quick summary table or checklist if applicable]

---

## [Section 1]

[Detailed content]

### [Subsection 1.1]
[Content]

---

## [Section 2]

[Detailed content]

---

## Common Issues & Solutions

- **Issue 1:** Solution description
- **Issue 2:** Solution description

---

**Last Updated:** [date] | **Owner:** [agent name] | **Related:** [other docs]
```

---

## Creating New Agents - Step by Step

### Phase 1: Requirements Gathering

Define what you need:

- [ ] Agent name (descriptive, lowercase with hyphens)
- [ ] Role (one line: "What does this agent do?")
- [ ] Authority (exclusive control over what?)
- [ ] Model (Claude Sonnet 4.5 or Claude Haiku 4.5)
- [ ] Tools (minimal permissions needed: Read, Write, Edit, Bash, Task, TodoWrite)
- [ ] Color (emoji + color name for visual identification, e.g., 🟣 Purple)
- [ ] Status (operational status description)
- [ ] Key responsibilities (3-5 main duties)
- [ ] Reference materials needed (list examples, procedures, templates)
- [ ] Integration points (which other agents does this work with?)

### Phase 2: Documentation Planning

Design the `/docs` structure:

- [ ] Identify documentation needed
- [ ] Determine which `/docs` subdirectories to use (or create new ones)
- [ ] Plan file names and organization
- [ ] Draft content outline for each doc file

Example for a hypothetical "WordPress Security Agent":
- `/docs/security/audit-procedures.md` - How to audit for vulnerabilities
- `/docs/security/nonce-validation.md` - Nonce verification procedures
- `/docs/security/sanitization-standards.md` - Input sanitization rules
- `/docs/security/escaping-rules.md` - Output escaping procedures

### Phase 3: Documentation Creation

Create all `/docs` files:

- [ ] Create subdirectory if needed
- [ ] Write comprehensive documentation
- [ ] Include examples and templates
- [ ] Add cross-references between related docs
- [ ] Verify accuracy and completeness

### Phase 4: Agent Creation

Write the lean agent file with REQUIRED 6-field header:

- [ ] Create `.claude/agents/{name}.md`
- [ ] **Add all 6 required fields:** Role, Authority, Model, Tools, Color, Status
- [ ] Write role statement
- [ ] List 3-5 key responsibilities
- [ ] Add cross-references to `/docs` files
- [ ] Add integration notes with other agents
- [ ] Add best practices section (high-level only)

### Phase 5: Quality Gate (MANDATORY - Block if failed)

Verify the agent meets standards:

- [ ] **ALL 6 required fields present:** Role, Authority, Model, Tools, Color, Status
- [ ] Model is Claude Sonnet 4.5 or Claude Haiku 4.5 (exceptional approval required otherwise)
- [ ] Tools allocation is minimal and necessary for role
- [ ] Color is emoji + color name (matches visual identification scheme)
- [ ] Agent file is 40-100 lines
- [ ] No examples in agent (all in `/docs`)
- [ ] No procedures in agent (all in `/docs`)
- [ ] All reference material linked from agent
- [ ] Cross-references use correct relative paths
- [ ] DRY principle: no duplication with existing agents
- [ ] No "TBD" or placeholder text
- [ ] Formatting matches other agents
- [ ] Authority statement is clear
- [ ] Update agent-registry.json with complete entry (id, name, role, model, color, tools, authority, file, status)

### Phase 6: Integration (Optional)

If agent needs direct user access:

- [ ] Create command in `.claude/commands/`
- [ ] Command references agent in documentation
- [ ] Command keeps lean with `/docs/commands/reference.md` entry

---

## Real Example: subagent-creator

### Requirements
- **Role:** Create new specialized agents
- **Authority:** Exclusive control over new agent creation and architecture
- **Responsibilities:** Design, document, create lean agents; enforce DRY; verify quality
- **Reference Materials:** Agent template, creation process, best practices

### Documentation Created
- `/docs/development/agent-creation.md` (this file) - Complete guide
- `/docs/development/agent-template.md` - Template for new agents

### Agent File
- `.claude/agents/subagent-creator.md` - 60 lines, lean definition

### Result
- Agent stays focused on role
- All detailed procedures in `/docs`
- Easy to update or enhance later
- Consistent with other agents

---

## Common Patterns

### Pattern 1: Domain Expert Agent

*Example: WordPress Security Specialist*

```
Directory: docs/security/
Files:
- audit-procedures.md
- vulnerability-database.md
- remediation-guide.md
- compliance-checklist.md

Agent file: 50-70 lines
References: All 4 docs
```

### Pattern 2: Process Executor Agent

*Example: Local Testing Specialist*

```
Directory: docs/testing/
Files:
- environment-setup.md
- test-procedures.md
- quality-gates.md
- test-templates.md

Agent file: 80-100 lines
References: All 4 docs
```

### Pattern 3: Workflow Manager Agent

*Example: Git Workflow Specialist*

```
Directory: docs/git/
Files:
- branching-strategy.md
- commit-format.md
- merge-procedures.md
- emergency-procedures.md

Agent file: 60-80 lines
References: All 4 docs
```

---

## Anti-Patterns (What NOT to Do)

❌ **Anti-Pattern 1: Everything in Agent**
- Large agent file (500+ lines)
- Examples in agent
- Procedures in agent
- Hard to maintain

✅ **Solution:** Extract to `/docs`

❌ **Anti-Pattern 2: No Documentation**
- Agent with vague descriptions
- No reference material
- Hard for new developers to use
- Inconsistent with existing agents

✅ **Solution:** Create comprehensive `/docs` files

❌ **Anti-Pattern 3: Duplicated Information**
- Same procedure in multiple agents
- Examples repeated across files
- Hard to update consistently

✅ **Solution:** Single source of truth in `/docs`

❌ **Anti-Pattern 4: Wrong Directory**
- Examples in agent instead of `/docs`
- Procedures mixed with role definition
- Reference material scattered

✅ **Solution:** Organized `/docs` structure by domain

---

## Migration Guide: Existing Agents

If refactoring an existing agent:

1. **Audit Phase** - Identify all content types in agent
   - Role/responsibility statements ✅ (keep in agent)
   - Examples/templates (move to `/docs`)
   - Procedures/instructions (move to `/docs`)
   - Reference material (move to `/docs`)

2. **Create Docs** - Write new `/docs` files with extracted content

3. **Refactor Agent** - Rewrite agent to 40-100 lines with cross-references

4. **Verify** - Run quality gate checks

5. **Update Cross-References** - Ensure all docs link to updated agent

---

## Troubleshooting

**Q: How many `/docs` files should an agent have?**
A: Typically 2-4 main documentation files. Each covers a major aspect of the agent's responsibilities.

**Q: What if my agent needs to reference another agent's docs?**
A: Use cross-references. For example, "See [Git Workflow](../../docs/git/branching-strategy.md) for branch details."

**Q: Can agents be created without commands?**
A: Yes. Not all agents need direct user commands. Some are used by other agents internally.

**Q: How do I know if something should be in `/docs` vs. in agent?**
A: Ask: "Would a new developer need to reference this?" If yes → `/docs`. If no → agent only.

**Q: What if `/docs` structure doesn't fit my new agent?**
A: Create new subdirectories as needed. Keep organization consistent with existing pattern.

---

**Last Updated:** 2025-10-16 | **Owner:** subagent-creator | **Related:** [Agent Template](agent-template.md), [Ticket System](ticket-system.md)
