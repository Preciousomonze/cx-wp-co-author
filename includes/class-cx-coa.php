<?php
defined( 'ABSPATH' ) || exit;

/**
 * Main Class to load.
 */
final class CX_COA {

    /**
     * The single instance of the class.
     *
     * @var CX_COA
     * @since 1.0.0
     */
    protected static $_instance = null;

    /**
     * Main instance
     * @return class object
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     */
    public function __construct() {
       self::init();
    }

    /**
     * Initialiseeee
     */
    public static function init() {
        self::define_constants(); //Define the constants.
        self::includes(); // Include relevant files.
	
		// Enqueue required js and css.
		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'enqueue_scripts' ) );

        /**
         * Init.
         *
         * @since 1.0.0
         */
        do_action( 'cx_coa_init' );
    }

    /**
     * Constants define
     */
    private static function define_constants() {
        self::define( 'CX_COA_ABSPATH', dirname( CX_COA_PLUGIN_FILE ) . '/' );
        self::define( 'CX_COA_PLUGIN_FILE', plugin_basename( CX_COA_PLUGIN_FILE ) );
		self::define( 'CX_COA_PLUGIN_VERSION', '1.0.0' );
    }

    /**
     * 
     * @param string $name
     * @param mixed $value
     */
    private static function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Check request
     * @param string $type
     * @return bool
     */
    private static function is_request( $type ) {
        switch ( $type ) {
            case 'admin' :
                return is_admin();
            case 'ajax' :
                return defined( 'DOING_AJAX' );
            case 'cron' :
                return defined( 'DOING_CRON' );
            case 'frontend' :
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
        }
    }

    /**
     * load plugin files
     */
    public static function includes() {

        // Admin side.
        if ( self::is_request( 'admin' ) ) {
            //include_once CX_COA_ABSPATH . 'includes/admin/meta-boxes/class-cx-coa-meta-box-data.php';
        }
    }
	
    /**
     * Load Localisation files.
     *
	 * @since  1.0.0
	 */
	public static function load_plugin_textdomain() {
		load_plugin_textdomain( 'cx-coa', false, plugin_basename( dirname( CX_COA_PLUGIN_FILE ) ) . '/languages' );
    }


	/**
	 * Enqueue needed css.
	 *
	 * @since 1.0.0
	 */
	public static function enqueue_script() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// Enqueue needed css for it.
		wp_enqueue_style( 'cx_coa_style', plugins_url( 'assets/css/style' . $suffix . '.css' , __DIR__ ), array(), CX_COA_PLUGIN_VERSION );
	}

	/*-----------------------------------------------------------------------------------*/
	/* Block Editor Functions */
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Load Script in Block Editor
	 * 
	 * @since 1.0.0
	 * @param string $hook the name of the page we're on in the WP admin.
	 */
	public static function enqueue_assets( $hook ) {

		$current_screen = get_current_screen();

		// Add styles and scripts for block editor.
    	if ( self::is_enabled_for_post_type( $current_screen->post_type ) && post_type_supports( $current_screen->post_type, 'custom-fields' ) ) {
			wp_enqueue_script( 'cx-coa-gutenberg-sidebar', plugins_url( 'js/dist/post-sidebar.js', __FILE__ ), array( 'wp-plugins', 'wp-edit-post', 'wp-i18n', 'wp-element' ), CX_COA_PLUGIN_VERSION );
		}

	}

	/**
	 * Register meta key for block editor.
	 * 
	 * @since 1.0
	 */
	public static function register_meta() {

		register_meta('post', 'cx_coa_coauthors', array(
			'show_in_rest' => true,
			'type' => 'string',
			'single' => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback' => function() { 
				return current_user_can( 'edit_posts' );
			}
		));

	}


}























