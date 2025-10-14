# Test Component Command

Test an individual component in a proper WordPress environment.

## Usage
```
/test-component [component-name]
```

## What This Does

This command tests a specific component or class to verify it works correctly in a WordPress environment:

1. **Identify component** - Determine which file(s) to test
2. **Set up test environment** - Ensure WordPress is loaded
3. **Run component tests** - Execute functionality checks
4. **Report results** - Show what worked and what failed

## Available Components

### CPT (Custom Post Type)
```bash
/test-component cpt
```
**Tests:**
- CPT registers successfully
- Post type slug is correct (gap_tracking)
- Labels display correctly in admin
- Meta fields are registered
- Admin columns appear
- Capabilities work correctly

### Meta Box
```bash
/test-component meta-box
```
**Tests:**
- Meta boxes appear in post editor
- Fields render correctly
- Nonces are present
- Save functionality works
- Data sanitization works
- Validation catches errors

### Conflict Detector
```bash
/test-component conflict-detector
```
**Tests:**
- GA4 scripts detected correctly
- GTM scripts detected correctly
- Regex patterns match variations
- Cache works correctly
- HTML scanning is accurate
- Warnings display properly

### Frontend Output
```bash
/test-component frontend-output
```
**Tests:**
- Scripts output in correct location (head/body)
- Scope filtering works (global, posts only, etc.)
- Caching functions correctly
- Multiple scripts handled properly
- Escaping is correct
- Hooks fire at right time

### Autoloader
```bash
/test-component autoloader
```
**Tests:**
- Classes load correctly
- Namespacing works
- No fatal errors
- All expected classes found

## Test Methods

### Manual Testing
For components that need admin interface:

```php
// Activate plugin
wp plugin activate ga-plugin

// Navigate to tracking scripts
// Create a new tracking script
// Fill in fields
// Save and verify data persists
```

### PHP Unit Tests
For components that can be tested programmatically:

```php
// Test CPT registration
function test_cpt_registration() {
    $post_type = get_post_type_object( 'gap_tracking' );

    if ( null === $post_type ) {
        return 'FAIL: Post type not registered';
    }

    if ( 'gap_tracking' !== $post_type->name ) {
        return 'FAIL: Wrong post type name';
    }

    return 'PASS: CPT registered correctly';
}
```

### WordPress CLI Tests
For components that can be tested via WP-CLI:

```bash
# Test post type exists
wp post-type list --field=name | grep gap_tracking

# Create test post
wp post create --post_type=gap_tracking --post_title="Test Script" --post_status=publish

# Get meta
wp post meta get [post-id] _gap_tracking_code
```

## Implementation

When this command is executed, perform the following steps:

1. **Validate component name:**
   ```
   Valid components: cpt, meta-box, conflict-detector, frontend-output, autoloader
   ```

2. **Load WordPress environment:**
   ```
   Ensure WordPress is available
   Plugin should be activated
   ```

3. **Run component-specific tests:**
   ```
   Execute tests appropriate for the component
   Capture output and errors
   ```

4. **Report results:**
   ```
   # Component Test: [component-name]

   ## Tests Run: [count]
   ## Passed: [count]
   ## Failed: [count]

   ## Details
   [Specific test results]

   ## Errors
   [Any errors encountered]

   ## Next Steps
   [Recommended fixes if failures]
   ```

## Test Scripts

### CPT Test Script
```php
<?php
// Test CPT registration
function gap_test_cpt() {
    $tests = array();

    // Test 1: Post type exists
    $post_type = get_post_type_object( 'gap_tracking' );
    $tests['post_type_exists'] = null !== $post_type;

    // Test 2: Labels correct
    $tests['labels_exist'] = isset( $post_type->labels->name );

    // Test 3: Meta registered
    $meta_keys = get_registered_meta_keys( 'post', 'gap_tracking' );
    $tests['meta_registered'] = ! empty( $meta_keys );

    return $tests;
}
```

### Meta Box Test Script
```php
<?php
// Test meta box functionality
function gap_test_meta_box() {
    $tests = array();

    // Create test post
    $post_id = wp_insert_post( array(
        'post_type'   => 'gap_tracking',
        'post_title'  => 'Test Script',
        'post_status' => 'draft',
    ) );

    if ( is_wp_error( $post_id ) ) {
        return array( 'error' => $post_id->get_error_message() );
    }

    // Test 1: Save meta
    update_post_meta( $post_id, '_gap_tracking_code', 'G-TEST123456' );
    $saved = get_post_meta( $post_id, '_gap_tracking_code', true );
    $tests['meta_save'] = 'G-TEST123456' === $saved;

    // Test 2: Delete test post
    wp_delete_post( $post_id, true );
    $tests['cleanup'] = null === get_post( $post_id );

    return $tests;
}
```

### Conflict Detector Test Script
```php
<?php
// Test conflict detection
function gap_test_conflict_detector() {
    $tests = array();

    $detector = new GAP_Conflict_Detector();

    // Test HTML with GA4
    $html_with_ga4 = '<script async src="https://www.googletagmanager.com/gtag/js?id=G-TESTCODE1"></script>';

    $found = $detector->gap_scan_html( $html_with_ga4, false );
    $tests['detects_ga4'] = ! empty( $found ) && 'G-TESTCODE1' === $found[0]['tracking_id'];

    // Test HTML with GTM
    $html_with_gtm = '<script>googletagmanager.com/gtm.js?id=GTM-TEST123</script>';

    $found = $detector->gap_scan_html( $html_with_gtm, false );
    $tests['detects_gtm'] = ! empty( $found ) && 'GTM-TEST123' === $found[0]['tracking_id'];

    return $tests;
}
```

## Success Criteria

A component test is successful when:
- [ ] Component loads without fatal errors
- [ ] All expected functionality works
- [ ] Data persists correctly (if applicable)
- [ ] No PHP warnings or notices
- [ ] Results match expected behavior

## When to Use

Use this command:
- **During development** - Test as you build
- **After making changes** - Verify nothing broke
- **When debugging** - Isolate problem component
- **Before phase review** - Pre-check before full review

## WordPress Environment Requirements

Tests require:
- WordPress installed and configured
- Plugin activated
- Database accessible
- Proper file permissions
- WP_DEBUG enabled (recommended)

## Notes

- Tests should be **non-destructive** when possible
- Use `wp_delete_post( $id, true )` to clean up test data
- Check for `is_wp_error()` on all WordPress operations
- Some tests may require admin user context

## Related Commands

- `/review-phase` - Full security and code review
- `/build-phase` - Build a specific phase
- `/switch-to-sequential` - Change workflow approach

## Troubleshooting

### WordPress Not Loaded
If WordPress isn't available:
```bash
# Load WordPress manually
require_once '/path/to/wp-load.php';
```

### Plugin Not Activated
If plugin isn't active:
```bash
wp plugin activate ga-plugin
```

### Permission Issues
If you can't create posts:
```php
// Set current user to admin
wp_set_current_user( 1 );
```
