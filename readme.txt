=== Tigsaw ===
Contributors: naveenrao
Tags: tigsaw, smart script, AI, visual editor, integration
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Integrate Tigsaw's AI-powered visual editor and smart script into your WordPress site with automatic domain verification.

== Description ==

**Tigsaw - AI + Visual Editor** is a powerful WordPress plugin that connects your site to the Tigsaw platform, enabling AI-powered content editing and smart script functionality. The plugin automatically verifies your domain connectivity and injects the smart script into your site header for seamless integration.

### Key Features

* **Automatic Domain Detection** - Automatically detects your WordPress domain for easy setup
* **Container Management** - Fetch and select from available Tigsaw containers
* **Smart Script Integration** - Automatically adds Tigsaw smart script to all pages
* **Cache Clearing** - Supports 25+ caching plugins for instant changes
* **Container Switching** - Easy interface to change containers without losing settings
* **Container Verification** - Verifies container activation before enabling
* **Multisite Compatible** - Full support for WordPress multisite installations
* **Manual Entry** - Allows manual container ID entry for localhost/development

### How It Works

1. **Install & Activate** - The plugin redirects you to settings after activation
2. **Fetch Container** - Click to fetch available containers from Tigsaw API
3. **Select Container** - Choose your container ID from the dropdown
4. **Automatic Activation** - Script is automatically enabled when you save
5. **Cache Cleared** - All caches are automatically cleared for instant effect

### Third-Party Services

This plugin relies on external services to function:

**Tigsaw API** (https://tigsaw.com/api/integration/)
- Used to fetch available container IDs for your domain
- Used to verify container activation status
- Data sent: Your WordPress site domain URL, selected container ID
- Privacy Policy: https://tigsaw.com/privacy
- Terms of Service: https://tigsaw.com/tos

**Tigsaw Smart Script** (https://static.tigsaw.com/delivery/smartscript.js)
- Loaded on the front-end when a container is activated
- Enables AI-powered editing and visual features
- Data handling governed by Tigsaw Privacy Policy

By using this plugin, you agree to the terms and privacy policies of these third-party services.

### Supported Cache Plugins

The plugin automatically clears cache from these popular plugins:
* WP Super Cache
* W3 Total Cache
* WP Fastest Cache
* LiteSpeed Cache
* WP Rocket
* Autoptimize
* Comet Cache
* Cache Enabler
* Hummingbird
* SG Optimizer
* Breeze (Cloudways)
* WP-Optimize
* Swift Performance
* Perfmatters
* NitroPack
* Cloudflare
* Pantheon Advanced Page Cache
* Varnish Cache
* WP Engine Cache
* Kinsta Cache
* Pagely Cache
* Nginx Helper
* Redis Object Cache
* And more!

== Installation ==

### Automatic Installation

1. Log in to your WordPress admin dashboard
2. Navigate to **Plugins > Add New**
3. Search for "Tigsaw"
4. Click **Install Now** and then **Activate**
5. You'll be redirected to the Tigsaw settings page

### Manual Installation

1. Download the plugin zip file
2. Log in to your WordPress admin dashboard
3. Navigate to **Plugins > Add New**
4. Click **Upload Plugin** at the top
5. Choose the zip file and click **Install Now**
6. Click **Activate Plugin**
7. Navigate to **Tigsaw** in the admin menu

### Configuration

1. After activation, you'll see the **Tigsaw** menu in your WordPress admin
2. Click **Fetch Container ID** to retrieve available containers from Tigsaw
3. Select your container from the dropdown
4. Click **Save & Activate Script**
5. The smart script is now active on all pages!

For localhost/development:
- Use the **Enter Manual Container ID** option to input a container ID directly
- This is useful for testing before going live

== Frequently Asked Questions ==

= What is Tigsaw? =

Tigsaw is an AI-powered visual editor platform that enables advanced content editing and management capabilities on your WordPress site.

= Do I need a Tigsaw account? =

Yes, you need to create a container on Tigsaw.com for your domain before using this plugin.

= Is this plugin free? =

The plugin itself is free, but you may need a Tigsaw account. Check Tigsaw.com for their pricing and plans.

= Does this work with multisite? =

Yes! The plugin fully supports WordPress multisite installations. Each site can have its own container configuration.

= What data is sent to Tigsaw? =

The plugin sends your WordPress domain URL to fetch available containers and your selected container ID for verification. No other WordPress data is transmitted.

= Does this slow down my site? =

No. The smart script is loaded asynchronously and won't block page rendering. It's optimized for performance.

= Can I use this on localhost? =

Yes! Use the manual entry option to input a container ID for local development and testing.

= How do I change containers? =

Click the "Change Container" button on the settings page, fetch available containers, select a new one, and save. The new container will be activated automatically.

= What happens when I deactivate the plugin? =

The smart script will stop loading on your site. Your settings are preserved in case you reactivate later.

= What happens when I uninstall the plugin? =

All plugin settings and data are completely removed from your database. You'll need to reconfigure if you reinstall.

= Which caching plugins are supported? =

The plugin supports 25+ caching plugins including WP Super Cache, W3 Total Cache, WP Rocket, LiteSpeed Cache, and many more. Cache is automatically cleared when you save settings.

= Can I disable the script without deactivating the plugin? =

Yes, use the "Remove Script" button to disable the script while keeping the plugin active.

= Does this work with page builders? =

Yes! The plugin injects the script into the header, so it works with all page builders including Elementor, Divi, WPBakery, Beaver Builder, and others.

= Is the plugin translation-ready? =

Yes, the plugin is fully internationalized and ready for translation into any language.

== Screenshots ==

1. Main settings page with active container showing status and quick actions
2. Container selection interface with fetched containers from Tigsaw API
3. Modal popup for changing containers with dropdown selection
4. Plugin settings link on the WordPress plugins page

== Changelog ==

= 1.0 =
* Initial release
* Automatic domain detection
* Container ID fetching from Tigsaw
* Smart script injection
* Auto-enable on container selection
* Support for 25+ caching plugins
* Ability to change containers
* Multisite compatibility
* Complete uninstall cleanup

== Upgrade Notice ==

= 1.0 =
Initial release of Tigsaw integration plugin. Install to connect your WordPress site with Tigsaw's AI-powered visual editor.

== Privacy Policy ==

This plugin connects to external services:

1. **Tigsaw API** - Sends your domain URL and container ID for verification
2. **Tailwind CSS CDN** - Loads styling (admin only, no data sent)
3. **Tigsaw Smart Script** - Loads on front-end when activated

No personal data is collected or stored by this plugin. All data handling is governed by Tigsaw's privacy policy.

== Support ==

For support, please visit:
* Documentation: https://docs.tigsaw.com/
* Support Portal: https://tigsaw.com/contact
* Website: https://tigsaw.com

== Credits ==

Developed by NaveenRao
Website: https://tigsaw.com
