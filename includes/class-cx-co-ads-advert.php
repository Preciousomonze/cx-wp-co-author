<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class CX_CO_Ads.
 */
class CX_CO_ADS_Advert {

    /**
     * Initialiseeee
     */
    public static function init() {

        // Register meta key in REST.
        add_action( 'init', array( __CLASS__, 'register_meta' ) );

		// Add ad to paragraph.
		add_filter( 'the_content', array( __CLASS__, 'place_ad_content' ) ); 

    }

	/**
	 * Register meta keys for block editor.
	 * 
	 * @since 1.0.0
	 */
	public static function register_meta() {
        // Ads.
		register_meta( 'post', 'cx_co_ads_ad_link', array(
			'show_in_rest'      => true,
			'type'              => 'string',
			'single'            => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => function() { 
				return current_user_can( 'edit_posts' );
			}
		));

		// Toggle ads display per post.
		register_meta( 'post', 'cx_co_ads_enable_ads', array(
			'show_in_rest'      => true,
			'type'              => 'boolean',
			'single'            => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => function() { 
				return current_user_can( 'edit_posts' );
			}
		));
	
    }

    /**
     * Place ad content in Post where necessary.
     * 
     * @param string $content
	 * @return string
     */
    public static function place_ad_content( $content ) {
		global $post;

		// Post type we're capturing.
   	    $post_types = array( 'post', 'page' );
        
		// Only continue when $post is valid and its a supported post type.
       	if ( ! $post || ! is_single() || ! in_array( $post->post_type, $post_types, true ) ) {
			return $content;
		}

		$ad_post_globe_enabled = false;
		// Check if ad was enabled or disabled for this post.
		if ( metadata_exists( 'post', $post->ID, 'cx_co_ads_enable_ads' ) ) {
			$ad_enabled = trim( get_post_meta( $post->ID, 'cx_co_ads_enable_ads', true ) );

			if ( empty( $ad_enabled ) ) { // It was disabled.
				return $content;
			}
			else {
				$ad_post_globe_enabled = true;
			}
		}

		if ( ! $ad_post_globe_enabled ) {
			// Check the global settings option.
			$ad_enabled_global_settings = get_option( 'cx_co_ads_enable_ads', '' );

			if ( empty( $ad_enabled_global_settings ) ) { // Disabled.
				return $content;
			}
		}

		$ad_link = trim( get_post_meta( $post->ID, 'cx_co_ads_ad_link', true ) );

		if ( empty( $ad_link ) ) {
			return $content;
		}

		$splitter   = '</p>';
		$paragraphs = explode( $splitter, $content );
		$p_count    = count( $paragraphs ) - 1;

		// No Paragraph?
		if ( $p_count < 1 ) {
			return $content;
		}

        // After what paragraph to add ad content. Note. values in quote are "x to the last paragraph".
		$ad_sections   = array( 4, 12 );
		$section_count = 0; // Use this to know the next paragraph to add ad content :).
		$paragraph_min = 12; // If our paragraph is less than these, don't show ads.

		// Is our paragraph less than the min? then don't show ads.
		if ( $p_count < $paragraph_min ) {
			return $content;
		}

		$ads_content = '<a class="cx-co-ads-adspace" href="' . esc_attr( $ad_link ) . '" style="margin:50px 0;display:block;">' . $ad_link . '</a>';
		
		for ( $i = 0; $i < count( $paragraphs ); $i++ ) {
			$p_num              = $i + 1;
			$current_ad_section = $ad_sections[ $section_count ];

			// Is the current paragraph equal to the current section we need to add ads?
			if ( $p_num  === $current_ad_section  ) {
				$paragraphs[ $i ] .= $ads_content;
				++$section_count;
			}
		}

		// Don't forget "X to the last".
		foreach ( $ad_sections as $section ) {
			if ( ! is_int( $section ) ) {
				// Not an int, so it's "X to the last" scene.
				$x_to_last = $p_count - $section;

				$paragraphs[ $x_to_last ] .= ( strpos( $paragraphs[ $x_to_last ], $ads_content ) !== false ? '' : $ads_content );
			}
		}
		
		$content = implode( $splitter, $paragraphs );
		return $content;
    }
}

CX_CO_ADS_Advert::init();
