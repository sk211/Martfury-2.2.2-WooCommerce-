<?php
/**
 * Theme options of Vendors.
 *
 * @package Martfury
 */


/**
 * Adds theme options sections of Martfury.
 *
 * @param array $sections Theme options sections.
 *
 * @return array
 */
function martfury_vendors_customize_sections( $sections ) {
	$sections = array_merge( $sections, array(
		'vendor_page' => array(
			'title'       => esc_html__( 'Vendor Page', 'martfury' ),
			'description' => '',
			'priority'    => 45,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
	) );

	return $sections;
}

add_filter( 'martfury_customize_sections', 'martfury_vendors_customize_sections' );

/**
 * Adds theme options of vendors.
 *
 * @param array $settings Theme options.
 *
 * @return array
 */
function martfury_vendors_customize_settings( $fields ) {

	if ( ! class_exists( 'WeDevs_Dokan' ) && ! class_exists( 'WC_Vendors' ) && ! class_exists( 'WCMp' ) && ! class_exists( 'WCFMmp' ) ) {
		return $fields;
	}

	$fields = array_merge(
		$fields, array(
			'catalog_vendor_name'    => array(
				'type'     => 'select',
				'label'    => esc_html__( 'Vendor Name', 'martfury' ),
				'section'  => 'catalog_page',
				'default'  => 'hover',
				'priority' => 90,
				'choices'  => array(
					'hover'   => esc_html__( 'Display after on hover', 'martfury' ),
					'display' => esc_html__( 'Always on display', 'martfury' ),
					'hidden'  => esc_html__( 'Hidden', 'martfury' ),
				),
			),
			// Other Catlog
			'catalog_full_width_12'  => array(
				'type'     => 'toggle',
				'label'    => esc_html__( 'Full Width', 'martfury' ),
				'section'  => 'vendor_page',
				'default'  => 0,
				'priority' => 70,
			),
			'page_header_vendor_els' => array(
				'type'     => 'multicheck',
				'label'    => esc_html__( 'Page Header Element', 'martfury' ),
				'section'  => 'vendor_page',
				'default'  => array( 'breadcrumb' ),
				'priority' => 70,
				'choices'  => array(
					'breadcrumb' => esc_html__( 'Breadcrumb', 'martfury' ),
				),
			),
			'catalog_toolbar_els_12' => array(
				'type'        => 'multicheck',
				'label'       => esc_html__( 'ToolBar Elements', 'martfury' ),
				'section'     => 'vendor_page',
				'default'     => array( 'found', 'view' ),
				'priority'    => 70,
				'choices'     => array(
					'found' => esc_html__( 'Products Found', 'martfury' ),
					'view'  => esc_html__( 'View', 'martfury' ),
				),
				'description' => esc_html__( 'Select which elements you want to show.', 'martfury' ),
			),
			'catalog_view_12'        => array(
				'type'     => 'select',
				'label'    => esc_html__( 'View', 'martfury' ),
				'section'  => 'vendor_page',
				'default'  => 'grid',
				'priority' => 70,
				'choices'  => array(
					'grid' => esc_html__( 'Grid', 'martfury' ),
					'list' => esc_html__( 'List', 'martfury' ),
				),
			),
			'products_columns_12'    => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Product Columns', 'martfury' ),
				'section'     => 'vendor_page',
				'default'     => '4',
				'priority'    => 80,
				'choices'     => array(
					'4' => esc_html__( '4 Columns', 'martfury' ),
					'6' => esc_html__( '6 Columns', 'martfury' ),
					'5' => esc_html__( '5 Columns', 'martfury' ),
					'3' => esc_html__( '3 Columns', 'martfury' ),
				),
				'description' => esc_html__( 'Specify how many product columns you want to show.', 'martfury' ),
			),
		)
	);

	if ( ! class_exists( 'WCFMmp' ) ) {
		$fields = array_merge(
			$fields, array(
				// Other Catlog
				'products_per_page_12'   => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Product Numbers Per Page', 'martfury' ),
					'section'     => 'vendor_page',
					'default'     => 12,
					'priority'    => 90,
					'description' => esc_html__( 'Specify how many products you want to show on the catalog page', 'martfury' ),
				),
			)
		);
	}

	if ( class_exists( 'WeDevs_Dokan' ) ) {
		$fields = array_merge(
			$fields, array(
				// Other Catlog
				'dokan_dashboard_layout' => array(
					'type'     => 'select',
					'label'    => esc_html__( 'Dashboard Layout', 'martfury' ),
					'default'  => '2',
					'section'  => 'vendor_page',
					'priority' => 90,
					'choices'  => array(
						'1' => esc_html__( 'Default by the theme', 'martfury' ),
						'2' => esc_html__( 'Default by the plugin', 'martfury' ),
					),
				),
			)
		);
	}

	if ( class_exists( 'WC_Vendors' ) || class_exists( 'WCMp' ) ) {
		$fields = array_merge(
			$fields, array(
				// Other Catlog
				'catalog_sidebar_12' => array(
					'type'        => 'select',
					'label'       => esc_html__( 'Vendor Layout', 'martfury' ),
					'default'     => 'sidebar-content',
					'section'     => 'vendor_page',
					'priority'    => 60,
					'description' => esc_html__( 'Select default layout for vendor page.', 'martfury' ),
					'choices'     => array(
						'full-content'    => esc_html__( 'No Sidebar', 'martfury' ),
						'sidebar-content' => esc_html__( 'Left Sidebar', 'martfury' ),
						'content-sidebar' => esc_html__( 'Right Sidebar', 'martfury' ),
					),
				),
			)
		);
	}

	if ( class_exists( 'WCMp' ) ) {
		$fields = array_merge(
			$fields, array(
				// Other Catlog
				'vendor_store_header' => array(
					'type'     => 'toggle',
					'label'    => esc_html__( 'Store Header', 'martfury' ),
					'default'  => 0,
					'section'  => 'vendor_page',
					'priority' => 60,
				),
			)
		);
	}

	if ( class_exists( 'WCFMmp' ) ) {
		$fields = array_merge(
			$fields, array(
				// Other Catlog
				'wcfm_single_sold_by_template' => array(
					'type'     => 'select',
					'label'    => esc_html__( 'Sold By Template', 'martfury' ),
					'default'  => 'theme',
					'section'  => 'product_page',
					'priority' => 60,
					'choices'  => array(
						'theme'  => esc_html__( 'By The Theme', 'martfury' ),
						'plugin' => esc_html__( 'By The Plugin', 'martfury' ),
					),
				),
			)
		);
	}

	return $fields;
}

add_filter( 'martfury_customize_fields', 'martfury_vendors_customize_settings', 30 );