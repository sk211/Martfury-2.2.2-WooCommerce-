<?php
global $martfury_woocommerce;
$title_class     = '';
$container_class = 'container';
if ( ! empty( $martfury_woocommerce ) && method_exists( $martfury_woocommerce, 'get_catalog_elements' ) ) {
	$elements = $martfury_woocommerce->get_catalog_elements();
	if ( empty( $elements ) || ! in_array( 'title', $elements ) ) {
		$title_class = 'hide-title';
	}
}

$container_class = apply_filters( 'martfury_catalog_page_header_container', 'container' );

?>

<div class="page-header page-header-catalog">
    <div class="page-title <?php echo esc_attr( $title_class ); ?>">
        <div class="<?php echo esc_attr( $container_class ); ?>">
			<?php the_archive_title( '<h1 class="entry-title">', '</h1>' ); ?>
        </div>
    </div>
	<?php if ( ! empty( $elements ) && in_array( 'breadcrumb', $elements ) ) : ?>
        <div class="page-breadcrumbs">
            <div class="<?php echo esc_attr( $container_class ); ?>">
				<?php martfury_get_breadcrumbs(); ?>
            </div>
        </div>
	<?php endif; ?>
</div>