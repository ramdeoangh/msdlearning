# Release Workflow Guide

## Branch Structure

- **`main`** - Main development branch where all development happens
- **`Release-MSD-XXX`** - Release branches created from main after testing (e.g., Release-MSD-101, Release-MSD-102)
- **`prd`** - Production branch that gets deployed to Hostinger

## Release Process Flow

### Step 1: Development on Main Branch
```bash
# Work on main branch
git checkout main
git pull origin main

# Make your changes, commit them
git add .
git commit -m "Your commit message"
git push origin main
```

### Step 2: Create Release Branch (After Testing on Main)
Once changes are tested and verified on the `main` branch:

```bash
# Make sure you're on main and it's up to date
git checkout main
git pull origin main

# Create a new release branch (increment the number)
# Example: Release-MSD-102, Release-MSD-103, etc.
git checkout -b Release-MSD-102

# Push the release branch to remote
git push origin Release-MSD-102
```

### Step 3: Create Pull Request to Production
1. Go to your GitHub repository
2. Click on "Pull requests" tab
3. Click "New pull request"
4. Set:
   - **Base branch**: `prd`
   - **Compare branch**: `Release-MSD-102` (your release branch)
5. Add title: "Release MSD-102 to Production"
6. Add description with changes included
7. Create the pull request
8. Review the changes
9. Merge the pull request when ready

### Step 4: Deploy to Hostinger
After merging the PR to `prd` branch:

```bash
# On your Hostinger server, navigate to your project directory
cd /path/to/your/project

# Pull the latest production changes
git checkout prd
git pull origin prd

# Verify the deployment
git log -1
```

## Quick Reference Commands

### Check Current Branch
```bash
git branch
```

### View All Branches
```bash
git branch -a
```

### Switch Between Branches
```bash
git checkout main          # Switch to main
git checkout prd           # Switch to production
git checkout Release-MSD-101  # Switch to specific release
```

### Update Local Branch from Remote
```bash
git pull origin <branch-name>
```

### Delete Old Release Branches (Optional)
After successful deployment, you can optionally delete old release branches:

```bash
# Delete local branch
git branch -d Release-MSD-101

# Delete remote branch
git push origin --delete Release-MSD-101
```

## Naming Convention

Release branches should follow this pattern:
- `Release-MSD-101`
- `Release-MSD-102`
- `Release-MSD-103`
- etc.

Increment the number for each new release.

## Best Practices

1. **Always test on `main` before creating a release branch**
2. **Never commit directly to `prd`** - always go through a release branch and PR
3. **Keep release branches** for at least a few weeks for rollback purposes
4. **Tag releases** for easy reference:
   ```bash
   git tag -a v1.0.1 -m "Release MSD-101"
   git push origin v1.0.1
   ```
5. **Document changes** in each release PR description
6. **Backup database** before deploying to production

## Rollback Process

If you need to rollback a release:

```bash
# On Hostinger server
git checkout prd
git reset --hard <previous-commit-hash>
git push origin prd --force

# Or revert to a specific tag
git checkout prd
git reset --hard v1.0.0
git push origin prd --force
```

**Note**: Force push should be used carefully and only in emergency situations.

## Troubleshooting

### If you accidentally committed to the wrong branch:
```bash
# Save your changes
git stash

# Switch to correct branch
git checkout <correct-branch>

# Apply your changes
git stash pop
```

### If branches are out of sync:
```bash
# Update all branches
git fetch --all

# Reset local branch to match remote
git reset --hard origin/<branch-name>
```

### If merge conflicts occur:
1. Pull the latest changes from both branches
2. Resolve conflicts in your code editor
3. Stage the resolved files: `git add .`
4. Complete the merge: `git commit`
5. Push the changes: `git push`

## Current Release Status

- **Current Release Branch**: Release-MSD-101
- **Production Branch**: prd
- **Latest Commit**: 541660a - Create php.yml

## Next Steps

1. Continue development on `main` branch
2. When ready for next release, create `Release-MSD-102`
3. Create PR from `Release-MSD-102` to `prd`
4. Deploy from `prd` on Hostinger
