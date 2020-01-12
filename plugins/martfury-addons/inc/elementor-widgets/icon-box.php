<?php

namespace Elementor;

namespace MartfuryAddons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Utils;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Icon_Box extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'mf-elementor-icon-box';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Martfury - Icon Box', 'martfury' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-icon-box';
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
			'section_Icon',
			[ 'label' => esc_html__( 'Icon', 'martfury' ) ]
		);

		$this->add_control(
			'type',
			[
				'label'   => esc_html__( 'Type', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'icon'        => esc_html__( 'Icon', 'martfury' ),
					'image'       => esc_html__( 'Image', 'martfury' ),
					'custom_icon' => esc_html__( 'New Icon', 'martfury' ),
				],
				'default' => 'icon',
				'toggle'  => false,
			]
		);

		$this->add_control(
			'icon',
			[
				'label'     => esc_html__( 'Icon', 'martfury' ),
				'type'      => Controls_Manager::ICON,
				'default'   => 'fa fa-star',
				'condition' => [
					'type' => 'icon',
				],
			]
		);

		$this->add_control(
			'image',
			[
				'label'     => esc_html__( 'Choose Image', 'martfury' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'type' => 'image',
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
				'condition' => [
					'type' => 'custom_icon',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				// Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'   => 'large',
				'separator' => 'none',
				'condition' => [
					'type' => 'image',
				],
			]
		);

		$this->add_responsive_control(
			'icon_position',
			[
				'label'   => esc_html__( 'Icon Position', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'left'   => esc_html__( 'Left', 'martfury' ),
					'right'  => esc_html__( 'Right', 'martfury' ),
					'center' => esc_html__( 'Top Center', 'martfury' ),
				],
				'default' => 'left',
				'toggle'  => false,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[ 'label' => esc_html__( 'Content', 'martfury' ) ]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'I am Icon Box', 'martfury' ),
				'placeholder' => esc_html__( 'Enter your title', 'martfury' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'desc',
			[
				'label'       => esc_html__( 'Description', 'martfury' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'This is the Icon Box content.', 'martfury' ),
				'placeholder' => esc_html__( 'Enter your description', 'martfury' ),
				'rows'        => 10,
			]
		);

		$this->add_control(
			'link_text', [
				'label'       => esc_html__( 'Link Text', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
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

		$this->end_controls_section();

		/**
		 * TAB STYLE
		 */
		// General
		$this->start_controls_section(
			'section_general_style',
			[
				'label' => esc_html__( 'General', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'background_color',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icon-box' => 'background-color: {{VALUE}};',
				],
				'default'   => '',
			]
		);
		$this->add_responsive_control(
			'padding',
			[
				'label'      => esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-elementor-icon-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'    => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'separator'  => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'border',
				'label'     => esc_html__( 'Border', 'martfury' ),
				'selector'  => '{{WRAPPER}} .mf-elementor-icon-box',
				'separator' => 'before',
			]
		);
		$this->end_controls_section();

		// Icon
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'icon_height',
			[
				'label'     => esc_html__( 'Min Height', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 200,
						'min' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icon-box.align-icon-center .mf-icon-area' => 'min-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_position' => 'center',
				],
			]
		);
		$this->add_responsive_control(
			'icon_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 200,
						'min' => 10,
					],
				],
				'default'   => [
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icon-box.align-icon-left .box-icon'       => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mf-elementor-icon-box.align-icon-right .box-icon'      => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mf-elementor-icon-box.align-icon-center .box-icon'     => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mf-elementor-icon-box.align-icon-left .box-img'        => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mf-elementor-icon-box.align-icon-right .box-img'       => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mf-elementor-icon-box.align-icon-center .box-img'      => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .mf-elementor-icon-box.align-icon-left .box-icon'  => 'padding-left: {{SIZE}}{{UNIT}};padding-right: 0',
					'.rtl {{WRAPPER}} .mf-elementor-icon-box.align-icon-right .box-icon' => 'padding-right: {{SIZE}}{{UNIT}};padding-left: 0',
					'.rtl {{WRAPPER}} .mf-elementor-icon-box.align-icon-left .box-img'   => 'padding-left: {{SIZE}}{{UNIT}};padding-right: 0',
					'.rtl {{WRAPPER}} .mf-elementor-icon-box.align-icon-right .box-img'  => 'padding-right: {{SIZE}}{{UNIT}};padding-left: 0',
				],
			]
		);
		$this->add_responsive_control(
			'icon_font_size',
			[
				'label'     => esc_html__( 'Font Size', 'martfury' ),
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
					'{{WRAPPER}} .mf-elementor-icon-box .box-icon i, {{WRAPPER}} .mf-elementor-icon-box .box-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'type' => [ 'icon', 'custom_icon' ],
				],
			]
		);
		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icon-box .box-icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .mf-elementor-icon-box .box-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'type' => [ 'icon', 'custom_icon' ],
				],
			]
		);

		$this->end_controls_section();

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
				'selector' => '{{WRAPPER}} .mf-elementor-icon-box .box-title',
			]
		);
		$this->add_responsive_control(
			'title_space',
			[
				'label'     => esc_html__( 'Title Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icon-box .box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'title_style' );
		$this->start_controls_tab(
			'title_normal',
			[
				'label' => esc_html__( 'Normal', 'martfury' ),
			]
		);
		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icon-box .box-title'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .mf-elementor-icon-box .box-title a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_hover',
			[
				'label' => esc_html__( 'Hover', 'martfury' ),
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icon-box .box-title:hover,
					{{WRAPPER}} .mf-elementor-icon-box .box-title:focus,
					{{WRAPPER}} .mf-elementor-icon-box .box-title a:hover,
					{{WRAPPER}} .mf-elementor-icon-box .box-title a:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->end_controls_section();

		// Description
		$this->start_controls_section(
			'section_desc_style',
			[
				'label' => esc_html__( 'Description', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_typography',
				'selector' => '{{WRAPPER}} .mf-elementor-icon-box .desc',
			]
		);
		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icon-box .desc' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();

		// Link
		$this->start_controls_section(
			'section_link_style',
			[
				'label' => esc_html__( 'Link', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'link_typography',
				'selector' => '{{WRAPPER}} .mf-elementor-icon-box .box-url',
			]
		);
		$this->add_responsive_control(
			'link_space',
			[
				'label'     => esc_html__( 'Top Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 23,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icon-box .box-url' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'link_style' );
		$this->start_controls_tab(
			'link_normal',
			[
				'label' => esc_html__( 'Normal', 'martfury' ),
			]
		);
		$this->add_control(
			'link_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icon-box .box-url' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_hover',
			[
				'label' => esc_html__( 'Hover', 'martfury' ),
			]
		);

		$this->add_control(
			'link_hover_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icon-box .box-url:hover,
					{{WRAPPER}} .mf-elementor-icon-box .box-url:focus' => 'color: {{VALUE}};',
				],
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
			'mf-elementor-icon-box',
			'align-icon-' . $settings['icon_position'],
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$title = $icon = $button = '';

		if ( $settings['type'] == 'image' ) {
			if ( $settings['image'] ) {
				$icon = Group_Control_Image_Size::get_attachment_image_html( $settings );
				$icon = $icon ? sprintf( '<div class="mf-icon-area box-img">%s</div>', $icon ) : '';
			}
		} elseif ( $settings['type'] == 'icon' ) {
			if ( $settings['icon'] ) {
				$icon = '<div class="mf-icon box-icon"><i class="' . esc_attr( $settings['icon'] ) . '"></i></div>';
			}
		} elseif ( $settings['type'] == 'custom_icon' ) {
			if ( $settings['custom_icon'] && \Elementor\Icons_Manager::is_migration_allowed() ) {
				ob_start();
				\Elementor\Icons_Manager::render_icon( $settings['custom_icon'], [ 'aria-hidden' => 'true' ] );
				$icon = '<div class="mf-icon box-icon">' . ob_get_clean() . '</div>';
			}
		}

		if ( $settings['title'] ) {
			$title = sprintf( '<h3 class="box-title">%s</h3>', $this->get_link_control( 'link', $settings['link'], $settings['title'], '' ) );
		}

		if ( $settings['link_text'] ) {
			$button = sprintf( '%s', $this->get_link_control( 'link_button', $settings['link'], $settings['link_text'], 'box-url' ) );
		}

		echo sprintf(
			'<div %s>
				%s
				<div class="box-wrapper">
					%s
					<div class="desc">%s</div>
					%s
				</div>
			</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			$icon,
			$title,
			$settings['desc'],
			$button
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