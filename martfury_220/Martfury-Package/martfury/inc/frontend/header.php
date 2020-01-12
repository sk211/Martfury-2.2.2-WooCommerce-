<?php
/**
 * Hooks for template header
 *
 * @package Martfury
 */


/**
 * Enqueue scripts and styles.
 *
 * @since 1.0
 */
function martfury_enqueue_scripts() {
	/**
	 * Register and enqueue styles
	 */
	wp_register_style( 'martfury-fonts', martfury_fonts_url(), array(), '20170801' );
	wp_register_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '3.3.7' );
	wp_register_style( 'linearicons', get_template_directory_uri() . '/css/linearicons.min.css', array(), '1.0.0' );
	wp_register_style( 'ionicons', get_template_directory_uri() . '/css/ionicons.min.css', array(), '2.0.0' );
	wp_register_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), '4.6.3' );
	wp_register_style( 'eleganticons', get_template_directory_uri() . '/css/eleganticons.min.css', array(), '1.0.0' );
	wp_register_style( 'photoswipe', get_theme_file_uri( 'css/photoswipe.css' ), array(), '4.1.1' );
	wp_enqueue_style(
		'martfury', get_template_directory_uri() . '/style.css', array(
		'martfury-fonts',
		'linearicons',
		'ionicons',
		'font-awesome',
		'eleganticons',
		'bootstrap',
	), '20190711'
	);

	wp_add_inline_style( 'martfury', martfury_header_styles() );

	/**
	 * Register and enqueue scripts
	 */

	wp_enqueue_script( 'html5shiv', get_template_directory_uri() . '/js/plugins/html5shiv.min.js', array(), '3.7.2' );
	wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'respond', get_template_directory_uri() . '/js/plugins/respond.min.js', array(), '1.4.2' );
	wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );

	wp_register_script( 'photoswipe', get_template_directory_uri() . '/js/plugins/photoswipe.min.js', array(), '4.1.1', true );
	wp_register_script( 'photoswipe-ui', get_template_directory_uri() . '/js/plugins/photoswipe-ui.min.js', array( 'photoswipe' ), '4.1.1', true );
	wp_register_script( 'threesixty', get_template_directory_uri() . '/js/plugins/threesixty.min.js', array(), '2.0.5', true );
	wp_register_script( 'waypoints', get_template_directory_uri() . '/js/plugins/waypoints.min.js', array( 'jquery' ), '2.0.2' );
	wp_register_script( 'isinviewport', get_template_directory_uri() . '/js/plugins/isInViewport.min.js', array(), '1.0.0', true );
	wp_register_script( 'mf-countdown', get_template_directory_uri() . '/js/plugins/jquery.coundown.js', array(), '1.0.0', true );
	wp_register_script( 'counterup', get_template_directory_uri() . '/js/plugins/jquery.counterup.min.js', array(), '1.0.0', true );
	wp_register_script( 'fitvids', get_template_directory_uri() . '/js/plugins/jquery.fitvids.js', array(), '1.1.0', true );
	wp_register_script( 'lazyload', get_template_directory_uri() . '/js/plugins/jquery.lazyload.min.js', array(), '1.9.7', true );
	wp_register_script( 'slimscroll', get_template_directory_uri() . '/js/plugins/jquery.slimscroll.js', array(), '1.3.8', true );
	wp_register_script( 'martfury-tabs', get_template_directory_uri() . '/js/plugins/jquery.tabs.js', array(), '1.0.0', true );
	wp_register_script( 'nprogress', get_template_directory_uri() . '/js/plugins/nprogress.js', array(), '1.0.0', true );
	wp_register_script( 'slick', get_template_directory_uri() . '/js/plugins/slick.min.js', array(), '1.6.0', true );
	wp_register_script( 'isotope', get_template_directory_uri() . '/js/plugins/isotope.pkgd.min.js', array(), '2.2.2', true );
	wp_register_script( 'notify', get_template_directory_uri() . '/js/plugins/notify.min.js', array(), '1.0.0', true );
	wp_enqueue_script( 'wc-add-to-cart-variation' );
	if ( is_singular() ) {

		wp_enqueue_style( 'photoswipe' );
		wp_enqueue_script( 'photoswipe-ui' );

		$photoswipe_skin = 'photoswipe-default-skin';
		if ( wp_style_is( $photoswipe_skin, 'registered' ) && ! wp_style_is( $photoswipe_skin, 'enqueued' ) ) {
			wp_enqueue_style( $photoswipe_skin );
		}
	}
	global $post;
	if ( ( ! empty( $post->post_content ) && strstr( $post->post_content, '[martfury_product_deals_carousel' ) ) ) {
		if ( current_theme_supports( 'wc-product-gallery-zoom' ) ) {
			wp_enqueue_script( 'zoom' );
		}
		if ( current_theme_supports( 'wc-product-gallery-slider' ) ) {
			wp_enqueue_script( 'flexslider' );
		}
		wp_enqueue_script( 'wc-single-product' );
	}

	if ( is_singular( 'product' ) ) {
		wp_enqueue_script( 'threesixty' );
	}

	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	if ( martfury_is_blog() ) {
		wp_enqueue_script( 'isotope' );
	}

	wp_enqueue_script(
		'martfury', get_template_directory_uri() . "/js/scripts$min.js", array(
		'jquery',
		'jquery-ui-tooltip',
		'imagesloaded',
		'lazyload',
		'waypoints',
		'isinviewport',
		'mf-countdown',
		'counterup',
		'fitvids',
		'slimscroll',
		'martfury-tabs',
		'nprogress',
		'slick',
		'notify',
	), '20170801', true
	);

	$product_images_dg = '';
	if ( is_singular( 'product' ) ) {
		$images_dg = get_post_meta( get_the_ID(), 'product_360_view', false );
		if ( $images_dg ) {
			foreach ( $images_dg as $image ) {
				$image_dg          = wp_get_attachment_image_src( $image, 'full' );
				$product_images_dg .= $product_images_dg ? ',' : '';
				$product_images_dg .= $image_dg ? $image_dg[0] : '';
			}
		}
	}

	wp_localize_script(
		'martfury', 'martfuryData', array(
			'direction'            => is_rtl() ? 'true' : 'false',
			'ajax_url'             => admin_url( 'admin-ajax.php' ),
			'nonce'                => wp_create_nonce( '_martfury_nonce' ),
			'currency_pos'         => get_option( 'woocommerce_currency_pos' ),
			'currency_symbol'      => function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : '',
			'thousand_sep'         => function_exists( 'wc_get_price_thousand_separator' ) ? wc_get_price_thousand_separator() : '',
			'decimal_sep'          => function_exists( 'wc_get_price_decimal_separator' ) ? wc_get_price_decimal_separator() : '',
			'price_decimals'       => function_exists( 'wc_get_price_decimals' ) ? wc_get_price_decimals() : '',
			'days'                 => esc_html__( 'days', 'martfury' ),
			'hours'                => esc_html__( 'hours', 'martfury' ),
			'minutes'              => esc_html__( 'minutes', 'martfury' ),
			'seconds'              => esc_html__( 'seconds', 'martfury' ),
			'product_degree'       => $product_images_dg,
			'add_to_cart_ajax'     => intval( martfury_get_option( 'product_add_to_cart_ajax' ) ),
			'search_content_type'  => martfury_get_option( 'search_content_type' ),
			'nl_days'              => intval( martfury_get_option( 'newsletter_reappear' ) ),
			'nl_seconds'           => intval( martfury_get_option( 'newsletter_visible' ) ) == 2 ? intval( martfury_get_option( 'newsletter_seconds' ) ) : 0,
			'added_to_cart_notice' => intval( martfury_get_option( 'added_to_cart_notice' ) ),
			'product_gallery'      => intval( martfury_get_option( 'product_images_lightbox' ) ),
			'ajax_search'          => intval( martfury_get_option( 'header_ajax_search' ) ),
			'collapse_the_filter'  => array(
				'collapse' => intval( martfury_get_option( 'collapse_the_filter' ) ),
				'status'   => martfury_get_option( 'collapse_the_filter_status' ),
			),
			'collapse_tab'         => array(
				'collapse' => intval( martfury_get_option( 'collapse_tab' ) ),
				'status'   => martfury_get_option( 'collapse_tab_status' ),
			),
			'quantity_ajax'        => martfury_get_option( 'quantity_ajax' ),
			'l10n'                 => array(
				'notice_text'           => esc_html__( 'has been added to your cart.', 'martfury' ),
				'notice_texts'          => esc_html__( 'have been added to your cart.', 'martfury' ),
				'cart_text'             => esc_html__( 'View Cart', 'martfury' ),
				'cart_link'             => function_exists( 'wc_get_cart_url' ) ? esc_url( wc_get_cart_url() ) : '',
				'cart_notice_auto_hide' => intval( martfury_get_option( 'cart_notice_auto_hide' ) ) > 0 ? intval( martfury_get_option( 'cart_notice_auto_hide' ) ) * 1000 : 0,
			),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'martfury_enqueue_scripts' );

/**
 * Custom styles on header
 *
 * @since  1.0.0
 */
function martfury_header_styles() {
	/**
	 * All Custom CSS rules
	 */
	$inline_css = '';

	//Logo
	$logo_size_width = apply_filters( 'martfury_logo_width', martfury_get_option( 'logo_width' ) );
	$logo_size_width = intval( $logo_size_width );
	$logo_css        = $logo_size_width ? 'width:' . $logo_size_width . 'px; ' : '';

	$logo_size_height = apply_filters( 'martfury_logo_height', martfury_get_option( 'logo_height' ) );
	$logo_size_height = intval( $logo_size_height );
	$logo_height_css  = $logo_size_height ? 'height:' . $logo_size_height . 'px; ' : '';

	$logo_margin     = apply_filters( 'martfury_logo_margins', martfury_get_option( 'logo_margins' ) );
	$logo_margin_css = $logo_margin['top'] ? 'margin-top:' . $logo_margin['top'] . '; ' : '';
	$logo_margin_css .= $logo_margin['right'] ? 'margin-right:' . $logo_margin['right'] . '; ' : '';
	$logo_margin_css .= $logo_margin['bottom'] ? 'margin-bottom:' . $logo_margin['bottom'] . '; ' : '';
	$logo_margin_css .= $logo_margin['left'] ? 'margin-left:' . $logo_margin['left'] . '; ' : '';

	if ( ! empty( $logo_css ) ) {
		$inline_css .= '.site-header .logo img ' . ' {' . $logo_css . '}';
	}

	if ( ! empty( $logo_height_css ) ) {
		$inline_css .= '.site-header .logo img ' . ' {' . $logo_height_css . '}';
	}

	if ( ! empty( $logo_margin_css ) ) {
		$inline_css .= '.site-header .logo ' . ' {' . $logo_margin_css . '}';
	}


	/* 404 background */

	if ( is_404() ) {
		$bg_color = martfury_get_option( 'not_found_bg' );

		if ( $bg_color ) {
			$inline_css .= '.error404 .site-content { background-color:' . esc_url( $bg_color ) . '; }';
		}
	}

	// Promotion
	if ( intval( martfury_get_option( 'promotion' ) ) ) {
		$promotion_bg_color = martfury_get_option( 'promotion_bg_color' );
		$promotion_bg_image = martfury_get_option( 'promotion_bg_image' );

		$promo_css = ! empty( $promotion_bg_color ) ? "background-color: {$promotion_bg_color};" : '';

		if ( ! empty( $promotion_bg_image ) ) {


			$promo_css .= "background-image: url({$promotion_bg_image});";

			$promo_bg_repeats = martfury_get_option( 'promotion_bg_repeats' );
			$promo_css        .= "background-repeat: {$promo_bg_repeats};";

			$promo_bg_vertical   = martfury_get_option( 'promotion_bg_vertical' );
			$promo_bg_horizontal = martfury_get_option( 'promotion_bg_horizontal' );
			$promo_css           .= "background-position: {$promo_bg_horizontal} {$promo_bg_vertical};";

			$promo_bg_attachments = martfury_get_option( 'promotion_bg_attachments' );
			$promo_css            .= "background-attachment: {$promo_bg_attachments};";

			$promo_bg_size = martfury_get_option( 'promotion_bg_size' );
			$promo_css     .= "background-size: {$promo_bg_size};";
		}

		if ( $promo_css ) {
			$inline_css .= '.top-promotion {' . $promo_css . '}';
		}

	}

	$color_scheme_option = martfury_get_option( 'color_scheme' );
	$color_scheme        = '';
	if ( $color_scheme_option == 'red' ) {
		$color_scheme = '#dd2400';
	} elseif ( $color_scheme_option == 'orange' ) {
		$color_scheme = '#fb7c00';
	} elseif ( $color_scheme_option == 'blue' ) {
		$color_scheme = '#0071df';
	} elseif ( $color_scheme_option == 'green' ) {
		$color_scheme = '#5fa30f';
	} elseif ( $color_scheme_option == 'black' ) {
		$color_scheme = '#000000';
	}
	if ( intval( martfury_get_option( 'custom_color_scheme' ) ) ) {
		$color_scheme = martfury_get_option( 'custom_color' );
	}
	// Don't do anything if the default color scheme is selected.
	if ( $color_scheme ) {
		$darken_color = $color_scheme;
		if ( class_exists( 'Kirki_Color' ) && method_exists( 'Kirki_Color', 'adjust_brightness' ) ) {
			$darken_color = Kirki_Color::adjust_brightness( $color_scheme, - 15 );
		}

		$inline_css .= martfury_get_color_scheme_css( $color_scheme, $darken_color );
	}

	$inline_css .= martfury_typography_css();

	$inline_css .= martfury_get_badges_color();

	$inline_css .= martfury_get_header_style();

	$inline_css .= martfury_get_footer_style();

	$inline_css .= martfury_get_progress_style();

	return apply_filters( 'martfury_header_inline_styles', $inline_css );
}

/**
 * @param $inline_css
 *
 * @return string
 */
function martfury_get_badges_color() {
	$inline_css = '';
	$hot_color  = martfury_get_option( 'hot_color' );
	if ( ! empty( $hot_color ) ) {
		$inline_css .= '.woocommerce .ribbons .ribbon.featured {background-color:' . $hot_color . '}';
	}

	$outofstock_color = martfury_get_option( 'outofstock_color' );
	if ( ! empty( $outofstock_color ) ) {
		$inline_css .= '.woocommerce .ribbons .ribbon.out-of-stock {background-color:' . $outofstock_color . '}';
	}

	$new_color = martfury_get_option( 'new_color' );
	if ( ! empty( $new_color ) ) {
		$inline_css .= '.woocommerce .ribbons .ribbon {background-color:' . $new_color . '}';
	}

	$sale_color = martfury_get_option( 'sale_color' );
	if ( ! empty( $sale_color ) ) {
		$inline_css .= '.woocommerce .ribbons .ribbon.onsale {background-color:' . $sale_color . '}';
	}

	return $inline_css;
}

/**
 * Custom functions for editor.
 *
 * @package Martfury
 */

if ( ! function_exists( 'martfury_typography_css' ) ) :
	/**
	 * Get typography CSS base on settings
	 *
	 * @since 1.1.6
	 */
	function martfury_typography_css() {
		$css        = '';
		$properties = array(
			'font-family'    => 'font-family',
			'font-size'      => 'font-size',
			'variant'        => 'font-weight',
			'line-height'    => 'line-height',
			'letter-spacing' => 'letter-spacing',
			'color'          => 'color',
			'text-transform' => 'text-transform',
		);

		$settings = array(
			'body_typo'          => 'body',
			'heading1_typo'      => 'h1',
			'heading2_typo'      => '.entry-content h2, .woocommerce div.product .woocommerce-tabs .panel h2',
			'heading3_typo'      => 'h3',
			'heading4_typo'      => '.entry-content h4, .woocommerce div.product .woocommerce-tabs .panel h4',
			'heading5_typo'      => '.entry-content h5, .woocommerce div.product .woocommerce-tabs .panel h5',
			'heading6_typo'      => '.entry-content h6, .woocommerce div.product .woocommerce-tabs .panel h6',
			'menu_typo'          => '.site-header .primary-nav > ul > li > a, .site-header .products-cats-menu .menu > li > a',
			'mega_menu_typo'     => '.site-header .menu .is-mega-menu .dropdown-submenu .menu-item-mega > a',
			'sub_menu_typo'      => '.site-header .menu li li a',
			'footer_typo'        => '.site-footer',
			'footer_widget_typo' => '.site-footer .footer-widgets .widget .widget-title',
		);

		foreach ( $settings as $setting => $selector ) {
			$typography = martfury_get_option( $setting );
			$default    = (array) martfury_get_option_default( $setting );
			$style      = '';

			foreach ( $properties as $key => $property ) {
				if ( isset( $typography[ $key ] ) && ! empty( $typography[ $key ] ) ) {
					if ( isset( $default[ $key ] ) && strtoupper( $default[ $key ] ) == strtoupper( $typography[ $key ] ) ) {
						continue;
					}
					$value = 'font-family' == $key ? '"' . rtrim( trim( $typography[ $key ] ), ',' ) . '"' : $typography[ $key ];
					$value = 'variant' == $key ? str_replace( 'regular', '400', $value ) : $value;

					if ( $value ) {
						$style .= $property . ': ' . $value . ';';
					}
				}
			}

			if ( ! empty( $style ) ) {
				$css .= $selector . '{' . $style . '}';
			}
		}

		$css .= martfury_get_heading_typography_css();

		return $css;
	}
endif;

/**
 * Returns CSS for the typography.
 *
 *
 * @param array $body_typo Color scheme body typography.
 *
 * @return string typography CSS.
 */
function martfury_get_heading_typography_css() {

	$headings   = array(
		'h1' => 'heading1_typo',
		'h2' => 'heading2_typo',
		'h3' => 'heading3_typo',
		'h4' => 'heading4_typo',
		'h5' => 'heading5_typo',
		'h6' => 'heading6_typo',
	);
	$inline_css = '';
	foreach ( $headings as $heading ) {
		$keys = array_keys( $headings, $heading );
		if ( $keys ) {
			$inline_css .= martfury_get_heading_font( $keys[0], $heading );
		}
	}

	return $inline_css;

}

/**
 * Returns CSS for the typography.
 *
 *
 * @param array $body_typo Color scheme body typography.
 *
 * @return string typography CSS.
 */
function martfury_get_heading_font( $key, $heading ) {

	$inline_css   = '';
	$heading_typo = martfury_get_option( $heading );

	if ( $heading_typo ) {
		if ( isset( $heading_typo['font-family'] ) && strtolower( $heading_typo['font-family'] ) !== 'work sans' ) {
			$typo       = rtrim( trim( $heading_typo['font-family'] ), ',' );
			$inline_css .= $key . '{font-family:' . $typo . ', Arial, sans-serif}';

			if ( isset( $heading_typo['variant'] ) ) {
				$inline_css .= $key . '.vc_custom_heading{font-weight:' . $heading_typo['variant'] . '}';
			}
		}
	}

	if ( empty( $inline_css ) ) {
		return;
	}

	return <<<CSS
	{$inline_css}
CSS;
}

/**
 * @param $inline_css header
 *
 * @return string
 */
function martfury_get_header_style() {
	$inline_css = '';

	if ( is_page_template( 'template-coming-soon-page.php' ) ) {
		return $inline_css;
	}

	$custom_header = apply_filters( 'martfury_custom_header_skin', martfury_get_option( 'custom_header_skin' ) );
	if ( ! intval( $custom_header ) ) {
		return;
	}

	$bg_color = martfury_get_option( 'header_bg_color' );
	if ( ! empty( $bg_color ) ) {
		$inline_css .= '#site-header, #site-header .header-main, .sticky-header #site-header.minimized .mobile-menu {background-color:' . $bg_color . '}';
		$inline_css .= '.sticky-header .site-header.minimized .header-main{border-bottom: none}';
		$inline_css .= '#site-header .aws-container .aws-search-form{background-color: transparent}';
		$inline_css .= '#site-header .aws-container .aws-search-form .aws-search-field{background-color: #fff}';
	}

	$text_color = martfury_get_option( 'header_text_color' );
	if ( ! empty( $text_color ) ) {
		$inline_css .= '#site-header .extras-menu > li > a, #site-header .product-extra-search .hot-words li a,#site-header .header-logo .products-cats-menu .cats-menu-title,#site-header .header-logo .products-cats-menu .cats-menu-title .text,' .
		               '#site-header .menu-item-hotline .hotline-content,#site-header .extras-menu .menu-item-hotline .extra-icon, #site-header .extras-menu .menu-item-hotline .hotline-content label, #site-header .mobile-menu-row .mf-toggle-menu{color:' . $text_color . '}';
	}

	$hover_color = martfury_get_option( 'header_hover_color' );
	if ( ! empty( $hover_color ) ) {
		$inline_css .= '#site-header:not(.minimized) .product-extra-search .hot-words li a:hover,' .
		               '#site-header .header-bar a:hover,#site-header .primary-nav > ul > li > a:hover, #site-header .header-bar a:hover{color:' . $hover_color . '}';

	}

	$button_bg_color = martfury_get_option( 'search_button_bg_color' );
	if ( ! empty( $button_bg_color ) ) {
		$inline_css .= '#site-header .product-extra-search .search-submit, #site-header .extras-menu > li > a .mini-item-counter{background-color:' . $button_bg_color . '}';

	}

	$button_color = martfury_get_option( 'search_button_text_color' );
	if ( ! empty( $button_color ) ) {
		$inline_css .= '#site-header .product-extra-search .search-submit, #site-header .extras-menu > li > a .mini-item-counter{color:' . $button_color . '}';

	}

	$topbar_bg_color = martfury_get_option( 'topbar_bg_color' );
	if ( ! empty( $topbar_bg_color ) ) {
		$inline_css .= '#topbar{background-color:' . $topbar_bg_color . '}';
		$inline_css .= '.site-header .topbar{background-color:transparent}';
	}

	$topbar_border_color = martfury_get_option( 'topbar_border_color' );
	if ( ! empty( $topbar_border_color ) ) {
		$inline_css .= '#topbar .widget:after {background-color:' . $topbar_border_color . '}';
	}

	$topbar_text_color = martfury_get_option( 'topbar_text_color' );
	if ( ! empty( $topbar_text_color ) ) {
		$inline_css .= '#topbar, #topbar a, #topbar #lang_sel > ul > li > a, #topbar .mf-currency-widget .current:after, #topbar  .lang_sel > ul > li > a:after, #topbar  #lang_sel > ul > li > a:after {color:' . $topbar_text_color . '}';
	}

	$topbar_hover_color = martfury_get_option( 'topbar_hover_color' );
	if ( ! empty( $topbar_hover_color ) ) {
		$inline_css .= '#topbar a:hover, #topbar .mf-currency-widget .current:hover, #topbar #lang_sel > ul > li > a:hover{color:' . $topbar_hover_color . '}';

	}

	$deparment_border_color = martfury_get_option( 'shop_department_border_color' );
	if ( ! empty( $deparment_border_color ) ) {
		$inline_css .= '.header-layout-3 #site-header .products-cats-menu:before, .header-layout-1 #site-header .products-cats-menu:before{background-color:' . $deparment_border_color . '}';
	}

	$menubar_bg_color = martfury_get_option( 'menu_bar_bg_color' );
	if ( ! empty( $menubar_bg_color ) ) {
		$inline_css .= '#site-header .main-menu{background-color:' . $menubar_bg_color . '}';
	}

	$menubar_border_color = martfury_get_option( 'menu_bar_border_color' );
	if ( ! empty( $menubar_border_color ) ) {
		$inline_css .= '#site-header .main-menu {border-color:' . $menubar_border_color . '; border-bottom: none}';
		$inline_css .= '#site-header .header-bar .widget:after {background-color:' . $menubar_border_color . '}';
	}

	$menubar_text_color = martfury_get_option( 'menu_bar_text_color' );
	if ( ! empty( $menubar_text_color ) ) {
		$inline_css .= '#site-header .header-bar a, #site-header .recently-viewed .recently-title,#site-header:not(.minimized) .main-menu .products-cats-menu .cats-menu-title .text, #site-header:not(.minimized) .main-menu .products-cats-menu .cats-menu-title, #site-header .main-menu .primary-nav > ul > li > a, #site-header .main-menu .header-bar,' .
		               '#site-header .header-bar #lang_sel  > ul > li > a, #site-header .header-bar .lang_sel > ul > li > a, #site-header .header-bar #lang_sel > ul > li > a:after, #site-header .header-bar .lang_sel > ul > li > a:after, #site-header .header-bar .mf-currency-widget .current:after{color:' . $menubar_text_color . '}';
		$inline_css .= '#site-header .header-bar .mf-currency-widget ul li a, #site-header .header-bar #lang_sel ul ul li a {color: #666}';
	}

	$menubar_hover_color = martfury_get_option( 'menu_bar_hover_color' );
	if ( ! empty( $menubar_hover_color ) ) {
		$inline_css .= '#site-header .header-bar a:hover,#site-header .primary-nav > ul > li:hover > a, #site-header .header-bar #lang_sel  > ul > li > a:hover, #site-header .header-bar .lang_sel > ul > li > a:hover, #site-header .header-bar #lang_sel > ul > li > a:hover:after, #site-header .header-bar .lang_sel > ul > li > a:hover:after, #site-header .header-bar .mf-currency-widget .current:hover,#site-header .header-bar .mf-currency-widget .current:hover:after{color:' . $menubar_hover_color . '}';

	}

	$menubar_active_color = martfury_get_option( 'menu_bar_active_color' );
	if ( ! empty( $menubar_active_color ) ) {
		$inline_css .= '#site-header .primary-nav > ul > li.current-menu-parent > a, #site-header .primary-nav > ul > li.current-menu-item > a, #site-header .primary-nav > ul > li.current-menu-ancestor > a{color:' . $menubar_active_color . '}';

	}

	$header_bottom_bg    = martfury_get_option( 'header_bottom_bg' );
	$header_bottom_style = '';
	if ( ! empty( $header_bottom_bg ) ) {
		$header_bottom_style .= 'background-image:url(' . $header_bottom_bg . ');';

		if ( $header_bottom_height = intval( martfury_get_option( 'header_bottom_height' ) ) ) {
			$header_bottom_style .= sprintf( 'height:%spx', $header_bottom_height );
			$inline_css          .= '.page-header .page-breadcrumbs{padding-top:' . $header_bottom_height . 'px}';
		}
	}

	if ( ! empty( $header_bottom_style ) ) {
		$inline_css .= '.site-header .header-bottom-feature{' . $header_bottom_style . '}';

	}

	return $inline_css;
}

/**
 * @param $inline_css footer
 *
 * @return string
 */
function martfury_get_footer_style() {
	$inline_css      = '';
	$cat_panel_width = intval( martfury_get_option( 'navigation_cat_panel_mobile' ) );
	if ( ! empty( $cat_panel_width ) ) {
		$inline_css .= '.mf-els-modal-mobile {width:' . $cat_panel_width . '%;left:-' . $cat_panel_width . '%}';
	}

	if ( martfury_get_option( 'footer_skin' ) != 'custom' ) {
		return $inline_css;
	};

	$bg_color = martfury_get_option( 'footer_bg_color' );
	if ( ! empty( $bg_color ) ) {
		$inline_css .= '.site-footer .footer-layout {background-color:' . $bg_color . '}';
	}

	$border_color = martfury_get_option( 'footer_border_color' );
	if ( ! empty( $border_color ) ) {
		$inline_css .= '.site-footer .footer-newsletter, .site-footer .footer-info, .site-footer .footer-widgets, .site-footer .footer-links {border-color:' . $border_color . '}';
		$inline_css .= '.site-footer .footer-info .info-item-sep:after {background-color:' . $border_color . '}';
	}

	$heading_color = martfury_get_option( 'footer_heading_color' );
	if ( ! empty( $heading_color ) ) {
		$inline_css .= '.site-footer h1, .site-footer h2, .site-footer h3, .site-footer h4, .site-footer h5, .site-footer h6, .site-footer .widget .widget-title {color:' . $heading_color . '}';
	}

	$text_color = martfury_get_option( 'footer_text_color' );
	if ( ! empty( $text_color ) ) {
		$inline_css .= '.site-footer, .site-footer .footer-widgets .widget ul li a, .site-footer .footer-copyright,' .
		               '.site-footer .footer-links .widget_nav_menu ul li a, .site-footer .footer-payments .text {color:' . $text_color . '}';
	}

	$hover_color = martfury_get_option( 'footer_hover_color' );
	if ( ! empty( $hover_color ) ) {
		$inline_css .= '.site-footer .footer-widgets .widget ul li a:hover,' .
		               '.site-footer .footer-links .widget_nav_menu ul li a:hover {color:' . $hover_color . '}';

		$inline_css .= '.site-footer .footer-widgets .widget ul li a:before, .site-footer .footer-links .widget_nav_menu ul li a:before{background-color:' . $hover_color . '}';
	}


	return $inline_css;
}

/**
 * @param $inline_css footer
 *
 * @return string
 */
function martfury_get_progress_style() {
	$inline_css = '';

	$bg_color = martfury_get_option( 'custom_preloader_progress' );
	if ( ! empty( $bg_color ) ) {
		$inline_css .= '#nprogress .bar {background-color:' . $bg_color . '}';
	}

	return $inline_css;
}

/**
 * Display the site header
 *
 * @since 1.0.0
 */
function martfury_show_header() {
	if ( is_page_template( 'template-coming-soon-page.php' ) ) {
		if ( intval( martfury_get_option( 'show_coming_soon_logo' ) ) ) {
			get_template_part( 'template-parts/logo' );
		}
	} else {
		$header_layout = martfury_get_option( 'header_layout' );
		$header_layout = $header_layout ? $header_layout : 1;
		$header_layout = $header_layout == 5 ? 2 : $header_layout;
		$header_layout = $header_layout == 7 ? 3 : $header_layout;
		get_template_part( 'template-parts/headers/layout', $header_layout );
	}
}

add_action( 'martfury_header', 'martfury_show_header' );

/**
 * Display the site header
 *
 * @since 1.0.0
 */
function martfury_header_bottom_feature() {
	$header_bottom_bg = martfury_get_option( 'header_bottom_bg' );

	if ( empty( $header_bottom_bg ) ) {
		return;
	}

	echo '<div class="header-bottom-feature"></div>';
}

add_action( 'martfury_header', 'martfury_header_bottom_feature' );

/**
 * Display the top bar
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'martfury_show_topbar' ) ) :
	function martfury_show_topbar() {

		$topbar        = apply_filters( 'martfury_get_topbar', martfury_get_option( 'topbar' ) );
		$topbar_mobile = apply_filters( 'martfury_get_topbar', martfury_get_option( 'topbar_mobile' ) );
		if ( ! intval( $topbar ) && ! intval( $topbar_mobile ) ) {
			return;
		}

		if ( is_page_template( 'template-coming-soon-page.php' ) ) {
			return;
		}

		get_template_part( 'template-parts/headers/topbar' );
	}
endif;

add_action( 'martfury_before_header', 'martfury_show_topbar', 10 );

/**
 * Display promotion section at the top of site
 *
 * @since 1.0
 */
if ( ! function_exists( 'martfury_promotion' ) ) :
	function martfury_promotion() {
		$promotion = apply_filters( 'martfury_get_promotion', martfury_get_option( 'promotion' ) );
		if ( ! intval( $promotion ) ) {
			return;
		}

		if ( is_404() || is_page_template( 'template-coming-soon-page.php' ) ) {
			return;
		}

		if ( intval( martfury_get_option( 'promotion_home_only' ) ) && ! is_front_page() ) {
			return;
		}

		$button      = '';
		$button_text = martfury_get_option( 'promotion_button_text' );
		$button_link = martfury_get_option( 'promotion_button_link' );
		if ( ! empty( $button_text ) && ! empty( $button_link ) ) {
			$button = sprintf( '<a class="link" href="%s">%s</a>', esc_url( $button_link ), esc_html( $button_text ) );
		}

		if ( intval( martfury_get_option( 'promotion_close' ) ) ) {
			$button .= '<span class="close"><i class="icon-cross2"></i></span>';
		}

		printf(
			'<div id="top-promotion" class="top-promotion promotion style-%s">
				<div class="%s">
					<div class="promotion-content">
						<div class="promo-inner">
						%s
						</div>
						<div class="promo-link">
						%s
						</div>
					</div>
				</div>
			</div>',
			esc_attr( martfury_get_option( 'promotion_style' ) ),
			martfury_header_container_classes(),
			do_shortcode( wp_kses( martfury_get_option( 'promotion_content' ), wp_kses_allowed_html( 'post' ) ) ),
			$button
		);
	}
endif;
add_action( 'martfury_before_header', 'martfury_promotion', 5 );

/**
 * Add before unload
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'martfury_preloader' ) ) :
	function martfury_preloader() {

		if ( ! intval( martfury_get_option( 'preloader' ) ) ) {
			return;
		}

		?>
        <div id="martfury-preloader" class="martfury-preloader">
        </div>
		<?php
	}
endif;

add_action( 'martfury_before_header', 'martfury_preloader' );