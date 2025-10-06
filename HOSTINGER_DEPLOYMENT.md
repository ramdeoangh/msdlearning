# Hostinger Deployment Guide

## Initial Setup on Hostinger

### Step 1: SSH into Hostinger
```bash
ssh your-username@your-server.hostinger.com
```

### Step 2: Navigate to Your Project Directory
```bash
cd /home/your-username/public_html
# or wherever your project is located
```

### Step 3: Clone the Repository (First Time Only)
```bash
# If not already cloned
git clone https://github.com/your-username/msdlearning.git .

# Checkout the production branch
git checkout prd
```

### Step 4: Configure Git (First Time Only)
```bash
git config user.name "Your Name"
git config user.email "your-email@example.com"

# Store credentials (optional, for easier pulls)
git config credential.helper store
```

## Regular Deployment Process

### Method 1: Manual Deployment (Recommended)

After a PR is merged to the `prd` branch on GitHub:

```bash
# SSH into Hostinger
ssh your-username@your-server.hostinger.com

# Navigate to project
cd /home/your-username/public_html

# Backup current state (recommended)
cp -r . ../backup_$(date +%Y%m%d_%H%M%S)

# Pull latest changes
git checkout prd
git pull origin prd

# Verify the deployment
git log -1

# Check application status
ls -la
```

### Method 2: Quick Deployment Script

Create a deployment script on Hostinger:

```bash
nano deploy.sh
```

Add this content:
```bash
#!/bin/bash

# Deployment script for Hostinger
echo "Starting deployment..."

# Backup
echo "Creating backup..."
BACKUP_DIR="../backups/backup_$(date +%Y%m%d_%H%M%S)"
mkdir -p $BACKUP_DIR
cp -r . $BACKUP_DIR
echo "Backup created at: $BACKUP_DIR"

# Pull changes
echo "Pulling latest changes from prd branch..."
git checkout prd
git pull origin prd

# Show latest commit
echo "Latest commit:"
git log -1 --oneline

# Clear cache if needed (for CodeIgniter)
echo "Clearing application cache..."
rm -rf application/cache/*
echo "index.html" > application/cache/index.html

echo "Deployment completed successfully!"
```

Make it executable:
```bash
chmod +x deploy.sh
```

Run deployment:
```bash
./deploy.sh
```

## Post-Deployment Checklist

After deploying to Hostinger:

- [ ] Verify the application is accessible
- [ ] Check error logs: `tail -f application/logs/log-*.php`
- [ ] Test critical functionality:
  - [ ] User login
  - [ ] Course access
  - [ ] Payment processing (if applicable)
  - [ ] Admin panel
- [ ] Clear browser cache and test
- [ ] Monitor for any errors

## Troubleshooting

### Issue: Permission Denied
```bash
# Fix file permissions
chmod -R 755 .
chmod -R 777 uploads/
chmod -R 777 application/cache/
chmod -R 777 application/logs/
```

### Issue: Git Pull Fails
```bash
# Check current status
git status

# If there are local changes, stash them
git stash

# Pull again
git pull origin prd

# Apply stashed changes if needed
git stash pop
```

### Issue: Merge Conflicts
```bash
# Reset to remote version (careful - this discards local changes)
git fetch origin
git reset --hard origin/prd
```

### Issue: Application Not Working After Deployment
```bash
# Check PHP error logs
tail -f /path/to/php/error.log

# Check application logs
tail -f application/logs/log-*.php

# Verify file permissions
ls -la

# Clear all caches
rm -rf application/cache/*
```

## Database Migrations

If your release includes database changes:

### Before Deployment
```bash
# Backup database
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql
```

### After Deployment
```bash
# If you have SQL migration files
mysql -u username -p database_name < migration_file.sql

# Or run migrations through your application
# (depends on your migration setup)
```

## Rollback Process

If something goes wrong:

### Option 1: Restore from Backup
```bash
# Stop the application if possible
# Restore files from backup
rm -rf /home/your-username/public_html/*
cp -r ../backups/backup_YYYYMMDD_HHMMSS/* /home/your-username/public_html/

# Restore database if needed
mysql -u username -p database_name < backup_YYYYMMDD_HHMMSS.sql
```

### Option 2: Git Rollback
```bash
# Find the previous working commit
git log --oneline

# Reset to previous commit
git reset --hard <commit-hash>

# Force pull if needed
git fetch origin
git reset --hard origin/prd
```

## Environment Configuration

### Important Files to Check After Deployment

1. **Database Configuration**
   ```bash
   nano application/config/database.php
   ```
   Verify database credentials are correct for production

2. **Base URL Configuration**
   ```bash
   nano application/config/config.php
   ```
   Verify base_url is set correctly

3. **Environment Settings**
   ```bash
   nano index.php
   ```
   Ensure ENVIRONMENT is set to 'production'

## Automated Deployment (Advanced)

### Using GitHub Webhooks

1. Create a webhook endpoint in your application
2. Configure GitHub webhook to call your endpoint on push to `prd`
3. The endpoint should execute the deployment script

### Using Hostinger Cron Job

Set up a cron job to check for updates:

```bash
# Edit crontab
crontab -e

# Add this line to check every 5 minutes
*/5 * * * * cd /home/your-username/public_html && git fetch origin && [ $(git rev-parse HEAD) != $(git rev-parse @{u}) ] && ./deploy.sh
```

## Security Considerations

1. **Never commit sensitive data** to git
2. **Use environment variables** for sensitive configuration
3. **Keep .htaccess** properly configured
4. **Restrict access** to sensitive directories
5. **Regular backups** of both files and database
6. **Monitor logs** for suspicious activity

## Quick Reference Commands

```bash
# Check current branch and status
git branch
git status

# View recent commits
git log --oneline -5

# Pull latest changes
git pull origin prd

# View differences before pulling
git fetch origin
git diff prd origin/prd

# Create backup
cp -r . ../backup_$(date +%Y%m%d_%H%M%S)

# Clear cache
rm -rf application/cache/*

# View logs
tail -f application/logs/log-*.php
```

## Support Contacts

- **Repository**: https://github.com/your-username/msdlearning
- **Hostinger Support**: https://www.hostinger.com/support
- **Emergency Rollback**: Follow rollback process above

---

**Last Updated**: October 6, 2025
