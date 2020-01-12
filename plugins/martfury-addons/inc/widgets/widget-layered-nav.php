<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists('Martfury_Widget_Layered_Nav') ) {
	/**
	 * Layered Navigation Widget.
	 *
	 * @author   WooThemes
	 * @category Widgets
	 * @package  WooCommerce/Widgets
	 * @version  2.6.0
	 * @extends  WC_Widget
	 */
	class Martfury_Widget_Layered_Nav extends WC_Widget {

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->widget_cssclass    = 'woocommerce widget_layered_nav woocommerce-widget-layered-nav mf-widget-layered-nav';
			$this->widget_description = esc_html__( 'Display a list of attributes to filter products in your store.', 'martfury' );
			$this->widget_id          = 'martfury_layered_nav';
			$this->widget_name        = esc_html__( 'Martfury - Filter Products by Attribute', 'martfury' );
			parent::__construct();
		}

		/**
		 * Updates a particular instance of a widget.
		 *
		 * @see WP_Widget->update
		 *
		 * @param array $new_instance
		 * @param array $old_instance
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			$this->init_settings();

			return parent::update( $new_instance, $old_instance );
		}

		/**
		 * Outputs the settings update form.
		 *
		 * @see WP_Widget->form
		 *
		 * @param array $instance
		 */
		public function form( $instance ) {
			$this->init_settings();
			parent::form( $instance );
		}

		/**
		 * Init settings after post types are registered.
		 */
		public function init_settings() {
			$attribute_array      = array();
			$attribute_taxonomies = wc_get_attribute_taxonomies();

			if ( ! empty( $attribute_taxonomies ) ) {
				foreach ( $attribute_taxonomies as $tax ) {
					if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
						$attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
					}
				}
			}

			$this->settings = array(
				'title'          => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Filter by', 'martfury' ),
					'label' => esc_html__( 'Title', 'martfury' ),
				),
				'attribute'      => array(
					'type'    => 'select',
					'std'     => '',
					'label'   => esc_html__( 'Attribute', 'martfury' ),
					'options' => $attribute_array,
				),
				'attribute_type' => array(
					'type'    => 'select',
					'std'     => '',
					'label'   => esc_html__( 'Attribute Type', 'martfury' ),
					'options' => array(
						'default' => esc_html__( 'Default', 'martfury' ),
						'list'    => esc_html__( 'List', 'martfury' ),
					),
				),
				'query_type'     => array(
					'type'    => 'select',
					'std'     => 'and',
					'label'   => esc_html__( 'Query type', 'martfury' ),
					'options' => array(
						'and' => esc_html__( 'AND', 'martfury' ),
						'or'  => esc_html__( 'OR', 'martfury' ),
					),
				),
				'search'         => array(
					'type'  => 'checkbox',
					'std'   => '1',
					'label' => esc_html__( 'Search Box', 'martfury' ),
				),
				'height'         => array(
					'type'  => 'text',
					'std'   => '130px',
					'label' => esc_html__( 'Max height.', 'martfury' ),
				),
			);
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
			$taxonomy           = isset( $instance['attribute'] ) ? wc_attribute_taxonomy_name( $instance['attribute'] ) : $this->settings['attribute']['std'];
			$query_type         = isset( $instance['query_type'] ) ? $instance['query_type'] : $this->settings['query_type']['std'];

			if ( ! taxonomy_exists( $taxonomy ) ) {
				return;
			}

			$get_terms_args = array( 'hide_empty' => '1' );

			$orderby = wc_attribute_orderby( $taxonomy );

			switch ( $orderby ) {
				case 'name' :
					$get_terms_args['orderby']    = 'name';
					$get_terms_args['menu_order'] = false;
					break;
				case 'id' :
					$get_terms_args['orderby']    = 'id';
					$get_terms_args['order']      = 'ASC';
					$get_terms_args['menu_order'] = false;
					break;
				case 'menu_order' :
					$get_terms_args['menu_order'] = 'ASC';
					break;
			}

			$terms = get_terms( $taxonomy, $get_terms_args );

			if ( is_wp_error( $terms ) ) {
				return;
			}

			if ( 0 === sizeof( $terms ) ) {
				return;
			}

			switch ( $orderby ) {
				case 'name_num' :
					usort( $terms, '_wc_get_product_terms_name_num_usort_callback' );
					break;
				case 'parent' :
					usort( $terms, '_wc_get_product_terms_parent_usort_callback' );
					break;
			}

			ob_start();

			$this->widget_start( $args, $instance );

			$height    = isset( $instance['height'] ) ? $instance['height'] : '100%';
			$search    = ( isset( $instance['search'] ) && $instance['search'] ) ? true : false;
			$attr_type = isset( $instance['attribute_type'] ) ? $instance['attribute_type'] : '';
			$found     = $this->layered_nav_list( $terms, $taxonomy, $query_type, $height, $search, $attr_type );

			$this->widget_end( $args );

			// Force found when option is selected - do not force found on taxonomy attributes
			if ( ! is_tax() && is_array( $_chosen_attributes ) && array_key_exists( $taxonomy, $_chosen_attributes ) ) {
				$found = true;
			}

			if ( ! $found ) {
				ob_end_clean();
			} else {
				echo ob_get_clean();
			}
		}

		/**
		 * Return the currently viewed taxonomy name.
		 * @return string
		 */
		protected function get_current_taxonomy() {
			return is_tax() ? get_queried_object()->taxonomy : '';
		}

		/**
		 * Return the currently viewed term ID.
		 * @return int
		 */
		protected function get_current_term_id() {
			return absint( is_tax() ? get_queried_object()->term_id : 0 );
		}

		/**
		 * Return the currently viewed term slug.
		 * @return int
		 */
		protected function get_current_term_slug() {
			return absint( is_tax() ? get_queried_object()->slug : 0 );
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

			// Min Rating Arg
			if ( isset( $_GET['product_brand'] ) ) {
				$link = add_query_arg( 'product_brand', wc_clean( $_GET['product_brand'] ), $link );
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
		 * Show list based layered nav.
		 *
		 * @param  array $terms
		 * @param  string $taxonomy
		 * @param  string $query_type
		 *
		 * @return bool   Will nav display?
		 */
		protected function layered_nav_list( $terms, $taxonomy, $query_type, $height, $search, $attribute_type ) {
			// List display

			$attr_type = '';

			if ( $attribute_type == 'default' && function_exists( 'TA_WCVS' ) ) {
				$attr = TA_WCVS()->get_tax_attribute( $taxonomy );
				if ( $attr ) {
					$attr_type = $attr->attribute_type;
				}
			}

			if ( $search ) {
				echo '<div class="search_layered_nav">';
				echo '<input type="text" class="mf-input-search-nav">';
				echo '</div>';
			}
			$list_class = '';
			if ( ! empty( $height ) && $height != '100%' ) {
				$list_class = 'mf-widget-layered-nav-scroll';
			}

			echo '<ul class="woocommerce-widget-layered-nav-list ' . esc_attr( $list_class ) . '" data-height="' . esc_attr( $height ) . '">';

			$term_counts        = martfury_get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
			$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
			$found              = false;

			$show_swatch = false;
			if ( class_exists( 'TA_WC_Variation_Swatches' ) ) {
				$show_swatch = true;
			}

			foreach ( $terms as $term ) {
				$current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();
				$option_is_set  = in_array( $term->slug, $current_values );
				$count          = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;


				// Only show options with count > 0
				if ( 0 < $count ) {
					$found = true;
				} elseif ( 0 === $count && ! $option_is_set ) {
					continue;
				}

				$filter_name    = 'filter_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) );
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

					// Add Query type Arg to URL
					if ( 'or' === $query_type && ! ( 1 === sizeof( $current_filter ) && $option_is_set ) ) {
						$link = add_query_arg( 'query_type_' . sanitize_title( str_replace( 'pa_', '', $taxonomy ) ), 'or', $link );
					}
				}

				$swatch = $this->swatch_html( $term, $attr_type, $count );
				if ( $show_swatch && empty( $swatch ) ) {
					$show_swatch = false;
				}

				$css_swatch  = $show_swatch ? 'show-swatch ' : '';
				$swatch_html = $show_swatch ? $swatch : esc_html( $term->name );

				if ( $count > 0 || $option_is_set ) {
					$link      = esc_url( apply_filters( 'woocommerce_layered_nav_link', $link, $term, $taxonomy ) );
					$term_html = '<a  data-title="' . $term->name . '" href="' . $link . '">' . $swatch_html . '</a>';

				} else {
					$link      = false;
					$term_html = '<span>' . $swatch_html . '</span>';
				}


				if ( ! $show_swatch ) {
					$term_html .= ' ' . apply_filters( 'woocommerce_layered_nav_count', '<span class="count">(' . absint( $count ) . ')</span>', $count, $term );
				}

				echo '<li class="woocommerce-widget-layered-nav-list__item wc-layered-nav-term ' . esc_attr( $css_swatch ) . ' ' . ( $option_is_set ? 'woocommerce-widget-layered-nav-list__item--chosen chosen' : '' ) . '">';
				echo $term_html;
				echo '</li>';
			}

			echo '</ul>';

			return $found;
		}

		/**
		 * Print HTML of a single swatch
		 *
		 * @since  1.0.0
		 * @return string
		 */
		protected function swatch_html( $term, $attr_type, $count ) {

			$html = '';
			$name = $term->name;

			switch ( $attr_type ) {
				case 'color':
					$color = get_term_meta( $term->term_id, 'color', true );
					$html  = sprintf(
						'<span class="swatch swatch-color" title="%s (%s)" data-rel="tooltip"><span class="sub-swatch" style="background-color:%s;"></span><span class="term-name">%s</span> </span>',
						esc_attr( $name ),
						esc_attr( $count ),
						esc_attr( $color ),
						esc_attr( $name )
					);

					break;

				case 'image':
					$image = get_term_meta( $term->term_id, 'image', true );
					if ( $image ) {
						$image = wp_get_attachment_image_src( $image );
						$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
						$html  = sprintf(
							'<span class="swatch swatch-image" title="%s (%s)" data-rel="tooltip"><img src="%s" alt="%s"></span>',
							esc_attr( $name ),
							esc_attr( $count ),
							esc_url( $image ),
							esc_attr( $name )
						);
					}

					break;

				case 'label':
					$label = get_term_meta( $term->term_id, 'label', true );
					$label = $label ? $label : $term->name;
					$html  = sprintf(
						'<span class="swatch swatch-label">%s</span>',
						esc_attr( $label )
					);
					break;

				default:
					$html = '';
					break;

			}

			return $html;
		}
	}
}