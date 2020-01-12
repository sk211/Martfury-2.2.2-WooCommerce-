<?php

/**
 * Class for all WooCommerce template modification
 *
 * @version 1.0
 */
class Martfury_WooCommerce {
	/**
	 * @var string Layout of current page
	 */
	public $layout;

	/**
	 * @var string shop view
	 */
	public $shop_view;

	/**
	 * @var string top_categories
	 */
	public $featured_categories;

	/**
	 * @var string catalog Layout
	 */
	public $catalog_layout;

	/**
	 * @var array elements of current page
	 */
	public $catalog_elements;

	/**
	 * @var array elements of product page
	 */
	public $product_layout;

	public $new_duration;

	/**
	 * Construction function
	 *
	 * @since  1.0
	 * @return Martfury_WooCommerce
	 */
	function __construct() {
		add_action( 'wc_ajax_martfury_search_products', array( $this, 'instance_search_result' ) );
		add_action( 'wp_ajax_martfury_search_products', array( $this, 'instance_search_result' ) );
		add_action( 'wp_ajax_nopriv_martfury_search_products', array( $this, 'instance_search_result' ) );

		// Check if Woocomerce plugin is actived
		if ( ! class_exists( 'woocommerce' ) ) {
			return;
		}

		// Define all hook
		add_action( 'template_redirect', array( $this, 'hooks' ) );

		add_filter( 'template_include', array( $this, 'archive_template_loader' ), 20 );

		// Track Product View
		add_action( 'template_redirect', array( $this, 'martfury_track_product_view' ) );

		// Need an early hook to ajaxify update mini shop cart
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'add_to_cart_fragments' ) );

		add_action( 'wp_loaded', array( $this, 'add_to_cart_action' ), 20 );

		add_action( 'wp_ajax_martfury_fbt_add_to_cart', array( $this, 'fbt_add_to_cart' ) );
		add_action( 'wp_ajax_nopriv_martfury_fbt_add_to_cart', array( $this, 'fbt_add_to_cart' ) );

		add_action( 'wp_ajax_update_wishlist_count', array( $this, 'update_wishlist_count' ) );
		add_action( 'wp_ajax_nopriv_update_wishlist_count', array( $this, 'update_wishlist_count' ) );


		add_action( 'wp_ajax_martfury_footer_recently_viewed', array( $this, 'martfury_footer_recently_viewed' ) );
		add_action( 'wp_ajax_nopriv_martfury_footer_recently_viewed', array(
			$this,
			'martfury_footer_recently_viewed',
		) );

		add_action( 'wp_ajax_martfury_header_recently_viewed', array( $this, 'martfury_header_recently_viewed' ) );
		add_action( 'wp_ajax_nopriv_martfury_header_recently_viewed', array(
			$this,
			'martfury_header_recently_viewed',
		) );

		add_action( 'wp_ajax_martfury_product_quick_view', array( $this, 'product_quick_view' ) );
		add_action( 'wp_ajax_nopriv_martfury_product_quick_view', array(
			$this,
			'product_quick_view',
		) );


		// Remove breadcrumb, use theme's instead
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

		// WooCommerce Styles
		add_filter( 'woocommerce_enqueue_styles', array( $this, 'wc_styles' ) );

		if ( function_exists( 'wsl_render_auth_widget_in_wp_login_form' ) ) {
			add_action( 'woocommerce_login_form_end', 'wsl_render_auth_widget_in_wp_login_form' );
			add_action( 'woocommerce_register_form_end', 'wsl_render_auth_widget_in_wp_login_form', 50 );
		}

		// Change possition cross sell
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
		add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );

		// Change columns and total of cross sell
		add_filter( 'woocommerce_cross_sells_columns', array( $this, 'cross_sells_columns' ) );
		add_filter( 'woocommerce_cross_sells_total', array( $this, 'cross_sells_numbers' ) );

		// Remove badges
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash' );

		// remove add to cart link
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );

		// Remove product link
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

		// Remove shop page title
		add_filter( 'woocommerce_show_page_title', '__return_false' );

		// Add Bootstrap classes
		add_filter( 'post_class', array( $this, 'product_class' ), 20, 3 );

		add_filter( 'product_cat_class', array( $this, 'product_cat_class' ), 30, 3 );

		// Wrap product loop content
		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'open_product_inner' ), 1 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'close_product_inner' ), 50 );

		// Change shop columns
		add_filter( 'loop_shop_columns', array( $this, 'shop_columns' ), 20 );

		// Remove catelog ordering
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

		// Remove shop result count
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

		// Add div before shop loop
		add_action( 'woocommerce_before_shop_loop', array( $this, 'catalog_before_shop_loop' ), 30 );

		// Add div after shop loop
		add_action( 'woocommerce_after_shop_loop', array( $this, 'catalog_after_shop_loop' ), 20 );

		// Add product title link
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
		add_action( 'woocommerce_shop_loop_item_title', array( $this, 'products_title' ), 10 );

		// Add product thumbnail
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_content_thumbnail' ) );

		add_action( 'woocommerce_shop_loop_item_title', array( $this, 'open_product_details' ), 5 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'close_product_details' ), 100 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'close_product_content' ), 9 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'close_product_price_box' ), 100 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'open_product_details_hover' ), 100 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'product_variations_loop' ), 110 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'products_title' ), 130 );
		add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 130 );
		add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 130 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'close_product_details_hover' ), 150 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'template_single_excerpt' ), 8 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'product_loop_footer_buttons' ), 10 );

		// remove description heading
		add_filter( 'woocommerce_product_description_heading', '__return_false' );

		// Change HTML for rating
		add_filter( 'woocommerce_product_get_rating_html', array( $this, 'product_get_rating_html' ) );

		// Change HTML for price
		add_filter( 'woocommerce_format_sale_price', array( $this, 'format_sale_price' ), 20, 3 );

		// Add Catalog Banners
		add_action( 'woocommerce_before_main_content', array( $this, 'catalog_layout' ), 5 );

		// Add Recommended Products
		add_action( 'woocommerce_archive_description', array( $this, 'catalog_products_header' ), 30 );

		add_action( 'woocommerce_archive_description', array( $this, 'search_products_header' ), 30 );

		// Add Shop Toolbar
		add_action( 'woocommerce_before_shop_loop', array( $this, 'shop_toolbar' ), 20 );
		add_action( 'woocommerce_before_shop_loop', array( $this, 'catalog_toolbar_space' ), 20 );

		add_action( 'dokan_store_profile_frame_after', array( $this, 'shop_toolbar' ), 20 );

		// Add other catalog layout
		add_action( 'martfury_woocommerce_main_content', array( $this, 'catalog_main_content' ) );

		// Remove default field in single product
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );

		add_action( 'woocommerce_single_product_summary', array( $this, 'template_single_summary_header' ), 10 );

		// Add single product header
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'single_product_header' ), 5 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_entry_header' ), 5 );

		// Change HTML for price in single product
		add_filter( 'woocommerce_get_price_html', array( $this, 'get_product_price_html' ), 20, 2 );

		// Change availability text in single product
		add_filter( 'woocommerce_get_availability_text', array( $this, 'get_product_availability_text' ), 20, 2 );

		add_filter( 'woocommerce_add_to_cart_redirect', array( $this, 'buy_now_redirect' ), 99 );

		add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'yith_button' ) );

		// Remove Up-Seller & Related Product
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

		// Add instagram photos
		add_action( 'martfury_before_footer', array( $this, 'product_instagram_photos' ), 10 );
		add_action( 'martfury_before_footer', array( $this, 'products_upsell_display' ), 15 );
		add_action( 'martfury_before_footer', array( $this, 'related_products_output' ), 20 );
		add_filter( 'woocommerce_upsells_total', array( $this, 'upsells_total' ) );

		// Related options
		add_filter( 'woocommerce_product_related_posts_relate_by_category', array(
			$this,
			'related_posts_relate_by_category'
		) );

		add_filter( 'woocommerce_get_related_product_cat_terms', array(
			$this,
			'related_posts_relate_by_parent_category'
		), 20, 2 );

		add_filter( 'woocommerce_product_related_posts_relate_by_tag', array(
			$this,
			'related_posts_relate_by_tag'
		) );


		add_action( 'woocommerce_after_single_product_summary', array( $this, 'single_product_summary_open' ), 1 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'fbt_product' ), 5 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'products_full_width_upsell' ), 5 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'single_product_summary_close' ), 100 );

		add_filter( 'woocommerce_single_product_image_gallery_classes', array(
			$this,
			'product_image_gallery_classes',
		) );

		add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_metas' ), 50 );

		add_action( 'woocommerce_account_navigation', array( $this, 'account_info' ), 5 );

		add_filter( 'posts_search', array( $this, 'product_search_sku' ), 9 );

		// QuicKview
		add_action( 'martfury_before_single_product_summary', 'woocommerce_show_product_images', 20 );
		add_action( 'martfury_single_product_summary', array( $this, 'get_product_quick_view_header' ), 5 );
		add_action( 'martfury_single_product_summary', 'woocommerce_template_single_price', 10 );
		add_action( 'martfury_single_product_summary', array( $this, 'template_single_summary_header' ), 15 );
		add_action( 'martfury_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		add_action( 'martfury_single_product_summary', 'woocommerce_template_single_add_to_cart', 25 );
		add_action( 'martfury_single_product_summary', array( $this, 'single_product_socials' ), 25 );

		// Product Deals
		add_action( 'martfury_woo_shop_loop_item_title', array( $this, 'products_title' ), 10 );
		add_action( 'martfury_woo_before_shop_loop_item_title', array( $this, 'product_content_thumbnail' ) );
		add_action( 'martfury_woo_before_shop_loop_item', array( $this, 'open_product_inner' ), 1 );
		add_action( 'martfury_woo_after_shop_loop_item', array( $this, 'close_product_inner' ), 50 );
		add_action( 'martfury_woo_before_shop_loop_item_title', 'woocommerce_template_loop_price', 30 );
		add_action( 'martfury_woo_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 40 );

		// single product deal
		add_action( 'martfury_before_single_product_deal_summary', 'woocommerce_show_product_images', 20 );
		add_action( 'martfury_single_product_deal_summary', array( $this, 'single_product_deal_header' ), 5 );
		add_action( 'martfury_single_product_deal_summary', 'woocommerce_template_single_price', 10 );
		add_action( 'martfury_single_product_deal_summary', 'woocommerce_template_single_rating', 15 );
		add_action( 'martfury_single_product_deal_summary', array( $this, 'single_product_deal_stock' ), 20 );
		if ( class_exists( 'TAWC_Deals_Frontend' ) ) {
			add_action( 'martfury_single_product_deal_summary', array(
				TAWC_Deals_Frontend::instance(),
				'single_product_template',
			), 25 );
		}

		add_filter( 'tawc_deals_expire_text', array( $this, 'deals_expire_text' ) );
		add_filter( 'tawc_deals_sold_text', array( $this, 'deals_sold_text' ) );

		add_filter( 'woocommerce_product_additional_information_tab_title', array(
			$this,
			'additional_information_tab_title',
		) );

		add_filter( 'woocommerce_product_description_tab_title', array(
			$this,
			'description_tab_title',
		) );

		add_filter( 'yith_woocompare_compare_added_label', array( $this, 'compare_added_label' ) );

		add_filter( 'woocommerce_default_catalog_orderby', array( $this, 'catalog_orderby_default' ) );

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'get_product_single_excerpt' ), 20 );

		add_action( 'wp_footer', array( $this, 'sticky_product_info' ) );

		add_filter( 'woocommerce_single_product_zoom_enabled', array( $this, 'single_product_zoom_enabled' ) );

		add_action( 'yith_wcwl_table_after_product_name', array( $this, 'wcwl_table_after_product_name' ) );

		remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_terms_and_conditions_page_content', 30 );

		if ( intval( martfury_get_option( 'single_product_badges' ) ) ) {
			add_action( 'woocommerce_after_product_gallery', array( $this, 'product_ribbons' ) );
		}

		add_filter( 'martfury_site_content_container_class', array( $this, 'catalog_content_container_class' ) );
		add_filter( 'martfury_catalog_page_header_container', array( $this, 'catalog_content_container_class' ) );

		// Custom Login Form Layout
		add_action( 'martfury_after_login_form', array( $this, 'login_form_promotion' ) );

		add_filter( 'woocommerce_structured_data_product', array( $this, 'structured_data_product' ), 20, 2 );

		// Empty cart.
		add_action( 'woocommerce_cart_actions', array( $this, 'empty_cart_button' ) );
		add_action( 'template_redirect', array( $this, 'empty_cart_action' ) );

		// Remove category description while hover on category on catalog sidebar
		add_filter( 'woocommerce_product_categories_widget_args', array(
			$this,
			'mf_child_product_categories_widget_args'
		) );

		// Rest orderby in products carousel
		add_filter( 'woocommerce_shortcode_products_query', array(
			$this,
			'martfury_catalog_shortcode_products_query',
		), 20, 3 );
	}

	function mf_child_product_categories_widget_args( $args ) {
		$args['use_desc_for_title'] = 0;

		return $args;
	}

	/**
	 * Hooks to WooCommerce actions, filters
	 *
	 * @since  1.0
	 * @return void
	 */
	function hooks() {
		$this->layout           = martfury_get_layout();
		$this->catalog_layout   = martfury_get_catalog_layout();
		$this->catalog_elements = $this->get_catalog_elements();
		$this->new_duration     = martfury_get_option( 'product_newness' );
		$this->shop_view        = isset( $_COOKIE['shop_view'] ) ? $_COOKIE['shop_view'] : martfury_get_option( 'catalog_view_' . $this->catalog_layout );
		$this->product_layout   = martfury_get_product_layout();
		$this->product_layout_default();

	}

	function catalog_content_container_class( $class ) {

		if ( martfury_get_catalog_full_width() ) {
			return 'martfury-container';
		} elseif ( is_singular( 'product' ) && martfury_get_product_layout() == '6' ) {
			return 'martfury-container';
		}

		return $class;
	}

	/**
	 * Redirect Archive product
	 */
	function archive_template_loader( $template ) {
		if ( martfury_is_catalog() && in_array( $this->catalog_layout, array( '2' ) ) ) {
			$template = wc_get_template_part( 'archive', 'product-2' );
		}

		return $template;
	}


	/**
	 * Ajaxify update cart viewer
	 *
	 * @since 1.0
	 *
	 * @param array $fragments
	 *
	 * @return array
	 */
	function add_to_cart_fragments( $fragments ) {
		global $woocommerce;

		if ( empty( $woocommerce ) ) {
			return $fragments;
		}

		ob_start();
		?>

        <a href="<?php echo esc_url( wc_get_cart_url() ) ?>" class="cart-contents" id="icon-cart-contents">
            <i class="extra-icon icon-bag2"></i>
            <span class="mini-item-counter"><?php echo intval( $woocommerce->cart->cart_contents_count ) ?></span>
        </a>

		<?php
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}


	/**
	 * Remove default woocommerce styles
	 *
	 * @since  1.0
	 *
	 * @param  array $styles
	 *
	 * @return array
	 */
	function wc_styles( $styles ) {
		unset( $styles['woocommerce-layout'] );
		unset( $styles['woocommerce-smallscreen'] );

		return $styles;
	}

	/**
	 * Add  all WooCommerce screen ids.
	 *
	 * @since  1.0
	 *
	 * @param  array $screen_ids
	 *
	 * @return array
	 */
	function brand_screen_ids( $screen_ids ) {
		$screen_ids[] = 'edit-product_brand';

		return $screen_ids;
	}

	/* Shop loading
	*
	* @since  1.0.0
	* @return string
	*/
	function catalog_before_shop_loop() {
		if ( ! martfury_is_catalog() && ! martfury_is_dc_vendor_store() ) {
			return;
		}
		echo '<div id="mf-shop-content" class="mf-shop-content">';
	}

	/**
	 * Shop loading
	 *
	 * @since  1.0.0
	 * @return string
	 */
	function catalog_after_shop_loop() {
		if ( ! martfury_is_catalog() && ! martfury_is_dc_vendor_store() ) {
			return;
		}
		echo '</div>';
	}

	/**
	 * Add product title link
	 *
	 * @since  1.0
	 *
	 * @param  array $styles
	 *
	 * @return array
	 */
	function products_title() {
		printf( '<h2><a href="%s">%s</a></h2>', esc_url( get_the_permalink() ), get_the_title() );
	}

	/**
	 * Display orders tile
	 *
	 * @since 1.0
	 */
	function orders_title( $has_orders ) {
		if ( $has_orders ) {
			printf( '<h2 class="orders-title">%s</h2>', esc_html__( 'Orders History', 'martfury' ) );
		}
	}

	/**
	 * Open product detail
	 *
	 * @since  1.0
	 *
	 *
	 * @return array
	 */
	function open_product_details() {
		echo '<div class="mf-product-details"><div class="mf-product-content">';
	}

	/**
	 * Close product detail
	 *
	 * @since  1.0
	 *
	 *
	 * @return array
	 */
	function close_product_details() {
		echo '</div>';
	}

	/**
	 * Close product content
	 *
	 * @since  1.0
	 *
	 *
	 * @return array
	 */
	function close_product_content() {
		echo '</div>';
		echo '<div class="mf-product-price-box">';
	}

	/**
	 * Close product content
	 *
	 * @since  1.0
	 *
	 *
	 * @return array
	 */
	function close_product_price_box() {
		echo '</div>';
	}

	/**
	 * Open product detail
	 *
	 * @since  1.0
	 *
	 *
	 * @return array
	 */
	function open_product_details_hover() {
		echo '<div class="mf-product-details-hover">';
	}

	/**
	 * Open product detail
	 *
	 * @since  1.0
	 *
	 *
	 * @return array
	 */
	function close_product_details_hover() {
		echo '</div>';
	}


	/**
	 * WooCommerce Loop Product Content Thumbs
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function product_content_thumbnail() {
		global $product, $post;

		printf( '<div class="mf-product-thumbnail">' );

		printf( '<a href="%s">', esc_url( get_the_permalink() ) );

		$image_size = 'shop_catalog';
		if ( has_post_thumbnail() ) {
			$post_thumbnail_id = get_post_thumbnail_id( $post );
			echo martfury_get_image_html( $post_thumbnail_id, $image_size );

		} elseif ( function_exists( 'woocommerce_get_product_thumbnail' ) ) {
			echo woocommerce_get_product_thumbnail();
		}

		if ( intval( martfury_get_option( 'show_badges' ) ) ) {
			$this->product_ribbons();

		}

		echo '</a>';

		$icons = martfury_get_option( 'catalog_featured_icons' );

		$show_icons = true;

		if ( is_customize_preview() ) {
			$show_icons = apply_filters( 'martfury_preview_featured_icons', false );
		}

		if ( ! empty( $icons ) && $show_icons ) {
			echo '<div class="footer-button">';

			foreach ( $icons as $icon ) {
				if ( 'cart' == $icon ) {
					if ( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {
						woocommerce_template_loop_add_to_cart();
					}
				}

				if ( 'qview' == $icon ) {
					echo '<a href="' . $product->get_permalink() . '" data-id="' . esc_attr( $product->get_id() ) . '"  class="mf-product-quick-view"><i class="p-icon icon-eye" title="' . esc_attr__( 'Quick View', 'martfury' ) . '" data-rel="tooltip"></i></a>';
				}

				if ( 'wishlist' == $icon ) {
					if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
						echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
					}
				}

				if ( 'compare' == $icon ) {
					$this->product_compare();
				}
			}


			echo '</div>';
		}


		echo '</div>';

	}


	/**
	 * WooCommerce Loop Product Content Thumbs
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function product_loop_footer_buttons() {

		if ( ! martfury_is_catalog() && ! martfury_is_vendor_page() ) {
			return;
		}

		echo '<div class="footer-button">';

		if ( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {
			woocommerce_template_loop_add_to_cart();
		}

		echo '<div class="action-button">';
		if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
		}
		$this->product_compare();
		echo '</div>';

		echo '</div>';

	}

	/**
	 * woocommerce_template_single_excerpt
	 */
	function template_single_excerpt() {
		if ( ! martfury_is_catalog() && ! martfury_is_vendor_page() ) {
			return;
		}

		if ( function_exists( 'woocommerce_template_single_excerpt' ) ) {
			woocommerce_template_single_excerpt();
		}
	}

	/**
	 * WooCommerce product compare
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function product_compare() {
		global $product;

		if ( ! class_exists( 'YITH_Woocompare' ) ) {
			return;
		}

		$button_text = get_option( 'yith_woocompare_button_text', esc_html__( 'Compare', 'martfury' ) );
		$product_id  = $product->get_id();
		$url_args    = array(
			'action' => 'yith-woocompare-add-product',
			'id'     => $product_id,
		);
		$lang        = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : false;
		if ( $lang ) {
			$url_args['lang'] = $lang;
		}

		$css_class   = 'compare';
		$cookie_name = 'yith_woocompare_list';
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			$cookie_name .= '_' . get_current_blog_id();
		}
		$the_list = isset( $_COOKIE[ $cookie_name ] ) ? json_decode( $_COOKIE[ $cookie_name ] ) : array();
		if ( in_array( $product_id, $the_list ) ) {
			$css_class          .= ' added';
			$url_args['action'] = 'yith-woocompare-view-table';
			$button_text        = apply_filters( 'yith_woocompare_compare_added_label', esc_html__( 'Added', 'martfury' ) );
		}

		$url = esc_url_raw( add_query_arg( $url_args, site_url() ) );
		echo '<div class="compare-button mf-compare-button">';
		printf( '<a href="%s" class="%s" title="%s" data-product_id="%d">%s</a>', esc_url( $url ), esc_attr( $css_class ), esc_html( $button_text ), $product_id, $button_text );
		echo '</div>';

	}


	/**
	 * Change the shop columns
	 *
	 * @since  1.0.0
	 *
	 * @param  int $columns The default columns
	 *
	 * @return int
	 */
	function shop_columns( $columns ) {

		if ( empty( $this->catalog_layout ) ) {
			return $columns;
		}


		$columns = intval( martfury_get_option( 'products_columns_' . $this->catalog_layout ) );

		return apply_filters( 'martfury_shop_columns', $columns );

	}

	/**
	 * Add Bootstrap's column classes for product
	 *
	 * @since 1.0
	 *
	 * @param array $classes
	 * @param string $class
	 * @param string $post_id
	 *
	 * @return array
	 */
	function product_class( $classes, $class = '', $post_id = '' ) {

		if ( ! $post_id || ( get_post_type( $post_id ) !== 'product' && get_post_type( $post_id ) != 'product_variation' ) ) {
			return $classes;
		}

		if ( is_admin() && function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			if ( $screen && $screen->parent_file == 'edit.php?post_type=product' && $screen->post_type == 'product' ) {
				return $classes;
			}
		}

		if ( ! is_single( $post_id ) ) {
			global $woocommerce_loop;

			$sm_class = 'col-sm-4';

			if ( $woocommerce_loop['columns'] == 2 ) {
				$sm_class = 'col-sm-6';
			}
			$md_class = 'col-md-3';
			if ( $woocommerce_loop['columns'] == 3 ) {
				$md_class = 'col-md-4';
			}

			$classes[] = 'col-xs-6 ' . $sm_class;
			if ( $woocommerce_loop['columns'] != 5 && $woocommerce_loop['columns'] > 0 ) {
				$classes[] = $md_class . ' col-lg-' . ( 12 / $woocommerce_loop['columns'] );
			} else {
				$classes[] = 'col-mf-5';
			}
			$classes[] = 'un-' . $woocommerce_loop['columns'] . '-cols';

		} else {
			$classes[]      = 'mf-single-product';
			$product_layout = martfury_get_product_layout();
			$classes[]      = 'mf-product-layout-' . $product_layout;
			if ( in_array( $product_layout, array( '2', '5' ) ) ) {
				$classes[] = 'mf-product-sidebar';
			}

			if ( intval( martfury_get_option( 'product_buy_now' ) ) ) {
				$classes[] = 'mf-has-buy-now';
			}

		}


		return $classes;
	}

	/**
	 * Add Bootstrap's column classes for product cat
	 *
	 * @since 1.0
	 *
	 * @param array $classes
	 * @param string $class
	 * @param string $post_id
	 *
	 * @return array
	 */
	function product_cat_class( $classes, $class = '', $category = '' ) {
		if ( is_search() ) {
			return $classes;
		}

		global $woocommerce_loop;

		$sm_class = 'col-sm-4';

		if ( $woocommerce_loop['columns'] == 2 ) {
			$sm_class = 'col-sm-6';
		}

		$classes[] = 'col-xs-6 ' . $sm_class;
		if ( $woocommerce_loop['columns'] != 5 && $woocommerce_loop['columns'] > 0 ) {
			$classes[] = 'col-md-' . ( 12 / $woocommerce_loop['columns'] );
		} else {
			$classes[] = 'col-mf-5';
		}
		$classes[] = 'un-' . $woocommerce_loop['columns'] . '-cols';

		return $classes;
	}


	/**
	 * Wrap product content
	 * Open a div
	 *
	 * @since 1.0
	 */
	function open_product_inner() {
		echo '<div class="product-inner  clearfix">';
	}

	/**
	 * Wrap product content
	 * Close a div
	 *
	 * @since 1.0
	 */
	function close_product_inner() {
		echo '</div>';
	}


	/**
	 * HTML for rating
	 *
	 * @since 1.0
	 */
	function product_get_rating_html( $html ) {
		if ( empty( $html ) ) {
			return $html;
		}
		global $product;
		if ( empty( $product ) ) {
			return $html;
		}
		$count = $product->get_rating_count();
		if ( $count < 10 ) {
			$count = '0' . $count;
		}
		$rating = '<div class="mf-rating">';
		$rating .= $html;
		$rating .= '<span class="count">' . $count . '</span>';
		$rating .= '</div>';

		return $rating;

	}

	/**
	 * Getting parts of a price, in html, used by get_price_html.
	 *
	 * @since  1.0.0
	 * @return string
	 */
	function format_sale_price( $price, $regular_price, $sale_price ) {

		$price = '<ins>' . ( is_numeric( $sale_price ) ? wc_price( $sale_price ) : $sale_price ) . '</ins><del>' . ( is_numeric( $regular_price ) ? wc_price( $regular_price ) : $regular_price ) . '</del>';

		return $price;
	}


	/**
	 * Track product views.
	 */
	function martfury_track_product_view() {
		if ( ! is_singular( 'product' ) ) {
			return;
		}

		global $post;

		if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) {
			$viewed_products = array();
		} else {
			$viewed_products = (array) explode( '|', $_COOKIE['woocommerce_recently_viewed'] );
		}

		if ( ! in_array( $post->ID, $viewed_products ) ) {
			$viewed_products[] = $post->ID;
		}

		if ( sizeof( $viewed_products ) > 15 ) {
			array_shift( $viewed_products );
		}

		// Store for session only
		wc_setcookie( 'woocommerce_recently_viewed', implode( '|', $viewed_products ), time() + 60 * 60 * 24 * 30 );
	}

	/**
	 * Add products carousel above product list
	 */
	function catalog_products_carousel() {

		if ( ! in_array( 'products_carousel', $this->catalog_elements ) ) {
			return;
		}

		$carousels = martfury_get_option( 'catalog_products_carousel_' . $this->catalog_layout );

		if ( empty( $carousels ) ) {
			return;
		}

		if ( ! is_array( $carousels ) ) {
			return;
		}

		foreach ( $carousels as $carousel ) {
			$output = array();
			$title  = sprintf( '<h2 class="title">%s</h2>', esc_html( $carousel['title'] ) );

			$order_by  = 'date ID';
			$order     = 'desc';
			$params    = '';
			$query_var = '';
			if ( $carousel['type'] == '1' ) {
				$params = 'visibility="featured"';
			} elseif ( $carousel['type'] == '2' ) {
				$params    = 'best_selling="true"';
				$query_var = '?orderby=popularity';
			} elseif ( $carousel['type'] == '3' ) {
				$params = 'on_sale="true"';
			} elseif ( $carousel['type'] == '4' ) {
				$query_var = '?orderby=date';
			} elseif ( $carousel['type'] == '5' ) {
				$params    = 'top_rated="true"';
				$query_var = '?orderby=rating';
			}

			$query_var = apply_filters( 'martfury_products_carousel_cats_link_2', $query_var, $carousel['type'] );

			if ( isset( $carousel['categories'] ) && intval( $carousel['categories'] ) ) {
				$cats_number = apply_filters( 'martfury_products_carousel_cats_number_2', 3 );
				$cats_order  = isset( $carousel['categories_orderby'] ) ? $carousel['categories_orderby'] : 'order';

				$atts = array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => 1,
					'number'     => $cats_number,
				);

				$atts['menu_order'] = false;
				if ( $cats_order == 'order' ) {
					$atts['menu_order'] = 'asc';
				} else {
					$atts['orderby'] = $cats_order;
					if ( $cats_order == 'count' ) {
						$atts['order'] = 'desc';
					}
				}

				$parent = 0;
				if ( function_exists( 'is_product_category' ) && is_product_category() ) {
					global $wp_query;
					$current_cat = $wp_query->get_queried_object();
					if ( $current_cat ) {
						$parent = $current_cat->term_id;
					}
				}

				$atts['parent'] = $parent;
				$terms          = get_terms( $atts );
				if ( $terms ) {
					$title .= '<ul class="cats-list">';
					foreach ( $terms as $term ) {
						$term_link = get_term_link( $term->term_id, 'product_cat' ) . $query_var;
						$title     .= sprintf(
							'<li>' .
							'<a href="%s" class="cat-name">%s</a>' .
							'</li>',
							esc_url( $term_link ),
							$term->name
						);
					}

					$title .= '</ul>';
				}

			}

			$title .= '<div class="slick-arrows"><span class="icon-chevron-left slick-prev-arrow"></span><span class="icon-chevron-right slick-next-arrow"></span></div>';

			$number = intval( $carousel['number'] );

			ob_start();

			$params .= ' limit="' . $number . '" order="' . $order . '" orderby ="' . $order_by . '"';

			if ( function_exists( 'is_product_category' ) && is_product_category() ) {
				global $wp_query;
				$current_cat = $wp_query->get_queried_object();
				if ( $current_cat ) {
					$params .= ' category="' . $current_cat->slug . '"';
				}
			}

			echo do_shortcode( '[products ' . $params . ']' );

			$output[] = ob_get_clean();

			$autoplay = intval( $carousel['autoplay'] );
			$cols     = '5';
			if ( isset( $carousel['columns'] ) && intval( $carousel['columns'] ) ) {
				$cols = intval( $carousel['columns'] );
			}

			if ( $output ) {
				printf(
					'<div class="mf-products-top-carousel" data-autoplay="%s" data-columns="%s">
 					<div class="carousel-header">%s</div>
 					%s
 					</div>',
					esc_attr( $autoplay ),
					esc_attr( $cols ),
					$title,
					implode( ' ', $output )
				);
			}
		}
	}

	/**
	 * Catalog main content
	 */
	function catalog_main_content() {

		if ( ! martfury_is_catalog() ) {
			return;
		}


		if ( empty( $this->catalog_elements ) ) {
			return;
		}

		$this->catalog_top_categories();
		echo '<div class="col-md-12 col-xs-12 col-sm-12">';
		$this->catalog_products_carousel();
		$this->catalog_featured_categories();
		$this->catalog_other_categories();
		echo '</div>';

	}

	/**
	 * Catalog Layout
	 */
	function catalog_layout() {
		if ( ! martfury_is_catalog() ) {
			return;
		}

		if ( empty( $this->catalog_elements ) ) {
			return;
		}

		if ( $this->catalog_layout == '1' ) {
			$cols = 'col-md-12 col-xs-12 col-sm-12';
			$this->catalog_banners( $cols );
			$this->catalog_brands();
			$this->catalog_categories();
		}

	}

	/**
	 * Catalog products header
	 */
	function catalog_products_header() {
		if ( ! martfury_is_catalog() ) {
			return;
		}


		if ( empty( $this->catalog_elements ) ) {
			return;
		}

		if ( $this->catalog_layout == '1' ) {
			$this->catalog_products_carousel();
		} elseif ( $this->catalog_layout == '3' ) {
			$this->catalog_title();
			$this->catalog_banners();
			$this->catalog_products_carousel();
		}
	}


	/**
	 * Catalog Title
	 */
	function catalog_title() {
		if ( ! in_array( 'title', $this->catalog_elements ) ) {
			return;
		}

		the_archive_title( '<h2 class="mf-catalog-title">', '</h2>' );

	}

	/**
	 * Catalog Banners
	 */
	function catalog_banners( $class_cols = '' ) {

		if ( ! in_array( 'banners', $this->catalog_elements ) ) {
			return;
		}

		$banners  = martfury_get_option( 'catalog_banners_' . $this->catalog_layout );
		$autoplay = intval( martfury_get_option( 'catalog_banners_autoplay_' . $this->catalog_layout ) );

		$output = array();

		if ( function_exists( 'is_product_category' ) && is_product_category() ) {
			$queried_object = get_queried_object();
			$term_id        = $queried_object->term_id;
			$banners_ids    = get_term_meta( $term_id, 'mf_cat_banners_id', true );
			$banners_links  = get_term_meta( $term_id, 'mf_cat_banners_link', true );

			if ( $banners_ids ) {
				$thumbnail_ids = explode( ',', $banners_ids );
				$banners_links = explode( "\n", $banners_links );
				$i             = 0;
				foreach ( $thumbnail_ids as $thumbnail_id ) {
					if ( empty( $thumbnail_id ) ) {
						continue;
					}

					$image = martfury_get_image_html( $thumbnail_id, 'full' );

					if ( empty( $image ) ) {
						continue;
					}
					if ( $image ) {
						$link = '';
						if ( $banners_links && isset( $banners_links[ $i ] ) ) {
							$link = preg_replace( '/<br \/>/iU', '', $banners_links[ $i ] );
						}

						$output[] = sprintf(
							'<li><a href="%s">%s</a></li>',
							esc_url( $link ),
							$image
						);
					}

					$i ++;


				}
			}
		}

		if ( empty( $output ) ) {
			if ( ! empty( $banners ) ) {
				foreach ( $banners as $banner ) {
					$image    = wp_get_attachment_image( $banner['image'], 'full' );
					$output[] = sprintf(
						'<li><a href="%s">%s</a></li>',
						esc_url( $banner['link_url'] ),
						$image
					);
				}
			}


		}

		if ( $output ) {
			printf(
				'<div class="mf-catalog-banners %s"><ul id="mf-catalog-banners" data-autoplay="%s">%s</ul></div>',
				esc_attr( $class_cols ),
				esc_attr( $autoplay ),
				implode( ' ', $output )
			);
		}

	}

	/**
	 * Catalog categories
	 */
	function catalog_categories() {

		if ( ! in_array( 'categories', $this->catalog_elements ) ) {
			return;
		}

		$cats_number    = martfury_get_option( 'catalog_categories_number_' . $this->catalog_layout );
		$subcats_number = martfury_get_option( 'catalog_subcategories_number_' . $this->catalog_layout );
		$cats_order     = martfury_get_option( 'catalog_categories_orderby_' . $this->catalog_layout );

		if ( intval( $cats_number ) < 1 ) {
			return;
		}

		$atts = array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => 1,
			'number'     => $cats_number,
		);

		$atts['menu_order'] = false;
		if ( $cats_order == 'order' ) {
			$atts['menu_order'] = 'asc';
		} else {
			$atts['orderby'] = $cats_order;
			if ( $cats_order == 'count' ) {
				$atts['order'] = 'desc';
			}
		}

		$parent = 0;
		if ( function_exists( 'is_product_category' ) && is_product_category() ) {
			global $wp_query;
			$current_cat = $wp_query->get_queried_object();
			if ( $current_cat ) {
				$parent = $current_cat->term_id;
			}
		}

		$atts['parent'] = $parent;

		$terms = get_terms( $atts );

		$output = array();
		foreach ( $terms as $term ) {

			$term_list = '';

			if ( $subcats_number ) {
				$atts        = array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => 1,
					'orderby'    => $cats_order,
					'number'     => $subcats_number,
					'parent'     => $term->term_id,
				);
				$child_terms = get_terms( $atts );
				if ( $child_terms ) {
					$term_list .= '<ul>';
					foreach ( $child_terms as $child ) {
						$term_list .= sprintf(
							'<li><a href="%s">%s</a></li>',
							get_term_link( $child->term_id, 'product_cat' ),
							$child->name
						);
					}

					$term_list .= '</ul>';
				}
			}


			$thumbnail_id         = absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
			$small_thumbnail_size = apply_filters( 'martfury_category_archive_thumbnail_size', 'shop_catalog' );

			$image_html = '';
			if ( $thumbnail_id ) {
				$image_html = sprintf(
					'<a class="thumbnail" href="%s">%s</a>',
					get_term_link( $term->term_id, 'product_cat' ),
					martfury_get_image_html( $thumbnail_id, $small_thumbnail_size )
				);
			}

			$term_list = sprintf(
				'<a href="%s" class="box-title">%s</a> %s',
				get_term_link( $term->term_id, 'product_cat' ),
				$term->name,
				$term_list
			);

			$column_class = '';

			if ( $this->catalog_layout == '1' && intval( martfury_get_option( 'catalog_full_width_1' ) ) ) {
				$column_class = 'col-lg-3';
			}

			$output[] = sprintf(
				'<div class="%s col-md-4 col-sm-6 col-xs-12 col-cat">' .
				'<div class="mf-image-box style-1">' .
				'%s' .
				'<div class="image-content">' .
				'%s' .
				'</div>' .
				'</div>' .
				'</div>',
				esc_attr( $column_class ),
				$image_html,
				$term_list
			);
		}

		if ( $output ) {
			printf(
				'<div class="mf-catalog-categories col-md-12 col-sm-12 col-xs-12"><div class="row">%s</div></div>',
				implode( ' ', $output )
			);
		}

	}

	/**
	 * Catalog top categories
	 */
	function catalog_top_categories() {

		if ( ! in_array( 'top_categories', $this->catalog_elements ) ) {
			return;
		}

		$output[] = $this->catalog_top_categories_list( $this->catalog_layout );
		$output[] = $this->catalog_top_categories_grid( $this->catalog_layout );

		printf(
			'<div class="mf-catalog-top-categories">' .
			'%s' .
			'</div>',
			implode( ' ', $output )
		);

	}

	/**
	 * Catalog top categories list
	 */
	function catalog_top_categories_list() {

		$cats_list_title     = martfury_get_option( 'catalog_categories_list_title_' . $this->catalog_layout );
		$cats_list_number    = intval( martfury_get_option( 'catalog_categories_list_number_' . $this->catalog_layout ) );
		$cats_order          = martfury_get_option( 'catalog_categories_list_orderby_' . $this->catalog_layout );
		$subcats_list_number = apply_filters( 'martfury_catalog_subcategories_list_number_2', $cats_list_number );

		if ( intval( $cats_list_number ) < 1 ) {
			return;
		}

		$atts = array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => 1,
			'number'     => $cats_list_number,
		);

		$atts['menu_order'] = false;
		if ( $cats_order == 'order' ) {
			$atts['menu_order'] = 'asc';
		} else {
			$atts['orderby'] = $cats_order;
			if ( $cats_order == 'count' ) {
				$atts['order'] = 'desc';
			}
		}

		$parent = 0;
		if ( function_exists( 'is_product_category' ) && is_product_category() ) {
			global $wp_query;
			$current_cat = $wp_query->get_queried_object();
			if ( $current_cat ) {
				$parent = $current_cat->term_id;
			}
		}

		$atts['parent'] = $parent;

		$terms = get_terms( $atts );

		$output = array();
		foreach ( $terms as $term ) {
			$atts        = array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => 1,
				'orderby'    => $cats_order,
				'number'     => $subcats_list_number,
				'parent'     => $term->term_id,
			);
			$child_terms = get_terms( $atts );
			$css_item    = '';
			$item_name   = '';
			$term_list   = array();
			if ( $child_terms ) {
				$css_item     = 'has-children';
				$item_name    .= '<span class="cat-menu-close"><i class="icon-chevron-down"></i> </span>';
				$term_list [] = '<ul class="sub-categories">';
				foreach ( $child_terms as $child ) {
					$term_list[] = sprintf(
						'<li><a href="%s">%s</a></li>',
						esc_url( get_term_link( $child->term_id, 'product_cat' ) ),
						$child->name
					);
				}
				$term_list [] = '</ul>';
			}

			$output[] = sprintf(
				'<li>' .
				'%s' .
				'<a href="%s" class="parent-cat %s">%s</a> %s' .
				'</li>',
				$item_name,
				get_term_link( $term->term_id, 'product_cat' ),
				esc_attr( $css_item ),
				$term->name,
				implode( ' ', $term_list )
			);
		}

		return sprintf(
			'<div class="col-md-3 col-sm-12 col-xs-12">' .
			'<div class="top-categories-list">' .
			'<h2 class="title">%s</h2>' .
			'<ul class="categories-list">' .
			'%s' .
			'</ul>' .
			'</div>' .
			'</div>',
			esc_html( $cats_list_title ),
			implode( ' ', $output )
		);

	}

	/**
	 * Catalog top categories
	 */
	function catalog_top_categories_grid() {

		$cats_number    = intval( martfury_get_option( 'catalog_categories_grid_number_' . $this->catalog_layout ) );
		$cats_order     = martfury_get_option( 'catalog_categories_grid_orderby_' . $this->catalog_layout );
		$subcats_number = apply_filters( 'martfury_catalog_subcategories_grid_number_2', 4 );

		if ( intval( $cats_number ) < 1 ) {
			return;
		}

		$atts = array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => 1,
			'number'     => $cats_number,
		);

		$atts['menu_order'] = false;
		if ( $cats_order == 'order' ) {
			$atts['menu_order'] = 'asc';
		} else {
			$atts['orderby'] = $cats_order;
			if ( $cats_order == 'count' ) {
				$atts['order'] = 'desc';
			}
		}

		$parent = 0;
		if ( function_exists( 'is_product_category' ) && is_product_category() ) {
			global $wp_query;
			$current_cat = $wp_query->get_queried_object();
			if ( $current_cat ) {
				$parent = $current_cat->term_id;
			}
		}

		$atts['parent'] = $parent;

		$terms = get_terms( $atts );

		$output = array();
		foreach ( $terms as $term ) {
			$atts        = array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => 1,
				'orderby'    => $cats_order,
				'number'     => $subcats_number,
				'parent'     => $term->term_id,
			);
			$child_terms = get_terms( $atts );
			$term_list   = array();
			if ( $child_terms ) {
				$term_list [] = '<ul class="sub-categories">';
				foreach ( $child_terms as $child ) {
					$term_list[] = sprintf(
						'<li><a href="%s">%s</a></li>',
						get_term_link( $child->term_id, 'product_cat' ),
						$child->name
					);
				}

				$term_list[] = sprintf(
					'<li class="view-more"><a href="%s">%s</a></li>',
					get_term_link( $term->term_id, 'product_cat' ),
					apply_filters( 'martfury_top_categories_shop_all_text_2', esc_html__( 'Shop All', 'martfury' ) )
				);

				$term_list [] = '</ul>';
			}

			$thumbnail_id         = absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
			$small_thumbnail_size = apply_filters( 'martfury_category_archive_thumbnail_size', 'shop_catalog' );

			$image_html = '';
			if ( $thumbnail_id ) {
				$image_html = sprintf(
					'<div class="cats-image"><a href="%s">%s</a></div>',
					get_term_link( $term->term_id, 'product_cat' ),
					martfury_get_image_html( $thumbnail_id, $small_thumbnail_size )
				);
			}

			$output[] = sprintf(
				'<div class="col-md-4 col-sm-6 col-xs-12 col-cat">' .
				'<div class="cats-box">' .
				'%s' .
				'<div class="cats-list">' .
				'<a href="%s" class="parent-cat">%s</a> %s' .
				'</div>' .
				'</div>' .
				'</div>',
				$image_html,
				get_term_link( $term->term_id, 'product_cat' ),
				$term->name,
				implode( ' ', $term_list )
			);
		}

		return sprintf(
			'<div class="col-md-9 col-sm-12 col-xs-12 col-top-categories">' .
			'<div class="top-categories-grid">' .
			'%s' .
			'</div>' .
			'</div>',
			implode( ' ', $output )
		);

	}

	/**
	 * Catalog top categories
	 */
	function catalog_featured_categories() {

		if ( ! in_array( 'featured_categories', $this->catalog_elements ) ) {
			return;
		}

		$cats_number    = intval( martfury_get_option( 'catalog_featured_categories_number_' . $this->catalog_layout ) );
		$cats_order     = martfury_get_option( 'catalog_featured_categories_orderby_' . $this->catalog_layout );
		$subcats_number = intval( martfury_get_option( 'catalog_featured_subcategories_number_' . $this->catalog_layout ) );
		$columns        = apply_filters( 'martfury_catalog_featured_categories_columns_2', 5 );
		$new_text       = martfury_get_option( 'catalog_featured_new_text_' . $this->catalog_layout );
		$best_text      = martfury_get_option( 'catalog_featured_best_seller_text_' . $this->catalog_layout );

		$show_banner = intval( martfury_get_option( 'catalog_featured_banner_' . $this->catalog_layout ) );

		if ( intval( $cats_number ) < 1 ) {
			return;
		}

		$classes = 'col-mf-5';
		if ( $columns != 5 ) {
			$classes = 'col-md-' . ( 12 / $columns );
		}

		$classes .= ' col-sm-4 col-xs-6';

		$atts = array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => 1,
			'number'     => $cats_number,
		);

		$atts['menu_order'] = false;
		if ( $cats_order == 'order' ) {
			$atts['menu_order'] = 'asc';
		} else {
			$atts['orderby'] = $cats_order;
			if ( $cats_order == 'count' ) {
				$atts['order'] = 'desc';
			}
		}

		$parent = 0;
		if ( function_exists( 'is_product_category' ) && is_product_category() ) {
			global $wp_query;
			$current_cat = $wp_query->get_queried_object();
			if ( $current_cat ) {
				$parent = $current_cat->term_id;
			}
		}

		$atts['parent'] = $parent;

		$terms = get_terms( $atts );

		$output = array();
		foreach ( $terms as $term ) {

			$this->featured_categories[] = $term->term_id;

			$atts        = array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => 1,
				'orderby'    => $cats_order,
				'number'     => $subcats_number,
				'parent'     => $term->term_id,
			);
			$child_terms = get_terms( $atts );
			$term_list   = array();

			$banner_html = '';
			if ( $show_banner ) {
				$banner_id    = absint( get_term_meta( $term->term_id, 'mf_cat_banners_2_id', true ) );
				$banners_link = get_term_meta( $term->term_id, 'mf_cat_banners_2_link', true );
				if ( $banner_id ) {
					$banner_html = sprintf(
						'<a class="cat-banner" href="%s">%s</a>',
						esc_url( $banners_link ),
						martfury_get_image_html( $banner_id, 'full' )
					);
				}
			}
			if ( $child_terms ) {
				$term_list [] = '<div class="sub-categories">';
				if ( $show_banner ) {
					$term_list[] = sprintf(
						'<div class="col-md-7 col-sm-3 col-xs-12 col-banner">%s</div>',
						$banner_html
					);
				}

				foreach ( $child_terms as $child ) {
					$thumbnail_id         = absint( get_term_meta( $child->term_id, 'thumbnail_id', true ) );
					$small_thumbnail_size = apply_filters( 'martfury_category_box_thumbnail_size', 'shop_catalog' );

					$image_html = '';
					if ( $thumbnail_id ) {
						$image_html = martfury_get_image_html( $thumbnail_id, $small_thumbnail_size );
					}

					$count = $child->count;

					$item_text = esc_html__( 'Items', 'martfury' );
					if ( $count <= 1 ) {
						$item_text = esc_html__( 'Item', 'martfury' );
					}

					$count .= ' ' . apply_filters( 'martfury_category_box_items_text', $item_text, $count );

					$term_list[] = sprintf(
						'<div class="%s col-cat"><a class="term-item" href="%s">%s <h3 class="term-name">%s <span class="count">%s</span></h3></a></div>',
						esc_attr( $classes ),
						esc_url( get_term_link( $child->term_id, 'product_cat' ) ),
						$image_html,
						$child->name,
						$count
					);
				}
				$term_list [] = '</div>';
			}

			$extra_links = '';
			if ( $new_text ) {
				$query_var   = apply_filters( 'martfury_featured_cats_new_link_2', '?orderby=date' );
				$extra_link  = get_term_link( $term->term_id, 'product_cat' ) . $query_var;
				$extra_links .= sprintf(
					'<li>' .
					'<a href="%s" class="extra-link">%s</a>' .
					'</li>',
					esc_url( $extra_link ),
					esc_html( $new_text )
				);
			}

			if ( $best_text ) {
				$query_var   = apply_filters( 'martfury_featured_cats_new_link_2', '?orderby=popularity' );
				$extra_link  = get_term_link( $term->term_id, 'product_cat' ) . $query_var;
				$extra_links .= sprintf(
					'<li>' .
					'<a href="%s" class="extra-link">%s</a>' .
					'</li>',
					esc_url( $extra_link ),
					esc_html( $best_text )
				);
			}

			if ( $extra_links ) {
				$extra_links = sprintf( '<ul class="extra-links">%s</ul>', $extra_links );
			}

			$output[] = sprintf(
				'<div class="mf-category-box mf-featured-categories">' .
				'<div class="cat-header">' .
				'<h2 class="cat-name"><a href="%s">' .
				'%s' .
				'</a></h2>' .
				'%s' .
				'</div>' .
				'%s' .
				'</div>',
				esc_url( get_term_link( $term->term_id, 'product_cat' ) ),
				esc_html( $term->name ),
				$extra_links,
				implode( ' ', $term_list )
			);
		}

		if ( $output ) {
			echo implode( ' ', $output );
		}

	}

	/**
	 * Catalog top categories
	 */
	function catalog_other_categories() {

		if ( ! in_array( 'other_categories', $this->catalog_elements ) ) {
			return;
		}

		$cats_number    = intval( martfury_get_option( 'catalog_other_categories_number_' . $this->catalog_layout ) );
		$cats_order     = martfury_get_option( 'catalog_other_categories_orderby_' . $this->catalog_layout );
		$cats_title     = martfury_get_option( 'catalog_other_categories_title_' . $this->catalog_layout );
		$subcats_number = apply_filters( 'martfury_catalog_other_subcategories_number_2', 8 );

		if ( intval( $cats_number ) < 1 ) {
			return;
		}

		$atts = array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => 1,
			'number'     => $cats_number,
			'exclude'    => $this->featured_categories,
		);

		$atts['menu_order'] = false;
		if ( $cats_order == 'order' ) {
			$atts['menu_order'] = 'asc';
		} else {
			$atts['orderby'] = $cats_order;
			if ( $cats_order == 'count' ) {
				$atts['order'] = 'desc';
			}
		}

		$parent = 0;
		if ( function_exists( 'is_product_category' ) && is_product_category() ) {
			global $wp_query;
			$current_cat = $wp_query->get_queried_object();
			if ( $current_cat ) {
				$parent = $current_cat->term_id;
			}
		}

		$atts['parent'] = $parent;

		$terms = get_terms( $atts );

		$output = array();
		foreach ( $terms as $term ) {
			$term_list = array();
			if ( $subcats_number ) {
				$atts        = array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => 1,
					'orderby'    => $cats_order,
					'number'     => $subcats_number,
					'parent'     => $term->term_id,
				);
				$child_terms = get_terms( $atts );
				if ( $child_terms ) {
					$term_list [] = '<ul class="sub-categories">';
					foreach ( $child_terms as $child ) {
						$term_list[] = sprintf(
							'<li><a href="%s">%s</a></li>',
							get_term_link( $child->term_id, 'product_cat' ),
							$child->name
						);
					}

					$term_list [] = '</ul>';
				}
			}


			$thumbnail_id         = absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
			$small_thumbnail_size = apply_filters( 'martfury_category_archive_thumbnail_size', 'shop_catalog' );

			$image_html = '';
			if ( $thumbnail_id ) {
				$image_html = sprintf(
					'<div class="cats-image"><a href="%s">%s</a></div>',
					get_term_link( $term->term_id, 'product_cat' ),
					martfury_get_image_html( $thumbnail_id, $small_thumbnail_size )
				);
			}

			$output[] = sprintf(
				'<div class="col-mf-5 col-sm-4 col-xs-12 col-cat">' .
				'<div class="cats-box">' .
				'%s' .
				'<div class="cats-list">' .
				'<a href="%s" class="parent-cat">%s</a> %s' .
				'</div>' .
				'</div>' .
				'</div>',
				$image_html,
				get_term_link( $term->term_id, 'product_cat' ),
				$term->name,
				implode( ' ', $term_list )
			);
		}

		printf(
			'<div class="mf-other-categories">' .
			'<div class="cat-header">' .
			'<h2 class="cat-name">' .
			'%s' .
			'</h2>' .
			'</div>' .
			'<div class="categories-list">' .
			'%s' .
			'</div>' .
			'</div>',
			esc_html( $cats_title ),
			implode( ' ', $output )
		);

	}

	/**
	 * Catalog categories
	 */
	function catalog_brands() {

		if ( ! in_array( 'brands', $this->catalog_elements ) ) {
			return;
		}

		$brands_number = martfury_get_option( 'catalog_brands_number_' . $this->catalog_layout );
		$brands_order  = martfury_get_option( 'catalog_brands_orderby_' . $this->catalog_layout );

		$number = $brands_number;
		if ( function_exists( 'is_product_category' ) && is_product_category() ) {
			$number = false;
		}

		$atts = array(
			'taxonomy'   => 'product_brand',
			'hide_empty' => 1,
			'number'     => $number,
		);

		$atts['menu_order'] = false;
		if ( $brands_order == 'order' ) {
			$atts['menu_order'] = 'asc';
		} else {
			$atts['orderby'] = $brands_order;
			if ( $brands_order == 'count' ) {
				$atts['order'] = 'desc';
			}
		}


		$terms                = get_terms( $atts );
		$small_thumbnail_size = apply_filters( 'martfury_brand_archive_thumbnail_size', 'shop_catalog' );
		$output               = array();

		if ( is_wp_error( $terms ) ) {
			return;
		}

		if ( empty( $terms ) || ! is_array( $terms ) ) {
			return;
		}

		$term_counts = array();

		if ( function_exists( 'is_product_category' ) && is_product_category() ) {
			$term_counts = martfury_get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), 'product_brand', 'pa_' );
		}

		$found = 0;
		foreach ( $terms as $term ) {
			if ( function_exists( 'is_product_category' ) && is_product_category() ) {
				$count = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

				if ( $found >= $brands_number ) {
					break;
				}

				if ( $count === 0 ) {
					continue;
				}

				$found ++;
			}
			$thumbnail_id = absint( get_term_meta( $term->term_id, 'brand_thumbnail_id', true ) );
			if ( $thumbnail_id ) {
				$output[] = sprintf(
					'<div class="brand-item">' .
					'<a href="%s">%s</a>' .
					'</div>',
					get_term_link( $term->term_id, 'product_brand' ),
					martfury_get_image_html( $thumbnail_id, $small_thumbnail_size )
				);
			}

		}

		if ( $output ) {
			printf(
				'<div class="mf-catalog-brands col-md-12 col-sm-12 col-xs-12">%s</div>',
				implode( ' ', $output )
			);
		}

	}

	/**
	 * Get catalog elements
	 *
	 * @since 1.0
	 */
	public function get_catalog_elements() {
		$elements = array();

		if ( function_exists( 'is_shop' ) && is_shop() ) {
			$elements = martfury_get_option( 'shop_els_' . $this->catalog_layout );
		} elseif ( function_exists( 'is_product_category' ) && is_product_category() && martfury_get_product_category_level() == 0 ) {
			$elements = martfury_get_option( 'products_cat_level_1_els_' . $this->catalog_layout );

			if ( function_exists( 'get_term_meta' ) ) {
				$queried_object = get_queried_object();
				$term_id        = $queried_object->term_id;
				$cat_layout     = get_term_meta( $queried_object->term_id, 'mf_cat_layout', true );
				if ( $cat_layout ) {
					$elements = get_term_meta( $term_id, 'product_cat_' . $cat_layout . '_els', true );
				}
			}

		}

		if ( $this->catalog_layout == 10 ) {
			$elements = martfury_get_option( 'shop_els_10' );
		}

		return $elements;
	}

	/**
	 * Display a tool bar on top of product archive
	 *
	 * @since 1.0
	 */
	function shop_toolbar() {
		if ( ! martfury_is_catalog() && ! martfury_is_vendor_page() ) {
			return;
		}

		$elements = $this->get_toolbar_elements();
		if ( ! $elements ) {
			return;
		}

		$css_class = '';

		if ( count( $elements ) > 2 ) {
			$css_class = 'multiple';
		}

		$output = array();


		if ( in_array( 'found', $elements ) ) {
			global $wp_query;
			$total    = $wp_query->found_posts;
			$output[] = '<div class="products-found"><strong>' . $total . '</strong>' . esc_html__( 'Products found', 'martfury' ) . '</div>';

		}

		if ( in_array( 'view', $elements ) ) {
			$list_current = $this->shop_view == 'list' ? 'current' : '';
			$grid_current = $this->shop_view == 'grid' ? 'current' : '';
			$output[]     = sprintf(
				'<div class="shop-view">' .
				'<span>%s</span>' .
				'<a href="#" class="grid-view mf-shop-view %s" data-view="grid"><i class="icon-grid"></i></a>' .
				'<a href="#" class="list-view mf-shop-view %s" data-view="list"><i class="icon-list4"></i></a>' .
				'</div>',
				esc_html__( 'View', 'martfury' ),
				$grid_current,
				$list_current
			);
		}

		if ( in_array( 'filter', $elements ) ) {
			$output[] = sprintf( '<a href="#" class="mf-filter-mobile" id="mf-filter-mobile"><i class="icon-equalizer"></i><span>%s</span></a>', esc_html__( 'Filter', 'martfury' ) );
		}

		if ( in_array( 'sortby', $elements ) ) {
			ob_start();
			woocommerce_catalog_ordering();
			$output[] = ob_get_clean();

		}

		if ( $output ) {
			?>
            <div id="mf-catalog-toolbar" class="shop-toolbar <?php echo esc_attr( $css_class ); ?>">
				<?php echo implode( ' ', $output ); ?>
            </div>
			<?php
		}
	}

	/**
	 * Display a top bar on top of product archive
	 *
	 * @since 1.0
	 */
	function catalog_toolbar_space() {
		if ( ! martfury_is_catalog() && ! martfury_is_dc_vendor_store() ) {
			return;
		}
		$elements = $this->get_toolbar_elements();
		if ( ! $elements ) {
			return;
		}

		?>
        <div class="mf-toolbar-empty-space"></div>
		<?php
	}

	/**
	 * Get toolbar elements
	 *
	 * @since 1.0
	 */
	function get_toolbar_elements() {

		$els = '';


		if ( empty( $this->catalog_layout ) ) {
			return $els;
		}

		$els = martfury_get_option( 'catalog_toolbar_els_' . $this->catalog_layout );
		if ( intval( martfury_get_option( 'catalog_filter_mobile_10' ) ) ) {
			$els[] = 'filter';
		}

		return apply_filters( 'martfury_catalog_toolbar_elements', $els );

	}

	/**
	 * Get variations
	 *
	 * @since  1.0.0
	 * @return string
	 */
	function product_variations_loop() {
		global $product;

		$variation_images = apply_filters( 'martfury_catalog_variation_images', martfury_get_option( 'catalog_variation_images' ) );

		if ( ! intval( $variation_images ) ) {
			return;
		}

		if ( ! martfury_is_catalog() && ! martfury_is_vendor_page() ) {
			return;
		}

		$variations = array();
		$item_class = '';
		if ( $product->get_type() == 'variable' ) {
			$args = array(
				'post_parent' => $product->get_id(),
				'post_type'   => 'product_variation',
				'orderby'     => 'menu_order',
				'order'       => 'ASC',
				'fields'      => 'ids',
				'post_status' => 'publish',
				'numberposts' => - 1,
			);

			if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
				$args['meta_query'][] = array(
					'key'     => '_stock_status',
					'value'   => 'instock',
					'compare' => '=',
				);
			}

			$posts          = get_posts( $args );
			$attachment_ids = array();
			foreach ( $posts as $post_id ) {
				$attachment_id = get_post_thumbnail_id( $post_id );
				if ( empty( $attachment_id ) ) {
					continue;
				}

				if ( ! in_array( $attachment_id, $attachment_ids ) ) {
					$attachment_ids[] = $attachment_id;
				} else {
					continue;
				}

				$image = martfury_get_image_html( $attachment_id, 'shop_catalog' );

				$variations[] = sprintf(
					'<span class="mf-swatch-image  swatch-image" >%s</span>',
					$image
				);

			}

			if ( count( $attachment_ids ) > 3 ) {
				$item_class = 'mf-attr-swatches-slick';
			}

		}

		if ( $variations ) {
			printf( '<div class="mf-attr-swatches %s">%s</div>', esc_attr( $item_class ), implode( ' ', $variations ) );
		}
	}


	/**
	 * Add single product header
	 */
	function single_product_entry_header() {
		$layout = martfury_get_product_layout();
		if ( ! in_array( $layout, array( '2', '3', '4', '6' ) ) ) {
			return;
		}
		$this->get_single_product_header( $layout );
	}

	/**
	 * Add single product header
	 */
	function single_product_header() {
		$layout = martfury_get_product_layout();
		if ( ! in_array( $layout, array( '1', '5' ) ) ) {
			return;
		}
		$this->get_single_product_header( $layout );
	}

	/**
	 * Add single product header
	 */
	function get_single_product_header( $layout ) {
		?>

        <div class="mf-entry-product-header">
            <div class="entry-left">
				<?php
				if ( function_exists( 'woocommerce_template_single_title' ) ) {
					woocommerce_template_single_title();
				}
				?>

                <ul class="entry-meta">
					<?php

					$this->single_product_brand();
					global $product;
					if ( function_exists( 'woocommerce_template_single_rating' ) && $product->get_rating_count() ) {
						echo '<li>';
						woocommerce_template_single_rating();
						echo '</li>';
					}
					if ( in_array( $layout, array( '1', '5' ) ) ) {
						$this->single_product_sku();
					}
					?>

                </ul>
            </div>
			<?php
			if ( in_array( $layout, array( '1', '5' ) ) ) {
				$this->single_product_socials();
			}
			?>
        </div>
		<?php
	}

	/**
	 * Add single product header
	 */
	function get_product_quick_view_header() {
		global $product;

		?>

        <div class="mf-entry-product-header">
            <div class="entry-left">
				<?php
				echo sprintf( '<h2 class="product_title"><a href="%s">%s</a></h2>', esc_url( $product->get_permalink() ), $product->get_title() );
				?>

                <ul class="entry-meta">
					<?php

					$this->single_product_brand();

					if ( function_exists( 'woocommerce_template_single_rating' ) && $product->get_rating_count() ) {
						echo '<li>';
						woocommerce_template_single_rating();
						echo '</li>';
					}
					?>

                </ul>
            </div>
        </div>
		<?php
	}

	/**
	 * Get product metas
	 */
	function single_product_metas() {
		$layout = martfury_get_product_layout();
		if ( in_array( $layout, array( '1', '5' ) ) ) {
			return;
		}
		$this->single_product_socials();
	}


	/**
	 * Get product SKU
	 */
	function single_product_socials() {

		if ( ! function_exists( 'martfury_addons_share_link_socials' ) ) {
			return;
		}

		$image = get_the_post_thumbnail_url( get_the_ID(), 'full' );
		martfury_addons_share_link_socials( get_the_title(), get_the_permalink(), $image );
	}

	/**
	 * Get product SKU
	 */
	function single_product_sku() {
		global $product;
		if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
            <li class="meta-sku">
				<?php esc_html_e( 'SKU:', 'martfury' ); ?>
                <span class="meta-value">
                    <?php
                    if ( $sku = $product->get_sku() ) {
	                    echo wp_kses_post( $sku );
                    } else {
	                    esc_html_e( 'N/A', 'martfury' );
                    }
                    ?>
                </span>
            </li>
		<?php endif;
	}

	/**
	 * Get sinlge product brand
	 */
	function single_product_brand() {
		global $product;
		$terms = get_the_terms( $product->get_id(), 'product_brand' );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ): ?>
            <li class="meta-brand">
				<?php echo apply_filters( 'martfury_product_brand_text', esc_html__( 'Brand:', 'martfury' ) ); ?>
                <a href="<?php echo esc_url( get_term_link( $terms[0] ), 'product_brand' ); ?>"
                   class="meta-value"><?php echo esc_html( $terms[0]->name ); ?></a>
            </li>
		<?php endif;
	}

	/**
	 * Get product price HTML
	 *
	 * @param $price
	 * @param $product
	 */
	function get_product_price_html( $price, $product ) {

		if ( is_admin() ) {
			return $price;
		}

		if ( ! function_exists( 'tawc_is_deal_product' ) ) {
			return $price;
		}

		if ( ! tawc_is_deal_product( $product ) ) {
			return $price;
		}


		if ( $product->get_type() == 'variable' ) {
			return $price;
		}

		if ( $product->is_on_sale() ) {
			$price      = wc_format_sale_price( wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) ), wc_get_price_to_display( $product ) ) . $product->get_price_suffix();
			$percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 ) . '%';
			if ( is_single( $product->get_id() ) ) {
				$price = sprintf( '%s <span class="sale">(-%s)</span>', $price, $percentage );
			} else {
				$price = sprintf( '%s <span class="sale">%s %s</span>', $price, $percentage, esc_html__( 'off', 'martfury' ) );
			}
		}

		return $price;
	}

	/**
	 * Get availability text
	 *
	 * @param $availability
	 * @param $product
	 */
	function get_product_availability_text( $availability, $product ) {

		if ( ! $product->get_type() == 'simple' ) {
			return $availability;
		}

		if ( ! $product->managing_stock() && $product->get_stock_status() == 'instock' ) {
			$availability = esc_html__( 'In stock', 'martfury' );
		}

		return $availability;
	}

	/**
	 * Simple stock
	 */
	function template_single_summary_header() {
		global $product;
		$output = array();
		if ( function_exists( 'dokan_get_store_url' ) ) {
			$author_id = get_post_field( 'post_author', $product->get_id() );
			$author    = get_user_by( 'id', $author_id );
			$shop_info = get_user_meta( $author_id, 'dokan_profile_settings', true );
			$shop_name = $author->display_name;
			if ( $shop_info && isset( $shop_info['store_name'] ) && $shop_info['store_name'] ) {
				$shop_name = $shop_info['store_name'];
			}
			if ( $author ) {
				$output[] = sprintf(
					'<div class="mf-summary-meta">' .
					'<div class="sold-by-meta">' .
					'<span class="sold-by-label">%s </span>' .
					'<a href="%s">%s</a>' .
					'</div>' .
					'</div > ',
					esc_html__( 'Sold By:', 'martfury' ),
					esc_url( dokan_get_store_url( $author_id ) ),
					$shop_name
				);
			}
		}
		if ( class_exists( 'WCV_Vendor_Shop' ) && method_exists( 'WCV_Vendor_Shop', 'template_loop_sold_by' ) ) {
			ob_start();
			echo '<div class="mf-summary-meta">';
			WCV_Vendor_Shop::template_loop_sold_by( $product->get_id() );
			echo '</div>';
			$output[] = ob_get_clean();
		}
		ob_start();
		do_action( 'martfury_single_product_header' );
		$output[] = ob_get_clean();

		if ( in_array( $product->get_type(), array( 'simple', 'variable' ) ) ) {
			$output[] = sprintf( '<div class="mf-summary-meta">%s</div>', wc_get_stock_html( $product ) );
		}

		echo sprintf( '<div class="mf-summary-header">%s</div>', implode( ' ', $output ) );
	}

	/**
	 * Display wishlist_button
	 *
	 * @since 1.0
	 */
	function yith_button() {

		echo '<div class="actions-button">';
		if ( in_array( $this->product_layout, array( '1', '3', '4', '6' ) ) ) {
			$this->product_buy_now_button();
		}

		if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
			echo '<div class="mf-wishlist-button">';
			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
			echo '</div>';
		}
		if ( get_option( 'yith_woocompare_compare_button_in_product_page' ) == 'yes' ) {
			$this->product_compare();
		}

		if ( in_array( $this->product_layout, array( '2', '5' ) ) ) {
			$this->product_buy_now_button();
		}

		echo '</div>';
	}

	/**
	 * Display wishlist_button
	 *
	 * @since 1.0
	 */
	function product_buy_now_button() {
		global $product;
		if ( ! intval( martfury_get_option( 'product_buy_now' ) ) ) {
			return;
		}

		if ( $product->get_type() == 'external' ) {
			return;
		}

		echo sprintf( '<button class="buy_now_button button">%s</button>', wp_kses_post( martfury_get_option( 'product_buy_now_text' ) ) );

	}

	/**
	 * Frequently Bought Together
	 */
	function fbt_product() {
		global $product;
		if ( ! intval( martfury_get_option( 'product_fbt' ) ) ) {
			return;
		}

		$product_ids = maybe_unserialize( get_post_meta( $product->get_id(), 'mf_pbt_product_ids', true ) );
		$product_ids = apply_filters( 'martfury_pbt_product_ids', $product_ids, $product );
		if ( empty( $product_ids ) || ! is_array( $product_ids ) ) {
			return;
		}


		$current_product = array( $product->get_id() );
		$product_ids     = array_merge( $current_product, $product_ids );
		$title           = martfury_get_option( 'product_fbt_title' );

		$total_price  = 0;
		$product_list = array();

		?>
        <div class="mf-product-fbt" id="mf-product-fbt">
            <h3 class="fbt-title"><?php echo esc_html( $title ); ?></h3>
            <ul class="products">
				<?php
				$dupplicate_id = 0;
				foreach ( $product_ids as $product_id ) {
					$product_id = apply_filters( 'wpml_object_id', $product_id, 'product' );
					$item       = wc_get_product( $product_id );

					if ( empty( $item ) ) {
						continue;
					}

					if ( $item->get_stock_status() == 'outofstock' ) {
						continue;
					}

					$data_id = $item->get_id();
					if ( $item->get_parent_id() > 0 ) {
						$data_id = $item->get_parent_id();
					}
					$total_price  += $item->get_price();
					$current_item = '';
					if ( $item->get_id() == $product->get_id() ) {
						$current_item = sprintf( '<strong>%s</strong>', esc_html__( 'This item:', 'martfury' ) );
					}
					$product_list[] = sprintf(
						'<li><a href="%s" data-id="%s" data-title="%s"><span class="p-title">%s %s</span></a><span class="s-price" data-price="%s">(%s)</span></li>',
						esc_url( $item->get_permalink() ),
						esc_attr( $item->get_id() ),
						esc_attr( $item->get_title() ),
						$current_item,
						$item->get_name(),
						esc_attr( $item->get_price() ),
						$item->get_price_html()
					);
					?>
                    <li class="product" data-id="<?php echo esc_attr( $data_id ); ?>"
                        id="fbt-product-<?php echo esc_attr( $item->get_id() ); ?>">
                        <div class="product-content">
                            <a class="thumbnail" href="<?php echo esc_url( $item->get_permalink() ) ?>">
								<?php echo martfury_get_image_html( $item->get_image_id(), 'shop_catalog' ); ?>
                            </a>

                            <h2>
                                <a href="<?php echo esc_url( $item->get_permalink() ) ?>">
									<?php echo esc_html( $item->get_name() ); ?>
                                </a>
                            </h2>

                            <div class="price">
								<?php echo wp_kses_post( $item->get_price_html() ); ?>
                            </div>
							<?php
							if ( $dupplicate_id != $data_id ) {
								if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
									echo do_shortcode( '[yith_wcwl_add_to_wishlist product_id ="' . $data_id . '"]' );
								}
								$dupplicate_id = $data_id;
							}
							?>
                        </div>
						<?php ?>
                    </li>
					<?php
				}
				?>
                <li class="product product-buttons">
                    <div class="price-box">
                        <span class="label"><?php esc_html_e( 'Total Price: ', 'martfury' ); ?></span>
                        <span class="s-price mf-total-price"><?php echo wc_price( $total_price ); ?></span>
                        <input type="hidden" data-price="<?php echo esc_attr( $total_price ); ?>" id="mf-data_price">
                    </div>
					<?php
					$product_ids = implode( ',', $product_ids );
					?>
                    <form class="fbt-cart" action="<?php echo esc_url( $product->get_permalink() ); ?>" method="post"
                          enctype="multipart/form-data">
                        <button type="submit" name="mf-add-to-cart" value="<?php echo esc_attr( $product_ids ); ?>"
                                class="mf_add_to_cart_button ajax_add_to_cart"><?php esc_html_e( 'Add All To Cart', 'martfury' ); ?></button>
                    </form>
					<?php if ( function_exists( 'YITH_WCWL' ) ) : ?>
                        <a href="<?php echo esc_url( $product->get_permalink() ); ?>"
                           class="btn-add-to-wishlist mf-wishlist-button"><span> <?php esc_html_e( 'Add All To Wishlist', 'martfury' ); ?></span></a>
                        <a href="<?php echo esc_url( $this->get_wishlist_url() ); ?>"
                           class="btn-view-to-wishlist mf-wishlist-button"><span><?php esc_html_e( 'Browse Wishlist', 'martfury' ); ?></span></a>
					<?php endif; ?>
                </li>
            </ul>
            <div class="clear"></div>
            <ul class="products-list">
				<?php echo implode( '', $product_list ); ?>
            </ul>
        </div>
		<?php
	}

	/**
	 * Open Product Summary
	 */
	function single_product_summary_open() {
		$layout = martfury_get_product_layout();
		if ( $layout != '4' ) {
			return;
		}

		echo '<div class="product-summary-content col-md-9 col-sm-12 col-xs-12">';
	}

	/**
	 * Open Product Summary
	 */
	function single_product_summary_close() {
		$layout = martfury_get_product_layout();
		if ( $layout != '4' ) {
			return;
		}

		$sidebar = 'product-sidebar';

		?>
        </div>
        <aside id="primary-sidebar"
               class="widgets-area primary-sidebar col-md-3 col-sm-12 col-xs-12 <?php echo esc_attr( $sidebar ) ?>">
			<?php
			if ( is_active_sidebar( $sidebar ) ) {
				dynamic_sidebar( $sidebar );
			}
			?>
        </aside><!-- #secondary -->
		<?php
	}


	/**
	 * Get current page URL for layered nav items.
	 * @return string
	 */
	function get_page_base_url() {
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

	function get_wishlist_url() {
		if ( function_exists( 'YITH_WCWL' ) ) {
			return YITH_WCWL()->get_wishlist_url();
		} else {
			$wishlist_page_id = get_option( 'yith_wcwl_wishlist_page_id' );

			return get_the_permalink( $wishlist_page_id );
		}
	}

	/**
	 * Add to cart action.
	 *
	 * Checks for a valid request, does validation (via hooks) and then redirects if valid.
	 *
	 * @param bool $url (default: false)
	 */
	function add_to_cart_action() {
		if ( empty( $_REQUEST['mf-add-to-cart'] ) ) {
			return;
		}

		wc_nocache_headers();

		$product_ids = $_REQUEST['mf-add-to-cart'];
		$product_ids = explode( ',', $product_ids );
		if ( ! is_array( $product_ids ) ) {
			return;
		}

		foreach ( $product_ids as $product_id ) {
			if ( $product_id == 0 ) {
				continue;
			}
			$was_added_to_cart = false;
			$adding_to_cart    = wc_get_product( $product_id );

			if ( ! $adding_to_cart ) {
				return;
			}

			$quantity          = 1;
			$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

			if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity ) ) {
				wc_add_to_cart_message( array( $product_id => $quantity ), true );
				$was_added_to_cart = true;
			}

			// If we added the product to the cart we can now optionally do a redirect.
			if ( $was_added_to_cart && 0 === wc_notice_count( 'error' ) ) {
				if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
					wp_safe_redirect( wc_get_cart_url() );
					exit;
				}
			}
		}

	}

	/**
	 * AJAX add to cart.
	 */
	function fbt_add_to_cart() {

		$product_ids = $_POST['product_ids'];
		$quantity    = 1;
		$product_ids = explode( ',', $product_ids );
		if ( is_array( $product_ids ) ) {
			foreach ( $product_ids as $product_id ) {
				if ( $product_id == 0 ) {
					continue;
				}
				$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
				$product_status    = get_post_status( $product_id );

				if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity ) && 'publish' === $product_status ) {

					do_action( 'woocommerce_ajax_added_to_cart', $product_id );

					if ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ) {
						wc_add_to_cart_message( array( $product_id => $quantity ), true );
					}


				} else {

					// If there was an error adding to the cart, redirect to the product page to show any errors
					$data = array(
						'error'       => true,
						'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $product_id ), $product_id ),
					);

					wp_send_json( $data );
				}
			}
		}

	}

	/**
	 * Add class for gallery
	 */
	function product_image_gallery_classes( $classes ) {
		global $product;
		$attachment_ids = $product->get_gallery_image_ids();
		$video_image    = get_post_meta( $product->get_id(), 'video_thumbnail', true );
		if ( count( $attachment_ids ) < 1 && empty( $video_image ) ) {
			$classes[] = 'without-thumbnails';
		}

		return $classes;
	}

	/**
	 * Display instagram photos by hashtag
	 *
	 * @return string
	 */
	function product_instagram_photos() {

		if ( ! intval( martfury_get_option( 'product_instagram' ) ) ) {
			return;
		}

		if ( ! is_singular( 'product' ) ) {
			return;
		}

		if ( get_query_var( 'edit' ) && is_singular( 'product' ) ) {
			return;
		}

		global $post;
		$default_hashtag = get_post_meta( $post->ID, 'product_instagram_hashtag', true );
		$default_hashtag = str_replace( '#', '', $default_hashtag );

		$numbers    = martfury_get_option( 'product_instagram_numbers' );
		$title      = martfury_get_option( 'product_instagram_title' );
		$columns    = martfury_get_option( 'product_instagram_columns' );
		$image_size = martfury_get_option( 'product_instagram_image_size' );

		$instagram_array = array();
		$instagram_by    = martfury_get_option( 'product_instagram_by' );
		if ( $instagram_by == 'user' ) {
			$instagram_array = $this->instagram_get_photos_by_user( $numbers, false );
		} else {
			$instagram_array = $this->instagram_get_photos_by_token( $numbers, false );
		}


		$columns         = intval( $columns );
		$container_class = martfury_get_product_layout() == '6' ? 'martfury-container' : 'container';
		echo '<div class="mf-product-instagram">';
		echo '<div class="' . $container_class . '">';
		echo '<div class="product-instagram-cont">';
		echo sprintf( '<h2>%s</h2>', wp_kses( $title, wp_kses_allowed_html( 'post' ) ) );
		echo '<ul class="products" data-columns="' . esc_attr( $columns ) . '" data-auto="0">';

		$output = array();

		if ( is_wp_error( $instagram_array ) ) {
			echo wp_kses_post( $instagram_array->get_error_message() );
		} elseif ( $instagram_array ) {
			$count = 0;
			foreach ( $instagram_array as $instagram_item ) {
				if ( ! empty( $default_hashtag ) && isset( $instagram_item['tags'] ) ) {
					if ( ! in_array( $default_hashtag, $instagram_item['tags'] ) ) {
						continue;
					}
				}

				$image_trans = get_template_directory_uri() . '/images/transparent.png';
				$image_link  = $instagram_item[ $image_size ];
				$image_url   = $instagram_item['link'];
				$image_html  = '';
				if ( intval( martfury_get_option( 'lazyload' ) ) ) {
					$image_html = sprintf( '<img src="%s" data-original="%s" alt="%s" class="lazy">', esc_url( $image_trans ), esc_url( $image_link ), esc_attr( '' ) );
				} else {
					$image_html = sprintf( '<img src="%s" alt="%s">', esc_url( $image_link ), esc_attr( '' ) );
				}

				$output[] = '<li class="product">' . '<a class="insta-item" href="' . esc_url( $image_url ) . '" target="_blank">' . $image_html . '<i class="social_instagram"></i></a>' . '</li>' . "\n";

				$count ++;
				$numbers = intval( $numbers );
				if ( $numbers > 0 ) {
					if ( $count == $numbers ) {
						break;
					}
				}
			}

			if ( ! empty( $output ) ) {
				echo implode( '', $output );
			} else {
				esc_html_e( 'Instagram did not return any images.', 'martfury' );
			}
		} else {
			esc_html_e( 'Instagram did not return any images.', 'martfury' );
		}

		echo '</ul></div></div></div>';
	}

	/**
	 * Display products upsell
	 *
	 * @return string
	 */
	function products_upsell_display() {

		if ( ! is_singular( 'product' ) ) {
			return;
		}

		if ( get_query_var( 'edit' ) && is_singular( 'product' ) ) {
			return;
		}


		if ( martfury_get_product_layout() == '1' && martfury_get_option( 'products_upsells_position' ) == '1' ) {
			return;
		}


		if ( function_exists( 'woocommerce_upsell_display' ) ) {
			woocommerce_upsell_display();
		}
	}

	/**
	 * Display products upsell
	 *
	 * @return string
	 */
	function products_full_width_upsell() {
		if ( ! is_singular( 'product' ) ) {
			return;
		}

		if ( get_query_var( 'edit' ) && is_singular( 'product' ) ) {
			return;
		}

		if ( ! intval( martfury_get_option( 'product_upsells' ) ) ) {
			return;
		}

		if ( martfury_get_product_layout() != '1' ) {
			return;
		}

		if ( martfury_get_option( 'products_upsells_position' ) == '2' ) {
			return;
		}


		if ( function_exists( 'woocommerce_upsell_display' ) ) {
			woocommerce_upsell_display();
		}
	}

	/**
	 * Display products related
	 *
	 * @return string
	 */
	function related_products_output() {

		if ( ! is_singular( 'product' ) ) {
			return;
		}

		if ( get_query_var( 'edit' ) && is_singular( 'product' ) ) {
			return;
		}

		if ( ! intval( martfury_get_option( 'product_related' ) ) ) {
			return;
		}


		if ( function_exists( 'woocommerce_related_products' ) ) {
			$args = array(
				'posts_per_page' => intval( martfury_get_option( 'related_products_numbers' ) ),
				'columns'        => intval( martfury_get_option( 'related_products_columns' ) ),
				'orderby'        => 'rand',
			);
			woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );
		}
	}


	/**
	 * Get instagram photo
	 *
	 * @param string $hashtag
	 * @param int $numbers
	 * @param string $title
	 * @param string $columns
	 */
	function instagram_get_photos_by_token( $numbers, $instagram_access_token = false ) {
		global $post;

		if ( ! $instagram_access_token ) {
			$instagram_access_token = martfury_get_option( 'instagram_token' );
		}

		if ( empty( $instagram_access_token ) ) {
			return '';
		}

		$transient_prefix = 'token' . $numbers;
		$instagram        = get_transient( 'martfury_instagram-' . $transient_prefix . '-' . sanitize_title_with_dashes( $instagram_access_token ) );
		if ( false === $instagram ) {

			$url = 'https://api.instagram.com/v1/users/self/media/recent?access_token=' . $instagram_access_token;


			$remote = wp_remote_get( $url );

			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'unable_communicate', esc_html__( 'Unable to communicate with Instagram.', 'martfury' ) );

			}

			if ( 200 != wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_200', esc_html__( 'Instagram did not return a 200.', 'martfury' ) );

			}

			$insta_array = json_decode( $remote['body'], true );

			if ( ! $insta_array ) {
				return new WP_Error( 'invalid_data', esc_html__( 'Instagram has returned invalid data.', 'martfury' ) );
			}


			if ( isset( $insta_array['data'] ) ) {
				$results = $insta_array['data'];
			} else {
				return new WP_Error( 'invalid_data', esc_html__( 'Instagram has returned invalid data.', 'martfury' ) );
			}

			if ( ! is_array( $results ) ) {
				return new WP_Error( 'invalid_data', esc_html__( 'Instagram has returned invalid data.', 'martfury' ) );
			}

			foreach ( $results as $item ) {
				$instagram[] = array(
					'tags'                => $item['tags'],
					'link'                => $item['link'],
					'thumbnail'           => $item['images']['thumbnail']['url'],
					'low_resolution'      => $item['images']['low_resolution']['url'],
					'standard_resolution' => $item['images']['standard_resolution']['url'],
				);

			}

			// do not set an empty transient - should help catch private or empty accounts.
			if ( ! empty( $instagram ) ) {
				$instagram = serialize( $instagram );
				set_transient( 'martfury_instagram-' . $transient_prefix . '-' . sanitize_title_with_dashes( $instagram_access_token ), $instagram, HOUR_IN_SECONDS * 2 );
			}
		}

		if ( ! empty( $instagram ) ) {
			return unserialize( $instagram );

		} else {

			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'martfury' ) );

		}
	}

	/**
	 * Get instagram photo
	 *
	 * @param string $hashtag
	 * @param int $numbers
	 * @param string $title
	 * @param string $columns
	 */
	function instagram_get_photos_by_user( $number, $instagram_user = false ) {
		global $post;

		if ( ! $instagram_user ) {
			$instagram_user = martfury_get_option( 'instagram_user' );
		}

		if ( empty( $instagram_user ) ) {
			return '';
		}
		$default_hashtag = get_post_meta( $post->ID, 'product_instagram_hashtag', true );
		if ( ! empty( $default_hashtag ) ) {
			$url              = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $default_hashtag );
			$transient_prefix = 'tag' . $number . $default_hashtag;
		} else {
			$url              = 'https://instagram.com/' . str_replace( '@', '', $instagram_user );
			$transient_prefix = 'user' . $number;
		}
		$instagram = get_transient( 'martfury_instagram-' . $transient_prefix . '-' . sanitize_title_with_dashes( $instagram_user ) );

		if ( false === $instagram ) {

			$remote = wp_remote_get( $url );


			if ( is_wp_error( $remote ) ) {
				return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'martfury' ) );
			}

			if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
				return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'martfury' ) );
			}

			$shards      = explode( 'window._sharedData = ', $remote['body'] );
			$insta_json  = explode( ';</script>', $shards[1] );
			$insta_array = json_decode( $insta_json[0], true );

			if ( ! $insta_array ) {
				return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'martfury' ) );
			}

			if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
			} elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
				$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
			} else {
				return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'martfury' ) );
			}

			if ( ! is_array( $images ) ) {
				return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'martfury' ) );
			}

			$instagram = array();

			foreach ( $images as $image ) {
				$instagram[] = array(
					'link'                => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
					'thumbnail'           => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
					'low_resolution'      => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
					'standard_resolution' => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
				);
			} // End foreach().

			// do not set an empty transient - should help catch private or empty accounts.
			if ( ! empty( $instagram ) ) {
				$instagram = serialize( $instagram );
				set_transient( 'martfury_instagram-' . $transient_prefix . '-' . sanitize_title_with_dashes( $instagram_user ), $instagram, HOUR_IN_SECONDS * 2 );
			}
		}

		if ( ! empty( $instagram ) ) {
			return unserialize( $instagram );

		} else {

			return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'martfury' ) );

		}
	}

	/**
	 * Get Account Info
	 */
	function account_info() {
		$user = get_user_by( 'ID', get_current_user_id() );
		if ( empty( $user ) ) {
			return;
		}

		?>
        <div class="account-info">
            <div class="account-avatar">
				<?php echo get_avatar( get_current_user_id(), 70 ); ?>
            </div>
            <div class="account-name">
                <span><?php esc_html_e( 'Hello!', 'martfury' ); ?></span>
                <h3><?php echo esc_html( $user->display_name ); ?></h3>
            </div>
        </div>
		<?php
	}

	/**
	 * Change number of columns when display cross sells products
	 *
	 * @param  int $cl
	 *
	 * @return int
	 */
	function cross_sells_columns( $cross_columns ) {
		return apply_filters( 'martfury_cross_sells_columns', 4 );
	}

	/**
	 * Change number of columns when display cross sells products
	 *
	 * @param  int $cl
	 *
	 * @return int
	 */
	function cross_sells_numbers( $cross_numbers ) {
		return apply_filters( 'martfury_cross_sells_total', 4 );
	}

	/**
	 * Ajaxify update count wishlist
	 *
	 * @since 1.0
	 *
	 * @param array $fragments
	 *
	 * @return array
	 */
	function update_wishlist_count() {
		if ( ! function_exists( 'YITH_WCWL' ) ) {
			return;
		}

		wp_send_json( YITH_WCWL()->count_products() );

	}

	/**
	 * Search products
	 *
	 * @since 1.0
	 */
	public function instance_search_result() {
		if ( apply_filters( 'martfury_check_ajax_referer', true ) ) {
			check_ajax_referer( '_martfury_nonce', 'nonce' );
		}
		$response = array();

		if ( isset( $_POST['search_type'] ) && $_POST['search_type'] == 'all' ) {
			$response = $this->instance_search_every_things_result();
		} else {
			$response = $this->instance_search_products_result();
		}

		if ( empty( $response ) ) {
			$response[] = sprintf( '<li>%s</li>', esc_html__( 'Nothing found', 'martfury' ) );
		}

		$output = sprintf( '<ul>%s</ul>', implode( ' ', $response ) );

		wp_send_json_success( $output );
		die();
	}

	function instance_search_products_result() {
		$response      = array();
		$result_number = intval( martfury_get_option( 'header_ajax_search_results_number' ) );
		$args_sku      = array(
			'post_type'        => 'product',
			'posts_per_page'   => $result_number,
			'meta_query'       => array(
				array(
					'key'     => '_sku',
					'value'   => trim( $_POST['term'] ),
					'compare' => 'like',
				),
			),
			'suppress_filters' => 0,
		);

		$args_variation_sku = array(
			'post_type'        => 'product_variation',
			'posts_per_page'   => $result_number,
			'meta_query'       => array(
				array(
					'key'     => '_sku',
					'value'   => trim( $_POST['term'] ),
					'compare' => 'like',
				),
			),
			'suppress_filters' => 0,
		);

		$args = array(
			'post_type'        => 'product',
			'posts_per_page'   => $result_number,
			's'                => trim( $_POST['term'] ),
			'suppress_filters' => 0,
		);

		if ( function_exists( 'wc_get_product_visibility_term_ids' ) ) {
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();
			$args['tax_query'][]         = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_term_ids['exclude-from-search'],
				'operator' => 'NOT IN',
			);
		}
		if ( isset( $_POST['cat'] ) && $_POST['cat'] != '0' ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $_POST['cat'],
			);

			$args_sku['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $_POST['cat'],
				),

			);
		}

		if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
			$args_variation_sku['meta_query'][] = array(
				'key'     => '_stock_status',
				'value'   => 'instock',
				'compare' => '=',
			);

			$args_sku['meta_query'][] = array(
				'key'     => '_stock_status',
				'value'   => 'instock',
				'compare' => '=',
			);

			$args['meta_query'][] = array(
				'key'     => '_stock_status',
				'value'   => 'instock',
				'compare' => '=',
			);
		}

		$products_sku           = get_posts( $args_sku );
		$products_s             = get_posts( $args );
		$products_variation_sku = get_posts( $args_variation_sku );

		$products    = array_merge( $products_sku, $products_s, $products_variation_sku );
		$product_ids = array();
		foreach ( $products as $product ) {
			$id = $product->ID;
			if ( ! in_array( $id, $product_ids ) ) {
				$product_ids[] = $id;

				$productw   = wc_get_product( $id );
				$response[] = sprintf(
					'<li>' .
					'<a class="image-item" href="%s">' .
					'%s' .
					'</a>' .
					'<div class="content-item">' .
					'<a class="title-item" href="%s">' .
					'%s' .
					'</a>' .
					'<div class="rating-item">%s</div>' .
					'<div class="price-item">%s</div>' .
					'</div>' .
					'</li>',
					esc_url( $productw->get_permalink() ),
					$productw->get_image( 'shop_thumbnail' ),
					esc_url( $productw->get_permalink() ),
					$productw->get_title(),
					wc_get_rating_html( $productw->get_average_rating() ),
					$productw->get_price_html()
				);
			}
		}

		return $response;
	}

	function instance_search_every_things_result() {
		$response      = array();
		$result_number = intval( martfury_get_option( 'header_ajax_search_results_number' ) );
		$args          = array(
			'post_type'        => 'any',
			'posts_per_page'   => $result_number,
			's'                => trim( $_POST['term'] ),
			'suppress_filters' => 0,
		);

		$posts    = get_posts( $args );
		$post_ids = array();
		foreach ( $posts as $post ) {
			$id = $post->ID;
			if ( ! in_array( $id, $post_ids ) ) {
				$post_ids[] = $id;
				$response[] = sprintf(
					'<li>' .
					'<a class="image-item" href="%s">' .
					'%s' .
					'</a>' .
					'<div class="content-item">' .
					'<a class="title-item" href="%s">' .
					'%s' .
					'</a>' .
					'</li>',
					esc_url( get_the_permalink( $id ) ),
					get_the_post_thumbnail( $id ),
					esc_url( get_the_permalink( $id ) ),
					$post->post_title
				);
			}
		}

		return $response;
	}

	/**
	 * get_recently_viewed_products
	 */
	function martfury_footer_recently_viewed() {
		if ( apply_filters( 'martfury_check_ajax_referer', true ) ) {
			check_ajax_referer( '_martfury_nonce', 'nonce' );
		}
		$atts              = array();
		$atts['numbers']   = martfury_get_option( 'footer_recently_viewed_number' );
		$atts['title']     = martfury_get_option( 'footer_recently_viewed_title' );
		$atts['link_text'] = martfury_get_option( 'footer_recently_viewed_link_text' );
		$atts['link_url']  = martfury_get_option( 'footer_recently_viewed_link_url' );
		$output            = martfury_recently_viewed_products( $atts );
		wp_send_json_success( $output );
		die();
	}

	/**
	 * get_recently_viewed_products
	 */
	function martfury_header_recently_viewed() {
		if ( apply_filters( 'martfury_check_ajax_referer', true ) ) {
			check_ajax_referer( '_martfury_nonce', 'nonce' );
		}
		$atts              = array();
		$atts['numbers']   = martfury_get_option( 'header_recently_viewed_number' );
		$atts['link_text'] = martfury_get_option( 'header_recently_viewed_link_text' );
		$atts['link_url']  = martfury_get_option( 'header_recently_viewed_link_url' );
		$output            = martfury_recently_viewed_products( $atts );
		wp_send_json_success( $output );
		die();
	}

	/**
	 *product_quick_view
	 */
	function product_quick_view() {
		if ( apply_filters( 'martfury_check_ajax_referer', true ) ) {
			check_ajax_referer( '_martfury_nonce', 'nonce' );
		}
		ob_start();
		if ( isset( $_POST['product_id'] ) && ! empty( $_POST['product_id'] ) ) {
			$product_id      = $_POST['product_id'];
			$original_post   = $GLOBALS['post'];
			$GLOBALS['post'] = get_post( $product_id ); // WPCS: override ok.
			setup_postdata( $GLOBALS['post'] );
			wc_get_template_part( 'content', 'product-quick-view' );
			$GLOBALS['post'] = $original_post; // WPCS: override ok.

		}
		$output = ob_get_clean();
		wp_send_json_success( $output );
		die();
	}

	/**
	 * Search SKU
	 *
	 * @since 1.0
	 */
	function product_search_sku( $where ) {
		global $pagenow, $wpdb, $wp;

		if ( ( is_admin() && 'edit.php' != $pagenow )
		     || ! is_search()
		     || ! isset( $wp->query_vars['s'] )
		     || ( isset( $wp->query_vars['post_type'] ) && 'product' != $wp->query_vars['post_type'] )
		     || ( isset( $wp->query_vars['post_type'] ) && is_array( $wp->query_vars['post_type'] ) && ! in_array( 'product', $wp->query_vars['post_type'] ) )
		) {
			return $where;
		}
		$search_ids = array();
		$terms      = explode( ',', $wp->query_vars['s'] );

		foreach ( $terms as $term ) {
			//Include the search by id if admin area.
			if ( is_admin() && is_numeric( $term ) ) {
				$search_ids[] = $term;
			}
			// search for variations with a matching sku and return the parent.

			$sku_to_parent_id = $wpdb->get_col( $wpdb->prepare( "SELECT p.post_parent as post_id FROM {$wpdb->posts} as p join {$wpdb->postmeta} pm on p.ID = pm.post_id and pm.meta_key='_sku' and pm.meta_value LIKE '%%%s%%' where p.post_parent <> 0 group by p.post_parent", wc_clean( $term ) ) );

			//Search for a regular product that matches the sku.
			$sku_to_id = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_sku' AND meta_value LIKE '%%%s%%';", wc_clean( $term ) ) );

			$search_ids = array_merge( $search_ids, $sku_to_id, $sku_to_parent_id );
		}

		$search_ids = array_filter( array_map( 'absint', $search_ids ) );

		if ( sizeof( $search_ids ) > 0 ) {
			$where = str_replace( ')))', ") OR ({$wpdb->posts}.ID IN (" . implode( ',', $search_ids ) . "))))", $where );
		}

		return $where;
	}

	function deals_expire_text( $expire ) {
		global $product;
		if ( is_single( $product->get_id() ) ) {

			$expire = wp_kses_post( martfury_get_option( 'product_deals_expire_text' ) );

			$expire = ! empty( $expire ) ? $expire : esc_html__( "Don't Miss Out!  This promotion will expires in", 'martfury' );
		}

		return $expire;
	}

	function deals_sold_text( $sold ) {
		global $product;
		if ( is_single( $product->get_id() ) ) {

			$sold = wp_kses_post( martfury_get_option( 'product_deals_sold_text' ) );

			$sold = ! empty( $sold ) ? $sold : esc_html__( "Sold items", 'martfury' );
		}

		return $sold;
	}

	function additional_information_tab_title() {
		return esc_html( martfury_get_option( 'product_specification_text' ) );
	}

	function description_tab_title() {
		return esc_html( martfury_get_option( 'product_description_text' ) );
	}

	function single_product_deal_header() {
		global $product;

		$ouput    = array();
		$taxonomy = 'product_cat';
		$terms    = get_the_terms( $product->get_id(), $taxonomy );

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			$link    = get_term_link( $terms[0], $taxonomy );
			$ouput[] = sprintf( '<a class="product-cat" href="%s">%s</a>', esc_url( $link ), $terms[0]->name );
		}

		$ouput[] = sprintf( '<h2 class="product-title"><a href="%s">%s</a></h2>', esc_url( $product->get_permalink() ), $product->get_title() );

		echo implode( ' ', $ouput );

	}

	function single_product_deal_stock() {
		global $product;
		echo wc_get_stock_html( $product );
	}

	function compare_added_label() {
		return esc_html__( 'Browse Compare', 'martfury' );
	}

	function upsells_total() {
		return intval( martfury_get_option( 'upsells_products_numbers' ) );
	}

	function product_layout_default() {
		// Single Product Layout 3

		if ( $this->product_layout != '3' ) {
			return;
		}

		global $product;

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

		add_action( 'woocommerce_single_product_summary', array( $this, 'open_single_product_summary_content' ), 1 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'close_single_product_summary_content' ), 70 );

		add_action( 'woocommerce_single_product_summary', array( $this, 'open_single_product_summary_sidebar' ), 70 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 80 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 90 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'close_single_product_summary_sidebar' ), 100 );
	}

	function open_single_product_summary_content() {
		echo '<div class="entry-summary-content">';
	}

	function close_single_product_summary_content() {
		echo '</div>';
	}

	function open_single_product_summary_sidebar() {
		echo '<div class="entry-summary-sidebar">';
	}

	function close_single_product_summary_sidebar() {
		echo '</div>';
	}

	function catalog_orderby_default( $orderby ) {
		$orderby = empty( $orderby ) ? 'menu_order' : $orderby;

		return $orderby;
	}

	function get_product_single_excerpt() {
		$featured_text = get_post_meta( get_the_ID(), 'product_features_desc', true );
		if ( ! empty( $featured_text ) ) {
			echo '<div class="woocommerce-product-details__short-description">';
			echo '<div class="mf-features-text">';
			echo wp_kses_post( $featured_text );
			echo '</div></div>';
		} elseif ( function_exists( 'woocommerce_template_single_excerpt' ) ) {
			woocommerce_template_single_excerpt();
		}
	}

	function sticky_product_info() {

		if ( ! is_singular( 'product' ) ) {
			return;
		}
		$sticky_product = apply_filters( 'martfury_sticky_product_info', martfury_get_option( 'sticky_product_info' ) );
		if ( ! intval( $sticky_product ) ) {
			return;
		}

		wc_get_template( 'single-product/sticky-product-info.php' );
	}

	/**
	 * Display badge for new product or featured product
	 *
	 * @since 1.0
	 */
	function product_ribbons() {
		global $product;

		$output = array();
		$badges = martfury_get_option( 'badges' );
		// Change the default sale ribbon

		$custom_badges = maybe_unserialize( get_post_meta( $product->get_id(), 'custom_badges_text', true ) );
		if ( $custom_badges ) {

			$output[] = '<span class="custom ribbon">' . esc_html( $custom_badges ) . '</span>';

		} else {
			if ( ! $product->is_in_stock() && in_array( 'outofstock', $badges ) ) {
				$outofstock = martfury_get_option( 'outofstock_text' );
				if ( ! $outofstock ) {
					$outofstock = esc_html__( 'Out Of Stock', 'martfury' );
				}
				$output[] = '<span class="out-of-stock ribbon">' . esc_html( $outofstock ) . '</span>';
			} elseif ( $product->is_on_sale() && in_array( 'sale', $badges ) ) {
				$percentage = 0;
				$save       = 0;
				if ( $product->get_type() == 'variable' ) {
					$available_variations = $product->get_available_variations();
					$percentage           = 0;
					$save                 = 0;

					for ( $i = 0; $i < count( $available_variations ); $i ++ ) {
						$variation_id     = $available_variations[ $i ]['variation_id'];
						$variable_product = new WC_Product_Variation( $variation_id );
						$regular_price    = $variable_product->get_regular_price();
						$sales_price      = $variable_product->get_sale_price();
						if ( empty( $sales_price ) ) {
							continue;
						}
						$max_percentage = $regular_price ? round( ( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ) ) : 0;
						$max_save       = $regular_price ? $regular_price - $sales_price : 0;

						if ( $percentage < $max_percentage ) {
							$percentage = $max_percentage;
						}

						if ( $save < $max_save ) {
							$save = $max_save;
						}
					}
				} elseif ( $product->get_type() == 'simple' || $product->get_type() == 'external' ) {
					$percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
					$save       = $product->get_regular_price() - $product->get_sale_price();
				}
				if ( martfury_get_option( 'sale_type' ) == '2' ) {
					if ( $save ) {
						$output[] = '<span class="onsale ribbon sale-text"><span class="sep">' . esc_html( martfury_get_option( 'sale_save_text' ) ) . '</span>' . ' ' . wc_price( $save ) . '</span>';
					}
				} else {
					if ( $percentage ) {
						$output[] = '<span class="onsale ribbon"><span class="sep">-</span>' . $percentage . '%' . '</span>';
					}
				}

			} elseif ( $product->is_featured() && in_array( 'hot', $badges ) ) {
				$hot = martfury_get_option( 'hot_text' );
				if ( ! $hot ) {
					$hot = esc_html__( 'Hot', 'martfury' );
				}
				$output[] = '<span class="featured ribbon">' . esc_html( $hot ) . '</span>';
			} elseif ( ( time() - ( 60 * 60 * 24 * $this->new_duration ) ) < strtotime( get_the_time( 'Y-m-d' ) ) && in_array( 'new', $badges ) ||
			           get_post_meta( $product->get_id(), '_is_new', true ) == 'yes'
			) {
				$new = martfury_get_option( 'new_text' );
				if ( ! $new ) {
					$new = esc_html__( 'New', 'martfury' );
				}
				$output[] = '<span class="newness ribbon">' . esc_html( $new ) . '</span>';
			}
		}


		if ( $output ) {
			printf( '<span class="ribbons">%s</span>', implode( '', $output ) );
		}


	}

	function single_product_zoom_enabled() {
		return martfury_get_option( 'product_zoom' );
	}

	function wcwl_table_after_product_name( $item ) {
		$product      = wc_get_product( $item['prod_id'] );
		$base_product = $product->is_type( 'variable' ) ? $product->get_variation_regular_price( 'max' ) : $product->get_price();
		echo '<div class="product-price">';
		if ( $base_product ) {
			echo wp_kses_post( $product->get_price_html() );
		} else {
			echo apply_filters( 'yith_free_text', esc_html__( 'Free!', 'martfury' ) );
		}
		echo '</div>';

		$availability = $product->get_availability();
		$stock_status = $availability['class'];

		if ( $stock_status != 'out-of-stock' ) {
			echo '<div class="product-add-to-cart">';
			woocommerce_template_loop_add_to_cart();
			echo '</div>';
		}
	}

	function search_products_header() {
		if ( ! martfury_is_catalog() ) {
			return;
		}

		if ( ! is_search() ) {
			return;
		}

		echo sprintf( '<h2 class="mf-catalog-title">%s<span> %s</span></h2>', esc_html__( 'Search Results for', 'martfury' ), '"' . get_search_query() . '"' );
	}

	function buy_now_redirect( $url ) {

		if ( ! isset( $_REQUEST['buy_now'] ) || $_REQUEST['buy_now'] == false ) {
			return $url;
		}

		if ( empty( $_REQUEST['quantity'] ) ) {
			return $url;
		}

		if ( is_array( $_REQUEST['quantity'] ) ) {
			$quantity_set = false;
			foreach ( $_REQUEST['quantity'] as $item => $quantity ) {
				if ( $quantity <= 0 ) {
					continue;
				}
				$quantity_set = true;
			}

			if ( ! $quantity_set ) {
				return $url;
			}
		}


		$redirect = martfury_get_option( 'product_buy_now_link' );
		if ( empty( $redirect ) ) {
			return wc_get_checkout_url();
		} else {
			wp_safe_redirect( $redirect );
			exit;
		}
	}

	function login_form_promotion() {
		if ( martfury_get_option( 'login_register_layout' ) != 'promotion' ) {
			return;
		}

		$output    = array();
		$pro_title = martfury_get_option( 'login_promotion_title' );
		if ( ! empty( $pro_title ) ) {
			$output[] = sprintf( '<h2 class="pro-title">%s</h2>', wp_kses_post( $pro_title ) );
		}
		$pro_text = martfury_get_option( 'login_promotion_text' );
		if ( ! empty( $pro_text ) ) {
			$output[] = sprintf( '<p class="pro-text">%s</p>', wp_kses_post( $pro_text ) );
		}
		$pro_list = martfury_get_option( 'login_promotion_list' );
		if ( ! empty( $pro_list ) ) {
			$output[] = sprintf( '<div class="pro-list">%s</div>', wp_kses_post( $pro_list ) );
		}
		$output[] = '<div class="pro-sep"></div>';

		$ads_title = martfury_get_option( 'login_ads_title' );
		$ads_text  = martfury_get_option( 'login_ads_text' );

		if ( ! empty( $ads_title ) || ! empty( $ads_text ) ) {
			$output[] = '<div class="promotion-ads-content">';
			if ( ! empty( $ads_title ) ) {
				$output[] = sprintf( '<h2 class="promotion-ads-title">%s</h2>', wp_kses_post( $ads_title ) );
			}
			if ( ! empty( $ads_text ) ) {
				$output[] = sprintf( '<div class="promotion-ads-text">%s</div>', wp_kses_post( $ads_text ) );
			}
			$output[] = '</div>';
		}

		if ( ! empty( $output ) ) {
			echo sprintf( '<div class="col-md-6 col-sm-12 col-md-offset-1 col-login-promotion"><div class="login-promotion">%s</div></div>', implode( ' ', $output ) );
		}
	}

	function structured_data_product( $markup, $product ) {
		$terms = get_the_terms( $product->get_id(), 'product_brand' );
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$markup['brand'] = $terms[0]->name;
		}

		return $markup;
	}

	/**
	 * Empty cart button.
	 */
	function empty_cart_button() {
		if ( ! intval( martfury_get_option( 'clear_cart_button' ) ) ) {
			return;
		}

		?>
        <button type="submit" class="button empty-cart-button" name="empty_cart"
                value="<?php esc_attr_e( 'Clear cart', 'martfury' ); ?>"><?php esc_html_e( 'Clear cart', 'martfury' ); ?></button>
		<?php
	}

	/**
	 * Empty cart.
	 */
	function empty_cart_action() {
		if ( ! intval( martfury_get_option( 'clear_cart_button' ) ) ) {
			return;
		}

		if ( ! empty( $_POST['empty_cart'] ) && wp_verify_nonce( wc_get_var( $_REQUEST['woocommerce-cart-nonce'] ), 'woocommerce-cart' ) ) {
			WC()->cart->empty_cart();
			wc_add_notice( esc_html__( 'Cart is cleared.', 'martfury' ) );

			$referer = wp_get_referer() ? remove_query_arg( array(
				'remove_item',
				'add-to-cart',
				'added-to-cart',
			), add_query_arg( 'cart_emptied', '1', wp_get_referer() ) ) : wc_get_cart_url();
			wp_safe_redirect( $referer );
			exit;
		}
	}

	function martfury_catalog_shortcode_products_query( $query_args, $attributes, $type ) {
		if ( martfury_is_catalog() && ! in_array( $type, array( 'best_selling_products', 'top_rated_products' ) ) ) {
			$query_args['orderby'] = $attributes['orderby'];
		}

		return $query_args;
	}

	function related_posts_relate_by_category() {
		return martfury_get_option( 'related_product_by_categories' );
	}

	function related_posts_relate_by_parent_category( $term_ids, $product_id ) {
		if ( ! intval( martfury_get_option( 'related_product_by_categories' ) ) ) {
			return $term_ids;
		}

		if ( ! intval( martfury_get_option( 'related_product_by_parent_category' ) ) ) {
			return $term_ids;
		}

		$terms = wc_get_product_terms(
			$product_id, 'product_cat', array(
				'orderby' => 'parent',
			)
		);

		$term_ids = array();

		if ( ! is_wp_error( $terms ) && $terms ) {
			$current_term = end( $terms );
			$term_ids[] = $current_term->term_id;
		}

		return $term_ids;

	}

	function related_posts_relate_by_tag() {
		return martfury_get_option( 'related_product_by_tags' );
	}
}