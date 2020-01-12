<?php
/**
 * DrFuri Core functions and definitions
 *
 * @package Martfury
 */


/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since  1.0
 *
 * @return void
 */
function martfury_setup() {
	// Sets the content width in pixels, based on the theme's design and stylesheet.
	$GLOBALS['content_width'] = apply_filters( 'martfury_content_width', 840 );

	// Make theme available for translation.
	load_theme_textdomain( 'martfury', get_template_directory() . '/lang' );

	// Theme supports
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'post-formats', array( 'audio', 'gallery', 'video', 'quote', 'link' ) );
	add_theme_support(
		'html5', array(
			'comment-list',
			'search-form',
			'comment-form',
			'gallery',
		)
	);

	if ( martfury_fonts_url() ) {
		add_editor_style( array( 'css/editor-style.css', martfury_fonts_url() ) );
	} else {
		add_editor_style( 'css/editor-style.css' );
	}

	// Load regular editor styles into the new block-based editor.
	add_theme_support( 'editor-styles' );

	// Load default block styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	add_theme_support( 'align-wide' );

	add_theme_support( 'align-full' );

	// Register theme nav menu
	$nav_menu = array(
		'primary'         => esc_html__( 'Primary Menu', 'martfury' ),
		'shop_department' => esc_html__( 'Shop By Department Menu', 'martfury' ),
		'mobile'          => esc_html__( 'Mobile Header Menu', 'martfury' ),
		'category_mobile' => esc_html__( 'Mobile Category Menu', 'martfury' ),
		'user_logged'     => esc_html__( 'User Logged Menu', 'martfury' ),
	);
	if ( martfury_has_vendor() ) {
		$nav_menu['vendor_logged'] = esc_html__( 'Vendor Logged Menu', 'martfury' );
	}
	register_nav_menus( $nav_menu );

	add_image_size( 'martfury-blog-grid', 380, 300, true );
	add_image_size( 'martfury-blog-list', 790, 510, true );
	add_image_size( 'martfury-blog-masonry', 370, 588, false );

	global $martfury_woocommerce;
	$martfury_woocommerce = new Martfury_WooCommerce;

	global $martfury_mobile;
	$martfury_mobile = new Martfury_Mobile;

	global $martfury_wcfm;
	$martfury_wcfm = new Martfury_WCFM;

}

add_action( 'after_setup_theme', 'martfury_setup', 100 );

/**
 * Register widgetized area and update sidebar with default widgets.
 *
 * @since 1.0
 *
 * @return void
 */
function martfury_register_sidebar() {
	// Register primary sidebar
	$sidebars = array(
		'blog-sidebar'    => esc_html__( 'Blog Sidebar', 'martfury' ),
		'topbar-left'     => esc_html__( 'Topbar Left', 'martfury' ),
		'topbar-right'    => esc_html__( 'Topbar Right', 'martfury' ),
		'topbar-mobile'   => esc_html__( 'Topbar on Mobile', 'martfury' ),
		'header-bar'      => esc_html__( 'Header Bar', 'martfury' ),
		'post-sidebar'    => esc_html__( 'Single Post Sidebar', 'martfury' ),
		'page-sidebar'    => esc_html__( 'Page Sidebar', 'martfury' ),
		'catalog-sidebar' => esc_html__( 'Catalog Sidebar', 'martfury' ),
		'product-sidebar' => esc_html__( 'Single Product Sidebar', 'martfury' ),
		'footer-links'    => esc_html__( 'Footer Links', 'martfury' ),
	);

	if ( class_exists( 'WC_Vendors' ) || class_exists( 'WCMp' ) ) {
		$sidebars['vendor_sidebar'] = esc_html( 'Vendor Sidebar', 'martfury' );
	}

	// Register footer sidebars
	for ( $i = 1; $i <= 6; $i ++ ) {
		$sidebars["footer-sidebar-$i"] = esc_html__( 'Footer', 'martfury' ) . " $i";
	}

	$custom_sidebar = martfury_get_option( 'custom_product_cat_sidebars' );
	if ( $custom_sidebar ) {
		foreach ( $custom_sidebar as $sidebar ) {
			$title                                = $sidebar['title'];
			$sidebars[ sanitize_title( $title ) ] = $title;
		}
	}

	// Register sidebars
	foreach ( $sidebars as $id => $name ) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}

}

add_action( 'widgets_init', 'martfury_register_sidebar' );

/**
 * Load theme
 */

// customizer hooks

require get_template_directory() . '/inc/mobile/theme-options.php';
require get_template_directory() . '/inc/vendors/theme-options.php';
require get_template_directory() . '/inc/backend/customizer.php';

// layout
require get_template_directory() . '/inc/functions/layout.php';

require get_template_directory() . '/inc/functions/entry.php';


// Woocommerce
require get_template_directory() . '/inc/frontend/woocommerce.php';

// Vendor
require get_template_directory() . '/inc/vendors/vendors.php';

// WC Frontend Manager
require get_template_directory() . '/inc/frontend/wcfm.php';

require get_template_directory() . '/inc/frontend/dokan.php';

// Mobile
require get_template_directory() . '/inc/libs/mobile_detect.php';
require get_template_directory() . '/inc/mobile/layout.php';

require get_template_directory() . '/inc/functions/media.php';

if ( is_admin() ) {
	require get_template_directory() . '/inc/libs/class-tgm-plugin-activation.php';
	require get_template_directory() . '/inc/backend/plugins.php';
	require get_template_directory() . '/inc/backend/meta-boxes.php';
	require get_template_directory() . '/inc/backend/product-cat.php';
	require get_template_directory() . '/inc/backend/product-meta-box-data.php';
	require get_template_directory() . '/inc/backend/woocommerce.php';
	require get_template_directory() . '/inc/mega-menu/class-mega-menu.php';
	require get_template_directory() . '/inc/backend/editor.php';

} else {
	// Frontend functions and shortcodes

	require get_template_directory() . '/inc/functions/nav.php';
	require get_template_directory() . '/inc/functions/header.php';
	require get_template_directory() . '/inc/functions/breadcrumbs.php';
	require get_template_directory() . '/inc/mega-menu/class-mega-menu-walker.php';
	require get_template_directory() . '/inc/mega-menu/class-mobile-walker.php';
	require get_template_directory() . '/inc/functions/comments.php';
	require get_template_directory() . '/inc/functions/footer.php';

	// Frontend hooks
	require get_template_directory() . '/inc/frontend/layout.php';
	require get_template_directory() . '/inc/frontend/header.php';
	require get_template_directory() . '/inc/frontend/nav.php';
	require get_template_directory() . '/inc/frontend/entry.php';
	require get_template_directory() . '/inc/frontend/footer.php';
}