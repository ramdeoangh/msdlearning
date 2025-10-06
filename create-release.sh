#!/bin/bash

# Release Creation Script
# Usage: ./create-release.sh <release_number>
# Example: ./create-release.sh 102

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if release number is provided
if [ -z "$1" ]; then
    echo -e "${RED}Error: Release number is required${NC}"
    echo "Usage: ./create-release.sh <release_number>"
    echo "Example: ./create-release.sh 102"
    exit 1
fi

RELEASE_NUMBER=$1
RELEASE_BRANCH="Release-MSD-${RELEASE_NUMBER}"

echo -e "${YELLOW}=== Creating Release ${RELEASE_NUMBER} ===${NC}\n"

# Check if we're in a git repository
if ! git rev-parse --git-dir > /dev/null 2>&1; then
    echo -e "${RED}Error: Not a git repository${NC}"
    exit 1
fi

# Check for uncommitted changes
if ! git diff-index --quiet HEAD --; then
    echo -e "${RED}Error: You have uncommitted changes. Please commit or stash them first.${NC}"
    git status --short
    exit 1
fi

# Fetch latest changes
echo -e "${YELLOW}Fetching latest changes...${NC}"
git fetch --all

# Switch to main and pull latest
echo -e "${YELLOW}Switching to main branch...${NC}"
git checkout main
git pull origin main

# Check if release branch already exists
if git show-ref --verify --quiet refs/heads/${RELEASE_BRANCH}; then
    echo -e "${RED}Error: Branch ${RELEASE_BRANCH} already exists locally${NC}"
    exit 1
fi

if git ls-remote --heads origin ${RELEASE_BRANCH} | grep -q ${RELEASE_BRANCH}; then
    echo -e "${RED}Error: Branch ${RELEASE_BRANCH} already exists on remote${NC}"
    exit 1
fi

# Create release branch
echo -e "${YELLOW}Creating release branch: ${RELEASE_BRANCH}${NC}"
git checkout -b ${RELEASE_BRANCH}

# Push release branch
echo -e "${YELLOW}Pushing release branch to remote...${NC}"
git push origin ${RELEASE_BRANCH}

echo -e "\n${GREEN}✅ Release branch created successfully!${NC}\n"

# Get commit log
echo -e "${YELLOW}Recent commits in this release:${NC}"
git log --oneline prd..${RELEASE_BRANCH} --no-merges | head -10

echo -e "\n${GREEN}=== Next Steps ===${NC}"
echo -e "1. Go to GitHub and create a Pull Request"
echo -e "   - Base branch: ${GREEN}prd${NC}"
echo -e "   - Compare branch: ${GREEN}${RELEASE_BRANCH}${NC}"
echo -e "\n2. Or use GitHub CLI:"
echo -e "   ${YELLOW}gh pr create --base prd --head ${RELEASE_BRANCH} --title \"Release MSD-${RELEASE_NUMBER} to Production\"${NC}"
echo -e "\n3. After PR is merged, deploy to Hostinger:"
echo -e "   ${YELLOW}cd /path/to/your/project${NC}"
echo -e "   ${YELLOW}git checkout prd${NC}"
echo -e "   ${YELLOW}git pull origin prd${NC}"
echo ""
