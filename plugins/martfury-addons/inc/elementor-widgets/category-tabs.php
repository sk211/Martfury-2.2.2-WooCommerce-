<?php

namespace MartfuryAddons\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Category_Tabs extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'martfury-category-tabs';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Category Tabs', 'martfury' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-tabs';
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

		$this->start_controls_section(
			'section_heading',
			[ 'label' => esc_html__( 'Heading', 'martfury' ) ]
		);

		$this->add_control(
			'title', [
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Search Trending', 'martfury' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'subtitle', [
				'label'       => esc_html__( 'Subtitle', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Updated at 9:00AM', 'martfury' ),
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_hot_trending',
			[ 'label' => esc_html__( 'Hot Trending', 'martfury' ) ]
		);

		$this->add_control(
			'enable_hot_trending',
			[
				'label'     => esc_html__( 'Enable', 'martfury' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'martfury' ),
				'label_off' => esc_html__( 'No', 'martfury' ),
				'default'   => 'no',
				'options'   => [
					'yes' => esc_html__( 'Yes', 'martfury' ),
					'no'  => esc_html__( 'No', 'martfury' ),
				],
			]
		);

		$this->add_control(
			'icon_hot_trending_type',
			[
				'label'   => esc_html__( 'Icon Type', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'icons'        => esc_html__( 'Default Icons', 'martfury' ),
					'custom_icons' => esc_html__( 'Custom Icon', 'martfury' ),
				],
				'default' => 'icons',
				'toggle'  => false,
			]
		);

		$this->add_control(
			'icon_hot_trending',
			[
				'label'     => esc_html__( 'Icon', 'martfury' ),
				'type'      => Controls_Manager::ICON,
				'default'   => 'icon-star',
				'condition' => [
					'icon_hot_trending_type' => 'icons',
				],
			]
		);

		$this->add_control(
			'custom_icon_hot_trending',
			[
				'label'            => esc_html__( 'Icon', 'martfury' ),
				'type'             => Controls_Manager::ICONS,
				'default'          => [
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition'        => [
					'icon_hot_trending_type' => 'custom_icons',
				],
			]
		);

		$this->add_control(
			'title_hot_trending', [
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Hot Trending', 'martfury' ),
				'label_block' => true,
			]
		);

		$this->end_controls_section();


		$count = apply_filters( 'martfury_category_tabs_count', 10 );

		if ( $count > 0 ) {
			for ( $i = 1; $i <= $count; $i ++ ) {
				$this->start_controls_section(
					'section_content_' . $i,
					[ 'label' => sprintf( '%s %s', esc_html__( 'Category Tab', 'martfury' ), $i ) ]
				);

				$this->add_control(
					'enable_tab_' . $i,
					[
						'label'     => esc_html__( 'Enable', 'martfury' ),
						'type'      => Controls_Manager::SWITCHER,
						'label_on'  => esc_html__( 'Yes', 'martfury' ),
						'label_off' => esc_html__( 'No', 'martfury' ),
						'default'   => 'yes',
						'options'   => [
							'yes' => esc_html__( 'Yes', 'martfury' ),
							'no'  => esc_html__( 'No', 'martfury' ),
						],
					]
				);

				$this->add_control(
					'icon_type_' . $i,
					[
						'label'   => esc_html__( 'Icon Type', 'martfury' ),
						'type'    => Controls_Manager::SELECT,
						'options' => [
							'icons'        => esc_html__( 'Default Icons', 'martfury' ),
							'custom_icons' => esc_html__( 'Custom Icon', 'martfury' ),
						],
						'default' => 'icons',
						'toggle'  => false,
					]
				);

				$this->add_control(
					'icon_' . $i,
					[
						'label'     => esc_html__( 'Icon', 'martfury' ),
						'type'      => Controls_Manager::ICON,
						'default'   => 'icon-star',
						'condition' => [
							'icon_type_' . $i => 'icons',
						],
					]
				);

				$this->add_control(
					'custom_icon_' . $i,
					[
						'label'            => esc_html__( 'Icon', 'martfury' ),
						'type'             => Controls_Manager::ICONS,
						'default'          => [
							'value'   => 'fas fa-star',
							'library' => 'fa-solid',
						],
						'condition'        => [
							'icon_type_' . $i => 'custom_icons',
						],
					]
				);

				$this->add_control(
					'tab_title_' . $i, [
						'label'       => esc_html__( 'Title', 'martfury' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => sprintf( '%s #%s', esc_html__( 'Tab', 'martfury' ), $i ),
						'label_block' => true,
					]
				);

				$repeater = new \Elementor\Repeater();

				$repeater->add_control(
					'image_tag',
					[
						'label'   => esc_html__( 'Choose Image', 'martfury' ),
						'type'    => Controls_Manager::MEDIA,
						'default' => [
							'url' => 'https://via.placeholder.com/150x150/f8f8f8?text=150x150',
						],
					]
				);

				$repeater->add_control(
					'title_tag', [
						'label'       => esc_html__( 'Title', 'martfury' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => esc_html__( '#tag1', 'martfury' ),
						'label_block' => true,
					]
				);

				$repeater->add_control(
					'link_tag', [
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

				$repeater->add_control(
					'hot_trending',
					[
						'label'     => esc_html__( 'Hot Trending', 'martfury' ),
						'type'      => Controls_Manager::SWITCHER,
						'label_on'  => esc_html__( 'Yes', 'martfury' ),
						'label_off' => esc_html__( 'No', 'martfury' ),
						'default'   => 'no',
						'options'   => [
							'yes' => esc_html__( 'Yes', 'martfury' ),
							'no'  => esc_html__( 'No', 'martfury' ),
						],
					]
				);

				$this->add_control(
					'tags_' . $i,
					[
						'label'         => esc_html__( 'Tags', 'martfury' ),
						'type'          => Controls_Manager::REPEATER,
						'fields'        => $repeater->get_controls(),
						'default'       => [
							[
								'title_tag' => '#tag1' . ( $i - 1 )
							],
							[
								'title_tag' => '#tag2' . ( $i - 1 )
							],
							[
								'title_tag' => '#tag3' . ( $i - 1 )
							],
							[
								'title_tag' => '#tag4' . ( $i - 1 )
							],
							[
								'title_tag' => '#tag5' . ( $i - 1 )
							],
							[
								'title_tag' => '#tag6' . ( $i - 1 )
							],
							[
								'title_tag' => '#tag7' . ( $i - 1 )
							],
							[
								'title_tag' => '#tag8' . ( $i - 1 )
							]
						],
						'title_field'   => '{{{ title_tag }}}',
						'prevent_empty' => false
					]
				);

				$this->end_controls_section();
			}
		}

		$this->register_heading_style_controls();
		$this->register_tab_header_style_controls();
		$this->register_tab_content_style_controls();


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
			'section_heading_style',
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
					'{{WRAPPER}} .mf-category-tabs .tabs-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-title' => 'background-color: {{VALUE}};',
				],
				'default'   => '',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'heading_border',
				'label'     => esc_html__( 'Border', 'martfury' ),
				'selector'  => '{{WRAPPER}} .mf-category-tabs .tabs-title',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_style_divider',
			[
				'label' => '',
				'type'  => Controls_Manager::DIVIDER,
			]
		);

		$this->start_controls_tabs( 'heading_style_settings' );

		$this->start_controls_tab( 'heading_tab_title', [ 'label' => esc_html__( 'Title', 'martfury' ) ] );

		$this->add_responsive_control(
			'heading_title_space',
			[
				'label'     => esc_html__( 'Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-title h2'      => 'margin-right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .mf-category-tabs .tabs-title h2' => 'margin-left: {{SIZE}}{{UNIT}};margin-right:0',
				],
			]
		);

		$this->add_control(
			'heading_title_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-title h2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_title_typography',
				'selector' => '{{WRAPPER}} .mf-category-tabs .tabs-title h2',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'heading_tab_subtitle', [ 'label' => esc_html__( 'Subtitle', 'martfury' ) ] );

		$this->add_control(
			'heading_subtitle_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-title  span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_subtitle_typography',
				'selector' => '{{WRAPPER}} .mf-category-tabs .tabs-title  span',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register the widget tab header style controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_tab_header_style_controls() {

		$this->start_controls_section(
			'section_tab_header_style',
			[
				'label' => esc_html__( 'Tab Header', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'show_button',
			[
				'label'     => esc_html__( 'Show Tab Header', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'block' => esc_html__( 'Show', 'martfury' ),
					'none'  => esc_html__( 'Hide', 'martfury' ),
				],
				'default'   => 'block',
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-header' => 'display: {{VALUE}}',

				],
			]
		);

		$this->add_responsive_control(
			'tab_header_padding',
			[
				'label'      => esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-category-tabs .tabs-header ul'    => 'padding-left: {{LEFT}}{{UNIT}};padding-right: {{RIGHT}}{{UNIT}};',
					'{{WRAPPER}} .mf-category-tabs .tabs-header ul li' => 'padding-top: {{TOP}}{{UNIT}};padding-bottom:{{BOTTOM}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tab_header_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-header' => 'background-color: {{VALUE}};',
				],
				'default'   => '',
			]
		);

		$this->add_control(
			'tab_header_border_style',
			[
				'label'     => esc_html__( 'Border Style', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'dotted' => esc_html__( 'Dotted', 'martfury' ),
					'dashed' => esc_html__( 'Dashed', 'martfury' ),
					'solid'  => esc_html__( 'Solid', 'martfury' ),
					'none'   => esc_html__( 'None', 'martfury' ),
				],
				'default'   => 'solid',
				'toggle'    => false,
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-header' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_header_border_width',
			[
				'label'     => esc_html__( 'Border Width', 'martfury' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-header' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'tab_header_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-header' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_header_style_divider',
			[
				'label' => '',
				'type'  => Controls_Manager::DIVIDER,
			]
		);

		$this->start_controls_tabs( 'tab_header_style_settings' );

		$this->start_controls_tab( 'tab_header_normal', [ 'label' => esc_html__( 'Normal Item', 'martfury' ) ] );


		$this->add_control(
			'tab_header_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-header ul li h2, {{WRAPPER}} .mf-category-tabs .tabs-header ul li a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_header_active', [ 'label' => esc_html__( 'Active Item', 'martfury' ) ] );

		$this->add_control(
			'tab_header_active_item_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-header ul li a.active, {{WRAPPER}} .mf-category-tabs .tabs-header ul li a.active h2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_header_active_item_border_width',
			[
				'label'     => esc_html__( 'Border Bottom Width', 'martfury' ),
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
					'{{WRAPPER}} .mf-category-tabs .tabs-header ul li:after' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'tab_header_active_item_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-header ul li:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'tab_header_icon',
			[
				'label'     => esc_html__( 'Icon', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_header_icon_typography',
				'selector' => '{{WRAPPER}} .mf-category-tabs .tabs-header ul li .mf-icon',
			]
		);

		$this->add_responsive_control(
			'tab_header_svg_font_size',
			[
				'label'     => esc_html__( 'SVG Font Size', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 40,
				],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-header ul li .icon-svg' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tab_header_title',
			[
				'label'     => esc_html__( 'Title', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_header_title_spacing',
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
					'{{WRAPPER}} .mf-category-tabs .tabs-header ul li h2' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_header_title_typography',
				'selector' => '{{WRAPPER}} .mf-category-tabs .tabs-header ul li h2',
			]
		);

		$this->add_control(
			'tab_header_arrows',
			[
				'label'     => esc_html__( 'Arrows', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tab_header_arrow_prev_position',
			[
				'label'              => esc_html__( 'Previous Position', 'martfury' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', 'em', '%' ],
				'allowed_dimensions' => [ 'top', 'left' ],
				'selectors'          => [
					'{{WRAPPER}} .mf-category-tabs .tabs-header .slick-prev-arrow' => 'top: {{TOP}}{{UNIT}};left: {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'tab_header_arrow_next_position',
			[
				'label'              => esc_html__( 'Next Position', 'martfury' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px', 'em', '%' ],
				'allowed_dimensions' => [ 'top', 'right' ],
				'selectors'          => [
					'{{WRAPPER}} .mf-category-tabs .tabs-header .slick-next-arrow' => 'top: {{TOP}}{{UNIT}};right: {{RIGHT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'tab_header_arrow_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-header .slick-arrow' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_header_arrow_hover_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-header .slick-arrow:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_header_arrow_disable_color',
			[
				'label'     => esc_html__( 'Disabled Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-header .slick-arrow.slick-disabled' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_header_arrow_typography',
				'selector' => '{{WRAPPER}} .mf-category-tabs .tabs-header .slick-arrow',
			]
		);


		$this->end_controls_section();
	}

	/**
	 * Register the widget tab content style controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_tab_content_style_controls() {

		$this->start_controls_section(
			'section_tab_content_style',
			[
				'label' => esc_html__( 'Tab Content', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'tab_content_padding',
			[
				'label'      => esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-category-tabs .tabs-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tab_content_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-content' => 'background-color: {{VALUE}};',
				],
				'default'   => '',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'tab_content_border',
				'label'     => esc_html__( 'Border', 'martfury' ),
				'selector'  => '{{WRAPPER}} .mf-category-tabs .tabs-content',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tab_content_item',
			[
				'label'     => esc_html__( 'Item', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'tab_content_item_spacing',
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
					'{{WRAPPER}} .mf-category-tabs .tabs-content ul li' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'tab_content_image',
			[
				'label'     => esc_html__( 'Image', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'label'     => esc_html__( 'Image Size', 'martfury' ),
				'name'      => 'image',
				'default'   => 'full',
				'separator' => 'none',
			]
		);

		$this->add_control(
			'tab_content_title',
			[
				'label'     => esc_html__( 'Title', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_content_title_spacing',
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
					'{{WRAPPER}} .mf-category-tabs .tabs-content ul li h2' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'tab_content_title_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-content ul li h2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_content_hover_title_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-category-tabs .tabs-content ul li a:hover h2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_content_title_typography',
				'selector' => '{{WRAPPER}} .mf-category-tabs .tabs-content ul li h2',
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
		$settings   = $this->get_settings_for_display();
		$tabs_count = apply_filters( 'martfury_category_tabs_count', 10 );


		$tab_title = '';
		if ( $settings['title'] ) {
			$tab_title = sprintf( '<h2>%s</h2>', $settings['title'] );
		}
		if ( $settings['subtitle'] ) {
			$tab_title .= sprintf( '<span>%s</span>', $settings['subtitle'] );
		}

		$hot_content = array();

		$tab_header      = array();
		$tab_content     = array();
		$section_enabled = 0;
		for ( $i = 1; $i <= $tabs_count; $i ++ ) {

			if ( isset( $settings[ 'enable_tab_' . $i ] ) && $settings[ 'enable_tab_' . $i ] != 'yes' ) {
				continue;
			}

			$section_enabled ++;
			$icon            = '';
			$icon_class = '';
			if ( $settings[ 'icon_type_' . $i ] == 'icons' ) {
				if ( $settings[ 'icon_' . $i ] ) {
					$icon = '<i class="' . esc_attr( $settings[ 'icon_' . $i ] ) . '"></i>';
				}
			} elseif ( $settings[ 'icon_type_' . $i ] == 'custom_icons' ) {
				if ( $settings[ 'custom_icon_' . $i ] && \Elementor\Icons_Manager::is_migration_allowed() ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon( $settings[ 'custom_icon_' . $i ], [ 'aria-hidden' => 'true' ] );
					$icon = ob_get_clean();

					if( $settings['custom_icon_' . $i]['library'] == 'svg' ) {
						$icon_class = 'icon-svg';
					}
				}
			}

			$tab_header[] = sprintf(
				'<li><a href="#"><span class="mf-icon %s">%s</span><h2>%s</h2></a></li>',
				$icon_class,
				$icon,
				isset( $settings[ 'tab_title_' . $i ] ) ? $settings[ 'tab_title_' . $i ] : ''
			);

			$tags = isset( $settings[ 'tags_' . $i ] ) ? $settings[ 'tags_' . $i ] : '';

			if ( $tags ) {
				$tab_content[] = '<div class="tabs-panel"><ul>';
				foreach ( $tags as $index => $item ) {
					$link_key = 'link_' . $index;

					$settings['image'] = $item['image_tag'];
					$tab_image         = Group_Control_Image_Size::get_attachment_image_html( $settings );
					$tab_item          = sprintf( '<span class="t-imgage">%s</span><h2>%s</h2>', $tab_image, $item['title_tag'] );

					$item_content  = sprintf(
						'<li>%s</li>',
						$this->get_link_control( $link_key, $item['link_tag'], $tab_item, '' )
					);
					$tab_content[] = $item_content;

					if ( isset( $item['hot_trending'] ) && $item['hot_trending'] == 'yes' ) {
						$hot_content[] = $item_content;
					}
				}
				$tab_content[] = '</ul></div>';
			}
		}

		if ( $settings['enable_hot_trending'] == 'yes' && ! empty( $hot_content ) ) {
			$icon_hot = '';
			$icon_hot_class = '';
			if ( $settings['icon_hot_trending_type'] == 'icons' ) {
				if ( $settings['icon_hot_trending'] ) {
					$icon_hot = '<i class="' . esc_attr( $settings['icon_hot_trending'] ) . '"></i>';
				}
			} elseif ( $settings['icon_hot_trending_type'] == 'custom_icons' ) {
				if ( $settings['custom_icon_hot_trending'] && \Elementor\Icons_Manager::is_migration_allowed() ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon( $settings['custom_icon_hot_trending'], [ 'aria-hidden' => 'true' ] );
					$icon_hot = ob_get_clean();

					if( $settings['custom_icon_hot_trending']['library'] == 'svg' ) {
						$icon_hot_class = 'icon-svg';
					}
				}
			}
			$hot_header = sprintf(
				'<li><a href="#"><span class="mf-icon %s">%s</span><h2>%s</h2></a></li>',
				$icon_hot_class,
				$icon_hot,
				$settings['title_hot_trending']
			);

			array_unshift( $tab_header, $hot_header );

			$hot_content_html = sprintf( '<div class="tabs-panel"><ul>%s</ul></div>', implode( ' ', $hot_content ) );

			array_unshift( $tab_content, $hot_content_html );
		}

		$classes = '';
		if ( $settings['enable_hot_trending'] == 'yes' && $section_enabled == 0 ) {
			$classes = 'single-tab';
		} elseif ( $settings['enable_hot_trending'] != 'yes' && $section_enabled == 1 ) {
			$classes = 'single-tab';
		}

		$this->add_render_attribute( 'wrapper', 'class', [
			'mf-category-tabs',
			$classes
		] );

		?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php
			if ( $tab_title ) {
				echo sprintf( '<div class="tabs-title">%s</div>', $tab_title );
			}
			?>
            <div class="martfury-tabs">
                <div class="tabs-header">
                    <ul class="tabs-nav">
						<?php echo implode( ' ', $tab_header ); ?>
                    </ul>
                </div>
                <div class="tabs-content">
					<?php echo implode( ' ', $tab_content ); ?>
                </div>
            </div>

        </div>
		<?php

	}

	/**
	 * Render icon box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected
	function _content_template() {
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
}