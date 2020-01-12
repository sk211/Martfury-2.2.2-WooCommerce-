<?php

/**
 * Define theme shortcodes
 *
 * @package Martfury
 */

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

class Martfury_Shortcodes {
	/**
	 * Store variables for js
	 *
	 * @var array
	 */
	public $l10n = array();

	public $maps = array();

	public $api_key = '';

	/**
	 * Check if WooCommerce plugin is actived or not
	 *
	 * @var bool
	 */
	private $wc_actived = false;

	public $cat_tabs = array();

	/**
	 * Construction
	 *
	 * @return Martfury_Shortcodes
	 */
	function __construct() {

		if ( ! is_plugin_active( 'js_composer/js_composer.php' ) ) {
			return false;
		}

		$this->wc_actived = function_exists( 'is_woocommerce' );

		$shortcodes = array(
			'empty_space',
			'icon_box',
			'icon_box_2',
			'icons_list',
			'button',
			'post_grid',
			'image_box',
			'counter',
			'journey',
			'testimonial_slides',
			'member',
			'partner',
			'process',
			'bubbles',
			'newsletter',
			'faqs',
			'images_grid',
			'products_of_category',
			'products_of_category_2',
			'product_tabs',
			'products_carousel',
			'products_list_carousel',
			'deals_of_the_day',
			'sales_countdown_timer',
			'product_deals_carousel',
			'product_deals_grid',
			'recently_viewed_products',
			'category_box',
			'top_selling',
			'top_selling_2',
			'countdown',
			'gmap',
			'category_tabs',
			'category_tab',
			'single_image',
			'banners_grid',
			'banners_grid_2',
			'banner_small',
			'banner_large',
			'banner_medium',
			'products_grid',
			'products_list',
		);

		foreach ( $shortcodes as $shortcode ) {
			add_shortcode( 'martfury_' . $shortcode, array( $this, $shortcode ) );
		}

		add_action( 'wp_footer', array( $this, 'footer' ) );

		add_action( 'wp_ajax_martfury_get_shortcode_ajax', array( $this, 'get_shortcode_ajax' ) );
		add_action( 'wp_ajax_nopriv_martfury_get_shortcode_ajax', array( $this, 'get_shortcode_ajax' ) );
		add_action( 'martfury_woo_after_shop_loop_item', array( $this, 'deal_progress' ), 7 );

		add_action( 'wc_ajax_nopriv_mf_wpbakery_load_products', [ $this, 'wpbakery_load_products' ] );
		add_action( 'wc_ajax_mf_wpbakery_load_products', [ $this, 'wpbakery_load_products' ] );


	}


	/**
	 * Load custom js in footer
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function footer() {

		// Load Google maps only when needed
		if ( isset( $this->l10n['map'] ) ) {
			echo '<script>if ( typeof google !== "object" || typeof google.maps !== "object" )
				document.write(\'<script src="//maps.google.com/maps/api/js?sensor=false&key=' . $this->api_key . '"><\/script>\')</script>';
		}

		wp_enqueue_script( 'martfury-shortcodes', MARTFURY_ADDONS_URL . '/assets/js/frontend.js', array( 'jquery' ), '20170530', true );

		$this->l10n['days']      = esc_html__( 'days', 'martfury' );
		$this->l10n['hours']     = esc_html__( 'hours', 'martfury' );
		$this->l10n['minutes']   = esc_html__( 'minutes', 'martfury' );
		$this->l10n['seconds']   = esc_html__( 'seconds', 'martfury' );
		$this->l10n['direction'] = is_rtl() ? 'true' : 'false';
		wp_localize_script( 'martfury-shortcodes', 'martfuryShortCode', $this->l10n );

	}


	/**
	 * Get empty space
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function empty_space( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'height'        => '',
				'height_mobile' => '',
				'height_tablet' => '',
				'el_class'      => '',
			), $atts
		);


		$css_class[] = $atts['el_class'];

		$height        = $atts['height'] ? (float) $atts['height'] : '';
		$height_tablet = $atts['height_tablet'] ? (float) $atts['height_tablet'] : $height;
		$height_mobile = $atts['height_mobile'] ? (float) $atts['height_mobile'] : $height_tablet;

		$inline_css        = $height >= 0.0 ? ' style="height: ' . esc_attr( $height ) . 'px"' : '';
		$inline_css_mobile = $height_mobile >= 0.0 ? ' style="height: ' . esc_attr( $height_mobile ) . 'px"' : '';
		$inline_css_tablet = $height_tablet >= 0.0 ? ' style="height: ' . esc_attr( $height_tablet ) . 'px"' : '';


		return sprintf(
			'<div class="martfury-empty-space %s">' .
			'<div class="mf_empty_space_lg" %s></div>' .
			'<div class="mf_empty_space_md" %s></div>' .
			'<div class="mf_empty_space_xs" %s></div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$inline_css,
			$inline_css_tablet,
			$inline_css_mobile
		);

	}

	/**
	 * Icon Box
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function icon_box( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'icon_type'        => 'fontawesome',
				'icon_fontawesome' => 'fa fa-adjust',
				'icon_linearicons' => '',
				'image'            => '',
				'icon_position'    => 'left',
				'text_align'       => 'left',
				'link'             => '',
				'title'            => esc_html__( 'I am Icon Box', 'martfury' ),
				'el_class'         => '',
			), $atts
		);

		$css_class = array(
			'martfury-icon-box',
			'icon_position-' . $atts['icon_position'],
			'text_' . $atts['text_align'],
			$atts['el_class'],
		);

		$attributes = array();

		$title = $icon = $link = $button = '';

		$link = vc_build_link( $atts['link'] );

		if ( ! empty( $link['url'] ) ) {
			$attributes['href'] = $link['url'];
		}

		$label = $link['title'];

		if ( ! empty( $label ) ) {
			$attributes['title'] = $label;
		}

		if ( ! empty( $link['target'] ) ) {
			$attributes['target'] = $link['target'];
		}

		if ( ! empty( $link['rel'] ) ) {
			$attributes['rel'] = $link['rel'];
		}

		$attr = array();

		foreach ( $attributes as $name => $value ) {
			$attr[] = $name . '="' . esc_attr( $value ) . '"';
		}

		if ( $atts['title'] ) {
			$title = sprintf(
				'<%1$s class="box-title"><%2$s %3$s>%4$s</%2$s></%1$s>',
				'h3',
				empty( $attributes['href'] ) ? 'span' : 'a',
				implode( ' ', $attr ),
				$atts['title']
			);
		}

		if ( ! empty( $link['title'] ) ) {
			$button = sprintf(
				'<%1$s %2$s class="box-url">%3$s</%1$s>',
				'a',
				implode( ' ', $attr ),
				$label
			);
		}

		if ( 'image' == $atts['icon_type'] ) {
			if ( function_exists( 'martfury_get_image_html' ) ) {
				$image = martfury_get_image_html( $atts['image'], 'full', 'img-icon' );
			} else {
				$image = wp_get_attachment_image( $atts['image'], 'full', '', array( 'class' => 'img-icon' ) );
			}
			$icon = $image ? sprintf( '<div class="mf-icon-area box-img">%s</div>', $image ) : '';
		} else {
			$icon = '<div class="mf-icon box-icon"><i class="' . esc_attr( $atts[ 'icon_' . $atts['icon_type'] ] ) . '"></i></div>';
		}


		return sprintf(
			'<div class="%s">%s<div class="box-wrapper">%s<div class="desc">%s</div>%s</div></div>',
			esc_attr( implode( ' ', $css_class ) ),
			$icon,
			$title,
			$content,
			$button
		);
	}

	/**
	 * Shortcode to display Icon Box 2
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function icon_box_2( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'border'   => '',
				'info'     => '',
				'el_class' => '',
			), $atts
		);

		$css_class = array(
			'martfury-icon-box-2',
			$atts['el_class'],
		);

		if ( $atts['border'] ) {
			$css_class[] = 'show-border';
		}

		$output = array();

		$info = function_exists( 'vc_param_group_parse_atts' ) ? vc_param_group_parse_atts( $atts['info'] ) : array();

		if ( ! empty( $info ) ) {
			foreach ( $info as $key => $value ) {
				$icon       = $title = $desc = '';
				$attributes = array();

				if ( 'image' == $value['icon_type'] ) {
					if ( isset( $value['image'] ) ) {
						if ( function_exists( 'martfury_get_image_html' ) ) {
							$image = martfury_get_image_html( $atts['image'], 'full', 'img-icon' );
						} else {
							$image = wp_get_attachment_image( $value['image'], 'full', '', array( 'class' => 'img-icon' ) );
						}
						$icon = $image ? sprintf( '<div class="mf-icon-area box-img">%s</div>', $image ) : '';
					}
				} else {
					vc_icon_element_fonts_enqueue( $value['icon_type'] );
					$icon = '<div class="mf-icon box-icon"><i class="' . esc_attr( $value[ 'icon_' . $value['icon_type'] ] ) . '"></i></div>';
				}

				if ( isset( $value['link'] ) ) {
					$link = vc_build_link( $value['link'] );

					if ( ! empty( $link['url'] ) ) {
						$attributes['href'] = $link['url'];
					}

					if ( ! empty( $link['title'] ) ) {
						$attributes['title'] = $link['title'];
					}

					if ( ! empty( $link['target'] ) ) {
						$attributes['target'] = $link['target'];
					}

					if ( ! empty( $link['rel'] ) ) {
						$attributes['rel'] = $link['rel'];
					}
				}

				$attr = array();

				foreach ( $attributes as $name => $v ) {
					$attr[] = $name . '="' . esc_attr( $v ) . '"';
				}

				if ( ! empty( $value['title'] ) ) {

					$title = sprintf(
						'<%1$s class="box-title"><%2$s %3$s>%4$s</%2$s></%1$s>',
						'h4',
						empty( $attributes['href'] ) ? 'span' : 'a',
						implode( ' ', $attr ),
						$value['title']
					);
				}

				if ( ! empty( $value['desc'] ) ) {
					$desc = sprintf( '<div class="desc">%s</div>', $value['desc'] );
				}


				$output[] = sprintf(
					'<div class="box-item">%s<div class="box-content">%s%s</div></div>',
					$icon,
					$title,
					$desc
				);
			}
		}


		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);
	}

	/**
	 * Icon Box
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function icons_list( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'style'    => '1',
				'icons'    => '',
				'el_class' => '',
			), $atts
		);

		$css_class = array(
			'martfury-icons-list',
			'style-' . $atts['style'],
			$atts['el_class'],
		);

		$output = array();

		$icons = function_exists( 'vc_param_group_parse_atts' ) ? vc_param_group_parse_atts( $atts['icons'] ) : array();

		if ( $icons ) {
			$count       = count( $icons );
			$css_class[] = $count > 0 ? 'columns-' . $count : '';
			foreach ( $icons as $icon ) {
				$link_atts = array(
					'link'    => isset( $icon['link'] ) ? $icon['link'] : '',
					'content' => isset( $icon['title'] ) ? $icon['title'] : '',
					'class'   => 'box-title',
				);
				$output[]  = sprintf(
					'<li class="martfury-icon-box icon_position-left">' .
					'<div class="mf-icon box-icon">' .
					'<i class="%s"></i>' .
					'</div>' .
					'<div class="box-wrapper">' .
					'%s' .
					'<div class="desc">%s</div>' .
					'</div>' .
					'</li>
					<li class="martfury-icon-box icon-box-step"></li>',
					esc_attr( $icon['icon_linearicons'] ),
					$this->get_vc_link( $link_atts ),
					isset( $icon['desc'] ) ? $icon['desc'] : ''
				);
			}
		}

		return sprintf(
			'<div class="%s"><ul>%s</ul></div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( ' ', $output )
		);
	}

	/**
	 * Category Tabs
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function category_tabs( $atts, $content ) {
		$atts           = shortcode_atts(
			array(
				'title'    => '',
				'subtitle' => '',
				'el_class' => '',
			), $atts
		);
		$this->cat_tabs = array();
		do_shortcode( $content );

		$css_class = array(
			$atts['el_class']
		);

		if ( empty( $this->cat_tabs ) ) {
			return '';
		}

		$total = count( $this->cat_tabs );

		if ( ! $total ) {
			return '';
		}

		if ( $total == 1 ) {
			$css_class[] = 'single-tab';
		}

		$tab_header  = array();
		$tab_content = array();
		foreach ( $this->cat_tabs as $index => $tab ) {

			$icon = '';
			if ( 'image' == $tab['icon_type'] ) {
				if ( function_exists( 'martfury_get_image_html' ) ) {
					$image = martfury_get_image_html( $tab['image'], 'full', 'img-icon' );
				} else {
					$image = wp_get_attachment_image( $tab['image'], 'full', '', array( 'class' => 'img-icon' ) );
				}
				$icon = $image ? $image : '';
			} else {
				$icon = '<i class="' . esc_attr( $tab[ 'icon_' . $tab['icon_type'] ] ) . '"></i>';
			}
			$tab_header[] = sprintf(
				'<li><a href="#">%s<h2>%s</h2></a></li>',
				$icon,
				$tab['title']
			);

			$tags = function_exists( 'vc_param_group_parse_atts' ) ? vc_param_group_parse_atts( $tab['tags'] ) : array();

			$tag_image_size = isset( $tab['image_size'] ) ? $tab['image_size'] : 'thumbnail';

			if ( $tags ) {
				$tab_content[] = '<div class="tabs-panel"><ul>';
				foreach ( $tags as $tag ) {

					$tag_image = $this->get_Image_By_Size(
						array(
							'attach_id'  => isset( $tag['image'] ) ? $tag['image'] : '',
							'thumb_size' => $tag_image_size,
						)
					);
					$link      = isset( $tag['link'] ) ? vc_build_link( $tag['link'] ) : '';

					$attributes = array();
					if ( ! empty( $link['url'] ) ) {
						$attributes['href'] = $link['url'];
					}

					if ( ! empty( $link['target'] ) ) {
						$attributes['target'] = $link['target'];
					}

					if ( ! empty( $link['rel'] ) ) {
						$attributes['rel'] = $link['rel'];
					}
					$attr = array();
					foreach ( $attributes as $name => $value ) {
						$attr[] = $name . '="' . esc_attr( $value ) . '"';
					}

					$tab_content[] = sprintf(
						'<li><a %s><span class="t-imgage">%s</span><h2>%s</h2></a></li>',
						implode( ' ', $attr ),
						$tag_image,
						isset( $tag['title'] ) ? $tag['title'] : ''
					);

				}
				$tab_content[] = '</ul></div>';
			}

		}

		$tab_title = '';
		if ( $atts['title'] ) {
			$tab_title = sprintf( '<h2>%s</h2>', $atts['title'] );
		}
		if ( $atts['subtitle'] ) {
			$tab_title .= sprintf( '<span>%s</span>', $atts['subtitle'] );
		}

		if ( $tab_title ) {
			$tab_title = sprintf( '<div class="tabs-title">%s</div>', $tab_title );
		}

		return sprintf(
			'<div class="mf-category-tabs %s">' .
			'%s' .
			'<div class="martfury-tabs">' .
			'<div class="tabs-header">' .
			'<ul class="tabs-nav">' .
			'%s' .
			'</ul>' .
			'</div>' .
			'<div class="tabs-content">' .
			'%s' .
			'</div>' .
			'</div>' .
			'</div>',
			implode( '', $css_class ),
			$tab_title,
			implode( ' ', $tab_header ),
			implode( ' ', $tab_content )
		);
	}

	/**
	 * Category Tab
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function category_tab( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'icon_type'        => 'fontawesome',
				'icon_fontawesome' => 'fa fa-adjust',
				'icon_linearicons' => '',
				'title'            => '',
				'tags'             => '',
				'image'            => '',
				'image_size'       => 'thumbnail',
			), $atts
		);

		$this->cat_tabs[] = array(
			'icon_type'        => $atts['icon_type'],
			'icon_fontawesome' => $atts['icon_fontawesome'],
			'icon_linearicons' => $atts['icon_linearicons'],
			'title'            => $atts['title'],
			'tags'             => $atts['tags'],
			'image_size'       => $atts['image_size'],
			'image'            => $atts['image'],
		);

		return '';

	}

	/**
	 * Button
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function button( $atts ) {
		$atts = shortcode_atts(
			array(
				'link'     => '',
				'size'     => 'large',
				'align'    => 'left',
				'color'    => 'dark',
				'el_class' => '',
			), $atts
		);

		return $this->martfury_addons_btn( $atts );
	}

	/**
	 * Shortcode to display counter
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function counter( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'icon_type'        => 'fontawesome',
				'icon_fontawesome' => 'fa fa-adjust',
				'icon_linearicons' => '',
				'image'            => '',
				'value'            => '',
				'unit_before'      => '',
				'unit_after'       => '',
				'title'            => '',
				'el_class'         => '',
			), $atts
		);

		$css_class = array(
			'martfury-counter',
			$atts['el_class'],
		);
		$icon      = $output = $unit_before = $unit_after = '';

		if ( 'image' == $atts['icon_type'] ) {
			if ( function_exists( 'martfury_get_image_html' ) ) {
				$image = martfury_get_image_html( $atts['image'], 'full', 'img-icon' );
			} else {
				$image = wp_get_attachment_image( $atts['image'], 'full', '', array( 'class' => 'img-icon' ) );
			}
			$icon = $image ? sprintf( '%s', $image ) : '';
		} else {
			vc_icon_element_fonts_enqueue( $atts['icon_type'] );
			$icon = '<span class="mf-icon"><i class="' . esc_attr( $atts[ 'icon_' . $atts['icon_type'] ] ) . '"></i></span>';
		}

		if ( $atts['unit_before'] ) {
			$unit_before = sprintf( '<span class="unit unit-before">%s</span>', $atts['unit_before'] );
		}

		if ( $atts['unit_after'] ) {
			$unit_after = sprintf( '<span class="unit unit-after">%s</span>', $atts['unit_after'] );
		}

		if ( $atts['value'] ) {
			$output = sprintf( '<div class="counter">%s<span class="counter-value">%s</span>%s</div>', $unit_before, $atts['value'], $unit_after );
		}

		if ( $atts['title'] ) {
			$output .= sprintf( '<h4 class="title">%s</h4>', $atts['title'] );
		}

		return sprintf(
			'<div class="%s">%s%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$icon,
			$output
		);
	}

	/**
	 * Shortcode to display latest post
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function post_grid( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'       => esc_html__( 'News', 'martfury' ),
				'number'      => '3',
				'links_group' => '',
				'columns'     => '3',
				'el_class'    => '',
			), $atts
		);

		$css_class = array(
			'martfury-latest-post blog-layout-grid',
			$atts['el_class'],
		);

		$title = '';

		if ( $atts['title'] ) {
			$title = sprintf( '<h2 class="section-title">%s</h2>', $atts['title'] );
		}

		$output = array();

		$query_args = array(
			'posts_per_page'      => $atts['number'],
			'post_type'           => 'post',
			'ignore_sticky_posts' => true,
		);

		$query = new WP_Query( $query_args );

		global $mf_post;
		$mf_post['css'] = 'col-md-4 col-sm-6 col-xs-6 post-item-grid';

		if ( $atts['columns'] == '4' ) {
			$mf_post['css'] = 'col-md-3 col-sm-6 col-xs-6 post-item-grid';
		}

		while ( $query->have_posts() ) : $query->the_post();
			ob_start();
			get_template_part( 'template-parts/content', get_post_format() );
			$output[] = ob_get_clean();

		endwhile;
		wp_reset_postdata();

		$title .= $this->get_links_group( $atts['links_group'] );

		return sprintf(
			'<div class="%s">
				<div class="post-header">%s</div>
                <div class="post-list row">%s</div>
            </div>',
			esc_attr( implode( ' ', $css_class ) ),
			$title,
			implode( '', $output )
		);
	}

	/**
	 * Shortcode to display image box
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function image_box( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'style'       => '1',
				'box_height'  => '',
				'image'       => '',
				'image_size'  => 'thumbnail',
				'no_border'   => false,
				'title'       => '',
				'title_style' => '1',
				'link'        => '',
				'links_group' => '',
				'el_class'    => '',
			), $atts
		);

		$css_class = array(
			'mf-image-box',
			'style-' . $atts['style'],
			'title-s' . $atts['title_style'],
			$atts['no_border'] ? 'no-border' : '',
			$atts['el_class'],
		);

		$output = $style = '';

		if ( $atts['box_height'] ) {
			$style = 'min-height:' . intval( $atts['box_height'] ) . 'px;';
		}

		$image      = $this->get_Image_By_Size(
			array(
				'attach_id'  => $atts['image'],
				'thumb_size' => $atts['image_size'],
			)
		);
		$link_atts  = array(
			'link'    => $atts['link'],
			'content' => $image,
			'class'   => 'thumbnail',
		);
		$image_html = $this->get_vc_link( $link_atts );

		if ( $atts['title'] ) {
			$link_atts = array(
				'link'    => $atts['link'],
				'content' => $atts['title'],
				'class'   => '',
			);
			$output    = sprintf(
				'<h2 class="box-title">%s</h2>',
				$this->get_vc_link( $link_atts )
			);
		}
		$output .= $this->get_links_group( $atts['links_group'] );

		return sprintf(
			'<div class="%s" style="%s">' .
			'%s' .
			'<div class="image-content">' .
			'%s' .
			'</div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $style ),
			$image_html,
			$output
		);
	}

	/**
	 * Shortcode to display image box
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function single_image( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'image'       => '',
				'image_size'  => '',
				'link'        => '',
				'image_align' => 'left',
				'el_class'    => '',
			), $atts
		);

		$css_class = array(
			'mf-single-image',
			'align-' . $atts['image_align'],
			$atts['el_class'],
		);

		$image = $this->get_Image_By_Size(
			array(
				'attach_id'  => $atts['image'],
				'thumb_size' => $atts['image_size'],
			)
		);

		$link_atts = array(
			'link'    => $atts['link'],
			'content' => $image,
			'class'   => 'thumbnail',
		);

		$output = $this->get_vc_link( $link_atts );

		return sprintf(
			'<div class="%s">' .
			'%s' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$output
		);
	}

	/**
	 * Shortcode to display banners grid
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function banners_grid( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'alias'    => '',
				'banners'  => '',
				'el_class' => '',
			), $atts
		);

		$css_class = array(
			$atts['el_class'],
		);

		$output_right = array();
		$output_left  = array();
		if ( ! empty( $atts['alias'] ) ) {
			$output_left[] = sprintf( '<div class="banner-item item-large">%s</div>', do_shortcode( '[rev_slider_vc alias="' . $atts['alias'] . '"]' ) );
		}

		$banners = function_exists( 'vc_param_group_parse_atts' ) ? vc_param_group_parse_atts( $atts['banners'] ) : array();

		if ( ! empty( $banners ) ) {
			$i = 0;
			foreach ( $banners as $banner ) {
				if ( empty( $banner ) || ! isset( $banner['image'] ) ) {
					continue;
				}
				$item_class = 'item-small';
				if ( $i % 6 == 0 ) {
					$item_class = 'item-large';
				} elseif ( $i % 6 == 1 || $i % 6 == 2 || $i % 6 == 4 || $i % 6 == 5 ) {
					$item_class = 'item-small';
				} elseif ( $i % 6 == 3 ) {
					$item_class = 'item-normal';
				}
				$image     = $this->get_Image_By_Size(
					array(
						'attach_id'  => $banner['image'],
						'thumb_size' => $banner['image_size'],
					)
				);
				$link_atts = array(
					'link'    => $banner['link'],
					'content' => $image,
					'class'   => 'link',
				);

				$item = sprintf( '<div class="banner-item %s">%s</div>', esc_attr( $item_class ), $this->get_vc_link( $link_atts ) );
				if ( $i % 6 == 0 ) {
					$output_left[] = $item;
				} else {
					$output_right[] = $item;
				}
				$i ++;
			}
		}

		return sprintf(
			'<div class="mf-banners-grid %s">' .
			'<div class="l-banners">' .
			'%s' .
			'</div>' .
			'<div class="r-banners">' .
			'%s' .
			'</div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( ' ', $output_left ),
			implode( ' ', $output_right )
		);
	}

	/**
	 * Shortcode to display banners grid
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function banners_grid_2( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'alias'    => '',
				'banners'  => '',
				'el_class' => '',
			), $atts
		);

		$css_class = array(
			$atts['el_class'],
		);

		$output_right  = array();
		$output_left   = array();
		$output_left[] = sprintf( '<div class="banner-item item-large">%s</div>', do_shortcode( '[rev_slider_vc alias="' . $atts['alias'] . '"]' ) );

		$banners = function_exists( 'vc_param_group_parse_atts' ) ? vc_param_group_parse_atts( $atts['banners'] ) : array();
		$count   = count( $banners );
		if ( ! empty( $banners ) ) {
			$i    = 0;
			$line = 3;
			foreach ( $banners as $banner ) {
				$item_class = 'item-small';
				if ( $i % $line == 0 ) {
					$item_class = 'item-medium';
				}

				if ( $line > 1 && $i % $line == 0 ) :
					$output_right[] = '<div class="r-banners">';
				endif;

				$image     = $this->get_Image_By_Size(
					array(
						'attach_id'  => $banner['image'],
						'thumb_size' => $banner['image_size'],
					)
				);
				$link_atts = array(
					'link'    => $banner['link'],
					'content' => $image,
					'class'   => 'link',
				);

				$item = sprintf( '<div class="banner-item %s">%s</div>', esc_attr( $item_class ), $this->get_vc_link( $link_atts ) );

				$output_right[] = $item;

				if ( $line > 1 && $i % $line == $line - 1 ) {
					$output_right[] = '</div>';
				} elseif ( $line > 1 && $i == $count - 1 ) {
					$output_right[] = '</div>';
				}
				$i ++;
			}
		}

		return sprintf(
			'<div class="mf-banners-grid-2 %s">' .
			'<div class="l-banners">' .
			'%s' .
			'</div>' .
			'%s' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( ' ', $output_left ),
			implode( ' ', $output_right )
		);
	}

	/**
	 * Shortcode to display testimonials
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function testimonial_slides( $atts, $content ) {

		$atts = shortcode_atts(
			array(
				'title'          => '',
				'nav'            => false,
				'nav_style'      => '1',
				'dot'            => false,
				'autoplay'       => false,
				'autoplay_speed' => '1200',
				'image_size'     => '',
				'setting'        => '',
				'el_class'       => '',
			), $atts
		);

		$css_class = array(
			'martfury-testimonial-slides',
			$atts['el_class'],
			'nav-' . $atts['nav_style'],
		);

		$autoplay_speed = intval( $atts['autoplay_speed'] );

		if ( $atts['autoplay'] ) {
			$autoplay = true;
		} else {
			$autoplay = false;
		}

		if ( $atts['nav'] ) {
			$nav = true;
		} else {
			$nav = false;
		}

		if ( $atts['dot'] ) {
			$dot = true;
		} else {
			$dot = false;
		}

		$id                               = uniqid( 'testimonial-slider-' );
		$this->l10n['testimonial'][ $id ] = array(
			'nav'            => $nav,
			'dot'            => $dot,
			'autoplay'       => $autoplay,
			'autoplay_speed' => $autoplay_speed,
		);

		$item = '';
		$size = 'full';

		if ( $atts['image_size'] ) {
			$size = $atts['image_size'];
		}

		$info = function_exists( 'vc_param_group_parse_atts' ) ? vc_param_group_parse_atts( $atts['setting'] ) : array();

		$output = array();

		if ( ! empty( $info ) ) {
			foreach ( $info as $key => $value ) {
				$title = $desc = $css = $address = $button = '';
				$icon  = '<i class="icon-play-circle"></i>';

				if ( isset( $value['image'] ) && $value['image'] ) {
					$item = $this->get_Image_By_Size(
						array(
							'attach_id'  => $value['image'],
							'thumb_size' => $size,
						)
					);

				} else {
					$css .= ' no-thumbnail';
				}

				if ( isset( $value['button_link'] ) && $value['button_link'] ) {
					$attributes = array();

					$link = vc_build_link( $value['button_link'] );

					if ( ! empty( $link['url'] ) ) {
						$attributes['href'] = $link['url'];
					}

					$label = $link['title'];

					if ( ! $label ) {
						$attributes['title'] = $label;
					}

					if ( ! empty( $link['target'] ) ) {
						$attributes['target'] = $link['target'];
					}

					if ( ! empty( $link['rel'] ) ) {
						$attributes['rel'] = $link['rel'];
					}

					$attr = array();

					foreach ( $attributes as $name => $v ) {
						$attr[] = $name . '="' . esc_attr( $v ) . '"';
					}

					$button = sprintf(
						'<%1$s %2$s>%3$s%4$s</%1$s>',
						empty( $attributes['href'] ) ? 'span' : 'a',
						implode( ' ', $attr ),
						$label,
						$icon
					);
				}

				if ( isset( $value['name'] ) && $value['name'] ) {
					$title = sprintf( '<span class="name">%s</span>', $value['name'] );
				}

				if ( isset( $value['job'] ) && $value['job'] ) {
					$address = sprintf( '<span class="job">%s</span>', $value['job'] );
				}

				if ( isset( $value['desc'] ) && $value['desc'] ) {
					$desc = sprintf( '<div class="desc">%s</div>', $value['desc'] );
				}

				$output[] = sprintf(
					'<div class="testimonial-info %s">
						<i class="icon_quotations quote"></i>
						<div class="testi-thumb">%s</div>
						<div class="testi-header">%s%s</div>
						%s%s
					</div>',
					esc_attr( $css ),
					$item,
					$title,
					$address,
					$desc,
					$button
				);
			}
		}

		return sprintf(
			'<div class="%s">
				<h2 class="tes-title">%s</h2>
				<div class="testimonial-list" id="%s">%s</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$atts['title'],
			esc_attr( $id ),
			implode( '', $output )
		);
	}

	/**
	 * Get member
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function journey( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'min_height' => '230',
				'journey'    => '',
				'el_class'   => '',
			), $atts
		);

		$css_class = array(
			'martfury-journey',
			$atts['el_class'],
		);

		$output      = array();
		$output_year = array();
		$style       = '';

		if ( $atts['min_height'] ) {
			$style = 'min-height: ' . intval( $atts['min_height'] ) . 'px;';
		}

		$setting = function_exists( 'vc_param_group_parse_atts' ) ? vc_param_group_parse_atts( $atts['journey'] ) : array();

		$number = count( $setting );

		$i = 1;

		if ( $setting ) {
			foreach ( $setting as $s ) {
				$year = $title = $desc = $item = $css = '';

				if ( isset( $s['year'] ) && $s['year'] ) {
					$year = sprintf( '<li><a href="#" data-tab="martfury-journey-tab-%s" data-number="%s">%s<span></span></a></li>', esc_attr( $i ), esc_attr( $i ), $s['year'] );
				}

				if ( isset( $s['title'] ) && $s['title'] ) {
					$title = sprintf( '<div class="journey-title">%s</div>', $s['title'] );
				}

				if ( isset( $s['desc'] ) && $s['desc'] ) {
					$desc = sprintf( '<div class="journey-desc">%s</div>', $s['desc'] );
				}

				if ( isset( $s['image'] ) && $s['image'] ) {
					$item .= $this->get_Image_By_Size(
						array(
							'attach_id'  => $s['image'],
							'thumb_size' => $s['image_size'],
						)
					);
				}

				if ( isset( $s['reverse'] ) && $s['reverse'] == '1' ) {
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
					$item,
					$title,
					$desc
				);

				$output_year[] = sprintf( '%s', $year );

				$i ++;
			}
		}

		return sprintf(
			'<div class="%s" data-number="%s">' .
			'<ul>%s</ul>' .
			'<div class="journey-content" style="%s">' .
			'%s' .
			'</div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $number ),
			implode( '', $output_year ),
			$style,
			implode( '', $output )
		);

	}

	/**
	 * Get member
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function member( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'image'    => '',
				'name'     => '',
				'job'      => '',
				'socials'  => '',
				'el_class' => '',
			), $atts
		);

		$css_class[] = $atts['el_class'];

		$output = array();

		if ( $atts['name'] ) {
			$output[] = sprintf( '<h4 class="name">%s</h4>', $atts['name'] );
		}

		if ( $atts['job'] ) {
			$output[] = sprintf( '<span class="job">%s</span>', $atts['job'] );
		}

		$socials = (array) json_decode( urldecode( $atts['socials'] ), true );

		$socials_html = '';
		if ( $socials ) {
			foreach ( $socials as $social ) {

				$text_html = '';
				if ( isset( $social['icon_fontawesome'] ) && $social['icon_fontawesome'] ) {
					$text_html = sprintf( '<i class="%s"></i>', esc_attr( $social['icon_fontawesome'] ) );
				}

				$link_html = '';
				if ( isset( $social['link'] ) && $social['link'] ) {
					if ( function_exists( 'vc_build_link' ) ) {
						$link = array_filter( vc_build_link( $social['link'] ) );
						if ( ! empty( $link ) ) {
							$link_html = sprintf(
								'<a href="%s" class="link" %s%s>%s</a>',
								esc_url( $link['url'] ),
								! empty( $link['target'] ) ? ' target="' . esc_attr( $link['target'] ) . '"' : '',
								! empty( $link['rel'] ) ? ' rel="' . esc_attr( $link['rel'] ) . '"' : '',
								$text_html
							);
						}
					}
				}

				if ( empty ( $link_html ) ) {
					$link_html = sprintf(
						'<span class="text">%s</span>',
						esc_html( $text_html )
					);
				}

				$socials_html .= $link_html;
			}

		}

		if ( $socials_html ) {
			$output[] = sprintf( '<div class="socials">%s</div>', $socials_html );
		}

		$image_html = '';
		if ( $atts['image'] ) {
			if ( function_exists( 'martfury_get_image_html' ) ) {
				$image_html = martfury_get_image_html( $atts['image'], 'full' );
			} else {
				$image_html = wp_get_attachment_image( $atts['image'], 'full' );
			}
		}


		return sprintf(
			'<div class="martfury-member %s">' .
			'%s' .
			'<div class="member-content">' .
			'%s' .
			'</div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$image_html,
			implode( ' ', $output )

		);

	}

	/**
	 * Process
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function process( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'process'  => '',
				'el_class' => '',
			), $atts
		);

		$css_class = array(
			'martfury-process',
			$atts['el_class'],
		);

		$output = array();

		$process = function_exists( 'vc_param_group_parse_atts' ) ? vc_param_group_parse_atts( $atts['process'] ) : array();
		$i       = 1;
		if ( $process ) {
			foreach ( $process as $k => $v ) {
				$image = $title = $desc = '';
				if ( isset( $v['image'] ) && $v['image'] ) {
					if ( function_exists( 'martfury_get_image_html' ) ) {
						$image = martfury_get_image_html( $v['image'], 'full' );
					} else {
						$image = wp_get_attachment_image( $v['image'], 'full' );
					}
				}

				if ( isset( $v['title'] ) && $v['title'] ) {
					$title = sprintf( '<h3>%s</h3>', $v['title'] );
				}

				if ( isset( $v['desc'] ) && $v['desc'] ) {
					$desc = sprintf( '<div class="desc">%s</div>', $v['desc'] );
				}

				$step = sprintf( '<div class="step">%s</div>', $i );

				$output[] = sprintf(
					'<div class="row process-content">
							<div class="col-md-5 process-image">%s</div>
							<div class="col-md-2 process-step">%s</div>
							<div class="col-md-5 process-desc">%s%s</div>
						</div>',
					$image,
					$step,
					$title,
					$desc
				);

				$i ++;
			}
		}

		return sprintf(
			'<div class="%s">' .
			'<div class="list-process">' .
			'%s' .
			'</div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( ' ', $output )
		);

	}

	/**
	 * Button
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	function bubbles( $atts ) {
		$atts = shortcode_atts(
			array(
				'value'    => '',
				'title'    => '',
				'el_class' => '',
			), $atts
		);

		$css_class = array(
			'martfury-bubbles',
			$atts['el_class'],
		);

		$value = $title = '';

		if ( $atts['value'] ) {
			$value = sprintf( '<div class="value">%s</div>', $atts['value'] );
		}

		if ( $atts['title'] ) {
			$title = sprintf( '<h5>%s</h5>', $atts['title'] );
		}

		return sprintf( '<div class="%s"><div class="bubble">%s%s</div></div>', esc_attr( implode( ' ', $css_class ) ), $value, $title );
	}

	/**
	 * Shortcode to display newsletter
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function newsletter( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'bg_color'       => '',
				'border'         => '',
				'form'           => '',
				'title'          => '',
				'desc'           => '',
				'image'          => '',
				'image_size'     => '',
				'image_position' => 'left',
				'btn_setting'    => '',
				'el_class'       => '',
			), $atts
		);

		$css_class = array(
			'martfury-newletter',
			$atts['el_class'],
		);

		if ( ! $atts['form'] ) {
			return '';
		}

		$style = $title = $desc = $image_html = '';
		$size  = 'full';

		if ( $atts['bg_color'] ) {
			$style = 'background-color:' . $atts['bg_color'] . ';';
		}

		if ( $atts['border'] == '1' ) {
			$css_class[] = 'show-border';
		}

		if ( $atts['image_size'] ) {
			$size = $atts['image_size'];
		}

		$image = $this->get_Image_By_Size(
			array(
				'attach_id'  => $atts['image'],
				'thumb_size' => $size,
			)
		);

		if ( $image ) {
			$image_html = sprintf( '<div class="form-image col-md-6 hidden-sm hidden-xs text-%s">%s</div>', esc_attr( $atts['image_position'] ), $image );
		} else {
			$css_class[] = 'no-image';
		}

		if ( $atts['title'] ) {
			$title = sprintf( '<h3 class="form-title">%s</h3>', $atts['title'] );
		}

		if ( $content ) {
			$desc = sprintf( '<div class="form-desc">%s</div>', $content );
		}

		$setting = function_exists( 'vc_param_group_parse_atts' ) ? vc_param_group_parse_atts( $atts['btn_setting'] ) : array();

		$output = array();

		if ( ! empty( $setting ) ) {
			foreach ( $setting as $key => $value ) {
				$btn_image = $button = '';

				if ( isset( $value['btn_image'] ) && $value['btn_image'] ) {
					$btn_image = martfury_get_image_html( $value['btn_image'], 'full' );
				}

				if ( isset( $value['button_link'] ) && $value['button_link'] ) {
					$link_atts = array(
						'link'    => $value['button_link'],
						'content' => $btn_image,
						'class'   => 'link',
					);

					$button = $this->get_vc_link( $link_atts );
				} else {
					$button = $btn_image;
				}

				$output[] = sprintf( '%s', $button );
			}
		}

		$btn_html = sprintf( '<div class="btn-area">%s</div>', implode( '', $output ) );

		return sprintf(
			'<div class="%s" style="%s">
                <div class="container">
                    <div class="row newsletter-row">
                        %s
                        <div class="form-area col-md-6 col-xs-12 col-sm-12">%s%s%s%s</div>
                    </div>
				</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $style ),
			$image_html,
			$title,
			$desc,
			do_shortcode( '[mc4wp_form id="' . esc_attr( $atts['form'] ) . '"]' ),
			$btn_html
		);
	}

	/**
	 * Shortcode to display partner
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function partner( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'columns'             => '5',
				'type'                => 'normal',
				'autoplay'            => '',
				'images'              => '',
				'image_size'          => 'thumbnail',
				'custom_links'        => '',
				'custom_links_target' => '_self',
				'el_class'            => '',
			), $atts
		);

		$css_class = array(
			'martfury-partner clearfix',
			$atts['type'] . '-type',
			$atts['el_class'],
		);

		$columns = intval( $atts['columns'] );
		$auto    = intval( $atts['autoplay'] );

		$class = $owl_class = '';

		if ( $atts['type'] == 'normal' ) {
			if ( $columns == 5 ) {
				$class = 'col-mf-5 col-sm-4 col-xs-3';
			} else {
				$class = 'col-sm-4 col-xs-3 col-md-' . intval( 12 / $columns );
			}

			$owl_class = 'row';

		}

		$output       = array();
		$custom_links = '';

		if ( function_exists( 'vc_value_from_safe' ) ) {
			$custom_links = vc_value_from_safe( $atts['custom_links'] );
			$custom_links = explode( ',', $custom_links );
		}

		$images = $atts['images'] ? explode( ',', $atts['images'] ) : '';

		if ( $images ) {
			$i = 0;
			foreach ( $images as $attachment_id ) {
				$image = $this->get_Image_By_Size(
					array(
						'attach_id'  => $attachment_id,
						'thumb_size' => $atts['image_size'],
					)
				);
				if ( $image ) {
					$link = '';
					if ( $custom_links && isset( $custom_links[ $i ] ) ) {
						$link = preg_replace( '/<br \/>/iU', '', $custom_links[ $i ] );

						if ( $link ) {
							$link = 'href="' . esc_url( $link ) . '"';
						}
					}

					$output[] = sprintf(
						'<div class="partner-item %s">
							<a %s target="%s" >%s</a>
						</div>',
						esc_attr( $class ),
						$link,
						esc_attr( $atts['custom_links_target'] ),
						$image
					);
				}
				$i ++;
			}
		}

		return sprintf(
			'<div class="%s" data-columns="%s" data-auto="%s">
				<div class="list-item %s">%s</div>
			</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $columns ),
			esc_attr( $auto ),
			esc_attr( $owl_class ),
			implode( '', $output )
		);
	}

	/**
	 * Get FAQs
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function faqs( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'    => '',
				'faqs'     => '',
				'el_class' => '',
			), $atts
		);

		if ( ! $atts['faqs'] ) {
			return '';
		}

		$css_class = array(
			'martfury-faq_group',
			$atts['el_class'],
		);

		$faqs = (array) json_decode( urldecode( $atts['faqs'] ), true );

		$output = array();

		$output[] = sprintf( '<div class="col-gtitle col-md-3 col-sm-3 col-xs-12"><h2 class="g-title">%s</h2></div>', $atts['title'] );

		$output[] = '<div class="col-md-9 col-sm-9 col-xs-12">';
		$output[] = '<div class="row">';
		foreach ( $faqs as $faq ) {
			$output[] = '<div class="faq-item">';

			if ( isset( $faq['title'] ) && $faq['title'] ) {
				$output[] = sprintf( '<div class="col-title col-md-5 col-sm-5 col-xs-12"> <h3 class="title">%s</h3></div>', $faq['title'] );
			}

			if ( isset( $faq['desc'] ) && $faq['desc'] ) {
				$output[] = sprintf( '<div class="col-md-7 col-sm-7 col-xs-12"><div class="desc">%s</div></div>', $faq['desc'] );
			}

			$output[] = '</div>';
		}

		$output[] = '</div>';
		$output[] = '</div>';

		return sprintf(
			'<div class="%s">' .
			'<div class="row">' .
			'%s' .
			'</div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( ' ', $output )
		);
	}

	/**
	 * Get Product Brands
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function images_grid( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'    => '',
				'images'   => '',
				'el_class' => '',
			), $atts
		);

		$css_class = array(
			'martfury-images-grid',
			$atts['el_class'],
		);

		$output = array();
		if ( ! empty( $atts['title'] ) ) {
			$output[] = sprintf(
				'<h2>%s</h2>',
				$atts['title']
			);
		}

		$images = function_exists( 'vc_param_group_parse_atts' ) ? vc_param_group_parse_atts( $atts['images'] ) : array();
		$count  = count( $images );

		if ( $images ) {
			$output[] = '<div class="images-list">';
			$i        = 1;
			foreach ( $images as $image ) {
				if ( $i % 5 == 1 ) {
					$output[] = '<div class="image-row">';
				}

				$image_html = martfury_get_image_html( $image['image'], 'thumbnail' );
				$link_atts  = array(
					'link'    => isset( $image['link'] ) && $image['link'] ? $image['link'] : '',
					'content' => $image_html,
					'class'   => 'img-link',
				);
				$output[]   = sprintf( '<div class="image-item">%s</div>', $this->get_vc_link( $link_atts ) );

				if ( $i % 5 == 0 || $i == $count ) {
					$output[] = '</div>';
				}

				$i ++;
			}
			$output[] = '</div>';
		}

		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);
	}

	/**
	 * Products shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function products_of_category( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'        => '',
				'cat'          => '',
				'tags'         => '',
				'links_group'  => '',
				'link'         => '',
				'all_link'     => '',
				'images'       => '',
				'custom_links' => '',
				'autoplay'     => false,
				'pagination'   => true,
				'products'     => 'recent',
				'per_page'     => 6,
				'orderby'      => '',
				'order'        => '',
				'columns'      => '3',
				'infinite'     => '',
				'parent_class' => true,
				'el_class'     => '',
			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		$css_class = array();

		$css_class[] = $atts['el_class'];
		$output      = array();
		$autoplay    = false;
		$speed       = 1000;
		if ( intval( $atts['autoplay'] ) ) {
			$autoplay = true;
			$speed    = intval( $atts['autoplay'] );
		}

		$id                                      = uniqid( 'mf-products-of-cat' );
		$this->l10n['productsOfCategory'][ $id ] = array(
			'autoplay'       => $autoplay,
			'autoplay_speed' => $speed,
			'pagination'     => $atts['pagination'],
		);

		$el_infinite = apply_filters( 'martfury_elements_infinite_scrolling', $atts['infinite'] );
		if ( $el_infinite ) {
			$params = array(
				'title'        => $atts['title'],
				'cat'          => $atts['cat'],
				'tags'         => $atts['tags'],
				'links_group'  => $atts['links_group'],
				'link'         => $atts['link'],
				'all_link'     => $atts['all_link'],
				'images'       => $atts['images'],
				'custom_links' => $atts['custom_links'],
				'autoplay'     => $atts['autoplay'],
				'pagination'   => $atts['pagination'],
				'per_page'     => intval( $atts['per_page'] ),
				'products'     => $atts['products'],
				'orderby'      => $atts['orderby'],
				'order'        => $atts['order'],
				'columns'      => $atts['columns'],
				'infinite'     => '',
				'parent_class' => '',
			);

			$this->l10n['productsOfCategory'][ $id ]['params'] = $params;

			$output[] = '<div class="mf-vc-loading"><div class="mf-vc-loading--wrapper"></div></div>';


		} else {
			$cats_html = array();
			$images    = array();
			$view_all  = '';
			if ( ! empty( $atts['title'] ) ) {
				$link_atts   = array(
					'link'    => $atts['link'],
					'content' => $atts['title'],
					'class'   => 'cat-title',
				);
				$cats_html[] = sprintf( '<h2>%s</h2>', $this->get_vc_link( $link_atts ) );
			}

			if ( empty( $atts['links_group'] ) ) {
				$css_class[] = 'no-links-group';
			} else {
				$links_group = function_exists( 'vc_param_group_parse_atts' ) ? vc_param_group_parse_atts( $atts['links_group'] ) : array();
				if ( empty( $links_group ) ) {
					$css_class[] = 'no-links-group';
				} else {
					if ( empty( $links_group[0] ) ) {
						$css_class[] = 'no-links-group';
					}
				}
			}

			$cats_html[] = $this->get_links_group( $atts['links_group'] );

			$link_html = '';
			if ( ! empty( $atts['all_link'] ) ) {
				$link_atts = array(
					'link'    => $atts['all_link'],
					'content' => '',
					'class'   => 'link',
				);
				$link_html = sprintf(
					'<div class="footer-link">%s</div>',
					$this->get_vc_link( $link_atts )
				);
			}

			$output[] = sprintf( '<div class="cats-info"><div class="cats-inner">%s</div>%s</div>', implode( ' ', $cats_html ), $link_html );

			$custom_links = '';
			if ( function_exists( 'vc_value_from_safe' ) ) {
				$custom_links = vc_value_from_safe( $atts['custom_links'] );
				$custom_links = explode( ',', $custom_links );
			}

			$images_id = $atts['images'] ? explode( ',', $atts['images'] ) : '';

			if ( $images_id ) {
				$i = 0;
				foreach ( $images_id as $attachment_id ) {
					$image = '';
					if ( function_exists( 'martfury_get_image_html' ) ) {
						$image = martfury_get_image_html( $attachment_id, 'full' );
					} else {
						$image = wp_get_attachment_image( $attachment_id, 'full' );
					}
					if ( $image ) {
						$href = '';
						if ( $custom_links && isset( $custom_links[ $i ] ) ) {
							$link = preg_replace( '/<br \/>/iU', '', $custom_links[ $i ] );
							if ( $link ) {
								$href = 'href="' . esc_url( $link ) . '"';
							}
						}

						$images[] = sprintf(
							'<a class="image-item" %s >%s</a>',
							$href,
							$image
						);
					}
					$i ++;
				}

				if ( $images ) {
					$output[] = sprintf( '<div class="images-slider"><div class="images-list">%s</div></div>', implode( ' ', $images ) );
				}
			}

			$products_html = $this->get_wc_products( $atts );

			$output[] = sprintf( '<div class="products-box" >%s</div>', $products_html );

			$css_class[] = 'no-infinite';
		}

		if ( $atts['parent_class'] ) {
			return sprintf(
				'<div class="mf-products-of-category woocommerce %s" id="%s">%s</div>',
				esc_attr( implode( ' ', $css_class ) ),
				esc_attr( $id ),
				implode( '', $output )
			);
		} else {
			return implode( '', $output );
		}

	}

	/**
	 * Products shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function products_of_category_2( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'                 => '',
				'link'                  => '',
				'all_link'              => '',
				'icon_type'             => '',
				'icon_fontawesome'      => '',
				'icon_linearicons'      => '',
				'links_group'           => '',
				'images'                => '',
				'custom_links'          => '',
				'autoplay'              => false,
				'navigation'            => true,
				'cat'                   => '',
				'tabs'                  => '',
				'hide_product_tabs'     => '',
				'pro_autoplay'          => false,
				'per_page'              => 12,
				'side_title'            => '',
				'ids'                   => '',
				'image_size'            => 'thumbnail',
				'side_link'             => '',
				'columns'               => '4',
				'infinite'              => '',
				'parent_class'          => true,
				'el_class'              => '',
				'product_tabs_lazyload' => true
			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		$css_class[] = $atts['el_class'];
		if ( $atts['hide_product_tabs'] ) {
			$css_class[] = 'hide-product-tabs';
		}
		$output       = array();
		$pro_autoplay = false;
		$pro_infinite = false;
		$speed        = 1000;
		if ( intval( $atts['pro_autoplay'] ) ) {
			$pro_autoplay = true;
			$pro_infinite = true;
			$speed        = intval( $atts['pro_autoplay'] );
		}

		$banner_autoplay = false;
		$banner_speed    = 1000;
		$banner_infinite = false;

		if ( intval( $atts['autoplay'] ) ) {
			$banner_autoplay = true;
			$banner_infinite = true;
			$banner_speed    = intval( $atts['autoplay'] );
		}

		$id                                       = uniqid( 'mf-products-of-cat-2-' );
		$this->l10n['productsOfCategory2'][ $id ] = array(
			'pro_autoplay'       => $pro_autoplay,
			'pro_infinite'       => $pro_infinite,
			'pro_autoplay_speed' => $speed,
			'pro_columns'        => intval( $atts['columns'] ),
			'pro_navigation'     => true,
			'autoplay'           => $banner_autoplay,
			'autoplay_speed'     => $banner_speed,
			'pagination'         => false,
			'infinite'           => $banner_infinite,
			'navigation'         => $atts['navigation'],
		);
		$el_infinite                              = apply_filters( 'martfury_elements_infinite_scrolling', $atts['infinite'] );
		if ( $el_infinite ) {
			$params = array(
				'title'                 => $atts['title'],
				'link'                  => $atts['link'],
				'all_link'              => $atts['all_link'],
				'icon_type'             => $atts['icon_type'],
				'icon_fontawesome'      => $atts['icon_fontawesome'],
				'icon_linearicons'      => $atts['icon_linearicons'],
				'links_group'           => $atts['links_group'],
				'images'                => $atts['images'],
				'custom_links'          => $atts['custom_links'],
				'autoplay'              => $atts['autoplay'],
				'navigation'            => $atts['navigation'],
				'cat'                   => $atts['cat'],
				'tabs'                  => $atts['tabs'],
				'hide_product_tabs'     => $atts['hide_product_tabs'],
				'pro_autoplay'          => $atts['pro_autoplay'],
				'per_page'              => $atts['per_page'],
				'side_title'            => $atts['side_title'],
				'ids'                   => $atts['ids'],
				'side_link'             => $atts['side_link'],
				'columns'               => $atts['columns'],
				'infinite'              => '',
				'parent_class'          => '',
				'product_tabs_lazyload' => false
			);

			$this->l10n['productsOfCategory2'][ $id ]['params'] = $params;

			$output[] = '<div class="mf-vc-loading"><div class="mf-vc-loading--wrapper"></div></div>';


		} else {

			$cats_html = array();
			if ( ! empty( $atts['title'] ) ) {
				$icon      = $atts[ 'icon_' . $atts['icon_type'] ];
				$icon      = $icon ? '<i class="' . esc_attr( $icon ) . '"></i>' : '';
				$icon      .= $atts['title'];
				$link_atts = array(
					'link'    => $atts['link'],
					'content' => $icon,
					'class'   => 'cat-title',
				);

				$cats_html[] = sprintf( '<h2>%s</h2>', $this->get_vc_link( $link_atts ) );
			}

			$cats_html[] = $this->get_links_group( $atts['links_group'] );

			$output[] = sprintf( '<div class="cats-header">%s</div>', implode( ' ', $cats_html ) );
			$output[] = '<div class="products-cat row">';
			$output[] = '<div class="col-md-9 col-sm-12 col-xs-12 col-product-content">';
			$output[] = $this->get_banners( $atts );

			$params   = ' title=""';
			$params   .= ' per_page="' . $atts['per_page'] . '"';
			$params   .= ' columns="4"';
			$params   .= ' tabs="' . $atts['tabs'] . '"';
			$params   .= ' autoplay="' . $atts['pro_autoplay'] . '"';
			$params   .= ' cat="' . $atts['cat'] . '"';
			$params   .= ' lazyload="' . $atts['product_tabs_lazyload'] . '"';
			$output[] = do_shortcode( '[martfury_product_tabs ' . $params . ']' );
			$output[] = '</div>';

			$output[] = '<div class="col-md-3 col-sm-12 col-xs-12">';
			if ( ! empty( $atts['ids'] ) ) {
				$output[] = '<div class="products-side">';
				if ( $atts['side_title'] ) {
					$output[] = sprintf( '<h2 class="side-title">%s</h2>', $atts['side_title'] );
				}

				$output[] = $this->get_custom_products_html( $atts['ids'], $atts['image_size'] );

				$link_atts = array(
					'link'    => $atts['side_link'],
					'content' => '',
					'class'   => 'link',
				);

				$output[] = $this->get_vc_link( $link_atts );

				$output[] = '</div>';
			}

			$output[] = '</div>';
			$output[] = '</div>';

			$css_class[] = 'no-infinite';

		}


		if ( $atts['parent_class'] ) {
			return sprintf(
				'<div class="mf-products-of-category-2 woocommerce %s" id="%s">%s</div>',
				esc_attr( implode( ' ', $css_class ) ),
				esc_attr( $id ),
				implode( '', $output )
			);
		} else {
			return implode( '', $output );
		}


	}


	/**
	 * get shortcode ajax AJAX
	 *
	 *
	 * @return string
	 */
	function get_shortcode_ajax() {
		$output = '';

		if ( isset( $_POST['params'] ) && ! empty( $_POST['params'] ) ) {
			$params = $_POST['params'];
			$atts   = '';
			foreach ( $params as $key => $value ) {
				$atts .= ' ' . $key . '="' . $value . '"';
			}

			$els = '';
			if ( isset( $_POST['element'] ) && ! empty( $_POST['element'] ) ) {
				$els = $_POST['element'];
			}

			if ( $els == 'productsOfCat' ) {
				$output = do_shortcode( '[martfury_products_of_category ' . $atts . ']' );
			} elseif ( $els == 'productsOfCat2' ) {
				$output = do_shortcode( '[martfury_products_of_category_2 ' . $atts . ']' );
			} elseif ( $els == 'productTabs' ) {
				$output = do_shortcode( '[martfury_product_tabs ' . $atts . ']' );
			} elseif ( $els == 'categoryBox' ) {
				$output = do_shortcode( '[martfury_category_box ' . $atts . ']' );
			} elseif ( $els == 'productsCarousel' ) {
				$output = do_shortcode( '[martfury_products_carousel ' . $atts . ']' );
			} elseif ( $els == 'topSelling' ) {
				$output = do_shortcode( '[martfury_top_selling ' . $atts . ']' );
			}
		}

		wp_send_json_success( $output );
		die();
	}

	/**
	 * Products tabs carousel shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function product_tabs( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'        => '',
				'header'       => '1',
				'per_page'     => 12,
				'columns'      => 5,
				'link'         => '',
				'all_link'     => '',
				'el_class'     => '',
				'tabs'         => '',
				'autoplay'     => 0,
				'navigation'   => true,
				'cat'          => '',
				'infinite'     => '',
				'parent_class' => true,
				'lazyload'     => true

			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		$css_class[] = $atts['el_class'];
		$css_class[] = 'header-style-' . $atts['header'];

		$output = array();

		$header_tabs = array();
		$view_all    = '';
		if ( ! empty( $atts['title'] ) ) {
			$link_atts     = array(
				'link'    => $atts['link'],
				'content' => $atts['title'],
				'class'   => 'cat-title',
			);
			$header_tabs[] = sprintf( '<h2>%s</h2>', $this->get_vc_link( $link_atts ) );
		}

		$header_tabs[] = '<div class="tabs-header-nav">';


		$tabs        = function_exists( 'vc_param_group_parse_atts' ) ? vc_param_group_parse_atts( $atts['tabs'] ) : array();
		$tab_content = array();
		if ( $tabs ) {
			$header_tabs[] = '<ul class="tabs-nav">';
			foreach ( $tabs as $tab ) {
				if ( isset( $tab['title'] ) ) {
					$header_tabs[] = sprintf( '<li><a href="#" data-href="%s">%s</a></li>', esc_attr( $tab['products'] ), esc_html( $tab['title'] ) );
				}
			}
			$header_tabs[] = '</ul>';
		}

		if ( ! empty( $atts['all_link'] ) ) {
			$link_atts     = array(
				'link'    => $atts['all_link'],
				'content' => '',
				'class'   => 'link',
			);
			$header_tabs[] = $this->get_vc_link( $link_atts );
		}

		$header_tabs[] = '</div>';

		$autoplay = false;
		$speed    = 1000;
		$infinite = false;
		if ( intval( $atts['autoplay'] ) ) {
			$autoplay = true;
			$infinite = true;
			$speed    = intval( $atts['autoplay'] );
		}
		$id                                = uniqid( 'products-tabs' );
		$this->l10n['productsTabs'][ $id ] = array(
			'pro_autoplay'       => $autoplay,
			'pro_infinite'       => $infinite,
			'pro_autoplay_speed' => $speed,
			'pro_columns'        => intval( $atts['columns'] ),
			'pro_navigation'     => $atts['navigation'],
			'per_page'           => intval( $atts['per_page'] ),
			'pro_cats'           => $atts['cat'],
		);
		$el_infinite                       = apply_filters( 'martfury_elements_infinite_scrolling', $atts['infinite'] );
		if ( $el_infinite ) {
			$params = array(
				'tabs'         => $atts['tabs'],
				'cat'          => $atts['cat'],
				'per_page'     => intval( $atts['per_page'] ),
				'columns'      => $atts['columns'],
				'infinite'     => '',
				'parent_class' => '',
			);

			$this->l10n['productsTabs'][ $id ]['params'] = $params;

			$tab_content[] = '<div class="mf-vc-loading"><div class="mf-vc-loading--wrapper"></div></div>';

		} else {
			$tabs = function_exists( 'vc_param_group_parse_atts' ) ? vc_param_group_parse_atts( $atts['tabs'] ) : array();
			if ( $tabs ) {
				$i = 0;
				foreach ( $tabs as $tab ) {
					$tab_atts = array(
						'columns'  => intval( $atts['columns'] ),
						'products' => $tab['products'],
						'order'    => '',
						'orderby'  => '',
						'per_page' => intval( $atts['per_page'] ),
						'cat'      => $atts['cat'],
					);

					if ( $i == 0 ) {
						$tab_content[] = sprintf( '<div class="tabs-panel tabs-%s tab-loaded">%s</div>', esc_attr( $tab['products'] ), $this->get_wc_products( $tab_atts ) );
					} else {
						$tab_content[] = sprintf(
							'<div class="tabs-panel tabs-%s"><div class="mf-vc-loading"><div class="mf-vc-loading--wrapper"></div></div></div>',
							esc_attr( $tab['products'] )
						);
					}

					if ( intval( $atts['lazyload'] ) ) {
						$i ++;
					}

				}
			}

			$css_class[] = 'no-infinite';
		}
		if ( $atts['parent_class'] ) {
			$output[] = sprintf( '<div class="tabs-header">%s</div>', implode( ' ', $header_tabs ) );
			$output[] = sprintf( '<div class="tabs-content">%s</div>', implode( ' ', $tab_content ) );

			return sprintf(
				'<div class="mf-products-tabs martfury-tabs woocommerce %s" id="%s">%s</div>',
				esc_attr( implode( ' ', $css_class ) ),
				esc_attr( $id ),
				implode( '', $output )
			);
		} else {
			return implode( ' ', $tab_content );
		}
	}

	function wpbakery_load_products() {
		$atts = array(
			'columns'  => isset( $_POST['columns'] ) ? intval( $_POST['columns'] ) : '',
			'products' => isset( $_POST['products'] ) ? $_POST['products'] : '',
			'order'    => isset( $_POST['order'] ) ? $_POST['order'] : '',
			'orderby'  => isset( $_POST['orderby'] ) ? $_POST['orderby'] : '',
			'per_page' => isset( $_POST['per_page'] ) ? intval( $_POST['per_page'] ) : '',
			'cat'      => isset( $_POST['product_cats'] ) ? $_POST['product_cats'] : '',
		);

		$products = $this->get_wc_products( $atts );

		wp_send_json_success( $products );
	}

	/**
	 * Products carousel shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function products_carousel( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'header'       => '1',
				'title'        => '',
				'link'         => '',
				'links_group'  => '',
				'cat'          => '',
				'per_page'     => 12,
				'autoplay'     => 0,
				'columns'      => 5,
				'navigation'   => true,
				'products'     => 'recent',
				'orderby'      => '',
				'order'        => '',
				'el_class'     => '',
				'infinite'     => '',
				'parent_class' => true,

			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		$css_class[] = $atts['el_class'];
		$css_class[] = 'header-' . $atts['header'];

		$output = array();

		$autoplay = false;
		$speed    = 1000;
		$infinite = false;
		if ( intval( $atts['autoplay'] ) ) {
			$autoplay = true;
			$infinite = true;
			$speed    = intval( $atts['autoplay'] );
		}
		$id                                    = uniqid( 'products-carousel' );
		$this->l10n['productsCarousel'][ $id ] = array(
			'pro_autoplay'       => $autoplay,
			'pro_infinite'       => $infinite,
			'pro_autoplay_speed' => $speed,
			'pro_columns'        => intval( $atts['columns'] ),
			'pro_navigation'     => $atts['navigation'],
		);
		$el_infinite                           = apply_filters( 'martfury_elements_infinite_scrolling', $atts['infinite'] );
		if ( $el_infinite ) {
			$params = array(
				'title'        => $atts['title'],
				'link'         => $atts['link'],
				'links_group'  => $atts['links_group'],
				'cat'          => $atts['cat'],
				'per_page'     => intval( $atts['per_page'] ),
				'autoplay'     => $atts['autoplay'],
				'columns'      => $atts['columns'],
				'navigation'   => $atts['navigation'],
				'products'     => $atts['products'],
				'orderby'      => $atts['orderby'],
				'order'        => $atts['order'],
				'infinite'     => '',
				'parent_class' => '',
			);

			$this->l10n['productsCarousel'][ $id ]['params'] = $params;

			$output[] = '<div class="mf-vc-loading"><div class="mf-vc-loading--wrapper"></div></div>';

		} else {

			$header_tabs = array();
			if ( ! empty( $atts['title'] ) ) {

				if ( in_array( $atts['header'], array( '1', '2' ) ) ) {
					$link_atts     = array(
						'link'    => $atts['link'],
						'content' => $atts['title'],
						'class'   => '',
					);
					$header_tabs[] = sprintf( '<h2 class="cat-title">%s</h2>', $this->get_vc_link( $link_atts ) );
				} else {
					$header_tabs[] = sprintf( '<h2 class="cat-title">%s</h2>', $atts['title'] );
				}

			}

			if ( in_array( $atts['header'], array( '1', '4' ) ) ) {
				$header_tabs[] = $this->get_links_group( $atts['links_group'] );
			}

			$products_atts = array(
				'columns'  => intval( $atts['columns'] ),
				'products' => $atts['products'],
				'order'    => '',
				'orderby'  => '',
				'per_page' => intval( $atts['per_page'] ),
				'cat'      => $atts['cat'],
			);

			$css_class[] = 'no-infinite';
			$output[]    = sprintf(
				'<div class="cat-header">%s</div>' .
				'<div class="products-content">%s</div>',
				implode( ' ', $header_tabs ),
				$this->get_wc_products( $products_atts )
			);
		}


		if ( $atts['parent_class'] ) {
			return sprintf(
				'<div class="mf-products-carousel woocommerce %s" id="%s">%s</div>',
				esc_attr( implode( ' ', $css_class ) ),
				esc_attr( $id ),
				implode( '', $output )
			);
		} else {
			return implode( ' ', $output );
		}

	}

	/**
	 * Products carousel shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function products_list( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'header'      => '1',
				'title'       => '',
				'links_group' => '',
				'cat'         => '',
				'per_page'    => 6,
				'columns'     => 3,
				'products'    => 'recent',
				'orderby'     => '',
				'order'       => '',
				'el_class'    => '',

			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		$css_class[] = $atts['el_class'];
		$css_class[] = 'header-' . $atts['header'];

		$output = array();


		$header_tabs = array();
		if ( ! empty( $atts['title'] ) ) {
			$header_tabs[] = sprintf( '<h2 class="cat-title">%s</h2>', $atts['title'] );
		}

		if ( ! empty( $atts['links_group'] ) ) {
			$header_tabs[] = $this->get_links_group( $atts['links_group'] );

		}

		$products_atts = array(
			'columns'  => intval( $atts['columns'] ),
			'products' => $atts['products'],
			'order'    => $atts['order'],
			'orderby'  => $atts['orderby'],
			'per_page' => intval( $atts['per_page'] ),
			'cat'      => $atts['cat'],
		);

		$output[] = sprintf(
			'<div class="cat-header">%s</div>' .
			'<div class="products-content">%s</div>',
			implode( ' ', $header_tabs ),
			$this->get_wc_products( $products_atts )
		);


		return sprintf(
			'<div class="mf-products-list mf-products woocommerce %s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);

	}

	/**
	 * Products carousel shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function products_grid( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'    => '',
				'link'     => '',
				'cat'      => '',
				'per_page' => 10,
				'columns'  => 5,
				'products' => 'recent',
				'orderby'  => '',
				'order'    => '',
				'el_class' => '',
			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		$css_class[] = $atts['el_class'];

		$output = array();

		$header_tabs = array();
		if ( ! empty( $atts['title'] ) ) {
			$header_tabs[] = sprintf( '<h2 class="cat-title">%s</h2>', $atts['title'] );
		}

		$tabs = '';
		if ( $atts['cat'] ) {
			$tabs = explode( ',', $atts['cat'] );
		}
		$header_tabs[] = '<div class="tabs-header-nav">';
		$tab_content   = array();
		if ( $tabs ) {
			$header_tabs[] = '<ul class="tabs-nav">';
			foreach ( $tabs as $tab ) {
				$term = get_term_by( 'slug', $tab, 'product_cat' );
				if ( ! is_wp_error( $term ) && $term ) {
					$header_tabs[] = sprintf( '<li><a href="#" data-href="%s">%s</a></li>', esc_attr( $tab ), esc_html( $term->name ) );
				}

				$tab_atts      = array(
					'columns'  => intval( $atts['columns'] ),
					'products' => $atts['products'],
					'order'    => $atts['order'],
					'orderby'  => $atts['orderby'],
					'per_page' => intval( $atts['per_page'] ),
					'cat'      => $tab,
				);
				$tab_content[] = sprintf( '<div class="tabs-panel tabs-%s">%s</div>', esc_attr( $tab ), $this->get_wc_products( $tab_atts ) );

			}
			$header_tabs[] = '</ul>';
		} else {
			$products_atts = array(
				'columns'  => intval( $atts['columns'] ),
				'products' => $atts['products'],
				'order'    => $atts['order'],
				'orderby'  => $atts['orderby'],
				'per_page' => intval( $atts['per_page'] ),
				'cat'      => $atts['cat'],
			);
			$tab_content[] = sprintf( '<div class="tabs-panel">%s</div>', $this->get_wc_products( $products_atts ) );
		}

		if ( ! empty( $atts['link'] ) ) {
			$link_atts     = array(
				'link'    => $atts['link'],
				'content' => '',
				'class'   => 'link',
			);
			$header_tabs[] = $this->get_vc_link( $link_atts );
		}
		$header_tabs[] = '</div>';
		$output[]      = sprintf(
			'<div class="cat-header">%s</div>' .
			'<div class="tabs-content">%s</div>',
			implode( ' ', $header_tabs ),
			implode( ' ', $tab_content )
		);

		return sprintf(
			'<div class="mf-products-grid woocommerce martfury-tabs %s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);


	}

	/**
	 * Product deals carousel shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function deals_of_the_day( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'type'         => 'carousel',
				'style'        => '1',
				'title'        => '',
				'ends_in_text' => '',
				'link'         => '',
				'product_type' => 'day',
				'cat'          => '',
				'per_page'     => 12,
				'autoplay'     => 0,
				'columns'      => 5,
				'navigation'   => true,
				'pagination'   => false,
				'progress_bar' => true,
				'orderby'      => '',
				'order'        => '',
				'el_class'     => '',

			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		$css_class = array();

		$css_class[] = $atts['el_class'];
		$css_class[] = 'type-' . $atts['type'];
		$css_class[] = 'style-' . $atts['style'];
		$css_class[] = 'product-type-' . $atts['product_type'];

		if ( $atts['type'] == 'carousel' ) {
			$css_class[] = 'mf-products-carousel';
		}

		if ( $atts['ends_in_text'] ) {
			$css_class[] = 'hide-ends-in-text';
		}

		$output = array();
		$id     = uniqid( 'product-deals-grid' );
		if ( $atts['type'] == 'carousel' ) {
			$autoplay = false;
			$speed    = 1000;
			$infinite = false;
			if ( intval( $atts['autoplay'] ) ) {
				$autoplay = true;
				$infinite = true;
				$speed    = intval( $atts['autoplay'] );
			}
			$id                              = uniqid( 'deals-of-day' );
			$this->l10n['DealsOfDay'][ $id ] = array(
				'pro_autoplay'       => $autoplay,
				'pro_infinite'       => $infinite,
				'pro_autoplay_speed' => $speed,
				'pro_columns'        => intval( $atts['columns'] ),
				'pro_navigation'     => $atts['navigation'],
			);
		}

		$header_tabs   = array();
		$header_tabs[] = '<div class="header-content">';
		if ( ! empty( $atts['title'] ) ) {
			$header_tabs[] = sprintf( '<h2 class="cat-title">%s</h2>', esc_html( $atts['title'] ) );
		}

		$now         = strtotime( current_time( 'Y-m-d H:i:s' ) );
		$expire_date = strtotime( '00:00 +1 day' );

		if ( $atts['product_type'] == 'week' ) {
			$expire_date = strtotime( '00:00 next monday' );
		} elseif ( $atts['product_type'] == 'month' ) {
			$expire_date = strtotime( '00:00 first day of next month' );
		}

		$expire        = $expire_date - $now;
		$header_tabs[] = sprintf( '<div class="header-countdown"><span class="ends-text">%s</span><div class="martfury-countdown" data-expire="%s"></div></div>', esc_html__( 'Ends in:', 'martfury' ), esc_attr( $expire ) );
		$header_tabs[] = '</div>';
		$link_atts     = array(
			'link'    => $atts['link'],
			'content' => '',
			'class'   => '',
		);

		$header_tabs[] = sprintf( '<div class="header-link">%s</div>', $this->get_vc_link( $link_atts ) );
		$per_page      = intval( $atts['per_page'] );
		$query_args    = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => $per_page,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'fields'              => 'ids',
			'post__in'            => (array) wc_get_product_ids_on_sale(),
			'meta_query'          => WC()->query->get_meta_query(),
			'tax_query'           => WC()->query->get_tax_query(),
			'orderby'             => $atts['orderby'],
			'order'               => $atts['order'],
		);
		if ( $atts['type'] == 'grid' && $atts['pagination'] == true ) {
			$paged                       = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$offset                      = ( $paged - 1 ) * $per_page;
			$query_args['offset']        = $offset;
			$query_args['no_found_rows'] = false;
		}

		if ( $atts['product_type'] == 'day' ) {
			$query_args['meta_query'] = apply_filters(
				'martfury_product_deals_meta_query', array_merge(
					WC()->query->get_meta_query(), array(
						array(
							'key'     => '_deal_quantity',
							'value'   => 0,
							'compare' => '>',
						),
						array(
							'key'     => '_sale_price_dates_to',
							'value'   => 0,
							'compare' => '>',
						),
						array(
							'key'     => '_sale_price_dates_to',
							'value'   => strtotime( '+1 day' ),
							'compare' => '<=',
						),
					)
				)
			);
		} elseif ( $atts['product_type'] == 'week' ) {
			$query_args['meta_query'] = apply_filters(
				'martfury_product_deals_meta_query', array_merge(
					WC()->query->get_meta_query(), array(
						array(
							'key'     => '_deal_quantity',
							'value'   => 0,
							'compare' => '>',
						),
						array(
							'key'     => '_sale_price_dates_to',
							'value'   => 0,
							'compare' => '>',
						),
						array(
							'key'     => '_sale_price_dates_to',
							'value'   => strtotime( 'next monday' ),
							'compare' => '<=',
						),
					)
				)
			);
		} elseif ( $atts['product_type'] == 'month' ) {
			$query_args['meta_query'] = apply_filters(
				'martfury_product_deals_meta_query', array_merge(
					WC()->query->get_meta_query(), array(
						array(
							'key'     => '_deal_quantity',
							'value'   => 0,
							'compare' => '>',
						),
						array(
							'key'     => '_sale_price_dates_to',
							'value'   => 0,
							'compare' => '>',
						),
						array(
							'key'     => '_sale_price_dates_to',
							'value'   => strtotime( 'first day of next month' ),
							'compare' => '<=',
						),
					)
				)
			);
		}


		if ( $atts['cat'] ) {
			$query_args['tax_query'] = array_merge(
				WC()->query->get_tax_query(), array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'slug',
						'terms'    => explode( ',', $atts['cat'] ),
					),
				)
			);
		}

		$deals = new WP_Query( $query_args );

		if ( ! $deals->have_posts() ) {
			return '';
		}

		global $woocommerce_loop;

		$woocommerce_loop['columns'] = intval( $atts['columns'] );
		if ( intval( $atts['progress_bar'] ) ) {
			$woocommerce_loop['name'] = 'martfury_deals';
		} else {
			$woocommerce_loop['name'] = '';
		}

		ob_start();

		self::loop_deals( $deals->posts, 'product-deals' );

		$product_html = ob_get_clean();

		$output[] = sprintf(
			'<div class="cat-header">%s</div>' .
			'<div class="products-content">%s</div>',
			implode( ' ', $header_tabs ),
			$product_html
		);


		if ( intval( $atts['pagination'] ) ) {
			ob_start();
			$total_pages = $deals->max_num_pages;
			$this->pagination_numeric( $total_pages );
			$output[] = ob_get_clean();
		}

		return sprintf(
			'<div class="mf-product-deals-day woocommerce %s" id="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $id ),
			implode( '', $output )
		);


	}

	/**
	 * Sales Countdown Timer carousel shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function sales_countdown_timer( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'type'         => 'carousel',
				'style'        => '1',
				'title'        => '',
				'ends_in_text' => '',
				'link'         => '',
				'product_type' => 'day',
				'cat'          => '',
				'per_page'     => 12,
				'autoplay'     => 0,
				'columns'      => 5,
				'navigation'   => true,
				'pagination'   => false,
				'progress_bar' => true,
				'orderby'      => '',
				'order'        => '',
				'el_class'     => '',

			), $atts
		);

		$params = '';
		foreach ( $atts as $key => $value ) {
			$params .= ' ' . $key . '="' . $value . '"';
		}

		return do_shortcode( '[martfury_deals_of_the_day ' . $params . ']' );
	}

	/**
	 * Product deals carousel shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function product_deals_carousel( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'type'         => 'day',
				'title'        => '',
				'progress_bar' => true,
				'cat'          => '',
				'per_page'     => 4,
				'autoplay'     => 0,
				'navigation'   => true,
				'orderby'      => '',
				'order'        => '',
				'el_class'     => '',

			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		$css_class[] = $atts['el_class'];

		if ( ! intval( $atts['progress_bar'] ) ) {
			$css_class[] = 'hide-progress-bar';
		}

		$output   = array();
		$autoplay = false;
		$speed    = 1000;
		$infinite = false;
		if ( intval( $atts['autoplay'] ) ) {
			$autoplay = true;
			$infinite = true;
			$speed    = intval( $atts['autoplay'] );
		}
		$id                                        = uniqid( 'product-deals-carousel' );
		$this->l10n['productDealsCarousel'][ $id ] = array(
			'pro_autoplay'       => $autoplay,
			'pro_infinite'       => $infinite,
			'pro_autoplay_speed' => $speed,
			'pro_columns'        => 1,
			'pro_navigation'     => $atts['navigation'],
		);

		$header_tabs = array();
		if ( ! empty( $atts['title'] ) ) {
			$header_tabs[] = sprintf( '<h2 class="cat-title">%s</h2>', esc_html( $atts['title'] ) );
		}

		$header_tabs[] = '<div class="slick-arrows"><span class="icon-chevron-left-circle slick-prev-arrow"></span><span class="icon-chevron-right-circle slick-next-arrow"></span> </div>';

		$per_page   = intval( $atts['per_page'] );
		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => $per_page,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'fields'              => 'ids',
			'post__in'            => (array) wc_get_product_ids_on_sale(),
			'meta_query'          => WC()->query->get_meta_query(),
			'tax_query'           => WC()->query->get_tax_query(),
			'orderby'             => $atts['orderby'],
			'order'               => $atts['order'],
		);

		if ( in_array( $atts['type'], array( 'day', 'week', 'month' ) ) ) {
			$date = '+1 day';
			if ( $atts['type'] == 'week' ) {
				$date = '+7 day';
			} else if ( $atts['type'] == 'month' ) {
				$date = '+1 month';
			}
			$query_args['meta_query'] = apply_filters(
				'martfury_product_deals_meta_query', array_merge(
					WC()->query->get_meta_query(), array(
						array(
							'key'     => '_deal_quantity',
							'value'   => 0,
							'compare' => '>',
						),
						array(
							'key'     => '_sale_price_dates_to',
							'value'   => 0,
							'compare' => '>',
						),
						array(
							'key'     => '_sale_price_dates_to',
							'value'   => strtotime( $date ),
							'compare' => '<=',
						),
					)
				)
			);
		} elseif ( $atts['type'] == 'deals' ) {
			$query_args['meta_query'] = apply_filters(
				'martfury_product_deals_meta_query', array_merge(
					WC()->query->get_meta_query(), array(
						array(
							'key'     => '_deal_quantity',
							'value'   => 0,
							'compare' => '>',
						)
					)
				)
			);
		}

		if ( $atts['cat'] ) {
			$query_args['tax_query'] = array_merge(
				WC()->query->get_tax_query(), array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'slug',
						'terms'    => explode( ',', $atts['cat'] ),
					),
				)
			);
		}

		$deals = new WP_Query( $query_args );

		if ( ! $deals->have_posts() ) {
			return '';
		}

		global $woocommerce_loop;

		$woocommerce_loop['columns'] = 1;

		ob_start();

		self::loop_deals( $deals->posts, 'single-product-deal' );

		$product_html = ob_get_clean();

		$output[] = sprintf(
			'<div class="cat-header">%s</div>' .
			'%s',
			implode( ' ', $header_tabs ),
			$product_html
		);


		return sprintf(
			'<div class="mf-product-deals-carousel woocommerce %s" id="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $id ),
			implode( '', $output )
		);


	}

	/**
	 * Product deals carousel shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function product_deals_grid( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'type'     => 'day',
				'title'    => '',
				'cat'      => '',
				'columns'  => '5',
				'per_page' => 12,
				'orderby'  => '',
				'order'    => '',
				'el_class' => '',

			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		$css_class[] = $atts['el_class'];

		$output = array();

		$header_tabs = array();
		if ( ! empty( $atts['title'] ) ) {
			$header_tabs[] = sprintf( '<h2 class="cat-title">%s</h2>', esc_html( $atts['title'] ) );
		}

		$per_page   = intval( $atts['per_page'] );
		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => $per_page,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'fields'              => 'ids',
			'post__in'            => (array) wc_get_product_ids_on_sale(),
			'meta_query'          => WC()->query->get_meta_query(),
			'tax_query'           => WC()->query->get_tax_query(),
			'orderby'             => $atts['orderby'],
			'order'               => $atts['order'],
		);

		if ( in_array( $atts['type'], array( 'day', 'week', 'month' ) ) ) {
			$date = '+1 day';
			if ( $atts['type'] == 'week' ) {
				$date = '+7 day';
			} else if ( $atts['type'] == 'month' ) {
				$date = '+1 month';
			}
			$query_args['meta_query'] = apply_filters(
				'martfury_product_deals_meta_query', array_merge(
					WC()->query->get_meta_query(), array(
						array(
							'key'     => '_deal_quantity',
							'value'   => 0,
							'compare' => '>',
						),
						array(
							'key'     => '_sale_price_dates_to',
							'value'   => 0,
							'compare' => '>',
						),
						array(
							'key'     => '_sale_price_dates_to',
							'value'   => strtotime( $date ),
							'compare' => '<=',
						),
					)
				)
			);
		} elseif ( $atts['type'] == 'deals' ) {
			$query_args['meta_query'] = apply_filters(
				'martfury_product_deals_meta_query', array_merge(
					WC()->query->get_meta_query(), array(
						array(
							'key'     => '_deal_quantity',
							'value'   => 0,
							'compare' => '>',
						)
					)
				)
			);
		}


		if ( $atts['cat'] ) {
			$query_args['tax_query'] = array_merge(
				WC()->query->get_tax_query(), array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'slug',
						'terms'    => explode( ',', $atts['cat'] ),
					),
				)
			);
		}

		$deals = new WP_Query( $query_args );

		if ( ! $deals->have_posts() ) {
			return '';
		}

		global $woocommerce_loop;

		$woocommerce_loop['columns'] = intval( $atts['columns'] );

		ob_start();

		self::loop_deals( $deals->posts, 'product' );

		$product_html = ob_get_clean();

		$output[] = sprintf(
			'<div class="cat-header">%s</div>' .
			'%s',
			implode( ' ', $header_tabs ),
			$product_html
		);


		return sprintf(
			'<div class="mf-product-deals-grid woocommerce %s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);


	}

	/**
	 * Recently Viewed Products shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function recently_viewed_products( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'per_page'   => 12,
				'columns'    => 5,
				'pagination' => false,
				'orderby'    => '',
				'order'      => '',
				'el_class'   => '',

			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		$css_class[]     = $atts['el_class'];
		$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
		$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );
		if ( empty( $viewed_products ) ) {
			return;
		}

		$output = array();

		$per_page   = intval( $atts['per_page'] );
		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => $per_page,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'fields'              => 'ids',
			'post__in'            => $viewed_products,
			'orderby'             => 'post__in',
		);
		if ( $atts['pagination'] == true ) {
			$paged                       = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$offset                      = ( $paged - 1 ) * $per_page;
			$query_args['offset']        = $offset;
			$query_args['no_found_rows'] = false;
		}

		$products = new WP_Query( $query_args );

		if ( ! $products->have_posts() ) {
			return '';
		}

		global $woocommerce_loop;

		$woocommerce_loop['columns'] = intval( $atts['columns'] );

		ob_start();

		if ( $products->have_posts() ) :

			woocommerce_product_loop_start();
			while ( $products->have_posts() ) : $products->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile; // end of the loop.

			woocommerce_product_loop_end();

		endif;

		wp_reset_postdata();

		$output[] = ob_get_clean();

		if ( $atts['pagination'] == true ) {
			ob_start();
			$total_pages = $products->max_num_pages;
			$this->pagination_numeric( $total_pages );
			$output[] = ob_get_clean();
		}

		return sprintf(
			'<div class="mf-product-recently-viewed woocommerce %s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			implode( '', $output )
		);


	}

	/**
	 * Loop over products
	 *
	 * @param array $products_ids
	 */
	public static function loop_deals( $products_ids, $template ) {
		update_meta_cache( 'post', $products_ids );
		update_object_term_cache( $products_ids, 'product' );

		$original_post = $GLOBALS['post'];

		woocommerce_product_loop_start();

		foreach ( $products_ids as $product_id ) {
			$GLOBALS['post'] = get_post( $product_id ); // WPCS: override ok.
			setup_postdata( $GLOBALS['post'] );
			wc_get_template_part( 'content', $template );
		}

		$GLOBALS['post'] = $original_post; // WPCS: override ok.
		woocommerce_product_loop_end();

		wp_reset_postdata();
		woocommerce_reset_loop();
	}


	function deal_progress() {
		global $woocommerce_loop, $product;

		if ( ! isset( $woocommerce_loop['name'] ) ) {
			return;
		}

		if ( 'martfury_deals' != $woocommerce_loop['name'] ) {
			return;
		}

		if ( ! function_exists( 'tawc_is_deal_product' ) ) {
			return;
		}

		if ( ! tawc_is_deal_product( $product ) ) {
			return;
		}

		$limit = get_post_meta( $product->get_id(), '_deal_quantity', true );
		$sold  = intval( get_post_meta( $product->get_id(), '_deal_sales_counts', true ) );
		?>

        <div class="deal-progress">
            <div class="progress-bar">
                <div class="progress-value" style="width: <?php echo esc_attr( $sold / $limit * 100 ); ?>%"></div>
            </div>
            <p class="progress-text"><?php esc_html_e( 'Sold', 'martfury' ) ?>: <?php echo $sold; ?></p>
        </div>

		<?php
	}

	/**
	 * Display numeric pagination
	 *
	 * @param $total_pages
	 * @param $paged
	 */
	function pagination_numeric( $max_num_pages ) {
		?>
        <nav class="navigation paging-navigation numeric-navigation">
			<?php
			$big  = 999999999;
			$args = array(
				'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'total'     => $max_num_pages,
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'prev_text' => esc_html__( 'Previous', 'martfury' ),
				'next_text' => esc_html__( 'Next', 'martfury' ),
				'type'      => 'plain',
			);

			echo paginate_links( $args );
			?>
        </nav>
		<?php
	}


	/**
	 * Products carousel shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function top_selling( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'header'       => '1',
				'title'        => '',
				'links_group'  => '',
				'limit'        => 12,
				'autoplay'     => 0,
				'columns'      => 5,
				'navigation'   => true,
				'range'        => '',
				'from'         => '',
				'to'           => '',
				'el_class'     => '',
				'type'         => '1',
				'infinite'     => '',
				'parent_class' => true,

			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		$css_class[] = $atts['el_class'];
		$css_class[] = 'header-' . $atts['header'];

		$output = array();

		$autoplay = false;
		$speed    = 1000;
		$infinite = false;
		if ( intval( $atts['autoplay'] ) ) {
			$autoplay = true;
			$infinite = true;
			$speed    = intval( $atts['autoplay'] );
		}
		$id                              = uniqid( 'top-selling' );
		$this->l10n['topSelling'][ $id ] = array(
			'pro_autoplay'       => $autoplay,
			'pro_infinite'       => $infinite,
			'pro_autoplay_speed' => $speed,
			'pro_columns'        => intval( $atts['columns'] ),
			'pro_navigation'     => $atts['navigation'],
		);
		$el_infinite                     = apply_filters( 'martfury_elements_infinite_scrolling', $atts['infinite'] );
		if ( $el_infinite ) {
			$params = array(
				'title'        => $atts['title'],
				'links_group'  => $atts['links_group'],
				'limit'        => intval( $atts['limit'] ),
				'autoplay'     => $atts['autoplay'],
				'columns'      => $atts['columns'],
				'navigation'   => $atts['navigation'],
				'range'        => $atts['range'],
				'from'         => $atts['from'],
				'to'           => $atts['to'],
				'infinite'     => '',
				'parent_class' => '',
			);

			$this->l10n['topSelling'][ $id ]['params'] = $params;

			$output[] = '<div class="mf-vc-loading"><div class="mf-vc-loading--wrapper"></div></div>';

		} else {

			$header_tabs = array();
			if ( ! empty( $atts['title'] ) ) {
				$header_tabs[] = sprintf( '<h2 class="cat-title">%s</h2>', $atts['title'] );
			}

			if ( $atts['header'] == '1' ) {
				$header_tabs[] = $this->get_links_group( $atts['links_group'] );

			}


			$css_class[] = 'no-infinite';
			$output[]    = sprintf(
				'<div class="cat-header">%s</div>' .
				'<div class="products-content">%s</div>',
				implode( ' ', $header_tabs ),
				$this->get_top_selling( $atts )
			);
		}


		if ( $atts['parent_class'] ) {
			return sprintf(
				'<div class="mf-products-carousel mf-top-selling woocommerce %s" id="%s">%s</div>',
				esc_attr( implode( ' ', $css_class ) ),
				esc_attr( $id ),
				implode( '', $output )
			);
		} else {
			return implode( ' ', $output );
		}

	}

	/**
	 * Products carousel shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function top_selling_2( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'    => '',
				'limit'    => 12,
				'autoplay' => 0,
				'rows'     => 4,
				'columns'  => 1,
				'dots'     => true,
				'range'    => '',
				'from'     => '',
				'to'       => '',
				'el_class' => '',
				'type'     => '2',

			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		$css_class[] = $atts['el_class'];

		$output = array();

		$autoplay = false;
		$speed    = 1000;
		$infinite = false;
		if ( intval( $atts['autoplay'] ) ) {
			$autoplay = true;
			$infinite = true;
			$speed    = intval( $atts['autoplay'] );
		}
		$id                                        = uniqid( 'products-list-carousel' );
		$this->l10n['productsListCarousel'][ $id ] = array(
			'autoplay'       => $autoplay,
			'infinite'       => $infinite,
			'autoplay_speed' => $speed,
			'columns'        => 1,
			'dots'           => $atts['dots'],
		);

		$header_tabs = array();
		if ( ! empty( $atts['title'] ) ) {
			$header_tabs[] = sprintf( '<h2 class="cat-title">%s</h2>', $atts['title'] );
		}

		$output[] = sprintf(
			'<div class="cat-header">%s</div>' .
			'<div class="products-content">%s</div>',
			implode( ' ', $header_tabs ),
			$this->get_top_selling( $atts )
		);

		return sprintf(
			'<div class="mf-products-carousel mf-products-list-carousel woocommerce %s" id="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $id ),
			implode( '', $output )
		);
	}

	/**
	 * Products List carousel shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function products_list_carousel( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'    => '',
				'ids'      => '',
				'autoplay' => 0,
				'rows'     => 4,
				'columns'  => 1,
				'dots'     => true,
				'el_class' => '',

			), $atts
		);

		if ( ! $this->wc_actived ) {
			return;
		}

		if ( empty( $atts['ids'] ) ) {
			return;
		}

		$css_class[] = $atts['el_class'];

		$output = array();

		$autoplay = false;
		$speed    = 1000;
		$infinite = false;
		if ( intval( $atts['autoplay'] ) ) {
			$autoplay = true;
			$infinite = true;
			$speed    = intval( $atts['autoplay'] );
		}
		$id                                        = uniqid( 'products-list-carousel' );
		$this->l10n['productsListCarousel'][ $id ] = array(
			'autoplay'       => $autoplay,
			'infinite'       => $infinite,
			'autoplay_speed' => $speed,
			'columns'        => 1,
			'dots'           => $atts['dots'],
		);

		$header_tabs = array();
		if ( ! empty( $atts['title'] ) ) {
			$header_tabs[] = sprintf( '<h2 class="cat-title">%s</h2>', $atts['title'] );
		}

		$product_ids = explode( ',', $atts['ids'] );
		global $woocommerce_loop;
		$woocommerce_loop['columns'] = $atts['columns'];
		$original_post               = $GLOBALS['post'];

		ob_start();

		woocommerce_product_loop_start();

		$i     = 0;
		$count = count( $product_ids );
		foreach ( $product_ids as $product_id ) {
			$post_object = get_post( $product_id );
			if ( empty( $post_object ) ) {
				continue;
			}
			$GLOBALS['post'] =& $post_object; // WPCS: override ok.
			setup_postdata( $post_object );
			$this->get_products_list_template( $atts, $i, $count );
			$i ++;
		}

		$GLOBALS['post'] = $original_post;
		woocommerce_product_loop_end();
		wp_reset_postdata();
		woocommerce_reset_loop();

		$output[] = sprintf(
			'<div class="cat-header">%s</div>' .
			'<div class="products-content">%s</div>',
			implode( ' ', $header_tabs ),
			ob_get_clean()
		);

		return sprintf(
			'<div class="mf-products-carousel mf-products-list-carousel woocommerce %s" id="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			esc_attr( $id ),
			implode( '', $output )
		);
	}

	/**
	 * Get top selling
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	function get_top_selling( $atts ) {
		if ( ! class_exists( 'WC_Admin_Report' ) ) {
			include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php' );
		}

		$report = new WC_Admin_Report();

		if ( 'custom' == $atts['range'] ) {
			// Backup
			$start_date = isset( $_GET['start_date'] ) ? $_GET['start_date'] : '';
			$end_date   = isset( $_GET['end_date'] ) ? $_GET['end_date'] : '';

			// Modify
			$_GET['start_date'] = $atts['from'];
			$_GET['end_date']   = $atts['to'];
		}

		$report->calculate_current_range( $atts['range'] );

		if ( 'custom' == $atts['range'] ) {
			// Reset
			$_GET['start_date'] = $start_date;
			$_GET['end_date']   = $end_date;
		}

		$top_sellers = $report->get_order_report_data(
			array(
				'data'         => array(
					'_product_id' => array(
						'type'            => 'order_item_meta',
						'order_item_type' => 'line_item',
						'function'        => '',
						'name'            => 'product_id',
					),
					'_qty'        => array(
						'type'            => 'order_item_meta',
						'order_item_type' => 'line_item',
						'function'        => 'SUM',
						'name'            => 'order_item_qty',
					),
				),
				'order_by'     => 'order_item_qty DESC',
				'group_by'     => 'product_id',
				'limit'        => $atts['limit'],
				'query_type'   => 'get_results',
				'filter_range' => true,
			)
		);

		if ( ! $top_sellers ) {
			return '';
		}

		global $woocommerce_loop;

		$classes                     = array(
			'woocommerce',
			'best-sellers-' . $atts['range'],
			'columns-' . $atts['columns'],
			$atts['el_class'],
		);
		$woocommerce_loop['columns'] = $atts['columns'];
		$original_post               = $GLOBALS['post'];

		ob_start();

		woocommerce_product_loop_start();

		$i     = 0;
		$count = count( $top_sellers );
		foreach ( $top_sellers as $product ) {
			$post_object     = get_post( $product->product_id );
			$GLOBALS['post'] =& $post_object; // WPCS: override ok.
			setup_postdata( $post_object );

			// Render product template.
			if ( $atts['type'] == '1' ) {
				wc_get_template_part( 'content', 'product' );
			} else {
				$this->get_products_list_template( $atts, $i, $count );
			}

			$i ++;
		}

		$GLOBALS['post'] = $original_post;
		woocommerce_product_loop_end();
		wp_reset_postdata();
		woocommerce_reset_loop();

		return '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">' . ob_get_clean() . '</div>';
	}

	public function get_products_list_template( $atts, $index, $count ) {
		$lines = $atts['rows'];

		if ( $lines > 1 && $index % $lines == 0 ) {
			echo '<li><ul>';
		}

		wc_get_template_part( 'content', 'product-list' );

		if ( $lines > 1 && $index % $lines == $lines - 1 ) {
			echo '</ul></li>';
		} elseif ( $lines > 1 && $index == $count - 1 ) {
			echo '</ul></li>';
		}
	}

	/**
	 * catagory box shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function category_box( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'title'       => '',
				'link'        => '',
				'links_group' => '',
				'image'       => '',
				'banner_link' => '',
				'cats'        => '',
				'el_class'    => '',
			), $atts
		);

		$css_class[] = $atts['el_class'];
		$output      = array();

		$header_tabs = array();
		if ( ! empty( $atts['title'] ) ) {
			$link_atts     = array(
				'link'    => $atts['link'],
				'content' => $atts['title'],
				'class'   => '',
			);
			$header_tabs[] = sprintf( '<h2 class="cat-name">%s</h2>', $this->get_vc_link( $link_atts ) );
		}

		$header_tabs[] = $this->get_links_group( $atts['links_group'] );

		$banner_html = '';
		if ( $atts['image'] ) {
			$link_atts   = array(
				'link'    => $atts['link'],
				'content' => martfury_get_image_html( $atts['image'], 'full' ),
				'class'   => 'cat-banner',
			);
			$banner_html = $this->get_vc_link( $link_atts );
		}

		$term_list = array();
		if ( $atts['cats'] ) {
			$term_list [] = '<div class="sub-categories">';
			if ( $banner_html ) {
				$term_list[] = sprintf(
					'<div class="col-md-7 col-sm-6 col-xs-12 col-banner">%s</div>',
					$banner_html
				);
			}

			$classes = 'col-mf-5 col-sm-3 col-xs-6';

			$cats = explode( ',', $atts['cats'] );
			foreach ( $cats as $cat ) {
				$terms = get_terms(
					array(
						'taxonomy' => 'product_cat',
						'slug'     => $cat,
						'number'   => 1,
					)
				);

				if ( ! is_wp_error( $terms ) && $terms ) {
					$category             = $terms[0];
					$term_id              = $category->term_id;
					$thumbnail_id         = absint( get_term_meta( $term_id, 'thumbnail_id', true ) );
					$small_thumbnail_size = apply_filters( 'martfury_category_box_thumbnail_size', 'shop_catalog' );

					$image_html = '';
					if ( $thumbnail_id ) {
						$image_html = martfury_get_image_html( $thumbnail_id, $small_thumbnail_size );
					}

					$count = $category->count;


					$item_text = esc_html__( 'Items', 'martfury' );
					if ( $count <= 1 ) {
						$item_text = esc_html__( 'Item', 'martfury' );
					}

					$count .= ' ' . apply_filters( 'martfury_category_box_items_text', $item_text, $count );

					$term_list[] = sprintf(
						'<div class="%s col-cat"><a class="term-item" href="%s">%s <h3 class="term-name">%s <span class="count">%s</span></h3></a></div>',
						esc_attr( $classes ),
						esc_url( get_term_link( $term_id, 'product_cat' ) ),
						$image_html,
						$category->name,
						$count
					);
				}

			}

			$term_list [] = '</div>';
		}

		$output[] = sprintf(
			'<div class="cat-header">' .
			'%s' .
			'</div>' .
			'%s',
			implode( '', $header_tabs ),
			implode( ' ', $term_list )
		);

		return sprintf(
			'<div class="mf-category-box %s">' .
			'%s' .
			'</div>',
			esc_attr( implode( '', $css_class ) ),
			implode( '', $output )
		);


	}

	/**
	 * Comming soon shortcode
	 *
	 * @param array $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function countdown( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'date'     => '',
				'el_class' => '',
			), $atts
		);

		$css_class = array(
			'martfury-coming-soon martfury-time-format text-center',
			$atts['el_class'],
		);

		$second = 0;
		if ( $atts['date'] ) {
			$second_current = strtotime( date_i18n( 'Y/m/d H:i:s' ) );
			$date           = new DateTime( $atts['date'] );
			if ( $date ) {
				$second_discount = strtotime( date_i18n( 'Y/m/d H:i:s', $date->getTimestamp() ) );
				if ( $second_discount > $second_current ) {
					$second = $second_discount - $second_current;
				}
			}
		}

		$time_html = sprintf( '<div class="martfury-time-countdown martfury-countdown" data-expire="%s"></div>', esc_attr( $second ) );

		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$time_html
		);
	}

	function get_custom_products_html( $ids, $image_size ) {
		if ( empty( $ids ) ) {
			return;
		}
		$product_ids = explode( ',', $ids );

		if ( empty( $product_ids ) ) {
			return;
		}
		$output = array();
		foreach ( $product_ids as $id ) {
			$product = wc_get_product( $id );
			if ( empty( $product ) ) {
				continue;
			}
			$output[]   = '<li class="product">';
			$image_html = '';
			if ( function_exists( 'wpb_getImageBySize' ) ) {
				$image = wpb_getImageBySize(
					array(
						'attach_id'  => get_post_thumbnail_id( $id ),
						'thumb_size' => $image_size,
					)
				);

				if ( $image['thumbnail'] ) {
					$image_html = $image['thumbnail'];
				} elseif ( $image['p_img_large'] ) {
					$image_html = sprintf(
						'<img alt="" src="%s">',
						esc_attr( $image['p_img_large'][0] )
					);
				}

			}
			$output[] = sprintf(
				'<div class="product-thumbnail"><a href="%s">%s</a></div>',
				esc_url( $product->get_permalink() ),
				$image_html
			);

			$output[] = sprintf(
				'<div class="product-inners">' .
				'<h2><a href="%s">%s</a></h2>' .
				'<span class="price">%s</span>' .
				'</div>',
				esc_url( $product->get_permalink() ),
				$product->get_title(),
				$product->get_price_html()
			);
			$output[] = '</li>';
		}

		return sprintf( '<ul class="products">%s</ul>', implode( ' ', $output ) );
	}

	/**
	 * Shortcode to display banner small     *
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function banner_small( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'image'          => '',
				'image_size'     => 'full',
				'image_type'     => '1',
				'height'         => '',
				'bg_position'    => '',
				'title'          => '',
				'border'         => '',
				'link'           => '',
				'price'          => '',
				'bg_color'       => '',
				'price_position' => '1',
				'el_class'       => '',
			), $atts
		);

		$css_class = array(
			'mf-banner-small',
			intval( $atts['border'] ) ? 'has-border' : '',
			$atts['image_type'] == '2' ? 'has-bg-img' : 'has-img',
			$atts['el_class'],
		);

		$output = array();

		$item_html = '';

		if ( $atts['image_type'] == '1' ) {
			$item_html = $this->get_Image_By_Size(
				array(
					'attach_id'  => $atts['image'],
					'thumb_size' => $atts['image_size'],
				)
			);

			$item_html = $item_html ? sprintf( '<div class="b-image">%s</div>', $item_html ) : '';
		} else {
			if ( ! empty( $atts['image'] ) ) {
				$image       = wp_get_attachment_image_src( $atts['image'], 'full' );
				$image_style = $atts['bg_position'] ? 'background-position:' . $atts['bg_position'] : '';
				$item_html   = $image ? sprintf( '<div class="featured-image" style="background-image:url(%s);%s"></div>', $image[0], $image_style ) : '';
			}
		}


		$link_atts = array(
			'link'    => $atts['link'],
			'content' => '&nbsp',
			'class'   => 'link',
		);
		$item_html .= $this->get_vc_link( $link_atts );

		if ( $atts['price'] ) {
			$css_class[] = 'position-price-' . $atts['price_position'];
			$item_html   .= sprintf( '<div class="box-price"><span class="s-price">%s</span></div>', $atts['price'] );
		}

		if ( $atts['title'] ) {
			$output [] = sprintf( '<h2 class="box-title">%s</h2>', $atts['title'] );
		}
		if ( $atts['image_type'] == '1' ) {
			if ( $content ) {
				$output[] = sprintf( '<p class="desc">%s</p>', $content );
			}
		} else {
			$link_atts = array(
				'link'    => $atts['link'],
				'content' => '',
				'class'   => 'link-all',
			);
			$output[]  = $this->get_vc_link( $link_atts );
		}

		$style = $style_c = '';
		if ( $atts['bg_color'] ) {
			$style = 'background-color:' . $atts['bg_color'] . ';';
		}

		if ( intval( $atts['height'] ) ) {
			$style_c = ' min-height:' . intval( $atts['height'] ) . 'px;';
		}

		return sprintf(
			'<div class="%s" style="%s">' .
			'%s' .
			'<div class="banner-content" style="%s">' .
			'%s' .
			'</div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$style,
			$item_html,
			$style_c,
			implode( ' ', $output )
		);
	}

	/**
	 * Shortcode to display banner medium     *
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function banner_medium( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'layout'      => '1',
				'image'       => '',
				'image_size'  => 'full',
				'image_type'  => '1',
				'height'      => '',
				'bg_position' => '',
				'subtitle'    => '',
				'title'       => '',
				'border'      => '',
				'link'        => '',
				'bg_color'    => '',
				'subdesc'     => '',
				'el_class'    => '',
			), $atts
		);

		$css_class = array(
			'mf-banner-medium',
			intval( $atts['border'] ) ? 'has-border' : '',
			'layout-' . $atts['layout'],
			$atts['image_type'] == '2' ? 'has-bg-img' : 'has-img',
			$atts['el_class'],
		);

		$output     = array();
		$image_html = '';
		if ( $atts['image'] ) {
			if ( $atts['image_type'] == '1' ) {
				$image_html = $this->get_Image_By_Size(
					array(
						'attach_id'  => $atts['image'],
						'thumb_size' => $atts['image_size'],
					)
				);
			} else {
				$image       = wp_get_attachment_image_src( $atts['image'], 'full' );
				$image_style = $atts['bg_position'] ? 'background-position:' . $atts['bg_position'] : '';
				$image_html  = $image ? sprintf( '<div class="featured-image" style="background-image:url(%s);%s"></div>', $image[0], $image_style ) : '';
			}
		}
		if ( $atts['layout'] == '1' ) {
			if ( $atts['subtitle'] ) {
				$output[] = sprintf( '<span class="subtitle">%s</span>', $atts['subtitle'] );
			}
		}

		if ( $atts['title'] ) {
			$output[] = sprintf( '<h2 class="title">%s</h2>', $atts['title'] );
		}

		if ( $content ) {
			if ( $atts['layout'] == '4' ) {
				$content = sprintf( '<span class="subtitle">%s</span>', $atts['subtitle'] ) . $content;
			}
			$output[] = sprintf( '<div class="desc">%s</div>', $content );
		}

		if ( $atts['subdesc'] ) {
			$output[] = sprintf( '<div class="subdesc">%s</div>', $atts['subdesc'] );
		}

		$item_html = $this->get_vc_link(
			array(
				'link'    => $atts['link'],
				'content' => '&nbsp',
				'class'   => 'link-all',
			)
		);

		$link_html = '';
		if ( in_array( $atts['layout'], array( '2', '5' ) ) ) {
			$link_html = $this->get_vc_link(
				array(
					'link'    => $atts['link'],
					'content' => '',
					'class'   => 'link',
				)
			);

			$link_html = sprintf( '<div class="link-box">%s</div>', $link_html );
		}

		$style = $style_c = '';
		if ( $atts['bg_color'] ) {
			$style = 'background-color:' . $atts['bg_color'] . ';';
		}

		if ( intval( $atts['height'] ) ) {
			$style_c = ' min-height:' . intval( $atts['height'] ) . 'px;';
		}

		return sprintf(
			'<div class="%s" style="%s">' .
			'%s' .
			'<div class="banner-content" style="%s">' .
			'<div class="s-content">' .
			'%s' .
			'</div>' .
			'%s' .
			'</div>' .
			'<div class="banner-image">' .
			'%s' .
			'</div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$style,
			$item_html,
			$style_c,
			implode( ' ', $output ),
			$link_html,
			$image_html
		);
	}

	/**
	 * Shortcode to display banner large     *
	 *
	 * @param  array $atts
	 * @param  string $content
	 *
	 * @return string
	 */
	function banner_large( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'layout'     => '1',
				'image'      => '',
				'image_size' => 'full',
				'desc'       => '',
				'border'     => '',
				'link'       => '',
				'price'      => '',
				'sale_price' => '',
				'bg_color'   => '',
				'el_class'   => '',
			), $atts
		);

		$css_class = array(
			'mf-banner-large',
			intval( $atts['border'] ) ? 'has-border' : '',
			'layout-' . $atts['layout'],
			$atts['el_class'],
		);

		$output     = array();
		$price_html = array();
		$image_html = '';
		$item_html  = '';

		if ( $atts['image'] ) {
			if ( $atts['layout'] == '1' ) {
				$image_html = $this->get_Image_By_Size(
					array(
						'attach_id'  => $atts['image'],
						'thumb_size' => $atts['image_size'],
					)
				);
			} else {
				$image = wp_get_attachment_image_src( $atts['image'], 'full' );
				if ( $image ) {
					$item_html .= sprintf( '<div class="featured-image" style="background-image:url(%s)"></div>', esc_url( $image[0] ) );
				}
			}
		}

		$item_html .= $this->get_vc_link(
			array(
				'link'    => $atts['link'],
				'content' => '&nbsp',
				'class'   => 'link-all',
			)
		);

		if ( $atts['sale_price'] ) {
			$price_html[] = sprintf( '<span class="sale-price">%s</span>', $atts['sale_price'] );
		}

		if ( $atts['price'] ) {
			$price_html[] = sprintf( '<span class="s-price">%s</span>', $atts['price'] );
		}

		$link_atts = array(
			'link'    => $atts['link'],
			'content' => '',
			'class'   => 'link',
		);

		$price_html[] = $this->get_vc_link( $link_atts );

		if ( $content ) {
			$output [] = sprintf( '<h2 class="box-title">%s</h2>', $content );
		}
		if ( $atts['desc'] ) {
			$output[] = sprintf( '<p class="desc">%s</p>', $atts['desc'] );
		}
		$style = '';
		if ( $atts['bg_color'] ) {
			$style = 'background-color:' . $atts['bg_color'] . ';';
		}

		return sprintf(
			'<div class="%s" style="%s">' .
			'%s' .
			'<div class="row banner-row">' .
			'<div class="col-md-offset-1 %s col-sm-6 col-xs-12 col-banner-content">' .
			'<div class="banner-content">' .
			'%s' .
			'</div>' .
			'</div>' .
			'<div class="%s col-sm-6 col-xs-12 col-banner-price">' .
			'<div class="banner-price">' .
			'%s' .
			'</div>' .
			'</div>' .
			'<div class="col-md-5 col-sm-12 col-xs-12 col-banner-image">' .
			'<div class="banner-image">' .
			'%s' .
			'</div>' .
			'</div>' .
			'</div>' .
			'</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$style,
			$item_html,
			$atts['layout'] == '1' ? 'col-md-4' : 'col-md-3',
			implode( ' ', $output ),
			$atts['layout'] == '1' ? 'col-md-2' : 'col-md-3',
			implode( ' ', $price_html ),
			$image_html
		);
	}


	/*
	 * GG Maps shortcode
	 */
	function gmap( $atts, $content ) {
		$atts = shortcode_atts(
			array(
				'api_key'            => '',
				'marker'             => '',
				'info'               => '',
				'width'              => '',
				'height'             => '640',
				'zoom'               => '14',
				'el_class'           => '',
				'map_color'          => '#a4c4c7',
				'road_highway_color' => '#f49555',
			), $atts
		);

		$class = array(
			'martfury-map-shortcode',
			$atts['el_class'],
		);

		$style = '';
		if ( $atts['width'] ) {
			$unit = 'px;';
			if ( strpos( $atts['width'], '%' ) ) {
				$unit = '%;';
			}

			$atts['width'] = intval( $atts['width'] );
			$style         .= 'width: ' . $atts['width'] . $unit;
		}
		if ( $atts['height'] ) {
			$unit = 'px;';
			if ( strpos( $atts['height'], '%' ) ) {
				$unit = '%;';
			}

			$atts['height'] = intval( $atts['height'] );
			$style          .= 'height: ' . $atts['height'] . $unit;
		}
		if ( $atts['zoom'] ) {
			$atts['zoom'] = intval( $atts['zoom'] );
		}

		$id   = uniqid( 'mf_map_' );
		$html = sprintf(
			'<div class="%s"><div id="%s" class="mf-map" style="%s"></div></div>',
			implode( ' ', $class ),
			$id,
			$style
		);

		$lats    = array();
		$lng     = array();
		$info    = array();
		$i       = 0;
		$fh_info = function_exists( 'vc_param_group_parse_atts' ) ? vc_param_group_parse_atts( $atts['info'] ) : array();

		if ( ! empty( $fh_info ) ) {
			foreach ( $fh_info as $key => $value ) {
				$map_img = $map_info = $map_html = '';

				if ( isset( $value['image'] ) && $value['image'] ) {
					$map_img = wp_get_attachment_image( $value['image'], 'full' );
				}

				if ( isset( $value['details'] ) && $value['details'] ) {
					$map_info = sprintf( '<div class="mf-map-info">%s</div>', $value['details'] );
				}

				$map_html = sprintf(
					'<div class="box-wrapper"><h4>%s</h4>%s</div>',
					esc_html__( 'Location', 'martfury' ),
					$map_info
				);

				$coordinates = $this->get_coordinates( $value['address'], $atts['api_key'] );
				$lats[]      = $coordinates['lat'];
				$lng[]       = $coordinates['lng'];
				$info[]      = $map_img;
				$info[]      = $map_html;

				if ( isset( $coordinates['error'] ) ) {
					return $coordinates['error'];
				}

				$i ++;
			}
		}

		$marker = '';
		if ( $atts['marker'] ) {

			if ( filter_var( $atts['marker'], FILTER_VALIDATE_URL ) ) {
				$marker = $atts['marker'];
			} else {
				$attachment_image = wp_get_attachment_image_src( intval( $atts['marker'] ), 'full' );
				$marker           = $attachment_image ? $attachment_image[0] : '';
			}
		}

		$this->api_key = $atts['api_key'];

		$this->l10n['map'][ $id ] = array(
			'type'               => 'normal',
			'lat'                => $lats,
			'lng'                => $lng,
			'zoom'               => $atts['zoom'],
			'marker'             => $marker,
			'height'             => $atts['height'],
			'info'               => implode( '', $info ),
			'number'             => $i,
			'map_color'          => $atts['map_color'],
			'road_highway_color' => $atts['road_highway_color'],
		);

		return $html;

	}


	function get_cookie( $name ) {
		if ( isset( $_COOKIE[ $name ] ) ) {
			return json_decode( stripslashes( $_COOKIE[ $name ] ), true );
		}

		return array();
	}


	/**
	 * Helper function to get coordinates for map
	 *
	 * @since 1.0.0
	 *
	 * @param string $address
	 * @param bool $refresh
	 *
	 * @return array
	 */
	function get_coordinates( $address, $api_key, $refresh = false ) {
		$address_hash = md5( $address );
		$coordinates  = get_transient( $address_hash );
		$results      = array( 'lat' => '', 'lng' => '' );

		if ( $refresh || $coordinates === false ) {
			$args     = array( 'address' => urlencode( $address ), 'sensor' => 'false', 'key' => $api_key );
			$url      = add_query_arg( $args, 'https://maps.googleapis.com/maps/api/geocode/json' );
			$response = wp_remote_get( $url );

			if ( is_wp_error( $response ) ) {
				$results['error'] = esc_html__( 'Can not connect to Google Maps APIs', 'martfury' );

				return $results;
			}

			$data = wp_remote_retrieve_body( $response );

			if ( is_wp_error( $data ) ) {
				$results['error'] = esc_html__( 'Can not connect to Google Maps APIs', 'martfury' );

				return $results;
			}

			if ( $response['response']['code'] == 200 ) {
				$data = json_decode( $data );

				if ( $data->status === 'OK' ) {
					$coordinates = $data->results[0]->geometry->location;

					$results['lat']     = $coordinates->lat;
					$results['lng']     = $coordinates->lng;
					$results['address'] = (string) $data->results[0]->formatted_address;

					// cache coordinates for 3 months
					set_transient( $address_hash, $results, 3600 * 24 * 30 * 3 );
				} elseif ( $data->status === 'ZERO_RESULTS' ) {
					$results['error'] = esc_html__( 'No location found for the entered address.', 'martfury' );
				} elseif ( $data->status === 'INVALID_REQUEST' ) {
					$results['error'] = esc_html__( 'Invalid request. Did you enter an address?', 'martfury' );
				} else {
					$results['error'] = esc_html__( 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.', 'martfury' );
				}
			} else {
				$results['error'] = esc_html__( 'Unable to contact Google API service.', 'martfury' );
			}
		} else {
			$results = $coordinates; // return cached results
		}

		return $results;
	}

	function martfury_addons_get_socials() {
		$socials = array(
			'facebook'   => esc_html__( 'Facebook', 'martfury' ),
			'twitter'    => esc_html__( 'Twitter', 'martfury' ),
			'google'     => esc_html__( 'Google', 'martfury' ),
			'tumblr'     => esc_html__( 'Tumblr', 'martfury' ),
			'flickr'     => esc_html__( 'Flickr', 'martfury' ),
			'vimeo'      => esc_html__( 'Vimeo', 'martfury' ),
			'youtube'    => esc_html__( 'Youtube', 'martfury' ),
			'linkedin'   => esc_html__( 'LinkedIn', 'martfury' ),
			'pinterest'  => esc_html__( 'Pinterest', 'martfury' ),
			'dribbble'   => esc_html__( 'Dribbble', 'martfury' ),
			'spotify'    => esc_html__( 'Spotify', 'martfury' ),
			'instagram'  => esc_html__( 'Instagram', 'martfury' ),
			'tumbleupon' => esc_html__( 'Tumbleupon', 'martfury' ),
			'wordpress'  => esc_html__( 'WordPress', 'martfury' ),
			'rss'        => esc_html__( 'Rss', 'martfury' ),
			'deviantart' => esc_html__( 'Deviantart', 'martfury' ),
			'share'      => esc_html__( 'Share', 'martfury' ),
			'skype'      => esc_html__( 'Skype', 'martfury' ),
			'behance'    => esc_html__( 'Behance', 'martfury' ),
			'apple'      => esc_html__( 'Apple', 'martfury' ),
			'yelp'       => esc_html__( 'Yelp', 'martfury' ),
		);

		return apply_filters( 'martfury_addons_get_socials', $socials );
	}

	protected
	function martfury_addons_btn(
		$atts
	) {
		$css_class = array(
			'martfury-button',
			'text-' . $atts['align'],
			'size-' . $atts['size'],
			'color-' . $atts['color'],
			$atts['el_class'],
		);

		$attributes = array();

		$link = vc_build_link( $atts['link'] );

		if ( ! empty( $link['url'] ) ) {
			$attributes['href'] = $link['url'];
		}

		$label = $link['title'];

		if ( ! $label ) {
			$attributes['title'] = $label;
		}

		if ( ! empty( $link['target'] ) ) {
			$attributes['target'] = $link['target'];
		}

		if ( ! empty( $link['rel'] ) ) {
			$attributes['rel'] = $link['rel'];
		}

		$attr = array();

		foreach ( $attributes as $name => $v ) {
			$attr[] = $name . '="' . esc_attr( $v ) . '"';
		}

		$button = sprintf(
			'<%1$s %2$s>%3$s</%1$s>',
			empty( $attributes['href'] ) ? 'span' : 'a',
			implode( ' ', $attr ),
			$label
		);

		return sprintf(
			'<div class="%s">%s</div>',
			esc_attr( implode( ' ', $css_class ) ),
			$button
		);
	}

	/**
	 * @param $atts
	 *
	 * @return string
	 */
	function get_wc_products( $atts ) {
		$params   = '';
		$order    = $atts['order'];
		$order_by = $atts['orderby'];
		if ( $atts['products'] == 'featured' ) {
			$params = 'visibility="featured"';
		} elseif ( $atts['products'] == 'best_selling' ) {
			$params = 'best_selling="true"';
		} elseif ( $atts['products'] == 'sale' ) {
			$params = 'on_sale="true"';
		} elseif ( $atts['products'] == 'recent' ) {
			$order    = $order ? $order : 'desc';
			$order_by = $order_by ? $order_by : 'date';
		} elseif ( $atts['products'] == 'top_rated' ) {
			$params = 'top_rated="true"';
		}

		$params .= ' columns="' . intval( $atts['columns'] ) . '" limit="' . intval( $atts['per_page'] ) . '" order="' . $order . '" orderby ="' . $order_by . '"';
		if ( ! empty( $atts['cat'] ) ) {
			$params .= ' category="' . $atts['cat'] . '" ';
		}

		if ( ! empty( $atts['tags'] ) ) {
			$params .= ' tag="' . $atts['tags'] . '" ';
		}

		return do_shortcode( '[products ' . $params . ']' );

	}

	/**
	 * Get vc link
	 */
	function get_vc_link( $atts ) {
		$attributes = array();
		$title      = '';

		if ( function_exists( 'vc_build_link' ) ) {
			$link = vc_build_link( $atts['link'] );

			if ( ! empty( $link['url'] ) ) {
				$attributes['href'] = $link['url'];
			}

			$title = $link['title'];

			if ( ! empty( $link['target'] ) ) {
				$attributes['target'] = $link['target'];
			}

			if ( ! empty( $link['rel'] ) ) {
				$attributes['rel'] = $link['rel'];
			}
		}

		$attr = array();

		foreach ( $attributes as $name => $v ) {
			$attr[] = $name . '="' . esc_attr( $v ) . '"';
		}

		$content = $atts['content'];

		if ( empty( $content ) ) {
			$content = $title;
			if ( empty( $title ) ) {
				$atts['class'] .= ' no-content';
			}
		}

		if ( $content == '&nbsp' ) {
			$content = '';
		}

		return sprintf(
			'<%1$s class="%2$s" %3$s>%4$s</%1$s>',
			empty( $attributes['href'] ) ? 'span' : 'a',
			esc_attr( $atts['class'] ),
			implode( ' ', $attr ),
			$content
		);
	}

	/**
	 * @param $atts
	 * @param $images
	 *
	 * @return string
	 */
	public function get_banners( $atts ) {
		$output       = array();
		$custom_links = '';
		if ( function_exists( 'vc_value_from_safe' ) ) {
			$custom_links = vc_value_from_safe( $atts['custom_links'] );
			$custom_links = explode( ',', $custom_links );
		}

		$images_id = $atts['images'] ? explode( ',', $atts['images'] ) : '';
		$images    = array();
		if ( $images_id ) {
			$i = 0;
			foreach ( $images_id as $attachment_id ) {
				$image = '';
				if ( function_exists( 'martfury_get_image_html' ) ) {
					$image = martfury_get_image_html( $attachment_id, 'full' );
				} else {
					$image = wp_get_attachment_image( $attachment_id, 'full' );
				}
				if ( $image ) {
					$href = '';
					if ( $custom_links && isset( $custom_links[ $i ] ) ) {
						$link = preg_replace( '/<br \/>/iU', '', $custom_links[ $i ] );
						if ( $link ) {
							$href = 'href="' . esc_url( $link ) . '"';
						}
					}

					$images[] = sprintf(
						'<a class="image-item" %s >%s</a>',
						$href,
						$image
					);
				}
				$i ++;
			}

			if ( $images ) {
				$output[] = sprintf( '<div class="images-slider"><div class="images-list">%s</div></div>', implode( ' ', $images ) );
			}
		}

		return implode( ' ', $output );
	}

	/**
	 * @param $atts
	 * @param $header_tabs
	 *
	 * @return string
	 */
	public
	function get_links_group(
		$links_group
	) {
		if ( empty( $links_group ) ) {
			return '';
		}

		$links_group = function_exists( 'vc_param_group_parse_atts' ) ? vc_param_group_parse_atts( $links_group ) : array();
		$extra_link  = array();
		foreach ( $links_group as $link_group ) {


			$css_class = 'extra-link ';

			$css_class .= isset( $link_group['style'] ) ? 'style-' . $link_group['style'] : '';

			$link_atts    = array(
				'link'    => isset( $link_group['link'] ) ? $link_group['link'] : '',
				'content' => isset( $link_group['title'] ) ? $link_group['title'] : '',
				'class'   => $css_class
			);
			$link         = $this->get_vc_link( $link_atts );
			$extra_link[] = sprintf( '<li>%s</li>', $link );
		}

		return $extra_link ? sprintf( '<ul class="extra-links">%s</ul>', implode( ' ', $extra_link ) ) : '';
	}

	/**
	 * @param array $params
	 *
	 * @since 4.2
	 * @return array|bool
	 */
	function get_Image_By_Size( $params = array() ) {
		$params = array_merge(
			array(
				'post_id'    => null,
				'attach_id'  => null,
				'thumb_size' => 'thumbnail',
				'class'      => '',
			), $params
		);

		if ( ! $params['thumb_size'] ) {
			$params['thumb_size'] = 'thumbnail';
		}

		if ( ! $params['attach_id'] && ! $params['post_id'] ) {
			return false;
		}

		$post_id = $params['post_id'];

		$attach_id   = $post_id ? get_post_thumbnail_id( $post_id ) : $params['attach_id'];
		$thumb_size  = $params['thumb_size'];
		$thumb_class = ( isset( $params['class'] ) && '' !== $params['class'] ) ? $params['class'] . ' ' : '';

		$thumbnail = '';

		if ( is_string( $thumb_size ) && ( in_array(
				$thumb_size, array(
					'thumbnail',
					'thumb',
					'medium',
					'large',
					'full',
				)
			) )
		) {
			$attributes = array( 'class' => $thumb_class . 'attachment-' . $thumb_size );
			if ( function_exists( 'martfury_get_image_html' ) ) {
				$thumbnail = martfury_get_image_html( $attach_id, $thumb_size );
			} else {
				$thumbnail = wp_get_attachment_image( $attach_id, $thumb_size, false, $attributes );
			}
		} elseif ( $attach_id ) {
			if ( is_string( $thumb_size ) ) {
				preg_match_all( '/\d+/', $thumb_size, $thumb_matches );
				if ( isset( $thumb_matches[0] ) ) {
					$thumb_size = array();
					$count      = count( $thumb_matches[0] );
					if ( $count > 1 ) {
						$thumb_size[] = $thumb_matches[0][0]; // width
						$thumb_size[] = $thumb_matches[0][1]; // height
					} elseif ( 1 === $count ) {
						$thumb_size[] = $thumb_matches[0][0]; // width
						$thumb_size[] = $thumb_matches[0][0]; // height
					} else {
						$thumb_size = false;
					}
				}
			}
			if ( is_array( $thumb_size ) ) {
				// Resize image to custom size
				if ( function_exists( 'wpb_resize' ) ) {
					$p_img      = wpb_resize( $attach_id, null, $thumb_size[0], $thumb_size[1], true );
					$alt        = trim( strip_tags( get_post_meta( $attach_id, '_wp_attachment_image_alt', true ) ) );
					$attachment = get_post( $attach_id );
					if ( ! empty( $attachment ) ) {
						$title = trim( strip_tags( $attachment->post_title ) );

						if ( empty( $alt ) ) {
							$alt = trim( strip_tags( $attachment->post_excerpt ) ); // If not, Use the Caption
						}
						if ( empty( $alt ) ) {
							$alt = $title;
						} // Finally, use the title
						if ( $p_img ) {

							$attributes = array(
								'class'  => $thumb_class,
								'src'    => $p_img['url'],
								'width'  => $p_img['width'],
								'height' => $p_img['height'],
								'alt'    => $alt,
							);


							if ( function_exists( 'martfury_get_option' ) ) {
								if ( martfury_get_option( 'lazyload' ) ) {
									$attributes['src']           = get_template_directory_uri() . '/images/transparent.png';
									$attributes['class']         .= ' lazy';
									$attributes['data-original'] = $p_img['url'];
								}
							}

							$atts = array();
							foreach ( $attributes as $name => $value ) {
								$atts[] = $name . '="' . esc_attr( $value ) . '"';
							}

							$thumbnail = '<img ' . implode( ' ', $atts ) . ' />';
						}
					}
				}

			}
		}

		if ( empty( $thumbnail ) ) {
			$thumbnail = wp_get_attachment_image( $attach_id, 'full' );
		}

		return $thumbnail;
	}

}

