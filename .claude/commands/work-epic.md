# /work-epic

Orchestrate complete epic execution from analysis through PR creation using SWARM methodology.

**Usage:**
```
/work-epic <epic-number> [--checkpoint=<option>]
```

**Parameters:**
- `<epic-number>` - Epic number (00-05)
- `--checkpoint=none` - Full automation (default)
- `--checkpoint=manual` - Pause after every ticket
- `--checkpoint=US` - Pause after each user story
- `--checkpoint=3` - Pause every 3 tickets
- `--checkpoint=5` - Pause every 5 tickets

**What It Does:**

1. **Preparation Phase** - Create epic branch, load ticket definitions from MASTER-DEVELOPMENT-PLAN.md
2. **Development Phase** - Parallel development with SWARM methodology
3. **Quality Assurance** - Run all quality gates
4. **PR Creation** - Create PR with test reports
5. **Monitoring** - Track progress with real-time updates

**Examples:**

```bash
# Full automation (fastest)
/work-epic 01

# Pause after every user story
/work-epic 01 --checkpoint=US

# Manual control - pause after every ticket
/work-epic 01 --checkpoint=manual

# Every 3 tickets
/work-epic 01 --checkpoint=3
```

**Output Shows:**
- Epic analysis and dependency graph
- Parallelization opportunities and time savings
- Assigned agents and their roles
- Execution phases with tickets
- Quality gates for each phase
- Real-time progress updates
- Checkpoint decisions and options
- Final PR summary with test results

**See also:**
- [Orchestration Workflow](../docs/orchestration/orchestration-workflow.md)
- [Checkpoint Guide](../docs/orchestration/checkpoint-guide.md)
- [Quality Gates](../docs/orchestration/quality-gates.md)
