# Quality Gates

Requirements that block PR creation. ALL must pass before PR can be created.

## Blocking Requirements

**Plugin Activation**
- [ ] Plugin activates without PHP fatal errors
- [ ] No PHP warnings in debug.log
- [ ] Custom post type appears in admin menu

**Meta Fields**
- [ ] All meta fields save correctly
- [ ] Fields persist after edit
- [ ] Validation works properly

**Frontend Output**
- [ ] Scripts output in correct locations
- [ ] Scope filtering works correctly
- [ ] Inactive scripts don't output

**Security Audit**
- [ ] ABSPATH check in all PHP files
- [ ] Nonce verification implemented
- [ ] Capability checks enforced
- [ ] Input sanitized (wp_kses_post, sanitize_text_field)
- [ ] Output escaped (esc_html, esc_attr, esc_url)

**Coding Standards**
- [ ] 0 PHP errors in PHPCS
- [ ] Naming conventions followed (GAP_* prefix)
- [ ] All methods have docblocks
- [ ] No debug code (var_dump, print_r, die)

**Test Coverage**
- [ ] All acceptance criteria tested
- [ ] Manual testing documented
- [ ] Edge cases tested
- [ ] Browser compatibility verified

## PR Creation Block Conditions

PR creation is **BLOCKED** if ANY of:
- ❌ Plugin doesn't activate
- ❌ PHP errors/warnings in debug.log
- ❌ Meta fields don't save correctly
- ❌ Frontend output in wrong location
- ❌ Security vulnerabilities found
- ❌ PHPCS errors present
- ❌ Acceptance criteria not met

## Gate Verification Process

1. **Deploy latest code** to local environment
2. **Run full test suite** (activation, meta fields, frontend, security)
3. **Review debug.log** for errors/warnings
4. **Run PHPCS** for coding standards
5. **Verify all gates pass** before proceeding
6. **Document results** in test report
7. **Create PR** only if all gates pass

## See Also

- [Test Procedures](test-procedures.md) - How to run tests
- [Test Templates](test-templates.md) - How to document results
- [Environment Setup](environment-setup.md) - Where to test
