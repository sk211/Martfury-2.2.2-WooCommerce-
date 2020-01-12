<?php
/**
 * Hooks for importer
 *
 * @package Martfury
 */


/**
 * Importer the demo content
 *
 * @since  1.0
 *
 */
function martfury_vc_addons_importer() {
	return array(
		array(
			'name'       => 'WPBakery - Auto Parts',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury/auto-parts/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury/auto-parts/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury/auto-parts/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury/auto-parts/widgets.wie',
			'sliders'    => 'http://demo2.drfuri.com/soo-importer/martfury/auto-parts/sliders.zip',
			'tab'        => '0',
			'pages'      => array(
				'front_page' => 'HomePage',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'WPBakery - Full Width',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury/full-width/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury/full-width/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury/full-width/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury/full-width/widgets.wie',
			'sliders'    => 'http://demo2.drfuri.com/soo-importer/martfury/full-width/sliders.zip',
			'tab'        => '0',
			'pages'      => array(
				'front_page' => 'Home Full Width',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'WPBakery - Technology',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury/technology/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury/technology/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury/technology/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury/technology/widgets.wie',
			'sliders'    => 'http://demo2.drfuri.com/soo-importer/martfury/technology/sliders.zip',
			'tab'        => '0',
			'pages'      => array(
				'front_page' => 'Home',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'WPBakery - Organic',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury/organic/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury/organic/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury/organic/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury/organic/widgets.wie',
			'sliders'    => 'http://demo2.drfuri.com/soo-importer/martfury/organic/sliders.zip',
			'tab'        => '0',
			'pages'      => array(
				'front_page' => 'Home',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'WPBakery - Marketplace V1',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v1/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v1/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v1/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v1/widgets.wie',
			'sliders'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v1/sliders.zip',
			'tab'        => '0',
			'pages'      => array(
				'front_page' => 'Home',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'WPBakery - Marketplace V1<br> Without AJAX',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v1/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v1/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v1/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v1/widgets.wie',
			'sliders'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v1/sliders.zip',
			'tab'        => '0',
			'pages'      => array(
				'front_page' => 'Home Without AJAX',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'WPBakery - Marketplace V2',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v2/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v2/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v2/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v2/widgets.wie',
			'sliders'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v2/sliders.zip',
			'tab'        => '0',
			'pages'      => array(
				'front_page' => 'HomePage',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'WPBakery - Marketplace V2<br> Without AJAX',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v2/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v2/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v2/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v2/widgets.wie',
			'sliders'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v2/sliders.zip',
			'tab'        => '0',
			'pages'      => array(
				'front_page' => 'Home Without AJAX',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'WPBakery - Marketplace V3',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v3/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v3/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v3/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v3/widgets.wie',
			'sliders'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v3/sliders.zip',
			'tab'        => '0',
			'pages'      => array(
				'front_page' => 'HomePage',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'WPBakery - Marketplace V3<br> Without AJAX',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v3/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v3/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v3/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v3/widgets.wie',
			'sliders'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v3/sliders.zip',
			'tab'        => '0',
			'pages'      => array(
				'front_page' => 'Home Without AJAX',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'WPBakery - Marketplace V4',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v4/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v4/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v4/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v4/widgets.wie',
			'sliders'    => 'http://demo2.drfuri.com/soo-importer/martfury/marketplace-v4/sliders.zip',
			'tab'        => '0',
			'pages'      => array(
				'front_page' => 'HomePage 4',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'WPBakery - Electronic',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury/electronic/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury/electronic/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury/electronic/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury/electronic/widgets.wie',
			'sliders'    => 'http://demo2.drfuri.com/soo-importer/martfury/electronic/sliders.zip',
			'tab'        => '0',
			'pages'      => array(
				'front_page' => 'HomePage',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'WPBakery - Electronic<br> Without AJAX',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury/electronic/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury/electronic/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury/electronic/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury/electronic/widgets.wie',
			'sliders'    => 'http://demo2.drfuri.com/soo-importer/martfury/electronic/sliders.zip',
			'tab'        => '0',
			'pages'      => array(
				'front_page' => 'Home Without AJAX',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'WPBakery - Furniture',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury/furniture/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury/furniture/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury/furniture/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury/furniture/widgets.wie',
			'sliders'    => 'http://demo2.drfuri.com/soo-importer/martfury/furniture/sliders.zip',
			'tab'        => '0',
			'pages'      => array(
				'front_page' => 'Home',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),

		// Elementor
		array(
			'name'       => 'Elementor - Auto Parts',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/auto-parts/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/auto-parts/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/auto-parts/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/auto-parts/widgets.wie',
			'tab'        => '1',
			'pages'      => array(
				'front_page' => 'HomePage',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'Elementor - Full Width',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/full-width/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/full-width/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/full-width/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/full-width/widgets.wie',
			'tab'        => '1',
			'pages'      => array(
				'front_page' => 'Home Full Width',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'Elementor - Technology',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/technology/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/technology/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/technology/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/technology/widgets.wie',
			'tab'        => '1',
			'pages'      => array(
				'front_page' => 'Home',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'Elementor - Organic',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/organic/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/organic/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/organic/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/organic/widgets.wie',
			'tab'        => '1',
			'pages'      => array(
				'front_page' => 'Home',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'Elementor - Marketplace V1',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/marketplace-v1/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/marketplace-v1/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/marketplace-v1/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/marketplace-v1/widgets.wie',
			'tab'        => '1',
			'pages'      => array(
				'front_page' => 'Home',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'Elementor - Marketplace V2',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/marketplace-v2/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/marketplace-v2/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/marketplace-v2/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/marketplace-v2/widgets.wie',
			'tab'        => '1',
			'pages'      => array(
				'front_page' => 'HomePage',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'Elementor - Marketplace V3',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/marketplace-v3/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/marketplace-v3/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/marketplace-v3/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/marketplace-v3/widgets.wie',
			'tab'        => '1',
			'pages'      => array(
				'front_page' => 'HomePage',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'Elementor - Marketplace V4',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/marketplace-v4/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/marketplace-v4/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/marketplace-v4/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/marketplace-v4/widgets.wie',
			'tab'        => '1',
			'pages'      => array(
				'front_page' => 'HomePage 4',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'Elementor - Electronic',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/electronic/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/electronic/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/electronic/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/electronic/widgets.wie',
			'tab'        => '1',
			'pages'      => array(
				'front_page' => 'HomePage',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
		array(
			'name'       => 'Elementor - Furniture',
			'preview'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/furniture/preview.jpg',
			'content'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/furniture/demo-content.xml',
			'customizer' => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/furniture/customizer.dat',
			'widgets'    => 'http://demo2.drfuri.com/soo-importer/martfury-elementor/furniture/widgets.wie',
			'tab'        => '1',
			'pages'      => array(
				'front_page' => 'Home',
				'blog'       => 'Our Press',
				'shop'       => 'Shop',
				'cart'       => 'Cart',
				'checkout'   => 'Checkout',
				'my_account' => 'My Account',
			),
			'menus'      => array(
				'primary'         => 'primary-menu',
				'shop_department' => 'shop-by-department',
			),
			'options'    => array(
				'shop_catalog_image_size'   => array(
					'width'  => 480,
					'height' => 480,
					'crop'   => 1,
				),
				'shop_single_image_size'    => array(
					'width'  => 600,
					'height' => 600,
					'crop'   => 1,
				),
				'shop_thumbnail_image_size' => array(
					'width'  => 70,
					'height' => 70,
					'crop'   => 1,
				),
			),
		),
	);
}

add_filter( 'soo_demo_packages', 'martfury_vc_addons_importer', 20 );


/**
 * Prepare product attributes before import demo content
 *
 * @param $file
 */
function martfury_addons_import_product_attributes( $file ) {
	global $wpdb;

	if ( ! class_exists( 'WXR_Parser' ) ) {
		require_once WP_PLUGIN_DIR . '/soo-demo-importer/includes/parsers.php';
	}

	$parser      = new WXR_Parser();
	$import_data = $parser->parse( $file );

	if ( isset( $import_data['posts'] ) ) {
		$posts = $import_data['posts'];

		if ( $posts && sizeof( $posts ) > 0 ) {
			foreach ( $posts as $post ) {
				if ( 'product' === $post['post_type'] ) {
					if ( ! empty( $post['terms'] ) ) {
						foreach ( $post['terms'] as $term ) {
							if ( strstr( $term['domain'], 'pa_' ) ) {
								if ( ! taxonomy_exists( $term['domain'] ) ) {
									$attribute_name = wc_sanitize_taxonomy_name( str_replace( 'pa_', '', $term['domain'] ) );

									// Create the taxonomy
									if ( ! in_array( $attribute_name, wc_get_attribute_taxonomies() ) ) {
										$attribute = array(
											'attribute_label'   => $attribute_name,
											'attribute_name'    => $attribute_name,
											'attribute_type'    => 'select',
											'attribute_orderby' => 'menu_order',
											'attribute_public'  => 0,
										);
										$wpdb->insert( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute );
										delete_transient( 'wc_attribute_taxonomies' );
									}

									// Register the taxonomy now so that the import works!
									register_taxonomy(
										$term['domain'],
										apply_filters( 'woocommerce_taxonomy_objects_' . $term['domain'], array( 'product' ) ),
										apply_filters(
											'woocommerce_taxonomy_args_' . $term['domain'], array(
												'hierarchical' => true,
												'show_ui'      => false,
												'query_var'    => true,
												'rewrite'      => false,
											)
										)
									);
								}
							}
						}
					}
				}
			}
		}
	}
}

add_action( 'soodi_before_import_content', 'martfury_addons_import_product_attributes' );
