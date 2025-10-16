# Local Testing Environment Setup

Configuration reference for the GA Plugin local testing environment.

## Environment Details

**Testing Platform**: Local by Flywheel
**URL**: http://localhost:10029/
**WordPress Version**: Latest (managed by Local)
**PHP Version**: Current (managed by Local)

## File Paths

```bash
# WordPress Root
/Users/chadmacbook/Local Sites/gap-testing/app/public/

# Plugin Directory
/Users/chadmacbook/Local Sites/gap-testing/app/public/wp-content/plugins/ga-plugin

# Development Directory (source)
/Users/chadmacbook/projects/gaplugin-v2

# Debug Log
/Users/chadmacbook/Local Sites/gap-testing/app/public/wp-content/debug.log

# Backup Directory
/Users/chadmacbook/Local Sites/gap-testing/backups/
```

## WordPress Credentials

**Admin User:**
- Username: `test1`
- Password: `test1pass`
- Email: test@local.test

**Database:**
- Name: gap_testing
- User: root
- Password: root

## WP-CLI Access

```bash
# Navigate to WordPress root
cd "/Users/chadmacbook/Local Sites/gap-testing/app/public"

# List installed plugins
wp plugin list

# Activate plugin
wp plugin activate ga-plugin

# Deactivate plugin
wp plugin deactivate ga-plugin

# Check plugin status
wp plugin status ga-plugin

# Clear cache/transients
wp transient delete --all

# Flush rewrite rules
wp rewrite flush
```

## Local Server Management

```bash
# Start Local server (if stopped)
open "/Applications/Local.app"

# Or from command line
local shell

# List sites
local sites

# Start site
local start gap-testing

# Stop site
local stop gap-testing
```

## Debug Mode

Enable WordPress debugging:

```php
// In wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Debug log available at: `wp-content/debug.log`

## Plugin Deployment

From development to local:

```bash
# Sync files (from development directory)
rsync -av --delete \
  --exclude='.git/' \
  --exclude='node_modules/' \
  --exclude='.DS_Store' \
  "/Users/chadmacbook/projects/gaplugin-v2/" \
  "/Users/chadmacbook/Local Sites/gap-testing/app/public/wp-content/plugins/ga-plugin/"

# Or use quick sync
rsync -av --checksum \
  "/Users/chadmacbook/projects/gaplugin-v2/" \
  "/Users/chadmacbook/Local Sites/gap-testing/app/public/wp-content/plugins/ga-plugin/"
```

## Browser Access

- **WordPress Admin**: http://localhost:10029/wp-admin/
- **Plugin Settings**: http://localhost:10029/wp-admin/admin.php?page=tracking_scripts
- **Frontend**: http://localhost:10029/

## Additional Resources

- [Test Procedures](test-procedures.md) - Detailed testing steps
- [Quality Gates](quality-gates.md) - PR blocking requirements
- [Test Templates](test-templates.md) - Test report formats
