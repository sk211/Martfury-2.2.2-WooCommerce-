<?php
/**
 * Hooks for template footer
 *
 * @package Martfury
 */


/**
 * Show footer
 */

if ( ! function_exists( 'martfury_show_footer' ) ) :
	function martfury_show_footer() {
		if ( is_page_template( 'template-coming-soon-page.php' ) ) {
			return;
		}

		$footer_layout = 1;
		get_template_part( 'template-parts/footers/layout', $footer_layout );
	}

endif;

add_action( 'martfury_footer', 'martfury_show_footer' );

/**
 * Display recently viewed products in footer
 *
 * @since 1.0.0
 *
 *  return string
 */
if ( ! function_exists( 'martfury_footer_recently_viewed' ) ) :
	function martfury_footer_recently_viewed() {

		if ( ! function_exists( 'is_woocommerce' ) ) {
			return;
		}

		$recently_viewed = apply_filters( 'martfury_footer_recently_viewed', martfury_get_option( 'footer_recently_viewed' ) );
		if ( ! intval( $recently_viewed ) ) {
			return;
		}

		if ( is_404() || is_page_template( 'template-coming-soon-page.php' ) ) {
			return;
		}

		$els = apply_filters( 'martfury_footer_recently_viewed_els', martfury_get_option( 'footer_recently_viewed_els' ) );
		if ( empty( $els ) ) {
			return;
		}

		if ( martfury_is_homepage() ) {
			if ( ! in_array( 'homepage', $els ) ) {
				return;
			}
		} elseif ( is_page() ) {
			if ( ! in_array( 'page', $els ) ) {
				return;
			}
		} elseif ( is_singular( 'post' ) ) {
			if ( ! in_array( 'post', $els ) ) {
				return;
			}
		} elseif ( martfury_is_blog() ) {
			if ( ! in_array( 'post', $els ) ) {
				return;
			}
		} elseif ( martfury_is_catalog() ) {
			if ( ! in_array( 'catalog', $els ) ) {
				return;
			}
		} elseif ( martfury_is_vendor_page() ) {
			if ( ! in_array( 'catalog', $els ) ) {
				return;
			}
		} elseif ( is_singular( 'product' ) ) {
			if ( ! in_array( 'single_product', $els ) ) {
				return;
			}
		} else {
			if ( ! in_array( 'other', $els ) ) {
				return;
			}
		}

		$title    = martfury_get_option( 'footer_recently_viewed_title' );
		$layout   = martfury_get_option( 'footer_recently_viewed_layout' );
		$columns  = 8;
		$rv_class = "";
		if ( martfury_footer_container_classes() == 'martfury-container' ) {
			$columns  = 11;
			$rv_class = 'rv-full-width';
		}
		if ( $layout == '1' ) {
			$pt       = intval( martfury_get_option( 'footer_recently_viewed_pt' ) );
			$pt_class = $pt ? ' no-padding-top' : '';
			$pt_class .= intval( martfury_get_option( 'footer_recently_viewed_no_bg' ) ) && martfury_is_homepage() ? ' no-background' : '';
			$pt_class .= ' ' . $rv_class;
			echo '<div class="mf-recently-products layout-1 footer-recently-viewed load-ajax' . esc_attr( $pt_class ) . '" data-columns = ' . $columns . ' id="footer-recently-viewed"><div class="mf-loading"></div></div>';
		} else {
			echo '<div class="footer-history-products" id="footer-history-products">';
			echo sprintf( '<h2 class="recently-title">%s</h2>', esc_html( $title ) );
			$atts['title'] = '';
			echo '<div class="mf-recently-products footer-recently-viewed ' . $rv_class . '" data-columns = ' . $columns . ' id="footer-recently-viewed"><div class="mf-loading"></div></div>';
			echo '</div>';
		}
	}
endif;

add_action( 'martfury_before_footer', 'martfury_footer_recently_viewed', 30 );

/**
 * Adds photoSwipe dialog element
 */
function martfury_product_images_lightbox() {
	if ( ! is_singular() ) {
		return;
	}

	if ( martfury_is_vendor_dashboard() ) {
		return;
	}

	if ( function_exists( 'wcmp_vendor_dashboard_page_id' ) ) {

		if ( is_page( wcmp_vendor_dashboard_page_id() ) ) {
			return;
		}
	}

	?>
    <div id="pswp" class="pswp" tabindex="-1" aria-hidden="true">

        <div class="pswp__bg"></div>

        <div class="pswp__scroll-wrap">

            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <div class="pswp__ui pswp__ui--hidden">

                <div class="pswp__top-bar">


                    <div class="pswp__counter"></div>

                    <button class="pswp__button pswp__button--close"
                            title="<?php esc_attr_e( 'Close (Esc)', 'martfury' ) ?>"></button>

                    <button class="pswp__button pswp__button--share"
                            title="<?php esc_attr_e( 'Share', 'martfury' ) ?>"></button>

                    <button class="pswp__button pswp__button--fs"
                            title="<?php esc_attr_e( 'Toggle fullscreen', 'martfury' ) ?>"></button>

                    <button class="pswp__button pswp__button--zoom"
                            title="<?php esc_attr_e( 'Zoom in/out', 'martfury' ) ?>"></button>

                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>

                <button class="pswp__button pswp__button--arrow--left"
                        title="<?php esc_attr_e( 'Previous (arrow left)', 'martfury' ) ?>">
                </button>

                <button class="pswp__button pswp__button--arrow--right"
                        title="<?php esc_attr_e( 'Next (arrow right)', 'martfury' ) ?>">
                </button>

                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>

            </div>

        </div>

    </div>
	<?php
}

add_action( 'wp_footer', 'martfury_product_images_lightbox' );

/**
 * Adds photoSwipe dialog element
 */
function martfury_product_images_degree_lightbox() {
	if ( ! is_singular( 'product' ) ) {
		return;
	}

	$images_dg = get_post_meta( get_the_ID(), 'product_360_view', true );
	if ( empty( $images_dg ) ) {
		return;
	}

	?>
    <div id="product-degree-pswp" class="product-degree-pswp">
        <div class="degree-pswp-close"><i class="icon-cross2"></i></div>
        <div class="degree-pswp-bg"></div>
        <div class="mf-product-gallery-degree">
            <div class="mf-gallery-degree-spinner"></div>
            <ul class="product-degree-images"></ul>
        </div>
    </div>
	<?php
}

add_action( 'wp_footer', 'martfury_product_images_degree_lightbox' );

/**
 * Adds quick view modal to footer
 */
if ( ! function_exists( 'martfury_quick_view_modal' ) ) :
	function martfury_quick_view_modal() {
		if ( is_page_template( 'template-coming-soon-page.php' ) || is_404() ) {
			return;
		}

		if ( function_exists( 'wcmp_vendor_dashboard_page_id' ) ) {

			if ( is_page( wcmp_vendor_dashboard_page_id() ) ) {
				return;
			}
		}
		?>

        <div id="mf-quick-view-modal" class="mf-quick-view-modal martfury-modal woocommerce" tabindex="-1">
            <div class="mf-modal-overlay"></div>
            <div class="modal-content">
                <a href="#" class="close-modal">
                    <i class="icon-cross"></i>
                </a>
                <div class="product-modal-content">
                </div>
            </div>
            <div class="mf-loading"></div>
        </div>

		<?php
	}

endif;

add_action( 'wp_footer', 'martfury_quick_view_modal' );


/**
 * Dispaly back to top
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'martfury_back_to_top' ) ) :
	function martfury_back_to_top() {

		if ( martfury_get_option( 'back_to_top' ) ) : ?>
            <a id="scroll-top" class="backtotop" href="#page-top">
                <i class="arrow_carrot_up_alt"></i>
            </a>
		<?php endif; ?>
		<?php
	}
endif;

add_action( 'wp_footer', 'martfury_back_to_top' );

/**
 * Add newsletter popup on the footer
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'martfury_newsletter_popup' ) ) :
	function martfury_newsletter_popup() {
		if ( ! martfury_get_option( 'newsletter_popup' ) ) {
			return;
		}

		if ( ! intval( martfury_get_option( 'newsletter_home_popup' ) ) ) {
			if ( martfury_is_homepage() || is_front_page() ) {
				return;
			}
		}

		if ( martfury_is_vendor_dashboard() ) {
			return;
		}

		$mf_newletter = '';
		if ( isset( $_COOKIE['mf_newletter'] ) ) {
			$mf_newletter = $_COOKIE['mf_newletter'];
		}

		if ( ! empty( $mf_newletter ) ) {
			return;
		}

		$output = array();

		if ( $desc = martfury_get_option( 'newsletter_content' ) ) {
			$output[] = sprintf( '<div class="n-desc">%s</div>', wp_kses( $desc, wp_kses_allowed_html( 'post' ) ) );
		}

		if ( $form = martfury_get_option( 'newsletter_form' ) ) {
			$output[] = sprintf( '<div class="n-form">%s</div>', do_shortcode( wp_kses( $form, wp_kses_allowed_html( 'post' ) ) ) );
		}

		$output[] = sprintf( '<a href="#" class="n-close">%s</a>', esc_html__( 'Don\'t show this popup again', 'martfury' ) );

		?>
        <div id="mf-newsletter-popup" class="martfury-modal mf-newsletter-popup" tabindex="-1" aria-hidden="true">
            <div class="mf-modal-overlay"></div>
            <div class="modal-content">
                <a href="#" class="close-modal">
                    <i class="icon-cross"></i>
                </a>
                <div class="newletter-content">
					<?php $image = martfury_get_option( 'newsletter_bg_image' );
					if ( $image ) {
						echo sprintf( '<div class="n-image" style="background-image:url(%s)"></div>', esc_url( $image ) );
					} ?>
                    <div class="nl-inner">
						<?php echo implode( '', $output ) ?>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}
endif;

add_action( 'wp_footer', 'martfury_newsletter_popup' );

/**
 * Add off mobile menu to footer
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'martfury_off_canvas_mobile_menu' ) ) :
	function martfury_off_canvas_mobile_menu() {

		if ( martfury_is_vendor_dashboard() ) {
			return;
		}

		if ( function_exists( 'wcmp_vendor_dashboard_page_id' ) ) {

			if ( is_page( wcmp_vendor_dashboard_page_id() ) ) {
				return;
			}
		}

		?>
        <div class="primary-mobile-nav mf-els-item" id="primary-mobile-nav">
            <div class="mobile-nav-content">
                <div class="mobile-nav-overlay"></div>
                <div class="mobile-nav-header">
					<?php
					$depart_menu = martfury_get_option( 'header_menu_panel_mobile_title' );
					$depart_menu = empty( $depart_menu ) ? esc_html__( 'Main Menu', 'martfury' ) : $depart_menu;
					?>
                    <h2><?php echo wp_kses_post( $depart_menu ); ?></h2>
                    <a class="close-mobile-nav"><i class="icon-cross"></i></a>
                </div>

				<?php

				$location = '';
				if ( has_nav_menu( 'mobile' ) ) {
					$location = 'mobile';
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
endif;

add_action( 'wp_footer', 'martfury_off_canvas_mobile_menu' );

/**
 * Add off canvas layer to footer
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'martfury_off_canvas_layer' ) ) :
	function martfury_off_canvas_layer() {
		?>
        <div id="mf-off-canvas-layer" class="martfury-off-canvas-layer"></div>
		<?php
	}
endif;

add_action( 'wp_footer', 'martfury_off_canvas_layer' );

/**
 * Add off mobile menu to footer
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'martfury_off_canvas_user_menu' ) ) :
	function martfury_off_canvas_user_menu() {

		if ( ! is_user_logged_in() ) {
			return;
		}

		if ( martfury_is_vendor_dashboard() ) {
			return;
		}

		if ( function_exists( 'wcmp_vendor_dashboard_page_id' ) ) {

			if ( is_page( wcmp_vendor_dashboard_page_id() ) ) {
				return;
			}
		}
		?>
        <div class="primary-user-nav" id="primary-user-nav">
            <div class="mobile-user-content">
                <div class="mobile-user-overlay"></div>
                <a href="#" class="close-canvas-mobile-panel">
					<span class="mf-nav-icon icon-cross2">
					</span>
                </a>
                <ul class="extra-account">
					<?php martfury_extra_account(); ?>
                </ul>
            </div>
        </div>
		<?php
	}
endif;

add_action( 'wp_footer', 'martfury_off_canvas_user_menu' );