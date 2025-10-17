#!/bin/bash

#
# deploy-to-local.sh
# Deploy GA Plugin to local WordPress environment (production files only)
#
# Usage: ./tools/deploy-to-local.sh
#

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration
SOURCE_DIR="/Users/chadmacbook/projects/gaplugin-v2/ga-plugin"
DEST_DIR="$HOME/Local Sites/gap-testing/app/public/wp-content/plugins/ga-plugin"

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}GA Plugin - Production Deployment${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Verify source directory exists
if [ ! -d "$SOURCE_DIR" ]; then
    echo -e "${RED}Error: Source directory not found: $SOURCE_DIR${NC}"
    exit 1
fi

# Verify destination directory exists
if [ ! -d "$(dirname "$DEST_DIR")" ]; then
    echo -e "${RED}Error: WordPress plugins directory not found${NC}"
    echo -e "${YELLOW}Make sure Local by Flywheel site 'gap-testing' is running${NC}"
    exit 1
fi

echo -e "${YELLOW}Source:${NC} $SOURCE_DIR"
echo -e "${YELLOW}Destination:${NC} $DEST_DIR"
echo ""

# Deploy using rsync with exclusions
echo -e "${GREEN}Deploying production files...${NC}"
rsync -av --delete \
    --exclude='.git/' \
    --exclude='.gitignore' \
    --exclude='.phpunit.result.cache' \
    --exclude='composer.json' \
    --exclude='composer.lock' \
    --exclude='phpunit.xml' \
    --exclude='phpcs.xml' \
    --exclude='PHPCS-REPORT.md' \
    --exclude='SECURITY-AUDIT.md' \
    --exclude='TESTING-REPORT.md' \
    --exclude='tests/' \
    --exclude='vendor/' \
    --exclude='node_modules/' \
    --exclude='.vscode/' \
    --exclude='.idea/' \
    --exclude='*.swp' \
    --exclude='*.swo' \
    --exclude='.DS_Store' \
    --exclude='Thumbs.db' \
    "$SOURCE_DIR/" "$DEST_DIR/"

echo ""
echo -e "${GREEN}✓ Deployment complete!${NC}"
echo ""

# Show deployed files
echo -e "${YELLOW}Production files deployed:${NC}"
echo "  ✓ ga-plugin.php (main plugin file)"
echo "  ✓ includes/ (7 core PHP classes)"
echo "  ✓ assets/ (CSS and JS)"
echo "  ✓ languages/ (translations)"
echo "  ✓ LICENSE.txt"
echo "  ✓ README.md"
echo "  ✓ CHANGELOG.md"
echo ""

# Show what was excluded
echo -e "${YELLOW}Development files excluded:${NC}"
echo "  ✗ tests/ directory"
echo "  ✗ vendor/ directory"
echo "  ✗ composer.json, composer.lock"
echo "  ✗ phpunit.xml, phpcs.xml"
echo "  ✗ Development reports (PHPCS, SECURITY, TESTING)"
echo "  ✗ Git and IDE files"
echo ""

echo -e "${GREEN}Plugin ready at:${NC} http://localhost:10029/wp-admin/plugins.php"
echo ""
