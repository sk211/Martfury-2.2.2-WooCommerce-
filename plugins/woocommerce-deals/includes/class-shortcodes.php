<?php

class TAWC_Deals_Shortcodes {
	/**
	 * Init shortcodes
	 */
	public static function init() {
		$shortcodes = array(
			'deals',
			'deals_of_day',
		);

		foreach ( $shortcodes as $shortcode ) {
			add_shortcode( 'tawc_' . $shortcode, array( __CLASS__, $shortcode ) );
		}

		// Add deal progress before add-to-cart button, in the loop of deal shortcodes
		add_action( 'woocommerce_after_shop_loop_item', array( __CLASS__, 'deal_progress' ), 7 );
	}

	/**
	 * List best deal products.
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function deals( $atts ) {
		$atts = shortcode_atts( array(
			'limit'   => '10',
			'columns' => '4',
			'class'   => '',
		), $atts, 'tawc_deals' );

		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => intval( $atts['limit'] ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'fields'              => 'ids',
			'post__in'            => (array) wc_get_product_ids_on_sale(),
			'meta_query'          => WC()->query->get_meta_query(),
			'tax_query'           => WC()->query->get_tax_query(),
		);

		$query_args['meta_query'][] = array(
			'key'     => '_deal_quantity',
			'value'   => 0,
			'compare' => '>',
		);

		$deals = new WP_Query( $query_args );

		if ( ! $deals->have_posts() ) {
			return '';
		}

		$classes = array( 'woocommerce', 'tawc-deals', 'columns-' . $atts['columns'], $atts['class'] );

		global $woocommerce_loop;

		$woocommerce_loop['columns'] = intval( $atts['columns'] );
		$woocommerce_loop['name']    = 'tawc_deals';

		ob_start();

		self::loop( $deals->posts );

		return '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">' . ob_get_clean() . '</div>';
	}

	/**
	 * List best deals of the day.
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public static function deals_of_day( $atts ) {
		$atts = shortcode_atts( array(
			'limit'   => '10',
			'columns' => '4',
			'class'   => '',
		), $atts, 'tawc_deals_of_day' );

		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => intval( $atts['limit'] ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'fields'              => 'ids',
			'post__in'            => (array) wc_get_product_ids_on_sale(),
			'meta_query'          => WC()->query->get_meta_query(),
			'tax_query'           => WC()->query->get_tax_query(),
		);

		$query_args['meta_query'] = array_merge( WC()->query->get_meta_query(), array(
			array(
				'key'     => '_deal_quantity',
				'value'   => 0,
				'compare' => '>',
			),
			array(
				'key'     => '_sale_price_dates_to',
				'value'   => 0,
				'compare' => '>',
			),
			array(
				'key'     => '_sale_price_dates_to',
				'value'   => strtotime( '+1 day' ),
				'compare' => '<=',
			)
		) );

		$deals = new WP_Query( $query_args );

		if ( ! $deals->have_posts() ) {
			return '';
		}

		$classes = array(
			'woocommerce',
			'tawc-deals',
			'tawc-deals-of-day',
			'columns-' . $atts['columns'],
			$atts['class'],
		);

		global $woocommerce_loop;

		$woocommerce_loop['columns'] = intval( $atts['columns'] );
		$woocommerce_loop['name']    = 'tawc_deals';

		ob_start();

		self::loop( $deals->posts );

		return '<div class="' . esc_attr( implode( ' ', $classes ) ) . '">' . ob_get_clean() . '</div>';
	}

	/**
	 * Loop over products
	 *
	 * @param array $products_ids
	 */
	public static function loop( $products_ids ) {
		update_meta_cache( 'post', $products_ids );
		update_object_term_cache( $products_ids, 'product' );

		$original_post = $GLOBALS['post'];

		woocommerce_product_loop_start();

		foreach ( $products_ids as $product_id ) {
			$GLOBALS['post'] = get_post( $product_id ); // WPCS: override ok.
			setup_postdata( $GLOBALS['post'] );

			wc_get_template_part( 'content', 'product' );
		}

		$GLOBALS['post'] = $original_post; // WPCS: override ok.
		woocommerce_product_loop_end();

		wp_reset_postdata();
		woocommerce_reset_loop();
	}

	/**
	 * Display deal progress on shortcode
	 */
	public static function deal_progress() {
		global $woocommerce_loop, $product;

		if( ! isset( $woocommerce_loop['name'] ) ) {
			return;
		}

		if ( 'tawc_deals' != $woocommerce_loop['name'] ) {
			return;
		}

		if ( ! tawc_is_deal_product( $product ) ) {
			return;
		}

		$limit = get_post_meta( $product->get_id(), '_deal_quantity', true );
		$sold  = intval( get_post_meta( $product->get_id(), '_deal_sales_counts', true ) );
		?>

		<div class="deal-progress">
			<div class="progress-bar">
				<div class="progress-value" style="width: <?php echo $sold/$limit*100 ?>%"></div>
			</div>
			<p class="progress-text"><?php esc_html_e( 'Sold', 'tawc-deals' ) ?>: <?php echo $sold; ?></p>
		</div>

		<?php
	}
}