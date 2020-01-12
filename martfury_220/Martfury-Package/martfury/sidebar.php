<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Martfury
 */

if ( ! in_array( martfury_get_layout(), array( 'sidebar-content', 'content-sidebar', 'small-thumb' ) ) ) {
	return;
}

$sidebar       = 'blog-sidebar';
$sidebar_class = '';
if ( is_singular( 'post' ) ) {
	$sidebar = 'post-sidebar';
	if ( ! is_active_sidebar( $sidebar ) ) {
		$sidebar = 'blog-sidebar';
	}
} elseif ( martfury_is_vendor_page() ) {
	$sidebar = 'vendor_sidebar';
} elseif ( martfury_is_catalog() ) {
	$sidebar = 'catalog-sidebar';
	if ( function_exists( 'is_product_category' ) && is_product_category() ) {
		$cat_sidebar    = '';
		$queried_object = get_queried_object();
		$term_id        = $queried_object->term_id;
		$cat_ancestors  = get_ancestors( $term_id, 'product_cat' );
		$cat_sidebar    = get_term_meta( $term_id, 'mf_cat_sidebar', true );

		if ( empty( $cat_sidebar ) && count( $cat_ancestors ) > 0 ) {
			$parent_id   = $cat_ancestors[0];
			$cat_sidebar = get_term_meta( $parent_id, 'mf_cat_sidebar', true );
		}


		$sidebar_class = 'catalog-sidebar';

		$sidebar = ! empty( $cat_sidebar ) ? $cat_sidebar : $sidebar;

	}

} elseif ( is_singular( 'product' ) ) {
	$sidebar = 'product-sidebar';
} elseif ( is_page() ) {
	$sidebar = 'page-sidebar';
}

$sidebar_class .= ' ' . $sidebar;

?>
<aside id="primary-sidebar"
       class="widgets-area primary-sidebar col-md-3 col-sm-12 col-xs-12 <?php echo esc_attr( $sidebar_class ) ?>">
	<?php
	if ( is_active_sidebar( $sidebar ) ) {
		dynamic_sidebar( $sidebar );
	}

	?>
</aside><!-- #secondary -->

