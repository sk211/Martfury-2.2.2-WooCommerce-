<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package MrBara
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

$comments_number = get_comments_number();
$comments_class  = $comments_number ? 'has-comments' : '';

?>

<div id="comments" class="comments-area">
	<div class="container">
		<div class="row">
			<div class="col-md-8  col-md-offset-2 col-comment">
				<h2 class="comments-title <?php echo esc_attr( $comments_class ); ?>">
					<?php
					if ( $comments_number ) {
						if ( '1' === $comments_number ) {
							/* translators: %s: post title */
							printf( _x( '1 Comment', 'comments title', 'martfury' ) );
						} else {
							printf(
							/* translators: 1: number of comments, 2: post title */
								_x(
									'%s Comments',
									'comments title',
									'martfury'
								),
								number_format_i18n( $comments_number )
							);
						}
					}
					?>
				</h2>

				<ol class="comment-list <?php echo esc_attr( $comments_class ); ?>">
					<?php
					wp_list_comments(
						array(
							'avatar_size' => 70,
							'short_ping'  => true,
							'callback'    => 'martfury_comment'
						)
					);
					?>
				</ol>
				<div class="comments-links">
					<?php
					paginate_comments_links(
						array(
							'prev_text' => '<span class="icon-arrow-left"></span>' . '<span class="screen-reader-text">' . esc_html__( 'Previous', 'martfury' ) . '</span>',
							'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next', 'martfury' ) . '</span>' . '</span><span class="icon-arrow-right"></span>',
						)
					);
					?>
				</div>
				<?php


				// If comments are closed and there are comments, let's leave a little note, shall we?
				if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>

					<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'martfury' ); ?></p>
					<?php
				endif;
				$comment_field = '<p class="comment-form-comment"><textarea required id="comment" placeholder="' . esc_html__( 'Content', 'martfury' ) . '" name="comment" cols="45" rows="7" aria-required="true"></textarea></p>';
				comment_form(
					array(
						'format'        => 'xhtml',
						'comment_field' => $comment_field,
					)
				)
				?>
			</div>
		</div>
	</div>
</div><!-- #comments -->
