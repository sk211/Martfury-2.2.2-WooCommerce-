<?php
/**
 * Custom functions for footer.
 *
 * @package Martfury
 */


/**
 * Display widget in footer
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'martfury_footer_widgets' ) ) :
	function martfury_footer_widgets() {

		if ( ! martfury_footer_is_active_sidebar() ) {
			return;
		}

		$footer_widget_columns = martfury_get_option( 'footer_widget_columns' );
		$columns               = max( 1, absint( $footer_widget_columns ) );
		?>
        <div class="footer-widgets" id="footer-widgets">
			<?php
			for ( $i = 1; $i <= $columns; $i ++ ) :
				if ( is_active_sidebar( "footer-sidebar-$i" ) ) {
					?>
                    <div class="footer-sidebar footer-<?php echo esc_attr( $i ) ?>">
						<?php dynamic_sidebar( "footer-sidebar-$i" ); ?>
                    </div>
				<?php } endfor;

			?>
        </div>
		<?php
	}
endif;

/**
 * Check active widget in footer
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'martfury_footer_is_active_sidebar' ) ) :
	function martfury_footer_is_active_sidebar() {
		$footer_widgets = apply_filters( 'martfury_get_footer_widgets', martfury_get_option( 'footer_widgets' ) );

		if ( ! intval( $footer_widgets ) ) {
			return false;
		}

		$footer_widget_columns = martfury_get_option( 'footer_widget_columns' );
		$columns               = max( 1, absint( $footer_widget_columns ) );
		for ( $i = 1; $i <= $columns; $i ++ ) {
			if ( is_active_sidebar( "footer-sidebar-$i" ) ) {
				return true;
			}
		}

		return false;
	}
endif;

/**
 * Display widget in footer
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'martfury_footer_info' ) ) :
	function martfury_footer_info() {

		$footer_info = apply_filters( 'martfury_get_footer_info', martfury_get_option( 'footer_info' ) );

		if ( ! intval( $footer_info ) ) {
			return;
		}

		$output = array();

		$footer_info = martfury_get_option( 'footer_info_list' );
		if ( $footer_info ) {

			foreach ( $footer_info as $info ) {
				$output[] = '<div class="info-item">';
				if ( isset( $info['icon'] ) && $info['icon'] ) {
					$output[] = sprintf( '<div class="info-thumb">%s</div>', $info['icon'] );
				}

				$output[] = '<div class="info-content">';
				if ( isset( $info['title'] ) && $info['title'] ) {
					$output[] = sprintf( '<h3>%s</h3>', esc_html( $info['title'] ) );
				}
				if ( isset( $info['desc'] ) && $info['desc'] ) {
					$output[] = sprintf( '<p>%s</p>', esc_html( $info['desc'] ) );
				}
				$output[] = '</div>';
				$output[] = '</div>';
				$output[] = '<div class="info-item-sep"></div>';

			}
		}

		echo sprintf( '<div class="footer-info">%s</div>', implode( ' ', $output ) );
	}
endif;


/**
 * Display contact form in footer
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'martfury_footer_newsletter' ) ) :
	function martfury_footer_newsletter() {

		$footer_newsletter = apply_filters( 'martfury_get_footer_newsletter', martfury_get_option( 'footer_newsletter' ) );

		if ( ! intval( $footer_newsletter ) ) {
			return;
		}

		$form = martfury_get_option( 'footer_newsletter_form' );
		$text = martfury_get_option( 'footer_newsletter_text' );
		printf(
			'<div class="footer-newsletter">' .
			'<div class="%s">' .
			'<div class="row">' .
			'<div class="col-lg-5 col-md-12 col-sm-12 col-xs-12 col-newsletter-content">' .
			'<div class="newsletter-content">' .
			'%s' .
			'</div>' .
			'</div>' .
			'<div class="col-lg-7 col-md-12 col-sm-12 col-xs-12">' .
			'<div class="newsletter-form">' .
			'%s' .
			'</div>' .
			'</div>' .
			'</div>' .
			'</div>' .
			'</div>',
			martfury_footer_container_classes(),
			wp_kses( $text, wp_kses_allowed_html( 'post' ) ),
			do_shortcode( wp_kses( $form, wp_kses_allowed_html( 'post' ) ) )
		);

	}
endif;

/**
 * Display links in footer
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'martfury_footer_links' ) ) :
	function martfury_footer_links() {
		$footer_links = apply_filters( 'martfury_get_footer_links', martfury_get_option( 'footer_links' ) );

		if ( ! intval( $footer_links ) ) {
			return;
		}

		if ( is_active_sidebar( 'footer-links' ) ) {
			?>
            <div class="footer-links" id="footer-links">
				<?php dynamic_sidebar( 'footer-links' ); ?>
            </div>
		<?php }
	}
endif;

/**
 * Display copyright in footer
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'martfury_footer_copyright' ) ) :
	function martfury_footer_copyright() {
		$footer_copyright = martfury_get_option( 'footer_copyright' );
		$footer_copyright = apply_filters( 'martfury_footer_copyright', $footer_copyright );
		if ( $footer_copyright ) {
			echo sprintf( '<div class="footer-copyright">%s</div>', do_shortcode( wp_kses( $footer_copyright, wp_kses_allowed_html( 'post' ) ) ) );
		}
	}
endif;

/**
 * Display payment  in footer
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'martfury_footer_payments' ) ) :
	function martfury_footer_payments() {

		$output = array();

		$text = martfury_get_option( 'footer_payment_text' );

		if ( $text ) {
			$output[] = sprintf( '<div class="text">%s</div>', esc_html( $text ) );
		}

		$images = martfury_get_option( 'footer_payment_images' );
		if ( $images ) {

			$output[] = '<ul class="payments">';
			foreach ( $images as $image ) {

				if ( ! isset( $image['image'] ) && ! $image['image'] ) {
					continue;
				}

				$image_id = $image['image'];

				$img = wp_get_attachment_image( $image_id, 'full' );
				if ( $img ) {
					$output[] = sprintf( '<li>%s</li>', $img );
				}
			}
			$output[] = '</ul>';
		}

		if ( $output ) {
			printf( '<div class="footer-payments">%s</div>', implode( ' ', $output ) );
		}
	}
endif;