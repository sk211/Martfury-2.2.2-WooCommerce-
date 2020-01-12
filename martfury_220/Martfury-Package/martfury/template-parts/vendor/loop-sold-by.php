<?php
if ( ! function_exists( 'dokan_get_store_url' ) ) {
	return;
}

global $product;
$author_id = get_post_field( 'post_author', $product->get_id() );
$author    = get_user_by( 'id', $author_id );
if ( empty( $author ) ) {
	return;
}

$shop_info = get_user_meta( $author_id, 'dokan_profile_settings', true );
$shop_name = $author->display_name;
if ( $shop_info && isset( $shop_info['store_name'] ) && $shop_info['store_name'] ) {
	$shop_name = $shop_info['store_name'];
}
?>
<div class="sold-by-meta">
	<span class="sold-by-label"><?php esc_html_e( 'Sold By:', 'martfury' ); ?> </span>
	<a href="<?php echo esc_url( dokan_get_store_url( $author_id ) ); ?>"><?php echo esc_html( $shop_name ); ?></a>
</div>

