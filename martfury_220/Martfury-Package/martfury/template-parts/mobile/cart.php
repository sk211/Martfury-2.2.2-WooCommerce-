<?php
if ( is_page_template( 'template-coming-soon-page.php' ) ) {
	return;
}

if ( ! function_exists( 'woocommerce_mini_cart' ) ) {
	return;
}

$cart_id    = get_option( 'woocommerce_cart_page_id' );
$cart_title = $cart_id ? '<h2>' . get_the_title( $cart_id ) . '</h2>' : '';

?>
<div id="mf-cart-mobile" class="mf-cart-mobile mf-els-item woocommerce mini-cart">
    <div class="mobile-cart-header">
		<?php echo wp_kses_post( $cart_title ); ?>
        <a class="close-cart-mobile"><i class="icon-cross"></i></a>
    </div>
    <div class="widget-canvas-content">
        <div class="widget_shopping_cart_content">
			<?php woocommerce_mini_cart(); ?>
        </div>
        <div class="widget-footer-cart">
            <a href="#" class="close-cart-mobile"><?php esc_html_e( 'Close', 'martfury' ); ?></a>
        </div>
    </div>
</div>
