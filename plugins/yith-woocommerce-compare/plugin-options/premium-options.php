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
    'premium' => array(
	    'landing' => array(
		    'type' => 'custom_tab',
		    'action' => 'yith_woocompare_premium'
	    )
    )
);

return $options;
