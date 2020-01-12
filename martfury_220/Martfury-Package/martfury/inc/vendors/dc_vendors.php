<?php

/**
 * Class for all Vendor template modification
 *
 * @version 1.0
 */
class Martfury_DCVendors {

	/**
	 * Construction function
	 *
	 * @since  1.0
	 * @return Martfury_Vendor
	 */
	function __construct() {
		if ( ! class_exists( 'WCMp' ) ) {
			return;
		}

		add_filter( 'wcmp_sold_by_text_after_products_shop_page', '__return_false' );

		add_action( 'woocommerce_after_shop_loop_item_title', array(
			$this,
			'template_loop_sold_by',
		), 7 );

		add_action( 'woocommerce_after_shop_loop_item_title', array(
			$this,
			'template_loop_sold_by',
		), 120 );

		add_action( 'martfury_woo_after_shop_loop_item_title', array(
			$this,
			'template_loop_sold_by',
		), 7 );

		add_action( 'martfury_single_product_header', array(
			$this,
			'template_loop_sold_by',
		) );

		add_action( 'woocommerce_shop_loop_item_title', array( $this, 'template_loop_show_sold_by' ), 6 );

		add_action( 'init', array( $this, 'hooks' ) );

		add_filter( 'body_class', array( $this, 'wc_body_class' ) );

		if ( class_exists( 'TAWC_Deals' ) ) {
			add_action( 'wcmp_afm_product_options_pricing', array( $this, 'product_manage_fields_pricing' ) );
		}

		add_action( 'wcmp_afm_product_options_related', array( $this, 'product_manage_fields_fbt' ) );

		add_action( 'wcmp_process_product_object', array( $this, 'process_product_save_object' ), 20, 2 );

		add_filter( 'wcmp_frontend_dash_upload_script_params', array( $this, 'frontend_dash_upload_script_params' ) );

	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since 1.0
	 *
	 * @param array $classes Classes for the body element.
	 *
	 * @return array
	 */
	function wc_body_class( $classes ) {

		if ( function_exists( 'wcmp_vendor_dashboard_page_id' ) ) {

			if ( is_page( wcmp_vendor_dashboard_page_id() ) ) {
				$classes[] = 'mf-vendor-dashboard-page';
			}
		}


		return $classes;

	}

	function hooks() {
		global $WCMp;
		if ( empty( $WCMp ) ) {
			return;
		}

		$store_header = intval( martfury_get_option( 'vendor_store_header' ) );

		if ( ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! $store_header ) {
			remove_action( 'woocommerce_archive_description', array(
				$WCMp->frontend,
				'product_archive_vendor_info',
			), 10 );
		}

	}

	function template_loop_show_sold_by() {
		if ( martfury_get_option( 'catalog_vendor_name' ) != 'display' ) {
			return;
		}

		echo '<div class="mf-vendor-name">';
		$this->template_loop_sold_by();
		echo '</div>';
	}


	function template_loop_sold_by() {
		if ( ! function_exists( 'get_wcmp_vendor_settings' ) ) {
			return;
		}

		if ( ! function_exists( 'get_wcmp_product_vendors' ) ) {
			return;
		}

		if ( 'Enable' !== get_wcmp_vendor_settings( 'sold_by_catalog', 'general' ) ) {
			return;
		}

		if ( martfury_get_option( 'catalog_vendor_name' ) == 'hidden' ) {
			return;
		}


		global $post;
		$vendor = get_wcmp_product_vendors( $post->ID );

		if ( empty( $vendor ) ) {
			return;
		}

		$sold_by_text = apply_filters( 'wcmp_sold_by_text', esc_html__( 'Sold By:', 'martfury' ) );
		echo '<div class="sold-by-meta">';
		echo '<span class="sold-by-label">' . $sold_by_text . ' ' . '</span>';
		echo sprintf(
			'<a href="%s">%s</a>',
			esc_url( $vendor->permalink ),
			$vendor->user_data->display_name
		);
		echo '</div>';
	}

	function product_manage_fields_pricing( $postID ) {
		$quantity     = get_post_meta( $postID, '_deal_quantity', true );
		$sales_counts = get_post_meta( $postID, '_deal_sales_counts', true );
		$sales_counts = intval( $sales_counts );
		?>
        <div class="form-group">
            <label class="control-label col-sm-3 col-md-3"
                   for="_deal_quantity"><?php echo esc_html__( 'Sale quantity', 'martfury' ); ?></label>
            <div class="col-md-6 col-sm-9">
                <input type="text" id="_deal_quantity" name="_deal_quantity"
                       value="<?php echo esc_attr( $quantity ); ?>"
                       class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-3 col-md-3"
                   for="_deal_sales_counts"><?php echo esc_html__( 'Sold Items', 'martfury' ); ?></label>
            <div class="col-md-6 col-sm-9">
                <input type="text" id="_deal_sales_counts" name="_deal_sales_counts"
                       value="<?php echo esc_attr( $sales_counts ); ?>" class="form-control">
            </div>
        </div>
		<?php
	}

	function product_manage_fields_fbt( $postID ) {
		?>
        <div class="row-padding">
            <div class="form-group hide_if_variation">
                <label class="control-label col-sm-3 col-md-3"
                       for="_deal_quantity"><?php echo esc_html__( 'Frequently Bought Together', 'martfury' ); ?></label>
                <div class="col-md-6 col-sm-9">
                    <select class="wc-product-search form-control" multiple="multiple" id="mf_pbt_product_ids"
                            name="mf_pbt_product_ids[]" data-sortable="true"
                            data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'martfury' ); ?>"
                            data-action="woocommerce_json_search_products"
                            data-exclude="<?php echo intval( $postID ); ?>">
						<?php
						$pbt_product_ids = get_post_meta( $postID, 'mf_pbt_product_ids', true );
						$pbt_product_ids = $pbt_product_ids ? $pbt_product_ids : array();
						if ( ! empty( $pbt_product_ids ) ) {
							foreach ( $pbt_product_ids as $pbt_product_id ) {
								$product = wc_get_product( $pbt_product_id );
								if ( is_object( $product ) ) {
									echo '<option value="' . esc_attr( $pbt_product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
								}
							}
						}

						?>
                    </select>
                </div>
            </div>
        </div>
		<?php
	}

	function process_product_save_object( $product, $POST ) {
		if ( class_exists( 'TAWC_Deals' ) ) {
			if ( isset( $POST['_deal_quantity'] ) ) {
				update_post_meta( $product->get_id(), '_deal_quantity', wc_clean( intval( $POST['_deal_quantity'] ) ) );
			}

			if ( isset( $POST['_deal_sales_counts'] ) ) {
				update_post_meta( $product->get_id(), '_deal_sales_counts', wc_clean( intval( $POST['_deal_sales_counts'] ) ) );
			}
		}


		if ( isset( $POST['mf_pbt_product_ids'] ) ) {
			update_post_meta( $product->get_id(), 'mf_pbt_product_ids', array_filter( array_map( 'intval', (array) $_POST['mf_pbt_product_ids'] ) ) );
		}
	}

	function frontend_dash_upload_script_params( $image_script_params ) {
		$image_script_params['default_logo_ratio'] = array( 270, 270 );

		return $image_script_params;
	}

}

