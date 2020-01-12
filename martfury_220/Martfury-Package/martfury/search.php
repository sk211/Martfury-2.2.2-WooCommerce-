<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Martfury
 */

get_header(); ?>

<section id="primary" class="content-area <?php martfury_content_columns(); ?>">
	<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>
			<div class="row">
				<div class="mf-post-list">
					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>

						<?php
						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/content', 'search' );
						?>

					<?php endwhile; ?>
				</div>
			</div>
			<?php martfury_numeric_pagination(); ?>
		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

	</main><!-- #main -->
</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
