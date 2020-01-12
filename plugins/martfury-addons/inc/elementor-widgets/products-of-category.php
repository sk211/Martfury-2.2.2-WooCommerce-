<?php

namespace MartfuryAddons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use MartfuryAddons\Elementor;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Products_Of_Category extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'martfury-products-of-category';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Products of Category', 'martfury' );
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
		$this->register_products_controls();

		// Style Tab
		$this->register_heading_style_controls();
		$this->register_banners_style_controls();
		$this->register_products_style_controls();
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
			'title',
			[
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Category Name', 'martfury' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'c_link', [
				'label'         => esc_html__( 'Link', 'martfury' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'martfury' ),
				'show_external' => true,
				'default'       => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
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
				'default'     => '',
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
				'label'         => esc_html__( 'Quick Link Items', 'martfury' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'link_text' => esc_html__( 'Best Seller', 'martfury' ),
						'link_url'  => '#',
					],
					[
						'link_text' => esc_html__( 'New Arrivals', 'martfury' ),
						'link_url'  => '#',
					],
					[
						'link_text' => esc_html__( 'On Sales', 'martfury' ),
						'link_url'  => '#',
					],

				],
				'title_field'   => '{{{ link_text }}}',
				'prevent_empty' => false
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'view_all_tab', [ 'label' => esc_html__( 'View All', 'martfury' ) ] );

		$this->add_control(
			'view_all_text',
			[
				'label'   => esc_html__( 'View All Text', 'martfury' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'View All', 'martfury' ),
			]
		);

		$this->add_control(
			'view_all_link', [
				'label'         => esc_html__( 'View All Link', 'martfury' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'martfury' ),
				'show_external' => true,
				'default'       => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
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

		$this->add_control(
			'banners_carousel',
			[
				'label'     => esc_html__( 'Enable', 'martfury' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'martfury' ),
				'label_off' => esc_html__( 'No', 'martfury' ),
				'default'   => 'yes',
			]
		);

		$this->start_controls_tabs( 'banners_content_settings' );

		$this->start_controls_tab( 'banner_images_tab', [ 'label' => esc_html__( 'Images', 'martfury' ) ] );

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'image',
			[
				'label'   => esc_html__( 'Choose Image', 'martfury' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/269x639/e7eff1?text=269x639+Banner',
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
					'url'         => '#',
					'is_external' => true,
					'nofollow'    => true,
				],
			]
		);

		$this->add_control(
			'banners',
			[
				'label'         => esc_html__( 'Images', 'martfury' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'image'      => [
							'url' => 'https://via.placeholder.com/269x639/e7eff1?text=269x639+Banner'
						],
						'image_link' => [
							'url' => '#'
						]
					],
					[
						'image'      => [
							'url' => 'https://via.placeholder.com/269x639/e7eff1?text=269x639+Banner+2'
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

		$this->start_controls_tab( 'carousel_settings_tab', [ 'label' => esc_html__( 'Carousel Settings', 'martfury' ) ] );

		$this->add_control(
			'autoplay',
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
			'autoplay_speed',
			[
				'label'   => esc_html__( 'Autoplay Speed', 'martfury' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 5000,
			]
		);

		$this->add_control(
			'speed',
			[
				'label'   => esc_html__( 'Speed', 'martfury' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 800,
			]
		);

		$this->add_control(
			'infinite',
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
			'arrows',
			[
				'label'     => esc_html__( 'Arrows', 'martfury' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'martfury' ),
				'label_off' => esc_html__( 'Hide', 'martfury' ),
				'default'   => 'yes',
				'options'   => [
					'yes' => esc_html__( 'Yes', 'martfury' ),
					'no'  => esc_html__( 'No', 'martfury' ),
				],
			]
		);

		$this->add_control(
			'dots',
			[
				'label'     => esc_html__( 'Dots', 'martfury' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'martfury' ),
				'label_off' => esc_html__( 'Hide', 'martfury' ),
				'default'   => 'yes',
				'options'   => [
					'yes' => esc_html__( 'Yes', 'martfury' ),
					'no'  => esc_html__( 'No', 'martfury' ),
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register the widget products controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_products_controls() {

		$this->start_controls_section(
			'section_product_content',
			[
				'label' => esc_html__( 'Products', 'martfury' ),
			]
		);

		$this->add_control(
			'products',
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
			'product_tags',
			[
				'label'       => esc_html__( 'Product Tags', 'martfury' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => Elementor::get_taxonomy( 'product_tag' ),
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
				'default' => 6,
				'min'     => 2,
				'max'     => 50,
				'step'    => 1,
			]
		);

		$this->add_control(
			'columns',
			[
				'label'   => esc_html__( 'Product Columns', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'3' => esc_html__( '3 Columns', 'martfury' ),
					'4' => esc_html__( '4 Columns', 'martfury' ),
				],
				'default' => '3',
				'toggle'  => false,
			]
		);

		$this->add_control(
			'orderby',
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
					'products' => [ 'top_rated', 'sale', 'featured' ],
				],
			]
		);

		$this->add_control(
			'order',
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
					'products' => [ 'top_rated', 'sale', 'featured' ],
				],
			]
		);

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
					'{{WRAPPER}} .mf-products-of-category .cats-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category .cats-info' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'heading_border',
				'label'    => esc_html__( 'Border', 'martfury' ),
				'selector' => '{{WRAPPER}} .mf-products-of-category .cats-info',
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

		$this->add_responsive_control(
			'heading_title_spacing',
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
					'{{WRAPPER}} .mf-products-of-category .cats-info h2' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_title_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category .cats-info .cat-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_title_hover_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category .cats-info .cat-title:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_title_typography',
				'selector' => '{{WRAPPER}} .mf-products-of-category .cats-info h2',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'heading_links_style_tab', [ 'label' => esc_html__( 'Links', 'martfury' ) ] );

		$this->add_responsive_control(
			'quick_links',
			[
				'label'     => esc_html__( 'Items Display', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'block' => esc_html__( 'Vertical', 'martfury' ),
					'flex'  => esc_html__( 'Horizontal', 'martfury' ),
					'none'  => esc_html__( 'None', 'martfury' ),
				],
				'default'   => 'block',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category .cats-info .extra-links' => 'display: {{VALUE}}',

				],
			]
		);

		$this->add_responsive_control(
			'heading_link_items_padding',
			[
				'label'     => esc_html__( 'Items Padding', 'martfury' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category .cats-info .extra-links li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};margin-top:0;margin-bottom:0',
				],
			]
		);

		$this->add_control(
			'heading_links_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category .cats-info .extra-links li a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_links_hover_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category .cats-info .extra-links li a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_links_typography',
				'selector' => '{{WRAPPER}} .mf-products-of-category .cats-info .extra-links li a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'heading_view_all_style_tab', [ 'label' => esc_html__( 'View All', 'martfury' ) ] );

		$this->add_responsive_control(
			'heading_view_all_spacing',
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
					'{{WRAPPER}} .mf-products-of-category .cats-info .footer-link' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_view_all_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category .cats-info .footer-link .link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_view_all_hover_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category .cats-info .footer-link .link:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_view_all_typography',
				'selector' => '{{WRAPPER}} .mf-products-of-category .cats-info .footer-link .link',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


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
			'section_style_sliders',
			[
				'label' => esc_html__( 'Sliders', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sliders_arrow_width',
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
					'{{WRAPPER}} .mf-products-of-category .images-slider .slick-arrow' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'sliders_arrow_height',
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
					'{{WRAPPER}} .mf-products-of-category .images-slider .slick-arrow' => 'height: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sliders_arrow_typography',
				'selector' => '{{WRAPPER}} .mf-products-of-category .images-slider .slick-arrow',
			]
		);

		$this->start_controls_tabs( 'sliders_normal_settings' );


		$this->start_controls_tab( 'sliders_normal', [ 'label' => esc_html__( 'Normal', 'martfury' ) ] );

		$this->add_control(
			'sliders_arrow_background',
			[
				'label'     => esc_html__( 'Arrow Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category .images-slider .slick-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sliders_arrow_color',
			[
				'label'     => esc_html__( 'Arrow Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category .images-slider .slick-arrow' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'sliders_hover', [ 'label' => esc_html__( 'Hover', 'martfury' ) ] );

		$this->add_control(
			'sliders_arrow_hover_background',
			[
				'label'     => esc_html__( 'Arrow Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category .images-slider .slick-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sliders_arrow_hover_color',
			[
				'label'     => esc_html__( 'Arrow Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category .images-slider .slick-arrow:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'sliders_dots_width',
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
					'{{WRAPPER}} .mf-products-of-category .images-slider .slick-dots li button' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sliders_dots_background',
			[
				'label'     => esc_html__( 'Dots Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category .images-slider .slick-dots li button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'sliders_dots_active_background',
			[
				'label'     => esc_html__( 'Dots Active Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category .images-slider .slick-dots li.slick-active button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register the widget products style controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_products_style_controls() {

		$this->start_controls_section(
			'section_style_products',
			[
				'label' => esc_html__( 'Products', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'products_border',
				'label'    => esc_html__( 'Border', 'martfury' ),
				'selector' => '{{WRAPPER}} .mf-products-of-category .products-box',
			]
		);


		$this->add_control(
			'products_item_border_color',
			[
				'label'     => esc_html__( 'Product Item Border Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-of-category .products-box ul.products li' => 'border-color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

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
				'mf-products-of-category woocommerce',
				$settings['quick_links'] != 'none' ? '' : 'no-links-group',
				$settings['banners_carousel'] == 'yes' ? '' : 'no-banners-carousel'
			]
		);

		$carousel_settings = [
			'infinite'       => $settings['infinite'],
			'autoplay'       => $settings['autoplay'],
			'autoplay_speed' => $settings['autoplay_speed'],
			'speed'          => $settings['speed'],
			'arrows'         => $settings['arrows'],
			'dots'           => $settings['dots']
		];
		$this->add_render_attribute( 'wrapper', 'data-settings', wp_json_encode( $carousel_settings ) );
		?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="cats-info">
                <div class="cats-inner">
                    <h2><?php echo $this->get_link_control( 'link', $settings['c_link'], $settings['title'], 'cat-title' ) ?></h2>
					<?php if ( $settings['quick_links'] != 'none' ) : ?>
                        <ul class="extra-links">
							<?php
							$links = $settings['links_group'];
							if ( $links ) {
								foreach ( $links as $index => $item ) {
									$link_key = 'extra_link' . $index;
									echo sprintf( '<li>%s</li>', $this->get_link_control( $link_key, $item['link_url'], $item['link_text'], 'extra-link' ) );
								}
							}

							?>
                        </ul>
					<?php endif; ?>
                </div>
                <div class="footer-link">
					<?php echo $this->get_link_control( 'footer_link', $settings['view_all_link'], $settings['view_all_text'], 'link' ) ?>
                </div>
            </div>
			<?php if ( $settings['banners_carousel'] == 'yes' ) : ?>
                <div class="images-slider">
                    <div class="images-list">
						<?php
						$banners = $settings['banners'];
						if ( $banners ) {
							foreach ( $banners as $index => $item ) {
								if ( empty( $item['image'] ) ) {
									continue;
								}
								$link_key               = 'banner_link' . $index;
								$settings['image']      = $item['image'];
								$settings['image_size'] = 'full';
								$image_url              = Group_Control_Image_Size::get_attachment_image_html( $settings );
								echo $this->get_link_control( $link_key, $item['image_link'], $image_url, 'image-item' );
							}
						}
						?>
                    </div>
                </div>
			<?php endif; ?>
            <div class="products-box">
				<?php
				echo Elementor::get_products( $settings );
				?>
            </div>
        </div>
		<?php
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
	protected
	function _content_template() {
	}
}