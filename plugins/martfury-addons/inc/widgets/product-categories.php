<?php
/**
 * Product Categories Widget
 *
 * @author   Automattic
 * @category Widgets
 * @package  WooCommerce/Widgets
 * @version  2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists('Martfury_Widget_Product_Categories') ) {
	/**
	 * Product categories widget class.
	 *
	 * @extends WC_Widget
	 */
	class Martfury_Widget_Product_Categories extends WC_Widget {

		/**
		 * Category ancestors.
		 *
		 * @var array
		 */
		public $cat_ancestors;

		/**
		 * Current Category.
		 *
		 * @var bool
		 */
		public $current_cat;

		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->widget_cssclass    = 'woocommerce mf_widget_product_categories';
			$this->widget_description = esc_html__( 'A list of product categories.', 'martfury' );
			$this->widget_id          = 'mf_product_categories';
			$this->widget_name        = esc_html__( 'Martfury - Product Categories', 'martfury' );
			$this->settings           = array(
				'title'      => array(
					'type'  => 'text',
					'std'   => esc_html__( 'Product categories', 'martfury' ),
					'label' => esc_html__( 'Title', 'martfury' ),
				),
				'orderby'    => array(
					'type'    => 'select',
					'std'     => 'name',
					'label'   => esc_html__( 'Order by', 'martfury' ),
					'options' => array(
						'order' => esc_html__( 'Category order', 'martfury' ),
						'title' => esc_html__( 'Name', 'martfury' ),
						'count' => esc_html__( 'Count', 'martfury' ),
					),
				),
				'count'      => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Show product counts', 'martfury' ),
				),
				'hide_empty' => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Hide empty categories', 'martfury' ),
				),
				'show_all_cats' => array(
					'type'  => 'checkbox',
					'std'   => 0,
					'label' => esc_html__( 'Show all categories in the subcategory page', 'martfury' ),
				),
				'max_depth'  => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'Maximum depth', 'martfury' ),
				),
			);

			parent::__construct();
		}

		/**
		 * Output widget.
		 *
		 * @see WP_Widget
		 *
		 * @param array $args Widget arguments.
		 * @param array $instance Widget instance.
		 */
		public function widget( $args, $instance ) {
			global $wp_query, $post;

			$count         = isset( $instance['count'] ) ? $instance['count'] : $this->settings['count']['std'];
			$orderby       = isset( $instance['orderby'] ) ? $instance['orderby'] : $this->settings['orderby']['std'];
			$hide_empty    = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : $this->settings['hide_empty']['std'];
			$show_all_cats    = isset( $instance['show_all_cats'] ) ? $instance['show_all_cats'] : $this->settings['show_all_cats']['std'];
			$dropdown_args = array(
				'hide_empty' => $hide_empty,
			);
			$list_args     = array(
				'show_count'   => $count,
				'hierarchical' => 1,
				'taxonomy'     => 'product_cat',
				'hide_empty'   => $hide_empty,
			);
			$max_depth     = absint( isset( $instance['max_depth'] ) ? $instance['max_depth'] : $this->settings['max_depth']['std'] );

			$list_args['menu_order'] = false;
			$dropdown_args['depth']  = $max_depth;
			$list_args['depth']      = $max_depth;

			if ( 'order' === $orderby ) {
				$list_args['menu_order'] = 'asc';
			} else {
				$list_args['orderby'] = $orderby;
				if ( $orderby === 'count' ) {
					$atts['order'] = 'desc';
				}
			}

			$this->current_cat   = false;
			$this->cat_ancestors = array();

			if ( is_tax( 'product_cat' ) && ! $show_all_cats ) {
				$this->current_cat   = $wp_query->queried_object;
				$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );

			} elseif ( is_singular( 'product' ) ) {
				$product_category = wc_get_product_terms(
					$post->ID, 'product_cat', apply_filters(
						'woocommerce_product_categories_widget_product_terms_args', array(
							'orderby' => 'parent',
						)
					)
				);


				if ( ! empty( $product_category ) ) {
					$current_term = '';
					foreach ( $product_category as $term ) {
						if ( $term->parent != 0 ) {
							$current_term = $term;
							break;
						}
					}
					$this->current_cat   = $current_term ? $current_term : $product_category[0];
					$this->cat_ancestors = get_ancestors( $this->current_cat->term_id, 'product_cat' );
				}

			}

			$this->widget_start( $args, $instance );

			$list_args['title_li']                   = '';
			$list_args['pad_counts']                 = 1;
			$list_args['show_option_none']           = esc_html__( 'No product categories exist.', 'martfury' );
			$list_args['current_category']           = ( $this->current_cat ) ? $this->current_cat->term_id : '';
			$list_args['current_category_ancestors'] = $this->cat_ancestors;
			$list_args['max_depth']                  = $max_depth;

			$parent_term_id = 0;
			if ( is_tax( 'product_cat' ) || is_singular( 'product' ) ) {
				if ( count( $this->cat_ancestors ) > 0 ) {
					$parent_term_id = end( $this->cat_ancestors );
				}

				$children_terms = get_term_children( $parent_term_id, 'product_cat' );
				if ( count( $children_terms ) <= 0 ) {
					$parent_term_id = 0;
				}

			}
			$list_args['child_of'] = $parent_term_id;

			echo '<ul class="product-categories">';
			if ( $parent_term_id ) {
				$parent_term = get_term_by( 'id', $parent_term_id, 'product_cat' );
				echo '<li class="current-cat-parent mf-current-cat-parent"><a href="' . esc_url( get_term_link( $parent_term_id, 'product_cat' ) ) . '">' . $parent_term->name . '</a>';
				echo '<ul class="children">';
			}
			wp_list_categories( apply_filters( 'woocommerce_product_categories_widget_args', $list_args ) );
			if ( $parent_term_id ) {
				echo '</ul>';
				echo '</li>';
			}
			echo '</ul>';

			$this->widget_end( $args );
		}
	}
}