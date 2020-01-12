<?php
/**
 * Plugin Name: WooCommerce Deals
 * Plugin URI: http://drfuri.com/woocommerce-deals
 * Description: A WooCommerce's extension. Make sale products become deals.
 * Version: 1.0.9
 * Author: Drfuri
 * Author URI: http://drfuri.com/
 * Requires at least: 4.5
 * Tested up to: 4.9.1
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: tawc-deals
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define TAWC_DEALS_PLUGIN_FILE
if ( ! defined( 'TAWC_DEALS_PLUGIN_FILE' ) ) {
	define( 'TAWC_DEALS_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'TAWC_DEALS_VERSION' ) ) {
	define( 'TAWC_DEALS_VERSION', '1.0.0' );
}

/**
 * Display a notice if WooCommerce plugin is not activated
 */
function tawc_deals_wc_notice() {
	?>

	<div class="error">
		<p><?php esc_html_e( 'WooCommerce Deals is enabled but not effective. It requires WooCommerce in order to work.', 'tawc-deals' ); ?></p>
	</div>

	<?php
}

/**
 * Construct plugin when plugins loaded in order to make sure WooCommerce API is fully loaded
 * Check if WooCommerce is not activated then show an admin notice
 * or create the main instance of plugin
 */
function tawc_deals_constructor() {
	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'tawc_deals_wc_notice' );
	} else {
		require_once plugin_dir_path( __FILE__ ) . '/includes/class-deals.php';
		TAWC_Deals::instance();
	}
}

add_action( 'plugins_loaded', 'tawc_deals_constructor' );
