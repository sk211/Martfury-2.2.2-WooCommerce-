<?php
/**
 * @package Martfury
 */

$size = 'martfury-blog-grid';
$css_class = 'blog-wapper col-md-4 col-sm-6 col-xs-6 post-item-grid';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $css_class ); ?>>
	<header class="entry-header">
		<?php martfury_post_format( 'grid', $size ); ?>
	</header>
	<!-- .entry-header -->

	<div class="entry-content">
		<div class="entry-content-top">
			<div class="categories-links">
				<?php the_category( ', ' ); ?>
			</div>
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</div>
		<div class="entry-content-bottom">
			<?php martfury_posted_on(); ?>
		</div>
	</div>
	<!-- .entry-content -->

</article><!-- #post-## -->
