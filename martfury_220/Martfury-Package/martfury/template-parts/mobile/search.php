<?php
$search_text = martfury_get_option( 'custom_search_text' );
?>

<div class="mf-search-mobile-modal mf-els-item current" id="mf-search-mobile">
	<?php if ( martfury_get_option( 'search_form_type' ) == 'default' ) : ?>
        <form class="products-search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <div class="search-wrapper">
                <input type="text" name="s" class="search-field" autocomplete="off"
                       placeholder="<?php echo esc_html( $search_text ); ?>">
				<?php
				$search_type = martfury_get_option( 'search_content_type' );
				if ( $search_type == 'product' ) {
					echo '<input type="hidden" name="post_type" value="product">';
				}

				$lang = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : false;
				if ( $lang ) {
					echo '<input type="hidden" name="lang" value="' . $lang . '"/>';
				}
				?>
                <button type="submit" class="search-submit"><i class="icon-magnifier"></i></button>
            </div>
            <div class="search-results woocommerce"></div>
        </form>
	<?php else: ?>
        <div class="search-wrapper">
			<?php
			echo do_shortcode( wp_kses( martfury_get_option( 'search_form_shortcode' ), wp_kses_allowed_html( 'post' ) ) );
			?>
        </div>
	<?php endif; ?>
	<?php if ( intval( martfury_get_option( 'hot_words_mobile' ) ) ) :
		if ( $hot_words = martfury_get_hot_words() ) :
			?>
            <div class="search-trending">
                <h3><?php esc_html_e( 'Search Trending', 'martfury' ); ?></h3>
				<?php echo implode( ' ', $hot_words ); ?>
            </div>
		<?php endif; ?>
	<?php endif; ?>
</div>