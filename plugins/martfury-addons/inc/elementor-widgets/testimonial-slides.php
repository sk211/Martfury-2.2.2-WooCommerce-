<?php

namespace MartfuryAddons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Testimonial_Slides extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'martfury-testimonial-slides';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Martfury - Testimonial Slides', 'martfury' );
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
			[ 'label' => esc_html__( 'Testimonials', 'martfury' ) ]
		);

		$this->start_controls_tabs( 'heading_content_settings' );

		$this->start_controls_tab( 'title_tab', [ 'label' => esc_html__( 'Title', 'martfury' ) ] );
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

		$this->end_controls_tab();

		$this->start_controls_tab( 'content_tab', [ 'label' => esc_html__( 'Content', 'martfury' ) ] );

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'image', [
				'label'   => esc_html__( 'Choose Image', 'martfury' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/100/',
				],
			]
		);
		$repeater->add_control(
			'name',
			[
				'label'       => esc_html__( 'Name', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Name /', 'martfury' ),
				'placeholder' => esc_html__( 'Enter the Name', 'martfury' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'job',
			[
				'label'       => esc_html__( 'Job', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Job', 'martfury' ),
				'placeholder' => esc_html__( 'Enter the Job', 'martfury' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'desc',
			[
				'label'       => esc_html__( 'Description', 'martfury' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'This is the description. Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie', 'martfury' ),
				'placeholder' => esc_html__( 'Enter the Description', 'martfury' ),
				'label_block' => true,
			]
		);
		$repeater->add_control(
			'link_text',
			[
				'label'       => esc_html__( 'Link Text', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'link_text'   => esc_html__( 'PLAY VIDEO', 'martfury' ),
				'placeholder' => esc_html__( 'Link Text', 'martfury' ),
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
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],

			]
		);
		$repeater->add_control(
			'link_icon',
			[
				'label'     => esc_html__( 'Link Icon', 'martfury' ),
				'type'      => Controls_Manager::ICON,
				'default'   => 'icon-play-circle',
				'separator' => 'before',
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
						'image'     => [
							'url' => 'https://via.placeholder.com/100/'
						],
						'link_text' => esc_html__( 'PLAY VIDEO', 'martfury' ),
						'link'      => [
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => true,
						],
						'link_icon'      => 'icon-play-circle',
						'name'      => esc_html__( 'Name 01 /', 'martfury' ),
						'job'       => esc_html__( 'Job 01', 'martfury' ),
						'desc'      => esc_html__( 'This is the description. Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie', 'martfury' ),
					],
					[
						'image'     => [
							'url' => 'https://via.placeholder.com/100/'
						],
						'link_text' => esc_html__( 'PLAY VIDEO', 'martfury' ),
						'link'      => [
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => true,
						],
						'link_icon'      => 'icon-play-circle',
						'name'      => esc_html__( 'Name 02 /', 'martfury' ),
						'job'       => esc_html__( 'Job 02', 'martfury' ),
						'desc'      => esc_html__( 'This is the description. Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie', 'martfury' ),
					],
					[
						'image'     => [
							'url' => 'https://via.placeholder.com/100/'
						],
						'link_text' => esc_html__( 'PLAY VIDEO', 'martfury' ),
						'link'      => [
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => true,
						],
						'link_icon'      => 'icon-play-circle',
						'name'      => esc_html__( 'Name 03 /', 'martfury' ),
						'job'       => esc_html__( 'Job 03', 'martfury' ),
						'desc'      => esc_html__( 'This is the description. Sed elit quam, iaculis sed semper sit amet udin vitae nibh. at magna akal semperFusce commodo molestie', 'martfury' ),
					]
				],
				'title_field'   => '{{{ name }}}',
				'prevent_empty' => false
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slider_settings',
			[ 'label' => esc_html__( 'Slider Settings', 'martfury' ) ]
		);

		$this->add_responsive_control(
			'navigation',
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
			'infinite',
			[
				'label'     => esc_html__( 'Infinite Loop', 'martfury' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'martfury' ),
				'label_on'  => esc_html__( 'On', 'martfury' ),
				'default'   => 'yes'
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'     => esc_html__( 'Autoplay', 'martfury' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'martfury' ),
				'label_on'  => esc_html__( 'On', 'martfury' ),
				'default'   => 'yes'
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'   => esc_html__( 'Autoplay Speed (in ms)', 'martfury' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3000,
				'min'     => 100,
				'step'    => 100,
			]
		);

		$this->add_control(
			'speed',
			[
				'label'       => esc_html__( 'Speed', 'martfury' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 800,
				'min'         => 100,
				'step'        => 100,
				'description' => esc_html__( 'Slide animation speed (in ms)', 'martfury' ),
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
		$this->add_responsive_control(
			'heading_spacing',
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
					'{{WRAPPER}} .mf-elementor-testimonial-slides .testimonial-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .mf-elementor-testimonial-slides .testimonial-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .testimonial-heading' => 'background-color: {{VALUE}};',
				],
				'default'   => '',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'banner_border',
				'label'    => esc_html__( 'Border', 'martfury' ),
				'selector' => '{{WRAPPER}} .mf-elementor-testimonial-slides .testimonial-heading',
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label'     => esc_html__( 'Title', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .tes-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mf-elementor-testimonial-slides .tes-title',
			]
		);

		$this->end_controls_section(); // End Link Section
		// Title

		// Avatar
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .testimonial-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'content_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .testimonial-info' => 'background-color: {{VALUE}};',
				],
				'default'   => '',
			]
		);
		$this->add_control(
			'content_border_style',
			[
				'label'     => esc_html__( 'Border Style', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'dotted' => esc_html__( 'Dotted', 'martfury' ),
					'dashed' => esc_html__( 'Dashed', 'martfury' ),
					''       => esc_html__( 'Solid', 'martfury' ),
					'none'   => esc_html__( 'None', 'martfury' ),
				],
				'default'   => '',
				'toggle'    => false,
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .testimonial-info' => 'border-style: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'content_border_width',
			[
				'label'     => esc_html__( 'Border Width', 'martfury' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .testimonial-info' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'content_border_style!' => 'none',
				],
			]
		);
		$this->add_control(
			'content_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .testimonial-info' => 'border-color: {{VALUE}};',
				],
				'content_border_style!' => 'none',
			]
		);

		$this->add_control(
			'content_avatar_heading',
			[
				'label'     => esc_html__( 'Avatar', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'default'   => '',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'avatar_image',
				// Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'   => 'full',
				'separator' => 'none',
			]
		);

		$this->add_responsive_control(
			'avatar_spacing',
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
					'{{WRAPPER}} .mf-elementor-testimonial-slides .testi-thumb' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'avatar_position_top',
			[
				'label'     => esc_html__( 'Position Top(-px)', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'default'   => [],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .testi-thumb' => 'transform: translateY(-{{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_responsive_control(
			'avatar_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'default'   => [],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .testimonial-info img' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Quoter
		$this->add_control(
			'content_quote_heading',
			[
				'label'     => esc_html__( 'Quote', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'default'   => '',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'quote_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .testimonial-info i.quote' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'quote_position_top',
			[
				'label'     => esc_html__( 'Position Top', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'default'   => [],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .testimonial-info i.quote' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'quote_position_right',
			[
				'label'     => esc_html__( 'Position Right', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
						'min' => 0,
					],
				],
				'default'   => [],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .testimonial-info i.quote' => 'right: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .mf-elementor-testimonial-slides .testimonial-info i.quote' => 'left: {{SIZE}}{{UNIT}};right: auto',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'quote_typography',
				'selector' => '{{WRAPPER}} .mf-elementor-testimonial-slides .testimonial-info i.quote',
			]
		);

		// Name
		$this->add_control(
			'content_name_heading',
			[
				'label'     => esc_html__( 'Name', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'name_spacing',
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
					'{{WRAPPER}} .mf-elementor-testimonial-slides .testi-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'selector' => '{{WRAPPER}} .mf-elementor-testimonial-slides .name',
			]
		);
		$this->add_control(
			'name_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .name' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'content_job_heading',
			[
				'label'     => esc_html__( 'Job', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'job_typography',
				'selector' => '{{WRAPPER}} .mf-elementor-testimonial-slides .job',
			]
		);
		$this->add_control(
			'job_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .job' => 'color: {{VALUE}};',
				],
			]
		);

		// Desc
		$this->add_control(
			'content_desc_heading',
			[
				'label'     => esc_html__( 'Description', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_typography',
				'selector' => '{{WRAPPER}} .mf-elementor-testimonial-slides .desc',
			]
		);
		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .desc' => 'color: {{VALUE}};',
				],
			]
		);

		// Link
		$this->add_control(
			'content_link_heading',
			[
				'label'     => esc_html__( 'Link', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'link_spacing',
			[
				'label'     => esc_html__( 'Top Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
						'min' => 0,
					],
				],
				'default'   => [],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides a' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'link_typography',
				'selector' => '{{WRAPPER}} .mf-elementor-testimonial-slides a',
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label'   => esc_html__( 'Icon Position', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left'  => esc_html__( 'Before', 'martfury' ),
					'right' => esc_html__( 'After', 'martfury' ),
				]
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label'     => esc_html__( 'Icon Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 20,
					],
				],
				'default'   => [
					'size' => 5
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides a .align-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mf-elementor-testimonial-slides a .align-icon-left'  => 'padding-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'link_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_link_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides a:hover, {{WRAPPER}} .mf-elementor-testimonial-slides a:focus' => 'color: {{VALUE}};',
				],
			]
		);


		$this->end_controls_section();

		// Carousel style
		$this->start_controls_section(
			'section_carousel_style',
			[
				'label' => esc_html__( 'Carousel Settings', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'carousel_settings' );

		$this->start_controls_tab( 'carousel_arrows_style', [ 'label' => esc_html__( 'Arrows', 'martfury' ) ] );

		$this->add_control(
			'arrows_position',
			[
				'label'     => esc_html__( 'Arrows Position', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'center' => esc_html__( 'Center', 'martfury' ),
					'top'    => esc_html__( 'Top Right', 'martfury' ),
				],
				'default'   => 'top',
				'toggle'    => false,
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label'     => esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .arrow-wrapper .slick-arrow' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'arrows_hover_color',
			[
				'label'     => esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .arrow-wrapper .slick-arrow:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'arrows_typography',
				'selector' => '{{WRAPPER}} .mf-elementor-testimonial-slides .arrow-wrapper .slick-arrow',
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab( 'carousel_dots_style', [ 'label' => esc_html__( 'Dots', 'martfury' ) ] );

		$this->add_responsive_control(
			'dots_spacing',
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
					'{{WRAPPER}} .mf-elementor-testimonial-slides .slick-dots' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'dots_width',
			[
				'label'     => esc_html__( 'Width', 'martfury' ),
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
					'{{WRAPPER}} .mf-elementor-testimonial-slides .slick-dots li button' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'dots_background',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .slick-dots li button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'dots_active_background',
			[
				'label'     => esc_html__( 'Active Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-testimonial-slides .slick-dots li.slick-active button' => 'background-color: {{VALUE}};',
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

		$nav        = $settings['navigation'];
		$nav_tablet = empty( $settings['navigation_tablet'] ) ? $nav : $settings['navigation_tablet'];
		$nav_mobile = empty( $settings['navigation_mobile'] ) ? $nav_tablet : $settings['navigation_mobile'];

		$classes = [
			'mf-elementor-testimonial-slides',
			'mf-elementor-testimonial-slides--' . $settings['arrows_position'],
			'navigation-' . $nav,
			'navigation-tablet-' . $nav_tablet,
			'navigation-mobile-' . $nav_mobile,
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$title = '';
		if ( $settings['title'] ) {
			$title = sprintf( '<h2 class="tes-title">%s</h2>', $settings['title'] );
		}

		$output = [];

		$testimonial_settings = $settings['settings'];

		if ( ! empty( $testimonial_settings ) ) {
			foreach ( $testimonial_settings as $index => $item ) {
				$link_key = 'link_' . $index;
				$image    = $name = $desc = $css = $address = $button = $link_text = '';
				$icon     = '';

				if ( isset( $item['image'] ) && $item['image'] ) {
					$item['image_size'] = $settings['avatar_image_size'];
					$image              = Group_Control_Image_Size::get_attachment_image_html( $item );
				} else {
					$css .= ' no-thumbnail';
				}

				if ( isset( $item['link_text'] ) && ! empty( $item['link_text'] ) ) {
					$this->add_render_attribute(
						[
							'icon-wrapper' => [
								'class' => [
									'extra-icon',
									'align-icon-' . $settings['icon_align'],
								],
							],
						]
					);
					$icon = sprintf(
						'<span %s><i class="%s"></i></span>',
						$this->get_render_attribute_string( 'icon-wrapper' ),
						esc_attr( $item['link_icon'] )
					);

					$link_text = $item['link_text'] . $icon;
					$button    = $this->get_link_control( $link_key, $item['link'], $link_text, '' );
				}

				if ( isset( $item['name'] ) && $item['name'] ) {
					$name = sprintf( '<span class="name">%s</span>', $item['name'] );
				}

				if ( isset( $item['job'] ) && $item['job'] ) {
					$address = sprintf( '<span class="job">%s</span>', $item['job'] );
				}

				if ( isset( $item['desc'] ) && $item['desc'] ) {
					$desc = sprintf( '<div class="desc">%s</div>', $item['desc'] );
				}

				$output[] = sprintf(
					'<div class="testimonial-info %s">
						<i class="icon_quotations quote"></i>
						<div class="testi-thumb">%s</div>
						<div class="testi-header">%s%s</div>
						%s%s
					</div>',
					esc_attr( $css ),
					$image,
					$name,
					$address,
					$desc,
					$button
				);
			}
		}

		$carousel_settings = [
			'autoplay'       => $settings['autoplay'],
			'infinite'       => $settings['infinite'],
			'autoplay_speed' => intval( $settings['autoplay_speed'] ),
			'speed'          => intval( $settings['speed'] ),
		];

		$this->add_render_attribute( 'wrapper', 'data-settings', wp_json_encode( $carousel_settings ) );

		echo sprintf(
			'<div %s>
				<div class="testimonial-heading">%s<div class="arrow-wrapper"></div></div>
				<div class="testimonial-list">%s</div>
			</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			$title,
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