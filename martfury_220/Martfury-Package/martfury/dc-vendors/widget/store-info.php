<?php
/**
 * The Template for displaying a store header
 *
 * Override this template by copying it to yourtheme/wc-vendors/store
 *
 * @package    WCVendors_Pro
 * @version    1.3.5
 */


if ( ! function_exists( 'get_wcmp_vendor' ) ) {
	return;
}

$vendor = get_wcmp_vendor( $vendor_id );
if ( empty( $vendor ) ) {
	return;
}

$store_title        = $vendor->page_title;
$facebook_url       = get_user_meta( $vendor_id, '_vendor_fb_profile', true );
$twitter_username   = get_user_meta( $vendor_id, '_vendor_twitter_profile', true );
$linkedin_url       = get_user_meta( $vendor_id, '_vendor_linkdin_profile', true );
$googleplus_url     = get_user_meta( $vendor_id, '_vendor_google_plus_profile', true );
$youtube_url        = get_user_meta( $vendor_id, '_vendor_youtube', true );
$instagram_username = get_user_meta( $vendor_id, '_vendor_instagram', true );

$social_icons = true;
if ( empty( $facebook_url ) && empty( $twitter_username ) &&
     empty( $linkedin_url ) && empty( $googleplus_url ) &&
     empty( $youtube_url ) && empty( $instagram_username )
) {
	$social_icons = false;
}

?>
<div class="mf-vendor-store-header">
	<div class="wcv-store-grid wcv-store-header">
		<h1 class="store-name"><?php echo esc_html( $store_title ); ?></h1>
		<div class="wcv-store-ratings">
			<?php
			$queried_object = get_queried_object();
			if (isset($queried_object->term_id) && !empty($queried_object) && function_exists('wcmp_get_vendor_review_info')) {
				$rating_val_array = wcmp_get_vendor_review_info($queried_object->term_id);
				global $WCMp;
				if( $WCMp ) {
					$WCMp->template->get_template('review/rating.php', array('rating_val_array' => $rating_val_array));
				}
			}
			?>
		</div>
	</div>
	<?php if ( $description != '' ) { ?>
		<div class="wcv-store-grid wcv-store-description">
			<p><?php echo wp_kses_post($description); ?></p>
		</div>
	<?php } ?>
	<div class="wcv-store-info wcv-store-grid ">
		<?php if ( $address != '' ) { ?>
			<div class="store-address">
				<span class="label"><?php esc_html_e( 'Address:', 'martfury' ); ?></span>
				<a href="http://maps.google.com/maps?&q=<?php echo esc_attr($address); ?>">
					<?php echo wp_kses_post($address); ?>
				</a>
			</div>
		<?php } ?>
		<?php if ( $social_icons ) : ?>
			<div class="store-socials">
				<span class="label"><?php esc_html_e( 'Follow us on social', 'martfury' ); ?></span>
				<ul class="social-icons">
					<?php if ( $facebook_url != '' ) { ?>
						<li>
						<a class="social-facebook" href="<?php echo esc_url( $facebook_url ); ?>" target="_blank"><i class="ion-social-facebook"></i></a>
						</li><?php } ?>
					<?php if ( $instagram_username != '' ) { ?>
						<li>
						<a class="social-instagram" href="//instagram.com/<?php echo esc_attr( $instagram_username ); ?>" target="_blank"><i class="ion-social-instagram"></i></a>
						</li><?php } ?>
					<?php if ( $twitter_username != '' ) { ?>
						<li>
						<a class="social-twitter" href="//twitter.com/<?php echo esc_attr( $twitter_username ); ?>" target="_blank"><i class="ion-social-twitter"></i></a>
						</li><?php } ?>
					<?php if ( $googleplus_url != '' ) { ?>
						<li>
						<a class="social-googleplus" href="<?php echo esc_url( $googleplus_url ); ?>" target="_blank"><i class="ion-social-googleplus"></i></a>
						</li><?php } ?>
					<?php if ( $youtube_url != '' ) { ?>
						<li>
						<a class="social-youtube" href="<?php echo esc_url( $youtube_url ); ?>" target="_blank"><i class="ion-social-youtube"></i></a>
						</li><?php } ?>
					<?php if ( $linkedin_url != '' ) { ?>
						<li>
						<a class="social-linkedin" href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank"><i class="ion-social-linkedin"></i></a>
						</li><?php } ?>
				</ul>
			</div>
		<?php endif; ?>
		<?php if ( $phone != '' ) { ?>
			<div class="store-phone">
				<span><?php esc_html_e( 'Call us directly', 'martfury' ); ?></span>
				<span class="phone-number"><?php echo wp_kses_post($phone); ?></span>
			</div>
		<?php } ?>
		<?php
		if ( $email ) { ?>
			<div class="store-contact">
				<?php esc_html_e( 'Or contact seller via email', 'martfury' ); ?>
				<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
			</div>
		<?php } ?>
	</div>
</div>

