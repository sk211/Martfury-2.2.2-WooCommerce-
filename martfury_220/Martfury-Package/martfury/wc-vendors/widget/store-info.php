<?php
/**
 * The Template for displaying a store header
 *
 * Override this template by copying it to yourtheme/wc-vendors/store
 *
 * @package    WCVendors_Pro
 * @version    1.3.5
 */


// Verified vendor
$verified_vendor       = ( array_key_exists( '_wcv_verified_vendor', $vendor_meta ) ) ? $vendor_meta['_wcv_verified_vendor'] : false;
$verified_vendor_label = WCVendors_Pro::get_option( 'verified_vendor_label' );
// $verified_vendor_icon_src 	= WCVendors_Pro::get_option( 'verified_vendor_icon_src' );

// Store title
$store_title = ( is_product() ) ? '<a href="' . WCV_Vendors::get_vendor_shop_page( $post->post_author ) . '">' . $vendor_meta['pv_shop_name'] . '</a>' : $vendor_meta['pv_shop_name'];

// Get store details including social, adddresses and phone number
$twitter_username   = get_user_meta( $vendor_id, '_wcv_twitter_username', true );
$instagram_username = get_user_meta( $vendor_id, '_wcv_instagram_username', true );
$facebook_url       = get_user_meta( $vendor_id, '_wcv_facebook_url', true );
$linkedin_url       = get_user_meta( $vendor_id, '_wcv_linkedin_url', true );
$youtube_url        = get_user_meta( $vendor_id, '_wcv_youtube_url', true );
$googleplus_url     = get_user_meta( $vendor_id, '_wcv_googleplus_url', true );
$pinterest_url      = get_user_meta( $vendor_id, '_wcv_pinterest_url', true );
$snapchat_username  = get_user_meta( $vendor_id, '_wcv_snapchat_username', true );

// Migrate to store address array
$address1       = ( array_key_exists( '_wcv_store_address1', $vendor_meta ) ) ? $vendor_meta['_wcv_store_address1'] : '';
$address2       = ( array_key_exists( '_wcv_store_address2', $vendor_meta ) ) ? $vendor_meta['_wcv_store_address2'] : '';
$city           = ( array_key_exists( '_wcv_store_city', $vendor_meta ) ) ? $vendor_meta['_wcv_store_city'] : '';
$state          = ( array_key_exists( '_wcv_store_state', $vendor_meta ) ) ? $vendor_meta['_wcv_store_state'] : '';
$phone          = ( array_key_exists( '_wcv_store_phone', $vendor_meta ) ) ? $vendor_meta['_wcv_store_phone'] : '';
$store_postcode = ( array_key_exists( '_wcv_store_postcode', $vendor_meta ) ) ? $vendor_meta['_wcv_store_postcode'] : '';

$address = ( $address1 != '' ) ? $address1 . ', ' . $city . ', ' . $state . ', ' . $store_postcode : '';

$social_icons = empty( $twitter_username ) && empty( $instagram_username ) && empty( $facebook_url ) && empty( $linkedin_url ) && empty( $youtube_url ) && empty( $googleplus_url ) && empty( $pinterst_url ) && empty( $snapchat_username ) ? false : true;

// This is where you would load your own custom meta fields if you stored any in the settings page for the dashboard

?>
<div class="mf-vendor-store-header">
	<div class="wcv-store-grid wcv-store-header">
		<?php if ( is_singular( 'product' ) ) { ?>
			<h2 class="store-name"><?php echo esc_html( $store_title ); ?></h2>
		<?php } else { ?>
			<h1 class="store-name"><?php echo esc_html( $store_title ); ?></h1>
		<?php } ?>
		<div class="wcv-store-ratings">
			<?php if ( ! WCVendors_Pro::get_option( 'ratings_management_cap' ) ) {
				echo WCVendors_Pro_Ratings_Controller::ratings_link( $vendor_id, true );
			} ?>
		</div>
	</div>

	<div class="wcv-store-grid wcv-store-description">
		<?php if ( $verified_vendor ) : ?>
			<div class="wcv-verified-vendor">
				<?php echo wp_kses_post($verified_vendor_label); ?>
			</div>
		<?php endif; ?>
		<p><?php echo wp_kses_post($vendor_meta['pv_shop_description']); ?></p>
	</div>

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
					<?php if ( $pinterest_url != '' ) { ?>
						<li>
						<a class="social-pinterest" href="<?php echo esc_url( $pinterest_url ); ?>" target="_blank"><i class="ion-social-pinterest"></i></a>
						</li><?php } ?>
					<?php if ( $youtube_url != '' ) { ?>
						<li>
						<a class="social-youtube" href="<?php echo esc_url( $youtube_url ); ?>" target="_blank"><i class="ion-social-youtube"></i></a>
						</li><?php } ?>
					<?php if ( $linkedin_url != '' ) { ?>
						<li>
						<a class="social-linkedin" href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank"><i class="ion-social-linkedin"></i></a>
						</li><?php } ?>
					<?php if ( $snapchat_username != '' ) { ?>
						<li>
						<a class="social-snapchat" href="//www.snapchat.com/add/<?php echo esc_attr( $snapchat_username ); ?>" target="_blank"><i class="ion-social-snapchat" aria-hidden="true"></i></a>
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
		$user = get_userdata( $vendor_id );
		if ( $user ) { ?>
			<div class="store-contact">
				<?php esc_html_e( 'Or contact seller via email', 'martfury' ); ?>
				<a href="mailto:<?php echo esc_attr( $user->user_email ); ?>"><?php echo esc_html( $user->user_email ); ?></a>
			</div>
		<?php } ?>
	</div>
</div>

