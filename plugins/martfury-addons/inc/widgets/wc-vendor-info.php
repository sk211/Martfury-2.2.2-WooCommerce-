<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists('Martfury_Widget_WC_Vendor_Info') ) {

	/**
	 * Layered Navigation Filters Widget.
	 *
	 * @author   WooThemes
	 * @category Widgets
	 * @package  WooCommerce/Widgets
	 * @version  2.3.0
	 * @extends  WC_Widget
	 */
	class Martfury_Widget_WC_Vendor_Info extends WC_Widget {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->widget_cssclass    = 'woocommerce widget_vendor_info';
			$this->widget_description = esc_html__( 'Display vendor information.', 'martfury' );
			$this->widget_id          = 'martfury_vendor_info';
			$this->widget_name        = esc_html__( 'Martfury - Vendor Information', 'martfury' );
			$this->settings           = array(
				'title' => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'Title', 'martfury' ),
				),
			);

			parent::__construct();
		}


		/**
		 * Output widget.
		 *
		 * @see WP_Widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {

			$this->widget_start( $args, $instance );
			$this->wc_vendor_info();
			$this->dc_vendor_info();
			$this->widget_end( $args );
		}

		function wc_vendor_info() {
			if ( ! class_exists( 'WCV_Vendors' ) ) {
				return '';
			}
			global $wcvendors_pro;
			$vendor_shop = urldecode( get_query_var( 'vendor_shop' ) );
			$vendor_id   = WCV_Vendors::get_vendor_id( $vendor_shop );

			$store_icon = $wcvendors_pro ? wp_get_attachment_image( get_user_meta( $vendor_id, '_wcv_store_icon_id', true ), array(
				270,
				270,
			) ) : '';
			$avatar     = $store_icon ? $store_icon : get_avatar( $vendor_id, 270 );
			echo $avatar;

			echo '<div class="store-content">';
			if ( $wcvendors_pro ) {
				if ( WCV_Vendors::is_vendor_page() ) {

					$vendor_meta = array_map( array( $this, 'vc_vendor_array' ), get_user_meta( $vendor_id ) );

					wc_get_template( 'store-info.php', array(
						'vendor_id'   => $vendor_id,
						'vendor_meta' => $vendor_meta,
					), 'wc-vendors/widget/' );

				}

			} else {
				WCV_Vendor_Shop::vendor_main_header();
			}

			echo '</div>';
		}

		function vc_vendor_array( $a ) {
			return $a[0];
		}

		function dc_vendor_info() {
			if ( ! class_exists( 'WCMp' ) ) {
				return '';
			}

			if ( ! martfury_is_dc_vendor_store() ) {
				return '';
			}

			$term_id = get_queried_object()->term_id;

			$address = '';
			if ( ! function_exists( 'get_wcmp_vendor_by_term' ) ) {
				return '';
			}
			$vendor = get_wcmp_vendor_by_term( $term_id );
			if ( empty( $vendor ) ) {
				return '';
			}
			$vendor_id = $vendor->id;
			if ( $vendor->get_image() ) {
				echo sprintf( '<img src="%s" >', esc_url( $vendor->get_image() ) );

			} else {
				echo get_avatar( $vendor_id, 270 );
			}

			if ( $vendor->city ) {
				$address = $vendor->city . ', ';
			}
			if ( $vendor->state ) {
				$address .= $vendor->state . ', ';
			}
			if ( $vendor->country ) {
				$address .= $vendor->country;
			}

			$phone       = $vendor->phone;
			$email       = $vendor->user_data->user_email;
			$description = $vendor->description;
			echo '<div class="store-content">';
			wc_get_template( 'store-info.php', array(
				'vendor_id'   => $vendor_id,
				'address'     => $address,
				'phone'       => $phone,
				'email'       => $email,
				'description' => $description,
			), 'dc-vendors/widget/' );
			echo '</div>';
		}
	}
}