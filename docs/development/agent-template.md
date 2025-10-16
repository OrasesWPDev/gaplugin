# Agent Template

Use this template when creating new specialized agents. Replace all `[bracketed text]` with your content.

---

## Agent File Template

**File location:** `.claude/agents/[agent-name].md`

**Target length:** 40-100 lines

```markdown
# [Agent Name]

**Role:** [One-line description of what this agent does]
**Authority:** [What exclusive control/decisions does this agent have?]
**Model:** [Claude Sonnet 4.5 or Claude Haiku 4.5]
**Tools:** [Comma-separated: Read, Write, Edit, Bash, Task, TodoWrite]
**Color:** [Emoji] [Color Name] (e.g., 🔵 Blue)
**Status:** [Current status or operational mode]

---

## Overview

[2-3 sentence overview explaining what this agent does and why it's important. What problems does it solve?]

---

## Key Responsibilities

1. **[Responsibility Name]** - [Brief description of this responsibility]
2. **[Responsibility Name]** - [Brief description of this responsibility]
3. **[Responsibility Name]** - [Brief description of this responsibility]
4. **[Responsibility Name]** - [Brief description of this responsibility]
5. **[Responsibility Name]** - [Brief description of this responsibility]

---

## Reference Documentation

See detailed reference materials in docs/[domain]/:

- **[Document Title](../../docs/[domain]/[filename].md)** - [What this document covers]
- **[Document Title](../../docs/[domain]/[filename].md)** - [What this document covers]
- **[Document Title](../../docs/[domain]/[filename].md)** - [What this document covers]

---

## Integration with Other Agents

- **[Agent Name]:** [How they work together or coordinate]
- **[Agent Name]:** [How they work together or coordinate]
- **[Agent Name]:** [How they work together or coordinate]

---

## Best Practices

✅ **DO:**
- [Best practice 1]
- [Best practice 2]
- [Best practice 3]

❌ **DON'T:**
- [Anti-pattern or mistake 1]
- [Anti-pattern or mistake 2]
- [Anti-pattern or mistake 3]

---

**Version:** [1.0] | **Last Updated:** [YYYY-MM-DD] | **Authority:** [Area of oversight]
```

---

## Documentation File Templates

Create documentation files in `/docs/[domain]/`. Use these templates:

### Simple Reference Document

```markdown
# [Document Title]

[1-2 sentence summary]

---

## Quick Reference

[Optional: Table, checklist, or quick lookup]

---

## [Main Section]

[Detailed content]

---

## [Another Section]

[Detailed content]

---

**Last Updated:** [date] | **Owner:** [agent name] | **Related:** [other docs]
```

### Procedure Document

```markdown
# [Procedure Title]

[1-2 sentence summary of what this procedure does]

---

## Prerequisites

- [Prerequisite 1]
- [Prerequisite 2]
- [Prerequisite 3]

---

## Step-by-Step Procedure

### Step 1: [Step Title]
[Detailed instructions]

### Step 2: [Step Title]
[Detailed instructions]

### Step 3: [Step Title]
[Detailed instructions]

---

## Verification

- [ ] [Verification check 1]
- [ ] [Verification check 2]
- [ ] [Verification check 3]

---

## Troubleshooting

**Issue:** [Problem description]
**Solution:** [How to fix it]

---

**Last Updated:** [date] | **Owner:** [agent name]
```

### Examples Document

```markdown
# [Examples Title]

Examples and use cases for [what this covers].

---

## Example 1: [Title]

**Scenario:** [What situation this is for]

**Input:**
```
[Example input/code]
```

**Output:**
```
[Expected output/result]
```

**Explanation:** [Why this works]

---

## Example 2: [Title]

[Repeat pattern]

---

## Common Variations

- **Variation 1:** [What's different and why]
- **Variation 2:** [What's different and why]

---

**Last Updated:** [date] | **Owner:** [agent name]
```

---

## Filling Out the Template: Step by Step

### Step 1: Agent Name
```
Replace [Agent Name] with:
- Clear, descriptive name
- Use lowercase with hyphens for filenames: "my-agent-name"
- Examples: "epic-ticket-generator", "git-workflow-specialist"
```

### Step 2: Role
```
Replace [One-line description] with:
- Concise statement of what the agent does
- Example: "Manages creation and lifecycle of development tickets"
- Example: "Oversees version control workflow and PR creation"
- Should be one sentence, under 15 words
```

### Step 3: Authority
```
Replace [exclusive control] with:
- What decisions only this agent makes
- What actions only this agent can perform
- Example: "Exclusive control over epic generation and ticket validation"
- Example: "Exclusive authority over Git workflow operations"
- Establishes boundaries and prevents overlapping responsibilities
```

### Step 3.5: Model
```
Replace [assigned model] with:
- Claude Sonnet 4.5 (for complex reasoning and implementation tasks)
- Claude Haiku 4.5 (for fast validation and lightweight tasks)
- Exceptional approval required for other models
- Example: **Model:** Claude Sonnet 4.5
```

### Step 3.6: Tools
```
Replace [permissions list] with:
- Minimal set of tools needed for this agent's role
- Choose from: Read, Write, Edit, Bash, Task, TodoWrite
- Allocate only what's necessary (principle of least privilege)
- Examples:
  - Read-only agent: "Read, Bash" (no write access)
  - Developer agent: "Read, Write, Edit, Bash" (full code access)
  - Orchestrator agent: "Read, Write, TodoWrite, Task, Bash, Edit" (all tools needed)
```

### Step 3.7: Color
```
Replace [emoji color] with:
- Emoji + color name for visual identification
- One emoji + one color name, space-separated
- Examples: 🔵 Blue, 🟣 Purple, 🟢 Green, 🟡 Yellow, 🔴 Red, 🟠 Orange
- Use consistent colors across related agents
```

### Step 3.8: Status
```
Replace [operational status] with:
- Current operational mode or focus
- One sentence description of what the agent is actively doing
- Examples:
  - "Orchestrating GA Plugin development through SWARM methodology"
  - "Implementing WordPress plugins following WP coding standards"
  - "Enforces lean architecture - all reference material must be in /docs"
```

### Step 4: Overview
```
Replace [2-3 sentence overview] with:
- What problem does this agent solve?
- How does it fit into the larger system?
- Who uses this agent and when?
- 2-3 sentences maximum
```

### Step 5: Key Responsibilities
```
Replace [5 responsibility items] with:
- Main duties of this agent
- 3-5 items (not more)
- Format: **[Bold Title]** - Brief description
- Should cover the main aspects of the role
- Examples:
  1. **Ticket Generation** - Create individual user stories and tasks
  2. **Validation** - Verify acceptance criteria and quality standards
  3. **Documentation** - Maintain ticket documentation and relationships
```

### Step 6: Reference Documentation
```
Replace [Documentation references] with:
- Links to all /docs files this agent uses
- Relative paths starting with ../../docs/
- Format: **[Title](../../docs/path/file.md)** - Brief purpose
- Should be 2-4 files typically
- Examples:
  - **[Ticket System](../../docs/development/ticket-system.md)** - Format and structure reference
  - **[Quality Checklist](../../docs/development/quality-checklist.md)** - Validation requirements
```

### Step 7: Integration Points
```
Replace [Agent integration] with:
- List 2-3 other agents this works with
- Explain how they coordinate
- Format: **[Agent Name]:** Brief description of interaction
- Examples:
  - **Git Workflow Specialist:** Receives validated tickets for PR creation
  - **Local Testing Specialist:** Tests generated code before deployment
```

### Step 8: Best Practices
```
Replace [Best practices and anti-patterns] with:
- 3 items in DO section (positive practices)
- 3 items in DON'T section (what to avoid)
- Should be specific to this agent's role
- Examples:
  ✅ DO: Always validate before proceeding
  ❌ DON'T: Skip quality checks to save time
```

### Step 9: Metadata
```
Replace [Version], [Date], [Authority] with:
- Version: Start at 1.0
- Date: Current date (YYYY-MM-DD format)
- Authority: Area of responsibility
- Example: **Version:** 1.0 | **Last Updated:** 2025-10-16 | **Authority:** Ticket Generation
```

---

## Checklist: Before Finalizing

Agent file completion checklist (MANDATORY - All must pass):

- [ ] **ALL 6 required fields present:** Role, Authority, Model, Tools, Color, Status
- [ ] Agent name is clear and descriptive
- [ ] Role is one sentence, under 15 words
- [ ] Authority is clearly defined
- [ ] Model is Claude Sonnet 4.5 or Claude Haiku 4.5
- [ ] Tools allocation is minimal and necessary
- [ ] Color includes emoji + color name (e.g., 🔵 Blue)
- [ ] Status describes operational mode
- [ ] Overview is 2-3 sentences
- [ ] Key responsibilities: 3-5 items, bolded titles
- [ ] All reference docs linked with correct paths
- [ ] Integration points cover main collaborators
- [ ] Best practices are specific to this agent
- [ ] No examples in agent (all in `/docs`)
- [ ] No procedures in agent (all in `/docs`)
- [ ] File is 40-100 lines total
- [ ] Metadata is accurate and complete
- [ ] Formatting matches other agents
- [ ] No TBD or placeholder text
- [ ] agent-registry.json updated with all 9 fields (id, name, role, model, color, tools, authority, file, status)

Documentation file completion checklist:

- [ ] File has clear title
- [ ] Opening summary sentence (1-2 sentences)
- [ ] Organized with clear sections
- [ ] Examples included (if applicable)
- [ ] Cross-references to related docs
- [ ] Last updated date is current
- [ ] Owner is identified
- [ ] No orphaned sections or unclear content

---

## Example: Using This Template

**Request:** Create an agent to manage WordPress cron jobs

**Filled Template:**

```markdown
# WordPress Cron Manager

**Role:** Manages scheduled tasks and cron job execution in WordPress
**Authority:** Exclusive control over cron scheduling, task registration, and automation
**Status:** Operational - monitoring cron health

---

## Overview

Handles registration, scheduling, and management of WordPress cron jobs. Ensures scheduled tasks execute reliably, preventing common WordPress cron issues. Monitors cron health and provides diagnostics for administrators.

---

## Key Responsibilities

1. **Task Registration** - Register new cron jobs with proper intervals and callbacks
2. **Execution Monitoring** - Track cron execution and identify failures
3. **Health Diagnostics** - Provide cron health status and issue detection
4. **Task Management** - Update, pause, or remove scheduled tasks
5. **Documentation** - Maintain cron job documentation and schedules

---

## Reference Documentation

See detailed reference materials in docs/automation/:

- **[Cron Setup Guide](../../docs/automation/cron-setup.md)** - Task registration and configuration
- **[Cron Procedures](../../docs/automation/cron-procedures.md)** - Common cron operations
- **[Health Monitoring](../../docs/automation/cron-monitoring.md)** - Diagnostics and troubleshooting

---

## Integration with Other Agents

- **Task Scheduler:** Receives task definitions for scheduling
- **Monitoring Agent:** Provides health status and alerts
- **WordPress Admin:** Displays cron information to administrators

---

## Best Practices

✅ **DO:**
- Register cron tasks with descriptive names
- Use appropriate intervals for task frequency
- Add error handling to cron callbacks
- Monitor cron execution regularly
- Document all registered tasks

❌ **DON'T:**
- Use WordPress cron for time-critical tasks
- Create cron jobs without cleanup routines
- Schedule tasks at exactly the same time
- Ignore cron health warnings
- Modify cron schedules without backup

---

**Version:** 1.0 | **Last Updated:** 2025-10-16 | **Authority:** WordPress Automation
```

---

## When to Create New Documentation vs. Enhance Existing

**Create new doc when:**
- ✅ Different domain/subdirectory (e.g., docs/automation/ is new)
- ✅ Standalone procedure that doesn't fit existing docs
- ✅ Agent responsible for completely new functionality
- ✅ Documentation would be 1000+ lines if added to existing file

**Enhance existing doc when:**
- ✅ Related to existing agent's domain
- ✅ Extends existing procedures or reference material
- ✅ Falls naturally into existing doc structure
- ✅ <500 lines of new content

---

**Last Updated:** 2025-10-16 | **Owner:** subagent-creator | **Related:** [Agent Creation Guide](agent-creation.md)
