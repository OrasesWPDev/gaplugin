# GA Plugin

A WordPress plugin for managing Google Analytics and Google Tag Manager scripts with advanced conflict detection and flexible placement options.

## Description

GA Plugin provides a robust solution for managing multiple tracking scripts (Google Analytics 4, Google Tag Manager, and other analytics tools) in WordPress. It includes intelligent conflict detection to prevent duplicate tracking IDs and offers precise control over script placement and scope.

## Features

- **Custom Post Type Management**: Manage tracking scripts as custom post types with a familiar WordPress interface
- **Flexible Script Placement**: Choose between `<head>` or end of `<body>` placement for each script
- **Scope Control**: Apply scripts globally, to specific pages/posts, or exclude certain pages
- **Conflict Detection**: Automatically detect and warn about duplicate tracking IDs (GA4, GTM)
- **HTML Scanning**: Scan page output to identify existing tracking scripts and prevent conflicts
- **Admin Interface**: Intuitive admin interface with custom columns showing tracking IDs, placement, and scope
- **Standards Compliant**: Built following WordPress coding standards and best practices

## Requirements

- **WordPress**: 6.0 or higher
- **PHP**: 7.4 or higher
- **MySQL**: 5.7 or higher (or MariaDB 10.3+)

## Installation

1. Upload the `ga-plugin` directory to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to 'Tracking Scripts' in the WordPress admin menu
4. Add your first tracking script

## Usage

### Adding a Tracking Script

1. Go to **Tracking Scripts > Add New** in WordPress admin
2. Enter a title for the script (e.g., "Main GA4 Tracking")
3. Paste your tracking script code in the "Script Content" field
4. Configure placement:
   - **Head**: Loads in `<head>` section (recommended for most analytics)
   - **Body**: Loads at end of `<body>` (useful for performance optimization)
5. Set scope:
   - **All Pages**: Display on entire site
   - **Specific Pages**: Choose specific pages/posts
   - **Exclude Pages**: Display everywhere except selected pages
6. Publish the script

### Conflict Detection

The plugin automatically extracts tracking IDs from your scripts:
- GA4 Measurement IDs (G-XXXXXXXXXX)
- GTM Container IDs (GTM-XXXXXXX)

If duplicate IDs are detected, you'll see:
- Admin warnings when editing scripts
- Visual indicators in the tracking scripts list
- Detailed conflict information in admin notices

### Best Practices

- Use descriptive titles for tracking scripts
- Enable only one script per tracking ID to avoid conflicts
- Test on a staging environment before deploying to production
- Review the conflict warnings before publishing scripts with duplicate IDs

## Development

### Directory Structure

```
ga-plugin/
├── ga-plugin.php              # Main plugin file
├── includes/                  # PHP classes
│   ├── class-gap-activator.php
│   ├── class-gap-cpt.php
│   ├── class-gap-meta-boxes.php
│   ├── class-gap-conflict-detector.php
│   ├── class-gap-frontend.php
│   └── class-gap-admin.php
├── assets/                    # Frontend assets
│   ├── css/
│   │   └── admin.css
│   └── js/
│       └── admin.js
├── languages/                 # Translation files
└── docs/                      # Documentation
```

### Coding Standards

This plugin follows:
- WordPress Coding Standards
- PHP_CodeSniffer with WordPress rulesets
- PSR-4 autoloading for classes

## Support

For bug reports and feature requests, please use the GitHub issue tracker.

## Contributing

Contributions are welcome! Please follow these guidelines:
1. Fork the repository
2. Create a feature branch
3. Follow WordPress coding standards
4. Write clear commit messages
5. Submit a pull request

## License

This plugin is licensed under the GPL v2 or later.

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
```

## Credits

Developed by Orases

## Changelog

### 1.0.0
- Initial release
- Custom post type for tracking scripts
- Script placement options (head/body)
- Scope control (all pages/specific/exclude)
- Conflict detection for GA4 and GTM IDs
- Admin interface with custom columns
- HTML scanning for existing scripts
