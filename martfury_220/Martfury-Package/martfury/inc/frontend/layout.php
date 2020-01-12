<?php
/**
 * Hooks for frontend display
 *
 * @package Martfury
 */


/**
 * Adds custom classes to the array of body classes.
 *
 * @since 1.0
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function martfury_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	if ( ! is_page_template( 'template-coming-soon-page.php' ) ) {
		if ( $header_layout = martfury_get_option( 'header_layout' ) ) {
			$header_layout = $header_layout == 7 ? 3 : $header_layout;
			$classes[]     = 'header-layout-' . $header_layout;
		}
	}

	if ( is_singular( 'post' ) ) {
		$classes[] = 'single-post-layout-' . martfury_single_post_style();
	}

	if ( martfury_is_blog() ) {
		$classes[] = 'mf-blog-page';
		$classes[] = 'blog-layout-' . martfury_get_layout();

		if ( intval( martfury_get_option( 'show_blog_cats' ) ) ) {
			$classes[] = 'has-blog-cats';
		}

	} elseif ( martfury_is_catalog() || martfury_is_dc_vendor_store() ) {
		$classes[]      = 'mf-catalog-page';
		$classes[]      = martfury_get_layout();
		$catalog_layout = martfury_get_catalog_layout();
		$classes[]      = 'mf-catalog-layout-' . $catalog_layout;
		$shop_view      = isset( $_COOKIE['shop_view'] ) ? $_COOKIE['shop_view'] : martfury_get_option( 'catalog_view_' . $catalog_layout );
		$classes[]      = 'shop-view-' . $shop_view;

		if ( intval( martfury_get_option( 'catalog_ajax_filter' ) ) ) {
			$classes[] = 'catalog-ajax-filter';
		}

		if ( intval( apply_filters( 'martfury_catalog_filter_mobile', martfury_get_option( 'catalog_filter_mobile_10' ) ) ) ) {
			$classes[] = 'catalog-filter-mobile';
		}

		$classes[] = 'navigation-type-' . martfury_get_option( 'catalog_nav_type' );

		if ( martfury_get_catalog_full_width() ) {
			$classes[] = 'catalog-full-width';
		}

	} elseif ( martfury_is_vendor_page() ) {
		$classes[] = 'navigation-type-' . martfury_get_option( 'catalog_nav_type' );
	} elseif ( is_search() ) {
		$classes[] = 'blog-layout-list';
	} else {
		$classes[] = martfury_get_layout();
	}

	if ( is_singular( 'product' ) ) {
		$product_layout = martfury_get_product_layout();
		$classes[]      = 'single-product-layout-' . $product_layout;

		$sticky_product = apply_filters( 'martfury_sticky_product_info', martfury_get_option( 'sticky_product_info' ) );
		if ( intval( $sticky_product ) ) {
			$classes[] = 'sticky-header-info';
		}
	}

	if ( intval( martfury_get_option( 'preloader' ) ) ) {
		$classes[] = 'mf-preloader';
	}

	if ( $skin = martfury_get_option( 'color_skin' ) ) {
		$classes[] = 'mf-' . $skin . '-skin';
	}

	$sticky_header = apply_filters( 'martfury_get_sticky_header', martfury_get_option( 'sticky_header' ) );
	if ( intval( $sticky_header ) ) {
		if ( is_singular( 'product' ) ) {
			if ( ! intval( martfury_get_option( 'sticky_product_info' ) ) ) {
				$classes[] = 'sticky-header';
			}
		} else {
			$classes[] = 'sticky-header';
		}

	}
	$extras = martfury_menu_extras();
	if ( empty( $extras ) || ! in_array( 'department', $extras ) ) {
		$classes[] = 'header-no-department';
	}

	if ( martfury_get_option( 'mini_cart_button' ) == '2' ) {
		$classes[] = 'mini-cart-button-lines';
	}

	if ( function_exists( 'dokan_get_option' ) ) {
		$page_id = dokan_get_option( 'dashboard', 'dokan_pages' );

		if ( ! empty( $page_id ) && ( is_page( $page_id ) || ( get_query_var( 'edit' ) && is_singular( 'product' ) ) ) ) {
			$classes[] = 'dokan-dashboard-layout-' . martfury_get_option( 'dokan_dashboard_layout' );
		}

	}

	if ( martfury_get_option( 'submenu_mobile' ) != 'menu' ) {
		$classes[] = 'submenus-mobile-' . martfury_get_option( 'submenu_mobile' );
	}

	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		$classes[] = 'account-page-' . martfury_get_option( 'login_register_layout' );
	}

	if ( martfury_is_wc_vendor_page() ) {
		$classes[] = 'wc-vendor-store';
	}

	if ( martfury_is_dc_vendor_store() ) {
		$classes[] = 'dc-vendor-store';
	}

	return $classes;
}

add_filter( 'body_class', 'martfury_body_classes' );

/**
 * Print the open tags of site content container
 */

if ( ! function_exists( 'martfury_open_site_content_container' ) ) :
	function martfury_open_site_content_container() {

		printf( '<div class="%s"><div class="row">', esc_attr( apply_filters( 'martfury_site_content_container_class', martfury_class_full_width() ) ) );
	}
endif;

add_action( 'martfury_after_site_content_open', 'martfury_open_site_content_container' );

/**
 * Print the close tags of site content container
 */

if ( ! function_exists( 'martfury_close_site_content_container' ) ) :
	function martfury_close_site_content_container() {
		print( '</div></div>' );
	}

endif;

add_action( 'martfury_before_site_content_close', 'martfury_close_site_content_container' );
