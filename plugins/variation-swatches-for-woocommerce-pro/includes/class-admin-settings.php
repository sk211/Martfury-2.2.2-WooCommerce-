<?php

/**
 * Class TA_WC_Variation_Swatches_Settings
 */
class TA_WC_Variation_Swatches_Admin_Settings {
	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_filter( 'woocommerce_get_sections_products', array( $this, 'swatches_section' ), 10, 2 );
		add_filter( 'woocommerce_get_settings_products', array( $this, 'swatches_settings' ), 10, 2 );
		add_action( 'woocommerce_admin_field_swatch_size', array( $this, 'swatch_size_field' ) );
		add_filter( 'woocommerce_admin_settings_sanitize_option', array( $this, 'sanitize_swatch_size_field' ), 10, 3 );
	}

	public function swatches_section( $sections ) {
		$sections['tawc_swatches'] = esc_html__( 'Variation Swatches', 'wcvs' );

		return $sections;
	}

	/**
	 * Adds settings to product display settings
	 *
	 * @param array  $settings
	 * @param string $section
	 *
	 * @return array
	 */
	public function swatches_settings( $settings, $section ) {
		if ( 'tawc_swatches' == $section ) {
			$settings = array();

			$settings[] = array(
				'id'    => 'tawcvs_options',
				'title' => esc_html__( 'Variation Swatches', 'wcvs' ),
				'desc'  => esc_html__( 'These settings affect the display of attribute swatches in variable products.', 'wcvs' ),
				'type'  => 'title',
			);

			$settings[] = array(
				'id'      => 'tawcvs_swatch_tooltip',
				'title'   => esc_html__( 'Swatch tooltip', 'wcvs' ),
				'desc'    => esc_html__( 'Show attribute term name as a tooltip', 'wcvs' ),
				'type'    => 'checkbox',
				'default' => 'yes',
			);

			$settings[] = array(
				'id'      => 'tawcvs_swatch_style',
				'title'   => esc_html__( 'Swatch style', 'wcvs' ),
				'type'    => 'radio',
				'default' => 'round',
				'options' => array(
					'round'   => esc_html__( 'Round', 'wcvs' ),
					'rounded' => esc_html__( 'Rounded', 'wcvs' ),
					'square'  => esc_html__( 'Square', 'wcvs' ),
				),
			);

			$settings[] = array(
				'id'       => 'tawcvs_swatch_image_size',
				'title'    => esc_html__( 'Swatch size', 'wcvs' ),
				'desc'     => esc_html__( 'This size is used for a swatch dimension. (W x H)', 'wcvs' ),
				'type'     => 'swatch_size',
				'default'  => array(
					'width'  => '30',
					'height' => '30',
				),
				'desc_tip' => true,
			);

			$settings[] = array(
				'id'   => 'tawcvs_options',
				'type' => 'sectionend',
			);
		}

		return $settings;
	}

	/**
	 * Output the setting field type swatch_size
	 *
	 * @param array $setting
	 */
	public function swatch_size_field( $setting ) {
		$size         = get_option( $setting['id'] );
		$width        = isset( $size['width'] ) ? $size['width'] : $setting['default']['width'];
		$height       = isset( $size['height'] ) ? $size['height'] : $setting['default']['height'];
		$tooltip_html = '';

		extract( WC_Admin_Settings::get_field_description( $setting ) );
		?>
		<tr valign="top">
			<th scope="row" class="titledesc"><?php echo esc_html( $setting['title'] ) ?><?php echo $tooltip_html; ?></th>
			<td class="forminp image_width_settings">
				<input name="<?php echo esc_attr( $setting['id'] ); ?>[width]" id="<?php echo esc_attr( $setting['id'] ); ?>-width" type="text" size="3" value="<?php echo $width; ?>" /> &times;
				<input name="<?php echo esc_attr( $setting['id'] ); ?>[height]" id="<?php echo esc_attr( $setting['id'] ); ?>-height" type="text" size="3" value="<?php echo $height; ?>" />px
			</td>
		</tr>
		<?php
	}

	/**
	 * Sanitize swatch_size field before being saved
	 *
	 * @param array $value
	 * @param array $setting
	 * @param array $raw_value
	 *
	 * @return array
	 */
	public function sanitize_swatch_size_field( $value, $setting, $raw_value ) {
		if ( 'swatch_size' != $setting['type'] ) {
			return $value;
		}

		$value['crop'] = isset( $raw_value['crop'] ) ? 1 : 0;

		return $value;
	}
}

new TA_WC_Variation_Swatches_Admin_Settings();