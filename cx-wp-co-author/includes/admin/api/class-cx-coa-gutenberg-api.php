<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class CX_COA_Gutenberg_Api
 *
 * WP REST API For gutenberg blocks.
 *
 */
class CX_COA_Gutenberg_Api {

	private static $version;
	private static $namespace;

	/**
	 * Run all of the plugin functions.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
        self::$version   = '1';
		self::$namespace = 'cx-coa/co-authors/v' . self::$version;
	
		add_action( 'rest_api_init', array( __CLASS__, 'request_authors' ) );
	}

	/**
	 * Register Author request endpoint.
	 */
	public static function request_authors() {

		// Council
		register_rest_route(
			self::$namespace,
			'/request-authors?:/(?P<post_id>\d+))?',
			array(
				'methods'             => 'GET',
                'callback'            => array( __CLASS__, 'get_co_authors' ),
                'args'                => [
                    'post_id'
                ],
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	/**
	 * Get the co authors data.
	 *
	 * @return $co_authors JSON feed of returned objects
	 */
	public static function get_co_authors() {
		$post_id = sanitize_text_field( $_GET['post_id'] );

        $co_authors = get_post_meta( $post_id, 'cx_coa_co_authors', true );

        if ( empty( $coauthors ) ) {
			return array();
		}

		$co_authors_details = array();
		$co_authors_data = explode( ',', $coauthors );

		foreach ( $co_authors_data as $co_author ) {
            $user = get_user_by( 'id', $co_author );
			if ( $user ) {
                $coauthors_details[] = array(
                    'value' => $co_author,
                    'label' => $user->display_name,
                );
			}

		}

		return $coauthors_details;
	}
}

CX_COA_Gutenberg_Api::init();