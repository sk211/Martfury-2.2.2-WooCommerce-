<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Martfury
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

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

</article><!-- #post-## -->
