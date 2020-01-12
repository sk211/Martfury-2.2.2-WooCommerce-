<?php
/**
 * Custom functions for Visual Composer
 *
 * @package    Martfury
 * @subpackage Visual Composer
 */

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

/**
 * Class fos_VC
 *
 * @since 1.0.0
 */
class Martfury_VC {

	/**
	 * Construction
	 */
	function __construct() {
		// Stop if VC is not installed
		if ( ! is_plugin_active( 'js_composer/js_composer.php' ) ) {
			return false;
		}

		add_action( 'init', array( $this, 'map_shortcodes' ), 20 );

		add_filter( 'vc_autocomplete_martfury_products_of_category_cat_callback', array(
			$this,
			'productCatsAutocompleteSuggester',
		), 10, 1 );
		add_filter( 'vc_autocomplete_martfury_products_of_category_cat_render', array(
			$this,
			'productCatsAutocompleteRender',
		), 10, 1 );

		add_filter( 'vc_autocomplete_martfury_products_of_category_tags_callback', array(
			$this,
			'productTagsAutocompleteSuggester',
		), 10, 1 );
		add_filter( 'vc_autocomplete_martfury_products_of_category_tags_render', array(
			$this,
			'productTagsAutocompleteRender',
		), 10, 1 );

		add_filter( 'vc_autocomplete_martfury_products_of_category_2_cat_callback', array(
			$this,
			'productCatsAutocompleteSuggester',
		), 10, 1 );
		add_filter( 'vc_autocomplete_martfury_products_of_category_2_cat_render', array(
			$this,
			'productCatsAutocompleteRender',
		), 10, 1 );

		add_filter( 'vc_autocomplete_martfury_products_of_category_2_ids_render', array(
			$this,
			'productIdsAutocompleteRender',
		), 10, 1 );
		add_filter( 'vc_autocomplete_martfury_products_of_category_2_ids_callback', array(
			$this,
			'productIdsAutocompleteSuggester',
		), 10, 1 );


		add_filter( 'vc_autocomplete_martfury_product_tabs_cat_callback', array(
			$this,
			'productCatsAutocompleteSuggester',
		), 10, 1 );
		add_filter( 'vc_autocomplete_martfury_producs_tabs_cat_render', array(
			$this,
			'productCatsAutocompleteRender',
		), 10, 1 );

		add_filter( 'vc_autocomplete_martfury_products_grid_cat_callback', array(
			$this,
			'productCatsAutocompleteSuggester',
		), 10, 1 );
		add_filter( 'vc_autocomplete_martfury_products_grid_cat_render', array(
			$this,
			'productCatsAutocompleteRender',
		), 10, 1 );

		add_filter( 'vc_autocomplete_martfury_products_list_cat_callback', array(
			$this,
			'productCatsAutocompleteSuggester',
		), 10, 1 );
		add_filter( 'vc_autocomplete_martfury_products_list_cat_render', array(
			$this,
			'productCatsAutocompleteRender',
		), 10, 1 );

		add_filter( 'vc_autocomplete_martfury_category_box_cats_callback', array(
			$this,
			'productCatsAutocompleteSuggester',
		), 10, 1 );
		add_filter( 'vc_autocomplete_martfury_category_box_cats_render', array(
			$this,
			'productCatsAutocompleteRender',
		), 10, 1 );

		add_filter( 'vc_autocomplete_martfury_products_carousel_cat_callback', array(
			$this,
			'productCatsAutocompleteSuggester',
		), 10, 1 );
		add_filter( 'vc_autocomplete_martfury_products_carousel_cat_render', array(
			$this,
			'productCatsAutocompleteRender',
		), 10, 1 );

		add_filter( 'vc_autocomplete_martfury_deals_of_the_day_cat_callback', array(
			$this,
			'productCatsAutocompleteSuggester',
		), 10, 1 );
		add_filter( 'vc_autocomplete_martfury_deals_of_the_day_cat_render', array(
			$this,
			'productCatsAutocompleteRender',
		), 10, 1 );


		add_filter( 'vc_autocomplete_martfury_product_deals_carousel_cat_callback', array(
			$this,
			'productCatsAutocompleteSuggester',
		), 10, 1 );
		add_filter( 'vc_autocomplete_martfury_product_deals_carousel_cat_render', array(
			$this,
			'productCatsAutocompleteRender',
		), 10, 1 );

		add_filter( 'vc_autocomplete_martfury_product_deals_grid_cat_callback', array(
			$this,
			'productCatsAutocompleteSuggester',
		), 10, 1 );
		add_filter( 'vc_autocomplete_martfury_product_deals_grid_cat_render', array(
			$this,
			'productCatsAutocompleteRender',
		), 10, 1 );


		add_filter( 'vc_autocomplete_martfury_products_list_carousel_ids_render', array(
			$this,
			'productIdsAutocompleteRender',
		), 10, 1 );
		add_filter( 'vc_autocomplete_martfury_products_list_carousel_ids_callback', array(
			$this,
			'productIdsAutocompleteSuggester',
		), 10, 1 );


		add_filter( 'vc_iconpicker-type-linearicons', array( $this, 'vc_iconpicker_type_linearicons' ) );

		add_action( 'vc_base_register_front_css', array( $this, 'vc_iconpicker_base_register_css' ) );
		add_action( 'vc_base_register_admin_css', array( $this, 'vc_iconpicker_base_register_css' ) );

		add_action( 'vc_enqueue_font_icon_element', array( $this, 'vc_icon_element_fonts_enqueue' ) );
	}


	/**
	 * Add new params or add new shortcode to VC
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	function map_shortcodes() {

		// Empty Space
		vc_map(
			array(
				'name'        => esc_html__( 'Martfury Empty Space', 'martfury' ),
				'base'        => 'martfury_empty_space',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Blank space with custom height for desktop, tablet an mobile', 'martfury' ),
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Height(px)', 'martfury' ),
						'param_name'  => 'height',
						'admin_label' => true,
						'description' => esc_html__( 'Enter empty space height on Desktop.', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Height on Tablet(px)', 'martfury' ),
						'param_name'  => 'height_tablet',
						'admin_label' => true,
						'description' => esc_html__( 'Enter empty space height on Mobile. Leave empty to use the height of the desktop', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Height on Mobile(px)', 'martfury' ),
						'param_name'  => 'height_mobile',
						'admin_label' => true,
						'description' => esc_html__( 'Enter empty space height on Mobile. Leave empty to use the height of the tablet', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// Icon Box
		vc_map(
			array(
				'name'        => esc_html__( 'Icon Box', 'martfury' ),
				'base'        => 'martfury_icon_box',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show an icon from libraries and a title', 'martfury' ),
				'params'      => array(
					array(
						'heading'     => esc_html__( 'Icon library', 'martfury' ),
						'description' => esc_html__( 'Select icon library.', 'martfury' ),
						'param_name'  => 'icon_type',
						'type'        => 'dropdown',
						'value'       => array(
							esc_html__( 'Font Awesome', 'martfury' ) => 'fontawesome',
							esc_html__( 'Linear Icons', 'martfury' ) => 'linearicons',
							esc_html__( 'Custom Image', 'martfury' ) => 'image',
						),
						'group'       => esc_html__( 'Icons', 'martfury' ),
					),
					array(
						'heading'     => esc_html__( 'Icon', 'martfury' ),
						'description' => esc_html__( 'Pick an icon from library.', 'martfury' ),
						'type'        => 'iconpicker',
						'param_name'  => 'icon_fontawesome',
						'value'       => 'fa fa-adjust',
						'settings'    => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
						),
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'fontawesome',
						),
						'group'       => esc_html__( 'Icons', 'martfury' ),
					),
					array(
						'heading'    => esc_html__( 'Icon', 'martfury' ),
						'type'       => 'iconpicker',
						'param_name' => 'icon_linearicons',
						'settings'   => array(
							'emptyIcon'    => true,
							'type'         => 'linearicons',
							'iconsPerPage' => 4000,
						),
						'dependency' => array(
							'element' => 'icon_type',
							'value'   => 'linearicons',
						),
						'group'      => esc_html__( 'Icons', 'martfury' ),
					),
					array(
						'heading'     => esc_html__( 'Icon Image', 'martfury' ),
						'description' => esc_html__( 'Upload icon image', 'martfury' ),
						'type'        => 'attach_image',
						'param_name'  => 'image',
						'value'       => '',
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'image',
						),
						'group'       => esc_html__( 'Icons', 'martfury' ),
					),
					array(
						'heading'    => esc_html__( 'Icon Position', 'martfury' ),
						'param_name' => 'icon_position',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Left', 'martfury' )       => 'left',
							esc_html__( 'Top Center', 'martfury' ) => 'top-center',
						),
						'group'      => esc_html__( 'Icons', 'martfury' ),
					),
					array(
						'heading'    => esc_html__( 'Text Align', 'martfury' ),
						'param_name' => 'text_align',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Left', 'martfury' )   => 'left',
							esc_html__( 'Center', 'martfury' ) => 'center',
							esc_html__( 'Right', 'martfury' )  => 'right',
						),
						'group'      => esc_html__( 'Icons', 'martfury' ),
						'dependency' => array(
							'element' => 'icon_position',
							'value'   => 'left',
						),
					),
					array(
						'heading'    => esc_html__( 'Link', 'martfury' ),
						'param_name' => 'link',
						'type'       => 'vc_link',
						'value'      => '',
						'group'      => esc_html__( 'Box Setting', 'martfury' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'martfury' ),
						'param_name' => 'title',
						'value'      => esc_html__( 'I am Icon Box', 'martfury' ),
						'group'      => esc_html__( 'Box Setting', 'martfury' ),
					),
					array(
						'type'        => 'textarea_html',
						'heading'     => esc_html__( 'Content', 'martfury' ),
						'param_name'  => 'content',
						'value'       => '',
						'description' => esc_html__( 'Enter the content of this box', 'martfury' ),
						'group'       => esc_html__( 'Box Setting', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
						'group'       => esc_html__( 'Box Setting', 'martfury' ),
					),
				),
			)
		);

		// Icon Box 2
		vc_map(
			array(
				'name'        => esc_html__( 'Icon Box 2', 'martfury' ),
				'base'        => 'martfury_icon_box_2',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show an icon from libraries and a title', 'martfury' ),
				'params'      => array(
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Border', 'martfury' ),
						'param_name'  => 'border',
						'value'       => array( esc_html__( 'Show', 'martfury' ) => '1' ),
						'description' => esc_html__( 'If "YES" Show Border on left', 'martfury' ),
					),
					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Box Info', 'martfury' ),
						'value'      => '',
						'param_name' => 'info',
						'params'     => array(
							array(
								'heading'     => esc_html__( 'Icon library', 'martfury' ),
								'description' => esc_html__( 'Select icon library.', 'martfury' ),
								'param_name'  => 'icon_type',
								'type'        => 'dropdown',
								'value'       => array(
									esc_html__( 'Font Awesome', 'martfury' ) => 'fontawesome',
									esc_html__( 'Linear Icons', 'martfury' ) => 'linearicons',
									esc_html__( 'Custom Image', 'martfury' ) => 'image',
								),
							),
							array(
								'heading'     => esc_html__( 'Icon', 'martfury' ),
								'description' => esc_html__( 'Pick an icon from library.', 'martfury' ),
								'type'        => 'iconpicker',
								'param_name'  => 'icon_fontawesome',
								'value'       => 'fa fa-adjust',
								'settings'    => array(
									'emptyIcon'    => false,
									'iconsPerPage' => 4000,
								),
								'dependency'  => array(
									'element' => 'icon_type',
									'value'   => 'fontawesome',
								),
							),
							array(
								'heading'    => esc_html__( 'Icon', 'martfury' ),
								'type'       => 'iconpicker',
								'param_name' => 'icon_linearicons',
								'settings'   => array(
									'emptyIcon'    => true,
									'type'         => 'linearicons',
									'iconsPerPage' => 4000,
								),
								'dependency' => array(
									'element' => 'icon_type',
									'value'   => 'linearicons',
								),
							),
							array(
								'heading'     => esc_html__( 'Icon Image', 'martfury' ),
								'description' => esc_html__( 'Upload icon image', 'martfury' ),
								'type'        => 'attach_image',
								'param_name'  => 'image',
								'value'       => '',
								'dependency'  => array(
									'element' => 'icon_type',
									'value'   => 'image',
								),
							),
							array(
								'heading'    => esc_html__( 'Link', 'martfury' ),
								'param_name' => 'link',
								'type'       => 'vc_link',
								'value'      => '',
							),
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'martfury' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'type'       => 'textarea',
								'heading'    => esc_html__( 'Description', 'martfury' ),
								'param_name' => 'desc',
								'value'      => '',
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'        => esc_html__( 'Icons List', 'martfury' ),
				'base'        => 'martfury_icons_list',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show list icons and titles', 'martfury' ),
				'params'      => array(
					array(
						'heading'    => esc_html__( 'Style', 'martfury' ),
						'param_name' => 'style',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Style 1', 'martfury' ) => '1',
							esc_html__( 'Style 2', 'martfury' ) => '2',
							esc_html__( 'Style 3', 'martfury' ) => '3',
						),
					),
					array(
						'heading'    => esc_html__( 'Icons', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'icons',
						'params'     => array(
							array(
								'heading'     => esc_html__( 'Icon', 'martfury' ),
								'description' => esc_html__( 'Select icon from library.', 'martfury' ),
								'type'        => 'iconpicker',
								'param_name'  => 'icon_linearicons',
								'value'       => '',
								'settings'    => array(
									'emptyIcon'    => false,
									'iconsPerPage' => 4000,
									'type'         => 'linearicons',
								),
							),
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'martfury' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__( 'Description', 'martfury' ),
								'param_name' => 'desc',
								'value'      => '',
							),
							array(
								'type'       => 'vc_link',
								'value'      => '',
								'heading'    => esc_html__( 'Link', 'martfury' ),
								'param_name' => 'link',
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// Add product categories box
		vc_map(
			array(
				'name'        => esc_html__( 'Products of Category', 'martfury' ),
				'base'        => 'martfury_products_of_category',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show multiple products in a category and banners slider.', 'martfury' ),
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'       => 'vc_link',
						'heading'    => esc_html__( 'Link', 'martfury' ),
						'param_name' => 'link',
					),
					array(
						'heading'    => esc_html__( 'Links Group', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'links_group',
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'martfury' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'type'       => 'vc_link',
								'heading'    => esc_html__( 'Link', 'martfury' ),
								'param_name' => 'link',
							),
						),
					),
					array(
						'type'       => 'vc_link',
						'heading'    => esc_html__( 'View All Link', 'martfury' ),
						'param_name' => 'all_link',
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Infinite scrolling', 'martfury' ),
						'param_name'  => 'infinite',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'true' ),
						'description' => esc_html__( 'Check this option to load content via AJAX.', 'martfury' ),
					),
					array(
						'type'        => 'attach_images',
						'heading'     => esc_html__( 'Images', 'martfury' ),
						'group'       => esc_html__( 'Slider', 'martfury' ),
						'param_name'  => 'images',
						'description' => esc_html__( 'Select images from media library', 'martfury' ),
					),
					array(
						'type'        => 'exploded_textarea_safe',
						'heading'     => esc_html__( 'Custom links', 'martfury' ),
						'group'       => esc_html__( 'Slider', 'martfury' ),
						'param_name'  => 'custom_links',
						'description' => esc_html__( 'Enter links for each slide here. Divide links with linebreaks (Enter).', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'martfury' ),
						'group'       => esc_html__( 'Slider', 'martfury' ),
						'param_name'  => 'autoplay',
						'value'       => '',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Hide Pagination', 'martfury' ),
						'group'       => esc_html__( 'Slider', 'martfury' ),
						'param_name'  => 'pagination',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
						'description' => esc_html__( 'If "YES" pagination control will be removed.', 'martfury' ),
					),
					array(
						'type'        => 'autocomplete',
						'heading'     => esc_html__( 'Product Category', 'martfury' ),
						'param_name'  => 'cat',
						'group'       => esc_html__( 'Products', 'martfury' ),
						'settings'    => array(
							'multiple' => true,
							'sortable' => false,
						),
						'save_always' => true,
						'description' => esc_html__( 'Enter a product category', 'martfury' ),
					),
					array(
						'type'        => 'autocomplete',
						'heading'     => esc_html__( 'Product Tags', 'martfury' ),
						'param_name'  => 'tags',
						'group'       => esc_html__( 'Products', 'martfury' ),
						'settings'    => array(
							'multiple' => true,
							'sortable' => false,
						),
						'save_always' => true,
						'description' => esc_html__( 'Enter a product tag', 'martfury' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Products', 'martfury' ),
						'group'      => esc_html__( 'Products', 'martfury' ),
						'param_name' => 'products',
						'value'      => array(
							esc_html__( 'Recent', 'martfury' )       => 'recent',
							esc_html__( 'Featured', 'martfury' )     => 'featured',
							esc_html__( 'Best Selling', 'martfury' ) => 'best_selling',
							esc_html__( 'Top Rated', 'martfury' )    => 'top_rated',
							esc_html__( 'On Sale', 'martfury' )      => 'sale',
						),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Product Columns', 'martfury' ),
						'group'      => esc_html__( 'Products', 'martfury' ),
						'param_name' => 'columns',
						'value'      => array(
							esc_html__( '3 Columns', 'martfury' ) => '3',
							esc_html__( '4 Columns', 'martfury' ) => '4',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Products per view', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '6',
						'param_name'  => 'per_page',
						'description' => esc_html__( 'Enter numbers of products you want to display at the same time.', 'martfury' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order By', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'orderby',
						'value'       => array(
							''                                     => '',
							esc_html__( 'Date', 'martfury' )       => 'date',
							esc_html__( 'Title', 'martfury' )      => 'title',
							esc_html__( 'Menu Order', 'martfury' ) => 'menu_order',
							esc_html__( 'Random', 'martfury' )     => 'rand',
						),
						'dependency'  => array(
							'element' => 'products',
							'value'   => array( 'recent', 'top_rated', 'sale', 'featured' ),
						),
						'description' => esc_html__( 'Select to order products. Leave empty to use the default order by of theme.', 'martfury' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'order',
						'value'       => array(
							''                                      => '',
							esc_html__( 'Ascending ', 'martfury' )  => 'asc',
							esc_html__( 'Descending ', 'martfury' ) => 'desc',
						),
						'dependency'  => array(
							'element' => 'products',
							'value'   => array( 'recent', 'top_rated', 'sale', 'featured' ),
						),
						'description' => esc_html__( 'Select to sort products. Leave empty to use the default sort of theme', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'martfury' ),
					),
				),
			)
		);

		// Add Products of Category 2
		vc_map(
			array(
				'name'        => esc_html__( 'Products of Category 2', 'martfury' ),
				'base'        => 'martfury_products_of_category_2',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show multiple products in a category, banners slider and product tabs.', 'martfury' ),
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'       => 'vc_link',
						'heading'    => esc_html__( 'Link', 'martfury' ),
						'param_name' => 'link',
					),
					array(
						'heading'     => esc_html__( 'Icon library', 'martfury' ),
						'description' => esc_html__( 'Select icon library.', 'martfury' ),
						'param_name'  => 'icon_type',
						'type'        => 'dropdown',
						'value'       => array(
							esc_html__( 'Font Awesome', 'martfury' ) => 'fontawesome',
							esc_html__( 'Linear Icons', 'martfury' ) => 'linearicons',
						),
					),
					array(
						'heading'     => esc_html__( 'Icon', 'martfury' ),
						'description' => esc_html__( 'Pick an icon from library.', 'martfury' ),
						'type'        => 'iconpicker',
						'param_name'  => 'icon_fontawesome',
						'value'       => 'fa fa-adjust',
						'settings'    => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
						),
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'fontawesome',
						),
					),
					array(
						'heading'    => esc_html__( 'Icon', 'martfury' ),
						'type'       => 'iconpicker',
						'param_name' => 'icon_linearicons',
						'settings'   => array(
							'emptyIcon'    => true,
							'type'         => 'linearicons',
							'iconsPerPage' => 4000,
						),
						'dependency' => array(
							'element' => 'icon_type',
							'value'   => 'linearicons',
						),
					),
					array(
						'heading'    => esc_html__( 'Links Group', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'links_group',
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'martfury' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'type'       => 'vc_link',
								'heading'    => esc_html__( 'Link', 'martfury' ),
								'param_name' => 'link',
							),
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Infinite scrolling', 'martfury' ),
						'param_name'  => 'infinite',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'true' ),
						'description' => esc_html__( 'Check this option to load content via AJAX.', 'martfury' ),
					),
					array(
						'type'        => 'attach_images',
						'heading'     => esc_html__( 'Images', 'martfury' ),
						'group'       => esc_html__( 'Slider', 'martfury' ),
						'param_name'  => 'images',
						'description' => esc_html__( 'Select images from media library', 'martfury' ),
					),
					array(
						'type'        => 'exploded_textarea_safe',
						'heading'     => esc_html__( 'Custom links', 'martfury' ),
						'group'       => esc_html__( 'Slider', 'martfury' ),
						'param_name'  => 'custom_links',
						'description' => esc_html__( 'Enter links for each slide here. Divide links with linebreaks (Enter).', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'martfury' ),
						'group'       => esc_html__( 'Slider', 'martfury' ),
						'param_name'  => 'autoplay',
						'value'       => '',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Hide Navigation', 'martfury' ),
						'group'       => esc_html__( 'Slider', 'martfury' ),
						'param_name'  => 'navigation',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
						'description' => esc_html__( 'If "YES" navigation control will be removed.', 'martfury' ),
					),
					array(
						'type'        => 'autocomplete',
						'heading'     => esc_html__( 'Product Category', 'martfury' ),
						'param_name'  => 'cat',
						'settings'    => array(
							'multiple' => true,
							'sortable' => false,
						),
						'save_always' => true,
						'group'       => esc_html__( 'Product Tabs', 'martfury' ),
						'description' => esc_html__( 'Enter a product category', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Products per view', 'martfury' ),
						'group'       => esc_html__( 'Product Tabs', 'martfury' ),
						'value'       => '12',
						'param_name'  => 'per_page',
						'description' => esc_html__( 'Enter numbers of products you want to display at the same time.', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'martfury' ),
						'param_name'  => 'pro_autoplay',
						'group'       => esc_html__( 'Product Tabs', 'martfury' ),
						'value'       => '',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Hide Tabs Text', 'martfury' ),
						'group'      => esc_html__( 'Product Tabs', 'martfury' ),
						'param_name' => 'hide_product_tabs',
						'value'      => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
					),
					array(
						'heading'    => esc_html__( 'Tabs Setting', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'tabs',
						'group'      => esc_html__( 'Product Tabs', 'martfury' ),
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'martfury' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__( 'Products', 'martfury' ),
								'param_name' => 'products',
								'value'      => array(
									esc_html__( 'Recent', 'martfury' )       => 'recent',
									esc_html__( 'Featured', 'martfury' )     => 'featured',
									esc_html__( 'Best Selling', 'martfury' ) => 'best_selling',
									esc_html__( 'Top Rated', 'martfury' )    => 'top_rated',
									esc_html__( 'On Sale', 'martfury' )      => 'sale',
								),
							),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'martfury' ),
						'param_name' => 'side_title',
						'value'      => '',
						'group'      => esc_html__( 'Side Products', 'martfury' ),
					),
					array(
						'type'        => 'autocomplete',
						'heading'     => esc_html__( 'Products', 'martfury' ),
						'group'       => esc_html__( 'Side Products', 'martfury' ),
						'param_name'  => 'ids',
						'settings'    => array(
							'multiple' => true,
							'sortable' => true,
						),
						'save_always' => true,
						'description' => esc_html__( 'Enter products', 'martfury' ),
					),
					array(
						'heading'     => esc_html__( 'Product Image Size', 'martfury' ),
						'param_name'  => 'image_size',
						'type'        => 'textfield',
						'value'       => 'thumbnail',
						'group'       => esc_html__( 'Side Products', 'martfury' ),
						'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'martfury' ),
					),
					array(
						'type'       => 'vc_link',
						'heading'    => esc_html__( 'View More Link', 'martfury' ),
						'group'      => esc_html__( 'Side Products', 'martfury' ),
						'param_name' => 'side_link',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'martfury' ),
					),
				),
			)
		);

		// Add products tabs
		vc_map(
			array(
				'name'        => esc_html__( 'Product Tabs', 'martfury' ),
				'base'        => 'martfury_product_tabs',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show multiple products in tabs.', 'martfury' ),
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Header Style', 'martfury' ),
						'param_name' => 'header',
						'value'      => array(
							esc_html__( 'Style 1', 'martfury' ) => '1',
							esc_html__( 'Style 2', 'martfury' ) => '2',
						),
					),
					array(
						'type'       => 'vc_link',
						'heading'    => esc_html__( 'Link', 'martfury' ),
						'param_name' => 'link',
					),
					array(
						'type'        => 'autocomplete',
						'heading'     => esc_html__( 'Product Category', 'martfury' ),
						'param_name'  => 'cat',
						'settings'    => array(
							'multiple' => true,
							'sortable' => false,
						),
						'save_always' => true,
						'description' => esc_html__( 'Enter product categories', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Total Products', 'martfury' ),
						'param_name'  => 'per_page',
						'value'       => '12',
						'description' => esc_html__( 'Set numbers of products to show.', 'martfury' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Columns', 'martfury' ),
						'param_name' => 'columns',
						'value'      => array(
							esc_html__( '5 Columns', 'martfury' ) => '5',
							esc_html__( '4 Columns', 'martfury' ) => '4',
							esc_html__( '3 Columns', 'martfury' ) => '3',
							esc_html__( '6 Columns', 'martfury' ) => '6',
							esc_html__( '7 Columns', 'martfury' ) => '7',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'martfury' ),
						'param_name'  => 'autoplay',
						'value'       => '',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Hide navigation', 'martfury' ),
						'param_name'  => 'navigation',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
						'description' => esc_html__( 'If "YES" prev / next control will be removed . ', 'martfury' ),
					),

					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Infinite scrolling', 'martfury' ),
						'param_name'  => 'infinite',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'true' ),
						'description' => esc_html__( 'Check this option to load products via AJAX.', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
					array(
						'heading'    => esc_html__( 'Tabs Setting', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'tabs',
						'group'      => esc_html__( 'Tabs', 'martfury' ),
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'martfury' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__( 'Products', 'martfury' ),
								'param_name' => 'products',
								'value'      => array(
									esc_html__( 'Recent', 'martfury' )       => 'recent',
									esc_html__( 'Featured', 'martfury' )     => 'featured',
									esc_html__( 'Best Selling', 'martfury' ) => 'best_selling',
									esc_html__( 'Top Rated', 'martfury' )    => 'top_rated',
									esc_html__( 'On Sale', 'martfury' )      => 'sale',
								),
							),
						),
					),
					array(
						'type'       => 'vc_link',
						'heading'    => esc_html__( 'View All Link', 'martfury' ),
						'param_name' => 'all_link',
						'group'      => esc_html__( 'Tabs', 'martfury' ),
					),
				),
			)
		);

		// Add Products Carousel
		vc_map(
			array(
				'name'        => esc_html__( 'Products Carousel', 'martfury' ),
				'base'        => 'martfury_products_carousel',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show multiple products with carousel.', 'martfury' ),
				'params'      => array(
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Header Style', 'martfury' ),
						'param_name' => 'header',
						'value'      => array(
							esc_html__( 'Style 1', 'martfury' ) => '1',
							esc_html__( 'Style 2', 'martfury' ) => '2',
							esc_html__( 'Style 3', 'martfury' ) => '3',
							esc_html__( 'Style 4', 'martfury' ) => '4',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'       => 'vc_link',
						'heading'    => esc_html__( 'Link', 'martfury' ),
						'param_name' => 'link',
						'dependency' => array(
							'element' => 'header',
							'value'   => array( '1', '2' ),
						),
					),
					array(
						'heading'    => esc_html__( 'Links Group', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'links_group',
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'martfury' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'type'       => 'vc_link',
								'heading'    => esc_html__( 'Link', 'martfury' ),
								'param_name' => 'link',
							),
						),
						'dependency' => array(
							'element' => 'header',
							'value'   => array( '1', '4' ),
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Infinite scrolling', 'martfury' ),
						'param_name'  => 'infinite',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'true' ),
						'description' => esc_html__( 'Check this option to load content via AJAX.', 'martfury' ),
					),
					array(
						'type'        => 'autocomplete',
						'heading'     => esc_html__( 'Product Category', 'martfury' ),
						'param_name'  => 'cat',
						'settings'    => array(
							'multiple' => true,
							'sortable' => false,
						),
						'save_always' => true,
						'group'       => esc_html__( 'Products', 'martfury' ),
						'description' => esc_html__( 'Enter a product category', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Products per view', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '12',
						'param_name'  => 'per_page',
						'description' => esc_html__( 'Enter numbers of products you want to display at the same time.', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'martfury' ),
						'param_name'  => 'autoplay',
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Hide Navigation', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'navigation',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
						'description' => esc_html__( 'If "YES" navigation control will be removed.', 'martfury' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Columns', 'martfury' ),
						'param_name' => 'columns',
						'group'      => esc_html__( 'Products', 'martfury' ),
						'value'      => array(
							esc_html__( '5 Columns', 'martfury' ) => '5',
							esc_html__( '4 Columns', 'martfury' ) => '4',
							esc_html__( '3 Columns', 'martfury' ) => '3',
							esc_html__( '6 Columns', 'martfury' ) => '6',
							esc_html__( '7 Columns', 'martfury' ) => '7',
						),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Products', 'martfury' ),
						'param_name' => 'products',
						'value'      => array(
							esc_html__( 'Recent', 'martfury' )       => 'recent',
							esc_html__( 'Featured', 'martfury' )     => 'featured',
							esc_html__( 'Best Selling', 'martfury' ) => 'best_selling',
							esc_html__( 'Top Rated', 'martfury' )    => 'top_rated',
							esc_html__( 'On Sale', 'martfury' )      => 'sale',
						),
						'group'      => esc_html__( 'Products', 'martfury' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order By', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'orderby',
						'value'       => array(
							''                                     => '',
							esc_html__( 'Date', 'martfury' )       => 'date',
							esc_html__( 'Title', 'martfury' )      => 'title',
							esc_html__( 'Menu Order', 'martfury' ) => 'menu_order',
							esc_html__( 'Random', 'martfury' )     => 'rand',
						),
						'dependency'  => array(
							'element' => 'products',
							'value'   => array( 'recent', 'top_rated', 'sale', 'featured' ),
						),
						'description' => esc_html__( 'Select to order products. Leave empty to use the default order by of theme.', 'martfury' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'order',
						'value'       => array(
							''                                      => '',
							esc_html__( 'Ascending ', 'martfury' )  => 'asc',
							esc_html__( 'Descending ', 'martfury' ) => 'desc',
						),
						'dependency'  => array(
							'element' => 'products',
							'value'   => array( 'recent', 'top_rated', 'sale', 'featured' ),
						),
						'description' => esc_html__( 'Select to sort products. Leave empty to use the default sort of theme', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'martfury' ),
					),
				),
			)
		);

		// Add Products Grid
		vc_map(
			array(
				'name'        => esc_html__( 'Products Grid', 'martfury' ),
				'base'        => 'martfury_products_grid',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show multiple products with grid.', 'martfury' ),
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Products per view', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '10',
						'param_name'  => 'per_page',
						'description' => esc_html__( 'Enter numbers of products you want to display at the same time.', 'martfury' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Columns', 'martfury' ),
						'param_name' => 'columns',
						'group'      => esc_html__( 'Products', 'martfury' ),
						'value'      => array(
							esc_html__( '5 Columns', 'martfury' ) => '5',
							esc_html__( '4 Columns', 'martfury' ) => '4',
							esc_html__( '3 Columns', 'martfury' ) => '3',
							esc_html__( '6 Columns', 'martfury' ) => '6',
						),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Products', 'martfury' ),
						'param_name' => 'products',
						'value'      => array(
							esc_html__( 'Recent', 'martfury' )       => 'recent',
							esc_html__( 'Featured', 'martfury' )     => 'featured',
							esc_html__( 'Best Selling', 'martfury' ) => 'best_selling',
							esc_html__( 'Top Rated', 'martfury' )    => 'top_rated',
							esc_html__( 'On Sale', 'martfury' )      => 'sale',
						),
						'group'      => esc_html__( 'Products', 'martfury' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order By', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'orderby',
						'value'       => array(
							''                                     => '',
							esc_html__( 'Date', 'martfury' )       => 'date',
							esc_html__( 'Title', 'martfury' )      => 'title',
							esc_html__( 'Menu Order', 'martfury' ) => 'menu_order',
							esc_html__( 'Random', 'martfury' )     => 'rand',
						),
						'dependency'  => array(
							'element' => 'products',
							'value'   => array( 'recent', 'top_rated', 'sale', 'featured' ),
						),
						'description' => esc_html__( 'Select to order products. Leave empty to use the default order by of theme.', 'martfury' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'order',
						'value'       => array(
							''                                      => '',
							esc_html__( 'Ascending ', 'martfury' )  => 'asc',
							esc_html__( 'Descending ', 'martfury' ) => 'desc',
						),
						'dependency'  => array(
							'element' => 'products',
							'value'   => array( 'recent', 'top_rated', 'sale', 'featured' ),
						),
						'description' => esc_html__( 'Select to sort products. Leave empty to use the default sort of theme', 'martfury' ),
					),
					array(
						'type'        => 'autocomplete',
						'heading'     => esc_html__( 'Product Categories', 'martfury' ),
						'param_name'  => 'cat',
						'settings'    => array(
							'multiple' => true,
							'sortable' => true,
						),
						'save_always' => true,
						'group'       => esc_html__( 'Tabs', 'martfury' ),
						'description' => esc_html__( 'Enter product categories', 'martfury' ),
					),
					array(
						'type'       => 'vc_link',
						'heading'    => esc_html__( 'View All Link', 'martfury' ),
						'param_name' => 'link',
						'group'      => esc_html__( 'Tabs', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'group'       => esc_html__( 'Products', 'martfury' ),
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'martfury' ),
					),
				),
			)
		);

		// Add Products Grid
		vc_map(
			array(
				'name'        => esc_html__( 'Products List', 'martfury' ),
				'base'        => 'martfury_products_list',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show multiple products with list.', 'martfury' ),
				'params'      => array(
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Header Style', 'martfury' ),
						'param_name' => 'header',
						'value'      => array(
							esc_html__( 'Style 1', 'martfury' ) => '1',
							esc_html__( 'Style 2', 'martfury' ) => '2',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'heading'    => esc_html__( 'Links Group', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'links_group',
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'martfury' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'type'       => 'vc_link',
								'heading'    => esc_html__( 'Link', 'martfury' ),
								'param_name' => 'link',
							),
						),
					),
					array(
						'type'        => 'autocomplete',
						'heading'     => esc_html__( 'Product Category', 'martfury' ),
						'param_name'  => 'cat',
						'settings'    => array(
							'multiple' => true,
							'sortable' => false,
						),
						'save_always' => true,
						'group'       => esc_html__( 'Products', 'martfury' ),
						'description' => esc_html__( 'Enter a product category', 'martfury' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Products', 'martfury' ),
						'param_name' => 'products',
						'value'      => array(
							esc_html__( 'Recent', 'martfury' )       => 'recent',
							esc_html__( 'Featured', 'martfury' )     => 'featured',
							esc_html__( 'Best Selling', 'martfury' ) => 'best_selling',
							esc_html__( 'Top Rated', 'martfury' )    => 'top_rated',
							esc_html__( 'On Sale', 'martfury' )      => 'sale',
						),
						'group'      => esc_html__( 'Products', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Products per view', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '6',
						'param_name'  => 'per_page',
						'description' => esc_html__( 'Enter numbers of products you want to display at the same time.', 'martfury' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Columns', 'martfury' ),
						'param_name' => 'columns',
						'group'      => esc_html__( 'Products', 'martfury' ),
						'value'      => array(
							esc_html__( '3 Columns', 'martfury' ) => '3',
							esc_html__( '4 Columns', 'martfury' ) => '4',
						),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order By', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'orderby',
						'value'       => array(
							''                                     => '',
							esc_html__( 'Date', 'martfury' )       => 'date',
							esc_html__( 'Title', 'martfury' )      => 'title',
							esc_html__( 'Menu Order', 'martfury' ) => 'menu_order',
							esc_html__( 'Random', 'martfury' )     => 'rand',
						),
						'dependency'  => array(
							'element' => 'products',
							'value'   => array( 'recent', 'top_rated', 'sale', 'featured' ),
						),
						'description' => esc_html__( 'Select to order products. Leave empty to use the default order by of theme.', 'martfury' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'order',
						'value'       => array(
							''                                      => '',
							esc_html__( 'Ascending ', 'martfury' )  => 'asc',
							esc_html__( 'Descending ', 'martfury' ) => 'desc',
						),
						'dependency'  => array(
							'element' => 'products',
							'value'   => array( 'recent', 'top_rated', 'sale', 'featured' ),
						),
						'description' => esc_html__( 'Select to sort products. Leave empty to use the default sort of theme', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'martfury' ),
					),
				),
			)
		);

		// Add Products Carousel
		vc_map(
			array(
				'name'        => esc_html__( 'Products List Carousel', 'martfury' ),
				'base'        => 'martfury_products_list_carousel',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show list of products with carousel.', 'martfury' ),
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'        => 'autocomplete',
						'heading'     => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'ids',
						'settings'    => array(
							'multiple' => true,
							'sortable' => true,
						),
						'save_always' => true,
						'group'       => esc_html__( 'Products', 'martfury' ),
						'description' => esc_html__( 'Enter products', 'martfury' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Rows', 'martfury' ),
						'param_name' => 'rows',
						'group'      => esc_html__( 'Products', 'martfury' ),
						'value'      => array(
							esc_html__( '4 Rows', 'martfury' ) => '4',
							esc_html__( '5 Rows', 'martfury' ) => '5',
							esc_html__( '3 Rows', 'martfury' ) => '3',
							esc_html__( '6 Rows', 'martfury' ) => '6',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'martfury' ),
						'param_name'  => 'autoplay',
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Hide Dots', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'dots',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
						'description' => esc_html__( 'If "YES" dots control will be removed.', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'martfury' ),
					),
				),
			)
		);

		// Add Product Deals Carousel
		vc_map(
			array(
				'name'        => esc_html__( 'Deals of the day', 'martfury' ),
				'base'        => 'martfury_deals_of_the_day',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show multiple products deals in a day.', 'martfury' ),
				'params'      => array(
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Type', 'martfury' ),
						'param_name' => 'type',
						'value'      => array(
							esc_html__( 'Carousel', 'martfury' ) => 'carousel',
							esc_html__( 'Grid', 'martfury' )     => 'grid',
						),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Style', 'martfury' ),
						'param_name' => 'style',
						'value'      => array(
							esc_html__( 'Style 1', 'martfury' ) => '1',
							esc_html__( 'Style 2', 'martfury' ) => '2',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'       => 'vc_link',
						'heading'    => esc_html__( 'Link', 'martfury' ),
						'param_name' => 'link',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Hide Ends In Text', 'martfury' ),
						'param_name' => 'ends_in_text',
						'value'      => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Products', 'martfury' ),
						'param_name' => 'product_type',
						'value'      => array(
							esc_html__( 'Deals Of The Day', 'martfury' ) => 'day',
							esc_html__( 'On Sale', 'martfury' )          => 'sale',
							esc_html__( 'Product Deals', 'martfury' )    => 'deals',
						),
						'group'      => esc_html__( 'Products', 'martfury' ),
					),
					array(
						'type'        => 'autocomplete',
						'heading'     => esc_html__( 'Product Category', 'martfury' ),
						'param_name'  => 'cat',
						'settings'    => array(
							'multiple' => true,
							'sortable' => false,
						),
						'save_always' => true,
						'group'       => esc_html__( 'Products', 'martfury' ),
						'description' => esc_html__( 'Enter a product category', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Products per view', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '12',
						'param_name'  => 'per_page',
						'description' => esc_html__( 'Enter numbers of products you want to display at the same time.', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'martfury' ),
						'param_name'  => 'autoplay',
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
						'dependency'  => array(
							'element' => 'type',
							'value'   => array( 'carousel' ),
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Hide Navigation', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'navigation',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
						'description' => esc_html__( 'If "YES" navigation control will be removed.', 'martfury' ),
						'dependency'  => array(
							'element' => 'type',
							'value'   => array( 'carousel' ),
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Show Pagination', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'pagination',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
						'description' => esc_html__( 'Check this option to show products with pagination.', 'martfury' ),
						'dependency'  => array(
							'element' => 'type',
							'value'   => array( 'grid' ),
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Hide Progress Bar', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'progress_bar',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
						'description' => esc_html__( 'Check this option to hide the progress bar.', 'martfury' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Columns', 'martfury' ),
						'param_name' => 'columns',
						'group'      => esc_html__( 'Products', 'martfury' ),
						'value'      => array(
							esc_html__( '5 Columns', 'martfury' ) => '5',
							esc_html__( '4 Columns', 'martfury' ) => '4',
							esc_html__( '3 Columns', 'martfury' ) => '3',
							esc_html__( '6 Columns', 'martfury' ) => '6',
						),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order By', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'orderby',
						'value'       => array(
							''                                     => '',
							esc_html__( 'Date', 'martfury' )       => 'date',
							esc_html__( 'Title', 'martfury' )      => 'title',
							esc_html__( 'Menu Order', 'martfury' ) => 'menu_order',
							esc_html__( 'Random', 'martfury' )     => 'rand',
						),
						'description' => esc_html__( 'Select to order products. Leave empty to use the default order by of theme.', 'martfury' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'order',
						'value'       => array(
							''                                      => '',
							esc_html__( 'Ascending ', 'martfury' )  => 'asc',
							esc_html__( 'Descending ', 'martfury' ) => 'desc',
						),
						'description' => esc_html__( 'Select to sort products. Leave empty to use the default sort of theme', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'martfury' ),
					),
				),
			)
		);

		// Add Product Deals Carousel
		vc_map(
			array(
				'name'        => esc_html__( 'Sales Countdown Timer', 'martfury' ),
				'base'        => 'martfury_sales_countdown_timer',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show multiple products deals of the day, deals of the week, deals of the month... with countdown timer.', 'martfury' ),
				'params'      => array(
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Type', 'martfury' ),
						'param_name' => 'type',
						'value'      => array(
							esc_html__( 'Carousel', 'martfury' ) => 'carousel',
							esc_html__( 'Grid', 'martfury' )     => 'grid',
						),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Style', 'martfury' ),
						'param_name' => 'style',
						'value'      => array(
							esc_html__( 'Style 1', 'martfury' ) => '1',
							esc_html__( 'Style 2', 'martfury' ) => '2',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'       => 'vc_link',
						'heading'    => esc_html__( 'Link', 'martfury' ),
						'param_name' => 'link',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Hide Ends In Text', 'martfury' ),
						'param_name' => 'ends_in_text',
						'value'      => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Products', 'martfury' ),
						'param_name' => 'product_type',
						'value'      => array(
							esc_html__( 'Deals Of The Day', 'martfury' )   => 'day',
							esc_html__( 'Deals Of The Week', 'martfury' )  => 'week',
							esc_html__( 'Deals Of The Month', 'martfury' ) => 'month',
						),
						'group'      => esc_html__( 'Products', 'martfury' ),
					),
					array(
						'type'        => 'autocomplete',
						'heading'     => esc_html__( 'Product Category', 'martfury' ),
						'param_name'  => 'cat',
						'settings'    => array(
							'multiple' => true,
							'sortable' => false,
						),
						'save_always' => true,
						'group'       => esc_html__( 'Products', 'martfury' ),
						'description' => esc_html__( 'Enter a product category', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Products per view', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '12',
						'param_name'  => 'per_page',
						'description' => esc_html__( 'Enter numbers of products you want to display at the same time.', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'martfury' ),
						'param_name'  => 'autoplay',
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
						'dependency'  => array(
							'element' => 'type',
							'value'   => array( 'carousel' ),
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Hide Navigation', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'navigation',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
						'description' => esc_html__( 'If "YES" navigation control will be removed.', 'martfury' ),
						'dependency'  => array(
							'element' => 'type',
							'value'   => array( 'carousel' ),
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Show Pagination', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'pagination',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
						'description' => esc_html__( 'Check this option to show products with pagination.', 'martfury' ),
						'dependency'  => array(
							'element' => 'type',
							'value'   => array( 'grid' ),
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Hide Progress Bar', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'progress_bar',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
						'description' => esc_html__( 'Check this option to hide the progress bar.', 'martfury' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Columns', 'martfury' ),
						'param_name' => 'columns',
						'group'      => esc_html__( 'Products', 'martfury' ),
						'value'      => array(
							esc_html__( '5 Columns', 'martfury' ) => '5',
							esc_html__( '4 Columns', 'martfury' ) => '4',
							esc_html__( '3 Columns', 'martfury' ) => '3',
							esc_html__( '6 Columns', 'martfury' ) => '6',
						),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order By', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'orderby',
						'value'       => array(
							''                                     => '',
							esc_html__( 'Date', 'martfury' )       => 'date',
							esc_html__( 'Title', 'martfury' )      => 'title',
							esc_html__( 'Menu Order', 'martfury' ) => 'menu_order',
							esc_html__( 'Random', 'martfury' )     => 'rand',
						),
						'description' => esc_html__( 'Select to order products. Leave empty to use the default order by of theme.', 'martfury' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'order',
						'value'       => array(
							''                                      => '',
							esc_html__( 'Ascending ', 'martfury' )  => 'asc',
							esc_html__( 'Descending ', 'martfury' ) => 'desc',
						),
						'description' => esc_html__( 'Select to sort products. Leave empty to use the default sort of theme', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'martfury' ),
					),
				),
			)
		);

		// Add Deals of the day
		vc_map(
			array(
				'name'        => esc_html__( 'Product Deals Carousel', 'martfury' ),
				'base'        => 'martfury_product_deals_carousel',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show multiple product deals with carousel.', 'martfury' ),
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Products Type', 'martfury' ),
						'param_name' => 'type',
						'value'      => array(
							esc_html__( 'Deals of the day', 'martfury' )   => 'day',
							esc_html__( 'Deals of the week', 'martfury' )  => 'week',
							esc_html__( 'Deals of the month', 'martfury' ) => 'month',
							esc_html__( 'On Sale', 'martfury' )            => 'sale',
							esc_html__( 'Product Deals', 'martfury' )      => 'deals',
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Hide Progress Bar', 'martfury' ),
						'param_name'  => 'progress_bar',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
						'description' => esc_html__( 'Check this option to hide the progress bar.', 'martfury' ),
					),
					array(
						'type'        => 'autocomplete',
						'heading'     => esc_html__( 'Product Category', 'martfury' ),
						'param_name'  => 'cat',
						'settings'    => array(
							'multiple' => true,
							'sortable' => false,
						),
						'save_always' => true,
						'group'       => esc_html__( 'Products', 'martfury' ),
						'description' => esc_html__( 'Enter a product category', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Number', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '4',
						'param_name'  => 'per_page',
						'description' => esc_html__( 'Enter number of products you want to display at the same time.', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'martfury' ),
						'param_name'  => 'autoplay',
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Hide Navigation', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'navigation',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
						'description' => esc_html__( 'If "YES" navigation control will be removed.', 'martfury' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order By', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'orderby',
						'value'       => array(
							''                                     => '',
							esc_html__( 'Date', 'martfury' )       => 'date',
							esc_html__( 'Title', 'martfury' )      => 'title',
							esc_html__( 'Menu Order', 'martfury' ) => 'menu_order',
							esc_html__( 'Random', 'martfury' )     => 'rand',
						),
						'description' => esc_html__( 'Select to order products. Leave empty to use the default order by of theme.', 'martfury' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'order',
						'value'       => array(
							''                                      => '',
							esc_html__( 'Ascending ', 'martfury' )  => 'asc',
							esc_html__( 'Descending ', 'martfury' ) => 'desc',
						),
						'description' => esc_html__( 'Select to sort products. Leave empty to use the default sort of theme', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'martfury' ),
					),
				),
			)
		);

		// Add Deals of the day
		vc_map(
			array(
				'name'        => esc_html__( 'Product Deals Grid', 'martfury' ),
				'base'        => 'martfury_product_deals_grid',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show multiple product deals with grid.', 'martfury' ),
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Products Type', 'martfury' ),
						'param_name' => 'type',
						'value'      => array(
							esc_html__( 'Deals of the day', 'martfury' )   => 'day',
							esc_html__( 'Deals of the week', 'martfury' )  => 'week',
							esc_html__( 'Deals of the month', 'martfury' ) => 'month',
							esc_html__( 'On Sale', 'martfury' )            => 'sale',
							esc_html__( 'Product Deals', 'martfury' )      => 'deals',
						),
					),
					array(
						'type'        => 'autocomplete',
						'heading'     => esc_html__( 'Product Category', 'martfury' ),
						'param_name'  => 'cat',
						'settings'    => array(
							'multiple' => true,
							'sortable' => false,
						),
						'save_always' => true,
						'group'       => esc_html__( 'Products', 'martfury' ),
						'description' => esc_html__( 'Enter a product category', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Number', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '12',
						'param_name'  => 'per_page',
						'description' => esc_html__( 'Enter number of products you want to display at the same time.', 'martfury' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Columns', 'martfury' ),
						'group'      => esc_html__( 'Products', 'martfury' ),
						'param_name' => 'columns',
						'value'      => array(
							esc_html__( '5 Columns', 'martfury' ) => '5',
							esc_html__( '4 Columns', 'martfury' ) => '4',
							esc_html__( '3 Columns', 'martfury' ) => '3',
							esc_html__( '6 Columns', 'martfury' ) => '6',
						),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order By', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'orderby',
						'value'       => array(
							''                                     => '',
							esc_html__( 'Date', 'martfury' )       => 'date',
							esc_html__( 'Title', 'martfury' )      => 'title',
							esc_html__( 'Menu Order', 'martfury' ) => 'menu_order',
							esc_html__( 'Random', 'martfury' )     => 'rand',
						),
						'description' => esc_html__( 'Select to order products. Leave empty to use the default order by of theme.', 'martfury' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Order', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'order',
						'value'       => array(
							''                                      => '',
							esc_html__( 'Ascending ', 'martfury' )  => 'asc',
							esc_html__( 'Descending ', 'martfury' ) => 'desc',
						),
						'description' => esc_html__( 'Select to sort products. Leave empty to use the default sort of theme', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'martfury' ),
					),
				),
			)
		);

		// Add Recently Viewed Products
		vc_map(
			array(
				'name'        => esc_html__( 'Recently Viewed Products', 'martfury' ),
				'base'        => 'martfury_recently_viewed_products',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show multiple products of your recent viewing history.', 'martfury' ),
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Products per view', 'martfury' ),
						'value'       => '12',
						'param_name'  => 'per_page',
						'description' => esc_html__( 'Enter numbers of products you want to display at the same time.', 'martfury' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Show Pagination', 'martfury' ),
						'param_name'  => 'pagination',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
						'description' => esc_html__( 'Check this option to show products with pagination.', 'martfury' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Columns', 'martfury' ),
						'param_name' => 'columns',
						'value'      => array(
							esc_html__( '5 Columns', 'martfury' ) => '5',
							esc_html__( '4 Columns', 'martfury' ) => '4',
							esc_html__( '3 Columns', 'martfury' ) => '3',
							esc_html__( '6 Columns', 'martfury' ) => '6',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'martfury' ),
					),
				),
			)
		);

		// Add Products Carousel
		vc_map(
			array(
				'name'        => esc_html__( 'Top Selling Products', 'martfury' ),
				'base'        => 'martfury_top_selling',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'List best selling products by a month, a week or a year with carousel.', 'martfury' ),
				'params'      => array(
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Header Style', 'martfury' ),
						'param_name' => 'header',
						'value'      => array(
							esc_html__( 'Style 1', 'martfury' ) => '1',
							esc_html__( 'Style 2', 'martfury' ) => '2',
							esc_html__( 'Style 3', 'martfury' ) => '3',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'heading'    => esc_html__( 'Links Group', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'links_group',
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'martfury' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'type'       => 'vc_link',
								'heading'    => esc_html__( 'Link', 'martfury' ),
								'param_name' => 'link',
							),
						),
						'dependency' => array(
							'element' => 'header',
							'value'   => array( '1' ),
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Infinite scrolling', 'martfury' ),
						'param_name'  => 'infinite',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'true' ),
						'description' => esc_html__( 'Check this option to load content via AJAX.', 'martfury' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Range', 'martfury' ),
						'param_name' => 'range',
						'value'      => array(
							esc_html__( 'Last 7 days', 'martfury' ) => '7day',
							esc_html__( 'This month', 'martfury' )  => 'month',
							esc_html__( 'Last month', 'martfury' )  => 'last_month',
							esc_html__( 'Year', 'martfury' )        => 'year',
							esc_html__( 'Custom', 'martfury' )      => 'custom',
						),
						'group'      => esc_html__( 'Products', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Start Date', 'martfury' ),
						'param_name'  => 'from',
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '',
						'description' => esc_html__( 'Enter the date by format: yyyy-mm-dd', 'martfury' ),
						'dependency'  => array(
							'element' => 'range',
							'value'   => array( 'custom' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'End Date', 'martfury' ),
						'param_name'  => 'to',
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '',
						'description' => esc_html__( 'Enter the date by format: yyyy-mm-dd', 'martfury' ),
						'dependency'  => array(
							'element' => 'range',
							'value'   => array( 'custom' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Products per view', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '12',
						'param_name'  => 'limit',
						'description' => esc_html__( 'Enter numbers of products you want to display at the same time.', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'martfury' ),
						'param_name'  => 'autoplay',
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Hide Navigation', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'navigation',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
						'description' => esc_html__( 'If "YES" navigation control will be removed.', 'martfury' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Columns', 'martfury' ),
						'param_name' => 'columns',
						'group'      => esc_html__( 'Products', 'martfury' ),
						'value'      => array(
							esc_html__( '5 Columns', 'martfury' ) => '5',
							esc_html__( '4 Columns', 'martfury' ) => '4',
							esc_html__( '3 Columns', 'martfury' ) => '3',
							esc_html__( '6 Columns', 'martfury' ) => '6',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'martfury' ),
					),
				),
			)
		);

		vc_map(
			array(
				'name'        => esc_html__( 'Top Selling Products 2', 'martfury' ),
				'base'        => 'martfury_top_selling_2',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'List best selling products by a month, a week or a year with multiple rows carousel.', 'martfury' ),
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Range', 'martfury' ),
						'param_name' => 'range',
						'value'      => array(
							esc_html__( 'Last 7 days', 'martfury' ) => '7day',
							esc_html__( 'This month', 'martfury' )  => 'month',
							esc_html__( 'Last month', 'martfury' )  => 'last_month',
							esc_html__( 'Year', 'martfury' )        => 'year',
							esc_html__( 'Custom', 'martfury' )      => 'custom',
						),
						'group'      => esc_html__( 'Products', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Start Date', 'martfury' ),
						'param_name'  => 'from',
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '',
						'description' => esc_html__( 'Enter the date by format: yyyy-mm-dd', 'martfury' ),
						'dependency'  => array(
							'element' => 'range',
							'value'   => array( 'custom' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'End Date', 'martfury' ),
						'param_name'  => 'to',
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '',
						'description' => esc_html__( 'Enter the date by format: yyyy-mm-dd', 'martfury' ),
						'dependency'  => array(
							'element' => 'range',
							'value'   => array( 'custom' ),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Products per view', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '12',
						'param_name'  => 'limit',
						'description' => esc_html__( 'Enter numbers of products you want to display at the same time.', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'martfury' ),
						'param_name'  => 'autoplay',
						'group'       => esc_html__( 'Products', 'martfury' ),
						'value'       => '',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Hide Dots', 'martfury' ),
						'group'       => esc_html__( 'Products', 'martfury' ),
						'param_name'  => 'dots',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'false' ),
						'description' => esc_html__( 'If "YES" dots control will be removed.', 'martfury' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Rows', 'martfury' ),
						'param_name' => 'rows',
						'group'      => esc_html__( 'Products', 'martfury' ),
						'value'      => array(
							esc_html__( '4 Rows', 'martfury' ) => '4',
							esc_html__( '5 Rows', 'martfury' ) => '5',
							esc_html__( '3 Rows', 'martfury' ) => '3',
							esc_html__( '6 Rows', 'martfury' ) => '6',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'martfury' ),
					),
				),
			)
		);

		// Add category tabs
		vc_map(
			array(
				'name'                    => esc_html__( 'Category Tabs', 'martfury' ),
				'base'                    => 'martfury_category_tabs',
				'as_parent'               => array( 'only' => 'martfury_category_tab' ),
				'content_element'         => true,
				'show_settings_on_create' => false,
				'is_container'            => true,
				'category'                => esc_html__( 'Martfury', 'martfury' ),
				'icon'                    => $this->get_icon(),
				'description'             => esc_html__( 'Show multiple categories in tabs.', 'martfury' ),
				'params'                  => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'SubTitle', 'martfury' ),
						'param_name' => 'subtitle',
						'value'      => '',
					),
					array(
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
						'param_name'  => 'el_class',
						'type'        => 'textfield',
						'value'       => '',
					),
				),
				'js_view'                 => 'VcColumnView',
			)
		);

		// Add category tabs
		vc_map(
			array(
				'name'                    => esc_html__( 'Category Tab', 'martfury' ),
				'base'                    => 'martfury_category_tab',
				'as_child'                => array( 'only' => 'martfury_category_tabs' ),
				'content_element'         => true,
				'show_settings_on_create' => false,
				'is_container'            => true,
				'category'                => esc_html__( 'Martfury', 'martfury' ),
				'icon'                    => $this->get_icon(),
				'params'                  => array(
					array(
						'heading'     => esc_html__( 'Icon library', 'martfury' ),
						'description' => esc_html__( 'Select icon library.', 'martfury' ),
						'param_name'  => 'icon_type',
						'type'        => 'dropdown',
						'value'       => array(
							esc_html__( 'Font Awesome', 'martfury' ) => 'fontawesome',
							esc_html__( 'Linear Icons', 'martfury' ) => 'linearicons',
							esc_html__( 'Custom Image', 'martfury' ) => 'image',
						),
					),
					array(
						'heading'     => esc_html__( 'Icon', 'martfury' ),
						'description' => esc_html__( 'Pick an icon from library.', 'martfury' ),
						'type'        => 'iconpicker',
						'param_name'  => 'icon_fontawesome',
						'value'       => 'fa fa-adjust',
						'settings'    => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
						),
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'fontawesome',
						),
					),
					array(
						'heading'    => esc_html__( 'Icon', 'martfury' ),
						'type'       => 'iconpicker',
						'param_name' => 'icon_linearicons',
						'settings'   => array(
							'emptyIcon'    => true,
							'type'         => 'linearicons',
							'iconsPerPage' => 4000,
						),
						'dependency' => array(
							'element' => 'icon_type',
							'value'   => 'linearicons',
						),
					),
					array(
						'heading'     => esc_html__( 'Icon Image', 'martfury' ),
						'description' => esc_html__( 'Upload icon image', 'martfury' ),
						'type'        => 'attach_image',
						'param_name'  => 'image',
						'value'       => '',
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'image',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'heading'    => esc_html__( 'Tags', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'tags',
						'params'     => array(
							array(
								'type'        => 'attach_image',
								'heading'     => esc_html__( 'Image', 'martfury' ),
								'param_name'  => 'image',
								'value'       => '',
								'description' => esc_html__( 'Select an image from media library', 'martfury' ),
							),
							array(
								'heading'     => esc_html__( 'Title', 'martfury' ),
								'param_name'  => 'title',
								'type'        => 'textfield',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'heading'    => esc_html__( 'Link', 'martfury' ),
								'param_name' => 'link',
								'type'       => 'vc_link',
								'value'      => '',
							),
						),
					),
					array(
						'heading'     => esc_html__( 'Image Size for Tags', 'martfury' ),
						'param_name'  => 'image_size',
						'type'        => 'textfield',
						'value'       => 'thumbnail',
						'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'martfury' ),
					),
				),
			)
		);

		// Add category tabs
		vc_map(
			array(
				'name'        => esc_html__( 'Category Box', 'martfury' ),
				'base'        => 'martfury_category_box',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show multiple categories and a banner.', 'martfury' ),
				'params'      => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'       => 'vc_link',
						'heading'    => esc_html__( 'Link', 'martfury' ),
						'param_name' => 'link',
						'value'      => '',
					),
					array(
						'heading'    => esc_html__( 'Links Group', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'links_group',
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'martfury' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'type'       => 'vc_link',
								'heading'    => esc_html__( 'Link', 'martfury' ),
								'param_name' => 'link',
							),
						),
					),
					array(
						'type'        => 'attach_image',
						'heading'     => esc_html__( 'Image', 'martfury' ),
						'param_name'  => 'image',
						'value'       => '',
						'description' => esc_html__( 'Select an image from media library', 'martfury' ),
						'group'       => esc_html__( 'Category', 'martfury' ),
					),
					array(
						'type'       => 'vc_link',
						'heading'    => esc_html__( 'Banner Link', 'martfury' ),
						'group'      => esc_html__( 'Category', 'martfury' ),
						'param_name' => 'banner_link',
						'value'      => '',
					),
					array(
						'type'        => 'autocomplete',
						'heading'     => esc_html__( 'Sub Categories', 'martfury' ),
						'param_name'  => 'cats',
						'settings'    => array(
							'multiple' => true,
							'sortable' => true,
						),
						'save_always' => true,
						'description' => esc_html__( 'Enter product categories', 'martfury' ),
						'group'       => esc_html__( 'Category', 'martfury' ),
					),
					array(
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
						'param_name'  => 'el_class',
						'type'        => 'textfield',
						'value'       => '',
					),
				),
			)
		);

		// Add category tabs
		vc_map(
			array(
				'name'        => esc_html__( 'Banners Grid', 'martfury' ),
				'base'        => 'martfury_banners_grid',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show multiple banners with grid.', 'martfury' ),
				'params'      => array(
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Revolution Slider', 'martfury' ),
						'param_name'  => 'alias',
						'value'       => $this->rev_sliders(),
						'description' => esc_html__( 'Select a Revolution Slider.', 'martfury' ),
					),
					array(
						'heading'     => esc_html__( 'Banners', 'martfury' ),
						'type'        => 'param_group',
						'value'       => '',
						'param_name'  => 'banners',
						'description' => esc_html__( 'Add banners by format: large - small - small - medium - small - small.', 'martfury' ),
						'params'      => array(
							array(
								'type'        => 'attach_image',
								'heading'     => esc_html__( 'Image', 'martfury' ),
								'param_name'  => 'image',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'heading'    => esc_html__( 'Image Size', 'martfury' ),
								'param_name' => 'image_size',
								'type'       => 'textfield',
								'value'      => 'full',
							),
							array(
								'type'       => 'vc_link',
								'heading'    => esc_html__( 'Link', 'martfury' ),
								'param_name' => 'link',
							),
						),
					),
					array(
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
						'param_name'  => 'el_class',
						'type'        => 'textfield',
						'value'       => '',
					),
				),
			)
		);

		vc_map(
			array(
				'name'        => esc_html__( 'Banners Grid 2', 'martfury' ),
				'base'        => 'martfury_banners_grid_2',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show multiple banners with grid.', 'martfury' ),
				'params'      => array(
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Revolution Slider', 'martfury' ),
						'param_name'  => 'alias',
						'value'       => $this->rev_sliders(),
						'description' => esc_html__( 'Select a Revolution Slider.', 'martfury' ),
					),
					array(
						'heading'     => esc_html__( 'Banners', 'martfury' ),
						'type'        => 'param_group',
						'value'       => '',
						'param_name'  => 'banners',
						'description' => esc_html__( 'Add banners by format: large - small - small.', 'martfury' ),
						'params'      => array(
							array(
								'type'        => 'attach_image',
								'heading'     => esc_html__( 'Image', 'martfury' ),
								'param_name'  => 'image',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'heading'    => esc_html__( 'Image Size', 'martfury' ),
								'param_name' => 'image_size',
								'type'       => 'textfield',
								'value'      => 'full',
							),
							array(
								'type'       => 'vc_link',
								'heading'    => esc_html__( 'Link', 'martfury' ),
								'param_name' => 'link',
							),
						),
					),
					array(
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
						'param_name'  => 'el_class',
						'type'        => 'textfield',
						'value'       => '',
					),
				),
			)
		);

		// Button
		vc_map(
			array(
				'name'     => esc_html__( 'Martfury Button', 'martfury' ),
				'base'     => 'martfury_button',
				'icon'     => $this->get_icon(),
				'category' => esc_html__( 'Martfury', 'martfury' ),
				'params'   => array(
					array(
						'heading'    => esc_html__( 'URL (Link)', 'martfury' ),
						'type'       => 'vc_link',
						'param_name' => 'link',
					),
					array(
						'heading'     => esc_html__( 'Button Size', 'martfury' ),
						'description' => esc_html__( 'Select button size', 'martfury' ),
						'param_name'  => 'size',
						'type'        => 'dropdown',
						'value'       => array(
							esc_html__( 'Large', 'martfury' )  => 'large',
							esc_html__( 'Medium', 'martfury' ) => 'medium',
						),
					),
					array(
						'heading'     => esc_html__( 'Button Color', 'martfury' ),
						'description' => esc_html__( 'Select button color', 'martfury' ),
						'param_name'  => 'color',
						'type'        => 'dropdown',
						'value'       => array(
							esc_html__( 'Dark', 'martfury' )  => 'dark',
							esc_html__( 'White', 'martfury' ) => 'white',
						),
					),
					array(
						'heading'     => esc_html__( 'Alignment', 'martfury' ),
						'description' => esc_html__( 'Select button alignment', 'martfury' ),
						'param_name'  => 'align',
						'type'        => 'dropdown',
						'value'       => array(
							esc_html__( 'Left', 'martfury' )   => 'left',
							esc_html__( 'Center', 'martfury' ) => 'center',
							esc_html__( 'Right', 'martfury' )  => 'right',
						),
					),
					array(
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
						'param_name'  => 'el_class',
						'type'        => 'textfield',
						'value'       => '',
					),
				),
			)
		);

		// Post Grid
		vc_map(
			array(
				'name'     => esc_html__( 'Post Grid', 'martfury' ),
				'base'     => 'martfury_post_grid',
				'class'    => '',
				'icon'     => $this->get_icon(),
				'category' => esc_html__( 'Martfury', 'martfury' ),
				'params'   => array(
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title of this Section', 'martfury' ),
						'param_name' => 'title',
						'value'      => esc_html__( 'News', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Number of Posts', 'martfury' ),
						'param_name'  => 'number',
						'value'       => '3',
						'description' => esc_html__( 'Set numbers of Posts you want to display. Set -1 to display all posts', 'martfury' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Columns', 'martfury' ),
						'param_name' => 'columns',
						'value'      => array(
							esc_html__( '3 Columns', 'martfury' ) => '3',
							esc_html__( '4 Columns', 'martfury' ) => '4',
						),
					),
					array(
						'heading'    => esc_html__( 'Links Group', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'links_group',
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'martfury' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'type'       => 'vc_link',
								'heading'    => esc_html__( 'Link', 'martfury' ),
								'param_name' => 'link',
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// Image Box
		vc_map(
			array(
				'name'        => esc_html__( 'Image Box', 'martfury' ),
				'base'        => 'martfury_image_box',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Show an image and a title', 'martfury' ),
				'params'      => array(
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Style', 'martfury' ),
						'param_name' => 'style',
						'value'      => array(
							esc_html__( 'Horizontal', 'martfury' ) => '1',
							esc_html__( 'Vertical', 'martfury' )   => '2',
						),
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'No Border', 'martfury' ),
						'param_name' => 'no_border',
						'value'      => array( esc_html__( 'Yes', 'martfury' ) => 'true' ),
					),
					array(
						'heading'    => esc_html__( 'Min Height (px)', 'martfury' ),
						'param_name' => 'box_height',
						'type'       => 'textfield',
						'value'      => '',
					),
					array(
						'type'        => 'attach_image',
						'heading'     => esc_html__( 'Image', 'martfury' ),
						'param_name'  => 'image',
						'value'       => '',
						'description' => esc_html__( 'Select an image from media library', 'martfury' ),
					),
					array(
						'heading'     => esc_html__( 'Image Size', 'martfury' ),
						'param_name'  => 'image_size',
						'type'        => 'textfield',
						'value'       => 'thumbnail',
						'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'martfury' ),
					),
					array(
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'type'        => 'textfield',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Title Style', 'martfury' ),
						'param_name' => 'title_style',
						'value'      => array(
							esc_html__( 'Style 1', 'martfury' ) => '1',
							esc_html__( 'Style 2', 'martfury' ) => '2',
						),
					),
					array(
						'heading'    => esc_html__( 'Link', 'martfury' ),
						'param_name' => 'link',
						'type'       => 'vc_link',
						'value'      => '',
					),
					array(
						'heading'    => esc_html__( 'Links Group', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'links_group',
						'params'     => array(
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'martfury' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'type'       => 'vc_link',
								'heading'    => esc_html__( 'Link', 'martfury' ),
								'param_name' => 'link',
							),
							array(
								'type'       => 'dropdown',
								'heading'    => esc_html__( 'Style', 'martfury' ),
								'param_name' => 'style',
								'value'      => array(
									esc_html__( 'Style 1', 'martfury' ) => '1',
									esc_html__( 'Style 2', 'martfury' ) => '2',
								),
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// Image Box
		vc_map(
			array(
				'name'        => esc_html__( 'Martfury Single Image', 'martfury' ),
				'base'        => 'martfury_single_image',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Simple image with lazy load', 'martfury' ),
				'params'      => array(
					array(
						'type'        => 'attach_image',
						'heading'     => esc_html__( 'Image', 'martfury' ),
						'param_name'  => 'image',
						'value'       => '',
						'description' => esc_html__( 'Select an image from media library', 'martfury' ),
						'admin_label' => true,
					),
					array(
						'heading'     => esc_html__( 'Image Size', 'martfury' ),
						'param_name'  => 'image_size',
						'type'        => 'textfield',
						'value'       => 'thumbnail',
						'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'martfury' ),
					),
					array(
						'heading'    => esc_html__( 'Link', 'martfury' ),
						'param_name' => 'link',
						'type'       => 'vc_link',
						'value'      => '',
					),
					array(
						'heading'     => esc_html__( 'Image alignment', 'martfury' ),
						'description' => esc_html__( 'Select image alignment.', 'martfury' ),
						'param_name'  => 'image_align',
						'type'        => 'dropdown',
						'value'       => array(
							esc_html__( 'Left', 'martfury' )   => 'left',
							esc_html__( 'Right', 'martfury' )  => 'right',
							esc_html__( 'Center', 'martfury' ) => 'center',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// Counter
		vc_map(
			array(
				'name'     => esc_html__( 'Counter', 'martfury' ),
				'base'     => 'martfury_counter',
				'class'    => '',
				'category' => esc_html__( 'Martfury', 'martfury' ),
				'icon'     => $this->get_icon(),
				'params'   => array(
					array(
						'heading'     => esc_html__( 'Icon library', 'martfury' ),
						'description' => esc_html__( 'Select icon library.', 'martfury' ),
						'param_name'  => 'icon_type',
						'type'        => 'dropdown',
						'value'       => array(
							esc_html__( 'Font Awesome', 'martfury' ) => 'fontawesome',
							esc_html__( 'Linear Icons', 'martfury' ) => 'linearicons',
							esc_html__( 'Custom Image', 'martfury' ) => 'image',
						),
					),
					array(
						'heading'     => esc_html__( 'Icon', 'martfury' ),
						'description' => esc_html__( 'Pick an icon from library.', 'martfury' ),
						'type'        => 'iconpicker',
						'param_name'  => 'icon_fontawesome',
						'value'       => 'fa fa-adjust',
						'settings'    => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
						),
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'fontawesome',
						),
					),
					array(
						'heading'    => esc_html__( 'Icon', 'martfury' ),
						'type'       => 'iconpicker',
						'param_name' => 'icon_linearicons',
						'settings'   => array(
							'emptyIcon'    => true,
							'type'         => 'linearicons',
							'iconsPerPage' => 4000,
						),
						'dependency' => array(
							'element' => 'icon_type',
							'value'   => 'linearicons',
						),
					),
					array(
						'heading'     => esc_html__( 'Icon Image', 'martfury' ),
						'description' => esc_html__( 'Upload icon image', 'martfury' ),
						'type'        => 'attach_image',
						'param_name'  => 'image',
						'value'       => '',
						'dependency'  => array(
							'element' => 'icon_type',
							'value'   => 'image',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Counter Value', 'martfury' ),
						'param_name'  => 'value',
						'value'       => '',
						'description' => esc_html__( 'Input integer value for counting', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Unit Before', 'martfury' ),
						'param_name'  => 'unit_before',
						'value'       => '',
						'description' => esc_html__( 'Enter the Unit. Example: +, % .etc', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Unit After', 'martfury' ),
						'param_name'  => 'unit_after',
						'value'       => '',
						'description' => esc_html__( 'Enter the Unit. Example: +, % .etc', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'description' => esc_html__( 'Enter the title of this box', 'martfury' ),
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// Journey
		vc_map(
			array(
				'name'     => esc_html__( 'Journey', 'martfury' ),
				'base'     => 'martfury_journey',
				'class'    => '',
				'category' => esc_html__( 'Martfury', 'martfury' ),
				'icon'     => $this->get_icon(),
				'params'   => array(
					array(
						array(
							'type'        => 'textfield',
							'heading'     => esc_html__( 'Min Height(px)', 'martfury' ),
							'param_name'  => 'min_height',
							'value'       => '230',
							'description' => esc_html__( 'Enter min height of content in px', 'martfury' ),
						),

						'heading'    => esc_html__( 'Journey Setting', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'journey',
						'params'     => array(
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__( 'Year', 'martfury' ),
								'param_name' => 'year',
								'value'      => '',
							),
							array(
								'type'        => 'attach_image',
								'heading'     => esc_html__( 'Image', 'martfury' ),
								'param_name'  => 'image',
								'value'       => '',
								'description' => esc_html__( 'Select an image from media library', 'martfury' ),
							),
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__( 'Image Size', 'martfury' ),
								'param_name' => 'image_size',
							),
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'martfury' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'type'       => 'textarea',
								'heading'    => esc_html__( 'Description', 'martfury' ),
								'param_name' => 'desc',
								'value'      => '',
							),
							array(
								'type'       => 'checkbox',
								'heading'    => esc_html__( 'Reverse', 'martfury' ),
								'param_name' => 'reverse',
								'value'      => array( esc_html__( 'Yes', 'martfury' ) => '1' ),
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// Testimonial
		vc_map(
			array(
				'name'     => esc_html__( 'Testimonials Slides', 'martfury' ),
				'base'     => 'martfury_testimonial_slides',
				'category' => esc_html__( 'Martfury', 'martfury' ),
				'icon'     => $this->get_icon(),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Image size', 'martfury' ),
						'param_name'  => 'image_size',
						'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'martfury' ),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Autoplay', 'martfury' ),
						'param_name'  => 'autoplay',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'yes' ),
						'description' => esc_html__( 'If "YES" Enable autoplay', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Autoplay speed', 'martfury' ),
						'param_name'  => 'autoplay_speed',
						'value'       => '1200',
						'description' => esc_html__( 'Set auto play speed (in ms).', 'martfury' ),
						'dependency'  => array(
							'element' => 'autoplay',
							'value'   => array( 'yes' ),
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Show Navigation', 'martfury' ),
						'param_name'  => 'nav',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'yes' ),
						'description' => esc_html__( 'If "YES" Enable navigation', 'martfury' ),
					),
					array(
						'heading'    => esc_html__( 'Navigation Style', 'martfury' ),
						'param_name' => 'nav_style',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Style 1', 'martfury' ) => '1',
							esc_html__( 'Style 2', 'martfury' ) => '2',
						),
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Show Dots', 'martfury' ),
						'param_name'  => 'dot',
						'value'       => array( esc_html__( 'Yes', 'martfury' ) => 'yes' ),
						'description' => esc_html__( 'If "YES" Enable dots', 'martfury' ),
					),
					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Testimonial Setting', 'martfury' ),
						'value'      => '',
						'param_name' => 'setting',
						'params'     => array(
							array(
								'heading'    => esc_html__( 'Image', 'martfury' ),
								'param_name' => 'image',
								'type'       => 'attach_image',
								'value'      => '',
							),
							array(
								'heading'    => esc_html__( 'Button Link', 'martfury' ),
								'param_name' => 'button_link',
								'type'       => 'vc_link',
								'value'      => '',
							),
							array(
								'heading'     => esc_html__( 'Name', 'martfury' ),
								'param_name'  => 'name',
								'type'        => 'textfield',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'heading'    => esc_html__( 'Job', 'martfury' ),
								'param_name' => 'job',
								'type'       => 'textfield',
								'value'      => '',
							),
							array(
								'type'       => 'textarea',
								'heading'    => esc_html__( 'Description', 'martfury' ),
								'param_name' => 'desc',
								'value'      => '',
							),
						),
					),
					array(
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'type'        => 'textfield',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// Member
		vc_map(
			array(
				'name'     => esc_html__( 'Member', 'martfury' ),
				'base'     => 'martfury_member',
				'class'    => '',
				'category' => esc_html__( 'Martfury', 'martfury' ),
				'icon'     => $this->get_icon(),
				'params'   => array(
					array(
						'type'        => 'attach_image',
						'heading'     => esc_html__( 'Image', 'martfury' ),
						'param_name'  => 'image',
						'value'       => '',
						'description' => esc_html__( 'Select an image from media library', 'martfury' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Name', 'martfury' ),
						'param_name' => 'name',
						'value'      => '',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Job', 'martfury' ),
						'param_name' => 'job',
						'value'      => '',
					),
					array(
						'heading'    => esc_html__( 'Socials', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'socials',
						'params'     => array(
							array(
								'heading'     => esc_html__( 'Icon', 'martfury' ),
								'description' => esc_html__( 'Select icon from library.', 'martfury' ),
								'type'        => 'iconpicker',
								'param_name'  => 'icon_fontawesome',
								'value'       => 'fa fa-adjust',
								'settings'    => array(
									'emptyIcon'    => false,
									'iconsPerPage' => 4000,
								),
								'dependency'  => array(
									'element' => 'icon_type',
									'value'   => 'fontawesome',
								),
							),
							array(
								'type'       => 'vc_link',
								'value'      => '',
								'heading'    => esc_html__( 'Link', 'martfury' ),
								'param_name' => 'link',
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// Process
		vc_map(
			array(
				'name'     => esc_html__( 'Process', 'martfury' ),
				'base'     => 'martfury_process',
				'class'    => '',
				'category' => esc_html__( 'Martfury', 'martfury' ),
				'icon'     => $this->get_icon(),
				'params'   => array(
					array(
						'heading'    => esc_html__( 'Process Setting', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'process',
						'params'     => array(
							array(
								'type'        => 'attach_image',
								'heading'     => esc_html__( 'Image', 'martfury' ),
								'param_name'  => 'image',
								'value'       => '',
								'description' => esc_html__( 'Select an image from media library', 'martfury' ),
							),
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Title', 'martfury' ),
								'param_name'  => 'title',
								'value'       => '',
								'admin_label' => true,
							),
							array(
								'type'       => 'textarea',
								'heading'    => esc_html__( 'Content', 'martfury' ),
								'param_name' => 'desc',
								'value'      => '',
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// Bubbles
		vc_map(
			array(
				'name'     => esc_html__( 'Bubbles', 'martfury' ),
				'base'     => 'martfury_bubbles',
				'class'    => '',
				'category' => esc_html__( 'Martfury', 'martfury' ),
				'icon'     => $this->get_icon(),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Value', 'martfury' ),
						'param_name'  => 'value',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'value'       => '',
						'admin_label' => true,
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// Partner
		vc_map(
			array(
				'name'     => esc_html__( 'Partners', 'martfury' ),
				'base'     => 'martfury_partner',
				'class'    => '',
				'category' => esc_html__( 'Martfury', 'martfury' ),
				'icon'     => $this->get_icon(),
				'params'   => array(
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Columns', 'martfury' ),
						'param_name'  => 'columns',
						'value'       => array(
							esc_html__( '5 Columns', 'martfury' ) => '5',
							esc_html__( '4 Columns', 'martfury' ) => '4',
							esc_html__( '6 Columns', 'martfury' ) => '6',
						),
						'description' => esc_html__( 'How many partner\'s columns want to display', 'martfury' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Type', 'martfury' ),
						'param_name' => 'type',
						'value'      => array(
							esc_html__( 'Normal', 'martfury' ) => 'normal',
							esc_html__( 'Slides', 'martfury' ) => 'carousel',
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Slider autoplay', 'martfury' ),
						'param_name'  => 'autoplay',
						'value'       => '',
						'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
						'dependency'  => array(
							'element' => 'type',
							'value'   => array( 'carousel' ),
						),
					),
					array(
						'type'        => 'attach_images',
						'heading'     => esc_html__( 'Images', 'martfury' ),
						'param_name'  => 'images',
						'value'       => '',
						'description' => esc_html__( 'Choose images from media library', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Image size', 'martfury' ),
						'param_name'  => 'image_size',
						'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'martfury' ),
					),
					array(
						'type'        => 'exploded_textarea_safe',
						'heading'     => esc_html__( 'Custom links', 'martfury' ),
						'param_name'  => 'custom_links',
						'description' => esc_html__( 'Enter links for each slide here. Divide links with linebreaks (Enter).', 'martfury' ),
					),
					array(
						'type'        => 'dropdown',
						'heading'     => esc_html__( 'Custom link target', 'martfury' ),
						'param_name'  => 'custom_links_target',
						'value'       => array(
							esc_html__( 'Same window', 'martfury' ) => '_self',
							esc_html__( 'New window', 'martfury' )  => '_blank',
						),
						'description' => esc_html__( 'Select where to open custom links.', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// Add newsletter shortcode
		// get form id of mailchimp
		$mail_forms    = get_posts( 'post_type=mc4wp-form&posts_per_page=-1' );
		$mail_form_ids = array(
			esc_html__( 'Select Form', 'martfury' ) => '',
		);
		foreach ( $mail_forms as $form ) {
			$mail_form_ids[ $form->post_title ] = $form->ID;
		}
		vc_map(
			array(
				'name'     => esc_html__( 'Newsletter', 'martfury' ),
				'base'     => 'martfury_newsletter',
				'class'    => '',
				'category' => esc_html__( 'Martfury', 'martfury' ),
				'icon'     => $this->get_icon(),
				'params'   => array(
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Background Colors', 'martfury' ),
						'param_name' => 'bg_color',
						'value'      => '',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Show Border', 'martfury' ),
						'param_name' => 'border',
						'value'      => array( esc_html__( 'Yes', 'martfury' ) => '1' ),
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Button', 'martfury' ),
						'param_name' => 'btn',
						'value'      => array( esc_html__( 'Show', 'martfury' ) => '1' ),
					),
					array(
						'type'       => 'dropdown',
						'heading'    => esc_html__( 'Mailchimp Form', 'martfury' ),
						'param_name' => 'form',
						'value'      => $mail_form_ids,
					),
					array(
						'heading'    => esc_html__( 'Title', 'martfury' ),
						'param_name' => 'title',
						'type'       => 'textfield',
						'value'      => '',
					),
					array(
						'type'       => 'textarea_html',
						'heading'    => esc_html__( 'Content', 'martfury' ),
						'param_name' => 'content',
						'value'      => '',
					),
					array(
						'type'        => 'attach_image',
						'heading'     => esc_html__( 'Image', 'martfury' ),
						'param_name'  => 'image',
						'value'       => '',
						'description' => esc_html__( 'Select an image from media library', 'martfury' ),
						'group'       => esc_html__( 'Image', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Image size', 'martfury' ),
						'param_name'  => 'image_size',
						'value'       => '',
						'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (W x H)).', 'martfury' ),
						'group'       => esc_html__( 'Image', 'martfury' ),
					),
					array(
						'heading'    => esc_html__( 'Image Position', 'martfury' ),
						'param_name' => 'image_position',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Left', 'martfury' )   => 'left',
							esc_html__( 'Center', 'martfury' ) => 'center',
							esc_html__( 'Right', 'martfury' )  => 'right',
						),
						'group'      => esc_html__( 'Image', 'martfury' ),
					),
					array(
						'heading'    => esc_html__( 'Button Setting', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'btn_setting',
						'params'     => array(
							array(
								'type'        => 'attach_image',
								'heading'     => esc_html__( 'Image', 'martfury' ),
								'param_name'  => 'btn_image',
								'value'       => '',
								'description' => esc_html__( 'Select an image from media library', 'martfury' ),
							),
							array(
								'heading'     => esc_html__( 'Button Link', 'martfury' ),
								'param_name'  => 'button_link',
								'type'        => 'vc_link',
								'value'       => '',
								'admin_label' => true,
							),
						),
						'group'      => esc_html__( 'Button', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// FAQs
		vc_map(
			array(
				'name'     => esc_html__( 'FAQs', 'martfury' ),
				'base'     => 'martfury_faqs',
				'category' => esc_html__( 'Martfury', 'martfury' ),
				'icon'     => $this->get_icon(),
				'class'    => '',
				'params'   => array(
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'martfury' ),
						'param_name' => 'title',
						'value'      => '',
					),
					array(
						'heading'    => esc_html__( 'FAQs', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'faqs',
						'params'     => array(
							array(
								'type'       => 'textfield',
								'heading'    => esc_html__( 'Title', 'martfury' ),
								'param_name' => 'title',
								'value'      => '',
							),
							array(
								'type'       => 'textarea',
								'value'      => '',
								'heading'    => esc_html__( 'Content', 'martfury' ),
								'param_name' => 'desc',
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// Product Brand
		vc_map(
			array(
				'name'     => esc_html__( 'Images Grid', 'martfury' ),
				'base'     => 'martfury_images_grid',
				'category' => esc_html__( 'Martfury', 'martfury' ),
				'icon'     => $this->get_icon(),
				'class'    => '',
				'params'   => array(
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Title', 'martfury' ),
						'param_name' => 'title',
						'value'      => '',
					),
					array(
						'heading'    => esc_html__( 'Images', 'martfury' ),
						'type'       => 'param_group',
						'value'      => '',
						'param_name' => 'images',
						'params'     => array(
							array(
								'type'       => 'attach_image',
								'heading'    => esc_html__( 'Image', 'martfury' ),
								'param_name' => 'image',
								'value'      => '',
							),
							array(
								'heading'    => esc_html__( 'Link', 'martfury' ),
								'param_name' => 'link',
								'type'       => 'vc_link',
								'value'      => '',
							),
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// Coming soon shortcode
		vc_map(
			array(
				'name'     => esc_html__( 'Countdown Timer', 'martfury' ),
				'base'     => 'martfury_countdown',
				'class'    => '',
				'category' => esc_html__( 'Martfury', 'martfury' ),
				'icon'     => $this->get_icon(),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Date', 'martfury' ),
						'param_name'  => 'date',
						'value'       => '',
						'description' => esc_html__( 'Enter the date by format: YYYY/MM/DD', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'value'       => '',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'martfury' ),
					),
				),
			)
		);

		// GG maps

		vc_map(
			array(
				'name'     => esc_html__( 'Google Maps', 'martfury' ),
				'base'     => 'martfury_gmap',
				'category' => esc_html__( 'Martfury', 'martfury' ),
				'icon'     => $this->get_icon(),
				'params'   => array(
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Api Key', 'martfury' ),
						'param_name'  => 'api_key',
						'value'       => '',
						'description' => sprintf( __( 'Please go to <a href="%s">Google Maps APIs</a> to get a key', 'martfury' ), esc_url( 'https://developers.google.com/maps/documentation/javascript/get-api-key#get-an-api-key' ) ),
						'group'       => esc_html__( 'Map Setting', 'martfury' ),
					),
					array(
						'type'        => 'attach_image',
						'heading'     => esc_html__( 'Marker', 'martfury' ),
						'param_name'  => 'marker',
						'value'       => '',
						'description' => esc_html__( 'Choose an image from media library', 'martfury' ),
						'group'       => esc_html__( 'Map Setting', 'martfury' ),
					),
					array(
						'type'       => 'param_group',
						'heading'    => esc_html__( 'Address Information', 'martfury' ),
						'value'      => '',
						'param_name' => 'info',
						'params'     => array(
							array(
								'type'        => 'attach_image',
								'heading'     => esc_html__( 'Location Image', 'martfury' ),
								'param_name'  => 'image',
								'value'       => '',
								'description' => esc_html__( 'Choose an image from media library', 'martfury' ),
							),
							array(
								'type'        => 'textfield',
								'heading'     => esc_html__( 'Address', 'martfury' ),
								'param_name'  => 'address',
								'admin_label' => true,
							),
							array(
								'type'       => 'textarea',
								'heading'    => esc_html__( 'Details', 'martfury' ),
								'param_name' => 'details',
							),
						),
						'group'      => esc_html__( 'Map Setting', 'martfury' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Width(px)', 'martfury' ),
						'param_name' => 'width',
						'value'      => '',
						'group'      => esc_html__( 'Map Setting', 'martfury' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Height(px)', 'martfury' ),
						'param_name' => 'height',
						'value'      => '640',
						'group'      => esc_html__( 'Map Setting', 'martfury' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Zoom', 'martfury' ),
						'param_name' => 'zoom',
						'value'      => '14',
						'group'      => esc_html__( 'Map Setting', 'martfury' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Water Colors', 'martfury' ),
						'param_name' => 'map_color',
						'value'      => '#a4c4c7',
						'group'      => esc_html__( 'Map Colors', 'martfury' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Road - HighWay Colors', 'martfury' ),
						'param_name' => 'road_highway_color',
						'value'      => '#f49555',
						'group'      => esc_html__( 'Map Colors', 'martfury' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file . ', 'martfury' ),
						'group'       => esc_html__( 'Map Setting', 'martfury' ),
					),
				),
			)
		);

		// Banner small
		vc_map(
			array(
				'name'        => esc_html__( 'Banner Small', 'martfury' ),
				'base'        => 'martfury_banner_small',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Simple image with text', 'martfury' ),
				'params'      => array(
					array(
						'type'        => 'attach_image',
						'heading'     => esc_html__( 'Image', 'martfury' ),
						'param_name'  => 'image',
						'value'       => '',
						'description' => esc_html__( 'Select an image from media library', 'martfury' ),
					),
					array(
						'heading'    => esc_html__( 'Image Type', 'martfury' ),
						'param_name' => 'image_type',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Type 1', 'martfury' ) => '1',
							esc_html__( 'Type 2', 'martfury' ) => '2',
						),
					),
					array(
						'heading'     => esc_html__( 'Image Size', 'martfury' ),
						'param_name'  => 'image_size',
						'type'        => 'textfield',
						'value'       => 'full',
						'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'martfury' ),
						'dependency'  => array(
							'element' => 'image_type',
							'value'   => array( '1' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Height(px)', 'martfury' ),
						'param_name' => 'height',
						'dependency' => array(
							'element' => 'image_type',
							'value'   => array( '2' ),
						),
					),
					array(
						'heading'    => esc_html__( 'Background Position', 'martfury' ),
						'param_name' => 'bg_position',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Left Top', 'martfury' )      => '',
							esc_html__( 'Left Center', 'martfury' )   => 'left center',
							esc_html__( 'Left Bottom', 'martfury' )   => 'left bottom',
							esc_html__( 'Right Top', 'martfury' )     => 'right top',
							esc_html__( 'Right Center', 'martfury' )  => 'right center',
							esc_html__( 'Right Bottom', 'martfury' )  => 'right bottom',
							esc_html__( 'Center Top', 'martfury' )    => 'center top',
							esc_html__( 'Center Center', 'martfury' ) => 'center center',
							esc_html__( 'Center Bottom', 'martfury' ) => 'center bottom',
						),
						'dependency' => array(
							'element' => 'image_type',
							'value'   => array( '2' ),
						),
					),
					array(
						'type'        => 'textarea',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'admin_label' => true,
					),
					array(
						'type'       => 'textarea',
						'heading'    => esc_html__( 'Description', 'martfury' ),
						'param_name' => 'content',
						'dependency' => array(
							'element' => 'image_type',
							'value'   => array( '1' ),
						),
					),
					array(
						'heading'    => esc_html__( 'Link', 'martfury' ),
						'param_name' => 'link',
						'type'       => 'vc_link',
						'value'      => '',
					),
					array(
						'type'       => 'textarea',
						'heading'    => esc_html__( 'Price', 'martfury' ),
						'param_name' => 'price',
					),
					array(
						'heading'    => esc_html__( 'Price position', 'martfury' ),
						'param_name' => 'price_position',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Position 1', 'martfury' ) => '1',
							esc_html__( 'Position 2', 'martfury' ) => '2',
							esc_html__( 'Position 3', 'martfury' ) => '3',
						),
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Border', 'martfury' ),
						'param_name' => 'border',
						'value'      => array( esc_html__( 'Yes', 'martfury' ) => '1' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Background Colors', 'martfury' ),
						'param_name' => 'bg_color',
						'value'      => '',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// Banner Medium
		vc_map(
			array(
				'name'        => esc_html__( 'Banner Medium', 'martfury' ),
				'base'        => 'martfury_banner_medium',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Simple image with text', 'martfury' ),
				'params'      => array(
					array(
						'heading'    => esc_html__( 'Layout', 'martfury' ),
						'param_name' => 'layout',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Layout 1', 'martfury' ) => '1',
							esc_html__( 'Layout 2', 'martfury' ) => '2',
							esc_html__( 'Layout 3', 'martfury' ) => '3',
							esc_html__( 'Layout 4', 'martfury' ) => '4',
							esc_html__( 'Layout 5', 'martfury' ) => '5',
						),
					),
					array(
						'type'        => 'attach_image',
						'heading'     => esc_html__( 'Image', 'martfury' ),
						'param_name'  => 'image',
						'value'       => '',
						'description' => esc_html__( 'Select an image from media library', 'martfury' ),
					),
					array(
						'heading'    => esc_html__( 'Image Type', 'martfury' ),
						'param_name' => 'image_type',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Type 1', 'martfury' ) => '1',
							esc_html__( 'Type 2', 'martfury' ) => '2',
						),
					),
					array(
						'heading'     => esc_html__( 'Image Size', 'martfury' ),
						'param_name'  => 'image_size',
						'type'        => 'textfield',
						'value'       => 'full',
						'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'martfury' ),
						'dependency'  => array(
							'element' => 'image_type',
							'value'   => array( '1' ),
						),
					),
					array(
						'heading'    => esc_html__( 'Background Position', 'martfury' ),
						'param_name' => 'bg_position',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Left Top', 'martfury' )      => '',
							esc_html__( 'Left Center', 'martfury' )   => 'left center',
							esc_html__( 'Left Bottom', 'martfury' )   => 'left bottom',
							esc_html__( 'Right Top', 'martfury' )     => 'right top',
							esc_html__( 'Right Center', 'martfury' )  => 'right center',
							esc_html__( 'Right Bottom', 'martfury' )  => 'right bottom',
							esc_html__( 'Center Top', 'martfury' )    => 'center top',
							esc_html__( 'Center Center', 'martfury' ) => 'center center',
							esc_html__( 'Center Bottom', 'martfury' ) => 'center bottom',
						),
						'dependency' => array(
							'element' => 'image_type',
							'value'   => array( '2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Height(px)', 'martfury' ),
						'param_name' => 'height',
						'dependency' => array(
							'element' => 'image_type',
							'value'   => array( '2' ),
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'SubTitle', 'martfury' ),
						'param_name' => 'subtitle',
						'dependency' => array(
							'element' => 'layout',
							'value'   => array( '1', '4' ),
						),
					),
					array(
						'type'        => 'textarea',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'title',
						'admin_label' => true,
						'dependency'  => array(
							'element' => 'layout',
							'value'   => array( '1', '2', '3', '5' ),
						),
					),
					array(
						'type'       => 'textarea',
						'heading'    => esc_html__( 'Description', 'martfury' ),
						'param_name' => 'content',
					),
					array(
						'type'       => 'textarea',
						'heading'    => esc_html__( 'Sub Description', 'martfury' ),
						'param_name' => 'subdesc',
						'dependency' => array(
							'element' => 'layout',
							'value'   => '4',
						),
					),
					array(
						'heading'    => esc_html__( 'Link', 'martfury' ),
						'param_name' => 'link',
						'type'       => 'vc_link',
						'value'      => '',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Border', 'martfury' ),
						'param_name' => 'border',
						'value'      => array( esc_html__( 'Yes', 'martfury' ) => '1' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Background Colors', 'martfury' ),
						'param_name' => 'bg_color',
						'value'      => '',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);

		// `rge
		vc_map(
			array(
				'name'        => esc_html__( 'Banner Large', 'martfury' ),
				'base'        => 'martfury_banner_large',
				'class'       => '',
				'category'    => esc_html__( 'Martfury', 'martfury' ),
				'icon'        => $this->get_icon(),
				'description' => esc_html__( 'Simple image with text', 'martfury' ),
				'params'      => array(
					array(
						'heading'    => esc_html__( 'Layout', 'martfury' ),
						'param_name' => 'layout',
						'type'       => 'dropdown',
						'value'      => array(
							esc_html__( 'Layout 1', 'martfury' ) => '1',
							esc_html__( 'Layout 2', 'martfury' ) => '2',
							esc_html__( 'Layout 3', 'martfury' ) => '3',
						),
					),
					array(
						'type'        => 'attach_image',
						'heading'     => esc_html__( 'Image', 'martfury' ),
						'param_name'  => 'image',
						'value'       => '',
						'description' => esc_html__( 'Select an image from media library', 'martfury' ),
					),
					array(
						'heading'     => esc_html__( 'Image Size', 'martfury' ),
						'param_name'  => 'image_size',
						'type'        => 'textfield',
						'value'       => 'full',
						'dependency'  => array(
							'element' => 'layout',
							'value'   => '1',
						),
						'description' => esc_html__( 'Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', 'martfury' ),
					),
					array(
						'type'        => 'textarea',
						'heading'     => esc_html__( 'Title', 'martfury' ),
						'param_name'  => 'content',
						'admin_label' => true,
					),
					array(
						'type'       => 'textarea',
						'heading'    => esc_html__( 'Description', 'martfury' ),
						'param_name' => 'desc',
					),
					array(
						'type'       => 'textarea',
						'heading'    => esc_html__( 'Price', 'martfury' ),
						'param_name' => 'price',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Sale Price', 'martfury' ),
						'param_name' => 'sale_price',
					),
					array(
						'heading'    => esc_html__( 'Link', 'martfury' ),
						'param_name' => 'link',
						'type'       => 'vc_link',
						'value'      => '',
					),
					array(
						'type'       => 'checkbox',
						'heading'    => esc_html__( 'Border', 'martfury' ),
						'param_name' => 'border',
						'value'      => array( esc_html__( 'Yes', 'martfury' ) => '1' ),
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => esc_html__( 'Background Colors', 'martfury' ),
						'param_name' => 'bg_color',
						'value'      => '',
					),
					array(
						'type'        => 'textfield',
						'heading'     => esc_html__( 'Extra class name', 'martfury' ),
						'param_name'  => 'el_class',
						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'martfury' ),
					),
				),
			)
		);
	}

	/**
	 * Get Icon URL
	 *
	 * @return string Full URL of icon image
	 */
	public function get_icon() {
		$url = MARTFURY_ADDONS_URL . '/img/default.png';

		return $url;
	}


	/**
	 * Suggester for autocomplete by slug
	 *
	 *
	 * @return array - id's from portfolio cat with title/slug.
	 */
	public function productCatsAutocompleteSuggester( $query ) {
		global $wpdb;
		$cat_id          = (int) $query;
		$query           = trim( $query );
		$post_meta_infos = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT a.term_id AS id, b.name as name, b.slug AS slug
						FROM {$wpdb->term_taxonomy} AS a
						INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
						WHERE a.taxonomy = 'product_cat' AND (a.term_id = '%d' OR b.slug LIKE '%%%s%%' OR b.name LIKE '%%%s%%' )", $cat_id > 0 ? $cat_id : - 1, stripslashes( $query ), stripslashes( $query )
			), ARRAY_A
		);

		$result = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data          = array();
				$data['value'] = $value['slug'];
				$data['label'] = esc_html__( 'Id', 'martfury' ) . ': ' . $value['id'] . ' - ' . esc_html__( 'Name', 'martfury' ) . ': ' . $value['name'];
				$result[]      = $data;
			}
		}

		return $result;
	}

	/**
	 * Find portfolio cat by slug
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function productCatsAutocompleteRender( $query ) {
		$query = $query['value'];
		$query = trim( $query );
		$term  = get_term_by( 'slug', $query, 'product_cat' );

		if ( is_wp_error( $term ) || ! $term ) {
			return false;
		}

		$data          = array();
		$data['value'] = $term->slug;
		$data['label'] = esc_html__( 'Id', 'martfury' ) . ': ' . $term->term_id . ' - ' . esc_html__( 'Name', 'martfury' ) . ': ' . $term->name;


		return $data;
	}

	/**
	 * Suggester for autocomplete by slug
	 *
	 *
	 * @return array - id's from portfolio cat with title/slug.
	 */
	public function productTagsAutocompleteSuggester( $query ) {
		global $wpdb;
		$cat_id          = (int) $query;
		$query           = trim( $query );
		$post_meta_infos = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT a.term_id AS id, b.name as name, b.slug AS slug
						FROM {$wpdb->term_taxonomy} AS a
						INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
						WHERE a.taxonomy = 'product_tag' AND (a.term_id = '%d' OR b.slug LIKE '%%%s%%' OR b.name LIKE '%%%s%%' )", $cat_id > 0 ? $cat_id : - 1, stripslashes( $query ), stripslashes( $query )
			), ARRAY_A
		);

		$result = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data          = array();
				$data['value'] = $value['slug'];
				$data['label'] = esc_html__( 'Id', 'martfury' ) . ': ' . $value['id'] . ' - ' . esc_html__( 'Name', 'martfury' ) . ': ' . $value['name'];
				$result[]      = $data;
			}
		}

		return $result;
	}

	/**
	 * Find portfolio cat by slug
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function productTagsAutocompleteRender( $query ) {
		$query = $query['value'];
		$query = trim( $query );
		$term  = get_term_by( 'slug', $query, 'product_tag' );

		if ( is_wp_error( $term ) || ! $term ) {
			return false;
		}

		$data          = array();
		$data['value'] = $term->slug;
		$data['label'] = esc_html__( 'Id', 'martfury' ) . ': ' . $term->term_id . ' - ' . esc_html__( 'Name', 'martfury' ) . ': ' . $term->name;


		return $data;
	}

	/**
	 * Find product by id
	 * @since 4.4
	 *
	 * @param $query
	 *
	 * @return bool|array
	 */
	public function productIdsAutocompleteRender(
		$query
	) {
		$query = trim( $query['value'] ); // get value from requested

		if ( empty( $query ) ) {
			return false;
		}

		$args = array(
			'post_type'              => 'product',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'ignore_sticky_posts'    => true,
			'p'                      => intval( $query ),
		);

		$query = new WP_Query( $args );
		$data  = array();
		while ( $query->have_posts() ) : $query->the_post();
			$data['value'] = get_the_ID();
			$data['label'] = esc_html__( 'Id', 'martfury' ) . ': ' . get_the_ID() . ' - ' . esc_html__( 'Title', 'martfury' ) . ': ' . get_the_title();
		endwhile;
		wp_reset_postdata();

		return $data;

	}

	function rev_sliders() {

		if ( ! class_exists( 'RevSlider' ) ) {
			return;
		}

		$slider     = new RevSlider();
		$arrSliders = $slider->getArrSliders();

		$revsliders = array();
		if ( $arrSliders ) {
			$revsliders[ esc_html__( 'Choose a slider', 'martfury' ) ] = 0;
			foreach ( $arrSliders as $slider ) {
				$revsliders[ $slider->getTitle() ] = $slider->getAlias();
			}
		} else {
			$revsliders[ esc_html__( 'No sliders found', 'martfury' ) ] = 0;
		}

		return $revsliders;
	}

	/**
	 * Suggester for autocomplete by slug
	 *
	 *
	 * @return array - id's from portfolio with title/slug.
	 */
	public function productIdsAutocompleteSuggester( $query ) {
		$args = array(
			'post_type'              => 'product',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
			'ignore_sticky_posts'    => true,
			's'                      => $query,
		);

		$query   = new WP_Query( $args );
		$results = array();
		while ( $query->have_posts() ) : $query->the_post();
			$data          = array();
			$data['value'] = get_the_ID();
			$data['label'] = esc_html__( 'Id', 'martfury' ) . ': ' . get_the_ID() . ' - ' . esc_html__( 'Title', 'martfury' ) . ': ' . get_the_title();
			$results[]     = $data;

		endwhile;
		wp_reset_postdata();

		return $results;
	}

	/**
	 * Get categories
	 *
	 * @return array|string
	 */
	function vc_iconpicker_type_linearicons( $icons ) {
		$linearicons = array(
			array( 'icon-home' => 'home' ),
			array( 'icon-home2' => 'home2' ),
			array( 'icon-home3' => 'home3' ),
			array( 'icon-home4' => 'home4' ),
			array( 'icon-home5' => 'home5' ),
			array( 'icon-home6' => 'home6' ),
			array( 'icon-bathtub' => 'bathtub' ),
			array( 'icon-toothbrush' => 'toothbrush' ),
			array( 'icon-bed' => 'bed' ),
			array( 'icon-couch' => 'couch' ),
			array( 'icon-chair' => 'chair' ),
			array( 'icon-city' => 'city' ),
			array( 'icon-apartment' => 'apartment' ),
			array( 'icon-pencil' => 'pencil' ),
			array( 'icon-pencil2' => 'pencil2' ),
			array( 'icon-pen' => 'pen' ),
			array( 'icon-pencil3' => 'pencil3' ),
			array( 'icon-eraser' => 'eraser' ),
			array( 'icon-pencil4' => 'pencil4' ),
			array( 'icon-pencil5' => 'pencil5' ),
			array( 'icon-feather' => 'feather' ),
			array( 'icon-feather2' => 'feather2' ),
			array( 'icon-feather3' => 'feather3' ),
			array( 'icon-pen2' => 'pen2' ),
			array( 'icon-pen-add' => 'pen-add' ),
			array( 'icon-pen-remove' => 'pen-remove' ),
			array( 'icon-vector' => 'vector' ),
			array( 'icon-pen3' => 'pen3' ),
			array( 'icon-blog' => 'blog' ),
			array( 'icon-brush' => 'brush' ),
			array( 'icon-brush2' => 'brush2' ),
			array( 'icon-spray' => 'spray' ),
			array( 'icon-paint-roller' => 'paint-roller' ),
			array( 'icon-stamp' => 'stamp' ),
			array( 'icon-tape' => 'tape' ),
			array( 'icon-desk-tape' => 'desk-tape' ),
			array( 'icon-texture' => 'texture' ),
			array( 'icon-eye-dropper' => 'eye-dropper' ),
			array( 'icon-palette' => 'palette' ),
			array( 'icon-color-sampler' => 'color-sampler' ),
			array( 'icon-bucket' => 'bucket' ),
			array( 'icon-gradient' => 'gradient' ),
			array( 'icon-gradient2' => 'gradient2' ),
			array( 'icon-magic-wand' => 'magic-wand' ),
			array( 'icon-magnet' => 'magnet' ),
			array( 'icon-pencil-ruler' => 'pencil-ruler' ),
			array( 'icon-pencil-ruler2' => 'pencil-ruler2' ),
			array( 'icon-compass' => 'compass' ),
			array( 'icon-aim' => 'aim' ),
			array( 'icon-gun' => 'gun' ),
			array( 'icon-bottle' => 'bottle' ),
			array( 'icon-drop' => 'drop' ),
			array( 'icon-drop-crossed' => 'drop-crossed' ),
			array( 'icon-drop2' => 'drop2' ),
			array( 'icon-snow' => 'snow' ),
			array( 'icon-snow2' => 'snow2' ),
			array( 'icon-fire' => 'fire' ),
			array( 'icon-lighter' => 'lighter' ),
			array( 'icon-knife' => 'knife' ),
			array( 'icon-dagger' => 'dagger' ),
			array( 'icon-tissue' => 'tissue' ),
			array( 'icon-toilet-paper' => 'toilet-paper' ),
			array( 'icon-poop' => 'poop' ),
			array( 'icon-umbrella' => 'umbrella' ),
			array( 'icon-umbrella2' => 'umbrella2' ),
			array( 'icon-rain' => 'rain' ),
			array( 'icon-tornado' => 'tornado' ),
			array( 'icon-wind' => 'wind' ),
			array( 'icon-fan' => 'fan' ),
			array( 'icon-contrast' => 'contrast' ),
			array( 'icon-sun-small' => 'sun-small' ),
			array( 'icon-sun' => 'sun' ),
			array( 'icon-sun2' => 'sun2' ),
			array( 'icon-moon' => 'moon' ),
			array( 'icon-cloud' => 'cloud' ),
			array( 'icon-cloud-upload' => 'cloud-upload' ),
			array( 'icon-cloud-download' => 'cloud-download' ),
			array( 'icon-cloud-rain' => 'cloud-rain' ),
			array( 'icon-cloud-hailstones' => 'cloud-hailstones' ),
			array( 'icon-cloud-snow' => 'cloud-snow' ),
			array( 'icon-cloud-windy' => 'cloud-windy' ),
			array( 'icon-sun-wind' => 'sun-wind' ),
			array( 'icon-cloud-fog' => 'cloud-fog' ),
			array( 'icon-cloud-sun' => 'cloud-sun' ),
			array( 'icon-cloud-lightning' => 'cloud-lightning' ),
			array( 'icon-cloud-sync' => 'cloud-sync' ),
			array( 'icon-cloud-lock' => 'cloud-lock' ),
			array( 'icon-cloud-gear' => 'cloud-gear' ),
			array( 'icon-cloud-alert' => 'cloud-alert' ),
			array( 'icon-cloud-check' => 'cloud-check' ),
			array( 'icon-cloud-cross' => 'cloud-cross' ),
			array( 'icon-cloud-crossed' => 'cloud-crossed' ),
			array( 'icon-cloud-database' => 'cloud-database' ),
			array( 'icon-database' => 'database' ),
			array( 'icon-database-add' => 'database-add' ),
			array( 'icon-database-remove' => 'database-remove' ),
			array( 'icon-database-lock' => 'database-lock' ),
			array( 'icon-database-refresh' => 'database-refresh' ),
			array( 'icon-database-check' => 'database-check' ),
			array( 'icon-database-history' => 'database-history' ),
			array( 'icon-database-upload' => 'database-upload' ),
			array( 'icon-database-download' => 'database-download' ),
			array( 'icon-server' => 'server' ),
			array( 'icon-shield' => 'shield' ),
			array( 'icon-shield-check' => 'shield-check' ),
			array( 'icon-shield-alert' => 'shield-alert' ),
			array( 'icon-shield-cross' => 'shield-cross' ),
			array( 'icon-lock' => 'lock' ),
			array( 'icon-rotation-lock' => 'rotation-lock' ),
			array( 'icon-unlock' => 'unlock' ),
			array( 'icon-key' => 'key' ),
			array( 'icon-key-hole' => 'key-hole' ),
			array( 'icon-toggle-off' => 'toggle-off' ),
			array( 'icon-toggle-on' => 'toggle-on' ),
			array( 'icon-cog' => 'cog' ),
			array( 'icon-cog2' => 'cog2' ),
			array( 'icon-wrench' => 'wrench' ),
			array( 'icon-screwdriver' => 'screwdriver' ),
			array( 'icon-hammer-wrench' => 'hammer-wrench' ),
			array( 'icon-hammer' => 'hammer' ),
			array( 'icon-saw' => 'saw' ),
			array( 'icon-axe' => 'axe' ),
			array( 'icon-axe2' => 'axe2' ),
			array( 'icon-shovel' => 'shovel' ),
			array( 'icon-pickaxe' => 'pickaxe' ),
			array( 'icon-factory' => 'factory' ),
			array( 'icon-factory2' => 'factory2' ),
			array( 'icon-recycle' => 'recycle' ),
			array( 'icon-trash' => 'trash' ),
			array( 'icon-trash2' => 'trash2' ),
			array( 'icon-trash3' => 'trash3' ),
			array( 'icon-broom' => 'broom' ),
			array( 'icon-game' => 'game' ),
			array( 'icon-gamepad' => 'gamepad' ),
			array( 'icon-joystick' => 'joystick' ),
			array( 'icon-dice' => 'dice' ),
			array( 'icon-spades' => 'spades' ),
			array( 'icon-diamonds' => 'diamonds' ),
			array( 'icon-clubs' => 'clubs' ),
			array( 'icon-hearts' => 'hearts' ),
			array( 'icon-heart' => 'heart' ),
			array( 'icon-star' => 'star' ),
			array( 'icon-star-half' => 'star-half' ),
			array( 'icon-star-empty' => 'star-empty' ),
			array( 'icon-flag' => 'flag' ),
			array( 'icon-flag2' => 'flag2' ),
			array( 'icon-flag3' => 'flag3' ),
			array( 'icon-mailbox-full' => 'mailbox-full' ),
			array( 'icon-mailbox-empty' => 'mailbox-empty' ),
			array( 'icon-at-sign' => 'at-sign' ),
			array( 'icon-envelope' => 'envelope' ),
			array( 'icon-envelope-open' => 'envelope-open' ),
			array( 'icon-paperclip' => 'paperclip' ),
			array( 'icon-paper-plane' => 'paper-plane' ),
			array( 'icon-reply' => 'reply' ),
			array( 'icon-reply-all' => 'reply-all' ),
			array( 'icon-inbox' => 'inbox' ),
			array( 'icon-inbox2' => 'inbox2' ),
			array( 'icon-outbox' => 'outbox' ),
			array( 'icon-box' => 'box' ),
			array( 'icon-archive' => 'archive' ),
			array( 'icon-archive2' => 'archive2' ),
			array( 'icon-drawers' => 'drawers' ),
			array( 'icon-drawers2' => 'drawers2' ),
			array( 'icon-drawers3' => 'drawers3' ),
			array( 'icon-eye' => 'eye' ),
			array( 'icon-eye-crossed' => 'eye-crossed' ),
			array( 'icon-eye-plus' => 'eye-plus' ),
			array( 'icon-eye-minus' => 'eye-minus' ),
			array( 'icon-binoculars' => 'binoculars' ),
			array( 'icon-binoculars2' => 'binoculars2' ),
			array( 'icon-hdd' => 'hdd' ),
			array( 'icon-hdd-down' => 'hdd-down' ),
			array( 'icon-hdd-up' => 'hdd-up' ),
			array( 'icon-floppy-disk' => 'floppy-disk' ),
			array( 'icon-disc' => 'disc' ),
			array( 'icon-tape2' => 'tape2' ),
			array( 'icon-printer' => 'printer' ),
			array( 'icon-shredder' => 'shredder' ),
			array( 'icon-file-empty' => 'file-empty' ),
			array( 'icon-file-add' => 'file-add' ),
			array( 'icon-file-check' => 'file-check' ),
			array( 'icon-file-lock' => 'file-lock' ),
			array( 'icon-files' => 'files' ),
			array( 'icon-copy' => 'copy' ),
			array( 'icon-compare' => 'compare' ),
			array( 'icon-folder' => 'folder' ),
			array( 'icon-folder-search' => 'folder-search' ),
			array( 'icon-folder-plus' => 'folder-plus' ),
			array( 'icon-folder-minus' => 'folder-minus' ),
			array( 'icon-folder-download' => 'folder-download' ),
			array( 'icon-folder-upload' => 'folder-upload' ),
			array( 'icon-folder-star' => 'folder-star' ),
			array( 'icon-folder-heart' => 'folder-heart' ),
			array( 'icon-folder-user' => 'folder-user' ),
			array( 'icon-folder-shared' => 'folder-shared' ),
			array( 'icon-folder-music' => 'folder-music' ),
			array( 'icon-folder-picture' => 'folder-picture' ),
			array( 'icon-folder-film' => 'folder-film' ),
			array( 'icon-scissors' => 'scissors' ),
			array( 'icon-paste' => 'paste' ),
			array( 'icon-clipboard-empty' => 'clipboard-empty' ),
			array( 'icon-clipboard-pencil' => 'clipboard-pencil' ),
			array( 'icon-clipboard-text' => 'clipboard-text' ),
			array( 'icon-clipboard-check' => 'clipboard-check' ),
			array( 'icon-clipboard-down' => 'clipboard-down' ),
			array( 'icon-clipboard-left' => 'clipboard-left' ),
			array( 'icon-clipboard-alert' => 'clipboard-alert' ),
			array( 'icon-clipboard-user' => 'clipboard-user' ),
			array( 'icon-register' => 'register' ),
			array( 'icon-enter' => 'enter' ),
			array( 'icon-exit' => 'exit' ),
			array( 'icon-papers' => 'papers' ),
			array( 'icon-news' => 'news' ),
			array( 'icon-reading' => 'reading' ),
			array( 'icon-typewriter' => 'typewriter' ),
			array( 'icon-document' => 'document' ),
			array( 'icon-document2' => 'document2' ),
			array( 'icon-graduation-hat' => 'graduation-hat' ),
			array( 'icon-license' => 'license' ),
			array( 'icon-license2' => 'license2' ),
			array( 'icon-medal-empty' => 'medal-empty' ),
			array( 'icon-medal-first' => 'medal-first' ),
			array( 'icon-medal-second' => 'medal-second' ),
			array( 'icon-medal-third' => 'medal-third' ),
			array( 'icon-podium' => 'podium' ),
			array( 'icon-trophy' => 'trophy' ),
			array( 'icon-trophy2' => 'trophy2' ),
			array( 'icon-music-note' => 'music-note' ),
			array( 'icon-music-note2' => 'music-note2' ),
			array( 'icon-music-note3' => 'music-note3' ),
			array( 'icon-playlist' => 'playlist' ),
			array( 'icon-playlist-add' => 'playlist-add' ),
			array( 'icon-guitar' => 'guitar' ),
			array( 'icon-trumpet' => 'trumpet' ),
			array( 'icon-album' => 'album' ),
			array( 'icon-shuffle' => 'shuffle' ),
			array( 'icon-repeat-one' => 'repeat-one' ),
			array( 'icon-repeat' => 'repeat' ),
			array( 'icon-headphones' => 'headphones' ),
			array( 'icon-headset' => 'headset' ),
			array( 'icon-loudspeaker' => 'loudspeaker' ),
			array( 'icon-equalizer' => 'equalizer' ),
			array( 'icon-theater' => 'theater' ),
			array( 'icon-3d-glasses' => '3d-glasses' ),
			array( 'icon-ticket' => 'ticket' ),
			array( 'icon-presentation' => 'presentation' ),
			array( 'icon-play' => 'play' ),
			array( 'icon-film-play' => 'film-play' ),
			array( 'icon-clapboard-play' => 'clapboard-play' ),
			array( 'icon-media' => 'media' ),
			array( 'icon-film' => 'film' ),
			array( 'icon-film2' => 'film2' ),
			array( 'icon-surveillance' => 'surveillance' ),
			array( 'icon-surveillance2' => 'surveillance2' ),
			array( 'icon-camera' => 'camera' ),
			array( 'icon-camera-crossed' => 'camera-crossed' ),
			array( 'icon-camera-play' => 'camera-play' ),
			array( 'icon-time-lapse' => 'time-lapse' ),
			array( 'icon-record' => 'record' ),
			array( 'icon-camera2' => 'camera2' ),
			array( 'icon-camera-flip' => 'camera-flip' ),
			array( 'icon-panorama' => 'panorama' ),
			array( 'icon-time-lapse2' => 'time-lapse2' ),
			array( 'icon-shutter' => 'shutter' ),
			array( 'icon-shutter2' => 'shutter2' ),
			array( 'icon-face-detection' => 'face-detection' ),
			array( 'icon-flare' => 'flare' ),
			array( 'icon-convex' => 'convex' ),
			array( 'icon-concave' => 'concave' ),
			array( 'icon-picture' => 'picture' ),
			array( 'icon-picture2' => 'picture2' ),
			array( 'icon-picture3' => 'picture3' ),
			array( 'icon-pictures' => 'pictures' ),
			array( 'icon-book' => 'book' ),
			array( 'icon-audio-book' => 'audio-book' ),
			array( 'icon-book2' => 'book2' ),
			array( 'icon-bookmark' => 'bookmark' ),
			array( 'icon-bookmark2' => 'bookmark2' ),
			array( 'icon-label' => 'label' ),
			array( 'icon-library' => 'library' ),
			array( 'icon-library2' => 'library2' ),
			array( 'icon-contacts' => 'contacts' ),
			array( 'icon-profile' => 'profile' ),
			array( 'icon-portrait' => 'portrait' ),
			array( 'icon-portrait2' => 'portrait2' ),
			array( 'icon-user' => 'user' ),
			array( 'icon-user-plus' => 'user-plus' ),
			array( 'icon-user-minus' => 'user-minus' ),
			array( 'icon-user-lock' => 'user-lock' ),
			array( 'icon-users' => 'users' ),
			array( 'icon-users2' => 'users2' ),
			array( 'icon-users-plus' => 'users-plus' ),
			array( 'icon-users-minus' => 'users-minus' ),
			array( 'icon-group-work' => 'group-work' ),
			array( 'icon-woman' => 'woman' ),
			array( 'icon-man' => 'man' ),
			array( 'icon-baby' => 'baby' ),
			array( 'icon-baby2' => 'baby2' ),
			array( 'icon-baby3' => 'baby3' ),
			array( 'icon-baby-bottle' => 'baby-bottle' ),
			array( 'icon-walk' => 'walk' ),
			array( 'icon-hand-waving' => 'hand-waving' ),
			array( 'icon-jump' => 'jump' ),
			array( 'icon-run' => 'run' ),
			array( 'icon-woman2' => 'woman2' ),
			array( 'icon-man2' => 'man2' ),
			array( 'icon-man-woman' => 'man-woman' ),
			array( 'icon-height' => 'height' ),
			array( 'icon-weight' => 'weight' ),
			array( 'icon-scale' => 'scale' ),
			array( 'icon-button' => 'button' ),
			array( 'icon-bow-tie' => 'bow-tie' ),
			array( 'icon-tie' => 'tie' ),
			array( 'icon-socks' => 'socks' ),
			array( 'icon-shoe' => 'shoe' ),
			array( 'icon-shoes' => 'shoes' ),
			array( 'icon-hat' => 'hat' ),
			array( 'icon-pants' => 'pants' ),
			array( 'icon-shorts' => 'shorts' ),
			array( 'icon-flip-flops' => 'flip-flops' ),
			array( 'icon-shirt' => 'shirt' ),
			array( 'icon-hanger' => 'hanger' ),
			array( 'icon-laundry' => 'laundry' ),
			array( 'icon-store' => 'store' ),
			array( 'icon-haircut' => 'haircut' ),
			array( 'icon-store-24' => 'store-24' ),
			array( 'icon-barcode' => 'barcode' ),
			array( 'icon-barcode2' => 'barcode2' ),
			array( 'icon-barcode3' => 'barcode3' ),
			array( 'icon-cashier' => 'cashier' ),
			array( 'icon-bag' => 'bag' ),
			array( 'icon-bag2' => 'bag2' ),
			array( 'icon-cart' => 'cart' ),
			array( 'icon-cart-empty' => 'cart-empty' ),
			array( 'icon-cart-full' => 'cart-full' ),
			array( 'icon-cart-plus' => 'cart-plus' ),
			array( 'icon-cart-plus2' => 'cart-plus2' ),
			array( 'icon-cart-add' => 'cart-add' ),
			array( 'icon-cart-remove' => 'cart-remove' ),
			array( 'icon-cart-exchange' => 'cart-exchange' ),
			array( 'icon-tag' => 'tag' ),
			array( 'icon-tags' => 'tags' ),
			array( 'icon-receipt' => 'receipt' ),
			array( 'icon-wallet' => 'wallet' ),
			array( 'icon-credit-card' => 'credit-card' ),
			array( 'icon-cash-dollar' => 'cash-dollar' ),
			array( 'icon-cash-euro' => 'cash-euro' ),
			array( 'icon-cash-pound' => 'cash-pound' ),
			array( 'icon-cash-yen' => 'cash-yen' ),
			array( 'icon-bag-dollar' => 'bag-dollar' ),
			array( 'icon-bag-euro' => 'bag-euro' ),
			array( 'icon-bag-pound' => 'bag-pound' ),
			array( 'icon-bag-yen' => 'bag-yen' ),
			array( 'icon-coin-dollar' => 'coin-dollar' ),
			array( 'icon-coin-euro' => 'coin-euro' ),
			array( 'icon-coin-pound' => 'coin-pound' ),
			array( 'icon-coin-yen' => 'coin-yen' ),
			array( 'icon-calculator' => 'calculator' ),
			array( 'icon-calculator2' => 'calculator2' ),
			array( 'icon-abacus' => 'abacus' ),
			array( 'icon-vault' => 'vault' ),
			array( 'icon-telephone' => 'telephone' ),
			array( 'icon-phone-lock' => 'phone-lock' ),
			array( 'icon-phone-wave' => 'phone-wave' ),
			array( 'icon-phone-pause' => 'phone-pause' ),
			array( 'icon-phone-outgoing' => 'phone-outgoing' ),
			array( 'icon-phone-incoming' => 'phone-incoming' ),
			array( 'icon-phone-in-out' => 'phone-in-out' ),
			array( 'icon-phone-error' => 'phone-error' ),
			array( 'icon-phone-sip' => 'phone-sip' ),
			array( 'icon-phone-plus' => 'phone-plus' ),
			array( 'icon-phone-minus' => 'phone-minus' ),
			array( 'icon-voicemail' => 'voicemail' ),
			array( 'icon-dial' => 'dial' ),
			array( 'icon-telephone2' => 'telephone2' ),
			array( 'icon-pushpin' => 'pushpin' ),
			array( 'icon-pushpin2' => 'pushpin2' ),
			array( 'icon-map-marker' => 'map-marker' ),
			array( 'icon-map-marker-user' => 'map-marker-user' ),
			array( 'icon-map-marker-down' => 'map-marker-down' ),
			array( 'icon-map-marker-check' => 'map-marker-check' ),
			array( 'icon-map-marker-crossed' => 'map-marker-crossed' ),
			array( 'icon-radar' => 'radar' ),
			array( 'icon-compass2' => 'compass2' ),
			array( 'icon-map' => 'map' ),
			array( 'icon-map2' => 'map2' ),
			array( 'icon-location' => 'location' ),
			array( 'icon-road-sign' => 'road-sign' ),
			array( 'icon-calendar-empty' => 'calendar-empty' ),
			array( 'icon-calendar-check' => 'calendar-check' ),
			array( 'icon-calendar-cross' => 'calendar-cross' ),
			array( 'icon-calendar-31' => 'calendar-31' ),
			array( 'icon-calendar-full' => 'calendar-full' ),
			array( 'icon-calendar-insert' => 'calendar-insert' ),
			array( 'icon-calendar-text' => 'calendar-text' ),
			array( 'icon-calendar-user' => 'calendar-user' ),
			array( 'icon-mouse' => 'mouse' ),
			array( 'icon-mouse-left' => 'mouse-left' ),
			array( 'icon-mouse-right' => 'mouse-right' ),
			array( 'icon-mouse-both' => 'mouse-both' ),
			array( 'icon-keyboard' => 'keyboard' ),
			array( 'icon-keyboard-up' => 'keyboard-up' ),
			array( 'icon-keyboard-down' => 'keyboard-down' ),
			array( 'icon-delete' => 'delete' ),
			array( 'icon-spell-check' => 'spell-check' ),
			array( 'icon-escape' => 'escape' ),
			array( 'icon-enter2' => 'enter2' ),
			array( 'icon-screen' => 'screen' ),
			array( 'icon-aspect-ratio' => 'aspect-ratio' ),
			array( 'icon-signal' => 'signal' ),
			array( 'icon-signal-lock' => 'signal-lock' ),
			array( 'icon-signal-80' => 'signal-80' ),
			array( 'icon-signal-60' => 'signal-60' ),
			array( 'icon-signal-40' => 'signal-40' ),
			array( 'icon-signal-20' => 'signal-20' ),
			array( 'icon-signal-0' => 'signal-0' ),
			array( 'icon-signal-blocked' => 'signal-blocked' ),
			array( 'icon-sim' => 'sim' ),
			array( 'icon-flash-memory' => 'flash-memory' ),
			array( 'icon-usb-drive' => 'usb-drive' ),
			array( 'icon-phone' => 'phone' ),
			array( 'icon-smartphone' => 'smartphone' ),
			array( 'icon-smartphone-notification' => 'smartphone-notification' ),
			array( 'icon-smartphone-vibration' => 'smartphone-vibration' ),
			array( 'icon-smartphone-embed' => 'smartphone-embed' ),
			array( 'icon-smartphone-waves' => 'smartphone-waves' ),
			array( 'icon-tablet' => 'tablet' ),
			array( 'icon-tablet2' => 'tablet2' ),
			array( 'icon-laptop' => 'laptop' ),
			array( 'icon-laptop-phone' => 'laptop-phone' ),
			array( 'icon-desktop' => 'desktop' ),
			array( 'icon-launch' => 'launch' ),
			array( 'icon-new-tab' => 'new-tab' ),
			array( 'icon-window' => 'window' ),
			array( 'icon-cable' => 'cable' ),
			array( 'icon-cable2' => 'cable2' ),
			array( 'icon-tv' => 'tv' ),
			array( 'icon-radio' => 'radio' ),
			array( 'icon-remote-control' => 'remote-control' ),
			array( 'icon-power-switch' => 'power-switch' ),
			array( 'icon-power' => 'power' ),
			array( 'icon-power-crossed' => 'power-crossed' ),
			array( 'icon-flash-auto' => 'flash-auto' ),
			array( 'icon-lamp' => 'lamp' ),
			array( 'icon-flashlight' => 'flashlight' ),
			array( 'icon-lampshade' => 'lampshade' ),
			array( 'icon-cord' => 'cord' ),
			array( 'icon-outlet' => 'outlet' ),
			array( 'icon-battery-power' => 'battery-power' ),
			array( 'icon-battery-empty' => 'battery-empty' ),
			array( 'icon-battery-alert' => 'battery-alert' ),
			array( 'icon-battery-error' => 'battery-error' ),
			array( 'icon-battery-low1' => 'battery-low1' ),
			array( 'icon-battery-low2' => 'battery-low2' ),
			array( 'icon-battery-low3' => 'battery-low3' ),
			array( 'icon-battery-mid1' => 'battery-mid1' ),
			array( 'icon-battery-mid2' => 'battery-mid2' ),
			array( 'icon-battery-mid3' => 'battery-mid3' ),
			array( 'icon-battery-full' => 'battery-full' ),
			array( 'icon-battery-charging' => 'battery-charging' ),
			array( 'icon-battery-charging2' => 'battery-charging2' ),
			array( 'icon-battery-charging3' => 'battery-charging3' ),
			array( 'icon-battery-charging4' => 'battery-charging4' ),
			array( 'icon-battery-charging5' => 'battery-charging5' ),
			array( 'icon-battery-charging6' => 'battery-charging6' ),
			array( 'icon-battery-charging7' => 'battery-charging7' ),
			array( 'icon-chip' => 'chip' ),
			array( 'icon-chip-x64' => 'chip-x64' ),
			array( 'icon-chip-x86' => 'chip-x86' ),
			array( 'icon-bubble' => 'bubble' ),
			array( 'icon-bubbles' => 'bubbles' ),
			array( 'icon-bubble-dots' => 'bubble-dots' ),
			array( 'icon-bubble-alert' => 'bubble-alert' ),
			array( 'icon-bubble-question' => 'bubble-question' ),
			array( 'icon-bubble-text' => 'bubble-text' ),
			array( 'icon-bubble-pencil' => 'bubble-pencil' ),
			array( 'icon-bubble-picture' => 'bubble-picture' ),
			array( 'icon-bubble-video' => 'bubble-video' ),
			array( 'icon-bubble-user' => 'bubble-user' ),
			array( 'icon-bubble-quote' => 'bubble-quote' ),
			array( 'icon-bubble-heart' => 'bubble-heart' ),
			array( 'icon-bubble-emoticon' => 'bubble-emoticon' ),
			array( 'icon-bubble-attachment' => 'bubble-attachment' ),
			array( 'icon-phone-bubble' => 'phone-bubble' ),
			array( 'icon-quote-open' => 'quote-open' ),
			array( 'icon-quote-close' => 'quote-close' ),
			array( 'icon-dna' => 'dna' ),
			array( 'icon-heart-pulse' => 'heart-pulse' ),
			array( 'icon-pulse' => 'pulse' ),
			array( 'icon-syringe' => 'syringe' ),
			array( 'icon-pills' => 'pills' ),
			array( 'icon-first-aid' => 'first-aid' ),
			array( 'icon-lifebuoy' => 'lifebuoy' ),
			array( 'icon-bandage' => 'bandage' ),
			array( 'icon-bandages' => 'bandages' ),
			array( 'icon-thermometer' => 'thermometer' ),
			array( 'icon-microscope' => 'microscope' ),
			array( 'icon-brain' => 'brain' ),
			array( 'icon-beaker' => 'beaker' ),
			array( 'icon-skull' => 'skull' ),
			array( 'icon-bone' => 'bone' ),
			array( 'icon-construction' => 'construction' ),
			array( 'icon-construction-cone' => 'construction-cone' ),
			array( 'icon-pie-chart' => 'pie-chart' ),
			array( 'icon-pie-chart2' => 'pie-chart2' ),
			array( 'icon-graph' => 'graph' ),
			array( 'icon-chart-growth' => 'chart-growth' ),
			array( 'icon-chart-bars' => 'chart-bars' ),
			array( 'icon-chart-settings' => 'chart-settings' ),
			array( 'icon-cake' => 'cake' ),
			array( 'icon-gift' => 'gift' ),
			array( 'icon-balloon' => 'balloon' ),
			array( 'icon-rank' => 'rank' ),
			array( 'icon-rank2' => 'rank2' ),
			array( 'icon-rank3' => 'rank3' ),
			array( 'icon-crown' => 'crown' ),
			array( 'icon-lotus' => 'lotus' ),
			array( 'icon-diamond' => 'diamond' ),
			array( 'icon-diamond2' => 'diamond2' ),
			array( 'icon-diamond3' => 'diamond3' ),
			array( 'icon-diamond4' => 'diamond4' ),
			array( 'icon-linearicons' => 'linearicons' ),
			array( 'icon-teacup' => 'teacup' ),
			array( 'icon-teapot' => 'teapot' ),
			array( 'icon-glass' => 'glass' ),
			array( 'icon-bottle2' => 'bottle2' ),
			array( 'icon-glass-cocktail' => 'glass-cocktail' ),
			array( 'icon-glass2' => 'glass2' ),
			array( 'icon-dinner' => 'dinner' ),
			array( 'icon-dinner2' => 'dinner2' ),
			array( 'icon-chef' => 'chef' ),
			array( 'icon-scale2' => 'scale2' ),
			array( 'icon-egg' => 'egg' ),
			array( 'icon-egg2' => 'egg2' ),
			array( 'icon-eggs' => 'eggs' ),
			array( 'icon-platter' => 'platter' ),
			array( 'icon-steak' => 'steak' ),
			array( 'icon-hamburger' => 'hamburger' ),
			array( 'icon-hotdog' => 'hotdog' ),
			array( 'icon-pizza' => 'pizza' ),
			array( 'icon-sausage' => 'sausage' ),
			array( 'icon-chicken' => 'chicken' ),
			array( 'icon-fish' => 'fish' ),
			array( 'icon-carrot' => 'carrot' ),
			array( 'icon-cheese' => 'cheese' ),
			array( 'icon-bread' => 'bread' ),
			array( 'icon-ice-cream' => 'ice-cream' ),
			array( 'icon-ice-cream2' => 'ice-cream2' ),
			array( 'icon-candy' => 'candy' ),
			array( 'icon-lollipop' => 'lollipop' ),
			array( 'icon-coffee-bean' => 'coffee-bean' ),
			array( 'icon-coffee-cup' => 'coffee-cup' ),
			array( 'icon-cherry' => 'cherry' ),
			array( 'icon-grapes' => 'grapes' ),
			array( 'icon-citrus' => 'citrus' ),
			array( 'icon-apple' => 'apple' ),
			array( 'icon-leaf' => 'leaf' ),
			array( 'icon-landscape' => 'landscape' ),
			array( 'icon-pine-tree' => 'pine-tree' ),
			array( 'icon-tree' => 'tree' ),
			array( 'icon-cactus' => 'cactus' ),
			array( 'icon-paw' => 'paw' ),
			array( 'icon-footprint' => 'footprint' ),
			array( 'icon-speed-slow' => 'speed-slow' ),
			array( 'icon-speed-medium' => 'speed-medium' ),
			array( 'icon-speed-fast' => 'speed-fast' ),
			array( 'icon-rocket' => 'rocket' ),
			array( 'icon-hammer2' => 'hammer2' ),
			array( 'icon-balance' => 'balance' ),
			array( 'icon-briefcase' => 'briefcase' ),
			array( 'icon-luggage-weight' => 'luggage-weight' ),
			array( 'icon-dolly' => 'dolly' ),
			array( 'icon-plane' => 'plane' ),
			array( 'icon-plane-crossed' => 'plane-crossed' ),
			array( 'icon-helicopter' => 'helicopter' ),
			array( 'icon-traffic-lights' => 'traffic-lights' ),
			array( 'icon-siren' => 'siren' ),
			array( 'icon-road' => 'road' ),
			array( 'icon-engine' => 'engine' ),
			array( 'icon-oil-pressure' => 'oil-pressure' ),
			array( 'icon-coolant-temperature' => 'coolant-temperature' ),
			array( 'icon-car-battery' => 'car-battery' ),
			array( 'icon-gas' => 'gas' ),
			array( 'icon-gallon' => 'gallon' ),
			array( 'icon-transmission' => 'transmission' ),
			array( 'icon-car' => 'car' ),
			array( 'icon-car-wash' => 'car-wash' ),
			array( 'icon-car-wash2' => 'car-wash2' ),
			array( 'icon-bus' => 'bus' ),
			array( 'icon-bus2' => 'bus2' ),
			array( 'icon-car2' => 'car2' ),
			array( 'icon-parking' => 'parking' ),
			array( 'icon-car-lock' => 'car-lock' ),
			array( 'icon-taxi' => 'taxi' ),
			array( 'icon-car-siren' => 'car-siren' ),
			array( 'icon-car-wash3' => 'car-wash3' ),
			array( 'icon-car-wash4' => 'car-wash4' ),
			array( 'icon-ambulance' => 'ambulance' ),
			array( 'icon-truck' => 'truck' ),
			array( 'icon-trailer' => 'trailer' ),
			array( 'icon-scale-truck' => 'scale-truck' ),
			array( 'icon-train' => 'train' ),
			array( 'icon-ship' => 'ship' ),
			array( 'icon-ship2' => 'ship2' ),
			array( 'icon-anchor' => 'anchor' ),
			array( 'icon-boat' => 'boat' ),
			array( 'icon-bicycle' => 'bicycle' ),
			array( 'icon-bicycle2' => 'bicycle2' ),
			array( 'icon-dumbbell' => 'dumbbell' ),
			array( 'icon-bench-press' => 'bench-press' ),
			array( 'icon-swim' => 'swim' ),
			array( 'icon-football' => 'football' ),
			array( 'icon-baseball-bat' => 'baseball-bat' ),
			array( 'icon-baseball' => 'baseball' ),
			array( 'icon-tennis' => 'tennis' ),
			array( 'icon-tennis2' => 'tennis2' ),
			array( 'icon-ping-pong' => 'ping-pong' ),
			array( 'icon-hockey' => 'hockey' ),
			array( 'icon-8ball' => '8ball' ),
			array( 'icon-bowling' => 'bowling' ),
			array( 'icon-bowling-pins' => 'bowling-pins' ),
			array( 'icon-golf' => 'golf' ),
			array( 'icon-golf2' => 'golf2' ),
			array( 'icon-archery' => 'archery' ),
			array( 'icon-slingshot' => 'slingshot' ),
			array( 'icon-soccer' => 'soccer' ),
			array( 'icon-basketball' => 'basketball' ),
			array( 'icon-cube' => 'cube' ),
			array( 'icon-3d-rotate' => '3d-rotate' ),
			array( 'icon-puzzle' => 'puzzle' ),
			array( 'icon-glasses' => 'glasses' ),
			array( 'icon-glasses2' => 'glasses2' ),
			array( 'icon-accessibility' => 'accessibility' ),
			array( 'icon-wheelchair' => 'wheelchair' ),
			array( 'icon-wall' => 'wall' ),
			array( 'icon-fence' => 'fence' ),
			array( 'icon-wall2' => 'wall2' ),
			array( 'icon-icons' => 'icons' ),
			array( 'icon-resize-handle' => 'resize-handle' ),
			array( 'icon-icons2' => 'icons2' ),
			array( 'icon-select' => 'select' ),
			array( 'icon-select2' => 'select2' ),
			array( 'icon-site-map' => 'site-map' ),
			array( 'icon-earth' => 'earth' ),
			array( 'icon-earth-lock' => 'earth-lock' ),
			array( 'icon-network' => 'network' ),
			array( 'icon-network-lock' => 'network-lock' ),
			array( 'icon-planet' => 'planet' ),
			array( 'icon-happy' => 'happy' ),
			array( 'icon-smile' => 'smile' ),
			array( 'icon-grin' => 'grin' ),
			array( 'icon-tongue' => 'tongue' ),
			array( 'icon-sad' => 'sad' ),
			array( 'icon-wink' => 'wink' ),
			array( 'icon-dream' => 'dream' ),
			array( 'icon-shocked' => 'shocked' ),
			array( 'icon-shocked2' => 'shocked2' ),
			array( 'icon-tongue2' => 'tongue2' ),
			array( 'icon-neutral' => 'neutral' ),
			array( 'icon-happy-grin' => 'happy-grin' ),
			array( 'icon-cool' => 'cool' ),
			array( 'icon-mad' => 'mad' ),
			array( 'icon-grin-evil' => 'grin-evil' ),
			array( 'icon-evil' => 'evil' ),
			array( 'icon-wow' => 'wow' ),
			array( 'icon-annoyed' => 'annoyed' ),
			array( 'icon-wondering' => 'wondering' ),
			array( 'icon-confused' => 'confused' ),
			array( 'icon-zipped' => 'zipped' ),
			array( 'icon-grumpy' => 'grumpy' ),
			array( 'icon-mustache' => 'mustache' ),
			array( 'icon-tombstone-hipster' => 'tombstone-hipster' ),
			array( 'icon-tombstone' => 'tombstone' ),
			array( 'icon-ghost' => 'ghost' ),
			array( 'icon-ghost-hipster' => 'ghost-hipster' ),
			array( 'icon-halloween' => 'halloween' ),
			array( 'icon-christmas' => 'christmas' ),
			array( 'icon-easter-egg' => 'easter-egg' ),
			array( 'icon-mustache2' => 'mustache2' ),
			array( 'icon-mustache-glasses' => 'mustache-glasses' ),
			array( 'icon-pipe' => 'pipe' ),
			array( 'icon-alarm' => 'alarm' ),
			array( 'icon-alarm-add' => 'alarm-add' ),
			array( 'icon-alarm-snooze' => 'alarm-snooze' ),
			array( 'icon-alarm-ringing' => 'alarm-ringing' ),
			array( 'icon-bullhorn' => 'bullhorn' ),
			array( 'icon-hearing' => 'hearing' ),
			array( 'icon-volume-high' => 'volume-high' ),
			array( 'icon-volume-medium' => 'volume-medium' ),
			array( 'icon-volume-low' => 'volume-low' ),
			array( 'icon-volume' => 'volume' ),
			array( 'icon-mute' => 'mute' ),
			array( 'icon-lan' => 'lan' ),
			array( 'icon-lan2' => 'lan2' ),
			array( 'icon-wifi' => 'wifi' ),
			array( 'icon-wifi-lock' => 'wifi-lock' ),
			array( 'icon-wifi-blocked' => 'wifi-blocked' ),
			array( 'icon-wifi-mid' => 'wifi-mid' ),
			array( 'icon-wifi-low' => 'wifi-low' ),
			array( 'icon-wifi-low2' => 'wifi-low2' ),
			array( 'icon-wifi-alert' => 'wifi-alert' ),
			array( 'icon-wifi-alert-mid' => 'wifi-alert-mid' ),
			array( 'icon-wifi-alert-low' => 'wifi-alert-low' ),
			array( 'icon-wifi-alert-low2' => 'wifi-alert-low2' ),
			array( 'icon-stream' => 'stream' ),
			array( 'icon-stream-check' => 'stream-check' ),
			array( 'icon-stream-error' => 'stream-error' ),
			array( 'icon-stream-alert' => 'stream-alert' ),
			array( 'icon-communication' => 'communication' ),
			array( 'icon-communication-crossed' => 'communication-crossed' ),
			array( 'icon-broadcast' => 'broadcast' ),
			array( 'icon-antenna' => 'antenna' ),
			array( 'icon-satellite' => 'satellite' ),
			array( 'icon-satellite2' => 'satellite2' ),
			array( 'icon-mic' => 'mic' ),
			array( 'icon-mic-mute' => 'mic-mute' ),
			array( 'icon-mic2' => 'mic2' ),
			array( 'icon-spotlights' => 'spotlights' ),
			array( 'icon-hourglass' => 'hourglass' ),
			array( 'icon-loading' => 'loading' ),
			array( 'icon-loading2' => 'loading2' ),
			array( 'icon-loading3' => 'loading3' ),
			array( 'icon-refresh' => 'refresh' ),
			array( 'icon-refresh2' => 'refresh2' ),
			array( 'icon-undo' => 'undo' ),
			array( 'icon-redo' => 'redo' ),
			array( 'icon-jump2' => 'jump2' ),
			array( 'icon-undo2' => 'undo2' ),
			array( 'icon-redo2' => 'redo2' ),
			array( 'icon-sync' => 'sync' ),
			array( 'icon-repeat-one2' => 'repeat-one2' ),
			array( 'icon-sync-crossed' => 'sync-crossed' ),
			array( 'icon-sync2' => 'sync2' ),
			array( 'icon-repeat-one3' => 'repeat-one3' ),
			array( 'icon-sync-crossed2' => 'sync-crossed2' ),
			array( 'icon-return' => 'return' ),
			array( 'icon-return2' => 'return2' ),
			array( 'icon-refund' => 'refund' ),
			array( 'icon-history' => 'history' ),
			array( 'icon-history2' => 'history2' ),
			array( 'icon-self-timer' => 'self-timer' ),
			array( 'icon-clock' => 'clock' ),
			array( 'icon-clock2' => 'clock2' ),
			array( 'icon-clock3' => 'clock3' ),
			array( 'icon-watch' => 'watch' ),
			array( 'icon-alarm2' => 'alarm2' ),
			array( 'icon-alarm-add2' => 'alarm-add2' ),
			array( 'icon-alarm-remove' => 'alarm-remove' ),
			array( 'icon-alarm-check' => 'alarm-check' ),
			array( 'icon-alarm-error' => 'alarm-error' ),
			array( 'icon-timer' => 'timer' ),
			array( 'icon-timer-crossed' => 'timer-crossed' ),
			array( 'icon-timer2' => 'timer2' ),
			array( 'icon-timer-crossed2' => 'timer-crossed2' ),
			array( 'icon-download' => 'download' ),
			array( 'icon-upload' => 'upload' ),
			array( 'icon-download2' => 'download2' ),
			array( 'icon-upload2' => 'upload2' ),
			array( 'icon-enter-up' => 'enter-up' ),
			array( 'icon-enter-down' => 'enter-down' ),
			array( 'icon-enter-left' => 'enter-left' ),
			array( 'icon-enter-right' => 'enter-right' ),
			array( 'icon-exit-up' => 'exit-up' ),
			array( 'icon-exit-down' => 'exit-down' ),
			array( 'icon-exit-left' => 'exit-left' ),
			array( 'icon-exit-right' => 'exit-right' ),
			array( 'icon-enter-up2' => 'enter-up2' ),
			array( 'icon-enter-down2' => 'enter-down2' ),
			array( 'icon-enter-vertical' => 'enter-vertical' ),
			array( 'icon-enter-left2' => 'enter-left2' ),
			array( 'icon-enter-right2' => 'enter-right2' ),
			array( 'icon-enter-horizontal' => 'enter-horizontal' ),
			array( 'icon-exit-up2' => 'exit-up2' ),
			array( 'icon-exit-down2' => 'exit-down2' ),
			array( 'icon-exit-left2' => 'exit-left2' ),
			array( 'icon-exit-right2' => 'exit-right2' ),
			array( 'icon-cli' => 'cli' ),
			array( 'icon-bug' => 'bug' ),
			array( 'icon-code' => 'code' ),
			array( 'icon-file-code' => 'file-code' ),
			array( 'icon-file-image' => 'file-image' ),
			array( 'icon-file-zip' => 'file-zip' ),
			array( 'icon-file-audio' => 'file-audio' ),
			array( 'icon-file-video' => 'file-video' ),
			array( 'icon-file-preview' => 'file-preview' ),
			array( 'icon-file-charts' => 'file-charts' ),
			array( 'icon-file-stats' => 'file-stats' ),
			array( 'icon-file-spreadsheet' => 'file-spreadsheet' ),
			array( 'icon-link' => 'link' ),
			array( 'icon-unlink' => 'unlink' ),
			array( 'icon-link2' => 'link2' ),
			array( 'icon-unlink2' => 'unlink2' ),
			array( 'icon-thumbs-up' => 'thumbs-up' ),
			array( 'icon-thumbs-down' => 'thumbs-down' ),
			array( 'icon-thumbs-up2' => 'thumbs-up2' ),
			array( 'icon-thumbs-down2' => 'thumbs-down2' ),
			array( 'icon-thumbs-up3' => 'thumbs-up3' ),
			array( 'icon-thumbs-down3' => 'thumbs-down3' ),
			array( 'icon-share' => 'share' ),
			array( 'icon-share2' => 'share2' ),
			array( 'icon-share3' => 'share3' ),
			array( 'icon-magnifier' => 'magnifier' ),
			array( 'icon-file-search' => 'file-search' ),
			array( 'icon-find-replace' => 'find-replace' ),
			array( 'icon-zoom-in' => 'zoom-in' ),
			array( 'icon-zoom-out' => 'zoom-out' ),
			array( 'icon-loupe' => 'loupe' ),
			array( 'icon-loupe-zoom-in' => 'loupe-zoom-in' ),
			array( 'icon-loupe-zoom-out' => 'loupe-zoom-out' ),
			array( 'icon-cross' => 'cross' ),
			array( 'icon-menu' => 'menu' ),
			array( 'icon-list' => 'list' ),
			array( 'icon-list2' => 'list2' ),
			array( 'icon-list3' => 'list3' ),
			array( 'icon-menu2' => 'menu2' ),
			array( 'icon-list4' => 'list4' ),
			array( 'icon-menu3' => 'menu3' ),
			array( 'icon-exclamation' => 'exclamation' ),
			array( 'icon-question' => 'question' ),
			array( 'icon-check' => 'check' ),
			array( 'icon-cross2' => 'cross2' ),
			array( 'icon-plus' => 'plus' ),
			array( 'icon-minus' => 'minus' ),
			array( 'icon-percent' => 'percent' ),
			array( 'icon-chevron-up' => 'chevron-up' ),
			array( 'icon-chevron-down' => 'chevron-down' ),
			array( 'icon-chevron-left' => 'chevron-left' ),
			array( 'icon-chevron-right' => 'chevron-right' ),
			array( 'icon-chevrons-expand-vertical' => 'chevrons-expand-vertical' ),
			array( 'icon-chevrons-expand-horizontal' => 'chevrons-expand-horizontal' ),
			array( 'icon-chevrons-contract-vertical' => 'chevrons-contract-vertical' ),
			array( 'icon-chevrons-contract-horizontal' => 'chevrons-contract-horizontal' ),
			array( 'icon-arrow-up' => 'arrow-up' ),
			array( 'icon-arrow-down' => 'arrow-down' ),
			array( 'icon-arrow-left' => 'arrow-left' ),
			array( 'icon-arrow-right' => 'arrow-right' ),
			array( 'icon-arrow-up-right' => 'arrow-up-right' ),
			array( 'icon-arrows-merge' => 'arrows-merge' ),
			array( 'icon-arrows-split' => 'arrows-split' ),
			array( 'icon-arrow-divert' => 'arrow-divert' ),
			array( 'icon-arrow-return' => 'arrow-return' ),
			array( 'icon-expand' => 'expand' ),
			array( 'icon-contract' => 'contract' ),
			array( 'icon-expand2' => 'expand2' ),
			array( 'icon-contract2' => 'contract2' ),
			array( 'icon-move' => 'move' ),
			array( 'icon-tab' => 'tab' ),
			array( 'icon-arrow-wave' => 'arrow-wave' ),
			array( 'icon-expand3' => 'expand3' ),
			array( 'icon-expand4' => 'expand4' ),
			array( 'icon-contract3' => 'contract3' ),
			array( 'icon-notification' => 'notification' ),
			array( 'icon-warning' => 'warning' ),
			array( 'icon-notification-circle' => 'notification-circle' ),
			array( 'icon-question-circle' => 'question-circle' ),
			array( 'icon-menu-circle' => 'menu-circle' ),
			array( 'icon-checkmark-circle' => 'checkmark-circle' ),
			array( 'icon-cross-circle' => 'cross-circle' ),
			array( 'icon-plus-circle' => 'plus-circle' ),
			array( 'icon-circle-minus' => 'circle-minus' ),
			array( 'icon-percent-circle' => 'percent-circle' ),
			array( 'icon-arrow-up-circle' => 'arrow-up-circle' ),
			array( 'icon-arrow-down-circle' => 'arrow-down-circle' ),
			array( 'icon-arrow-left-circle' => 'arrow-left-circle' ),
			array( 'icon-arrow-right-circle' => 'arrow-right-circle' ),
			array( 'icon-chevron-up-circle' => 'chevron-up-circle' ),
			array( 'icon-chevron-down-circle' => 'chevron-down-circle' ),
			array( 'icon-chevron-left-circle' => 'chevron-left-circle' ),
			array( 'icon-chevron-right-circle' => 'chevron-right-circle' ),
			array( 'icon-backward-circle' => 'backward-circle' ),
			array( 'icon-first-circle' => 'first-circle' ),
			array( 'icon-previous-circle' => 'previous-circle' ),
			array( 'icon-stop-circle' => 'stop-circle' ),
			array( 'icon-play-circle' => 'play-circle' ),
			array( 'icon-pause-circle' => 'pause-circle' ),
			array( 'icon-next-circle' => 'next-circle' ),
			array( 'icon-last-circle' => 'last-circle' ),
			array( 'icon-forward-circle' => 'forward-circle' ),
			array( 'icon-eject-circle' => 'eject-circle' ),
			array( 'icon-crop' => 'crop' ),
			array( 'icon-frame-expand' => 'frame-expand' ),
			array( 'icon-frame-contract' => 'frame-contract' ),
			array( 'icon-focus' => 'focus' ),
			array( 'icon-transform' => 'transform' ),
			array( 'icon-grid' => 'grid' ),
			array( 'icon-grid-crossed' => 'grid-crossed' ),
			array( 'icon-layers' => 'layers' ),
			array( 'icon-layers-crossed' => 'layers-crossed' ),
			array( 'icon-toggle' => 'toggle' ),
			array( 'icon-rulers' => 'rulers' ),
			array( 'icon-ruler' => 'ruler' ),
			array( 'icon-funnel' => 'funnel' ),
			array( 'icon-flip-horizontal' => 'flip-horizontal' ),
			array( 'icon-flip-vertical' => 'flip-vertical' ),
			array( 'icon-flip-horizontal2' => 'flip-horizontal2' ),
			array( 'icon-flip-vertical2' => 'flip-vertical2' ),
			array( 'icon-angle' => 'angle' ),
			array( 'icon-angle2' => 'angle2' ),
			array( 'icon-subtract' => 'subtract' ),
			array( 'icon-combine' => 'combine' ),
			array( 'icon-intersect' => 'intersect' ),
			array( 'icon-exclude' => 'exclude' ),
			array( 'icon-align-center-vertical' => 'align-center-vertical' ),
			array( 'icon-align-right' => 'align-right' ),
			array( 'icon-align-bottom' => 'align-bottom' ),
			array( 'icon-align-left' => 'align-left' ),
			array( 'icon-align-center-horizontal' => 'align-center-horizontal' ),
			array( 'icon-align-top' => 'align-top' ),
			array( 'icon-square' => 'square' ),
			array( 'icon-plus-square' => 'plus-square' ),
			array( 'icon-minus-square' => 'minus-square' ),
			array( 'icon-percent-square' => 'percent-square' ),
			array( 'icon-arrow-up-square' => 'arrow-up-square' ),
			array( 'icon-arrow-down-square' => 'arrow-down-square' ),
			array( 'icon-arrow-left-square' => 'arrow-left-square' ),
			array( 'icon-arrow-right-square' => 'arrow-right-square' ),
			array( 'icon-chevron-up-square' => 'chevron-up-square' ),
			array( 'icon-chevron-down-square' => 'chevron-down-square' ),
			array( 'icon-chevron-left-square' => 'chevron-left-square' ),
			array( 'icon-chevron-right-square' => 'chevron-right-square' ),
			array( 'icon-check-square' => 'check-square' ),
			array( 'icon-cross-square' => 'cross-square' ),
			array( 'icon-menu-square' => 'menu-square' ),
			array( 'icon-prohibited' => 'prohibited' ),
			array( 'icon-circle' => 'circle' ),
			array( 'icon-radio-button' => 'radio-button' ),
			array( 'icon-ligature' => 'ligature' ),
			array( 'icon-text-format' => 'text-format' ),
			array( 'icon-text-format-remove' => 'text-format-remove' ),
			array( 'icon-text-size' => 'text-size' ),
			array( 'icon-bold' => 'bold' ),
			array( 'icon-italic' => 'italic' ),
			array( 'icon-underline' => 'underline' ),
			array( 'icon-strikethrough' => 'strikethrough' ),
			array( 'icon-highlight' => 'highlight' ),
			array( 'icon-text-align-left' => 'text-align-left' ),
			array( 'icon-text-align-center' => 'text-align-center' ),
			array( 'icon-text-align-right' => 'text-align-right' ),
			array( 'icon-text-align-justify' => 'text-align-justify' ),
			array( 'icon-line-spacing' => 'line-spacing' ),
			array( 'icon-indent-increase' => 'indent-increase' ),
			array( 'icon-indent-decrease' => 'indent-decrease' ),
			array( 'icon-text-wrap' => 'text-wrap' ),
			array( 'icon-pilcrow' => 'pilcrow' ),
			array( 'icon-direction-ltr' => 'direction-ltr' ),
			array( 'icon-direction-rtl' => 'direction-rtl' ),
			array( 'icon-page-break' => 'page-break' ),
			array( 'icon-page-break2' => 'page-break2' ),
			array( 'icon-sort-alpha-asc' => 'sort-alpha-asc' ),
			array( 'icon-sort-alpha-desc' => 'sort-alpha-desc' ),
			array( 'icon-sort-numeric-asc' => 'sort-numeric-asc' ),
			array( 'icon-sort-numeric-desc' => 'sort-numeric-desc' ),
			array( 'icon-sort-amount-asc' => 'sort-amount-asc' ),
			array( 'icon-sort-amount-desc' => 'sort-amount-desc' ),
			array( 'icon-sort-time-asc' => 'sort-time-asc' ),
			array( 'icon-sort-time-desc' => 'sort-time-desc' ),
			array( 'icon-sigma' => 'sigma' ),
			array( 'icon-pencil-line' => 'pencil-line' ),
			array( 'icon-hand' => 'hand' ),
			array( 'icon-pointer-up' => 'pointer-up' ),
			array( 'icon-pointer-right' => 'pointer-right' ),
			array( 'icon-pointer-down' => 'pointer-down' ),
			array( 'icon-pointer-left' => 'pointer-left' ),
			array( 'icon-finger-tap' => 'finger-tap' ),
			array( 'icon-fingers-tap' => 'fingers-tap' ),
			array( 'icon-reminder' => 'reminder' ),
			array( 'icon-fingers-crossed' => 'fingers-crossed' ),
			array( 'icon-fingers-victory' => 'fingers-victory' ),
			array( 'icon-gesture-zoom' => 'gesture-zoom' ),
			array( 'icon-gesture-pinch' => 'gesture-pinch' ),
			array( 'icon-fingers-scroll-horizontal' => 'fingers-scroll-horizontal' ),
			array( 'icon-fingers-scroll-vertical' => 'fingers-scroll-vertical' ),
			array( 'icon-fingers-scroll-left' => 'fingers-scroll-left' ),
			array( 'icon-fingers-scroll-right' => 'fingers-scroll-right' ),
			array( 'icon-hand2' => 'hand2' ),
			array( 'icon-pointer-up2' => 'pointer-up2' ),
			array( 'icon-pointer-right2' => 'pointer-right2' ),
			array( 'icon-pointer-down2' => 'pointer-down2' ),
			array( 'icon-pointer-left2' => 'pointer-left2' ),
			array( 'icon-finger-tap2' => 'finger-tap2' ),
			array( 'icon-fingers-tap2' => 'fingers-tap2' ),
			array( 'icon-reminder2' => 'reminder2' ),
			array( 'icon-gesture-zoom2' => 'gesture-zoom2' ),
			array( 'icon-gesture-pinch2' => 'gesture-pinch2' ),
			array( 'icon-fingers-scroll-horizontal2' => 'fingers-scroll-horizontal2' ),
			array( 'icon-fingers-scroll-vertical2' => 'fingers-scroll-vertical2' ),
			array( 'icon-fingers-scroll-left2' => 'fingers-scroll-left2' ),
			array( 'icon-fingers-scroll-right2' => 'fingers-scroll-right2' ),
			array( 'icon-fingers-scroll-vertical3' => 'fingers-scroll-vertical3' ),
			array( 'icon-border-style' => 'border-style' ),
			array( 'icon-border-all' => 'border-all' ),
			array( 'icon-border-outer' => 'border-outer' ),
			array( 'icon-border-inner' => 'border-inner' ),
			array( 'icon-border-top' => 'border-top' ),
			array( 'icon-border-horizontal' => 'border-horizontal' ),
			array( 'icon-border-bottom' => 'border-bottom' ),
			array( 'icon-border-left' => 'border-left' ),
			array( 'icon-border-vertical' => 'border-vertical' ),
			array( 'icon-border-right' => 'border-right' ),
			array( 'icon-border-none' => 'border-none' ),
			array( 'icon-ellipsis' => 'ellipsis' ),
			array( 'icon-uni21' => 'uni21' ),
			array( 'icon-uni22' => 'uni22' ),
			array( 'icon-uni23' => 'uni23' ),
			array( 'icon-uni24' => 'uni24' ),
			array( 'icon-uni25' => 'uni25' ),
			array( 'icon-uni26' => 'uni26' ),
			array( 'icon-uni27' => 'uni27' ),
			array( 'icon-uni28' => 'uni28' ),
			array( 'icon-uni29' => 'uni29' ),
			array( 'icon-uni2a' => 'uni2a' ),
			array( 'icon-uni2b' => 'uni2b' ),
			array( 'icon-uni2c' => 'uni2c' ),
			array( 'icon-uni2d' => 'uni2d' ),
			array( 'icon-uni2e' => 'uni2e' ),
			array( 'icon-uni2f' => 'uni2f' ),
			array( 'icon-uni30' => 'uni30' ),
			array( 'icon-uni31' => 'uni31' ),
			array( 'icon-uni32' => 'uni32' ),
			array( 'icon-uni33' => 'uni33' ),
			array( 'icon-uni34' => 'uni34' ),
			array( 'icon-uni35' => 'uni35' ),
			array( 'icon-uni36' => 'uni36' ),
			array( 'icon-uni37' => 'uni37' ),
			array( 'icon-uni38' => 'uni38' ),
			array( 'icon-uni39' => 'uni39' ),
			array( 'icon-uni3a' => 'uni3a' ),
			array( 'icon-uni3b' => 'uni3b' ),
			array( 'icon-uni3c' => 'uni3c' ),
			array( 'icon-uni3d' => 'uni3d' ),
			array( 'icon-uni3e' => 'uni3e' ),
			array( 'icon-uni3f' => 'uni3f' ),
			array( 'icon-uni40' => 'uni40' ),
			array( 'icon-uni41' => 'uni41' ),
			array( 'icon-uni42' => 'uni42' ),
			array( 'icon-uni43' => 'uni43' ),
			array( 'icon-uni44' => 'uni44' ),
			array( 'icon-uni45' => 'uni45' ),
			array( 'icon-uni46' => 'uni46' ),
			array( 'icon-uni47' => 'uni47' ),
			array( 'icon-uni48' => 'uni48' ),
			array( 'icon-uni49' => 'uni49' ),
			array( 'icon-uni4a' => 'uni4a' ),
			array( 'icon-uni4b' => 'uni4b' ),
			array( 'icon-uni4c' => 'uni4c' ),
			array( 'icon-uni4d' => 'uni4d' ),
			array( 'icon-uni4e' => 'uni4e' ),
			array( 'icon-uni4f' => 'uni4f' ),
			array( 'icon-uni50' => 'uni50' ),
			array( 'icon-uni51' => 'uni51' ),
			array( 'icon-uni52' => 'uni52' ),
			array( 'icon-uni53' => 'uni53' ),
			array( 'icon-uni54' => 'uni54' ),
			array( 'icon-uni55' => 'uni55' ),
			array( 'icon-uni56' => 'uni56' ),
			array( 'icon-uni57' => 'uni57' ),
			array( 'icon-uni58' => 'uni58' ),
			array( 'icon-uni59' => 'uni59' ),
			array( 'icon-uni5a' => 'uni5a' ),
			array( 'icon-uni5b' => 'uni5b' ),
			array( 'icon-uni5c' => 'uni5c' ),
			array( 'icon-uni5d' => 'uni5d' ),
			array( 'icon-uni5e' => 'uni5e' ),
			array( 'icon-uni5f' => 'uni5f' ),
			array( 'icon-uni60' => 'uni60' ),
			array( 'icon-uni61' => 'uni61' ),
			array( 'icon-uni62' => 'uni62' ),
			array( 'icon-uni63' => 'uni63' ),
			array( 'icon-uni64' => 'uni64' ),
			array( 'icon-uni65' => 'uni65' ),
			array( 'icon-uni66' => 'uni66' ),
			array( 'icon-uni67' => 'uni67' ),
			array( 'icon-uni68' => 'uni68' ),
			array( 'icon-uni69' => 'uni69' ),
			array( 'icon-uni6a' => 'uni6a' ),
			array( 'icon-uni6b' => 'uni6b' ),
			array( 'icon-uni6c' => 'uni6c' ),
			array( 'icon-uni6d' => 'uni6d' ),
			array( 'icon-uni6e' => 'uni6e' ),
			array( 'icon-uni6f' => 'uni6f' ),
			array( 'icon-uni70' => 'uni70' ),
			array( 'icon-uni71' => 'uni71' ),
			array( 'icon-uni72' => 'uni72' ),
			array( 'icon-uni73' => 'uni73' ),
			array( 'icon-uni74' => 'uni74' ),
			array( 'icon-uni75' => 'uni75' ),
			array( 'icon-uni76' => 'uni76' ),
			array( 'icon-uni77' => 'uni77' ),
			array( 'icon-uni78' => 'uni78' ),
			array( 'icon-uni79' => 'uni79' ),
			array( 'icon-uni7a' => 'uni7a' ),
			array( 'icon-uni7b' => 'uni7b' ),
			array( 'icon-uni7c' => 'uni7c' ),
			array( 'icon-uni7d' => 'uni7d' ),
			array( 'icon-uni7e' => 'uni7e' ),
			array( 'icon-copyright' => 'copyright' ),
		);

		return array_merge( $icons, $linearicons );
	}

	/**
	 * Enqueue icon element font
	 *
	 * @param $font
	 */
	function vc_icon_element_fonts_enqueue( $font ) {
		switch ( $font ) {
			case 'linearicons':
				wp_enqueue_style( 'linearicons' );
		}
	}

	function vc_iconpicker_base_register_css() {
		wp_enqueue_style( 'linearicons', MARTFURY_ADDONS_URL . 'assets/css/linearicons.min.css', array(), '1.0.0' );
	}
}

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_martfury_category_tabs extends WPBakeryShortCodesContainer {
	}
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
	class WPBakeryShortCode_martfury_category_tab extends WPBakeryShortCode {
	}
}