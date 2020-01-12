<?php
class TAWC_Deals_Frontend {
	/**
	 * The single instance of the class
	 *
	 * @var TAWC_Deals_Frontend
	 */
	protected static $instance = null;

	/**
	 * Main instance
	 *
	 * @return TAWC_Deals_Frontend
	 */
	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_filter( 'woocommerce_quantity_input_args', array( $this, 'quantity_input_args' ), 10, 2 );

		add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_template' ), 25 );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		wp_enqueue_style( 'tawc-deals', plugins_url( '/assets/css/tawc-deals.css', TAWC_DEALS_PLUGIN_FILE ), array(), TAWC_DEALS_VERSION );

		wp_enqueue_script( 'tawc-deals', plugins_url( '/assets/js/tawc-deals.js', TAWC_DEALS_PLUGIN_FILE ), array( 'jquery' ), TAWC_DEALS_VERSION, true );
		wp_localize_script( 'tawc-deals', 'tawcDeals', array(
			'l10n' => array(
				'days'    => esc_html__( 'Days', 'tawc-deals' ),
				'hours'   => esc_html__( 'Hours', 'tawc-deals' ),
				'minutes' => esc_html__( 'Minutes', 'tawc-deals' ),
				'seconds' => esc_html__( 'Seconds', 'tawc-deals' ),
			)
		) );
	}

	/**
	 * Change the "max" attribute of quantity input
	 *
	 * @param array $args
	 * @param object $product
	 *
	 * @return array
	 */
	public function quantity_input_args( $args, $product ) {
		if ( ! tawc_is_deal_product( $product ) ) {
			return $args;
		}

		$args['max_value'] = $this->get_max_purchase_quantity( $product );

		return $args;
	}

	/**
	 * Get max value of quantity input for a deal product
	 *
	 * @param object $product
	 *
	 * @return int
	 */
	public function get_max_purchase_quantity( $product ) {
		$limit = get_post_meta( $product->get_id(), '_deal_quantity', true );
		$sold = intval(get_post_meta( $product->get_id(), '_deal_sales_counts', true ));

		$max = $limit - $sold;
		$original_max = $product->is_sold_individually() ? 1 : ( $product->backorders_allowed() || ! $product->managing_stock() ? -1 : $product->get_stock_quantity() );

		if ( $original_max < 0 ) {
			return $max;
		}

		return min( $max, $original_max );
	}

	/**
	 * Display countdown and sold items in single product page
	 */
	public function single_product_template() {
		global $product;

		if ( ! tawc_is_deal_product( $product ) ) {
			return;
		}

		$expire_date = ! empty( $product->get_date_on_sale_to() ) ? $product->get_date_on_sale_to()->getOffsetTimestamp() : '';

		if ( empty( $expire_date ) ) {
			return;
		}

		$now         = strtotime( current_time( 'Y-m-d H:i:s' ) );

		$expire = $expire_date - $now;

		if ( $expire <= 0 ) {
			return;
		}

		wc_get_template(
			'single-product/deal.php',
			array(
				'expire'      => $expire,
				'limit'       => get_post_meta( $product->get_id(), '_deal_quantity', true ),
				'sold'        => intval( get_post_meta( $product->get_id(), '_deal_sales_counts', true ) ),
				'expire_text' => apply_filters( 'tawc_deals_expire_text', __( 'Expires in', 'tawc-deals' ) ),
				'sold_text'   => apply_filters( 'tawc_deals_sold_text', __( 'Sold items', 'tawc-deals' ) ),
			),
			'',
			plugin_dir_path( TAWC_DEALS_PLUGIN_FILE ) . '/templates/'
		);
	}
}