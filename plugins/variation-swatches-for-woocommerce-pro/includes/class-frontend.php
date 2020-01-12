<?php

/**
 * Class TA_WC_Variation_Swatches_Frontend
 */
class TA_WC_Variation_Swatches_Frontend {
	/**
	 * The single instance of the class
	 *
	 * @var TA_WC_Variation_Swatches_Frontend
	 */
	protected static $instance = null;

	/**
	 * Main instance
	 *
	 * @return TA_WC_Variation_Swatches_Frontend
	 */
	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array(
			$this,
			'get_swatch_html',
		), 100, 2 );
		add_filter( 'tawcvs_swatch_html', array( $this, 'swatch_html' ), 5, 5 );
	}

	/**
	 * Enqueue scripts and stylesheets
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'tawcvs-frontend', plugins_url( 'assets/css/frontend.css', dirname( __FILE__ ) ), array(), '20171128' );
		wp_add_inline_style( 'tawcvs-frontend', $this->swatch_size_css() );

		if ( get_option( 'tawcvs_swatch_tooltip' ) === 'yes' ) {
			wp_enqueue_script( 'jquery-ui-tooltip' );
		}

		wp_enqueue_script( 'tawcvs-frontend', plugins_url( 'assets/js/frontend.js', dirname( __FILE__ ) ), array( 'jquery' ), '20171127', true );

		wp_localize_script( 'tawcvs-frontend', 'tawcvs', array(
			'tooltip' => get_option( 'tawcvs_swatch_tooltip' ),
		) );
	}

	/**
	 * Inline style of swatch size
	 */
	public function swatch_size_css() {
		$size = get_option( 'tawcvs_swatch_image_size', array(
			'width'  => '30',
			'height' => '30',
		) );

		$css = ".tawcvs-swatches .swatch { width: {$size['width']}px; height: {$size['height']}px; }";

		return apply_filters( 'tawcvs_swatch_size_css', $css );
	}

	/**
	 * Filter function to add swatches bellow the default selector
	 *
	 * @param $html
	 * @param $args
	 *
	 * @return string
	 */
	public function get_swatch_html( $html, $args ) {
		$swatch_types  = TA_WCVS()->types;
		$options       = $args['options'];
		$product       = $args['product'];
		$attribute     = taxonomy_exists( $args['attribute'] ) ? $args['attribute'] : sanitize_title( $args['attribute'] );
		$swatches      = '';
		$default_style = get_option( 'tawcvs_swatch_style', 'round' );
		$default_size  = get_option( 'tawcvs_swatch_image_size', array(
			'width'  => '30',
			'height' => '30',
		) );

		if ( empty( $product ) ) {
			return $html;
		}

		$swatches_settings = get_post_meta( $product->get_id(), 'tawcvs_swatches', true );

		if ( ! empty( $swatches_settings[ $attribute ] ) ) {
			$swatches_setting = $swatches_settings[ $attribute ];

			$swatch['default_size']  = $default_size;
			$swatch['default_style'] = $default_style;

			// Ensure use the correct default type of attribute
			if ( taxonomy_exists( $attribute ) ) {
				$attribute_taxonomy               = TA_WCVS()->get_tax_attribute( $attribute );
				$swatches_setting['default_type'] = $attribute_taxonomy->attribute_type;
			} else {
				$swatches_setting['default_type'] = 'select';
			}
		} else {
			if ( taxonomy_exists( $attribute ) ) {
				$attribute_taxonomy = TA_WCVS()->get_tax_attribute( $attribute );
				$swatches_setting   = array(
					'type'          => array_key_exists( $attribute_taxonomy->attribute_type, $swatch_types ) ? $attribute_taxonomy->attribute_type : 'default',
					'style'         => 'default',
					'size'          => 'default',
					'custom_size'   => $default_size,
					'default_type'  => 'select',
					'default_style' => $default_style,
					'default_size'  => $default_size,
					'swatches'      => array(),
				);
			} else {
				$swatches_setting = array(
					'type'          => 'default',
					'style'         => 'default',
					'size'          => 'default',
					'custom_size'   => $default_size,
					'default_type'  => 'select',
					'default_style' => $default_style,
					'default_size'  => $default_size,
					'swatches'      => array(),
				);
			}
		}

		$type = 'default' == $swatches_setting['type'] ? $swatches_setting['default_type'] : $swatches_setting['type'];

		if ( ! array_key_exists( $type, $swatch_types ) ) {
			return $html;
		}

		$class = 'variation-selector variation-select-' . $type;

		if ( empty( $options ) && ! empty( $attribute ) ) {
			$attributes = $product->get_variation_attributes();
			$options    = $attributes[ $attribute ];
		}

		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				// Get terms if this is a taxonomy - ordered. We need the names too.
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options ) ) {
						$swatches .= apply_filters( 'tawcvs_swatch_html', '', $term, $type, $args, $swatches_setting );
					}
				}
			} else {
				foreach ( $options as $option ) {
					$swatches .= apply_filters( 'tawcvs_swatch_html', '', $option, $type, $args, $swatches_setting );
				}
			}
		}

		if ( ! empty( $swatches ) ) {
			$class .= ' hidden';

			$swatches = '<div class="tawcvs-swatches" data-attribute_name="attribute_' . esc_attr( $attribute ) . '">' . $swatches . '</div>';
			$html     = '<div class="' . esc_attr( $class ) . '">' . $html . '</div>' . $swatches;
		}

		return $html;
	}

	/**
	 * Print HTML of a single swatch
	 *
	 * @param $html
	 * @param $term
	 * @param $type
	 * @param $args
	 * @param $settings
	 *
	 * @return string
	 */
	public function swatch_html( $html, $term, $type, $args, $settings ) {
		$value    = is_object( $term ) ? $term->slug : $term;
		$selected = sanitize_title( $args['selected'] ) == sanitize_title( $value ) ? 'selected' : '';
		$name     = is_object( $term ) ? $term->name : $term;
		$name     = apply_filters( 'woocommerce_variation_option_name', $name );
		$sharp    = 'default' == $settings['style'] ? get_option( 'tawcvs_swatch_style', 'round' ) : $settings['style'];
		$size     = 'custom' == $settings['size'] ? $settings['custom_size'] : array();
		$size_css = $size ? 'width:' . $size['width'] . 'px;height: ' . $size['height'] . 'px;line-height: ' . $size['height'] . 'px;' : '';

		switch ( $type ) {
			case 'color':
				$color = $this->get_swatch_property( $term, $settings );
				list( $r, $g, $b ) = sscanf( $color, "#%02x%02x%02x" );
				$html = sprintf(
					'<span class="swatch swatch-color swatch-%s %s %s" style="background-color:%s;color:%s;%s" title="%s" data-value="%s">%s</span>',
					esc_attr( $value ),
					$sharp,
					$selected,
					esc_attr( $color ),
					"rgba($r,$g,$b,0.5)",
					$size_css,
					esc_attr( $name ),
					esc_attr( $value ),
					esc_html( $name )
				);
				break;

			case 'image':
				$size  = ! empty( $size ) ? $size : get_option( 'tawcvs_swatch_image_size', array(
					'width'  => '30',
					'height' => '30',
				) );
				$size  = array_values( $size );
				$image = $this->get_swatch_property( $term, $settings );
				$image = $image ? $this->get_image( $image, $size ) : '';
				$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';

				$html = sprintf(
					'<span class="swatch swatch-image swatch-%s %s %s" title="%s" data-value="%s" %s><img src="%s" alt="%s">%s</span>',
					esc_attr( $value ),
					$sharp,
					$selected,
					esc_attr( $name ),
					esc_attr( $value ),
					$size_css ? 'style="' . esc_attr( $size_css ) . '"' : '',
					esc_url( $image ),
					esc_attr( $name ),
					esc_attr( $name )
				);
				break;

			case 'label':
				$label = $this->get_swatch_property( $term, $settings );
				$label = $label ? $label : $name;
				$html  = sprintf(
					'<span class="swatch swatch-label swatch-%s %s %s" title="%s" data-value="%s" %s>%s</span>',
					esc_attr( $value ),
					$sharp,
					$selected,
					esc_attr( $name ),
					esc_attr( $value ),
					$size_css ? 'style="' . esc_attr( $size_css ) . '"' : '',
					esc_html( $label )
				);
				break;
		}

		return $html;
	}

	/**
	 * Get swatch property
	 *
	 * @param string|object $term
	 * @param array         $settings
	 *
	 * @return string
	 */
	public function get_swatch_property( $term, $settings ) {
		$key   = is_object( $term ) ? $term->term_id : sanitize_title( $term );
		$type  = $settings['type'];
		$value = '';

		if ( 'default' == $type ) {
			$type = $settings['default_type'];

			if ( is_object( $term ) ) {
				$value = get_term_meta( $term->term_id, $type, true );
			}
		} elseif ( 'select' != $type ) {
			$value = isset( $settings['swatches'][ $key ] ) && isset( $settings['swatches'][ $key ][ $type ] ) ? $settings['swatches'][ $key ][ $type ] : '';

			if ( empty( $value ) && is_object( $term ) ) {
				$value = get_term_meta( $term->term_id, $type, true );
			}
		}

		return $value;
	}

	/**
	 * Get the correct image by size.
	 * Crop a new image if the correct image is not exists.
	 *
	 * @param int   $attachment_id
	 * @param array $size
	 *
	 * @return array|bool
	 */
	public function get_image( $attachment_id, $size ) {
		if ( is_string( $size ) ) {
			return wp_get_attachment_image_src( $attachment_id, $size );
		}

		$width     = $size[0];
		$height    = $size[1];
		$image_src = wp_get_attachment_image_src( $attachment_id, 'full' );
		$file_path = get_attached_file( $attachment_id );

		if ( $file_path ) {
			$file_info = pathinfo( $file_path );
			$extension = '.' . $file_info['extension'];

			if ( $image_src[1] > $width || $image_src[2] > $height ) {
				$no_ext_path      = $file_info['dirname'] . '/' . $file_info['filename'];
				$cropped_img_path = $no_ext_path . '-' . $width . 'x' . $height . $extension;

				// the file is larger, check if the resized version already exists
				if ( file_exists( $cropped_img_path ) ) {
					$cropped_img_url = str_replace( basename( $image_src[0] ), basename( $cropped_img_path ), $image_src[0] );

					return array(
						0 => $cropped_img_url,
						1 => $width,
						2 => $height,
					);
				}

				// No resized file, let's crop it
				$image_editor = wp_get_image_editor( $file_path );

				if ( is_wp_error( $image_editor ) || is_wp_error( $image_editor->resize( $width, $height, true ) ) ) {
					return false;
				}

				$new_img_path = $image_editor->generate_filename();

				if ( is_wp_error( $image_editor->save( $new_img_path ) ) ) {
					false;
				}

				if ( ! is_string( $new_img_path ) ) {
					return false;
				}

				$new_img_size = getimagesize( $new_img_path );
				$new_img      = str_replace( basename( $image_src[0] ), basename( $new_img_path ), $image_src[0] );

				return array(
					0 => $new_img,
					1 => $new_img_size[0],
					2 => $new_img_size[1],
				);
			}
		}

		return false;
	}
}