<?php
/**
 * Plugin Name: CodeXplorer WP Post Ads
 * Plugin URI: https://github.com/Preciousomonze/cx-wp-co-author/tree/ads-only-branch
 * Description: CodeXplorer WP Post Multiple Author with Ads.
 * Author: Precious Omonzejele (CodeXplorer 🤾🏽‍♂️🥞🦜🤡)
 * Author URI: https://codexplorer.ninja
 * Version: 1.0.0
 * Requires at least: 5.0
 * Tested up to: 5.5
 *
 * Text Domain: cx-co-ads
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

// Make sure you update the version values when necessary.
define( 'CX_CO_ADS_PLUGIN_DIR',  plugin_dir_path( __FILE__ ) );
define( 'CX_CO_ADS_PLUGIN_FILE', __FILE__ );

// Include the main class.
if ( ! class_exists( 'CX_CO_ADS' ) ) {
    include_once dirname(__FILE__) . '/includes/class-cx-co-ads.php';
}

/**
 * Return instance of the func.
 * 
 * @return Instanace 
 */
function cx_co_ads() {
    return CX_CO_ADS::instance();
}

add_action( 'plugins_loaded', 'cx_co_ads' );

$GLOBALS['cx_co_ads'] = cx_co_ads();
