<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class CX_CO_Ads_Settings.
 * 
 * Displays settings page and its stuff.
 * Some of these logic, co-necesstary shaa, but sho get.
 */
class CX_CO_ADS_Settings {

	/**
	 * Logged in user id.
	 * 
	 * @var int
	 */
	protected static $current_user_id = 0;

	/**
	 * The notice message.
	 * 
	 * @var string
	 */
	protected static $notice_msg = '';

	/**
	 * Transient value for success note.
	 *
	 * @var string
	 */
	private static $transient_s_value = 'cx-co-ads-setting-note-s';

	/**
	 * Transient value for failed note.
	 *
	 * @var string
	 */
	private static $transient_f_value = 'cx-co-ads-setting-note-f';

	/**
	 * Transient timeout in seconds
	 * @var int
	 */
	private static $transient_timeout = 5;


    /**
     * Initialiseeee
     */
    public static function init() {
       	self::$current_user_id = get_current_user_id();
		add_action( 'admin_menu', array( __CLASS__, 'menu'), 10 );

		// For displaying notices, can't even remember why I did all this.
		if( isset( $_GET[ 'notice' ] ) ) {
			add_action( 'admin_notices', array( __CLASS__, 'success_submit_notice' ) );
			add_action( 'admin_notices',array( __CLASS__, 'failed_submit_notice' ) );
		}
    }

	/**
	 * Init menu.
	 */
	public static function menu() {
		$parent_slug = 'edit.php';

		// Menu stuff.
		$page_title = __( 'Ads Global Settings', 'cx-co-ads' );
		$menu_title = __('Ads Settings', 'cx-co-ads' );
		$menu_slug = 'cx-co-ads-ad-settings';
		$capability = 'edit_posts';
		$function = array( __CLASS__, 'display_settings' );

		$menu_page_hook_view = add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $parent_slug, $function );
	}

	/**
	* Display settings page.
	*/
   public static function display_settings() {

		if( ! current_user_can( 'edit_posts' ) ) {
			wp_die( __( 'You do not have sufficient permission to access this page.', 'cx-co-ads' ) );
	  	}

		// Delete transient for the other values, incase.
		delete_transient( self::$transient_s_value . self::$current_user_id );
		delete_transient( self::$transient_f_value . self::$current_user_id );
		  
		self::validate();
		?>
		<div class="cx-co-ads-checkbox-holder">
			<form method="post">
			<br>
			<div class="row">
			<div class="col-sm-9">
				<input type="checkbox" value="yes" <?php if( get_option( 'cx_co_ads_enable_ads', false ) ) { echo "checked"; } ?> name="cx_co_ads_enable_ads">
				<strong style="margin-left:7px;">Enable Ads Globally.</strong>
				<?php  wp_nonce_field( 'cx-co-ads-settings', 'cx-co-ads-settings' ); ?>
			</div>
			<div>
				<br>
				<label>Ads ShortCode <small>(Shortcode to put globally on all posts. e.g <code>[asdd]</code></small></label><br>
				<input type="text" value="<?php echo get_option( 'cx_co_ads_ad_shortcode', '' )  ?>" name="cx_co_ads_ad_shortcode"><br><br>
			</div>
			<div class="col-sm-3">
				<button type="submit">Submit</button>
			</div>
			</div>
			</form>
		</div>
		<?php
   }

	/**
	 * Handles validation.
	 *
	 */
	public static function validate() {
		if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['cx-co-ads-settings'] ) && wp_verify_nonce( $_POST['cx-co-ads-settings'], 'cx-co-ads-settings' ) ) {

			$checkbox = filter_input( INPUT_POST, 'cx_co_ads_enable_ads' );
			$shortcode = filter_input( INPUT_POST, 'cx_co_ads_ad_shortcode' );
			
			update_option( 'cx_co_ads_enable_ads', $checkbox, false );
			update_option( 'cx_co_ads_ad_shortcode', $shortcode, false );

			// Delete transient for the failed value.
			delete_transient( self::$transient_f_value . self::$current_user_id );
			set_transient( self::$transient_s_value . self::$current_user_id, 'Ad Settings updated successfully.', self::$transient_timeout );

			// Redirect safely to show new value.
			wp_safe_redirect( add_query_arg( array( 'notice' => true ) ) );
			exit();
		}
	}


	/**
	 * Successful submit message.
	 */
	public static function success_submit_notice() {
		$t_value = self::$transient_s_value . self::$current_user_id;
		$success_f_value = self::$transient_f_value . self::$current_user_id; // Remove the failed transient value.
		$transient = get_transient( $t_value );
		if ( ! empty( $transient ) ) {
		?>
		<div class="notice notice-success is-dismissible">
    		<p><?php echo $transient; ?></p>
		</div>
		<?php
		}
	}

	/**
	 * Faild submit message.
	 */
	public static function failed_submit_notice() {
		$t_value = self::$transient_f_value . self::$current_user_id;
		$success_t_value = self::$transient_s_value . self::$current_user_id; // Remove the success transient value. 
		$transient = get_transient( $t_value );
		if ( ! empty( $transient ) ) {
		?>
		<div class="error notice">
    		<p><?php echo $transient; ?></p>
		</div>
		<?php
		}
	}
}

CX_CO_ADS_Settings::init();
