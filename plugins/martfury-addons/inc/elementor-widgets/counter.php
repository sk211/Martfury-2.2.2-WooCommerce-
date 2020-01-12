<?php

namespace MartfuryAddons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Counter extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'martfury-counter';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Martfury - Counter', 'martfury' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-counter-circle';
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
			'section_icon',
			[ 'label' => esc_html__( 'Icons', 'martfury' ) ]
		);

		$this->add_control(
			'icon_type',
			[
				'label'   => esc_html__( 'Icon Type', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'icon'        => esc_html__( 'Old Icon', 'martfury' ),
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
				'default'   => 'icon-cube',
				'condition' => [
					'icon_type' => 'icon',
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
					'icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'custom_icon',
			[
				'label'     => esc_html__( 'Icon', 'martfury' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition' => [
					'icon_type' => 'custom_icon',
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
					'icon_type' => 'image',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[ 'label' => esc_html__( 'Content', 'martfury' ) ]
		);
		$this->add_control(
			'value', [
				'label'       => esc_html__( 'Value', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '1.8',
				'description' => esc_html__( 'Input integer value for counting', 'martfury' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'unit_before', [
				'label'       => esc_html__( 'Unit Before', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'description' => esc_html__( 'Enter the Unit. Example: +, % .etc', 'martfury' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'unit_after', [
				'label'       => esc_html__( 'Unit After', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'M',
				'description' => esc_html__( 'Enter the Unit. Example: +, % .etc', 'martfury' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'title', [
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Products for sale', 'martfury' ),
				'placeholder' => esc_html__( 'Enter the title', 'martfury' ),
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		/**
		 * Tab Style
		 */
		// Icon
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon/Image', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'icon_position',
			[
				'label'   => esc_html__( 'Position', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'left'   => esc_html__( 'Left', 'martfury' ),
					'right'  => esc_html__( 'Right', 'martfury' ),
					'center' => esc_html__( 'Top Center', 'martfury' ),
				],
				'default' => 'center',
				'toggle'  => false,
			]
		);
		$this->add_responsive_control(
			'icon_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 28,
				],
				'selectors' => [
					'{{WRAPPER}} .martfury-counter-els.mf-icon--left .mf-icon'   => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .martfury-counter-els.mf-icon--left .mf-img'    => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .martfury-counter-els.mf-icon--right .mf-icon'  => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .martfury-counter-els.mf-icon--right .mf-img'   => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .martfury-counter-els.mf-icon--center .mf-icon' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .martfury-counter-els.mf-icon--center .mf-img'  => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_font_size',
			[
				'label'     => esc_html__( 'Icon Font Size', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 72,
				],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .martfury-counter-els .mf-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'icon_type' => ['icon', 'custom_icon'],
				],
			]
		);
		$this->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgb(252,184,0)',
				'selectors' => [
					'{{WRAPPER}} .martfury-counter-els .mf-icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .martfury-counter-els .mf-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'icon_type' => ['icon', 'custom_icon'],
				],
			]
		);

		$this->end_controls_section();

		// Value
		$this->start_controls_section(
			'section_item_style',
			[
				'label' => esc_html__( 'Value', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'value_typography',
				'selector' => '{{WRAPPER}} .martfury-counter-els .counter',
			]
		);
		$this->add_control(
			'value_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .martfury-counter-els .counter' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'value_spacing',
			[
				'label'     => esc_html__( 'Bottom Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'default'   => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .martfury-counter-els .counter' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'separator_1',
			[
				'label'     => esc_html__( 'Unit Before', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'before_font_size',
			[
				'label'     => esc_html__( 'Font Size', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'default'   => [],
				'selectors' => [
					'{{WRAPPER}} .martfury-counter-els .counter .unit-before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'before_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .martfury-counter-els .counter .unit-before' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'separator_2',
			[
				'label'     => esc_html__( 'Unit After', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'after_font_size',
			[
				'label'     => esc_html__( 'Font Size', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'default'   => [],
				'selectors' => [
					'{{WRAPPER}} .martfury-counter-els .counter .unit-after' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'after_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .martfury-counter-els .counter .unit-after' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .martfury-counter-els h4',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .martfury-counter-els h4' => 'color: {{VALUE}};',
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

		$this->add_render_attribute(
			'wrapper', 'class', [
				'martfury-counter-els',
				'mf-icon--' . $settings['icon_position'],
			]
		);

		$icon = $output = $unit_before = $unit_after = '';

		if ( $settings['icon_type'] == 'image' ) {
			if ( $settings['image'] ) {
				$icon = '<div class="mf-img">' . Group_Control_Image_Size::get_attachment_image_html( $settings ) . '</div>';
			}
		} elseif ( $settings['icon_type'] == 'icon' ) {
			$icon = '<span class="mf-icon"><i class="' . esc_attr( $settings['icon'] ) . '"></i></span>';
		} else {
			if ( $settings['custom_icon'] && \Elementor\Icons_Manager::is_migration_allowed() ) {
				ob_start();
				\Elementor\Icons_Manager::render_icon( $settings['custom_icon'], [ 'aria-hidden' => 'true' ] );
				$icon = '<span class="mf-icon">' . ob_get_clean() . '</span>';
			}
		}

		if ( $settings['unit_before'] ) {
			$unit_before = sprintf( '<span class="unit unit-before">%s</span>', $settings['unit_before'] );
		}

		if ( $settings['unit_after'] ) {
			$unit_after = sprintf( '<span class="unit unit-after">%s</span>', $settings['unit_after'] );
		}

		if ( $settings['value'] ) {
			$output = sprintf( '<div class="counter">%s<span class="counter-value">%s</span>%s</div>', $unit_before, $settings['value'], $unit_after );
		}

		if ( $settings['title'] ) {
			$output .= sprintf( '<h4 class="title">%s</h4>', $settings['title'] );
		}

		echo sprintf(
			'<div %s>%s<div class="counter-content">%s</div></div>',
			$this->get_render_attribute_string( 'wrapper' ),
			$icon,
			$output
		);

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