<?php
/**
 * Plugin Name: Martfury Addons
 * Plugin URI: http://drfuri.com/martfury
 * Description: Extra elements for WPBakery Page Builder and Elementor. It was built for Martfury theme.
 * Version: 2.0.3
 * Author: DrFuri
 * Author URI: http://drfuri.com/
 * License: GPL2+
 * Text Domain: martfury
 * Domain Path: /lang/
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! defined( 'MARTFURY_ADDONS_DIR' ) ) {
	define( 'MARTFURY_ADDONS_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'MARTFURY_ADDONS_URL' ) ) {
	define( 'MARTFURY_ADDONS_URL', plugin_dir_url( __FILE__ ) );
}

require_once MARTFURY_ADDONS_DIR . '/inc/taxonomies.php';
require_once MARTFURY_ADDONS_DIR . '/inc/visual-composer.php';
require_once MARTFURY_ADDONS_DIR . '/inc/shortcodes.php';
require_once MARTFURY_ADDONS_DIR . '/inc/socials.php';
require_once MARTFURY_ADDONS_DIR . '/inc/widgets/widgets.php';

if ( is_admin() ) {
	require_once MARTFURY_ADDONS_DIR . '/inc/importer.php';
}

/**
 * Init
 */
function martfury_vc_addons_init() {
	load_plugin_textdomain( 'martfury', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );

	new Martfury_Taxonomies;
	new Martfury_VC;
	new Martfury_Shortcodes;

}

add_action( 'after_setup_theme', 'martfury_vc_addons_init', 30 );

function martfury_unregister_sidebar() {
	unregister_widget( 'WC_Widget_Layered_Nav' );
	unregister_widget( 'WC_Widget_Layered_Nav_Filters' );
	unregister_widget( 'WC_Widget_Rating_Filter' );
}

add_action( 'widgets_init', 'martfury_unregister_sidebar' );

/**
 * Undocumented function
 */
function martfury_init_elementor() {
	// Check if Elementor installed and activated
	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}

	// Check for required Elementor version
	if ( ! version_compare( ELEMENTOR_VERSION, '2.0.0', '>=' ) ) {
		return;
	}

	// Check for required PHP version
	if ( version_compare( PHP_VERSION, '5.4', '<' ) ) {
		return;
	}

	// Once we get here, We have passed all validation checks so we can safely include our plugin
	include_once( MARTFURY_ADDONS_DIR . 'inc/elementor.php' );
}

add_action( 'plugins_loaded', 'martfury_init_elementor' );

