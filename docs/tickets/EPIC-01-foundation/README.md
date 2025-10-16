# EPIC-01: Foundation & Core Plugin - Ticket Breakdown

**Epic ID:** EPIC-01
**Epic Name:** Foundation & Core Plugin
**Status:** Not Started
**Priority:** P0 (Critical)
**Total Estimated Time:** 4-6 hours
**Dependencies:** EPIC-00 (Project Setup)

---

## Overview

This epic establishes the core plugin foundation including the main plugin file, constants, autoloader, activation/deactivation logic, and basic plugin initialization. This is the fundamental architecture that all other components will build upon.

---

## Tickets Summary

### User Stories (5 tickets)

| Ticket ID | Title | Priority | Story Points | Status |
|-----------|-------|----------|--------------|--------|
| US-01.1 | As a WordPress administrator, I need to activate the plugin | P0 | 5 | Not Started |
| US-01.2 | As a developer, I need plugin constants for consistent path references | P0 | 2 | Not Started |
| US-01.3 | As a developer, I need an autoloader to load plugin classes | P0 | 3 | Not Started |
| US-01.4 | As a WordPress administrator, I need proper plugin activation/deactivation | P0 | 3 | Not Started |
| US-01.5 | As a developer, I need plugin initialization sequence | P0 | 3 | Not Started |

**Total Story Points:** 16

### Technical Tasks (4 tickets)

| Ticket ID | Title | Estimated Time | Status |
|-----------|-------|----------------|--------|
| TT-01.1 | Create Main Plugin File | 2 hours | Not Started |
| TT-01.2 | Create Activator Class | 1.5 hours | Not Started |
| TT-01.3 | Create Placeholder Class Files | 1 hour | Not Started |
| TT-01.4 | Implement Autoloader Testing | 30 minutes | Not Started |

**Total Estimated Time:** 5 hours

---

## User Story Details

### US-01.1: Plugin Activation
**File:** [user-stories/us-01.1-plugin-activation.md](./user-stories/us-01.1-plugin-activation.md)

Create the WordPress plugin header with all required metadata so the plugin appears in the WordPress admin plugins list and can be activated without errors.

**Key Deliverables:**
- WordPress plugin header in ga-plugin.php
- All required metadata fields
- ABSPATH security check
- Successful activation in WordPress admin

---

### US-01.2: Plugin Constants
**File:** [user-stories/us-01.2-plugin-constants.md](./user-stories/us-01.2-plugin-constants.md)

Define standardized plugin constants for version, file paths, and URLs to ensure consistent references throughout the codebase.

**Key Deliverables:**
- GAP_VERSION constant
- GAP_PLUGIN_FILE constant
- GAP_PLUGIN_DIR constant
- GAP_PLUGIN_URL constant
- GAP_PLUGIN_BASENAME constant

---

### US-01.3: Autoloader
**File:** [user-stories/us-01.3-autoloader.md](./user-stories/us-01.3-autoloader.md)

Implement PSR-4 compliant autoloader to automatically load plugin classes without manual include/require statements.

**Key Deliverables:**
- spl_autoload_register() implementation
- Class name to file name conversion
- GAP_ prefix filtering
- File existence checking

---

### US-01.4: Activation/Deactivation
**File:** [user-stories/us-01.4-activation-deactivation.md](./user-stories/us-01.4-activation-deactivation.md)

Create proper plugin lifecycle management with activation and deactivation hooks that initialize and clean up appropriately.

**Key Deliverables:**
- GAP_Activator class
- activate() static method
- deactivate() static method
- Rewrite rules flushing
- Data preservation on deactivation

---

### US-01.5: Plugin Initialization
**File:** [user-stories/us-01.5-plugin-initialization.md](./user-stories/us-01.5-plugin-initialization.md)

Establish plugin initialization sequence to load all components in the correct order when WordPress loads.

**Key Deliverables:**
- gap_init() function
- plugins_loaded hook
- Text domain loading
- Core component initialization

---

## Technical Task Details

### TT-01.1: Main Plugin File
**File:** [technical-tasks/tt-01.1-main-plugin-file.md](./technical-tasks/tt-01.1-main-plugin-file.md)

Create the main `ga-plugin.php` file containing plugin header, constants, autoloader, hooks, and initialization.

**Implementation Steps:**
1. Add WordPress plugin header
2. Add ABSPATH security check
3. Define all plugin constants
4. Implement autoloader
5. Register activation/deactivation hooks
6. Create and hook gap_init() function

---

### TT-01.2: Activator Class
**File:** [technical-tasks/tt-01.2-activator-class.md](./technical-tasks/tt-01.2-activator-class.md)

Create the `GAP_Activator` class to handle plugin activation and deactivation lifecycle events.

**Implementation Steps:**
1. Create class file with ABSPATH check
2. Implement activate() method
3. Implement deactivate() method
4. Add inline documentation
5. Test activation/deactivation cycle

---

### TT-01.3: Placeholder Classes
**File:** [technical-tasks/tt-01.3-placeholder-classes.md](./technical-tasks/tt-01.3-placeholder-classes.md)

Create placeholder class files for all core components using singleton pattern.

**Files to Create:**
- class-gap-cpt.php
- class-gap-meta-boxes.php
- class-gap-conflict-detector.php
- class-gap-frontend.php
- class-gap-admin.php

---

### TT-01.4: Autoloader Testing
**File:** [technical-tasks/tt-01.4-autoloader-testing.md](./technical-tasks/tt-01.4-autoloader-testing.md)

Test autoloader implementation to ensure correct class loading, prefix filtering, and error handling.

**Test Cases:**
1. Load GAP_ classes successfully
2. Ignore non-GAP_ classes
3. Handle non-existent classes gracefully
4. Verify singleton pattern
5. Validate file naming convention

---

## Dependencies

### Upstream
- **EPIC-00:** Project Setup (requires directory structure)

### Downstream
- **EPIC-02:** Admin Interface (requires autoloader and initialization)
- **EPIC-03:** Conflict Detection (requires autoloader and initialization)
- **EPIC-04:** Frontend Output (requires autoloader and initialization)

---

## Definition of Done

- [ ] All 5 user stories completed
- [ ] All 4 technical tasks completed
- [ ] ga-plugin.php created with complete plugin header
- [ ] All plugin constants defined and documented
- [ ] Autoloader implemented and tested
- [ ] GAP_Activator class created with activate/deactivate methods
- [ ] All placeholder class files created
- [ ] Plugin activates without errors in WordPress admin
- [ ] Plugin metadata displays correctly in plugins list
- [ ] Deactivation works without deleting data
- [ ] All code follows WordPress coding standards
- [ ] All files have ABSPATH security check
- [ ] Inline documentation complete
- [ ] Code review completed
- [ ] Manual testing completed
- [ ] All tickets committed to version control

---

## Testing Checklist

### Activation Testing
- [ ] Plugin activates in WordPress admin
- [ ] No PHP errors or warnings
- [ ] Plugin appears in plugins list
- [ ] Plugin metadata displays correctly

### Deactivation Testing
- [ ] Plugin deactivates without errors
- [ ] Data preserved after deactivation
- [ ] Can reactivate successfully

### Autoloader Testing
- [ ] All GAP_ classes load successfully
- [ ] Non-GAP_ classes ignored
- [ ] Non-existent classes handled gracefully
- [ ] Singleton pattern works correctly

### Constants Testing
- [ ] All constants defined correctly
- [ ] Constants accessible throughout plugin
- [ ] Paths and URLs correct

---

## Quick Links

### User Stories
- [US-01.1: Plugin Activation](./user-stories/us-01.1-plugin-activation.md)
- [US-01.2: Plugin Constants](./user-stories/us-01.2-plugin-constants.md)
- [US-01.3: Autoloader](./user-stories/us-01.3-autoloader.md)
- [US-01.4: Activation/Deactivation](./user-stories/us-01.4-activation-deactivation.md)
- [US-01.5: Plugin Initialization](./user-stories/us-01.5-plugin-initialization.md)

### Technical Tasks
- [TT-01.1: Main Plugin File](./technical-tasks/tt-01.1-main-plugin-file.md)
- [TT-01.2: Activator Class](./technical-tasks/tt-01.2-activator-class.md)
- [TT-01.3: Placeholder Classes](./technical-tasks/tt-01.3-placeholder-classes.md)
- [TT-01.4: Autoloader Testing](./technical-tasks/tt-01.4-autoloader-testing.md)

### Related Documents
- [EPIC-01 Main Document](./EPIC.md)
- [WordPress Plugin Header Requirements](https://developer.wordpress.org/plugins/plugin-basics/header-requirements/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)

---

**Created:** 2025-10-16
**Last Updated:** 2025-10-16
**Epic Owner:** TBD
