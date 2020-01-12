<?php

/**
 * Class for all Vendor template modification
 *
 * @version 1.0
 */
class Martfury_DokanFM {

	/**
	 * Construction function
	 *
	 * @since  1.0
	 * @return Martfury_DokanFM
	 */
	function __construct() {
		if ( ! class_exists( 'WeDevs_Dokan' ) ) {
			return;
		}

		add_action( 'dokan_new_product_after_product_tags', array( $this, 'add_product_brand_field' ) );
		add_action( 'dokan_new_product_added', array( $this, 'new_product_brand_added' ), 20, 2 );
		add_action( 'dokan_product_updated', array( $this, 'new_product_brand_added' ), 20, 2 );

		add_action( 'dokan_product_edit_after_product_tags', array( $this, 'edit_product_brand_field' ), 20, 2 );

	}

	function new_product_brand_added( $product_id, $data ) {
		if ( isset( $data['product_brand'] ) && ! empty( $data['product_brand'] ) ) {
			$brand_ids = array_map( 'absint', (array) $data['product_brand'] );
			wp_set_object_terms( $product_id, $brand_ids, 'product_brand' );
		}
	}

	function add_product_brand_field() {
		?>
        <div class="dokan-form-group">
			<?php
			$drop_down_brands = wp_dropdown_categories( array(
				'show_option_none' => __( '', 'martfury' ),
				'hierarchical'     => 1,
				'hide_empty'       => 0,
				'name'             => 'product_brand[]',
				'id'               => 'product_brand',
				'taxonomy'         => 'product_brand',
				'title_li'         => '',
				'class'            => 'product_cat dokan-form-control dokan-select2',
				'exclude'          => '',
				'selected'         => array(),
				'echo'             => 0,
				'walker'           => class_exists( 'DokanTaxonomyWalker' ) ? new DokanTaxonomyWalker() : ''
			) );

			echo str_replace( '<select', '<select data-placeholder="' . esc_html__( 'Select product brands', 'martfury' ) . '" multiple="multiple" ', $drop_down_brands ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			?>
        </div>

		<?php
	}


	function edit_product_brand_field( $post, $post_id ) {
		$term = wp_get_post_terms( $post_id, 'product_brand', array( 'fields' => 'ids' ) );
		$selected = ( $term ) ? $term : array();
		?>
        <label for="product_brand" class="form-label"><?php esc_html_e( 'Brands', 'martfury' ); ?></label>
        <div class="dokan-form-group">
			<?php
			$drop_down_brands = wp_dropdown_categories( array(
				'show_option_none' => __( '', 'martfury' ),
				'hierarchical'     => 1,
				'hide_empty'       => 0,
				'name'             => 'product_brand[]',
				'id'               => 'product_brand',
				'taxonomy'         => 'product_brand',
				'title_li'         => '',
				'class'            => 'product_brand dokan-form-control dokan-select2',
				'exclude'          => '',
				'selected'         => $selected,
				'echo'             => 0,
				'walker'           => class_exists( 'DokanTaxonomyWalker' ) ? new DokanTaxonomyWalker( $post_id ) : ''
			) );

			echo str_replace( '<select', '<select data-placeholder="' . esc_html__( 'Select product brands', 'martfury' ) . '" multiple="multiple" ', $drop_down_brands ); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			?>
        </div>

		<?php
	}

}

new Martfury_DokanFM;