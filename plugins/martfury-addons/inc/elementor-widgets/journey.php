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
class Journey extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'martfury-journey';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Journey', 'martfury' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-post-slider';
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
			'section_content',
			[ 'label' => esc_html__( 'Content', 'martfury' ) ]
		);

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'year',
			[
				'label'       => esc_html__( 'Year', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( '2019', 'martfury' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'image', [
				'label'   => esc_html__( 'Choose Image', 'martfury' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/100',
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
			'title',
			[
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'January 2019', 'martfury' ),
				'placeholder' => esc_html__( 'Enter the title', 'martfury' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'desc',
			[
				'label'       => esc_html__( 'Description', 'martfury' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie', 'martfury' ),
				'placeholder' => esc_html__( 'Enter the Description', 'martfury' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'reverse',
			[
				'label'     => esc_html__( 'Reverse', 'martfury' ),
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
			'settings',
			[
				'label'         => esc_html__( 'Settings', 'martfury' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'year'    => '2010',
						'image'   => [
							'url' => 'https://via.placeholder.com/100'
						],
						'title'   => esc_html__( 'January 2010', 'martfury' ),
						'desc'    => esc_html__( 'Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie', 'martfury' ),
						'reverse' => 'no'

					],
					[
						'year'    => '2012',
						'image'   => [
							'url' => 'https://via.placeholder.com/100'
						],
						'title'   => esc_html__( 'January 2012', 'martfury' ),
						'desc'    => esc_html__( 'Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie', 'martfury' ),
						'reverse' => 'no'

					],
					[
						'year'    => '2016',
						'image'   => [
							'url' => 'https://via.placeholder.com/100'
						],
						'title'   => esc_html__( 'January 2016', 'martfury' ),
						'desc'    => esc_html__( 'Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie', 'martfury' ),
						'reverse' => 'no'

					],
					[
						'year'    => '2018',
						'image'   => [
							'url' => 'https://via.placeholder.com/100'
						],
						'title'   => esc_html__( 'January 2018', 'martfury' ),
						'desc'    => esc_html__( 'Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie', 'martfury' ),
						'reverse' => 'yes'

					]
				],
				'title_field'   => '{{{ year }}}',
				'prevent_empty' => false
			]
		);
		$this->end_controls_section();

		/**
		 * TAB STYLE
		 */
		$this->start_controls_section(
			'section_timeline_style',
			[
				'label' =>esc_html__( 'Timeline', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'timeline_color',
			[
				'label'     =>esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .martfury-journey-els ul:after,
					{{WRAPPER}} .martfury-journey-els ul a span'  => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .martfury-journey-els ul a span' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'timeline_active_color',
			[
				'label'     =>esc_html__( 'Active Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .martfury-journey-els ul a:hover span'  => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .martfury-journey-els ul a.active span' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'border_width',
			[
				'label'     =>esc_html__( 'Border Width', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 10,
						'min' => 1,
					],
				],
				'default'   => [ ],
				'selectors' => [
					'{{WRAPPER}} .martfury-journey-els ul a span' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .martfury-journey-els ul:after'  => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		// Content
		$this->start_controls_section(
			'section_content_style',
			[
				'label' =>esc_html__( 'Content', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'min_height',
			[
				'label'     => esc_html__( 'Height', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 500,
						'min' => 200,
					],
				],
				'default'   => [
					'size' => 230
				],
				'selectors' => [
					'{{WRAPPER}} .martfury-journey-els .journey-content' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'content_spacing',
			[
				'label'     =>esc_html__( 'Top Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 150,
						'min' => 50,
					],
				],
				'default'   => [ ],
				'selectors' => [
					'{{WRAPPER}} .martfury-journey-els .journey-content' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'content_bg_color',
			[
				'label'     =>esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .martfury-journey-els .journey-wrapper'        => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .martfury-journey-els .journey-wrapper:before' => 'border-color: transparent transparent {{VALUE}} transparent;',
				],
			]
		);
		$this->add_responsive_control(
			'content_padding',
			[
				'label'      =>esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .martfury-journey-els .journey-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
				'default'    => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
			]
		);
		$this->end_controls_section(); // End Title Section

		// Image
		$this->start_controls_section(
			'section_image_style',
			[
				'label' =>esc_html__( 'Image', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'image_spacing',
			[
				'label'     =>esc_html__( 'Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 300,
						'min' => 0,
					],
				],
				'default'   => [ ],
				'selectors' => [
					'{{WRAPPER}} .martfury-journey-els .journey-wrapper .info' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section(); // End Image Section

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
				'selector' => '{{WRAPPER}} .martfury-journey-els .journey-wrapper .journey-title',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label'     =>esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .martfury-journey-els .journey-wrapper .journey-title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_spacing',
			[
				'label'     =>esc_html__( 'Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
						'min' => 0,
					],
				],
				'default'   => [ ],
				'selectors' => [
					'{{WRAPPER}} .martfury-journey-els .journey-wrapper .journey-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section(); // End Title Section

		// Desc
		$this->start_controls_section(
			'section_desc_style',
			[
				'label' =>esc_html__( 'Description', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_typography',
				'selector' => '{{WRAPPER}} .martfury-journey-els .journey-wrapper .journey-desc',
			]
		);
		$this->add_control(
			'desc_color',
			[
				'label'     =>esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .martfury-journey-els .journey-wrapper .journey-desc' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section(); // End Desc Section
	}

	/**
	 * Render icon box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$classes = [
			'martfury-journey-els'
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$output      = [ ];
		$output_year = [ ];

		$journey_settings = $settings['settings'];
		$number           = count( $journey_settings );

		$i = 1;

		if ( ! empty( $journey_settings ) ) {
			foreach ( $journey_settings as $index => $item ) {
				$year = $title = $desc = $image = $css = '';

				if ( isset( $item['image'] ) && $item['image'] ) {
					$image = Group_Control_Image_Size::get_attachment_image_html( $item );
				}

				if ( isset( $item['year'] ) && $item['year'] ) {
					$year = sprintf( '<li><a href="#" data-tab="martfury-journey-tab-%s" data-number="%s">%s<span></span></a></li>', esc_attr( $i ), esc_attr( $i ), $item['year'] );
				}

				if ( isset( $item['title'] ) && $item['title'] ) {
					$title = sprintf( '<div class="journey-title">%s</div>', $item['title'] );
				}

				if ( isset( $item['desc'] ) && $item['desc'] ) {
					$desc = sprintf( '<div class="journey-desc">%s</div>', $item['desc'] );
				}

				if ( isset( $item['reverse'] ) && $item['reverse'] == 'yes' ) {
					$css = 'reverse';
				}

				$output[] = sprintf(
					'<div class="journey-wrapper clearfix %s journey-tab-%s" id="martfury-journey-tab-%s">
						<div class="avatar">%s</div>
						<div class="info">%s%s</div>
					</div>',
					esc_attr( $css ),
					esc_attr( $i ),
					esc_attr( $i ),
					$image,
					$title,
					$desc
				);

				$output_year[] = sprintf( '%s', $year );

				$i ++;
			}
		}

		$data = [
			'number' => $number,
		];

		$this->add_render_attribute( 'wrapper', 'data-settings', wp_json_encode( $data ) );

		echo sprintf(
			'<div %s>
				<ul>%s</ul>
				<div class="journey-content">%s</div>
			</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			implode( '', $output_year ),
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
}