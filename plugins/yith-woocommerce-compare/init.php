<?php
/**
 * Plugin Name: YITH WooCommerce Compare
 * Plugin URI: https://yithemes.com/themes/plugins/yith-woocommerce-compare/
 * Description: The <code><strong>YITH WooCommerce Compare</strong></code> plugin allow you to compare in a simple and efficient way products on sale in your shop and analyze their main features in a single table. <a href="https://yithemes.com/" target="_blank">Get more plugins for your e-commerce shop on <strong>YITH</strong></a>.
 * Version: 2.3.18
 * Author: YITH
 * Author URI: https://yithemes.com/
 * Text Domain: yith-woocommerce-compare
 * Domain Path: /languages/
 * WC requires at least: 3.7
 * WC tested up to: 3.9
 *
 * @author YITH
 * @package YITH WooCommerce Compare
 * @version 2.3.18
 */
/*  Copyright 2015  Your Inspiration Themes  (email : plugins@yithemes.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( !defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

function yith_woocompare_install_woocommerce_admin_notice() {
	?>
	<div class="error">
		<p><?php _e( 'YITH WooCommerce Compare is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-compare' ); ?></p>
	</div>
<?php
}

function yith_woocompare_install_free_admin_notice() {
	?>
	<div class="error">
		<p><?php _e( 'You can\'t activate the free version of YITH WooCommerce Compare while you are using the premium one.', 'yith-woocommerce-compare' ); ?></p>
	</div>
<?php
}

if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

if ( ! defined( 'YITH_WOOCOMPARE_VERSION' ) ){
	define( 'YITH_WOOCOMPARE_VERSION', '2.3.18' );
}
if ( ! defined( 'YITH_WOOCOMPARE_FREE_INIT' ) ) {
	define( 'YITH_WOOCOMPARE_FREE_INIT', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'YITH_WOOCOMPARE_INIT' ) ) {
	define( 'YITH_WOOCOMPARE_INIT', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'YITH_WOOCOMPARE' ) ) {
	define( 'YITH_WOOCOMPARE', true );
}
if ( ! defined( 'YITH_WOOCOMPARE_FILE' ) ) {
	define( 'YITH_WOOCOMPARE_FILE', __FILE__ );
}
if ( ! defined( 'YITH_WOOCOMPARE_URL' ) ) {
	define( 'YITH_WOOCOMPARE_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'YITH_WOOCOMPARE_DIR' ) ) {
	define( 'YITH_WOOCOMPARE_DIR', plugin_dir_path( __FILE__ )  );
}
if ( ! defined( 'YITH_WOOCOMPARE_TEMPLATE_PATH' ) ) {
	define( 'YITH_WOOCOMPARE_TEMPLATE_PATH', YITH_WOOCOMPARE_DIR . 'templates' );
}
if ( ! defined( 'YITH_WOOCOMPARE_ASSETS_URL' ) ) {
	define( 'YITH_WOOCOMPARE_ASSETS_URL', YITH_WOOCOMPARE_URL . 'assets' );
}
if ( ! defined( 'YITH_WOOCOMPARE_SLUG' ) ) {
    define( 'YITH_WOOCOMPARE_SLUG', 'yith-woocommerce-compare' );
}
/* Plugin Framework Version Check */
if( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_WOOCOMPARE_DIR . 'plugin-fw/init.php' ) ) {
	require_once( YITH_WOOCOMPARE_DIR . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( YITH_WOOCOMPARE_DIR  );

function yith_woocompare_constructor() {

	global $woocommerce;

	if ( ! isset( $woocommerce ) || ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'yith_woocompare_install_woocommerce_admin_notice' );
		return;
	}
	elseif ( defined( 'YITH_WOOCOMPARE_PREMIUM' ) ) {
		add_action( 'admin_notices', 'yith_woocompare_install_free_admin_notice' );
		deactivate_plugins( plugin_basename( __FILE__ ) );
		return;
	}

    load_plugin_textdomain( 'yith-woocommerce-compare', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );

    // Load required classes and functions
    require_once('includes/class.yith-woocompare-helper.php');
    require_once('widgets/class.yith-woocompare-widget.php');
    require_once('includes/class.yith-woocompare.php');

    // Let's start the game!
    global $yith_woocompare;
    $yith_woocompare = new YITH_Woocompare();
}
add_action( 'plugins_loaded', 'yith_woocompare_constructor', 11 );
