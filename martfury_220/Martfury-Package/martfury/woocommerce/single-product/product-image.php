<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;
$columns           = martfury_get_option( 'product_thumbnail_numbers' );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'woocommerce-product-gallery--' . ( $product->get_image_id() ? 'with-images' : 'without-images' ),
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
) );

$video_first       = get_post_meta( $product->get_id(), 'video_position', true );
$wrapper_classes[] = $video_first == '2' ? 'video-first' : '';

?>

<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>"
     data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
    <figure class="woocommerce-product-gallery__wrapper">
		<?php

		if ( $video_first == '2' && is_singular( 'product' ) ) {
			echo martfury_product_video();
		}

		if ( $product->get_image_id() ) {
			$html = wc_get_gallery_image_html( $post_thumbnail_id, true );
		} else {
			$html = '<div class="woocommerce-product-gallery__image--placeholder">';
			$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'martfury' ) );
			$html .= '</div>';
		}

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );

		do_action( 'woocommerce_product_thumbnails' );
		?>
    </figure>
	<?php do_action( 'woocommerce_after_product_gallery' ); ?>
	<?php
	$images_dg  = get_post_meta( $product->get_id(), 'product_360_view', true );
	if ( $images_dg ) {
		?>
        <div class="product-degree-images"><i class="icon-camera-flip"></i><span class="number">360<small>0</small></span>
        </div>
		<?php
	}
	?>
    <div class="product-image-ms ms-image-zoom"><?php esc_html_e( 'Roll over image to zoom in', 'martfury' ); ?></div>
    <div class="product-image-ms ms-image-view hide"><?php esc_html_e( 'Click to open expanded view', 'martfury' ); ?></div>
</div>
