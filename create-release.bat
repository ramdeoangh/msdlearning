@echo off
REM Release Creation Script for Windows
REM Usage: create-release.bat <release_number>
REM Example: create-release.bat 102

setlocal enabledelayedexpansion

if "%1"=="" (
    echo Error: Release number is required
    echo Usage: create-release.bat ^<release_number^>
    echo Example: create-release.bat 102
    exit /b 1
)

set RELEASE_NUMBER=%1
set RELEASE_BRANCH=Release-MSD-%RELEASE_NUMBER%

echo.
echo === Creating Release %RELEASE_NUMBER% ===
echo.

REM Check if we're in a git repository
git rev-parse --git-dir >nul 2>&1
if errorlevel 1 (
    echo Error: Not a git repository
    exit /b 1
)

REM Check for uncommitted changes
git diff-index --quiet HEAD --
if errorlevel 1 (
    echo Error: You have uncommitted changes. Please commit or stash them first.
    git status --short
    exit /b 1
)

REM Fetch latest changes
echo Fetching latest changes...
git fetch --all

REM Switch to main and pull latest
echo Switching to main branch...
git checkout main
git pull origin main

REM Check if release branch already exists locally
git show-ref --verify --quiet refs/heads/%RELEASE_BRANCH%
if not errorlevel 1 (
    echo Error: Branch %RELEASE_BRANCH% already exists locally
    exit /b 1
)

REM Check if release branch already exists on remote
git ls-remote --heads origin %RELEASE_BRANCH% | findstr %RELEASE_BRANCH% >nul
if not errorlevel 1 (
    echo Error: Branch %RELEASE_BRANCH% already exists on remote
    exit /b 1
)

REM Create release branch
echo Creating release branch: %RELEASE_BRANCH%
git checkout -b %RELEASE_BRANCH%

REM Push release branch
echo Pushing release branch to remote...
git push origin %RELEASE_BRANCH%

echo.
echo ✓ Release branch created successfully!
echo.

REM Get commit log
echo Recent commits in this release:
git log --oneline prd..%RELEASE_BRANCH% --no-merges -10

echo.
echo === Next Steps ===
echo 1. Go to GitHub and create a Pull Request
echo    - Base branch: prd
echo    - Compare branch: %RELEASE_BRANCH%
echo.
echo 2. Or use GitHub CLI:
echo    gh pr create --base prd --head %RELEASE_BRANCH% --title "Release MSD-%RELEASE_NUMBER% to Production"
echo.
echo 3. After PR is merged, deploy to Hostinger:
echo    cd /path/to/your/project
echo    git checkout prd
echo    git pull origin prd
echo.

endlocal
