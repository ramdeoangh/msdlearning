# Quick Start Guide - Release Workflow

## 🚀 TL;DR - Release Process

### For Next Release (Release-MSD-102):

**Option 1: Using the Script (Easiest)**
```bash
# On Windows
create-release.bat 102

# On Linux/Mac
./create-release.sh 102
```

**Option 2: Using GitHub Actions**
1. Go to GitHub → Actions tab
2. Click "Create Release Branch and PR"
3. Click "Run workflow"
4. Enter release number: `102`
5. Add release notes
6. Click "Run workflow"

**Option 3: Manual Process**
```bash
# 1. Create release branch from main
git checkout main
git pull origin main
git checkout -b Release-MSD-102
git push origin Release-MSD-102

# 2. Create PR on GitHub
# Go to: https://github.com/your-repo/pulls
# Create PR: Release-MSD-102 → prd

# 3. After PR is merged, deploy on Hostinger
ssh your-username@hostinger
cd /path/to/project
git checkout prd
git pull origin prd
```

## 📋 Current Setup

- ✅ **main** - Development branch
- ✅ **Release-MSD-101** - Current release branch
- ✅ **prd** - Production branch (deployed to Hostinger)

## 📚 Documentation Files

- **`RELEASE_WORKFLOW.md`** - Complete workflow guide
- **`HOSTINGER_DEPLOYMENT.md`** - Hostinger deployment instructions
- **`create-release.sh`** - Linux/Mac release script
- **`create-release.bat`** - Windows release script
- **`.github/workflows/create-release.yml`** - Automated release creation
- **`.github/workflows/auto-deploy-notification.yml`** - Deployment notifications

## 🔄 Typical Development Cycle

```
1. Develop on 'main' branch
   ↓
2. Test changes on 'main'
   ↓
3. Create release branch (Release-MSD-XXX)
   ↓
4. Create PR to 'prd'
   ↓
5. Review and merge PR
   ↓
6. Pull 'prd' on Hostinger
   ↓
7. Verify deployment
```

## ⚡ Quick Commands

```bash
# Check current branch
git branch

# Switch to main for development
git checkout main

# Update your local branches
git fetch --all

# View all branches
git branch -a

# See recent commits
git log --oneline -5
```

## 🎯 Next Release Checklist

When you're ready to create Release-MSD-102:

- [ ] All changes committed to `main`
- [ ] Changes tested on `main` branch
- [ ] Run: `create-release.bat 102` (Windows) or `./create-release.sh 102` (Linux/Mac)
- [ ] Create PR on GitHub: Release-MSD-102 → prd
- [ ] Review changes in PR
- [ ] Merge PR
- [ ] SSH to Hostinger and pull `prd` branch
- [ ] Verify deployment
- [ ] Test critical functionality

## 🆘 Need Help?

- **Full workflow details**: See `RELEASE_WORKFLOW.md`
- **Hostinger deployment**: See `HOSTINGER_DEPLOYMENT.md`
- **Rollback needed**: Check `HOSTINGER_DEPLOYMENT.md` → Rollback Process
- **Merge conflicts**: Check `RELEASE_WORKFLOW.md` → Troubleshooting

## 📞 Emergency Rollback

```bash
# On Hostinger
cd /path/to/project
git checkout prd
git log --oneline  # Find previous working commit
git reset --hard <previous-commit-hash>
```

---

**Remember**: Never commit directly to `prd` - always go through a release branch and PR!
