<?php

namespace MartfuryAddons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use MartfuryAddons\Elementor;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Products_Of_Category_2 extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'martfury-products-of-category-2';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Products of Category 2', 'martfury' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-products';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'martfury' ];
	}

	public function get_script_depends() {
		return [
			'martfury-elementor'
		];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		// Content Tab

		$this->register_heading_controls();
		$this->register_banners_controls();
		$this->register_product_tabs_controls();
		$this->register_side_products_controls();

		// Style Tab

		$this->register_heading_style_controls();
		$this->register_banners_style_controls();
		$this->register_product_tabs_style_controls();
		$this->register_side_products_style_controls();

	}

	/**
	 * Register the widget heading controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_heading_controls() {

		$this->start_controls_section(
			'section_heading',
			[ 'label' => esc_html__( 'Heading', 'martfury' ) ]
		);

		$this->start_controls_tabs( 'heading_content_settings' );

		$this->start_controls_tab( 'title_tab', [ 'label' => esc_html__( 'Title', 'martfury' ) ] );

		$this->add_control(
			'icon_type',
			[
				'label'   => esc_html__( 'Icon Type', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'icons'        => esc_html__( 'Old Icons', 'martfury' ),
					'custom_icons' => esc_html__( 'New Icons', 'martfury' ),
				],
				'default' => 'icons',
				'toggle'  => false,
			]
		);

		$this->add_control(
			'icon',
			[
				'label'     => esc_html__( 'Icon', 'martfury' ),
				'type'      => Controls_Manager::ICON,
				'default'   => 'icon-shirt',
				'condition' => [
					'icon_type' => 'icons',
				],
			]
		);

		$this->add_control(
			'custom_icon',
			[
				'label'            => esc_html__( 'Icon', 'martfury' ),
				'type'             => Controls_Manager::ICONS,
				'default'          => [
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition'        => [
					'icon_type' => 'custom_icons',
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Category Name', 'martfury' ),
				'placeholder' => esc_html__( 'Enter the title', 'martfury' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'link', [
				'label'         => esc_html__( 'Link', 'martfury' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'martfury' ),
				'show_external' => true,
				'default'       => [
					'url'         => '#',
					'is_external' => true,
					'nofollow'    => true,
				],
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab( 'quick_links_tab', [ 'label' => esc_html__( 'Quick Links', 'martfury' ) ] );

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'link_text', [
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Category Name', 'martfury' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'link_url', [
				'label'         => esc_html__( 'Link', 'martfury' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'martfury' ),
				'show_external' => true,
				'default'       => [
					'url'         => '#',
					'is_external' => true,
					'nofollow'    => true,
				],
			]
		);

		$this->add_control(
			'links_group',
			[
				'label'         => esc_html__( 'Links', 'martfury' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'link_text' => esc_html__( 'Sub Category 1', 'martfury' ),
						'link_url'  => [
							'url' => '#',
						]
					],
					[
						'link_text' => esc_html__( 'Sub Category 2', 'martfury' ),
						'link_url'  => [
							'url' => '#',
						]
					],
					[
						'link_text' => esc_html__( 'Sub Category 3', 'martfury' ),
						'link_url'  => [
							'url' => '#',
						]
					],
					[
						'link_text' => esc_html__( 'Sub Category 4', 'martfury' ),
						'link_url'  => [
							'url' => '#',
						]
					]
				],
				'title_field'   => '{{{ link_text }}}',
				'prevent_empty' => false
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register the widget banners controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_banners_controls() {

		$this->start_controls_section(
			'section_banners_content',
			[
				'label' => esc_html__( 'Banners Carousel', 'martfury' ),
			]
		);

		$this->start_controls_tabs( 'banners_content_settings' );

		$this->start_controls_tab( 'banners_tab', [ 'label' => esc_html__( 'Banners', 'martfury' ) ] );

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'image',
			[
				'label'   => esc_html__( 'Choose Image', 'martfury' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/829x241/e7eff1?text=829x241+Banner'
				],
			]
		);

		$repeater->add_control(
			'image_link', [
				'label'         => esc_html__( 'Link', 'martfury' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'martfury' ),
				'show_external' => true,
				'default'       => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
			]
		);

		$this->add_control(
			'banners',
			[
				'label'         => esc_html__( 'Banners', 'martfury' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'image'      => [
							'url' => 'https://via.placeholder.com/829x241/e7eff1?text=829x241+Banner'
						],
						'image_link' => [
							'url' => '#'
						]
					],
					[
						'image'      => [
							'url' => 'https://via.placeholder.com/829x241/e7eff1?text=829x241+Banner+2'
						],
						'image_link' => [
							'url' => '#'
						]
					]
				],
				'prevent_empty' => false
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'banners_carousel_tab', [ 'label' => esc_html__( 'Carousel', 'martfury' ) ] );

		$this->add_control(
			'banners_autoplay',
			[
				'label'   => esc_html__( 'Autoplay', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'no',
				'options' => [
					'yes' => esc_html__( 'Yes', 'martfury' ),
					'no'  => esc_html__( 'No', 'martfury' ),
				],
			]
		);

		$this->add_control(
			'banners_autoplay_speed',
			[
				'label'   => esc_html__( 'Autoplay Speed', 'martfury' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5000,
			]
		);

		$this->add_control(
			'banners_speed',
			[
				'label'   => esc_html__( 'Speed', 'martfury' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 800,
			]
		);

		$this->add_control(
			'banners_infinite',
			[
				'label'   => esc_html__( 'Infinite Loop', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => esc_html__( 'Yes', 'martfury' ),
					'no'  => esc_html__( 'No', 'martfury' ),
				],
			]
		);

		$this->add_control(
			'banners_arrows',
			[
				'label'     => esc_html__( 'Arrows', 'martfury' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'martfury' ),
				'label_off' => esc_html__( 'Hide', 'martfury' ),
				'default'   => 'yes',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register the widget product tabs controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_product_tabs_controls() {

		$this->start_controls_section(
			'section_product_content',
			[
				'label' => esc_html__( 'Product Tabs', 'martfury' ),
			]
		);

		$this->start_controls_tabs( 'products_content_settings' );

		$this->start_controls_tab( 'products_tab', [ 'label' => esc_html__( 'Products', 'martfury' ) ] );

		$this->add_control(
			'product_cats',
			[
				'label'       => esc_html__( 'Product Categories', 'martfury' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => Elementor::get_taxonomy(),
				'default'     => '',
				'multiple'    => true,
				'label_block' => true,
			]
		);

		$this->add_control(
			'per_page',
			[
				'label'   => esc_html__( 'Products per view', 'martfury' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 12,
				'min'     => 2,
				'max'     => 50,
				'step'    => 1,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'product_tabs_tab', [ 'label' => esc_html__( 'Tabs', 'martfury' ) ] );

		$this->add_responsive_control(
			'show_heading_tab',
			[
				'label'     => esc_html__( 'Heading', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'block' => esc_html__( 'Show', 'martfury' ),
					'none'  => esc_html__( 'Hide', 'martfury' ),
				],
				'default'   => 'block',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .tabs-header' => 'display: {{VALUE}}!important',

				],
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'tab_title', [
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Tab Name', 'martfury' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'tab_products',
			[
				'label'   => esc_html__( 'Products', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'recent'       => esc_html__( 'Recent', 'martfury' ),
					'featured'     => esc_html__( 'Featured', 'martfury' ),
					'best_selling' => esc_html__( 'Best Selling', 'martfury' ),
					'top_rated'    => esc_html__( 'Top Rated', 'martfury' ),
					'sale'         => esc_html__( 'On Sale', 'martfury' ),
				],
				'default' => 'recent',
				'toggle'  => false,
			]
		);

		$repeater->add_control(
			'tab_orderby',
			[
				'label'     => esc_html__( 'Order By', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''           => esc_html__( 'Default', 'martfury' ),
					'date'       => esc_html__( 'Date', 'martfury' ),
					'title'      => esc_html__( 'Title', 'martfury' ),
					'menu_order' => esc_html__( 'Menu Order', 'martfury' ),
					'rand'       => esc_html__( 'Random', 'martfury' ),
				],
				'default'   => '',
				'condition' => [
					'products' => [ 'recent', 'top_rated', 'sale', 'featured' ],
				],
			]
		);

		$repeater->add_control(
			'tab_order',
			[
				'label'     => esc_html__( 'Order', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''     => esc_html__( 'Default', 'martfury' ),
					'asc'  => esc_html__( 'Ascending', 'martfury' ),
					'desc' => esc_html__( 'Descending', 'martfury' ),
				],
				'default'   => '',
				'condition' => [
					'products' => [ 'recent', 'top_rated', 'sale', 'featured' ],
				],
			]
		);

		$this->add_control(
			'tabs',
			[
				'label'         => esc_html__( 'Product Tabs', 'martfury' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'tab_title'    => esc_html__( 'New Arrivals', 'martfury' ),
						'tab_products' => 'recent'
					],
					[
						'tab_title'    => esc_html__( 'Best Seller', 'martfury' ),
						'tab_products' => 'best_selling'
					],
					[
						'tab_title'    => esc_html__( 'Sale', 'martfury' ),
						'tab_products' => 'sale'
					]
				],
				'title_field'   => '{{{ tab_title }}}',
				'prevent_empty' => false
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab( 'product_carousel_tab', [ 'label' => esc_html__( 'Carousel', 'martfury' ) ] );

		$this->add_control(
			'products_slides_to_show',
			[
				'label'   => esc_html__( 'Slides To Show', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'4' => esc_html__( '4 Columns', 'martfury' ),
					'3' => esc_html__( '3 Columns', 'martfury' ),
					'5' => esc_html__( '5 Columns', 'martfury' ),
					'6' => esc_html__( '6 Columns', 'martfury' ),
				],
				'default' => '4',
				'toggle'  => false,
			]
		);

		$this->add_control(
			'products_slides_to_scroll',
			[
				'label'   => esc_html__( 'Slides To Scroll', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'4' => esc_html__( '4 Columns', 'martfury' ),
					'3' => esc_html__( '3 Columns', 'martfury' ),
					'5' => esc_html__( '5 Columns', 'martfury' ),
					'6' => esc_html__( '6 Columns', 'martfury' ),
				],
				'default' => '4',
				'toggle'  => false,
			]
		);

		$this->add_responsive_control(
			'products_navigation',
			[
				'label'   => esc_html__( 'Navigation', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'both'   => esc_html__( 'Arrows and Dots', 'martfury' ),
					'arrows' => esc_html__( 'Arrows', 'martfury' ),
					'dots'   => esc_html__( 'Dots', 'martfury' ),
					'none'   => esc_html__( 'None', 'martfury' ),
				],
				'default' => 'arrows',
				'toggle'  => false,
			]
		);

		$this->add_control(
			'products_infinite',
			[
				'label'     => esc_html__( 'Infinite Loop', 'martfury' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'martfury' ),
				'label_on'  => esc_html__( 'On', 'martfury' ),
				'default'   => 'yes'
			]
		);

		$this->add_control(
			'products_autoplay',
			[
				'label'     => esc_html__( 'Autoplay', 'martfury' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'martfury' ),
				'label_on'  => esc_html__( 'On', 'martfury' ),
				'default'   => 'no'
			]
		);

		$this->add_control(
			'products_autoplay_speed',
			[
				'label'   => esc_html__( 'Autoplay Speed (in ms)', 'martfury' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5000,
				'min'     => 100,
				'step'    => 100,
			]
		);

		$this->add_control(
			'products_speed',
			[
				'label'   => esc_html__( 'Speed (in ms)', 'martfury' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 800,
				'min'     => 100,
				'step'    => 100,
			]
		);


		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register the widget side products controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_side_products_controls() {

		$this->start_controls_section(
			'section_side_product',
			[
				'label' => esc_html__( 'Side Products', 'martfury' ),
			]
		);


		$this->start_controls_tabs( 'side_products_content_settings' );

		$this->start_controls_tab( 'side_products_heading_tab', [ 'label' => esc_html__( 'Heading', 'martfury' ) ] );

		$this->add_control(
			'side_title',
			[
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Heading Name', 'martfury' ),
				'placeholder' => esc_html__( 'Enter your title', 'martfury' ),
				'label_block' => true,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'side_products_tab', [ 'label' => esc_html__( 'Products', 'martfury' ) ] );

		$this->add_control(
			'side_per_page',
			[
				'label'   => esc_html__( 'Total Products', 'martfury' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 2,
				'max'     => 50,
				'step'    => 1,
			]
		);

		$this->add_control(
			'side_products',
			[
				'label'   => esc_html__( 'Products', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'recent'       => esc_html__( 'Recent', 'martfury' ),
					'featured'     => esc_html__( 'Featured', 'martfury' ),
					'best_selling' => esc_html__( 'Best Selling', 'martfury' ),
					'top_rated'    => esc_html__( 'Top Rated', 'martfury' ),
					'sale'         => esc_html__( 'On Sale', 'martfury' ),
				],
				'default' => 'recent',
				'toggle'  => false,
			]
		);

		$this->add_control(
			'side_product_cats',
			[
				'label'       => esc_html__( 'Product Categories', 'martfury' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => Elementor::get_taxonomy(),
				'default'     => '',
				'multiple'    => true,
				'label_block' => true,
			]
		);

		$this->add_control(
			'side_product_orderby',
			[
				'label'     => esc_html__( 'Order By', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''           => esc_html__( 'Default', 'martfury' ),
					'date'       => esc_html__( 'Date', 'martfury' ),
					'title'      => esc_html__( 'Title', 'martfury' ),
					'menu_order' => esc_html__( 'Menu Order', 'martfury' ),
					'rand'       => esc_html__( 'Random', 'martfury' ),
				],
				'default'   => '',
				'condition' => [
					'side_products' => [ 'recent', 'top_rated', 'sale', 'featured' ],
				],
			]
		);

		$this->add_control(
			'side_product_order',
			[
				'label'     => esc_html__( 'Order', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''     => esc_html__( 'Default', 'martfury' ),
					'asc'  => esc_html__( 'Ascending', 'martfury' ),
					'desc' => esc_html__( 'Descending', 'martfury' ),
				],
				'default'   => '',
				'condition' => [
					'side_products' => [ 'recent', 'top_rated', 'sale', 'featured' ],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'side_products_view_all_tab', [ 'label' => esc_html__( 'View All', 'martfury' ) ] );

		$this->add_control(
			'side_link_text', [
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'View More', 'martfury' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'side_link_url', [
				'label'         => esc_html__( 'Link', 'martfury' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'martfury' ),
				'show_external' => true,
				'default'       => [
					'url'         => '#',
					'is_external' => true,
					'nofollow'    => true,
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register the widget heading style controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_heading_style_controls() {

		$this->start_controls_section(
			'section_style_heading',
			[
				'label' => esc_html__( 'Heading', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'heading_padding',
			[
				'label'      => esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-of-category-2 .cats-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .cats-header' => 'background-color: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_border_type',
			[
				'label'     => esc_html__( 'Border Type', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''       => esc_html__( 'Solid', 'martfury' ),
					'double' => _x( 'Double', 'Border Control', 'martfury' ),
					'dotted' => _x( 'Dotted', 'Border Control', 'martfury' ),
					'dashed' => _x( 'Dashed', 'Border Control', 'martfury' ),
					'groove' => _x( 'Groove', 'Border Control', 'martfury' ),
					'none'   => _x( 'None', 'Border Control', 'martfury' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .cats-header' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_border_width',
			[
				'label'     => esc_html__( 'Border Width', 'martfury' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .cats-header' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'heading_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'heading_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .cats-header' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'heading_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'heading_style_divider',
			[
				'label' => '',
				'type'  => Controls_Manager::DIVIDER,
			]
		);

		$this->start_controls_tabs( 'heading_title_style_settings' );

		$this->start_controls_tab( 'heading_title_style_tab', [ 'label' => esc_html__( 'Title', 'martfury' ) ] );

		$this->add_control(
			'heading_title_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .cats-header .cat-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'heading_title_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .cats-header h2' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_title_hover_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .cats-header .cat-title:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_title_typography',
				'selector' => '{{WRAPPER}} .mf-products-of-category-2 .cats-header .cat-title',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'heading_icon_style_tab', [ 'label' => esc_html__( 'Icon', 'martfury' ) ] );

		$this->add_responsive_control(
			'heading_icon_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .cats-header .cat-title .mf-icon' => 'padding-right: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_icon_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .cats-header .cat-title i'            => 'color: {{VALUE}}',
					'{{WRAPPER}} .mf-products-of-category-2 .cats-header .cat-title .mf-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_icon_typography',
				'selector' => '{{WRAPPER}} .mf-products-of-category-2 .cats-header .cat-title .mf-icon',
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab( 'heading_links_style_tab', [ 'label' => esc_html__( 'Links', 'martfury' ) ] );

		$this->add_responsive_control(
			'heading_links',
			[
				'label'     => esc_html__( 'Show Links', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'flex' => esc_html__( 'Yes', 'martfury' ),
					'none' => esc_html__( 'No', 'martfury' ),
				],
				'default'   => 'flex',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .cats-header .extra-links' => 'display: {{VALUE}}',

				],
			]
		);

		$this->add_responsive_control(
			'heading_links_padding',
			[
				'label'     => esc_html__( 'Padding', 'martfury' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .cats-header .extra-links li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_links_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .cats-header .extra-links li a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_links_hover_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .cats-header .extra-links li a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_links_typography',
				'selector' => '{{WRAPPER}} .mf-products-of-category-2 .cats-header .extra-links li a',
			]
		);


		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();


		$this->end_controls_section();
	}

	/**
	 * Register the widget banners style controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_banners_style_controls() {

		$this->start_controls_section(
			'section_banners_style',
			[
				'label' => esc_html__( 'Banners Carousel', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'banners_show',
			[
				'label'     => esc_html__( 'Show Banners', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'block' => esc_html__( 'Yes', 'martfury' ),
					'none'  => esc_html__( 'No', 'martfury' ),
				],
				'default'   => 'block',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .images-slider' => 'display: {{VALUE}}',

				],
			]
		);

		$this->add_responsive_control(
			'banners_padding',
			[
				'label'      => esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-of-category-2 .images-slider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'banners_border',
				'label'    => esc_html__( 'Border', 'martfury' ),
				'selector' => '{{WRAPPER}} .mf-products-of-category-2 .images-slider',
			]
		);

		$this->add_control(
			'banners_style_divider',
			[
				'label' => '',
				'type'  => Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'banners_arrow_width',
			[
				'label'     => esc_html__( 'Arrow Width', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .images-slider .slick-arrow' => 'width: {{SIZE}}{{UNIT}};    overflow: hidden;',
				],
			]
		);

		$this->add_responsive_control(
			'banners_arrow_height',
			[
				'label'     => esc_html__( 'Arrow Height', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .images-slider .slick-arrow' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'banners_arrow_typography',
				'selector' => '{{WRAPPER}} .mf-products-of-category-2 .images-slider .slick-arrow',
			]
		);

		$this->start_controls_tabs( 'banners_normal_settings' );


		$this->start_controls_tab( 'banners_normal', [ 'label' => esc_html__( 'Normal', 'martfury' ) ] );

		$this->add_control(
			'banners_arrow_background',
			[
				'label'     => esc_html__( 'Arrow Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .images-slider .slick-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'banners_arrow_color',
			[
				'label'     => esc_html__( 'Arrow Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .images-slider .slick-arrow' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'banners_hover', [ 'label' => esc_html__( 'Hover', 'martfury' ) ] );

		$this->add_control(
			'banners_arrow_hover_background',
			[
				'label'     => esc_html__( 'Arrow Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .images-slider .slick-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'banners_arrow_hover_color',
			[
				'label'     => esc_html__( 'Arrow Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .images-slider .slick-arrow:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register the widget product tabs style controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_product_tabs_style_controls() {

		$this->start_controls_section(
			'section_product_tabs_style',
			[
				'label' => esc_html__( 'Product Tabs', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'product_tabs_padding',
			[
				'label'      => esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'product_tabs_border',
				'label'    => esc_html__( 'Border', 'martfury' ),
				'selector' => '{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs',
			]
		);

		$this->add_control(
			'product_tabs_style_divider',
			[
				'label' => '',
				'type'  => Controls_Manager::DIVIDER,
			]
		);

		$this->add_responsive_control(
			'product_tabs_heading_padding',
			[
				'label'      => esc_html__( 'Heading Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .tabs-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'product_tabs_heading_border_type',
			[
				'label'     => esc_html__( 'Heading Border Type', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''       => esc_html__( 'Solid', 'martfury' ),
					'double' => _x( 'Double', 'Border Control', 'martfury' ),
					'dotted' => _x( 'Dotted', 'Border Control', 'martfury' ),
					'dashed' => _x( 'Dashed', 'Border Control', 'martfury' ),
					'groove' => _x( 'Groove', 'Border Control', 'martfury' ),
					'none'   => _x( 'None', 'Border Control', 'martfury' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .tabs-header' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_tabs_heading_border_width',
			[
				'label'     => esc_html__( 'Heading Border Width', 'martfury' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .tabs-header' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'product_tabs_heading_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'product_tabs_heading_border_color',
			[
				'label'     => esc_html__( 'Heading Border Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .tabs-header' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'product_tabs_heading_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'product_tabs_heading_style_divider',
			[
				'label' => '',
				'type'  => Controls_Manager::DIVIDER,
			]
		);

		$this->start_controls_tabs( 'product_tabs_style_settings' );


		$this->start_controls_tab( 'product_tabs_title_style', [ 'label' => esc_html__( 'Title', 'martfury' ) ] );

		$this->add_responsive_control(
			'product_tabs_title_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .tabs-header .tabs-nav li'                  => 'padding-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .tabs-header .tabs-nav li:last-child'       => 'padding-right: 0',
					'.rtl {{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .tabs-header .tabs-nav li:last-child'  => 'padding-right: {{SIZE}}{{UNIT}}',
					'.rtl {{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .tabs-header .tabs-nav li:first-child' => 'padding-right: 0',
				],
			]
		);

		$this->add_control(
			'product_tabs_title_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .tabs-header li a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_tabs_title_hover_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .tabs-header li a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_tabs_title_active_color',
			[
				'label'     => esc_html__( 'Active Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .tabs-header .tabs-nav li a.active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_tabs_title_typography',
				'selector' => '{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .tabs-header li a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'product_tabs_arrows_style', [ 'label' => esc_html__( 'Arrows', 'martfury' ) ] );

		$this->add_control(
			'product_tabs_arrows_prev_position',
			[
				'label'              => esc_html__( 'Arrow Previous Position', 'martfury' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', 'em', '%' ],
				'allowed_dimensions' => [ 'top', 'right' ],
				'default'            => [
					'top'   => '-54',
					'right' => '35',
				],
				'selectors'          => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .slick-prev-arrow' => 'top: {{TOP}}{{UNIT}};right: {{RIGHT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'product_tabs_arrows_next_position',
			[
				'label'              => esc_html__( 'Arrow Next Position', 'martfury' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', 'em', '%' ],
				'allowed_dimensions' => [ 'top', 'right' ],
				'default'            => [
					'top'   => '-54',
					'right' => '0',
				],
				'selectors'          => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .slick-next-arrow' => 'top: {{TOP}}{{UNIT}};right: {{RIGHT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'product_tabs_arrows_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .slick-arrow' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'product_tabs_arrows_hover_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .slick-arrow:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_tabs_arrows_typography',
				'selector' => '{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .slick-arrow',
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab( 'product_tabs_dots_style', [ 'label' => esc_html__( 'Dots', 'martfury' ) ] );

		$this->add_responsive_control(
			'product_tabs_dots_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .slick-dots' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'product_tabs_dots_width',
			[
				'label'     => esc_html__( 'Dots Width', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .slick-dots li button' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_tabs_dots_background',
			[
				'label'     => esc_html__( 'Dots Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .slick-dots li button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'product_tabs_dots_active_background',
			[
				'label'     => esc_html__( 'Dots Active Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .mf-products-tabs .slick-dots li.slick-active button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register the widget product tabs style controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_side_products_style_controls() {

		$this->start_controls_section(
			'section_side_products_style',
			[
				'label' => esc_html__( 'Side Products', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'side_products_hide_desktop',
			[
				'label'        => esc_html__( 'Hide On Desktop', 'martfury' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Hide', 'martfury' ),
				'label_off'    => esc_html__( 'Show', 'martfury' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'side_products_hide_tablet',
			[
				'label'        => esc_html__( 'Hide On Tablet', 'martfury' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Hide', 'martfury' ),
				'label_off'    => esc_html__( 'Show', 'martfury' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'side_products_hide_mobile',
			[
				'label'        => esc_html__( 'Hide On Mobile', 'martfury' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Hide', 'martfury' ),
				'label_off'    => esc_html__( 'Show', 'martfury' ),
				'return_value' => 'yes',
			]
		);

		$this->add_responsive_control(
			'side_products_padding',
			[
				'label'      => esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-of-category-2 .products-side' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'side_products_border',
				'label'    => esc_html__( 'Border', 'martfury' ),
				'selector' => '{{WRAPPER}} .mf-products-of-category-2 .products-side',
			]
		);

		$this->add_control(
			'side_products_style_divider',
			[
				'label' => '',
				'type'  => Controls_Manager::DIVIDER,
			]
		);


		$this->start_controls_tabs( 'side_products_heading_settings' );


		$this->start_controls_tab( 'side_products_heading_style', [ 'label' => esc_html__( 'Heading', 'martfury' ) ] );

		$this->add_responsive_control(
			'side_products_heading_padding',
			[
				'label'      => esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-of-category-2 .products-side .side-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'side_products_heading_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .products-side .side-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'side_products_heading_border_type',
			[
				'label'     => esc_html__( 'Border Type', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''       => esc_html__( 'Solid', 'martfury' ),
					'double' => _x( 'Double', 'Border Control', 'martfury' ),
					'dotted' => _x( 'Dotted', 'Border Control', 'martfury' ),
					'dashed' => _x( 'Dashed', 'Border Control', 'martfury' ),
					'groove' => _x( 'Groove', 'Border Control', 'martfury' ),
					'none'   => _x( 'None', 'Border Control', 'martfury' ),
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .products-side .side-title' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'side_products_heading_border_width',
			[
				'label'     => esc_html__( 'Border Width', 'martfury' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .products-side .side-title' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'product_tabs_heading_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'side_products_heading_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .products-side .side-title' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'product_tabs_heading_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'side_products_heading_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .products-side .side-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'side_products_title_typography',
				'selector' => '{{WRAPPER}} .mf-products-of-category-2 .products-side .side-title',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'side_products_products_style', [ 'label' => esc_html__( 'Products', 'martfury' ) ] );

		$this->add_control(
			'side_products_product_items_spacing',
			[
				'label'     => esc_html__( 'Product Items Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .products-side ul.products li.product' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'side_products_view_all_style', [ 'label' => esc_html__( 'View All', 'martfury' ) ] );

		$this->add_control(
			'side_products_view_all_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .products-side .link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'side_products_view_all_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category-2 .products-side .link:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'side_products_view_all_typography',
				'selector' => '{{WRAPPER}} .mf-products-of-category-2 .products-side .link',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}


	/**
	 * Render icon box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'wrapper', 'class', [
				'mf-products-of-category-2 woocommerce'
			]
		);

		$this->add_render_attribute( 'sliders_wrapper', 'class', [ 'images-slider' ] );

		$carousel_settings = [
			'infinite'       => $settings['banners_infinite'],
			'autoplay'       => $settings['banners_autoplay'],
			'autoplay_speed' => $settings['banners_autoplay_speed'],
			'speed'          => $settings['banners_speed'],
			'arrows'         => $settings['banners_arrows'],
		];

		$this->add_render_attribute( 'sliders_wrapper', 'data-settings', wp_json_encode( $carousel_settings ) );

		$output = [];

		// Cat HTML
		$cats_html = [];

		$icon = '';

		if ( $settings['icon_type'] == 'icons' ) {
			if ( $settings['icon'] ) {
				$icon = '<span class="mf-icon"><i class="' . esc_attr( $settings['icon'] ) . '"></i></span>';
			}
		} elseif ( $settings['icon_type'] == 'custom_icons' ) {
			if ( $settings['custom_icon'] && \Elementor\Icons_Manager::is_migration_allowed() ) {
				ob_start();
				\Elementor\Icons_Manager::render_icon( $settings['custom_icon'], [ 'aria-hidden' => 'true' ] );
				$icon = '<span class="mf-icon">' . ob_get_clean() . '</span>';
			}
		}

		if ( $settings['title'] ) {
			$title       = $icon . $settings['title'];
			$cats_html[] = sprintf( '<h2>%s</h2>', $this->get_link_control( 'link', $settings['link'], $title, 'cat-title' ) );
		}

		$links_group = $settings['links_group'];

		if ( ! empty ( $links_group ) ) {
			$cats_html[] = '<ul class="extra-links">';
			foreach ( $links_group as $index => $item ) {
				$link_key = 'link_' . $index;

				$link = $item['link_text'] ? $this->get_link_control( $link_key, $item['link_url'], $item['link_text'], 'extra-link' ) : '';

				$cats_html[] = sprintf( '<li>%s</li>', $link );
			}
			$cats_html[] = '</ul>';
		}

		// Banner Carousel
		$banners        = $settings['banners'];
		$banners_output = [];

		if ( ! empty ( $banners ) ) {
			foreach ( $banners as $index => $item ) {
				$link_key = 'link_' . $index;

				$settings['image']      = $item['image'];
				$settings['image_size'] = 'full';
				$btn_image              = Group_Control_Image_Size::get_attachment_image_html( $settings );

				$link = $this->get_link_control( $link_key, $item['image_link'], $btn_image, 'image-item' );

				$banners_output[] = sprintf( '%s', $link );
			}
		}

		$output[]              = sprintf( '<div class="cats-header">%s</div>', implode( ' ', $cats_html ) );
		$product_content_class = $settings['side_products_hide_desktop'] != 'yes' ? 'col-md-9 has-side-product' : 'col-md-12';

		$output[] = '<div class="products-cat row">';
		$output[] = '<div class="' . $product_content_class . ' col-sm-12 col-xs-12 col-product-content">';

		$output[] = sprintf( '<div %s><div class="images-list">%s</div></div>', $this->get_render_attribute_string( 'sliders_wrapper' ), implode( ' ', $banners_output ) );

		$output[] = $this->get_product_tabs();

		$output[] = '</div>'; // .col-product-content


		if ( $settings['side_products_hide_desktop'] != 'yes' ) {
			$output[]     = '<div class="col-md-3 col-sm-12 col-xs-12 side-products">';
			$side_classes = array();
			if ( $settings['side_products_hide_tablet'] == 'yes' ) {
				$side_classes[] = 'elementor-hidden-tablet';
			}

			if ( $settings['side_products_hide_mobile'] == 'yes' ) {
				$side_classes[] = 'elementor-hidden-phone';
			}

			$output[] = sprintf( '<div class="products-side %s">', implode( ' ', $side_classes ) );
			if ( $settings['side_title'] ) {
				$output[] = sprintf( '<h2 class="side-title">%s</h2>', $settings['side_title'] );
			}

			$atts = [
				'per_page' => $settings['side_per_page'],
				'type'     => $settings['side_products'],
				'order'    => $settings['side_product_order'],
				'orderby'  => $settings['side_product_orderby'],
				'category' => is_array( $settings['side_product_cats'] ) ? implode( ',', $settings['side_product_cats'] ) : '',
			];

			$output[] = $this->get_products( $atts );

			if ( $settings['side_link_text'] ) {
				$output[] = sprintf( '%s', $this->get_link_control( 'side_link_text', $settings['side_link_url'], $settings['side_link_text'], 'link' ) );
			}

			$output[] = '</div>';
			$output[] = '</div>'; // .side-product
		}


		$output[] = '</div>'; // .products-cat

		echo sprintf(
			'<div %s>%s</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			implode( '', $output )
		);

	}

	/**
	 * Get the link control
	 *
	 * @return string.
	 */
	protected function get_link_control( $link_key, $url, $content, $class_css ) {

		if ( $url['is_external'] ) {
			$this->add_render_attribute( $link_key, 'target', '_blank' );
		}

		if ( $url['nofollow'] ) {
			$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
		}

		$attr = 'span';
		if ( $url['url'] ) {
			$this->add_render_attribute( $link_key, 'href', $url['url'] );
			$attr = 'a';
		}

		return sprintf( '<%1$s class="%4$s" %2$s>%3$s</%1$s>', $attr, $this->get_render_attribute_string( $link_key ), $content, $class_css );
	}

	/**
	 * Render icon box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function _content_template() {
	}

	/**
	 * Get products
	 *
	 */

	protected function get_product_tabs() {
		$settings = $this->get_settings_for_display();

		$nav        = $settings['products_navigation'];
		$nav_tablet = empty( $settings['products_navigation_tablet'] ) ? $nav : $settings['products_navigation_tablet'];
		$nav_mobile = empty( $settings['products_navigation_mobile'] ) ? $nav : $settings['products_navigation_mobile'];
		$classes    = [
			'mf-products-tabs martfury-tabs woocommerce header-style-1 mf-elementor-products-navigation',
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile
		];

		$this->add_render_attribute( 'child_wrapper', 'class', $classes );

		$output      = [];
		$header_tabs = [];

		$header_tabs[] = '<div class="tabs-header-nav">';

		$tabs = $settings['tabs'];

		$tab_content = [];

		if ( ! empty ( $tabs ) ) {
			$header_tabs[] = '<ul class="tabs-nav">';
			foreach ( $tabs as $index => $item ) {

				if ( isset( $item['tab_title'] ) ) {
					$header_tabs[] = sprintf( '<li><a href="#" data-href="%s">%s</a></li>', esc_attr( $item['tab_products'] ), esc_html( $item['tab_title'] ) );
				}
			}
			$header_tabs[] = '</ul>';
		}

		if ( ! empty( $settings['view_all_text'] ) ) {
			$header_tabs[] = $this->get_link_control( 'all_link', $settings['all_link'], $settings['view_all_text'], 'link' );
		}

		$header_tabs[] = '</div>';

		$carousel_settings = [
			'autoplay'       => $settings['products_autoplay'],
			'infinite'       => $settings['products_infinite'],
			'autoplay_speed' => $settings['products_autoplay_speed'],
			'speed'          => $settings['products_speed'],
			'slidesToShow'   => $settings['products_slides_to_show'],
			'slidesToScroll' => $settings['products_slides_to_scroll']
		];
		$i                 = 0;
		if ( $tabs ) {
			foreach ( $tabs as $index => $item ) {
				$tab_atts = array(
					'columns'      => $settings['products_slides_to_show'],
					'products'     => $item['tab_products'],
					'order'        => $item['tab_order'],
					'orderby'      => $item['tab_orderby'],
					'per_page'     => intval( $settings['per_page'] ),
					'product_cats' => $settings['product_cats'],
				);

				if ( $i == 0 ) {
					$tab_content[] = sprintf( '<div class="tabs-panel tabs-%s tab-loaded">%s</div>', esc_attr( $item['tab_products'] ), Elementor::get_products( $tab_atts ) );
				} else {
					$this->add_render_attribute( 'wrapper_tab_products_' . $item['tab_products'], 'data-settings', wp_json_encode( $tab_atts ) );
					$tab_content[] = sprintf(
						'<div class="tabs-panel tabs-%s" %s><div class="mf-vc-loading"><div class="mf-vc-loading--wrapper"></div></div></div>',
						esc_attr( $item['tab_products'] ),
						$this->get_render_attribute_string( 'wrapper_tab_products_' . $item['tab_products'] )
					);
				}

				$i ++;
			}
		}

		$this->add_render_attribute( 'child_wrapper', 'data-settings', wp_json_encode( $carousel_settings ) );

		$output[] = sprintf( '<div class="tabs-header">%s</div>', implode( ' ', $header_tabs ) );
		$output[] = sprintf( '<div class="tabs-content">%s</div>', implode( ' ', $tab_content ) );

		return sprintf(
			'<div %s>%s</div>',
			$this->get_render_attribute_string( 'child_wrapper' ),
			implode( '', $output )
		);
	}

	/**
	 * Get products
	 *
	 */
	protected function get_products( $atts ) {
		$query_args = self::get_query_args( $atts );

		$products    = get_posts( $query_args );
		$product_ids = [];
		$output      = [];
		$i           = 0;
		foreach ( $products as $product ) {
			$id = $product->ID;

			if ( ! in_array( $id, $product_ids ) ) {
				$product_ids[] = $id;

				$productw = new \WC_Product( $id );

				$output[] = sprintf(
					'<li class="product">
						<div class="product-thumbnail">
							<a href="%s">%s</a>
						</div>

						<div class="product-inners">
							<h2>
								<a href="%s">%s</a>
							</h2>
							<span class="price">%s</span>
						</div>
					</li>',
					esc_url( $productw->get_permalink() ),
					wp_get_attachment_image( get_post_thumbnail_id( $id ), 'thumbnail' ),
					esc_url( $productw->get_permalink() ),
					$productw->get_title(),
					wp_kses_post( $productw->get_price_html() )
				);
			}
			$i ++;
		}
		remove_filter( 'posts_clauses', array( __CLASS__, 'order_by_rating_post_clauses' ) );

		return sprintf( '<ul class="products">%s</ul>', implode( '', $output ) );
	}

	/**
	 * Build query args from shortcode attributes
	 *
	 * @param array $atts
	 *
	 * @return array
	 */
	protected static function get_query_args( $atts ) {
		$args = array(
			'post_type'              => 'product',
			'post_status'            => 'publish',
			'orderby'                => get_option( 'woocommerce_default_catalog_orderby' ),
			'order'                  => 'DESC',
			'ignore_sticky_posts'    => 1,
			'posts_per_page'         => $atts['per_page'],
			'meta_query'             => WC()->query->get_meta_query(),
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		);

		if ( version_compare( WC()->version, '3.0.0', '>=' ) ) {
			$args['tax_query'] = WC()->query->get_tax_query();
		}

		// Ordering
		if ( 'menu_order' == $args['orderby'] || 'price' == $args['orderby'] ) {
			$args['order'] = 'ASC';
		}

		if ( 'price-desc' == $args['orderby'] ) {
			$args['orderby'] = 'price';
		}

		if ( method_exists( WC()->query, 'get_catalog_ordering_args' ) ) {
			$ordering_args   = WC()->query->get_catalog_ordering_args( $args['orderby'], $args['order'] );
			$args['orderby'] = $ordering_args['orderby'];
			$args['order']   = $ordering_args['order'];

			if ( $ordering_args['meta_key'] ) {
				$args['meta_key'] = $ordering_args['meta_key'];
			}
		}

		if ( ! empty( $atts['category'] ) ) {
			$args['product_cat'] = $atts['category'];
		}

		if ( isset( $atts['type'] ) ) {
			switch ( $atts['type'] ) {
				case 'recent':
					$args['order']   = 'DESC';
					$args['orderby'] = 'date';

					break;

				case 'featured':
					if ( version_compare( WC()->version, '3.0.0', '<' ) ) {
						$args['meta_query'][] = array(
							'key'   => '_featured',
							'value' => 'yes',
						);
					} else {
						$args['tax_query'][] = array(
							'taxonomy' => 'product_visibility',
							'field'    => 'name',
							'terms'    => 'featured',
							'operator' => 'IN',
						);
					}

					break;

				case 'sale':
					$args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
					break;

				case 'best_selling':
					$args['meta_key'] = 'total_sales';
					$args['orderby']  = 'meta_value_num';
					$args['order']    = 'DESC';

					add_filter( 'posts_clauses', array( __CLASS__, 'order_by_popularity_post_clauses' ) );
					break;

				case 'top_rated':
					$args['meta_key'] = '_wc_average_rating';
					$args['orderby']  = 'meta_value_num';
					$args['order']    = 'DESC';
					add_filter( 'posts_clauses', array( __CLASS__, 'order_by_rating_post_clauses' ) );
					break;
			}
		}

		return $args;
	}


	/**
	 * WP Core doens't let us change the sort direction for invidual orderby params - https://core.trac.wordpress.org/ticket/17065.
	 *
	 * This lets us sort by meta value desc, and have a second orderby param.
	 *
	 * @access public
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function order_by_popularity_post_clauses( $args ) {
		global $wpdb;
		$args['orderby'] = "$wpdb->postmeta.meta_value+0 DESC, $wpdb->posts.post_date DESC";

		return $args;
	}


	/**
	 * Order by rating.
	 *
	 * @since  3.2.0
	 *
	 * @param  array $args Query args.
	 *
	 * @return array
	 */
	public static function order_by_rating_post_clauses( $args ) {
		global $wpdb;

		$args['where']   .= " AND $wpdb->commentmeta.meta_key = 'rating' ";
		$args['join']    .= "LEFT JOIN $wpdb->comments ON($wpdb->posts.ID = $wpdb->comments.comment_post_ID) LEFT JOIN $wpdb->commentmeta ON($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)";
		$args['orderby'] = "$wpdb->commentmeta.meta_value DESC";
		$args['groupby'] = "$wpdb->posts.ID";

		return $args;
	}
}