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
class Member extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'martfury-member';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Member', 'martfury' );
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
			[ 'label' => esc_html__( 'General', 'martfury' ) ]
		);

		$this->add_control(
			'image', [
				'label'   => esc_html__( 'Choose Image', 'martfury' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/270/f8f8f8?text=270x270+Image',
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
			]
		);

		$this->add_control(
			'name',
			[
				'label'       => esc_html__( 'Name', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Robert Downey Jr', 'martfury' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'job',
			[
				'label'       => esc_html__( 'Job', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'CEO Founder', 'martfury' ),
				'label_block' => true,
			]
		);

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'icon_type',
			[
				'label'   => esc_html__( 'Icon Type', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'icon'        => esc_html__( 'Old Icon', 'martfury' ),
					'custom_icon' => esc_html__( 'New Icon', 'martfury' ),
				],
				'default' => 'icon',
				'toggle'  => false,
			]
		);
		$repeater->add_control(
			'icon',
			[
				'label'     => esc_html__( 'Icon', 'martfury' ),
				'type'      => Controls_Manager::ICON,
				'default'   => 'fa fa-star',
				'condition' => [
					'icon_type' => 'icon',
				],
			]
		);

		$repeater->add_control(
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
			'socials',
			[
				'label'         => esc_html__( 'Socials', 'martfury' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'icon' => 'fa fa-twitter',
						'link' => [
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => true,
						],
					],
					[
						'icon' => 'fa fa-facebook',
						'link' => [
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => true,
						],
					],
					[
						'icon' => 'fa fa-linkedin-square',
						'link' => [
							'url'         => '#',
							'is_external' => true,
							'nofollow'    => true,
						],
					]
				],
				'prevent_empty' => false
			]
		);

		$this->end_controls_section();

		/**
		 * Tab Style
		 */
		// Content
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'text_align',
			[
				'label'     => esc_html__( 'Alignment', 'martfury' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'martfury' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'martfury' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'martfury' ),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'martfury' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'default'   => 'left',
				'selectors' => [
					'{{WRAPPER}} .martfury-member .member-content' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'background_color',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .martfury-member:after' => 'background-color: {{VALUE}};',
				],
				'default'   => '',
			]
		);
		$this->add_control(
			'opacity',
			[
				'label'     => esc_html__( 'Opacity', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max'  => 1,
						'min'  => 0,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .martfury-member:hover:after' => 'opacity: {{SIZE}};',
				],
			]
		);
		$this->add_control(
			'separator',
			[
				'label'     => esc_html__( 'Position', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$position = [
			'top'    => esc_html__( 'Top', 'martfury' ),
			'right'  => esc_html__( 'Right', 'martfury' ),
			'bottom' => esc_html__( 'Bottom', 'martfury' ),
			'left'   => esc_html__( 'Left', 'martfury' ),
		];

		foreach ( $position as $key => $label ) {
			$this->add_responsive_control(
				$key,
				[
					'label'     => $label,
					'type'      => Controls_Manager::NUMBER,
					'min'       => 0,
					'max'       => 100,
					'step'      => 1,
					'default'   => '',
					'selectors' => [
						"{{WRAPPER}} .martfury-member .member-content" => "$key: {{VALUE}}px;",
					],
				]
			);
		}

		$this->end_controls_section();

		// Name
		$this->start_controls_section(
			'section_name_style',
			[
				'label' => esc_html__( 'Name', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'selector' => '{{WRAPPER}} .martfury-member .name',
			]
		);
		$this->add_control(
			'name_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .martfury-member .name' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'name_spacing',
			[
				'label'     => esc_html__( 'Bottom Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .martfury-member .name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		// Job
		$this->start_controls_section(
			'section_job_style',
			[
				'label' => esc_html__( 'Job', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'job_typography',
				'selector' => '{{WRAPPER}} .martfury-member .job',
			]
		);
		$this->add_control(
			'job_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .martfury-member .job' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();

		// Socials
		$this->start_controls_section(
			'section_socials_style',
			[
				'label' => esc_html__( 'Socials', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'social_size',
			[
				'label'     => esc_html__( 'Font Size', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .martfury-member .socials .link' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'social_svg_size',
			[
				'label'     => esc_html__( 'SVG Font Size', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .martfury-member .socials .link .icon-svg' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'social_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .martfury-member .socials .link' => 'color: {{VALUE}};',
					'{{WRAPPER}} .martfury-member .socials .link svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'social_gap',
			[
				'label'     => esc_html__( 'Gaps', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .martfury-member .socials .link'            => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .martfury-member .socials .link:last-child' => 'margin-right: 0;',
				],
			]
		);
		$this->add_responsive_control(
			'social_spacing',
			[
				'label'     => esc_html__( 'Top Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .martfury-member .socials' => 'margin-top: {{SIZE}}{{UNIT}};',
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
			'martfury-member'
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$output = [];

		if ( $settings['name'] ) {
			$output[] = sprintf( '<h4 class="name">%s</h4>', $settings['name'] );
		}

		if ( $settings['job'] ) {
			$output[] = sprintf( '<span class="job">%s</span>', $settings['job'] );
		}

		$socials = $settings['socials'];

		$socials_html = [];

		if ( ! empty ( $socials ) ) {

			foreach ( $socials as $index => $item ) {
				$link_key = 'link_' . $index;
				$icon     = '';
				$icon_class = '';
				if ( $item['icon_type'] == 'icon' ) {
					if ( $item['icon'] ) {
						$icon = '<span class="mf-icon"><i class="' . esc_attr( $item['icon'] ) . '"></i></span>';
					}
				} else {
					if ( $item['custom_icon'] && \Elementor\Icons_Manager::is_migration_allowed() ) {
						ob_start();
						\Elementor\Icons_Manager::render_icon( $item['custom_icon'], [ 'aria-hidden' => 'true' ] );
						if( $item['custom_icon']['library'] == 'svg' ) {
							$icon_class = 'icon-svg';
						}

						$icon = '<span class="mf-icon ' . $icon_class .  '">' . ob_get_clean() . '</span>';


					}
				}

				$link = $this->get_link_control( $link_key, $item['link'], $icon, 'link' );

				$socials_html[] = sprintf( '%s', $link );
			}
		}

		$output[] = sprintf( '<div class="socials">%s</div>', implode( '', $socials_html ) );

		$image = '';
		if ( $settings['image'] ) {
			$image = Group_Control_Image_Size::get_attachment_image_html( $settings );
		}

		echo sprintf(
			'<div %s>%s<div class="member-content">%s</div></div>',
			$this->get_render_attribute_string( 'wrapper' ),
			$image,
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