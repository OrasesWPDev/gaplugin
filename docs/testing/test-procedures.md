# Test Procedures Reference

Detailed testing procedures for GA Plugin. See [local-testing-specialist agent](./.claude/agents/local-testing-specialist.md) for automated testing execution.

## Pre-Testing

1. Deploy latest code to local environment
2. Activate plugin in WordPress admin
3. Check debug.log is clean
4. Create fresh test data if needed

## Test Categories

### 1. Plugin Activation Test
- [ ] Plugin activates without errors
- [ ] No PHP warnings in debug.log
- [ ] Admin menu appears
- [ ] Custom post type registered

### 2. Custom Post Type Test
- [ ] CPT menu item visible
- [ ] List view loads without errors
- [ ] Add New button works
- [ ] Custom columns display correctly

### 3. Meta Fields Test
- [ ] Script content field saves
- [ ] Placement field saves
- [ ] Scope field saves
- [ ] Target pages field saves
- [ ] Active checkbox works
- [ ] Fields persist after edit

### 4. Frontend Output Test
- [ ] Head placement correct
- [ ] Body top placement correct
- [ ] Body bottom placement correct
- [ ] Footer placement correct
- [ ] Global scope works
- [ ] Specific scope works
- [ ] Inactive scripts don't output

### 5. Conflict Detection Test
- [ ] Admin detects duplicate IDs
- [ ] Frontend skips duplicates
- [ ] Debug logging works
- [ ] HTML comments present

### 6. Security Audit
- [ ] ABSPATH checks present
- [ ] Nonce verification works
- [ ] Capability checks enforced
- [ ] Input sanitized
- [ ] Output escaped
- [ ] SQL injection blocked
- [ ] XSS blocked
- [ ] CSRF protected

### 7. Coding Standards
- [ ] PHPCS: 0 errors
- [ ] Naming conventions
- [ ] Docblocks complete
- [ ] No debug code

## Running Tests Locally

```bash
# Activate plugin
wp plugin activate ga-plugin

# Create test post
POST_ID=$(wp post create --post_type=tracking_script --post_title="Test" --porcelain)

# Set meta fields
wp post meta add $POST_ID _gap_script_content "<script>gtag('config', 'G-TEST');</script>"
wp post meta add $POST_ID _gap_placement "head"
wp post meta add $POST_ID _gap_scope "global"
wp post meta add $POST_ID _gap_is_active "1"

# Verify saved
wp post meta get $POST_ID _gap_script_content

# Check frontend output
curl -s http://localhost:10029/ | grep "gtag"

# Check debug log
tail -n 50 wp-content/debug.log
```

## Documentation

See detailed test procedures in [local-testing-specialist](./.claude/agents/local-testing-specialist.md) agent documentation.
