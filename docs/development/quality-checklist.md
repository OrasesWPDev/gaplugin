# Ticket Quality Checklist

Use this checklist before finalizing tickets to ensure they meet quality standards.

## Pre-Finalization Checks

### General Format

- [ ] Ticket ID format is correct (`US-XX.Y` or `TT-XX.Y`)
- [ ] Title is clear and descriptive
- [ ] File path uses kebab-case filename convention
- [ ] File location is correct (user-stories/ or technical-tasks/)
- [ ] All template sections are filled out
- [ ] No placeholder text like "TBD" in critical sections
- [ ] No "TBD" in: Acceptance Criteria, Tasks, Technical Details, Definition of Done

### Content Quality

#### Acceptance Criteria
- [ ] Each criterion is specific and testable
- [ ] Criteria are observable and verifiable
- [ ] No vague language (e.g., "user-friendly", "works well")
- [ ] Criteria represent the "what", not the "how"
- [ ] At least 3-5 criteria per ticket (typically)
- [ ] Criteria can be checked off in testing

**Bad Example:**
```
- [ ] The admin interface works
```

**Good Example:**
```
- [ ] Admin menu appears under Dashboard with label "Tracking Scripts"
- [ ] Only users with manage_options capability see the menu
- [ ] Custom post type is registered with public=false
```

#### Implementation Tasks
- [ ] Each task has a time estimate (15 min - 2 hours)
- [ ] Tasks are concrete and actionable
- [ ] Tasks build logically toward completion
- [ ] Each task covers a single responsibility
- [ ] Time estimates are realistic (can be verified in estimation)
- [ ] No task takes more than 2 hours (break it up if needed)
- [ ] All tasks together should match Story Points / Estimated Time

**Bad Example:**
```
- [ ] Implement the CPT
```

**Good Example:**
```
- [ ] Create GAP_CPT class with singleton pattern (15 min)
- [ ] Implement register_post_type() with proper arguments (45 min)
- [ ] Configure admin menu with correct icon (15 min)
- [ ] Add custom columns to list view (30 min)
```

#### Dependencies
- [ ] All blocking tickets are listed
- [ ] All epic dependencies are noted
- [ ] Reason for each blocker is explained
- [ ] Related but non-blocking tickets are identified
- [ ] Related tickets are useful context (not random)

#### Technical Details
- [ ] File names are specific (includes/ location, class names)
- [ ] WordPress hooks are listed (init, save_post, etc.)
- [ ] Meta keys are documented (e.g., `_gap_script_content`)
- [ ] Database changes are documented (if applicable)
- [ ] Class names follow naming convention (GAP_*)
- [ ] Relevant code examples are included
- [ ] All external dependencies are noted

#### Testing Requirements
- [ ] Steps are specific and repeatable
- [ ] Expected outcomes are clear
- [ ] Edge cases are identified
- [ ] Security scenarios are tested (if applicable)
- [ ] Browser/environment specifics noted (if relevant)
- [ ] Success criteria are objective, not subjective

**Bad Example:**
```
- [ ] Test that it works
```

**Good Example:**
```
- [ ] Activate plugin without PHP errors
- [ ] Navigate to Tracking Scripts menu
- [ ] Create test post with GA4 tracking code
- [ ] Verify meta fields save and persist
- [ ] Verify non-admin users cannot access
```

### Completeness

- [ ] Description explains why this ticket exists
- [ ] Context is sufficient for someone not on the team
- [ ] No critical information is missing
- [ ] Relationships to other features are documented
- [ ] User story includes user role and benefit (if applicable)
- [ ] Technical task includes rationale
- [ ] All acceptance criteria have corresponding test steps

### Priority & Estimation

**For User Stories:**
- [ ] Priority is appropriate (P0-P3)
- [ ] Story Points are in Fibonacci sequence (1,2,3,5,8,13)
- [ ] Points match complexity compared to other stories
- [ ] Time estimate aligns with points (roughly 30 min per point)

**For Technical Tasks:**
- [ ] Priority is appropriate (P0-P3)
- [ ] Time estimate is realistic
- [ ] All sub-tasks add up to total estimate
- [ ] Time includes implementation + testing + review

**Priority Validation:**
- [ ] P0 items are truly blocking other work
- [ ] P0 items are fewer than P1 items
- [ ] P1 items are required for release quality
- [ ] P2/P3 items are truly optional

### WordPress Standards

- [ ] Naming follows conventions (GAP_*, gap_*, _gap_*)
- [ ] Meta keys start with `_gap_`
- [ ] Class names start with `GAP_`
- [ ] Function names start with `gap_`
- [ ] Uses appropriate WordPress hooks
- [ ] Security practices mentioned (nonce, capabilities, sanitization)

### Technical Accuracy

- [ ] File paths are relative to project root
- [ ] Class structure matches WordPress patterns
- [ ] Database queries would use wpdb (if applicable)
- [ ] Functions would use WordPress APIs (not PHP functions)
- [ ] No security vulnerabilities in proposed approach
- [ ] Approach avoids conflicting with other epics

### Integration Points

- [ ] Integration with other teams/epics is documented
- [ ] File ownership is clear
- [ ] No conflicts with other epics
- [ ] Merge order implications are noted
- [ ] Parallel development considerations documented

---

## Pre-Commit Checks (After Completing Ticket)

### Definition of Done Verification
- [ ] All acceptance criteria met and tested
- [ ] All implementation tasks completed
- [ ] Code follows WordPress coding standards
- [ ] No debug code (var_dump, print_r, die, console.log)
- [ ] Security checks passed
- [ ] Manual testing completed
- [ ] Code committed to git
- [ ] Documentation updated

### Code Quality
- [ ] No PHP errors or warnings
- [ ] ABSPATH check in all PHP files
- [ ] Proper escaping of output (esc_html, esc_attr, esc_url)
- [ ] Input sanitization (sanitize_text_field, wp_kses_post, absint)
- [ ] Nonce verification for forms
- [ ] Capability checks (current_user_can)
- [ ] Proper namespacing (GAP_ prefix)
- [ ] Inline comments on complex logic
- [ ] Docblocks on classes and methods

### Git Commit
- [ ] Commit message includes ticket ID (US-XX.Y or TT-XX.Y)
- [ ] Commit follows conventional commit format
- [ ] Files relevant to ticket are committed
- [ ] No unrelated changes included
- [ ] Commit message describes changes clearly

---

## Issue Resolution Checklist

### If Acceptance Criteria Are Too Vague
**Problem:** Criteria like "admin interface works"
**Solution:** Rewrite with specific, observable outcomes
- [ ] Specify what appears where
- [ ] Specify user permissions
- [ ] Specify data validation
- [ ] Use acceptance testing language

### If Tasks Are Too Large
**Problem:** Single task takes > 2 hours
**Solution:** Break into smaller tasks
- [ ] Identify logical sub-steps
- [ ] Each task 30 min - 2 hours
- [ ] Update time estimates
- [ ] Maintain overall estimate

### If Story Points Don't Match Time
**Problem:** 8-point story estimated at 30 minutes
**Solution:** Adjust one or both
- [ ] 8 points ≈ 4 hours of work
- [ ] Revisit task breakdown
- [ ] Reconsider complexity
- [ ] Compare to similar stories

### If Dependencies Are Unclear
**Problem:** "Depends on admin interface" (too vague)
**Solution:** Specify exact tickets/epics
- [ ] Link to specific ticket IDs (US-02.1)
- [ ] Link to specific epic (EPIC-02)
- [ ] Explain the dependency
- [ ] Note merge order if applicable

### If Technical Details Are Missing
**Problem:** No class names or file paths
**Solution:** Add specific technical information
- [ ] File paths from project root
- [ ] WordPress hooks and filters used
- [ ] Class names and methods
- [ ] Database tables and meta keys
- [ ] Configuration values

---

## Epic-Level Quality Review

When all tickets in an epic are ready, verify:

- [ ] All user stories have corresponding technical tasks
- [ ] Story points total is reasonable (typically 15-30 for an epic)
- [ ] Dependencies between stories/tasks are clear
- [ ] No orphaned tickets (stories without supporting tasks)
- [ ] Merge order with other epics is documented
- [ ] Integration points are documented
- [ ] Epic README summarizes all tickets

---

## Common Quality Issues

### Issue: Too Many Small Tasks
**Symptom:** 15+ tasks with 15-minute estimates
**Solution:** Group related tasks
**Result:** 5-8 focused tasks that flow logically

### Issue: Unrealistic Estimates
**Symptom:** 1-hour task for complete feature
**Solution:** Add sub-tasks, get team input
**Result:** Honest estimates that enable planning

### Issue: Acceptance Criteria = Implementation Tasks
**Symptom:** Acceptance criteria and tasks are identical
**Solution:** Separate requirements (what) from steps (how)
**Result:** Criteria describe outcomes, tasks describe process

### Issue: Missing Security Details
**Symptom:** No mention of nonces or capability checks
**Solution:** Add security checklist to Definition of Done
**Result:** All tickets include security considerations

### Issue: No Testing Plan
**Symptom:** "Test it works" in requirements
**Solution:** Write specific test steps with expected outputs
**Result:** Clear, repeatable testing procedure

---

## Approval Gates

**Tickets MUST pass all of these to be considered "Ready":**
1. ✅ All required template sections filled
2. ✅ All acceptance criteria are testable
3. ✅ All tasks have time estimates
4. ✅ No placeholder text in critical sections
5. ✅ Dependencies are clear and specific
6. ✅ Technical details include file paths and class names
7. ✅ Testing requirements are concrete and repeatable
8. ✅ Definition of Done is complete

**If ANY gate fails:** Update the ticket before starting work.
