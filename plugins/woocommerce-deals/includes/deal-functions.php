<?php
/**
 * WooCommerce deal functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if a product is a deal
 *
 * @param int|object $product
 *
 * @return bool
 */
function tawc_is_deal_product( $product ) {
	$product = is_numeric( $product ) ? wc_get_product( $product ) : $product;

	// It must be a sale product first
	if ( ! $product->is_on_sale() ) {
		return false;
	}

	// Only support product type "simple" and "external"
	if ( ! $product->is_type( 'simple' ) && ! $product->is_type( 'external' ) ) {
		return false;
	}

	$deal_quantity = get_post_meta( $product->get_id(), '_deal_quantity', true );

	if ( $deal_quantity > 0 ) {
		return true;
	}

	return false;
}
