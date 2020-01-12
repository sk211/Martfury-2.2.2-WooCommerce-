<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

$css_class = 'col-md-5';
if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
	$css_class = 'col-md-12';
}
?>
<div class="col-xs-12 col-sm-12 col-form-coupon <?php echo esc_attr( $css_class ); ?>">
	<div class="woocommerce-form-coupon-toggle">
		<?php wc_print_notice( apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'martfury' ) . ' <a href="#" class="showcoupon">' . esc_html__( 'Click here to enter your code', 'martfury' ) . '</a>' ), 'notice' ); ?>
	</div>
	<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none">

		<p><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'martfury' ); ?></p>

		<p class="form-row form-row-first">
			<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'martfury' ); ?>" id="coupon_code" value="" />
		</p>

		<p class="form-row form-row-last">
			<button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'martfury' ); ?>" ><?php esc_html_e( 'Apply coupon', 'martfury' ); ?></button>
		</p>

		<div class="clear"></div>
	</form>
</div>
