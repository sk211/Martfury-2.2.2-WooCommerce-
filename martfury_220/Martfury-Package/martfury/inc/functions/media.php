<?php
/**
 * Custom functions for images, audio, videos.
 *
 * @package Martfury
 */


/**
 * Register fonts
 *
 * @since  1.0.0
 *
 * @return string
 */
function martfury_fonts_url() {
	$fonts_url = '';

	/* Translators: If there are characters in your language that are not
	* supported by Montserrat, translate this to 'off'. Do not translate
	* into your own language.
	*/

	$fonts = martfury_get_option( 'font_families_typo' );

	if ( 'off' !== _x( 'on', 'WorkSans font: on or off', 'martfury' ) && ( is_array( $fonts ) && in_array( 'worksans', $fonts ) ) ) {
		$font_families[] = 'Work Sans:300,400,500,600,700';
	}

	if ( 'off' !== _x( 'on', 'Libre-Baskerville font: on or off', 'martfury' ) && ( is_array( $fonts ) && in_array( 'libre_baskerville', $fonts ) ) ) {
		$font_families[] = 'Libre Baskerville:400,700';
	}

	if ( ! empty( $font_families ) ) {
		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

		return esc_url_raw( $fonts_url );

	}

	return $fonts_url;

}


/**
 * @param array $params
 *
 * @since 4.2
 * @return array|bool
 */
function martfury_get_image_by_size( $params = array() ) {
	$params = array_merge( array(
		'post_id'    => null,
		'attach_id'  => null,
		'thumb_size' => 'thumbnail',
		'class'      => '',
	), $params );

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

	if ( is_string( $thumb_size ) && ( in_array( $thumb_size, array(
			'thumbnail',
			'thumb',
			'medium',
			'large',
			'full',
		) ) )
	) {
		$thumbnail = martfury_get_image_html( $attach_id, $thumb_size, $thumb_class . 'attachment-' . $thumb_size );

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
				$p_img = wpb_resize( $attach_id, null, $thumb_size[0], $thumb_size[1], true );

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


						if ( martfury_get_option( 'lazyload' ) ) {
							$attributes['src']           = get_template_directory_uri() . '/images/transparent.png';
							$attributes['class']         .= ' lazy';
							$attributes['data-original'] = $p_img['url'];
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
		$thumbnail = martfury_get_image_html( $attach_id, 'full' );
	}

	return $thumbnail;
}