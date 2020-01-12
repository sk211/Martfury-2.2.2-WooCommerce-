<?php
$page_header = martfury_get_page_header();
$css_class   = '';
if ( ! in_array( 'title', $page_header ) ) {
	$css_class = 'hide-title';
}

?>
<div class="page-header text-center page-header-blog layout-1 <?php echo esc_attr( $css_class ); ?>">
	<div class="container">
		<?php
		the_archive_title( '<h1 class="entry-title">', '</h1>' );
		martfury_get_breadcrumbs();
		?>
	</div>
</div>