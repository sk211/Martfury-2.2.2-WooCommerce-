<?php
/**
 * Vendors Compatibility File
 *
 * @package Martfury
 */

/**
 * Vendors setup function.
 *
 * @return void
 */
function martfury_vendors_setup() {
	global $martfury_dokan;
	$martfury_dokan = new Martfury_Dokan;

	global $martfury_wcvendors;
	$martfury_wcvendors = new Martfury_WCVendors;

	global $martfury_dcvendors;
	$martfury_dcvendors = new Martfury_DCVendors;

	global $martfury_wcfmvendors;
	$martfury_wcfmvendors = new Martfury_WCFMVendors;
}

add_action( 'after_setup_theme', 'martfury_vendors_setup' );

require get_theme_file_path( '/inc/vendors/dokan.php' );
require get_theme_file_path( '/inc/vendors/wc_vendors.php' );
require get_theme_file_path( '/inc/vendors/dc_vendors.php' );
require get_theme_file_path( '/inc/vendors/wcfm_vendors.php' );