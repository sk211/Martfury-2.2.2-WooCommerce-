<?php
/**
 * Custom functions for entry.
 *
 * @package Martfury
 */

/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since 1.0.0
 */
function martfury_posted_on( $show_cat = false, $show_character = false ) {
	global $post;
	$time_string   = '<time class="entry-date published updated" datetime="%s">%s</time>';
	$time_string   = sprintf(
		$time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);
	$archive_year  = get_the_time( 'Y' );
	$archive_month = get_the_time( 'm' );
	$archive_day   = get_the_time( 'd' );

	$posted_on = '<a href="' . esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ) . '" class="entry-meta" rel="bookmark">' . $time_string . '</a>';

	if ( $show_character ) {
		$posted_on .= '<span class="sep"> /</span>';
	}

	$author_id = $post->post_author;
	$posted_on .= '<span class="entry-author entry-meta">' . esc_html__( ' by ', 'martfury' ) . '<a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID', $author_id ) ) ) . '">' . esc_html( get_the_author_meta( 'display_name', $author_id ) ) . '</a></span>';

	if ( $show_cat ) {

		if ( $show_character ) {
			$posted_on .= '<span class="sep"> /</span>';
		}

		$categories_list = get_the_category_list( '<span class="sep">, </span>' );
		if ( $categories_list ) {
			$posted_on .= sprintf( '<span class="cat-links entry-meta">' . esc_html__( ' in %s', 'martfury' ) . '</span>', $categories_list );
		}
	}

	echo wp_kses_post( $posted_on );
}

/**
 * Prints HTML with meta information for the categories, tags and comments.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'martfury_entry_footer' ) ) :
	function martfury_entry_footer() {
		// Hide category and tag text for pages.
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', ', ' );

		if ( empty( $tags_list ) && ! intval( martfury_get_option( 'show_post_socials' ) ) ) {
			return;
		}

		echo '<footer class="entry-footer">';
		if ( $tags_list ) {
			echo sprintf( '<span class="tags-links"><strong>' . esc_html__( 'Tags', 'martfury' ) . ': </strong>%1$s</span>', $tags_list );
		}

		if ( martfury_get_option( 'show_post_socials' ) && function_exists( 'martfury_addons_share_link_socials' ) ):
			echo sprintf( '<div class="footer-socials">' );
			$image = get_the_post_thumbnail_url( get_the_ID(), 'full' );
			martfury_addons_share_link_socials( get_the_title(), get_permalink(), $image );
			echo sprintf( '</div>' );
		endif;

	}

endif;

/**
 * Get or display limited words from given string.
 * Strips all tags and shortcodes from string.
 *
 * @since 1.0.0
 *
 * @param integer $num_words The maximum number of words
 * @param string $more More link.
 * @param bool $echo Echo or return output
 *
 * @return string Limited content.
 */
function martfury_content_limit( $content, $num_words, $more = "&hellip;" ) {
	// Strip tags and shortcodes so the content truncation count is done correctly
	$content = strip_tags( strip_shortcodes( $content ), apply_filters( 'martfury_content_limit_allowed_tags', '<script>,<style>' ) );

	// Remove inline styles / scripts
	$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

	// Truncate $content to $max_char
	$content = wp_trim_words( $content, $num_words );

	if ( $more ) {
		$output = sprintf(
			'<p>%s <a href="%s" class="more-link" title="%s">%s</a></p>',
			$content,
			get_permalink(),
			sprintf( esc_html__( 'Continue reading &quot;%s&quot;', 'martfury' ), the_title_attribute( 'echo=0' ) ),
			esc_html( $more )
		);
	} else {
		$output = sprintf( '<p>%s</p>', $content );
	}

	return $output;
}


/**
 * Show entry thumbnail base on its format
 *
 * @since  1.0
 */
function martfury_entry_thumbnail( $size = 'thumbnail' ) {
	$html      = '';
	$css_class = 'format-' . get_post_format();

	if ( is_singular( 'post' ) ) {
		$size = '1170x635';
	}

	switch ( get_post_format() ) {
		case 'gallery':
			$image_ids = get_post_meta( get_the_ID(), 'images', false );

			$gallery = array();
			foreach ( $image_ids as $id ) {
				$image     = martfury_get_image_by_size(
					array(
						'attach_id'  => $id,
						'thumb_size' => $size,
					)
				);
				$gallery[] = '<li>' . $image . '</li>';

			}
			$html .= '<ul class="slides">' . implode( '', $gallery ) . '</ul>';
			break;
		case 'audio':
			$html = martfury_post_format_audio();
			break;

		case 'video':
			$html = martfury_post_format_video();
			break;

		case 'link':
			$html = martfury_post_format_link();
			break;
		case 'quote':
			$html = martfury_post_format_quote();
			break;

		default:
			$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
			$thumb             = martfury_get_image_by_size(
				array(
					'attach_id'  => $post_thumbnail_id,
					'thumb_size' => $size,
				)
			);

			$thumb = $thumb ? '<div class="featured-image-post">' . $thumb . '</div>' : '';

			if ( is_singular( 'post' ) ) {
				$html .= $thumb;

			} else {
				$html .= '<a class="entry-image" href="' . get_permalink() . '">' . $thumb . '</a>';

			}
			break;
	}

	if ( $html = apply_filters( __FUNCTION__, $html, get_post_format() ) ) {
		$css_class = esc_attr( $css_class );
		echo "<div id='mf-single-entry-format' class='entry-format $css_class'>$html</div>";
	}
}

/**
 * Get author meta
 *
 * @since  1.0
 *
 */
if ( ! function_exists( 'martfury_author_box' ) ) :
	function martfury_author_box() {

		if ( ! intval( martfury_get_option( 'post_author_box' ) ) ) {
			return;
		}

		?>
        <div class="post-author-box clearfix">
            <div class="post-author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'ID' ), 130 ); ?>
            </div>
            <div class="post-author-desc">
                <div class="post-author-name">
                    <h3><?php the_author_meta( 'display_name' ); ?></h3>

                    <p><?php the_author_meta( 'description' ); ?></p>
                </div>
            </div>
        </div>
		<?php
	}
endif;

/**
 * Get related post
 *
 * @since  1.0
 *
 */
if ( ! function_exists( 'martfury_related_posts' ) ) :
	function martfury_related_posts() {

		if ( ! intval( martfury_get_option( 'related_posts' ) ) ) {
			return;
		}

		global $post;

		$numbers = intval( martfury_get_option( 'related_posts_number' ) );
		$related = new WP_Query(
			array(
				'post_type'           => 'post',
				'posts_per_page'      => $numbers,
				'ignore_sticky_posts' => 1,
				'no_found_rows'       => 1,
				'order'               => 'rand',
				'post__not_in'        => array( $post->ID ),
				'tax_query'           => array(
					'relation' => 'OR',
					array(
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => martfury_get_related_terms( 'category', $post->ID ),
						'operator' => 'IN',
					),
					array(
						'taxonomy' => 'post_tag',
						'field'    => 'term_id',
						'terms'    => martfury_get_related_terms( 'post_tag', $post->ID ),
						'operator' => 'IN',
					),
				),
			)
		);

		if ( $related->post_count == 0 ) {
			return;
		}

		$related_title = martfury_get_option( 'related_posts_title' );

		?>
        <div class="mf-related-posts" id="mf-related-posts">
            <h2 class="related-title"><?php echo esc_html( $related_title ); ?></h2>

            <div class="row">
                <div class="related-posts-list">
					<?php
					while ( $related->have_posts() ) : $related->the_post();
						get_template_part( 'template-parts/content', 'related' );
					endwhile;
					wp_reset_postdata();
					?>
                </div>
            </div>
        </div>
		<?php
	}
endif;

/**
 * Get single post style
 *
 * @since  1.0
 *
 */
if ( ! function_exists( 'martfury_single_post_style' ) ) :
	function martfury_single_post_style() {

		$post_style = martfury_get_option( 'single_post_style' );

		if ( get_post_meta( get_the_ID(), 'custom_style', true ) ) {
			$post_style = get_post_meta( get_the_ID(), 'post_style', true );
		}

		return apply_filters( 'martfury_single_post_style', $post_style );
	}
endif;

/**
 * Get breadcrumbs
 *
 * @since  1.0.0
 *
 * @return string
 */

if ( ! function_exists( 'martfury_get_breadcrumbs' ) ) :
	function martfury_get_breadcrumbs() {
		$page_header = martfury_get_page_header();
		if ( ! $page_header ) {
			return;
		}

		if ( ! in_array( 'breadcrumb', $page_header ) ) {
			return;
		}
		ob_start();
		?>
        <ul class="breadcrumbs">
			<?php
			martfury_breadcrumbs(
				array(
					'before'   => '',
					'taxonomy' => function_exists( 'is_woocommerce' ) && is_woocommerce() ? 'product_cat' : 'category',
				)
			);
			?>
        </ul>
		<?php
		echo ob_get_clean();
	}

endif;

/**
 * Check is blog
 *
 * @since  1.0
 */

if ( ! function_exists( 'martfury_is_blog' ) ) :
	function martfury_is_blog() {
		if ( ( is_archive() || is_author() || is_category() || is_home() || is_tag() ) && 'post' == get_post_type() ) {
			return true;
		}

		return false;
	}

endif;

/**
 * Check is catalog
 *
 * @return bool
 */
if ( ! function_exists( 'martfury_is_catalog' ) ) :
	function martfury_is_catalog() {

		if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
			return true;
		}

		if ( is_tax( 'product_brand' ) ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Check has vendor
 *
 * @return bool
 */
if ( ! function_exists( 'martfury_has_vendor' ) ) :
	function martfury_has_vendor() {

		if ( class_exists( 'WCV_Vendors' ) ) {
			return true;
		}

		if ( class_exists( 'WCMp' ) ) {
			return true;
		}

		if ( class_exists( 'WeDevs_Dokan' ) ) {
			return true;
		}

		if ( class_exists( 'WCFMmp' ) ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Check is vendor page
 *
 * @return bool
 */
if ( ! function_exists( 'martfury_is_vendor_page' ) ) :
	function martfury_is_vendor_page() {

		if ( function_exists( 'dokan_is_store_page' ) && dokan_is_store_page() ) {
			return true;
		}

		if ( martfury_is_wc_vendor_page() ) {
			return true;
		}

		if ( martfury_is_dc_vendor_store() ) {
			return true;
		}

		if ( function_exists( 'wcfm_is_store_page' ) && wcfm_is_store_page() ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Check is vendor page
 *
 * @return bool
 */
if ( ! function_exists( 'martfury_is_wc_vendor_page' ) ) :
	function martfury_is_wc_vendor_page() {

		if ( class_exists( 'WCV_Vendors' ) && method_exists( 'WCV_Vendors', 'is_vendor_page' ) ) {
			return WCV_Vendors::is_vendor_page();
		}

		return false;
	}
endif;

/**
 * Check is vendor page
 *
 * @return bool
 */
if ( ! function_exists( 'martfury_is_dc_vendor_store' ) ) :
	function martfury_is_dc_vendor_store() {

		if ( ! class_exists( 'WCMp' ) ) {
			return false;
		}

		global $WCMp;
		if ( empty( $WCMp ) ) {
			return false;
		}

		if ( is_tax( $WCMp->taxonomy->taxonomy_name ) ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Check is vendor dashboard
 *
 * @return bool
 */
if ( ! function_exists( 'martfury_is_vendor_dashboard' ) ) :
	function martfury_is_vendor_dashboard() {

		if ( ! function_exists( 'is_vendor_dashboard' ) ) {
			return false;
		}

		if ( ! function_exists( 'is_user_wcmp_vendor' ) ) {
			return false;
		}

		if ( is_vendor_dashboard() && is_user_logged_in() && is_user_wcmp_vendor( get_current_user_id() ) ) {
			return true;
		}

		return false;
	}
endif;


/**
 * Check is portfolio
 *
 * @since  1.0
 */

if ( ! function_exists( 'martfury_is_portfolio' ) ) :
	function martfury_is_portfolio() {
		if ( is_post_type_archive( 'portfolio_project' ) || is_tax( 'portfolio_category' ) ) {
			return true;
		}

		return false;
	}
endif;


/**
 * Get post format
 *
 * @since  1.0
 */

if ( ! function_exists( 'martfury_post_format' ) ) :
	function martfury_post_format( $blog_layout, $size ) {

		$post_format = get_post_format();
		$post_format = $post_format ? $post_format : 'image';
		$css_class   = 'format-' . $post_format;

		$html = '';
		if ( $blog_layout == 'list' ) {

			switch ( get_post_format() ) {
				case 'gallery':
					$html = martfury_post_format_gallery( $size );
					break;
				case 'audio':
					$html = martfury_post_format_audio();
					break;

				case 'video':
					$html = martfury_post_format_video();
					break;

				case 'link':
					$html = martfury_post_format_link();
					break;
				case 'quote':
					$html = martfury_post_format_quote();
					break;

				default:
					$html = martfury_post_format_image( $size );
					break;
			}
		} elseif ( $blog_layout == 'small-thumb' ) {
			switch ( get_post_format() ) {
				case 'audio':
					$html = martfury_post_format_audio();
					break;
				case 'link':
					$html = martfury_post_format_link();
					break;
				case 'quote':
					$html = martfury_post_format_quote();
					break;
				default:
					$html = martfury_post_format_image( $size );
					break;
			}
		} else {
			$html = martfury_post_format_image( $size );
		}

		if ( $html ) {
			echo "<div class='entry-format $css_class'>$html</div>";
		}

	}
endif;

/**
 * Get post format quote
 *
 * @since  1.0
 */

if ( ! function_exists( 'martfury_post_format_quote' ) ) :
	function martfury_post_format_quote() {
		$quote      = get_post_meta( get_the_ID(), 'quote', true );
		$author     = get_post_meta( get_the_ID(), 'quote_author', true );
		$author_url = get_post_meta( get_the_ID(), 'author_url', true );

		if ( ! $quote ) {
			return;
		}

		return sprintf(
			'<blockquote>%s<cite>%s</cite></blockquote>',
			esc_html( $quote ),
			empty( $author_url ) ? $author : '<a href="' . esc_url( $author_url ) . '">' . $author . '</a>'
		);

	}
endif;

/**
 * Get post format link
 *
 * @since  1.0
 */

if ( ! function_exists( 'martfury_post_format_link' ) ) :
	function martfury_post_format_link() {
		$desc = get_post_meta( get_the_ID(), 'desc', true );
		$link = get_post_meta( get_the_ID(), 'url', true );
		$text = get_post_meta( get_the_ID(), 'url_text', true );

		if ( ! $link ) {
			return;
		}

		if ( $desc ) {
			$desc = sprintf( '<div class="desc">%s</div>', $desc );
		}

		return sprintf( '%s<a href="%s" class="link-block">%s</a>', $desc, esc_url( $link ), $text ? $text : $link );

	}
endif;

/**
 * Get post format gallery
 *
 * @since  1.0
 */

if ( ! function_exists( 'martfury_post_format_gallery' ) ) :
	function martfury_post_format_gallery( $size ) {
		$image_ids = get_post_meta( get_the_ID(), 'images', false );

		if ( empty( $image_ids ) ) {
			$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );

			return '<a class="entry-image" href="' . get_permalink() . '">' . martfury_get_image_html( $post_thumbnail_id, $size ) . '</a>';
		} else {
			$gallery = array();
			foreach ( $image_ids as $id ) {
				$image = martfury_get_image_html( $id, $size );
				if ( $image ) {
					$gallery[] = '<li><a class="entry-image" href="' . get_permalink() . '">' . $image . '</a></li>';
				}

			}

			return '<ul class="slides">' . implode( '', $gallery ) . '</ul>';
		}

	}
endif;

/**
 * Get post format video
 *
 * @since  1.0
 */

if ( ! function_exists( 'martfury_post_format_video' ) ) :
	function martfury_post_format_video() {
		$video = get_post_meta( get_the_ID(), 'video', true );

		if ( ! $video ) {
			return;
		}

		$video_html = '';

		// If URL: show oEmbed HTML
		if ( filter_var( $video, FILTER_VALIDATE_URL ) ) {
			if ( $oembed = @wp_oembed_get( $video, array( 'width' => 1170 ) ) ) {
				$video_html = $oembed;
			} else {
				$atts = array(
					'src'   => $video,
					'width' => 1170,
				);

				if ( has_post_thumbnail() ) {
					$atts['poster'] = get_the_post_thumbnail_url( get_the_ID(), 'full' );
				}
				$video_html = wp_video_shortcode( $atts );
			}
		} // If embed code: just display
		else {
			$video_html = $video;
		}

		return $video_html;

	}
endif;

/**
 * Get post format audio
 *
 * @since  1.0
 */

if ( ! function_exists( 'martfury_post_format_audio' ) ) :
	function martfury_post_format_audio() {
		$audio = get_post_meta( get_the_ID(), 'audio', true );
		if ( ! $audio ) {
			return;
		}

		$html = '';

		// If URL: show oEmbed HTML or jPlayer
		if ( filter_var( $audio, FILTER_VALIDATE_URL ) ) {
			// Try oEmbed first
			if ( $oembed = @wp_oembed_get( $audio ) ) {
				$html .= $oembed;
			} // Use audio shortcode
			else {
				$html .= '<div class="audio-player">' . wp_audio_shortcode( array( 'src' => $audio ) ) . '</div>';
			}
		} // If embed code: just display
		else {
			$html .= $audio;
		}

		return $html;

	}
endif;

/**
 * Get post format image
 *
 * @since  1.0
 */

if ( ! function_exists( 'martfury_post_format_image' ) ) :
	function martfury_post_format_image( $size ) {
		$post_thumbnail_id = get_post_thumbnail_id( get_the_ID() );
		if ( martfury_get_option( 'blog_layout' ) == 'masonry' ) {
			$thumb = wp_get_attachment_image( $post_thumbnail_id, $size );

		} else {
			$thumb = martfury_get_image_html( $post_thumbnail_id, $size );
		}

		if ( empty( $thumb ) ) {
			return;
		}

		return '<a class="entry-image" href="' . get_permalink() . '">' . $thumb . '</a>';
	}
endif;

/**
 * show categories filter
 *
 * @return string
 */

if ( ! function_exists( 'martfury_taxs_list' ) ) :
	function martfury_taxs_list( $taxonomy = 'category' ) {

		if ( $taxonomy == 'category' ) {
			if ( ! intval( martfury_get_option( 'show_blog_cats' ) ) ) {
				return '';
			}
		}


		$cats   = '';
		$output = array();
		$number = apply_filters( 'blog_cats_number', 6 );


		$args = array(
			'number'  => $number,
			'orderby' => 'count',
			'order'   => 'DESC',

		);

		$term_id = 0;

		if ( is_tax( $taxonomy ) || is_category() ) {

			$queried_object = get_queried_object();
			if ( $queried_object ) {
				$term_id = $queried_object->term_id;
			}
		}

		$found       = false;
		$custom_slug = intval( martfury_get_option( 'custom_blog_cats' ) );
		if ( $custom_slug ) {
			$cats_slug = martfury_get_option( 'blog_cats_slug' );
			foreach ( $cats_slug as $slug ) {
				$cat = get_term_by( 'slug', $slug, $taxonomy );
				if ( $cat ) {
					$css_class = '';
					if ( $cat->term_id == $term_id ) {
						$css_class = 'selected';
						$found     = true;
					}
					$cats .= sprintf( '<li><a class="%s" href="%s">%s</a></li>', esc_attr( $css_class ), esc_url( get_term_link( $cat ) ), esc_html( $cat->name ) );
				}
			}
		} else {
			$categories = get_terms( $taxonomy, $args );
			if ( ! is_wp_error( $categories ) && $categories ) {
				foreach ( $categories as $cat ) {
					$cat_selected = '';
					if ( $cat->term_id == $term_id ) {
						$cat_selected = 'selected';
						$found        = true;
					}
					$cats .= sprintf( '<li><a href="%s" class="%s">%s</a></li>', esc_url( get_term_link( $cat ) ), esc_attr( $cat_selected ), esc_html( $cat->name ) );
				}
			}
		}


		$cat_selected = $found ? '' : 'selected';

		if ( $cats ) {
			$blog_url = get_page_link( get_option( 'page_for_posts' ) );
			if ( 'posts' == get_option( 'show_on_front' ) ) {
				$blog_url = home_url();
			}

			$output[] = sprintf(
				'<ul>
				<li><a href="%s" class="%s">%s</a></li>
				 %s
			</ul>',
				esc_url( $blog_url ),
				esc_attr( $cat_selected ),
				esc_html__( 'All', 'martfury' ),
				$cats
			);
		}

		if ( $output ) {
			echo '<div class="mf-taxs-list"><div class="container">' . implode( "\n", $output ) . '</div></div>';
		}

	}

endif;

/**
 * Retrieves related product terms
 *
 * @param string $term
 *
 * @return array
 */
function martfury_get_related_terms( $term, $post_id = null ) {
	$post_id     = $post_id ? $post_id : get_the_ID();
	$terms_array = array( 0 );

	$terms = wp_get_post_terms( $post_id, $term );
	foreach ( $terms as $term ) {
		$terms_array[] = $term->term_id;
	}

	return array_map( 'absint', $terms_array );
}

/**
 * Get product image use lazyload
 *
 * @since  1.0
 *
 * @return string
 */
function martfury_get_image_html( $post_thumbnail_id, $image_size, $css_class = '', $attributes = false ) {
	$output = '';
	if ( intval( martfury_get_option( 'lazyload' ) ) ) {
		$alt   = trim( strip_tags( get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true ) ) );
		$image = wp_get_attachment_image_src( $post_thumbnail_id, $image_size );

		if ( $image ) {
			$image_trans = get_template_directory_uri() . '/images/transparent.png';

			if ( $attributes ) {
				$full_size_image = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
				$output          = sprintf(
					'<img src="%s" data-original="%s"  alt="%s" class="lazy %s" data-large_image="%s" data-large_image_width="%s" data-large_image_height="%s">',
					esc_url( $image_trans ),
					esc_url( $image[0] ),
					esc_attr( $alt ),
					esc_attr( $css_class ),
					esc_attr( $full_size_image[0] ),
					esc_attr( $attributes['data-large_image_width'] ),
					esc_attr( $attributes['data-large_image_height'] )
				);
			} else {
				$output = sprintf(
					'<img src="%s" data-original="%s"  alt="%s" class="lazy %s" width="%s" height="%s">',
					esc_url( $image_trans ),
					esc_url( $image[0] ),
					esc_attr( $alt ),
					esc_attr( $css_class ),
					esc_attr( $image[1] ),
					esc_attr( $image[2] )
				);
			}

		}
	} else {
		$attributes['class'] = $css_class;
		$output              = wp_get_attachment_image( $post_thumbnail_id, $image_size, false, $attributes );
	}

	return $output;
}

/**
 * Get current page URL for layered nav items.
 * @return string
 */
if ( ! function_exists( 'martfury_get_page_base_url' ) ) :
	function martfury_get_page_base_url() {
		if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
			$link = home_url();
		} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
			$link = get_post_type_archive_link( 'product' );
		} elseif ( is_product_category() ) {
			$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
		} elseif ( is_product_tag() ) {
			$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
		} else {
			$queried_object = get_queried_object();
			if ( $queried_object ) {
				$link = get_term_link( $queried_object->slug, $queried_object->taxonomy );
			}
		}

		return $link;
	}
endif;

/**
 * Get catalog layout
 *
 * @since 1.0
 */
if ( ! function_exists( 'martfury_get_catalog_layout' ) ) :
	function
	martfury_get_catalog_layout() {
		$layout = '10';

		if ( martfury_is_vendor_page() ) {
			$layout = '12';
		} elseif ( is_search() ) {
			$layout = '10';
		} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
			$layout = martfury_get_option( 'shop_layout' );
		} elseif ( function_exists( 'is_product_category' ) && is_product_category() && martfury_get_product_category_level() == 0 ) {
			$layout = martfury_get_option( 'products_cat_level_1_layout' );

			if ( function_exists( 'get_term_meta' ) ) {
				$queried_object = get_queried_object();
				$cat_layout     = get_term_meta( $queried_object->term_id, 'mf_cat_layout', true );
				$layout         = $cat_layout ? $cat_layout : $layout;
			}

		}

		return apply_filters( 'martfury_get_catalog_layout', $layout );
	}
endif;

/**
 * Get catalog layout
 *
 * @since 1.0
 */
if ( ! function_exists( 'martfury_get_catalog_full_width' ) ) :
	function martfury_get_catalog_full_width() {

		if ( ! martfury_is_catalog() && ! martfury_is_vendor_page() ) {
			return false;
		}

		$catalog_layout = martfury_get_catalog_layout();
		if ( $catalog_layout == '1' && intval( martfury_get_option( 'catalog_full_width_1' ) ) ) {
			return true;
		} elseif ( $catalog_layout == '3' && intval( martfury_get_option( 'catalog_full_width_3' ) ) ) {
			return true;
		} elseif ( $catalog_layout == '10' ) {
			if ( is_search() ) {
				if ( intval( martfury_get_option( 'search_full_width' ) ) ) {
					return true;
				}
			} else {
				if ( intval( martfury_get_option( 'catalog_full_width_10' ) ) ) {
					return true;
				}

			}
		} elseif ( $catalog_layout == '12' && intval( martfury_get_option( 'catalog_full_width_12' ) ) ) {
			return true;
		}

		return false;

	}
endif;

/**
 * Get category level
 *
 * @since 1.0
 */
if ( ! function_exists( 'martfury_get_product_category_level' ) ) :
	function martfury_get_product_category_level() {
		global $wp_query;
		$current_cat = $wp_query->get_queried_object();
		if ( empty( $current_cat ) ) {
			return 0;
		}

		$term_id = $current_cat->term_id;

		return count( get_ancestors( $term_id, 'product_cat' ) );
	}
endif;

/**
 * Get catalog layout
 *
 * @since 1.0
 */
if ( ! function_exists( 'martfury_get_filtered_term_product_counts' ) ) :
	function martfury_get_filtered_term_product_counts( $term_ids, $taxonomy = false, $query_type = false ) {
		global $wpdb;

		if ( ! class_exists( 'WC_Query' ) ) {
			return false;
		}

		$tax_query  = WC_Query::get_main_tax_query();
		$meta_query = WC_Query::get_main_meta_query();

		if ( 'or' === $query_type ) {
			foreach ( $tax_query as $key => $query ) {
				if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
					unset( $tax_query[ $key ] );
				}
			}
		}

		if ( 'product_brand' === $taxonomy ) {
			foreach ( $tax_query as $key => $query ) {
				if ( is_array( $query ) ) {
					if ( $query['taxonomy'] === 'product_brand' ) {
						unset( $tax_query[ $key ] );

						if ( preg_match( '/pa_/', $query['taxonomy'] ) ) {
							unset( $tax_query[ $key ] );
						}
					}
				}
			}
		}

		$meta_query     = new WP_Meta_Query( $meta_query );
		$tax_query      = new WP_Tax_Query( $tax_query );
		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		// Generate query
		$query           = array();
		$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
		$query['from']   = "FROM {$wpdb->posts}";
		$query['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];

		$query['where'] = "
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			" . $tax_query_sql['where'] . $meta_query_sql['where'] . "
			AND terms.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
		";

		if ( $search = WC_Query::get_main_search_query_sql() ) {
			$query['where'] .= ' AND ' . $search;
		}

		$query['group_by'] = "GROUP BY terms.term_id";
		$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
		$query             = implode( ' ', $query );

		// We have a query - let's see if cached results of this query already exist.
		$query_hash = md5( $query );
		$cache      = apply_filters( 'woocommerce_layered_nav_count_maybe_cache', true );
		if ( true === $cache ) {
			$cached_counts = (array) get_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ) );
		} else {
			$cached_counts = array();
		}

		if ( ! isset( $cached_counts[ $query_hash ] ) ) {
			$results                      = $wpdb->get_results( $query, ARRAY_A );
			$counts                       = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );
			$cached_counts[ $query_hash ] = $counts;
			set_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ), $cached_counts, DAY_IN_SECONDS );
		}

		return array_map( 'absint', (array) $cached_counts[ $query_hash ] );
	}
endif;

/**
 * Get socials
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'martfury_get_socials' ) ) :
	function martfury_get_socials() {
		$socials = array(
			'facebook'   => esc_html__( 'Facebook', 'martfury' ),
			'twitter'    => esc_html__( 'Twitter', 'martfury' ),
			'google'     => esc_html__( 'Google', 'martfury' ),
			'tumblr'     => esc_html__( 'Tumblr', 'martfury' ),
			'flickr'     => esc_html__( 'Flickr', 'martfury' ),
			'vimeo'      => esc_html__( 'Vimeo', 'martfury' ),
			'youtube'    => esc_html__( 'Youtube', 'martfury' ),
			'linkedin'   => esc_html__( 'LinkedIn', 'martfury' ),
			'pinterest'  => esc_html__( 'Pinterest', 'martfury' ),
			'dribbble'   => esc_html__( 'Dribbble', 'martfury' ),
			'spotify'    => esc_html__( 'Spotify', 'martfury' ),
			'instagram'  => esc_html__( 'Instagram', 'martfury' ),
			'tumbleupon' => esc_html__( 'Tumbleupon', 'martfury' ),
			'wordpress'  => esc_html__( 'WordPress', 'martfury' ),
			'rss'        => esc_html__( 'Rss', 'martfury' ),
			'deviantart' => esc_html__( 'Deviantart', 'martfury' ),
			'share'      => esc_html__( 'Share', 'martfury' ),
			'skype'      => esc_html__( 'Skype', 'martfury' ),
			'behance'    => esc_html__( 'Behance', 'martfury' ),
			'apple'      => esc_html__( 'Apple', 'martfury' ),
			'yelp'       => esc_html__( 'Yelp', 'martfury' ),
		);

		return apply_filters( 'martfury_header_socials', $socials );
	}
endif;

/**
 * Get page header layout
 *
 * @return array
 */

if ( ! function_exists( 'martfury_get_page_header' ) ) :
	function martfury_get_page_header() {
		if ( is_404() || is_page_template( 'template-homepage.php' ) || is_page_template( 'template-home-full-width.php' ) || is_page_template( 'template-coming-soon-page.php' ) ) {
			return false;
		}

		$page_header = array( 'title', 'breadcrumb' );
		if ( martfury_is_blog() ) {
			if ( ! intval( martfury_get_option( 'page_header_blog' ) ) ) {
				return false;
			}
			$page_header = martfury_get_option( 'page_header_blog_els' );


		} elseif ( is_page() ) {
			if ( ! intval( martfury_get_option( 'page_header_page' ) ) ) {
				return false;
			}

			$custom_layout = get_post_meta( get_the_ID(), 'custom_page_header', true );
			if ( $custom_layout ) {
				$hide_page_header = get_post_meta( get_the_ID(), 'hide_page_header', true );

				if ( $hide_page_header ) {
					return false;
				}
				if ( get_post_meta( get_the_ID(), 'hide_breadcrumb', true ) ) {

					$key = array_search( 'breadcrumb', $page_header );
					if ( $key !== false ) {
						unset( $page_header[ $key ] );
					}
				}

				if ( get_post_meta( get_the_ID(), 'hide_title', true ) ) {

					$key = array_search( 'title', $page_header );
					if ( $key !== false ) {
						unset( $page_header[ $key ] );
					}
				}
			} else {
				$page_header = martfury_get_option( 'page_header_pages_els' );
			}
		} elseif ( is_search() ) {
			$page_header = array( 'title', 'breadcrumb' );
		} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
			$shop_id       = get_option( 'woocommerce_shop_page_id' );
			$custom_layout = get_post_meta( $shop_id, 'custom_page_header', true );
			if ( $custom_layout ) {
				$hide_page_header = get_post_meta( $shop_id, 'hide_page_header', true );

				if ( $hide_page_header ) {
					return false;
				}
				if ( get_post_meta( $shop_id, 'hide_breadcrumb', true ) ) {

					$key = array_search( 'breadcrumb', $page_header );
					if ( $key !== false ) {
						unset( $page_header[ $key ] );
					}
				}

				if ( get_post_meta( $shop_id, 'hide_title', true ) ) {

					$key = array_search( 'title', $page_header );
					if ( $key !== false ) {
						unset( $page_header[ $key ] );
					}
				}
			}
		}

		if ( martfury_is_vendor_page() ) {
			$page_header = martfury_get_option( 'page_header_vendor_els' );
		}

		return apply_filters( 'martfury_get_page_header', $page_header );

	}

endif;

/**
 * Get recently viewed products
 *
 * @return string
 */
if ( ! function_exists( 'martfury_recently_viewed_products' ) ) :
	function martfury_recently_viewed_products( $atts ) {

		$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : array();
		$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

		$output = array();

		$thumbnail_size = 'thumbnail';
		if ( function_exists( 'wc_get_image_size' ) ) {
			$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
			$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array(
				$gallery_thumbnail['width'],
				$gallery_thumbnail['height']
			) );
		}

		$output[] = '<div class="recently-header">';
		if ( isset( $atts['title'] ) && $atts['title'] ) {
			$output[] = sprintf( '<h2 class="title">%s</h2>', esc_html( $atts['title'] ) );
		}

		if ( isset( $atts['link_text'] ) && $atts['link_text'] ) {
			$output[] = sprintf( '<a href="%s" class="link">%s</a>', esc_url( $atts['link_url'] ), esc_html( $atts['link_text'] ) );
		}
		$output[] = '</div>';

		if ( empty( $viewed_products ) ) {

			$output[] = sprintf(
				'<ul class="product-list no-products">' .
				'<li class="text-center">%s <br><a href="%s" class="btn-secondary">%s</a></li>' .
				'</ul>',
				esc_html__( 'Recently Viewed Products is a function which helps you keep track of your recent viewing history.', 'martfury' ),
				esc_url( get_permalink( get_option( 'woocommerce_shop_page_id' ) ) ),
				esc_html__( 'Shop Now', 'martfury' )
			);

		} else {
			if ( ! function_exists( 'wc_get_product' ) ) {
				$output[] = sprintf(
					'<ul class="product-list no-products">' .
					'<li class="text-center">%s <br><a href="%s" class="btn-secondary">%s</a></li>' .
					'</ul>',
					esc_html__( 'Recently Viewed Products is a function which helps you keep track of your recent viewing history.', 'martfury' ),
					esc_url( get_permalink( get_option( 'woocommerce_shop_page_id' ) ) ),
					esc_html__( 'Shop Now', 'martfury' )
				);
			}


			$output[] = '<ul class="product-list">';
			$number   = intval( $atts['numbers'] );
			$index    = 1;
			foreach ( $viewed_products as $product_id ) {
				if ( $index > $number ) {
					break;
				}

				$index ++;

				$product = wc_get_product( $product_id );

				if ( empty( $product ) ) {
					continue;
				}
				$output[] = sprintf(
					'<li>' .
					'<a href="%s">%s</a>' .
					'</li>',
					esc_url( $product->get_permalink() ),
					$product->get_image( $thumbnail_size )
				);
			}
			$output[] = '</ul>';
		}


		return sprintf( '<div class="container rv-container">%s</div>', implode( ' ', $output ) );
	}
endif;

/**
 * Print HTML of currency switcher
 * It requires plugin WooCommerce Currency Switcher installed
 */
if ( ! function_exists( 'martfury_currency_switcher' ) ) :
	function martfury_currency_switcher( $show_desc = false ) {
		$currency_dd = '';
		if ( class_exists( 'WOOCS' ) ) {
			global $WOOCS;

			$key_cur = 'name';
			if ( $show_desc ) {
				$key_cur = 'description';
			}

			$currencies    = $WOOCS->get_currencies();
			$currency_list = array();
			foreach ( $currencies as $key => $currency ) {
				if ( $WOOCS->current_currency == $key ) {
					array_unshift(
						$currency_list, sprintf(
							'<li class="actived"><a href="#" class="woocs_flag_view_item woocs_flag_view_item_current" data-currency="%s">%s</a></li>',
							esc_attr( $currency['name'] ),
							esc_html( $currency[ $key_cur ] )
						)
					);
				} else {
					$currency_list[] = sprintf(
						'<li><a href="#" class="woocs_flag_view_item" data-currency="%s">%s</a></li>',
						esc_attr( $currency['name'] ),
						esc_html( $currency[ $key_cur ] )
					);
				}
			}

			$currency_dd = sprintf(
				'<span class="current">%s</span>' .
				'<ul>%s</ul>',
				$currencies[ $WOOCS->current_currency ][ $key_cur ],
				implode( "\n\t", $currency_list )
			);


		} elseif ( class_exists( 'Alg_WC_Currency_Switcher' ) ) {
			$function_currencies    = alg_get_enabled_currencies();
			$currencies             = get_woocommerce_currencies();
			$selected_currency      = alg_get_current_currency_code();
			$selected_currency_name = '';
			$currency_list          = array();
			$first_link             = '';
			foreach ( $function_currencies as $currency_code ) {
				if ( isset( $currencies[ $currency_code ] ) ) {
					$the_text = alg_format_currency_switcher( $currencies[ $currency_code ], $currency_code, false );
					$the_link = '<li><a id="alg_currency_' . $currency_code . '" href="' . add_query_arg( 'alg_currency', $currency_code ) . '">' . $the_text . '</a></li>';
					if ( $currency_code != $selected_currency ) {
						$currency_list[] = $the_link;
					} else {
						$first_link             = $the_link;
						$selected_currency_name = $the_text;
					}
				}
			}
			if ( '' != $first_link ) {
				$currency_list = array_merge( array( $first_link ), $currency_list );
			}

			if ( ! empty( $currency_list ) && ! empty( $selected_currency_name ) ) {
				$currency_dd = sprintf(
					'<span class="current">%s</span>' .
					'<ul>%s</ul>',
					$selected_currency_name,
					implode( "\n\t", $currency_list )
				);
			}

		}

		return $currency_dd;
	}

endif;

if ( ! function_exists( 'martfury_product_video' ) ) :
	function martfury_product_video() {
		global $product;
		$video_image  = get_post_meta( $product->get_id(), 'video_thumbnail', true );
		$video_url    = get_post_meta( $product->get_id(), 'video_url', true );
		$video_first  = get_post_meta( $product->get_id(), 'video_position', true );
		$video_width  = 1024;
		$video_height = 768;
		$video_html   = '';
		if ( $video_image ) {
			$video_thumb = wp_get_attachment_image_src( $video_image, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
			$video_thumb = $video_thumb[0];
			// If URL: show oEmbed HTML
			if ( filter_var( $video_url, FILTER_VALIDATE_URL ) ) {

				$atts = array(
					'width'  => $video_width,
					'height' => $video_height
				);

				if ( $oembed = @wp_oembed_get( $video_url, $atts ) ) {
					$video_html = $oembed;
				} else {
					$atts = array(
						'src'    => $video_url,
						'width'  => $video_width,
						'height' => $video_height
					);

					$video_html = wp_video_shortcode( $atts );

				}
			}
			if ( $video_html ) {

				$vid_html = '<div class="mf-video-wrapper">' . $video_html . '</div>';
				if ( $video_first == '2' ) {
					$post_thumbnail_id = $product->get_image_id();
					$vid_html          .= '<div class="woocommerce-product-gallery__image"><a href="#"><img class="wp-post-image" src="#"></a></div>';
				}
				$video_wrapper = sprintf( '<div class="mf-video-content">%s</div>', $vid_html );
				$video_html    = '<div data-thumb="' . esc_url( $video_thumb ) . '" class="woocommerce-product-gallery__image mf-product-video">' . $video_wrapper . '</div>';
			}
		}

		return $video_html;
	}
endif;