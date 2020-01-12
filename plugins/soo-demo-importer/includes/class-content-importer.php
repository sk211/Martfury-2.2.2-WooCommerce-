<?php
if ( ! class_exists( 'WP_Importer' ) ) {
	defined( 'WP_LOAD_IMPORTERS' ) || define( 'WP_LOAD_IMPORTERS', true );
	require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
}

// Include required files, if not already present (via separate plugin).
if ( ! class_exists( 'WXR_Importer' ) ) {
	require dirname( __FILE__ ) . '/class-wxr-importer.php';
}

class Soo_Demo_Content_Importer extends WXR_Importer {
	public function __construct( $options = array() ) {
		parent::__construct( $options );

		// Set current user to $mapping variable.
		// Fixes the [WARNING] Could not find the author for ... log warning messages.
		$current_user_obj = wp_get_current_user();
		$this->mapping['user_slug'][$current_user_obj->user_login] = $current_user_obj->ID;
	}
}
