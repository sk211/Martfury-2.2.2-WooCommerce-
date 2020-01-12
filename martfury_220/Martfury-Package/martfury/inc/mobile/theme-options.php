<?php
/**
 * Theme options of Mobile.
 *
 * @package Martfury
 */

/**
 * Adds theme options panels of Mobile.
 *
 * @param array $sections Theme options sections.
 *
 * @return array
 */
function martfury_mobile_customize_panels( $panels ) {
	$panels = array_merge(
		$panels, array(
			'mobile' => array(
				'title'    => esc_html__( 'Mobile', 'martfury' ),
				'priority' => 90,
			),
		)
	);

	return $panels;
}

add_filter( 'martfury_customize_panels', 'martfury_mobile_customize_panels' );

/**
 * Adds theme options sections of Mobile.
 *
 * @param array $sections Theme options sections.
 *
 * @return array
 */
function martfury_mobile_customize_sections( $sections ) {
	$sections = array_merge(
		$sections, array(
			'general_mobile'         => array(
				'title'       => esc_html__( 'General', 'martfury' ),
				'description' => '',
				'priority'    => 20,
				'capability'  => 'edit_theme_options',
				'panel'       => 'mobile',
			),
			'homepage_mobile'        => array(
				'title'       => esc_html__( 'Homepage Settings', 'martfury' ),
				'description' => '',
				'priority'    => 20,
				'capability'  => 'edit_theme_options',
				'panel'       => 'mobile',
			),
			'inner_page_mobile'      => array(
				'title'       => esc_html__( 'Inner Pages Settings', 'martfury' ),
				'description' => '',
				'priority'    => 20,
				'capability'  => 'edit_theme_options',
				'panel'       => 'mobile',
			),
			'logo_mobile'            => array(
				'title'       => esc_html__( 'Logo', 'martfury' ),
				'description' => '',
				'priority'    => 20,
				'capability'  => 'edit_theme_options',
				'panel'       => 'mobile',
			),
			'header_mobile'          => array(
				'title'       => esc_html__( 'Header', 'martfury' ),
				'description' => '',
				'priority'    => 20,
				'capability'  => 'edit_theme_options',
				'panel'       => 'mobile',
			),
			'navigation_mobile'      => array(
				'title'       => esc_html__( 'Navigation', 'martfury' ),
				'description' => '',
				'priority'    => 20,
				'capability'  => 'edit_theme_options',
				'panel'       => 'mobile',
			),
			'catalog_mobile'         => array(
				'title'       => esc_html__( 'Product Catalog', 'martfury' ),
				'description' => '',
				'priority'    => 20,
				'capability'  => 'edit_theme_options',
				'panel'       => 'mobile',
			),
			'catalog_sidebar_mobile' => array(
				'title'       => esc_html__( 'Catalog Sidebar', 'martfury' ),
				'description' => '',
				'priority'    => 20,
				'capability'  => 'edit_theme_options',
				'panel'       => 'mobile',
			),
			'vendor_mobile'          => array(
				'title'       => esc_html__( 'Vendor Page', 'martfury' ),
				'description' => '',
				'priority'    => 20,
				'capability'  => 'edit_theme_options',
				'panel'       => 'mobile',
			),
			'product_page_mobile'    => array(
				'title'       => esc_html__( 'Product Page', 'martfury' ),
				'description' => '',
				'priority'    => 20,
				'capability'  => 'edit_theme_options',
				'panel'       => 'mobile',
			),

			'footer_mobile' => array(
				'title'       => esc_html__( 'Footer', 'martfury' ),
				'description' => '',
				'priority'    => 20,
				'capability'  => 'edit_theme_options',
				'panel'       => 'mobile',
			),
		)
	);

	return $sections;
}

add_filter( 'martfury_customize_sections', 'martfury_mobile_customize_sections' );

/**
 * Adds theme options of Mobile.
 *
 * @param array $settings Theme options.
 *
 * @return array
 */
function martfury_mobile_customize_fields( $fields ) {
	$fields = array_merge(
		$fields, array(
			'enable_mobile_version'             => array(
				'type'     => 'toggle',
				'label'    => esc_html__( 'Mobile Version', 'martfury' ),
				'section'  => 'general_mobile',
				'default'  => '1',
				'priority' => 20,
			),
			'homepage_mobile'                   => array(
				'type'     => 'select',
				'label'    => esc_html__( 'Homepage', 'martfury' ),
				'section'  => 'homepage_mobile',
				'default'  => '',
				'priority' => 20,
				'choices'  => class_exists( 'Kirki_Helper' ) && is_admin() ? Kirki_Helper::get_posts( array(
					'posts_per_page' => - 1,
					'post_type'      => 'page',
				) ) : '',
			),
			'topbar_mobile'                     => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Top Bar', 'martfury' ),
				'default'     => '0',
				'section'     => 'header_mobile',
				'priority'    => 20,
				'description' => esc_html__( 'Go to Appearance > Widgets > Topbar Left and Topbar Right to add widgets content.', 'martfury' ),
			),
			'menu_extras_mobile_custom_1'       => array(
				'type'     => 'custom',
				'default'  => '<hr/>',
				'section'  => 'header_mobile',
				'priority' => 20,
			),
			'sticky_header_mobile'              => array(
				'type'     => 'toggle',
				'label'    => esc_html__( 'Sticky Header', 'martfury' ),
				'default'  => '1',
				'section'  => 'header_mobile',
				'priority' => 20,
			),
			'sticky_header_type_mobile'         => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Sticky Header Type', 'martfury' ),
				'section'     => 'header_mobile',
				'default'     => 'header_top',
				'priority'    => 20,
				'choices'     => array(
					'header_top'    => esc_html__( 'Header Top', 'martfury' ),
					'header_bottom' => esc_html__( 'Header Bottom', 'martfury' ),
				),
				'description' => esc_html__( 'This option is used for homepage.', 'martfury' ),
			),
			'menu_extras_mobile'                => array(
				'type'     => 'multicheck',
				'label'    => esc_html__( 'Elements', 'martfury' ),
				'section'  => 'header_mobile',
				'default'  => array( 'search', 'cart', 'account' ),
				'priority' => 20,
				'choices'  => array(
					'search'   => esc_html__( 'Search', 'martfury' ),
					'compare'  => esc_html__( 'Compare', 'martfury' ),
					'wishlist' => esc_html__( 'WishList', 'martfury' ),
					'cart'     => esc_html__( 'Cart', 'martfury' ),
					'account'  => esc_html__( 'Account', 'martfury' ),
					'category' => esc_html__( 'Menu', 'martfury' ),
				),
			),
			'header_menu_panel_mobile_title'    => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Menu Panel Title', 'martfury' ),
				'section'  => 'header_mobile',
				'default'  => esc_html__( 'Main Menu', 'martfury' ),
				'priority' => 20,
			),
			'header_skin_mobile_custom_1'       => array(
				'type'     => 'custom',
				'default'  => '<hr/>',
				'section'  => 'header_mobile',
				'priority' => 20,
			),
			// Header Skin
			'custom_header_skin_mobile'         => array(
				'type'     => 'toggle',
				'label'    => esc_html__( 'Custom Header Skin', 'martfury' ),
				'section'  => 'header_mobile',
				'default'  => '',
				'priority' => 20,
			),
			'custom_header_homepage_mobile'     => array(
				'type'     => 'toggle',
				'label'    => esc_html__( 'Use Custom Header For Homepage', 'martfury' ),
				'section'  => 'header_mobile',
				'default'  => '',
				'priority' => 20,
			),
			'topbar_bg_color_mobile'            => array(
				'type'            => 'color',
				'label'           => esc_html__( 'Top Bar Background Color', 'martfury' ),
				'default'         => '',
				'section'         => 'header_mobile',
				'priority'        => 20,
				'choices'         => array(
					'alpha' => true,
				),
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'custom_header_skin_mobile',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'topbar_text_color_mobile'          => array(
				'type'            => 'color',
				'label'           => esc_html__( 'Top Bar Text Color', 'martfury' ),
				'default'         => '',
				'section'         => 'header_mobile',
				'priority'        => 20,
				'choices'         => array(
					'alpha' => true,
				),
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'custom_header_skin_mobile',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'header_bg_color_mobile'            => array(
				'type'            => 'color',
				'label'           => esc_html__( 'Header Background Color', 'martfury' ),
				'default'         => '',
				'section'         => 'header_mobile',
				'priority'        => 20,
				'choices'         => array(
					'alpha' => true,
				),
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'custom_header_skin_mobile',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'header_text_color_mobile'          => array(
				'type'            => 'color',
				'label'           => esc_html__( 'Header Text Color', 'martfury' ),
				'default'         => '',
				'section'         => 'header_mobile',
				'priority'        => 20,
				'choices'         => array(
					'alpha' => true,
				),
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'custom_header_skin_mobile',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'search_button_bg_color_mobile'     => array(
				'type'            => 'color',
				'label'           => esc_html__( 'Button & Counter Background Color', 'martfury' ),
				'default'         => '',
				'section'         => 'header_mobile',
				'priority'        => 20,
				'choices'         => array(
					'alpha' => true,
				),
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'custom_header_skin_mobile',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'search_button_text_color_mobile'   => array(
				'type'            => 'color',
				'label'           => esc_html__( 'Button & Counter Text Color', 'martfury' ),
				'default'         => '',
				'section'         => 'header_mobile',
				'priority'        => 20,
				'choices'         => array(
					'alpha' => true,
				),
				'transport'       => 'postMessage',
				'active_callback' => array(
					array(
						'setting'  => 'custom_header_skin_mobile',
						'operator' => '==',
						'value'    => '1',
					),
				),
			),
			'logo_mobile'                       => array(
				'type'        => 'image',
				'label'       => esc_html__( 'Logo', 'martfury' ),
				'description' => esc_html__( 'This logo is used for mobile.', 'martfury' ),
				'section'     => 'logo_mobile',
				'default'     => '',
				'priority'    => 20,

			),
			'logo_mobile_width'                 => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Logo Width(px)', 'martfury' ),
				'section'  => 'logo_mobile',
				'priority' => 20,
			),
			'logo_mobile_height'                => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Logo Height(px)', 'martfury' ),
				'section'  => 'logo_mobile',
				'priority' => 20,
			),
			'logo_mobile_margins'               => array(
				'type'     => 'spacing',
				'label'    => esc_html__( 'Logo Margin', 'martfury' ),
				'section'  => 'logo_mobile',
				'priority' => 20,
				'default'  => array(
					'top'    => '0',
					'bottom' => '0',
					'left'   => '0',
					'right'  => '0',
				),
			),
			'navigation_mobile'                 => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Navigation Buttons', 'martfury' ),
				'section'     => 'navigation_mobile',
				'description' => esc_html__( 'Check this option to show the navigation buttons in the bottom of the page.', 'martfury' ),
				'default'     => 1,
				'priority'    => 20,
			),
			'navigation_els_mobile'             => array(
				'type'     => 'sortable',
				'label'    => esc_html__( 'Navigation Elements', 'martfury' ),
				'section'  => 'navigation_mobile',
				'default'  => array( 'home', 'cat', 'search', 'cart' ),
				'priority' => 70,
				'choices'  => array(
					'home'     => esc_html__( 'Home', 'martfury' ),
					'cat'      => esc_html__( 'Category', 'martfury' ),
					'search'   => esc_html__( 'Search', 'martfury' ),
					'cart'     => esc_html__( 'Cart', 'martfury' ),
					'wishlist' => esc_html__( 'Wishlist', 'martfury' ),
					'compare'  => esc_html__( 'Compare', 'martfury' ),
					'account'  => esc_html__( 'Account', 'martfury' ),
				),
			),
			'navigation_cat_panel_mobile'       => array(
				'type'     => 'number',
				'label'    => esc_html__( 'Navigation Panel Width(%)', 'martfury' ),
				'section'  => 'navigation_mobile',
				'default'  => '',
				'priority' => 70,
				'choices'  => [
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				],
			),
			'custom_home_mobile_extras'         => array(
				'type'     => 'custom',
				'section'  => 'navigation_mobile',
				'default'  => '<hr>',
				'priority' => 70,
			),
			'navigation_home_mobile'            => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Navigation Home Text', 'martfury' ),
				'section'  => 'navigation_mobile',
				'default'  => esc_html__( 'Home', 'martfury' ),
				'priority' => 70,
			),
			'custom_cat_mobile_extras'          => array(
				'type'     => 'custom',
				'section'  => 'navigation_mobile',
				'default'  => '<hr>',
				'priority' => 70,
			),
			'navigation_cat_mobile'             => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Navigation Category Text', 'martfury' ),
				'section'  => 'navigation_mobile',
				'default'  => esc_html__( 'Category', 'martfury' ),
				'priority' => 70,
			),
			'navigation_cat_panel_mobile_title' => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Category Panel Title', 'martfury' ),
				'section'  => 'navigation_mobile',
				'default'  => '',
				'priority' => 70,
			),
			'navigation_cat_panel_mobile_link'  => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Category Panel Link', 'martfury' ),
				'section'  => 'navigation_mobile',
				'priority' => 70,
			),
			'submenu_mobile'                    => array(
				'type'     => 'select',
				'label'    => esc_html__( 'Open Submenus by', 'martfury' ),
				'section'  => 'navigation_mobile',
				'default'  => 'menu',
				'priority' => 70,
				'choices'  => array(
					'menu' => esc_html__( 'Click on menu items', 'martfury' ),
					'icon' => esc_html__( 'Click on menu icons', 'martfury' ),
				),
			),
			'custom_search_mobile_extras'       => array(
				'type'     => 'custom',
				'section'  => 'navigation_mobile',
				'default'  => '<hr>',
				'priority' => 70,
			),
			'navigation_search_mobile'          => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Navigation Search Text', 'martfury' ),
				'section'  => 'navigation_mobile',
				'default'  => esc_html__( 'Search', 'martfury' ),
				'priority' => 70,
			),
			'hot_words_mobile'                  => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Enable Hot Words', 'martfury' ),
				'section'     => 'navigation_mobile',
				'default'     => 1,
				'priority'    => 70,
				'description' => esc_html__( 'Check this option to enable hot words below search box on mobile', 'martfury' ),
			),
			'custom_cart_mobile_extras'         => array(
				'type'     => 'custom',
				'section'  => 'navigation_mobile',
				'default'  => '<hr>',
				'priority' => 70,
			),
			'navigation_cart_mobile'            => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Navigation Cart Text', 'martfury' ),
				'section'  => 'navigation_mobile',
				'default'  => esc_html__( 'Cart', 'martfury' ),
				'priority' => 70,
			),
			'navigation_cart_behaviour'         => array(
				'type'     => 'radio',
				'label'    => esc_html__( 'Cart Icon Behaviour', 'martfury' ),
				'default'  => 'link',
				'section'  => 'navigation_mobile',
				'choices'  => array(
					'panel' => esc_attr__( 'Open the cart panel', 'martfury' ),
					'link'  => esc_attr__( 'Open the cart page', 'martfury' ),
				),
				'priority' => 70,
			),
			'custom_wishlist_mobile_extras'     => array(
				'type'     => 'custom',
				'section'  => 'navigation_mobile',
				'default'  => '<hr>',
				'priority' => 70,
			),
			'navigation_wishlist_mobile'        => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Navigation Wishlist Text', 'martfury' ),
				'section'  => 'navigation_mobile',
				'default'  => esc_html__( 'Wishlist', 'martfury' ),
				'priority' => 70,
			),
			'custom_compare_mobile_extras'      => array(
				'type'     => 'custom',
				'section'  => 'navigation_mobile',
				'default'  => '<hr>',
				'priority' => 70,
			),
			'navigation_compare_mobile'         => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Navigation Compare Text', 'martfury' ),
				'section'  => 'navigation_mobile',
				'default'  => esc_html__( 'Compare', 'martfury' ),
				'priority' => 70,
			),
			'custom_account_mobile_extras'      => array(
				'type'     => 'custom',
				'section'  => 'navigation_mobile',
				'default'  => '<hr>',
				'priority' => 70,
			),
			'navigation_account_mobile'         => array(
				'type'     => 'text',
				'label'    => esc_html__( 'Navigation Account Text', 'martfury' ),
				'section'  => 'navigation_mobile',
				'default'  => esc_html__( 'Account', 'martfury' ),
				'priority' => 70,
			),
			// Catalog Mobile
			'catalog_toolbar_els_mobile'        => array(
				'type'        => 'multicheck',
				'label'       => esc_html__( 'ToolBar Elements', 'martfury' ),
				'section'     => 'catalog_mobile',
				'default'     => array( 'filter', 'sortby' ),
				'priority'    => 70,
				'choices'     => array(
					'filter' => esc_html__( 'Filter', 'martfury' ),
					'sortby' => esc_html__( 'Sort By', 'martfury' ),
				),
				'description' => esc_html__( 'Select which elements you want to show.', 'martfury' ),
			),
			'catalog_variation_images_mobile'   => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Show Variation Images', 'martfury' ),
				'section'     => 'catalog_mobile',
				'default'     => 0,
				'priority'    => 90,
				'description' => esc_html__( 'Check this option to show variation images in the product item.', 'martfury' ),
			),
			'catalog_featured_icons_mobile'     => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Show Featured Icons', 'martfury' ),
				'section'     => 'catalog_mobile',
				'default'     => 0,
				'priority'    => 90,
				'description' => esc_html__( 'Check this option to show featured icons in the product item.', 'martfury' ),
			),
			// Catalog Sidebar
			'collapse_the_filter'               => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Filter Collapse', 'martfury' ),
				'section'     => 'catalog_sidebar_mobile',
				'default'     => 0,
				'priority'    => 40,
				'description' => esc_html__( 'Check this option to collapse the filter on catalog sidebar.', 'martfury' ),
			),
			'collapse_the_filter_status'        => array(
				'type'            => 'select',
				'label'           => esc_html__( 'Collapse Status', 'martfury' ),
				'section'         => 'catalog_sidebar_mobile',
				'default'         => 'close',
				'priority'        => 40,
				'choices'         => array(
					'close' => esc_html__( 'Close', 'martfury' ),
					'open'  => esc_html__( 'Open', 'martfury' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'collapse_the_filter',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),

			// Product page
			'product_add_to_cart_fixed_mobile'  => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Add to cart fixed', 'martfury' ),
				'section'     => 'product_page_mobile',
				'default'     => 1,
				'priority'    => 40,
				'description' => esc_html__( 'Check this option to enable add to cart button fixed on mobile.', 'martfury' ),
			),
			'product_sidebar_mobile'            => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Product Sidebar', 'martfury' ),
				'section'     => 'product_page_mobile',
				'default'     => 0,
				'priority'    => 40,
				'description' => esc_html__( 'Check this option to enable product sidebar on mobile.', 'martfury' ),
			),
			'sticky_product_info_mobile'        => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Sticky Product Info', 'martfury' ),
				'section'     => 'product_page_mobile',
				'default'     => 0,
				'priority'    => 40,
				'description' => esc_html__( 'Check this option to enable sticky product info on the product page.', 'martfury' ),
			),
			'custom_product_page_mobile_1'      => array(
				'type'     => 'custom',
				'section'  => 'product_page_mobile',
				'default'  => '<hr>',
				'priority' => 40,
			),
			'collapse_tab'                      => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Product Tabs Collapse', 'martfury' ),
				'section'     => 'product_page_mobile',
				'default'     => 0,
				'priority'    => 40,
				'description' => esc_html__( 'Check this option to show the product tabs collapse on product page.', 'martfury' ),
			),
			'collapse_tab_status'               => array(
				'type'            => 'select',
				'label'           => esc_html__( 'Collapse Status', 'martfury' ),
				'section'         => 'product_page_mobile',
				'default'         => 'close',
				'priority'        => 40,
				'choices'         => array(
					'close' => esc_html__( 'Close', 'martfury' ),
					'open'  => esc_html__( 'Open', 'martfury' ),
				),
				'active_callback' => array(
					array(
						'setting'  => 'collapse_tab',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
			// Inner Page
			'inner_page_header_layout'          => array(
				'type'     => 'select',
				'label'    => esc_html__( 'Header Layout', 'martfury' ),
				'section'  => 'inner_page_mobile',
				'default'  => 'v2',
				'priority' => 20,
				'choices'  => array(
					'v1' => esc_html__( 'Layout 1', 'martfury' ),
					'v2' => esc_html__( 'Layout 2', 'martfury' ),
				),
			),
			// Footer Mobile
			'footer_newsletter_mobile'          => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Footer Newsletter', 'martfury' ),
				'section'     => 'footer_mobile',
				'description' => esc_html__( 'Check this option to show the footer newsletter on mobile.', 'martfury' ),
				'default'     => 0,
				'priority'    => 20,
			),
			'footer_info_mobile'                => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Footer Info', 'martfury' ),
				'section'     => 'footer_mobile',
				'description' => esc_html__( 'Check this option to show the footer info on mobile.', 'martfury' ),
				'default'     => 0,
				'priority'    => 20,
			),
			'footer_widgets_mobile'             => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Footer Widgets', 'martfury' ),
				'section'     => 'footer_mobile',
				'description' => esc_html__( 'Check this option to show the footer widgets on mobile.', 'martfury' ),
				'default'     => 1,
				'priority'    => 20,
			),
			'footer_links_mobile'               => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Footer Link', 'martfury' ),
				'section'     => 'footer_mobile',
				'description' => esc_html__( 'Check this option to show the footer link on mobile.', 'martfury' ),
				'default'     => 0,
				'priority'    => 20,
			),
			'custom_footer_recently_mobile'     => array(
				'type'     => 'custom',
				'section'  => 'footer_mobile',
				'default'  => '<hr>',
				'priority' => 20,
			),
			'footer_recently_viewed_mobile'     => array(
				'type'        => 'toggle',
				'label'       => esc_html__( 'Show Recently Viewed', 'martfury' ),
				'section'     => 'footer_mobile',
				'default'     => 1,
				'priority'    => 90,
				'description' => esc_html__( 'Check this option to show the recently viewed products on mobile.', 'martfury' ),
			),
			'footer_recently_viewed_els_mobile' => array(
				'type'            => 'multicheck',
				'label'           => esc_html__( 'Show Recently Viewed in', 'martfury' ),
				'section'         => 'footer_mobile',
				'default'         => array( 'homepage', 'catalog', 'single_product' ),
				'priority'        => 90,
				'choices'         => array(
					'homepage'       => esc_html__( 'HomePage', 'martfury' ),
					'catalog'        => esc_html__( 'Catalog', 'martfury' ),
					'single_product' => esc_html__( 'Single Product', 'martfury' ),
					'page'           => esc_html__( 'Page', 'martfury' ),
					'post'           => esc_html__( 'Post', 'martfury' ),
					'other'          => esc_html__( 'Other Pages', 'martfury' ),
				),
				'description'     => esc_html__( 'Check pages to show the recently viewed products on mobile.', 'martfury' ),
				'active_callback' => array(
					array(
						'setting'  => 'footer_recently_viewed_mobile',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
		)
	);

	if ( class_exists( 'WeDevs_Dokan' ) || class_exists( 'WC_Vendors' ) || class_exists( 'WCMp' ) || class_exists( 'WCFMmp' ) ) {
		$fields = array_merge(
			$fields, array(
				'catalog_toolbar_els_12_mobile' => array(
					'type'        => 'multicheck',
					'label'       => esc_html__( 'ToolBar Elements', 'martfury' ),
					'section'     => 'vendor_mobile',
					'default'     => array( 'filter', 'view' ),
					'priority'    => 70,
					'choices'     => array(
						'filter' => esc_html__( 'Filter', 'martfury' ),
						'view'   => esc_html__( 'View', 'martfury' ),
					),
					'description' => esc_html__( 'Select which elements you want to show.', 'martfury' ),
				),
			)
		);
	}

	return $fields;
}

add_filter( 'martfury_customize_fields', 'martfury_mobile_customize_fields' );