<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div data-taxonomy="<?php echo esc_attr( $attribute->get_taxonomy() ); ?>" class="woocommerce_attribute woocommerce_attribute-swatch wc-metabox closed <?php echo esc_attr( implode( ' ', $metabox_class ) ); ?>" rel="<?php echo esc_attr( $attribute->get_position() ); ?>">
	<h3>
		<div class="handlediv" title="<?php esc_attr_e( 'Click to toggle', 'woocommerce' ); ?>"></div>
		<div class="swatch-type"><?php echo 'default' == $swatch['type'] ? $type_labels[$swatch['default_type']] : $type_labels[$swatch['type']] ?></div>
		<strong class="attribute_name"><?php echo esc_html( wc_attribute_label( $attribute->get_name() ) ); ?></strong>
	</h3>
	<div class="attribute-swatches wc-metabox-content" style="display: none;">
		<div class="options_group"></div>
		<div class="data">
			<div class="options_group">
				<?php
				woocommerce_wp_select( array(
					'id'            => 'tawcvs_swatches[' . $option_name . '][type]',
					'class'         => 'select',
					'wrapper_class' => 'type_field',
					'label'         => esc_html__( 'Swatch type', 'wcvs' ),
					'value'         => $swatch['type'],
					'options'       => array(
						'default' => esc_html__( 'Default', 'wcvs' ) . ' (' . $type_labels[ $swatch['default_type'] ] . ')',
						'select'  => esc_html__( 'Select', 'wcvs' ),
						'color'   => esc_html__( 'Custom Color', 'wcvs' ),
						'image'   => esc_html__( 'Custom Image', 'wcvs' ),
						'label'   => esc_html__( 'Custom Label', 'wcvs' ),
					),
				) );
				?>
			</div>

			<div class="options_group">
				<?php
				woocommerce_wp_select( array(
					'id'            => 'tawcvs_swatches[' . $option_name . '][style]',
					'class'         => 'select',
					'wrapper_class' => 'style_field',
					'label'         => esc_html__( 'Swatch style', 'wcvs' ),
					'value'         => $swatch['style'],
					'options'       => array(
						'default' => esc_html__( 'Default', 'wcvs' ) . ' (' . $style_labels[ $swatch['default_style'] ] . ')',
						'round'   => esc_html__( 'Round', 'wcvs' ),
						'rounded' => esc_html__( 'Rounded', 'wcvs' ),
						'square'  => esc_html__( 'Square', 'wcvs' ),
					),
				) );
				?>
			</div>

			<div class="options_group">
				<?php
				woocommerce_wp_radio( array(
					'id'            => 'tawcvs_swatches[' . $option_name . '][size]',
					'wrapper_class' => 'size_field',
					'label'         => esc_html__( 'Swatch size', 'wcvs' ),
					'value'         => $swatch['size'],
					'options'       => array(
						'default' => esc_html__( 'Default', 'wcvs' ) . ' (' . $swatch['default_size']['width'] . 'x' . $swatch['default_size']['height'] . ')',
						'custom'  => esc_html__( 'Custom size', 'wcvs' ),
					),
				) );
				?>
				<p class="form-field dimensions_field custom_size_field <?php echo 'default' == $swatch['size'] ? 'hidden' : '' ?>">
					<span class="wrap">
						<input type="text" name="tawcvs_swatches[<?php echo $option_name ?>][custom_size][width]" value="<?php echo $swatch['custom_size']['width'] ?>" size="5" placeholder="<?php esc_attr_e( 'Width', 'wcvs' ) ?>">
						<input type="text" name="tawcvs_swatches[<?php echo $option_name ?>][custom_size][height]" value="<?php echo $swatch['custom_size']['height'] ?>" size="5" placeholder="<?php esc_attr_e( 'Height', 'wcvs' ) ?>">
					</span>
				</p>
			</div>

			<div class="options_group swatches-options_group">
				<p class="form-field swatches_field swatch-colors <?php echo 'color' != $swatch['type'] ? 'hidden' : '' ?>">
					<?php
					foreach ( $attribute->get_options() as $option ) :
						$swatch_name  = $attribute->is_taxonomy() ? $option : sanitize_title( $option );
						$swatch_term  = $attribute->is_taxonomy() ? get_term( $option ) : false;
						$swatch_label = $swatch_term ? $swatch_term->name : $option;
						$swatch_type  = 'color';

						include( 'html-swatch-admin.php' );
					endforeach;
					?>
				</p>

				<p class="form-field swatches_field swatch-images <?php echo 'image' != $swatch['type'] ? 'hidden' : '' ?>">
					<?php
					foreach ( $attribute->get_options() as $option ) :
						$swatch_name  = $attribute->is_taxonomy() ? $option : sanitize_title( $option );
						$swatch_term  = $attribute->is_taxonomy() ? get_term( $option ) : false;
						$swatch_label = $swatch_term ? $swatch_term->name : $option;
						$swatch_type  = 'image';

						include( 'html-swatch-admin.php' );
					endforeach;
					?>
				</p>

				<p class="form-field swatches_field swatch-labels <?php echo 'label' != $swatch['type'] ? 'hidden' : '' ?>">
					<?php
					foreach ( $attribute->get_options() as $option ) :
						$swatch_name  = $attribute->is_taxonomy() ? $option : sanitize_title( $option );
						$swatch_term  = $attribute->is_taxonomy() ? get_term( $option ) : false;
						$swatch_label = $swatch_term ? $swatch_term->name : $option;
						$swatch_type  = 'label';

						include( 'html-swatch-admin.php' );
					endforeach;
					?>
				</p>
			</div>

		</div>
	</div>
</div>
