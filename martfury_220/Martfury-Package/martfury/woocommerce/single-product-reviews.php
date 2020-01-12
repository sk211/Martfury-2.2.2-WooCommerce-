<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $product;

if ( ! comments_open() ) {
	return;
}

$average_rating = round( $product->get_average_rating(), 2 );

$show_rating = true;

$class_review = 'col-md-7 col-sm-12';
if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' || $average_rating == 0 ) {
	$show_rating  = false;
	$class_review = 'col-md-12 col-sm-12';
}

?>
<div id="reviews" class="woocommerce-Reviews">
	<div class="mf-product-rating row">
		<?php if ( $show_rating ) : ?>
			<div class="col-md-5 col-sm-12 col-xs-12 col-average-rating">
				<div class="average-rating">
					<h6 class="average-label"><?php esc_html_e( 'Average Rating', 'martfury' ); ?></h6>

					<?php if ( $average_rating ) : ?>
						<h3 class="average-value"><?php echo number_format( $average_rating, 2 ); ?></h3>
					<?php endif; ?>
					<?php
					if ( function_exists( 'woocommerce_template_single_rating' ) ) {
						woocommerce_template_single_rating();
					}
					?>
					<div class="bar-rating">
						<?php
						$rating_arr = $product->get_rating_counts();
						$count      = $product->get_rating_count();
						for ( $i = 5; $i > 0; $i -- ) {
							$rating_count = 0;
							$rating_per   = 0;
							if ( isset( $rating_arr[$i] ) ) {
								$rating_count = $rating_arr[$i];
								$rating_per   = round( ( $rating_count / $count ) * 100, 2 );
							}

							$rating_label = $i . ' ' . esc_html__( 'Star', 'martfury' );

							printf(
								'<div class="star-item %s-stars">' .
								'<div class="slabel">' .
								'%s' .
								'</div>' .
								'<div class="sbar">' .
								'<div class="bar-content">' .
								'<span style="width: %s"></span>' .
								'</div>' .
								'</div>' .
								'<div class="svalue">' .
								'%s' .
								'</div>' .
								'</div>',
								esc_attr( $i ),
								$rating_label,
								esc_attr( $rating_per ) . '%',
								esc_attr( $rating_per ) . '%'
							);
						}
						?>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="<?php echo esc_attr( $class_review ); ?> col-xs-12 col-review_form">
			<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>

				<div id="review_form_wrapper">
					<div id="review_form">
						<?php
						$commenter = wp_get_current_commenter();

						$comment_form = array(
							'title_reply'         => esc_html__( 'Submit your review', 'martfury' ),
							'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'martfury' ),
							'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
							'title_reply_after'   => '</span>',
							'comment_notes_after' => '',
							'fields'              => array(
								'author' => '<p class="comment-form-author">' .
									'<input id="author" name="author" required placeholder ="' . esc_html__( 'Your Name', 'martfury' ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" required /></p>',
								'email'  => '<p class="comment-form-email">' .
									'<input id="email" name="email" type="email" required  placeholder ="' . esc_html__( 'Your Email', 'martfury' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" aria-required="true" required /></p>',
							),
							'logged_in_as'        => '',
							'comment_field'       => '',
							'label_submit'        => esc_html__( 'Submit Review', 'martfury' ),
							'format'              => 'xhtml'
						);

						if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
							$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a review.', 'martfury' ), esc_url( $account_page_url ) ) . '</p>';
						}

						if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
							$comment_form['comment_field'] = '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating of this product', 'martfury' ) . '</label><select name="rating" id="rating" aria-required="true" required>
							<option value="">' . esc_html__( 'Rate&hellip;', 'martfury' ) . '</option>
							<option value="5">' . esc_html__( 'Perfect', 'martfury' ) . '</option>
							<option value="4">' . esc_html__( 'Good', 'martfury' ) . '</option>
							<option value="3">' . esc_html__( 'Average', 'martfury' ) . '</option>
							<option value="2">' . esc_html__( 'Not that bad', 'martfury' ) . '</option>
							<option value="1">' . esc_html__( 'Very poor', 'martfury' ) . '</option>
						</select></div>';
						}
						$comment_form['comment_field'] .= '<p class="comment-form-comment"><textarea id="comment" required placeholder ="' . esc_html__( 'Write your review here...', 'martfury' ) . '" name="comment" cols="45" rows="8" aria-required="true" required></textarea></p>';

						comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
						?>
					</div>
				</div>

			<?php else : ?>

				<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'martfury' ); ?></p>

			<?php endif; ?>
		</div>
	</div>
	<div id="comments">
		<h2 class="woocommerce-Reviews-title"><?php
			if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && ( $count = $product->get_review_count() ) ) {
				/* translators: 1: reviews count 2: product name */
				printf( esc_html( _n( '%1$s Review For This Product', '%1$s Reviews For This Product', $count, 'martfury' ) ), esc_html( $count ) );
			} else {
				esc_html_e( 'Reviews', 'martfury' );
			}
			?></h2>

		<?php if ( have_comments() ) : ?>

			<ol class="commentlist">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
			</ol>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="woocommerce-pagination">';
				paginate_comments_links(
					apply_filters(
						'woocommerce_comment_pagination_args', array(
							'prev_text' => '&larr;',
							'next_text' => '&rarr;',
							'type'      => 'list',
						)
					)
				);
				echo '</nav>';
			endif; ?>

		<?php else : ?>

			<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'martfury' ); ?></p>

		<?php endif; ?>
	</div>
	<div class="clear"></div>
</div>
