<?php
/**
 * Custom functions for displaying comments
 *
 * @package Martfury
 */

/**
 * Comment callback function
 *
 * @param object $comment
 * @param array  $args
 * @param int    $depth
 */

if ( ! function_exists( 'martfury_comment' ) ) :
	function martfury_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		extract( $args, EXTR_SKIP );

		$avatar = '';
		if ( $args['avatar_size'] != 0 ) {
			$avatar = get_avatar( $comment, $args['avatar_size'] );
		}

		$classes = get_comment_class( empty( $args['has_children'] ) ? '' : 'parent' );
		$classes = $classes ? implode( ' ', $classes ) : $classes;

		$comments = array(
			'comment_parent'      => 0,
			'comment_ID'          => get_comment_ID(),
			'comment_class'       => $classes,
			'comment_avatar'      => $avatar,
			'comment_author_link' => get_comment_author_link(),
			'comment_link'        => get_comment_link( get_comment_ID() ),
			'comment_date'        => get_comment_date(),
			'comment_time'        => get_comment_time(),
			'comment_approved'    => $comment->comment_approved,
			'comment_text'        => get_comment_text(),
			'comment_reply'       => get_comment_reply_link( array_merge( $args, array( 'add_below' => 'comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) )

		);

		echo martfury_comment_template( $comments );

	}

endif;

/**
 * Comment Template function
 *
 * @param object $comment
 * @param array  $args
 * @param int    $depth
 */

if ( ! function_exists( 'martfury_comment_template' ) ) :
	function martfury_comment_template( $comments ) {

		$output = array();

		$output[]  = sprintf( '<li id="comment-%s" class="%s">', esc_attr( $comments['comment_ID'] ), esc_attr( $comments['comment_class'] ) );
		$output[]  = sprintf( '<article id="div-comment-%s" class="comment-body">', $comments['comment_ID'] );
		$output [] = sprintf(
			'<header class="comment-meta">' .
			'<div class="comment-author vcard">%s</div>' .
			'</header>',
			$comments['comment_avatar']
		);
		$output[]  = '<div class="comment-content"><div class="comment-metadata">';
		$output[]  = sprintf( '<cite class="fn">%s - </cite>', $comments['comment_author_link'] );
		$date      = sprintf( esc_html__( '%1$s at %2$s', 'martfury' ), $comments['comment_date'], $comments['comment_time'] );
		$output[]  = sprintf( '<a href="%s" class="date">%s</a>', esc_url( $comments['comment_link'] ), $date );
		$output[]  = '</div>';
		if ( $comments['comment_approved'] == '0' ) {
			$output[] = sprintf( '<em class="comment-awaiting-moderation">%s</em>', esc_html__( 'Your comment is awaiting moderation.', 'martfury' ) );
		} else {
			$output[] = $comments['comment_text'];
		}

		$output[] = '<div class="reply">';
		$output[] = $comments['comment_reply'];

		if ( current_user_can( 'edit_comment', $comments['comment_ID'] ) ) {
			$output[] = sprintf( '<a class="comment-edit-link" href="%s">%s</a>', esc_url( admin_url( 'comment.php?action=editcomment&amp;c=' ) . $comments['comment_ID'] ), esc_html__( 'Edit', 'martfury' ) );
		}

		$output[] = '</div></div></article>';

		return implode( ' ', $output );
	}

endif;