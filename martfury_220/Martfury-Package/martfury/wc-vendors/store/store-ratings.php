<?php
/**
 * Display the vendor store ratings
 *
 * Override this template by copying it to yourtheme/wc-vendors/store
 *
 * @package    WCVendors_Pro
 * @version    1.2.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$vendor_shop     = urldecode( get_query_var( 'vendor_shop' ) );
$vendor_id       = WCV_Vendors::get_vendor_id( $vendor_shop );
$vendor_feedback = WCVendors_Pro_Ratings_Controller::get_vendor_feedback( $vendor_id );
$vendor_shop_url = WCV_Vendors::get_vendor_shop_page( $vendor_id );

get_header( 'shop' ); ?>

<?php do_action( 'woocommerce_before_main_content' ); ?>
	<div class="mf-store-ratings-content">

		<h2 class="page-title"><?php esc_html_e( 'Customer Ratings', 'martfury' ); ?></h2>

		<?php if ( $vendor_feedback ) {

			foreach ( $vendor_feedback as $vf ) {

				$customer      = get_userdata( $vf->customer_id );
				$rating        = $vf->rating;
				$rating_title  = $vf->rating_title;
				$comment       = $vf->comments;
				$post_date     = date_i18n( get_option( 'date_format' ), strtotime( $vf->postdate ) );
				$customer_name = ucfirst( $customer->display_name );
				$product_link  = get_permalink( $vf->product_id );
				$product_title = get_the_title( $vf->product_id );

				// This outputs the star rating
				$stars = '';
				for ( $i = 1; $i <= stripslashes( $rating ); $i ++ ) {
					$stars .= "<i class='fa fa-star'></i>";
				}
				for ( $i = stripslashes( $rating ); $i < 5; $i ++ ) {
					$stars .= "<i class='fa fa-star-o'></i>";
				}
				?>
				<h2 class="product-title">
					<a href="<?php echo esc_url($product_link); ?>" target="_blank"><?php echo wp_kses_post($product_title); ?></a></h2>
				<div class="rating-html">
					<?php if ( ! empty( $rating_title ) ) {
						echo wp_kses_post($rating_title) . ' :: ';
					} ?><?php echo wp_kses_post($stars); ?>
				</div>
				<div class="rating-meta">
					<?php echo wp_kses_post($post_date); ?>
					<?php esc_html_e( 'by', 'martfury' );
					echo ' ' . wp_kses_post($customer_name); ?>
				</div>
				<div class="rating-comment">
					<p><?php echo wp_kses_post($comment); ?></p>
				</div>
				<hr />

				<?php
			}


		} else {
			echo esc_html__( 'No ratings have been submitted for this vendor yet.', 'martfury' );
		} ?>

		<a class="btn-return" href="<?php echo esc_url($vendor_shop_url); ?>"><?php esc_html_e( 'Return to store', 'martfury' ); ?></a>
	</div>

<?php do_action( 'woocommerce_after_main_content' ); ?>


<?php do_action( 'woocommerce_sidebar' ); ?>

<?php get_footer( 'shop' ); ?>