<?php
if ( ! intval( martfury_get_option( 'product_breadcrumb' ) ) ) {
	return;
}

$container_class = apply_filters( 'martfury_catalog_page_header_container', 'container' );
?>

<div class="page-header page-header-catalog">
    <div class="page-breadcrumbs">
        <div class="<?php echo esc_attr( $container_class ); ?>">
			<?php martfury_get_breadcrumbs(); ?>
        </div>
    </div>
</div>