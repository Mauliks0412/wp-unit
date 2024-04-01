<?php
/**
 * Plugin Name: WP Unit
 * Plugin URI: https://github.com/Mauliks0412
 * Description: WP Unit Plugin For unit tests.
 * Version: 1.0.0
 * Author: Maulik
 * Author URI: https://github.com/Mauliks0412
 * Text Domain: wpunit
 * Domain Path: /languages/
 *
 * @package WP Unit
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Basic Plugin Definitions
 *
 * @package WP Unit
 * @since   1.0.0
 */
if ( ! defined( 'WPU_VERSION' ) ) {
	define( 'WPU_VERSION', '1.0.0' ); // version of plugin.
}
if ( ! defined( 'WPU_DIR' ) ) {
	define( 'WPU_DIR', dirname( __FILE__ ) ); // plugin dir.
}
if ( ! defined( 'WPU_URL' ) ) {
	define( 'WPU_URL', plugin_dir_url( __FILE__ ) ); // plugin url.
}
if ( ! defined( 'WPU_INC_DIR' ) ) {
	define( 'WPU_INC_DIR', WPU_DIR . '/includes' ); // plugin admin dir.
}
if ( ! defined( 'WPU_INC_URL' ) ) {
	define( 'WPU_INC_URL', WPU_URL . 'includes' );    // Plugin include url.
}
if ( ! defined( 'WPU_TEXT_DOMAIN' ) ) {
	define( 'WPU_TEXT_DOMAIN', 'wpunit' ); // text domain for translation.
}
if ( ! defined( 'WPU_PLUGIN_BASENAME' ) ) {
	define( 'WPU_PLUGIN_BASENAME', basename( WPU_DIR ) ); // Plugin base name.
}

/**
 * Load Text Domain
 *
 * This gets the plugin ready for translation.
 *
 * @package WP Unit
 * @since   1.0.0
 */
function wpu_load_textdomain() {
	// Set filter for plugin's languages directory.
	$wpu_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$wpu_lang_dir = apply_filters( 'wpu_languages_directory', $wpu_lang_dir );

	// Traditional WordPress plugin locale filter.
	$locale = apply_filters( 'plugin_locale', get_locale(), 'wpunit' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'wpunit', $locale );

	// Setup paths to current locale file.
	$mofile_local  = $wpu_lang_dir . $mofile;
	$mofile_global = WP_LANG_DIR . '/' . WPU_PLUGIN_BASENAME . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/wpunit folder.
		load_textdomain( 'wpunit', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) { // Look in local /wp-content/plugins/wpunit/languages/ folder.
		load_textdomain( 'wpunit', $mofile_local );
	} else { // Load the default language files.
		load_plugin_textdomain( 'wpunit', false, $wpu_lang_dir );
	}
}

/**
 * Load Plugin
 *
 * Handles to load plugin after
 * dependent plugin is loaded
 * successfully
 *
 * @package WP Unit
 * @since   1.0.0
 */
function wpu_plugin_loaded() {
	// load first plugin text domain.
	wpu_load_textdomain();
}

// add action to load plugin.
add_action( 'plugins_loaded', 'wpu_plugin_loaded' );

// Global variables.
global $wpu_public;

// Public class handles most of public functionalities of plugin.
require_once WPU_INC_DIR . '/class-wpu-public.php';
$wpu_public = new Wpu_Public();
$wpu_public->add_hooks();
