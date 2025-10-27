# Tigsaw - AI + Visual Editor

![Version](https://img.shields.io/badge/version-1.0-orange)
![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-blue)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple)
![License](https://img.shields.io/badge/license-GPL%20v2%2B-green)

A powerful WordPress plugin that seamlessly integrates Tigsaw's AI-powered visual editor and smart script into your WordPress site with automatic domain verification and container management.

## 🚀 Features

- ✅ **Automatic Domain Detection** - Detects your WordPress domain automatically
- ✅ **Container Management** - Fetch and select from available Tigsaw containers
- ✅ **Smart Script Integration** - Automatically injects script into all pages
- ✅ **Comprehensive Cache Clearing** - Supports 25+ caching plugins
- ✅ **Modal Container Switching** - Easy UI to change containers
- ✅ **API Verification** - Verifies container before activation
- ✅ **Multisite Compatible** - Full WordPress multisite support
- ✅ **Manual Entry** - Allows manual container ID for development
- ✅ **Beautiful Admin Interface** - Modern, responsive settings page
- ✅ **Translation Ready** - Fully internationalized (i18n)

## 📋 Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- Active Tigsaw account with container ID

## 🔧 Installation

### Via WordPress Admin

1. Download the latest release
2. Go to **Plugins > Add New > Upload Plugin**
3. Choose the downloaded ZIP file
4. Click **Install Now** then **Activate**
5. You'll be redirected to Tigsaw settings page

### Manual Installation

1. Upload the `tigsaw` folder to `/wp-content/plugins/`
2. Activate the plugin through the **Plugins** menu in WordPress
3. Navigate to **Tigsaw** in the WordPress admin menu

## ⚙️ Configuration

1. After activation, go to **Tigsaw** in your WordPress admin
2. Click **Fetch Container ID** to retrieve containers from Tigsaw API
3. Select your container from the dropdown
4. Click **Save & Activate Script**
5. The smart script is now active on all pages!

### For Localhost/Development

- Use the **Enter Manual Container ID** option
- Input your container ID directly for testing

## 🔌 Third-Party Services

This plugin connects to external services:

### Tailwind CSS CDN
- **Purpose**: Admin interface styling
- **Endpoint**: `https://cdn.tailwindcss.com`
- **Data Sent**: None
- **Scope**: Admin only

### Tigsaw Smart Script
- **Purpose**: AI-powered editing features
- **Endpoint**: `https://static.tigsaw.com/delivery/smartscript.js`
- **Scope**: Front-end when container is active

## 🗑️ Cache Support

Automatically clears cache from:

- WP Super Cache
- W3 Total Cache
- WP Fastest Cache
- LiteSpeed Cache
- WP Rocket
- Autoptimize
- Comet Cache
- Cache Enabler
- Hummingbird
- SG Optimizer
- Breeze (Cloudways)
- WP-Optimize
- Swift Performance
- Perfmatters
- NitroPack
- Cloudflare
- Pantheon Cache
- Varnish Cache
- WP Engine Cache
- Kinsta Cache
- Pagely Cache
- Nginx Helper
- Redis Object Cache
- And more!

## 📁 File Structure

```
tigsaw/
├── assets/
│   └── images/
│       ├── logo.svg
│       └── icon.svg
├── languages/
│   └── tigsaw.pot
├── tigsaw.php           # Main plugin file
├── uninstall.php        # Cleanup on uninstall
├── readme.txt           # WordPress.org readme
└── README.md           # This file
```

## 🛠️ Development

### Code Standards

- Follows WordPress Coding Standards
- All functions prefixed with `tigsaw_`
- Proper sanitization and escaping
- Nonce verification for forms
- Capability checks (`manage_options`)

### Security

- ✅ Input sanitization with `sanitize_text_field()`
- ✅ Output escaping with `esc_html()`, `esc_attr()`, `esc_url()`
- ✅ Nonce verification via WordPress Settings API
- ✅ Capability checks for admin functions
- ✅ Direct file access prevention
- ✅ SQL injection prevention

### Hooks & Filters

**Actions:**
- `tigsaw_activate` - Plugin activation
- `tigsaw_deactivate` - Plugin deactivation

**Filters:**
- `plugin_action_links_tigsaw/tigsaw.php` - Add settings link
- `plugin_row_meta` - Add documentation and support links

## 🌐 Internationalization

The plugin is translation-ready:

1. All strings use proper text domain: `tigsaw`
2. JavaScript strings localized via `wp_localize_script()`
3. POT file included: `languages/tigsaw.pot`

To translate:
1. Use POT file as template
2. Create `.po` file for your language
3. Compile to `.mo` file
4. Place in `languages/` directory

## 📝 Changelog

### Version 1.0
- Initial release
- Automatic domain detection
- Container ID fetching from Tigsaw
- Smart script injection
- Auto-enable on container selection
- Support for 25+ caching plugins
- Ability to change containers
- Multisite compatibility
- Complete uninstall cleanup

## 🤝 Support

- **Documentation**: [https://docs.tigsaw.com/](https://docs.tigsaw.com/)
- **Support Portal**: [https://tigsaw.com/contact](https://tigsaw.com/contact)
- **Website**: [https://tigsaw.com](https://tigsaw.com)

## 📄 License

This plugin is licensed under the GNU General Public License v2 or later.

```
Copyright (C) 2025 NaveenRao

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## 👨‍💻 Author

**NaveenRao**
- Website: [https://tigsaw.com](https://tigsaw.com)
- Plugin URI: [https://tigsaw.com](https://tigsaw.com)

---

**⭐ If you find this plugin useful, please rate it on WordPress.org!**
