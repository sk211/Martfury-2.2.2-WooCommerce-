<?php

/**
 * Class for all WooCommerce template modification
 *
 * @version 1.0
 */
class Martfury_WooCommerce_Admin {
	/**
	 * @var string Layout of current page
	 */
	public $layout;

	/**
	 * @var string shop view
	 */
	public $shop_view;

	/**
	 * @var string top_categories
	 */
	public $featured_categories;

	/**
	 * Construction function
	 *
	 * @since  1.0
	 * @return Martfury_WooCommerce
	 */
	function __construct() {
		// Check if Woocomerce plugin is actived
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return;
		}

		// Add admin style
		add_filter( 'woocommerce_screen_ids', array( $this, 'brand_screen_ids' ), 20 );


		// add section in product edit page
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'product_meta_tab' ), 10, 1 );
		add_action( 'woocommerce_product_data_panels', array( $this, 'product_meta_panel') );
		add_action( 'woocommerce_process_product_meta', array( $this, 'product_meta_fields_save' ) );

	}


	/**
	 * Add  all WooCommerce screen ids.
	 *
	 * @since  1.0
	 *
	 * @param  array $screen_ids
	 *
	 * @return array
	 */
	function brand_screen_ids( $screen_ids ) {
		$screen_ids[] = 'edit-mf_product_brand';

		return $screen_ids;
	}

	/**
	 * Add Frequently bought together tab in edit product page
	 *
	 * @param $tabs
	 *
	 * @return mixed
	 */
	public function product_meta_tab( $tabs ){

		$tabs['martfury_pbt_product'] = array(
			'label'  => esc_html__( 'Frequently Bought Together', 'martfury' ),
			'target' => 'martfury_pbt_product_data',
			'class'  => array( 'hide_if_grouped', 'hide_if_external', 'hide_if_bundle' ),
		);

		return $tabs;
	}

	/**
	 * product_meta_fields_save function.
	 *
	 * @param mixed $post_id
	 */
	public function product_meta_fields_save( $post_id ) {
		if ( isset( $_POST['mf_pbt_product_ids'] ) ) {
			$woo_data = $_POST['mf_pbt_product_ids'];
			update_post_meta( $post_id, 'mf_pbt_product_ids', $woo_data );
		} else {
			update_post_meta( $post_id, 'mf_pbt_product_ids', 0 );
		}
	}


	/**
	 * Add Frequently bought together panel in edit product page
	 */
	public function product_meta_panel(){
		global $post;
		?>

		<div id="martfury_pbt_product_data" class="panel woocommerce_options_panel">

			<div class="options_group">

				<p class="form-field">
					<label for="mf_pbt_product_ids"><?php esc_html_e( 'Select Products', 'martfury' ); ?></label>
					<select class="wc-product-search" multiple="multiple" style="width: 50%;" id="mf_pbt_product_ids" name="mf_pbt_product_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'martfury' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>">
						<?php
						$product_ids = maybe_unserialize( get_post_meta( $post->ID, 'mf_pbt_product_ids', true ) );

						if( $product_ids && is_array( $product_ids ) ) {
							foreach ( $product_ids as $product_id ) {
								$product = wc_get_product( $product_id );
								if ( is_object( $product ) ) {
									echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
								}
							}
						}

						?>
					</select> <?php echo wc_help_tip( __( 'Select products for "Frequently bought together" group.', 'martfury' ) ); ?>
				</p>
				
			</div>

		</div>

		<?php
	}
}

new Martfury_WooCommerce_Admin();
