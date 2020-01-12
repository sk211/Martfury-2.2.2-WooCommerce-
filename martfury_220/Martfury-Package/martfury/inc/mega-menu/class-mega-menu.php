<?php

class Martfury_Mega_Menu {
	/**
	 * Martfury_Mega_Menu constructor.
	 */
	public function __construct() {

		$this->load();
		$this->init();

		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'edit_nav_menu_walker' ) );
	}

	/**
	 * Load files
	 */
	private function load() {
		include get_template_directory() . '/inc/mega-menu/class-menu-edit.php';
	}

	/**
	 * Initialize
	 */
	private function init() {
		if ( is_admin() ) {
			new Martfury_Mega_Menu_Edit();
		}
	}

	/**
	 * Change the default nav menu walker
	 *
	 * @return string
	 */
	public function edit_nav_menu_walker() {
		return 'Martfury_Mega_Menu_Walker_Edit';
	}
}

add_action( 'init', 'martfury_mega_menu_init' );
function martfury_mega_menu_init() {
	global $martfury_mega_menu;

	$martfury_mega_menu = new Martfury_Mega_Menu();

}