<?php

/**
 * Hooks for template mobile
 *
 * @package Martfury
 */
class Martfury_Mobile {

	function __construct() {

		if ( ! intval( martfury_is_mobile() ) ) {
			return;
		}

		if ( ! intval( martfury_get_option( 'enable_mobile_version' ) ) ) {
			return;
		}

		add_filter( 'pre_option_page_on_front', array( $this, 'homepage_mobile_init' ) );

		remove_action( 'martfury_header', 'martfury_show_header' );
		add_action( 'martfury_header', array( $this, 'header_mobile' ) );

		add_filter( 'martfury_site_logo', array( $this, 'site_logo' ) );

		add_filter( 'body_class', array( $this, 'body_classes' ) );

		remove_action( 'wp_footer', 'martfury_off_canvas_mobile_menu' );

		add_action( 'wp_footer', array( $this, 'mobile_modal_popup' ) );

		add_action( 'wp_footer', array( $this, 'navigation_mobile' ) );

		add_action( 'wp_footer', array( $this, 'mobile_catalog_sorting_popup' ) );

		add_filter( 'martfury_get_footer_widgets', array( $this, 'mobile_get_footer_widgets' ) );
		add_filter( 'martfury_get_footer_info', array( $this, 'mobile_get_footer_info' ) );
		add_filter( 'martfury_get_footer_newsletter', array( $this, 'mobile_get_footer_newsletter' ) );
		add_filter( 'martfury_get_footer_links', array( $this, 'mobile_get_footer_links' ) );

		add_filter( 'martfury_footer_recently_viewed', array( $this, 'footer_recently_viewed' ) );
		add_filter( 'martfury_footer_recently_viewed_els', array( $this, 'footer_recently_viewed_els' ) );

		add_filter( 'martfury_get_topbar', array( $this, 'mobile_get_topbar' ) );
		add_filter( 'martfury_get_menu_extras', array( $this, 'mobile_get_menu_extras' ) );

		add_filter( 'martfury_get_catalog_layout', array( $this, 'mobile_get_catalog_layout' ) );

		add_filter( 'martfury_catalog_toolbar_elements', array( $this, 'mobile_catalog_toolbar_elements' ) );

		add_action( 'wp_footer', array( $this, 'catalog_close_sidebar' ) );

		add_filter( 'martfury_catalog_variation_images', array( $this, 'catalog_variation_images' ) );

		add_action( 'martfury_sticky_product_info', array( $this, 'sticky_product_info' ) );

		add_filter( 'martfury_custom_header_skin', '__return_false' );

		add_filter( 'martfury_get_promotion', '__return_false' );

		add_filter( 'martfury_product_tabs_layout', array( $this, 'product_tabs_layout' ) );

		add_filter( 'martfury_get_product_layout', array( $this, 'mobile_get_product_layout' ) );

		add_filter( 'martfury_site_layout', array( $this, 'mobile_site_layout' ) );

		add_filter( 'martfury_header_inline_styles', array( $this, 'header_inline_styles' ) );

		add_action( 'martfury_header_mobile_title', array( $this, 'header_mobile_title' ) );

//		if( martfury_is_catalog() ) {
//			remove_action( 'martfury_after_header', 'martfury_page_header' );
//		}

		add_action( 'woocommerce_before_cart_table', array( $this, 'cart_table_title' ) );

		add_filter( 'martfury_logo_width', array( $this, 'logo_width' ) );

		add_filter( 'martfury_logo_height', array( $this, 'logo_height' ) );

		add_filter( 'martfury_logo_margins', array( $this, 'logo_margins' ) );

		add_filter( 'martfury_get_sticky_header', array( $this, 'get_sticky_header' ) );

		add_filter( 'martfury_catalog_filter_mobile', '__return_false' );

		add_filter( 'martfury_get_page_header', array( $this, 'get_page_header' ) );

		add_filter( 'martfury_single_post_style', array( $this, 'single_post_style' ) );

	}


	/**
	 * Display homepage mobile
	 *
	 * @since 1.0.0
	 *
	 *  return string
	 */

	function homepage_mobile_init( $value ) {
		$homepage = martfury_get_option( 'homepage_mobile' );
		$value    = ! empty( $homepage ) ? $homepage : $value;


		return $value;
	}

	function body_classes( $classes ) {
		$classes[] = 'mobile-version';

		if ( intval( martfury_get_option( 'catalog_featured_icons_mobile' ) ) ) {
			$classes[] = 'show-featured-icons';
		}

		if ( is_singular( 'product' ) ) {
			if ( intval( martfury_get_option( 'sticky_header_mobile' ) ) ) {
				$classes[] = 'sticky-header';
			}

			if ( intval( martfury_get_option( 'product_add_to_cart_fixed_mobile' ) ) ) {
				$classes[] = 'mf-add-to-cart-fixed';
			}
		}

		if ( intval( martfury_get_option( 'sticky_header_mobile' ) ) &&
		     martfury_get_option( 'sticky_header_type_mobile' ) == 'header_bottom' &&
		     martfury_is_homepage()
		) {
			$classes[] = 'sticky-header-bottom';
		}

		if ( martfury_get_option( 'navigation_cart_behaviour' ) == 'panel' ) {
			$classes[] = 'cart-panel-mobile';
		}

		if ( intval( martfury_get_option( 'navigation_mobile' ) ) && ! empty( martfury_get_option( 'navigation_els_mobile' ) ) ) {
			$classes[] = 'mobile-nav-enable';
		}

		if ( martfury_is_vendor_page() ) {
			$els = martfury_get_option( 'catalog_toolbar_els_12_mobile' );

			if ( ! empty( $els ) && ! in_array( 'filter', $els ) ) {
				$classes[] = 'vendor-store-not-filter';
			}
		}

		return $classes;
	}

	/**
	 * Display header mobile
	 *
	 * @since 1.0.0
	 *
	 *  return string
	 */

	function header_mobile() {

		if ( is_page_template( 'template-coming-soon-page.php' ) ) {
			get_template_part( 'template-parts/logo' );

			return;
		}

		$layout = ( martfury_is_homepage() || is_front_page() ) ? 'v1' : martfury_get_option( 'inner_page_header_layout' );
		get_template_part( 'template-parts/mobile/header', $layout );
	}

	function site_logo( $logo ) {
		$logo_mobile = martfury_get_option( 'logo_mobile' );
		$logo        = empty( $logo_mobile ) ? $logo : $logo_mobile;

		return $logo;
	}

	/**
	 * Display navigation mobile
	 *
	 *
	 * @since 1.0.0
	 *
	 *  return string
	 */
	function navigation_mobile() {

		if ( is_page_template( 'template-coming-soon-page.php' ) ) {
			return;
		}

		if ( ! intval( martfury_get_option( 'navigation_mobile' ) ) ) {
			return;
		}

		if ( function_exists( 'wcmp_vendor_dashboard_page_id' ) ) {

			if ( is_page( wcmp_vendor_dashboard_page_id() ) ) {
				return;
			}
		}

		get_template_part( 'template-parts/mobile/navigation' );
	}

	function mobile_modal_popup() {

		if ( function_exists( 'wcmp_vendor_dashboard_page_id' ) ) {

			if ( is_page( wcmp_vendor_dashboard_page_id() ) ) {
				return;
			}
		}

		$els = martfury_get_option( 'navigation_els_mobile' );

		$extra_menus = martfury_get_option( 'menu_extras_mobile' );

		if ( empty( $els ) ) {
			return;
		}

		$search_navi = in_array( 'search', $els ) ? true : false;
		$cat_navi    = in_array( 'cat', $els ) ? true : false;
		$cart_navi   = in_array( 'cart', $els ) ? true : false;
		$cat_header  = in_array( 'category', $extra_menus ) ? true : false;

		if ( martfury_get_option( 'inner_page_header_layout' ) == 'v2' ) {
			$cat_header = ( martfury_is_homepage() || is_front_page() ) ? $cat_header : false;
		}

		if ( ! $cart_navi ) {
			$cart_navi = in_array( 'cart', $extra_menus ) ? true : false;
		}


		echo sprintf( '<div class="mf-els-modal-mobile" id="mf-els-modal-mobile">' );

		if ( $search_navi ) {
			get_template_part( 'template-parts/mobile/search' );
		}

		if ( $cat_header ) {
			martfury_off_canvas_mobile_menu();
		}

		if ( $cat_navi ) {
			$this->mobile_category_menu();
		}

		if ( $cart_navi ) {
			get_template_part( 'template-parts/mobile/cart' );
		}

		echo '</div>';
	}

	function mobile_get_footer_widgets() {
		return martfury_get_option( 'footer_widgets_mobile' );
	}

	function mobile_get_footer_newsletter() {
		return martfury_get_option( 'footer_newsletter_mobile' );
	}

	function mobile_get_footer_links() {
		return martfury_get_option( 'footer_links_mobile' );
	}

	function mobile_get_footer_info() {
		return martfury_get_option( 'footer_info_mobile' );
	}

	function footer_recently_viewed() {
		return martfury_get_option( 'footer_recently_viewed_mobile' );
	}

	function footer_recently_viewed_els() {
		return martfury_get_option( 'footer_recently_viewed_els_mobile' );
	}

	function mobile_get_topbar() {
		return martfury_get_option( 'topbar_mobile' );
	}

	function mobile_get_menu_extras() {
		$els = martfury_get_option( 'menu_extras_mobile' );

		return $els;
	}

	function mobile_get_catalog_layout() {
		return 10;
	}

	function mobile_catalog_toolbar_elements( $els ) {

		if ( martfury_is_vendor_page() ) {
			$els = martfury_get_option( 'catalog_toolbar_els_12_mobile' );
		} elseif ( martfury_is_catalog() ) {
			$els = martfury_get_option( 'catalog_toolbar_els_mobile' );
		}

		return $els;
	}

	function mobile_catalog_sorting_popup() {

		if ( ! martfury_is_catalog() ) {
			return;
		}

		$els = martfury_get_option( 'catalog_toolbar_els_mobile' );

		if ( empty( $els ) ) {
			return;
		}

		if ( ! in_array( 'sortby', $els ) ) {
			return;
		}

		echo '<div class="mf-catalog-sorting-mobile" id="mf-catalog-sorting-mobile">';

		woocommerce_catalog_ordering();

		echo '</div>';
	}

	function catalog_close_sidebar() {
		if ( ! martfury_is_catalog() && ! martfury_is_vendor_page() ) {
			return;
		}

		echo sprintf(
			'<div href="#" class="mf-catalog-close-sidebar" id="mf-catalog-close-sidebar"> <h2>%s</h2> <a class="close-sidebar"><i class="icon-cross"></i></a></div>', esc_html__( 'Filter Products', 'martfury' )
		);
	}

	function catalog_variation_images() {
		return martfury_get_option( 'catalog_variation_images_mobile' );
	}

	function product_tabs_layout() {
		return 3;
	}

	function mobile_get_product_layout() {
		return 6;
	}

	function mobile_site_layout( $layout ) {
		if ( is_singular( 'product' ) ) {
			$layout = 'full-content';
			if ( intval( martfury_get_option( 'product_sidebar_mobile' ) ) ) {
				$layout = 'content-sidebar';
			}

		}

		return $layout;
	}

	function header_mobile_title() {
		$post_id      = get_the_ID();
		$link         = home_url( '/' );
		$custom_title = '';
		if ( martfury_is_blog() ) {
			$custom_title = get_the_title( get_option( 'page_for_posts' ) );
		} elseif ( function_exists( 'wcfm_is_store_page' ) && wcfm_is_store_page() ) {
			$author       = get_user_by( 'id', get_query_var( 'author' ) );
			$custom_title = $author->display_name;
			if ( function_exists( 'wcfmmp_get_store' ) ) {
				$store_user   = wcfmmp_get_store( get_query_var( 'author' ) );
				$store_info   = $store_user->get_shop_info();
				$custom_title = $store_info['store_name'];
			}

		} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
			if ( martfury_is_wc_vendor_page() && class_exists( 'WCV_Vendors' ) ) {
				$vendor_shop  = urldecode( get_query_var( 'vendor_shop' ) );
				$vendor_id    = WCV_Vendors::get_vendor_id( $vendor_shop );
				$custom_title = WCV_Vendors::get_vendor_shop_name( $vendor_id );
			} else {
				$post_id = get_option( 'woocommerce_shop_page_id' );
			}

			if ( is_search() ) {
				$custom_title = get_search_query();
			}
		} elseif ( ( function_exists( 'is_product_tag' ) && is_product_tag() ) || is_tax( 'product_brand' ) ) {
			$link = get_permalink( get_option( 'woocommerce_shop_page_id' ) );
		} elseif ( function_exists( 'is_product_category' ) && is_product_category() ) {
			$current_term = get_queried_object();
			$custom_title = $current_term->name;
			$terms        = martfury_get_term_parents( get_queried_object_id(), $current_term->taxonomy );
			if ( $terms ) {
				$link = get_term_link( end( $terms ), 'product_cat' );
			} else {
				$link = get_permalink( get_option( 'woocommerce_shop_page_id' ) );
			}
		} elseif ( is_tax() || is_category() || is_tag() ) {
			$current_term = get_queried_object();
			$custom_title = $current_term->name;
		} elseif ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() ) ) {
			$shop_id = get_option( 'woocommerce_shop_page_id' );
			if ( $shop_id ) {
				$link = get_permalink( $shop_id );
			}
		} elseif ( is_singular( 'product' ) ) {
			$post_id = get_option( 'woocommerce_shop_page_id' );
			$terms   = wc_get_product_terms(
				get_the_ID(), 'product_cat', apply_filters(
					'woocommerce_product_categories_widget_product_terms_args', array(
						'orderby' => 'parent',
					)
				)
			);

			$shop_title = '';
			if ( $terms ) {
				$link       = get_term_link( end( $terms ), 'product_cat' );
				$shop_title = esc_html__( 'Category', 'martfury' );
			} else {
				$post_id = get_option( 'woocommerce_shop_page_id' );
				if ( $post_id ) {
					$link       = get_permalink( $post_id );
					$shop_title = get_the_title( $post_id );
				}
			}
			if ( $shop_title ) {
				$custom_title = sprintf( '<label>%s</label>', esc_html__( 'Back to', 'martfury' ) ) . ' ' . $shop_title;
			}
		} elseif ( is_singular( 'post' ) ) {
			$post_id = get_option( 'page_for_posts' );
			if ( $post_id ) {
				$custom_title = sprintf( '<label>%s</label>', esc_html__( 'Back to', 'martfury' ) ) . ' ' . get_the_title( $post_id );
				$link         = get_permalink( $post_id );
			}
		} elseif ( function_exists( 'dokan_is_store_page' ) && dokan_is_store_page() ) {
			$author       = get_user_by( 'id', get_query_var( 'author' ) );
			$shop_info    = get_user_meta( get_query_var( 'author' ), 'dokan_profile_settings', true );
			$custom_title = $author->display_name;
			if ( $shop_info && isset( $shop_info['store_name'] ) && $shop_info['store_name'] ) {
				$custom_title = $shop_info['store_name'];
			}
		} elseif ( is_author() ) {
			$custom_title = get_the_author();
		} elseif ( is_search() ) {
			$custom_title = get_search_query();
		} elseif ( is_404() ) {
			$custom_title = esc_html__( 'Not Found', 'martfury' );
		}
		$title = $custom_title ? $custom_title : get_the_title( $post_id );

		echo sprintf( '<a href="%s" class="header-go-back"><i class="icon-chevron-left"></i><span>%s</span></a>', esc_url( $link ), $title );

	}

	function cart_table_title() {
		echo sprintf( '<h2 class="cart-title">%s</h2>', esc_html__( 'Your Cart Items', 'martfury' ) );
	}

	function header_inline_styles( $inline_css ) {


		if ( is_page_template( 'template-coming-soon-page.php' ) ) {
			return $inline_css;
		}

		if ( ! intval( martfury_get_option( 'custom_header_skin_mobile' ) ) ) {
			return $inline_css;
		}

		if ( ! intval( martfury_get_option( 'custom_header_homepage_mobile' ) ) && martfury_is_homepage() ) {
			return $inline_css;
		}

		$bg_color = martfury_get_option( 'header_bg_color_mobile' );
		if ( ! empty( $bg_color ) ) {
			$inline_css .= '#site-header,#site-header .header-mobile-v1 .header-main {background-color:' . $bg_color . '}';
		}

		$text_color = martfury_get_option( 'header_text_color_mobile' );
		if ( ! empty( $text_color ) ) {
			$inline_css .= '#site-header .mobile-menu .site-header-category--mobile, #site-header .extras-menu > li > a, #site-header .header-main .header-title .header-go-back, #site-header .header-main .header-title h2{color:' . $text_color . '}';
		}

		$button_bg_color = martfury_get_option( 'search_button_bg_color_mobile' );
		if ( ! empty( $button_bg_color ) ) {
			$inline_css .= '#site-header .product-extra-search .search-submit, #site-header .extras-menu > li > a .mini-item-counter{background-color:' . $button_bg_color . '}';

			$inline_css .= '#site-header .product-extra-search .search-field{border: none }';

		}

		$button_color = martfury_get_option( 'search_button_text_color_mobile' );
		if ( ! empty( $button_color ) ) {
			$inline_css .= '#site-header .product-extra-search .search-submit, #site-header .extras-menu > li > a .mini-item-counter {color:' . $button_color . '}';

		}

		$topbar_bg_color = martfury_get_option( 'topbar_bg_color_mobile' );
		if ( ! empty( $topbar_bg_color ) ) {
			$inline_css .= '#topbar{background-color:' . $topbar_bg_color . '}';
		}

		$topbar_text_color = martfury_get_option( 'topbar_text_color_mobile' );
		if ( ! empty( $topbar_text_color ) ) {
			$inline_css .= '#topbar, #topbar a, #topbar #lang_sel > ul > li > a, #topbar .mf-currency-widget .current:after, #topbar  .lang_sel > ul > li > a:after, #topbar  #lang_sel > ul > li > a:after {color:' . $topbar_text_color . '}';
		}

		return $inline_css;
	}

	function logo_width() {
		return martfury_get_option( 'logo_mobile_width' );
	}

	function logo_height() {
		return martfury_get_option( 'logo_mobile_height' );
	}

	function logo_margins() {
		return martfury_get_option( 'logo_mobile_margins' );
	}

	function sticky_product_info() {
		return martfury_get_option( 'sticky_product_info_mobile' );
	}

	function get_sticky_header() {
		return martfury_get_option( 'sticky_header_mobile' );
	}

	function mobile_category_menu() {

		if ( martfury_is_vendor_dashboard() ) {
			return;
		}

		?>
        <div class="primary-mobile-nav mf-els-item" id="mf-category-mobile-nav">
            <div class="mobile-nav-content">
                <div class="mobile-nav-overlay"></div>
                <div class="mobile-nav-header">
					<?php
					$depart_menu = martfury_get_option( 'navigation_cat_panel_mobile_title' );
					$c_link      = martfury_get_option( 'navigation_cat_panel_mobile_link' );
					$depart_menu = empty( $depart_menu ) ? esc_html__( 'Shop By Departments', 'martfury' ) : $depart_menu;
					if ( ! empty( $c_link ) ) {
						$depart_menu = '<a href="' . esc_url( $c_link ) . '" class="text">' . $depart_menu . '</a>';
					}
					?>
                    <h2><?php echo wp_kses_post( $depart_menu ); ?></h2>
                    <a class="close-mobile-nav"><i class="icon-cross"></i></a>
                </div>

				<?php

				$location = '';
				if ( has_nav_menu( 'category_mobile' ) ) {
					$location = 'category_mobile';
				} elseif ( has_nav_menu( 'shop_department' ) ) {
					$location = 'shop_department';
				} elseif ( has_nav_menu( 'primary' ) ) {
					$location = 'primary';
				}

				if ( $location ) {
					$options = array(
						'theme_location' => $location,
						'container'      => false,
						'walker'         => new Martfury_Mobile_Walker()
					);

					wp_nav_menu( $options );
				}
				?>

            </div>
        </div>
		<?php
	}

	function get_page_header( $page_header ) {
		if ( is_singular( 'post' ) || is_page() ) {
			$key = array_search( 'breadcrumb', $page_header );
			if ( $key !== false ) {
				unset( $page_header[ $key ] );
			}
		} else {
			$page_header = false;

		}

		return $page_header;
	}

	function single_post_style( $layout ) {
		$layout = '3';

		return $layout;
	}
}


