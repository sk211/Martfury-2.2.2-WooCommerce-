<?php

namespace MartfuryAddons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class FAQs extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'martfury-faqs';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'FAQs', 'martfury' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-editor-list-ul';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'martfury' ];
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
			'section_general',
			[ 'label' => esc_html__( 'FAQs', 'martfury' ) ]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'FAQs Title', 'martfury' ),
				'placeholder' => esc_html__( 'Enter FAQs title', 'martfury' ),
				'label_block' => true,
			]
		);

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'This is the question', 'martfury' ),
				'placeholder' => esc_html__( 'Enter your title', 'martfury' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'desc',
			[
				'label'       => esc_html__( 'Content', 'martfury' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'This is the answer', 'martfury' ),
				'placeholder' => esc_html__( 'Enter your title', 'martfury' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'faqs_setting',
			[
				'label'         => esc_html__( 'Content', 'martfury' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'title' => esc_html__( 'This is the question #01', 'martfury' ),
						'desc'  => esc_html__( 'This is the answer #01. Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie', 'martfury' ),
					],
					[
						'title' => esc_html__( 'This is the question #02', 'martfury' ),
						'desc'  => esc_html__( 'This is the answer #02. Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie', 'martfury' ),
					],
					[
						'title' => esc_html__( 'This is the question #03', 'martfury' ),
						'desc'  => esc_html__( 'This is the answer #03. Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie', 'martfury' ),
					]
				],
				'title_field'   => '{{{ title }}}',
				'prevent_empty' => false
			]
		);

		$this->end_controls_section();

		/**
		 * Tab Style
		 */
		 // Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .martfury-faq_group .g-title',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .martfury-faq_group .g-title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();

		// Item
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'content_spacing',
			[
				'label'     => esc_html__( 'Bottom Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'default'   => [],
				'selectors' => [
					'{{WRAPPER}} .martfury-faq_group .faq-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'separator_1',
			[
				'label'     => esc_html__( 'Title', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'item_title_typography',
				'selector' => '{{WRAPPER}} .martfury-faq_group .title',
			]
		);
		$this->add_control(
			'item_title_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .martfury-faq_group .title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'separator_2',
			[
				'label'     => esc_html__( 'Content', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'item_content_typography',
				'selector' => '{{WRAPPER}} .martfury-faq_group .desc',
			]
		);
		$this->add_control(
			'item_content_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .martfury-faq_group .desc' => 'color: {{VALUE}};',
				],
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

		$classes = [
			'martfury-faq_group'
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$output = [];

		if ( ! empty( $settings['title'] ) ) {
			$output[] = sprintf( '<div class="col-gtitle col-md-3 col-sm-3 col-xs-12"><h2 class="g-title">%s</h2></div>', $settings['title'] );
		}

		$output[] = '<div class="col-md-9 col-sm-9 col-xs-12">';
		$output[] = '<div class="row">';

		$btn_settings = $settings['faqs_setting'];

		if ( ! empty ( $btn_settings ) ) {

			foreach ( $btn_settings as $index => $item ) {
				$output[] = '<div class="faq-item">';

				if ( isset( $item['title'] ) && $item['title'] ) {
					$output[] = sprintf( '<div class="col-title col-md-5 col-sm-5 col-xs-12"> <h3 class="title">%s</h3></div>', $item['title'] );
				}

				if ( isset( $item['desc'] ) && $item['desc'] ) {
					$output[] = sprintf( '<div class="col-md-7 col-sm-7 col-xs-12"> <div class="desc">%s</div></div>', $item['desc'] );
				}

				$output[] = '</div>';
			}
		}

		$output[] = '</div>';
		$output[] = '</div>';

		echo sprintf(
			'<div %s><div class="row">%s</div></div>',
			$this->get_render_attribute_string( 'wrapper' ),
			implode( '', $output )
		);
	}

	/**
	 * Render icon box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function _content_template() {
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