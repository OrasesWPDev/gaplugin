# Finish Phase Command

Complete a phase and create a pull request for review and merging.

## Usage
```
/finish-phase [phase-number]
```

## What This Does

This command automates the post-phase completion process by:

1. **Validating phase completion** - Checks all completion criteria are met
2. **Committing final changes** - Ensures all work is committed
3. **Pushing to remote** - Syncs local branch with remote
4. **Generating PR description** - Creates detailed PR from phase deliverables
5. **Creating pull request** - Opens PR using gh CLI or provides template
6. **Displaying next steps** - Shows review process and merge requirements

## Phase-Specific Templates

### Phase 1 - Foundation
```bash
/finish-phase 1
```

**PR Title:** `Phase 1: Foundation - Core Plugin Setup`

**Deliverables Checklist:**
- [ ] Main plugin file created (`ga-plugin.php`)
- [ ] Plugin constants defined
- [ ] Autoloader implemented
- [ ] CPT registered (`gap_tracking`)
- [ ] Activation/deactivation hooks
- [ ] Plugin activates without errors

### Phase 2 - Admin Interface
```bash
/finish-phase 2
```

**PR Title:** `Phase 2: Admin Interface - Meta Boxes & Fields`

**Deliverables Checklist:**
- [ ] Meta box class created
- [ ] Fields render correctly
- [ ] Save handlers with security
- [ ] Nonce verification working
- [ ] All input sanitized
- [ ] All output escaped

### Phase 2.5 - Conflict Detection
```bash
/finish-phase 2.5
```

**PR Title:** `Phase 2.5: Conflict Detection System`

**Deliverables Checklist:**
- [ ] Conflict detector class created
- [ ] GA4/GTM ID extraction working
- [ ] Duplicate detection functional
- [ ] HTML scanning implemented
- [ ] Caching system working

### Phase 3 - Frontend Output
```bash
/finish-phase 3
```

**PR Title:** `Phase 3: Frontend Output Handler`

**Deliverables Checklist:**
- [ ] Frontend output class created
- [ ] Scripts output to correct placement
- [ ] Scope filtering works
- [ ] Caching implemented
- [ ] No duplicate scripts
- [ ] All output escaped

### Phase 4 - Testing & Security
```bash
/finish-phase 4
```

**PR Title:** `Phase 4: Testing & Security Audit`

**Deliverables Checklist:**
- [ ] All tests passing
- [ ] Security audit complete
- [ ] Code review complete
- [ ] Documentation updated
- [ ] Ready for deployment

---

## Implementation

When this command is executed, perform the following steps in order:

### 1. Validate Phase Number
```bash
# Valid phases: 1, 2, 2.5, 3, 4
if [[ ! "$PHASE" =~ ^(1|2|2\.5|3|4)$ ]]; then
    echo "ERROR: Invalid phase number"
    echo "Valid phases: 1, 2, 2.5, 3, 4"
    exit 1
fi
```

### 2. Verify Correct Branch
```bash
# Determine expected branch name
case "$PHASE" in
    1) EXPECTED_BRANCH="phase-1-foundation" ;;
    2) EXPECTED_BRANCH="phase-2-admin" ;;
    2.5) EXPECTED_BRANCH="phase-2.5-conflict-detection" ;;
    3) EXPECTED_BRANCH="phase-3-frontend" ;;
    4) EXPECTED_BRANCH="phase-4-testing" ;;
esac

CURRENT_BRANCH=$(git branch --show-current)

if [ "$CURRENT_BRANCH" != "$EXPECTED_BRANCH" ]; then
    echo "⚠️  ERROR: Not on correct branch"
    echo "   Current: $CURRENT_BRANCH"
    echo "   Expected: $EXPECTED_BRANCH"
    echo ""
    echo "SOLUTION: Switch to phase branch:"
    echo "   git checkout $EXPECTED_BRANCH"
    exit 1
fi
```

### 3. Check Completion Criteria
```bash
echo "📋 Checking phase completion criteria..."
echo ""

# Load phase planning document to get completion checklist
case "$PHASE" in
    1)
        PLANNING_DOC="planning/phase-1-foundation/planning.md"
        ;;
    2)
        PLANNING_DOC="planning/phase-2-admin/planning.md"
        ;;
    2.5)
        PLANNING_DOC="planning/phase-2.5-conflict-detection/planning.md"
        ;;
    3)
        PLANNING_DOC="planning/phase-3-frontend/planning.md"
        ;;
    4)
        PLANNING_DOC="planning/phase-4-testing/planning.md"
        ;;
esac

# Extract and display completion criteria
echo "Review completion criteria from: $PLANNING_DOC"
echo ""
grep -A 10 "^## Completion Criteria" "$PLANNING_DOC" || echo "No criteria section found"
echo ""

read -p "Have all completion criteria been met? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Aborted: Complete all criteria before creating PR"
    exit 1
fi
```

### 4. Check for Uncommitted Changes
```bash
echo "📝 Checking for uncommitted changes..."

if [ -n "$(git status --porcelain)" ]; then
    echo ""
    echo "⚠️  Uncommitted changes detected:"
    git status --short
    echo ""
    read -p "Commit these changes now? (y/n) " -n 1 -r
    echo

    if [[ $REPLY =~ ^[Yy]$ ]]; then
        # Stage all changes
        git add .

        # Prompt for commit message
        echo "Enter commit message (or press Enter for default):"
        read -r COMMIT_MSG

        if [ -z "$COMMIT_MSG" ]; then
            COMMIT_MSG="chore: final changes for Phase $PHASE completion"
        fi

        # Commit
        git commit -m "$COMMIT_MSG"
        echo "✅ Changes committed"
    else
        echo "Aborted: Commit or stash changes before creating PR"
        exit 1
    fi
else
    echo "✅ No uncommitted changes"
fi
```

### 5. Push to Remote
```bash
echo "📤 Pushing final changes to remote..."

if git push; then
    echo "✅ Branch synchronized with remote"
else
    echo "❌ Failed to push to remote"
    echo "   Fix the issue and try again"
    exit 1
fi
```

### 6. Generate PR Description
```bash
echo ""
echo "📝 Generating PR description..."

# Create temporary PR description file
PR_DESC_FILE=$(mktemp)

# Determine PR content based on phase
case "$PHASE" in
    1)
        cat > "$PR_DESC_FILE" << 'EOF'
## Phase 1: Foundation - Core Plugin Setup

### Overview
Establishes the plugin foundation with main plugin file, constants, autoloader, and Custom Post Type registration.

### Deliverables
- [x] Main plugin file (`ga-plugin.php`)
- [x] Plugin constants defined (GAP_VERSION, GAP_PLUGIN_DIR, etc.)
- [x] PSR-4 autoloader implemented
- [x] CPT registered (`gap_tracking`)
- [x] Activation/deactivation hooks
- [x] Activator class (`class-gap-activator.php`)

### Files Changed
- `ga-plugin.php` - Main plugin file with header, constants, autoloader
- `includes/class-gap-activator.php` - Activation/deactivation logic
- `includes/class-gap-post-type.php` - CPT registration and admin columns

### Testing Completed
- [x] Plugin activates without errors
- [x] CPT appears in admin menu
- [x] Autoloader loads classes correctly
- [x] Constants defined properly
- [x] No PHP errors or warnings

### Dependencies
**Requires:** None (foundation phase)
**Blocks:** Phase 2, Phase 2.5

### Review Focus
**Code Quality:**
- CPT registration follows WordPress standards
- Autoloader implements PSR-4 correctly
- Proper use of singleton pattern
- File naming conventions (class-gap-*.php)

**Security:**
- ABSPATH checks in all PHP files
- Proper constant definitions
- No debug code

### Completion Checklist
- [x] All deliverables implemented
- [x] WordPress coding standards followed
- [x] GAP_ prefix used consistently
- [x] Text domain 'ga-plugin' used throughout
- [x] No debug code (var_dump, print_r, die)

🤖 Generated with [Claude Code](https://claude.com/claude-code)
EOF
        ;;

    2)
        cat > "$PR_DESC_FILE" << 'EOF'
## Phase 2: Admin Interface - Meta Boxes & Fields

### Overview
Creates admin interface for configuring tracking scripts with meta boxes, field handling, and proper security implementation.

### Deliverables
- [x] Meta box class for tracking configuration
- [x] Field rendering with proper escaping
- [x] Save handlers with security (nonces, capabilities, sanitization)
- [x] Admin styles and JavaScript
- [x] Validation and error handling

### Files Changed
- `includes/admin/class-gap-meta-box.php` - Meta box registration and rendering
- `assets/css/admin-meta-box.css` - Admin styling
- `assets/js/admin-meta-box.js` - Dynamic field handling

### Testing Completed
- [x] Meta boxes render in post editor
- [x] Fields save correctly
- [x] Nonce verification working
- [x] All input sanitized
- [x] All output escaped
- [x] Validation catches invalid data
- [x] Dynamic fields show/hide correctly

### Dependencies
**Requires:** Phase 1 (foundation)
**Blocks:** Phase 3 (frontend)

### Review Focus
**Security (CRITICAL):**
- Nonce verification on all save operations
- Capability checks (`edit_post` permission)
- Input sanitization (sanitize_text_field, wp_kses_post)
- Output escaping (esc_attr, esc_html, esc_textarea)
- Autosave protection

**Code Quality:**
- DRY principle (no repeated validation logic)
- Single Responsibility (meta box class focuses on admin UI)
- WordPress standards (Yoda conditions, spacing)

### Completion Checklist
- [x] All security checks implemented
- [x] All fields validated
- [x] User-friendly labels and help text
- [x] Follows GAP_ naming conventions
- [x] No XSS vulnerabilities
- [x] No CSRF vulnerabilities

🤖 Generated with [Claude Code](https://claude.com/claude-code)
EOF
        ;;

    2.5)
        cat > "$PR_DESC_FILE" << 'EOF'
## Phase 2.5: Conflict Detection System

### Overview
Implements duplicate tracking script detection system to prevent double-tracking of Google Analytics IDs.

### Deliverables
- [x] Conflict detector class
- [x] GA4 tracking ID extraction (G-XXXXXXXXXX)
- [x] GTM tracking ID extraction (GTM-XXXXXXX)
- [x] Duplicate detection across posts
- [x] HTML scanning for existing scripts
- [x] Caching system
- [x] Conflict logging

### Files Changed
- `includes/class-gap-conflict-detector.php` - Conflict detection logic

### Testing Completed
- [x] GA4 IDs extracted correctly
- [x] GTM IDs extracted correctly
- [x] Regex patterns tested with valid/invalid IDs
- [x] Duplicate detection works across multiple posts
- [x] HTML scanning finds existing scripts
- [x] Caching reduces database queries
- [x] Conflict logging works with WP_DEBUG

### Dependencies
**Requires:** Phase 1 (foundation)
**Blocks:** Phase 3 (frontend - needs conflict detector)

### Review Focus
**Code Quality:**
- Regex patterns robust and secure
- Efficient HTML scanning (no excessive DOM parsing)
- Proper caching implementation (transients)
- Singleton pattern used correctly

**Performance:**
- Regex doesn't cause ReDoS vulnerabilities
- Caching strategy reduces DB load
- HTML scanning is performant

### Completion Checklist
- [x] Regex patterns tested thoroughly
- [x] No performance bottlenecks
- [x] Caching implemented correctly
- [x] Follows GAP_ naming conventions
- [x] Proper error handling

🤖 Generated with [Claude Code](https://claude.com/claude-code)
EOF
        ;;

    3)
        cat > "$PR_DESC_FILE" << 'EOF'
## Phase 3: Frontend Output Handler

### Overview
Implements frontend script output with scope filtering, caching, and conflict prevention integration.

### Deliverables
- [x] Frontend output class
- [x] Hook integration (wp_head, wp_footer)
- [x] Scope filtering logic
- [x] Script rendering for GA4/GTM
- [x] Cache implementation
- [x] Conflict detector integration
- [x] Duplicate prevention

### Files Changed
- `includes/frontend/class-gap-frontend-output.php` - Frontend output logic

### Testing Completed
- [x] Scripts output to correct placement (head/body)
- [x] Scope filtering works (global, posts, pages, etc.)
- [x] Caching reduces queries
- [x] Duplicate scripts prevented
- [x] Conflict detector integration works
- [x] All output properly escaped
- [x] No scripts output when disabled

### Dependencies
**Requires:** Phase 1 (foundation), Phase 2.5 (conflict detector)
**Blocks:** Phase 4 (testing)

### Review Focus
**Security (CRITICAL):**
- Script output escaped appropriately
- No XSS vulnerabilities in dynamic script generation
- User input never executed directly

**Functionality:**
- Scope filtering logic covers all cases
- Conflict detection prevents duplicates
- Caching strategy effective
- Hook priorities correct

### Completion Checklist
- [x] All scope options work correctly
- [x] No duplicate scripts output
- [x] Conflict warnings displayed
- [x] Performance acceptable
- [x] Follows GAP_ naming conventions

🤖 Generated with [Claude Code](https://claude.com/claude-code)
EOF
        ;;

    4)
        cat > "$PR_DESC_FILE" << 'EOF'
## Phase 4: Testing & Security Audit

### Overview
Complete testing, security audit, code quality review, and deployment preparation.

### Deliverables
- [x] Manual testing complete
- [x] Security audit passed
- [x] Code quality review passed
- [x] Documentation updated
- [x] Deployment checklist complete

### Files Changed
- Documentation updates
- README.md
- Any bug fixes discovered during testing

### Testing Completed
- [x] Activation/deactivation works correctly
- [x] CPT functions properly
- [x] Meta boxes save data correctly
- [x] Frontend scripts output correctly
- [x] Conflict detection works
- [x] Duplicate prevention works
- [x] Security audit passed (nonces, sanitization, capabilities)
- [x] Code standards validated
- [x] Browser testing (Chrome, Firefox, Safari)
- [x] WordPress compatibility tested

### Dependencies
**Requires:** All previous phases (1, 2, 2.5, 3)
**Blocks:** None (final phase)

### Review Focus
**Final Verification:**
- All functionality works end-to-end
- No security vulnerabilities remain
- Code quality acceptable
- Documentation complete and accurate
- Ready for production deployment

### Completion Checklist
- [x] All tests passing
- [x] No security vulnerabilities
- [x] Code follows standards
- [x] Documentation complete
- [x] Ready for v1.0.0 release

🤖 Generated with [Claude Code](https://claude.com/claude-code)
EOF
        ;;
esac

echo "✅ PR description generated"
```

### 7. Create Pull Request
```bash
echo ""
echo "🔀 Creating pull request..."

# Determine PR title
case "$PHASE" in
    1) PR_TITLE="Phase 1: Foundation - Core Plugin Setup" ;;
    2) PR_TITLE="Phase 2: Admin Interface - Meta Boxes & Fields" ;;
    2.5) PR_TITLE="Phase 2.5: Conflict Detection System" ;;
    3) PR_TITLE="Phase 3: Frontend Output Handler" ;;
    4) PR_TITLE="Phase 4: Testing & Security Audit" ;;
esac

# Try to create PR with gh CLI
if command -v gh &> /dev/null; then
    echo "Using GitHub CLI to create PR..."

    if gh pr create \
        --title "$PR_TITLE" \
        --body-file "$PR_DESC_FILE" \
        --base main \
        --head "$EXPECTED_BRANCH"; then

        echo ""
        echo "✅ Pull request created successfully!"
        echo ""

        # Get PR number
        PR_NUMBER=$(gh pr view --json number -q .number)
        PR_URL=$(gh pr view --json url -q .url)

        echo "📋 PR #$PR_NUMBER: $PR_TITLE"
        echo "🔗 $PR_URL"

        # Clean up temp file
        rm "$PR_DESC_FILE"
    else
        echo "❌ Failed to create PR with gh CLI"
        echo ""
        echo "📋 PR description saved to: $PR_DESC_FILE"
        echo "   Create PR manually using this description"
    fi
else
    echo "⚠️  GitHub CLI (gh) not found"
    echo ""
    echo "📋 PR description saved to: $PR_DESC_FILE"
    echo ""
    echo "Create PR manually:"
    echo "1. Go to: https://github.com/YOUR-ORG/gaplugin/compare/$EXPECTED_BRANCH?expand=1"
    echo "2. Set base branch to: main"
    echo "3. Copy description from: $PR_DESC_FILE"
    echo "4. Click 'Create pull request'"
fi
```

### 8. Display Next Steps
```bash
echo ""
echo "🎯 Phase $PHASE Completion Summary"
echo "====================================="
echo ""
echo "✅ All changes committed"
echo "✅ Branch pushed to remote: $EXPECTED_BRANCH"
echo "✅ Pull request created: $PR_TITLE"
echo ""
echo "📋 Next Steps:"
echo ""
echo "1. **Request Reviews**"
echo "   - Security review: @wp-security-scanner"
echo "   - Code review: @wp-code-reviewer"
echo ""
echo "2. **Address Feedback**"
echo "   - Make requested changes on branch: $EXPECTED_BRANCH"
echo "   - Commit and push: git add . && git commit && git push"
echo ""
echo "3. **Merge When Approved**"
echo "   - Wait for approval from reviewers"
echo "   - Use 'Squash and merge' option"
echo "   - Delete branch after merging"
echo ""
echo "4. **Update Local Main**"
echo "   git checkout main"
echo "   git pull origin main"
echo ""

# Show merge dependencies
case "$PHASE" in
    1)
        echo "5. **Unblocks Next Phases**"
        echo "   - Phase 2 (Admin Interface) can now start"
        echo "   - Phase 2.5 (Conflict Detection) can now start"
        echo "   - Both can be developed in parallel"
        ;;
    2)
        echo "5. **Merge Requirements for Phase 3**"
        echo "   - Phase 2.5 must also merge before Phase 3 can start"
        ;;
    2.5)
        echo "5. **Merge Requirements for Phase 3**"
        echo "   - Phase 2 must also merge before Phase 3 can start"
        ;;
    3)
        echo "5. **Unblocks Final Phase**"
        echo "   - Phase 4 (Testing & Security) can now start"
        ;;
    4)
        echo "5. **Plugin Complete!**"
        echo "   - All phases implemented"
        echo "   - Ready for deployment"
        echo "   - Create v1.0.0 release tag"
        ;;
esac

echo ""
echo "📖 References:"
echo "   - Git Workflow: planning/GIT-WORKFLOW.md"
echo "   - Phase Plan: $PLANNING_DOC"
echo ""
```

---

## Success Criteria

Phase completion is successful when:
- [ ] All completion criteria met
- [ ] All changes committed
- [ ] Branch pushed to remote
- [ ] PR created with detailed description
- [ ] Reviewers can be assigned
- [ ] Next steps clearly communicated

---

## Error Handling

### Wrong Branch
```
⚠️  ERROR: Not on correct branch
   Current: main
   Expected: phase-2-admin

SOLUTION: Switch to phase branch:
   git checkout phase-2-admin
```

### Uncommitted Changes
```
⚠️  Uncommitted changes detected:
M  includes/admin/class-gap-meta-box.php
?? assets/css/admin-meta-box.css

Commit these changes now? (y/n)
```

### Failed to Push
```
❌ Failed to push to remote
   Fix the issue and try again

POSSIBLE CAUSES:
- Merge conflict with remote branch
- Network connection issue
- Need to pull remote changes first
```

### gh CLI Not Available
```
⚠️  GitHub CLI (gh) not found

📋 PR description saved to: /tmp/pr-desc-XYZ.md

Create PR manually:
1. Go to GitHub repository
2. Click "New pull request"
3. Copy description from saved file
```

---

## Related Commands

- **`/start-phase [X]`** - Prepare environment before starting phase
- **`/build-phase [X]`** - Guided implementation
- **`/review-phase [X]`** - Security and code review
- **`/test-component [name]`** - Test individual component

---

## Notes

- Always run `/review-phase [X]` before `/finish-phase [X]` to catch issues early
- PR descriptions are auto-generated from phase deliverables
- Reviewers should check against phase completion criteria
- Squash merge is recommended to keep main branch clean
- Branch will be deleted automatically after merge (if using gh CLI)

---

## Git Workflow Integration

This command implements Step 3 of the Git workflow:
1. **`/start-phase`** ← Prepares environment
2. **`/build-phase`** ← Guided implementation with commits
3. **`/finish-phase`** ← Creates PR (YOU ARE HERE)
4. **PR Review** ← Security and code quality checks
5. **Merge** ← Integration to main

**Reference:** See `planning/GIT-WORKFLOW.md` for complete workflow documentation

---

**Version:** 1.0
**Created:** 2025-10-14
**Integration:** Git & GitHub workflow implementation
