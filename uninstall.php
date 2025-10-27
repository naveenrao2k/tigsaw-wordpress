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
	global $wpdb;
	$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
	$original_blog_id = get_current_blog_id();

	foreach ( $blog_ids as $blog_id ) {
		switch_to_blog( $blog_id );
		delete_option( 'tigsaw_container_id' );
		delete_option( 'tigsaw_script_enabled' );
		delete_option( 'tigsaw_activation_redirect' );
	}

	switch_to_blog( $original_blog_id );
}
