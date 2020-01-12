<?php
/**
 * Plugin Name: WooCommerce Variation Swatches Pro
 * Plugin URI: http://drfuri.com/woocommerce-variation-swatches
 * Description: An extension of WooCommerce to make variable products be more beauty and friendly to users.
 * Version: 1.0.5
 * Author: Drfuri
 * Author URI: http://drfuri.com/
 * Requires at least: 4.5
 * Tested up to: 4.9
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wcvs
 * Domain Path: /languages/
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'TAWC_VS_PRO', true );

// Define TAWC_DEALS_PLUGIN_FILE
if ( ! defined( 'TAWC_VS_PLUGIN_FILE' ) ) {
	define( 'TAWC_VS_PLUGIN_FILE', __FILE__ );
}

if ( ! function_exists( 'ta_wc_variation_swatches_wc_notice' ) ) {
	/**
	 * Display notice in case of WooCommerce plugin is not activated
	 */
	function ta_wc_variation_swatches_wc_notice() {
		?>

		<div class="error">
			<p><?php esc_html_e( 'WooCommerce Variation Swatches is enabled but not effective. It requires WooCommerce in order to work.', 'wcvs' ); ?></p>
		</div>

		<?php
	}
}

if ( ! function_exists( 'ta_wc_variation_swatches_pro_constructor' ) ) {
	/**
	 * Construct plugin when plugins loaded in order to make sure WooCommerce API is fully loaded
	 * Check if WooCommerce is not activated then show an admin notice
	 * or create the main instance of plugin
	 */
	function ta_wc_variation_swatches_pro_constructor() {
		if ( ! function_exists( 'WC' ) ) {
			add_action( 'admin_notices', 'ta_wc_variation_swatches_wc_notice' );
		} else {
			require_once plugin_dir_path( __FILE__ ) . '/includes/class-variation-swatches.php';
			TA_WCVS();
		}
	}
}

if ( ! function_exists( 'ta_wc_variation_swatches_deactivate' ) ) {
	/**
	 * Deactivation hook.
	 * Backup all unsupported types of attributes then reset them to "select".
	 *
	 * @param bool $network_deactivating Whether the plugin is deactivated for all sites in the network
	 *                                   or just the current site. Multisite only. Default is false.
	 */
	function ta_wc_variation_swatches_deactivate( $network_deactivating ) {
		global $wpdb;

		$blog_ids         = array( 1 );
		$original_blog_id = 1;
		$network          = false;

		if ( is_multisite() && $network_deactivating ) {
			$blog_ids         = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
			$original_blog_id = get_current_blog_id();
			$network          = true;
		}

		foreach ( $blog_ids as $blog_id ) {
			if ( $network ) {
				switch_to_blog( $blog_id );
			}

			// Backup attribute types
			$attributes         = wc_get_attribute_taxonomies();
			$default_types      = array( 'text', 'select' );
			$ta_wcvs_attributes = array();

			if ( ! empty( $attributes ) ) {
				foreach ( $attributes as $attribute ) {
					if ( ! in_array( $attribute->attribute_type, $default_types ) ) {
						$ta_wcvs_attributes[ $attribute->attribute_id ] = $attribute;
					}
				}
			}

			if ( ! empty( $ta_wcvs_attributes ) ) {
				set_transient( 'tawcvs_attribute_taxonomies', $ta_wcvs_attributes );
				delete_transient( 'wc_attribute_taxonomies' );
				update_option( 'tawcvs_backup_attributes_time', time() );
			}

			// Reset attributes
			if ( ! empty( $ta_wcvs_attributes ) ) {
				foreach ( $ta_wcvs_attributes as $id => $attribute ) {
					$wpdb->update(
						$wpdb->prefix . 'woocommerce_attribute_taxonomies',
						array( 'attribute_type' => 'select' ),
						array( 'attribute_id' => $id ),
						array( '%s' ),
						array( '%d' )
					);
				}
			}

			// Delete the ignore restore
			delete_option( 'tawcvs_ignore_restore_attributes' );
		}

		if ( $network ) {
			switch_to_blog( $original_blog_id );
		}
	}
}

add_action( 'plugins_loaded', 'ta_wc_variation_swatches_pro_constructor' );
register_deactivation_hook( __FILE__, 'ta_wc_variation_swatches_deactivate' );
