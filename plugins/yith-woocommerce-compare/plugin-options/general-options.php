<?php
/**
 * Main admin class
 *
 * @author YITH
 * @package YITH Woocommerce Compare
 * @version 1.1.1
 */

if ( ! defined( 'YITH_WOOCOMPARE' ) ) {
	exit;
} // Exit if accessed directly

$options = array(
    'general' => array(
        array(
            'name' => __( 'General Settings', 'yith-woocommerce-compare' ),
            'type' => 'title',
            'desc' => '',
            'id' => 'yith_woocompare_general'
        ),

        array(
            'name'      => __( 'Link or Button', 'yith-woocommerce-compare' ),
            'desc_tip'  => __( 'Choose if you want to use a link or a button for the comepare actions.', 'yith-woocommerce-compare' ),
            'id'        => 'yith_woocompare_is_button',
            'default'   => 'button',
            'type'      => 'select',
            'class'     => 'wc-enhanced-select',
            'options'   => array(
                'link' => __( 'Link', 'yith-woocommerce-compare' ),
                'button' => __( 'Button', 'yith-woocommerce-compare' )
            )
        ),

        array(
            'name'      => __( 'Link/Button text', 'yith-woocommerce-compare' ),
            'desc'      => __( 'Type the text to use for the button or the link of the compare.', 'yith-woocommerce-compare' ),
            'id'        => 'yith_woocompare_button_text',
            'default'   => __( 'Compare', 'yith-woocommerce-compare' ),
            'type'      => 'text'
        ),

        array(
            'name'      => __( 'Show button in single product page', 'yith-woocommerce-compare' ),
            'desc'      => __( 'Say if you want to show the button in the single product page.', 'yith-woocommerce-compare' ),
            'id'        => 'yith_woocompare_compare_button_in_product_page',
            'default'   => 'yes',
            'type'      => 'checkbox'
        ),

        array(
            'name'      => __( 'Show button in products list', 'yith-woocommerce-compare' ),
            'desc'      => __( 'Say if you want to show the button in the products list.', 'yith-woocommerce-compare' ),
            'id'        => 'yith_woocompare_compare_button_in_products_list',
            'default'   => 'no',
            'type'      => 'checkbox'
        ),

        array(
            'name'      => __( 'Open automatically lightbox', 'yith-woocommerce-compare' ),
            'desc'      => __( 'Open link after click into "Compare" button".', 'yith-woocommerce-compare' ),
            'id'        => 'yith_woocompare_auto_open',
            'default'   => 'yes',
            'type'      => 'checkbox'
        ),

        array(
	        'type' => 'sectionend',
	        'id' => 'yith_woocompare_general_end'
        ),

	    array(
		    'name' => __( 'Table Settings', 'yith-woocommerce-compare' ),
		    'type' => 'title',
		    'desc' => '',
		    'id' => 'yith_woocompare_table'
	    ),

	    array(
		    'name'      => __( 'Table title', 'yith-woocommerce-compare' ),
		    'desc'      => __( 'Type the text to use for the table title.', 'yith-woocommerce-compare' ),
		    'id'        => 'yith_woocompare_table_text',
		    'default'   => __( 'Compare products', 'yith-woocommerce-compare' ),
		    'type'      => 'text'
	    ),

	    array(
		    'name'      => __( 'Fields to show', 'yith-woocommerce-compare' ),
		    'desc'      => __( 'Select the fields to show in the comparison table and order them by drag&drop (are included also the woocommerce attributes)', 'yith-woocommerce-compare' ),
		    'id'        => 'yith_woocompare_fields_attrs',
		    'default'   => 'all',
		    'type'      => 'woocompare_attributes'
	    ),

	    array(
		    'name'      => __( 'Repeat "Price" field', 'yith-woocommerce-compare' ),
		    'desc'      => __( 'Repeat the "Price" field at the end of the table', 'yith-woocommerce-compare' ),
		    'id'        => 'yith_woocompare_price_end',
		    'default'   => 'yes',
		    'type'      => 'checkbox'
	    ),

	    array(
		    'name'      => __( 'Repeat "Add to cart" field', 'yith-woocommerce-compare' ),
		    'desc'      => __( 'Repeat the "Add to cart" field at the end of the table', 'yith-woocommerce-compare' ),
		    'id'        => 'yith_woocompare_add_to_cart_end',
		    'default'   => 'no',
		    'type'      => 'checkbox'
	    ),

	    array(
		    'name' => __( 'Image size', 'yith-woocommerce-compare' ),
		    'desc' => __( 'Set the size for the images', 'yith-woocommerce-compare' ),
		    'id'   => 'yith_woocompare_image_size',
		    'type' 		=> 'woocompare_image_width',
		    'default'	=> array(
			    'width' 	=> 220,
			    'height'	=> 154,
			    'crop'		=> 1
		    ),
		    'std'	=> array(
			    'width' 	=> 220,
			    'height'	=> 154,
			    'crop'		=> 1
		    )
	    ),

	    array(
		    'type' => 'sectionend',
		    'id' => 'yith_woocompare_table_end'
	    )
    )
);

return apply_filters( 'yith_woocompare_general_settings', $options );
