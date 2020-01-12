<?php
/**
 * Register post types
 *
 * @package Martfury
 */

/**
 * Class Product Categtory
 */
class Martfury_Product_Cat {

	/**
	 * Construction function
	 *
	 * @since 1.0.0
	 *
	 * @return Martfury_Taxonomies
	 */

	/**
	 * Check if category layout
	 *
	 * @var bool
	 */
	private $cat_layout = array();

	/**
	 * Sidebar
	 *
	 * @var bool
	 */
	private $cat_sidebars = array();

	/**
	 * @var string placeholder image
	 */
	public $placeholder_img_src;


	function __construct() {

		if ( ! function_exists( 'is_woocommerce' ) ) {
			return false;
		}

		$this->cat_layout = array(
			'0' => esc_html__( 'Default', 'martfury' ),
			'1' => esc_html__( 'Layout 1', 'martfury' ),
			'2' => esc_html__( 'Layout 2', 'martfury' ),
			'3' => esc_html__( 'Layout 3', 'martfury' ),
		);

		$this->cat_sidebars[''] = esc_html__( 'Default', 'martfury' );
		global $wp_registered_sidebars;
		if ( $wp_registered_sidebars ) {
			foreach ( $wp_registered_sidebars as $sidebar ) {
				$this->cat_sidebars[ $sidebar['id'] ] = $sidebar['name'];
			}
		}

		// Register custom post type and custom taxonomy
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		$this->placeholder_img_src = get_template_directory_uri() . '/images/placeholder.png';
		// Add form
		add_action( 'product_cat_add_form_fields', array( $this, 'add_category_fields' ), 30 );
		add_action( 'product_cat_edit_form_fields', array( $this, 'edit_category_fields' ), 20 );
		add_action( 'created_term', array( $this, 'save_category_fields' ), 20, 3 );
		add_action( 'edit_term', array( $this, 'save_category_fields' ), 20, 3 );

	}


	function register_admin_scripts( $hook ) {
		$screen = get_current_screen();
		if ( ( $hook == 'edit-tags.php' && ( $screen->taxonomy == 'product_cat' || $screen->taxonomy == 'product_brand' ) ) || ( $hook == 'term.php' && $screen->taxonomy == 'product_cat' ) ) {
			wp_enqueue_media();
			wp_enqueue_script( 'martfury_product_cat_js', get_template_directory_uri() . "/js/backend/product-cat.js", array( 'jquery' ), '20161101', true );
			wp_enqueue_style( 'martfury_product_cat_style', get_template_directory_uri() . "/css/backend/product-cat.css", array(), '20161101' );
		}
	}


	/**
	 * Category thumbnail fields.
	 */
	function add_category_fields() {
		?>
        <div class="form-field mf-cat-banners-group">
            <label><?php esc_html_e( 'Banners', 'martfury' ); ?></label>

            <div id="mf_cat_banners" class="mf-cat-banners">
                <ul class="mf-cat-images"></ul>
                <input type="hidden" id="mf_cat_banners_id" class="mf_cat_banners_id" name="mf_cat_banners_id"/>
                <button type="button" data-multiple="1" data-delete="<?php esc_html_e( 'Delete image', 'martfury' ); ?>"
                        data-text="<?php esc_html_e( 'Delete', 'martfury' ); ?>"
                        class="upload_images_button button"><?php esc_html_e( 'Upload/Add Images', 'martfury' ); ?></button>
            </div>
            <div class="clear"></div>
        </div>
        <div class="form-field mf-cat-banners-group">
            <label><?php esc_html_e( 'Banners Link', 'martfury' ); ?></label>
            <div id="mf_cat_banners_link" class="mf-cat-banners_link">
                <textarea id="mf_cat_banners_link" cols="50" rows="3" name="mf_cat_banners_link"></textarea>
                <p class="description"><?php esc_html_e( 'Enter links for each banner here. Divide links with linebreaks (Enter).', 'martfury' ); ?></p>
            </div>
            <div class="clear"></div>
        </div>

        <div class="form-field mf-cat-banners-group">
            <label><?php esc_html_e( 'Category Layout', 'martfury' ); ?></label>
            <div class="mf-cat-layout">
                <select id="mf_cat_layout" name="mf_cat_layout">
					<?php
					foreach ( $this->cat_layout as $key => $value ) {
						?>
                        <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
						<?php
					}
					?>
                </select>
            </div>
            <div class="clear"></div>
        </div>
        <div class="form-field mf-cat-banners-group" id="mf-custom-elements">
            <label><?php esc_html_e( 'Elements', 'martfury' ); ?></label>
            <div class="mf-custom-elements" id="mf-custom-elements-1">
                <p>
                    <input type="checkbox" checked="checked" id="product_cat_1_title" name="product_cat_1_els[]"
                           value="title">
                    <label for="product_cat_1_title"> <?php esc_html_e( 'Page Title', 'martfury' ); ?></label>
                </p>
                <p>
                    <input type="checkbox" checked="checked" name="product_cat_1_els[]" id="product_cat_1_banners"
                           value="banners">
                    <label for="product_cat_1_banners"><?php esc_html_e( 'Banners', 'martfury' ); ?></label>
                </p>
                <p>
                    <input type="checkbox" checked="checked" name="product_cat_1_els[]" id="product_cat_1_brands"
                           value="brands">
                    <label for="product_cat_1_brands"><?php esc_html_e( 'Brands', 'martfury' ); ?></label>
                </p>
                <p>
                    <input type="checkbox" checked="checked" name="product_cat_1_els[]" id="product_cat_1_categories"
                           value="categories">
                    <label for="product_cat_1_categories"><?php esc_html_e( 'Categories Box', 'martfury' ); ?></label>
                </p>
                <p>
                    <input type="checkbox" checked="checked" name="product_cat_1_els[]" id="product_cat_1_carousel"
                           value="products_carousel">
                    <label for="product_cat_1_carousel"><?php esc_html_e( 'Product Carousel', 'martfury' ); ?></label>
                </p>
            </div>
            <div class="mf-custom-elements" id="mf-custom-elements-2">
                <p>
                    <input type="checkbox" checked="checked" name="product_cat_2_els[]" id="product_cat_1_title"
                           value="title">
                    <label for="product_cat_2_title"> <?php esc_html_e( 'Page Title', 'martfury' ); ?></label>
                </p>
                <p>
                    <input type="checkbox" checked="checked" name="product_cat_2_els[]" id="product_cat_2_top"
                           value="top_categories">
                    <label for="product_cat_2_top"><?php esc_html_e( 'Top Categories', 'martfury' ); ?></label>
                </p>
                <p>
                    <input type="checkbox" checked="checked" name="product_cat_2_els[]" id="product_cat_2_carousel"
                           value="products_carousel">
                    <label for="product_cat_2_carousel"><?php esc_html_e( 'Products Carousel', 'martfury' ); ?></label>
                </p>
                <p>
                    <input type="checkbox" checked="checked" name="product_cat_2_els[]" id="product_cat_2_featured"
                           value="featured_categories">
                    <label for="product_cat_2_featured"><?php esc_html_e( 'Featured Categories', 'martfury' ); ?></label>
                </p>
                <p>
                    <input type="checkbox" checked="checked" name="product_cat_2_els[]" id="product_cat_2_other"
                           value="other_categories">
                    <label for="product_cat_2_other"><?php esc_html_e( 'Other Categories', 'martfury' ); ?></label>
                </p>
            </div>
            <div class="mf-custom-elements" id="mf-custom-elements-3">
                <p>
                    <input type="checkbox" checked="checked" name="product_cat_3_els[]" id="product_cat_3_title"
                           value="title">
                    <label for="product_cat_3_title"><?php esc_html_e( 'Page Title', 'martfury' ); ?></label>
                </p>
                <p>
                    <input type="checkbox" checked="checked" name="product_cat_3_els[]" id="product_cat_3_banners"
                           value="banners">
                    <label for="product_cat_3_banners"><?php esc_html_e( 'Banners', 'martfury' ); ?></label>
                </p>
                <p>
                    <input type="checkbox" checked="checked" name="product_cat_3_els[]" id="product_cat_3_carousel"
                           value="products_carousel">
                    <label for="product_cat_3_carousel"><?php esc_html_e( 'Products Carousel', 'martfury' ); ?></label>
                </p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="form-field mf-cat-banners-2-group">
            <label><?php esc_html_e( 'Single Banner', 'martfury' ); ?></label>

            <div id="mf_cat_banners-2" class="mf-cat-banners-2"
                 data-rel="<?php echo esc_url( $this->placeholder_img_src ); ?>">
                <img class="mf-cat-image" src="<?php echo esc_url( $this->placeholder_img_src ); ?>" width="60px"
                     height="60px"/>
                <input type="hidden" id="mf_cat_banners_2_id" name="mf_cat_banners_2_id"/>
                <button type="button"
                        class="upload_banner_2_button button"><?php esc_html_e( 'Upload/Add image', 'martfury' ); ?></button>
                <button type="button"
                        class="remove_banner_2_button button"><?php esc_html_e( 'Remove image', 'martfury' ); ?></button>
                <p class="description"><?php esc_html_e( 'This option is used for the category is level 1 and layout 2.', 'martfury' ); ?></p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="form-field mf-cat-banners-2-group">
            <label><?php esc_html_e( 'Single Banner Link', 'martfury' ); ?></label>
            <div id="mf_cat_banners_2_link" class="mf-cat-banners_2_link"
            ">
            <textarea id="mf_cat_banners_2_link" cols="50" rows="1" name="mf_cat_banners_2_link"></textarea>
            <p class="description"><?php esc_html_e( 'This option is used for the category is level 1 and layout 2.', 'martfury' ); ?></p>
        </div>
        <div class="clear"></div>
        </div>
        <div class="form-field mf-cat-sidebar-group">
            <label><?php esc_html_e( 'Custom Sidebar', 'martfury' ); ?></label>
            <div class="mf-cat-sidebar">
                <select id="mf_cat_sidebar" name="mf_cat_sidebar">
					<?php
					foreach ( $this->cat_sidebars as $key => $value ) {
						?>
                        <option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
						<?php
					}
					?>
                </select>
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
	function edit_category_fields( $term ) {

		$thumbnail_ids     = get_term_meta( $term->term_id, 'mf_cat_banners_id', true );
		$banners_2_ids     = absint( get_term_meta( $term->term_id, 'mf_cat_banners_2_id', true ) );
		$banners_link      = get_term_meta( $term->term_id, 'mf_cat_banners_link', true );
		$banners_2_link    = get_term_meta( $term->term_id, 'mf_cat_banners_2_link', true );
		$cat_layout        = get_term_meta( $term->term_id, 'mf_cat_layout', true );
		$cat_sidebar       = get_term_meta( $term->term_id, 'mf_cat_sidebar', true );
		$product_cat_1_els = get_term_meta( $term->term_id, 'product_cat_1_els', true );
		$product_cat_2_els = get_term_meta( $term->term_id, 'product_cat_2_els', true );
		$product_cat_3_els = get_term_meta( $term->term_id, 'product_cat_3_els', true );

		$product_cat_1_els = $product_cat_1_els ? $product_cat_1_els : array();
		$product_cat_2_els = $product_cat_2_els ? $product_cat_2_els : array();
		$product_cat_3_els = $product_cat_3_els ? $product_cat_3_els : array();


		?>
        <tr class="form-field mf-cat-banners-group">
            <th scope="row" valign="top"><label><?php esc_html_e( 'Banners', 'martfury' ); ?></label></th>
            <td>
                <div id="mf_cat_banners" class="mf-cat-banners">
                    <ul class="mf-cat-images">
						<?php

						if ( $thumbnail_ids ) {
							$thumbnails = explode( ',', $thumbnail_ids );
							foreach ( $thumbnails as $thumbnail_id ) {
								if ( empty( $thumbnail_id ) ) {
									continue;
								}
								$image = wp_get_attachment_thumb_url( $thumbnail_id );
								if ( empty( $image ) ) {
									continue;
								}
								?>
                                <li class="image" data-attachment_id="<?php echo esc_attr( $thumbnail_id ); ?>">
                                    <img src="<?php echo esc_url( $image ); ?>" width="100px" height="100px"/>
                                    <ul class="actions">
                                        <li>
                                            <a href="#" class="delete"
                                               title="<?php esc_html_e( 'Delete image', 'martfury' ); ?>"><?php esc_html_e( 'Delete', 'martfury' ); ?></a>
                                        </li>
                                    </ul>
                                </li>
								<?php
							}
						}

						?>
                    </ul>
                    <input type="hidden" id="mf_cat_banners_id" class="mf_cat_banners_id" name="mf_cat_banners_id"
                           value="<?php echo esc_attr( $thumbnail_ids ); ?>"/>
                    <button type="button" data-multiple="1"
                            data-delete="<?php esc_html_e( 'Delete image', 'martfury' ); ?>"
                            data-text="<?php esc_html_e( 'Delete', 'martfury' ); ?>"
                            class="upload_images_button button"><?php esc_html_e( 'Upload/Add Images', 'martfury' ); ?></button>
                </div>
                <div class="clear"></div>
            </td>
        </tr>
        <tr class="form-field mf-cat-banners-group">
            <th scope="row" valign="top"><label><?php esc_html_e( 'Banners Link', 'martfury' ); ?></label></th>
            <td>
                <div id="mf_cat_banners_link" class="mf-cat-banners-link">
                    <textarea id="mf_cat_banners_link" cols="50" rows="4"
                              name="mf_cat_banners_link"><?php echo esc_html( $banners_link ); ?></textarea>
                    <p class="description"><?php esc_html_e( 'Enter links for each banner here. Divide links with linebreaks (Enter).', 'martfury' ); ?></p>
                </div>
                <div class="clear"></div>
            </td>
        </tr>

        <tr class="form-field mf-cat-banners-group">
            <th scope="row" valign="top"><label><?php esc_html_e( 'Category Layout', 'martfury' ); ?></label></th>
            <td>
                <div class="mf-cat-layout">
                    <select id="mf_cat_layout" name="mf_cat_layout">
						<?php
						foreach ( $this->cat_layout as $key => $value ) {
							$selected = ( $key == $cat_layout ) ? 'selected=selected' : '';
							?>
                            <option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $value ); ?></option>
							<?php
						}
						?>
                    </select>
                </div>
                <div class="clear"></div>
            </td>
        </tr>
        <tr class="form-field mf-cat-banners-group" id="mf-custom-elements">
            <th scope="row" valign="top"><label><?php esc_html_e( 'Elements', 'martfury' ); ?></label></th>
            <td>
                <div class="mf-custom-elements" id="mf-custom-elements-1">
                    <p>
                        <input type="checkbox" <?php checked( in_array( 'title', $product_cat_1_els ) ); ?>
                               id="product_cat_1_title" name="product_cat_1_els[]" value="title">
                        <label for="product_cat_1_title"> <?php esc_html_e( 'Page Title', 'martfury' ); ?></label>
                    </p>
                    <p>
                        <input type="checkbox" <?php checked( in_array( 'banners', $product_cat_1_els ) ); ?>
                               name="product_cat_1_els[]" id="product_cat_1_banners" value="banners">
                        <label for="product_cat_1_banners"><?php esc_html_e( 'Banners', 'martfury' ); ?></label>
                    </p>
                    <p>
                        <input type="checkbox" <?php checked( in_array( 'brands', $product_cat_1_els ) ); ?>
                               name="product_cat_1_els[]" id="product_cat_1_brands" value="brands">
                        <label for="product_cat_1_brands"><?php esc_html_e( 'Brands', 'martfury' ); ?></label>
                    </p>
                    <p>
                        <input type="checkbox" <?php checked( in_array( 'categories', $product_cat_1_els ) ); ?>
                               name="product_cat_1_els[]" id="product_cat_1_categories" value="categories">
                        <label for="product_cat_1_categories"><?php esc_html_e( 'Categories Box', 'martfury' ); ?></label>
                    </p>
                    <p>
                        <input type="checkbox" <?php checked( in_array( 'products_carousel', $product_cat_1_els ) ); ?>
                               name="product_cat_1_els[]" id="product_cat_1_carousel" value="products_carousel">
                        <label for="product_cat_1_carousel"><?php esc_html_e( 'Product Carousel', 'martfury' ); ?></label>
                    </p>
                </div>
                <div class="mf-custom-elements" id="mf-custom-elements-2">
                    <p>
                        <input type="checkbox" <?php checked( in_array( 'title', $product_cat_2_els ) ); ?>
                               name="product_cat_2_els[]" id="product_cat_1_title" value="title">
                        <label for="product_cat_2_title"> <?php esc_html_e( 'Page Title', 'martfury' ); ?></label>
                    </p>
                    <p>
                        <input type="checkbox" <?php checked( in_array( 'top_categories', $product_cat_2_els ) ); ?>
                               name="product_cat_2_els[]" id="product_cat_2_top" value="top_categories">
                        <label for="product_cat_2_top"><?php esc_html_e( 'Top Categories', 'martfury' ); ?></label>
                    </p>
                    <p>
                        <input type="checkbox" <?php checked( in_array( 'products_carousel', $product_cat_2_els ) ); ?>
                               name="product_cat_2_els[]" id="product_cat_2_carousel" value="products_carousel">
                        <label for="product_cat_2_carousel"><?php esc_html_e( 'Products Carousel', 'martfury' ); ?></label>
                    </p>
                    <p>
                        <input type="checkbox" <?php checked( in_array( 'featured_categories', $product_cat_2_els ) ); ?>
                               name="product_cat_2_els[]" id="product_cat_2_featured" value="featured_categories">
                        <label for="product_cat_2_featured"><?php esc_html_e( 'Featured Categories', 'martfury' ); ?></label>
                    </p>
                    <p>
                        <input type="checkbox" <?php checked( in_array( 'other_categories', $product_cat_2_els ) ); ?>
                               name="product_cat_2_els[]" id="product_cat_2_other" value="other_categories">
                        <label for="product_cat_2_other"><?php esc_html_e( 'Other Categories', 'martfury' ); ?></label>
                    </p>
                </div>
                <div class="mf-custom-elements" id="mf-custom-elements-3">
                    <p>
                        <input type="checkbox" <?php checked( in_array( 'title', $product_cat_3_els ) ); ?>
                               name="product_cat_3_els[]" id="product_cat_3_title" value="title">
                        <label for="product_cat_3_title"><?php esc_html_e( 'Page Title', 'martfury' ); ?></label>
                    </p>
                    <p>
                        <input type="checkbox" <?php checked( in_array( 'banners', $product_cat_3_els ) ); ?>
                               name="product_cat_3_els[]" id="product_cat_3_banners" value="banners">
                        <label for="product_cat_3_banners"><?php esc_html_e( 'Banners', 'martfury' ); ?></label>
                    </p>
                    <p>
                        <input type="checkbox" <?php checked( in_array( 'products_carousel', $product_cat_3_els ) ); ?>
                               name="product_cat_3_els[]" id="product_cat_3_carousel" value="products_carousel">
                        <label for="product_cat_3_carousel"><?php esc_html_e( 'Products Carousel', 'martfury' ); ?></label>
                    </p>
                </div>
                <div class="clear"></div>
            </td>
        </tr>
        <tr class="form-field mf-cat-banners-2-group">
            <th scope="row" valign="top"><label><?php esc_html_e( 'Single Banner', 'martfury' ); ?></label></th>
            <td>
                <div id="mf_cat_banners-2" class="mf-cat-banners-2"
                     data-rel="<?php echo esc_url( $this->placeholder_img_src ); ?>">
					<?php
					if ( $banners_2_ids ) {
						$image = wp_get_attachment_thumb_url( $banners_2_ids );
					} else {
						$image = $this->placeholder_img_src;
					}
					?>
                    <img class="mf-cat-image" src="<?php echo esc_url( $image ); ?>" width="60px" height="60px"/>
                    <input type="hidden" id="mf_cat_banners_2_id" name="mf_cat_banners_2_id"
                           value="<?php echo esc_attr( $banners_2_ids ); ?>"/>
                    <button type="button"
                            class="upload_banner_2_button button"><?php esc_html_e( 'Upload/Add image', 'martfury' ); ?></button>
                    <button type="button"
                            class="remove_banner_2_button button"><?php esc_html_e( 'Remove image', 'martfury' ); ?></button>
                    <p class="description"><?php esc_html_e( 'This option is used for the category is level 1 and layout 2.', 'martfury' ); ?></p>
                </div>
                <div class="clear"></div>
            </td>
        </tr>
        <tr class="form-field mf-cat-banners-2-group">
            <th scope="row" valign="top"><label><?php esc_html_e( 'Single Banner Link', 'martfury' ); ?></label></th>
            <td>
                <div id="mf_cat_banners_2_link" class="mf-cat-banners-2-link"
                ">
                <textarea id="mf_cat_banners_2_link" cols="50" rows="1"
                          name="mf_cat_banners_2_link"><?php echo esc_html( $banners_2_link ); ?></textarea>
                <p class="description"><?php esc_html_e( 'This option is used for the category is level 1 and layout 2.', 'martfury' ); ?></p>
                </div>
                <div class="clear"></div>
            </td>
        </tr>
        <tr class="form-field mf-cat-sidebar-group">
            <th scope="row" valign="top"><label><?php esc_html_e( 'Custom Sidebar', 'martfury' ); ?></label></th>
            <td>
                <div class="mf-cat-sidebar">
                    <select id="mf_cat_sidebar" name="mf_cat_sidebar">
						<?php
						foreach ( $this->cat_sidebars as $key => $value ) {
							$selected = ( $key == $cat_sidebar ) ? 'selected=selected' : '';
							?>
                            <option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $value ); ?></option>
							<?php
						}
						?>
                    </select>
                </div>
                <div class="clear"></div>
            </td>
        </tr>
		<?php
	}

	/**
	 * save_category_fields function.
	 *
	 * @param mixed $term_id Term ID being saved
	 * @param mixed $tt_id
	 * @param string $taxonomy
	 */
	function save_category_fields( $term_id, $tt_id = '', $taxonomy = '' ) {

		if ( 'product_cat' === $taxonomy && function_exists( 'update_term_meta' ) ) {
			if ( isset( $_POST['mf_cat_banners_id'] ) ) {
				update_term_meta( $term_id, 'mf_cat_banners_id', $_POST['mf_cat_banners_id'] );
			}

			if ( isset( $_POST['mf_cat_banners_link'] ) ) {
				update_term_meta( $term_id, 'mf_cat_banners_link', $_POST['mf_cat_banners_link'] );
			}

			if ( isset( $_POST['mf_cat_banners_2_id'] ) ) {
				update_term_meta( $term_id, 'mf_cat_banners_2_id', absint( $_POST['mf_cat_banners_2_id'] ) );
			}

			if ( isset( $_POST['mf_cat_banners_2_link'] ) ) {
				update_term_meta( $term_id, 'mf_cat_banners_2_link', $_POST['mf_cat_banners_2_link'] );
			}

			if ( isset( $_POST['mf_cat_layout'] ) ) {
				update_term_meta( $term_id, 'mf_cat_layout', $_POST['mf_cat_layout'] );
			}

			if ( isset( $_POST['mf_cat_sidebar'] ) ) {
				update_term_meta( $term_id, 'mf_cat_sidebar', $_POST['mf_cat_sidebar'] );
			}

			$els_1 = array();
			if ( isset( $_POST['product_cat_1_els'] ) ) {
				$els_1 = $_POST['product_cat_1_els'];
			}
			update_term_meta( $term_id, 'product_cat_1_els', $els_1 );


			$els_2 = array();
			if ( isset( $_POST['product_cat_2_els'] ) ) {
				$els_2 = $_POST['product_cat_2_els'];
			}
			update_term_meta( $term_id, 'product_cat_2_els', $els_2 );

			$els_3 = array();
			if ( isset( $_POST['product_cat_3_els'] ) ) {
				$els_3 = $_POST['product_cat_3_els'];
			}
			update_term_meta( $term_id, 'product_cat_3_els', $els_3 );
		}
	}

}

function martfury_product_cat_init() {
	new Martfury_Product_Cat;
}

add_action( 'init', 'martfury_product_cat_init' );

