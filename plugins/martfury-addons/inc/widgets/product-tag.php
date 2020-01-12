<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if( ! class_exists('Martfury_Widget_Product_Tag_Cloud') ) {
	/**
	 * Tag Cloud Widget.
	 *
	 */
	class Martfury_Widget_Product_Tag_Cloud extends WC_Widget {

		/**
		 * Constructor.
		 */
		public function __construct() {


			$this->widget_cssclass    = 'woocommerce mf-widget-layered-nav woocommerce-widget-layered-nav';
			$this->widget_description = esc_html__( 'Your most used product tags in cloud format.', 'martfury' );
			$this->widget_id          = 'martfury_product_tag_cloud';
			$this->widget_name        = esc_html__( 'Martfury Product Tags', 'martfury' );
			$this->settings           = array(
				'title'  => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Product Tags', 'martfury' ),
					'label' => esc_html__( 'Title', 'martfury' ),
				),
				'height' => array(
					'type'  => 'text',
					'std'   => '130',
					'label' => esc_html__( 'Max height(px)', 'martfury' ),
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
			if ( ! martfury_is_catalog() ) {
				return;
			}

			$taxonomy = 'product_tag';
			if ( ! taxonomy_exists( $taxonomy ) ) {
				return;
			}

			$get_terms_args = array( 'hide_empty' => '1' );

			$terms = get_terms( $taxonomy, $get_terms_args );

			if ( 0 === sizeof( $terms ) ) {
				return;
			}

			ob_start();

			$this->widget_start( $args, $instance );

			$height = intval( $instance['height'] ) ? intval( $instance['height'] ) : 0;

			$found = $this->layered_nav_list( $terms, $taxonomy, $height );

			$this->widget_end( $args );

			if ( ! $found ) {
				ob_end_clean();
			} else {
				echo ob_get_clean();
			}
		}

		/**
		 * Get current page URL for layered nav items.
		 *
		 * @param string $taxonomy
		 *
		 * @return string
		 */
		protected function get_page_base_url( $taxonomy ) {
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
					if ( $name === $taxonomy ) {
						continue;
					}
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
		 * Return the currently viewed term slug.
		 * @return int
		 */
		protected function get_current_term_slug() {
			return absint( is_tax() ? get_queried_object()->slug : 0 );
		}


		/**
		 * Show list based layered nav.
		 *
		 * @param  array $terms
		 * @param  string $taxonomy
		 * @param  string $query_type
		 *
		 * @return bool   Will nav display?
		 */
		protected function layered_nav_list( $terms, $taxonomy, $height ) {
			// List display
			echo '<div class="search_layered_nav">';
			echo '<input type="text" class="mf-input-search-nav">';
			echo '</div>';
			echo '<ul class="woocommerce-widget-layered-nav-list mf-widget-layered-nav-scroll" data-height="' . esc_attr( $height ) . '">';
			$term_counts = martfury_get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, 'or' );
			$found       = false;

			foreach ( $terms as $term ) {
				$count          = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;
				$current_values = ! empty( $_GET[ $taxonomy ] ) ? explode( ',', wc_clean( $_GET[ $taxonomy ] ) ) : array();
				if ( empty( $current_values ) && ( function_exists( 'is_product_tag' ) && is_product_tag() ) ) {
					$current_term = get_queried_object();
					if ( $current_term ) {
						$current_values[] = $current_term->slug;
					}
				}
				$option_is_set = in_array( $term->slug, $current_values );
				// Only show options with count > 0
				if ( 0 < $count ) {
					$found = true;
				} elseif ( 0 === $count && ! $option_is_set ) {
					continue;
				}

				$filter_name    = sanitize_title( $taxonomy );
				$current_filter = isset( $_GET[ $filter_name ] ) ? explode( ',', wc_clean( $_GET[ $filter_name ] ) ) : array();
				$current_filter = array_map( 'sanitize_title', $current_filter );

				if ( ! in_array( $term->slug, $current_filter ) ) {
					$current_filter[] = $term->slug;
				}

				$link = $this->get_page_base_url( $taxonomy );
				// Add current filters to URL.
				foreach ( $current_filter as $key => $value ) {
					// Exclude query arg for current term archive term
					if ( $value === $this->get_current_term_slug() ) {
						unset( $current_filter[ $key ] );
					}
					// Exclude self so filter can be unset on click.
					if ( $option_is_set && $value === $term->slug ) {
						unset( $current_filter[ $key ] );
					}

				}

				if ( ! empty( $current_filter ) ) {
					$link = add_query_arg( $filter_name, implode( ',', $current_filter ), $link );
				}

				if ( $count > 0 || $option_is_set ) {
					$link      = esc_url( apply_filters( 'woocommerce_layered_nav_link', $link, $term, $taxonomy ) );
					$term_html = '<a data-title="' . esc_html( $term->name ) . '" href="' . $link . '">' . esc_html( $term->name ) . '</a>';
				} else {
					$link      = false;
					$term_html = '<span data-title="' . esc_html( $term->name ) . '">' . esc_html( $term->name ) . '</span>';
				}

				$term_html .= ' ' . apply_filters( 'woocommerce_layered_nav_count', '<span class="count">(' . absint( $count ) . ')</span>', $count, $term );

				echo '<li class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term ' . ( $option_is_set ? 'woocommerce-widget-layered-nav-list__item--chosen chosen' : '' ) . '">';
				echo $term_html;
				echo '</li>';
			}

			echo '</ul>';

			return $found;
		}


	}
}