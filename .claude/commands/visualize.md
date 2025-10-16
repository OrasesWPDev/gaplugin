# /visualize

Display real-time terminal dashboard for multi-agent orchestration.

Shows live status of all agents, epic progress, system health, and recent activity in a beautiful terminal UI.

**Usage:**
```
/visualize [options]
```

**Options:**
- `--once` - Show dashboard once and exit (don't live-update)
- `--refresh-rate <seconds>` - Update interval in seconds (default: 5)
- `--repo-root <path>` - Repository root directory (default: current)

**What It Shows:**

The dashboard displays four real-time panels:

**📊 Epic Progress (Top Left)**
- All 6 epics with individual progress bars
- Completion status (completed/total tasks)
- Status indicators: ✅ Complete, 🔄 Active, ⏳ Pending
- Example:
  ```
  EPIC-00 ████████████████████ 100% (7/7)   ✅ COMPLETE
  EPIC-01 ████████░░░░░░░░░░░░  44% (4/9)   🔄 ACTIVE
  EPIC-02 ░░░░░░░░░░░░░░░░░░░░   0% (0/8)   ⏳ PENDING
  ```

**🤖 Agent Status (Bottom Left)**
- All 7 active agents with real-time status
- Current task per agent
- Progress percentage
- Status colors: 🟢 Active, 🔵 Working, 🟡 Idle, 🔴 Monitoring
- Table format for easy scanning

**📈 System Metrics (Top Right)**
- Total tasks and completion progress
- Quality gates passed/total
- Success rate percentage
- Estimated time remaining
- Active agents count
- Example:
  ```
  Total Tasks:       52
  Completed:         4/52
  Progress:          7.7%
  Quality Gates:     4/4 ✅
  ETA:               ~27 hours
  ```

**📜 Recent Activity (Bottom Right)**
- Last 8 commits with timestamps and messages
- Activity emoji indicators:
  - ✅ Completions
  - 🎉 New features
  - 🔧 Fixes
  - 📝 Documentation
- Live feed of recent actions

**Examples:**

```bash
# Start live dashboard (updates every 5 seconds)
/visualize

# Show dashboard once and exit (good for CI/CD logs)
/visualize --once

# Custom refresh rate (2 seconds - very frequent updates)
/visualize --refresh-rate 2

# Show dashboard from different repository
/visualize --repo-root ~/projects/my-plugin
```

**Output Example:**

```
╔════════════════════════════════════════════════════════════════╗
║ 🤖 AGENT EXECUTION DASHBOARD                                  ║
║ Updated: 2025-10-16 17:45:32                                  ║
╚════════════════════════════════════════════════════════════════╝

┌────────────────────────────────┬────────────────────────────────┐
│ 📊 Epic Progress               │ 📈 System Metrics              │
├────────────────────────────────┼────────────────────────────────┤
│ EPIC-00 ███████████ 100% ✅    │ Total Tasks:       52          │
│ EPIC-01 ████░░░░░░░  44% 🔄    │ Completed:         4/52        │
│ EPIC-02 ░░░░░░░░░░░░   0% ⏳    │ Progress:          7.7%        │
│ EPIC-03 ░░░░░░░░░░░░   0% ⏳    │ Quality Gates:     4/4 ✅      │
│ EPIC-04 ░░░░░░░░░░░░   0% ⏳    │ ETA:               ~27 hours   │
│ EPIC-05 ░░░░░░░░░░░░   0% 🔒    │ Active Agents:     7/7         │
├────────────────────────────────┼────────────────────────────────┤
│ 🤖 Agent Status                │ 📜 Recent Activity             │
├────────────────────────────────┼────────────────────────────────┤
│ epic-orchestrator      🔵 Work  │ ✅ 17:42:15 US-01.3 complete  │
│ wordpress-developer    🟢 Idle  │ 📝 17:42:20 Commit 3a7b9f1     │
│ standards-validator    🟢 Idle  │ ✅ 17:42:25 Validation pass   │
│ git-workflow           🟢 Idle  │ 🎉 17:42:30 US-01.4 started   │
│ local-testing          🟢 Idle  │ 🔄 17:45:12 US-01.4 67%       │
│ subagent-orchestrator  🟢 Idle  │ 📝 17:45:20 Dashboard update  │
│ agent-health-monitor   🔴 Watch │ 🟢 17:45:32 All systems OK    │
└────────────────────────────────┴────────────────────────────────┘

⏱ Uptime: 45 min | 🔄 Live Updates Every 5s | Press Ctrl+C to Exit
```

**Features:**

✅ **Real-time Updates** - Live data refresh every 5 seconds (configurable)
✅ **Multi-Panel Layout** - Organized view of all critical metrics
✅ **Color Coded** - Status indicators with colors and emojis
✅ **Progress Bars** - Visual progress for all epics
✅ **Activity Feed** - Recent commits and completions
✅ **Keyboard Friendly** - Ctrl+C to exit, no interaction needed
✅ **CI/CD Ready** - `--once` flag for non-interactive use

**Technical Details:**

- Built with **Rich library** (Python) for beautiful terminal output
- Reads from `MASTER-DEVELOPMENT-PLAN.md` for task status
- Polls `.claude/agents/agent-registry.json` for agent definitions
- Uses `git log` for recent activity
- Runs in local terminal (no server required)
- Updates every 5 seconds (or custom interval)

**Integration:**

Works seamlessly with:
- `/work-epic` - Shows live progress of epic execution
- `/monitor-agents` - Complements health monitoring
- `/work-status` - Alternative detailed status view
- `/epic-status` - Per-epic deep dive

**See Also:**

- [Agent Health Monitor](../agents/agent-health-monitor.md) - Failure detection
- [Epic Orchestrator Executor](../agents/epic-orchestrator-executor.md) - Execution engine
- [Master Development Plan](../docs/development/MASTER-DEVELOPMENT-PLAN.md) - Task specifications
