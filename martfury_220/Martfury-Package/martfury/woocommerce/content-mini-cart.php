<?php
global $product;
global $woocommerce;
?>
<div class="popup-mini-cart">
    <h2>
        <?php if ($qty > 1) { ?>
            <?php echo sprintf('%s %s', $qty, esc_html__(' products were successfully added to your cart', 'martfury')); ?>
        <?php } else { ?>
            <?php echo sprintf('%s %s', $qty, esc_html__(' product was successfully added to your cart', 'martfury')); ?>
        <?php } ?>
    </h2>
    <div class="pp-mini-cart-content">
        <div class="mini-cart-detail">
            <div class="product-image">
                <a href="<?php echo esc_url($product->get_permalink()); ?>">
                    <?php echo wp_kses_post($product->get_image('thumbnail')); ?>
                </a>
            </div>
            <div class="product-entry">
                <h2><?php echo wp_kses_post($product->get_title()); ?></h2>
                <p class="price"><?php echo wp_kses_post($product->get_price_html()); ?></p>
                <p class="qty"><?php echo sprintf('%s: %s', esc_html_e('Qty', 'martfury'), $qty); ?></p>
            </div>
        </div>
        <div class="mini-cart-total">
            <p class="mc-total">
                <strong><?php esc_html_e('Subtotal', 'martfury'); ?>
                    :</strong> <?php echo WC()->cart->get_cart_subtotal(); ?>
            </p>
            <div class="mc-buttons">
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>"
                   class="button"><?php echo sprintf('%s (%s)', esc_html__('View cart', 'martfury'), intval($woocommerce->cart->cart_contents_count)); ?></a>
                <a href="<?php echo esc_url(wc_get_checkout_url()); ?>"
                   class="button checkout"><?php esc_html_e('Checkout', 'martfury'); ?></a>
            </div>
        </div>
    </div>
</div>