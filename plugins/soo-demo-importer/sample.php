<?php
/**
 * Demo file for using in your theme
 */

add_filter( 'soo_demo_packages', function() {
	return array(
		array(
			'name'       => 'Sober',
			'preview'    => 'http://localhost/demo/images/a1.jpg',
			'content'    => 'http://localhost/demo/demo-content.xml',
			'customizer' => 'http://localhost/demo/customizer.dat',
			'widgets'    => 'http://localhost/demo/widgets.wie',
			'sliders'    => 'http://localhost/demo/sliders.zip',
			'pages'      => array(
				'front_page' => 'Home Page 1',
				'blog'       => 'Blog',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'   => 'primary-menu',
				'secondary' => 'secondary-menu',
				'footer'    => 'footer-menu',
				'topbar'	=> 'topbar-menu',
			),
			'options' => array(
				'shop_catalog_image_size' => array(
					'width' => 300,
					'height' => 300,
					'crop' => 1,
				),
				'shop_single_image_size' => array(
					'width' => 600,
					'height' => 600,
					'crop' => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width' => 180,
					'height' => 180,
					'crop' => 1,
				),
			),
		),
	);
} );
