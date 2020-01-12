<?php

namespace MartfuryAddons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Icon Box widget
 */
class Recently_Viewed_Products extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'martfury-recently-viewed-products';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Recently Viewed Products', 'martfury' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-posts-grid';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'martfury' ];
	}

	public function get_script_depends() {
		return [
			'martfury-elementor'
		];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_product',
			[ 'label' => esc_html__( 'Products', 'martfury' ) ]
		);

		$this->add_control(
			'per_page',
			[
				'label'   => esc_html__( 'Total Products', 'martfury' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 12,
				'min'     => 2,
				'max'     => 50,
				'step'    => 1,
			]
		);

		$this->add_control(
			'columns',
			[
				'label'   => esc_html__( 'Columns', 'martfury' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'3' => esc_html__( '3 Columns', 'martfury' ),
					'4' => esc_html__( '4 Columns', 'martfury' ),
					'5' => esc_html__( '5 Columns', 'martfury' ),
					'6' => esc_html__( '6 Columns', 'martfury' ),
				],
				'default' => '5',
				'toggle'  => false,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_pagination',
			[ 'label' => esc_html__( 'Pagination', 'martfury' ) ]
		);
		$this->add_control(
			'pagination',
			[
				'label'        => esc_html__( 'Show Pagination', 'martfury' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'martfury' ),
				'label_off'    => esc_html__( 'Hide', 'martfury' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Render icon box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$classes = [
			'mf-product-recently-viewed woocommerce'
		];

		$this->add_render_attribute( 'wrapper', 'class', $classes );

		$viewed_products = ! empty( $_COOKIE['woocommerce_recently_viewed'] ) ? (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] ) : [ ];
		$viewed_products = array_reverse( array_filter( array_map( 'absint', $viewed_products ) ) );

		$output = [ ];

		$per_page   = intval( $settings['per_page'] );
		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => $per_page,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'fields'              => 'ids',
			'post__in'            => $viewed_products,
			'orderby'             => 'post__in',
		);
		if ( $settings['pagination'] == 'yes' ) {
			$paged                       = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$offset                      = ( $paged - 1 ) * $per_page;
			$query_args['offset']        = $offset;
			$query_args['no_found_rows'] = false;
		}

		$products = new \WP_Query( $query_args );

		if ( ! $products->have_posts() ) {
			return '';
		}

		global $woocommerce_loop;

		$woocommerce_loop['columns'] = intval( $settings['columns'] );

		ob_start();

		if ( $products->have_posts() ) :

			woocommerce_product_loop_start();
			while ( $products->have_posts() ) : $products->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile; // end of the loop.

			woocommerce_product_loop_end();

		endif;

		wp_reset_postdata();

		$output[] = ob_get_clean();

		if ( $settings['pagination'] == 'yes' ) {
			ob_start();
			$total_pages = $products->max_num_pages;
			$this->pagination_numeric( $total_pages );
			$output[] = ob_get_clean();
		}

		echo sprintf(
			'<div %s>%s</div>',
			$this->get_render_attribute_string( 'wrapper' ),
			implode( '', $output )
		);
	}

	/**
	 * Render icon box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 */
	protected function _content_template() {
	}

	/**
	 * Display numeric pagination
	 *
	 * @param $max_num_pages
	 */
	protected function pagination_numeric( $max_num_pages ) {
		?>
		<nav class="navigation paging-navigation numeric-navigation">
			<?php
			$big  = 999999999;
			$args = array(
				'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'total'     => $max_num_pages,
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'prev_text' => esc_html__( 'Previous', 'martfury' ),
				'next_text' => esc_html__( 'Next', 'martfury' ),
				'type'      => 'plain',
			);

			echo paginate_links( $args );
			?>
		</nav>
		<?php
	}
}