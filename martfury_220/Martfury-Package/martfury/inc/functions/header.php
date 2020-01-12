<?php
/**
 * Custom functions for header.
 *
 * @package Martfury
 */


/**
 * Get Menu extra Account
 *
 * @since  1.0.0
 *
 * @return string
 */
if ( ! function_exists( 'martfury_extra_account' ) ) :
	function martfury_extra_account() {
		$extras = martfury_menu_extras();

		if ( empty( $extras ) || ! in_array( 'account', $extras ) ) {
			return;
		}

		if ( is_user_logged_in() ) {
			$user_menu = martfury_nav_vendor_menu();
			$user_id   = get_current_user_id();
			if ( empty( $user_menu ) ) {
				$user_menu = martfury_nav_user_menu();
			}
			$account      = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
			$account_link = $account;
			$author       = get_user_by( 'id', $user_id );

			if ( function_exists( 'dokan_get_navigation_url' ) && in_array( 'seller', $author->roles ) ) {
				$account_link = dokan_get_navigation_url();
			} elseif ( class_exists( 'WCVendors_Pro' ) && in_array( 'vendor', $author->roles ) ) {
				$dashboard_page_id = get_option( 'wcvendors_vendor_dashboard_page_id' );
				if ( $dashboard_page_id ) {
					$account_link = get_permalink( $dashboard_page_id );
				}

			} elseif ( class_exists( 'WC_Vendors' ) && in_array( 'vendor', $author->roles ) ) {
				$vendor_dashboard_page = get_option( 'wcvendors_vendor_dashboard_page_id' );
				$account_link          = get_permalink( $vendor_dashboard_page );

			} elseif ( class_exists( 'WCMp' ) && in_array( 'dc_vendor', $author->roles ) ) {
				if ( function_exists( 'wcmp_vendor_dashboard_page_id' ) && wcmp_vendor_dashboard_page_id() ) {
					$account_link = get_permalink( wcmp_vendor_dashboard_page_id() );
				}
			} elseif ( function_exists( 'wcfm_is_vendor' ) && wcfm_is_vendor() ) {
				$pages = get_option( "wcfm_page_options" );
				if ( isset( $pages['wc_frontend_manager_page_id'] ) && $pages['wc_frontend_manager_page_id'] ) {
					$account_link = get_permalink( $pages['wc_frontend_manager_page_id'] );
				}

			}

			$logged_type = '<i class="extra-icon icon-user"></i>';
			$user_type   = 'icon';
			if ( martfury_get_option( 'user_logged_type' ) == 'avatar' ) {
				$logged_type = get_avatar( $user_id, 32 );
				$user_type   = 'avatar';
			}

			echo sprintf(
				'<li class="extra-menu-item menu-item-account logined %s">
				<a href="%s">%s</a>
				<ul>
					<li>
						<h3>%s</h3>
					</li>
					<li>
						%s
					</li>
					<li class="line-space"></li>
					<li class="logout">
						<a href="%s">%s</a>
					</li>
				</ul>
			</li>',
				esc_attr( $user_type ),
				esc_url( $account_link ),
				$logged_type,
				esc_html__( 'Hello,', 'martfury' ) . ' ' . $author->display_name . '!',
				implode( ' ', $user_menu ),
				esc_url( wp_logout_url( $account ) ),
				esc_html__( 'Logout', 'martfury' )
			);
		} else {

			$register      = '';
			$register_text = esc_html__( 'Register', 'martfury' );

			if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) {
				$register = sprintf(
					'<a href="%s" class="item-register" id="menu-extra-register">%s</a>',
					esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ),
					$register_text
				);
			}

			echo sprintf(
				'<li class="extra-menu-item menu-item-account">
					<a href="%s" id="menu-extra-login"><i class="extra-icon icon-user"></i>%s</a>
					%s
				</li>',
				esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ),
				esc_html__( 'Log in', 'martfury' ),
				$register
			);
		}


	}
endif;
/**
 * Get Menu extra cart
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'martfury_vendor_navigation_url' ) ) :
	function martfury_vendor_navigation_url() {
		$author = get_user_by( 'id', get_current_user_id() );
		$vendor = array();
		if ( function_exists( 'dokan_get_navigation_url' ) && in_array( 'seller', $author->roles ) ) {
			$vendor[] = sprintf( '<li><a href="%s">%s</a></li>', esc_url( dokan_get_navigation_url() ), esc_html__( 'Dashboard', 'martfury' ) );
			$vendor[] = sprintf( '<li><a href="%s">%s</a></li>', esc_url( dokan_get_navigation_url( 'products' ) ), esc_html__( 'Products', 'martfury' ) );
			$vendor[] = sprintf( '<li><a href="%s">%s</a></li>', esc_url( dokan_get_navigation_url( 'orders' ) ), esc_html__( 'Orders', 'martfury' ) );
			$vendor[] = sprintf( '<li><a href="%s">%s</a></li>', esc_url( dokan_get_navigation_url( 'edit-account' ) ), esc_html__( 'Settings', 'martfury' ) );
			if ( function_exists( 'dokan_get_store_url' ) ) {
				$vendor[] = sprintf( '<li><a href="%s">%s</a></li>', esc_url( dokan_get_store_url( get_current_user_id() ) ), esc_html__( 'Visit Store', 'martfury' ) );
			}
			$vendor[] = sprintf( '<li><a href="%s">%s</a></li>', esc_url( dokan_get_navigation_url( 'withdraw' ) ), esc_html__( 'Withdraw', 'martfury' ) );
		} elseif ( class_exists( 'WCVendors_Pro' ) && in_array( 'vendor', $author->roles ) ) {
			$dashboard_page_id = get_option( 'wcvendors_vendor_dashboard_page_id' );
			if ( $dashboard_page_id ) {
				$dashboard_page_url = get_permalink( $dashboard_page_id );
				$vendor[]           = sprintf( '<li><a href="%s">%s</a></li>', esc_url( $dashboard_page_url ), esc_html__( 'Dashboard', 'martfury' ) );
				$vendor[]           = sprintf( '<li><a href="%s">%s</a></li>', esc_url( $dashboard_page_url . 'product' ), esc_html__( 'Products', 'martfury' ) );
				$vendor[]           = sprintf( '<li><a href="%s">%s</a></li>', esc_url( $dashboard_page_url . 'order' ), esc_html__( 'Orders', 'martfury' ) );
				$vendor[]           = sprintf( '<li><a href="%s">%s</a></li>', esc_url( $dashboard_page_url . 'settings' ), esc_html__( 'Settings', 'martfury' ) );
			}
		} elseif ( class_exists( 'WC_Vendors' ) && in_array( 'vendor', $author->roles ) ) {
			$vendor_dashboard_page = get_option( 'wcvendors_vendor_dashboard_page_id' );
			$shop_settings_page    = get_option( 'wcvendors_shop_settings_page_id' );

			if ( ! empty( $vendor_dashboard_page ) && ! empty( $shop_settings_page ) ) {
				if ( ! empty( $vendor_dashboard_page ) ) {
					$vendor[] = sprintf( '<li><a href="%s">%s</a></li>', esc_url( get_permalink( $vendor_dashboard_page ) ), esc_html__( 'Dashboard', 'martfury' ) );
				}
				if ( ! empty( $shop_settings_page ) ) {
					$vendor[] = sprintf( '<li><a href="%s">%s</a></li>', esc_url( get_permalink( $shop_settings_page ) ), esc_html__( 'Shop Settings', 'martfury' ) );
				}
				if ( class_exists( 'WCV_Vendors' ) ) {
					$shop_page = WCV_Vendors::get_vendor_shop_page( get_current_user_id() );
					$vendor[]  = sprintf( '<li><a href="%s">%s</a></li>', esc_url( $shop_page ), esc_html__( 'Visit Store', 'martfury' ) );
				}
			}

		}

		return $vendor;
	}
endif;

/**
 * Get Custom Vendor
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'martfury_nav_user_menu' ) ) :
	function martfury_nav_user_menu() {
		$user_menu = array();
		if ( ! has_nav_menu( 'user_logged' ) ) {
			$orders  = get_option( 'woocommerce_myaccount_orders_endpoint', 'orders' );
			$account = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
			if ( substr( $account, - 1, 1 ) != '/' ) {
				$account .= '/';
			}
			$orders   = $account . $orders;
			$wishlist = '';
			if ( shortcode_exists( 'yith_wishlist_constructor' ) ) {
				$wishlist = sprintf(
					'<li>
						<a href="%s">%s</a>
					</li>',
					esc_url( get_permalink( get_option( 'yith_wcwl_wishlist_page_id' ) ) ),
					esc_html__( 'My Wishlist', 'martfury' )
				);
			}

			$user_menu[] = sprintf(
				'<li>
					<a href="%s">%s</a>
				</li>
				<li>
					<a href="%s">%s</a>
				</li>
				<li>
					<a href="%s">%s</a>
				</li>
				%s
				</li>',
				esc_url( $account ),
				esc_html__( 'Dashboard', 'martfury' ),
				esc_url( $account . get_option( 'woocommerce_myaccount_edit_account_endpoint', 'edit-account' ) ),
				esc_html__( 'Account Settings', 'martfury' ),
				esc_url( $orders ),
				esc_html__( 'Orders History', 'martfury' ),
				$wishlist
			);
		} else {
			ob_start();
			martfury_get_nav_menu( 'user_logged' );
			$user_menu[] = ob_get_clean();
		}

		return $user_menu;
	}
endif;

/**
 * Get Custom Vendor
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'martfury_nav_vendor_menu' ) ) :
	function martfury_nav_vendor_menu() {
		$author      = get_user_by( 'id', get_current_user_id() );
		$vendor_menu = array();

		if ( ! in_array( 'vendor', $author->roles ) && ! in_array( 'seller', $author->roles )
		     && ! in_array( 'dc_vendor', $author->roles ) && ! in_array( 'wcfm_vendor', $author->roles ) ) {
			return $vendor_menu;
		}
		if ( ! has_nav_menu( 'vendor_logged' ) ) {
			$vendor_menu = martfury_vendor_navigation_url();
		} else {
			ob_start();
			martfury_get_nav_menu( 'vendor_logged' );
			$vendor_menu[] = ob_get_clean();
		}

		return $vendor_menu;
	}
endif;


/**
 * Get Menu extra cart
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'martfury_extra_cart' ) ) :
	function martfury_extra_cart() {
		$extras = martfury_menu_extras();

		if ( empty( $extras ) || ! in_array( 'cart', $extras ) ) {
			return '';
		}

		if ( ! function_exists( 'woocommerce_mini_cart' ) ) {
			return '';
		}
		global $woocommerce;
		ob_start();
		woocommerce_mini_cart();
		$mini_cart = ob_get_clean();

		$mini_content = sprintf( '	<div class="widget_shopping_cart_content">%s</div>', $mini_cart );

		printf(
			'<li class="extra-menu-item menu-item-cart mini-cart woocommerce">
				<a class="cart-contents" id="icon-cart-contents" href="%s">
					<i class="icon-bag2 extra-icon"></i>
					<span class="mini-item-counter">
						%s
					</span>
				</a>
				<div class="mini-cart-content">
				<span class="tl-arrow-menu"></span>
				%s
				</div>
			</li>',
			esc_url( wc_get_cart_url() ),
			intval( $woocommerce->cart->cart_contents_count ),
			$mini_content
		);

	}
endif;

/**
 * Get Menu extra wishlist
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'martfury_extra_wislist' ) ) :
	function martfury_extra_wislist() {
		$extras = martfury_menu_extras();


		if ( empty( $extras ) || ! in_array( 'wishlist', $extras ) ) {
			return '';
		}

		if ( ! function_exists( 'YITH_WCWL' ) ) {
			return '';
		}

		$count = YITH_WCWL()->count_products();

		printf(
			'<li class="extra-menu-item menu-item-wishlist menu-item-yith">
			<a class="yith-contents" id="icon-wishlist-contents" href="%s">
				<i class="icon-heart extra-icon" rel="tooltip"></i>
				<span class="mini-item-counter">
					%s
				</span>
			</a>
		</li>',
			esc_url( get_permalink( get_option( 'yith_wcwl_wishlist_page_id' ) ) ),
			intval( $count )
		);

	}
endif;

/**
 * Get Menu extra wishlist
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'martfury_extra_compare' ) ) :
	function martfury_extra_compare() {
		$extras = martfury_menu_extras();

		if ( empty( $extras ) || ! in_array( 'compare', $extras ) ) {
			return '';
		}

		if ( ! class_exists( 'YITH_Woocompare' ) ) {
			return '';
		}

		global $yith_woocompare;

		$count = $yith_woocompare->obj->products_list;


		printf(
			'<li class="extra-menu-item menu-item-compare menu-item-yith">
				<a class="yith-contents yith-woocompare-open" href="#">
					<i class="icon-chart-bars extra-icon"></i>
					<span class="mini-item-counter" id="mini-compare-counter">
						%s
					</span>
				</a>
			</li>', sizeof( $count )
		);

	}
endif;

/**
 * Get Menu extra hotline
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'martfury_extra_hotline' ) ) :
	function martfury_extra_hotline() {
		$extras = martfury_menu_extras();


		if ( empty( $extras ) || ! in_array( 'hotline', $extras ) ) {
			return '';
		}

		$hotline_text   = martfury_get_option( 'custom_hotline_text' );
		$hotline_number = martfury_get_option( 'custom_hotline_number' );


		printf(
			'<li class="extra-menu-item menu-item-hotline">
				<i class="icon-telephone extra-icon"></i>
				<span class="hotline-content">
					<label>%s</label>
					<span>%s</span>
				</span>
		    </li>',
			esc_html( $hotline_text ),
			esc_html( $hotline_number )
		);

	}
endif;


/**
 * Get Menu extra search
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'martfury_extra_search' ) ) :
	function martfury_extra_search( $show_cat = true ) {
		$extras = martfury_menu_extras();
		if ( empty( $extras ) || ! in_array( 'search', $extras ) ) {
			return;
		}

		$output = array();

		if ( martfury_get_option( 'search_form_type' ) == 'default' ) {
			$output[] = martfury_extra_search_form( $show_cat );
		} else {
			$search_form = martfury_get_option( 'search_form_shortcode' );
			$output[]    = do_shortcode( wp_kses( $search_form, wp_kses_allowed_html( 'post' ) ) );
		}

		$hot_words = array();
		if ( intval( martfury_get_option( 'header_hot_words_enable' ) ) ) {
			$hot_words = martfury_get_hot_words();
		}

		echo sprintf(
			'<div class="product-extra-search">
                %s %s
            </div>',
			implode( '', $output ),
			implode( '', $hot_words )
		);

	}
endif;

/**
 * Get Menu extra search form
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'martfury_extra_search_form' ) ) :
	function martfury_extra_search_form( $show_cat = true ) {

		$cats_text   = martfury_get_option( 'custom_categories_text' );
		$search_text = martfury_get_option( 'custom_search_text' );
		$button_text = martfury_get_option( 'custom_search_button' );
		$search_type = martfury_get_option( 'search_content_type' );

		if ( $search_type == 'all' ) {
			$show_cat = false;
		}

		if ( ! intval( martfury_get_option( 'search_product_categories' ) ) ) {
			$show_cat = false;
		}

		$cat = '';
		if ( taxonomy_exists( 'product_cat' ) && $show_cat ) {

			$depth = 0;
			if ( intval( martfury_get_option( 'custom_categories_depth' ) ) > 0 ) {
				$depth = intval( martfury_get_option( 'custom_categories_depth' ) );
			}

			$args = array(
				'name'            => 'product_cat',
				'taxonomy'        => 'product_cat',
				'orderby'         => 'NAME',
				'hierarchical'    => 1,
				'hide_empty'      => 1,
				'echo'            => 0,
				'value_field'     => 'slug',
				'class'           => 'product-cat-dd',
				'show_option_all' => esc_html( $cats_text ),
				'depth'           => $depth,
				'id'              => 'header-search-product-cat',
			);

			$cat_include = martfury_get_option( 'custom_categories_include' );
			if ( ! empty( $cat_include ) ) {
				$cat_include     = explode( ',', $cat_include );
				$args['include'] = $cat_include;
			}

			$cat_exclude = martfury_get_option( 'custom_categories_exclude' );
			if ( ! empty( $cat_exclude ) ) {
				$cat_exclude     = explode( ',', $cat_exclude );
				$args['exclude'] = $cat_exclude;
			}

			$cat = wp_dropdown_categories( $args );
		}
		$item_class     = empty( $cat ) ? 'no-cats' : '';
		$post_type_html = '';
		if ( $search_type == 'product' ) {
			$post_type_html = '<input type="hidden" name="post_type" value="product">';
		}

		$lang = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : false;
		if ( $lang ) {
			$post_type_html .= '<input type="hidden" name="lang" value="' . $lang . '"/>';
		}

		return sprintf(
			'<form class="products-search" method="get" action="%s">
                <div class="psearch-content">
                    <div class="product-cat"><div class="product-cat-label %s">%s</div> %s</div>
                    <div class="search-wrapper">
                        <input type="text" name="s"  class="search-field" autocomplete="off" placeholder="%s">
                        %s
                        <div class="search-results woocommerce"></div>
                    </div>
                    <button type="submit" class="search-submit">%s</button>
                </div>
            </form>',
			esc_url( home_url( '/' ) ),
			esc_attr( $item_class ),
			esc_html( $cats_text ),
			$cat,
			esc_html( $search_text ),
			$post_type_html,
			wp_kses( $button_text, wp_kses_allowed_html( 'post' ) )
		);

	}
endif;

/**
 * Get header menu
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'martfury_extra_category' ) ) :
	function martfury_extra_category() {
		$extras = martfury_menu_extras();
		$items  = '';

		if ( empty( $extras ) || ! in_array( 'category', $extras ) ) {
			return $items;
		}

		echo '<a href="#" class="site-header-category--mobile" id="site-header-category--mobile"><i class="icon-menu"></i></a>';
	}
endif;


/**
 * Get header menu
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'martfury_header_menu' ) ) :
	function martfury_header_menu() {
		if ( ! has_nav_menu( 'primary' ) ) {
			return;
		}
		?>
        <div class="primary-nav nav">
			<?php
			$options = array(
				'theme_location' => 'primary',
				'container'      => false,
				'walker'         => new Martfury_Mega_Menu_Walker(),
			);
			wp_nav_menu( $options );
			?>
        </div>
		<?php
	}
endif;

/**
 * Get header bar
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'martfury_header_bar' ) ) :
	function martfury_header_bar() {
		if ( ! intval( martfury_get_option( 'header_bar' ) ) ) {
			return;
		}

		?>
        <div class="header-bar topbar">
			<?php
			$sidebar = 'header-bar';
			if ( is_active_sidebar( $sidebar ) ) {
				dynamic_sidebar( $sidebar );
			}
			?>
        </div>
		<?php
	}
endif;

/**
 * Get header recently products
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'martfury_header_recently_products' ) ) :
	function martfury_header_recently_products() {

		if ( ! intval( martfury_get_option( 'header_recently_viewed' ) ) ) {
			return;
		}

		$title    = martfury_get_option( 'header_recently_viewed_title' );
		$columns  = 8;
		$rv_class = "";
		if ( martfury_footer_container_classes() == 'martfury-container' ) {
			$columns  = 11;
			$rv_class = 'rv-full-width';
		}
		if ( $title ):
			?>
            <h3 class="recently-title">
				<?php echo esc_html( $title ); ?>
            </h3>
			<?php
			echo '<div class="mf-recently-products header-recently-viewed ' . $rv_class . '" data-columns="' . $columns . '" id="header-recently-viewed"><div class="mf-loading"></div></div>';
		endif;
	}
endif;

/**
 * Get header exrta department
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'martfury_extra_department' ) ) :
	function martfury_extra_department( $dep_close = false, $id = '' ) {
		$extras   = martfury_menu_extras();
		$location = 'shop_department';

		if ( empty( $extras ) || ! in_array( 'department', $extras ) ) {
			return;
		}

		if ( ! has_nav_menu( $location ) ) {
			return;
		}

		$dep_text = '<i class="icon-menu"><span class="s-space">&nbsp;</span></i>';
		$c_link   = martfury_get_option( 'custom_department_link' );
		if ( ! empty( $c_link ) ) {
			$dep_text .= '<a href="' . esc_url( $c_link ) . '" class="text">' . martfury_get_option( 'custom_department_text' ) . '</a>';
		} else {
			$dep_text .= '<span class="text">' . martfury_get_option( 'custom_department_text' ) . '</span>';
		}

		$dep_open = 'close';

		if ( $dep_close && martfury_is_homepage() ) {
			$dep_open = martfury_get_option( 'department_open_homepage' );
		}
		$cat_style = '';
		$space     = martfury_get_option( 'department_space_2_homepage' );
		if ( in_array( martfury_get_option( 'header_layout' ), array( '2', '3' ) ) ) {
			$space = martfury_get_option( 'department_space_homepage' );
		}
		if ( martfury_is_homepage() && $space ) {
			$cat_style = sprintf( 'style=padding-top:%s', esc_attr( $space ) );
		}

		?>
        <div class="products-cats-menu <?php echo esc_attr( $dep_open ); ?>">
            <h2 class="cats-menu-title"><?php echo wp_kses( $dep_text, wp_kses_allowed_html( 'post' ) ); ?></h2>

            <div class="toggle-product-cats nav" <?php echo esc_attr( $cat_style ); ?>>
				<?php
				global $martfury_department_menu;

				if ( empty( $martfury_department_menu ) ) {

					$options = array(
						'theme_location' => $location,
						'container'      => false,
						'echo'           => false,
						'walker'         => new Martfury_Mega_Menu_Walker()
					);

					$martfury_department_menu = wp_nav_menu( $options );
				}

				echo ! empty( $martfury_department_menu ) ? $martfury_department_menu : '';
				?>
            </div>
        </div>
		<?php
	}
endif;


/**
 * Get header exrta department
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'martfury_get_nav_menu' ) ) :
	function martfury_get_nav_menu( $location, $walker = true ) {
		if ( ! has_nav_menu( $location ) ) {
			return;
		}

		$options = array(
			'theme_location' => $location,
			'container'      => false,
		);

		if ( $walker ) {
			$options['walker'] = new Martfury_Mega_Menu_Walker();
		}
		wp_nav_menu( $options );

		?>
		<?php
	}
endif;


/**
 * Get menu extra
 *
 * @since  1.0.0
 *
 *
 * @return string
 */

if ( ! function_exists( 'martfury_menu_extras' ) ) :
	function martfury_menu_extras() {
		$menu_extras = apply_filters( 'martfury_get_menu_extras', martfury_get_option( 'menu_extras' ) );

		return $menu_extras;
	}
endif;

/**
 * Get header bar
 *
 * @since  1.0.0
 *
 *
 * @return array
 */
if ( ! function_exists( 'martfury_get_hot_words' ) ) :
	function martfury_get_hot_words() {

		$words_html = array();

		$hot_words = martfury_get_option( 'header_hot_words' );
		if ( ! empty( $hot_words ) ) {

			$words_html[] = '<ul class="hot-words">';
			foreach ( $hot_words as $word ) {
				if ( isset( $word['text'] ) && ! empty( $word['text'] ) ) {
					$words_html[] = sprintf( '<li><a href="%s">%s</a></li>', esc_url( $word['link'] ), $word['text'] );
				}
			}
			$words_html[] = '</ul>';
		}

		return $words_html;
	}
endif;


/**
 * Returns CSS for the color schemes.
 *
 *
 * @param array $colors Color scheme colors.
 *
 * @return string Color scheme CSS.
 */
function martfury_get_color_scheme_css( $colors, $darken_color ) {

	if ( is_page_template( 'template-coming-soon-page.php' ) ) {
		return;
	}

	return <<<CSS
	/* Color Scheme */

	/* Color */

	a:hover, 
	.primary-color, 
	.site-header .products-cats-menu .menu > li:hover > a, 
	.header-layout-3 .site-header .primary-nav > ul > li > a:hover, 
	.header-layout-6 .site-header .primary-nav > ul > li > a:hover, 
	.header-layout-6 .site-header .primary-nav > ul > li.current-menu-parent > a,.header-layout-6 .site-header .primary-nav > ul > li.current-menu-item > a,.header-layout-6 .site-header .primary-nav > ul > li.current-menu-ancestor > a, 
	.page-header .breadcrumbs, 
	.single-post-header .entry-metas a:hover, 
	.single-post-header.layout-2.has-bg .entry-metas a:hover, 
	.page-header-catalog .page-breadcrumbs a:hover, 
	.page-header-page .page-breadcrumbs a:hover, 
	.page-header-default .page-breadcrumbs a:hover, 
	.nav li li a:hover, 
	.blog-wapper .categories-links a:hover, 
	.blog-wapper .entry-title a:hover, 
	.blog-wapper .entry-meta a:hover, 
	.blog-wapper.sticky .entry-title:hover:before, 
	.numeric-navigation .page-numbers.current,.numeric-navigation .page-numbers:hover, 
	.single-post .entry-header .entry-metas a:hover, 
	.single-post .entry-format.format-quote blockquote cite a:hover, 
	.single-post .entry-footer .tags-links a:hover, 
	.single-post .post-navigation .nav-links a:hover, 
	.error-404 .page-content a, 
	.woocommerce ul.products li.product.product-category:hover .woocommerce-loop-category__title,.woocommerce ul.products li.product.product-category:hover .count, 
	.woocommerce ul.products li.product .mf-product-details-hover .sold-by-meta a:hover, 
	.woocommerce ul.products li.product .mf-product-details-hover .product-title, 
	.woocommerce ul.products li.product h2:hover a, 
	.woocommerce.shop-view-list .mf-shop-content ul.products li.product .mf-product-details h2 a:hover, 
	.woocommerce.shop-view-list .mf-shop-content ul.products li.product .mf-product-details .mf-product-price-box .yith-wcwl-add-to-wishlist .yith-wcwl-add-button > a:hover,.woocommerce.shop-view-list .mf-shop-content ul.products li.product .mf-product-details .mf-product-price-box .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse > a:hover,.woocommerce.shop-view-list .mf-shop-content ul.products li.product .mf-product-details .mf-product-price-box .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse > a:hover, 
	.woocommerce.shop-view-list .mf-shop-content ul.products li.product .mf-product-details .mf-product-price-box .compare-button .compare:hover, 
	.woocommerce-cart .woocommerce table.shop_table td.product-remove .mf-remove:hover, 
	.woocommerce-account .woocommerce .woocommerce-MyAccount-navigation ul li:not(.is-active) a:hover, 
	.woocommerce-account .woocommerce .woocommerce-Addresses .woocommerce-Address .woocommerce-Address-edit .edit:hover, 
	.catalog-sidebar .woocommerce-widget-layered-nav .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item.chosen.show-swatch .swatch-label, 
	.catalog-sidebar .widget_rating_filter ul .wc-layered-nav-rating.chosen a:after, 
	.catalog-sidebar .widget_rating_filter ul .wc-layered-nav-rating.chosen.show-swatch .swatch-label, 
	.mf-catalog-topbar .widget .woocommerce-ordering li li .active, 
	.mf-catalog-topbar .woocommerce-widget-layered-nav .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item.show-swatch.chosen .swatch-color:before, 
	.mf-catalog-topbar .catalog-filter-actived .remove-filter-actived, 
	.mf-products-top-carousel .carousel-header .cats-list li a:hover, 
	.mf-catalog-top-categories .top-categories-list .categories-list > li:hover > a, 
	.mf-catalog-top-categories .top-categories-grid .cats-list .parent-cat:hover, 
	.mf-catalog-top-categories .top-categories-grid .cats-list ul li.view-more a:hover, 
	.mf-other-categories .categories-list .cats-list .parent-cat:hover, 
	.dokan-dashboard .dokan-dashboard-wrap .dokan-table a:hover, 
	.dokan-widget-area .dokan-category-menu #cat-drop-stack > ul li.parent-cat-wrap a:hover, 
	.dokan-store.shop-view-list .seller-items ul.products li.product .mf-product-details h2 a:hover, 
	.dokan-store.shop-view-list .seller-items ul.products li.product .mf-product-details .mf-product-price-box .yith-wcwl-add-to-wishlist .yith-wcwl-add-button > a:hover,.dokan-store.shop-view-list .seller-items ul.products li.product .mf-product-details .mf-product-price-box .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse > a:hover,.dokan-store.shop-view-list .seller-items ul.products li.product .mf-product-details .mf-product-price-box .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse > a:hover, 
	.dokan-store.shop-view-list .seller-items ul.products li.product .mf-product-details .mf-product-price-box .compare-button .compare:hover, 
	.comment-respond .logged-in-as a:hover, 
	.widget ul li a:hover, 
	.widget_product_tag_cloud a:hover, 
	.widget-language ul li a:hover, 
	.widget-language ul li.active a, 
	.widgets-area ul li.current-cat > a,.dokan-store-sidebar ul li.current-cat > a,.widgets-area ul li.chosen > a,.dokan-store-sidebar ul li.chosen > a,.widgets-area ul li.current-cat > .count,.dokan-store-sidebar ul li.current-cat > .count,.widgets-area ul li.chosen > .count,.dokan-store-sidebar ul li.chosen > .count, 
	.widgets-area ul li .children li.current-cat > a,.dokan-store-sidebar ul li .children li.current-cat > a, 
	.widgets-area .mf_widget_product_categories ul li .children li.current-cat > a,.dokan-store-sidebar .mf_widget_product_categories ul li .children li.current-cat > a, 
	.site-footer .footer-info .info-item i, 
	.mf-recently-products .recently-header .link:hover, 
	.martfury-icon-box.icon_position-top-center .box-icon, 
	.martfury-icon-box.icon_position-left .box-icon, 
	.martfury-icon-box .box-url:hover, 
	.martfury-icon-box-2 .box-item .box-icon, 
	.martfury-latest-post .extra-links a:hover, 
	.mf-image-box .box-title a:hover, 
	.martfury-counter .mf-icon,
	.martfury-counter-els .mf-icon, 
	.martfury-testimonial-slides .testimonial-info > i, 
	.martfury-faq_group .g-title, 
	.mf-products-of-category .cats-info .extra-links li a:hover, 
	.mf-products-of-category .cats-info .footer-link .link:hover, 
	.mf-products-of-category .products-box ul.products li.product .product-inner:hover .mf-product-content h2 a, 
	.mf-category-tabs .tabs-header ul li a.active, 
	.mf-category-tabs .tabs-header ul li a.active h2, 
	.mf-products-of-category-2 .cats-header .extra-links li a:hover, 
	.mf-products-of-category-2 .products-side .link:hover, 
	.mf-category-box .cat-header .extra-links li a:hover, 
	.mf-category-box .sub-categories .term-item:hover .term-name, 
	.mf-products-carousel .cat-header .cat-title a:hover, 
	.mf-products-carousel .cat-header .extra-links li a:hover, 
	.mf-product-deals-day ul.products li.product .sold-by-meta a:hover, 
	.mf-product-deals-day .header-link a:hover, 
	.mf-product-deals-carousel .product .entry-summary .product-title a:hover,
	.mf-products-grid .cat-header .tabs-nav li a:hover, .mf-products-grid .cat-header .tabs-nav li a.active,
	.martfury-testimonial-slides.nav-2 .slick-arrow:hover,
	.mf-products-grid .cat-header .link:hover,
	.mf-navigation-mobile .navigation-icon.active,
	.mf-catalog-sorting-mobile .woocommerce-ordering ul li a.active,
	.account-page-promotion .customer-login .tabs-nav a.active,
	.account-page-promotion .login-promotion .pro-list ul li i {
		color: {$colors};
	}

	/* Background Color */

	.btn-primary,.btn,
	.slick-dots li:hover button,.slick-dots li.slick-active button,
	#nprogress .bar,
	.mf-newsletter-popup .newletter-content .mc4wp-form input[type="submit"],
	.site-header .products-search .search-submit,
	.site-header .extras-menu > li > a .mini-item-counter,
	.header-layout-1 .site-header .products-cats-menu:before,
	.header-layout-2 .site-header .main-menu,
	.header-layout-3 .site-header,
	.header-layout-3 .site-header .header-main,
	.header-layout-3 .site-header .products-cats-menu .menu > li:hover,
	.header-layout-4 .site-header,
	.header-layout-4 .site-header .header-main,
	.page-header-catalog .page-title,
	.single-post .post-password-form input[type=submit],
	.woocommerce a.button,.woocommerce button.button,.woocommerce input.button,.woocommerce #respond input#submit,
	.woocommerce a.button:hover,.woocommerce button.button:hover,.woocommerce input.button:hover,.woocommerce #respond input#submit:hover,
	.woocommerce a.button.alt,.woocommerce button.button.alt,.woocommerce input.button.alt,.woocommerce #respond input#submit.alt,
	.woocommerce a.button.alt:hover,.woocommerce button.button.alt:hover,.woocommerce input.button.alt:hover,.woocommerce #respond input#submit.alt:hover,
	
	.woocommerce ul.products li.product .mf-product-thumbnail .compare-button .compare:hover,
	.woocommerce ul.products li.product .mf-product-thumbnail .footer-button > a:hover,.woocommerce ul.products li.product .mf-product-thumbnail .footer-button .added_to_cart:hover,
	.woocommerce.shop-view-list .mf-shop-content ul.products li.product .mf-product-details .mf-product-price-box .button,
	.woocommerce.shop-view-list .mf-shop-content ul.products li.product .mf-product-details .mf-product-price-box .added_to_cart.wc-forward,
	.woocommerce div.product .wc-tabs-wrapper ul.tabs .tl-wc-tab,
	.woocommerce div.product form.cart .single_add_to_cart_button,
	.woocommerce nav.woocommerce-pagination ul li span.current,.woocommerce nav.woocommerce-pagination ul li a:hover,
	.woocommerce-cart .woocommerce table.cart .btn-shop,.woocommerce-cart .woocommerce table.checkout .btn-shop,
	.woocommerce-account .woocommerce .woocommerce-MyAccount-navigation ul li.is-active,
	.woocommerce-account .woocommerce .woocommerce-MyAccount-content .my_account_orders .leave_feedback,
	.mf-product-fbt .product-buttons .mf_add_to_cart_button,
	.mf-product-instagram .slick-slider .slick-dots li.slick-active,
	.mf-product-instagram .slick-slider .slick-dots li:hover button,.mf-product-instagram .slick-slider .slick-dots li.slick-active button,
	.dokan-dashboard .dokan-dashboard-wrap .dokan-btn,
	.dokan-widget-area .seller-form .dokan-btn,
	.dokan-widget-area .seller-form .dokan-btn:hover,
	.dokan-widget-area .dokan-store-contact .dokan-btn,
	.dokan-widget-area .dokan-store-contact .dokan-btn:hover,
	.dokan-store.shop-view-list .seller-items ul.products li.product .mf-product-details .mf-product-price-box .button,
	.dokan-store.shop-view-list .seller-items ul.products li.product .mf-product-details .mf-product-price-box .added_to_cart.wc-forward,
	.dokan-pagination-container ul.dokan-pagination li.active a,.dokan-pagination-container ul.dokan-pagination li a:hover,
	.dokan-seller-listing .store-footer .dokan-btn,
	.comment-respond .form-submit .submit,
	.widget .mc4wp-form input[type="submit"],
	.site-footer .footer-newsletter .newsletter-form .mc4wp-form-fields input[type="submit"],
	.mf-recently-products .product-list li .btn-secondary,
	.martfury-button.color-dark a,
	.martfury-button.color-white a,
	.martfury-journey ul a.active span,.martfury-journey ul a:hover span,
	.martfury-journey-els ul a.active span,.martfury-journey-els ul a:hover span,
	.martfury-member:after,
	.martfury-process .process-step:before,
	.martfury-newletter .mc4wp-form input[type="submit"],.woocommerce ul.products li.product .mf-product-thumbnail .yith-wcwl-add-to-wishlist .yith-wcwl-add-button > a:hover,.woocommerce ul.products li.product .mf-product-thumbnail .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistaddedbrowse > a:hover,.woocommerce ul.products li.product .mf-product-thumbnail .yith-wcwl-add-to-wishlist .yith-wcwl-wishlistexistsbrowse > a:hover,
	.wpcf7 input[type="submit"],
	.mf-category-tabs .tabs-header ul li:after,
	.mf-product-deals-day ul.slick-dots li.slick-active button,
	.mf-product-deals-grid .cat-header,
	.woocommerce .tawc-deal .deal-progress .progress-value,
	.mf-products-list-carousel ul.slick-dots li.slick-active button,
	 .mf-banner-large .banner-price .link,
	 .mf-banner-medium.layout-2 .banner-content .link, 
	 .mf-banner-medium.layout-3 .banner-content .link, 
	 .mf-banner-medium.layout-4 .banner-content .link,
	 .mf-banner-small .box-price,
	 .mf-els-modal-mobile .search-wrapper,
	 .primary-mobile-nav .mobile-nav-header,
	 .mf-els-modal-mobile .mf-cart-mobile .mobile-cart-header,
	 .sticky-header.header-layout-3 .site-header.minimized .mobile-menu, 
	 .sticky-header.header-layout-4 .site-header.minimized .mobile-menu,
	 .wcfm-membership-wrapper #wcfm_membership_container input.wcfm_submit_button,
	  .wcfm-membership-wrapper #wcfm_membership_container input.wcfm_submit_button:hover,
	  .wcfmmp-store-page #wcfmmp-store .add_review button, .wcfmmp-store-page #wcfmmp-store .user_rated, .wcfmmp-store-page #wcfmmp-stores-wrap a.wcfmmp-visit-store,
	  .wcfmmp-store-page #wcfmmp-store .add_review button:hover, .wcfmmp-store-page #wcfmmp-store .user_rated:hover, .wcfmmp-store-page #wcfmmp-stores-wrap a.wcfmmp-visit-store:hover,
	  .aws-container .aws-search-form .aws-search-btn,
	  .aws-container .aws-search-form .aws-search-btn:hover,
	  .aws-search-result .aws_add_to_cart .aws_cart_button,
	  .aws-search-result .aws_add_to_cart .aws_cart_button:hover{
		background-color: {$colors};
	}
	
	.widget_shopping_cart_content .woocommerce-mini-cart__buttons .checkout,
	 .header-layout-4 .topbar,
	 .header-layout-3 .topbar{
		background-color: {$darken_color};
	}

	/* Border Color */
	.slick-dots li button, 
	.woocommerce.shop-view-list .mf-shop-content ul.products li.product .mf-product-details .mf-product-price-box .compare-button .compare:hover:after, 
	.woocommerce div.product div.images .product-degree-images, 
	.woocommerce div.product div.images .flex-control-nav li:hover img, 
	.woocommerce div.product div.images .flex-control-nav li img.flex-active, 
	.woocommerce div.product .tawcvs-swatches .swatch.selected, 
	.woocommerce div.product .tawcvs-swatches .swatch.swatch-color.selected:after, 
	.catalog-sidebar .woocommerce-widget-layered-nav .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item.chosen a:before, 
	.catalog-sidebar .woocommerce-widget-layered-nav .woocommerce-widget-layered-nav-list .woocommerce-widget-layered-nav-list__item.chosen.show-swatch .swatch-label, 
	.catalog-sidebar .widget_rating_filter ul .wc-layered-nav-rating.chosen a:before, 
	.catalog-sidebar .widget_rating_filter ul .wc-layered-nav-rating.chosen.show-swatch .swatch-label, 
	.mf-catalog-categories-4 .cat-item:hover, 
	.mf-catalog-top-categories .top-categories-list .categories-list .sub-categories, 
	.mf-catalog-top-categories .top-categories-grid .cats-list ul li.view-more a:hover, 
	.mf-product-instagram .slick-slider .slick-dots li button, 
	.mf-recently-products .recently-header .link:hover, 
	.mf-recently-products .product-list li a:hover, 
	.mf-image-box:hover, 
	.martfury-process .process-step .step, 
	.martfury-bubbles, 
	.mf-product-deals-carousel, 
	.mf-products-list-carousel ul.slick-dots li.slick-active button, 
	.mf-product-deals-grid ul.products,
	.dokan-dashboard input[type="submit"].dokan-btn-theme, .dokan-dashboard a.dokan-btn-theme, .dokan-dashboard .dokan-btn-theme,
	.header-layout-2 .site-header .main-menu,
	.mobile-version .mf-product-deals-carousel.woocommerce .product .woocommerce-product-gallery .flex-control-thumbs li img.flex-active,
	.account-page-promotion .customer-login .tabs-nav a:after{
		border-color: {$colors};
	}
	
	.mf-loading:before,
	.woocommerce .blockUI.blockOverlay:after,
	.mf-product-gallery-degree .mf-gallery-degree-spinner:before{
		  border-color: {$colors} {$colors} {$colors} transparent;
	}
	
	#nprogress .peg {  box-shadow: 0 0 10px {$colors}, 0 0 5px {$colors};}
	
	blockquote {
		border-left-color:{$colors};
	}
	
	blockquote {
		border-right-color:{$colors};
	}
	
	.mf-product-deals-day .header-link a:hover{border-bottom-color: {$colors}; }
	
CSS;
}

function martfury_header_class() {
	$classes = array();

	if ( intval( martfury_get_option( 'header_hot_words_enable' ) ) ) {
		if ( martfury_get_option( 'header_hot_words' ) ) {
			$classes[] = 'has-hot-words';
		}
	}

	if ( intval( martfury_get_option( 'sticky_header' ) ) ) {
		if ( intval( martfury_get_option( 'sticky_header_logo' ) ) ) {
			$classes[] = 'sticky-header-logo';
		}
	}


	echo implode( ' ', $classes );
}