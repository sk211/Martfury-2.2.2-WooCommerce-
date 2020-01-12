<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Martfury
 */

get_header(); ?>

<div id="primary" class="content-area <?php martfury_content_columns(); ?>">
	<main id="main" class="site-main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'template-parts/content', 'single' ); ?>

			<?php martfury_author_box(); ?>
			<?php martfury_related_posts() ?>
			<?php martfury_post_nav(); ?>

			<?php
			// If comments are open or we have at least one comment, load up the comment template
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>

		<?php endwhile; // end of the loop. ?>

	</main>
	<!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
