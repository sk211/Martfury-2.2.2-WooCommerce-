<?php

/**
 * Class menu walker
 *
 * @package Martfury
 */
class Martfury_Mega_Menu_Walker extends Walker_Nav_Menu {
	/**
	 * Store state of top level item
	 *
	 * @since 1.0.0
	 * @var boolean
	 */
	protected $in_mega = false;

	/**
	 * Background Item
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $style = '';

	/**
	 * Mega menu column
	 *
	 * @since 1.0.0
	 * @var int
	 */
	protected $column = 3;

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see   Walker::start_lvl()
	 *
	 * @since 1.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param array $args An array of arguments. @see wp_nav_menu()
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );

		if ( ! $this->in_mega ) {
			$output .= "\n$indent<ul class=\"dropdown-submenu\">\n";
		} else {
			if ( $depth == 0 ) {
				$output .= "\n$indent<ul\n$this->style class=\"dropdown-submenu\">\n$indent<li>\n$indent<div class=\"mega-menu-content\">\n$indent<div class=\"row\">\n";
			} elseif ( $depth == 1 ) {
				$output .= "\n$indent<div class=\"mega-menu-submenu\"><ul class=\"sub-menu check\">\n";
			} else {
				$output .= "\n$indent<ul class=\"sub-menu check\">\n";
			}
		}
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see   Walker::end_lvl()
	 *
	 * @since 1.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param array $args An array of arguments. @see wp_nav_menu()
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );

		if ( ! $this->in_mega ) {
			$output .= "\n$indent</ul>\n";
		} else {
			if ( $depth == 0 ) {
				$output .= "\n$indent</div>\n$indent</div>\n$indent</li>\n$indent</ul>\n";
			} elseif ( $depth == 1 ) {
				$output .= "\n$indent</ul>\n$indent</div>";
			} else {
				$output .= "\n$indent</ul>\n";
			}
		}
	}

	/**
	 * Start the element output.
	 * Display item description text and classes
	 *
	 * @see   Walker::start_el()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param array $args An array of arguments. @see wp_nav_menu()
	 * @param int $id Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$item_icon            = get_post_meta( $item->ID, 'tamm_menu_item_icon', true );
		$item_icon_color            = get_post_meta( $item->ID, 'tamm_menu_item_icon_color', true );
		$item_content         = get_post_meta( $item->ID, 'tamm_menu_item_content', true );
		$item_is_mega         = apply_filters( 'martfury_menu_item_mega', get_post_meta( $item->ID, 'tamm_menu_item_mega', true ), $item->ID );
		$item_mega_width      = get_post_meta( $item->ID, 'tamm_menu_item_mega_width', true );
		$item_width           = get_post_meta( $item->ID, 'tamm_menu_item_width', true );
		$item_hide_text       = get_post_meta( $item->ID, 'tamm_menu_item_hide_text', true );
		$item_visible_text    = get_post_meta( $item->ID, 'tamm_menu_item_visible_text', true );
		$item_hot             = get_post_meta( $item->ID, 'tamm_menu_item_hot', true );
		$item_new             = get_post_meta( $item->ID, 'tamm_menu_item_new', true );
		$item_trending        = get_post_meta( $item->ID, 'tamm_menu_item_trending', true );
		$item_is_label        = get_post_meta( $item->ID, 'tamm_menu_item_is_label', true );
		$item_mega_background = get_post_meta( $item->ID, 'tamm_menu_item_background', true );

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$this->style = '';
		$inline      = '';

		if ( $item_mega_background ) {


			if ( isset( $item_mega_background['image'] ) ) {
				$inline = 'background-image: url(' . esc_attr( $item_mega_background['image'] ) . ')';
			}

			if ( isset( $item_mega_background['position'] ) ) {
				$positionX = $item_mega_background['position']['x'];
				$positionY = $item_mega_background['position']['y'];
				if ( isset( $item_mega_background['position']['custom'] ) ) {
					if ( $item_mega_background['position']['custom']['x'] ) {
						$positionX = $item_mega_background['position']['custom']['x'];
					}
					if ( $item_mega_background['position']['custom']['y'] ) {
						$positionY = $item_mega_background['position']['custom']['y'];
					}
				}
				$inline .= '; background-position:' . esc_attr( $positionX ) . ' ' . esc_attr( $positionY );
			}

			if ( isset( $item_mega_background['repeat'] ) ) {
				$inline .= ' ; background-repeat:' . esc_attr( $item_mega_background['repeat'] );
			}

			if ( isset( $item_mega_background['size'] ) ) {
				$inline .= '; background-size:' . esc_attr( $item_mega_background['size'] );

			}
			if ( isset( $item_mega_background['attachment'] ) ) {
				$inline .= '; background-attachment:' . esc_attr( $item_mega_background['attachment'] );
			}

			if ( isset( $item_mega_background['color'] ) && ! empty( $item_mega_background['color'] ) ) {
				$inline .= '; background-color:' . esc_attr( $item_mega_background['color'] );
			}

		}

		if ( $item_mega_width ) {
			$inline .= '; width:' . esc_attr( $item_mega_width );
		}

		if ( $inline ) {
			$this->style = 'style="' . $inline . '"';;
		}

		/**
		 * Filter the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param array $args An array of arguments.
		 * @param object $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		/**
		 * Check if this is top level and is mega menu
		 * Add Bootstrap class for menu that has children
		 */
		if ( ! $depth ) {
			$this->in_mega = $item_is_mega;
		}

		/**
		 * Store mege menu panel's column
		 */
		if ( 1 == $depth && intval( $this->in_mega ) ) {
			$columns      = array(
				'16.66%'  => 2,
				'20.00%'  => 5,
				'25.00%'  => 3,
				'33.33%'  => 4,
				'50.00%'  => 6,
				'66.66%'  => 8,
				'75.00%'  => 9,
				'100.00%' => 12,
			);
			$width        = $item_width ? $item_width : '25.00%';
			$this->column = $columns[ $width ];
		}

		/**
		 * Add active class for current menu item
		 */
		$active_classes = array(
			'current-menu-item',
			'current-menu-parent',
			'current-menu-ancestor',
		);
		$is_active      = array_intersect( $classes, $active_classes );
		if ( ! empty( $is_active ) ) {
			$classes[] = 'active';
		}

		if ( in_array( 'menu-item-has-children', $classes ) ) {
			if ( ! $depth || ( $depth && ! intval( $this->in_mega ) ) ) {
				$classes[] = 'dropdown';
			}
			if ( ! $depth && intval( $this->in_mega ) ) {
				$classes[] = 'is-mega-menu';

				if ( $item_mega_width ) {
					$classes[] = 'has-width';
				}

				if ( $item_mega_background ) {
					$classes[] = 'has-background';
				}
			}
			if ( ! intval( $this->in_mega ) ) {
				$classes[] = 'hasmenu';
			}
		}

		/**
		 * Filter the CSS class(es) applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $classes The CSS classes that are applied to the menu item's `<li>` element.
		 * @param object $item The current menu item.
		 * @param array $args An array of {@see wp_nav_menu()} arguments.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filter the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param object $item The current menu item.
		 * @param array $args An array of {@see wp_nav_menu()} arguments.
		 * @param int $depth Depth of menu item. Used for padding.
		 */

		if ( $depth == 1 && intval( $this->in_mega ) ) {

			if ( $this->column == 5 ) {
				$class_names = ' class="mr-col col-md-1-' . $this->column . '"';
			} else {
				$class_names = ' class="mr-col col-md-' . $this->column . '"';
			}
			$output .= $indent . '<div' . $class_names . '>' . "\n";

			$output .= $indent . '<div class="menu-item-mega">';


		} else {
			$output .= $indent . '<li' . $class_names . '>';
		}

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		$atts['class'] = '';
		/**
		 * Add attributes for menu item link when this is not mega menu item
		 */
		if ( in_array( 'menu-item-has-children', $classes ) ) {
			$atts['class']         = 'dropdown-toggle';
			$atts['role']          = 'button';
			$atts['data-toggle']   = 'dropdown';
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
		}

		if ( $depth == 1 && intval( $this->in_mega ) ) {
			if ( $item_hide_text ) {
				$atts['class'] .= ' hide-text';
			}

			if ( $item_visible_text ) {
				$atts['class'] .= ' visible-text';
			}
		} else {
			if ( $item_icon ) {
				$atts['class'] .= ' has-icon';
			}

			if ( $item_is_label ) {
				$atts['class'] .= ' is-label';
			}
		}


		/**
		 * Filter the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *                       The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 * @type string $title Title attribute.
		 * @type string $target Target attribute.
		 * @type string $rel The rel attribute.
		 * @type string $href The href attribute.
		 * }
		 *
		 * @param object $item The current menu item.
		 * @param array $args An array of {@see wp_nav_menu()} arguments.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value      = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );

		/**
		 * Filter a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string $title The menu item's title.
		 * @param object $item The current menu item.
		 * @param array $args An array of {@see wp_nav_menu()} arguments.
		 * @param int $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$badge = array();
		if ( $item_hot || $item_new || $item_trending ) {
			$badge[] = '<span class="items-badge">';
			if ( $item_hot ) {
				$badge[] = '<span class="hot-badge">' . esc_html__( 'Hot', 'martfury' ) . '</span>';
			}
			if ( $item_new ) {
				$badge[] = '<span class="new-badge">' . esc_html__( 'New', 'martfury' ) . '</span>';
			}
			if ( $item_trending ) {
				$badge[] = '<span class="trending-badge">' . esc_html__( 'Trending', 'martfury' ) . '</span>';
			}
			$badge[] = '</span>';
		}

		$item_icon_color = $item_icon_color ? 'style="color:' . $item_icon_color . '"' : '';

		if ( $depth == 1 && intval( $this->in_mega ) ) {

			$icon_output = $item_icon ? '<i ' . $item_icon_color . ' class="' . esc_attr( $item_icon ) . '"></i> ' : '';
			$item_output = '<a ' . $attributes . '>' . $icon_output . $title . implode( $badge ) . '</a>';

			if ( ! empty( $item_content ) ) {
				$item_output .= '<div class="mega-content">' . do_shortcode( $item_content ) . '</div>';
			}
		} else {
			$item_output = ! empty( $args->before ) ? $args->before : '';
			$item_output .= '<a' . $attributes . '>' . implode( $badge );
			$item_output .= $item_icon ? '<i ' . $item_icon_color .  ' class="' . esc_attr( $item_icon ) . '"></i> ' : '';
			$item_output .= ( ! empty( $args->link_before ) ? $args->link_before : '' ) . $title . ( ! empty( $args->link_after ) ? $args->link_after : '' );
			$item_output .= '</a>';
			$item_output .= ! empty( $args->after ) ? $args->after : '';
		}

		/**
		 * Filter a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string $item_output The menu item's starting HTML output.
		 * @param object $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param array $args An array of {@see wp_nav_menu()} arguments.
		 */
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @see   Walker::end_el()
	 *
	 * @since 1.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Page data object. Not used.
	 * @param int $depth Depth of page. Not Used.
	 * @param array $args An array of arguments. @see wp_nav_menu()
	 */
	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
		if ( $depth == 1 && intval( $this->in_mega ) ) {
			$output .= "</div>\n";
			$output .= "</div>\n";
		} else {
			$output .= "</li>\n";
		}
	}
}