<span class="swatch">
	<?php
	switch ( $swatch_type ) {
		case 'color':
			printf(
				'<input type="text" name="%s" value="%s">%s',
				"tawcvs_swatches[$option_name][swatches][$swatch_name][color]",
				isset( $swatch['swatches'][ $swatch_name ] ) && isset( $swatch['swatches'][ $swatch_name ]['color'] ) ? esc_attr( $swatch['swatches'][ $swatch_name ]['color'] ) : '',
				$swatch_label
			);
			break;

		case 'image':
			$default_image = WC()->plugin_url() . '/assets/images/placeholder.png';
			printf(
				'<img src="%s" data-default="%s"><input type="hidden" name="%s" value="%s"><br>%s<br><a href="#" class="edit-image">%s</a><br><a href="#" class="remove-image %s">%s</a>',
				isset( $swatch['swatches'][ $swatch_name ] ) && isset( $swatch['swatches'][ $swatch_name ]['image'] ) ? wp_get_attachment_image_url( $swatch['swatches'][ $swatch_name ]['image'] ) : $default_image,
				$default_image,
				"tawcvs_swatches[$option_name][swatches][$swatch_name][image]",
				isset( $swatch['swatches'][ $swatch_name ] ) && isset( $swatch['swatches'][ $swatch_name ]['image'] ) ? esc_attr( $swatch['swatches'][ $swatch_name ]['image'] ) : '',
				$swatch_label,
				esc_html__( 'Edit', 'wcvs' ),
				isset( $swatch['swatches'][ $swatch_name ] ) && isset( $swatch['swatches'][ $swatch_name ]['image'] ) ? '' : 'hidden',
				esc_html__( 'Remove', 'wcvs' )
			);
			break;

		case 'label':
			printf(
				'<input type="text" name="%s" value="%s"><br>%s',
				"tawcvs_swatches[$option_name][swatches][$swatch_name][label]",
				isset( $swatch['swatches'][ $swatch_name ] ) && isset( $swatch['swatches'][ $swatch_name ]['label'] ) ? esc_attr( $swatch['swatches'][ $swatch_name ]['label'] ) : '',
				$swatch_label
			);
			break;
	}
	?>
</span>