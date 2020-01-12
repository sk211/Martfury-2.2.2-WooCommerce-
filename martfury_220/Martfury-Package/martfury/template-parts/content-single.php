<?php
/**
 * @package Martfury
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post-wrapper' ); ?>>
	<header class="entry-header">
		<?php do_action( 'martfury_before_content_single' ); ?>
	</header>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'martfury' ),
				'after'  => '</div>',
			)
		);
		?>
	</div>
	<!-- .entry-content -->

	<?php martfury_entry_footer(); ?>
	<!-- .entry-footer -->
</article><!-- #post-## -->
