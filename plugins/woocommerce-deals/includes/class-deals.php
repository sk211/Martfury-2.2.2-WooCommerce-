<?php
/**
 * Plugin's main class
 */
class TAWC_Deals {
	/**
	 * The single instance of the class
	 *
	 * @var TAWC_Deals
	 */
	protected static $instance = null;

	/**
	 * Extra attribute types
	 *
	 * @var array
	 */
	public $types = array();

	/**
	 * Main instance
	 *
	 * @return TAWC_Deals
	 */
	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		require_once dirname( __FILE__ ) . '/deal-functions.php';
		require_once dirname( __FILE__ ) . '/class-frontend.php';
		require_once dirname( __FILE__ ) . '/class-shortcodes.php';
		require_once dirname( __FILE__ ) . '/class-admin.php';
	}

	/**
	 * Initialize hooks
	 */
	public function init_hooks() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( 'TAWC_Deals_Frontend', 'instance' ) );
		add_action( 'init', array( 'TAWC_Deals_Shortcodes', 'init' ) );
	}

	/**
	 * Load plugin text domain
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'tawc-deals', false, dirname( plugin_basename( TAWC_DEALS_PLUGIN_FILE ) ) . '/languages/' );
	}
}