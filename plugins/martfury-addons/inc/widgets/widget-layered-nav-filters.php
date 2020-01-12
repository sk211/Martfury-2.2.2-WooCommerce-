<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists('Martfury_Widget_Layered_Nav_Filters') ) {
	/**
	 * Layered Navigation Filters Widget.
	 *
	 * @author   WooThemes
	 * @category Widgets
	 * @package  WooCommerce/Widgets
	 * @version  2.3.0
	 * @extends  WC_Widget
	 */
	class Martfury_Widget_Layered_Nav_Filters extends WC_Widget {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->widget_cssclass    = 'woocommerce widget_layered_nav_filters';
			$this->widget_description = esc_html__( 'Display a list of active product filters.', 'martfury' );
			$this->widget_id          = 'martfury_layered_nav_filters';
			$this->widget_name        = esc_html__( 'Martfury - Active Product Filters', 'martfury' );
			$this->settings           = array(
				'title' => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Active filters', 'martfury' ),
					'label' => esc_html__( 'Title', 'martfury' ),
				),
			);

			parent::__construct();
		}

		/**
		 * Get current page URL for layered nav items.
		 *
		 * @return string
		 */
		protected function get_page_base_url() {

			$link = $this->get_page_current_url();

			// Min/Max
			if ( isset( $_GET['min_price'] ) ) {
				$link = add_query_arg( 'min_price', wc_clean( $_GET['min_price'] ), $link );
			}

			if ( isset( $_GET['max_price'] ) ) {
				$link = add_query_arg( 'max_price', wc_clean( $_GET['max_price'] ), $link );
			}

			// Order by
			if ( isset( $_GET['orderby'] ) ) {
				$link = add_query_arg( 'orderby', wc_clean( $_GET['orderby'] ), $link );
			}

			// Min Rating Arg
			if ( isset( $_GET['product_brand'] ) ) {
				$link = add_query_arg( 'product_brand', wc_clean( $_GET['product_brand'] ), $link );
			}

			/**
			 * Search Arg.
			 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
			 */
			if ( get_search_query() ) {
				$link = add_query_arg( 's', rawurlencode( htmlspecialchars_decode( get_search_query() ) ), $link );
			}

			// Post Type Arg
			if ( isset( $_GET['post_type'] ) ) {
				$link = add_query_arg( 'post_type', wc_clean( $_GET['post_type'] ), $link );
			}

			// Min Rating Arg
			if ( isset( $_GET['rating_filter'] ) ) {
				$link = add_query_arg( 'rating_filter', wc_clean( $_GET['rating_filter'] ), $link );
			}

			// All current filters
			if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) {
				foreach ( $_chosen_attributes as $name => $data ) {
					$filter_name = sanitize_title( str_replace( 'pa_', '', $name ) );
					if ( ! empty( $data['terms'] ) ) {
						$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
					}
					if ( 'or' == $data['query_type'] ) {
						$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
					}
				}
			}

			return $link;
		}

		/**
		 * Get current page URL for layered nav items.
		 *
		 * @return string
		 */
		protected function get_page_current_url() {
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
				$link           = get_term_link( $queried_object->slug, $queried_object->taxonomy );
			}

			return $link;
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
			if ( ! is_post_type_archive( 'product' ) && ! is_tax( get_object_taxonomies( 'product' ) ) ) {
				return;
			}

			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$min_price          = isset( $_GET['min_price'] ) ? wc_clean( $_GET['min_price'] ) : 0;
			$max_price          = isset( $_GET['max_price'] ) ? wc_clean( $_GET['max_price'] ) : 0;
			$rating_filter      = isset( $_GET['rating_filter'] ) ? array_filter( array_map( 'absint', explode( ',', $_GET['rating_filter'] ) ) ) : array();
			$product_brands     = isset( $_GET['product_brand'] ) ? array_filter( explode( ',', wc_clean( $_GET['product_brand'] ) ) ) : array();
			$base_link          = $this->get_page_base_url();

			if ( 0 < count( $_chosen_attributes ) || 0 < $min_price || 0 < $max_price || ! empty( $rating_filter ) || ! empty( $product_brands ) ) {

				$this->widget_start( $args, $instance );

				echo '<ul>';

				// Attributes
				if ( ! empty( $_chosen_attributes ) ) {
					foreach ( $_chosen_attributes as $taxonomy => $data ) {
						foreach ( $data['terms'] as $term_slug ) {
							if ( ! $term = get_term_by( 'slug', $term_slug, $taxonomy ) ) {
								continue;
							}

							$filter_name    = 'filter_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) );
							$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( $_GET[ $filter_name ] ) ) : array();
							$current_filter = array_map( 'sanitize_title', $current_filter );
							$new_filter     = array_diff( $current_filter, array( $term_slug ) );

							$link = remove_query_arg( array( 'add-to-cart', $filter_name ), $base_link );

							if ( sizeof( $new_filter ) > 0 ) {
								$link = add_query_arg( $filter_name, implode( ',', $new_filter ), $link );
							}

							echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'martfury' ) . '" href="' . esc_url( $link ) . '">' . esc_html( $term->name ) . '</a></li>';
						}
					}
				}

				if ( $min_price ) {
					$link = remove_query_arg( 'min_price', $base_link );
					echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'martfury' ) . '" href="' . esc_url( $link ) . '">' . sprintf( __( 'Min %s', 'martfury' ), wc_price( $min_price ) ) . '</a></li>';
				}

				if ( $max_price ) {
					$link = remove_query_arg( 'max_price', $base_link );
					echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'martfury' ) . '" href="' . esc_url( $link ) . '">' . sprintf( __( 'Max %s', 'martfury' ), wc_price( $max_price ) ) . '</a></li>';
				}

				if ( ! empty( $rating_filter ) ) {
					foreach ( $rating_filter as $rating ) {
						$link_ratings = implode( ',', array_diff( $rating_filter, array( $rating ) ) );
						$link         = $link_ratings ? add_query_arg( 'rating_filter', $link_ratings ) : remove_query_arg( 'rating_filter', $base_link );
						echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'martfury' ) . '" href="' . esc_url( $link ) . '">' . sprintf( esc_html__( 'Rated %s out of 5', 'martfury' ), esc_html( $rating ) ) . '</a></li>';
					}
				}

				if ( ! empty( $product_brands ) ) {
					foreach ( $product_brands as $brand ) {
						if ( ! $term = get_term_by( 'slug', $brand, 'product_brand' ) ) {
							continue;
						}
						$link_brands = implode( ',', array_diff( $product_brands, array( $brand ) ) );
						$link        = $link_brands ? add_query_arg( 'product_brand', $link_brands ) : remove_query_arg( 'product_brand', $base_link );
						echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove filter', 'martfury' ) . '" href="' . esc_url( $link ) . '">' . esc_html( $term->name ) . '</a></li>';
					}
				}

				$link = $this->get_page_current_url();
				echo '<li class="chosen"><a rel="nofollow" aria-label="' . esc_attr__( 'Remove all filters', 'martfury' ) . '" href="' . esc_url( $link ) . '">' . esc_attr__( 'Remove all filters', 'martfury' ) . '</a></li>';

				echo '</ul>';

				$this->widget_end( $args );
			}
		}
	}
}