<?php

/**
 * Main class of plugin for admin
 */
class TAWC_Deals_Admin {
	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'woocommerce_product_options_pricing', array( $this, 'add_deal_fields' ) );
		add_action( 'save_post', array( $this, 'save_product_data' ) );

		add_action( 'woocommerce_recorded_sales', array( $this, 'update_deal_sales' ) );
		add_action( 'woocommerce_scheduled_sales', array( $this, 'schedule_deals' ) );
	}

	/**
	 * Enqueue scripts
	 *
	 * @param string $hook
	 */
	public function enqueue_scripts( $hook ) {
		global $post;

		if ( $hook != 'post-new.php' && $hook != 'post.php' ) {
			return;
		}

		if ( 'product' != $post->post_type ) {
			return;
		}

		wp_enqueue_script( 'tawc-deals-admin', plugins_url( '/assets/js/tawc-deals-admin.js', TAWC_DEALS_PLUGIN_FILE ), array( 'jquery' ), TAWC_DEALS_VERSION, true );
	}

	/**
	 * Add the sale quantity field
	 */
	public function add_deal_fields() {
		global $thepostid;

		$quantity     = intval( get_post_meta( $thepostid, '_deal_quantity', true ) );
		$sales_counts = get_post_meta( $thepostid, '_deal_sales_counts', true );
		$sales_counts = intval( $sales_counts );
		$min          = $sales_counts > 0 ? $sales_counts + 1 : 0;
		?>

        <p class="form-field _deal_quantity_field">
            <label for="_sale_quantity"><?php esc_html_e( 'Sale quantity', 'tawc-deals' ) ?></label>
			<?php echo wc_help_tip( __( 'Set this quantity will make the product to be a deal. The sale will end when this quantity is sold out.', 'tawc-deals' ) ); ?>
            <input type="number" min="<?php echo $min; ?>" class="short" name="_deal_quantity" id="_deal_quantity"
                   value="<?php echo esc_attr( $quantity ) ?>">
        </p>


        <p class="form-field _deal_sales_counts_field">
            <label for="_sale_quantity"><?php esc_html_e( 'Sold Items', 'tawc-deals' ) ?></label>
			<?php echo wc_help_tip( __( 'Set this sold items should be less than the sale quantity.', 'tawc-deals' ) ); ?>
            <input type="number" class="short" name="_deal_sales_counts"
                   id="_deal_sales_counts" value="<?php echo esc_attr( $sales_counts ) ?>">

        </p>

		<?php
	}

	/**
	 * Save product data
	 *
	 * @param int $post_id
	 */
	public function save_product_data( $post_id ) {
		if ( 'product' !== get_post_type( $post_id ) ) {
			return;
		}

		if ( isset( $_POST['_deal_quantity'] ) ) {
			$current_sales = get_post_meta( $post_id, '_deal_sales_counts', true );

			// Reset sales counts if set the qty to 0
			if ( $_POST['_deal_quantity'] <= 0 ) {
				update_post_meta( $post_id, '_deal_sales_counts', 0 );
				update_post_meta( $post_id, '_deal_quantity', '' );
			} elseif ( $_POST['_deal_quantity'] < $current_sales ) {
				$this->end_deal( $post_id );
			} elseif ( $_POST['_deal_quantity'] < $_POST['_deal_sales_counts'] ) {
				$this->end_deal( $post_id );
			} else {
				update_post_meta( $post_id, '_deal_quantity', wc_clean( $_POST['_deal_quantity'] ) );
				update_post_meta( $post_id, '_deal_sales_counts', wc_clean( $_POST['_deal_sales_counts'] ) );
			}
		} else {
			// Reset sales counts and qty setting
			update_post_meta( $post_id, '_deal_sales_counts', 0 );
			update_post_meta( $post_id, '_deal_quantity', '' );
		}
	}

	/**
	 * Update deal sales count
	 *
	 * @param int $order_id
	 */
	public function update_deal_sales( $order_id ) {
		$order_post = get_post( $order_id );

		// Only apply for the main order
		if ( $order_post->post_parent != 0 ) {
			return;
		}

		$order = wc_get_order( $order_id );

		if ( sizeof( $order->get_items() ) > 0 ) {
			foreach ( $order->get_items() as $item ) {
				if ( $product_id = $item->get_product_id() ) {
					if ( ! tawc_is_deal_product( $product_id ) ) {
						continue;
					}

					add_post_meta( $product_id, '_deal_sales_counts', 0, true );

					$current_sales = get_post_meta( $product_id, '_deal_sales_counts', true );
					$deal_quantity = get_post_meta( $product_id, '_deal_quantity', true );
					$new_sales     = $current_sales + absint( $item['qty'] );

					// Reset deal sales and remove sale price when reach to limit sale quantity
					if ( $new_sales >= $deal_quantity ) {
						$this->end_deal( $product_id );
					} else {
						update_post_meta( $product_id, '_deal_sales_counts', $new_sales );
					}
				}
			}
		}
	}

	/**
	 * Remove deal data when sale is scheduled end
	 */
	public function schedule_deals() {
		$data_store  = WC_Data_Store::load( 'product' );
		$product_ids = $data_store->get_ending_sales();

		if ( $product_ids ) {
			foreach ( $product_ids as $product_id ) {
				if ( $product = wc_get_product( $product_id ) ) {
					update_post_meta( $product_id, '_deal_sales_counts', 0 );
					update_post_meta( $product_id, '_deal_quantity', '' );
				}
			}
		}
	}

	/**
	 * Remove deal data of a product.
	 * Remove sale price
	 * Remove sale schedule dates
	 * Remove sale quantity
	 * Reset sales counts
	 *
	 * @param int $post_id
	 */
	public function end_deal( $post_id ) {
		update_post_meta( $post_id, '_deal_sales_counts', 0 );
		update_post_meta( $post_id, '_deal_quantity', '' );

		// Remove sale price
		$product       = wc_get_product( $post_id );
		$regular_price = $product->get_regular_price();
		$product->set_price( $regular_price );
		$product->set_sale_price( '' );
		$product->set_date_on_sale_to( '' );
		$product->set_date_on_sale_from( '' );
		$product->save();

		delete_transient( 'wc_products_onsale' );
	}
}

new TAWC_Deals_Admin;