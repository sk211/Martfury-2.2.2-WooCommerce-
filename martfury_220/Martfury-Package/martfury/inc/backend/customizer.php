<?php
/**
 * Martfury theme customizer
 *
 * @package Martfury
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Martfury_Customize {
	/**
	 * Customize settings
	 *
	 * @var array
	 */
	protected $config = array();

	/**
	 * The class constructor
	 *
	 * @param array $config
	 */
	public function __construct( $config ) {
		$this->config = $config;

		if ( ! class_exists( 'Kirki' ) ) {
			return;
		}

		$this->register();
	}

	/**
	 * Register settings
	 */
	public function register() {

		/**
		 * Add the theme configuration
		 */
		if ( ! empty( $this->config['theme'] ) ) {
			Kirki::add_config(
				$this->config['theme'], array(
					'capability'  => 'edit_theme_options',
					'option_type' => 'theme_mod',
				)
			);
		}

		/**
		 * Add panels
		 */
		if ( ! empty( $this->config['panels'] ) ) {
			foreach ( $this->config['panels'] as $panel => $settings ) {
				Kirki::add_panel( $panel, $settings );
			}
		}

		/**
		 * Add sections
		 */
		if ( ! empty( $this->config['sections'] ) ) {
			foreach ( $this->config['sections'] as $section => $settings ) {
				Kirki::add_section( $section, $settings );
			}
		}

		/**
		 * Add fields
		 */
		if ( ! empty( $this->config['theme'] ) && ! empty( $this->config['fields'] ) ) {
			foreach ( $this->config['fields'] as $name => $settings ) {
				if ( ! isset( $settings['settings'] ) ) {
					$settings['settings'] = $name;
				}

				Kirki::add_field( $this->config['theme'], $settings );
			}
		}
	}

	/**
	 * Get config ID
	 *
	 * @return string
	 */
	public function get_theme() {
		return $this->config['theme'];
	}

	/**
	 * Get customize setting value
	 *
	 * @param string $name
	 *
	 * @return bool|string
	 */
	public function get_option( $name ) {

		$default = $this->get_option_default( $name );

		return get_theme_mod( $name, $default );
	}

	/**
	 * Get default option values
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public function get_option_default( $name ) {
		if ( ! isset( $this->config['fields'][ $name ] ) ) {
			return false;
		}

		return isset( $this->config['fields'][ $name ]['default'] ) ? $this->config['fields'][ $name ]['default'] : false;
	}
}

/**
 * This is a short hand function for getting setting value from customizer
 *
 * @param string $name
 *
 * @return bool|string
 */
function martfury_get_option( $name ) {
	global $martfury_customize;

	$value = false;

	if ( class_exists( 'Kirki' ) ) {
		$value = Kirki::get_option( 'martfury', $name );
	} elseif ( ! empty( $martfury_customize ) ) {
		$value = $martfury_customize->get_option( $name );
	}

	return apply_filters( 'martfury_get_option', $value, $name );
}

/**
 * Get nav menus
 *
 * @return string
 */
function martfury_customizer_get_categories( $taxonomies, $default = false ) {
	if ( ! taxonomy_exists( $taxonomies ) ) {
		return;
	}

	if ( ! is_admin() ) {
		return;
	}

	$output = array();

	if ( $default ) {
		$output[0] = esc_html__( 'Select Category', 'martfury' );
	}

	global $wpdb;
	$post_meta_infos = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT a.term_id AS id, b.name as name, b.slug AS slug
						FROM {$wpdb->term_taxonomy} AS a
						INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
						WHERE a.taxonomy = '%s'", $taxonomies
		), ARRAY_A
	);

	if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
		foreach ( $post_meta_infos as $value ) {
			$output[ $value['slug'] ] = $value['name'];
		}
	}


	return $output;
}


/**
 * Get default option values
 *
 * @param $name
 *
 * @return mixed
 */
function martfury_get_option_default( $name ) {
	global $martfury_customize;

	if ( empty( $martfury_customize ) ) {
		return false;
	}

	return $martfury_customize->get_option_default( $name );
}

/**
 * Move some default sections to `general` panel that registered by theme
 *
 * @param object $wp_customize
 */
function martfury_customize_modify( $wp_customize ) {
	$wp_customize->get_section( 'title_tagline' )->panel     = 'general';
	$wp_customize->get_section( 'static_front_page' )->panel = 'general';
}

add_action( 'customize_register', 'martfury_customize_modify' );


/**
 * Get customize settings
 *
 * @return array
 */
function martfury_customize_settings() {
	/**
	 * Customizer configuration
	 */

	$settings = array(
		'theme' => 'martfury',
	);

	$panels = array(
		'general'     => array(
			'priority' => 10,
			'title'    => esc_html__( 'General', 'martfury' ),
		),
		'typography'  => array(
			'priority' => 20,
			'title'    => esc_html__( 'Typography', 'martfury' ),
		),
		// Styling
		'styling'     => array(
			'title'    => esc_html__( 'Styling', 'martfury' ),
			'priority' => 30,
		),
		'header'      => array(
			'priority' => 50,
			'title'    => esc_html__( 'Header', 'martfury' ),
		),
		'woocommerce' => array(
			'priority' => 60,
			'title'    => esc_html__( 'Woocommerce', 'martfury' ),
		),
		'blog'        => array(
			'title'    => esc_html__( 'Blog', 'martfury' ),
			'priority' => 70,
		),
		'pages'       => array(
			'title'    => esc_html__( 'Pages', 'martfury' ),
			'priority' => 80,
		),
		'footer'      => array(
			'title'    => esc_html__( 'Footer', 'martfury' ),
			'priority' => 90,
		),
	);

	$sections = array(
		// Styling
		'styling_general'             => array(
			'title'       => esc_html__( 'General', 'martfury' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'styling',
		),
		'color_scheme'                => array(
			'title'       => esc_html__( 'Color Scheme', 'martfury' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'styling',
		),
		'newsletter'                  => array(
			'title'       => esc_html__( 'NewsLetter', 'martfury' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'general',
		),
		'font_families_typo'          => array(
			'title'       => esc_html__( 'Fonts Families', 'martfury' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'body_typo'                   => array(
			'title'       => esc_html__( 'Body', 'martfury' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'heading_typo'                => array(
			'title'       => esc_html__( 'Heading', 'martfury' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'header_typo'                 => array(
			'title'       => esc_html__( 'Header', 'martfury' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'page_header_typo'            => array(
			'title'       => esc_html__( 'Page Header', 'martfury' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'footer_typo'                 => array(
			'title'       => esc_html__( 'Footer', 'martfury' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		// Header
		'promotion'                   => array(
			'title'       => esc_html__( 'Promotion', 'martfury' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'logo'                        => array(
			'title'       => esc_html__( 'Logo', 'martfury' ),
			'description' => '',
			'priority'    => 15,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'header'                      => array(
			'title'       => esc_html__( 'Header Layout', 'martfury' ),
			'description' => '',
			'priority'    => 20,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'header_sticky'               => array(
			'title'       => esc_html__( 'Sticky Header', 'martfury' ),
			'description' => '',
			'priority'    => 20,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'header_search_content'       => array(
			'title'       => esc_html__( 'Search', 'martfury' ),
			'description' => '',
			'priority'    => 20,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'header_department'           => array(
			'title'       => esc_html__( 'Department', 'martfury' ),
			'description' => '',
			'priority'    => 20,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'header_hotline'              => array(
			'title'       => esc_html__( 'Hotline', 'martfury' ),
			'description' => '',
			'priority'    => 20,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'header_cart'                 => array(
			'title'       => esc_html__( 'Cart', 'martfury' ),
			'description' => '',
			'priority'    => 20,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'header_account'              => array(
			'title'       => esc_html__( 'Account', 'martfury' ),
			'description' => '',
			'priority'    => 20,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'header_recently_viewed'      => array(
			'title'       => esc_html__( 'Recently Viewed', 'martfury' ),
			'description' => '',
			'priority'    => 20,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'header_skin'                 => array(
			'title'       => esc_html__( 'Header Skin', 'martfury' ),
			'description' => '',
			'priority'    => 20,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		// Catalog
		'catalog_page'                => array(
			'title'       => esc_html__( 'Catalog General', 'martfury' ),
			'description' => '',
			'priority'    => 40,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'catalog_layout_1'            => array(
			'title'       => esc_html__( 'Catalog Layout 1', 'martfury' ),
			'description' => '',
			'priority'    => 40,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'catalog_layout_2'            => array(
			'title'       => esc_html__( 'Catalog Layout 2', 'martfury' ),
			'description' => '',
			'priority'    => 40,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'catalog_layout_3'            => array(
			'title'       => esc_html__( 'Catalog Layout 3', 'martfury' ),
			'description' => '',
			'priority'    => 40,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'shop_page'                   => array(
			'title'       => esc_html__( 'Shop Page', 'martfury' ),
			'description' => '',
			'priority'    => 40,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'product_cat_level_1_page'    => array(
			'title'       => esc_html__( 'Category Level 1 Page', 'martfury' ),
			'description' => '',
			'priority'    => 40,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'shop_badge'                  => array(
			'title'       => esc_html__( 'Badges', 'martfury' ),
			'description' => '',
			'priority'    => 40,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'product_page'                => array(
			'title'       => esc_html__( 'Single Product Page', 'martfury' ),
			'description' => '',
			'priority'    => 50,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'related_products'            => array(
			'title'       => esc_html__( 'Related Products', 'martfury' ),
			'description' => '',
			'priority'    => 50,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'upsells_products'            => array(
			'title'       => esc_html__( 'Up-Sells Products', 'martfury' ),
			'description' => '',
			'priority'    => 50,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'instagram_photos'            => array(
			'title'       => esc_html__( 'Instagram Photos', 'martfury' ),
			'description' => '',
			'priority'    => 50,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'custom_product_cat_sidebars' => array(
			'title'       => esc_html__( 'Custom Categories Sidebar', 'martfury' ),
			'description' => '',
			'priority'    => 50,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'cart_page'                   => array(
			'title'       => esc_html__( 'Cart Page', 'martfury' ),
			'description' => '',
			'priority'    => 50,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'account_page'                => array(
			'title'       => esc_html__( 'Account Page', 'martfury' ),
			'description' => '',
			'priority'    => 50,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'search_page'                 => array(
			'title'       => esc_html__( 'Search Page', 'martfury' ),
			'description' => '',
			'priority'    => 50,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'page_header_blog'            => array(
			'title'       => esc_html__( 'Blog Page Header', 'martfury' ),
			'description' => '',
			'priority'    => 40,
			'capability'  => 'edit_theme_options',
			'panel'       => 'blog',
		),
		'blog'                        => array(
			'title'       => esc_html__( 'General', 'martfury' ),
			'description' => '',
			'priority'    => 40,
			'panel'       => 'blog',
			'capability'  => 'edit_theme_options',
		),
		'single_post'                 => array(
			'title'       => esc_html__( 'Single Post', 'martfury' ),
			'description' => '',
			'priority'    => 50,
			'panel'       => 'blog',
			'capability'  => 'edit_theme_options',
		),
		'page_header_page'            => array(
			'title'       => esc_html__( 'Page Header', 'martfury' ),
			'description' => '',
			'priority'    => 40,
			'capability'  => 'edit_theme_options',
			'panel'       => 'pages',
		),
		'single_page'                 => array(
			'title'       => esc_html__( 'Page Layout', 'martfury' ),
			'description' => '',
			'priority'    => 40,
			'capability'  => 'edit_theme_options',
			'panel'       => 'pages',
		),
		// 404
		'not_found'                   => array(
			'title'       => esc_html__( '404 Page', 'martfury' ),
			'description' => '',
			'priority'    => 60,
			'panel'       => 'pages',
			'capability'  => 'edit_theme_options',
		),
		// Coming Soon
		'coming_soon'                 => array(
			'title'       => esc_html__( 'Coming Soon Page', 'martfury' ),
			'description' => '',
			'priority'    => 60,
			'panel'       => 'pages',
			'capability'  => 'edit_theme_options',
		),
		'footer'                      => array(
			'title'       => esc_html__( 'Footer Layout', 'martfury' ),
			'description' => '',
			'priority'    => 60,
			'panel'       => 'footer',
			'capability'  => 'edit_theme_options',
		),
		'footer_newsletter'           => array(
			'title'       => esc_html__( 'Footer Newsletter', 'martfury' ),
			'description' => '',
			'priority'    => 60,
			'panel'       => 'footer',
			'capability'  => 'edit_theme_options',
		),
		'footer_info'                 => array(
			'title'       => esc_html__( 'Footer Info', 'martfury' ),
			'description' => '',
			'priority'    => 60,
			'panel'       => 'footer',
			'capability'  => 'edit_theme_options',
		),
		'footer_widgets'              => array(
			'title'       => esc_html__( 'Footer Widgets', 'martfury' ),
			'description' => '',
			'priority'    => 60,
			'panel'       => 'footer',
			'capability'  => 'edit_theme_options',
		),
		'footer_links'                => array(
			'title'       => esc_html__( 'Footer Links', 'martfury' ),
			'description' => '',
			'priority'    => 60,
			'panel'       => 'footer',
			'capability'  => 'edit_theme_options',
		),
		'footer_recently_viewed'      => array(
			'title'       => esc_html__( 'Recently Viewed Products', 'martfury' ),
			'description' => '',
			'priority'    => 60,
			'panel'       => 'footer',
			'capability'  => 'edit_theme_options',
		),
	);

	$fields = array(
		// NewsLetter
		'newsletter_popup'                        => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Enable NewsLetter Popup', 'martfury' ),
			'default'     => 0,
			'section'     => 'newsletter',
			'priority'    => 10,
			'description' => esc_html__( 'Check this option to show newsletter popup.', 'martfury' ),
		),
		'newsletter_home_popup'                   => array(
			'type'        => 'toggle',
			'label'       => esc_html__( "Show in Homepage", 'martfury' ),
			'default'     => 1,
			'section'     => 'newsletter',
			'priority'    => 10,
			'description' => esc_html__( 'Check this option to enable newsletter popup in the homepage.', 'martfury' ),
		),
		'newsletter_bg_image'                     => array(
			'type'     => 'image',
			'label'    => esc_html__( 'Background Image', 'martfury' ),
			'default'  => '',
			'section'  => 'newsletter',
			'priority' => 20,
		),
		'newsletter_content'                      => array(
			'type'     => 'textarea',
			'label'    => esc_html__( 'Content', 'martfury' ),
			'default'  => '',
			'section'  => 'newsletter',
			'priority' => 20,
		),
		'newsletter_form'                         => array(
			'type'        => 'textarea',
			'label'       => esc_html__( 'NewsLetter Form', 'martfury' ),
			'default'     => '',
			'description' => sprintf( wp_kses_post( 'Enter the shortcode of MailChimp form . You can edit your sign - up form in the <a href= "%s" > MailChimp for WordPress form settings </a>.', 'martfury' ), admin_url( 'admin.php?page=mailchimp-for-wp-forms' ) ),
			'section'     => 'newsletter',
			'priority'    => 20,
		),
		'newsletter_reappear'                     => array(
			'type'        => 'number',
			'label'       => esc_html__( 'Reappear', 'martfury' ),
			'default'     => '1',
			'section'     => 'newsletter',
			'priority'    => 20,
			'description' => esc_html__( 'Reappear after how many day(s) using Cookie', 'martfury' ),
		),
		'newsletter_visible'                      => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Visible', 'martfury' ),
			'default'  => '1',
			'section'  => 'newsletter',
			'priority' => 20,
			'choices'  => array(
				'1' => esc_html__( 'After page loaded', 'martfury' ),
				'2' => esc_html__( 'After how many seconds', 'martfury' ),
			),
		),
		'newsletter_seconds'                      => array(
			'type'            => 'number',
			'label'           => esc_html__( 'Seconds', 'martfury' ),
			'default'         => '10',
			'section'         => 'newsletter',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'newsletter_visible',
					'operator' => '==',
					'value'    => '2',
				),
			),
		),
		'lazyload'                                => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Enable Lazy Load', 'martfury' ),
			'default'     => 0,
			'section'     => 'styling_general',
			'priority'    => 10,
			'description' => esc_html__( 'Check this to delay loading of images.', 'martfury' ),
		),
		'back_to_top'                             => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Back To Top', 'martfury' ),
			'default'     => 0,
			'section'     => 'styling_general',
			'priority'    => 10,
			'description' => esc_html__( 'Check this to show back to top.', 'martfury' ),
		),
		'custom_preloader'                       => array(
			'type'            => 'custom',
			'section'         => 'styling_general',
			'default'         => '<hr>',
			'priority'        => 10,
		),
		'preloader'                               => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Preloader', 'martfury' ),
			'default'     => 0,
			'section'     => 'styling_general',
			'priority'    => 10,
			'description' => esc_html__( 'Display a preloader when page is loading.', 'martfury' ),
		),
		'custom_preloader_progress'                            => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Progress Bar Background Color', 'martfury' ),
			'default'         => '',
			'section'         => 'styling_general',
			'priority'        => 10,
			'choices'         => array(
				'alpha' => true,
			),
		),
		// Color Scheme
		'color_scheme'                            => array(
			'type'     => 'palette',
			'label'    => esc_html__( 'Base Color Scheme', 'martfury' ),
			'default'  => '',
			'section'  => 'color_scheme',
			'priority' => 10,
			'choices'  => array(
				''       => array( '#fcb800' ),
				'red'    => array( '#dd2400' ),
				'orange' => array( '#fb7c00' ),
				'blue'   => array( '#0071df' ),
				'green'  => array( '#5fa30f' ),
				'black'  => array( '#000000' ),
			),
		),
		'custom_color_scheme'                     => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Custom Color Scheme', 'martfury' ),
			'default'  => 0,
			'section'  => 'color_scheme',
			'priority' => 10,
		),
		'custom_color'                            => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Color', 'martfury' ),
			'default'         => '',
			'section'         => 'color_scheme',
			'priority'        => 10,
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'custom_color_scheme',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'color_skin'                              => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Skin', 'martfury' ),
			'section'  => 'color_scheme',
			'default'  => '',
			'priority' => 10,
			'choices'  => array(
				''      => esc_html__( 'Dark', 'martfury' ),
				'light' => esc_html__( 'Light', 'martfury' ),
			),
		),
		// Typography
		'font_families_typo'                      => array(
			'type'     => 'multicheck',
			'label'    => esc_html__( 'Font Families Default', 'martfury' ),
			'section'  => 'font_families_typo',
			'default'  => array( 'worksans', 'libre_baskerville' ),
			'priority' => 20,
			'choices'  => array(
				'worksans'          => esc_html__( 'WorkSans', 'martfury' ),
				'libre_baskerville' => esc_html__( 'Libre Baskerville', 'martfury' ),
			),
		),
		'body_typo'                               => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Body', 'martfury' ),
			'section'  => 'body_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Work Sans',
				'variant'        => 'regular',
				'font-size'      => '14px',
				'line-height'    => '1.6',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#666',
				'text-transform' => 'none',
			),
		),
		'heading1_typo'                           => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 1', 'martfury' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Work Sans',
				'variant'        => '700',
				'font-size'      => '36px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#000',
				'text-transform' => 'none',
			),
		),
		'heading2_typo'                           => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 2', 'martfury' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Work Sans',
				'variant'        => '700',
				'font-size'      => '30px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#000',
				'text-transform' => 'none',
			),
		),
		'heading3_typo'                           => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 3', 'martfury' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Work Sans',
				'variant'        => '700',
				'font-size'      => '24px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#000',
				'text-transform' => 'none',
			),
		),
		'heading4_typo'                           => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 4', 'martfury' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Work Sans',
				'variant'        => '700',
				'font-size'      => '18px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#000',
				'text-transform' => 'none',
			),
		),
		'heading5_typo'                           => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 5', 'martfury' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Work Sans',
				'variant'        => '700',
				'font-size'      => '16px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#000',
				'text-transform' => 'none',
			),
		),
		'heading6_typo'                           => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 6', 'martfury' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Work Sans',
				'variant'        => '700',
				'font-size'      => '12px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#000',
				'text-transform' => 'none',
			),
		),
		'menu_typo'                               => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Menu', 'martfury' ),
			'section'  => 'header_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Work Sans',
				'subsets'        => array( 'latin-ext' ),
				'font-size'      => '16px',
				'text-transform' => 'none',
			),
		),
		'mega_menu_typo'                          => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Mega Menu Heading', 'martfury' ),
			'section'  => 'header_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Work Sans',
				'variant'        => '600',
				'subsets'        => array( 'latin-ext' ),
				'font-size'      => '16px',
				'color'          => '#000',
				'text-transform' => 'none',
			),
		),
		'sub_menu_typo'                           => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Sub Menu', 'martfury' ),
			'section'  => 'header_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Work Sans',
				'variant'        => '400',
				'subsets'        => array( 'latin-ext' ),
				'font-size'      => '14px',
				'color'          => '#000',
				'text-transform' => 'none',
			),
		),
		'footer_typo'                             => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Footer', 'martfury' ),
			'section'  => 'footer_typo',
			'priority' => 10,
			'default'  => array(
				'font-family' => 'Work Sans',
				'variant'     => '400',
				'subsets'     => array( 'latin-ext' ),
				'font-size'   => '14px',
				'color'       => '#666',
			),
		),
		'footer_widget_typo'                      => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Widget Title', 'martfury' ),
			'section'  => 'footer_typo',
			'priority' => 10,
			'default'  => array(
				'font-family' => 'Work Sans',
				'variant'     => '600',
				'subsets'     => array( 'latin-ext' ),
				'font-size'   => '16px',
				'color'       => '#000',
			),
		),
		// 404
		'not_found_img'                           => array(
			'type'     => 'image',
			'label'    => esc_html__( 'Image', 'martfury' ),
			'section'  => 'not_found',
			'default'  => '',
			'priority' => 10,
		),
		'not_found_bg'                            => array(
			'type'     => 'color',
			'label'    => esc_html__( 'Background Color', 'martfury' ),
			'default'  => '#efeef0',
			'section'  => 'not_found',
			'priority' => 10,
		),

		// Coming Soon
		'show_coming_soon_logo'                   => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Logo', 'martfury' ),
			'section'  => 'coming_soon',
			'default'  => 1,
			'priority' => 10,
		),
		'coming_soon_logo'                        => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Logo', 'martfury' ),
			'section'         => 'coming_soon',
			'default'         => '',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'show_coming_soon_logo',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'show_coming_soon_socials'                => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Socials', 'martfury' ),
			'section'     => 'coming_soon',
			'default'     => 1,
			'description' => esc_html__( 'Display social sharing icons on Coming Soon Page', 'martfury' ),
			'priority'    => 20,
		),
		'coming_soon_socials'                     => array(
			'type'            => 'repeater',
			'label'           => esc_html__( 'Socials', 'martfury' ),
			'section'         => 'coming_soon',
			'priority'        => 60,
			'default'         => array(
				array(
					'link_url' => 'https://instagram.com/',
				),
				array(
					'link_url' => 'https://facebook.com/',
				),
				array(
					'link_url' => 'https://twitter.com/',
				),
				array(
					'link_url' => 'https://youtube.com/',
				),
			),
			'fields'          => array(
				'link_url' => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Social URL', 'martfury' ),
					'description' => esc_html__( 'Enter the URL for this social', 'martfury' ),
					'default'     => '',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'show_coming_soon_socials',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		// Promotion
		'promotion'                               => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Promotion', 'martfury' ),
			'section'  => 'promotion',
			'default'  => 0,
			'priority' => 10,
		),
		'promotion_home_only'                     => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Display On Homepage Only', 'martfury' ),
			'section'         => 'promotion',
			'default'         => 0,
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'promotion',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'promotion_close'                         => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Show Close button', 'martfury' ),
			'default'         => '0',
			'section'         => 'promotion',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'promotion',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'promotion_bg_color'                      => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Background Color', 'martfury' ),
			'default'         => '',
			'section'         => 'promotion',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'promotion',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'promotion_bg_image'                      => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Background Image', 'martfury' ),
			'default'         => '',
			'section'         => 'promotion',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'promotion',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'promotion_bg_horizontal'                 => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Background Horizontal', 'martfury' ),
			'default'         => 'left',
			'section'         => 'promotion',
			'priority'        => 10,
			'choices'         => array(
				'left'   => esc_html__( 'Left', 'martfury' ),
				'right'  => esc_html__( 'Right', 'martfury' ),
				'center' => esc_html__( 'Center', 'martfury' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'promotion',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'promotion_bg_vertical'                   => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Background Vertical', 'martfury' ),
			'default'         => 'top',
			'section'         => 'promotion',
			'priority'        => 10,
			'choices'         => array(
				'top'    => esc_html__( 'Top', 'martfury' ),
				'center' => esc_html__( 'Center', 'martfury' ),
				'bottom' => esc_html__( 'Bottom', 'martfury' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'promotion',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'promotion_bg_repeats'                    => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Background Repeat', 'martfury' ),
			'default'         => 'repeat',
			'section'         => 'promotion',
			'priority'        => 10,
			'choices'         => array(
				'repeat'    => esc_html__( 'Repeat', 'martfury' ),
				'repeat-x'  => esc_html__( 'Repeat Horizontally', 'martfury' ),
				'repeat-y'  => esc_html__( 'Repeat Vertically', 'martfury' ),
				'no-repeat' => esc_html__( 'No Repeat', 'martfury' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'promotion',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'promotion_bg_attachments'                => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Background Attachment', 'martfury' ),
			'default'         => 'scroll',
			'section'         => 'promotion',
			'priority'        => 10,
			'choices'         => array(
				'scroll' => esc_html__( 'Scroll', 'martfury' ),
				'fixed'  => esc_html__( 'Fixed', 'martfury' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'promotion',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'promotion_bg_size'                       => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Background Size', 'martfury' ),
			'default'         => 'auto',
			'section'         => 'promotion',
			'priority'        => 10,
			'choices'         => array(
				'auto'    => esc_html__( 'Auto', 'martfury' ),
				'contain' => esc_html__( 'Contain', 'martfury' ),
				'cover'   => esc_html__( 'Cover', 'martfury' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'promotion',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'promotion_style'                         => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Style', 'martfury' ),
			'default'         => '1',
			'section'         => 'promotion',
			'priority'        => 10,
			'choices'         => array(
				'1' => esc_html__( 'Style 1', 'martfury' ),
				'2' => esc_html__( 'Style 2', 'martfury' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'promotion',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'promotion_content'                       => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Content', 'martfury' ),
			'section'         => 'promotion',
			'default'         => '',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'promotion',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'promotion_button_text'                   => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Button Text', 'martfury' ),
			'section'         => 'promotion',
			'default'         => '',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'promotion',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'promotion_button_link'                   => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Button Link', 'martfury' ),
			'section'         => 'promotion',
			'default'         => '',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'promotion',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		// Logo
		'logo'                                    => array(
			'type'        => 'image',
			'label'       => esc_html__( 'Logo', 'martfury' ),
			'description' => esc_html__( 'This logo is used for all site.', 'martfury' ),
			'section'     => 'logo',
			'default'     => '',
			'priority'    => 20,
		),
		'logo_width'                              => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Logo Width(px)', 'martfury' ),
			'section'  => 'logo',
			'priority' => 20,
			array(
				'setting'  => 'logo',
				'operator' => '!=',
				'value'    => '',
			),
		),
		'logo_height'                             => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Logo Height(px)', 'martfury' ),
			'section'  => 'logo',
			'priority' => 20,
			array(
				'setting'  => 'logo',
				'operator' => '!=',
				'value'    => '',
			),
		),
		'logo_margins'                            => array(
			'type'     => 'spacing',
			'label'    => esc_html__( 'Logo Margin', 'martfury' ),
			'section'  => 'logo',
			'priority' => 20,
			'default'  => array(
				'top'    => '0',
				'bottom' => '0',
				'left'   => '0',
				'right'  => '0',
			),
			array(
				'setting'  => 'logo',
				'operator' => '!=',
				'value'    => '',
			),
		),
		'sticky_header'                           => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Sticky Header', 'martfury' ),
			'default'  => '0',
			'section'  => 'header_sticky',
			'priority' => 20,
		),
		'sticky_header_logo'                      => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Sticky Header Logo', 'martfury' ),
			'default'     => '0',
			'section'     => 'header_sticky',
			'priority'    => 20,
			'description' => esc_html__( 'Check this option to enable the header logo in the sticky header instead of the department.', 'martfury' ),
		),
		'logo_sticky'                             => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Logo', 'martfury' ),
			'description'     => esc_html__( 'This logo is used for the sticky header.', 'martfury' ),
			'section'         => 'header_sticky',
			'default'         => '',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'sticky_header_logo',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		// Header layout
		'header_full_width'                       => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Header Full Width', 'martfury' ),
			'default'  => '0',
			'section'  => 'header',
			'priority' => 20,
		),
		'header_layout'                           => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Header Layout', 'martfury' ),
			'section'  => 'header',
			'default'  => '1',
			'priority' => 10,
			'choices'  => array(
				'1' => esc_html__( 'Header 1', 'martfury' ),
				'2' => esc_html__( 'Header 2', 'martfury' ),
				'3' => esc_html__( 'Header 3', 'martfury' ),
				'4' => esc_html__( 'Header 4', 'martfury' ),
				'5' => esc_html__( 'Header 5', 'martfury' ),
				'6' => esc_html__( 'Header 6', 'martfury' ),
				'7' => esc_html__( 'Header 7', 'martfury' ),
			),
		),
		'topbar'                                  => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Enable Top Bar', 'martfury' ),
			'default'     => '0',
			'section'     => 'header',
			'priority'    => 20,
			'description' => esc_html__( 'Go to Appearance > Widgets > Topbar Left and Topbar Right to add widgets content.', 'martfury' ),
		),
		'menu_extras'                             => array(
			'type'     => 'multicheck',
			'label'    => esc_html__( 'Elements', 'martfury' ),
			'section'  => 'header',
			'default'  => array( 'search', 'wishlist', 'cart', 'account', 'custom_text', 'department' ),
			'priority' => 20,
			'choices'  => array(
				'search'     => esc_html__( 'Search', 'martfury' ),
				'compare'    => esc_html__( 'Compare', 'martfury' ),
				'wishlist'   => esc_html__( 'WishList', 'martfury' ),
				'cart'       => esc_html__( 'Cart', 'martfury' ),
				'account'    => esc_html__( 'Account', 'martfury' ),
				'department' => esc_html__( 'Department Menu', 'martfury' ),
				'hotline'    => esc_html__( 'Hotline', 'martfury' ),
			),
		),
		'user_logged_type'                        => array(
			'type'     => 'select',
			'label'    => esc_html__( 'User Logged In Type', 'martfury' ),
			'section'  => 'header_account',
			'default'  => 'icon',
			'priority' => 90,
			'choices'  => array(
				'icon'   => esc_html__( 'Icon', 'martfury' ),
				'avatar' => esc_html__( 'Avatar', 'martfury' ),
			),
		),
		'mini_cart_button'                        => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Display Mini Cart Button', 'martfury' ),
			'section'     => 'header_cart',
			'default'     => '1',
			'priority'    => 90,
			'description' => esc_html__( 'Choose type you want to display mini cart button', 'martfury' ),
			'choices'     => array(
				'1' => esc_html__( 'In line', 'martfury' ),
				'2' => esc_html__( 'In multiple lines', 'martfury' ),
			),
		),
		'custom_menu_right'                       => array(
			'type'            => 'custom',
			'section'         => 'header',
			'default'         => '<hr>',
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => 'in',
					'value'    => array( '1', '3', '6', '7' ),
				),
			),
		),
		'header_bar'                              => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Enable Header Bar', 'martfury' ),
			'section'         => 'header',
			'default'         => 1,
			'priority'        => 90,
			'description'     => esc_html__( 'Go to Appearance > Widgets > Header Bar to add widgets content.', 'martfury' ),
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => 'in',
					'value'    => array( '1', '3', '6', '7' ),
				),
			),
		),
		'custom_department_text'                  => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Department Text', 'martfury' ),
			'section'  => 'header_department',
			'default'  => esc_html__( 'Shop By Department', 'martfury' ),
			'priority' => 90,
		),
		'custom_department_link'                  => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Department Link', 'martfury' ),
			'section'  => 'header_department',
			'priority' => 90,
		),
		'department_open_homepage'                => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Department Menu on Homepage', 'martfury' ),
			'section'  => 'header_department',
			'default'  => 'open',
			'priority' => 90,
			'choices'  => array(
				'open'  => esc_html__( 'Open', 'martfury' ),
				'close' => esc_html__( 'Close', 'martfury' ),
			),
		),
		'department_space_homepage'               => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Department Space on Homepage', 'martfury' ),
			'section'         => 'header_department',
			'default'         => '30px',
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => 'in',
					'value'    => array( '2', '3' ),
				),
			),

			'priority'    => 90,
			'description' => esc_html__( 'Enter space between the title and the menu content of department on homepage. Example 30px', 'martfury' ),
		),
		'department_space_2_homepage'             => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Department Space on Homepage', 'martfury' ),
			'section'         => 'header_department',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => 'in',
					'value'    => array( '1', '4', '5', '6', '7' ),
				),
			),

			'priority'    => 90,
			'description' => esc_html__( 'Enter space(px) between the title and the menu content of department on homepage. Example 30px', 'martfury' ),
		),
		'custom_hotline_text'                     => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Hotline Text', 'martfury' ),
			'section'  => 'header_hotline',
			'default'  => esc_html__( 'Hotline', 'martfury' ),
			'priority' => 90,
		),
		'custom_hotline_number'                   => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Hotline Number', 'martfury' ),
			'section'  => 'header_hotline',
			'priority' => 90,
			'default'  => '1-800-234-5678',
		),
		'search_form_type'                        => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Search Form Type', 'martfury' ),
			'section'  => 'header_search_content',
			'default'  => 'default',
			'priority' => 90,
			'choices'  => array(
				'default'   => esc_html__( 'Default', 'martfury' ),
				'shortcode' => esc_html__( 'Use Shortcode', 'martfury' ),
			),
		),
		'search_form_shortcode'                   => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Search Form', 'martfury' ),
			'section'         => 'header_search_content',
			'default'         => '',
			'description'     => esc_html__( 'Add search form using shortcode such as [aws_search_form id="1"]', 'martfury' ),
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'search_form_type',
					'operator' => '==',
					'value'    => 'shortcode',
				),
			),
		),
		'search_content_type'                     => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Search For', 'martfury' ),
			'section'         => 'header_search_content',
			'default'         => 'product',
			'priority'        => 90,
			'choices'         => array(
				'all'     => esc_html__( 'Everything', 'martfury' ),
				'product' => esc_html__( 'Only Products', 'martfury' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'search_form_type',
					'operator' => '==',
					'value'    => 'default',
				),
			),
		),
		'custom_search_categories'                => array(
			'type'            => 'custom',
			'label'           => '<hr>',
			'section'         => 'header_search_content',
			'default'         => '<h3>' . esc_html__( 'Product Categories', 'martfury' ) . '</h3>',
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'search_form_type',
					'operator' => '==',
					'value'    => 'default',
				),
				array(
					'setting'  => 'search_content_type',
					'operator' => '==',
					'value'    => 'product',
				),
			),
		),
		'search_product_categories'               => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Enable Categories', 'martfury' ),
			'section'         => 'header_search_content',
			'default'         => 1,
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'search_form_type',
					'operator' => '==',
					'value'    => 'default',
				),
				array(
					'setting'  => 'search_content_type',
					'operator' => '==',
					'value'    => 'product',
				),
			),
		),
		'custom_categories_text'                  => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Categories Text', 'martfury' ),
			'section'         => 'header_search_content',
			'default'         => esc_html__( 'All', 'martfury' ),
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'search_form_type',
					'operator' => '==',
					'value'    => 'default',
				),
				array(
					'setting'  => 'search_content_type',
					'operator' => '==',
					'value'    => 'product',
				),
			),
		),
		'custom_categories_depth'                 => array(
			'type'            => 'number',
			'label'           => esc_html__( 'Categories Depth', 'martfury' ),
			'section'         => 'header_search_content',
			'default'         => 0,
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'search_form_type',
					'operator' => '==',
					'value'    => 'default',
				),
				array(
					'setting'  => 'search_content_type',
					'operator' => '==',
					'value'    => 'product',
				),
			),
		),
		'custom_categories_include'               => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Categories Include', 'martfury' ),
			'section'         => 'header_search_content',
			'default'         => '',
			'description'     => esc_html__( 'Enter Category IDs to include. Divide every category by comma(,)', 'martfury' ),
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'search_form_type',
					'operator' => '==',
					'value'    => 'default',
				),
				array(
					'setting'  => 'search_content_type',
					'operator' => '==',
					'value'    => 'product',
				),
			),
		),
		'custom_categories_exclude'               => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Categories Exclude', 'martfury' ),
			'section'         => 'header_search_content',
			'default'         => '',
			'description'     => esc_html__( 'Enter Category IDs to exclude. Divide every category by comma(,)', 'martfury' ),
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'search_form_type',
					'operator' => '==',
					'value'    => 'default',
				),
				array(
					'setting'  => 'search_content_type',
					'operator' => '==',
					'value'    => 'product',
				),
			),
		),
		'custom_search_content'                   => array(
			'type'            => 'custom',
			'label'           => '<hr>',
			'section'         => 'header_search_content',
			'default'         => '<h3>' . esc_html__( 'Search Content', 'martfury' ) . '</h3>',
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'search_form_type',
					'operator' => '==',
					'value'    => 'default',
				),
			),
		),
		'custom_search_text'                      => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Search Text', 'martfury' ),
			'section'         => 'header_search_content',
			'default'         => esc_html__( "I'm shopping for...", 'martfury' ),
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'search_form_type',
					'operator' => '==',
					'value'    => 'default',
				),
			),
		),
		'custom_search_button'                    => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Button Text', 'martfury' ),
			'section'         => 'header_search_content',
			'default'         => esc_html__( 'Search', 'martfury' ),
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'search_form_type',
					'operator' => '==',
					'value'    => 'default',
				),
			),
		),
		'custom_search_ajax'                      => array(
			'type'            => 'custom',
			'label'           => '<hr>',
			'section'         => 'header_search_content',
			'default'         => '<h3>' . esc_html__( 'Search AJAX', 'martfury' ) . '</h3>',
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'search_form_type',
					'operator' => '==',
					'value'    => 'default',
				),
			),
		),
		'header_ajax_search'                      => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'AJAX Search', 'martfury' ),
			'section'         => 'header_search_content',
			'default'         => 1,
			'priority'        => 90,
			'description'     => esc_html__( 'Check this option to enable AJAX search in the header', 'martfury' ),
			'active_callback' => array(
				array(
					'setting'  => 'search_form_type',
					'operator' => '==',
					'value'    => 'default',
				),
			),
		),
		'header_ajax_search_results_number'       => array(
			'type'            => 'number',
			'label'           => esc_html__( 'AJAX Search Results Number', 'martfury' ),
			'section'         => 'header_search_content',
			'default'         => 7,
			'priority'        => 90,
			'description'     => esc_html__( 'The number of maximum results in the search box', 'martfury' ),
			'active_callback' => array(
				array(
					'setting'  => 'search_form_type',
					'operator' => '==',
					'value'    => 'default',
				),
			),
		),
		'header_hot_words_custom_'                => array(
			'type'     => 'custom',
			'label'    => '<hr>',
			'default'  => '<h3>' . esc_html__( 'Hot Words', 'martfury' ) . '</h3>',
			'section'  => 'header_search_content',
			'priority' => 90,
		),
		'header_hot_words_enable'                 => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Enable Hot Words', 'martfury' ),
			'section'     => 'header_search_content',
			'default'     => 0,
			'priority'    => 90,
			'description' => esc_html__( 'Check this option to enable hot words below search box in the header', 'martfury' ),
		),
		'header_hot_words'                        => array(
			'type'      => 'repeater',
			'label'     => esc_html__( 'Hot Words', 'martfury' ),
			'section'   => 'header_search_content',
			'priority'  => 90,
			'default'   => array(),
			'row_label' => array(
				'type'  => 'text',
				'value' => esc_html__( 'Word', 'martfury' ),
			),
			'fields'    => array(
				'text' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Text', 'martfury' ),
					'default' => '',
				),
				'link' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Link', 'martfury' ),
					'default' => '',
				),
			),
		),
		'header_recently_viewed'                  => array(
			'type'            => 'toggle',
			'section'         => 'header_recently_viewed',
			'label'           => esc_html__( 'Show Recently Viewed', 'martfury' ),
			'default'         => 1,
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => 'in',
					'value'    => array( '3' ),
				),
			),
		),
		'header_recently_viewed_title'            => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Recently Viewed Title', 'martfury' ),
			'section'         => 'header_recently_viewed',
			'default'         => esc_html__( 'Your Recently Viewed', 'martfury' ),
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => 'in',
					'value'    => array( '3' ),
				),
			),
		),
		'header_recently_viewed_link_text'        => array(
			'type'            => 'text',
			'label'           => esc_html__( 'View All Text', 'martfury' ),
			'section'         => 'header_recently_viewed',
			'default'         => '',
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => 'in',
					'value'    => array( '3' ),
				),
			),
		),
		'header_recently_viewed_link_url'         => array(
			'type'            => 'text',
			'label'           => esc_html__( 'View All Link', 'martfury' ),
			'section'         => 'header_recently_viewed',
			'default'         => '',
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => 'in',
					'value'    => array( '3' ),
				),
			),
		),
		'header_recently_viewed_number'           => array(
			'type'            => 'number',
			'label'           => esc_html__( 'Products Number', 'martfury' ),
			'section'         => 'header_recently_viewed',
			'default'         => 12,
			'priority'        => 90,
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => 'in',
					'value'    => array( '3' ),
				),
			),
		),
		// Header Skin
		'custom_header_skin'                      => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Custom Header Skin', 'martfury' ),
			'section'  => 'header_skin',
			'default'  => '1',
			'priority' => 20,
		),
		'topbar_bg_color'                         => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Top Bar Background Color', 'martfury' ),
			'default'         => '',
			'section'         => 'header_skin',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '#topbar',
					'property' => 'background-color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'topbar_text_color'                       => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Top Bar Text Color', 'martfury' ),
			'default'         => '',
			'section'         => 'header_skin',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '#topbar, #topbar a, #topbar #lang_sel > ul > li > a, #topbar .mf-currency-widget .current:after, #topbar  .lang_sel > ul > li > a:after, #topbar  #lang_sel > ul > li > a:after',
					'property' => 'color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'topbar_hover_color'                      => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Top Bar Hover Color', 'martfury' ),
			'default'         => '',
			'section'         => 'header_skin',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '#topbar a:hover, #topbar .mf-currency-widget .current:hover, #topbar #lang_sel > ul > li > a:hover',
					'property' => 'color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'topbar_border_color'                     => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Top Bar Border Color', 'martfury' ),
			'default'         => '',
			'section'         => 'header_skin',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '#topbar .widget:after',
					'property' => 'background-color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'topbar_custom_1'                         => array(
			'type'            => 'custom',
			'default'         => '<hr/>',
			'section'         => 'header_skin',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'header_bg_color'                         => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Search Bar Background Color', 'martfury' ),
			'default'         => '',
			'section'         => 'header_skin',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '#site-header, #site-header .header-main',
					'property' => 'background-color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'header_text_color'                       => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Search Bar Text Color', 'martfury' ),
			'default'         => '',
			'section'         => 'header_skin',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '#site-header .extras-menu > li > a, #site-header .product-extra-search .hot-words li a, #site-header .menu-item-hotline .hotline-content,#site-header .extras-menu .menu-item-hotline .extra-icon, #site-header .extras-menu .menu-item-hotline .hotline-content label',
					'property' => 'color',
				),
				array(
					'element'  => '#site-header .header-logo .products-cats-menu .cats-menu-title,#site-header .header-logo .products-cats-menu .cats-menu-title .text, #site-header .mobile-menu-row .mf-toggle-menu',
					'property' => 'color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'header_hover_color'                      => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Search Bar Hover Color', 'martfury' ),
			'default'         => '',
			'section'         => 'header_skin',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '#site-header:not(.minimized) .product-extra-search .hot-words li a:hover',
					'property' => 'color',
				),
				array(
					'element'  => '#site-header .header-bar a:hover,#site-header .primary-nav > ul > li > a:hover, #site-header .header-bar a:hover',
					'property' => 'color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'search_button_bg_color'                  => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Button & Counter Background Color', 'martfury' ),
			'default'         => '',
			'section'         => 'header_skin',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '#site-header .product-extra-search .search-submit, #site-header .extras-menu > li > a .mini-item-counter',
					'property' => 'background-color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'search_button_text_color'                => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Button & Counter Text Color', 'martfury' ),
			'default'         => '',
			'section'         => 'header_skin',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '#site-header .product-extra-search .search-submit, #site-header .extras-menu > li > a .mini-item-counter',
					'property' => 'color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'menu_bar_custom_1'                       => array(
			'type'            => 'custom',
			'default'         => '<hr/>',
			'section'         => 'header_skin',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'shop_department_border_color'            => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Shop By Department Border Color', 'martfury' ),
			'default'         => '',
			'section'         => 'header_skin',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.header-layout-3 #site-header .products-cats-menu:before, .header-layout-1 #site-header .products-cats-menu:before',
					'property' => 'background-color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
				array(
					'setting'  => 'header_layout',
					'operator' => 'in',
					'value'    => array( '1', '3' ),
				),
			),
		),
		'menu_bar_bg_color'                       => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Menu Bar Background Color', 'martfury' ),
			'default'         => '',
			'section'         => 'header_skin',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '#site-header .main-menu',
					'property' => 'background-color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'menu_bar_text_color'                     => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Menu Bar Text Color', 'martfury' ),
			'default'         => '',
			'section'         => 'header_skin',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '#site-header .header-bar a, #site-header .recently-viewed .recently-title,#site-header:not(.minimized) .main-menu .products-cats-menu .cats-menu-title .text, #site-header:not(.minimized) .main-menu .products-cats-menu .cats-menu-title, #site-header .main-menu .primary-nav > ul > li > a, #site-header .main-menu .header-bar',
					'property' => 'color',
				),
				array(
					'element'  => '#site-header .header-bar #lang_sel > ul > li > a, #site-header .header-bar .lang_sel > ul > li > a, #site-header .header-bar #lang_sel > ul > li > a:after, #site-header .header-bar .lang_sel > ul > li > a:after, #site-header .header-bar .mf-currency-widget .current:after',
					'property' => 'color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'menu_bar_hover_color'                    => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Menu Bar Hover Color', 'martfury' ),
			'default'         => '',
			'section'         => 'header_skin',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '#site-header .header-bar a:hover,#site-header .primary-nav > ul > li:hover > a, #site-header .header-bar #lang_sel  > ul > li > a:hover, #site-header .header-bar .lang_sel > ul > li > a:hover, #site-header .header-bar #lang_sel > ul > li > a:hover:after, #site-header .header-bar .lang_sel > ul > li > a:hover:after, #site-header .header-bar .mf-currency-widget .current:hover,#site-header .header-bar .mf-currency-widget .current:hover:after',
					'property' => 'color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'menu_bar_active_color'                   => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Menu Bar Active Color', 'martfury' ),
			'default'         => '',
			'section'         => 'header_skin',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '#site-header .primary-nav > ul > li.current-menu-parent > a, #site-header .primary-nav > ul > li.current-menu-item > a, #site-header .primary-nav > ul > li.current-menu-ancestor > a',
					'property' => 'color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'menu_bar_border_color'                   => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Menu Bar Border Color', 'martfury' ),
			'default'         => '',
			'section'         => 'header_skin',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '#site-header .main-menu',
					'property' => 'border-color',
				),
				array(
					'element'  => '#site-header .header-bar .widget:after',
					'property' => 'background-color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'header_bottom_custom_1'                  => array(
			'type'            => 'custom',
			'default'         => '<hr/>',
			'section'         => 'header_skin',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'header_bottom_bg'                        => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Header Bottom Background', 'martfury' ),
			'section'         => 'header_skin',
			'default'         => '',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),

		),
		'header_bottom_height'                    => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Header Bottom Height', 'martfury' ),
			'section'  => 'header_skin',
			'default'  => 9,
			'priority' => 20,
			'choices'  => [
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
			],
			'active_callback' => array(
				array(
					'setting'  => 'custom_header_skin',
					'operator' => '==',
					'value'    => '1',
				),
			),

		),
		// Catalog
		'catalog_full_width_1'                    => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Full Width', 'martfury' ),
			'section'  => 'catalog_layout_1',
			'default'  => 0,
			'priority' => 70,
		),
		'catalog_banners_1'                       => array(
			'type'      => 'repeater',
			'label'     => esc_html__( 'Banners', 'martfury' ),
			'section'   => 'catalog_layout_1',
			'default'   => '',
			'priority'  => 70,
			'row_label' => array(
				'type'  => 'text',
				'value' => esc_html__( 'Banner', 'martfury' ),
			),
			'fields'    => array(
				'image'    => array(
					'type'    => 'image',
					'label'   => esc_html__( 'Image', 'martfury' ),
					'default' => '',
				),
				'link_url' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Link(URL)', 'martfury' ),
					'default' => '',
				),
			),
		),
		'catalog_banners_autoplay_1'              => array(
			'type'        => 'number',
			'label'       => esc_html__( 'Banners Autoplay', 'martfury' ),
			'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
			'section'     => 'catalog_layout_1',
			'default'     => '0',
			'priority'    => 70,
		),
		'catalog_brands_custom_1'                 => array(
			'type'     => 'custom',
			'label'    => '<hr/>',
			'default'  => '<h2>' . esc_html__( 'Brands Grid', 'martfury' ) . '</h2>',
			'section'  => 'catalog_layout_1',
			'priority' => 70,
		),
		'catalog_brands_number_1'                 => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Brands Number', 'martfury' ),
			'section'  => 'catalog_layout_1',
			'default'  => '6',
			'priority' => 70,
		),
		'catalog_brands_orderby_1'                => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Order By', 'martfury' ),
			'section'  => 'catalog_layout_1',
			'default'  => 'order',
			'priority' => 70,
			'choices'  => array(
				'order' => esc_html__( 'Brand Order', 'martfury' ),
				'name'  => esc_html__( 'Name', 'martfury' ),
				'count' => esc_html__( 'Count', 'martfury' ),
			),
		),
		'catalog_categories_custom_1'             => array(
			'type'     => 'custom',
			'label'    => '<hr/>',
			'default'  => '<h2>' . esc_html__( 'Categories Box', 'martfury' ) . '</h2>',
			'section'  => 'catalog_layout_1',
			'priority' => 70,
		),
		'catalog_categories_number_1'             => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Parent Categories Number', 'martfury' ),
			'section'  => 'catalog_layout_1',
			'default'  => '6',
			'priority' => 70,
		),
		'catalog_subcategories_number_1'          => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Children Categories Number', 'martfury' ),
			'section'  => 'catalog_layout_1',
			'default'  => '5',
			'priority' => 70,
		),
		'catalog_categories_orderby_1'            => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Order By', 'martfury' ),
			'section'  => 'catalog_layout_1',
			'default'  => 'order',
			'priority' => 70,
			'choices'  => array(
				'order' => esc_html__( 'Category Order', 'martfury' ),
				'title' => esc_html__( 'Name', 'martfury' ),
				'count' => esc_html__( 'Count', 'martfury' ),
			),
		),
		'catalog_featured_custom_1'               => array(
			'type'     => 'custom',
			'default'  => '<hr/>',
			'section'  => 'catalog_layout_1',
			'priority' => 70,
		),
		'catalog_products_carousel_1'             => array(
			'type'      => 'repeater',
			'label'     => esc_html__( 'Products Carousel', 'martfury' ),
			'section'   => 'catalog_layout_1',
			'default'   => '',
			'priority'  => 70,
			'row_label' => array(
				'type'  => 'text',
				'value' => esc_html__( 'Products Carousel', 'martfury' ),
			),
			'fields'    => array(
				'title'    => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Title', 'martfury' ),
					'default' => '',
				),
				'number'   => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Number', 'martfury' ),
					'default' => '',
				),
				'columns'  => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Columns', 'martfury' ),
					'default' => '5',
					'choices' => array(
						'4' => esc_html__( '4 Columns', 'martfury' ),
						'6' => esc_html__( '6 Columns', 'martfury' ),
						'5' => esc_html__( '5 Columns', 'martfury' ),
						'3' => esc_html__( '3 Columns', 'martfury' ),
					),
				),
				'autoplay' => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Autoplay', 'martfury' ),
					'default'     => '',
					'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
				),
				'type'     => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Type', 'martfury' ),
					'default' => '1',
					'choices' => array(
						'1' => esc_html__( 'Featured Products', 'martfury' ),
						'2' => esc_html__( 'Best Seller Products', 'martfury' ),
						'3' => esc_html__( 'Sale Products', 'martfury' ),
						'4' => esc_html__( 'Recent Products', 'martfury' ),
						'5' => esc_html__( 'Top Rated Products', 'martfury' ),
					),
				),
			),
		),
		'catalog_sidebar_custom_1'                => array(
			'type'     => 'custom',
			'default'  => '<hr/>',
			'section'  => 'catalog_layout_1',
			'priority' => 70,
		),
		'catalog_sidebar_1'                       => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Sidebar', 'martfury' ),
			'default'     => 'sidebar-content',
			'section'     => 'catalog_layout_1',
			'priority'    => 70,
			'description' => esc_html__( 'Select default layout for this layout.', 'martfury' ),
			'choices'     => array(
				'full-content'    => esc_html__( 'No Sidebar', 'martfury' ),
				'sidebar-content' => esc_html__( 'Left Sidebar', 'martfury' ),
				'content-sidebar' => esc_html__( 'Right Sidebar', 'martfury' ),
			),
		),
		'catalog_toolbar_els_1'                   => array(
			'type'        => 'multicheck',
			'label'       => esc_html__( 'ToolBar Elements', 'martfury' ),
			'section'     => 'catalog_layout_1',
			'default'     => array( 'found', 'sortby', 'view' ),
			'priority'    => 70,
			'choices'     => array(
				'found'  => esc_html__( 'Products Found', 'martfury' ),
				'sortby' => esc_html__( 'Sort By', 'martfury' ),
				'view'   => esc_html__( 'View', 'martfury' ),
			),
			'description' => esc_html__( 'Select which elements you want to show.', 'martfury' ),
		),
		'catalog_view_1'                          => array(
			'type'     => 'select',
			'label'    => esc_html__( 'View', 'martfury' ),
			'section'  => 'catalog_layout_1',
			'default'  => 'grid',
			'priority' => 70,
			'choices'  => array(
				'grid' => esc_html__( 'Grid', 'martfury' ),
				'list' => esc_html__( 'List', 'martfury' ),
			),
		),
		'products_columns_1'                      => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Product Columns', 'martfury' ),
			'section'     => 'catalog_layout_1',
			'default'     => '4',
			'priority'    => 80,
			'choices'     => array(
				'4' => esc_html__( '4 Columns', 'martfury' ),
				'6' => esc_html__( '6 Columns', 'martfury' ),
				'5' => esc_html__( '5 Columns', 'martfury' ),
				'3' => esc_html__( '3 Columns', 'martfury' ),
				'2' => esc_html__( '2 Columns', 'martfury' ),
			),
			'description' => esc_html__( 'Specify how many product columns you want to show.', 'martfury' ),
		),
		'products_per_page_1'                     => array(
			'type'        => 'number',
			'label'       => esc_html__( 'Product Numbers Per Page', 'martfury' ),
			'section'     => 'catalog_layout_1',
			'default'     => 12,
			'priority'    => 90,
			'description' => esc_html__( 'Specify how many products you want to show on the catalog page', 'martfury' ),
		),
		// Catalog 2
		'catalog_categories_custom_2'             => array(
			'type'     => 'custom',
			'default'  => '<h2>' . esc_html__( 'Top Categories', 'martfury' ) . '</h2>',
			'section'  => 'catalog_layout_2',
			'priority' => 70,
		),
		'catalog_categories_list_title_2'         => array(
			'type'     => 'text',
			'label'    => esc_html__( ' Categories List Title', 'martfury' ),
			'section'  => 'catalog_layout_2',
			'default'  => esc_html__( 'Categories', 'martfury' ),
			'priority' => 70,
		),
		'catalog_categories_list_number_2'        => array(
			'type'     => 'number',
			'label'    => esc_html__( ' Categories List Number', 'martfury' ),
			'section'  => 'catalog_layout_2',
			'default'  => '7',
			'priority' => 70,
		),
		'catalog_categories_list_orderby_2'       => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Categories List Order By', 'martfury' ),
			'section'  => 'catalog_layout_2',
			'default'  => 'order',
			'priority' => 70,
			'choices'  => array(
				'order' => esc_html__( 'Category Order', 'martfury' ),
				'title' => esc_html__( 'Name', 'martfury' ),
				'count' => esc_html__( 'Count', 'martfury' ),
			),
		),
		'catalog_categories_grid_number_2'        => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Categories Grid Number', 'martfury' ),
			'section'  => 'catalog_layout_2',
			'default'  => '6',
			'priority' => 70,
		),
		'catalog_categories_grid_orderby_2'       => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Categories Grid Order By', 'martfury' ),
			'section'  => 'catalog_layout_2',
			'default'  => 'order',
			'priority' => 70,
			'choices'  => array(
				'order' => esc_html__( 'Category Order', 'martfury' ),
				'title' => esc_html__( 'Name', 'martfury' ),
				'count' => esc_html__( 'Count', 'martfury' ),
			),
		),
		'catalog_featured_custom_2'               => array(
			'type'     => 'custom',
			'label'    => '<hr/>',
			'default'  => '<h2>' . esc_html__( 'Products Carousel', 'martfury' ) . '</h2>',
			'section'  => 'catalog_layout_2',
			'priority' => 70,
		),
		'catalog_products_carousel_2'             => array(
			'type'      => 'repeater',
			'label'     => '',
			'section'   => 'catalog_layout_2',
			'default'   => '',
			'priority'  => 70,
			'row_label' => array(
				'type'  => 'text',
				'value' => esc_html__( 'Products Carousel', 'martfury' ),
			),
			'fields'    => array(
				'title'              => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Title', 'martfury' ),
					'default' => '',
				),
				'number'             => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Number', 'martfury' ),
					'default' => '12',
				),
				'columns'            => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Columns', 'martfury' ),
					'default' => '5',
					'choices' => array(
						'4' => esc_html__( '4 Columns', 'martfury' ),
						'6' => esc_html__( '6 Columns', 'martfury' ),
						'5' => esc_html__( '5 Columns', 'martfury' ),
						'3' => esc_html__( '3 Columns', 'martfury' ),
					),
				),
				'autoplay'           => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Autoplay', 'martfury' ),
					'default'     => '',
					'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
				),
				'type'               => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Type', 'martfury' ),
					'default' => '1',
					'choices' => array(
						'1' => esc_html__( 'Featured Products', 'martfury' ),
						'2' => esc_html__( 'Best Seller Products', 'martfury' ),
						'3' => esc_html__( 'Sale Products', 'martfury' ),
						'4' => esc_html__( 'Recent Products', 'martfury' ),
						'5' => esc_html__( 'Top Rated Products', 'martfury' ),
					),
				),
				'categories'         => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Show Categories', 'martfury' ),
					'default' => '',
				),
				'categories_orderby' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Categories Order By', 'martfury' ),
					'default' => 'order',
					'choices' => array(
						'order' => esc_html__( 'Category Order', 'martfury' ),
						'title' => esc_html__( 'Name', 'martfury' ),
						'count' => esc_html__( 'Count', 'martfury' ),
					),
				),
			),
		),
		'catalog_featured_categories_custom_2'    => array(
			'type'     => 'custom',
			'label'    => '<hr/>',
			'default'  => '<h2>' . esc_html__( 'Featured Categories', 'martfury' ) . '</h2>',
			'section'  => 'catalog_layout_2',
			'priority' => 70,
		),
		'catalog_featured_categories_number_2'    => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Categories Number', 'martfury' ),
			'default'  => '2',
			'section'  => 'catalog_layout_2',
			'priority' => 70,
		),
		'catalog_featured_subcategories_number_2' => array(
			'type'     => 'number',
			'label'    => esc_html__( 'SubCategories Number', 'martfury' ),
			'default'  => '7',
			'section'  => 'catalog_layout_2',
			'priority' => 70,
		),
		'catalog_featured_categories_orderby_2'   => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Order By', 'martfury' ),
			'section'  => 'catalog_layout_2',
			'default'  => 'order',
			'priority' => 70,
			'choices'  => array(
				'order' => esc_html__( 'Category Order', 'martfury' ),
				'title' => esc_html__( 'Name', 'martfury' ),
				'count' => esc_html__( 'Count', 'martfury' ),
			),
		),
		'catalog_featured_banner_2'               => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Banner', 'martfury' ),
			'section'  => 'catalog_layout_2',
			'default'  => '1',
			'priority' => 70,
		),
		'catalog_featured_new_text_2'             => array(
			'type'     => 'text',
			'label'    => esc_html__( 'New Arrivals Text', 'martfury' ),
			'section'  => 'catalog_layout_2',
			'default'  => esc_html__( 'New Arrivals', 'martfury' ),
			'priority' => 70,
		),
		'catalog_featured_best_seller_text_2'     => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Best Seller Text', 'martfury' ),
			'section'  => 'catalog_layout_2',
			'default'  => esc_html__( 'Best Seller', 'martfury' ),
			'priority' => 70,
		),
		'catalog_other_categories_custom_2'       => array(
			'type'     => 'custom',
			'label'    => '<hr/>',
			'default'  => '<h2>' . esc_html__( 'Other Categories', 'martfury' ) . '</h2>',
			'section'  => 'catalog_layout_2',
			'priority' => 70,
		),
		'catalog_other_categories_title_2'        => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Title', 'martfury' ),
			'section'  => 'catalog_layout_2',
			'default'  => esc_html__( 'More Categories', 'martfury' ),
			'priority' => 70,
		),
		'catalog_other_categories_number_2'       => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Categories Number', 'martfury' ),
			'default'  => '5',
			'section'  => 'catalog_layout_2',
			'priority' => 70,
		),
		'catalog_other_categories_orderby_2'      => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Order By', 'martfury' ),
			'section'  => 'catalog_layout_2',
			'default'  => 'order',
			'priority' => 70,
			'choices'  => array(
				'order' => esc_html__( 'Category Order', 'martfury' ),
				'title' => esc_html__( 'Name', 'martfury' ),
				'count' => esc_html__( 'Count', 'martfury' ),
			),
		),
		// Catalog 3
		'catalog_full_width_3'                    => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Full Width', 'martfury' ),
			'section'  => 'catalog_layout_3',
			'default'  => 0,
			'priority' => 70,
		),
		'catalog_banners_3'                       => array(
			'type'      => 'repeater',
			'label'     => esc_html__( 'Banners', 'martfury' ),
			'section'   => 'catalog_layout_3',
			'default'   => '',
			'priority'  => 70,
			'row_label' => array(
				'type'  => 'text',
				'value' => esc_html__( 'Banner', 'martfury' ),
			),
			'fields'    => array(
				'image'    => array(
					'type'    => 'image',
					'label'   => esc_html__( 'Image', 'martfury' ),
					'default' => '',
				),
				'link_url' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Link(URL)', 'martfury' ),
					'default' => '',
				),
			),
		),
		'catalog_banners_autoplay_3'              => array(
			'type'        => 'number',
			'label'       => esc_html__( 'Banners Autoplay', 'martfury' ),
			'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
			'section'     => 'catalog_layout_3',
			'default'     => '0',
			'priority'    => 70,
		),
		'catalog_featured_custom_3'               => array(
			'type'     => 'custom',
			'default'  => '<hr/>',
			'section'  => 'catalog_layout_3',
			'priority' => 70,
		),
		'catalog_products_carousel_3'             => array(
			'type'      => 'repeater',
			'label'     => esc_html__( 'Products Carousel', 'martfury' ),
			'section'   => 'catalog_layout_3',
			'default'   => '',
			'priority'  => 70,
			'row_label' => array(
				'type'  => 'text',
				'value' => esc_html__( 'Products Carousel', 'martfury' ),
			),
			'fields'    => array(
				'title'    => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Title', 'martfury' ),
					'default' => '',
				),
				'number'   => array(
					'type'    => 'number',
					'label'   => esc_html__( 'Number', 'martfury' ),
					'default' => '',
				),
				'columns'  => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Columns', 'martfury' ),
					'default' => '5',
					'choices' => array(
						'4' => esc_html__( '4 Columns', 'martfury' ),
						'6' => esc_html__( '6 Columns', 'martfury' ),
						'5' => esc_html__( '5 Columns', 'martfury' ),
						'3' => esc_html__( '3 Columns', 'martfury' ),
					),
				),
				'autoplay' => array(
					'type'        => 'number',
					'label'       => esc_html__( 'Autoplay', 'martfury' ),
					'default'     => '',
					'description' => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
				),
				'type'     => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Type', 'martfury' ),
					'default' => '1',
					'choices' => array(
						'1' => esc_html__( 'Featured Products', 'martfury' ),
						'2' => esc_html__( 'Best Seller Products', 'martfury' ),
						'3' => esc_html__( 'Sale Products', 'martfury' ),
						'4' => esc_html__( 'Recent Products', 'martfury' ),
						'5' => esc_html__( 'Top Rated Products', 'martfury' ),
					),
				),

			),
		),
		'catalog_sidebar_custom_3'                => array(
			'type'     => 'custom',
			'default'  => '<hr/>',
			'section'  => 'catalog_layout_3',
			'priority' => 70,
		),
		'catalog_sidebar_3'                       => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Sidebar', 'martfury' ),
			'default'     => 'sidebar-content',
			'section'     => 'catalog_layout_3',
			'priority'    => 70,
			'description' => esc_html__( 'Select default layout for this layout.', 'martfury' ),
			'choices'     => array(
				'full-content'    => esc_html__( 'No Sidebar', 'martfury' ),
				'sidebar-content' => esc_html__( 'Left Sidebar', 'martfury' ),
				'content-sidebar' => esc_html__( 'Right Sidebar', 'martfury' ),
			),
		),
		'catalog_toolbar_els_3'                   => array(
			'type'        => 'multicheck',
			'label'       => esc_html__( 'ToolBar Elements', 'martfury' ),
			'section'     => 'catalog_layout_3',
			'default'     => array( 'found', 'sortby', 'view' ),
			'priority'    => 70,
			'choices'     => array(
				'found'  => esc_html__( 'Products Found', 'martfury' ),
				'sortby' => esc_html__( 'Sort By', 'martfury' ),
				'view'   => esc_html__( 'View', 'martfury' ),
			),
			'description' => esc_html__( 'Select which elements you want to show.', 'martfury' ),
		),
		'catalog_view_3'                          => array(
			'type'     => 'select',
			'label'    => esc_html__( 'View', 'martfury' ),
			'section'  => 'catalog_layout_3',
			'default'  => 'grid',
			'priority' => 70,
			'choices'  => array(
				'grid' => esc_html__( 'Grid', 'martfury' ),
				'list' => esc_html__( 'List', 'martfury' ),
			),
		),
		'products_columns_3'                      => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Product Columns', 'martfury' ),
			'section'     => 'catalog_layout_3',
			'default'     => '4',
			'priority'    => 80,
			'choices'     => array(
				'4' => esc_html__( '4 Columns', 'martfury' ),
				'6' => esc_html__( '6 Columns', 'martfury' ),
				'5' => esc_html__( '5 Columns', 'martfury' ),
				'3' => esc_html__( '3 Columns', 'martfury' ),
				'2' => esc_html__( '2 Columns', 'martfury' ),
			),
			'description' => esc_html__( 'Specify how many product columns you want to show.', 'martfury' ),
		),
		'products_per_page_3'                     => array(
			'type'        => 'number',
			'label'       => esc_html__( 'Product Numbers Per Page', 'martfury' ),
			'section'     => 'catalog_layout_3',
			'default'     => 12,
			'priority'    => 90,
			'description' => esc_html__( 'Specify how many products you want to show on the catalog page', 'martfury' ),
		),
		'catalog_ajax_filter'                     => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'AJAX For Filtering', 'martfury' ),
			'section'     => 'catalog_page',
			'description' => esc_html__( 'Check this option to use AJAX for filtering in the catalog page.', 'martfury' ),
			'default'     => 0,
			'priority'    => 70,
		),
		'added_to_cart_notice'                    => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Added to Cart Notification', 'martfury' ),
			'description' => esc_html__( 'Display a notification when a product is added to cart', 'martfury' ),
			'section'     => 'catalog_page',
			'priority'    => 70,
			'default'     => 0,
		),
		'cart_notice_auto_hide'                   => array(
			'type'        => 'number',
			'label'       => esc_html__( 'Cart Notification Auto Hide', 'martfury' ),
			'description' => esc_html__( 'How many seconds you want to hide the notification.', 'martfury' ),
			'section'     => 'catalog_page',
			'priority'    => 70,
			'default'     => 3,
		),
		// Other Catlog
		'catalog_sidebar_10'                      => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Catalog Layout', 'martfury' ),
			'default'     => 'sidebar-content',
			'section'     => 'catalog_page',
			'priority'    => 70,
			'description' => esc_html__( 'Select default layout for this layout.', 'martfury' ),
			'choices'     => array(
				'full-content'    => esc_html__( 'No Sidebar', 'martfury' ),
				'sidebar-content' => esc_html__( 'Left Sidebar', 'martfury' ),
				'content-sidebar' => esc_html__( 'Right Sidebar', 'martfury' ),
			),
		),
		'catalog_full_width_10'                   => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Full Width', 'martfury' ),
			'section'  => 'catalog_page',
			'default'  => 0,
			'priority' => 70,
		),
		'shop_els_10'                             => array(
			'type'     => 'multicheck',
			'label'    => esc_html__( 'Page Header Elements', 'martfury' ),
			'section'  => 'catalog_page',
			'default'  => array( 'breadcrumb' ),
			'priority' => 70,
			'choices'  => array(
				'title'      => esc_html__( 'Title', 'martfury' ),
				'breadcrumb' => esc_html__( 'Breadcrumb', 'martfury' ),
			),
		),
		'catalog_toolbar_els_10'                  => array(
			'type'        => 'multicheck',
			'label'       => esc_html__( 'ToolBar Elements', 'martfury' ),
			'section'     => 'catalog_page',
			'default'     => array( 'found', 'sortby', 'view' ),
			'priority'    => 70,
			'choices'     => array(
				'found'  => esc_html__( 'Products Found', 'martfury' ),
				'sortby' => esc_html__( 'Sort By', 'martfury' ),
				'view'   => esc_html__( 'View', 'martfury' ),
			),
			'description' => esc_html__( 'Select which elements you want to show.', 'martfury' ),
		),
		'catalog_filter_mobile_10'                => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Filter On Mobile', 'martfury' ),
			'section'         => 'catalog_page',
			'default'         => 1,
			'priority'        => 70,
			'description'     => esc_html__( 'Check this option to show filter on mobile', 'martfury' ),
			'active_callback' => array(
				array(
					'setting'  => 'enable_mobile_version',
					'operator' => '==',
					'value'    => '0',
				),
			),
		),
		'catalog_view_10'                         => array(
			'type'     => 'select',
			'label'    => esc_html__( 'View', 'martfury' ),
			'section'  => 'catalog_page',
			'default'  => 'grid',
			'priority' => 70,
			'choices'  => array(
				'grid' => esc_html__( 'Grid', 'martfury' ),
				'list' => esc_html__( 'List', 'martfury' ),
			),
		),
		'products_columns_10'                     => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Product Columns', 'martfury' ),
			'section'         => 'catalog_page',
			'default'         => '4',
			'priority'        => 80,
			'choices'         => array(
				'4' => esc_html__( '4 Columns', 'martfury' ),
				'6' => esc_html__( '6 Columns', 'martfury' ),
				'5' => esc_html__( '5 Columns', 'martfury' ),
				'3' => esc_html__( '3 Columns', 'martfury' ),
				'2' => esc_html__( '2 Columns', 'martfury' ),
			),
			'description'     => esc_html__( 'Specify how many product columns you want to show.', 'martfury' ),
			'active_callback' => array(
				array(
					'setting'  => 'catalog_view_10',
					'operator' => '==',
					'value'    => 'grid',
				),
			),
		),
		'products_per_page_10'                    => array(
			'type'        => 'number',
			'label'       => esc_html__( 'Product Numbers Per Page', 'martfury' ),
			'section'     => 'catalog_page',
			'default'     => 12,
			'priority'    => 90,
			'description' => esc_html__( 'Specify how many products you want to show on the catalog page', 'martfury' ),
		),
		'product_item_grid_custom'                => array(
			'type'     => 'custom',
			'section'  => 'catalog_page',
			'default'  => '<hr>',
			'priority' => 90,
		),
		'catalog_variation_images'                => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Variation Images', 'martfury' ),
			'section'     => 'catalog_page',
			'default'     => 1,
			'priority'    => 90,
			'description' => esc_html__( 'Check this option to show variation images in the product item.', 'martfury' ),
		),
		'catalog_featured_icons'                  => array(
			'type'        => 'multicheck',
			'label'       => esc_html__( 'Featured Icons', 'martfury' ),
			'section'     => 'catalog_page',
			'default'     => array( 'cart', 'qview', 'wishlist', 'compare' ),
			'priority'    => 90,
			'choices'     => array(
				'cart'     => esc_html__( 'Add To Cart', 'martfury' ),
				'qview'    => esc_html__( 'Quick View', 'martfury' ),
				'wishlist' => esc_html__( 'Wishlist', 'martfury' ),
				'compare'  => esc_html__( 'Compare', 'martfury' ),
			),
			'description' => esc_html__( 'Select which icons you want to show.', 'martfury' ),
		),
		'catalog_nav_type'                        => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Type of Navigation', 'martfury' ),
			'section'  => 'catalog_page',
			'default'  => 'numbers',
			'priority' => 100,
			'choices'  => array(
				'numbers'  => esc_html__( 'Page Numbers', 'martfury' ),
				'infinite' => esc_html__( 'Infinite Scroll', 'martfury' ),
			),
		),
		//Badge
		'show_badges'                             => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Catalog Badges', 'martfury' ),
			'section'     => 'shop_badge',
			'default'     => 1,
			'priority'    => 20,
			'description' => esc_html__( 'Check this to show badges in the catalog page.', 'martfury' ),
		),
		'single_product_badges'                   => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Single Product Badges', 'martfury' ),
			'section'     => 'shop_badge',
			'default'     => 0,
			'priority'    => 20,
			'description' => esc_html__( 'Check this to show badges in the single product.', 'martfury' ),
		),
		'badges'                                  => array(
			'type'        => 'multicheck',
			'label'       => esc_html__( 'Badges', 'martfury' ),
			'section'     => 'shop_badge',
			'default'     => array( 'hot', 'new', 'sale', 'outofstock' ),
			'priority'    => 20,
			'choices'     => array(
				'hot'        => esc_html__( 'Hot', 'martfury' ),
				'new'        => esc_html__( 'New', 'martfury' ),
				'sale'       => esc_html__( 'Sale', 'martfury' ),
				'outofstock' => esc_html__( 'Out Of Stock', 'martfury' ),
			),
			'description' => esc_html__( 'Select which badges you want to show', 'martfury' ),
		),
		'hot_text'                                => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Custom Hot Text', 'martfury' ),
			'section'         => 'shop_badge',
			'default'         => 'Hot',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'badges',
					'operator' => 'contains',
					'value'    => 'hot',
				),
			),
		),
		'hot_color'                               => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Custom Hot Color', 'martfury' ),
			'default'         => '',
			'section'         => 'shop_badge',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'badges',
					'operator' => 'contains',
					'value'    => 'hot',
				),
			),
		),
		'hot_color_custom'                        => array(
			'type'     => 'custom',
			'section'  => 'shop_badge',
			'default'  => '<hr>',
			'priority' => 20,
		),
		'outofstock_text'                         => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Custom Out Of Stock Text', 'martfury' ),
			'section'         => 'shop_badge',
			'default'         => 'Out Of Stock',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'badges',
					'operator' => 'contains',
					'value'    => 'outofstock',
				),
			),
		),
		'outofstock_color'                        => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Custom Out Of Stock Color', 'martfury' ),
			'default'         => '',
			'section'         => 'shop_badge',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'badges',
					'operator' => 'contains',
					'value'    => 'outofstock',
				),
			),
		),
		'outofstock_color_custom'                 => array(
			'type'     => 'custom',
			'section'  => 'shop_badge',
			'default'  => '<hr>',
			'priority' => 20,
		),
		'new_text'                                => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Custom New Text', 'martfury' ),
			'section'         => 'shop_badge',
			'default'         => 'New',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'badges',
					'operator' => 'contains',
					'value'    => 'new',
				),
			),
		),
		'new_color'                               => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Custom New Color', 'martfury' ),
			'default'         => '',
			'section'         => 'shop_badge',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'badges',
					'operator' => 'contains',
					'value'    => 'new',
				),
			),
		),
		'new_color_custom'                        => array(
			'type'     => 'custom',
			'section'  => 'shop_badge',
			'default'  => '<hr>',
			'priority' => 20,
		),
		'sale_color'                              => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Custom Sale Color', 'martfury' ),
			'default'         => '',
			'section'         => 'shop_badge',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'badges',
					'operator' => 'contains',
					'value'    => 'sale',
				),
			),
		),
		'sale_type'                               => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Sale Type', 'martfury' ),
			'default'         => '1',
			'section'         => 'shop_badge',
			'priority'        => 20,
			'choices'         => array(
				'1' => esc_html__( 'Percent', 'martfury' ),
				'2' => esc_html__( 'Save', 'martfury' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'badges',
					'operator' => 'contains',
					'value'    => 'sale',
				),
			),
		),
		'sale_save_text'                          => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Custom Save Text', 'martfury' ),
			'section'         => 'shop_badge',
			'default'         => esc_html__( 'Save', 'martfury' ),
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'badges',
					'operator' => 'contains',
					'value'    => 'new',
				),
				array(
					'setting'  => 'sale_type',
					'operator' => '==',
					'value'    => '2',
				),
			),
		),
		'sale_color_custom'                       => array(
			'type'     => 'custom',
			'section'  => 'shop_badge',
			'default'  => '<hr>',
			'priority' => 20,
		),
		'product_newness'                         => array(
			'type'            => 'number',
			'label'           => esc_html__( 'Product Newness', 'martfury' ),
			'section'         => 'shop_badge',
			'default'         => 3,
			'priority'        => 20,
			'description'     => esc_html__( 'Display the "New" badge for how many days?', 'martfury' ),
			'active_callback' => array(
				array(
					'setting'  => 'badges',
					'operator' => 'contains',
					'value'    => 'new',
				),
			),
		),
		// Product Page
		'product_page_layout'                     => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Product Page Layout', 'martfury' ),
			'section'     => 'product_page',
			'default'     => '1',
			'priority'    => 40,
			'choices'     => array(
				'1' => esc_html__( 'Layout 1', 'martfury' ),
				'2' => esc_html__( 'Layout 2', 'martfury' ),
				'3' => esc_html__( 'Layout 3', 'martfury' ),
				'4' => esc_html__( 'Layout 4', 'martfury' ),
				'5' => esc_html__( 'Layout 5', 'martfury' ),
				'6' => esc_html__( 'Layout 6', 'martfury' ),
			),
			'description' => esc_html__( 'Select default layout for product page.', 'martfury' ),
		),
		'product_breadcrumb'                      => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Breadcrumb', 'martfury' ),
			'section'     => 'product_page',
			'default'     => 1,
			'description' => esc_html__( 'Check this option to show the breadcrumb in the sinlle product', 'martfury' ),
			'priority'    => 40,
		),
		'product_zoom'                            => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Product Zoom', 'martfury' ),
			'section'     => 'product_page',
			'default'     => 1,
			'description' => esc_html__( 'Check this option to show a bigger size product image on mouseover', 'martfury' ),
			'priority'    => 40,
		),
		'product_images_lightbox'                 => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Product Images Gallery', 'martfury' ),
			'section'     => 'product_page',
			'default'     => 1,
			'description' => esc_html__( 'Check this option to open product gallery images in a lightbox', 'martfury' ),
			'priority'    => 40,
		),
		'product_thumbnail_numbers'               => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Product Thumbnail Numbers', 'martfury' ),
			'section'  => 'product_page',
			'default'  => 5,
			'priority' => 40,
		),
		'product_page_sidebar'                    => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Product Page Sidebar', 'martfury' ),
			'section'         => 'product_page',
			'default'         => 'content-sidebar',
			'priority'        => 40,
			'choices'         => array(
				'content-sidebar' => esc_html__( 'Content Sidebar', 'martfury' ),
				'sidebar-content' => esc_html__( 'Sidebar Content', 'martfury' ),
			),
			'description'     => esc_html__( 'Select default sidebar for product page.', 'martfury' ),
			'active_callback' => array(
				array(
					'setting'  => 'product_page_layout',
					'operator' => 'in',
					'value'    => array( '2', '4', '5', '6' ),
				),
			),
		),
		'product_add_to_cart_ajax'                => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Add to cart with AJAX', 'martfury' ),
			'section'     => 'product_page',
			'default'     => 1,
			'priority'    => 40,
			'description' => esc_html__( 'Check this option to enable add to cart with AJAX on the product page.', 'martfury' ),
		),
		'sticky_product_info'                     => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Sticky Product Info', 'martfury' ),
			'section'     => 'product_page',
			'default'     => 0,
			'priority'    => 40,
			'description' => esc_html__( 'Check this option to enable sticky product info on the product page.', 'martfury' ),
		),
		'product_buy_now_custom'                  => array(
			'type'     => 'custom',
			'section'  => 'product_page',
			'default'  => '<hr>',
			'priority' => 40,
		),
		'product_buy_now'                         => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Buy Now Button', 'martfury' ),
			'section'     => 'product_page',
			'default'     => 0,
			'priority'    => 40,
			'description' => esc_html__( 'Check this option to enable buy now button after add to cart button in the single product.', 'martfury' ),
		),
		'product_buy_now_text'                    => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Buy Now Text', 'martfury' ),
			'section'         => 'product_page',
			'default'         => esc_html__( "Buy Now", 'martfury' ),
			'priority'        => 40,
			'active_callback' => array(
				array(
					'setting'  => 'product_buy_now',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'product_buy_now_link'                    => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Buy Now Link', 'martfury' ),
			'section'         => 'product_page',
			'default'         => '',
			'priority'        => 40,
			'active_callback' => array(
				array(
					'setting'  => 'product_buy_now',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'product_socials_custom'                  => array(
			'type'     => 'custom',
			'section'  => 'product_page',
			'default'  => '<hr>',
			'priority' => 40,
		),
		'show_product_socials'                    => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Product Socials', 'martfury' ),
			'section'  => 'product_page',
			'default'  => 1,
			'priority' => 40,
		),
		'product_social_icons'                    => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Socials', 'martfury' ),
			'section'         => 'product_page',
			'default'         => array( 'twitter', 'facebook', 'google', 'pinterest', 'linkedin', 'vkontakte' ),
			'priority'        => 40,
			'choices'         => array(
				'twitter'   => esc_html__( 'Twitter', 'martfury' ),
				'facebook'  => esc_html__( 'Facebook', 'martfury' ),
				'google'    => esc_html__( 'Google Plus', 'martfury' ),
				'pinterest' => esc_html__( 'Pinterest', 'martfury' ),
				'linkedin'  => esc_html__( 'Linkedin', 'martfury' ),
				'vkontakte' => esc_html__( 'Vkontakte', 'martfury' ),
				'whatsapp'  => esc_html__( 'Whatsapp', 'martfury' ),
				'email'     => esc_html__( 'Email', 'martfury' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'show_product_socials',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'product_countdown_custom'                => array(
			'type'     => 'custom',
			'section'  => 'product_page',
			'default'  => '<hr>',
			'priority' => 40,
		),
		'product_deals_expire_text'               => array(
			'type'     => 'textarea',
			'label'    => esc_html__( 'Deals Expire Text', 'martfury' ),
			'section'  => 'product_page',
			'default'  => esc_html__( "Don't Miss Out!  This promotion will expires in", 'martfury' ),
			'priority' => 40,
		),
		'product_deals_sold_text'                 => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Deals Sold Text', 'martfury' ),
			'section'  => 'product_page',
			'default'  => esc_html__( "Sold Items", 'martfury' ),
			'priority' => 40,
		),
		'product_specification_text'              => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Specification Text', 'martfury' ),
			'section'  => 'product_page',
			'default'  => esc_html__( "Specification", 'martfury' ),
			'priority' => 40,
		),
		'product_description_text'                => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Description Text', 'martfury' ),
			'section'  => 'product_page',
			'default'  => esc_html__( "Description", 'martfury' ),
			'priority' => 40,
		),
		'product_fbt_custom'                      => array(
			'type'     => 'custom',
			'section'  => 'product_page',
			'default'  => '<hr>',
			'priority' => 40,
		),
		'product_fbt'                             => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Frequently Bought Together', 'martfury' ),
			'section'  => 'product_page',
			'default'  => 1,
			'priority' => 40,
		),
		'product_fbt_title'                       => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Frequently Bought Together Title', 'martfury' ),
			'section'         => 'product_page',
			'default'         => esc_html__( 'Frequently Bought Together', 'martfury' ),
			'priority'        => 40,
			'active_callback' => array(
				array(
					'setting'  => 'product_fbt',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'product_instagram'                       => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Instagram Photos', 'martfury' ),
			'section'     => 'instagram_photos',
			'default'     => 1,
			'priority'    => 40,
			'description' => esc_html__( 'Check this option to show instagram photos in single product page', 'martfury' ),
		),
		'product_instagram_by'                    => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Instagram Photos By', 'martfury' ),
			'section'  => 'instagram_photos',
			'default'  => 'token',
			'priority' => 40,
			'choices'  => array(
				'token' => esc_html__( 'Access Token', 'martfury' ),
				'user'  => esc_html__( 'User', 'martfury' ),
			),
		),
		'instagram_token'                         => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Access Token', 'martfury' ),
			'section'         => 'instagram_photos',
			'default'         => '',
			'priority'        => 40,
			'description'     => esc_html__( 'Enter your Access Token', 'martfury' ),
			'active_callback' => array(
				array(
					'setting'  => 'product_instagram_by',
					'operator' => '==',
					'value'    => 'token',
				),
			),
		),
		'instagram_user'                          => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Username', 'martfury' ),
			'section'         => 'instagram_photos',
			'default'         => '',
			'priority'        => 40,
			'description'     => esc_html__( 'Enter your instagram Username', 'martfury' ),
			'active_callback' => array(
				array(
					'setting'  => 'product_instagram_by',
					'operator' => '==',
					'value'    => 'user',
				),
			),
		),
		'product_instagram_title'                 => array(
			'type'     => 'textarea',
			'label'    => esc_html__( 'Product Instagram Title', 'martfury' ),
			'section'  => 'instagram_photos',
			'default'  => esc_html__( 'See It Styled On Instagram', 'martfury' ),
			'priority' => 40,
		),
		'product_instagram_columns'               => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Instagram Photos Columns', 'martfury' ),
			'section'     => 'instagram_photos',
			'default'     => '5',
			'priority'    => 40,
			'description' => esc_html__( 'Specify how many columns of Instagram Photos you want to show on single product page', 'martfury' ),
			'choices'     => array(
				'3' => esc_html__( '3 Columns', 'martfury' ),
				'4' => esc_html__( '4 Columns', 'martfury' ),
				'5' => esc_html__( '5 Columns', 'martfury' ),
				'6' => esc_html__( '6 Columns', 'martfury' ),
				'7' => esc_html__( '7 Columns', 'martfury' ),
			),
		),
		'product_instagram_numbers'               => array(
			'type'        => 'number',
			'label'       => esc_html__( 'Instagram Photos Numbers', 'martfury' ),
			'section'     => 'instagram_photos',
			'default'     => 10,
			'priority'    => 40,
			'description' => esc_html__( 'Specify how many Instagram Photos you want to show on single product page.', 'martfury' ),
		),
		'product_instagram_image_size'            => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Instagram Image Size', 'martfury' ),
			'section'  => 'instagram_photos',
			'default'  => 'low_resolution',
			'priority' => 40,
			'choices'  => array(
				'low_resolution'      => esc_html__( 'Small', 'martfury' ),
				'thumbnail'           => esc_html__( 'Thumbnail', 'martfury' ),
				'standard_resolution' => esc_html__( 'Large', 'martfury' ),
			),
		),
		'product_upsells'                         => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Up-sells Products', 'martfury' ),
			'section'     => 'upsells_products',
			'default'     => 1,
			'description' => esc_html__( 'Check this option to show instagram photos in single product page', 'martfury' ),
			'priority'    => 40,
		),
		'products_upsells_position'               => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Up-sells Products Position', 'martfury' ),
			'section'         => 'upsells_products',
			'default'         => '1',
			'priority'        => 40,
			'choices'         => array(
				'1' => esc_html__( 'Above Product Tabs', 'martfury' ),
				'2' => esc_html__( 'Under Product Tabs', 'martfury' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_page_layout',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'product_upsells_title'                   => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Up-sells Products Title', 'martfury' ),
			'section'  => 'upsells_products',
			'default'  => esc_html__( 'You may also like', 'martfury' ),
			'priority' => 40,
		),
		'upsells_products_columns'                => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Up-sells Products Columns', 'martfury' ),
			'section'     => 'upsells_products',
			'default'     => '5',
			'priority'    => 40,
			'description' => esc_html__( 'Specify how many columns of up-sells products you want to show on single product page', 'martfury' ),
			'choices'     => array(
				'3' => esc_html__( '3 Columns', 'martfury' ),
				'4' => esc_html__( '4 Columns', 'martfury' ),
				'5' => esc_html__( '5 Columns', 'martfury' ),
				'6' => esc_html__( '6 Columns', 'martfury' ),
				'7' => esc_html__( '7 Columns', 'martfury' ),
			),
		),
		'upsells_products_numbers'                => array(
			'type'        => 'number',
			'label'       => esc_html__( 'Up-sells Products Numbers', 'martfury' ),
			'section'     => 'upsells_products',
			'default'     => 6,
			'priority'    => 40,
			'description' => esc_html__( 'Specify how many numbers of up-sells products you want to show on single product page', 'martfury' ),
		),
		'product_related'                         => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Related Products', 'martfury' ),
			'section'     => 'related_products',
			'description' => esc_html__( 'Check this option to show instagram photos in single product page', 'martfury' ),
			'default'     => 1,
			'priority'    => 40,
		),
		'product_related_title'                   => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Related Products Title', 'martfury' ),
			'section'  => 'related_products',
			'default'  => esc_html__( 'Related products', 'martfury' ),
			'priority' => 40,
		),
		'related_product_by_categories'           => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Related Products By Categories', 'martfury' ),
			'section'  => 'related_products',
			'default'  => 1,
			'priority' => 40,
		),
		'related_product_by_parent_category'      => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Related Products By Parent Category', 'martfury' ),
			'section'         => 'related_products',
			'default'         => 0,
			'priority'        => 40,
			'active_callback' => array(
				array(
					'setting'  => 'related_product_by_categories',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'related_product_by_tags'                 => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Related Products By Tags', 'martfury' ),
			'section'  => 'related_products',
			'default'  => 1,
			'priority' => 40,
		),
		'related_products_columns'                => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Related Products Columns', 'martfury' ),
			'section'     => 'related_products',
			'default'     => '5',
			'priority'    => 40,
			'description' => esc_html__( 'Specify how many columns of related products you want to show on single product page', 'martfury' ),
			'choices'     => array(
				'3' => esc_html__( '3 Columns', 'martfury' ),
				'4' => esc_html__( '4 Columns', 'martfury' ),
				'5' => esc_html__( '5 Columns', 'martfury' ),
				'6' => esc_html__( '6 Columns', 'martfury' ),
				'7' => esc_html__( '7 Columns', 'martfury' ),
			),
		),
		'related_products_numbers'                => array(
			'type'        => 'number',
			'label'       => esc_html__( 'Related Products Numbers', 'martfury' ),
			'section'     => 'related_products',
			'default'     => 6,
			'priority'    => 40,
			'description' => esc_html__( 'Specify how many numbers of related products you want to show on single product page', 'martfury' ),
		),
		// Custom categories sidebar
		'custom_product_cat_sidebars'             => array(
			'type'      => 'repeater',
			'label'     => esc_html__( 'Custom Sidebars', 'martfury' ),
			'section'   => 'custom_product_cat_sidebars',
			'default'   => '',
			'priority'  => 40,
			'row_label' => array(
				'type'  => 'text',
				'value' => esc_html__( 'Sidebar', 'martfury' ),
			),
			'fields'    => array(
				'title' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Title', 'martfury' ),
					'default' => '',
				),
			),
		),
		// Cart Page
		'clear_cart_button'                       => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Clear Cart Button', 'martfury' ),
			'default'     => 0,
			'section'     => 'cart_page',
			'description' => esc_html__( 'Enable to show Clear Cart button on cart page.', 'martfury' ),
			'priority'    => 20,
		),
		'quantity_ajax'                           => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Update Quantity AJAX', 'martfury' ),
			'default'     => 0,
			'section'     => 'cart_page',
			'description' => esc_html__( 'Change qty in the cart page with AJAX', 'martfury' ),
			'priority'    => 20,
		),

		// Account Page
		'login_register_layout'                   => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Login & Register Layout', 'martfury' ),
			'section'  => 'account_page',
			'default'  => 'tabs',
			'priority' => 40,
			'choices'  => array(
				'tabs'      => esc_html__( 'Tabs', 'martfury' ),
				'columns'   => esc_html__( 'Columns', 'martfury' ),
				'promotion' => esc_html__( 'With Promotion', 'martfury' ),
			),
		),
		'login_promotion_title'                   => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Promotion Title', 'martfury' ),
			'section'         => 'account_page',
			'default'         => esc_html__( 'Sign up today and you will be able to:', 'martfury' ),
			'priority'        => 40,
			'active_callback' => array(
				array(
					'setting'  => 'login_register_layout',
					'operator' => '==',
					'value'    => 'promotion',
				),
			),
		),
		'login_promotion_text'                    => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Promotion Text', 'martfury' ),
			'section'         => 'account_page',
			'default'         => '',
			'priority'        => 40,
			'active_callback' => array(
				array(
					'setting'  => 'login_register_layout',
					'operator' => '==',
					'value'    => 'promotion',
				),
			),
		),
		'login_promotion_list'                    => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Promotion List', 'martfury' ),
			'section'         => 'account_page',
			'default'         => '',
			'priority'        => 40,
			'active_callback' => array(
				array(
					'setting'  => 'login_register_layout',
					'operator' => '==',
					'value'    => 'promotion',
				),
			),
		),
		'login_ads_title'                         => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Ads Title', 'martfury' ),
			'section'         => 'account_page',
			'default'         => '',
			'priority'        => 40,
			'active_callback' => array(
				array(
					'setting'  => 'login_register_layout',
					'operator' => '==',
					'value'    => 'promotion',
				),
			),
		),
		'login_ads_text'                          => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Ads Text', 'martfury' ),
			'section'         => 'account_page',
			'default'         => '',
			'priority'        => 40,
			'active_callback' => array(
				array(
					'setting'  => 'login_register_layout',
					'operator' => '==',
					'value'    => 'promotion',
				),
			),
		),
		// Search Page
		'search_full_width'                       => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Full Width', 'martfury' ),
			'default'     => '0',
			'section'     => 'search_page',
			'description' => esc_html__( 'Enable to show the product search page as full width.', 'martfury' ),
			'priority'    => 20,
		),
		// Page Header Blog
		'page_header_blog'                        => array(
			'type'        => 'toggle',
			'default'     => 1,
			'label'       => esc_html__( 'Enable Page Header', 'martfury' ),
			'section'     => 'page_header_blog',
			'description' => esc_html__( 'Enable to show a page header for the blog page below the site header', 'martfury' ),
			'priority'    => 20,
		),
		'page_header_blog_layout'                 => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Page Header Layout', 'martfury' ),
			'section'         => 'page_header_blog',
			'default'         => '1',
			'priority'        => 20,
			'choices'         => array(
				'1' => esc_html__( 'Layout 1', 'martfury' ),
				'2' => esc_html__( 'Layout 2', 'martfury' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'page_header_blog',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'page_header_blog_els'                    => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Page Header Elements', 'martfury' ),
			'section'         => 'page_header_blog',
			'default'         => array( 'breadcrumb', 'title' ),
			'priority'        => 20,
			'choices'         => array(
				'breadcrumb' => esc_html__( 'BreadCrumb', 'martfury' ),
				'title'      => esc_html__( 'Title', 'martfury' ),
			),
			'description'     => esc_html__( 'Select which elements you want to show.', 'martfury' ),
			'active_callback' => array(
				array(
					'setting'  => 'page_header_blog_layout',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'page_header_blog_slider'                 => array(
			'type'            => 'repeater',
			'label'           => esc_html__( 'Sliders Content', 'martfury' ),
			'section'         => 'page_header_blog',
			'default'         => '',
			'priority'        => 40,
			'row_label'       => array(
				'type'  => 'text',
				'value' => esc_html__( 'Slider', 'martfury' ),
			),
			'fields'          => array(
				'subtitle' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'SubTitle', 'martfury' ),
					'default' => '',
				),
				'title'    => array(
					'type'    => 'textarea',
					'label'   => esc_html__( 'Title', 'martfury' ),
					'default' => '',
				),
				'desc'     => array(
					'type'    => 'textarea',
					'label'   => esc_html__( 'Description', 'martfury' ),
					'default' => '',
				),
				'image'    => array(
					'type'    => 'image',
					'label'   => esc_html__( 'Image', 'martfury' ),
					'default' => '',
				),
				'link_url' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Link(URL)', 'martfury' ),
					'default' => '',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'page_header_blog_layout',
					'operator' => '==',
					'value'    => '2',
				),
				array(
					'setting'  => 'page_header_blog',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'page_header_blog_height'                 => array(
			'type'            => 'number',
			'label'           => esc_html__( 'Sliders Height(px)', 'martfury' ),
			'default'         => 860,
			'section'         => 'page_header_blog',
			'priority'        => 40,
			'active_callback' => array(
				array(
					'setting'  => 'page_header_blog_layout',
					'operator' => '==',
					'value'    => '2',
				),
				array(
					'setting'  => 'page_header_blog',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'page_header_blog_autoplay'               => array(
			'type'            => 'number',
			'label'           => esc_html__( 'Sliders Autoplay', 'martfury' ),
			'default'         => 0,
			'section'         => 'page_header_blog',
			'priority'        => 40,
			'description'     => esc_html__( 'Duration of animation between slides (in ms). Enter the value is 0 or empty if you want the slider is not autoplay', 'martfury' ),
			'active_callback' => array(
				array(
					'setting'  => 'page_header_blog_layout',
					'operator' => '==',
					'value'    => '2',
				),
				array(
					'setting'  => 'page_header_blog',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		// Blog
		'blog_layout'                             => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Blog Layout', 'martfury' ),
			'default'     => 'list',
			'section'     => 'blog',
			'priority'    => 10,
			'description' => esc_html__( 'Select default layout for blog page.', 'martfury' ),
			'choices'     => array(
				'grid'            => esc_html__( 'Grid', 'martfury' ),
				'list'            => esc_html__( 'List', 'martfury' ),
				'masonry'         => esc_html__( 'Masonry', 'martfury' ),
				'small-thumb'     => esc_html__( 'Small Thumb', 'martfury' ),
				'sidebar-content' => esc_html__( 'Sidebar Content', 'martfury' ),
				'content-sidebar' => esc_html__( 'Content Sidebar', 'martfury' ),
			),
		),
		'blog_excerpt_length'                     => array(
			'type'            => 'number',
			'label'           => esc_html__( 'Excerpt Length', 'martfury' ),
			'section'         => 'blog',
			'default'         => 15,
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'blog_layout',
					'operator' => 'in',
					'value'    => array( 'list', 'small-thumb' ),
				),
			),
		),
		'show_blog_cats'                          => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Categories List', 'martfury' ),
			'section'     => 'blog',
			'default'     => 1,
			'description' => esc_html__( 'Display categories list below site header on blog, category page', 'martfury' ),
			'priority'    => 20,
		),
		'custom_blog_cats'                        => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Custom Categories List', 'martfury' ),
			'section'  => 'blog',
			'default'  => 0,
			'priority' => 20,
		),
		'blog_cats_slug'                          => array(
			'type'            => 'select',
			'section'         => 'blog',
			'label'           => esc_html__( 'Custom Categories', 'martfury' ),
			'description'     => esc_html__( 'Select product categories you want to show.', 'martfury' ),
			'default'         => '',
			'multiple'        => 999,
			'priority'        => 20,
			'choices'         => martfury_customizer_get_categories( 'category' ),
			'active_callback' => array(
				array(
					'setting'  => 'custom_blog_cats',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'blog_nav_type'                           => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Type of Navigation', 'martfury' ),
			'section'  => 'blog',
			'default'  => 'links',
			'priority' => 20,
			'choices'  => array(
				'links'    => esc_html__( 'Links', 'martfury' ),
				'infinite' => esc_html__( 'Infinite Scroll', 'martfury' ),
			),
		),
		// Single Post
		'single_post_layout'                      => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Single Post Layout', 'martfury' ),
			'default'     => 'full-content',
			'section'     => 'single_post',
			'priority'    => 10,
			'description' => esc_html__( 'Select default sidebar for single post.', 'martfury' ),
			'choices'     => array(
				'full-content'    => esc_html__( 'Full Content', 'martfury' ),
				'content-sidebar' => esc_html__( 'Content Sidebar', 'martfury' ),
				'sidebar-content' => esc_html__( 'Sidebar Content', 'martfury' ),
			),
		),
		'single_post_style'                       => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Single Post Style', 'martfury' ),
			'default'  => '1',
			'section'  => 'single_post',
			'priority' => 10,
			'choices'  => array(
				'1' => esc_html__( 'Style 1', 'martfury' ),
				'2' => esc_html__( 'Style 2', 'martfury' ),
				'3' => esc_html__( 'Style 3', 'martfury' ),
				'4' => esc_html__( 'Style 4', 'martfury' ),
			),
		),
		'show_post_format'                        => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Show Post Format', 'martfury' ),
			'section'         => 'single_post',
			'default'         => 1,
			'description'     => esc_html__( 'Check this option to show the post format above the single content.', 'martfury' ),
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'single_post_style',
					'operator' => 'in',
					'value'    => array( '1', '3', '4' ),
				),
			),
		),
		'show_post_socials'                       => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Post Socials', 'martfury' ),
			'section'     => 'single_post',
			'default'     => 1,
			'description' => esc_html__( 'Display social sharing icons on single post', 'martfury' ),
			'priority'    => 20,
		),
		'post_social_icons'                       => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Socials', 'martfury' ),
			'section'         => 'single_post',
			'default'         => array( 'facebook', 'twitter', 'pinterest', 'google', 'linkedin', 'vkontakte' ),
			'priority'        => 20,
			'choices'         => array(
				'twitter'   => esc_html__( 'Twitter', 'martfury' ),
				'facebook'  => esc_html__( 'Facebook', 'martfury' ),
				'google'    => esc_html__( 'Google Plus', 'martfury' ),
				'pinterest' => esc_html__( 'Pinterest', 'martfury' ),
				'linkedin'  => esc_html__( 'Linkedin', 'martfury' ),
				'vkontakte' => esc_html__( 'Vkontakte', 'martfury' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'post_share_box',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'post_author_box'                         => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Author Box', 'martfury' ),
			'section'     => 'single_post',
			'default'     => 1,
			'description' => esc_html__( 'Check this option to  display author box on single post', 'martfury' ),
			'priority'    => 20,
		),
		'related_posts'                           => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Related Posts', 'martfury' ),
			'section'     => 'single_post',
			'default'     => 1,
			'description' => esc_html__( 'Check this option to display related posts on single post', 'martfury' ),
			'priority'    => 20,
		),
		'related_posts_title'                     => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Related Posts Title', 'martfury' ),
			'section'  => 'single_post',
			'default'  => esc_html__( 'Related Posts', 'martfury' ),
			'priority' => 20,
		),
		'related_posts_number'                    => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Related Posts Number', 'martfury' ),
			'section'  => 'single_post',
			'default'  => '3',
			'priority' => 20,
		),
		// Page Header Blog
		'page_header_page'                        => array(
			'type'        => 'toggle',
			'default'     => 1,
			'label'       => esc_html__( 'Enable Page Header', 'martfury' ),
			'section'     => 'page_header_page',
			'description' => esc_html__( 'Enable to show a page header for the page below the site header', 'martfury' ),
			'priority'    => 20,
		),
		'page_header_pages_els'                   => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Page Header Elements', 'martfury' ),
			'section'         => 'page_header_page',
			'default'         => array( 'breadcrumb', 'title' ),
			'priority'        => 20,
			'choices'         => array(
				'breadcrumb' => esc_html__( 'BreadCrumb', 'martfury' ),
				'title'      => esc_html__( 'Title', 'martfury' ),
			),
			'description'     => esc_html__( 'Select which elements you want to show.', 'martfury' ),
			'active_callback' => array(
				array(
					'setting'  => 'page_header_page',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'page_layout'                             => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Page Layout', 'martfury' ),
			'default'     => 'full-content',
			'section'     => 'single_page',
			'priority'    => 10,
			'description' => esc_html__( 'Select default layout for page.', 'martfury' ),
			'choices'     => array(
				'full-content'    => esc_html__( 'Full Content', 'martfury' ),
				'sidebar-content' => esc_html__( 'Sidebar Content', 'martfury' ),
				'content-sidebar' => esc_html__( 'Content Sidebar', 'martfury' ),
			),
		),
		// Footer
		'footer_skin'                             => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Footer Skin', 'martfury' ),
			'section'  => 'footer',
			'default'  => 'light',
			'priority' => 20,
			'choices'  => array(
				'light'  => esc_html__( 'Light', 'martfury' ),
				'gray'   => esc_html__( 'Gray', 'martfury' ),
				'custom' => esc_html__( 'Custom', 'martfury' ),
			),
		),
		'footer_bg_color'                         => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Custom Footer Skin', 'martfury' ),
			'default'         => '',
			'section'         => 'footer',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'footer_skin',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.site-footer .footer-layout',
					'property' => 'background-color',
				),
			)
		),
		'footer_heading_color'                    => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Footer Heading Color', 'martfury' ),
			'default'         => '',
			'section'         => 'footer',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'footer_skin',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.site-footer h1, .site-footer h2, .site-footer h3, .site-footer h4, .site-footer h5, .site-footer h6, .site-footer .widget .widget-title',
					'property' => 'color',
				),
			)
		),
		'footer_text_color'                       => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Footer Text Color', 'martfury' ),
			'default'         => '',
			'section'         => 'footer',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'footer_skin',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.site-footer, .site-footer .footer-widgets .widget ul li a, .site-footer .footer-copyright, .site-footer .footer-links .widget_nav_menu ul li a, .site-footer .footer-payments .text',
					'property' => 'color',
				),
			)
		),
		'footer_hover_color'                      => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Footer Hover Color', 'martfury' ),
			'default'         => '',
			'section'         => 'footer',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'footer_skin',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.site-footer .footer-widgets .widget ul li a:hover,.site-footer .footer-links .widget_nav_menu ul li a:hover',
					'property' => 'color',
				),
				array(
					'element'  => '.site-footer .footer-widgets .widget ul li a:before, .site-footer .footer-links .widget_nav_menu ul li a:before',
					'property' => 'background-color',
				),
			)
		),
		'footer_border_color'                     => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Footer Border Color', 'martfury' ),
			'default'         => '',
			'section'         => 'footer',
			'priority'        => 20,
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'footer_skin',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'postMessage',
			'js_vars'         => array(
				array(
					'element'  => '.site-footer .footer-newsletter, .site-footer .footer-info, .site-footer .footer-widgets, .site-footer .footer-links',
					'property' => 'border-color',
				),
				array(
					'element'  => '.site-footer .footer-info .info-item-sep:after',
					'property' => 'background-color',
				),
			)
		),
		'footer_full_width'                       => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Footer Full Width', 'martfury' ),
			'default'  => '0',
			'section'  => 'footer',
			'priority' => 20,
		),
		'footer_newsletter'                       => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Footer Newsletter', 'martfury' ),
			'section'     => 'footer_newsletter',
			'default'     => 0,
			'description' => esc_html__( 'Check this option to the newsletter in the footer.', 'martfury' ),
			'priority'    => 20,
		),
		'footer_newsletter_text'                  => array(
			'type'     => 'textarea',
			'label'    => esc_html__( 'Newsletter Text', 'martfury' ),
			'section'  => 'footer_newsletter',
			'default'  => '',
			'priority' => 20,
		),
		'footer_newsletter_form'                  => array(
			'type'        => 'textarea',
			'label'       => esc_html__( 'Newsletter Form', 'martfury' ),
			'description' => esc_html__( 'Enter the shortcode of MailChimp form', 'martfury' ),
			'section'     => 'footer_newsletter',
			'default'     => '',
			'priority'    => 20,
		),
		'custom_newsletter_form_2'                => array(
			'type'     => 'custom',
			'section'  => 'footer_newsletter',
			'default'  => sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=mailchimp-for-wp-forms' ), esc_html__( 'Go to MailChimp form', 'martfury' ) ),
			'priority' => 20,
		),
		'footer_info'                             => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Footer Info', 'martfury' ),
			'section'     => 'footer_info',
			'default'     => 0,
			'description' => esc_html__( 'Check this option to the info in the footer.', 'martfury' ),
			'priority'    => 20,
		),
		'footer_info_list'                        => array(
			'type'      => 'repeater',
			'label'     => esc_html__( 'Footer Info', 'martfury' ),
			'section'   => 'footer_info',
			'priority'  => 20,
			'row_label' => array(
				'type'  => 'text',
				'value' => esc_html__( 'Icon Box', 'martfury' ),
			),
			'fields'    => array(
				'icon'  => array(
					'type'    => 'textarea',
					'label'   => esc_html__( 'Icon', 'martfury' ),
					'default' => '',
				),
				'title' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Title', 'martfury' ),
					'default' => '',
				),
				'desc'  => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Description', 'martfury' ),
					'default' => '',
				),
			),
		),
		'footer_widgets'                          => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Footer Widgets', 'martfury' ),
			'section'  => 'footer_widgets',
			'default'  => 1,
			'priority' => 20,
		),
		'footer_widget_columns'                   => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Footer Columns', 'martfury' ),
			'section'     => 'footer_widgets',
			'default'     => '4',
			'description' => esc_html__( 'Go to Appearance - Widgets - Footer 1, 2, 3, 4, 5, 6 to add widgets content.', 'martfury' ),
			'priority'    => 20,
			'choices'     => array(
				'4' => esc_html__( '4 Columns', 'martfury' ),
				'3' => esc_html__( '3 Columns', 'martfury' ),
				'5' => esc_html__( '5 Columns', 'martfury' ),
				'6' => esc_html__( '6 Columns', 'martfury' ),
			),

		),
		'footer_links'                            => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Footer Links', 'martfury' ),
			'section'     => 'footer_links',
			'default'     => 1,
			'description' => esc_html__( 'Go to Appearance > Widgets > Footer Links to add widgets content.', 'martfury' ),
			'priority'    => 20,
		),
		'custom_footer_copyright'                 => array(
			'type'     => 'custom',
			'section'  => 'footer',
			'default'  => '<hr>',
			'priority' => 20,
		),
		'footer_copyright'                        => array(
			'type'        => 'textarea',
			'label'       => esc_html__( 'Footer Copyright', 'martfury' ),
			'description' => esc_html__( 'Shortcodes are allowed', 'martfury' ),
			'section'     => 'footer',
			'default'     => esc_html__( 'Copyright &copy; 2018', 'martfury' ),
			'priority'    => 20,
		),
		'footer_payment_text'                     => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Footer Payment Text', 'martfury' ),
			'section'  => 'footer',
			'priority' => 20,
		),
		'footer_payment_images'                   => array(
			'type'      => 'repeater',
			'label'     => esc_html__( 'Footer Images', 'martfury' ),
			'section'   => 'footer',
			'priority'  => 40,
			'row_label' => array(
				'type'  => 'text',
				'value' => esc_html__( 'Image', 'martfury' ),
			),
			'fields'    => array(
				'image' => array(
					'type'    => 'image',
					'label'   => esc_html__( 'Image', 'martfury' ),
					'default' => '',
				),
			),
		),
		// Recent viewed
		'footer_recently_viewed'                  => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Recently Viewed', 'martfury' ),
			'section'     => 'footer_recently_viewed',
			'default'     => 1,
			'priority'    => 90,
			'description' => esc_html__( 'Check this option to show the recently viewed products above the footer.', 'martfury' ),
		),
		'footer_recently_viewed_layout'           => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Recently Viewed Layout', 'martfury' ),
			'section'  => 'footer_recently_viewed',
			'default'  => '1',
			'priority' => 90,
			'choices'  => array(
				'1' => esc_html__( 'Layout 1', 'martfury' ),
				'2' => esc_html__( 'Layout 2', 'martfury' ),
			),
		),
		'footer_recently_viewed_empty'            => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Hide Recently Viewed Empty', 'martfury' ),
			'section'         => 'footer_recently_viewed',
			'default'         => 1,
			'priority'        => 90,
			'description'     => esc_html__( 'Check this option to hide the recently viewed products when empty.', 'martfury' ),
			'active_callback' => array(
				array(
					'setting'  => 'footer_recently_viewed_layout',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'footer_recently_viewed_pt'               => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'No Padding Top', 'martfury' ),
			'section'         => 'footer_recently_viewed',
			'default'         => 0,
			'priority'        => 90,
			'description'     => esc_html__( 'Check this option to remove padding top of the recently viewed products. This option is used for the homepage only.', 'martfury' ),
			'active_callback' => array(
				array(
					'setting'  => 'footer_recently_viewed_layout',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'footer_recently_viewed_no_bg'               => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'No Background', 'martfury' ),
			'section'         => 'footer_recently_viewed',
			'default'         => 0,
			'priority'        => 90,
			'description'     => esc_html__( 'Check this option to disable background of the recently viewed products. This option is used for the homepage only.', 'martfury' ),
			'active_callback' => array(
				array(
					'setting'  => 'footer_recently_viewed_layout',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'footer_recently_viewed_els'              => array(
			'type'        => 'multicheck',
			'label'       => esc_html__( 'Show Recently Viewed in', 'martfury' ),
			'section'     => 'footer_recently_viewed',
			'default'     => array( 'homepage', 'catalog', 'single_product' ),
			'priority'    => 90,
			'choices'     => array(
				'homepage'       => esc_html__( 'HomePage', 'martfury' ),
				'catalog'        => esc_html__( 'Catalog', 'martfury' ),
				'single_product' => esc_html__( 'Single Product', 'martfury' ),
				'page'           => esc_html__( 'Page', 'martfury' ),
				'post'           => esc_html__( 'Post', 'martfury' ),
				'other'          => esc_html__( 'Other Pages', 'martfury' ),
			),
			'description' => esc_html__( 'Check pages to show the recently viewed products above the footer.', 'martfury' ),
		),
		'footer_recently_viewed_title'            => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Recently Viewed Title', 'martfury' ),
			'section'  => 'footer_recently_viewed',
			'default'  => esc_html__( 'Your Recently Viewed Products', 'martfury' ),
			'priority' => 90,
		),
		'footer_recently_viewed_number'           => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Products Per Page', 'martfury' ),
			'section'  => 'footer_recently_viewed',
			'default'  => 12,
			'priority' => 90,
		),
		'footer_recently_viewed_link_text'        => array(
			'type'     => 'text',
			'label'    => esc_html__( 'View All Text', 'martfury' ),
			'section'  => 'footer_recently_viewed',
			'default'  => '',
			'priority' => 90,
		),
		'footer_recently_viewed_link_url'         => array(
			'type'     => 'text',
			'label'    => esc_html__( 'View All URL', 'martfury' ),
			'section'  => 'footer_recently_viewed',
			'default'  => '',
			'priority' => 90,
		),

	);

	$catalog_layouts = array(
		'shop_layout'                 => array(
			'section'     => 'shop_page',
			'field'       => 'shop',
			'label'       => esc_html__( 'Shop Layout', 'martfury' ),
			'description' => esc_html__( 'Select default layout for shop.', 'martfury' ),
		),
		'products_cat_level_1_layout' => array(
			'section'     => 'product_cat_level_1_page',
			'field'       => 'products_cat_level_1',
			'label'       => esc_html__( 'Category Layout', 'martfury' ),
			'description' => esc_html__( 'Select default layout for category level 1.', 'martfury' ),
		),
	);

	$catalog_fields = array();
	foreach ( $catalog_layouts as $layout ) {
		$catalog_fields[ $layout['field'] . '_layout' ] = array(
			'type'        => 'select',
			'label'       => $layout['label'],
			'default'     => '10',
			'section'     => $layout['section'],
			'description' => $layout['description'],
			'priority'    => 70,
			'choices'     => array(
				'10' => esc_html__( 'Default', 'martfury' ),
				'1'  => esc_html__( 'Catalog Layout 1', 'martfury' ),
				'2'  => esc_html__( 'Catalog Layout 2', 'martfury' ),
				'3'  => esc_html__( 'Catalog Layout 3', 'martfury' ),
			),
		);

		$catalog_fields[ $layout['field'] . '_els_1' ] = array(
			'type'            => 'multicheck',
			'label'           => $layout['label'] . ' ' . esc_html__( 'Elements', 'martfury' ),
			'default'         => array( 'banners', 'brands', 'categories', 'products_carousel', 'breadcrumb' ),
			'section'         => $layout['section'],
			'priority'        => 70,
			'description'     => esc_html__( 'Select which elements you want to show.', 'martfury' ),
			'choices'         => array(
				'title'             => esc_html__( 'Page Title', 'martfury' ),
				'breadcrumb'        => esc_html__( 'Breadcrumb', 'martfury' ),
				'banners'           => esc_html__( 'Banners', 'martfury' ),
				'brands'            => esc_html__( 'Brands', 'martfury' ),
				'categories'        => esc_html__( 'Categories Box', 'martfury' ),
				'products_carousel' => esc_html__( 'Products Carousel', 'martfury' ),
			),
			'active_callback' => array(
				array(
					'setting'  => $layout['field'] . '_layout',
					'operator' => '==',
					'value'    => '1',
				),
			),
		);

		$catalog_fields[ $layout['field'] . '_els_2' ] = array(
			'type'            => 'multicheck',
			'label'           => $layout['label'] . ' ' . esc_html__( 'Elements', 'martfury' ),
			'default'         => array(
				'title',
				'top_categories',
				'products_carousel',
				'featured_categories',
				'other_categories',
				'breadcrumb'
			),
			'section'         => $layout['section'],
			'priority'        => 70,
			'description'     => esc_html__( 'Select which elements you want to show.', 'martfury' ),
			'choices'         => array(
				'title'               => esc_html__( 'Page Title', 'martfury' ),
				'breadcrumb'          => esc_html__( 'Breadcrumb', 'martfury' ),
				'top_categories'      => esc_html__( 'Top Categories', 'martfury' ),
				'products_carousel'   => esc_html__( 'Products Carousel', 'martfury' ),
				'featured_categories' => esc_html__( 'Featured Categories', 'martfury' ),
				'other_categories'    => esc_html__( 'Other Categories', 'martfury' ),
			),
			'active_callback' => array(
				array(
					'setting'  => $layout['field'] . '_layout',
					'operator' => '==',
					'value'    => '2',
				),
			),
		);

		$catalog_fields[ $layout['field'] . '_els_3' ] = array(
			'type'            => 'multicheck',
			'label'           => $layout['label'] . ' ' . esc_html__( 'Elements', 'martfury' ),
			'default'         => array( 'title', 'banners', 'products_carousel', 'breadcrumb' ),
			'section'         => $layout['section'],
			'priority'        => 70,
			'description'     => esc_html__( 'Select which elements you want to show.', 'martfury' ),
			'choices'         => array(
				'title'             => esc_html__( 'Page Title', 'martfury' ),
				'breadcrumb'        => esc_html__( 'Breadcrumb', 'martfury' ),
				'banners'           => esc_html__( 'Banners', 'martfury' ),
				'products_carousel' => esc_html__( 'Products Carousel', 'martfury' ),
			),
			'active_callback' => array(
				array(
					'setting'  => $layout['field'] . '_layout',
					'operator' => '==',
					'value'    => '3',
				),
			),
		);
		$catalog_fields[ $layout['field'] . 'custom' ] = array(
			'type'     => 'custom',
			'default'  => '<hr/>',
			'section'  => $layout['section'],
			'priority' => 70,
		);
	}


	$fields = array_merge( $fields, $catalog_fields );


	$settings['panels']   = apply_filters( 'martfury_customize_panels', $panels );
	$settings['sections'] = apply_filters( 'martfury_customize_sections', $sections );
	$settings['fields']   = apply_filters( 'martfury_customize_fields', $fields );

	return $settings;
}

$martfury_customize = new Martfury_Customize( martfury_customize_settings() );


