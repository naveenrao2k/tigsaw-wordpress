# GitHub Actions CI/CD Setup Guide

This guide explains the complete CI/CD pipeline for the Tigsaw WordPress plugin.

## Overview

The GitHub Actions workflow automatically:
- Tests code quality on every push
- Builds production Tailwind CSS
- Creates releases with downloadable plugin zip
- Optionally deploys to WordPress.org

## Files Created

### 1. `.github/workflows/deploy.yml`
Main CI/CD pipeline with 4 jobs:
- **test**: Runs PHP CodeSniffer for WordPress standards
- **build**: Builds production Tailwind CSS and uploads artifacts
- **release**: Creates GitHub releases on version tags
- **deploy-to-wordpress**: Optional WordPress.org deployment

### 2. `.github/workflows/ci.yml`
Simple status check workflow

### 3. `.distignore`
Lists files to exclude from production releases

## How It Works

### On Every Push to master/main:
1. Code quality checks run via PHPCS
2. Production Tailwind CSS is built
3. Build artifacts are uploaded and stored for 5 days

### On Tag Push (v*):
1. All above steps run
2. Creates a clean plugin package:
   - Excludes: node_modules, .git, source files, dev dependencies
   - Includes: Only production-ready files
3. Generates SHA256 checksum
4. Creates GitHub release with:
   - Plugin zip file
   - Checksum file
   - Automated release notes

## Creating a Release

### Step 1: Update Version
Edit `tigsaw.php`:
```php
* Version:         1.1.0
```

Edit `readme.txt`:
```
Stable tag: 1.1.0

== Changelog ==
= 1.1.0 =
* Your changes here
```

### Step 2: Commit Changes
```bash
git add .
git commit -m "Release v1.1.0"
git push origin master
```

### Step 3: Create and Push Tag
```bash
git tag v1.1.0
git push origin v1.1.0
```

### Step 4: Wait for GitHub Actions
- Go to Actions tab in your repository
- Watch the workflow complete
- Check Releases tab for the new release

## Production Package Contents

The release zip includes ONLY:
- `tigsaw.php` - Main plugin file
- `uninstall.php` - Cleanup script
- `readme.txt` - WordPress.org readme
- `assets/` folder:
  - `css/tailwind.min.css` (production build)
  - `images/` (icons and logos)
  - `js/` (admin scripts)
- `languages/` - Translation files

Excluded from production:
- `node_modules/`
- `vendor/`
- `.git/` and `.github/`
- Source CSS files (`assets/css/src/`)
- Development config files
- Documentation files

## WordPress.org Deployment (Optional)

### Setup
1. Go to your GitHub repository Settings → Secrets
2. Add repository secrets:
   - Name: `SVN_USERNAME`, Value: Your WordPress.org username
   - Name: `SVN_PASSWORD`, Value: Your WordPress.org password

### First Time Setup
Create `.wordpress-org/` folder with assets:
```
.wordpress-org/
├── banner-772x250.png
├── banner-1544x500.png
├── icon-128x128.png
├── icon-256x256.png
└── screenshot-1.png
```

### Deploy
Just push a version tag - the workflow handles the rest!

## Workflow Features

### Production Tailwind Build
- Automatically runs `npm run build` with `NODE_ENV=production`
- Generates optimized, minified CSS
- Only includes classes used in the plugin
- Verifies build output before continuing

### Code Quality
- Runs PHP CodeSniffer with WordPress standards
- Reports issues inline in pull requests
- Continues on errors (won't block builds)

### Smart Packaging
- Uses `rsync` with exclusions for clean package
- Calculates SHA256 checksums
- Creates versioned releases automatically

### Caching
- Caches Composer dependencies
- Caches npm packages
- Speeds up workflow runs

## Testing Locally

Before pushing, test locally:

```bash
# Install dependencies
npm install
composer install --dev

# Run code standards check
vendor/bin/phpcs --standard=WordPress --extensions=php tigsaw.php

# Build production CSS
npm run build

# Verify build
ls -lh assets/css/tailwind.min.css
```

## Troubleshooting

### Build Fails
- Check `npm run build` works locally
- Ensure `tailwind.config.js` is correct
- Verify `package.json` has correct scripts

### PHPCS Errors
- Run locally: `vendor/bin/phpcs --standard=WordPress .`
- Fix issues or add `phpcs:ignore` comments
- Update `.phpcs.xml.dist` for custom rules

### Release Not Created
- Ensure tag starts with 'v' (e.g., v1.0.0)
- Check Actions tab for error messages
- Verify all jobs passed

### WordPress.org Deploy Fails
- Check SVN credentials are correct
- Ensure `.wordpress-org/` assets exist
- Verify WordPress.org plugin slug matches

## Monitoring

### GitHub Actions Tab
- View all workflow runs
- See real-time logs
- Download artifacts

### Releases Tab
- All version releases listed
- Download links for users
- Checksums for verification

## Best Practices

1. **Always test locally** before pushing tags
2. **Update changelog** in readme.txt for each release
3. **Use semantic versioning** (MAJOR.MINOR.PATCH)
4. **Review Actions logs** after each push
5. **Keep secrets secure** - never commit credentials

## Example Release Flow

```bash
# 1. Make your changes
vim tigsaw.php

# 2. Test locally
npm run build
vendor/bin/phpcs tigsaw.php

# 3. Update version and changelog
vim tigsaw.php readme.txt

# 4. Commit and push
git add .
git commit -m "Add new feature X"
git push origin master

# 5. Create release
git tag v1.1.0
git push origin v1.1.0

# 6. Wait and verify
# Check GitHub Actions → Releases tab
```

## Additional Resources

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

---

Your plugin is now production-ready with automated CI/CD! 🚀
