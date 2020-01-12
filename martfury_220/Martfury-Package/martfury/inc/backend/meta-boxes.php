<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */


/**
 * Enqueue script for handling actions with meta boxes
 *
 * @since 1.0
 *
 * @param string $hook
 */
function martfury_meta_box_scripts( $hook ) {
	// Detect to load un-minify scripts when WP_DEBUG is enable
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
		wp_enqueue_script( 'martfury-meta-boxes', get_template_directory_uri() . "/js/backend/meta-boxes.js", array( 'jquery' ), '20181210', true );
		wp_enqueue_style( 'martfury-meta-boxes', get_template_directory_uri() . "/css/backend/meta-boxes.css", '20170801' );
	}
}

add_action( 'admin_enqueue_scripts', 'martfury_meta_box_scripts' );


/**
 * Registering meta boxes
 *
 * Using Meta Box plugin: http://www.deluxeblogtips.com/meta-box/
 *
 * @see http://www.deluxeblogtips.com/meta-box/docs/define-meta-boxes
 *
 * @param array $meta_boxes Default meta boxes. By default, there are no meta boxes.
 *
 * @return array All registered meta boxes
 */
function martfury_register_meta_boxes( $meta_boxes ) {
	// Post format's meta box
	$meta_boxes[] = array(
		'id'       => 'post-format-settings',
		'title'    => esc_html__( 'Format Details', 'martfury' ),
		'pages'    => array( 'post' ),
		'context'  => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields'   => array(
			array(
				'name'             => esc_html__( 'Image', 'martfury' ),
				'id'               => 'image',
				'type'             => 'image_advanced',
				'class'            => 'image',
				'max_file_uploads' => 1,
			),
			array(
				'name'  => esc_html__( 'Gallery', 'martfury' ),
				'id'    => 'images',
				'type'  => 'image_advanced',
				'class' => 'gallery',
			),
			array(
				'name'  => esc_html__( 'Audio', 'martfury' ),
				'id'    => 'audio',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 2,
				'class' => 'audio',
			),
			array(
				'name'  => esc_html__( 'Video', 'martfury' ),
				'id'    => 'video',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 2,
				'class' => 'video',
			),
			array(
				'name'  => esc_html__( 'Description', 'martfury' ),
				'id'    => 'desc',
				'type'  => 'textarea',
				'cols'  => 40,
				'rows'  => 2,
				'class' => 'link',
			),
			array(
				'name'  => esc_html__( 'Link', 'martfury' ),
				'id'    => 'url',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'link',
			),
			array(
				'name'  => esc_html__( 'Text', 'martfury' ),
				'id'    => 'url_text',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'link',
			),
			array(
				'name'  => esc_html__( 'Quote', 'martfury' ),
				'id'    => 'quote',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 2,
				'class' => 'quote',
			),
			array(
				'name'  => esc_html__( 'Author', 'martfury' ),
				'id'    => 'quote_author',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'quote',
			),
			array(
				'name'  => esc_html__( 'Author URL', 'martfury' ),
				'id'    => 'author_url',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'quote',
			),
			array(
				'name'  => esc_html__( 'Status', 'martfury' ),
				'id'    => 'status',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 1,
				'class' => 'status',
			),
		),
	);

	// Display Settings
	$meta_boxes[] = array(
		'id'       => 'post-style-settings',
		'title'    => esc_html__( 'Post Style Settings', 'martfury' ),
		'pages'    => array( 'post' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name' => esc_html__( 'Style', 'martfury' ),
				'id'   => 'heading_style',
				'type' => 'heading',
			),
			array(
				'name' => esc_html__( 'Custom Style', 'martfury' ),
				'id'   => 'custom_style',
				'type' => 'checkbox',
				'std'  => false,
			),
			array(
				'name'    => esc_html__( 'Style', 'martfury' ),
				'id'      => 'post_style',
				'type'    => 'select',
				'options' => array(
					'1' => esc_html__( 'Style 1', 'martfury' ),
					'2' => esc_html__( 'Style 2', 'martfury' ),
					'3' => esc_html__( 'Style 3', 'martfury' ),
					'4' => esc_html__( 'Style 4', 'martfury' ),
				),
			),
			array(
				'name'             => esc_html__( 'Page Header Background', 'martfury' ),
				'id'               => 'post_header_bg',
				'type'             => 'image_advanced',
				'max_file_uploads' => 1,
				'std'              => false,
				'class'            => 'show-post-header-2',
			),
		),
	);

	// Display Settings
	$meta_boxes[] = array(
		'id'       => 'display-settings',
		'title'    => esc_html__( 'Display Settings', 'martfury' ),
		'pages'    => array( 'post', 'page' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name' => esc_html__( 'Layout', 'martfury' ),
				'id'   => 'heading_layout',
				'type' => 'heading',
			),
			array(
				'name' => esc_html__( 'Custom Layout', 'martfury' ),
				'id'   => 'custom_layout',
				'type' => 'checkbox',
				'std'  => false,
			),
			array(
				'name'    => esc_html__( 'Layout', 'martfury' ),
				'id'      => 'layout',
				'type'    => 'image_select',
				'class'   => 'custom-layout',
				'options' => array(
					'full-content'    => get_template_directory_uri() . '/images/sidebars/empty.png',
					'sidebar-content' => get_template_directory_uri() . '/images/sidebars/single-left.png',
					'content-sidebar' => get_template_directory_uri() . '/images/sidebars/single-right.png',
				),
			),
		),
	);

	// Display Settings
	$meta_boxes[] = array(
		'id'       => 'page-header-settings',
		'title'    => esc_html__( 'Page Header Settings', 'martfury' ),
		'pages'    => array( 'page' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name'  => esc_html__( 'Custom Page Header', 'martfury' ),
				'id'    => 'custom_page_header',
				'type'  => 'checkbox',
				'std'   => false,
				'class' => 'hide-homepage',
			),
			array(
				'name'  => esc_html__( 'Hide Page Header', 'martfury' ),
				'id'    => 'hide_page_header',
				'type'  => 'checkbox',
				'std'   => false,
				'class' => 'hide-homepage',
			),
			array(
				'name'  => esc_html__( 'Hide Breadcrumb', 'martfury' ),
				'id'    => 'hide_breadcrumb',
				'type'  => 'checkbox',
				'std'   => false,
				'class' => 'hide-homepage',
			),
			array(
				'name'  => esc_html__( 'Hide Title', 'martfury' ),
				'id'    => 'hide_title',
				'type'  => 'checkbox',
				'std'   => false,
				'class' => 'hide-homepage',
			),
		),
	);

	$meta_boxes[] = array(
		'id'       => 'product-videos',
		'title'    => esc_html__( 'Product Video', 'martfury' ),
		'pages'    => array( 'product' ),
		'context'  => 'side',
		'priority' => 'low',
		'fields'   => array(
			array(
				'name' => esc_html__( 'Video URL', 'martfury' ),
				'id'   => 'video_url',
				'type' => 'oembed',
				'std'  => false,
				'desc' => esc_html__( 'Enter URL of Youtube or Vimeo or specific filetypes such as mp4, webm, ogv.', 'martfury' ),
			),
			array(
				'name'             => esc_html__( 'Video Thumbnail', 'martfury' ),
				'id'               => 'video_thumbnail',
				'type'             => 'image_advanced',
				'max_file_uploads' => 1,
				'std'              => false,
				'desc'             => esc_html__( 'Add video thumbnail', 'martfury' ),
			),
			array(
				'name'    => esc_html__( 'Video Position', 'martfury' ),
				'id'      => 'video_position',
				'type'    => 'select',
				'options' => array(
					'1' => esc_html__( 'The last product gallery', 'martfury' ),
					'2' => esc_html__( 'The first product gallery', 'martfury' ),
				),
			),
		),
	);

	$meta_boxes[] = array(
		'id'       => 'product-360-view',
		'title'    => esc_html__( 'Product 360 View', 'martfury' ),
		'pages'    => array( 'product' ),
		'context'  => 'side',
		'priority' => 'low',
		'fields'   => array(
			array(
				'id'   => 'product_360_view',
				'type' => 'image_advanced',
			),
		),
	);

	$meta_boxes[] = array(
		'id'       => 'product-features-desc',
		'title'    => esc_html__( 'Product features description', 'martfury' ),
		'pages'    => array( 'product' ),
		'context'  => 'normal',
		'priority' => 'low',
		'fields'   => array(
			array(
				'id'   => 'product_features_desc',
				'type' => 'WYSIWYG',
			),
		),
	);

	return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'martfury_register_meta_boxes' );

function martfury_admin_notice__success() {
	if ( ! function_exists( 'martfury_vc_addons_init' ) ) {
		return;
	}

	$versions = get_plugin_data( WP_PLUGIN_DIR . '/martfury-addons/martfury-addons.php' );
	if ( version_compare( $versions['Version'], '1.2.4', '>=' ) ) {
		return;
	}
	?>
    <div class="notice notice-info is-dismissible">
        <p>
            <strong><?php esc_html_e( 'The Martfury Addons plugin needs to be updated to 1.2.4 to ensure maximum compatibility with this theme. Go to Plugins > Martfury Addons to update it.', 'martfury' ); ?></strong>
        </p>
    </div>
	<?php
}

add_action( 'admin_notices', 'martfury_admin_notice__success' );

function martfury_page_builder_notice() {
	if ( defined( 'ELEMENTOR_VERSION' ) || defined( 'WPB_VC_VERSION' ) ) {
		return;
	}

	?>
    <div class="notice notice-info is-dismissible">
        <p>
            <strong><?php esc_html_e( 'This theme recommends installing and activating only one plugin Elementor or WPBakery Page Builder.', 'martfury' ); ?></strong>
        </p>
    </div>
	<?php
}

add_action( 'admin_notices', 'martfury_page_builder_notice', 25 );


add_action( 'woocommerce_new_product', 'martfury_invalidate_brand_count' );
add_action( 'woocommerce_update_product', 'martfury_invalidate_brand_count' );

function martfury_invalidate_brand_count() {
	delete_transient( 'wc_layered_nav_counts_product_brand' );
}