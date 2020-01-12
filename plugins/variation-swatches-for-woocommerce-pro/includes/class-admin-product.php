<?php

/**
 * Class TA_WC_Variation_Swatches_Admin_Product
 */
class TA_WC_Variation_Swatches_Admin_Product {
	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'woocommerce_product_option_terms', array( $this, 'product_option_terms' ), 10, 2 );

		add_filter( 'woocommerce_product_data_tabs', array( $this, 'swatches_tab' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'swatches_panel' ) );

		add_action( 'woocommerce_process_product_meta', array( $this, 'process_product_swatches_meta' ) );

		add_action( 'wp_ajax_tawcvs_add_new_attribute', array( $this, 'add_new_attribute_ajax' ) );
		add_action( 'admin_footer', array( $this, 'add_attribute_term_template' ) );

		add_action( 'wp_ajax_product_meta_fields', array( $this, 'swatches_panel' ) );
	}

	/**
	 * Add selector for extra attribute types
	 *
	 * @param $taxonomy
	 * @param $index
	 */
	public function product_option_terms( $taxonomy, $index ) {
		if ( ! array_key_exists( $taxonomy->attribute_type, TA_WCVS()->types ) ) {
			return;
		}

		$taxonomy_name = wc_attribute_taxonomy_name( $taxonomy->attribute_name );
		global $thepostid;

		$product_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : $thepostid;
		?>

		<select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'wcvs' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo $index; ?>][]">
			<?php

			$all_terms = get_terms( $taxonomy_name, apply_filters( 'woocommerce_product_attribute_terms', array( 'orderby' => 'name', 'hide_empty' => false ) ) );
			if ( $all_terms ) {
				foreach ( $all_terms as $term ) {
					echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( has_term( absint( $term->term_id ), $taxonomy_name, $product_id ), true, false ) . '>' . esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
				}
			}
			?>
		</select>
		<button class="button plus select_all_attributes"><?php esc_html_e( 'Select all', 'wcvs' ); ?></button>
		<button class="button minus select_no_attributes"><?php esc_html_e( 'Select none', 'wcvs' ); ?></button>
		<button class="button fr plus tawcvs_add_new_attribute" data-type="<?php echo $taxonomy->attribute_type ?>"><?php esc_html_e( 'Add new', 'wcvs' ); ?></button>

		<?php
	}

	/**
	 * Add new product data tab for swatches
	 *
	 * @param array $tabs
	 *
	 * @return array
	 */
	public function swatches_tab( $tabs ) {
		$tabs['swatches'] = array(
			'label'    => __( 'Swatches', 'wcvs' ),
			'target'   => 'variable_product_swatches',
			'class'    => array( 'swatches_tab', 'show_if_variable' ),
			'priority' => 61,
		);

		return $tabs;
	}

	/**
	 * Outputs the swatches panel
	 */
	public function swatches_panel() {
		global $post, $thepostid, $product_object;
		?>

		<div id="variable_product_swatches" class="panel variable_swatches_panel woocommerce_options_panel wc-metaboxes-wrapper hidden">
			<div id="variable_product_swatches_inner" class="variable_product_swatches wc-metaboxes">
				<?php
				$attributes   = $product_object->get_attributes( 'edit' );
				$swatches     = get_post_meta( $thepostid, 'tawcvs_swatches', true );
				$swatch_style = get_option( 'tawcvs_swatch_style', 'round' );
				$swatch_size  = get_option( 'tawcvs_swatch_image_size', array(
					'width'  => '30',
					'height' => '30',
				) );
				$type_labels  = wp_parse_args( TA_WCVS()->types, array( 'select' => esc_html__( 'Select', 'wcvs' ) ) );
				$style_labels = array(
					'round'   => esc_html__( 'Round', 'wcvs' ),
					'rounded' => esc_html__( 'Rounded', 'wcvs' ),
					'square'  => esc_html__( 'Square', 'wcvs' ),
				);
				$i            = -1;

				foreach ( $attributes as $attribute ) {
					$i++;
					$metabox_class = array();
					$option_name   = sanitize_title( $attribute->get_name() );

					if ( ! $attribute->get_variation() ) {
						continue;
					}

					if ( $attribute->is_taxonomy() ) {
						$metabox_class[] = 'taxonomy';
						$metabox_class[] = $attribute->get_name();
					}

					if ( isset( $swatches[$option_name] ) ) {
						$swatch                  = $swatches[ $option_name ];
						$swatch['default_type']  = $attribute->is_taxonomy() ? $attribute->get_taxonomy_object()->attribute_type : 'select';
						$swatch['default_size']  = $swatch_size;
						$swatch['default_style'] = $swatch_style;
					} else {
						$swatch = array(
							'type'          => 'default',
							'style'         => 'default',
							'size'          => 'default',
							'custom_size'   => $swatch_size,
							'default_type'  => $attribute->is_taxonomy() ? $attribute->get_taxonomy_object()->attribute_type : 'select',
							'default_style' => $swatch_style,
							'default_size'  => $swatch_size,
							'swatches'      => array(),
						);
					}

					include( 'views/html-product-data-swatches.php' );
				}
				?>
			</div>
		</div>

		<?php
	}

	/**
	 * Save custom swatches data
	 *
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public function process_product_swatches_meta( $post_id ) {
		if ( ! ( isset( $_POST['woocommerce_meta_nonce'], $_POST['tawcvs_swatches'] ) || wp_verify_nonce( sanitize_key( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ) ) {
			return false;
		}

		update_post_meta( $post_id, 'tawcvs_swatches', $_POST['tawcvs_swatches'] );
	}

	/**
	 * Ajax function handles adding new attribute term
	 */
	public function add_new_attribute_ajax() {
		$nonce  = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		$tax    = isset( $_POST['taxonomy'] ) ? $_POST['taxonomy'] : '';
		$type   = isset( $_POST['type'] ) ? $_POST['type'] : '';
		$name   = isset( $_POST['name'] ) ? $_POST['name'] : '';
		$slug   = isset( $_POST['slug'] ) ? $_POST['slug'] : '';
		$swatch = isset( $_POST['swatch'] ) ? $_POST['swatch'] : '';

		if ( ! wp_verify_nonce( $nonce, '_tawcvs_create_attribute' ) ) {
			wp_send_json_error( esc_html__( 'Wrong request', 'wcvs' ) );
		}

		if ( empty( $name ) || empty( $swatch ) || empty( $tax ) || empty( $type ) ) {
			wp_send_json_error( esc_html__( 'Not enough data', 'wcvs' ) );
		}

		if ( ! taxonomy_exists( $tax ) ) {
			wp_send_json_error( esc_html__( 'Taxonomy is not exists', 'wcvs' ) );
		}

		if ( term_exists( $_POST['name'], $_POST['tax'] ) ) {
			wp_send_json_error( esc_html__( 'This term is exists', 'wcvs' ) );
		}

		$term = wp_insert_term( $name, $tax, array( 'slug' => $slug ) );

		if ( is_wp_error( $term ) ) {
			wp_send_json_error( $term->get_error_message() );
		} else {
			$term = get_term_by( 'id', $term['term_id'], $tax );
			update_term_meta( $term->term_id, $type, $swatch );
		}

		wp_send_json_success(
			array(
				'msg'  => esc_html__( 'Added successfully', 'wcvs' ),
				'id'   => $term->term_id,
				'slug' => $term->slug,
				'name' => $term->name,
			)
		);
	}

	/**
	 * Print HTML of modal at admin footer and add js templates
	 */
	public function add_attribute_term_template() {
		global $pagenow, $post;

		if ( $pagenow != 'post.php' || ( isset( $post ) && get_post_type( $post->ID ) != 'product' ) ) {
			return;
		}
		?>

		<div id="tawcvs-modal-container" class="tawcvs-modal-container">
			<div class="tawcvs-modal">
				<button type="button" class="button-link media-modal-close tawcvs-modal-close">
					<span class="media-modal-icon"></span></button>
				<div class="tawcvs-modal-header"><h2><?php esc_html_e( 'Add new term', 'wcvs' ) ?></h2></div>
				<div class="tawcvs-modal-content">
					<p class="tawcvs-term-name">
						<label>
							<?php esc_html_e( 'Name', 'wcvs' ) ?>
							<input type="text" class="widefat tawcvs-input" name="name">
						</label>
					</p>
					<p class="tawcvs-term-slug">
						<label>
							<?php esc_html_e( 'Slug', 'wcvs' ) ?>
							<input type="text" class="widefat tawcvs-input" name="slug">
						</label>
					</p>
					<div class="tawcvs-term-swatch">

					</div>
					<div class="hidden tawcvs-term-tax"></div>

					<input type="hidden" class="tawcvs-input" name="nonce" value="<?php echo wp_create_nonce( '_tawcvs_create_attribute' ) ?>">
				</div>
				<div class="tawcvs-modal-footer">
					<button class="button button-secondary tawcvs-modal-close"><?php esc_html_e( 'Cancel', 'wcvs' ) ?></button>
					<button class="button button-primary tawcvs-new-attribute-submit"><?php esc_html_e( 'Add New', 'wcvs' ) ?></button>
					<span class="message"></span>
					<span class="spinner"></span>
				</div>
			</div>
			<div class="tawcvs-modal-backdrop media-modal-backdrop"></div>
		</div>

		<script type="text/template" id="tmpl-tawcvs-input-color">

			<label><?php esc_html_e( 'Color', 'wcvs' ) ?></label><br>
			<input type="text" class="tawcvs-input tawcvs-input-color" name="swatch">

		</script>

		<script type="text/template" id="tmpl-tawcvs-input-image">

			<label><?php esc_html_e( 'Image', 'wcvs' ) ?></label><br>
			<div class="tawcvs-term-image-thumbnail" style="float:left;margin-right:10px;">
				<img src="<?php echo esc_url( WC()->plugin_url() . '/assets/images/placeholder.png' ) ?>" width="60px" height="60px" />
			</div>
			<div style="line-height:60px;">
				<input type="hidden" class="tawcvs-input tawcvs-input-image tawcvs-term-image" name="swatch" value="" />
				<button type="button" class="tawcvs-upload-image-button button"><?php esc_html_e( 'Upload/Add image', 'wcvs' ); ?></button>
				<button type="button" class="tawcvs-remove-image-button button hidden"><?php esc_html_e( 'Remove image', 'wcvs' ); ?></button>
			</div>

		</script>

		<script type="text/template" id="tmpl-tawcvs-input-label">

			<label>
				<?php esc_html_e( 'Label', 'wcvs' ) ?>
				<input type="text" class="widefat tawcvs-input tawcvs-input-label" name="swatch">
			</label>

		</script>

		<script type="text/template" id="tmpl-tawcvs-input-tax">

			<input type="hidden" class="tawcvs-input" name="taxonomy" value="{{data.tax}}">
			<input type="hidden" class="tawcvs-input" name="type" value="{{data.type}}">

		</script>
		<?php
	}
}

new TA_WC_Variation_Swatches_Admin_Product();