<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs   = apply_filters( 'woocommerce_product_tabs', array() );
$layout = apply_filters( 'martfury_product_tabs_layout', martfury_get_product_layout() );

$dropdown     = intval( martfury_is_mobile() ) ? '<span class="tab-toggle"><i class="icon-chevron-down"></i></span>' : '';
$collapse_tab = martfury_get_option( 'collapse_tab' );
$css          = intval( martfury_is_mobile() ) && intval( $collapse_tab ) ? 'collapse-tab-enable' : '';
if ( ! empty( $tabs ) ) :
	if ( $layout != '3' ) {
		?>

		<div class="woocommerce-tabs wc-tabs-wrapper">
			<ul class="tabs wc-tabs" role="tablist">
				<?php foreach ( $tabs as $key => $tab ) : ?>
					<li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>"
						role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
						<a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab"
					 id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel"
					 aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
					<?php if ( isset( $tab['callback'] ) ) {
						call_user_func( $tab['callback'], $key, $tab );
					} ?>
				</div>
			<?php endforeach; ?>
			<?php do_action( 'woocommerce_product_after_tabs' ); ?>
		</div>


	<?php } else {
		?>
		<div class="mf-woo-tabs wc-tabs-wrapper <?php echo esc_attr( $css ) ?>">
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<div class="mf-Tabs-panel mf-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content"
					 id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel"
					 aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
					<h3 class="tab-title"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?><?php echo '' . $dropdown ?></h3>
					<?php if ( isset( $tab['callback'] ) ) {
						echo '<div class="tab-content-wrapper">';
						call_user_func( $tab['callback'], $key, $tab );
						echo '</div>';
					} ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

endif; ?>

