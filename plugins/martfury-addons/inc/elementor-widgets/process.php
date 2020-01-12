<?php

namespace MartfuryAddons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Process extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'martfury-process';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Martfury - Process', 'martfury' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-navigation-vertical';
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
			[ 'label' => esc_html__( 'Content', 'martfury' ) ]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'image',
			[
				'label'   => esc_html__( 'Choose Image', 'martfury' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/361x291/f8f8f8?text=361x291+Image',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				// Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'   => 'full',
				'separator' => 'none',
			]
		);

		$repeater->add_control(
			'title', [
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Process Name', 'martfury' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'desc', [
				'label'   => esc_html__( 'Description', 'martfury' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'This is the description. Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie', 'martfury' ),
			]
		);

		$this->add_control(
			'process_settings',
			[
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'image' => [
							'url' => 'https://via.placeholder.com/361x291/f8f8f8?text=361x291+Image',
						],
						'title' => esc_html__( 'Process Name #01', 'martfury' ),
						'desc'  =>__(
							'<ul class="mf-list">
								<li>This is the description #01. Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie</li>
								<li>Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie</li>
							</ul>',
							'martfury'
						),
					],
					[
						'image' => [
							'url' => 'https://via.placeholder.com/361x291/f8f8f8?text=361x291+Image',
						],
						'title' => esc_html__( 'Process Name #02', 'martfury' ),
						'desc'  => __(
							'<ul class="mf-list">
								<li>This is the description #02. Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie</li>
								<li>Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie</li>
							</ul>',
							'martfury'
						),
					],
					[
						'image' => [
							'url' => 'https://via.placeholder.com/361x291/f8f8f8?text=361x291+Image',
						],
						'title' => esc_html__( 'Process Name #03', 'martfury' ),
						'desc'  => __(
							'<ul class="mf-list">
								<li>This is the description #03. Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie</li>
								<li>Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie</li>
							</ul>',
							'martfury'
						),
					],
					[
						'image' => [
							'url' => 'https://via.placeholder.com/361x291/f8f8f8?text=361x291+Image',
						],
						'title' => esc_html__( 'Process Name #04', 'martfury' ),
						'desc'  => __(
							'<ul class="mf-list">
								<li>This is the description #04. Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie</li>
								<li>Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie</li>
							</ul>',
							'martfury'
						),
					]
				],
				'title_field'   => '{{{ title }}}',
				'prevent_empty' => false
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
				'label' =>esc_html__( 'General', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'row_bottom_space',
			[
				'label'     =>esc_html__( 'Row Bottom Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ ],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-process .process-content' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'row_left_space',
			[
				'label'     =>esc_html__( 'Row Left Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'devices'   => [ 'tablet', 'mobile' ],
				'default'   => [ ],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-process .process-content' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		// Axis
		$this->start_controls_section(
			'section_axis_style',
			[
				'label' =>esc_html__( 'Axis', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'axis_left_space',
			[
				'label'     =>esc_html__( 'Left Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'devices'   => [ 'tablet', 'mobile' ],
				'range'     => [
					'px' => [
						'min' => 15,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-process .process-step:before' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'axis_color',
			[
				'label'     =>esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-process .process-step .step'  => 'color: {{VALUE}};'
				],
			]
		);
		$this->add_responsive_control(
			'step_font_size',
			[
				'label'     =>esc_html__( 'Font Size', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ ],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-process .process-step .step' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'bg_color',
			[
				'label'     =>esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-process .process-step .step' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'step_width',
			[
				'label'     =>esc_html__( 'Width', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ ],
				'range'     => [
					'px' => [
						'min' => 50,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-process .process-step .step' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'step_height',
			[
				'label'     =>esc_html__( 'Height', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ ],
				'range'     => [
					'px' => [
						'min' => 50,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-process .process-step .step' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'step_border_width',
			[
				'label'     => esc_html__( 'Border Width', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-process .process-step .step'  => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mf-elementor-process .process-step:before' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'step_border__color',
			[
				'label'     =>esc_html__( 'Border Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-process .process-step .step'  => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .mf-elementor-process .process-step:before' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'step_border_radius',
			[
				'label'      =>esc_html__( 'Border Radius', 'martfury' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%'  => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default'    => [ ],
				'selectors'  => [
					'{{WRAPPER}} .mf-elementor-process .process-step .step' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		// Image
		$this->start_controls_section(
			'section_image_style',
			[
				'label' =>esc_html__( 'Image', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'image_bottom_spacing',
			[
				'label'          =>esc_html__( 'Image Bottom Spacing', 'martfury' ),
				'type'           => Controls_Manager::SLIDER,
				'range'          => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .mf-elementor-process img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		$this->end_controls_section();
		// Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' =>esc_html__( 'Title', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mf-elementor-process h3',
			]
		);
		$this->add_responsive_control(
			'title_space',
			[
				'label'     =>esc_html__( 'Bottom Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [ ],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-process h3' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     =>esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-process h3' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();

		// Content
		$this->start_controls_section(
			'section_desc_style',
			[
				'label' =>esc_html__( 'Content', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_typography',
				'selector' => '{{WRAPPER}} .mf-elementor-process .desc',
			]
		);
		$this->add_control(
			'desc_color',
			[
				'label'     =>esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-process .desc' => 'color: {{VALUE}};',
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
				'mf-elementor-process',
			]
		);
		$output  = [ ];
		$process = $settings['process_settings'];

		if ( ! empty( $process ) ) {
			$i = 1;
			foreach ( $process as $index => $item ) {
				$image = $title = $desc = '';

				if ( isset( $item['image'] ) && $item['image'] ) {
					$image = Group_Control_Image_Size::get_attachment_image_html( $item );
				}

				if ( isset( $item['title'] ) && $item['title'] ) {
					$title = sprintf( '<h3>%s</h3>', $item['title'] );
				}

				if ( isset( $item['desc'] ) && $item['desc'] ) {
					$desc = sprintf( '<div class="desc">%s</div>', $item['desc'] );
				}

				$step = sprintf( '<div class="step">%s</div>', $i );

				$output[] = sprintf(
					'<div class="row process-content">
						<div class="col-md-5 col-xs-12 process-image">%s</div>
						<div class="col-md-2 col-xs-12 process-step">%s</div>
						<div class="col-md-5 col-xs-12 process-desc">%s%s</div>
					</div>',
					$image,
					$step,
					$title,
					$desc
				);

				$i ++;
			}
		}

		echo sprintf(
			'<div %s>
				<div class="list-process">%s</div>
			</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			implode( ' ', $output )
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