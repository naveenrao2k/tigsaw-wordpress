<?php
/**
 * Uninstall Tigsaw Smart Script
 *
 * @package Tigsaw
 */

// If uninstall not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete plugin options
delete_option( 'tigsaw_container_id' );
delete_option( 'tigsaw_script_enabled' );
delete_option( 'tigsaw_activation_redirect' );

// For multisite
if ( is_multisite() ) {
	// Get all sites using WordPress function
	$sites = get_sites(
		array(
			'number' => 9999,
		)
	);

	foreach ( $sites as $site ) {
		switch_to_blog( $site->blog_id );
		delete_option( 'tigsaw_container_id' );
		delete_option( 'tigsaw_script_enabled' );
		delete_option( 'tigsaw_activation_redirect' );
	}

	restore_current_blog();
}
