<?php
/**
 * Custom functions for layout.
 *
 * @package Martfury
 */

/**
 * Get layout base on current page
 *
 * @return string
 */


if ( ! function_exists( 'martfury_get_layout' ) ) :
	function martfury_get_layout() {
		$layout = martfury_get_option( 'blog_layout' );

		if ( is_singular( 'post' ) ) {
			$layout = martfury_get_option( 'single_post_layout' );

			if ( get_post_meta( get_the_ID(), 'custom_layout', true ) ) {
				$layout = get_post_meta( get_the_ID(), 'layout', true );
			}

		} elseif ( martfury_is_vendor_page() ) {
			$layout = 'full-content';
			if ( martfury_is_wc_vendor_page() || martfury_is_dc_vendor_store() ) {
				$layout = martfury_get_option( 'catalog_sidebar_12' );
			}
		} elseif ( martfury_is_catalog() ) {
			$catalog_layout = martfury_get_catalog_layout();
			$layout         = martfury_get_option( 'catalog_sidebar_' . $catalog_layout );
		} elseif ( is_singular( 'product' ) ) {
			$product_layout = martfury_get_product_layout();
			$layout         = 'full-content';

			if ( in_array( $product_layout, array( '2', '5', '6' ) ) ) {
				$layout = martfury_get_option( 'product_page_sidebar' );
			}
		} elseif ( is_page() ) {
			$layout = martfury_get_option( 'page_layout' );
			if ( get_post_meta( get_the_ID(), 'custom_layout', true ) ) {
				$layout = get_post_meta( get_the_ID(), 'layout', true );
			}
		} elseif ( is_search() ) {
			$layout = 'list';
		}

		return apply_filters( 'martfury_site_layout', $layout );
	}

endif;

/**
 * Get Bootstrap column classes for content area
 *
 * @since  1.0
 *
 * @return array Array of classes
 */

if ( ! function_exists( 'martfury_get_content_columns' ) ) :
	function martfury_get_content_columns( $layout = null ) {
		$layout  = $layout ? $layout : martfury_get_layout();
		$classes = array( 'col-md-9', 'col-sm-12', 'col-xs-12' );

		if ( ! in_array( $layout, array( 'sidebar-content', 'content-sidebar', 'small-thumb' ) ) ) {
			$classes = array( 'col-md-12' );
		}

		return $classes;
	}

endif;

/**
 * Echos Bootstrap column classes for content area
 *
 * @since 1.0
 */

if ( ! function_exists( 'martfury_content_columns' ) ) :
	function martfury_content_columns( $layout = null ) {
		echo implode( ' ', martfury_get_content_columns( $layout ) );
	}
endif;
/**
 * Get classes for content area
 *
 * @since  1.0
 *
 * @return string of classes
 */

if ( ! function_exists( 'martfury_class_full_width' ) ) :
	function martfury_class_full_width() {
		if ( is_page_template( 'template-homepage.php' ) || is_page_template( 'template-coming-soon-page.php' ) || is_page_template( 'template-full-width.php' ) ) {
			return 'container-fluid';
		} elseif ( is_page_template( 'template-home-full-width.php' ) ) {
			return 'martfury-container';
		}


		return 'container';
	}

endif;

/**
 * Check homepage
 *
 * @since  1.0
 *
 */

if ( ! function_exists( 'martfury_is_homepage' ) ) :
	function martfury_is_homepage() {
		if ( is_page_template( 'template-homepage.php' ) || is_page_template( 'template-home-full-width.php' ) ) {
			return true;
		}

		return false;
	}

endif;

/**
 * Check header container
 *
 * @since  1.0
 *
 */
if ( ! function_exists( 'martfury_header_container_classes' ) ) :
	function martfury_header_container_classes() {
		$full_width    = intval( martfury_get_option( 'header_full_width' ) );
		$custom_header = get_post_meta( get_the_ID(), 'header_container', true );
		if ( ! empty( $custom_header ) && $custom_header != '2' && is_page() ) {
			$full_width = intval( $custom_header );
		}

		return $full_width ? 'martfury-container' : 'container';
	}
endif;


/**
 * Check footer container
 *
 * @since  1.0
 *
 */
if ( ! function_exists( 'martfury_footer_container_classes' ) ) :
	function martfury_footer_container_classes() {
		$full_width    = intval( martfury_get_option( 'footer_full_width' ) );
		$custom_header = get_post_meta( get_the_ID(), 'footer_container', true );
		if ( ! empty( $custom_header ) && $custom_header != '2' && is_page() ) {
			$full_width = intval( $custom_header );
		}

		return $full_width ? 'martfury-container' : 'container';
	}
endif;

/**
 * Get product layout
 *
 * @return string
 */


if ( ! function_exists( 'martfury_get_product_layout' ) ) :
	function martfury_get_product_layout() {
		$product_layout = martfury_get_option( 'product_page_layout' );

		return apply_filters( 'martfury_get_product_layout', $product_layout );
	}
endif;

/**
 * Check mobile version
 *
 * @since  1.0
 *
 */
if ( ! function_exists( 'martfury_is_mobile' ) ) :
	function martfury_is_mobile() {

		if ( ! class_exists( 'Mobile_Detect' ) ) {
			return false;
		}

		$mobile = false;
		$detect = new Mobile_Detect();
		if ( $detect->isMobile() && ! $detect->isTablet() ) {
			$mobile = true;
		}

		return $mobile;
	}
endif;