<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see           https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$product_class = '';
if ( intval( martfury_get_option( 'product_buy_now' ) ) ) {
	$product_class = 'mf-has-buy-now';
}
global $product;
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	<div class="mf-product-detail <?php echo esc_attr( $product_class ); ?>">
		<?php
		/**
		 * woocommerce_before_single_product_summary hook.
		 *
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'martfury_before_single_product_summary' );
		?>

		<div class="summary entry-summary">

			<?php
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 15
			 * @hooked WC_Structured_Data::generate_product_data() - 60
			 */
			do_action( 'martfury_single_product_summary' );
			?>

		</div>
		<!-- .summary -->
	</div>
</div><!-- #product-<?php the_ID(); ?> -->

