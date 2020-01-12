<?php
global $product;
$tabs            = apply_filters( 'woocommerce_product_tabs', array() );
$i               = 0;
$container_class = martfury_get_product_layout() == '6' ? 'martfury-container' : 'container';
$thumbnail_size = 'thumbnail';
if ( function_exists( 'wc_get_image_size' ) ) {
	$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
	$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array(
		$gallery_thumbnail['width'],
		$gallery_thumbnail['height']
	) );
}
?>
<div class="sticky-product-info-wapper" id="sticky-product-info-wapper">
    <div class="<?php echo esc_attr( $container_class ); ?>">
        <div class="sticky-product-inner">
            <div class="sc-product-info">
                <div class="product-thumb">
					<?php echo wp_kses_post( $product->get_image( $thumbnail_size ) ); ?>
                </div>
                <div class="product-name">
                    <h2><?php echo wp_kses_post( $product->get_title() ); ?></h2>
                    <ul class="sc-tabs">
						<?php foreach ( $tabs as $key => $tab ) :
							$css_class = 'tab-' . $key;
							if ( $i == 0 ) {
								$css_class .= ' active';
							}
							$i ++;
							?>
                            <li class="<?php echo esc_attr( $key ); ?>_tab">
                                <a class="<?php echo esc_attr( $css_class ); ?>"
                                   data-tab="<?php echo esc_attr( $key ); ?>"
                                   href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
                            </li>
						<?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="sc-product-cart">
                <p class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
				<?php if ( $product->get_stock_status() != 'outofstock' ) : ?>
                    <a href="#" class="button">
						<?php
						echo apply_filters( 'martfury_product_info_add_to_cart_text', esc_html__( 'Add to Cart', 'martfury' ) );
						?>
                    </a>
				<?php endif; ?>
            </div>
        </div>
    </div>
</div>