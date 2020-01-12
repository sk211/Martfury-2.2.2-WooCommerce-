<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// get form action url
$form_action = martfury_get_page_base_url();

// Keep query string vars intact

$params = '';
foreach ( $_GET as $key => $val ) {
	if ( 'orderby' === $key || 'submit' === $key ) {
		continue;
	}

	if ( is_array( $val ) ) {
		foreach ( $val as $innerVal ) {
			$params .= '&' . $key . '=' . $innerVal;
		}
	} else {
		$params .= '&' . $key . '=' . $val;
	}
}

$order_current = esc_html__('Sort by Default', 'martfury');
$order_html    = '';
foreach ( $catalog_orderby_options as $id => $name ) {

    if( is_wp_error( $id ) ) {
        continue;
    }
	$url       = $form_action . '?orderby=' . esc_attr( $id ) . $params;
	$css_class = '';
	if ( $orderby == $id ) {
		$css_class     = 'active';
		$order_current = $name;
	}

	$order_html .= sprintf(
		'<li><a href="%s" class="%s">%s</a></li>',
		esc_url( $url ),
		esc_attr( $css_class ),
		$name
	);
}
?>
<ul class="woocommerce-ordering">
	<li class="current"><span> <?php echo wp_kses_post($order_current); ?></span>
		<ul>
			<?php echo wp_kses_post($order_html) ?>
		</ul>
	</li>
    <li class="cancel-ordering">
       <a href="#" class="mf-cancel-order"><?php esc_html_e('Cancel', 'martfury'); ?></a>
    </li>
</ul>
