<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package ReadingTimeCalculator
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete options from database.
delete_option( 'rtc_settings' );

// Delete cached transients.
global $wpdb;
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_rtc_rt_%'" );
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_rtc_rt_%'" );