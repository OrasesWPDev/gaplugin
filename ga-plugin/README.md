# GA Plugin

Enterprise-grade Google Analytics 4 (GA4) and Google Tag Manager (GTM) tracking script management for WordPress with automatic duplicate detection and flexible placement control.

---

## Description

GA Plugin provides professional-level management of tracking scripts in WordPress with intelligent conflict detection and prevention. Whether you're managing a single GA4 property or multiple tracking scripts across complex multi-site installations, GA Plugin ensures clean, conflict-free analytics implementation.

### Key Benefits
- Prevent double-tracking and data corruption from duplicate scripts
- Granular control over script placement and execution
- Easy management through familiar WordPress interface
- Performance-optimized with request-level caching
- Security-first implementation following WordPress best practices

---

## Features

### Core Features
- **Custom Post Type Interface**: Manage tracking scripts like WordPress posts with familiar editing experience
- **Automatic ID Extraction**: Automatically detects GA4 (G-XXXXXXXXXX) and GTM (GTM-XXXXXXX) tracking IDs
- **Duplicate Prevention**: Scans page HTML to prevent double-tracking from theme/plugin conflicts
- **Multiple Placement Options**:
  - Head (before `</head>`)
  - Body Top (after `<body>`)
  - Body Bottom (before `</body>`)
  - Footer (wp_footer hook)
- **Flexible Scope Control**:
  - Global (all pages)
  - Specific Pages (choose individual pages)
- **Active/Inactive Toggle**: Disable scripts without deleting them

### Advanced Features
- **Real-time Conflict Detection**: Admin warnings when duplicate tracking IDs detected
- **HTML Scanning**: Detects existing scripts added by themes or other plugins
- **Request-Level Caching**: Optimized performance (≤2 database queries per page)
- **Admin Columns**: View tracking IDs, placement, scope, and status at a glance
- **Debug Logging**: Conflict information logged for troubleshooting
- **HTML Comments**: Debug comments in page source for verification

### Security Features
- **Nonce Verification**: All forms protected against CSRF attacks
- **Capability Checks**: Requires `manage_options` permission
- **Input Sanitization**: All user input properly sanitized (wp_kses_post)
- **Output Escaping**: All output properly escaped for context
- **ABSPATH Checks**: Direct file access prevented

---

## Requirements

| Requirement | Version |
|-------------|---------|
| **WordPress** | 6.0 or higher |
| **PHP** | 7.4 or higher |
| **MySQL** | 5.7 or higher |
| **MariaDB** | 10.3 or higher |

**Recommended:**
- WordPress 6.4+
- PHP 8.0+
- Modern browser for admin interface

---

## Installation

### From ZIP File

1. Download the latest release ZIP file
2. Go to **Plugins > Add New** in WordPress admin
3. Click **Upload Plugin**
4. Choose the ZIP file and click **Install Now**
5. Click **Activate** when installation completes

### Manual Installation

1. Upload the `ga-plugin` directory to `/wp-content/plugins/`
2. Activate the plugin through **Plugins** menu in WordPress
3. Navigate to **Tracking Scripts** in the admin menu
4. Add your first tracking script

### From GitHub

```bash
cd /path/to/wordpress/wp-content/plugins/
git clone https://github.com/OrasesWPDev/gaplugin.git ga-plugin
```

Then activate through WordPress admin.

---

## Quick Start Guide

### Adding Your First Tracking Script

1. Navigate to **Tracking Scripts > Add New**
2. Enter a descriptive title (e.g., "Main GA4 Tracking")
3. Paste your GA4 or GTM code in the **Script Content** field
4. **Select Placement:**
   - Choose **Head** for standard GA4/GTM implementation
5. **Set Scope:**
   - Choose **Global (all pages)** for site-wide tracking
6. **Check Active** to enable the script
7. Click **Publish**

### Example GA4 Script

```html
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXXXXX');
</script>
```

### Example GTM Script

```html
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-XXXXXXX');</script>
<!-- End Google Tag Manager -->
```

---

## Usage

### Script Placement Options

| Placement | Location | Use Case | Priority |
|-----------|----------|----------|----------|
| **Head** | Before `</head>` | Standard analytics, GA4, GTM | Recommended |
| **Body Top** | After `<body>` | Scripts requiring early page load | Alternative |
| **Body Bottom** | Before `</body>` | Performance-optimized loading | Performance |
| **Footer** | wp_footer hook | Late-loading scripts | Last resort |

**Recommendation:** Use **Head** placement for GA4 and GTM unless you have specific performance requirements.

### Scope Control

#### Global Scope
Scripts with global scope appear on every page of your site:
- Homepage
- All posts
- All pages
- Archives
- Search results

**Best for:** Site-wide tracking (GA4, GTM)

#### Specific Pages
Target individual pages by selecting them from the page selector:
1. Set **Scope** to **Specific Pages**
2. Check the pages where the script should appear
3. All other pages will NOT load this script

**Best for:**
- Landing page tracking
- Conversion tracking on specific pages
- A/B testing scripts

### Conflict Detection

#### How It Works

The plugin automatically:
1. **Extracts tracking IDs** from script content when you save
2. **Checks for duplicates** across all tracking scripts
3. **Scans page HTML** before outputting scripts
4. **Skips duplicate scripts** if ID already exists on page
5. **Logs conflicts** to WordPress debug log
6. **Shows admin warnings** when conflicts detected

#### Admin Warnings

You'll see warnings when:
- Multiple tracking scripts use the same GA4 or GTM ID
- A script tries to load when the ID is already on the page

**Warning Example:**
```
Duplicate Tracking IDs Detected!
The following tracking IDs are used in multiple tracking scripts:

G-ABCDEFG123 (Google Analytics 4) is used in:
- Main GA4 Tracking (edit)
- Secondary GA4 Script (edit)

Recommendation: Each tracking script should use a unique tracking ID to prevent duplicate tracking and data corruption.
```

#### Debug Comments

When viewing page source, you'll see comments like:
```html
<!-- GA Plugin: head -->
<script>...</script>
<!-- GA Plugin: Skipping 'Secondary GA4' - Duplicate tracking IDs detected: G-ABCDEFG123 -->
<!-- /GA Plugin: head -->
```

---

## Admin Interface

### Tracking Scripts List

Custom columns show:

| Column | Description |
|--------|-------------|
| **Title** | Script name |
| **Tracking IDs** | Extracted GA4/GTM IDs with badges |
| **Placement** | Where script loads (Head, Body Top, etc.) |
| **Scope** | Global or Specific Pages |
| **Target Pages** | Count of pages when scope is specific |
| **Status** | Active or Inactive |
| **Date** | Publication date |

### Edit Screen

Meta box fields:
- **Script Content** (required): Paste your tracking code
- **Placement** (required): Select injection location
- **Scope** (required): Global or Specific Pages
- **Target Pages**: Page selector (appears when Scope = Specific Pages)
- **Active**: Toggle to enable/disable script

---

## Best Practices

### DO
- ✅ Use descriptive titles ("Main GA4 Tracking", "GTM Container - Production")
- ✅ Keep one tracking script per tracking ID
- ✅ Test on staging before deploying to production
- ✅ Use Global scope for site-wide tracking
- ✅ Mark scripts inactive rather than deleting them
- ✅ Review admin warnings about conflicts

### DON'T
- ❌ Add the same GA4/GTM ID in multiple scripts
- ❌ Mix theme-based tracking with plugin-based tracking
- ✅ Ignore conflict warnings without investigation
- ❌ Use Specific Pages scope for global tracking needs

### Performance Tips
- Limit total active scripts to ≤ 10
- Use GTM for managing multiple tracking tools
- Prefer Head placement for better tracking accuracy
- Monitor page load impact with tools like GTmetrix

---

## Troubleshooting

### Script Not Appearing on Page

**Check:**
1. Is the script marked as **Active**?
2. Is the **Scope** correct (Global vs. Specific Pages)?
3. If Specific Pages, are the correct pages selected?
4. View page source for debug comments
5. Check for JavaScript errors in browser console

### Duplicate Tracking Warning

**Solution:**
1. Review admin warning to identify conflicting scripts
2. Determine which script should be active
3. Mark the duplicate script as **Inactive**
4. Or delete the duplicate entirely

### Tracking Not Working

**Check:**
1. Verify tracking ID is correct (G-XXXXXXXXXX or GTM-XXXXXXX)
2. Use browser extensions (Google Tag Assistant) to verify
3. Check Network tab in browser DevTools for analytics requests
4. Verify script placement is appropriate (Head recommended)
5. Ensure no ad blockers are interfering

### Debug Logging

Enable WordPress debug logging to see conflict detection:

```php
// In wp-config.php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

Check `/wp-content/debug.log` for messages starting with `GAP Conflict:`

---

## For Developers

### Architecture

```
ga-plugin/
├── ga-plugin.php                      # Main plugin file, autoloader, initialization
├── includes/
│   ├── class-gap-activator.php        # Activation/deactivation hooks
│   ├── class-gap-cpt.php              # Custom post type registration
│   ├── class-gap-meta-boxes.php       # Meta box rendering and saving
│   ├── class-gap-conflict-detector.php # Tracking ID extraction and conflict detection
│   ├── class-gap-frontend.php         # Frontend script output
│   └── class-gap-admin.php            # Admin notices and UI
├── assets/
│   ├── css/
│   │   └── admin.css                  # Admin styling
│   └── js/
│       └── admin.js                   # Admin JavaScript (scope selector)
├── languages/                          # Translation files (.pot)
└── tests/                              # PHPUnit tests
```

### Hooks & Filters

#### Actions
```php
// Before initializing plugin components
do_action( 'gap_before_init' );

// After initializing plugin components
do_action( 'gap_after_init' );
```

#### Filters
```php
// Filter script content before output
apply_filters( 'gap_script_content', $content, $post_id );

// Filter active scripts query args
apply_filters( 'gap_active_scripts_args', $args, $placement );

// Filter tracking ID extraction patterns
apply_filters( 'gap_tracking_id_patterns', $patterns );
```

### Database Schema

**Post Type:** `tracking_script`

**Meta Fields:**

| Meta Key | Type | Description |
|----------|------|-------------|
| `_gap_script_content` | text | The tracking script code |
| `_gap_placement` | string | head, body_top, body_bottom, footer |
| `_gap_scope` | string | global, specific_pages |
| `_gap_target_pages` | array | Array of page IDs (when scope = specific_pages) |
| `_gap_is_active` | string | '1' or '0' |
| `_gap_extracted_ids` | array | Array of tracking ID objects |
| `_gap_unique_hash` | string | MD5 hash of script content |

### Performance Metrics

- **Database Queries:** ≤ 2 queries per page load (cached)
- **Page Load Impact:** < 50ms
- **Memory Usage:** < 5MB
- **Cache:** Request-level (resets per page load)

### Testing

Run PHPUnit tests:
```bash
cd ga-plugin
composer install
./vendor/bin/phpunit
```

Run PHPCS validation:
```bash
./vendor/bin/phpcs --standard=phpcs.xml
```

Auto-fix coding standards:
```bash
./vendor/bin/phpcbf --standard=phpcs.xml
```

---

## FAQ

**Q: Can I use this with Universal Analytics (UA)?**
A: The plugin works with any JavaScript tracking code, but automatic ID extraction only supports GA4 and GTM. UA codes will still work, but won't show extracted IDs in admin.

**Q: Will this work with my theme?**
A: Yes. The plugin uses standard WordPress hooks (wp_head, wp_body_open, wp_footer). Most modern themes support these hooks.

**Q: Can I have multiple GTM containers?**
A: Technically yes, but Google recommends using a single GTM container per site. Use GTM's built-in tag management instead.

**Q: Does this work with caching plugins?**
A: Yes. The duplicate detection happens server-side before page caching, so cached pages maintain correct tracking implementation.

**Q: How do I migrate from hardcoded tracking?**
A: 1) Add your tracking code via this plugin, 2) Verify it works, 3) Remove hardcoded scripts from theme, 4) Clear caches.

**Q: Is this compatible with GDPR/cookie consent plugins?**
A: The plugin outputs scripts as configured. GDPR compliance depends on your consent management solution (e.g., Cookiebot, OneTrust) which should load before/instead of tracking scripts until consent is given.

---

## Support

**Bug Reports & Feature Requests:**
[GitHub Issues](https://github.com/OrasesWPDev/gaplugin/issues)

**Documentation:**
[Full Documentation](https://github.com/OrasesWPDev/gaplugin/wiki)

**Professional Support:**
For enterprise support, customization, or integration services, contact Orases at https://orases.com

---

## Contributing

We welcome contributions! Please follow these guidelines:

1. **Fork the repository** on GitHub
2. **Create a feature branch** (`git checkout -b feature/amazing-feature`)
3. **Follow WordPress Coding Standards**
4. **Write/update tests** for new functionality
5. **Run PHPCS** before committing (`./vendor/bin/phpcs`)
6. **Commit with clear messages** (`git commit -m 'Add amazing feature'`)
7. **Push to your branch** (`git push origin feature/amazing-feature`)
8. **Open a Pull Request**

### Development Setup

```bash
git clone https://github.com/OrasesWPDev/gaplugin.git
cd gaplugin/ga-plugin
composer install
```

---

## License

This plugin is licensed under the **GPL v2 or later**.

```
Copyright 2025 Orases

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
```

---

## Credits

**Developed by:** [Orases](https://orases.com)

**Contributors:**
- Orases Development Team

**Technologies:**
- WordPress 6.0+
- PHP 7.4+
- PHPUnit for testing
- PHPCS for code standards

---

## Changelog

See [CHANGELOG.md](./CHANGELOG.md) for detailed version history.

### v1.0.0 - 2025-10-16

**Initial Release**

Added:
- Custom post type for managing tracking scripts
- Automatic GA4/GTM tracking ID extraction
- Duplicate detection across tracking scripts
- Frontend duplicate prevention via HTML scanning
- Admin warnings for conflicting tracking IDs
- Multiple placement options (head, body_top, body_bottom, footer)
- Scope control (global or page-specific)
- Admin columns for visibility (IDs, placement, scope, status)
- Request-level caching for performance
- Conflict logging for debugging
- Unit tests with PHPUnit
- WordPress coding standards compliance

Security:
- Nonce verification on all forms
- Capability checks (manage_options)
- Input sanitization (wp_kses_post, sanitize_text_field)
- Output escaping where appropriate
- ABSPATH checks in all PHP files
- No SQL injection vulnerabilities
- No XSS vulnerabilities
- No CSRF vulnerabilities

Performance:
- Request-level caching (< 2 queries per page)
- Optimized meta queries
- Minimal page load impact (< 50ms)
- Memory usage < 5MB

---

**Made with ❤️ by Orases**
