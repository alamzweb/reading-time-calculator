<?php
/**
 * Plugin Name:       Reading Time Calculator
 * Plugin URI:        https://sitecroftdev.vercel.app/plugins/reading-time-calculator
 * Description:       Automatically calculates and displays estimated reading time for WordPress posts and pages.
 * Version:           1.0.0
 * Author:            Saad
 * Author URI:        https://sitecroftdev.vercel.app/
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       reading-time-calculator
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define plugin constants.
define( 'RTC_VERSION', '1.0.0' );
define( 'RTC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'RTC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'RTC_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Autoload core classes.
require_with_clean_scope( RTC_PLUGIN_DIR . 'includes/class-plugin.php' );
require_with_clean_scope( RTC_PLUGIN_DIR . 'includes/class-settings.php' );
require_with_clean_scope( RTC_PLUGIN_DIR . 'includes/class-reading-time.php' );
require_with_clean_scope( RTC_PLUGIN_DIR . 'includes/class-shortcode.php' );
require_with_clean_scope( RTC_PLUGIN_DIR . 'includes/class-gutenberg-block.php' );

/**
 * Scoped file inclusion utility.
 *
 * @param string $file Absolute path to file.
 */
function require_with_clean_scope( string $file ): void {
	if ( file_exists( $file ) ) {
		require_once $file;
	}
}

/**
 * Activation Hook logic.
 */
function rtc_activate_plugin(): void {
	if ( false === get_option( 'rtc_settings' ) ) {
		$default_settings = [
			'enable_plugin'   => 1,
			'wpm'             => 200,
			'prefix'          => __( 'Reading Time:', 'reading-time-calculator' ),
			'display_position'=> 'before',
			'post_types'      => [ 'post', 'page' ],
		];
		update_option( 'rtc_settings', $default_settings );
	}
}
register_activation_hook( __FILE__, 'rtc_activate_plugin' );

/**
 * Deactivation Hook logic.
 */
function rtc_deactivate_plugin(): void {
	// Flush rewrite rules if needed, or leave clear for custom implementations.
}
register_deactivation_hook( __FILE__, 'rtc_deactivate_plugin' );

// Initialize the plugin instance.
add_action( 'plugins_loaded', function() {
	\RTC\Includes\Plugin::get_instance();
} );