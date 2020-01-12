<?php
/**
 * Custom functions for editor typography.
 *
 * @package Martfury
 */

if ( ! function_exists( 'martfury_editor_typography_css' ) ) :
	/**
	 * Get typography CSS base on settings
	 *
	 * @since 1.1.6
	 */
	function martfury_editor_typography_css() {
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
			'body_typo'     => '.edit-post-layout__content  .editor-styles-wrapper',
			'heading1_typo' => '.editor-styles-wrapper .wp-block-heading h1',
			'heading2_typo' => '.editor-styles-wrapper .wp-block-heading h2',
			'heading3_typo' => '.editor-styles-wrapper .wp-block-heading h3',
			'heading4_typo' => '.editor-styles-wrapper .wp-block-heading h4',
			'heading5_typo' => '.editor-styles-wrapper .wp-block-heading h5',
			'heading6_typo' => '.editor-styles-wrapper .wp-block-heading h6',
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

		return $css;
	}
endif;

/**
 * Enqueue editor styles for Gutenberg
 *
 */
function martfury_block_editor_styles() {

	wp_enqueue_style( 'martfury-block-editor-style', get_theme_file_uri( '/css/editor-blocks.css' ) );
	wp_enqueue_style( 'martfury-block-editor-fonts', martfury_fonts_url(), array(), '20180831' );
	wp_add_inline_style( 'martfury-block-editor-style', martfury_editor_typography_css() );
}

add_action( 'enqueue_block_editor_assets', 'martfury_block_editor_styles' );