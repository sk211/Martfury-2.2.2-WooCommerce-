<?php
/**
 * @package Martfury
 */

global $wp_query, $mf_post;
$css_class   = 'col-md-12 col-sm-12 col-xs-12 post-item-list';
$size        = 'martfury-blog-grid';
$blog_layout = '';
if ( martfury_is_blog() ) {
	$blog_layout = martfury_get_layout();
}
if ( $blog_layout == 'grid' ) {
	$css_class = 'col-md-4 col-sm-6 col-xs-6 post-item-grid';
	$size      = 'martfury-blog-grid';

} elseif ( $blog_layout == 'small-thumb' ) {
	$css_class = 'col-md-12 col-sm-12 col-xs-12 post-item-small-thumb';
} elseif ( in_array( $blog_layout, array( 'sidebar-content', 'content-sidebar' ) ) ) {
	$css_class    = 'col-md-6 col-sm-6 col-xs-6';
	$current_page = 1;

	if ( martfury_get_option( 'blog_nav_type' ) == 'infinite' ) {
		$current_page = max( 1, get_query_var( 'paged' ) );
	}

	if ( $wp_query->current_post == 0 && $current_page == 1 ) {
		$css_class = 'col-md-12 col-sm-12 col-xs-12 blog-first';
		$size      = 'martfury-blog-list';
	}
} elseif ( $blog_layout == 'masonry' ) {
	$size      = 'martfury-blog-masonry';
	$css_class = 'col-md-4 col-sm-6 col-xs-6 post-item-masonry';
} elseif ( $blog_layout == 'list' ) {
	$size = 'martfury-blog-list';
}

if ( isset( $mf_post['css'] ) ) {
	$css_class = $mf_post['css'];
}

if ( ! has_post_thumbnail( get_the_ID() ) ) {
	$css_class .= ' blog-no-image';
}

$css_class      .= ' blog-wapper';
$excerpt_length = intval( martfury_get_option( 'blog_excerpt_length' ) );
$cat_list       = get_the_category();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $css_class ); ?>>
	<header class="entry-header">
		<?php martfury_post_format( $blog_layout, $size ); ?>
	</header>
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
						$i++;
						echo '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . $cat_name . '</a>';
					} ?>
				</div>
			<?php endif; ?>
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<?php if ( in_array( $blog_layout, array( 'list', 'small-thumb' ) ) ) : ?>
				<div class="entry-desc">
					<?php echo martfury_content_limit( get_the_excerpt(), $excerpt_length, '' ); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="entry-content-bottom">
			<?php martfury_posted_on(); ?>
		</div>
	</div>
	<!-- .entry-content -->

</article><!-- #post-## -->
