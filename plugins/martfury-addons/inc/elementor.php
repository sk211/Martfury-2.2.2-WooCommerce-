<?php

namespace MartfuryAddons;

/**
 * Integrate with Elementor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Elementor {
	/**
	 * Instance
	 *
	 * @access private
	 */
	private static $_instance = null;


	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Martfury_Addons_Elementor An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		$this->_includes();
		$this->add_actions();
	}

	/**
	 * Auto load widgets
	 */
	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		if ( false === strpos( $class, 'Widgets' ) ) {
			return;
		}

		$path     = explode( '\\', $class );
		$filename = strtolower( array_pop( $path ) );

		if ( 'Widgets' != array_pop( $path ) ) {
			return;
		}

		$filename = str_replace( '_', '-', $filename );
		$filename = MARTFURY_ADDONS_DIR . 'inc/elementor-widgets/' . $filename . '.php';

		if ( is_readable( $filename ) ) {
			include( $filename );
		}
	}

	/**
	 * Includes files which are not widgets
	 */
	private function _includes() {
	}

	/**
	 * Hooks to init
	 */
	protected function add_actions() {
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'styles' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'scripts' ] );

		add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

		add_action( 'elementor/elements/categories_registered', [ $this, 'add_category' ] );

		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_styles' ] );

		add_action( 'post_class', [ $this, 'get_product_classes' ], 20, 3 );

		add_action( 'martfury_woo_after_shop_loop_item', [ $this, 'deal_progress' ], 7 );

		add_action( 'wc_ajax_nopriv_mf_elementor_load_products', [ $this, 'elementor_load_products' ] );
		add_action( 'wc_ajax_mf_elementor_load_products', [ $this, 'elementor_load_products' ] );
	}

	public function get_product_classes( $classes, $class, $post_id ) {
		if ( is_admin() && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			$post      = get_post( $post_id );
			$classes[] = $post->post_type;
		}

		return $classes;
	}

	/**
	 * Register styles
	 */
	public function styles() {
	}

	/**
	 * Register styles
	 */
	public function scripts() {
		wp_register_script( 'martfury-elementor', MARTFURY_ADDONS_URL . '/assets/js/elementor.js', array( 'jquery' ), '20170530', true );
	}


	/**
	 * Register styles
	 */
	public function editor_styles() {
		wp_enqueue_style( 'linearicons', MARTFURY_ADDONS_URL . 'assets/css/linearicons.min.css', array(), '1.0.0' );
	}

	/**
	 * Init Controls
	 */
	public function init_controls( $controls_registry ) {
		$this->modify_icons_controls( $controls_registry );

		add_action( 'elementor/icons_manager/additional_tabs', [ $this, 'elementor_custom_icons' ] );
	}

	/**
	 * Init Widgets
	 */
	public function init_widgets() {
		$widgets_manager = \Elementor\Plugin::instance()->widgets_manager;

		// Products
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Product_Deals_Carousel() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Product_Deals_Carousel_2() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Product_Deals_Grid() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Newsletter() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Products_Of_Category() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Products_Of_Category_2() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Product_Tabs_Carousel() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Product_Tabs_Grid() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Products_List_Carousel() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Products_Carousel() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Products_Grid() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Products_List() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Recently_Viewed_Products() );

		// Banners
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Banner_Small() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Banner_Medium() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Banner_Large() );

		// Images
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Image_Box() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Images_Grid() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Images_Carousel() );

		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Icons_List() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Category_Tabs() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Category_Box() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Post_Grid() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Testimonial_Slides() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\FAQs() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Contact_Form_7() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Counter() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Countdown() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Journey() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Member() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Icon_Box() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Process() );
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Bubbles() );

		// Slides
		$widgets_manager->register_widget_type( new \MartfuryAddons\Elementor\Widgets\Slides() );
	}

	/**
	 * Add Martfury category
	 */
	public function add_category( $elements_manager ) {
		$elements_manager->add_category(
			'martfury',
			[
				'title' => esc_html__( 'Martfury', 'martfury' )
			]
		);
	}

	/**
	 * Adding custom icon to icon control in Elementor
	 */
	public function modify_icons_controls( $controls_registry ) {
		// Get existing icons
		$icons = $controls_registry->get_control( 'icon' )->get_settings( 'options' );
		// Append new icons
		$new_icons = array_merge(
			array(
				'icon-home'                         => 'home',
				'icon-home2'                        => 'home2',
				'icon-home3'                        => 'home3',
				'icon-home4'                        => 'home4',
				'icon-home5'                        => 'home5',
				'icon-home6'                        => 'home6',
				'icon-bathtub'                      => 'bathtub',
				'icon-toothbrush'                   => 'toothbrush',
				'icon-bed'                          => 'bed',
				'icon-couch'                        => 'couch',
				'icon-chair'                        => 'chair',
				'icon-city'                         => 'city',
				'icon-apartment'                    => 'apartment',
				'icon-pencil'                       => 'pencil',
				'icon-pencil2'                      => 'pencil2',
				'icon-pen'                          => 'pen',
				'icon-pencil3'                      => 'pencil3',
				'icon-eraser'                       => 'eraser',
				'icon-pencil4'                      => 'pencil4',
				'icon-pencil5'                      => 'pencil5',
				'icon-feather'                      => 'feather',
				'icon-feather2'                     => 'feather2',
				'icon-feather3'                     => 'feather3',
				'icon-pen2'                         => 'pen2',
				'icon-pen-add'                      => 'pen-add',
				'icon-pen-remove'                   => 'pen-remove',
				'icon-vector'                       => 'vector',
				'icon-pen3'                         => 'pen3',
				'icon-blog'                         => 'blog',
				'icon-brush'                        => 'brush',
				'icon-brush2'                       => 'brush2',
				'icon-spray'                        => 'spray',
				'icon-paint-roller'                 => 'paint-roller',
				'icon-stamp'                        => 'stamp',
				'icon-tape'                         => 'tape',
				'icon-desk-tape'                    => 'desk-tape',
				'icon-texture'                      => 'texture',
				'icon-eye-dropper'                  => 'eye-dropper',
				'icon-palette'                      => 'palette',
				'icon-color-sampler'                => 'color-sampler',
				'icon-bucket'                       => 'bucket',
				'icon-gradient'                     => 'gradient',
				'icon-gradient2'                    => 'gradient2',
				'icon-magic-wand'                   => 'magic-wand',
				'icon-magnet'                       => 'magnet',
				'icon-pencil-ruler'                 => 'pencil-ruler',
				'icon-pencil-ruler2'                => 'pencil-ruler2',
				'icon-compass'                      => 'compass',
				'icon-aim'                          => 'aim',
				'icon-gun'                          => 'gun',
				'icon-bottle'                       => 'bottle',
				'icon-drop'                         => 'drop',
				'icon-drop-crossed'                 => 'drop-crossed',
				'icon-drop2'                        => 'drop2',
				'icon-snow'                         => 'snow',
				'icon-snow2'                        => 'snow2',
				'icon-fire'                         => 'fire',
				'icon-lighter'                      => 'lighter',
				'icon-knife'                        => 'knife',
				'icon-dagger'                       => 'dagger',
				'icon-tissue'                       => 'tissue',
				'icon-toilet-paper'                 => 'toilet-paper',
				'icon-poop'                         => 'poop',
				'icon-umbrella'                     => 'umbrella',
				'icon-umbrella2'                    => 'umbrella2',
				'icon-rain'                         => 'rain',
				'icon-tornado'                      => 'tornado',
				'icon-wind'                         => 'wind',
				'icon-fan'                          => 'fan',
				'icon-contrast'                     => 'contrast',
				'icon-sun-small'                    => 'sun-small',
				'icon-sun'                          => 'sun',
				'icon-sun2'                         => 'sun2',
				'icon-moon'                         => 'moon',
				'icon-cloud'                        => 'cloud',
				'icon-cloud-upload'                 => 'cloud-upload',
				'icon-cloud-download'               => 'cloud-download',
				'icon-cloud-rain'                   => 'cloud-rain',
				'icon-cloud-hailstones'             => 'cloud-hailstones',
				'icon-cloud-snow'                   => 'cloud-snow',
				'icon-cloud-windy'                  => 'cloud-windy',
				'icon-sun-wind'                     => 'sun-wind',
				'icon-cloud-fog'                    => 'cloud-fog',
				'icon-cloud-sun'                    => 'cloud-sun',
				'icon-cloud-lightning'              => 'cloud-lightning',
				'icon-cloud-sync'                   => 'cloud-sync',
				'icon-cloud-lock'                   => 'cloud-lock',
				'icon-cloud-gear'                   => 'cloud-gear',
				'icon-cloud-alert'                  => 'cloud-alert',
				'icon-cloud-check'                  => 'cloud-check',
				'icon-cloud-cross'                  => 'cloud-cross',
				'icon-cloud-crossed'                => 'cloud-crossed',
				'icon-cloud-database'               => 'cloud-database',
				'icon-database'                     => 'database',
				'icon-database-add'                 => 'database-add',
				'icon-database-remove'              => 'database-remove',
				'icon-database-lock'                => 'database-lock',
				'icon-database-refresh'             => 'database-refresh',
				'icon-database-check'               => 'database-check',
				'icon-database-history'             => 'database-history',
				'icon-database-upload'              => 'database-upload',
				'icon-database-download'            => 'database-download',
				'icon-server'                       => 'server',
				'icon-shield'                       => 'shield',
				'icon-shield-check'                 => 'shield-check',
				'icon-shield-alert'                 => 'shield-alert',
				'icon-shield-cross'                 => 'shield-cross',
				'icon-lock'                         => 'lock',
				'icon-rotation-lock'                => 'rotation-lock',
				'icon-unlock'                       => 'unlock',
				'icon-key'                          => 'key',
				'icon-key-hole'                     => 'key-hole',
				'icon-toggle-off'                   => 'toggle-off',
				'icon-toggle-on'                    => 'toggle-on',
				'icon-cog'                          => 'cog',
				'icon-cog2'                         => 'cog2',
				'icon-wrench'                       => 'wrench',
				'icon-screwdriver'                  => 'screwdriver',
				'icon-hammer-wrench'                => 'hammer-wrench',
				'icon-hammer'                       => 'hammer',
				'icon-saw'                          => 'saw',
				'icon-axe'                          => 'axe',
				'icon-axe2'                         => 'axe2',
				'icon-shovel'                       => 'shovel',
				'icon-pickaxe'                      => 'pickaxe',
				'icon-factory'                      => 'factory',
				'icon-factory2'                     => 'factory2',
				'icon-recycle'                      => 'recycle',
				'icon-trash'                        => 'trash',
				'icon-trash2'                       => 'trash2',
				'icon-trash3'                       => 'trash3',
				'icon-broom'                        => 'broom',
				'icon-game'                         => 'game',
				'icon-gamepad'                      => 'gamepad',
				'icon-joystick'                     => 'joystick',
				'icon-dice'                         => 'dice',
				'icon-spades'                       => 'spades',
				'icon-diamonds'                     => 'diamonds',
				'icon-clubs'                        => 'clubs',
				'icon-hearts'                       => 'hearts',
				'icon-heart'                        => 'heart',
				'icon-star'                         => 'star',
				'icon-star-half'                    => 'star-half',
				'icon-star-empty'                   => 'star-empty',
				'icon-flag'                         => 'flag',
				'icon-flag2'                        => 'flag2',
				'icon-flag3'                        => 'flag3',
				'icon-mailbox-full'                 => 'mailbox-full',
				'icon-mailbox-empty'                => 'mailbox-empty',
				'icon-at-sign'                      => 'at-sign',
				'icon-envelope'                     => 'envelope',
				'icon-envelope-open'                => 'envelope-open',
				'icon-paperclip'                    => 'paperclip',
				'icon-paper-plane'                  => 'paper-plane',
				'icon-reply'                        => 'reply',
				'icon-reply-all'                    => 'reply-all',
				'icon-inbox'                        => 'inbox',
				'icon-inbox2'                       => 'inbox2',
				'icon-outbox'                       => 'outbox',
				'icon-box'                          => 'box',
				'icon-archive'                      => 'archive',
				'icon-archive2'                     => 'archive2',
				'icon-drawers'                      => 'drawers',
				'icon-drawers2'                     => 'drawers2',
				'icon-drawers3'                     => 'drawers3',
				'icon-eye'                          => 'eye',
				'icon-eye-crossed'                  => 'eye-crossed',
				'icon-eye-plus'                     => 'eye-plus',
				'icon-eye-minus'                    => 'eye-minus',
				'icon-binoculars'                   => 'binoculars',
				'icon-binoculars2'                  => 'binoculars2',
				'icon-hdd'                          => 'hdd',
				'icon-hdd-down'                     => 'hdd-down',
				'icon-hdd-up'                       => 'hdd-up',
				'icon-floppy-disk'                  => 'floppy-disk',
				'icon-disc'                         => 'disc',
				'icon-tape2'                        => 'tape2',
				'icon-printer'                      => 'printer',
				'icon-shredder'                     => 'shredder',
				'icon-file-empty'                   => 'file-empty',
				'icon-file-add'                     => 'file-add',
				'icon-file-check'                   => 'file-check',
				'icon-file-lock'                    => 'file-lock',
				'icon-files'                        => 'files',
				'icon-copy'                         => 'copy',
				'icon-compare'                      => 'compare',
				'icon-folder'                       => 'folder',
				'icon-folder-search'                => 'folder-search',
				'icon-folder-plus'                  => 'folder-plus',
				'icon-folder-minus'                 => 'folder-minus',
				'icon-folder-download'              => 'folder-download',
				'icon-folder-upload'                => 'folder-upload',
				'icon-folder-star'                  => 'folder-star',
				'icon-folder-heart'                 => 'folder-heart',
				'icon-folder-user'                  => 'folder-user',
				'icon-folder-shared'                => 'folder-shared',
				'icon-folder-music'                 => 'folder-music',
				'icon-folder-picture'               => 'folder-picture',
				'icon-folder-film'                  => 'folder-film',
				'icon-scissors'                     => 'scissors',
				'icon-paste'                        => 'paste',
				'icon-clipboard-empty'              => 'clipboard-empty',
				'icon-clipboard-pencil'             => 'clipboard-pencil',
				'icon-clipboard-text'               => 'clipboard-text',
				'icon-clipboard-check'              => 'clipboard-check',
				'icon-clipboard-down'               => 'clipboard-down',
				'icon-clipboard-left'               => 'clipboard-left',
				'icon-clipboard-alert'              => 'clipboard-alert',
				'icon-clipboard-user'               => 'clipboard-user',
				'icon-register'                     => 'register',
				'icon-enter'                        => 'enter',
				'icon-exit'                         => 'exit',
				'icon-papers'                       => 'papers',
				'icon-news'                         => 'news',
				'icon-reading'                      => 'reading',
				'icon-typewriter'                   => 'typewriter',
				'icon-document'                     => 'document',
				'icon-document2'                    => 'document2',
				'icon-graduation-hat'               => 'graduation-hat',
				'icon-license'                      => 'license',
				'icon-license2'                     => 'license2',
				'icon-medal-empty'                  => 'medal-empty',
				'icon-medal-first'                  => 'medal-first',
				'icon-medal-second'                 => 'medal-second',
				'icon-medal-third'                  => 'medal-third',
				'icon-podium'                       => 'podium',
				'icon-trophy'                       => 'trophy',
				'icon-trophy2'                      => 'trophy2',
				'icon-music-note'                   => 'music-note',
				'icon-music-note2'                  => 'music-note2',
				'icon-music-note3'                  => 'music-note3',
				'icon-playlist'                     => 'playlist',
				'icon-playlist-add'                 => 'playlist-add',
				'icon-guitar'                       => 'guitar',
				'icon-trumpet'                      => 'trumpet',
				'icon-album'                        => 'album',
				'icon-shuffle'                      => 'shuffle',
				'icon-repeat-one'                   => 'repeat-one',
				'icon-repeat'                       => 'repeat',
				'icon-headphones'                   => 'headphones',
				'icon-headset'                      => 'headset',
				'icon-loudspeaker'                  => 'loudspeaker',
				'icon-equalizer'                    => 'equalizer',
				'icon-theater'                      => 'theater',
				'icon-3d-glasses'                   => '3d-glasses',
				'icon-ticket'                       => 'ticket',
				'icon-presentation'                 => 'presentation',
				'icon-play'                         => 'play',
				'icon-film-play'                    => 'film-play',
				'icon-clapboard-play'               => 'clapboard-play',
				'icon-media'                        => 'media',
				'icon-film'                         => 'film',
				'icon-film2'                        => 'film2',
				'icon-surveillance'                 => 'surveillance',
				'icon-surveillance2'                => 'surveillance2',
				'icon-camera'                       => 'camera',
				'icon-camera-crossed'               => 'camera-crossed',
				'icon-camera-play'                  => 'camera-play',
				'icon-time-lapse'                   => 'time-lapse',
				'icon-record'                       => 'record',
				'icon-camera2'                      => 'camera2',
				'icon-camera-flip'                  => 'camera-flip',
				'icon-panorama'                     => 'panorama',
				'icon-time-lapse2'                  => 'time-lapse2',
				'icon-shutter'                      => 'shutter',
				'icon-shutter2'                     => 'shutter2',
				'icon-face-detection'               => 'face-detection',
				'icon-flare'                        => 'flare',
				'icon-convex'                       => 'convex',
				'icon-concave'                      => 'concave',
				'icon-picture'                      => 'picture',
				'icon-picture2'                     => 'picture2',
				'icon-picture3'                     => 'picture3',
				'icon-pictures'                     => 'pictures',
				'icon-book'                         => 'book',
				'icon-audio-book'                   => 'audio-book',
				'icon-book2'                        => 'book2',
				'icon-bookmark'                     => 'bookmark',
				'icon-bookmark2'                    => 'bookmark2',
				'icon-label'                        => 'label',
				'icon-library'                      => 'library',
				'icon-library2'                     => 'library2',
				'icon-contacts'                     => 'contacts',
				'icon-profile'                      => 'profile',
				'icon-portrait'                     => 'portrait',
				'icon-portrait2'                    => 'portrait2',
				'icon-user'                         => 'user',
				'icon-user-plus'                    => 'user-plus',
				'icon-user-minus'                   => 'user-minus',
				'icon-user-lock'                    => 'user-lock',
				'icon-users'                        => 'users',
				'icon-users2'                       => 'users2',
				'icon-users-plus'                   => 'users-plus',
				'icon-users-minus'                  => 'users-minus',
				'icon-group-work'                   => 'group-work',
				'icon-woman'                        => 'woman',
				'icon-man'                          => 'man',
				'icon-baby'                         => 'baby',
				'icon-baby2'                        => 'baby2',
				'icon-baby3'                        => 'baby3',
				'icon-baby-bottle'                  => 'baby-bottle',
				'icon-walk'                         => 'walk',
				'icon-hand-waving'                  => 'hand-waving',
				'icon-jump'                         => 'jump',
				'icon-run'                          => 'run',
				'icon-woman2'                       => 'woman2',
				'icon-man2'                         => 'man2',
				'icon-man-woman'                    => 'man-woman',
				'icon-height'                       => 'height',
				'icon-weight'                       => 'weight',
				'icon-scale'                        => 'scale',
				'icon-button'                       => 'button',
				'icon-bow-tie'                      => 'bow-tie',
				'icon-tie'                          => 'tie',
				'icon-socks'                        => 'socks',
				'icon-shoe'                         => 'shoe',
				'icon-shoes'                        => 'shoes',
				'icon-hat'                          => 'hat',
				'icon-pants'                        => 'pants',
				'icon-shorts'                       => 'shorts',
				'icon-flip-flops'                   => 'flip-flops',
				'icon-shirt'                        => 'shirt',
				'icon-hanger'                       => 'hanger',
				'icon-laundry'                      => 'laundry',
				'icon-store'                        => 'store',
				'icon-haircut'                      => 'haircut',
				'icon-store-24'                     => 'store-24',
				'icon-barcode'                      => 'barcode',
				'icon-barcode2'                     => 'barcode2',
				'icon-barcode3'                     => 'barcode3',
				'icon-cashier'                      => 'cashier',
				'icon-bag'                          => 'bag',
				'icon-bag2'                         => 'bag2',
				'icon-cart'                         => 'cart',
				'icon-cart-empty'                   => 'cart-empty',
				'icon-cart-full'                    => 'cart-full',
				'icon-cart-plus'                    => 'cart-plus',
				'icon-cart-plus2'                   => 'cart-plus2',
				'icon-cart-add'                     => 'cart-add',
				'icon-cart-remove'                  => 'cart-remove',
				'icon-cart-exchange'                => 'cart-exchange',
				'icon-tag'                          => 'tag',
				'icon-tags'                         => 'tags',
				'icon-receipt'                      => 'receipt',
				'icon-wallet'                       => 'wallet',
				'icon-credit-card'                  => 'credit-card',
				'icon-cash-dollar'                  => 'cash-dollar',
				'icon-cash-euro'                    => 'cash-euro',
				'icon-cash-pound'                   => 'cash-pound',
				'icon-cash-yen'                     => 'cash-yen',
				'icon-bag-dollar'                   => 'bag-dollar',
				'icon-bag-euro'                     => 'bag-euro',
				'icon-bag-pound'                    => 'bag-pound',
				'icon-bag-yen'                      => 'bag-yen',
				'icon-coin-dollar'                  => 'coin-dollar',
				'icon-coin-euro'                    => 'coin-euro',
				'icon-coin-pound'                   => 'coin-pound',
				'icon-coin-yen'                     => 'coin-yen',
				'icon-calculator'                   => 'calculator',
				'icon-calculator2'                  => 'calculator2',
				'icon-abacus'                       => 'abacus',
				'icon-vault'                        => 'vault',
				'icon-telephone'                    => 'telephone',
				'icon-phone-lock'                   => 'phone-lock',
				'icon-phone-wave'                   => 'phone-wave',
				'icon-phone-pause'                  => 'phone-pause',
				'icon-phone-outgoing'               => 'phone-outgoing',
				'icon-phone-incoming'               => 'phone-incoming',
				'icon-phone-in-out'                 => 'phone-in-out',
				'icon-phone-error'                  => 'phone-error',
				'icon-phone-sip'                    => 'phone-sip',
				'icon-phone-plus'                   => 'phone-plus',
				'icon-phone-minus'                  => 'phone-minus',
				'icon-voicemail'                    => 'voicemail',
				'icon-dial'                         => 'dial',
				'icon-telephone2'                   => 'telephone2',
				'icon-pushpin'                      => 'pushpin',
				'icon-pushpin2'                     => 'pushpin2',
				'icon-map-marker'                   => 'map-marker',
				'icon-map-marker-user'              => 'map-marker-user',
				'icon-map-marker-down'              => 'map-marker-down',
				'icon-map-marker-check'             => 'map-marker-check',
				'icon-map-marker-crossed'           => 'map-marker-crossed',
				'icon-radar'                        => 'radar',
				'icon-compass2'                     => 'compass2',
				'icon-map'                          => 'map',
				'icon-map2'                         => 'map2',
				'icon-location'                     => 'location',
				'icon-road-sign'                    => 'road-sign',
				'icon-calendar-empty'               => 'calendar-empty',
				'icon-calendar-check'               => 'calendar-check',
				'icon-calendar-cross'               => 'calendar-cross',
				'icon-calendar-31'                  => 'calendar-31',
				'icon-calendar-full'                => 'calendar-full',
				'icon-calendar-insert'              => 'calendar-insert',
				'icon-calendar-text'                => 'calendar-text',
				'icon-calendar-user'                => 'calendar-user',
				'icon-mouse'                        => 'mouse',
				'icon-mouse-left'                   => 'mouse-left',
				'icon-mouse-right'                  => 'mouse-right',
				'icon-mouse-both'                   => 'mouse-both',
				'icon-keyboard'                     => 'keyboard',
				'icon-keyboard-up'                  => 'keyboard-up',
				'icon-keyboard-down'                => 'keyboard-down',
				'icon-delete'                       => 'delete',
				'icon-spell-check'                  => 'spell-check',
				'icon-escape'                       => 'escape',
				'icon-enter2'                       => 'enter2',
				'icon-screen'                       => 'screen',
				'icon-aspect-ratio'                 => 'aspect-ratio',
				'icon-signal'                       => 'signal',
				'icon-signal-lock'                  => 'signal-lock',
				'icon-signal-80'                    => 'signal-80',
				'icon-signal-60'                    => 'signal-60',
				'icon-signal-40'                    => 'signal-40',
				'icon-signal-20'                    => 'signal-20',
				'icon-signal-0'                     => 'signal-0',
				'icon-signal-blocked'               => 'signal-blocked',
				'icon-sim'                          => 'sim',
				'icon-flash-memory'                 => 'flash-memory',
				'icon-usb-drive'                    => 'usb-drive',
				'icon-phone'                        => 'phone',
				'icon-smartphone'                   => 'smartphone',
				'icon-smartphone-notification'      => 'smartphone-notification',
				'icon-smartphone-vibration'         => 'smartphone-vibration',
				'icon-smartphone-embed'             => 'smartphone-embed',
				'icon-smartphone-waves'             => 'smartphone-waves',
				'icon-tablet'                       => 'tablet',
				'icon-tablet2'                      => 'tablet2',
				'icon-laptop'                       => 'laptop',
				'icon-laptop-phone'                 => 'laptop-phone',
				'icon-desktop'                      => 'desktop',
				'icon-launch'                       => 'launch',
				'icon-new-tab'                      => 'new-tab',
				'icon-window'                       => 'window',
				'icon-cable'                        => 'cable',
				'icon-cable2'                       => 'cable2',
				'icon-tv'                           => 'tv',
				'icon-radio'                        => 'radio',
				'icon-remote-control'               => 'remote-control',
				'icon-power-switch'                 => 'power-switch',
				'icon-power'                        => 'power',
				'icon-power-crossed'                => 'power-crossed',
				'icon-flash-auto'                   => 'flash-auto',
				'icon-lamp'                         => 'lamp',
				'icon-flashlight'                   => 'flashlight',
				'icon-lampshade'                    => 'lampshade',
				'icon-cord'                         => 'cord',
				'icon-outlet'                       => 'outlet',
				'icon-battery-power'                => 'battery-power',
				'icon-battery-empty'                => 'battery-empty',
				'icon-battery-alert'                => 'battery-alert',
				'icon-battery-error'                => 'battery-error',
				'icon-battery-low1'                 => 'battery-low1',
				'icon-battery-low2'                 => 'battery-low2',
				'icon-battery-low3'                 => 'battery-low3',
				'icon-battery-mid1'                 => 'battery-mid1',
				'icon-battery-mid2'                 => 'battery-mid2',
				'icon-battery-mid3'                 => 'battery-mid3',
				'icon-battery-full'                 => 'battery-full',
				'icon-battery-charging'             => 'battery-charging',
				'icon-battery-charging2'            => 'battery-charging2',
				'icon-battery-charging3'            => 'battery-charging3',
				'icon-battery-charging4'            => 'battery-charging4',
				'icon-battery-charging5'            => 'battery-charging5',
				'icon-battery-charging6'            => 'battery-charging6',
				'icon-battery-charging7'            => 'battery-charging7',
				'icon-chip'                         => 'chip',
				'icon-chip-x64'                     => 'chip-x64',
				'icon-chip-x86'                     => 'chip-x86',
				'icon-bubble'                       => 'bubble',
				'icon-bubbles'                      => 'bubbles',
				'icon-bubble-dots'                  => 'bubble-dots',
				'icon-bubble-alert'                 => 'bubble-alert',
				'icon-bubble-question'              => 'bubble-question',
				'icon-bubble-text'                  => 'bubble-text',
				'icon-bubble-pencil'                => 'bubble-pencil',
				'icon-bubble-picture'               => 'bubble-picture',
				'icon-bubble-video'                 => 'bubble-video',
				'icon-bubble-user'                  => 'bubble-user',
				'icon-bubble-quote'                 => 'bubble-quote',
				'icon-bubble-heart'                 => 'bubble-heart',
				'icon-bubble-emoticon'              => 'bubble-emoticon',
				'icon-bubble-attachment'            => 'bubble-attachment',
				'icon-phone-bubble'                 => 'phone-bubble',
				'icon-quote-open'                   => 'quote-open',
				'icon-quote-close'                  => 'quote-close',
				'icon-dna'                          => 'dna',
				'icon-heart-pulse'                  => 'heart-pulse',
				'icon-pulse'                        => 'pulse',
				'icon-syringe'                      => 'syringe',
				'icon-pills'                        => 'pills',
				'icon-first-aid'                    => 'first-aid',
				'icon-lifebuoy'                     => 'lifebuoy',
				'icon-bandage'                      => 'bandage',
				'icon-bandages'                     => 'bandages',
				'icon-thermometer'                  => 'thermometer',
				'icon-microscope'                   => 'microscope',
				'icon-brain'                        => 'brain',
				'icon-beaker'                       => 'beaker',
				'icon-skull'                        => 'skull',
				'icon-bone'                         => 'bone',
				'icon-construction'                 => 'construction',
				'icon-construction-cone'            => 'construction-cone',
				'icon-pie-chart'                    => 'pie-chart',
				'icon-pie-chart2'                   => 'pie-chart2',
				'icon-graph'                        => 'graph',
				'icon-chart-growth'                 => 'chart-growth',
				'icon-chart-bars'                   => 'chart-bars',
				'icon-chart-settings'               => 'chart-settings',
				'icon-cake'                         => 'cake',
				'icon-gift'                         => 'gift',
				'icon-balloon'                      => 'balloon',
				'icon-rank'                         => 'rank',
				'icon-rank2'                        => 'rank2',
				'icon-rank3'                        => 'rank3',
				'icon-crown'                        => 'crown',
				'icon-lotus'                        => 'lotus',
				'icon-diamond'                      => 'diamond',
				'icon-diamond2'                     => 'diamond2',
				'icon-diamond3'                     => 'diamond3',
				'icon-diamond4'                     => 'diamond4',
				'icon-linearicons'                  => 'linearicons',
				'icon-teacup'                       => 'teacup',
				'icon-teapot'                       => 'teapot',
				'icon-glass'                        => 'glass',
				'icon-bottle2'                      => 'bottle2',
				'icon-glass-cocktail'               => 'glass-cocktail',
				'icon-glass2'                       => 'glass2',
				'icon-dinner'                       => 'dinner',
				'icon-dinner2'                      => 'dinner2',
				'icon-chef'                         => 'chef',
				'icon-scale2'                       => 'scale2',
				'icon-egg'                          => 'egg',
				'icon-egg2'                         => 'egg2',
				'icon-eggs'                         => 'eggs',
				'icon-platter'                      => 'platter',
				'icon-steak'                        => 'steak',
				'icon-hamburger'                    => 'hamburger',
				'icon-hotdog'                       => 'hotdog',
				'icon-pizza'                        => 'pizza',
				'icon-sausage'                      => 'sausage',
				'icon-chicken'                      => 'chicken',
				'icon-fish'                         => 'fish',
				'icon-carrot'                       => 'carrot',
				'icon-cheese'                       => 'cheese',
				'icon-bread'                        => 'bread',
				'icon-ice-cream'                    => 'ice-cream',
				'icon-ice-cream2'                   => 'ice-cream2',
				'icon-candy'                        => 'candy',
				'icon-lollipop'                     => 'lollipop',
				'icon-coffee-bean'                  => 'coffee-bean',
				'icon-coffee-cup'                   => 'coffee-cup',
				'icon-cherry'                       => 'cherry',
				'icon-grapes'                       => 'grapes',
				'icon-citrus'                       => 'citrus',
				'icon-apple'                        => 'apple',
				'icon-leaf'                         => 'leaf',
				'icon-landscape'                    => 'landscape',
				'icon-pine-tree'                    => 'pine-tree',
				'icon-tree'                         => 'tree',
				'icon-cactus'                       => 'cactus',
				'icon-paw'                          => 'paw',
				'icon-footprint'                    => 'footprint',
				'icon-speed-slow'                   => 'speed-slow',
				'icon-speed-medium'                 => 'speed-medium',
				'icon-speed-fast'                   => 'speed-fast',
				'icon-rocket'                       => 'rocket',
				'icon-hammer2'                      => 'hammer2',
				'icon-balance'                      => 'balance',
				'icon-briefcase'                    => 'briefcase',
				'icon-luggage-weight'               => 'luggage-weight',
				'icon-dolly'                        => 'dolly',
				'icon-plane'                        => 'plane',
				'icon-plane-crossed'                => 'plane-crossed',
				'icon-helicopter'                   => 'helicopter',
				'icon-traffic-lights'               => 'traffic-lights',
				'icon-siren'                        => 'siren',
				'icon-road'                         => 'road',
				'icon-engine'                       => 'engine',
				'icon-oil-pressure'                 => 'oil-pressure',
				'icon-coolant-temperature'          => 'coolant-temperature',
				'icon-car-battery'                  => 'car-battery',
				'icon-gas'                          => 'gas',
				'icon-gallon'                       => 'gallon',
				'icon-transmission'                 => 'transmission',
				'icon-car'                          => 'car',
				'icon-car-wash'                     => 'car-wash',
				'icon-car-wash2'                    => 'car-wash2',
				'icon-bus'                          => 'bus',
				'icon-bus2'                         => 'bus2',
				'icon-car2'                         => 'car2',
				'icon-parking'                      => 'parking',
				'icon-car-lock'                     => 'car-lock',
				'icon-taxi'                         => 'taxi',
				'icon-car-siren'                    => 'car-siren',
				'icon-car-wash3'                    => 'car-wash3',
				'icon-car-wash4'                    => 'car-wash4',
				'icon-ambulance'                    => 'ambulance',
				'icon-truck'                        => 'truck',
				'icon-trailer'                      => 'trailer',
				'icon-scale-truck'                  => 'scale-truck',
				'icon-train'                        => 'train',
				'icon-ship'                         => 'ship',
				'icon-ship2'                        => 'ship2',
				'icon-anchor'                       => 'anchor',
				'icon-boat'                         => 'boat',
				'icon-bicycle'                      => 'bicycle',
				'icon-bicycle2'                     => 'bicycle2',
				'icon-dumbbell'                     => 'dumbbell',
				'icon-bench-press'                  => 'bench-press',
				'icon-swim'                         => 'swim',
				'icon-football'                     => 'football',
				'icon-baseball-bat'                 => 'baseball-bat',
				'icon-baseball'                     => 'baseball',
				'icon-tennis'                       => 'tennis',
				'icon-tennis2'                      => 'tennis2',
				'icon-ping-pong'                    => 'ping-pong',
				'icon-hockey'                       => 'hockey',
				'icon-8ball'                        => '8ball',
				'icon-bowling'                      => 'bowling',
				'icon-bowling-pins'                 => 'bowling-pins',
				'icon-golf'                         => 'golf',
				'icon-golf2'                        => 'golf2',
				'icon-archery'                      => 'archery',
				'icon-slingshot'                    => 'slingshot',
				'icon-soccer'                       => 'soccer',
				'icon-basketball'                   => 'basketball',
				'icon-cube'                         => 'cube',
				'icon-3d-rotate'                    => '3d-rotate',
				'icon-puzzle'                       => 'puzzle',
				'icon-glasses'                      => 'glasses',
				'icon-glasses2'                     => 'glasses2',
				'icon-accessibility'                => 'accessibility',
				'icon-wheelchair'                   => 'wheelchair',
				'icon-wall'                         => 'wall',
				'icon-fence'                        => 'fence',
				'icon-wall2'                        => 'wall2',
				'icon-icons'                        => 'icons',
				'icon-resize-handle'                => 'resize-handle',
				'icon-icons2'                       => 'icons2',
				'icon-select'                       => 'select',
				'icon-select2'                      => 'select2',
				'icon-site-map'                     => 'site-map',
				'icon-earth'                        => 'earth',
				'icon-earth-lock'                   => 'earth-lock',
				'icon-network'                      => 'network',
				'icon-network-lock'                 => 'network-lock',
				'icon-planet'                       => 'planet',
				'icon-happy'                        => 'happy',
				'icon-smile'                        => 'smile',
				'icon-grin'                         => 'grin',
				'icon-tongue'                       => 'tongue',
				'icon-sad'                          => 'sad',
				'icon-wink'                         => 'wink',
				'icon-dream'                        => 'dream',
				'icon-shocked'                      => 'shocked',
				'icon-shocked2'                     => 'shocked2',
				'icon-tongue2'                      => 'tongue2',
				'icon-neutral'                      => 'neutral',
				'icon-happy-grin'                   => 'happy-grin',
				'icon-cool'                         => 'cool',
				'icon-mad'                          => 'mad',
				'icon-grin-evil'                    => 'grin-evil',
				'icon-evil'                         => 'evil',
				'icon-wow'                          => 'wow',
				'icon-annoyed'                      => 'annoyed',
				'icon-wondering'                    => 'wondering',
				'icon-confused'                     => 'confused',
				'icon-zipped'                       => 'zipped',
				'icon-grumpy'                       => 'grumpy',
				'icon-mustache'                     => 'mustache',
				'icon-tombstone-hipster'            => 'tombstone-hipster',
				'icon-tombstone'                    => 'tombstone',
				'icon-ghost'                        => 'ghost',
				'icon-ghost-hipster'                => 'ghost-hipster',
				'icon-halloween'                    => 'halloween',
				'icon-christmas'                    => 'christmas',
				'icon-easter-egg'                   => 'easter-egg',
				'icon-mustache2'                    => 'mustache2',
				'icon-mustache-glasses'             => 'mustache-glasses',
				'icon-pipe'                         => 'pipe',
				'icon-alarm'                        => 'alarm',
				'icon-alarm-add'                    => 'alarm-add',
				'icon-alarm-snooze'                 => 'alarm-snooze',
				'icon-alarm-ringing'                => 'alarm-ringing',
				'icon-bullhorn'                     => 'bullhorn',
				'icon-hearing'                      => 'hearing',
				'icon-volume-high'                  => 'volume-high',
				'icon-volume-medium'                => 'volume-medium',
				'icon-volume-low'                   => 'volume-low',
				'icon-volume'                       => 'volume',
				'icon-mute'                         => 'mute',
				'icon-lan'                          => 'lan',
				'icon-lan2'                         => 'lan2',
				'icon-wifi'                         => 'wifi',
				'icon-wifi-lock'                    => 'wifi-lock',
				'icon-wifi-blocked'                 => 'wifi-blocked',
				'icon-wifi-mid'                     => 'wifi-mid',
				'icon-wifi-low'                     => 'wifi-low',
				'icon-wifi-low2'                    => 'wifi-low2',
				'icon-wifi-alert'                   => 'wifi-alert',
				'icon-wifi-alert-mid'               => 'wifi-alert-mid',
				'icon-wifi-alert-low'               => 'wifi-alert-low',
				'icon-wifi-alert-low2'              => 'wifi-alert-low2',
				'icon-stream'                       => 'stream',
				'icon-stream-check'                 => 'stream-check',
				'icon-stream-error'                 => 'stream-error',
				'icon-stream-alert'                 => 'stream-alert',
				'icon-communication'                => 'communication',
				'icon-communication-crossed'        => 'communication-crossed',
				'icon-broadcast'                    => 'broadcast',
				'icon-antenna'                      => 'antenna',
				'icon-satellite'                    => 'satellite',
				'icon-satellite2'                   => 'satellite2',
				'icon-mic'                          => 'mic',
				'icon-mic-mute'                     => 'mic-mute',
				'icon-mic2'                         => 'mic2',
				'icon-spotlights'                   => 'spotlights',
				'icon-hourglass'                    => 'hourglass',
				'icon-loading'                      => 'loading',
				'icon-loading2'                     => 'loading2',
				'icon-loading3'                     => 'loading3',
				'icon-refresh'                      => 'refresh',
				'icon-refresh2'                     => 'refresh2',
				'icon-undo'                         => 'undo',
				'icon-redo'                         => 'redo',
				'icon-jump2'                        => 'jump2',
				'icon-undo2'                        => 'undo2',
				'icon-redo2'                        => 'redo2',
				'icon-sync'                         => 'sync',
				'icon-repeat-one2'                  => 'repeat-one2',
				'icon-sync-crossed'                 => 'sync-crossed',
				'icon-sync2'                        => 'sync2',
				'icon-repeat-one3'                  => 'repeat-one3',
				'icon-sync-crossed2'                => 'sync-crossed2',
				'icon-return'                       => 'return',
				'icon-return2'                      => 'return2',
				'icon-refund'                       => 'refund',
				'icon-history'                      => 'history',
				'icon-history2'                     => 'history2',
				'icon-self-timer'                   => 'self-timer',
				'icon-clock'                        => 'clock',
				'icon-clock2'                       => 'clock2',
				'icon-clock3'                       => 'clock3',
				'icon-watch'                        => 'watch',
				'icon-alarm2'                       => 'alarm2',
				'icon-alarm-add2'                   => 'alarm-add2',
				'icon-alarm-remove'                 => 'alarm-remove',
				'icon-alarm-check'                  => 'alarm-check',
				'icon-alarm-error'                  => 'alarm-error',
				'icon-timer'                        => 'timer',
				'icon-timer-crossed'                => 'timer-crossed',
				'icon-timer2'                       => 'timer2',
				'icon-timer-crossed2'               => 'timer-crossed2',
				'icon-download'                     => 'download',
				'icon-upload'                       => 'upload',
				'icon-download2'                    => 'download2',
				'icon-upload2'                      => 'upload2',
				'icon-enter-up'                     => 'enter-up',
				'icon-enter-down'                   => 'enter-down',
				'icon-enter-left'                   => 'enter-left',
				'icon-enter-right'                  => 'enter-right',
				'icon-exit-up'                      => 'exit-up',
				'icon-exit-down'                    => 'exit-down',
				'icon-exit-left'                    => 'exit-left',
				'icon-exit-right'                   => 'exit-right',
				'icon-enter-up2'                    => 'enter-up2',
				'icon-enter-down2'                  => 'enter-down2',
				'icon-enter-vertical'               => 'enter-vertical',
				'icon-enter-left2'                  => 'enter-left2',
				'icon-enter-right2'                 => 'enter-right2',
				'icon-enter-horizontal'             => 'enter-horizontal',
				'icon-exit-up2'                     => 'exit-up2',
				'icon-exit-down2'                   => 'exit-down2',
				'icon-exit-left2'                   => 'exit-left2',
				'icon-exit-right2'                  => 'exit-right2',
				'icon-cli'                          => 'cli',
				'icon-bug'                          => 'bug',
				'icon-code'                         => 'code',
				'icon-file-code'                    => 'file-code',
				'icon-file-image'                   => 'file-image',
				'icon-file-zip'                     => 'file-zip',
				'icon-file-audio'                   => 'file-audio',
				'icon-file-video'                   => 'file-video',
				'icon-file-preview'                 => 'file-preview',
				'icon-file-charts'                  => 'file-charts',
				'icon-file-stats'                   => 'file-stats',
				'icon-file-spreadsheet'             => 'file-spreadsheet',
				'icon-link'                         => 'link',
				'icon-unlink'                       => 'unlink',
				'icon-link2'                        => 'link2',
				'icon-unlink2'                      => 'unlink2',
				'icon-thumbs-up'                    => 'thumbs-up',
				'icon-thumbs-down'                  => 'thumbs-down',
				'icon-thumbs-up2'                   => 'thumbs-up2',
				'icon-thumbs-down2'                 => 'thumbs-down2',
				'icon-thumbs-up3'                   => 'thumbs-up3',
				'icon-thumbs-down3'                 => 'thumbs-down3',
				'icon-share'                        => 'share',
				'icon-share2'                       => 'share2',
				'icon-share3'                       => 'share3',
				'icon-magnifier'                    => 'magnifier',
				'icon-file-search'                  => 'file-search',
				'icon-find-replace'                 => 'find-replace',
				'icon-zoom-in'                      => 'zoom-in',
				'icon-zoom-out'                     => 'zoom-out',
				'icon-loupe'                        => 'loupe',
				'icon-loupe-zoom-in'                => 'loupe-zoom-in',
				'icon-loupe-zoom-out'               => 'loupe-zoom-out',
				'icon-cross'                        => 'cross',
				'icon-menu'                         => 'menu',
				'icon-list'                         => 'list',
				'icon-list2'                        => 'list2',
				'icon-list3'                        => 'list3',
				'icon-menu2'                        => 'menu2',
				'icon-list4'                        => 'list4',
				'icon-menu3'                        => 'menu3',
				'icon-exclamation'                  => 'exclamation',
				'icon-question'                     => 'question',
				'icon-check'                        => 'check',
				'icon-cross2'                       => 'cross2',
				'icon-plus'                         => 'plus',
				'icon-minus'                        => 'minus',
				'icon-percent'                      => 'percent',
				'icon-chevron-up'                   => 'chevron-up',
				'icon-chevron-down'                 => 'chevron-down',
				'icon-chevron-left'                 => 'chevron-left',
				'icon-chevron-right'                => 'chevron-right',
				'icon-chevrons-expand-vertical'     => 'chevrons-expand-vertical',
				'icon-chevrons-expand-horizontal'   => 'chevrons-expand-horizontal',
				'icon-chevrons-contract-vertical'   => 'chevrons-contract-vertical',
				'icon-chevrons-contract-horizontal' => 'chevrons-contract-horizontal',
				'icon-arrow-up'                     => 'arrow-up',
				'icon-arrow-down'                   => 'arrow-down',
				'icon-arrow-left'                   => 'arrow-left',
				'icon-arrow-right'                  => 'arrow-right',
				'icon-arrow-up-right'               => 'arrow-up-right',
				'icon-arrows-merge'                 => 'arrows-merge',
				'icon-arrows-split'                 => 'arrows-split',
				'icon-arrow-divert'                 => 'arrow-divert',
				'icon-arrow-return'                 => 'arrow-return',
				'icon-expand'                       => 'expand',
				'icon-contract'                     => 'contract',
				'icon-expand2'                      => 'expand2',
				'icon-contract2'                    => 'contract2',
				'icon-move'                         => 'move',
				'icon-tab'                          => 'tab',
				'icon-arrow-wave'                   => 'arrow-wave',
				'icon-expand3'                      => 'expand3',
				'icon-expand4'                      => 'expand4',
				'icon-contract3'                    => 'contract3',
				'icon-notification'                 => 'notification',
				'icon-warning'                      => 'warning',
				'icon-notification-circle'          => 'notification-circle',
				'icon-question-circle'              => 'question-circle',
				'icon-menu-circle'                  => 'menu-circle',
				'icon-checkmark-circle'             => 'checkmark-circle',
				'icon-cross-circle'                 => 'cross-circle',
				'icon-plus-circle'                  => 'plus-circle',
				'icon-circle-minus'                 => 'circle-minus',
				'icon-percent-circle'               => 'percent-circle',
				'icon-arrow-up-circle'              => 'arrow-up-circle',
				'icon-arrow-down-circle'            => 'arrow-down-circle',
				'icon-arrow-left-circle'            => 'arrow-left-circle',
				'icon-arrow-right-circle'           => 'arrow-right-circle',
				'icon-chevron-up-circle'            => 'chevron-up-circle',
				'icon-chevron-down-circle'          => 'chevron-down-circle',
				'icon-chevron-left-circle'          => 'chevron-left-circle',
				'icon-chevron-right-circle'         => 'chevron-right-circle',
				'icon-backward-circle'              => 'backward-circle',
				'icon-first-circle'                 => 'first-circle',
				'icon-previous-circle'              => 'previous-circle',
				'icon-stop-circle'                  => 'stop-circle',
				'icon-play-circle'                  => 'play-circle',
				'icon-pause-circle'                 => 'pause-circle',
				'icon-next-circle'                  => 'next-circle',
				'icon-last-circle'                  => 'last-circle',
				'icon-forward-circle'               => 'forward-circle',
				'icon-eject-circle'                 => 'eject-circle',
				'icon-crop'                         => 'crop',
				'icon-frame-expand'                 => 'frame-expand',
				'icon-frame-contract'               => 'frame-contract',
				'icon-focus'                        => 'focus',
				'icon-transform'                    => 'transform',
				'icon-grid'                         => 'grid',
				'icon-grid-crossed'                 => 'grid-crossed',
				'icon-layers'                       => 'layers',
				'icon-layers-crossed'               => 'layers-crossed',
				'icon-toggle'                       => 'toggle',
				'icon-rulers'                       => 'rulers',
				'icon-ruler'                        => 'ruler',
				'icon-funnel'                       => 'funnel',
				'icon-flip-horizontal'              => 'flip-horizontal',
				'icon-flip-vertical'                => 'flip-vertical',
				'icon-flip-horizontal2'             => 'flip-horizontal2',
				'icon-flip-vertical2'               => 'flip-vertical2',
				'icon-angle'                        => 'angle',
				'icon-angle2'                       => 'angle2',
				'icon-subtract'                     => 'subtract',
				'icon-combine'                      => 'combine',
				'icon-intersect'                    => 'intersect',
				'icon-exclude'                      => 'exclude',
				'icon-align-center-vertical'        => 'align-center-vertical',
				'icon-align-right'                  => 'align-right',
				'icon-align-bottom'                 => 'align-bottom',
				'icon-align-left'                   => 'align-left',
				'icon-align-center-horizontal'      => 'align-center-horizontal',
				'icon-align-top'                    => 'align-top',
				'icon-square'                       => 'square',
				'icon-plus-square'                  => 'plus-square',
				'icon-minus-square'                 => 'minus-square',
				'icon-percent-square'               => 'percent-square',
				'icon-arrow-up-square'              => 'arrow-up-square',
				'icon-arrow-down-square'            => 'arrow-down-square',
				'icon-arrow-left-square'            => 'arrow-left-square',
				'icon-arrow-right-square'           => 'arrow-right-square',
				'icon-chevron-up-square'            => 'chevron-up-square',
				'icon-chevron-down-square'          => 'chevron-down-square',
				'icon-chevron-left-square'          => 'chevron-left-square',
				'icon-chevron-right-square'         => 'chevron-right-square',
				'icon-check-square'                 => 'check-square',
				'icon-cross-square'                 => 'cross-square',
				'icon-menu-square'                  => 'menu-square',
				'icon-prohibited'                   => 'prohibited',
				'icon-circle'                       => 'circle',
				'icon-radio-button'                 => 'radio-button',
				'icon-ligature'                     => 'ligature',
				'icon-text-format'                  => 'text-format',
				'icon-text-format-remove'           => 'text-format-remove',
				'icon-text-size'                    => 'text-size',
				'icon-bold'                         => 'bold',
				'icon-italic'                       => 'italic',
				'icon-underline'                    => 'underline',
				'icon-strikethrough'                => 'strikethrough',
				'icon-highlight'                    => 'highlight',
				'icon-text-align-left'              => 'text-align-left',
				'icon-text-align-center'            => 'text-align-center',
				'icon-text-align-right'             => 'text-align-right',
				'icon-text-align-justify'           => 'text-align-justify',
				'icon-line-spacing'                 => 'line-spacing',
				'icon-indent-increase'              => 'indent-increase',
				'icon-indent-decrease'              => 'indent-decrease',
				'icon-text-wrap'                    => 'text-wrap',
				'icon-pilcrow'                      => 'pilcrow',
				'icon-direction-ltr'                => 'direction-ltr',
				'icon-direction-rtl'                => 'direction-rtl',
				'icon-page-break'                   => 'page-break',
				'icon-page-break2'                  => 'page-break2',
				'icon-sort-alpha-asc'               => 'sort-alpha-asc',
				'icon-sort-alpha-desc'              => 'sort-alpha-desc',
				'icon-sort-numeric-asc'             => 'sort-numeric-asc',
				'icon-sort-numeric-desc'            => 'sort-numeric-desc',
				'icon-sort-amount-asc'              => 'sort-amount-asc',
				'icon-sort-amount-desc'             => 'sort-amount-desc',
				'icon-sort-time-asc'                => 'sort-time-asc',
				'icon-sort-time-desc'               => 'sort-time-desc',
				'icon-sigma'                        => 'sigma',
				'icon-pencil-line'                  => 'pencil-line',
				'icon-hand'                         => 'hand',
				'icon-pointer-up'                   => 'pointer-up',
				'icon-pointer-right'                => 'pointer-right',
				'icon-pointer-down'                 => 'pointer-down',
				'icon-pointer-left'                 => 'pointer-left',
				'icon-finger-tap'                   => 'finger-tap',
				'icon-fingers-tap'                  => 'fingers-tap',
				'icon-reminder'                     => 'reminder',
				'icon-fingers-crossed'              => 'fingers-crossed',
				'icon-fingers-victory'              => 'fingers-victory',
				'icon-gesture-zoom'                 => 'gesture-zoom',
				'icon-gesture-pinch'                => 'gesture-pinch',
				'icon-fingers-scroll-horizontal'    => 'fingers-scroll-horizontal',
				'icon-fingers-scroll-vertical'      => 'fingers-scroll-vertical',
				'icon-fingers-scroll-left'          => 'fingers-scroll-left',
				'icon-fingers-scroll-right'         => 'fingers-scroll-right',
				'icon-hand2'                        => 'hand2',
				'icon-pointer-up2'                  => 'pointer-up2',
				'icon-pointer-right2'               => 'pointer-right2',
				'icon-pointer-down2'                => 'pointer-down2',
				'icon-pointer-left2'                => 'pointer-left2',
				'icon-finger-tap2'                  => 'finger-tap2',
				'icon-fingers-tap2'                 => 'fingers-tap2',
				'icon-reminder2'                    => 'reminder2',
				'icon-gesture-zoom2'                => 'gesture-zoom2',
				'icon-gesture-pinch2'               => 'gesture-pinch2',
				'icon-fingers-scroll-horizontal2'   => 'fingers-scroll-horizontal2',
				'icon-fingers-scroll-vertical2'     => 'fingers-scroll-vertical2',
				'icon-fingers-scroll-left2'         => 'fingers-scroll-left2',
				'icon-fingers-scroll-right2'        => 'fingers-scroll-right2',
				'icon-fingers-scroll-vertical3'     => 'fingers-scroll-vertical3',
				'icon-border-style'                 => 'border-style',
				'icon-border-all'                   => 'border-all',
				'icon-border-outer'                 => 'border-outer',
				'icon-border-inner'                 => 'border-inner',
				'icon-border-top'                   => 'border-top',
				'icon-border-horizontal'            => 'border-horizontal',
				'icon-border-bottom'                => 'border-bottom',
				'icon-border-left'                  => 'border-left',
				'icon-border-vertical'              => 'border-vertical',
				'icon-border-right'                 => 'border-right',
				'icon-border-none'                  => 'border-none',
				'icon-ellipsis'                     => 'ellipsis',
				'icon-uni21'                        => 'uni21',
				'icon-uni22'                        => 'uni22',
				'icon-uni23'                        => 'uni23',
				'icon-uni24'                        => 'uni24',
				'icon-uni25'                        => 'uni25',
				'icon-uni26'                        => 'uni26',
				'icon-uni27'                        => 'uni27',
				'icon-uni28'                        => 'uni28',
				'icon-uni29'                        => 'uni29',
				'icon-uni2a'                        => 'uni2a',
				'icon-uni2b'                        => 'uni2b',
				'icon-uni2c'                        => 'uni2c',
				'icon-uni2d'                        => 'uni2d',
				'icon-uni2e'                        => 'uni2e',
				'icon-uni2f'                        => 'uni2f',
				'icon-uni30'                        => 'uni30',
				'icon-uni31'                        => 'uni31',
				'icon-uni32'                        => 'uni32',
				'icon-uni33'                        => 'uni33',
				'icon-uni34'                        => 'uni34',
				'icon-uni35'                        => 'uni35',
				'icon-uni36'                        => 'uni36',
				'icon-uni37'                        => 'uni37',
				'icon-uni38'                        => 'uni38',
				'icon-uni39'                        => 'uni39',
				'icon-uni3a'                        => 'uni3a',
				'icon-uni3b'                        => 'uni3b',
				'icon-uni3c'                        => 'uni3c',
				'icon-uni3d'                        => 'uni3d',
				'icon-uni3e'                        => 'uni3e',
				'icon-uni3f'                        => 'uni3f',
				'icon-uni40'                        => 'uni40',
				'icon-uni41'                        => 'uni41',
				'icon-uni42'                        => 'uni42',
				'icon-uni43'                        => 'uni43',
				'icon-uni44'                        => 'uni44',
				'icon-uni45'                        => 'uni45',
				'icon-uni46'                        => 'uni46',
				'icon-uni47'                        => 'uni47',
				'icon-uni48'                        => 'uni48',
				'icon-uni49'                        => 'uni49',
				'icon-uni4a'                        => 'uni4a',
				'icon-uni4b'                        => 'uni4b',
				'icon-uni4c'                        => 'uni4c',
				'icon-uni4d'                        => 'uni4d',
				'icon-uni4e'                        => 'uni4e',
				'icon-uni4f'                        => 'uni4f',
				'icon-uni50'                        => 'uni50',
				'icon-uni51'                        => 'uni51',
				'icon-uni52'                        => 'uni52',
				'icon-uni53'                        => 'uni53',
				'icon-uni54'                        => 'uni54',
				'icon-uni55'                        => 'uni55',
				'icon-uni56'                        => 'uni56',
				'icon-uni57'                        => 'uni57',
				'icon-uni58'                        => 'uni58',
				'icon-uni59'                        => 'uni59',
				'icon-uni5a'                        => 'uni5a',
				'icon-uni5b'                        => 'uni5b',
				'icon-uni5c'                        => 'uni5c',
				'icon-uni5d'                        => 'uni5d',
				'icon-uni5e'                        => 'uni5e',
				'icon-uni5f'                        => 'uni5f',
				'icon-uni60'                        => 'uni60',
				'icon-uni61'                        => 'uni61',
				'icon-uni62'                        => 'uni62',
				'icon-uni63'                        => 'uni63',
				'icon-uni64'                        => 'uni64',
				'icon-uni65'                        => 'uni65',
				'icon-uni66'                        => 'uni66',
				'icon-uni67'                        => 'uni67',
				'icon-uni68'                        => 'uni68',
				'icon-uni69'                        => 'uni69',
				'icon-uni6a'                        => 'uni6a',
				'icon-uni6b'                        => 'uni6b',
				'icon-uni6c'                        => 'uni6c',
				'icon-uni6d'                        => 'uni6d',
				'icon-uni6e'                        => 'uni6e',
				'icon-uni6f'                        => 'uni6f',
				'icon-uni70'                        => 'uni70',
				'icon-uni71'                        => 'uni71',
				'icon-uni72'                        => 'uni72',
				'icon-uni73'                        => 'uni73',
				'icon-uni74'                        => 'uni74',
				'icon-uni75'                        => 'uni75',
				'icon-uni76'                        => 'uni76',
				'icon-uni77'                        => 'uni77',
				'icon-uni78'                        => 'uni78',
				'icon-uni79'                        => 'uni79',
				'icon-uni7a'                        => 'uni7a',
				'icon-uni7b'                        => 'uni7b',
				'icon-uni7c'                        => 'uni7c',
				'icon-uni7d'                        => 'uni7d',
				'icon-uni7e'                        => 'uni7e',
				'icon-copyright'                    => 'copyright',
			),
			$icons
		);
		// Then we set a new list of icons as the options of the icon control
		$controls_registry->get_control( 'icon' )->set_settings( 'options', $new_icons );
	}

	public function elementor_custom_icons( $additional_tabs ) {
		$additional_tabs['linearicons'] = [
			'name'          => 'linearicons',
			'label'         => esc_html__( 'Linearicons', 'martfury' ),
			'url'           => self::get_linearicons_asset_url( 'linearicons' ),
			'enqueue'       => [ self::get_linearicons_asset_url( 'linearicons' ) ],
			'prefix'        => 'icon-',
			'displayPrefix' => 'icon',
			'labelIcon'     => 'icon-pencil4',
			'ver'           => '1.0.0',
			'fetchJson'     => self::get_linearicons_asset_url( 'linearicons', 'js', false ),
			'native'        => true,
		];

		return $additional_tabs;
	}

	public static function get_linearicons_asset_url( $filename, $ext_type = 'css', $add_suffix = true ) {

		$url = MARTFURY_ADDONS_URL . 'assets/' . $ext_type . '/' . $filename;

		if ( $add_suffix ) {
			$url .= '.min';
		}

		return $url . '.' . $ext_type;
	}

	/**
	 * Load products
	 */
	public static function elementor_load_products() {

		$atts = array(
			'columns'      => isset( $_POST['columns'] ) ? intval( $_POST['columns'] ) : '',
			'products'     => isset( $_POST['products'] ) ? $_POST['products'] : '',
			'order'        => isset( $_POST['order'] ) ? $_POST['order'] : '',
			'orderby'      => isset( $_POST['orderby'] ) ? $_POST['orderby'] : '',
			'per_page'     => isset( $_POST['per_page'] ) ? intval( $_POST['per_page'] ) : '',
			'product_cats' => isset( $_POST['product_cats'] ) ? $_POST['product_cats'] : '',
		);

		$products = self::get_products( $atts );

		wp_send_json_success( $products );
	}

	/**
	 * Retrieve the list of taxonomy
	 *
	 * @return array Widget categories.
	 */
	public static function get_taxonomy( $taxonomy = 'product_cat' ) {

		$output = array();

		$categories = get_categories(
			array(
				'taxonomy' => $taxonomy
			)
		);

		foreach ( $categories as $category ) {
			$output[ $category->slug ] = $category->name;
		}

		return $output;
	}

	/**
	 * Retrieve the list of products
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public static function get_products( $atts ) {
		$params   = '';
		$order    = $atts['order'];
		$order_by = $atts['orderby'];
		if ( $atts['products'] == 'featured' ) {
			$params = 'visibility="featured"';
		} elseif ( $atts['products'] == 'best_selling' ) {
			$params = 'best_selling="true"';
		} elseif ( $atts['products'] == 'sale' ) {
			$params = 'on_sale="true"';
		} elseif ( $atts['products'] == 'recent' ) {
			$order    = $order ? $order : 'desc';
			$order_by = $order_by ? $order_by : 'date';
		} elseif ( $atts['products'] == 'top_rated' ) {
			$params = 'top_rated="true"';
		}

		$params .= ' columns="' . intval( $atts['columns'] ) . '" limit="' . intval( $atts['per_page'] ) . '" order="' . $order . '" orderby ="' . $order_by . '"';
		if ( ! empty( $atts['product_cats'] ) ) {
			$cats = $atts['product_cats'];
			if ( is_array( $cats ) ) {
				$cats = implode( ',', $cats );
			}

			$params .= ' category="' . $cats . '" ';
		}

		if ( ! empty( $atts['product_tags'] ) ) {
			$params .= ' tag="' . implode( ',', $atts['product_tags'] ) . '" ';
		}

		if ( is_admin() ) {
			add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
			add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
			remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
			remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );

		}

		return do_shortcode( '[products ' . $params . ']' );

	}

	/**
	 * Display numeric pagination
	 *
	 * @param $max_num_pages
	 *
	 * *@return string
	 */
	public static function get_pagination_numeric( $max_num_pages ) {
		$big  = 999999999;
		$args = array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'total'     => $max_num_pages,
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'prev_text' => esc_html__( 'Previous', 'martfury' ),
			'next_text' => esc_html__( 'Next', 'martfury' ),
			'type'      => 'plain',
		);

		return sprintf( '<nav class="navigation paging-navigation numeric-navigation">%s</nav>', paginate_links( $args ) );
	}

	/**
	 * Loop over products
	 *
	 * @param array $products_ids
	 */
	public static function get_loop_deals( $products_ids, $template ) {
		update_meta_cache( 'post', $products_ids );
		update_object_term_cache( $products_ids, 'product' );

		$original_post = $GLOBALS['post'];

		woocommerce_product_loop_start();

		foreach ( $products_ids as $product_id ) {
			$GLOBALS['post'] = get_post( $product_id ); // WPCS: override ok.
			setup_postdata( $GLOBALS['post'] );
			wc_get_template_part( 'content', $template );
		}

		$GLOBALS['post'] = $original_post; // WPCS: override ok.
		woocommerce_product_loop_end();

		wp_reset_postdata();
		woocommerce_reset_loop();
	}

	public static function deal_progress() {
		global $woocommerce_loop, $product;

		if ( ! isset( $woocommerce_loop['name'] ) ) {
			return;
		}

		if ( 'martfury_deals_elementor' != $woocommerce_loop['name'] ) {
			return;
		}

		if ( ! function_exists( 'tawc_is_deal_product' ) ) {
			return;
		}

		if ( ! tawc_is_deal_product( $product ) ) {
			return;
		}

		$limit = get_post_meta( $product->get_id(), '_deal_quantity', true );
		$sold  = intval( get_post_meta( $product->get_id(), '_deal_sales_counts', true ) );
		?>

        <div class="deal-progress">
            <div class="progress-bar">
                <div class="progress-value" style="width: <?php echo esc_attr( $sold / $limit * 100 ); ?>%"></div>
            </div>
            <p class="progress-text"><?php esc_html_e( 'Sold', 'martfury' ) ?>: <?php echo $sold; ?></p>
        </div>

		<?php
	}

	/**
	 * Get the product deals
	 *
	 * @return string.
	 */
	public static function get_product_deals( $settings, $template = 'product-deals' ) {

		$per_page   = intval( $settings['per_page'] );
		$query_args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => $per_page,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'fields'              => 'ids',
			'post__in'            => (array) wc_get_product_ids_on_sale(),
			'meta_query'          => WC()->query->get_meta_query(),
			'tax_query'           => WC()->query->get_tax_query(),
			'orderby'             => $settings['orderby'],
			'order'               => $settings['order'],
		);

		if ( $settings['pagination'] == 'yes' ) {
			$paged                       = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			$offset                      = ( $paged - 1 ) * $per_page;
			$query_args['offset']        = $offset;
			$query_args['no_found_rows'] = false;
		}

		if ( in_array( $settings['product_type'], array( 'day', 'week', 'month' ) ) ) {
			$date = '+1 day';
			if ( $settings['product_type'] == 'week' ) {
				$date = '+7 day';
			} else if ( $settings['product_type'] == 'month' ) {
				$date = '+1 month';
			}
			$query_args['meta_query'] = apply_filters(
				'martfury_product_deals_meta_query', array_merge(
					WC()->query->get_meta_query(), array(
						array(
							'key'     => '_deal_quantity',
							'value'   => 0,
							'compare' => '>',
						),
						array(
							'key'     => '_sale_price_dates_to',
							'value'   => 0,
							'compare' => '>',
						),
						array(
							'key'     => '_sale_price_dates_to',
							'value'   => strtotime( $date ),
							'compare' => '<=',
						),
					)
				)
			);
		} elseif ( $settings['product_type'] == 'deals' ) {
			$query_args['meta_query'] = apply_filters(
				'martfury_product_deals_meta_query', array_merge(
					WC()->query->get_meta_query(), array(
						array(
							'key'     => '_deal_quantity',
							'value'   => 0,
							'compare' => '>',
						)
					)
				)
			);
		}


		if ( $settings['product_cats'] ) {
			$query_args['tax_query'] = array_merge(
				WC()->query->get_tax_query(), array(
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'slug',
						'terms'    => $settings['product_cats'],
					),
				)
			);
		}

		$deals = new \WP_Query( $query_args );

		if ( ! $deals->have_posts() ) {
			return '';
		}

		global $woocommerce_loop;

		if ( $template == 'product-deals' ) {
			$woocommerce_loop['columns'] = intval( $settings['columns'] );
			$woocommerce_loop['name']    = 'martfury_deals_elementor';
		} else {
			$woocommerce_loop['columns'] = 1;
		}

		ob_start();

		self::get_loop_deals( $deals->posts, $template );

		if ( $settings['pagination'] == 'yes' ) {
			$total_pages = $deals->max_num_pages;
			$align_class = 'text-' . $settings['pagination_text_align'];
			self::pagination_numeric( $total_pages, $align_class );
		}

		return ob_get_clean();
	}

	/**
	 * Get pagination numeric
	 *
	 * @return string.
	 */

	public static function pagination_numeric( $max_num_pages, $align_class ) {
		?>
        <nav class="navigation paging-navigation numeric-navigation <?php echo esc_attr( $align_class ); ?>">
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

Elementor::instance();