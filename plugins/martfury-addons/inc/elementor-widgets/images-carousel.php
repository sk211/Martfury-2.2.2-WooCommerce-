<?php

namespace MartfuryAddons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Images_Carousel extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'martfury-images-carousel';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Images Carousel', 'martfury' );
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
			'section_images',
			[ 'label' => esc_html__( 'Images', 'martfury' ) ]
		);

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'image', [
				'label'   => esc_html__( 'Choose Image', 'martfury' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/144x93/f8f8f8?text=144x93',
				],
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
		$this->add_control(
			'group_setting',
			[
				'label'         => esc_html__( 'Images Group', 'martfury' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'image' => [
							'url' => 'https://via.placeholder.com/144x93/f8f8f8?text=144x93'
						]
					],
					[
						'image' => [
							'url' => 'https://via.placeholder.com/144x93/f8f8f8?text=144x93'
						]
					],
					[
						'image' => [
							'url' => 'https://via.placeholder.com/144x93/f8f8f8?text=144x93'
						]
					],
					[
						'image' => [
							'url' => 'https://via.placeholder.com/144x93/f8f8f8?text=144x93'
						]
					],
					[
						'image' => [
							'url' => 'https://via.placeholder.com/144x93/f8f8f8?text=144x93'
						]
					],
					[
						'image' => [
							'url' => 'https://via.placeholder.com/144x93/f8f8f8?text=144x93'
						]
					]
				],
				'prevent_empty' => false
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slider_settings',
			[
				'label' => esc_html__( 'Carousel Settings', 'martfury' ),
			]
		);

		$this->add_responsive_control(
			'slidesToShow',
			[
				'label'   => esc_html__( 'Slides To Show', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'1' => esc_html__( '1 Columns', 'martfury' ),
					'2' => esc_html__( '2 Columns', 'martfury' ),
					'3' => esc_html__( '3 Columns', 'martfury' ),
					'4' => esc_html__( '4 Columns', 'martfury' ),
					'5' => esc_html__( '5 Columns', 'martfury' ),
					'6' => esc_html__( '6 Columns', 'martfury' ),
				],
				'default' => '5',
				'toggle'  => false,
			]
		);

		$this->add_responsive_control(
			'slidesToScroll',
			[
				'label'   => esc_html__( 'Slides to Scroll', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'1' => esc_html__( '1 Columns', 'martfury' ),
					'2' => esc_html__( '2 Columns', 'martfury' ),
					'3' => esc_html__( '3 Columns', 'martfury' ),
					'4' => esc_html__( '4 Columns', 'martfury' ),
					'5' => esc_html__( '5 Columns', 'martfury' ),
					'6' => esc_html__( '6 Columns', 'martfury' ),
				],
				'default' => '5',
				'toggle'  => false,
			]
		);

		$this->add_control(
			'dots',
			[
				'label'   => esc_html__( 'Dots', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'yes' => esc_html__( 'Show', 'martfury' ),
					'no'    => esc_html__( 'Hide', 'martfury' ),
				],
				'default' => 'yes',
				'toggle'  => false,
			]
		);

		$this->add_control(
			'infinite',
			[
				'label'     =>esc_html__( 'Infinite Loop', 'martfury' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' =>esc_html__( 'Off', 'martfury' ),
				'label_on'  =>esc_html__( 'On', 'martfury' ),
				'default'   => 'yes'
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'     =>esc_html__( 'Autoplay', 'martfury' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' =>esc_html__( 'Off', 'martfury' ),
				'label_on'  =>esc_html__( 'On', 'martfury' ),
				'default'   => 'no'
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'   =>esc_html__( 'Autoplay Speed (in ms)', 'martfury' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3000,
				'min'     => 100,
				'step'    => 100,
			]
		);

		$this->add_control(
			'speed',
			[
				'label'       =>esc_html__( 'Speed', 'martfury' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 800,
				'min'         => 100,
				'step'        => 100,
				'description' => esc_html__( 'Slide animation speed (in ms)', 'martfury' ),
			]
		);

		$this->end_controls_section();


		// Item
		$this->start_controls_section(
			'section_item_style',
			[
				'label' =>esc_html__( 'Image Item', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'padding',
			[
				'label'      =>esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-images-gallery .image-item .image-item__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_caoursel_style',
			[
				'label' =>esc_html__( 'Carousel', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

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
					'{{WRAPPER}} .mf-images-gallery .slick-dots' => 'margin-top: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .mf-images-gallery .slick-dots li button' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .mf-images-gallery .slick-dots li button' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .mf-images-gallery .slick-dots li.slick-active button' => 'background-color: {{VALUE}};',
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
			'mf-images-gallery',
			'mf-images-gallery--slide'
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$output = [];

		$btn_settings = $settings['group_setting'];

		if ( ! empty ( $btn_settings ) ) {
			$output[] = '<div class="images-list">';

			foreach ( $btn_settings as $index => $item ) {
				$link_key = 'link_' . $index;

				$settings['image']      = $item['image'];
				$settings['image_size'] = 'full';
				$btn_image              = Group_Control_Image_Size::get_attachment_image_html( $settings );

				$attr = [
					'class' => 'img-link',
				];

				$link = $this->get_link_control( $link_key, $item['link'], $btn_image, $attr );

				$output[] = sprintf( '<div class="image-item"><div class="image-item__wrapper">%s</div></div>', $link );
			}

			$output[] = '</div>';
		}

		$carousel_settings = [
			'autoplay'       => $settings['autoplay'],
			'infinite'       => $settings['infinite'],
			'autoplay_speed' => intval( $settings['autoplay_speed'] ),
			'speed'          => intval( $settings['speed'] ),
			'slidesToShow'   => intval( $settings['slidesToShow'] ),
			'slidesToScroll' => intval( $settings['slidesToScroll'] ),
			'dots'           => $settings['dots'],
		];

		$this->add_render_attribute( 'wrapper', 'data-settings', wp_json_encode( $carousel_settings ) );

		echo sprintf(
			'<div %s>%s</div>',
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
	 * Render link control output
	 *
	 * @param       $link_key
	 * @param       $url
	 * @param       $content
	 * @param array $attr
	 *
	 * @return string
	 */
	protected function get_link_control( $link_key, $url, $content, $attr = [] ) {
		$attr_default = [
			'href' => $url['url'] ? $url['url'] : '#',
		];

		if ( $url['is_external'] ) {
			$attr_default['target'] = '_blank';
		}

		if ( $url['nofollow'] ) {
			$attr_default['rel'] = 'nofollow';
		}

		$attr = wp_parse_args( $attr, $attr_default );

		$this->add_render_attribute( $link_key, $attr );

		return sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( $link_key ), $content );
	}
}