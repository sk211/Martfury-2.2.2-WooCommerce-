<?php

/**
 * Class for all Vendor template modification
 *
 * @version 1.0
 */
class Martfury_WCFMVendors {

	/**
	 * Construction function
	 *
	 * @since  1.0
	 * @return Martfury_Vendor
	 */
	function __construct() {
		// Check if Woocomerce plugin is actived
		if ( ! class_exists( 'WCFMmp' ) ) {
			return;
		}

		add_filter( 'wcfmmp_is_allow_archive_product_sold_by', '__return_false' );

		add_action( 'woocommerce_shop_loop_item_title', array( $this, 'catalog_display_vendor_sold_by' ), 6 );

		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'catalog_vendor_sold_by' ), 120 );
		add_action( 'martfury_woo_after_shop_loop_item_title', array( $this, 'catalog_vendor_sold_by' ), 20 );

		if ( martfury_get_option( 'wcfm_single_sold_by_template' ) == 'theme' ) {
			add_filter( 'wcfmmp_is_allow_single_product_sold_by', '__return_false' );

			add_action( 'martfury_single_product_header', array(
				$this,
				'get_vendor_sold_by',
			) );
		}
	}

	function catalog_display_vendor_sold_by() {
		if ( martfury_get_option( 'catalog_vendor_name' ) != 'display' ) {
			return;
		}

		echo '<div class="mf-vendor-name">';
		$this->get_vendor_sold_by();
		echo '</div>';
	}

	function catalog_vendor_sold_by() {
		if ( martfury_get_option( 'catalog_vendor_name' ) == 'hidden' ) {
			return;
		}

		echo '<div class="mf-vendor-name">';
		$this->get_vendor_sold_by();
		echo '</div>';
	}


	function get_vendor_sold_by() {

		if ( ! class_exists( 'WCFM' ) ) {
			return;
		}

		global $WCFM, $post;

		$vendor_id = $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $post->ID );

		if ( ! $vendor_id ) {
			return;
		}

		$sold_by_text = apply_filters( 'wcfmmp_sold_by_label', esc_html__( 'Sold By:', 'martfury' ) );
		$store_name   = $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint( $vendor_id ) );

		echo '<div class="sold-by-meta">';
		echo '<span class="sold-by-label">' . $sold_by_text . ' ' . '</span>';
		echo wp_kses_post( $store_name );
		echo '</div>';
	}

}

