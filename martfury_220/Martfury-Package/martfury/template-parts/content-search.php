<?php
/**
 * @package Martfury
 */

$css_class = 'col-md-12 col-sm-12 col-xs-12 post-item-list';
$size      = 'martfury-blog-list';

$css_class .= ' blog-wapper';
$excerpt_length = intval( martfury_get_option( 'blog_excerpt_length' ) );
$cat_list       = get_the_category();

if ( ! has_post_thumbnail() ) {
	$css_class .= ' no-thumb';
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $css_class ); ?>>
	<?php if ( has_post_thumbnail() ): ?>
	<header class="entry-header">
		<?php martfury_post_format( 'list', $size ); ?>
	</header>
	<?php endif; ?>
	<!-- .entry-header -->

	<div class="entry-content">
		<div class="entry-content-top">
			<?php if ( ! empty( $cat_list ) ) : ?>
				<div class="categories-links">
					<?php
					$i = 0;
					foreach ( $cat_list as $cat ) {
						$cat_name = esc_html( $cat->name );
						if ( $i != 0 ) {
							$cat_name = ', ' . $cat_name;
						}
						$i ++;
						echo '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . $cat_name . '</a>';
					} ?>
				</div>
			<?php endif; ?>
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<div class="entry-desc">
				<?php echo martfury_content_limit( get_the_excerpt(), $excerpt_length, '' ); ?>
			</div>
		</div>
		<div class="entry-content-bottom">
			<?php martfury_posted_on(); ?>
		</div>
	</div>
	<!-- .entry-content -->

</article><!-- #post-## -->
