<?php
/**
 * Class for the customizer importer used in the One Click Demo Import plugin.
 *
 * Code is mostly from the Customizer Export/Import plugin.
 *
 * @see https://wordpress.org/plugins/customizer-export-import/
 */
class Soo_Demo_Customizer_Importer {
	public $download_images = false;

	private $processed_images = array();

	/**
	 * Imports uploaded mods and calls WordPress core customize_save actions so
	 * themes that hook into them can act before mods are saved to the database.
	 *
	 * @param  string $file
	 */
	public function import( $file ) {
		// Setup global vars.
		global $wp_customize;

		// Setup internal vars.
		$template = get_template();

		// Get the upload data.
		$raw  = file_get_contents( $file );
		$data = @unserialize( $raw );

		// Data checks.
		if ( ! is_array( $data ) && ( ! isset( $data['template'] ) || ! isset( $data['mods'] ) ) ) {
			return new WP_Error(
				'customizer_import_data_error',
				esc_html__( 'The customizer import file is not in a correct format. Please make sure to use the correct customizer import file.', 'soodi' )
			);
		}
		if ( $data['template'] !== $template ) {
			return new WP_Error(
				'customizer_import_wrong_theme',
				esc_html__( 'The customizer import file is not suitable for current theme. You can only import customizer settings for the same theme or a child theme.', 'soodi' )
			);
		}

		// Import images.
		if ( $this->download_images ) {
			$data['mods'] = $this->import_images( $data['mods'] );
		}

		// Import custom options.
		if ( isset( $data['options'] ) ) {
			require dirname( __FILE__ ) . '/customizer-option.php';

			foreach ( $data['options'] as $option_key => $option_value ) {
				$option = new Soo_Demo_Customizer_Option( $wp_customize, $option_key, array(
					'default'    => '',
					'type'       => 'option',
					'capability' => 'edit_theme_options',
				) );

				$option->import( $option_value );
			}
		}

		// Loop through the mods.
		foreach ( $data['mods'] as $key => $val ) {

			// Save the mod.
			set_theme_mod( $key, $val );
		}

		return true;
	}

	/**
	 * Imports images for settings saved as mods.
	 *
	 * @return array The mods array with any new import data.
	 */
	private function import_images( $mods ) {
		foreach ( $mods as $key => $val ) {

			if ( $this->is_image_url( $val ) ) {

				if ( array_key_exists( $val, $this->processed_images ) ) {
					$data = $this->processed_images[$val];
				} else {
					$data = $this->sideload_image( $val );
					$this->processed_images[$val] = $data;
				}

				if ( ! is_wp_error( $data ) ) {

					$mods[ $key ] = $data->url;

					// Handle header image controls.
					if ( isset( $mods[ $key . '_data' ] ) ) {
						$mods[ $key . '_data' ] = $data;
						update_post_meta( $data->attachment_id, '_wp_attachment_is_custom_header', get_stylesheet() );
					}
				}
			}
		}

		return $mods;
	}

	/**
	 * Taken from the core media_sideload_image function and
	 * modified to return an array of data instead of html.
	 *
	 * @param string $file The image file path.
	 * @return array An array of image data.
	 */
	private function sideload_image( $file ) {
		$data = new stdClass();

		if ( ! function_exists( 'media_handle_sideload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
		}

		if ( ! empty( $file ) ) {
			// Set variables for storage, fix file filename for query strings.
			preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );
			$file_array = array();
			$file_array['name'] = basename( $matches[0] );

			// Download file to temp location.
			$file_array['tmp_name'] = download_url( $file );

			// If error storing temporarily, return the error.
			if ( is_wp_error( $file_array['tmp_name'] ) ) {
				return $file_array['tmp_name'];
			}

			// Do the validation and storage stuff.
			$id = media_handle_sideload( $file_array, 0 );

			// If error storing permanently, unlink.
			if ( is_wp_error( $id ) ) {
				@unlink( $file_array['tmp_name'] );
				return $id;
			}

			// Build the object to return.
			$meta					= wp_get_attachment_metadata( $id );
			$data->attachment_id	= $id;
			$data->url				= wp_get_attachment_url( $id );
			$data->thumbnail_url	= wp_get_attachment_thumb_url( $id );
			$data->height			= $meta['height'];
			$data->width			= $meta['width'];
		}

		return $data;
	}

	/**
	 * Checks to see whether a string is an image url or not.
	 *
	 * @return bool Whether the string is an image url or not.
	 */
	private function is_image_url( $string = '' ) {
		if ( is_string( $string ) ) {

			if ( preg_match( '/\.(jpg|jpeg|png|gif)/i', $string ) ) {
				return true;
			}
		}

		return false;
	}
}
