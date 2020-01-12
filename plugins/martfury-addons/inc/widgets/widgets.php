<?php
/**
 * Load and register widgets
 *
 * @package Martfury
 */

/**
 * Register widgets
 *
 * @since  1.0
 *
 * @return void
 */

if( ! function_exists('martfury_register_widgets') ) {
	function martfury_register_widgets() {
		require_once MARTFURY_ADDONS_DIR . '/inc/widgets/social-media-links.php';
		require_once MARTFURY_ADDONS_DIR . '/inc/widgets/account.php';

		register_widget( 'Martfury_Social_Links_Widget' );
		register_widget( 'Martfury_Account' );

		if ( class_exists( 'WC_Widget' ) ) {
			require_once MARTFURY_ADDONS_DIR . '/inc/widgets/product-categories.php';
			require_once MARTFURY_ADDONS_DIR . '/inc/widgets/widget-layered-nav.php';
			require_once MARTFURY_ADDONS_DIR . '/inc/widgets/widget-brands-nav.php';
			require_once MARTFURY_ADDONS_DIR . '/inc/widgets/widget-rating-filter.php';
			require_once MARTFURY_ADDONS_DIR . '/inc/widgets/widget-layered-nav-filters.php';
			require_once MARTFURY_ADDONS_DIR . '/inc/widgets/widget-products.php';
			require_once MARTFURY_ADDONS_DIR . '/inc/widgets/currencies.php';
			require_once MARTFURY_ADDONS_DIR . '/inc/widgets/product-tag.php';


			register_widget( 'Martfury_Widget_Product_Categories' );
			register_widget( 'Martfury_Widget_Layered_Nav' );
			register_widget( 'Martfury_Widget_Brands_Nav' );
			register_widget( 'Martfury_Widget_Rating_Filter' );
			register_widget( 'Martfury_Widget_Layered_Nav_Filters' );
			register_widget( 'Martfury_Widget_Products' );
			register_widget( 'Martfury_Currencies_Widget' );
			register_widget( 'Martfury_Widget_Product_Tag_Cloud' );

			if ( class_exists( 'WCV_Vendors' ) || class_exists( 'WCMp' ) ) {
				require_once MARTFURY_ADDONS_DIR . '/inc/widgets/wc-vendor-info.php';
				register_widget( 'Martfury_Widget_WC_Vendor_Info' );
			}

		}
	}

	add_action( 'widgets_init', 'martfury_register_widgets' );
}