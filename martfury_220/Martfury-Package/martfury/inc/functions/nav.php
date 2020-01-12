<?php
/**
 * Custom functions for nav menu
 *
 * @package Martfury
 */


/**
 * Display numeric pagination
 *
 * @since 1.0
 * @return void
 */
function martfury_numeric_pagination() {
	global $wp_query;

	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}

	$nav_type = '';

	if( martfury_is_blog() ) {
		$nav_type  = martfury_get_option( 'blog_nav_type' );
	}
	$next_text = '<i class="ion-ios-arrow-right"></i>';
	$nav_class = '';
	if ( $nav_type == 'infinite' ) {
		$next_text = sprintf(
			'<div id="mf-infinite-loading" class="nav-previous"><span class="dots-loading"><span>.</span><span>.</span><span>.</span>%s<span>.</span><span>.</span><span>.</span></span></div>',
			esc_html__( 'Loading', 'martfury' )
		);

		$nav_class = 'infinite';
	}

	?>
	<nav class="navigation paging-navigation numeric-navigation <?php echo esc_attr( $nav_class ); ?>">
		<?php
		$big  = 999999999;
		$args = array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'total'     => $wp_query->max_num_pages,
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'prev_text' => '<i class="ion-ios-arrow-left"></i>',
			'next_text' => $next_text,
			'type'      => 'plain',
		);

		echo paginate_links( $args );
		?>
	</nav>
	<?php
}

/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since 1.0
 * @return void
 */
function martfury_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation">
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
				<div class="nav-previous"><?php next_posts_link( sprintf( '<span class="meta-nav"><i class="icon-arrow-left"></i> </span> %s', esc_html__( 'Older posts', 'martfury' ) ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
				<div class="nav-next"><?php previous_posts_link( sprintf( '%s <span class="meta-nav"><i class="icon-arrow-right"></i></span>', esc_html__( 'Newer posts', 'martfury' ) ) ); ?></div>
			<?php endif; ?>

		</div>
		<!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}


/**
 * Display navigation to next/previous post when applicable.
 *
 * @since 1.0
 * @return void
 */
function martfury_post_nav() {
	the_post_navigation(
		array(
			'prev_text' => '<span class="screen-reader-text">' . esc_html__( 'Previous Post', 'martfury' ) . '</span><span class="icon-arrow-left"></span><span aria-hidden="true" class="nav-subtitle">' . esc_html__( 'Previous', 'martfury' ) . '</span><br> <span class="nav-title">%title</span>',
			'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next Post', 'martfury' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . esc_html__( 'Next', 'martfury' ) . '</span><span class="icon-arrow-right"></span><br> <span class="nav-title">%title</span>',
		)
	);

}

