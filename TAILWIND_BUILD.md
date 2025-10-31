# Tailwind CSS Build Guide

This document explains how to work with Tailwind CSS in the Tigsaw WordPress plugin.

## 📁 File Structure

```
tigsaw/
├── assets/
│   └── css/
│       ├── src/
│       │   └── tailwind.css          # Source file (edit this)
│       └── tailwind.min.css          # Generated file (don't edit)
├── package.json                      # NPM configuration
├── tailwind.config.js                # Tailwind configuration
└── .gitignore                        # Ignore node_modules & generated CSS
```

## 🚀 Initial Setup (One-Time)

1. **Install Node.js** (if not already installed)
   - Download from: https://nodejs.org/
   - Verify: `node --version`

2. **Install Dependencies**
   ```bash
   cd /path/to/wp-content/plugins/tigsaw
   npm install
   ```

## 🔨 NPM Commands

### Build for Production (Minified)
```bash
npm run build
```
- Generates: `assets/css/tailwind.min.css`
- Minified and optimized
- Use this before committing/deploying

### Watch Mode (Development)
```bash
npm run watch
```
- Watches for changes in PHP files
- Auto-rebuilds CSS when classes change
- Keep running while developing
- Press `Ctrl+C` to stop

### Development Build (Non-Minified)
```bash
npm run dev
```
- Generates: `assets/css/tailwind.css`
- Unminified for easier debugging

## 🎨 Making Style Changes

### When You Add/Change Tailwind Classes

1. **Add classes in your PHP files**
   ```php
   <div class="bg-blue-500 text-white p-4 rounded-lg">
       New styled element
   </div>
   ```

2. **Rebuild the CSS**
   ```bash
   npm run build
   ```

3. **Refresh your WordPress admin page**
   - Hard refresh: `Ctrl+Shift+R` (Windows) or `Cmd+Shift+R` (Mac)

### When You Need Custom CSS

Edit `assets/css/src/tailwind.css`:

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Custom Tigsaw Plugin Styles */
.my-custom-class {
    /* Your custom CSS here */
}
```

Then run: `npm run build`

## ⚙️ Configuration

### Tailwind Config (`tailwind.config.js`)

```javascript
module.exports = {
  content: [
    "./tigsaw.php",           // Scans main plugin file
    "./assets/**/*.php",      // Scans assets folder
    "./includes/**/*.php",    // Scans includes folder (if any)
  ],
  theme: {
    extend: {
      colors: {
        primary: '#ff6600',        // Your brand orange
        'primary-dark': '#e65c00',
        'primary-light': '#ff8533',
      }
    }
  },
  plugins: [],
  corePlugins: {
    preflight: false,  // Important: Prevents conflicts with WP admin styles
  }
}
```

### To Add New Color

1. Edit `tailwind.config.js`
2. Add to `theme.extend.colors`
3. Run `npm run build`
4. Use in PHP: `<div class="bg-mynewcolor">`

## 📦 What Gets Committed to Git

**✅ Commit these:**
- `assets/css/src/tailwind.css` (source)
- `assets/css/tailwind.min.css` (generated, for distribution)
- `package.json`
- `tailwind.config.js`
- `.gitignore`

**❌ Don't commit these:**
- `node_modules/` (added to .gitignore)
- `package-lock.json` (optional)

## 🚢 Before Releasing

**Always run before creating a release:**

```bash
npm run build
git add assets/css/tailwind.min.css
git commit -m "Build Tailwind CSS for release"
```

## 🐛 Troubleshooting

### CSS classes not working?
1. Check if class is in `tigsaw.php` or scanned files
2. Run `npm run build`
3. Hard refresh browser (`Ctrl+Shift+R`)
4. Clear WordPress cache if using caching plugin

### Node modules error?
```bash
rm -rf node_modules package-lock.json
npm install
```

### CSS file not updating?
1. Check file permissions on `assets/css/`
2. Make sure `tailwind.min.css` is writable
3. Run `npm run build` again

### Watch mode not detecting changes?
1. Stop watch: `Ctrl+C`
2. Restart: `npm run watch`
3. Or just run: `npm run build` manually

## 📊 File Sizes

- **Before (CDN)**: ~3MB+ (full Tailwind downloaded every page load)
- **After (Local)**: ~15-30KB (only used classes, minified)

This reduces load time and removes external dependency! 🎉

## 🔄 Workflow Summary

### Daily Development
```bash
# Start watching for changes
npm run watch

# Make changes in tigsaw.php
# CSS rebuilds automatically

# When done, stop watch
Ctrl+C
```

### Before Committing
```bash
# Build production version
npm run build

# Commit the generated CSS
git add assets/css/tailwind.min.css
git commit -m "Update styles"
```

### One-Off Changes
```bash
# Make changes in PHP
# Then build once
npm run build
```

---

**That's it!** You now have a local, optimized Tailwind CSS build. 🎨✨
