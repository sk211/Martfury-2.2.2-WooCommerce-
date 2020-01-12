<?php
/**
 * Add to wishlist button template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.8
 */

if ( ! defined( 'YITH_WCWL' ) ) {
    exit;
} // Exit if accessed directly

global $product;

$item = wc_get_product( $product_id );
if( $item ) {
    if( $item->get_type() == 'variation' && $item->get_parent_id() > 0 ) {
        $product_id = $item->get_parent_id();
    }
}

?>

<a href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', $product_id ) )?>" title="<?php echo esc_html($label); ?>" data-rel="tooltip" data-product-id="<?php echo esc_attr($product_id); ?>" data-product-type="<?php echo esc_attr($product_type);?>" class="<?php echo esc_attr($link_classes); ?>">
	<?php echo esc_html($label);?>
</a>
