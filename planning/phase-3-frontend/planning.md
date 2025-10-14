# Phase 3: Frontend Output - Script Injection

**Estimated Time:** 2-3 hours

**Status:** Not Started

## Overview

This phase implements frontend script output: querying active scripts, filtering by scope, hooking into WordPress at correct placements, and integrating duplicate detection to prevent conflicts.

## Dependencies

- **Phase 1** must be complete (CPT registered, autoloader working)
- **Phase 2.5** must be complete (conflict detector needed for duplicate prevention)

## Deliverables

- Frontend output class (`GAP_Frontend_Output`)
- Hook integration (wp_head, wp_footer)
- Query logic with caching
- Scope filtering (global, posts only, pages only, etc.)
- Script rendering for GA4, GTM, and custom scripts
- Duplicate detection integration
- Conflict prevention with logging

## Completion Criteria

- [ ] Scripts output to correct location (head or body)
- [ ] Global scripts appear on all pages
- [ ] Scoped scripts only appear where configured
- [ ] Caching minimizes database queries
- [ ] Duplicate scripts prevented
- [ ] All output properly escaped
- [ ] Conflicts logged in HTML comments

---

## Hook Integration

### WordPress Hooks Used

```php
add_action('wp_head', array($this, 'gap_output_head_scripts'), 10);
add_action('wp_footer', array($this, 'gap_output_footer_scripts'), 10);
```

### Hook Priority

- Priority `10` (default) - runs with most theme/plugin scripts
- Can be adjusted if needed for specific load order

---

## File Structure

```
wp-content/plugins/ga-plugin/
└── includes/
    └── frontend/
        └── class-gap-frontend-output.php
```

---

## Implementation Reference

**See master plan for complete implementation code:**

- **Master Plan Location:** `@TRACKING-SCRIPT-MANAGER-PLAN.md` (Lines 581-796)
- **Key Section:** Phase 4: Frontend Output (`class-tsm-frontend.php`)

**Important Notes for Implementation:**

1. **Update all TSM references to GAP:**
   - Class name: `GAP_Frontend_Output` (not TSM_Frontend)
   - Function prefix: `gap_` (not `tsm_`)
   - Cache keys: `gap_compiled_scripts_head`, `gap_compiled_scripts_body`
   - Text domain: `ga-plugin` (not `tracking-script-manager`)

2. **Meta Key Changes:**
   - Use `_gap_` prefix instead of `_tsm_`
   - Example: `_gap_placement` instead of `_tsm_placement`

3. **Post Type Reference:**
   - Use `gap_tracking` instead of `tracking_script`

4. **Placement Values:**
   - `head` - outputs in wp_head
   - `body` - outputs in wp_footer

---

## Scope Filtering

### Available Scopes

| Scope | WordPress Function | Description |
|-------|-------------------|-------------|
| `global` | Always true | Output on all pages |
| `posts_only` | `is_single() && 'post' === get_post_type()` | Only single post pages |
| `pages_only` | `is_page()` | Only pages |
| `frontend_only` | `!is_admin()` | All frontend (no admin) |
| `home_only` | `is_front_page() \|\| is_home()` | Homepage only |

### Custom Scope Extension

```php
// Allow custom scopes via filter
$should_display = apply_filters('gap_custom_scope_check', false, $script, $scope);
```

---

## Caching Strategy

### Cache Keys

```php
const CACHE_KEY_HEAD = 'gap_compiled_scripts_head';
const CACHE_KEY_BODY = 'gap_compiled_scripts_body';
const CACHE_DURATION = 3600; // 1 hour
```

### Cache Logic

1. **Check cache** for placement (head or body)
2. **If cached:** Return cached scripts, then filter by scope for current page
3. **If not cached:** Query database, cache results, then filter by scope

### Why Filter After Caching

- Cache all scripts regardless of scope (scope-agnostic)
- Filter scripts by scope at render time (current page context)
- Prevents needing separate cache for each page type

---

## Script Output Formats

### GA4 (Google Analytics 4)

```html
<!-- Google Analytics 4 - Script Title -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXXXXX');
</script>
```

### GTM (Google Tag Manager)

**Head Script:**

```html
<!-- Google Tag Manager - Script Title -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-XXXXXX');</script>
<!-- End Google Tag Manager -->
```

**Body Noscript (if placement = body):**

```html
<!-- Google Tag Manager (noscript) - Script Title -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXXXX"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
```

### Custom Scripts

```html
<!-- Custom Script - Script Title -->
{custom_code_from_meta}
<!-- End Custom Script - Script Title -->
```

---

## Duplicate Detection Integration

### Before Output

```php
// Get tracking IDs from current script
$extracted_ids = get_post_meta($script->ID, '_gap_extracted_ids', true);

// Scan page HTML for existing scripts
$detector = GAP_Conflict_Detector::get_instance();
$found_ids = $detector->gap_scan_html($html, $extracted_ids);

// If duplicate found, skip output
if (!empty($found_ids)) {
    echo "<!-- GA Plugin: Skipped \"{$script->post_title}\" - duplicate detected -->\n";
    continue;
}
```

### Conflict Logging

```php
// Log to debug log if WP_DEBUG enabled
$detector->log_conflict("Duplicate script skipped: {$script->post_title}");

// HTML comment for developers
echo "<!-- Duplicate IDs found: {$id_list} -->\n";
```

---

## Performance Optimization

### 1. Request-Level Caching

```php
private $scripts_cache = array();

if (isset($this->scripts_cache[$placement])) {
    return $this->scripts_cache[$placement];
}
```

### 2. Single Database Query

```php
// One query gets all scripts for placement
$args = array(
    'post_type' => 'gap_tracking',
    'meta_query' => array(
        array('key' => '_gap_placement', 'value' => $placement),
        array('key' => '_gap_enabled', 'value' => '1')
    )
);
```

### 3. Transient Caching

```php
// Cache compiled scripts for 1 hour
set_transient(self::CACHE_KEY_HEAD, $scripts, self::CACHE_DURATION);
```

---

## Security Considerations

### Output Escaping

```php
// Escape tracking IDs in URLs
<script src="<?php echo esc_url($url); ?>?id=<?php echo esc_attr($tracking_id); ?>"></script>

// Escape tracking IDs in JavaScript
gtag('config', '<?php echo esc_js($tracking_id); ?>');

// Custom code already sanitized on save (wp_kses_post)
echo wp_kses_post($custom_code);
```

### Context Checks

```php
// Don't output in admin
if (is_admin()) {
    return;
}
```

---

## Testing Checklist

### Output Location

- [ ] Head scripts output in `<head>`
- [ ] Body scripts output before `</body>`
- [ ] Scripts don't output in admin
- [ ] Disabled scripts don't output

### Scope Filtering

- [ ] Global scripts on all pages
- [ ] Posts-only scripts only on single posts
- [ ] Pages-only scripts only on pages
- [ ] Home-only scripts only on homepage
- [ ] Frontend-only excludes admin

### Caching

- [ ] First request queries database
- [ ] Second request uses cache
- [ ] Cache clears on script save
- [ ] Cache expires after 1 hour

### Duplicate Prevention

- [ ] Existing GA4 script prevents duplicate
- [ ] Existing GTM script prevents duplicate
- [ ] HTML comment explains skip
- [ ] Conflict logged with WP_DEBUG

### Script Formats

- [ ] GA4 scripts render correctly
- [ ] GTM head scripts render correctly
- [ ] GTM noscript renders in body
- [ ] Custom scripts output as-is

---

## Agent Responsibilities

**Primary Agent:** `frontend-output-specialist`

- Creates frontend output class
- Implements hook integration
- Implements caching strategy
- Implements scope filtering
- Integrates conflict detection

**Support Agent:** `conflict-detector-specialist`

- Assists with conflict detection integration
- Verifies HTML scanning works
- Ensures cache coordination

**Review Agent:** `wp-security-scanner`

- Checks output escaping
- Verifies no XSS vulnerabilities
- Confirms admin context check

**Review Agent:** `wp-code-reviewer`

- Reviews caching implementation
- Checks query optimization
- Verifies WordPress standards

---

## Git Workflow for This Phase

### Branch Information
- **Branch name:** `phase-3-frontend`
- **Created from:** `main` (after Phase 1, 2, AND 2.5 all merged)
- **Merges to:** `main`
- **Merge dependencies:** Phase 1, Phase 2, AND Phase 2.5 must all be merged first
- **Unblocks:** Phase 4 (testing)

### Starting This Phase
```bash
/start-phase 3
```

**IMPORTANT:** Verify all dependencies merged before starting:
```bash
git log main --oneline | grep -E "Phase (1|2|2.5)"
# Should show all three phases merged
```

### Commit Strategy

- [ ] **Commit 1:** Frontend output class structure
  ```bash
  git add includes/frontend/class-gap-frontend-output.php
  git commit -m "feat(frontend): create frontend output class

  - Add GAP_Frontend_Output class
  - Implement singleton pattern
  - Add hook registration

  Addresses: Phase 3 deliverable (frontend class)"
  git push
  ```

- [ ] **Commit 2:** Hook integration
  ```bash
  git add includes/frontend/class-gap-frontend-output.php
  git commit -m "feat(frontend): integrate WordPress hooks

  - Hook into wp_head
  - Hook into wp_footer
  - Add priority configuration

  Addresses: Phase 3 deliverable (hook integration)"
  git push
  ```

- [ ] **Commit 3:** Scope filtering
  ```bash
  git add includes/frontend/class-gap-frontend-output.php
  git commit -m "feat(frontend): implement scope filtering

  - Add global scope logic
  - Add posts_only filtering
  - Add pages_only filtering
  - Add frontend_only filtering
  - Add home_only filtering

  Addresses: Phase 3 deliverable (scope filtering)"
  git push
  ```

- [ ] **Commit 4:** Script rendering and caching
  ```bash
  git add includes/frontend/class-gap-frontend-output.php
  git commit -m "feat(frontend): add script rendering and caching

  - Implement script output logic
  - Add transient caching
  - Integrate conflict detector
  - Add duplicate prevention

  Addresses: Phase 3 deliverable (rendering, caching, conflict prevention)"
  git push
  ```

### PR Template
**Title:** `Phase 3: Frontend Output Handler`

Use `/finish-phase 3` to auto-generate PR description.

### File Ownership
**Phase 3 owns:**
- `includes/frontend/class-gap-frontend-output.php`

**Dependencies (do not modify):**
- `includes/class-gap-conflict-detector.php` (Phase 2.5 - use only, don't modify)

---

## Next Steps

After this phase is complete:

1. Run `/review-phase 3` to check security and code quality
2. Run `/test-component frontend-output` to verify functionality
3. Test on actual WordPress site with real tracking codes
4. Run `/finish-phase 3` to create pull request
5. Proceed to **Phase 4** (Testing & Security Audit)

---

## References

- Master Plan: `@TRACKING-SCRIPT-MANAGER-PLAN.md` (Lines 581-796)
- WordPress Plugin API: https://developer.wordpress.org/plugins/hooks/
- Conditional Tags: https://developer.wordpress.org/themes/basics/conditional-tags/
- Transients API: https://developer.wordpress.org/apis/transients/
- Data Escaping: https://developer.wordpress.org/apis/security/escaping/
