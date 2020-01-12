<?php
/**
 * Register required, recommended plugins for theme
 *
 * @package Martfury
 */

/**
 * Register required plugins
 *
 * @since  1.0
 */
function martfury_register_required_plugins() {
	$plugins = array(
		array(
			'name'               => esc_html__( 'Meta Box', 'martfury' ),
			'slug'               => 'meta-box',
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__( 'Kirki', 'martfury' ),
			'slug'               => 'kirki',
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__( 'WooCommerce', 'martfury' ),
			'slug'               => 'woocommerce',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__( 'Martfury Addons', 'martfury' ),
			'slug'               => 'martfury-addons',
			'source'             => esc_url( 'http://demo2.drfuri.com/plugins/martfury-addons.zip' ),
			'required'           => true,
			'force_activation'   => false,
			'force_deactivation' => false,
			'version'            => '2.1.0',
		),
		array(
			'name'               => esc_html__( 'Variation Swatches for WooCommerce Pro', 'martfury' ),
			'slug'               => 'variation-swatches-for-woocommerce-pro',
			'source'             => esc_url( 'http://demo2.drfuri.com/plugins/variation-swatches-for-woocommerce-pro.zip' ),
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
			'version'            => '1.0.5',
		),
		array(
			'name'               => esc_html__( 'Woocommerce Deals', 'martfury' ),
			'slug'               => 'woocommerce-deals',
			'source'             => esc_url( 'http://demo2.drfuri.com/plugins/woocommerce-deals.zip' ),
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
			'version'            => '1.0.9',
		),
		array(
			'name'               => esc_html__( 'Contact Form 7', 'martfury' ),
			'slug'               => 'contact-form-7',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'     => esc_html__( 'MailChimp for WordPress', 'martfury' ),
			'slug'     => 'mailchimp-for-wp',
			'required' => false,
		),
		array(
			'name'               => esc_html__( 'YITH WooCommerce Wishlist', 'martfury' ),
			'slug'               => 'yith-woocommerce-wishlist',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__( 'YITH WooCommerce Compare', 'martfury' ),
			'slug'               => 'yith-woocommerce-compare',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),
		array(
			'name'               => esc_html__( 'Revolution Slider', 'martfury' ),
			'slug'               => 'revslider',
			'source'             => esc_url( 'http://demo2.drfuri.com/plugins/revslider.zip' ),
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		),

	);

	if ( ! defined( 'ELEMENTOR_VERSION' ) && ! defined( 'WPB_VC_VERSION' ) ) {
		$plugins[] = array(
			'name'               => esc_html__( 'WPBakery Page Builder', 'martfury' ),
			'slug'               => 'js_composer',
			'source'             => esc_url( 'http://demo2.drfuri.com/plugins/js_composer.zip' ),
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		);

		$plugins[] = array(
			'name'               => esc_html__( 'Elementor Page Builder', 'martfury' ),
			'slug'               => 'elementor',
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		);
	} elseif ( ! defined( 'ELEMENTOR_VERSION' ) ) {
		$plugins[] = array(
			'name'               => esc_html__( 'WPBakery Page Builder', 'martfury' ),
			'slug'               => 'js_composer',
			'source'             => esc_url( 'http://demo2.drfuri.com/plugins/js_composer.zip' ),
			'required'           => false,
			'force_activation'   => false,
			'force_deactivation' => false,
		);
	}


	$config = array(
		'domain'       => 'martfury',
		'default_path' => '',
		'menu'         => 'install-required-plugins',
		'has_notices'  => true,
		'is_automatic' => false,
		'message'      => '',
		'strings'      => array(
			'page_title'                      => esc_html__( 'Install Required Plugins', 'martfury' ),
			'menu_title'                      => esc_html__( 'Install Plugins', 'martfury' ),
			'installing'                      => esc_html__( 'Installing Plugin: %s', 'martfury' ),
			'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'martfury' ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'martfury' ),
			'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'martfury' ),
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'martfury' ),
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'martfury' ),
			'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'martfury' ),
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'martfury' ),
			'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'martfury' ),
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'martfury' ),
			'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'martfury' ),
			'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'martfury' ),
			'return'                          => esc_html__( 'Return to Required Plugins Installer', 'martfury' ),
			'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'martfury' ),
			'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'martfury' ),
			'nag_type'                        => 'updated',
		),
	);

	tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'martfury_register_required_plugins' );
