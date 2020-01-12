<?php

/**
 * Class for all Vendor template modification
 *
 * @version 1.0
 */
class Martfury_WCVendors {

	/**
	 * Construction function
	 *
	 * @since  1.0
	 * @return Martfury_Vendor
	 */
	function __construct() {
		if( ! class_exists('WC_Vendors') ) {
			return;
		}

		// Define all hook
		add_filter( 'body_class', array( $this, 'wc_body_class' ) );
		if ( get_option('wcvendors_display_label_sold_by_enable') == 'yes' ) {
			if ( class_exists( 'WCV_Vendor_Shop' ) && method_exists( 'WCV_Vendor_Shop', 'template_loop_sold_by' ) ) {
				remove_action( 'woocommerce_after_shop_loop_item', array(
					'WCV_Vendor_Shop',
					'template_loop_sold_by',
				), 9 );
			}
			if ( martfury_get_option( 'catalog_vendor_name' ) != 'hidden' ) {
				add_action( 'woocommerce_after_shop_loop_item_title', array(
					'WCV_Vendor_Shop',
					'template_loop_sold_by',
				), 120 );
				add_action( 'woocommerce_after_shop_loop_item_title', array(
					'WCV_Vendor_Shop',
					'template_loop_sold_by',
				), 7 );
				add_action( 'martfury_woo_after_shop_loop_item_title', array(
					'WCV_Vendor_Shop',
					'template_loop_sold_by',
				), 7 );
			}

			add_action( 'woocommerce_shop_loop_item_title', array( $this, 'template_loop_show_sold_by' ), 6 );

			if ( class_exists( 'WCV_Vendor_Cart' ) && method_exists( 'WCV_Vendor_Cart', 'sold_by_meta' ) ) {
				remove_action( 'woocommerce_product_meta_start', array( 'WCV_Vendor_Cart', 'sold_by_meta' ), 10, 2 );
			}
		}

		add_action( 'woocommerce_before_main_content', array( $this, 'vendor_header_tabs' ), 20 );

		// Change HTML for sold by
		add_filter( 'wcvendors_vendor_registration_checkbox', array( $this, 'vendor_registration_checkbox' ) );
		add_action( 'wcvendors_before_dashboard', array( $this, 'vendors_before_dashboard' ) );
		add_action( 'wcvendors_after_dashboard', array( $this, 'vendors_after_dashboard' ) );
	}


	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since 1.0
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array
	 */
	function wc_body_class( $classes ) {
		if ( class_exists( 'WC_Vendors' ) ) {
			$orders_page_id     = get_option( 'wcvendors_product_orders_page_id' );
			$shop_settings_page = get_option( 'wcvendors_shop_settings_page_id' );
			$vendor_page_id = get_option('wcvendors_vendor_dashboard_page_id');
			if ( is_page( $orders_page_id ) ) {
				$classes[] = 'mf-vendor-page';
			} elseif ( $shop_settings_page == get_the_ID() ) {
				$classes[] = 'mf-vendor-page mf-vendor-shop-page';
			}

			if ( is_page( $vendor_page_id ) ) {
				$classes[] = 'mf-vendor-dashboard-page';
			}
		}

		return $classes;
	}


	function template_loop_show_sold_by() {
		if ( martfury_get_option( 'catalog_vendor_name' ) != 'display' ) {
			return;
		}
		global $product;
		if ( class_exists( 'WCV_Vendor_Shop' ) && method_exists( 'WCV_Vendor_Shop', 'template_loop_sold_by' ) ) {
			echo '<div class="mf-vendor-name">';
			WCV_Vendor_Shop::template_loop_sold_by( $product->get_id() );
			echo '</div>';
		}
	}


	/**
	 * Change HTML for vendor registration checkbox
	 */
	function vendor_registration_checkbox( $value ) {
		return '<span>' . $value . '</span>';
	}

	/**
	 * vendors_before_dashboard
	 */
	function vendors_before_dashboard() {
		echo '<div class="mf-vendors-dashboard">';
	}

	/**
	 * vendors_after_dashboard
	 */
	function vendors_after_dashboard() {
		echo '</div>';
	}

	/**
	 * Vendor header tabs
	 */
	function vendor_header_tabs() {
		if ( ! martfury_is_wc_vendor_page() ) {
			return;
		}


		$ratings_class = '';
		if ( get_query_var( 'ratings' ) ) {
			$ratings_class = 'active';
		}
		$about_class = '';
		if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
			$about_class = 'active';
		}

		$search = '';
		if ( isset( $_GET['s'] ) && $_GET['s'] ) {
			$search = $_GET['s'];
		}

		$product_class = $about_class || $ratings_class ? '' : 'active';

		$vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
		$vendor_id   = WCV_Vendors::get_vendor_id( $vendor_shop );
		$url         = WCV_Vendors::get_vendor_shop_page( $vendor_id );
		global $wcvendors_pro;
		?>
		<div class="mf-vendor-header-tabs">
			<ul>
				<li>
					<a class="<?php echo esc_attr( $product_class ); ?>" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Products', 'martfury' ); ?></a>
				</li>
				<?php if ( $wcvendors_pro ): ?>
					<li>
						<a class="<?php echo esc_attr( $ratings_class ); ?>" href="<?php echo esc_url( $url ) . 'ratings'; ?>"><?php esc_html_e( 'Reviews', 'martfury' ); ?></a>
					</li>
				<?php endif; ?>
			</ul>
			<div class="vendor-search">
				<form action="<?php echo esc_url( $url ); ?>">
					<input type="text" name="s" value="<?php echo esc_attr( $search ); ?>" class="search-input" placeholder="<?php esc_html_e( 'Search this shop', 'martfury' ); ?>">
					<input type="hidden" name="wcv_vendor_id" value="<?php echo esc_attr( $vendor_id ); ?>">
					<input class="btn-button" type="submit">
				</form>
			</div>
		</div>
		<?php
	}

}

