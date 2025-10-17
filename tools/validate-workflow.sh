#!/bin/bash
#
# Workflow Validation Script - GA Plugin v2
# Validates that development workflow guidelines are being followed
#

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo ""
echo "🔍 GA Plugin v2 - Workflow Validation"
echo "========================================"
echo ""

# Track validation status
all_passed=true

# 1. Check current branch
echo -n "1. Checking current branch... "
current_branch=$(git symbolic-ref --short HEAD 2>/dev/null)

if [ "$current_branch" = "main" ]; then
    echo -e "${RED}FAIL${NC}"
    echo "   ❌ Currently on 'main' branch"
    echo "   ✅ Required: Work on epic branch (epic-XX-name)"
    all_passed=false
elif [[ "$current_branch" =~ ^epic-[0-9]{2}- ]]; then
    echo -e "${GREEN}PASS${NC}"
    echo "   ✅ On epic branch: $current_branch"
else
    echo -e "${YELLOW}WARN${NC}"
    echo "   ⚠️  Branch doesn't match epic pattern: $current_branch"
    echo "   Expected: epic-XX-name (e.g., epic-01-foundation)"
fi

# 2. Check git hooks installed
echo -n "2. Checking git hooks... "
if [ -x ".git/hooks/pre-commit" ] && [ -x ".git/hooks/pre-push" ]; then
    echo -e "${GREEN}PASS${NC}"
    echo "   ✅ pre-commit hook: active"
    echo "   ✅ pre-push hook: active"
else
    echo -e "${RED}FAIL${NC}"
    if [ ! -x ".git/hooks/pre-commit" ]; then
        echo "   ❌ pre-commit hook: missing or not executable"
    fi
    if [ ! -x ".git/hooks/pre-push" ]; then
        echo "   ❌ pre-push hook: missing or not executable"
    fi
    all_passed=false
fi

# 3. Check remote tracking
echo -n "3. Checking remote tracking... "
if git rev-parse --abbrev-ref --symbolic-full-name @{u} > /dev/null 2>&1; then
    remote_branch=$(git rev-parse --abbrev-ref --symbolic-full-name @{u})
    echo -e "${GREEN}PASS${NC}"
    echo "   ✅ Tracking: $remote_branch"
else
    echo -e "${YELLOW}WARN${NC}"
    echo "   ⚠️  No remote tracking configured"
    echo "   Run: git push -u origin $current_branch"
fi

# 4. Check uncommitted changes
echo -n "4. Checking working directory... "
if git diff-index --quiet HEAD --; then
    echo -e "${GREEN}PASS${NC}"
    echo "   ✅ No uncommitted changes"
else
    echo -e "${YELLOW}WARN${NC}"
    echo "   ⚠️  Uncommitted changes present"
    echo "   Status: $(git status --short | wc -l) files modified"
fi

# 5. Check main branch status
echo -n "5. Checking main branch sync... "
git fetch origin main --quiet 2>/dev/null || true
local_main=$(git rev-parse main 2>/dev/null || echo "")
remote_main=$(git rev-parse origin/main 2>/dev/null || echo "")

if [ "$local_main" = "$remote_main" ]; then
    echo -e "${GREEN}PASS${NC}"
    echo "   ✅ Main branch in sync with origin"
elif [ -z "$local_main" ] || [ -z "$remote_main" ]; then
    echo -e "${YELLOW}WARN${NC}"
    echo "   ⚠️  Could not compare main branches"
else
    echo -e "${YELLOW}WARN${NC}"
    echo "   ⚠️  Local main out of sync with origin"
    echo "   Run: git checkout main && git pull origin main"
fi

# 6. Check workflow documentation
echo -n "6. Checking documentation... "
if [ -f "docs/GIT-WORKFLOW.md" ] && [ -f "docs/DEVELOPMENT-WORKFLOW.md" ]; then
    echo -e "${GREEN}PASS${NC}"
    echo "   ✅ Workflow documentation present"
else
    echo -e "${RED}FAIL${NC}"
    echo "   ❌ Missing workflow documentation"
    all_passed=false
fi

echo ""
echo "========================================"
if [ "$all_passed" = true ]; then
    echo -e "${GREEN}✅ All critical validations passed${NC}"
    echo ""
    echo "You can proceed with development on: $current_branch"
    exit 0
else
    echo -e "${RED}❌ Some critical validations failed${NC}"
    echo ""
    echo "Please fix the issues above before proceeding."
    echo ""
    echo "📖 Documentation:"
    echo "   docs/GIT-WORKFLOW.md"
    echo "   docs/DEVELOPMENT-WORKFLOW.md"
    exit 1
fi
