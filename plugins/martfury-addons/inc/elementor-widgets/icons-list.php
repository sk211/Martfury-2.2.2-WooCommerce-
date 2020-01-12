<?php

namespace MartfuryAddons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Icons_List extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'martfury-icons-list';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Martfury - Icons List', 'martfury' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-bullet-list';
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
			'section_icon',
			[ 'label' => esc_html__( 'Icons List', 'martfury' ) ]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'icon_type',
			[
				'label'   => esc_html__( 'Icon Type', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'icons'        => esc_html__( 'Old Icons', 'martfury' ),
					'custom_icons' => esc_html__( 'New Icon', 'martfury' ),
				],
				'default' => 'icons',
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
					'icon_type' => 'icons',
				],
			]
		);

		$repeater->add_control(
			'custom_icon',
			[
				'label'            => esc_html__( 'Icon', 'martfury' ),
				'type'             => Controls_Manager::ICONS,
				'default'          => [
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition'        => [
					'icon_type' => 'custom_icons',
				],
			]
		);

		$repeater->add_control(
			'title', [
				'label'       => esc_html__( 'Title', 'martfury' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'This is the title', 'martfury' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'desc', [
				'label'       => esc_html__( 'Description', 'martfury' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'This is the description', 'martfury' ),
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


		$this->add_control(
			'icons',
			[
				'label'         => '',
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $repeater->get_controls(),
				'default'       => [
					[
						'icon'  => 'icon-rocket',
						'title' => esc_html__( 'This is the title', 'martfury' ),
						'desc'  => esc_html__( 'This is the description', 'martfury' ),
					],
					[
						'icon'  => 'icon-sync',
						'title' => esc_html__( 'This is the title 2', 'martfury' ),
						'desc'  => esc_html__( 'This is the description 2', 'martfury' ),
					],
					[
						'icon'  => 'icon-credit-card',
						'title' => esc_html__( 'This is the title 3', 'martfury' ),
						'desc'  => esc_html__( 'This is the description 3', 'martfury' ),
					],
					[
						'icon'  => 'icon-bubbles',
						'title' => esc_html__( 'This is the title 4', 'martfury' ),
						'desc'  => esc_html__( 'This is the description 4', 'martfury' ),
					],
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
				'label' => esc_html__( 'Icons List', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'columns',
			[
				'label'        => esc_html__( 'Columns', 'martfury' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'1' => esc_html__( '1 Columns', 'martfury' ),
					'2' => esc_html__( '2 Columns', 'martfury' ),
					'3' => esc_html__( '3 Columns', 'martfury' ),
					'4' => esc_html__( '4 Columns', 'martfury' ),
					'5' => esc_html__( '5 Columns', 'martfury' ),
				],
				'default'      => '4',
				'toggle'       => false,
				'prefix_class' => 'columns-%s',
			]
		);
		$this->add_control(
			'background_color',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icons-list' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .mf-elementor-icons-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector'  => '{{WRAPPER}} .mf-elementor-icons-list',
				'separator' => 'before',
			]
		);
		$this->end_controls_section();

		// Content
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Item Content', 'martfury' ),
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
					'{{WRAPPER}} .mf-elementor-icons-list .icons-list-wrapper .martfury-icon-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'default'    => [
					'top'    => '10',
					'right'  => '10',
					'bottom' => '10',
					'left'   => '10',
				],
			]
		);
		$this->add_responsive_control(
			'content_bottom_spacing',
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
					'{{WRAPPER}} .mf-elementor-icons-list .icons-list-wrapper .box-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
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
		$this->add_responsive_control(
			'extra_link_icon_indent',
			[
				'label'     => esc_html__( 'Icon Spacing', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 200,
						'min' => 10,
					],
				],
				'default'   => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icons-list .mf-icon-left .box-icon'       => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mf-elementor-icons-list .mf-icon-right .box-icon'      => 'padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mf-elementor-icons-list .mf-icon-center .box-icon'     => 'padding-bottom: {{SIZE}}{{UNIT}};',
					'.rtl {{WRAPPER}} .mf-elementor-icons-list .mf-icon-left .box-icon'  => 'padding-left: {{SIZE}}{{UNIT}};padding-right: 0',
					'.rtl {{WRAPPER}} .mf-elementor-icons-list .mf-icon-right .box-icon' => 'padding-right: {{SIZE}}{{UNIT}};padding-left:0',
				],
			]
		);
		$this->add_responsive_control(
			'icon_font_size',
			[
				'label'     => esc_html__( 'Icon Font Size', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 40,
				],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icons-list .icons-list-wrapper .box-icon' => 'font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .mf-elementor-icons-list .icons-list-wrapper .box-icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .mf-elementor-icons-list .icons-list-wrapper .box-icon svg' => 'fill: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .mf-elementor-icons-list .martfury-icon-box .box-title',
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
					'{{WRAPPER}} .mf-elementor-icons-list .martfury-icon-box .box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .mf-elementor-icons-list .martfury-icon-box .box-title' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .mf-elementor-icons-list .martfury-icon-box .box-title:hover,
					{{WRAPPER}} .mf-elementor-icons-list .martfury-icon-box .box-title:focus' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .mf-elementor-icons-list .martfury-icon-box .desc',
			]
		);
		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__( 'Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icons-list .icons-list-wrapper .desc' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
		// Separator
		$this->start_controls_section(
			'section_sep_style',
			[
				'label' => esc_html__( 'Separator', 'martfury' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'sep_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'martfury' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icons-list .icons-list-wrapper .separator' => 'background-color: {{VALUE}};',
				],
				'default'   => 'rgb(218,218,218)',
			]
		);
		$this->add_responsive_control(
			'sep_width',
			[
				'label'     => esc_html__( 'Width', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 1,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icons-list .icons-list-wrapper .separator' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'sep_height',
			[
				'label'     => esc_html__( 'Height', 'martfury' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 60,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .mf-elementor-icons-list .icons-list-wrapper .separator' => 'height: {{SIZE}}{{UNIT}};',
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
			[
				'wrapper'      => [
					'class' => 'mf-elementor-icons-list',
				],
				'icon-wrapper' => [
					'class' => [
						'martfury-icon-box',
						'mf-icon-' . $settings['icon_position']
					],
				],
			]
		);

		$icons = $settings['icons'];
		?>
        <div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
            <div class="icons-list-wrapper">
				<?php
				if ( $icons ) {
					foreach ( $icons as $index => $item ) {
						$link_key = 'link_' . $index;

						if ( $item['link']['is_external'] ) {
							$this->add_render_attribute( $link_key, 'target', '_blank' );
						}

						if ( $item['link']['nofollow'] ) {
							$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
						}

						$link = '';
						if ( $item['link']['url'] ) {
							$this->add_render_attribute( $link_key, 'href', $item['link']['url'] );
							$link = sprintf( '<a class="box-title" %s>%s</a>', $this->get_render_attribute_string( $link_key ), esc_html( $item['title'] ) );
						} else {
							$link = sprintf( '<span class="box-title" %s>%s</span>', $this->get_render_attribute_string( $link_key ), esc_html( $item['title'] ) );
						}

						$icon = '';
						$icon_class = '';
						if ( $item['icon_type'] == 'icons' ) {
							if ( $item['icon'] ) {
								$icon = '<i class="' . esc_attr( $item['icon'] ) . '"></i>';
							}
						} elseif ( $item['icon_type'] == 'custom_icons' ) {
							if ( $item['custom_icon'] && \Elementor\Icons_Manager::is_migration_allowed() ) {
								ob_start();
								\Elementor\Icons_Manager::render_icon( $item['custom_icon'], [ 'aria-hidden' => 'true' ] );
								$icon = ob_get_clean();

								if( $item['custom_icon']['library'] == 'svg' ) {
									$icon_class = 'icon-svg';
                                }
							}
						}

						?>
                        <div class="box-item">
                            <div <?php echo $this->get_render_attribute_string( 'icon-wrapper' ); ?>>
                                <div class="mf-icon box-icon <?php echo esc_attr($icon_class); ?>">
									<?php echo $icon; ?>
                                </div>
                                <div class="box-wrapper">
									<?php echo $link; ?>
                                    <div class="desc"><?php echo isset( $item['desc'] ) ? $item['desc'] : ''; ?></div>
                                </div>
                            </div>
                            <div class="separator"></div>
                        </div>
						<?php
					}

				}
				?>
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
}