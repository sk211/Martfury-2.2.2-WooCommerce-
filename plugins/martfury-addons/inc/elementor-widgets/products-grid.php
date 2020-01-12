<?php

namespace MartfuryAddons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Widget_Base;
use MartfuryAddons\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Products_Grid extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'martfury-products-grid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Products Grid', 'martfury' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-grid';
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

		$this->start_controls_tabs( 'tabs_heading' );
		$this->start_controls_tab(
			'title_tab',
			[
				'label' => esc_html__( 'Title', 'martfury' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Heading Name', 'martfury' ),
				'placeholder' => esc_html__( 'Enter your title', 'martfury' ),
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
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
			]
		);

		$this->end_controls_tab();

		// Group

		$this->start_controls_tab(
			'links_group_tab',
			[
				'label' => esc_html__( 'Links Group', 'martfury' ),
			]
		);


		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Heading Item', 'martfury' ),
				'placeholder' => esc_html__( 'Enter your title', 'martfury' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
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
		$this->add_control(
			'link_group',
			[
				'label'         => esc_html__( 'Links Group', 'martfury' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'title' => esc_html__( 'Link 1', 'martfury' ),
						'link'  => '#'
					],
					[
						'title' => esc_html__( 'Link 2', 'martfury' ),
						'link'  => '#'
					],
					[
						'title' => esc_html__( 'Link 3', 'martfury' ),
						'link'  => '#'
					]
				],
				'title_field'   => '{{{ title }}}',
				'prevent_empty' => false
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_link',
			[
				'label' => esc_html__( 'View All', 'martfury' ),
			]
		);

		$this->add_control(
			'view_all_text',
			[
				'label'       => esc_html__( 'Text', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'View All', 'martfury' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'view_all_link', [
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

		// Products

		$this->start_controls_section(
			'section_products',
			[ 'label' => esc_html__( 'Products', 'martfury' ) ]
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
			'per_page',
			[
				'label'   => esc_html__( 'Total Products', 'martfury' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 10,
				'min'     => 2,
				'max'     => 50,
				'step'    => 1,
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
			'columns',
			[
				'label'   => esc_html__( 'Columns', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'5' => esc_html__( '5', 'martfury' ),
					'4' => esc_html__( '4', 'martfury' ),
					'3' => esc_html__( '3', 'martfury' ),
					'6' => esc_html__( '6', 'martfury' ),
					'7' => esc_html__( '7', 'martfury' ),
				],
				'default' => '5',
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'     => esc_html__( 'Order By', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''           => '',
					'date'       => esc_html__( 'Date', 'martfury' ),
					'title'      => esc_html__( 'Title', 'martfury' ),
					'menu_order' => esc_html__( 'Menu Order', 'martfury' ),
					'rand'       => esc_html__( 'Random', 'martfury' ),
				],
				'default'   => '',
				'toggle'    => false,
				'condition' => [
					'products' => [ 'recent', 'top_rated', 'sale', 'featured' ],
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'     => esc_html__( 'Order', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''     => '',
					'asc'  => esc_html__( 'ASC', 'martfury' ),
					'desc' => esc_html__( 'DESC', 'martfury' ),
				],
				'default'   => '',
				'toggle'    => false,
				'condition' => [
					'products' => [ 'recent', 'top_rated', 'sale', 'featured' ],
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Tab Style
		 */
		// Heading
		$this->start_controls_section(
			'section_heading_style',
			[
				'label' => esc_html__( 'Heading', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_text_align',
			[
				'label'       => esc_html__( 'Text Align', 'martfury' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'martfury' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'     => [
						'title' => esc_html__( 'Center', 'martfury' ),
						'icon'  => 'fa fa-align-center',
					],
					'flex-end'   => [
						'title' => esc_html__( 'Right', 'martfury' ),
						'icon'  => 'fa fa-align-right',
					],
					''           => [
						'title' => esc_html__( 'Space Between', 'martfury' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'default'     => '',
				'selectors'   => [
					'{{WRAPPER}} .mf-products-grid .cat-header' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'heading_padding',
			[
				'label'      => esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-grid .cat-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_responsive_control(
			'heading_space',
			[
				'label'     => esc_html__( 'Bottom Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-products-grid .cat-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-grid .cat-header' => 'background-color: {{VALUE}};',
				],
				'default'   => '',
			]
		);
		$this->add_responsive_control(
			'heading_border_width',
			[
				'label'     => esc_html__( 'Border Width', 'martfury' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mf-products-grid .cat-header' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
				'default'   => [
					'top'    => '0',
					'right'  => '0',
					'bottom' => '1',
					'left'   => '0',
				],
			]
		);
		$this->add_control(
			'heading_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgb(225,225,225)',
				'selectors' => [
					'{{WRAPPER}} .mf-products-grid .cat-header' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'heading_border_style',
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
					'{{WRAPPER}} .mf-products-grid .cat-header' => 'border-style: {{VALUE}};',
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

		$this->start_controls_tabs( 'heading_style_settings' );

		$this->start_controls_tab( 'heading_title', [ 'label' => esc_html__( 'Title', 'martfury' ) ] );

		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-products-grid .cat-header .cat-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-grid .cat-header .cat-title a, {{WRAPPER}} .mf-products-grid .cat-header .cat-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-grid .cat-header .cat-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mf-products-grid .cat-header .cat-title',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'links_title', [ 'label' => esc_html__( 'Links', 'martfury' ) ] );

		$this->add_responsive_control(
			'show_product_links',
			[
				'label'     => esc_html__( 'Links', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'inline-block' => esc_html__( 'Show', 'martfury' ),
					'none'         => esc_html__( 'Hide', 'martfury' ),
				],
				'default'   => 'inline-block',
				'selectors' => [
					'{{WRAPPER}} .mf-products-grid .cat-header .extra-links li:not(.view-all-link)' => 'display: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'product_link_items_spacing',
			[
				'label'     => esc_html__( 'Items Spacing', 'martfury' ),
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
					'{{WRAPPER}} .mf-products-grid .cat-header .extra-links li'                    => 'padding-left: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .mf-products-grid .cat-header .extra-links li:first-child'        => 'padding-left: 0',
					'.rtl {{WRAPPER}} .mf-products-grid .cat-header .extra-links li:last-child'  => 'padding-left: 0',
					'.rtl {{WRAPPER}} .mf-products-grid .cat-header .extra-links li:first-child' => 'padding-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'product_link_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-products-grid .cat-header .extra-links li a, {{WRAPPER}} .mf-products-grid .cat-header .extra-links li' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_link_hover_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-products-grid .cat-header .extra-links li a:hover' => 'color: {{VALUE}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'product_link_typography',
				'selector' => '{{WRAPPER}} .mf-products-grid .cat-header .extra-links li',
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

		$classes = [
			'mf-products-grid woocommerce',
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$header_tabs   = [];
		$header_tabs[] = '<div class="cat-header">';
		if ( ! empty( $settings['title'] ) ) {
			$header_tabs[] = sprintf( '<h2 class="cat-title">%s</h2>', $this->get_link_control( 'link', $settings['link'], $settings['title'], '' ) );
		}


		$link_group = $settings['link_group'];


		$header_tabs[] = '<ul class="extra-links">';
		if ( ! empty ( $link_group ) ) {
			foreach ( $link_group as $index => $item ) {
				$link_key = 'link_' . $index;

				$link = $this->get_link_control( $link_key, $item['link'], $item['title'], 'extra-link' );

				$header_tabs[] = sprintf( '<li>%s</li>', $link );
			}
		}

		if ( ! empty( $settings['view_all_text'] ) ) {
			$header_tabs[] = '<li class="view-all-link">' . $this->get_link_control( 'view_all_link', $settings['view_all_link'], $settings['view_all_text'], 'all-link' ) . '</li>';
		}

		$header_tabs[] = '</ul>';

		$header_tabs[] = '</div>';

		$atts = [
			'per_page'     => $settings['per_page'],
			'products'     => $settings['products'],
			'order'        => $settings['order'],
			'orderby'      => $settings['orderby'],
			'product_cats' => $settings['product_cats'],
			'columns'      => $settings['columns'],
		];

		$content = sprintf( '<div class="products-content">%s</div>', Elementor::get_products( $atts ) );

		echo sprintf(
			'<div %s>%s%s</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			implode( '', $header_tabs ),
			$content
		);
	}

	/**
	 * Render icon box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function _content_template() {
	}

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