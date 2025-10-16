# File Ownership Matrix

Reference for which epic owns which files to prevent conflicts during parallel development.

## Ownership Overview

Each epic creates and maintains specific files. During parallel development, respect file ownership to avoid conflicts.

### Core Principle
**Don't modify files owned by other epics** while both are in development. If you need to modify another epic's files:
1. Wait for that epic to merge first, OR
2. Communicate and coordinate with the other epic owner

---

## Epic File Ownership

### EPIC-00: Project Setup
**Purpose**: Project infrastructure and initialization

**Creates:**
- `.gitignore` - Version control exclusions
- `README.md` - Main project documentation
- `ga-plugin.php` - Main plugin file (header only, not classes)
- `.wp-json` - WordPress configuration
- `composer.json` - PHP dependencies (if used)
- `package.json` - Node dependencies (if used)

**Protected From:** All other epics (do not modify)
**Others Can Modify:** Only under coordination

**Notes:**
- Main plugin header MUST be created by EPIC-00
- Other epics add includes/requires to this file
- Version number managed here

---

### EPIC-01: Foundation
**Purpose**: Core plugin foundation and autoloader

**Creates:**
- `includes/class-gap-activator.php` - Activation hooks
- `includes/class-gap-deactivator.php` - Deactivation hooks
- `includes/class-gap-autoloader.php` - Class autoloading
- `includes/class-gap-loader.php` - Main plugin loader
- `assets/` - Initial asset directories

**Modifies:**
- `ga-plugin.php` - Add main plugin initialization

**Protected From:** EPIC-02, EPIC-03, EPIC-04, EPIC-05
**Can Safely Modify:** Only its own files

**Parallel Development:**
- ✅ Can run parallel with EPIC-02, 03, 04, 05
- ✅ These epics depend on it (must merge first)

**Notes:**
- Autoloader enables all other epics
- Version upgrade hooks go here
- Plugin hooks and filters initialized here

---

### EPIC-02: Admin Interface
**Purpose**: Admin UI and custom post type management

**Creates:**
- `includes/class-gap-cpt.php` - Custom post type registration
- `includes/class-gap-meta-boxes.php` - Meta box handling
- `includes/class-gap-settings.php` - Plugin settings
- `assets/css/admin.css` - Admin styling
- `assets/js/admin.js` - Admin JavaScript
- `assets/images/` - Admin images

**Modifies:**
- `ga-plugin.php` - Include admin classes

**Protected From:** EPIC-00, EPIC-01, EPIC-03, EPIC-04, EPIC-05
**Can Safely Modify:** Only its own files

**Parallel Development:**
- ✅ Can run parallel with EPIC-03 (different files)
- ⚠️ Can run parallel with EPIC-04 (must coordinate frontend changes)
- ✅ Must wait for EPIC-01 to complete (requires autoloader)

**Notes:**
- Admin interface only (not frontend)
- CPT registration happens here
- Meta field definitions here
- Admin page for settings here

---

### EPIC-03: Conflict Detection
**Purpose**: Duplicate script detection system

**Creates:**
- `includes/class-gap-conflict-detector.php` - Conflict detection logic
- `includes/class-gap-script-analyzer.php` - Script analysis utilities
- `assets/js/conflict-detection.js` - Client-side detection

**Modifies:**
- `ga-plugin.php` - Include conflict detection classes
- `includes/class-gap-loader.php` - Add conflict detection hooks

**Protected From:** EPIC-00, EPIC-01, EPIC-02, EPIC-04, EPIC-05
**Can Safely Modify:** Only its own files + loader for hooks

**Parallel Development:**
- ✅ Can run parallel with EPIC-02 (different files)
- ⚠️ Can run parallel with EPIC-04 (coordinate frontend usage)
- ✅ Must wait for EPIC-01 (requires autoloader)

**Notes:**
- Detects duplicate tracking IDs
- Scans HTML for existing scripts
- Provides admin notifications
- Works on both admin and frontend

---

### EPIC-04: Frontend Output
**Purpose**: Frontend script output injection

**Creates:**
- `includes/class-gap-frontend.php` - Frontend output class
- `includes/class-gap-script-injector.php` - Script injection logic
- `assets/css/frontend.css` - Frontend styling
- `assets/js/frontend.js` - Frontend JavaScript

**Modifies:**
- `ga-plugin.php` - Include frontend classes
- `includes/class-gap-loader.php` - Add frontend hooks
- ⚠️ May need to modify EPIC-02 or EPIC-03 files for integration

**Protected From:** EPIC-00, EPIC-01, EPIC-02, EPIC-03, EPIC-05
**Can Safely Modify:** Only its own files + loader for hooks

**Parallel Development:**
- ⚠️ Can run parallel with EPIC-02 (requires coordination if modifying CPT output)
- ⚠️ Can run parallel with EPIC-03 (requires coordination on conflict detection flow)
- ✅ Must wait for EPIC-01, EPIC-02, EPIC-03 to complete first

**Notes:**
- Frontend output only (no admin)
- Uses scripts from CPT (EPIC-02)
- Uses conflict detection (EPIC-03)
- Injects scripts based on scope and placement
- Critically dependent on other epics

---

### EPIC-05: Testing & Launch
**Purpose**: Testing, documentation, launch preparation

**Creates:**
- `tests/` - All test files
- `docs/` - All documentation
- No new source code classes

**Modifies:**
- No core source files (references only)

**Protected From:** All other epics
**Can Safely Modify:** Only test and doc files

**Parallel Development:**
- ⚠️ Can run parallel but should wait for other epics to stabilize
- ✅ No file conflicts (only tests/docs)
- ✅ Runs after all source code is complete

**Notes:**
- Test coverage for all epics
- Integration testing
- Security testing
- Performance optimization
- Launch checklist

---

## Conflict Prevention Matrix

| Epic | EPIC-00 | EPIC-01 | EPIC-02 | EPIC-03 | EPIC-04 | EPIC-05 |
|------|---------|---------|---------|---------|---------|---------|
| **EPIC-00** | - | 🔒 | 🔒 | 🔒 | 🔒 | 🔒 |
| **EPIC-01** | 🔒 | - | ✅ | ✅ | ✅ | ✅ |
| **EPIC-02** | 🔒 | ✅ | - | ✅ | ⚠️ | ✅ |
| **EPIC-03** | 🔒 | ✅ | ✅ | - | ⚠️ | ✅ |
| **EPIC-04** | 🔒 | ✅ | ⚠️ | ⚠️ | - | ✅ |
| **EPIC-05** | 🔒 | ✅ | ✅ | ✅ | ✅ | - |

Legend:
- `🔒` = Blocked (wait for other epic)
- `✅` = Safe (can develop in parallel)
- `⚠️` = Coordinate (safe with communication)

---

## Recommended Merge Order

```
1. EPIC-00: Project Setup (foundation)
   ↓
2. EPIC-01: Foundation (autoloader, classes)
   ↓
   ├→ EPIC-02: Admin Interface (parallel)
   │  └→ EPIC-04: Frontend (after 02)
   │
   └→ EPIC-03: Conflict Detection (parallel with 02)
      └→ EPIC-04: Frontend (after 03)
   ↓
3. EPIC-04: Frontend Output (after 02 & 03)
   ↓
4. EPIC-05: Testing & Launch (after all others)
```

---

## File Modification Guidelines

### If You Need Another Epic's File

**Scenario 1: Both Epics in Development**
- ❌ Don't modify it directly
- ✅ Contact the other epic owner
- ✅ Discuss the needed change
- ✅ Coordinate approach
- ✅ Plan merge order carefully

**Scenario 2: Other Epic Already Merged**
- ✅ You can modify the file
- ✅ Create ticket for your epic
- ✅ Document the modification
- ✅ Test thoroughly
- ✅ Create commit with reference to both epics

**Example:**
```
feat(epic-04): US-04.2 - improve script injection filtering

- Modified GAP_CPT class to expose script listing API
- EPIC-02 merged; safe to modify
- Coordinates with conflict detection (EPIC-03)

Ticket: US-04.2
Epic: EPIC-04
Related Epic: EPIC-02
Status: Completed
```

---

## Coordinating Cross-Epic Changes

### Step 1: Identify the Need
- Your epic needs to modify another epic's file
- Both epics still in development

### Step 2: Communication
- Message the other epic owner
- Explain what you need and why
- Discuss potential solutions

### Step 3: Coordination Options
**Option A: Implement in Source Epic**
- Ask source epic to add feature/API
- Avoids direct file modification
- Source epic creates ticket for it
- Cleaner architecture

**Option B: Coordinate Merge Order**
- Source epic merges first
- Your epic then modifies after merge
- No parallel development risk
- May delay your epic

**Option C: Create Integration Task**
- Both epics complete independently
- After both merged, create integration ticket in EPIC-05
- Tests ensure compatibility
- Cleanest separation of concerns

### Step 4: Document the Coordination
- Update ticket with coordination notes
- Document in commit message
- Update file-ownership if permanent change
- Communicate in PR

---

## Troubleshooting Conflicts

### If Merge Conflicts Occur

1. **Identify the conflict source:**
   ```bash
   git diff --name-only --diff-filter=U
   ```

2. **Determine ownership:**
   - Who owns the conflicting file?
   - When did each epic modify it?

3. **Resolve based on ownership:**
   - If one epic owns it: theirs takes priority
   - If both modified: review changes carefully
   - If cross-epic modification: discuss with both owners

4. **Communicate:**
   - Notify both epic owners
   - Explain resolution approach
   - Get approval before merging

5. **Document:**
   - Create commit explaining resolution
   - Reference both epic PRs
   - Update merge order documentation if needed

### Example Resolution

```
git merge epic-04-frontend-output
# Conflict in includes/class-gap-cpt.php
# EPIC-02 owns this file

# View both versions
git show :2:includes/class-gap-cpt.php  # EPIC-02 version
git show :3:includes/class-gap-cpt.php  # EPIC-04 version

# Determine how to combine changes
# EPIC-02 owns the file, but EPIC-04 needs changes

# Contact EPIC-02 owner, then either:
# A) Have EPIC-02 merge first, then EPIC-04 modifies
# B) Have EPIC-02 add the needed API/method
# C) Manually merge compatible changes

git resolve # After decision
```

---

## Best Practices

### DO ✅
- ✅ Know which files your epic owns
- ✅ Know which files are off-limits
- ✅ Communicate before modifying other epic files
- ✅ Plan merge order to minimize conflicts
- ✅ Document cross-epic modifications
- ✅ Keep epic-level dependencies in loader.php

### DON'T ❌
- ❌ Modify other epic's files without coordination
- ❌ Start EPIC-04 before EPIC-02 and EPIC-03 merge
- ❌ Ignore merge conflicts
- ❌ Merge without resolving conflicts
- ❌ Forget to update file ownership if it changes
- ❌ Create circular dependencies
