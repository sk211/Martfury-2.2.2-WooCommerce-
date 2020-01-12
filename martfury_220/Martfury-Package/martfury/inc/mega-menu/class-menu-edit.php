<?php
/**
 * Customize and add more fields for mega menu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Walker_Nav_Menu_Edit' ) ) {
	require_once ABSPATH . 'wp-admin/includes/nav-menu.php';
}

class Martfury_Mega_Menu_Walker_Edit extends Walker_Nav_Menu_Edit {
	/**
	 * Start the element output.
	 *
	 * @see   Walker_Nav_Menu::start_el()
	 * @since 3.0.0
	 *
	 * @global int $_wp_nav_menu_max_depth
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param array $args Not used.
	 * @param int $id Not used.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$item_icon           = get_post_meta( $item->ID, 'tamm_menu_item_icon', true );
		$item_icon_color           = get_post_meta( $item->ID, 'tamm_menu_item_icon_color', true );
		$item_hide_text      = get_post_meta( $item->ID, 'tamm_menu_item_hide_text', true );
		$item_uppercase_text = get_post_meta( $item->ID, 'tamm_menu_item_uppercase_text', true );
		$item_hot            = get_post_meta( $item->ID, 'tamm_menu_item_hot', true );
		$item_new            = get_post_meta( $item->ID, 'tamm_menu_item_new', true );
		$item_trending       = get_post_meta( $item->ID, 'tamm_menu_item_trending', true );
		$item_is_label       = get_post_meta( $item->ID, 'tamm_menu_item_is_label', true );
		$item_disable_link   = get_post_meta( $item->ID, 'tamm_menu_item_disable_link', true );
		$item_content        = get_post_meta( $item->ID, 'tamm_menu_item_content', true );
		$item_mega           = get_post_meta( $item->ID, 'tamm_menu_item_mega', true );
		$item_mega_style2    = get_post_meta( $item->ID, 'tamm_menu_item_mega_style2', true );
		$item_mega_width     = get_post_meta( $item->ID, 'tamm_menu_item_width', true );
		$mega_width          = get_post_meta( $item->ID, 'tamm_menu_item_mega_width', true );

		$item_mega_background = wp_parse_args(
			get_post_meta( $item->ID, 'tamm_menu_item_background', true ),
			array(
				'image'      => '',
				'color'      => '',
				'attachment' => 'scroll',
				'size'       => '',
				'repeat'     => 'no-repeat',
				'position'   => array(
					'x'      => 'left',
					'y'      => 'top',
					'custom' => array(
						'x' => '',
						'y' => '',
					)
				)
			)
		);

		$item_output = '';
		parent::start_el( $item_output, $item, $depth, $args );

		$dom                  = new DOMDocument();
		$dom->validateOnParse = true;
		$dom->loadHTML( mb_convert_encoding( $item_output, 'HTML-ENTITIES', 'UTF-8' ) );

		$xpath = new DOMXPath( $dom );

		// Remove spaces in href attribute
		$anchors = $xpath->query( "//a" );

		foreach ( array_reverse( iterator_to_array( $anchors ) ) as $anchor ) {
			$anchor->setAttribute( 'href', trim( $anchor->getAttribute( 'href' ) ) );
		}

		// Add more menu item data
		$settings = $xpath->query( "//*[@id='menu-item-settings-" . $item->ID . "']" )->item( 0 );

		if ( $settings ) {
			$data = $dom->createElement( 'span' );
			$data->setAttribute( 'class', 'hidden tamm-data' );
			$data->setAttribute( 'data-mega', intval( $item_mega ) );
			$data->setAttribute( 'data-mega-style2', intval( $item_mega_style2 ) );
			$data->setAttribute( 'data-mega_width', esc_attr( $mega_width ) );
			$data->setAttribute( 'data-width', esc_attr( $item_mega_width ) );
			$data->setAttribute( 'data-background', json_encode( $item_mega_background ) );
			$data->setAttribute( 'data-icon', esc_attr( $item_icon ) );
			$data->setAttribute( 'data-icon_color', esc_attr( $item_icon_color ) );
			$data->setAttribute( 'data-hide-text', intval( $item_hide_text ) );
			$data->setAttribute( 'data-uppercase-text', intval( $item_uppercase_text ) );
			$data->setAttribute( 'data-hot', intval( $item_hot ) );
			$data->setAttribute( 'data-new', intval( $item_new ) );
			$data->setAttribute( 'data-trending', intval( $item_trending ) );
			$data->setAttribute( 'data-disable-link', intval( $item_disable_link ) );
			$data->setAttribute( 'data-is-label', intval( $item_is_label ) );
			$data->nodeValue = $item_content;

			$settings->appendChild( $data );
		}

		// Add settings link
		$cancel = $xpath->query( "//*[@id='cancel-" . $item->ID . "']" )->item( 0 );

		if ( $cancel ) {
			$link            = $dom->createElement( 'a' );
			$link->nodeValue = esc_html__( 'Settings', 'martfury' );
			$link->setAttribute( 'class', 'item-config-mega opensettings submitcancel hide-if-no-js' );
			$link->setAttribute( 'href', '#' );
			$sep            = $dom->createElement( 'span' );
			$sep->nodeValue = ' | ';
			$sep->setAttribute( 'class', 'meta-sep hide-if-no-js' );

			$cancel->parentNode->insertBefore( $link, $cancel );
			$cancel->parentNode->insertBefore( $sep, $cancel );
		}


		$output .= $dom->saveHTML();
	}
}

class Martfury_Mega_Menu_Edit {
	/**
	 * Martfury_Mega_Menu_Edit constructor.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'admin_footer-nav-menus.php', array( $this, 'modal' ) );
		add_action( 'admin_footer-nav-menus.php', array( $this, 'templates' ) );
		add_action( 'wp_ajax_tamm_save_menu_item_data', array( $this, 'save_menu_item_data' ) );
	}

	/**
	 * Load scripts on Menus page only
	 *
	 * @param string $hook
	 */
	public function scripts( $hook ) {
		if ( 'nav-menus.php' !== $hook ) {
			return;
		}

		wp_register_style( 'martfury-mega-font-ionicons', get_template_directory_uri() . '/css/ionicons.min.css', array(), '2.0' );
		wp_register_style( 'martfury-mega-font-linearicons', get_template_directory_uri() . '/css/linearicons.min.css', array(), '1.0' );
		wp_register_style( 'martfury-mega-menu', get_template_directory_uri() . '/inc/mega-menu/css/mega-menu.css', array(
			'media-views',
			'wp-color-picker',
			'martfury-mega-font-ionicons',
			'martfury-mega-font-linearicons'
		), '20160530' );
		wp_enqueue_style( 'martfury-mega-menu' );

		wp_register_script( 'martfury-mega-menu', get_template_directory_uri() . '/inc/mega-menu/js/mega-menu.js', array(
			'jquery',
			'jquery-ui-resizable',
			'wp-util',
			'backbone',
			'underscore',
			'wp-color-picker'
		), '20160530', true );
		wp_enqueue_media();
		wp_enqueue_script( 'martfury-mega-menu' );
	}

	/**
	 * Prints HTML of modal on footer
	 */
	public function modal() {
		?>
        <div id="tamm-settings" tabindex="0" class="tamm-settings">
            <div class="tamm-modal media-modal wp-core-ui">
                <button type="button" class="button-link media-modal-close tamm-modal-close">
                    <span class="media-modal-icon"><span
                                class="screen-reader-text"><?php esc_html_e( 'Close', 'martfury' ) ?></span></span>
                </button>
                <div class="media-modal-content">
                    <div class="tamm-frame-menu media-frame-menu">
                        <div class="tamm-menu media-menu"></div>
                    </div>
                    <div class="tamm-frame-title media-frame-title"></div>
                    <div class="tamm-frame-content media-frame-content">
                        <div class="tamm-content">
                            <!--							<span class="spinner"></span>-->
                        </div>
                    </div>
                    <div class="tamm-frame-toolbar media-frame-toolbar">
                        <div class="tamm-toolbar media-toolbar">
                            <div class="tamm-toolbar-primary media-toolbar-primary search-form">
                                <button type="button"
                                        class="button tamm-button tamm-button-save media-button button-primary button-large"><?php esc_html_e( 'Save Changes', 'martfury' ) ?></button>
                                <button type="button"
                                        class="button tamm-button tamm-button-cancel media-button button-secondary button-large"><?php esc_html_e( 'Cancel', 'martfury' ) ?></button>
                                <span class="spinner"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="media-modal-backdrop tamm-modal-backdrop"></div>
        </div>
		<?php
	}

	/**
	 * Prints underscore template on footer
	 */
	public function templates() {
		$templates = apply_filters(
			'tamm_js_templates', array(
				'menus',
				'title',
				'mega',
				'background',
				'icon',
				'content',
				'general',
				'general_2'
			)
		);

		foreach ( $templates as $template ) {
			$file = apply_filters( 'tamm_js_template_path', plugin_dir_path( __FILE__ ) . 'tmpl/' . $template . '.php', $template );
			?>
            <script type="text/template" id="tmpl-tamm-<?php echo esc_attr( $template ) ?>">
				<?php
				if ( file_exists( $file ) ) {
					include $file;
				}
				?>
            </script>
			<?php
		}
	}

	/**
	 * Ajax function to save menu item data
	 */
	public function save_menu_item_data() {
		$_POST['data'] = stripslashes_deep( $_POST['data'] );
		parse_str( $_POST['data'], $data );


		$i = 0;
		// Save menu item data
		foreach ( $data['menu-item'] as $id => $meta ) {

			// Update meta value for checkboxes
			$keys = array_keys( $meta );

			if ( $i == 0 ) {
				if ( in_array( 'mega', $keys ) ) {
					update_post_meta( $id, 'tamm_menu_item_mega', true );
				} else {
					delete_post_meta( $id, 'tamm_menu_item_mega' );
				}

				if ( in_array( 'hideText', $keys ) ) {
					update_post_meta( $id, 'tamm_menu_item_hide_text', true );
				} else {
					delete_post_meta( $id, 'tamm_menu_item_hide_text' );
				}

				if ( in_array( 'visibleText', $keys ) ) {
					update_post_meta( $id, 'tamm_menu_item_visible_text', true );
				} else {
					delete_post_meta( $id, 'tamm_menu_item_visible_text' );
				}

				if ( in_array( 'hot', $keys ) ) {
					update_post_meta( $id, 'tamm_menu_item_hot', true );
				} else {
					delete_post_meta( $id, 'tamm_menu_item_hot' );
				}

				if ( in_array( 'new', $keys ) ) {
					update_post_meta( $id, 'tamm_menu_item_new', true );
				} else {
					delete_post_meta( $id, 'tamm_menu_item_new' );
				}

				if ( in_array( 'trending', $keys ) ) {
					update_post_meta( $id, 'tamm_menu_item_trending', true );
				} else {
					delete_post_meta( $id, 'tamm_menu_item_trending' );
				}

				if ( in_array( 'isLabel', $keys ) ) {
					update_post_meta( $id, 'tamm_menu_item_is_label', true );
				} else {
					delete_post_meta( $id, 'tamm_menu_item_is_label' );
				}
			}

			foreach ( $meta as $key => $value ) {
				$key = str_replace( '-', '_', $key );
				update_post_meta( $id, 'tamm_menu_item_' . $key, $value );
			}

			$i ++;
		}


		do_action( 'martfury_save_menu_item_data', $data );

		wp_send_json_success( $data );
	}
}
