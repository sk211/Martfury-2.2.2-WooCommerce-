<?php
/**
 * Register post types
 *
 * @package Martfury
 */

/**
 * Class Martfury_Taxonomies
 */
class Martfury_Taxonomies {

	/**
	 * Construction function
	 *
	 * @since 1.0.0
	 *
	 * @return Martfury_Taxonomies
	 */

	/**
	 * Check if active brand
	 *
	 * @var bool
	 */
	private $active_brand = true;
	private $option = 'product_brand_slug';

	/**
	 * @var string placeholder image
	 */
	public $placeholder_img_src;


	public function __construct() {

		if ( ! function_exists( 'is_woocommerce' ) ) {
			return false;
		}

		add_action( 'admin_init', array( $this, 'settings_api_init' ) );
		add_action( 'current_screen', array( $this, 'settings_save' ) );

		$this->active_brand = apply_filters( 'martfury_register_brand', true );

		if ( ! $this->active_brand ) {
			return false;
		}

		if ( get_option( $this->option ) ) {
			return false;
		}

		// Register custom post type and custom taxonomy
		add_action( 'init', array( $this, 'register_brand' ), 100 );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		$this->placeholder_img_src = MARTFURY_ADDONS_URL . '/img/placeholder.png';
		// Add form
		add_action( 'product_brand_add_form_fields', array( $this, 'add_category_fields' ) );
		add_action( 'product_brand_edit_form_fields', array( $this, 'edit_category_fields' ), 20 );
		add_action( 'created_term', array( $this, 'save_category_fields' ), 20, 3 );
		add_action( 'edit_term', array( $this, 'save_category_fields' ), 20, 3 );

		// Add columns
		add_filter( 'manage_edit-product_brand_columns', array( $this, 'product_brand_columns' ) );
		add_filter( 'manage_product_brand_custom_column', array( $this, 'product_brand_column' ), 10, 3 );

		add_filter( 'woocommerce_sortable_taxonomies', array( $this, 'product_brand_sortable' ) );

	}

	/**
	 * Register custom post type for testimonails
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function register_brand() {
		// Return if post type is exists
		if ( post_type_exists( 'mf-brand' ) ) {
			return;
		}

		if ( ! post_type_exists( 'product' ) ) {
			return;
		}


		$labels = array(
			'name'                       => __( 'Brands', 'martfury' ),
			'singular_name'              => __( 'Brand', 'martfury' ),
			'menu_name'                  => __( 'Brands', 'martfury' ),
			'all_items'                  => __( 'All Brands', 'martfury' ),
			'edit_item'                  => __( 'Edit Brand', 'martfury' ),
			'view_item'                  => __( 'View Brand', 'martfury' ),
			'update_item'                => __( 'Update Brand', 'martfury' ),
			'add_new_item'               => __( 'Add New Brand', 'martfury' ),
			'new_item_name'              => __( 'New Brand Name', 'martfury' ),
			'parent_item'                => __( 'Parent Brand', 'martfury' ),
			'parent_item_colon'          => __( 'Parent Brand:', 'martfury' ),
			'search_items'               => __( 'Search Brands', 'martfury' ),
			'popular_items'              => __( 'Popular Brands', 'martfury' ),
			'separate_items_with_commas' => __( 'Separate brands with commas', 'martfury' ),
			'add_or_remove_items'        => __( 'Add or remove brands', 'martfury' ),
			'choose_from_most_used'      => __( 'Choose from the most used brands', 'martfury' ),
			'not_found'                  => __( 'No brands found', 'martfury' )
		);

		$permalinks         = get_option( 'product_brand_permalinks' );
		$product_brand_base = empty( $permalinks['product_brand_base'] ) ? _x( 'product-brand', 'slug', 'martfury' ) : $permalinks['product_brand_base'];

		$args = array(
			'hierarchical'          => true,
			'update_count_callback' => '_wc_term_recount',
			'labels'                => $labels,
			'show_ui'               => true,
			'query_var'             => true,
			'rewrite'               => array(
				'slug'         => $product_brand_base,
				'hierarchical' => true,
				'ep_mask'      => EP_PERMALINK
			)
		);

		register_taxonomy( 'product_brand', array( 'product' ), $args );
	}

	public function register_admin_scripts( $hook ) {
		$screen = get_current_screen();
		if ( $hook == 'edit-tags.php' && $screen->taxonomy == 'product_brand' || $hook == 'term.php' && $screen->taxonomy == 'product_brand' ) {
			wp_enqueue_media();
		}
		wp_enqueue_script( 'mf_product_brand_js', MARTFURY_ADDONS_URL . "/assets/js/taxonomies.js", array( 'jquery' ), '20160802', true );

	}

	public function product_brand_sortable( $taxonomy ) {
		$taxonomy[] = 'product_brand';

		return $taxonomy;

	}

	/**
	 * Add  field in 'Settings' > 'Reading'
	 * for enabling CPT functionality.
	 */
	public function settings_api_init() {
		add_settings_section(
			'martfury_brand_section',
			'<span id="brand-options">' . esc_html__( 'Product brand', 'martfury' ) . '</span>',
			array( $this, 'writing_section_html' ),
			'writing'
		);
		
		add_settings_field(
			$this->option,
			'<span class="brand-options">' . esc_html__( 'Product brand', 'martfury' ) . '</span>',
			array( $this, 'disable_field_html' ),
			'writing',
			'martfury_brand_section'
		);
		register_setting(
			'writing',
			$this->option,
			'intval'
		);

		add_settings_field(
			'product_brand_slug',
			'<label for="product_brand_slug">' . esc_html__( 'Product brand base', 'martfury' ) . '</label>',
			array( $this, 'product_brand_slug_input' ),
			'permalink',
			'optional'
		);

		register_setting(
			'permalink',
			'product_brand_slug',
			'sanitize_text_field'
		);
	}

	/**
	 * Show a slug input box.
	 */
	public function product_brand_slug_input() {
		$permalinks = get_option( 'product_brand_permalinks' );
		$brand_base = isset( $permalinks['product_brand_base'] ) ? $permalinks['product_brand_base'] : '';
		?>
		<input name="product_brand_slug" type="text" class="regular-text code" value="<?php echo esc_attr( $brand_base ); ?>" placeholder="<?php echo esc_attr_x( 'product-brand', 'slug', 'martfury' ) ?>" />
		<?php
	}

	/**
	 * Save the settings.
	 */
	public function settings_save() {
		if ( ! is_admin() ) {
			return;
		}

		if ( ! $screen = get_current_screen() ) {
			return;
		}

		if ( 'options-permalink' != $screen->id ) {
			return;
		}

		$permalinks = get_option( 'product_brand_permalinks' );

		if ( isset( $_POST['product_brand_slug'] ) ) {
			$permalinks['product_brand_base'] = $this->sanitize_permalink( trim( $_POST['product_brand_slug'] ) );
		}

		update_option( 'product_brand_permalinks', $permalinks );
	}

	/**
	 * Sanitize permalink
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	private function sanitize_permalink( $value ) {
		global $wpdb;

		$value = $wpdb->strip_invalid_text_for_column( $wpdb->options, 'option_value', $value );

		if ( is_wp_error( $value ) ) {
			$value = '';
		}

		$value = esc_url_raw( $value );
		$value = str_replace( 'http://', '', $value );

		return untrailingslashit( $value );
	}

	/**
	 * Category thumbnail fields.
	 */
	public function add_category_fields() {
		?>
		<div class="form-field" id="product-brand-thumb-box">
			<label><?php esc_html_e( 'Thumbnail', 'martfury' ); ?></label>

			<div id="product_brand_thumb" class="product-brand-thumb" data-rel="<?php echo esc_url( $this->placeholder_img_src ); ?>">
				<img src="<?php echo esc_url( $this->placeholder_img_src ); ?>" width="60px" height="60px" /></div>
			<div class="product-brand-thumb-box">
				<input type="hidden" id="product_brand_thumb_id" name="product_brand_thumb_id" />
				<button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'martfury' ); ?></button>
				<button type="button" class="remove_image_button button"><?php esc_html_e( 'Remove image', 'martfury' ); ?></button>
			</div>
			<div class="clear"></div>
		</div>
		<?php
	}

	/**
	 * Edit category thumbnail field.
	 *
	 * @param mixed $term Term (category) being edited
	 */
	public function edit_category_fields( $term ) {

		$thumbnail_id = '';
		if ( function_exists( 'get_term_meta' ) ) {
			$thumbnail_id = absint( get_term_meta( $term->term_id, 'brand_thumbnail_id', true ) );
		}

		if ( $thumbnail_id ) {
			$image = wp_get_attachment_thumb_url( $thumbnail_id );
		} else {
			$image = $this->placeholder_img_src;
		}
		?>
		<tr class="form-field product-brand-thumb" id="product-brand-thumb-box">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Thumbnail', 'martfury' ); ?></label></th>
			<td>
				<div id="product_brand_thumb" class="product-brand-thumb" data-rel="<?php echo esc_url( $this->placeholder_img_src ); ?>">
					<img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" />
				</div>
				<div class="product-brand-thumb-box">
					<input type="hidden" id="product_brand_thumb_id" name="product_brand_thumb_id" value="<?php echo esc_attr( $thumbnail_id ); ?>" />
					<button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'martfury' ); ?></button>
					<button type="button" class="remove_image_button button"><?php esc_html_e( 'Remove image', 'martfury' ); ?></button>
				</div>
				<div class="clear"></div>
			</td>
		</tr>
		<?php
	}

	/**
	 * save_category_fields function.
	 *
	 * @param mixed  $term_id Term ID being saved
	 * @param mixed  $tt_id
	 * @param string $taxonomy
	 */
	public function save_category_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
		if ( isset( $_POST['product_brand_thumb_id'] ) && 'product_brand' === $taxonomy && function_exists( 'update_woocommerce_term_meta' ) ) {
			update_woocommerce_term_meta( $term_id, 'brand_thumbnail_id', absint( $_POST['product_brand_thumb_id'] ) );
		}
	}

	/**
	 * Thumbnail column added to category admin.
	 *
	 * @param mixed $columns
	 *
	 * @return array
	 */
	public function product_brand_columns( $columns ) {
		$new_columns = array();

		if ( isset( $columns['cb'] ) ) {
			$new_columns['cb'] = $columns['cb'];
			unset( $columns['cb'] );
		}

		$new_columns['thumb'] = esc_html__( 'Image', 'martfury' );

		return array_merge( $new_columns, $columns );
	}

	/**
	 * Thumbnail column value added to category admin.
	 *
	 * @param string $columns
	 * @param string $column
	 * @param int    $id
	 *
	 * @return string
	 */
	public function product_brand_column( $columns, $column, $id ) {

		if ( 'thumb' == $column ) {

			$thumbnail_id = get_term_meta( $id, 'brand_thumbnail_id', true );

			if ( $thumbnail_id ) {
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
			} else {
				$image = $this->placeholder_img_src;
			}

			// Prevent esc_url from breaking spaces in urls for image embeds
			// Ref: https://core.trac.wordpress.org/ticket/23605
			$image = str_replace( ' ', '%20', $image );

			$columns .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( 'Thumbnail', 'martfury' ) . '" class="wp-post-image" height="48" width="48" />';

		}

		return $columns;
	}
	
	/**
	 * Add writing setting section
	 */
	public function writing_section_html() {
		?>
		<p>
			<?php esc_html_e( 'Use these settings to disable custom types of content on your site', 'martfury' ); ?>
		</p>
		<?php
	}

	/**
	 * HTML code to display a checkbox true/false option
	 * for the Services CPT setting.
	 */
	public function disable_field_html() {
		?>

		<label for="<?php echo esc_attr( $this->option ); ?>">
			<input name="<?php echo esc_attr( $this->option ); ?>"
				   id="<?php echo esc_attr( $this->option ); ?>" <?php checked( get_option( $this->option ), true ); ?>
				   type="checkbox" value="1" />
			<?php esc_html_e( 'Disable Brand for this site.', 'martfury' ); ?>
		</label>

		<?php
	}

}
