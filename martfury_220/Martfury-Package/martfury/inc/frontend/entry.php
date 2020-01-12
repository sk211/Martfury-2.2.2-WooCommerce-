<?php
/**
 * Hooks for template archive
 *
 * @package Martfury
 */


/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @since 1.0
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function martfury_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}

add_action( 'wp', 'martfury_setup_author' );


function martfury_excerpt_more( $more ) {
	$more = '&hellip;';

	return $more;
}

add_filter( 'excerpt_more', 'martfury_excerpt_more' );

/**
 * Set order by get posts
 *
 * @since  1.0
 *
 * @param object $query
 *
 * @return string
 */
function martfury_pre_get_posts( $query ) {
	if ( is_admin() ) {
		return;
	}

	if ( ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_archive() ) {
		if ( martfury_is_catalog() || martfury_is_dc_vendor_store() ) {
			$number = intval( martfury_get_option( 'products_per_page_' . martfury_get_catalog_layout() ) );
			$query->set( 'posts_per_page', $number );
		}
	}
}

add_action( 'pre_get_posts', 'martfury_pre_get_posts' );


/**
 * The archive title
 *
 * @since  1.0
 *
 * @param  array $title
 *
 * @return mixed
 */
function martfury_the_archive_title( $title ) {
	if ( is_search() ) {
		$title = sprintf( esc_html__( 'Search Results', 'martfury' ) );
	} elseif ( is_404() ) {
		$title = sprintf( esc_html__( 'Page Not Found', 'martfury' ) );
	} elseif ( is_page() ) {
		$title = get_the_title();
	} elseif ( is_home() && is_front_page() ) {
		$title = esc_html__( 'The Latest Posts', 'martfury' );
	} elseif ( is_home() && ! is_front_page() ) {
		$title = get_the_title( get_option( 'page_for_posts' ) );
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$title = get_the_title( get_option( 'woocommerce_shop_page_id' ) );
	} elseif ( function_exists( 'is_product' ) && is_product() ) {
		$title = get_the_title();
	} elseif ( is_single() ) {
		$title = get_the_title();
	} elseif ( is_post_type_archive( 'portfolio_project' ) ) {
		$title = get_the_title( get_option( 'drf_portfolio_page_id' ) );
	} elseif ( is_tax() || is_category() ) {
		$title = single_term_title( '', false );
	}

	if ( get_option( 'woocommerce_shop_page_id' ) ) {
		if ( is_front_page() && ( get_option( 'woocommerce_shop_page_id' ) == get_option( 'page_on_front' ) ) ) {
			$title = get_the_title( get_option( 'woocommerce_shop_page_id' ) );
		}
	}


	return $title;
}

add_filter( 'get_the_archive_title', 'martfury_the_archive_title', 30 );


/**
 * Add entry format for single post
 *
 * @since  1.0
 *
 *
 */
if ( ! function_exists( 'martfury_single_post_format' ) ) :
	function martfury_single_post_format() {
		if ( ! is_singular( 'post' ) ) {
			return;
		}

		$post_style = martfury_single_post_style();

		if ( ! in_array( $post_style, array( '1' ) ) ) {
			return;
		}

		if ( ! intval( martfury_get_option( 'show_post_format' ) ) ) {
			return;
		}

		martfury_entry_thumbnail();
	}
endif;

add_action( 'martfury_before_content_single', 'martfury_single_post_format', 100 );

/**
 * Add entry format for single post
 *
 * @since  1.0
 *
 *
 */
if ( ! function_exists( 'martfury_single_entry_header' ) ) :
	function martfury_single_entry_header() {
		if ( ! is_singular( 'post' ) ) {
			return;
		}

		$post_style = martfury_single_post_style();

		if ( ! in_array( $post_style, array( '3', '4' ) ) ) {
			return;
		}

		if ( $post_style == '4' && intval( martfury_get_option( 'show_post_format' ) ) ) {
			martfury_entry_thumbnail();
		}

		the_title( '<h1 class="entry-title">', '</h1>' );
		?>
        <div class="entry-metas">
			<?php martfury_posted_on( false, true ); ?>
        </div>
		<?php
	}
endif;

add_action( 'martfury_before_content_single', 'martfury_single_entry_header', 100 );

/**
 * Custom fields comment form
 *
 * @since  1.0
 *
 * @return  array  $fields
 */
if ( ! function_exists( 'martfury_comment_form_fields' ) ) :
	function martfury_comment_form_fields() {
		global $commenter, $aria_req;

		$fields = array(
			'author' => '<p class="comment-form-author col-md-6 col-sm-12">' .
			            '<input id ="author" placeholder="' . esc_html__( 'Name', 'martfury' ) . ' " name="author" type="text" required value="' . esc_attr( $commenter['comment_author'] ) .
			            '" size    ="30"' . $aria_req . ' /></p>',

			'email' => '<p class="comment-form-email col-md-6 col-sm-12">' .
			           '<input id ="email" placeholder="' . esc_html__( 'Email', 'martfury' ) . '"name="email" type="email" required value="' . esc_attr( $commenter['comment_author_email'] ) .
			           '" size    ="30"' . $aria_req . ' /></p>',

			'url' => '<p class="comment-form-url col-md-12 col-sm-12">' .
			         '<input id ="url" placeholder="' . esc_html__( 'Website', 'martfury' ) . '"name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) .
			         '" size    ="30" /></p>'
		);

		return $fields;
	}
endif;

add_filter( 'comment_form_default_fields', 'martfury_comment_form_fields' );


/**
 * Get page header
 *
 * @since  1.0
 *
 *
 */

if ( ! function_exists( 'martfury_page_header' ) ) :
	function martfury_page_header() {

		if ( ! martfury_get_page_header() ) {
			return;
		}

		if ( martfury_is_blog() ) {
			$layout = martfury_get_option( 'page_header_blog_layout' );
			get_template_part( 'template-parts/page-headers/blog', $layout );
		} elseif ( martfury_is_catalog() ) {
			$layout = 1;
			get_template_part( 'template-parts/page-headers/catalog', $layout );
		} elseif ( is_singular( 'product' ) ) {
			get_template_part( 'template-parts/page-headers/product' );
		} elseif ( is_singular( 'post' ) ) {
			$post_style = martfury_single_post_style();

			if ( $post_style == '4' ) {
				return;
			}

			get_template_part( 'template-parts/page-headers/post', $post_style );
		} elseif ( is_page() ) {
			get_template_part( 'template-parts/page-headers/page' );
		} elseif ( is_search() ) {
			get_template_part( 'template-parts/page-headers/blog', 1 );
		} else {
			get_template_part( 'template-parts/page-headers/default' );
		}

	}

endif;

add_action( 'martfury_after_header', 'martfury_page_header' );

/**
 * Get Categories Filter
 *
 * @since  1.0
 *
 *
 */

if ( ! function_exists( 'martfury_blog_cats_filter' ) ) :
	function martfury_blog_cats_filter() {

		if ( ! martfury_is_blog() ) {
			return;
		}

		martfury_taxs_list();

	}

endif;

add_action( 'martfury_after_header', 'martfury_blog_cats_filter', 40 );

if ( ! function_exists( 'martfury_coming_soon_socials' ) ) :
	function martfury_coming_soon_socials() {

		if ( ! intval( martfury_get_option( 'show_coming_soon_socials' ) ) ) {
			return;
		}

		$project_social = (array) martfury_get_option( 'coming_soon_socials' );

		if ( $project_social ) {

			$socials = (array) martfury_get_socials();

			printf( '<ul class="socials-inline coming-soon-socials">' );
			foreach ( $project_social as $social ) {
				foreach ( $socials as $name => $label ) {
					$link_url = $social['link_url'];

					if ( preg_match( '/' . $name . '/', $link_url ) ) {

						if ( $name == 'google' ) {
							$name = 'googleplus';
						}

						printf( '<li><a href="%s" target="_blank"><i class="social_%s"></i></a></li>', esc_url( $link_url ), esc_attr( $name ) );
						break;
					}
				}
			}
			printf( '</ul>' );
		}

	}

endif;

add_action( 'martfury_coming_soon_page_content', 'martfury_coming_soon_socials', 40 );