# /create-agent

Create a new specialized agent with supporting documentation.

**Usage:**
```
/create-agent <agent-name> "<role>" "<responsibility-1>" "<responsibility-2>" ...
```

**Parameters:**
- `<agent-name>` - Agent name in lowercase with hyphens (e.g., wordpress-security-agent)
- `<role>` - One-line role description in quotes
- `<responsibility-N>` - Key responsibilities in quotes (3-5 items)

**What it does:**
1. Validates agent naming and requirements (Role, Authority, Model, Tools, Color, Status)
2. Plans documentation structure and `/docs` directories
3. Gathers reference material needs
4. Generates comprehensive documentation files
5. Creates lean agent file with REQUIRED 6-field header (Role, Authority, Model, Tools, Color, Status)
6. Updates agent-registry.json with complete entry
7. Verifies architecture and quality gates

**Example:**
```
/create-agent wordpress-cache-manager "Manages WordPress caching strategies and performance" "Cache invalidation on content updates" "Performance monitoring and reporting" "Cache warmup scheduling"
```

**Output shows:**
- Planned `/docs` directory structure
- Documentation files created with content summaries
- Agent file created at `.claude/agents/{name}.md` with 6 required fields
- Agent registry updated with: id, name, role, model, color, tools, authority, file, status
- Verification: All 6 fields present, architecture compliance, size <100 lines, cross-reference check
- Next steps: Review and integrate agent

**Integration:**
Works with subagent-creator to ensure all agents follow the lean architecture pattern. All agents MUST have:
- ✅ **Role** - One-line description
- ✅ **Authority** - Exclusive boundaries
- ✅ **Model** - Claude Sonnet 4.5 or Claude Haiku 4.5
- ✅ **Tools** - Minimal necessary permissions (Read, Write, Edit, Bash, etc.)
- ✅ **Color** - Emoji + color name for visual identification (e.g., 🔵 Blue)
- ✅ **Status** - Operational status description

All reference material extracted to `/docs` with single source of truth.

**See also:**
- [Agent Creation Guide](../docs/development/agent-creation.md)
- [Agent Template](../docs/development/agent-template.md)
- [Command Reference](../docs/commands/reference.md)
