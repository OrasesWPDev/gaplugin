# 🚀 START HERE - GA Plugin Development Guide

**Welcome!** This guide will walk you through developing the GA Plugin using a structured, multi-session approach with specialized AI agents.

---

## 📋 Table of Contents

1. [Quick Overview - How This Works](#quick-overview---how-this-works)
2. [Visual Workflow](#visual-workflow)
3. [Step-by-Step: Starting Development](#step-by-step-starting-development)
4. [Understanding the Phases](#understanding-the-phases)
5. [Using Agents and Commands](#using-agents-and-commands)
6. [What to Expect at Each Phase](#what-to-expect-at-each-phase)
7. [Troubleshooting](#troubleshooting)

---

## Quick Overview - How This Works

### The Big Picture

You're building a WordPress plugin using a **phased development approach** with **specialized AI agents** that focus on specific tasks. Think of it like a construction crew where each person has a specific expertise.

### Key Concepts

1. **5 Phases** - Your project is broken into 5 sequential phases (like building floors of a house)
2. **Specialized Agents** - AI assistants with specific expertise (CPT specialist, security scanner, etc.)
3. **Git Workflow** - Each phase gets its own branch, preventing conflicts
4. **Slash Commands** - Simple commands like `/start-phase 1` that automate complex tasks

### The Files You Need to Know

```
gaplugin/
├── START-HERE.md                    ← YOU ARE HERE
├── GA-PLUGIN-PLAN.md                ← Master plan (reference only)
├── .claude/
│   ├── CLAUDE.md                    ← Orchestration hub
│   ├── agents/                      ← AI specialists (6 experts)
│   └── commands/                    ← Automation commands
└── planning/
    ├── GIT-WORKFLOW.md              ← Git reference guide
    ├── phase-1-foundation/          ← Phase 1 instructions
    ├── phase-2-admin/               ← Phase 2 instructions
    ├── phase-2.5-conflict-detection/← Phase 2.5 instructions
    ├── phase-3-frontend/            ← Phase 3 instructions
    └── phase-4-testing/             ← Phase 4 instructions
```

---

## Visual Workflow

### The Development Journey

```
START
  ↓
┌─────────────────────────────────────────────────────────────┐
│ PHASE 1: Foundation (1-2 hours)                             │
│ ├── Main plugin file                                        │
│ ├── Autoloader                                              │
│ └── Custom Post Type (CPT)                                  │
│                                                              │
│ Agent: cpt-specialist                                       │
│ Branch: phase-1-foundation                                  │
│ BLOCKS: Everything else (must finish first)                 │
└─────────────────────────────────────────────────────────────┘
  ↓
┌─────────────────────────────────────────────────────────────┐
│ PHASE 2 + 2.5: Parallel Development (4-6 hours total)      │
│                                                              │
│ ┌────────────────────────┐  ┌───────────────────────────┐  │
│ │ PHASE 2: Admin UI      │  │ PHASE 2.5: Conflict Detect│  │
│ │ ├── Meta boxes         │  │ ├── Regex patterns        │  │
│ │ ├── Field rendering    │  │ ├── HTML scanning         │  │
│ │ └── Save handlers      │  │ └── Duplicate detection   │  │
│ │                        │  │                           │  │
│ │ Agent: meta-box        │  │ Agent: conflict-detector  │  │
│ │ Branch: phase-2-admin  │  │ Branch: phase-2.5-conflict│  │
│ └────────────────────────┘  └───────────────────────────┘  │
│                                                              │
│ THESE RUN AT SAME TIME (parallel)                          │
└─────────────────────────────────────────────────────────────┘
  ↓
┌─────────────────────────────────────────────────────────────┐
│ PHASE 3: Frontend Output (2-3 hours)                        │
│ ├── WordPress hooks (wp_head, wp_footer)                   │
│ ├── Scope filtering                                         │
│ ├── Caching strategy                                        │
│ └── Script rendering                                        │
│                                                              │
│ Agent: frontend-output-specialist                           │
│ Branch: phase-3-frontend                                    │
│ REQUIRES: Phase 1, 2, AND 2.5 merged                       │
└─────────────────────────────────────────────────────────────┘
  ↓
┌─────────────────────────────────────────────────────────────┐
│ PHASE 4: Testing & Security (4-5 hours)                    │
│ ├── Manual testing                                          │
│ ├── Security audit                                          │
│ ├── Code quality review                                     │
│ └── Deployment prep                                         │
│                                                              │
│ Agents: wp-security-scanner, wp-code-reviewer              │
│ Branch: phase-4-testing                                     │
│ REQUIRES: ALL previous phases merged                        │
└─────────────────────────────────────────────────────────────┘
  ↓
🎉 RELEASE v1.0.0
```

### Git Branch Strategy

```
main (protected)
  ↓
  ├─→ phase-1-foundation
  │     ↓ (merge via PR)
  │     ↓
  ├─→ phase-2-admin ────────┐
  │     ↓                    │
  └─→ phase-2.5-conflict ────┤ (both merge independently)
        ↓                    │
        ↓←───────────────────┘
        ↓
  ├─→ phase-3-frontend
  │     ↓ (merge via PR)
  │     ↓
  └─→ phase-4-testing
        ↓ (merge via PR)
        ↓
      v1.0.0 tag
```

---

## Step-by-Step: Starting Development

### Prerequisites Check

Before starting, make sure:
- [ ] You're in the project directory: `~/projects/gaplugin`
- [ ] You have Git installed: `git --version`
- [ ] You have GitHub CLI (optional but helpful): `gh --version`
- [ ] Main branch is up to date: `git pull origin main`

### Step 1: Start Phase 1 (Foundation)

Now you'll begin the actual plugin development. Phase 1 builds the foundation.

#### Option A: Using the Automated Command (Recommended)

```bash
# In Claude Code, type this command:
/start-phase 1
```

**What this does:**
1. Updates your main branch
2. Creates `phase-1-foundation` branch
3. Pushes branch to remote
4. Shows you the Phase 1 planning document
5. Gives you workflow reminders

#### Option B: Manual Git Commands

```bash
# Make sure main is up to date
git checkout main
git pull origin main

# Create Phase 1 branch
git checkout -b phase-1-foundation

# Push to remote
git push -u origin phase-1-foundation
```

Then in Claude Code:
```
Can you show me the Phase 1 planning document and help me get started?
```

---

### Step 2: Build Phase 1

Now you'll actually build the code. You have two options:

#### Option A: Guided Building (Recommended for Beginners)

In Claude Code, type:
```
/build-phase 1
```

**What this does:**
- Walks you through Phase 1 step-by-step
- Invokes the `cpt-specialist` agent automatically
- Reminds you when to commit
- Checks your work

#### Option B: Manual Approach

In Claude Code, have a conversation:
```
I'm ready to build Phase 1. Can you help me create:
1. The main plugin file (ga-plugin.php)
2. The autoloader
3. The Custom Post Type registration

Let's start with the main plugin file.
```

The AI will invoke the `cpt-specialist` agent when needed.

---

### Step 3: Commit Your Work

As you complete parts of Phase 1, commit your work:

```bash
# After creating main plugin file
git add ga-plugin.php
git commit -m "feat(setup): create main plugin file with constants

- Add plugin header with metadata
- Define plugin constants
- Add autoloader implementation

Addresses: Phase 1 deliverable (main plugin file)"
git push
```

**When to commit:**
- After creating the main plugin file
- After implementing the autoloader
- After creating the CPT class
- Before requesting a review

The agent will remind you when to commit.

---

### Step 4: Review Your Code

When Phase 1 is complete, get it reviewed:

```bash
# In Claude Code:
/review-phase 1
```

**What this does:**
1. Invokes `wp-security-scanner` agent (checks security)
2. Invokes `wp-code-reviewer` agent (checks quality)
3. Generates reports with specific issues
4. Tells you what to fix

If issues are found, fix them, commit, and re-run the review.

---

### Step 5: Create Pull Request

When Phase 1 is complete and reviewed:

```bash
# In Claude Code:
/finish-phase 1
```

**What this does:**
1. Commits any uncommitted work
2. Pushes to remote
3. Creates a Pull Request on GitHub
4. Adds phase-specific description
5. Shows you next steps

#### Manual PR Creation

```bash
# Push final changes
git push

# Create PR with GitHub CLI
gh pr create --title "Phase 1: Foundation - Core Plugin Setup" \
  --body "Complete Phase 1 implementation. See planning/phase-1-foundation/planning.md for details."
```

---

### Step 6: Merge and Move to Phase 2

After your PR is approved:

1. **Merge the PR** (on GitHub, click "Squash and merge")
2. **Update local main:**
   ```bash
   git checkout main
   git pull origin main
   git branch -d phase-1-foundation  # Delete local branch
   ```
3. **Start Phase 2:**
   ```bash
   /start-phase 2
   ```

**Important:** Phase 1 MUST be merged before starting Phase 2 or 2.5.

---

### Step 7: Understand Parallel Development (Phase 2 + 2.5)

After Phase 1 merges, you can work on Phase 2 and Phase 2.5 **at the same time** (if you want). They are independent.

#### Working on Phase 2 (Admin Interface)

```bash
/start-phase 2
/build-phase 2
# Work on meta boxes...
/finish-phase 2
```

#### Working on Phase 2.5 (Conflict Detection) - Parallel

You can do this in a different Claude Code session:

```bash
/start-phase 2.5
/build-phase 2.5
# Work on conflict detector...
/finish-phase 2.5
```

**Why parallel?** They don't touch the same files, so no conflicts!

---

### Step 8: Continue Through Remaining Phases

Repeat the same process for each phase:

- **Phase 3:** `/start-phase 3` (requires Phase 1, 2, AND 2.5 merged)
- **Phase 4:** `/start-phase 4` (final testing, requires all phases merged)

---

## Understanding the Phases

### Phase 1: Foundation (1-2 hours)
**What you're building:** The skeleton of the plugin
- Main plugin file with header
- Constants (GAP_VERSION, GAP_PLUGIN_DIR, etc.)
- Autoloader (loads classes automatically)
- Custom Post Type registration

**Key file:** `ga-plugin.php`, `includes/class-gap-post-type.php`

**Branch:** `phase-1-foundation`

**Completion criteria:**
- [ ] Plugin activates without errors
- [ ] CPT appears in admin menu
- [ ] Autoloader loads classes

---

### Phase 2: Admin Interface (2-3 hours)
**What you're building:** The WordPress admin screens
- Meta boxes (fields where users enter tracking codes)
- Field rendering (displaying inputs)
- Save handlers (saving data securely)
- Admin styling

**Key files:** `includes/admin/class-gap-meta-box.php`, CSS/JS assets

**Branch:** `phase-2-admin`

**Can run parallel with:** Phase 2.5

**Completion criteria:**
- [ ] Meta boxes display in admin
- [ ] Fields save correctly
- [ ] Nonce verification working
- [ ] Data sanitized properly

---

### Phase 2.5: Conflict Detection (2-3 hours)
**What you're building:** Smart duplicate detection
- Regex patterns for GA4 (G-XXXXXXXXXX)
- Regex patterns for GTM (GTM-XXXXXXX)
- HTML scanning to find existing scripts
- Caching for performance

**Key file:** `includes/class-gap-conflict-detector.php`

**Branch:** `phase-2.5-conflict-detection`

**Can run parallel with:** Phase 2

**Completion criteria:**
- [ ] GA4 IDs detected correctly
- [ ] GTM IDs detected correctly
- [ ] HTML scanning works
- [ ] Caching implemented

---

### Phase 3: Frontend Output (2-3 hours)
**What you're building:** Script injection to website
- Hook into `wp_head` and `wp_footer`
- Filter scripts by scope (global, posts only, etc.)
- Render GA4/GTM scripts
- Prevent duplicate scripts

**Key file:** `includes/frontend/class-gap-frontend-output.php`

**Branch:** `phase-3-frontend`

**Requires:** Phase 1, 2, AND 2.5 merged first

**Completion criteria:**
- [ ] Scripts output on frontend
- [ ] Scope filtering works
- [ ] No duplicate scripts
- [ ] Caching implemented

---

### Phase 4: Testing & Security (4-5 hours)
**What you're building:** Quality assurance
- Manual testing (create scripts, verify output)
- Security audit (no XSS, CSRF, SQL injection)
- Code quality review (WordPress standards)
- Documentation updates

**No new code files** - just testing and fixing

**Branch:** `phase-4-testing`

**Requires:** ALL previous phases merged

**Completion criteria:**
- [ ] All manual tests pass
- [ ] Security audit clean
- [ ] Code review clean
- [ ] Ready for v1.0.0 release

---

## Using Agents and Commands

### Your AI Specialists (Agents)

Think of these as expert consultants you can call on:

#### Development Agents (Write Code)

1. **cpt-specialist** - Phase 1
   - Expertise: Custom Post Type registration
   - When to use: Building Phase 1

2. **meta-box-specialist** - Phase 2
   - Expertise: Admin meta boxes and forms
   - When to use: Building Phase 2

3. **conflict-detector-specialist** - Phase 2.5
   - Expertise: Regex patterns, HTML scanning
   - When to use: Building Phase 2.5

4. **frontend-output-specialist** - Phase 3
   - Expertise: WordPress hooks, script output
   - When to use: Building Phase 3

#### Review Agents (Read-Only, Report Issues)

5. **wp-security-scanner** - All Phases
   - Expertise: WordPress security (XSS, CSRF, SQL injection)
   - When to use: `/review-phase [number]`

6. **wp-code-reviewer** - All Phases
   - Expertise: Code quality, WordPress standards
   - When to use: `/review-phase [number]`

### Your Automation Commands

These are shortcuts for common workflows:

#### `/start-phase [number]`
**Example:** `/start-phase 2`

**What it does:**
1. Checks dependencies are merged
2. Updates main branch
3. Creates feature branch
4. Pushes to remote
5. Shows planning document

**When to use:** Beginning any phase

---

#### `/build-phase [number]`
**Example:** `/build-phase 1`

**What it does:**
1. Verifies correct branch
2. Shows step-by-step guide
3. Invokes appropriate specialist agent
4. Reminds you when to commit

**When to use:** During development of a phase

---

#### `/review-phase [number]`
**Example:** `/review-phase 2`

**What it does:**
1. Runs security scan (wp-security-scanner)
2. Runs code quality review (wp-code-reviewer)
3. Generates detailed reports
4. Shows what needs fixing

**When to use:** After completing phase development

---

#### `/finish-phase [number]`
**Example:** `/finish-phase 3`

**What it does:**
1. Commits uncommitted work
2. Pushes to remote
3. Creates Pull Request
4. Shows merge dependencies

**When to use:** Phase is complete and reviewed

---

## What to Expect at Each Phase

### When You Start a Phase

1. **Branch created** - You're on `phase-[X]-[name]` branch
2. **Planning shown** - You see what to build
3. **Ready to code** - Start with `/build-phase [number]`

### During Development

1. **Agent guides you** - Specialist agent helps build code
2. **Commit reminders** - Agent tells you when to commit
3. **Git commands shown** - Exact commands to run

### After Completing Phase

1. **Review your code** - `/review-phase [number]`
2. **Fix any issues** - Agent reports what's wrong
3. **Create PR** - `/finish-phase [number]`
4. **Merge on GitHub** - Squash and merge
5. **Start next phase** - `/start-phase [next]`

---

## Troubleshooting

### "I'm on the wrong branch"

```bash
# See current branch
git branch --show-current

# Switch to correct branch
git checkout phase-[number]-[name]

# Or start the phase over
/start-phase [number]
```

---

### "I have uncommitted changes"

```bash
# See what's changed
git status

# Add and commit
git add [files]
git commit -m "your message"
git push
```

---

### "Dependencies not merged yet"

**Error:** "Phase 3 requires Phase 1, 2, AND 2.5 merged first"

**Solution:**
1. Go to GitHub
2. Merge the required PRs
3. Update your local main: `git checkout main && git pull origin main`
4. Try again: `/start-phase 3`

---

### "Agent not responding correctly"

**Try:**
1. Be specific: "Use the cpt-specialist agent to create the CPT class"
2. Use commands: `/build-phase 1` instead of asking manually
3. Check you're on correct branch: `git branch --show-current`

---

### "Not sure what to do next"

**Check:**
1. Current phase planning: `planning/phase-[X]-[name]/planning.md`
2. Completion criteria in planning doc
3. Ask: "What should I do next for Phase [X]?"

---

## Quick Command Reference

```bash
# Starting a phase
/start-phase [1|2|2.5|3|4]

# Building a phase
/build-phase [1|2|2.5|3|4]

# Reviewing code
/review-phase [1|2|2.5|3|4]

# Finishing a phase
/finish-phase [1|2|2.5|3|4]

# Git commands
git status              # See what's changed
git add [files]         # Stage files
git commit -m "msg"     # Commit changes
git push                # Push to remote
git checkout main       # Switch to main
git pull origin main    # Update main

# Branch management
git branch --show-current       # See current branch
git checkout [branch-name]      # Switch branches
git branch -d [branch-name]     # Delete merged branch
```

---

## Need More Help?

- **Git workflow details:** See `planning/GIT-WORKFLOW.md`
- **Orchestration overview:** See `.claude/CLAUDE.md`
- **Master plan:** See `GA-PLUGIN-PLAN.md`
- **Phase-specific details:** See `planning/phase-[X]-[name]/planning.md`

---

## You're Ready! 🎉

**Your first command:**
```bash
/start-phase 1
```

**Then:**
```bash
/build-phase 1
```

**Good luck building your plugin!** The agents are here to help every step of the way.
