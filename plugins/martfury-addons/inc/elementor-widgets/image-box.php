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
 * Image Box widget
 */
class Image_Box extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'martfury-image-box';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Martfury - Image Box', 'martfury' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-image-box';
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
			'section_image',
			[ 'label' => esc_html__( 'Image', 'martfury' ) ]
		);

		$this->add_control(
			'image',
			[
				'label'   => esc_html__( 'Choose Image', 'martfury' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => 'https://via.placeholder.com/170x170/ffffff?text=170x170',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				// Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'   => 'full',
				'separator' => 'none',
			]
		);
		$this->add_responsive_control(
			'image_position',
			[
				'label'        =>esc_html__( 'Image Position', 'martfury' ),
				'type'         => Controls_Manager::CHOOSE,
				'default'      => 'left',
				'options'      => [
					'left'  => [
						'title' =>esc_html__( 'Left', 'martfury' ),
						'icon'  => 'fa fa-align-left',
					],
					'top'   => [
						'title' =>esc_html__( 'Top', 'martfury' ),
						'icon'  => 'fa fa-align-center',
					],
					'right' => [
						'title' =>esc_html__( 'Right', 'martfury' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'prefix_class' => 'elementor-position-%s',
				'toggle'       => false,
				'separator'    => 'before',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_content',
			[ 'label' => esc_html__( 'Content', 'martfury' ) ]
		);

		$this->start_controls_tabs( 'image_content_settings' );

		$this->start_controls_tab( 'tab_title', [ 'label' => esc_html__( 'Title', 'martfury' ) ] );

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'The Title', 'martfury' ),
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
					'url'         => '#',
					'is_external' => true,
					'nofollow'    => true,
				],
			]
		);
		$this->end_controls_tab();


		$this->start_controls_tab( 'tab_subtitles', [ 'label' => esc_html__( 'Subtitles', 'martfury' ) ] );

		$this->add_responsive_control(
			'show_subtitles',
			[
				'label'     => esc_html__( 'Show Subtitles', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'block' => esc_html__( 'Show', 'martfury' ),
					'none'  => esc_html__( 'Hide', 'martfury' ),
				],
				'default'   => 'block',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box .image-content ul li:not(.last-link)' => 'display: {{VALUE}}',

				],
			]
		);
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'title', [
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
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
			'subtitles',
			[
				'label'         => 'Subtitles',
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'title' => esc_html__( 'Subtitle 1', 'martfury' ),
					],
					[
						'title' => esc_html__( 'Subtitle 2', 'martfury' ),
					],
					[
						'title' => esc_html__( 'Subtitle 3', 'martfury' ),
					],
					[
						'title' => esc_html__( 'Subtitle 4', 'martfury' ),
					],
				],
				'title_field'   => '{{{ title }}}',
				'prevent_empty' => false,

			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab( 'tab_extra_link', [ 'label' => esc_html__( 'Extra Link', 'martfury' ) ] );
		$this->add_responsive_control(
			'show_extra_link',
			[
				'label'     => esc_html__( 'Show Extra Link', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'block' => esc_html__( 'Show', 'martfury' ),
					'none'  => esc_html__( 'Hide', 'martfury' ),
				],
				'default'   => 'block',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box .image-content ul li.last-link' => 'display: {{VALUE}}',

				],
			]
		);
		$this->add_control(
			'extra_link_text',
			[
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => esc_html__( 'Enter your title', 'martfury' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'extra_link', [
				'label'         => esc_html__( 'Link', 'martfury' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => esc_html__( 'https://your-link.com', 'martfury' ),
				'show_external' => true,
				'default' => [
					'url'         => '#',
					'is_external' => true,
					'nofollow'    => true,
				],
			]
		);
		$this->add_control(
			'extra_link_icon',
			[
				'label'     => esc_html__( 'Icon', 'martfury' ),
				'type'      => Controls_Manager::ICON,
				'default'   => 'chevron-right',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'extra_link_icon_align',
			[
				'label'   =>esc_html__( 'Icon Position', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left'  =>esc_html__( 'Before', 'martfury' ),
					'right' =>esc_html__( 'After', 'martfury' ),
				]
			]
		);

		$this->add_control(
			'extra_link_icon_indent',
			[
				'label'     =>esc_html__( 'Icon Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box a.view-more .align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mf-elementor-image-box a.view-more .align-icon-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Tab Style
		 */
		// General
		$this->start_controls_section(
			'section_image_box_style',
			[
				'label' =>esc_html__( 'Image Box', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'min_height',
			[
				'label'     =>esc_html__( 'Min height', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 1000,
						'min' => 100,
					],
				],
				'default'   => [],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label'      =>esc_html__( 'Padding', 'martfury' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .mf-elementor-image-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'background_color',
			[
				'label'     =>esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box' => 'background-color: {{VALUE}};',
				],
				'default'   => '',
			]
		);
		$this->add_control(
			'border_style',
			[
				'label'     => esc_html__( 'Border Style', 'martfury' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'solid'  => esc_html__( 'Solid', 'martfury' ),
					'dotted' => esc_html__( 'Dotted', 'martfury' ),
					'dashed' => esc_html__( 'Dashed', 'martfury' ),
					'double' => esc_html__( 'Double', 'martfury' ),
					'none'   => esc_html__( 'None', 'martfury' ),
				],
				'default'   => 'solid',
				'toggle'    => false,
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'border_width',
			[
				'label'     =>esc_html__( 'Border Width', 'martfury' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label'     =>esc_html__( 'Border Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_border_color',
			[
				'label'     =>esc_html__( 'Hover Border Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_content_style',
			[
				'label' =>esc_html__( 'Content', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'text_align',
			[
				'label'     =>esc_html__( 'Alignment', 'martfury' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' =>esc_html__( 'Left', 'martfury' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'  => [
						'title' =>esc_html__( 'Center', 'martfury' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'   => [
						'title' =>esc_html__( 'Right', 'martfury' ),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' =>esc_html__( 'Justified', 'martfury' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box' => 'text-align: {{VALUE}};',
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
					'{{WRAPPER}} .mf-elementor-image-box .image-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);
		$this->add_control(
			'title_heading',
			[
				'label'     =>esc_html__( 'Title', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'heading_title_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 15,
					'unit' => 'px',
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box .box-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box .box-title, {{WRAPPER}} .mf-elementor-image-box .box-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_border_color',
			[
				'label'     => esc_html__( 'Hover Title Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box .box-title:hover,
					{{WRAPPER}} .mf-elementor-image-box .box-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mf-elementor-image-box .box-title',
			]
		);

		$this->add_control(
			'subtitles_heading',
			[
				'label'     =>esc_html__( 'SubTitles', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'subtitles_items_spacing',
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
					'{{WRAPPER}} .mf-elementor-image-box .image-content ul li'            => 'margin-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .mf-elementor-image-box .image-content ul li:last-child' => 'margin-bottom: 0',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitles_typography',
				'selector' => '{{WRAPPER}} .mf-elementor-image-box .extra-links a.extra-link',
			]
		);

		$this->start_controls_tabs( 'tab_subtitle_style' );
		$this->start_controls_tab(
			'tab_subtitle_normal',
			[
				'label' =>esc_html__( 'Normal', 'martfury' ),
			]
		);
		$this->add_control(
			'subtitle_color',
			[
				'label'     =>esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box .extra-links a.extra-link' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'border_bottom_color',
			[
				'label'     =>esc_html__( 'Border Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box .extra-links a.extra-link' => 'border-bottom-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Hover Tab
		$this->start_controls_tab(
			'tab_subtitle_hover',
			[
				'label' =>esc_html__( 'Hover', 'martfury' ),
			]
		);

		$this->add_control(
			'subtitle_hover_color',
			[
				'label'     =>esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box .extra-links a.extra-link:hover,
					{{WRAPPER}} .mf-elementor-image-box .extra-links a.extra-link:focus' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'border_bottom_hover_color',
			[
				'label'     =>esc_html__( 'Border Bottom Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box .extra-links a:hover' => 'border-bottom-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'extra_link_heading',
			[
				'label'     =>esc_html__( 'Extra Link', 'martfury' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'extra_link_spacing',
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
					'{{WRAPPER}} .mf-elementor-image-box .image-content ul li .view-more' => 'padding-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'extra_link_typography',
				'selector' => '{{WRAPPER}} .mf-elementor-image-box .extra-links a.view-more',
			]
		);

		$this->add_control(
			'extra_link_color',
			[
				'label'     =>esc_html__( 'Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box .extra-links a.view-more' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'extra_link_hover_color',
			[
				'label'     =>esc_html__( 'Hover Text Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-image-box .extra-links a.view-more:hover,
					{{WRAPPER}} .mf-elementor-image-box .extra-links a.view-more:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_font_size',
			[
				'label'     => esc_html__( 'Icon Font Size', 'martfury' ),
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
					'{{WRAPPER}} .mf-elementor-image-box a.view-more .extra-icon' => 'font-size: {{SIZE}}{{UNIT}}',
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
				'mf-elementor-image-box',
			]
		);

		?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php
			$image = Group_Control_Image_Size::get_attachment_image_html( $settings );
			echo $this->get_link_control( 'link', $settings['link'], $image, 'thumbnail' );
			?>
            <div class="image-content">
                <h2 class="box-title">
					<?php
					echo $this->get_link_control( 'link', $settings['link'], $settings['title'], '' );
					?>
                </h2>
                <ul class="extra-links">

					<?php
					$links = $settings['subtitles'];
					if ( $links ) {
						foreach ( $links as $index => $item ) {
							$link_key = 'extra_link' . $index;
							$classes  = 'extra-link';
							echo sprintf( '<li>%s</li>', $this->get_link_control( $link_key, $item['link'], $item['title'], $classes ) );
						}
					}
					?>

					<?php
					if ( $settings['extra_link_text'] ) {
						$icon = '';
						if ( $settings['extra_link_icon'] ) {
							$this->add_render_attribute(
								[
									'icon-wrapper' => [
										'class' => [
											'extra-icon',
											'align-icon-' . $settings['extra_link_icon_align'],
										],
									],
								]
							);
							$icon = sprintf(
								'<span %s><i class="%s"></i></span>',
								$this->get_render_attribute_string( 'icon-wrapper' ),
								esc_attr( $settings['extra_link_icon'] )
							);
						}
						$extra_link_content = $settings['extra_link_text'] . $icon;
						echo sprintf( '<li class="last-link">%s</li>', $this->get_link_control( 'extra_link', $settings['extra_link'], $extra_link_content, 'view-more' ) );
					}

					?>
                </ul>
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