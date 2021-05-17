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
		// Toggle overriding global ads settings.
		register_meta( 'post', 'cx_co_ads_override_global_settings', array(
			'show_in_rest'      => true,
			'type'              => 'boolean',
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

		// Ads shortcode.
		register_meta( 'post', 'cx_co_ads_ad_shortcode', array(
			'show_in_rest'      => true,
			'type'              => 'string',
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
		// Add the is_active plugin logic here. not added for now.
		#######

		$ad_post_globe_override = false;
		$ad_shortcode = false;

		// Is global settings overriden for this post?
		if ( metadata_exists( 'post', $post->ID, 'cx_co_ads_override_global_settings' ) &&
		! empty( trim( get_post_meta( $post->ID, 'cx_co_ads_override_global_settings', true ) ) ) ) {
			
			$ad_post_globe_override = true;
	
			// Check if ad was enabled or disabled for this post.
			if ( metadata_exists( 'post', $post->ID, 'cx_co_ads_enable_ads' ) ) {
				$ad_enabled = trim( get_post_meta( $post->ID, 'cx_co_ads_enable_ads', true ) );

				if ( empty( $ad_enabled ) ) { // It was disabled.
					return $content;
				}
				else { // Get shortcode.
					$ad_shortcode = trim( get_post_meta( $post->ID, 'cx_co_ads_ad_shortcode', true ) );
				}
			}
		}

		if ( ! $ad_post_globe_override ) {
			// Check the global settings option.
			$ad_enabled_global_settings = get_option( 'cx_co_ads_enable_ads', '' );

			if ( empty( $ad_enabled_global_settings ) ) { // Disabled.
				return $content;
			}
			else { // Get global shortcode.
				$ad_shortcode = trim( stripslashes( get_option( 'cx_co_ads_ad_shortcode', '' ) ) );
				
			}
		}

		if ( empty( $ad_shortcode ) ) {
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

		$ads_content = '<div class="cx-co-ads-adspace">' . do_shortcode( $ad_shortcode ). '</div>';
		
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

    /**
 	 * Checks if the other ad plugin is active.
	 *
	 * @param string $plugin The Plugin bootstrap file.
	 * @return bool
	 */
	public static function is_plugin_active( $plugin ) {
		$active_plugins = ( array ) get_option( 'active_plugins', array() );

        if ( is_multisite() ) {
            $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
        }

        return in_array( $plugin, $active_plugins ) || array_key_exists( $plugin, $active_plugins );

	}

}



CX_CO_ADS_Advert::init();
