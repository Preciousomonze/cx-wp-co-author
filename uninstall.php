<?php
/**
 *  Uninstall.
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global $wpdb, $wp_version;

// Keys.
$wpdb->query( "DELETE FROM {$wpdb->base_prefix}options WHERE option_name LIKE '%cx_co_ads%'" );

// Clear any cached data that has been removed.
wp_cache_flush();
