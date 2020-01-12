<?php
$page_header = martfury_get_page_header();
$css_class   = '';
if ( ! in_array( 'title', $page_header ) ) {
	$css_class = 'hide-title';
}

?>

<div class="page-header page-header-page <?php echo esc_attr( $css_class ); ?>">
	<div class="page-breadcrumbs">
		<div class="container">
			<?php martfury_get_breadcrumbs(); ?>
		</div>
	</div>
	<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
</div>