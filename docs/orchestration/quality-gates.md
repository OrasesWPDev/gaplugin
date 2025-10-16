# Quality Gates

Mandatory checks that block progression - NO CODE MOVES FORWARD without passing all gates.

---

## Gate 1: WordPress Coding Standards

**When:** After each ticket implementation
**Tool:** PHPCS with WordPress-VIP-Go ruleset
**Blocking:** YES - Ticket cannot commit without passing

**Checks:**
- ✓ Function naming: `gap_function_name()`
- ✓ Class naming: `GAP_Class_Name`
- ✓ File naming: `class-gap-name.php`
- ✓ Constant naming: `GAP_CONSTANT_NAME`
- ✓ Array declaration syntax (short array syntax)
- ✓ Yoda conditions for comparisons
- ✓ Single quotes for strings
- ✓ Proper indentation (tabs, not spaces)
- ✓ Documentation blocks present

**Failure Example:**
```
❌ PHPCS: Function 'getTrackingScript' should be named 'gap_get_tracking_script'
   File: includes/class-gap-frontend.php:42

[🔧 Auto-Fix] [✏️ Review] [🔙 Reassign]
```

---

## Gate 2: Security Audit

**When:** After each ticket implementation
**Tool:** Manual + automated security checks
**Blocking:** YES - Ticket cannot commit without passing

**Checks:**
- ✓ ABSPATH check in every PHP file
- ✓ Capability checks (if user actions)
- ✓ Nonce verification (if forms)
- ✓ Input sanitization (if accepting data)
- ✓ Output escaping (if displaying data)
- ✓ No hardcoded credentials
- ✓ No direct SQL queries (not applicable in v1.0)
- ✓ No eval() or dynamic code execution

**Failure Example:**
```
❌ SECURITY: Missing ABSPATH check
   File: includes/class-gap-cpt.php:1
   Issue: File should start with if (!defined('ABSPATH')) exit;

[🔧 Auto-Fix] [📋 Review] [🔙 Reassign]
```

---

## Gate 3: Automated Tests

**When:** After each ticket implementation + final epic test
**Tool:** PHPUnit + WordPress test framework
**Blocking:** YES - Code cannot commit without passing

**Checks:**
- ✓ All unit tests pass
- ✓ All integration tests pass
- ✓ No PHP errors in tests
- ✓ No PHP warnings
- ✓ Code coverage meets minimum (70%)

**Failure Example:**
```
❌ TEST FAILURE: test_autoloader_loads_class
   Expected: GAP_CPT loaded
   Got: Class not found

   Stack trace:
   includes/class-gap-cpt.php:45 - call to undefined function

[🔙 Debug] [📋 Review] [🔙 Reassign]
```

---

## Gate 4: No Debug Code

**When:** Before commit
**Tool:** Automated search for debug patterns
**Blocking:** YES - Ticket cannot commit with debug code

**Prohibited:**
- ✗ `var_dump()`
- ✗ `print_r()`
- ✗ `echo 'debug'`
- ✗ `error_log('debug')`
- ✗ `die()`
- ✗ `var_export()`
- ✗ `wp_die()` (unless intentional)
- ✗ `phpinfo()`

**Failure Example:**
```
❌ DEBUG CODE FOUND
   File: includes/class-gap-cpt.php:156
   Issue: var_dump($post_data);

   Remove all debug code before commit.

[🔧 Auto-Fix] [✏️ Review] [🔙 Reassign]
```

---

## Gate 5: Epic-Level Testing

**When:** All tickets complete (before PR)
**Tool:** Full test suite + integration tests
**Blocking:** YES - PR cannot create without passing

**Comprehensive Checks:**
- ✓ Plugin activates without errors
- ✓ Plugin deactivates without errors
- ✓ All classes load successfully
- ✓ All hooks register correctly
- ✓ Autoloader works for all classes
- ✓ No PHP errors in error_log
- ✓ No PHP warnings
- ✓ Constants defined correctly
- ✓ All tests pass (>95% success rate)

**Failure Example:**
```
❌ INTEGRATION TEST FAILED
   Issue: Plugin fails to activate
   Error: Undefined function gap_init()
   Location: ga-plugin.php:95

   This blocks PR creation. Fix and re-test.

[🔧 Auto-Fix] [📋 Review] [🔙 Reassign]
```

---

## Gate 6: Security Audit (Epic-Level)

**When:** All tickets complete (before PR)
**Tool:** Comprehensive security scan
**Blocking:** YES - PR cannot create without passing

**Full Checks:**
- ✓ No SQL injection vectors
- ✓ No XSS vulnerabilities
- ✓ No privilege escalation
- ✓ No direct file access
- ✓ No hardcoded secrets
- ✓ ABSPATH checks present everywhere
- ✓ Capability checks for admin functions
- ✓ Nonce verification for forms
- ✓ Proper escaping everywhere

**Failure Example:**
```
❌ SECURITY VULNERABILITY
   Type: Unescaped output
   Location: includes/class-gap-admin.php:234
   Issue: echo $_GET['id'];

   Output must be escaped with esc_attr(), esc_html(), or esc_url()

[🔧 Auto-Fix] [📋 Review] [🔙 Reassign]
```

---

## Blocking Conditions

### A Ticket is BLOCKED if:

1. ❌ Standards check fails
2. ❌ Security audit fails
3. ❌ Tests don't pass
4. ❌ Debug code present
5. ❌ Dependencies not met (upstream ticket not complete)
6. ❌ File ownership conflict with other epic

### An Epic is BLOCKED if:

1. ❌ Any ticket fails quality gate
2. ❌ Integration tests fail
3. ❌ Security audit fails
4. ❌ Code coverage below 70%
5. ❌ Upstream epic incomplete
6. ❌ File ownership conflicts unresolved

### A PR is BLOCKED if:

1. ❌ Epic tests fail
2. ❌ Security audit fails
3. ❌ Code review pending
4. ❌ Merge conflicts exist
5. ❌ Branch not up to date with main

---

## Gate Override Protocol

### When Can Gates Be Overridden?

**Answer: NEVER**

Gates are absolute. If code fails a gate, it does not proceed. No exceptions.

**Why:**
- Maintains code quality
- Prevents security vulnerabilities
- Ensures consistency
- Protects team and users

---

## Quality Gate Dashboard

**During Execution:**

```
QUALITY GATE STATUS - EPIC-01

Ticket: US-01.1 (Plugin Activation)
Status: READY FOR COMMIT ✅

Gate 1: WordPress Coding Standards
├─ Function Naming: ✅ PASS
├─ Class Naming: ✅ PASS
├─ File Naming: ✅ PASS
└─ Indentation: ✅ PASS
Result: ✅ PASS

Gate 2: Security Audit
├─ ABSPATH Check: ✅ PASS
├─ No Hardcoded Secrets: ✅ PASS
├─ No Direct Access: ✅ PASS
└─ Proper Escaping: ✅ PASS
Result: ✅ PASS

Gate 3: Automated Tests
├─ Unit Tests: ✅ 15/15 PASS
├─ Integration Tests: ✅ 3/3 PASS
├─ Coverage: ✅ 85% (>70% required)
└─ No Errors/Warnings: ✅ PASS
Result: ✅ PASS

Gate 4: No Debug Code
└─ Clean: ✅ PASS
Result: ✅ PASS

═══════════════════════════════════════
ALL GATES PASSED ✅
Ready to commit: YES
```

---

## Recovery from Failed Gates

**When a Gate Fails:**

```
1. Orchestrator: "Gate failed, sending back to developer"
2. wordpress-developer: "Received failure report, fixing..."
3. wordpress-developer: "Re-running through all gates..."
4. If all pass: "Commit ready"
   If any fail: "Still failing, investigating..."
5. Repeat until all gates pass
```

**No Progression Without ALL Gates Passing**

---

## Gate Performance Impact

**Quality gates add time:**
- PHPCS check: 1-2 minutes
- Security audit: 2-3 minutes
- Tests: 2-5 minutes
- Debug scan: <1 minute

**Total per ticket: ~10-15 minutes**

**Worth it because:**
- Prevents bugs reaching production
- Prevents security vulnerabilities
- Maintains code quality
- Saves rework/debugging time
- Ensures consistency

---

## Configuration

**Gates can be configured:**

```json
{
  "quality_gates": {
    "blocking": [
      "wordpress_standards",
      "security_audit",
      "automated_tests",
      "no_debug_code"
    ],
    "warning_only": [],
    "skip_gates": false,
    "auto_fix": {
      "enabled": true,
      "debug_code": true,
      "standards": false
    }
  }
}
```

**Note:** Blocking gates cannot be disabled. Warning gates can be reviewed but don't block.

---

**Last Updated:** 2025-10-16 | **Owner:** subagent-orchestrator | **Related:** [Orchestration Workflow](orchestration-workflow.md), [Failure Recovery](failure-recovery.md)
