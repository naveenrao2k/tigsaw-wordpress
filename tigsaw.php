<?php
/**
 * Plugin Name:     Tigsaw
 * Plugin URI:      https://tigsaw.com
 * Description:     Integration plugin with Tigsaw platform to verify domain connectivity and add smart script to your WordPress site header.
 * Author:          NaveenRao
 * Author URI:      https://tigsaw.com
 * Text Domain:     tigsaw
 * Domain Path:     /languages
 * Version:         1.0
 * Requires at least: 5.0
 * Requires PHP:    7.4
 * Tested up to:    6.8
 * License:         GPL v2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package         Tigsaw
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'TIGSAW_VERSION', '1.0' );
define( 'TIGSAW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'TIGSAW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Activation hook - redirect to settings page
 */
function tigsaw_activate() {
	add_option( 'tigsaw_activation_redirect', true );
}
register_activation_hook( __FILE__, 'tigsaw_activate' );

/**
 * Redirect to settings page on activation
 */
function tigsaw_activation_redirect() {
	if ( get_option( 'tigsaw_activation_redirect', false ) ) {
		delete_option( 'tigsaw_activation_redirect' );
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET['activate-multi'] ) ) {
			wp_safe_redirect( admin_url( 'admin.php?page=tigsaw-settings' ) );
			exit;
		}
	}
}
add_action( 'admin_init', 'tigsaw_activation_redirect' );

/**
 * Deactivation hook
 */
function tigsaw_deactivate() {
	// Cleanup on deactivation if needed
}
register_deactivation_hook( __FILE__, 'tigsaw_deactivate' );

/**
 * Add admin menu
 */
function tigsaw_add_admin_menu() {
	$icon_url = TIGSAW_PLUGIN_URL . 'assets/images/icon.svg';
	
	add_menu_page(
		'Tigsaw Smart Script',
		'Tigsaw',
		'manage_options',
		'tigsaw-settings',
		'tigsaw_settings_page',
		$icon_url,
		65
	);
}
add_action( 'admin_menu', 'tigsaw_add_admin_menu' );

/**
 * Enqueue admin styles for menu icon
 */
function tigsaw_admin_menu_styles() {
	?>
	<style>
		/* Tigsaw menu icon styling - Default/Unselected state (20px) */
		#adminmenu li#toplevel_page_tigsaw-settings .wp-menu-image img {
			width: 20px !important;
			height: 20px !important;
			min-width: 20px !important;
			min-height: 20px !important;
			max-width: 20px !important;
			max-height: 20px !important;
			padding: 6px 0 !important;
			margin: 0 !important;
			opacity: 0.6;
			transition: all 0.2s ease;
		}
		
		/* Hover state - keep 20px */
		#adminmenu li#toplevel_page_tigsaw-settings:hover .wp-menu-image img {
			opacity: 1 !important;
		}
		
		/* Selected/Active state - Increase to 30px height */
		#adminmenu li#toplevel_page_tigsaw-settings.current .wp-menu-image img,
		#adminmenu li#toplevel_page_tigsaw-settings.wp-has-current-submenu .wp-menu-image img,
		#adminmenu li#toplevel_page_tigsaw-settings.wp-menu-open .wp-menu-image img {
			width: 30px !important;
			height: 30px !important;
			min-width: 30px !important;
			min-height: 30px !important;
			max-width: 30px !important;
			max-height: 30px !important;
			opacity: 1 !important;
			padding: 3px 0 !important;
		}
		
		/* Container fixes - Default */
		#adminmenu li#toplevel_page_tigsaw-settings .wp-menu-image {
			padding-top: 0 !important;
			display: flex !important;
			align-items: center !important;
			justify-content: center !important;
			width: 36px !important;
			height: 34px !important;
		}
		
		#adminmenu li#toplevel_page_tigsaw-settings .wp-menu-image:before {
			display: none !important;
		}
		
		/* Override any WordPress auto-sizing */
		#adminmenu li#toplevel_page_tigsaw-settings div.wp-menu-image {
			background-size: 20px 20px !important;
		}
		
		#adminmenu li#toplevel_page_tigsaw-settings.current div.wp-menu-image,
		#adminmenu li#toplevel_page_tigsaw-settings.wp-has-current-submenu div.wp-menu-image {
			background-size: 30px 30px !important;
		}
		
		/* Remove unwanted current/highlighted state when not on the page */
		#adminmenu #toplevel_page_tigsaw-settings.wp-not-current-submenu {
			background: transparent !important;
		}
		
		#adminmenu #toplevel_page_tigsaw-settings.wp-not-current-submenu > a {
			background: transparent !important;
			color: #f0f0f1 !important;
		}
		
		#adminmenu #toplevel_page_tigsaw-settings.wp-not-current-submenu:hover > a {
			background: rgba(255, 255, 255, 0.05) !important;
			color: #72aee6 !important;
		}
	</style>
	<?php
}
add_action( 'admin_head', 'tigsaw_admin_menu_styles' );

/**
 * Add settings link on plugins page
 */
function tigsaw_plugin_action_links( $links ) {
	$settings_link = sprintf(
		'<a href="%s" style="color: #ff6600; font-weight: 600;">%s</a>',
		admin_url( 'admin.php?page=tigsaw-settings' ),
		esc_html__( 'Settings', 'tigsaw' )
	);
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'tigsaw_plugin_action_links' );

/**
 * Add additional links on plugins page
 */
function tigsaw_plugin_row_meta( $links, $file ) {
	if ( plugin_basename( __FILE__ ) === $file ) {
		$row_meta = array(
			'docs' => sprintf(
				'<a href="%s" target="_blank" style="color: #ff6600;">%s</a>',
				'https://docs.tigsaw.com',
				esc_html__( 'Documentation', 'tigsaw' )
			),
			'support' => sprintf(
				'<a href="%s" target="_blank" style="color: #ff6600;">%s</a>',
				'https://tigsaw.com/contact',
				esc_html__( 'Support', 'tigsaw' )
			),
		);
		return array_merge( $links, $row_meta );
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'tigsaw_plugin_row_meta', 10, 2 );

/**
 * Register settings
 */
function tigsaw_register_settings() {
	register_setting( 
		'tigsaw_settings_group', 
		'tigsaw_container_id',
		array(
			'type' => 'string',
			'sanitize_callback' => 'tigsaw_sanitize_container_id',
			'default' => ''
		)
	);
	register_setting( 
		'tigsaw_settings_group', 
		'tigsaw_script_enabled',
		array(
			'type' => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default' => '0'
		)
	);
}
add_action( 'admin_init', 'tigsaw_register_settings' );

/**
 * Sanitize container ID and auto-enable script
 */
function tigsaw_sanitize_container_id( $value ) {
	$sanitized = sanitize_text_field( $value );
	
	// Auto-enable script when container ID is saved
	if ( ! empty( $sanitized ) ) {
		update_option( 'tigsaw_script_enabled', '1' );
	} else {
		update_option( 'tigsaw_script_enabled', '0' );
	}
	
	// Clear all caches after saving
	tigsaw_clear_all_caches();
	
	return $sanitized;
}

/**
 * Clear all caches - WordPress core and popular caching plugins
 */
function tigsaw_clear_all_caches() {
	// Clear WordPress object cache
	if ( function_exists( 'wp_cache_flush' ) ) {
		wp_cache_flush();
	}
	
	// Clear WordPress transients using delete_expired_transients()
	// This is a core WordPress function that safely clears transients
	delete_expired_transients( true );
	
	// Also clear site transients if on multisite
	if ( is_multisite() ) {
		delete_site_transient( '' ); // Clears all site transients
	}
	
	// WP Super Cache
	if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
	}
	
	// W3 Total Cache
	if ( function_exists( 'w3tc_flush_all' ) ) {
		w3tc_flush_all();
	}
	
	// WP Fastest Cache
	if ( function_exists( 'wpfc_clear_all_cache' ) ) {
		wpfc_clear_all_cache( true );
	}
	
	// LiteSpeed Cache
	if ( class_exists( 'LiteSpeed_Cache_API' ) && method_exists( 'LiteSpeed_Cache_API', 'purge_all' ) ) {
		LiteSpeed_Cache_API::purge_all();
	}
	if ( class_exists( 'LiteSpeed\Purge' ) ) {
		LiteSpeed\Purge::purge_all();
	}
	
	// WP Rocket
	if ( function_exists( 'rocket_clean_domain' ) ) {
		rocket_clean_domain();
	}
	
	// Autoptimize
	if ( class_exists( 'autoptimizeCache' ) ) {
		autoptimizeCache::clearall();
	}
	
	// Comet Cache
	if ( class_exists( 'comet_cache' ) && method_exists( 'comet_cache', 'clear' ) ) {
		comet_cache::clear();
	}
	
	// Cache Enabler
	if ( class_exists( 'Cache_Enabler' ) && method_exists( 'Cache_Enabler', 'clear_complete_cache' ) ) {
		Cache_Enabler::clear_complete_cache();
	}
	
	// Hummingbird Cache
	if ( class_exists( 'Hummingbird\WP_Hummingbird' ) ) {
		do_action( 'wphb_clear_page_cache' );
	}
	
	// SG Optimizer
	if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
		sg_cachepress_purge_cache();
	}
	
	// Breeze Cache (Cloudways)
	if ( class_exists( 'Breeze_PurgeCache' ) ) {
		do_action( 'breeze_clear_all_cache' );
	}
	
	// WP-Optimize
	if ( class_exists( 'WP_Optimize' ) && method_exists( 'WP_Optimize', 'get_page_cache' ) ) {
		$cache = WP_Optimize()->get_page_cache();
		if ( $cache && method_exists( $cache, 'purge' ) ) {
			$cache->purge();
		}
	}
	
	// Swift Performance
	if ( class_exists( 'Swift_Performance_Cache' ) && method_exists( 'Swift_Performance_Cache', 'clear_all_cache' ) ) {
		Swift_Performance_Cache::clear_all_cache();
	}
	
	// Perfmatters
	if ( function_exists( 'perfmatters_clear_cache' ) ) {
		perfmatters_clear_cache();
	}
	
	// NitroPack
	if ( function_exists( 'nitropack_sdk_purge' ) ) {
		nitropack_sdk_purge();
	}
	
	// Cloudflare (official plugin)
	if ( class_exists( 'CF\WordPress\Hooks' ) ) {
		do_action( 'cloudflare_purge_everything' );
	}
	
	// Pantheon Advanced Page Cache
	if ( function_exists( 'pantheon_wp_clear_edge_all' ) ) {
		pantheon_wp_clear_edge_all();
	}
	
	// Varnish Cache
	if ( function_exists( 'varnish_http_purge' ) ) {
		do_action( 'vhp_purge_all' );
	}
	
	// WP Engine
	if ( class_exists( 'WpeCommon' ) ) {
		if ( method_exists( 'WpeCommon', 'purge_memcached' ) ) {
			WpeCommon::purge_memcached();
		}
		if ( method_exists( 'WpeCommon', 'clear_maxcdn_cache' ) ) {
			WpeCommon::clear_maxcdn_cache();
		}
		if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
			WpeCommon::purge_varnish_cache();
		}
	}
	
	// Kinsta Cache
	global $kinsta_cache;
	if ( class_exists( 'Kinsta\Cache' ) && ! empty( $kinsta_cache ) ) {
		$kinsta_cache->kinsta_cache_purge->purge_complete_caches();
	}
	
	// Pagely
	if ( class_exists( 'PagelyCachePurge' ) ) {
		$purger = new PagelyCachePurge();
		$purger->purgeAll();
	}
	
	// Nginx Helper
	if ( class_exists( 'Nginx_Helper' ) ) {
		do_action( 'rt_nginx_helper_purge_all' );
	}
	
	// Redis Object Cache
	if ( function_exists( 'wp_cache_flush' ) && class_exists( 'Redis' ) ) {
		wp_cache_flush();
	}
}

/**
 * Enqueue admin styles for settings page
 */
function tigsaw_enqueue_admin_styles( $hook ) {
	// Only load on our settings page
	if ( 'toplevel_page_tigsaw-settings' !== $hook ) {
		return;
	}

	// Enqueue Tailwind CSS
	wp_enqueue_style(
		'tigsaw-tailwind',
		TIGSAW_PLUGIN_URL . 'assets/css/tailwind.min.css',
		array(),
		TIGSAW_VERSION,
		'all'
	);
}
add_action( 'admin_enqueue_scripts', 'tigsaw_enqueue_admin_styles' );

/**
 * Settings page HTML
 */
function tigsaw_settings_page() {
	// Check user permissions
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'tigsaw' ) );
	}

	// Get current domain
	$site_url = get_site_url();
	$domain = preg_replace( '#^https?://(www\.)?#', '', $site_url );
	$parsed = wp_parse_url( 'http://' . $domain );
	$domain = isset( $parsed['host'] ) ? rtrim( $parsed['host'], '/' ) : $domain;
	
	// Get saved settings
	$container_id = get_option( 'tigsaw_container_id', '' );
	$script_enabled = get_option( 'tigsaw_script_enabled', '0' );
	
	?>
	<div class="wrap">
		<div class="max-w-5xl mx-auto mt-8 mb-12">
			<!-- Header -->
			<div class="relative overflow-hidden bg-gradient-to-br from-primary via-primary-dark to-orange-700 rounded-2xl shadow-2xl mb-8">
				<!-- Background Pattern -->
				<div class="absolute inset-0 opacity-10">
					<div class="absolute transform rotate-45 -right-20 -top-20 w-64 h-64 bg-white rounded-full"></div>
					<div class="absolute transform -rotate-12 -left-10 -bottom-10 w-48 h-48 bg-white rounded-full"></div>
				</div>
				
				<!-- Content -->
				<div class="relative z-10 p-10">
					<div class="flex items-center justify-between flex-wrap gap-6">
						<div class="flex-1 min-w-[300px]">
							<!-- Logo/Icon Section -->
							<div class="flex items-center mb-4">
								<div class="bg-white rounded-2xl p-4 mr-5 shadow-xl ring-4 ring-white ring-opacity-20">
									<img src="<?php echo esc_url( TIGSAW_PLUGIN_URL . 'assets/images/logo.svg' ); ?>" 
									     alt="Tigsaw Logo" 
									     class="w-20 h-20 object-contain"
									     style="display: block;">
								</div>
								<div>
									<h1 class="text-4xl font-extrabold text-white mb-1 tracking-tight">
										<?php echo esc_html__( 'Tigsaw - AI + Visual Editor', 'tigsaw' ); ?>
									</h1>
									<div class="flex items-center gap-2">
										<span class="text-white text-opacity-90 text-sm font-medium"><?php echo esc_html__( 'Smart Script Integration', 'tigsaw' ); ?></span>
									</div>
								</div>
							</div>
							
							<!-- Description -->
							<p class="text-white text-opacity-95 text-lg leading-relaxed max-w-2xl">
								<?php echo esc_html__( 'Seamlessly verify domain connectivity and integrate smart script into your WordPress site', 'tigsaw' ); ?>
							</p>
						</div>
						
						<!-- Stats/Badge Section -->
						<div class="flex flex-col gap-3">
							<div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl px-6 py-3 text-center shadow-lg border border-white border-opacity-30">
								<div class="text-white text-opacity-90 text-xs font-semibold uppercase tracking-wider mb-1"><?php echo esc_html__( 'Version', 'tigsaw' ); ?></div>
								<div class="text-white text-2xl font-bold"><?php echo esc_html( TIGSAW_VERSION ); ?></div>
							</div>
							<div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl px-6 py-3 text-center shadow-lg border border-white border-opacity-30">
								<div class="text-white text-opacity-90 text-xs font-semibold uppercase tracking-wider mb-1"><?php echo esc_html__( 'Status', 'tigsaw' ); ?></div>
								<div class="flex items-center justify-center gap-2">
									<?php if ( $container_id && $script_enabled === '1' ) : ?>
										<span class="w-2 h-2 bg-green-400 rounded-full"></span>
										<span class="text-white text-sm font-bold"><?php echo esc_html__( 'Active', 'tigsaw' ); ?></span>
									<?php else : ?>
										<span class="w-2 h-2 bg-gray-300 rounded-full"></span>
										<span class="text-white text-sm font-bold"><?php echo esc_html__( 'Inactive', 'tigsaw' ); ?></span>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
					
					<!-- Quick Links -->
					<div class="mt-6 pt-6 border-t border-white border-opacity-20 flex items-center gap-6 flex-wrap">
						<a href="https://tigsaw.com" target="_blank" class="flex items-center gap-2 text-white hover:text-white text-opacity-90 hover:text-opacity-100 text-sm font-medium transition-all align-middle">
							<span class="dashicons dashicons-external text-xl flex-shrink-0 self-center" style="line-height:1;"></span>
							<span class="align-middle"><?php echo esc_html__( 'Visit Tigsaw.com', 'tigsaw' ); ?></span>
						</a>
						<a href="https://docs.tigsaw.com/" target="_blank" class="flex items-center gap-2 text-white hover:text-white text-opacity-90 hover:text-opacity-100 text-sm font-medium transition-all align-middle">
							<span class="dashicons dashicons-book text-xl flex-shrink-0 self-center" style="line-height:1;"></span>
							<span class="align-middle"><?php echo esc_html__( 'Documentation', 'tigsaw' ); ?></span>
						</a>
						<a href="https://tigsaw.com/contact" target="_blank" class="flex items-center gap-2 text-white hover:text-white text-opacity-90 hover:text-opacity-100 text-sm font-medium transition-all align-middle">
							<span class="dashicons dashicons-sos text-xl flex-shrink-0 self-center" style="line-height:1;"></span>
							<span class="align-middle"><?php echo esc_html__( 'Support', 'tigsaw' ); ?></span>
						</a>
					</div>
				</div>
			</div>

			<!-- Main Content Card -->
			<div class="bg-white rounded-lg shadow-lg p-8">
				<!-- Domain Detection -->
				<div class="mb-8 pb-6 border-b border-gray-200">
					<h2 class="text-xl font-semibold text-gray-800 mb-4"><?php echo esc_html__( 'Site Domain', 'tigsaw' ); ?></h2>
					<div class="flex items-center bg-gray-50 rounded-lg p-4">
						<span class="dashicons dashicons-admin-site text-primary text-2xl mr-3"></span>
						<code class="text-lg font-mono text-gray-700 font-semibold"><?php echo esc_html( $domain ); ?></code>
					</div>
				</div>

				<!-- Container ID Section -->
				<div class="mb-8">
					<h2 class="text-xl font-semibold text-gray-800 mb-4"><?php echo esc_html__( 'Container Configuration', 'tigsaw' ); ?></h2>
					
					<?php if ( $container_id && $script_enabled === '1' ) : ?>
						<!-- Active Script Status -->
						<div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-6 rounded-r-lg mb-6">
							<div class="flex items-start">
								<span class="dashicons dashicons-yes-alt text-3xl mr-4"></span>
								<div class="flex-1">
									<p class="font-bold text-lg mb-3"><?php echo esc_html__( 'Smart Script Active', 'tigsaw' ); ?></p>
									<div class="bg-white bg-opacity-60 rounded-lg p-4 mb-4">
										<p class="text-sm"><span class="font-semibold"><?php echo esc_html__( 'Container ID:', 'tigsaw' ); ?></span> <code class="bg-green-100 px-2 py-1 rounded font-mono font-bold"><?php echo esc_html( $container_id ); ?></code></p>
									</div>
									<div class="flex gap-3">
										<button id="tigsaw-change-container" class="bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
											<span class="dashicons dashicons-update align-middle mr-1"></span>
											<?php echo esc_html__( 'Change Container', 'tigsaw' ); ?>
										</button>
										<button id="tigsaw-remove-script" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
											<span class="dashicons dashicons-trash align-middle mr-1"></span>
											<?php echo esc_html__( 'Remove Script', 'tigsaw' ); ?>
										</button>
									</div>
								</div>
							</div>
						</div>

						<!-- Change Container Modal -->
						<div id="tigsaw-change-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%;">
							<div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
								<!-- Modal Header -->
								<div class="bg-gradient-to-r from-primary to-primary-dark p-6 rounded-t-2xl">
									<div class="flex items-center justify-between">
										<h3 class="text-2xl font-bold text-white flex items-center gap-3">
											<span class="dashicons dashicons-update text-3xl"></span>
											<?php echo esc_html__( 'Change Container ID', 'tigsaw' ); ?>
										</h3>
										<button id="tigsaw-modal-close" class="text-white hover:text-gray-200 transition-colors">
											<span class="dashicons dashicons-no-alt text-3xl"></span>
										</button>
									</div>
								</div>

								<!-- Modal Body -->
								<div class="p-6">
									<!-- Current Container Info -->
									<div class="bg-gray-50 border-l-4 border-gray-400 p-4 rounded-r-lg mb-6">
										<p class="text-sm text-gray-700">
											<span class="font-semibold"><?php echo esc_html__( 'Current Container:', 'tigsaw' ); ?></span> 
											<code class="bg-gray-200 px-2 py-1 rounded font-mono font-bold"><?php echo esc_html( $container_id ); ?></code>
										</p>
									</div>

									<!-- Fetch Section -->
									<div id="tigsaw-modal-fetch-section" class="mb-6">
										<div class="flex gap-4">
											<button id="tigsaw-modal-fetch-btn" class="flex-1 bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
												<span class="dashicons dashicons-update-alt align-middle mr-2"></span>
												<?php echo esc_html__( 'Fetch Available Containers', 'tigsaw' ); ?>
											</button>
											<?php if ( strpos( $domain, 'localhost' ) !== false || strpos( $domain, '127.0.0.1' ) !== false ) : ?>
											<button id="tigsaw-modal-manual-btn" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
												<span class="dashicons dashicons-edit align-middle mr-2"></span>
												<?php echo esc_html__( 'Enter Manually', 'tigsaw' ); ?>
											</button>
											<?php endif; ?>
										</div>
									</div>

									<!-- Manual Entry Section -->
									<div id="tigsaw-modal-manual-section" class="hidden mb-6">
										<div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
											<div class="flex items-start">
												<span class="dashicons dashicons-edit text-2xl mr-3 text-blue-600"></span>
												<div class="flex-1">
													<p class="font-semibold mb-3 text-blue-800">Manual Container ID Entry</p>
													<div class="flex gap-3 items-center">
														<input type="text" id="tigsaw-modal-manual-input" placeholder="Enter container ID (e.g., CW172SE6)" class="flex-1 px-4 py-2 border-2 border-blue-300 rounded-lg focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600 focus:ring-opacity-20 transition-all duration-200 font-mono">
														<button id="tigsaw-modal-manual-confirm" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200">
															<span class="dashicons dashicons-yes-alt align-middle mr-1"></span>
															Use This ID
														</button>
													</div>
													<p class="text-sm mt-2 text-blue-700">Enter your Tigsaw container ID for testing purposes.</p>
												</div>
											</div>
										</div>
									</div>

									<!-- Loading -->
									<div id="tigsaw-modal-loading" class="hidden mb-6">
										<div class="flex items-center bg-blue-50 text-blue-700 p-4 rounded-lg">
											<svg class="animate-spin h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
												<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
												<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
											</svg>
											<span class="font-medium">Fetching available containers from Tigsaw...</span>
										</div>
									</div>

									<!-- Error -->
									<div id="tigsaw-modal-error" class="hidden mb-6">
										<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg">
											<div class="flex items-start">
												<span class="dashicons dashicons-warning text-2xl mr-3"></span>
												<div>
													<p class="font-semibold mb-2">No Container Found</p>
													<p class="mb-3">No container ID found for your domain. Please create one on Tigsaw.</p>
													<a href="https://tigsaw.com" target="_blank" class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
														<span class="dashicons dashicons-external align-middle mr-2"></span>
														Create Container on Tigsaw
													</a>
												</div>
											</div>
										</div>
									</div>

									<!-- Form -->
									<form method="post" action="options.php" id="tigsaw-modal-form" class="hidden">
										<?php settings_fields( 'tigsaw_settings_group' ); ?>
										<div class="mb-6">
											<label for="tigsaw_modal_container_id" class="block text-base font-bold text-gray-800 mb-3 flex items-center gap-2">
												<span class="dashicons dashicons-portfolio text-primary text-xl"></span>
												Select New Container ID
											</label>
											<div class="relative">
												<select name="tigsaw_container_id" id="tigsaw_modal_container_id" class="w-full px-5 py-4 pr-12 text-lg font-mono font-semibold border-2 border-gray-300 rounded-xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-20 transition-all duration-200 bg-white hover:border-primary-light shadow-sm hover:shadow-md cursor-pointer appearance-none bg-gradient-to-r from-white to-gray-50">
													<option value="">-- Select a Container --</option>
												</select>
												<div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
													<svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
													</svg>
												</div>
											</div>
											<div class="mt-3 bg-blue-50 border-l-4 border-primary rounded-r-lg p-4 flex items-start gap-3">
												<span class="dashicons dashicons-info text-primary text-xl flex-shrink-0 mt-0.5"></span>
												<p class="text-sm text-gray-700 leading-relaxed">
													The new container will be <strong class="text-primary">automatically activated</strong> when you save.
												</p>
											</div>
										</div>

										<!-- Modal Activation Status Messages -->
										<div id="tigsaw-modal-activation-loading" class="hidden mb-6">
											<div class="flex items-center bg-blue-50 text-blue-700 p-4 rounded-lg">
												<svg class="animate-spin h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
													<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
													<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
												</svg>
												<span class="font-medium">Verifying container with Tigsaw...</span>
											</div>
										</div>

										<div id="tigsaw-modal-activation-error" class="hidden mb-6">
											<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg">
												<div class="flex items-start">
													<span class="dashicons dashicons-warning text-2xl mr-3"></span>
													<div class="flex-1">
														<p class="font-semibold mb-2">Activation Failed</p>
														<p id="tigsaw-modal-activation-error-message" class="text-sm"></p>
													</div>
												</div>
											</div>
										</div>

										<input type="hidden" name="tigsaw_script_enabled" value="1">

										<div class="flex gap-4">
											<button type="submit" class="flex-1 bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-8 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
												<span class="dashicons dashicons-yes-alt align-middle mr-2"></span>
												Save & Activate New Container
											</button>
											<button type="button" id="tigsaw-modal-cancel" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
												Cancel
											</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					<?php else : ?>
						<?php if ( strpos( $domain, 'localhost' ) !== false || strpos( $domain, '127.0.0.1' ) !== false ) : ?>
							<!-- Localhost Notice -->
							<div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-4 rounded-r-lg mb-6">
								<div class="flex items-start">
									<span class="dashicons dashicons-info text-2xl mr-3"></span>
									<div>
										<p class="font-semibold mb-1">Development Environment Detected</p>
										<p class="text-sm">You're running on <code class="bg-yellow-100 px-2 py-1 rounded font-mono"><?php echo esc_html( $domain ); ?></code>. You can manually enter a container ID or fetch from the API.</p>
									</div>
								</div>
							</div>
							
							<div id="tigsaw-fetch-section" class="mb-6">
								<div class="flex gap-4">
									<button id="tigsaw-fetch-btn" class="bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
										<span class="dashicons dashicons-update-alt align-middle mr-2"></span>
										Fetch Container ID
									</button>
									<button id="tigsaw-manual-btn" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
										<span class="dashicons dashicons-edit align-middle mr-2"></span>
										Enter Manual Container ID
									</button>
								</div>
							</div>
							
							<div id="tigsaw-manual-section" class="hidden mb-6">
								<div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 rounded-r-lg">
									<div class="flex items-start">
										<span class="dashicons dashicons-edit text-2xl mr-3"></span>
										<div class="flex-1">
											<p class="font-semibold mb-3">Manual Container ID Entry</p>
											<div class="flex gap-3 items-center">
												<input type="text" id="tigsaw-manual-input" placeholder="Enter container ID (e.g., CW172SE6)" class="flex-1 px-4 py-2 border-2 border-blue-300 rounded-lg focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600 focus:ring-opacity-20 transition-all duration-200 font-mono">
												<button id="tigsaw-manual-confirm" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition-colors duration-200">
													<span class="dashicons dashicons-yes-alt align-middle mr-1"></span>
													Use This ID
												</button>
											</div>
											<p class="text-sm mt-2 text-blue-700">Enter your Tigsaw container ID for testing purposes.</p>
										</div>
									</div>
								</div>
							</div>
						<?php else : ?>
							<div id="tigsaw-fetch-section" class="mb-6">
								<button id="tigsaw-fetch-btn" class="bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
									<span class="dashicons dashicons-update-alt align-middle mr-2"></span>
									Fetch Container ID
								</button>
							</div>
						<?php endif; ?>
					<?php endif; ?>

					<div id="tigsaw-loading" class="hidden mb-6">
						<div class="flex items-center bg-blue-50 text-blue-700 p-4 rounded-lg">
							<svg class="animate-spin h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
								<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
								<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
							</svg>
							<span class="font-medium">Fetching container ID from Tigsaw...</span>
						</div>
					</div>

					<div id="tigsaw-error" class="hidden mb-6">
						<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg">
							<div class="flex items-start">
								<span class="dashicons dashicons-warning text-2xl mr-3"></span>
								<div>
									<p class="font-semibold mb-2">No Container Found</p>
									<p class="mb-3">No container ID found for your domain. Please create one on Tigsaw.</p>
									<a href="https://tigsaw.com" target="_blank" class="inline-flex items-center bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
										<span class="dashicons dashicons-external align-middle mr-2"></span>
										Create Container on Tigsaw
									</a>
								</div>
							</div>
						</div>
					</div>

					<?php if ( ! ( $container_id && $script_enabled === '1' ) ) : ?>
						<form method="post" action="options.php" id="tigsaw-settings-form" class="hidden">
							<?php settings_fields( 'tigsaw_settings_group' ); ?>
							<?php do_settings_sections( 'tigsaw_settings_group' ); ?>

						<div class="mb-8">
							<label for="tigsaw_container_id" class="block text-base font-bold text-gray-800 mb-3 flex items-center gap-2">
								<span class="dashicons dashicons-portfolio text-primary text-xl"></span>
								Select Container ID
							</label>
							<div class="relative">
								<select name="tigsaw_container_id" id="tigsaw_container_id" class="w-full px-5 py-4 pr-12 text-lg font-mono font-semibold border-2 border-gray-300 rounded-xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary focus:ring-opacity-20 transition-all duration-200 bg-white hover:border-primary-light shadow-sm hover:shadow-md cursor-pointer appearance-none bg-gradient-to-r from-white to-gray-50">
									<option value="">-- Select a Container --</option>
								</select>
								<div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
									<svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
									</svg>
								</div>
							</div>
							<div class="mt-3 bg-blue-50 border-l-4 border-primary rounded-r-lg p-4 flex items-start gap-3">
								<span class="dashicons dashicons-info text-primary text-xl flex-shrink-0 mt-0.5"></span>
								<p class="text-sm text-gray-700 leading-relaxed">
									The smart script will be <strong class="text-primary">automatically activated</strong> and added to all pages once you save your container selection.
								</p>
							</div>
						</div>

						<!-- Activation Status Messages -->
						<div id="tigsaw-activation-loading" class="hidden mb-6">
							<div class="flex items-center bg-blue-50 text-blue-700 p-4 rounded-lg">
								<svg class="animate-spin h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
									<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
									<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
								</svg>
								<span class="font-medium">Verifying container with Tigsaw...</span>
							</div>
						</div>

						<div id="tigsaw-activation-error" class="hidden mb-6">
							<div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg">
								<div class="flex items-start">
									<span class="dashicons dashicons-warning text-2xl mr-3"></span>
									<div class="flex-1">
										<p class="font-semibold mb-2">Activation Failed</p>
										<p id="tigsaw-activation-error-message" class="text-sm"></p>
									</div>
								</div>
							</div>
						</div>

						<input type="hidden" name="tigsaw_script_enabled" value="1">							<div class="flex gap-4">
								<button type="submit" class="bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-8 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
									<span class="dashicons dashicons-yes-alt align-middle mr-2"></span>
									Save & Activate Script
								</button>
							</div>
						</form>
					<?php endif; ?>
				</div>
			</div>

			<!-- Footer Info -->
			<div class="mt-6 text-center text-gray-500 text-sm">
				<p><?php 
				/* translators: %s: Plugin version number */
				echo sprintf( esc_html__( 'Tigsaw v%s', 'tigsaw' ), esc_html( TIGSAW_VERSION ) ); ?> | <a href="https://tigsaw.com" target="_blank" class="text-primary hover:text-primary-dark"><?php echo esc_html__( 'Visit Tigsaw.com', 'tigsaw' ); ?></a></p>
			</div>
		</div>

		<script>
		var tigsawL10n = {
			selectContainer: <?php echo wp_json_encode( __( 'Please select a container ID.', 'tigsaw' ) ); ?>,
			containerFormat: <?php echo wp_json_encode( __( 'Container ID format looks unusual. Continue anyway?\n\nTypical format: CW172SE6 (6-12 alphanumeric characters)', 'tigsaw' ) ); ?>,
			enterValid: <?php echo wp_json_encode( __( 'Please enter a valid container ID.', 'tigsaw' ) ); ?>,
			removeConfirm: <?php echo wp_json_encode( __( 'Are you sure you want to remove the Tigsaw Smart Script? This will disconnect your site from the Tigsaw platform.', 'tigsaw' ) ); ?>,
			selectContainerOption: <?php echo wp_json_encode( __( '-- Select a Container --', 'tigsaw' ) ); ?>,
			manualLabel: <?php echo wp_json_encode( __( ' (Manual)', 'tigsaw' ) ); ?>,
			verificationFailed: <?php echo wp_json_encode( __( 'Container verification failed', 'tigsaw' ) ); ?>,
			unableToVerify: <?php echo wp_json_encode( __( 'Unable to verify container with Tigsaw. ', 'tigsaw' ) ); ?>,
			containerNotFound: <?php echo wp_json_encode( __( 'Container not found.', 'tigsaw' ) ); ?>,
			internalError: <?php echo wp_json_encode( __( 'Internal server error. Please try again later.', 'tigsaw' ) ); ?>,
			checkConnection: <?php echo wp_json_encode( __( 'Please check your connection and try again.', 'tigsaw' ) ); ?>
		};
		</script>
		<script>
		jQuery(document).ready(function($) {
			const domain = '<?php echo esc_js( $domain ); ?>';
			const savedContainerId = '<?php echo esc_js( $container_id ); ?>';

			// Function to verify container with API
			function verifyContainer(containerId, onSuccess, onError, isModal) {
				const loadingEl = isModal ? '#tigsaw-modal-activation-loading' : '#tigsaw-activation-loading';
				const errorEl = isModal ? '#tigsaw-modal-activation-error' : '#tigsaw-activation-error';
				const errorMsgEl = isModal ? '#tigsaw-modal-activation-error-message' : '#tigsaw-activation-error-message';

				// Skip verification on localhost or 127.0.0.1
				if (domain.indexOf('localhost') !== -1 || domain.indexOf('127.0.0.1') !== -1) {
					onSuccess();
					return;
				}

				$(loadingEl).removeClass('hidden').show();
				$(errorEl).hide();

				$.ajax({
					url: 'https://tigsaw.com/api/integration/verify?containerId=' + encodeURIComponent(containerId),
					type: 'PUT',
					dataType: 'json',
					success: function(response) {
						$(loadingEl).hide();
                        
						if (response && response.status === true) {
							// Verification successful
							console.log('Container verified:', response.message);
							onSuccess();
						} else {
							// Container not found or verification failed
							const errorMessage = response.message || tigsawL10n.verificationFailed;
							$(errorMsgEl).text(errorMessage);
							$(errorEl).removeClass('hidden').show();
							onError(errorMessage);
						}
					},
					error: function(xhr, status, error) {
						$(loadingEl).hide();
                        
						let errorMessage = tigsawL10n.unableToVerify;
                        
						if (xhr.status === 404) {
							errorMessage += tigsawL10n.containerNotFound;
						} else if (xhr.status === 500) {
							errorMessage += tigsawL10n.internalError;
						} else {
							errorMessage += tigsawL10n.checkConnection;
						}
                        
						$(errorMsgEl).text(errorMessage);
						$(errorEl).removeClass('hidden').show();
						onError(errorMessage);
						console.error('Verification API Error:', error, xhr);
					}
				});
			}

			// Intercept main form submission
			$('#tigsaw-settings-form').on('submit', function(e) {
				e.preventDefault();
				
				const selectedContainerId = $('#tigsaw_container_id').val();
				
				if (!selectedContainerId) {
					alert(tigsawL10n.selectContainer);
					return;
				}

				const $form = $(this);
				const $submitBtn = $form.find('button[type="submit"]');
				$submitBtn.prop('disabled', true);

				// Verify container before submitting
				verifyContainer(
					selectedContainerId,
					function() {
						// Success - submit the form
						$submitBtn.prop('disabled', false);
						$form.off('submit').submit();
					},
					function(errorMessage) {
						// Error - re-enable button
						$submitBtn.prop('disabled', false);
					},
					false
				);
			});

			// Intercept modal form submission
			$('#tigsaw-modal-form').on('submit', function(e) {
				e.preventDefault();
				
				const selectedContainerId = $('#tigsaw_modal_container_id').val();
				
				if (!selectedContainerId) {
					alert(tigsawL10n.selectContainer);
					return;
				}

				const $form = $(this);
				const $submitBtn = $form.find('button[type="submit"]');
				$submitBtn.prop('disabled', true);

				// Verify container before submitting
				verifyContainer(
					selectedContainerId,
					function() {
						// Success - submit the form
						$submitBtn.prop('disabled', false);
						$form.off('submit').submit();
					},
					function(errorMessage) {
						// Error - re-enable button
						$submitBtn.prop('disabled', false);
					},
					true
				);
			});

			// Fetch container ID
			$('#tigsaw-fetch-btn').on('click', function() {
				$('#tigsaw-fetch-section').hide();
				$('#tigsaw-manual-section').hide();
				$('#tigsaw-loading').removeClass('hidden').show();
				$('#tigsaw-error').hide();
				$('#tigsaw-settings-form').hide();

				$.ajax({
					url: 'https://tigsaw.com/api/integration/get-container?url=' + encodeURIComponent(domain),
					type: 'GET',
					dataType: 'json',
					success: function(response) {
						$('#tigsaw-loading').hide();

						if (response && response.containerIds && response.containerIds.length > 0) {
							// Populate dropdown
							const $select = $('#tigsaw_container_id');
							$select.empty();
							$select.append('<option value="">' + tigsawL10n.selectContainerOption + '</option>');
							
							response.containerIds.forEach(function(containerId) {
								const selected = (containerId === savedContainerId) ? 'selected' : '';
								$select.append('<option value="' + containerId + '" ' + selected + '>' + containerId + '</option>');
							});

							$('#tigsaw-settings-form').removeClass('hidden').show();
						} else {
							$('#tigsaw-error').removeClass('hidden').show();
							$('#tigsaw-fetch-section').show();
						}
					},
					error: function(xhr, status, error) {
						$('#tigsaw-loading').hide();
						$('#tigsaw-error').removeClass('hidden').show();
						$('#tigsaw-fetch-section').show();
						console.error('API Error:', error);
					}
				});
			});

			// Manual container ID button (localhost only)
			$('#tigsaw-manual-btn').on('click', function() {
				$('#tigsaw-fetch-section').hide();
				$('#tigsaw-loading').hide();
				$('#tigsaw-error').hide();
				$('#tigsaw-settings-form').hide();
				$('#tigsaw-manual-section').removeClass('hidden').show();
				$('#tigsaw-manual-input').focus();
			});

			// Manual container ID confirmation
			$('#tigsaw-manual-confirm').on('click', function() {
				const manualContainerId = $('#tigsaw-manual-input').val().trim();
				
				if (manualContainerId === '') {
					alert(tigsawL10n.enterValid);
					return;
				}

				// Validate format (alphanumeric, typically 8 characters)
				if (!/^[A-Z0-9]{6,12}$/i.test(manualContainerId)) {
					if (!confirm(tigsawL10n.containerFormat)) {
						return;
					}
				}

				// Populate dropdown with manual ID
				const $select = $('#tigsaw_container_id');
				$select.empty();
				$select.append('<option value="">' + tigsawL10n.selectContainerOption + '</option>');
				$select.append('<option value="' + manualContainerId + '" selected>' + manualContainerId + tigsawL10n.manualLabel + '</option>');

				// Hide manual section and show form
				$('#tigsaw-manual-section').hide();
				$('#tigsaw-settings-form').removeClass('hidden').show();
			});

			// Allow Enter key in manual input
			$('#tigsaw-manual-input').on('keypress', function(e) {
				if (e.which === 13) {
					e.preventDefault();
					$('#tigsaw-manual-confirm').trigger('click');
				}
			});

			// Auto-fetch if container ID exists but script not active
			<?php if ( $container_id && $script_enabled !== '1' ) : ?>
				$('#tigsaw-fetch-btn').trigger('click');
			<?php endif; ?>

			// Change container handler
			$('#tigsaw-change-container').on('click', function() {
				$('#tigsaw-change-modal').removeClass('hidden');
			});

			// Modal close handlers
			$('#tigsaw-modal-close, #tigsaw-modal-cancel').on('click', function() {
				$('#tigsaw-change-modal').addClass('hidden');
				// Reset modal state
				$('#tigsaw-modal-fetch-section').show();
				$('#tigsaw-modal-manual-section').hide();
				$('#tigsaw-modal-loading').hide();
				$('#tigsaw-modal-error').hide();
				$('#tigsaw-modal-form').hide();
				$('#tigsaw-modal-manual-input').val('');
			});

			// Close modal on outside click
			$('#tigsaw-change-modal').on('click', function(e) {
				if ($(e.target).is('#tigsaw-change-modal')) {
					$('#tigsaw-modal-close').trigger('click');
				}
			});

			// Modal fetch button
			$('#tigsaw-modal-fetch-btn').on('click', function() {
				$('#tigsaw-modal-fetch-section').hide();
				$('#tigsaw-modal-manual-section').hide();
				$('#tigsaw-modal-loading').removeClass('hidden').show();
				$('#tigsaw-modal-error').hide();
				$('#tigsaw-modal-form').hide();

				$.ajax({
					url: 'https://tigsaw.com/api/integration/get-container?url=' + encodeURIComponent(domain),
					type: 'GET',
					dataType: 'json',
					success: function(response) {
						$('#tigsaw-modal-loading').hide();

						if (response && response.containerIds && response.containerIds.length > 0) {
							// Populate dropdown
							const $select = $('#tigsaw_modal_container_id');
							$select.empty();
							$select.append('<option value="">' + tigsawL10n.selectContainerOption + '</option>');
							
							response.containerIds.forEach(function(containerId) {
								// Don't pre-select the current one, let user choose
								$select.append('<option value="' + containerId + '">' + containerId + '</option>');
							});

							$('#tigsaw-modal-form').removeClass('hidden').show();
						} else {
							$('#tigsaw-modal-error').removeClass('hidden').show();
							$('#tigsaw-modal-fetch-section').show();
						}
					},
					error: function(xhr, status, error) {
						$('#tigsaw-modal-loading').hide();
						$('#tigsaw-modal-error').removeClass('hidden').show();
						$('#tigsaw-modal-fetch-section').show();
						console.error('API Error:', error);
					}
				});
			});

			// Modal manual container ID button
			$('#tigsaw-modal-manual-btn').on('click', function() {
				$('#tigsaw-modal-fetch-section').hide();
				$('#tigsaw-modal-loading').hide();
				$('#tigsaw-modal-error').hide();
				$('#tigsaw-modal-form').hide();
				$('#tigsaw-modal-manual-section').removeClass('hidden').show();
				$('#tigsaw-modal-manual-input').focus();
			});

			// Modal manual container ID confirmation
			$('#tigsaw-modal-manual-confirm').on('click', function() {
				const manualContainerId = $('#tigsaw-modal-manual-input').val().trim();
				
				if (manualContainerId === '') {
					alert(tigsawL10n.enterValid);
					return;
				}

				// Validate format (alphanumeric, typically 8 characters)
				if (!/^[A-Z0-9]{6,12}$/i.test(manualContainerId)) {
					if (!confirm(tigsawL10n.containerFormat)) {
						return;
					}
				}

				// Populate dropdown with manual ID
				const $select = $('#tigsaw_modal_container_id');
				$select.empty();
				$select.append('<option value="">' + tigsawL10n.selectContainerOption + '</option>');
				$select.append('<option value="' + manualContainerId + '" selected>' + manualContainerId + tigsawL10n.manualLabel + '</option>');

				// Hide manual section and show form
				$('#tigsaw-modal-manual-section').hide();
				$('#tigsaw-modal-form').removeClass('hidden').show();
			});

			// Allow Enter key in modal manual input
			$('#tigsaw-modal-manual-input').on('keypress', function(e) {
				if (e.which === 13) {
					e.preventDefault();
					$('#tigsaw-modal-manual-confirm').trigger('click');
				}
			});

			// Remove script handler
			$('#tigsaw-remove-script').on('click', function() {
				if (confirm(tigsawL10n.removeConfirm)) {
					// Create hidden form to submit removal
					var form = $('<form method="post" action="options.php"></form>');
					form.append('<?php echo wp_kses( wp_nonce_field( 'tigsaw_settings_group-options', '_wpnonce', true, false ), array( 'input' => array( 'type' => array(), 'id' => array(), 'name' => array(), 'value' => array() ) ) ); ?>');
					form.append('<input type="hidden" name="option_page" value="tigsaw_settings_group">');
					form.append('<input type="hidden" name="action" value="update">');
					form.append('<input type="hidden" name="tigsaw_container_id" value="">');
					form.append('<input type="hidden" name="tigsaw_script_enabled" value="0">');
					$('body').append(form);
					form.submit();
				}
			});
		});
		</script>
	</div>
	<?php
}

/**
 * Enqueue Smart Script in header
 */
function tigsaw_enqueue_smart_script() {
	$container_id = get_option( 'tigsaw_container_id', '' );
	$script_enabled = get_option( 'tigsaw_script_enabled', '0' );

	if ( $container_id && $script_enabled === '1' ) {
		$script_url = 'https://static.tigsaw.com/delivery/smartscript.js?container=' . esc_attr( $container_id ) . '&mode=wordpress';
		
		wp_enqueue_script(
			'tigsaw-smart-script',
			$script_url,
			array(),
			null,
			array(
				'strategy'  => 'async',
				'in_footer' => false,
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'tigsaw_enqueue_smart_script' );
