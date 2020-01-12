<?php
$image_url    = get_the_post_thumbnail_url( get_the_ID(), 'full' );
$css_class    = '';
$featured_img = '';

if ( get_post_meta( get_the_ID(), 'custom_style', true ) ) {
	$image_id = get_post_meta( get_the_ID(), 'post_header_bg', true );
	if ( $image_id ) {
		$image_url = wp_get_attachment_url( $image_id );
	}
}

$css_class = $image_url ? 'has-bg' : '';

?>

<div class="single-post-header text-center layout-2 <?php echo esc_attr( $css_class ); ?>">
	<?php if ( $image_url ) {
		echo sprintf( '<div class="featured-image" style="background-image: url(%s)"></div>', esc_url( $image_url ) );
	} ?>
	<div class="container">
		<div class="page-content">
			<?php
			$categories_list = get_the_category_list( ', ' );
			if ( $categories_list ) { ?>
				<div class="cat-links"><?php echo wp_kses_post($categories_list); ?></div>
				<?php
			}
			the_title( '<h1 class="entry-title">', '</h1>' );
			?>
			<div class="entry-metas">
				<?php martfury_posted_on( false, true ); ?>
			</div>
		</div>
	</div>
</div>