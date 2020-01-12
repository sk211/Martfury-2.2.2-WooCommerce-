<?php

/**
 * Class for all Vendor template modification
 *
 * @version 1.0
 */
class Martfury_Dokan {

	/**
	 * Construction function
	 *
	 * @since  1.0
	 * @return Martfury_Vendor
	 */
	function __construct() {
		if( ! class_exists('WeDevs_Dokan') ) {
			return;
		}

		// Define all hook
		add_filter( 'dokan_settings_fields', array( $this, 'dokan_settings_fields' ) );
		add_filter( 'pre_get_posts', array( $this, 'store_query_filter' ) );

		add_action( 'woocommerce_shop_loop_item_title', array( $this, 'template_loop_show_sold_by' ), 6 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'template_loop_sold_by' ), 7 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'template_loop_sold_by' ), 120 );
		add_action( 'martfury_woo_after_shop_loop_item_title', array( $this, 'template_loop_sold_by' ), 20 );


		add_filter( 'dokan_dashboard_nav_common_link', array( $this, 'dashboard_nav_common_link' ) );

		add_filter( 'body_class', array( $this, 'body_classes' ) );

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
	function body_classes( $classes ) {
		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( martfury_is_vendor_page() ) {
			$shop_view = isset( $_COOKIE['shop_view'] ) ? $_COOKIE['shop_view'] : martfury_get_option( 'catalog_view_12' );
			$classes[] = 'shop-view-' . $shop_view;
		}

		return $classes;
	}

	function template_loop_show_sold_by() {
		if ( martfury_get_option( 'catalog_vendor_name' ) != 'display' ) {
			return;
		}

		echo '<div class="mf-vendor-name">';
		$this->template_loop_sold_by();
		echo '</div>';
	}


	/**
	 * Add sold by
	 */
	function template_loop_sold_by() {
		if ( martfury_get_option( 'catalog_vendor_name' ) == 'hidden' ) {
			return;
		}

		get_template_part( 'template-parts/vendor/loop', 'sold-by' );
	}

	/**
	 * dashboard_nav_common_link
	 *
	 * @param $common_links
	 */
	function dashboard_nav_common_link( $common_links ) {
		if ( ! function_exists( 'dokan_get_store_url' ) && ! function_exists( 'dokan_get_navigation_url' ) ) {
			return $common_links;
		}

		if ( martfury_get_option( 'dokan_dashboard_layout' ) == '2' ) {
			return $common_links;
		}

		$common_links = sprintf(
			'<li class="dokan-common-links dokan-clearfix">' .
			'<a href="%s" ><i class="fa fa-external-link"></i> <span>%s</span></a >' .
			'<a href="%s" ><i class="fa fa-user"></i><span>%s</span></a >' .
			'<a href="%s" ><i class="fa fa-power-off"></i><span>%s</span></a >' .
			'</li>',
			esc_url( dokan_get_store_url( get_current_user_id() ) ),
			esc_html__( 'Visit Store', 'martfury' ),
			esc_url( dokan_get_navigation_url( 'edit-account' ) ),
			esc_html__( 'Edit Account', 'martfury' ),
			esc_url( wp_logout_url( home_url() ) ),
			esc_html__( 'Log out', 'martfury' )

		);


		return $common_links;
	}

	/**
	 * Dokan Settings Fields
	 */
	function dokan_settings_fields( $settings_fields ) {
		$settings_fields['dokan_appearance']['store_header_template']['options']['mf_custom'] = get_template_directory_uri() . '/images/vendor.jpg';

		return $settings_fields;
	}

	/**
	 * Store query filter
	 *
	 * Handles the product filtering by category in store page
	 *
	 * @param object $query
	 *
	 * @return void
	 */
	function store_query_filter( $query ) {
		global $wp_query;

		if ( ! is_admin() && $query->is_main_query() && martfury_is_vendor_page() ) {
			$post_per_page = isset( $store_info['store_ppp'] ) && ! empty( $store_info['store_ppp'] ) ? $store_info['store_ppp'] : intval( martfury_get_option( 'products_per_page_12' ) );
			set_query_var( 'posts_per_page', $post_per_page );
		}
	}

}