<?php
/**
 * Add to wishlist template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.0
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly

global $product;
$browse_wishlist_text = apply_filters( 'yith-wcwl-browse-wishlist-label', $browse_wishlist_text);
?>

<div class="yith-wcwl-add-to-wishlist add-to-wishlist-<?php echo esc_attr($product_id) ?>">
	<?php if( ! ( $disable_wishlist && ! is_user_logged_in() ) ): ?>
		<?php
		$add_class = ( $exists && ! $available_multi_wishlist ) ? 'hide' : 'show';
		$add_style = ( $exists && ! $available_multi_wishlist ) ? 'none' : 'block';
		?>
	    <div class="yith-wcwl-add-button <?php echo esc_attr( $add_class ); ?>" style="display:<?php echo esc_attr( $add_style ); ?>">

	        <?php yith_wcwl_get_template( 'add-to-wishlist-' . $template_part . '.php', $atts ); ?>

	    </div>

	    <div class="yith-wcwl-wishlistaddedbrowse hide" style="display:none;">
	        <span class="feedback"><?php echo wp_kses_post($product_added_text) ?></span>
	        <a href="<?php echo esc_url( $wishlist_url )?>" data-rel="tooltip" title="<?php echo esc_attr($browse_wishlist_text);?>">
		        <?php echo esc_html($browse_wishlist_text);?>
	        </a>
	    </div>

		<?php
		$browse_class = ( $exists && ! $available_multi_wishlist ) ? 'show' : 'hide';
		$browse_style = ( $exists && ! $available_multi_wishlist ) ? 'block' : 'none';
		?>

	    <div class="yith-wcwl-wishlistexistsbrowse <?php echo esc_attr( $browse_class ); ?>"  style="display:<?php echo esc_attr( $browse_style ); ?>">
	        <span class="feedback"><?php echo wp_kses_post($already_in_wishslist_text) ?></span>
	        <a href="<?php echo esc_url( $wishlist_url ) ?>" data-rel="tooltip" title="<?php echo esc_attr($browse_wishlist_text);?>">
		        <?php echo esc_html($browse_wishlist_text);?>
	        </a>
	    </div>

	    <div style="clear:both"></div>
	    <div class="yith-wcwl-wishlistaddresponse"></div>
	<?php else: ?>
		<a href="<?php echo esc_url( add_query_arg( array( 'wishlist_notice' => 'true', 'add_to_wishlist' => $product_id ), get_permalink( wc_get_page_id( 'myaccount' ) ) ) )?>" title="<?php echo esc_attr($label); ?>" data-rel="tooltip" class="<?php echo str_replace( 'add_to_wishlist', '', $link_classes ) ?>" >
			<?php echo esc_html($browse_wishlist_text);?>
		</a>
	<?php endif; ?>

</div>

<div class="clear"></div>